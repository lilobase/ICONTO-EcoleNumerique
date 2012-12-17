<?php
/**
 * Actiongroup du back-office du module Teleprocedures
 *
 * @package	Iconito
 * @subpackage teleprocedures
 */

_classInclude('teleprocedures|teleproceduresservice');

require_once(COPIX_UTILS_PATH.'CopixUtils.lib.php');

class ActionGroupAdmin extends EnicActionGroup
{
    public function beforeAction ()
    {
        _currentUser()->assertCredential ('group:[current_user]');

    }

    /**
   * Administration des teleprocedures d'une ville
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2009/01/12
     * @param integer $ville Id de la ville
   */
    public function admin ()
    {
        $id = $this->getRequest ('id', null);

        $motcle = _request("motcle") ? _request("motcle") : NULL;
        $type = _request("type") ? _request("type") : NULL;
        $clos = _request("clos") ? _request("clos") : NULL;
        $ecole = _request("ecole") ? _request("ecole") : NULL;

        $dao = CopixDAOFactory::create("teleprocedures|teleprocedure");
        $rTelep = $dao->get($id);

        $criticErrors = array();

        if (!$rTelep)
            $criticErrors[] = CopixI18N::get ('teleprocedures|teleprocedures.error.noParameter');
        else {
            $mondroit = Kernel::getLevel( "MOD_TELEPROCEDURES", $id );
            if (!TeleproceduresService::canMakeInTelep('ADMIN',$mondroit))
                $criticErrors[] = CopixI18N::get ('kernel|kernel.error.noRights');
            else {
                $parent = Kernel::getModParentInfo( "MOD_TELEPROCEDURES", $id);
                $rTelep->parent = $parent;
            }
        }

        if ($criticErrors)
            return CopixActionGroup::process ('genericTools|Messages::getError', array ('message'=>implode('<br/>',$criticErrors), 'back'=>CopixUrl::get('teleprocedures||')));

        $title = $rTelep->parent["nom"];
        $ville = $rTelep->parent["id"];

        $rBlog = TeleproceduresService::checkIfBlogExists ($rTelep);

        $canAdminBlog = TeleproceduresService::canMakeInTelep('ADMIN_BLOG',$mondroit);

        if (!TeleproceduresService::canMakeInTelep('VIEW_COMBO_ECOLES',$mondroit))
            $ecole = null;

        $tplListe = new CopixTpl ();
        $tplListe->assign ('filtre', CopixZone::process('filtre',array('rTelep'=>$rTelep, 'motcle'=>$motcle, 'clos'=>$clos, 'type'=>$type, 'ecole'=>$ecole, 'admin'=>true, 'mondroit'=>$mondroit)));
        $tplListe->assign ('list', CopixZone::process('list',array('rTelep'=>$rTelep, 'motcle'=>$motcle, 'clos'=>$clos, 'type'=>$type, 'ecole'=>$ecole, 'mondroit'=>$mondroit)));
        $tplListe->assign ('types', CopixZone::process('types',array('rTelep'=>$rTelep, 'admin'=>true)));

        if ($canAdminBlog && $rBlog) {
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
        $tplListe->assign ('rBlog', $rBlog);
        $tplListe->assign ('canAdminBlog', $canAdminBlog);

        $main = $tplListe->fetch('list.tpl');

        $tpl = new CopixTpl ();
        $tpl->assign ('TITLE_PAGE', $title);

        $tpl->assign ("MAIN", $main);
        return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);

    }

