<?php
/**
 * Sso - ActionGroup
 *
 * @package	Iconito
 * @subpackage  Sso
 * @version     $Id: sso.actiongroup.php,v 1.6 2007-12-21 17:35:39 fmossmann Exp $
 * @author      Frederic Mossmann <fmossmann@cap-tic.fr>
 * @copyright   2006 CAP-TIC
 * @link        http://www.cap-tic.fr
 */

class ActionGroupSso extends CopixActionGroup
{
    public function beforeAction ()
    {
        //_currentUser()->assertCredential ('group:[current_user]');

    }

   public function getSso ()
   {
      $id = $this->getRequest('id', null);

           if (!$id) {
            // Récupération des infos de l'utilisateur.
            $userInfo = Kernel::getUserInfo();
            // Création des modules inexistants.
            Kernel::createMissingModules( $userInfo["type"], $userInfo["id"] );
            // Liste des modules activés.
            $modsList = Kernel::getModEnabled( $userInfo["type"], $userInfo["id"] );
            foreach( $modsList AS $modInfo ) {
                if( $modInfo->module_type == "MOD_SSO" && $modInfo->module_id) {
                    $urlReturn = CopixUrl::get ('sso||getSso', array('id'=>$modInfo->module_id));
                    return new CopixActionReturn (COPIX_AR_REDIRECT, $urlReturn);
                }
            }
        }


    if (!$id)
            return CopixActionGroup::process ('genericTools|Messages::getError', array ('message'=>'Problème', 'back'=>CopixUrl::get('||')));


        $tpl = new CopixTpl ();

        $tpl->assign ('TITLE_PAGE', CopixI18N::get ('sso.title.servext'));



        $dao = CopixDAOFactory::create("sso|sso_auth");
        // $all = $dao->findAll();
        $all = $dao->findBySso($id);

        $tpl->assign ('MAIN', CopixZone::process ('sso|SsoAuthList', array('list'=>$all, 'id'=>_request("id"))) );


        return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);

    }

    public function doSso ()
    {
    $criticErrors = array();

        $dao = CopixDAOFactory::create("sso|sso_auth");
        $sso = $dao->get( _request("id") );

    if (!$sso)
      $criticErrors[] = CopixI18N::get ('sso.error.nosso');
    $mondroit = Kernel::getLevel( "MOD_SSO", $sso->sso_auth_id_sso);
    if ($mondroit < PROFILE_CCV_ADMIN)
      $criticErrors[] = CopixI18N::get ('sso.error.norights');

        $url = $sso->sso_auth_url.'?mode=challenge';
        $url.= '&login_ico='.urlencode($sso->sso_auth_login_local);
        $url.= '&login_distant='.urlencode($sso->sso_auth_login_distant);
        $file = fopen( $url, 'r' );
        if ($file && !$criticErrors) {
            $challenge = '';
            while (!feof($file)) {
                $challenge .= fread($file, 1024);
            }
            fclose ($file);
            if ($challenge!='') {
                $challenge_crypt = md5($challenge.$sso->sso_auth_secret_key);
                $url = $sso->sso_auth_url.'?mode=login';
                $url.= '&login_ico='.urlencode($sso->sso_auth_login_local);
                $url.= '&login_distant='.urlencode($sso->sso_auth_login_distant);
                $url.= '&key='.urlencode($challenge_crypt);
                return new CopixActionReturn (COPIX_AR_REDIRECT, $url );
            } else {
        $criticErrors[] = CopixI18N::get ('sso.error.pbacces');
      }
        //	Kernel::MyDebug ($challenge);

        }

    if ($criticErrors)
            return CopixActionGroup::process ('genericTools|Messages::getError', array ('message'=>implode('<br/>',$criticErrors), 'back'=>CopixUrl::get('sso||')));

        //return new CopixActionReturn (COPIX_AR_REDIRECT, $url );

    }

    /**
     * Affichage du formulaire d'ajout d'un service SSO
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2006/11/14
     * @param integer $sso Id de la collection SSO
     * @param array $errors Tableau d'erreurs
     * @todo vérifier les droits
     */
  public function processGetServiceNewForm ()
  {
        $id = $this->getRequest('id', null);
        $errors = $this->getRequest('errors', null);
        $url = $this->getRequest('url', CopixConfig::get ('sso|sso_gael_url'));
        $type = $this->getRequest('type', null);

    $criticErrors = array();

    if ($criticErrors)
            return CopixActionGroup::process ('genericTools|Messages::getError', array ('message'=>implode('<br/>',$criticErrors), 'back'=>CopixUrl::get('sso||')));

    $tpl = new CopixTpl ();
        $tpl->assign ('TITLE_PAGE', CopixI18N::get ('sso.string.newsso'));

        $tplForm = new CopixTpl ();
        $tplForm->assign ('id', $id);
        $tplForm->assign ('message', $message);
        $tplForm->assign ('url', $url);
        $tplForm->assign ('type', $type);
        $tplForm->assign ("errors", $errors);

        $result = $tplForm->fetch('getservicenewform.tpl');
        $tpl->assign ('MAIN', $result);

        return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);

  }

   /**
     * Traitement du formulaire d'ajout d'un service SSO
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2006/11/14
     * @param integer $sso Id de la collection SSO
     * @param string $url URL à interroger
     * @param string $type Type
   * @todo vérifier les droits
   */
  public function doServiceNewForm ()
  {
        $id = $this->getRequest('id', null);
        $url = $this->getRequest('url', null);
        $type = $this->getRequest('type', null);

    $criticErrors = $errors = array();

    if ($criticErrors)
            return CopixActionGroup::process ('genericTools|Messages::getError', array ('message'=>implode('<br/>',$criticErrors), 'back'=>CopixUrl::get('sso||')));

        if (!$url)	$errors[] = CopixI18N::get ('sso.error.newsso_url');
        if (!$type)	$errors[] = CopixI18N::get ('sso.error.newsso_type');

//  print_r(htmlentities(CopixUrl::get('sso||doActivateService', array('id'=>$newSso->sso_auth_id))));
    if (!$errors) { // Ok, on traite

      $dao = CopixDAOFactory::create("sso|sso_auth");

            $newSso = CopixDAOFactory::createRecord("sso_auth");
            $newSso->sso_auth_id_sso = $id;
            $newSso->sso_auth_login_local = _currentUser()->getLogin();
            $newSso->sso_auth_type = $type;
            $newSso->sso_auth_url = $url;
            $newSso->sso_auth_date_crea = date("Y-m-d H:i:s");
            $newSso->sso_auth_login_distant = '';
            $newSso->sso_auth_secret_key = '';
            $newSso->sso_auth_date_valid = '';

            $dao->insert ($newSso);

            if ($newSso->sso_auth_id !== NULL) {
        $go = $url.'?mode=register'."&login_ico="._currentUser()->getLogin()."&url=".urlencode(CopixUrl::get().CopixUrl::get('sso||doActivateService', array('id'=>$newSso->sso_auth_id)));
//        die ($go);
        return new CopixActionReturn (COPIX_AR_REDIRECT, $go );
            }


    }

        return CopixActionGroup::process ('sso|sso::getServiceNewForm', array ('id'=>$id, 'errors'=>$errors, 'url'=>$url, 'type'=>$type));


  }

   /**
     * Activation d'un service SSO. Adresse "cachée" utilisée par le site distant après vérification du compte à jumeler
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2006/11/14
     * @param integer $id Id du SSO à activer
     * @param string $login_distant Login distant
     * @param string $key Clé utilisée pour communiquer entre les 2 sites
   * @todo vérifier les droits et tester les erreurs. Afficher un message OK quand l'activation s'est bien passée ?
   */

  public function doActivateService ()
  {
        $id = $this->getRequest('id', null);
        $login_distant = $this->getRequest('login_distant', null);
        $secret_key = $this->getRequest('cle', null);
        $url_sso = $this->getRequest('url_sso', null);

    $dao = CopixDAOFactory::create("sso|sso_auth");
    $obj = $dao->get($id);

    $criticErrors = $errors = array();

    if (!$obj)
      $criticErrors[] = CopixI18N::get ('sso.error.nosso');
    $mondroit = Kernel::getLevel( "MOD_SSO", $obj->sso_auth_id_sso);
    if ($mondroit < PROFILE_CCV_ADMIN)
      $criticErrors[] = CopixI18N::get ('sso.error.norights');

    if ($criticErrors)
            return CopixActionGroup::process ('genericTools|Messages::getError', array ('message'=>implode('<br/>',$criticErrors), 'back'=>CopixUrl::get('sso||')));

    $obj->sso_auth_url = $url_sso;
    $obj->sso_auth_login_distant = $login_distant;
    $obj->sso_auth_secret_key = $secret_key;
    $obj->sso_auth_date_valid = date("Y-m-d H:i:s");
    $dao->update ($obj);

    return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get('sso||'));

  }

   /**
     * Suppression d'un service SSO.
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2006/11/14
     * @param integer $id Id du SSO à supprimer
   */

  public function doDeleteService ()
  {
        $id = $this->getRequest('id', null);

    $dao = CopixDAOFactory::create("sso|sso_auth");
    $obj = $dao->get($id);

    $criticErrors = $errors = array();

    if (!$obj)
      $criticErrors[] = CopixI18N::get ('sso.error.nosso');
    $mondroit = Kernel::getLevel( "MOD_SSO", $obj->sso_auth_id_sso);
    if ($mondroit < PROFILE_CCV_ADMIN)
      $criticErrors[] = CopixI18N::get ('sso.error.norights');

    if ($criticErrors)
            return CopixActionGroup::process ('genericTools|Messages::getError', array ('message'=>implode('<br/>',$criticErrors), 'back'=>CopixUrl::get('sso||')));

    $dao->delete ($id);

    return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get('sso||'));

  }

}

