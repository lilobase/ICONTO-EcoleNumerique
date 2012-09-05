<?php
/**
 * Actiongroup du module Contact
 *
 * @package	Iconito
 * @subpackage Contact
 */


class ActionGroupDefault extends enicActionGroup
{
  public function beforeAction ()
  {
    _currentUser()->assertCredential ('group:[current_user]');
  }

  /**
  * Entree dans le module
  *
  * @author Christophe Beyer <cbeyer@cap-tic.fr>
  * @since 2010/08/26
  * @param integer $id Id du module
  */
  public function processGo ()
  {
    $ppo = new CopixPPO();

    $iId = CopixRequest::getInt('id');
    $iSubmit = CopixRequest::getInt('submit');

    $this->addCss('styles/module_contact.css');
    $this->js->button('.button');

    $getLevel = Kernel::getLevel('MOD_CONTACT', $iId);

    //echo "getLevel=$getLevel";

    $rContact = _dao('contact|contacts')->get($iId);
    //print_r($rContact);

    if (!$rContact)
      return $this->error ('contact|contact.errors.param', true, CopixUrl::get ('||'));

    $ppo->types = _dao('contact|contacts_types')->findByContact($iId);

    $user = _currentUser ();

    $possible = (CopixConfig::get('|mailEnabled')==1 && CopixConfig::get('|mailSmtpHost'));

    if ($iSubmit) { // Submit du formulaire

      $record = _record('contact|contacts_messages');
      $record->contact = $iId;
      $record->to_email = $rContact->email;
      $record->date = date("Y-m-d H:i:s");
      $record->from_nom = _request('from_nom');
      $record->from_email = _request('from_email');
      $record->from_login = $user->getLogin();
      $record->from_user_id = $user->getId();
      $record->type = _request('type');
      $record->message = _request('message');
      $record->ip = $_SERVER["REMOTE_ADDR"];

      $check = _dao('contact|contacts_messages')->check($record);
      $ok = false;

      if (!is_array($check)) { // OK, pas d'erreurs
        _dao('contact|contacts_messages')->insert($record);


        if ($record->id) { // Enregistrement bien passe

          $type_nom = '';
          foreach ($ppo->types as $type) {
            if ($type->id == $record->type)
              $type_nom = $type->nom;
          }

          if ($possible) {
            $to = $record->to_email;
                    $subject = $type_nom;

            $message = CopixI18N::get('contact.mail.date')." : ".date("d/m/Y H:i");
            $message .= "\n".CopixI18N::get('contact.mail.ip')." : ".$record->ip;
            $message .= "\n";
            $message .= "\n".CopixI18N::get('contact.mail.nom')." : ".$record->from_nom;
            if ($record->from_login)
              $message .= ' ('.$record->from_login.')';
            $message .= "\n".CopixI18N::get('contact.mail.email')." : ".$record->from_email;
            $message .= "\n";
            $message .= "\n".CopixI18N::get('contact.mail.type')." : ".$type_nom;
            $message .= "\n".CopixI18N::get('contact.mail.message')." : ".$record->message;
            $message .= "\n\n-- \n".CopixUrl::get ();

                    $from = $record->from_email;
                    $fromName = $record->from_nom;
            if ($record->from_login)
              $fromName .= ' ('.$record->from_login.')';
                      $cc = $cci = '';
                    $monMail = new CopixTextEMail ($to, $cc, $cci, utf8_decode($subject), utf8_decode($message));
                    $send = $monMail->send (utf8_decode($from), utf8_decode($fromName));
            if ($send)
              $ok = true;
            else
              $check = array(CopixI18N::get ('contact|contact.errors.mailSend'));
          } else {
            $check = array(CopixI18N::get ('contact|contact.errors.mailDisabledAfter'));
          }

        } else {
          $check = array(CopixI18N::get ('contact|contact.errors.save'));
        }
      }

      $ppo->rForm = $record;
      $ppo->errors = $check;
      $ppo->ok = $ok;

    } else {
      $ppo->rForm = _record('contact|contacts_messages');
      foreach ($ppo->types as $type) {
        if ($type->is_default)
          $ppo->rForm->type = $type->id;
      }
      $ppo->rForm->from_nom = trim($user->getExtra('prenom').' '.$user->getExtra('nom'));
      $prefs = Prefs::getPrefs ($user->getId());
      if (isset($prefs['prefs']['alerte_mail_email']))
        $ppo->rForm->from_email = $prefs['prefs']['alerte_mail_email'];

      if (!$possible)
        $ppo->errors = array(CopixI18N::get ('contact|contact.errors.mailDisabled'));

    }

    $ppo->TITLE_PAGE = $rContact->titre;

    return _arPPO($ppo, 'contact.tpl');

  }




}



