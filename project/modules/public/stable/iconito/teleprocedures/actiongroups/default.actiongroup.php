<?php
/**
 * Actiongroup du module Teleprocedures
 *
 * @package	Iconito
 * @subpackage teleprocedures
 */

_classInclude('teleprocedures|teleproceduresservice');

class ActionGroupDefault extends EnicActionGroup
{
    public function beforeAction ()
    {
        _currentUser()->assertCredential ('group:[current_user]');

    }


    public function go ()
    {
        $id = $this->getRequest ('id', null);
        if( ereg( 'ECOLE_([0-9]+)', $id, $regs ) ) {
            if ($rEcole = Kernel::getNodeInfo ('BU_ECOLE', $regs[1], false)) {
                $daoModEnabled = CopixDAOFactory::create("kernel|kernel_mod_enabled");
                $modules = $daoModEnabled->getByNode('BU_VILLE',$rEcole['ALL']->eco_id_ville);
                foreach ($modules as $module) {
                    if ($module->module_type == 'MOD_TELEPROCEDURES')
                        return CopixActionGroup::process ('teleprocedures|default::listTeleprocedures', array ('id'=>$module->module_id));
                }
        return CopixActionGroup::process ('genericTools|Messages::getError', array ('message'=>CopixI18N::get ('teleprocedures|teleprocedures.error.noModule'), 'back'=>CopixUrl::get('||')));
            } else {
        return CopixActionGroup::process ('genericTools|Messages::getError', array ('message'=>CopixI18N::get ('teleprocedures|teleprocedures.error.noEcole'), 'back'=>CopixUrl::get('||')));
      }
        }
        return CopixActionGroup::process ('teleprocedures|default::listTeleprocedures', array ('id'=>$id));
    }