    /**
   * Formulaire de saisie/modif d'un type de teleprocedure
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2009/01/15
   * @param integer $save 1 si submit du formulaire
   */
    public function formtype ()
    {
        $idtype = $this->getRequest ('idtype');
        $teleprocedure = $this->getRequest ('teleprocedure');
        $save = $this->getRequest ('save');

        $daoType = & CopixDAOFactory::create ('teleprocedures|type');

        $criticErrors = $errors = array();

        $tplForm = new CopixTpl ();
        $tplForm->assign ('is_online', array('values'=>array(1,0), 'output'=>array(CopixI18N::get('blog|blog.oui'), CopixI18N::get('blog|blog.non'))));
        $formats = CopixConfig::get ('teleprocedures|formats_types');
        $tabFormats = preg_split('/[\s,]+/',$formats);
        $values = $output = array();
        foreach ($tabFormats as $k) {
            $values[] = $k;
            $output[] = CopixI18N::get('blog|blog.default_format_articles.'.$k);
        }
        $tplForm->assign ('format', array('values'=>$values, 'output'=>$output));

        $tplForm->assign ('linkpopup_responsables', CopixZone::process ('annuaire|linkpopup', array('field'=>'responsables', 'profil'=>'USER_VIL')));
        $tplForm->assign ('linkpopup_lecteurs', CopixZone::process ('annuaire|linkpopup', array('field'=>'lecteurs', 'profil'=>'USER_VIL')));


        // Verifications
        if ($idtype) {
            if ($rType = $daoType->get($idtype)) {
                $mondroit = Kernel::getLevel("MOD_TELEPROCEDURES", $rType->teleprocedure);
                if (!TeleproceduresService::canMakeInTelep('ADMIN',$mondroit))
                    $criticErrors[] = CopixI18N::get ('kernel|kernel.error.noRights');
            } else
                $criticErrors[] = CopixI18N::get ('teleprocedures|teleprocedures.error.noParameter');
        } elseif ($teleprocedure) {
            $mondroit = Kernel::getLevel("MOD_TELEPROCEDURES", $teleprocedure);
            if (!TeleproceduresService::canMakeInTelep('ADMIN',$mondroit))
                $criticErrors[] = CopixI18N::get ('kernel|kernel.error.noRights');
        } else
            $criticErrors[] = CopixI18N::get ('teleprocedures|teleprocedures.error.noParameter');

        if ($criticErrors)
            return CopixActionGroup::process ('genericTools|Messages::getError', array ('message'=>implode('<br/>',$criticErrors), 'back'=>CopixUrl::get('teleprocedures||')));

        // Traitement

        if ($idtype) { // Modif

            $title = CopixI18N::get ('teleprocedures|teleprocedures.title.modifType');

            if ($save) {

                // Responsables
                $responsables = $this->getRequest ('responsables');
                $responsables = str_replace(array(" "), "", $responsables);
                $responsables = str_replace(array(",",";"), ",", $responsables);
                $responsables = preg_split('/[\s,]+/',$responsables);
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
                //print_r($tabResponsables);

                // Lecteurs
                $lecteurs = $this->getRequest ('lecteurs');
                $lecteurs = str_replace(array(" "), "", $lecteurs);
                $lecteurs = str_replace(array(",",";"), ",", $lecteurs);
                $lecteurs = preg_split('/[\s,]+/',$lecteurs);
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

                $type = $rType;
                $type->nom = $this->getRequest ('nom');
                $type->is_online = $this->getRequest ('is_online');
                $type->teleprocedure = $this->getRequest ('teleprocedure');
                $type->format = $this->getRequest ('format');
                $type->texte_defaut = $this->getRequest ('texte_defaut');
                $type->responsables = $this->getRequest ('responsables');
                $type->lecteurs = $this->getRequest ('lecteurs');
                $type->mail_from = $this->getRequest ('mail_from');
                $type->mail_to = $this->getRequest ('mail_to');
                $type->mail_cc = $this->getRequest ('mail_cc');
                $type->mail_message = $this->getRequest ('mail_message');

                if ($type->mail_from && !validateEMail($type->mail_from))
                    $errors[] = CopixI18N::get ('teleprocedures|teleprocedures.error.incorrectMail', $type->mail_from);
                if ($type->mail_to) {
                    $list = preg_split('/[\s,]+/',$type->mail_to);
                    foreach ($list as $email) {
                        if (!validateEMail($email))
                            $errors[] = CopixI18N::get ('teleprocedures|teleprocedures.error.incorrectMail', $email);
                    }
                }
                if ($type->mail_cc) {
                    $list = preg_split('/[\s,]+/',$type->mail_cc);
                    foreach ($list as $email) {
                        if (!validateEMail($email))
                            $errors[] = CopixI18N::get ('teleprocedures|teleprocedures.error.incorrectMail', $email);
                    }
                }
                if ($type->mail_to && !$type->mail_from)
                    $errors[] = CopixI18N::get ('teleprocedures|teleprocedures.error.mail_from');

                $errorsDao = _dao('teleprocedures|type')->check($type);

                //die();
                if (count($errors) || is_array($errorsDao)) { // Erreurs
                    if (is_array($errorsDao))
                        $errors = array_merge($errorsDao,$errors);
                } else { // Pas d'erreurs
                    $daoType->update ($type);
                    TeleproceduresService::saveDroits ("type", $idtype, 'responsables', $tabResponsables);
                    TeleproceduresService::saveDroits ("type", $idtype, 'lecteurs', $tabLecteurs);
                    return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('admin|admin', array('id'=>$type->teleprocedure)));
                }
            } else {
                $type = $rType;
            }

        } else { // Creation

            $title = CopixI18N::get ('teleprocedures|teleprocedures.title.newType');

            $type = CopixDAOFactory::createRecord('teleprocedures|type');
            if ($save) {

                // Responsables
                $responsables = $this->getRequest ('responsables');
                $responsables = str_replace(array(" "), "", $responsables);
                $responsables = str_replace(array(",",";"), ",", $responsables);
                $responsables = preg_split('/[\s,]+/',$responsables);
                $tabResponsables = array();
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
                $lecteurs = preg_split('/[\s,]+/',$lecteurs);
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

                $type->nom = $this->getRequest ('nom');
                $type->is_online = $this->getRequest ('is_online');
                $type->teleprocedure = $this->getRequest ('teleprocedure');
                $type->format = $this->getRequest ('format');
                $type->texte_defaut = $this->getRequest ('texte_defaut');
                $type->responsables = $this->getRequest ('responsables');
                $type->lecteurs = $this->getRequest ('lecteurs');
                $type->mail_from = $this->getRequest ('mail_from');
                $type->mail_to = $this->getRequest ('mail_to');
                $type->mail_cc = $this->getRequest ('mail_cc');
                $type->mail_message = $this->getRequest ('mail_message');
                $errorsDao = _dao('teleprocedures|type')->check($type);

                if (count($errors) || is_array($errorsDao)) { // Erreurs
                    if (is_array($errorsDao))
                        $errors = array_merge($errorsDao,$errors);
                } else { // Pas d'erreurs
                    $daoType->insert ($type);
                    if ($type->idtype) {
                        TeleproceduresService::saveDroits ("type", $type->idtype, 'responsables', $tabResponsables);
                        TeleproceduresService::saveDroits ("type", $type->idtype, 'lecteurs', $tabLecteurs);
                    }
                    return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('admin|admin', array('id'=>$type->teleprocedure)));

                }

            } else {
                $type->teleprocedure = $teleprocedure;
                $type->is_online = 1;
                $type->format = CopixConfig::get ('teleprocedures|default_format');
            }

        }

        //print_r($type);
        $tplForm->assign ('type', $type);

        $tplForm->assign ('edition_texte_defaut', CopixZone::process ('kernel|edition', array('field'=>'texte_defaut', 'format'=>$type->format, 'content'=>$type->texte_defaut, 'height'=>460)));

        $tplForm->assign ('errors', $errors);
        $tplForm->assign ('mailEnabled', (CopixConfig::get('|mailEnabled')));

        $main = $tplForm->fetch('form-type.tpl');

        $tpl = new CopixTpl ();
        $tpl->assign ('TITLE_PAGE', $title);

        $tpl->assign ("MAIN", $main);
        return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);

    }

}


