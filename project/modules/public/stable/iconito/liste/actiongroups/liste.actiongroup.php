<?php

_classInclude('liste|listeservice');

/**
 * Actiongroup du module Liste
 *
 * @package Iconito
 * @subpackage	Liste
 */
class ActionGroupListe extends CopixActionGroup
{
    public function beforeAction ()
    {
        _currentUser()->assertCredential ('group:[current_user]');

    }

   /**
   * Accueil d'une liste
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2005/11/23
     * @param integer $id Id de la liste
   */
   public function getListe ()
   {
        $kernel_service = & CopixClassesFactory::Create ('kernel|kernel');

        $id = _request("id") ? _request("id") : NULL;
        $errors = array();

         $dao = CopixDAOFactory::create("liste|liste_listes");

        $liste = $dao->get($id);

        if (!$liste)
            $errors[] = CopixI18N::get ('liste|liste.error.noListe');
        else {
            $mondroit = $kernel_service->getLevel( "MOD_LISTE", $id );
            if (!ListeService::canMakeInListe('VIEW_HOME',$mondroit))
                $errors[] = CopixI18N::get ('kernel|kernel.error.noRights');
            else {
                $parent = $kernel_service->getModParentInfo( "MOD_LISTE", $id);
                $liste->parent = $parent;
            }
        }
        //print_r($liste);

        if ($errors) {
            return CopixActionGroup::process ('genericTools|Messages::getError', array ('message'=>implode('<br/>',$errors), 'back'=>CopixUrl::get('liste||')));
        } else {

         CopixHTMLHeader::addCSSLink (_resource("styles/module_liste.css"));

            $tpl = new CopixTpl ();
            $tpl->assign ('TITLE_PAGE', $liste->parent["nom"]);

      $menu = array();
      $menu[] = array('txt' => CopixI18N::get('liste|liste.homeLinkMsgSend'), 'url' => CopixUrl::get ('minimail||getListSend'));
          $tpl->assign ('MENU', $menu);

            $tplListe = new CopixTpl ();
            $tplListe->assign ('liste', $liste);
      $tplListe->assign ('canWrite', ListeService::canMakeInListe('WRITE',$mondroit));

            $result = $tplListe->fetch('getliste.tpl');
            $tpl->assign ('MAIN', $result);

            return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
        }
    }


   /**
   * Formulaire d'écriture d'un message
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2005/11/23
     * @see doMessageForm()
     * @param integer $liste Id de la liste sur laquelle on écrit
     * @param string $title Titre du message
     * @param string $message Corps du message
     * @param integer $preview (option) Si 1, affichera la preview du message soumis, si 0 validera le formulaire
   * @param array $errors Erreurs déjà rencontrées
   */
     function processGetMessageForm ()
     {
        $kernel_service = & CopixClassesFactory::Create ('kernel|kernel');

        $criticErrors = array();
        $liste = _request("liste") ? _request("liste") : NULL;
        $titre = _request("titre") ? _request("titre") : NULL;
        $message = _request("message") ? _request("message") : NULL;
        $preview = _request("preview") ? _request("preview") : 0;
        $errors = _request("errors") ? _request("errors") : array();

        if ($liste) {		// Nouveau message dans une liste
            $dao_listes = CopixDAOFactory::create("liste|liste_listes");
            $rListe = $dao_listes->get($liste);
            if (!$rListe)
                $criticErrors[] = CopixI18N::get ('liste|liste.error.noListe');
            else {
                $kernel_service = & CopixClassesFactory::Create ('kernel|kernel');
                $mondroit = $kernel_service->getLevel( "MOD_LISTE", $liste);
                if (!ListeService::canMakeInListe('WRITE',$mondroit))
                    $criticErrors[] = CopixI18N::get ('kernel|kernel.error.noRights');
            }
        } else {
            $criticErrors[] = CopixI18N::get ('liste|liste.error.impossible');
        }

        if ($criticErrors) {
            return CopixActionGroup::process ('genericTools|Messages::getError', array ('message'=>implode('<br/>',$criticErrors), 'back'=>CopixUrl::get('liste||')));
        } else {


            // $contexte = $dao->get($id);
            // rustine PNL pour afficher le nom du groupe
            $id = _request("liste") ? _request("liste") : NULL;
            $parent = $kernel_service->getModParentInfo( "MOD_LISTE", $id);

            $tpl = new CopixTpl ();
            $title_page = $parent["nom"];
            $tpl->assign ('TITLE_PAGE', $title_page);

            $format = CopixConfig::get ('minimail|default_format');

            $tplForm = new CopixTpl ();
            $tplForm->assign ('liste', $liste);
            $tplForm->assign ('titre', $titre);
            $tplForm->assign ('message', $message);
            $tplForm->assign ('preview', $preview);
            $tplForm->assign ('errors', $errors);
            //$tplForm->assign ('wikibuttons', CopixZone::process ('kernel|wikibuttons', array('field'=>'message', 'format'=>CopixConfig::get ('minimail|default_format'), 'object'=>array('type'=>'MOD_LISTE', 'id'=>$id))));
            $tplForm->assign ('message_edition', CopixZone::process ('kernel|edition', array('field'=>'message', 'format'=>$format, 'content'=>$message, 'height'=>200, 'object'=>array('type'=>'MOD_LISTE', 'id'=>$id))));


            $tplForm->assign ('format', $format);
            $result = $tplForm->fetch('getmessageform.tpl');
            $tpl->assign ('MAIN', $result);

            return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
        }
    }