    /**
   * Liste des teleprocedures
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2008/01/30
     * @param integer $id Id du module

   */
    public function processListTeleprocedures ()
    {
        $id = $this->getRequest ('id', null);
        $motcle = _request("motcle");
        $type = _request("type");
        $clos = _request("clos");
        $ecole = _request("ecole");

        $dao = CopixDAOFactory::create("teleprocedures|teleprocedure");
        $rTelep = $dao->get($id);
        $criticErrors = array();

        if (!$rTelep)
            $criticErrors[] = CopixI18N::get ('teleprocedures|teleprocedures.error.noParameter');
        else {
            $mondroit = Kernel::getLevel( "MOD_TELEPROCEDURES", $id );
            if (!TeleproceduresService::canMakeInTelep('VIEW',$mondroit))
                $criticErrors[] = CopixI18N::get ('kernel|kernel.error.noRights');
            else {
                $parent = Kernel::getModParentInfo( "MOD_TELEPROCEDURES", $id);
                $rTelep->parent = $parent;
            }
        }
        //print_r($rTelep);

        if ($criticErrors)
            return CopixActionGroup::process ('genericTools|Messages::getError', array ('message'=>implode('<br/>',$criticErrors), 'back'=>CopixUrl::get('||')));

        $title = $rTelep->parent["nom"];
        $ville = $rTelep->parent["id"];

        $rBlog = TeleproceduresService::checkIfBlogExists ($rTelep);

        $canInsert = TeleproceduresService::canMakeInTelep('ADD_INTERVENTION',$mondroit);
        $canViewBlog = TeleproceduresService::canMakeInTelep('VIEW_BLOG',$mondroit);

        if (!TeleproceduresService::canMakeInTelep('VIEW_COMBO_ECOLES',$mondroit))
            $ecole = null;

        $tplListe = new CopixTpl ();
        $tplListe->assign ('filtre', CopixZone::process('filtre',array('rTelep'=>$rTelep, 'motcle'=>$motcle, 'clos'=>$clos, 'type'=>$type, 'ecole'=>$ecole, 'mondroit'=>$mondroit)));
        $tplListe->assign ('list', CopixZone::process('list',array('rTelep'=>$rTelep, 'motcle'=>$motcle, 'clos'=>$clos, 'type'=>$type, 'ecole'=>$ecole, 'mondroit'=>$mondroit)));
        $tplListe->assign ('types', CopixZone::process('types',array('rTelep'=>$rTelep, 'canInsert'=>$canInsert)));
        if ($canViewBlog && $rBlog) {
            $tplListe->assign ("infosVille", CopixZone::process ('welcome|Actualites', array(
             //'titre'=>CopixI18N::get ('teleprocedures.blog.infosVille'),
             'blog'=>$rBlog->url_blog,
             'nb'=>3,
             'colonnes'=>1,
             'chapo'=>true,
             'hreflib'=>CopixI18N::get ('teleprocedures.blog.infosVille.viewAll'),
             'hr'=>true,
            )));
            $tplListe->assign ("pagesVille", CopixZone::process ('welcome|Pages', array(
             //'titre'=>CopixI18N::get ('teleprocedures.blog.pagesVille'),
             'blog'=>$rBlog->url_blog,
             'nb'=>3,
             'content'=>true,
             'hr'=>true,
            )));


        }


        $main = $tplListe->fetch('list.tpl');

        $tpl = new CopixTpl ();
        $tpl->assign ('TITLE_PAGE', $title);

    $MENU = array();
        if (TeleproceduresService::canMakeInTelep('ADMIN',$mondroit))
      $MENU[] = array('txt' => CopixI18N::get('teleprocedures|teleprocedures.admin'), 'type' => '', 'url' => CopixUrl::get ('admin|admin', array('id'=>$rTelep->id)));

    $tpl->assign ("MENU", $MENU);
        $tpl->assign ("MAIN", $main);
        return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);

    }



    /**
   * Insertion d'une teleprocedure
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2008/01/30
     * @param integer $ecole Id de l'ecole
     * @param integer $idtype Type de la teleprocedure
   */
    public function insert ()
    {
        $save = _request("save") ? _request("save") : NULL;

        $idtype = _request("idtype") ? _request("idtype") : NULL;
        $idstatu = _request("idstatu") ? _request("idstatu") : NULL;
        $objet = _request("objet") ? _request("objet") : NULL;
        $detail = _request("detail") ? _request("detail") : NULL;

        $criticErrors = $errors = array();

        $daoType = & CopixDAOFactory::create ('teleprocedures|type');

        if ($idtype) {

            if ($rType = $daoType->get($idtype)) {
                $mondroit = Kernel::getLevel("MOD_TELEPROCEDURES", $rType->teleprocedure);
                if (!TeleproceduresService::canMakeInTelep('ADD_INTERVENTION',$mondroit))
                    $criticErrors[] = CopixI18N::get ('kernel|kernel.error.noRights');
            } else
                $criticErrors[] = CopixI18N::get ('teleprocedures|teleprocedures.error.noParameter');

            $rEcole = Kernel::getNodeInfo ('BU_ECOLE', TeleproceduresService::getTelepEcole(), false);
            if (!$rEcole)
                $criticErrors[] = CopixI18N::get ('teleprocedures|teleprocedures.error.prob.ecole');

        } else
            $criticErrors[] = CopixI18N::get ('teleprocedures|teleprocedures.error.noParameter');

        if ($criticErrors)
            return CopixActionGroup::process ('genericTools|Messages::getError', array ('message'=>implode('<br/>',$criticErrors), 'back'=>CopixUrl::get('teleprocedures||')));

        $title = $rEcole["nom"].(($rEcole["desc"])?" (".$rEcole["desc"].")":"");

        $rForm = CopixDAOFactory::createRecord('intervention');
        if ($save == 1) {

            if (!$objet)
                $errors[] = CopixI18N::get ('teleprocedures.error.objet.manquant');
            if (!$detail || $detail==html_entity_decode(CopixI18N::get ('teleprocedures.interv.default.detail')))
                $errors[] = CopixI18N::get ('teleprocedures.error.detail.manquant');

            $rForm->idtype = $idtype;
            $rForm->format = $rType->format;
            //$rForm->idstatu = $idstatu;
            $rForm->idstatu = CopixConfig::get ('teleprocedures|statutNouveau');
            $rForm->objet = $objet;
            $rForm->detail = $detail;

            if (!count($errors)) {
                $daoIntervention = CopixDAOFactory::create("intervention");
                $session = Kernel::getSessionBU();
                $rForm->iduser = $session['user_id'];
                $rForm->dateinter = date('Ymd');
                $rForm->idetabliss = $rEcole["id"];
                //$rForm->datederniere = 0;
                $rForm->datederniere = date('Y-m-d H:i:s');
                $rForm->responsables = $rType->responsables;
                $rForm->lecteurs = $rType->lecteurs;
                $rForm->mail_from = $rType->mail_from;
                $rForm->mail_to = $rType->mail_to;
                $rForm->mail_cc = $rType->mail_cc;
                $rForm->mail_message = $rType->mail_message;
                //print_r($rForm);
                $daoIntervention->insert ($rForm);

                if ($rForm->idinter) {
                    $droits = TeleproceduresService::copyDroitFromTypeToInter ($rForm);
                    TeleproceduresService::alertResponsables ($rForm, $droits);

                    TeleproceduresService::userReadIntervention ($rForm->idinter, $session['user_id']);

                }
                return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get('|listTeleprocedures', array('id'=>$rType->teleprocedure)));

            }

        } else {
            $rForm->objet = $rType->nom;
            $rForm->idtype = $idtype;
            $rForm->format = $rType->format;
            $rForm->detail = ($rType->texte_defaut) ? $rType->texte_defaut : html_entity_decode(CopixI18N::get ('teleprocedures.interv.default.detail'));
        }

        $tplForm = new CopixTpl ();

        $tplForm->assign ('detail_edition', CopixZone::process ('kernel|edition', array('field'=>'detail', 'format'=>$rForm->format, 'content'=>$rForm->detail, 'height'=>450)));

        $tplForm->assign ('date',date("Y-m-d"));
        $tplForm->assign ('rEcole',$rEcole);
        $tplForm->assign ('rForm',$rForm);
        $tplForm->assign ('rType',$rType);
        $tplForm->assign ('errors',$errors);
        //var_dump($rEcole);

        $daoStat = & CopixDAOFactory::create ('teleprocedures|statu');
    $tplForm->assign ('arStat', $daoStat->findAll ());
      $daoType = & CopixDAOFactory::create ('teleprocedures|type');
    $tplForm->assign ('arType', $daoType->findAll ());

        $main = $tplForm->fetch('insert.tpl');

        $tpl = new CopixTpl ();
        $tpl->assign ('TITLE_PAGE', CopixI18N::get ('teleprocedures.title.newTelep'));
        $tpl->assign ("MAIN", $main);
        return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);

    }


    /**
   * Detail d'une teleprocedure
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2008/01/30
     * @param integer $id Id de la procedure
     * @param integer $print Si version imprimable
     * @param array $rFiche (Si on revient en modif apres erreur) Contient des donnees de l'intervention qui ecrasent celles issues de la base
   */
    public function processFiche ()
    {
        $id = $this->getRequest ('id', null);
        $errors = ($this->getRequest ('errors', array()));
        $ok = ($this->getRequest ('ok', array()));
        $print = $this->getRequest ('print');
        $send = $this->getRequest ('send');
        $fiche = $this->getRequest ('rFiche', array());

        $daoIntervention = CopixDAOFactory::create("intervention");

        $criticErrors = array();

        if ($id && $rFiche = $daoIntervention->get($id)) {
            $title = $rFiche->objet;
            $mondroit = Kernel::getLevel("MOD_TELEPROCEDURES", $rFiche->type_teleprocedure);
            if (!TeleproceduresService::canMakeInTelep('VIEW', $mondroit))
                $criticErrors[] = CopixI18N::get ('kernel|kernel.error.noRights');
        } else
            $criticErrors[] = CopixI18N::get ('teleprocedures|teleprocedures.error.prob.telep');

        if ($criticErrors)
            return CopixActionGroup::process ('genericTools|Messages::getError', array ('message'=>implode('<br/>',$criticErrors), 'back'=>CopixUrl::get('teleprocedures||')));

        if (isset($fiche['mail_from'])) $rFiche->mail_from = $fiche['mail_from'];
        if (isset($fiche['mail_to'])) $rFiche->mail_to = $fiche['mail_to'];
        if (isset($fiche['mail_cc'])) $rFiche->mail_cc = $fiche['mail_cc'];
        if (isset($fiche['mail_message'])) $rFiche->mail_message = $fiche['mail_message'];


        $fiche = CopixZone::process('fiche',array('rFiche'=>$rFiche, 'mondroit'=>$mondroit, 'errors'=>$errors, 'ok'=>$ok, 'print'=>$print));
        $comms = CopixZone::process('ficheComms',array('rFiche'=>$rFiche, 'mondroit'=>$mondroit));
        $actions = CopixZone::process('ficheActions',array('rFiche'=>$rFiche, 'mondroit'=>$mondroit));
        if ($print)
            $main = $fiche;
        else
            $main = $fiche.$comms.$actions;

        $tpl = new CopixTpl ();
        $tpl->assign ('TITLE_PAGE', $title);

        $tpl->assign ("MAIN", $main);

        if (!$print) {
            // Enregistrement dans le trackin
            $user = _currentUser ()->getId();
            TeleproceduresService::userReadIntervention ($id, $user);
        }


        if (0 && $print) {
            $ppo = new CopixPPO ();
            $ppo->result = $main;
            $ppo->TITLE_PAGE = $title;
            return _arPPO ($ppo, array ('template'=>'print_ppo.tpl', 'mainTemplate'=>'default|main_print.php'));
        } else
            return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
    }



    /**
   * Detail des droits d'une teleprocedure
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2009/03/06
     * @param integer $id Id de la procedure
     * @param array $rFiche (Si on revient en modif apres erreur) Contient des donnees de l'intervention qui ecrasent celles issues de la base
   */
    public function processFicheDroits ()
    {
        $id = $this->getRequest ('id', null);
        $errors = $this->getRequest ('errors', array());
        //$ok = $this->getRequest ('ok', array());
        $fiche = $this->getRequest ('rFiche', array());

        $daoIntervention = CopixDAOFactory::create("intervention");

        $criticErrors = array();



        if ($id && $rFiche = $daoIntervention->get($id)) {
            $title = $rFiche->objet;
            $mondroit = Kernel::getLevel("MOD_TELEPROCEDURES", $rFiche->type_teleprocedure);
            $canDelegue = TeleproceduresService::canMakeInTelep('DELEGUE', $mondroit, array('idinter'=>$rFiche->idinter));
            if (!TeleproceduresService::canMakeInTelep('VIEW', $mondroit))
                $criticErrors[] = CopixI18N::get ('kernel|kernel.error.noRights');
            elseif (!$canDelegue)
                $criticErrors[] = CopixI18N::get ('kernel|kernel.error.noRights');
        } else
            $criticErrors[] = CopixI18N::get ('teleprocedures|teleprocedures.error.prob.telep');

        if ($criticErrors)
            return CopixActionGroup::process ('genericTools|Messages::getError', array ('message'=>implode('<br/>',$criticErrors), 'back'=>CopixUrl::get('teleprocedures||')));

        if (isset($fiche['responsables'])) $rFiche->responsables = $fiche['responsables'];
        if (isset($fiche['lecteurs'])) $rFiche->lecteurs = $fiche['lecteurs'];


        //$fiche = CopixZone::process('fiche',array('rFiche'=>$rFiche, 'errors'=>$errors, 'ok'=>$ok));
        $fiche = '';
        $actions = CopixZone::process('ficheActionsDroits',array('rFiche'=>$rFiche, 'errors'=>$errors));
        $main = $fiche.$actions;

        $tpl = new CopixTpl ();
        $tpl->assign ('TITLE_PAGE', $title);

        $tpl->assign ("MAIN", $main);

        return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
    }


    /**
   * Changement du statut d'une procedure
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2008/01/30
     * @param integer $id Id de la procedure
   */
    public function changeStatut ()
    {
        $id = $this->getRequest ('id', null);
        $idstatu = $this->getRequest ('idstatu', null);

        $daoIntervention = CopixDAOFactory::create("intervention");

        $criticErrors = $errors = array();

        if ($idstatu && $id && $rFiche = $daoIntervention->get($id)) {
            $title = $rFiche->objet;
            $mondroit = Kernel::getLevel("MOD_TELEPROCEDURES", $rFiche->type_teleprocedure);
            if (!TeleproceduresService::canMakeInTelep('CHANGE_STATUT', $mondroit, array('idinter'=>$rFiche->idinter)))
                $criticErrors[] = CopixI18N::get ('kernel|kernel.error.noRights');
        } else
            $criticErrors[] = CopixI18N::get ('teleprocedures|teleprocedures.error.prob.telep');

        if ($criticErrors)
            return CopixActionGroup::process ('genericTools|Messages::getError', array ('message'=>implode('<br/>',$criticErrors), 'back'=>CopixUrl::get('teleprocedures||')));

        if ($rFiche->idstatu != $idstatu) {
            $rFiche->idstatu = $idstatu;
            $daoIntervention->update($rFiche);
        }
        return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('|fiche', array('id'=>$id)));
    }


    /**
   * Changement des responsables d'une procedure
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2009/01/21
     * @param integer $id Id de la procedure
     * @param string $responsables Nouveaux responsables
   */

    public function changeResponsables ()
    {
        $id = $this->getRequest ('id', null);

        $daoIntervention = CopixDAOFactory::create("intervention");

        $criticErrors = $errors = array();

        if ($id && $rFiche = $daoIntervention->get($id)) {
            $title = $rFiche->objet;
            $mondroit = Kernel::getLevel("MOD_TELEPROCEDURES", $rFiche->type_teleprocedure);
            if (!TeleproceduresService::canMakeInTelep('DELEGUE', $mondroit, array('idinter'=>$rFiche->idinter)))
                $criticErrors[] = CopixI18N::get ('kernel|kernel.error.noRights');
        } else
            $criticErrors[] = CopixI18N::get ('teleprocedures|teleprocedures.error.prob.telep');

        if ($criticErrors)
            return CopixActionGroup::process ('genericTools|Messages::getError', array ('message'=>implode('<br/>',$criticErrors), 'back'=>CopixUrl::get('teleprocedures||')));

        $reqResponsables = $this->getRequest ('responsables');

        // Responsables
        $responsables = $reqResponsables;
        $responsables = str_replace(array(" "), "", $responsables);
        $responsables = str_replace(array(",",";"), ",", $responsables);
        $responsables = preg_split("/[\s,]+/", $responsables);
        $tabResponsables = array();
        $deja = array();
        // On vérifie que les membres existent
        while (list(,$login) = each ($responsables)) {
            if (!$login) continue;
            $userInfo = Kernel::getUserInfo("LOGIN", $login);
            if (!$userInfo)
                $errors[] = CopixI18N::get ('teleprocedures|teleprocedures.error.memberNoUser', array($login));
            elseif ($userInfo['type']!='USER_VIL')
                $errors[] = CopixI18N::get ('teleprocedures|teleprocedures.error.memberNoVille', array($login));
            else {
                $tabResponsables[] = $userInfo;
                $deja[] = $login;
            }
        }

        // Lecteurs
        $lecteurs = $this->getRequest ('lecteurs');
        $lecteurs = str_replace(array(" "), "", $lecteurs);
        $lecteurs = str_replace(array(",",";"), ",", $lecteurs);
        $lecteurs = preg_split("/[\s,]+/", $lecteurs);
        $tabLecteurs = array();
        // On vérifie que les membres existent
        while (list(,$login) = each ($lecteurs)) {
            if (!$login) continue;
            $userInfo = Kernel::getUserInfo("LOGIN", $login);
            if (!$userInfo)
                $errors[] = CopixI18N::get ('teleprocedures|teleprocedures.error.memberNoUser', array($login));
            elseif ($userInfo['type']!='USER_VIL')
                $errors[] = CopixI18N::get ('teleprocedures|teleprocedures.error.memberNoVille', array($login));
            elseif (in_array($login,$deja))
                $errors[] = CopixI18N::get ('teleprocedures|teleprocedures.error.memberDeja', array($login));
            else
                $tabLecteurs[] = $userInfo;
        }

        if (!count($tabResponsables))
            $errors[] = CopixI18N::get ('teleprocedures|teleprocedures.error.noResp');

        if (!$errors) {
            if ($rFiche->responsables != $reqResponsables) {
                TeleproceduresService::alertChangeResponsables ($rFiche, $reqResponsables);
                $rFiche->responsables = $reqResponsables;
                //var_dump($rFiche);
                $daoIntervention->update($rFiche);
                TeleproceduresService::saveDroits ('intervention', $rFiche->idinter, 'responsables', $tabResponsables);
            }
            return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('|fiche', array('id'=>$id)));
        }

        return CopixActionGroup::process ('teleprocedures|default::ficheDroits', array ('id'=>$id, 'errors'=>$errors, 'rFiche'=>array('responsables'=>$this->getRequest('responsables'), 'lecteurs'=>$this->getRequest('lecteurs'))));

    }


    /**
   * Envoi d'une intervention a des mails exterieurs
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2009/01/22
     * @param integer $id Id de la procedure
     * @param string $mail_from Expediteur
     * @param string $mail_to Destinataire
     * @param string $mail_cc Copie a
     * @param string $mail_message Message d'accompagnement optionnel
   */

    public function sendMails ()
    {
        $id = $this->getRequest ('id', null);
        $mail_from = $this->getRequest ('mail_from', null);
        $mail_to = $this->getRequest ('mail_to', null);
        $mail_cc = $this->getRequest ('mail_cc', null);
        $mail_message = $this->getRequest ('mail_message', null);

        $daoIntervention = CopixDAOFactory::create("intervention");

        $criticErrors = $errors = array();

        if ($id && $rFiche = $daoIntervention->get($id)) {
            $title = $rFiche->objet;
            $mondroit = Kernel::getLevel("MOD_TELEPROCEDURES", $rFiche->type_teleprocedure);
            if (!TeleproceduresService::canMakeInTelep('SEND_MAILS', $mondroit, array('idinter'=>$rFiche->idinter)))
                $criticErrors[] = CopixI18N::get ('kernel|kernel.error.noRights');
        } else
            $criticErrors[] = CopixI18N::get ('teleprocedures|teleprocedures.error.prob.telep');

        if (!CopixConfig::get('|mailEnabled'))
            $criticErrors[] = CopixI18N::get ('teleprocedures|teleprocedures.error.noMailEnabled');

        if ($criticErrors)
            return CopixActionGroup::process ('genericTools|Messages::getError', array ('message'=>implode('<br/>',$criticErrors), 'back'=>CopixUrl::get('teleprocedures||')));

        $fiche = CopixZone::process('fiche',array('rFiche'=>$rFiche, 'print'=>1));

        $ecoleInfos = Kernel::getNodeInfo('BU_ECOLE',$rFiche->idetabliss,false);

        $errors = $ok = null;

        require_once(COPIX_UTILS_PATH.'CopixUtils.lib.php');

        if (!$mail_from)
            $errors = CopixI18N::get ('teleprocedures|teleprocedures.error.mail_from', '');
        else {
            $list = preg_split("/[\s,]+/",$mail_from);
            foreach ($list as $email) {
                if (!validateEMail($email))
                    $errors .= CopixI18N::get ('teleprocedures|teleprocedures.error.incorrectMail', $email)."\n";
            }
        }
        if (!$mail_to)
            $errors = CopixI18N::get ('teleprocedures|teleprocedures.error.mail_to', '');
        else {
            $list = preg_split("/[\s,]+/",$mail_to);
            foreach ($list as $email) {
                if (!validateEMail($email))
                    $errors .= CopixI18N::get ('teleprocedures|teleprocedures.error.incorrectMail', $email)."\n";
            }
        }
        if ($mail_cc) {
            $list = preg_split("/[\s,]+/",$mail_cc);
            foreach ($list as $email) {
                if (!validateEMail($email))
                    $errors .= CopixI18N::get ('teleprocedures|teleprocedures.error.incorrectMail', $email)."\n";
            }
        }

        if ($errors)
            return CopixActionGroup::process ('teleprocedures|default::fiche', array ('id'=>$id, 'errors'=>$errors, 'rFiche'=>array('mail_from'=>$mail_from, 'mail_to'=>$mail_to, 'mail_cc'=>$mail_cc, 'mail_message'=>$mail_message)));


        if (!$errors) {

            require_once(COPIX_UTILS_PATH.'CopixEMailer.class.php');
            $from = $fromName = $mail_from;
            $to = $mail_to;
            $cc = $cci = null;
            if ($mail_cc)
                $cc = $mail_cc;
            $subject = $rFiche->objet.' / '.$rFiche->type_nom.' / '.$rFiche->ecole_nom;
            if ($rFiche->ecole_type)
                $subject .= ' ('.$rFiche->ecole_type.')';

            $de = _currentUser()->getExtra('prenom').' '._currentUser()->getExtra('nom');

            $message = '';
            $message .= "<p>L'intervention suivante vous est transmise par ".$de.".</p>";

            if ($mail_message)
                $message .= '<p>'.nl2br($mail_message).'</p><hr/>';

            $message .= $fiche;


            //$from = CopixConfig::get('|mailFrom');
            //$fromName = CopixConfig::get('|mailFromName');

            $mail = new CopixHtmlEMail ($to, $cc, $cci, utf8_decode($subject), utf8_decode($message));

            $send = $mail->send ($from, $fromName);

            if (!$send)
                $errors = CopixI18N::get ('teleprocedures|teleprocedures.error.sendMail');
            else {
                $ok = CopixI18N::get ('teleprocedures|teleprocedures.ok.sendMail', $mail_to);

                $info_commentaire = "Mail envoy&eacute; de $from &agrave; $to";
                if ($cc)
                    $info_commentaire .= " (copie &agrave; $cc)";
                if ($mail_message)
                    $info_commentaire .= " - Message d'acompagnement : ".$mail_message."";

                $rFiche->insertInfoSupp (null, $info_commentaire);

            }
        }

        //var_dump($send);

        if ($errors) $errors = (str_replace("\n","<br/>",trim($errors)));
        if ($ok) $ok = ($ok);

        return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('|fiche', array('id'=>$id, 'errors'=>$errors, 'ok'=>$ok)));
        //return CopixActionGroup::process ('teleprocedures|default::fiche', array ('id'=>$id, 'errors'=>$errors));

    }


    /**
   * Ajout d'une info supplementaire
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2008/01/30
     * @param integer $id Id de la procedure
   */
    public function insertInfoSupp ()
    {
        $id = $this->getRequest ('id', null);
        $info_message = $this->getRequest ('info_message', null);
        $info_commentaire = $this->getRequest ('info_commentaire', null);
        $visible = $this->getRequest ('visible', null);

        $daoIntervention = CopixDAOFactory::create("intervention");
        $daoInfoSupp = CopixDAOFactory::create("infosupp");

        $criticErrors = $errors = array();

        if ($id && $rFiche = $daoIntervention->get($id)) {
            $title = $rFiche->objet;
            $mondroit = Kernel::getLevel("MOD_TELEPROCEDURES", $rFiche->type_teleprocedure);
            if (!TeleproceduresService::canMakeInTelep('ADD_COMMENT', $mondroit))
                $criticErrors[] = CopixI18N::get ('kernel|kernel.error.noRights');
        } else
            $criticErrors[] = CopixI18N::get ('teleprocedures|teleprocedures.error.prob.telep');

        if ($criticErrors)
            return CopixActionGroup::process ('genericTools|Messages::getError', array ('message'=>implode('<br/>',$criticErrors), 'back'=>CopixUrl::get('teleprocedures||')));

        if ($info_message || $info_commentaire) {

                //$canCheckVisible = TeleproceduresService::canMakeInTelep('CHECK_VISIBLE', $mondroit);

                $rFiche->insertInfoSupp ($info_message, $info_commentaire);

        }

        return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('|fiche', array('id'=>$id)));

    }



}