   /**
   * Soumission du formulaire d'écriture d'un message sur une liste
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2005/11/23
     * @see getMessageForm()
     * @param integer $liste Id de la liste sur laquelle on écrit
     * @param string $title Titre du minimail
     * @param string $message Corps du minimail
     * @param string $go Forme de soumission : preview (prévisualiser) ou send (enregistrer)
   */
    public function doMessageForm ()
    {
        $errors = $criticErrors = array();
        $liste = _request("liste") ? _request("liste") : NULL;
        $titre = _request("titre") ? _request("titre") : NULL;
        $message = _request("message") ? _request("message") : NULL;
        $go = _request("go") ? _request("go") : 'preview';

        if ($liste) {		// Nouveau message
            $dao_listes = CopixDAOFactory::create("liste|liste_listes");
            $rListe = $dao_listes->get($liste);
            if (!$rListe)
                $criticErrors[] = CopixI18N::get ('liste|liste.error.noListe');
            else {
                $kernel_service = & CopixClassesFactory::Create ('kernel|kernel');
                $mondroit = $kernel_service->getLevel( "MOD_LISTE", $liste);
                if (!ListeService::canMakeInListe('WRITE',$mondroit))
                    $criticErrors[] = CopixI18N::get ('kernel|kernel.error.noRights');
            }
        } else {
            $criticErrors[] = CopixI18N::get ('liste|liste.error.impossible');
        }

        if (!$titre)	$errors[] = CopixI18N::get ('liste|liste.error.typeTitle');
        if (!$message)	$errors[] = CopixI18N::get ('liste|liste.error.typeMessage');

        if ($criticErrors) {
            return CopixActionGroup::process ('genericTools|Messages::getError', array ('message'=>implode('<br/>',$criticErrors), 'back'=>CopixUrl::get('liste||')));
        } else {

            $auteur = _currentUser ()->getId();

            if (!$errors && $go=='save') {	// Insertion
                $service = CopixClassesFactory::create("ListeService");
                $add = $service->addListeMessage ($liste, $auteur, $titre, $message);
                if (!$add)
                    $errors[] = CopixI18N::get ('liste|liste.error.sendMessage');
                $urlReturn = CopixUrl::get ('liste||getListe', array("id"=>$liste));
                if (!$errors)
                    return new CopixActionReturn (COPIX_AR_REDIRECT, $urlReturn);
            }

            return CopixActionGroup::process ('liste|liste::getMessageForm', array ('liste'=>$liste, 'titre'=>$titre, 'message'=>$message, 'errors'=>$errors, 'preview'=>(($go=='save')?0:1)));

        }
    }

}

