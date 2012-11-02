<?php

_classInclude('agenda|dateservices');

/**
 * Actiongroup du module Groupe
 *
 * @package Iconito
 * @subpackage	Groupe
 */
class ActionGroupGroupe extends enicActionGroup
{
    public function beforeAction ()
    {
        _currentUser()->assertCredential ('group:[current_user]');

    }

        public function processMigrate()
        {
            $datas = $this->db->query('SELECT * FROM module_groupe_tag')->toArray();
            foreach($datas as $data){
                $this->service('tagService')->addTagsToGroup($data['groupe'], array(utf8_encode($data['tag'])));
            }

            echo "si il n'y a rien au dessus, c'est cool ! :)";

            return _arNone();
        }

    /**
     * Affiche la liste des groupes o� l'usager courant est inscrit
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2005/11/15
     * @param integer $page (option, 1 par d�faut) Num�ro de page dans la liste des groupes
     */
    public function getListMy ()
    {
        return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('||'));
        if (!Kernel::is_connected()) return CopixActionGroup::process ('genericTools|Messages::getError', array ('message'=>CopixI18N::get ('kernel|kernel.error.nologin'), 'back'=>CopixUrl::get ('auth|default|login')));

        $dao = CopixDAOFactory::create("groupe");
        $kernel_service = & CopixClassesFactory::Create ('kernel|kernel');
        $groupeService = & CopixClassesFactory::Create ('groupe|groupeService');


        /*
         // Existance de groupes personnels ?
         $dao_kernel_link_user2node = CopixDAOFactory::create("kernel|kernel_link_user2node");
         $mes_groupes = $dao_kernel_link_user2node->getByUserAndNodeType( _currentUser()->getExtra('type'), _currentUser()->getExtra('id'), "CLUB" );
         // Kernel::MyDebug( $mes_groupes );
         foreach( $mes_groupes AS $mes_groupes_key=>$mes_groupes_val ) {
            if( $mes_groupes_val->droit <= 10 ) {
            unset($mes_groupes[$mes_groupes_key]);
            }
            }

            if( 0==count($mes_groupes) ) {
            return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('groupe||getListPublic'));
            }
            */

        $tpl = new CopixTpl ();
        $tpl->assign ('TITLE_PAGE', CopixI18N::get ('groupe|groupe.my'));
        // $tpl->assign ('MENU', '<a href="'.CopixUrl::get ('groupe||getListPublic').'">'.CopixI18N::get ('groupe|groupe.annuaire').'</a> :: <a href="'.CopixUrl::get ('groupe||getListMy').'">'.CopixI18N::get ('groupe|groupe.my').'</a>');

        /*
         $menu = array();
         $menu[] = array('txt'=>CopixI18N::get ('groupe|groupe.annuaire'), 'url' => CopixUrl::get ('groupe||getListPublic'), 'size'=>100);
         $menu[] = array('txt'=>CopixI18N::get ('groupe|groupe.my'), 'url' => CopixUrl::get ('groupe||getListMy'), 'size'=>190);
         $tpl->assign ('MENU', $menu);
         */

        $tplListe = new CopixTpl ();
        $tplListe->assign ('list', CopixZone::process ('groupe|mygroupes', array('where'=>'groupes')));
        $result = $tplListe->fetch("getlistmy.tpl");

    $menu = array();
        if($groupeService->canMakeInGroupe('ADD_GROUP',NULL)) $menu[] = array('url' => CopixUrl::get ('groupe||getEdit'), 'txt'=>CopixI18N::get ('groupe|groupe.btn.addGroup'), 'size'=>140, 'type'=>'create');
    if( CopixConfig::exists('|can_group_showlist') && CopixConfig::get('|can_group_showlist') ) {
    $menu[] = array('url' => CopixUrl::get ('groupe||getListPublic'), 'txt'=>CopixI18N::get ('groupe|groupe.annuaire'), 'current'=>false);
    }
    $menu[] = array('url' => CopixUrl::get ('groupe||getListMy'), 'txt'=>CopixI18N::get ('groupe|groupe.my'), 'current'=>true);
        $tpl->assign ('MENU', $menu);


        $tpl->assign ("MAIN", $result);

        return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
    }


    /**
     * Affiche la liste des groupes publics
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2005/11/15
     * @param integer $page (option, 1 par d�faut) Num�ro de page dans la liste des groupes
     * @param string $kw (option, NULL par d�faut) Mot cl� en cas de recherche
     */
    public function getListPublic ()
    {
        if (!Kernel::is_connected()) return CopixActionGroup::process ('genericTools|Messages::getError', array ('message'=>CopixI18N::get ('kernel|kernel.error.nologin'), 'back'=>CopixUrl::get ('auth|default|login')));

        $dao = CopixDAOFactory::create("groupe");
        $groupeService = & CopixClassesFactory::Create ('groupe|groupeService');

        $page = $this->getRequest ('page', 1);
        $kw = $this->getRequest ('kw');


        $offset = ($page-1)*CopixConfig::get ('groupe|list_nbgroupes');
        $count = CopixConfig::get ('groupe|list_nbgroupes');
        $groupesAll = $dao->getListPublic('','',$kw);

        $nbPages = ceil(count($groupesAll) / $count);

        //for Tags Cloud
        $groupsArray = array();
        foreach($groupesAll as $groupAll)
            $groupsArray[] = $groupAll->id;

        $list = array_slice ($groupesAll, $offset, $count);

        $groupes = array();

        //var_dump($groupes);

        foreach ($list as $groupe) {

            $parent = Kernel::getNodeParents ("CLUB", $groupe->id );

            $ok = true;

            if ($groupe->parent) {
                $parentInfo = Kernel::getNodeInfo ($groupe->parent[0]["type"], $groupe->parent[0]["id"], false);
                if (isset($parentInfo["nom"]))		$groupe->rattachement = $parentInfo["nom"];
                if (isset($parentInfo["desc"]))	$groupe->rattachement .= " (".$parentInfo["desc"].")";

            }

            $userInfo = Kernel::getUserInfo("ID", $groupe->createur);
            $groupe->createur_nom = $userInfo["prenom"]." ".$userInfo["nom"];
            $groupe->createur_infos = $userInfo;
            $mondroit = Kernel::getLevel( "CLUB", $groupe->id);
            $groupe->mondroit = $mondroit;
            $members = $groupeService->getNbMembersInGroupe($groupe->id);
            $groupe->inscrits = $members['inscrits'];
            $groupe->canViewHome = ($groupeService->canMakeInGroupe('VIEW_HOME', $mondroit));

            $blog = $groupeService->getGroupeBlog($groupe->id);
            if ($blog && ($blog->is_public || $groupeService->canMakeInGroupe('VIEW_HOME', $mondroit)))
            $groupe->blog = $blog;
            $groupe->canAdmin = $groupeService->canMakeInGroupe('ADMIN', $mondroit);
                        $groupe->tags = $this->service('tagService')->createLinkForGroup($groupe->id);
            $groupes[] = $groupe;

        }

        $tagsCloud = $this->service('tagService')->createTagsCloud($groupsArray);
        //_dump($tagsCloud);
        $this->js->confirm('#groups .subscribe', 'groupe.conf.groupJoin');

        $tpl = new CopixTpl ();
        $tpl->assign ('TITLE_PAGE', CopixI18N::get ('groupe|groupe.annuaire'));
        $menu = array();
        if($groupeService->canMakeInGroupe('ADD_GROUP',NULL)) $menu[] = array('url' => CopixUrl::get ('groupe||getEdit'), 'txt'=>CopixI18N::get ('groupe|groupe.btn.addGroup'), 'size'=>140, 'type'=>'create');
         $tpl->assign ('MENU', $menu);

        $tplListe = new CopixTpl ();
        $tplListe->assign ('list', $groupes);
        $tplListe->assign ('canCreate', ($groupeService->canMakeInGroupe('ADD_GROUP',NULL) ? 1 : 0));
        $tplListe->assign ('reglettepages', CopixZone::process ('kernel|reglettepages', array('page'=>$page, 'nbPages'=>$nbPages, 'url'=>CopixUrl::get('groupe||getListPublic'))));
        $tplListe->assign ("kw", $kw);
        $tplListe->assign ('tagsCloud', $tagsCloud);

        $result = $tplListe->fetch("getlist.tpl");
        $this->addCss('styles/tag_cloud.css');

        $tpl->assign ("MAIN", $result);

        return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
    }



    /**
     * Formulaire de cr�ation / modification d'un groupe
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2005/11/15
     * @param integer $id Id du groupe qu'on modifie, ou NULL si cr�ation d'un nouveau groupe
     * @param string $titre (si formulaire soumis) Titre du groupe
     * @param string $description (si formulaire soumis) Sa description
     * @param integer $is_open (si formulaire soumis) 1 si le groupe est public, 0 s'il est priv�
     * @param string $membres (si formulaire soumis) La liste des logins des membres � ajouter (s�par�s par des virgules �ventuellement)
     * @param array $his_modules (si formulaire soumis) Les modules � rattacher au groupe
     * @param array $errors (option) Erreurs rencontr�es
     */
    public function processGetEdit ()
    {
        //$messages = $dao->getList();
        $kernel_service = & CopixClassesFactory::Create ('kernel|kernel');
        $groupeService = & CopixClassesFactory::Create ('groupe|groupeService');
        $dao = CopixDAOFactory::create("groupe");
        $tagService = $this->service('tagService');
        $tpl = new CopixTpl ();

        $id = $this->getRequest ('id', null);

        $titre = $description = $membres = NULL;
        $is_open = 1;
        $errors = array();
        $nodes = array();
        $tags = '';

        if ($id) {	// Modification
            $tplTitle = CopixI18N::get ('groupe|groupe.modify');
            $groupe = $dao->getGroupe($id);

            if ($groupe) {
                $mondroit = $kernel_service->getLevel( "CLUB", $id );
                if (!$groupeService->canMakeInGroupe('ADMIN',$mondroit))
                $errors[] = CopixI18N::get ('kernel|kernel.error.noRights');
                else {
                    $tags = $tagService->createTagsString($tagService->getTagsByGroup($id));
                    $titre = $groupe[0]->titre;
                    $description = $groupe[0]->description;
                    $is_open = $groupe[0]->is_open;
                    $parentClass = $parentRef = null;
                }
            } else
            $errors[] = CopixI18N::get ('groupe|groupe.error.noGroup');
        } else {
            $tplTitle = CopixI18N::get ('groupe|groupe.new');

            if (!$groupeService->canMakeInGroupe('ADD_GROUP',NULL))
            $errors[] = CopixI18N::get ('kernel|kernel.error.noRights');


            // On regarde a quoi on pourrait rattacher le groupe
            $nodes_all = Kernel::getNodeParents( _currentUser()->getExtra('type'), _currentUser()->getExtra('id') );
            foreach($nodes_all AS $node) {
                if( $node['type']=='CLUB' && CopixConfig::exists('kernel|groupeAssistance') && ($groupeAssistance=CopixConfig::get('kernel|groupeAssistance')) && $node['id']==$groupeAssistance) {
                    continue;
                }
                if ($node['type'] == 'BU_CLASSE' || $node['type'] == 'BU_ECOLE' || $node['type'] == 'BU_VILLE') {
                    $nodes[] = $node;
                }
            }

            $nbNodes = count($nodes);
            if ($nbNodes==1) {
                $parentClass = $nodes[0]['type'];
                $parentRef   = $nodes[0]['id'];
            } elseif ($nbNodes==0) { // Pas de parent pour la creation d'un groupe -> attache a ROOT/0  (FM 14/11/2008)
                $parentClass = "ROOT";
                $parentRef   = 0;
            } else { // Plus d'un noeud, on proposera le choix
                $parentClass = $parentRef = '';
            }
        }

        if ($errors) {
            return CopixActionGroup::process ('genericTools|Messages::getError', array ('message'=>implode('<br/>',$errors), 'back'=>CopixUrl::get('groupe||')));
        } else {

            $titre = $this->getRequest ('titre', $titre);
            $tags = $this->getRequest('tags', $tags);
            $description = $this->getRequest ('description', $description);
            $is_open = $this->getRequest ('is_open', $is_open);
            $membres = $this->getRequest ('membres', $membres);
            $his_modules = $this->getRequest ('his_modules', array());
            $parent = _request ('parent');

            if ($id) {	// Modif d'un groupe, on cherche ses modules
                $his_modules = array();
                $modEnabled = $kernel_service->getModEnabled ("club", $id);
                //print_r($modEnabled);
                foreach ($modEnabled as $tmp) {
                    $his_modules[$tmp->module_type] = 1;
                }
            }

            $errors = $this->getRequest ('errors', array());
            $kernel_service = CopixClassesFactory::create("kernel|Kernel");
            $modules = $kernel_service->getModAvailable ("club");
            foreach ($modules as $k=>$tmp) {
                if($tmp->module_type=='MOD_MAGICMAIL') { unset($modules[$k]); continue; }
                $modules[$k]->module_name = Kernel::Code2Name ($tmp->module_type);
                $modules[$k]->module_desc = Kernel::Code2Desc ($tmp->module_type);
            }

            $tpl->assign ('TITLE_PAGE', $tplTitle);

            $menu = array();
            if (0 && !$id) {
                if($groupeService->canMakeInGroupe('ADD_GROUP',NULL)) $menu[] = array('url' => CopixUrl::get ('groupe||getEdit'), 'txt'=>CopixI18N::get ('groupe|groupe.btn.addGroup'), 'size'=>140, 'type'=>'create', 'current'=>true);
                $menu[] = array('url' => CopixUrl::get ('groupe||getListPublic'), 'txt'=>CopixI18N::get ('groupe|groupe.annuaire'));
            }
            $tpl->assign ('MENU', $menu);
            $tplForm = new CopixTpl ();
            $tplForm->assign ("id", $id);
            $tplForm->assign ("titre", $titre);
            $tplForm->assign ("description", $description);
            $tplForm->assign ("is_open", $is_open);
            $tplForm->assign ("membres", $membres);
            $tplForm->assign ("modules", $modules);
            $tplForm->assign ("parentClass", $parentClass);
            $tplForm->assign ("parentRef", $parentRef);
            $tplForm->assign ("his_modules", $his_modules);
            $tplForm->assign ('linkpopup', CopixZone::process ('annuaire|linkpopup', array('field'=>'membres')));
            $tplForm->assign ("errors", $errors);
            $tplForm->assign ("nodes", $nodes);
            $tplForm->assign ("parent", $parent);
            $tplForm->assign ("tags", $tags);

            if( CopixConfig::exists('|can_group_createpublic') && CopixConfig::get('|can_group_createpublic') ) {
                $tplForm->assign ("can_group_createpublic", 1);
            } else {
                $tplForm->assign ("can_group_createpublic", 0);
            }

            $result = $tplForm->fetch("formedit.tpl");

            $tpl->assign ("MAIN", $result);

            return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
        }
    }


    /**
     * Soumission du formulaire de cr�ation / modification d'un groupe
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2005/11/15
     * @param integer $id Id du groupe en cas de modification, NULL si cr�ation d'un nouveau groupe
     * @param string $titre (si formulaire soumis) Titre du groupe
     * @param string $parentClass (si nouveau groupe) ParentClass o� sera rattach� le groupe
     * @param integer $parentRef (si nouveau groupe) ParentRef o� sera rattach� le groupe
     * @param string $titre Titre du groupe
     * @param string $description Sa description
     * @param integer $is_open 1 si le groupe est public, 0 s'il est priv�
     * @param string $membres Liste des logins des membres � ajouter (s�par�s par des virgules �ventuellement)
     * @param array $his_modules Les (nouveaux) modules � rattacher au groupe
     * @param array $errors (option) Erreurs rencontr�es
     */
    public function doEdit ()
    {
        $dao = CopixDAOFactory::create("groupe");
        $kernel_service = CopixClassesFactory::create("kernel|Kernel");
        $groupeService = CopixClassesFactory::create("groupe|groupeService");
        $tagService = $this->service('tagService');

        $errors = array();

        $id = $this->getRequest ('id', null);
        $parentClass = $this->getRequest ('parentClass', null);
        $parentRef = $this->getRequest ('parentRef', null);
        $parent = $this->getRequest ('parent', null);

        if ($id) {	// Modification
            $groupe = $dao->getGroupe($id);
            if ($groupe) {
                $mondroit = $kernel_service->getLevel( "CLUB", $id );
                if (!$groupeService->canMakeInGroupe('ADMIN',$mondroit))
                $errors[] = CopixI18N::get ('kernel|kernel.error.noRights');
            } else
            $errors[] = CopixI18N::get ('groupe|groupe.error.noGroup');
        } else {	// Nouveau groupe
            if (!$groupeService->canMakeInGroupe('ADD_GROUP',NULL))
            $errors[] = CopixI18N::get ('kernel|kernel.error.noRights');
            if (!$parentClass && !$parentRef && !$parent)	$errors[] = CopixI18N::get ('groupe|groupe.error.noParentClass');
        }

        //if ($errors)
        //return CopixActionGroup::process ('genericTools|Messages::getError', array ('message'=>implode('<br/>',$errors), 'back'=>'javascript:history.back();'));

        $titre = $this->getRequest ('titre', null);
        $description = $this->getRequest ('description', null);
        $is_open = $this->getRequest ('is_open', 1);
        $membres = $this->getRequest ('membres', null);
        $his_modules = $this->getRequest ('his_modules', array());
        $tags = $this->request('tags');


        if (!$titre)
            $errors[] = CopixI18N::get ('groupe|groupe.error.typeTitle');
        if (!$description)
            $errors[] = CopixI18N::get ('groupe|groupe.error.typeDesc');

        $createurId = _currentUser ()->getId();

        $dao = CopixDAOFactory::create("groupe");
        if ($id) {	// Modification d'un groupe
            $groupe[0]->titre = $titre;
            $groupe[0]->description = $description;
            $groupe[0]->is_open = $is_open;
            $dao->update ($groupe[0]);
        } else {	// Cr�ation

            $tab_membres = $membres;
            $tab_membres = str_replace(array(" "), "", $tab_membres);
            $tab_membres = str_replace(array(",",";"), ",", $tab_membres);
            $tab_membres = explode (",", $tab_membres);

            $tabInscrits = array();

            // On v�rifie que les membres existent
            $kernel_service = & CopixClassesFactory::Create ('kernel|kernel');
            while (list(,$login) = each ($tab_membres)) {
                if (!$login) continue;
                $userInfo = $kernel_service->getUserInfo("LOGIN", $login);
                //print_r("login=$login");
                //print_r($userInfo);
                if (!$userInfo)
                $errors[] = CopixI18N::get ('groupe|groupe.error.memberNoUser', array($login));
                elseif ($userInfo["user_id"] == $createurId)
                $errors[] = CopixI18N::get ('groupe|groupe.error.memberNotHimself');
                else
                $tabInscrits[$userInfo["user_id"]] = $userInfo["user_id"];
            }

            if ($parent) {
                list($parentClass, $parentRef) = explode('|', $parent);
            }

            if (!$errors) {
                $serv = CopixClassesFactory::create("GroupeService");
                $create = $serv->createGroupe ($titre, $description, $is_open, $createurId, $tabInscrits, $his_modules, $parentClass, $parentRef);
                if (!$create)
                    $errors[] = CopixI18N::get ('groupe|groupe.error.create');
                else
                    $id = $create;
            }
        }

        if ($errors)
            return CopixActionGroup::process('groupe|groupe::getEdit', array('id' => $id, 'titre' => $titre, 'description' => $description, 'is_open' => $is_open, 'membres' => $membres, 'his_modules' => $his_modules, 'errors' => $errors, 'parent' => $parent));
        else{
            $tagService->deleteTagsOgGroup($id);
            $tagService->addTagsToGroup($id, $tagService->extractTags($tags));
        }
        return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('groupe||getHomeAdmin', array("id"=>$id)));
    }



    /**
     * Suppression d'un groupe. Renvoie sur la page demandant confirmation avant de supprimer.
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2005/11/16
     * @see doDelete()
     * @param integer $id Id du groupe
     */
    public function getDelete ()
    {
        $dao = CopixDAOFactory::create("groupe");
        $kernel_service = & CopixClassesFactory::Create ('kernel|kernel');
        $groupeService = & CopixClassesFactory::Create ('groupe|groupeService');

        $errors = array();
        $id = $this->getRequest ('id', null);

        $groupe = $dao->getGroupe($id);

        if (!$groupe)
        $errors[] = CopixI18N::get ('groupe|groupe.error.noGroup');

        $mondroit = $kernel_service->getLevel( "CLUB", $id );
        if (!$groupeService->canMakeInGroupe('ADMIN', $mondroit))
        $errors[] = CopixI18N::get ('kernel|kernel.error.noRights');

        if ($errors) {
            return CopixActionGroup::process ('genericTools|Messages::getError', array ('message'=>implode('<br/>',$errors), 'back'=>CopixUrl::get('groupe||')));
        } else {

            return CopixActionGroup::process ('genericTools|Messages::getConfirm',
            array (
                    'title'=>$groupe[0]->titre,
                    'message'=>CopixI18N::get ('groupe|groupe.conf.groupAsk'),
                    'confirm'=>CopixUrl::get('groupe||doDelete', array('id'=>$id)),
                    'cancel'=>CopixUrl::get('groupe||getHomeAdmin', array('id'=>$id)),
          // 'MENU'=>'<a href="'.CopixUrl::get ('groupe||getHome', array("id"=>$id)).'">'.CopixI18N::get ('groupe|groupe.backHome').'</a> :: <a href="'.CopixUrl::get ('groupe||getHomeAdmin', array("id"=>$id)).'">'.CopixI18N::get ('groupe|groupe.backHomeAdmin').'</a>',
            )
            );
        }

    }


    /**
     * Suppression effective d'un groupe
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2005/11/16
     * @see getDelete()
     * @param integer $id Id du groupe
     */
    public function doDelete ()
    {
        $dao = CopixDAOFactory::create("groupe");
        $kernel_service = & CopixClassesFactory::Create ('kernel|kernel');
        $groupeService = & CopixClassesFactory::Create ('groupe|groupeService');

        $errors = array();
        $id = $this->getRequest ('id', null);

        $groupe = $dao->getGroupe($id);

        if (!$groupe)
        $errors[] = CopixI18N::get ('groupe|groupe.error.noGroup');

        $mondroit = $kernel_service->getLevel( "CLUB", $id );
        if (!$groupeService->canMakeInGroupe('ADMIN', $mondroit))
        $errors[] = CopixI18N::get ('kernel|kernel.error.noRights');

        if ($errors) {
            return CopixActionGroup::process ('genericTools|Messages::getError', array ('message'=>implode('<br/>',$errors), 'back'=>CopixUrl::get('groupe||')));
        } else {
            // On supprime ses modules
            $his_modules = Kernel::getModEnabled ("CLUB", $id);
            foreach ($his_modules as $k=>$node) {
                list(,$module) = explode("_",$node->module_type);
                $module = strtolower($module);
                $classeModule = & CopixClassesFactory::create($module.'|Kernel'.$module);
                if( method_exists( $classeModule, "delete" ) ) {
                    $classeModule->delete($node->module_id);
                }
            }

            // On supprime tous les noeuds
            $daoKernelModEnabled = CopixDAOFactory::create("kernel|kernel_mod_enabled");
            $daoKernelModEnabled->delByNode("CLUB", $id);

            // On supprime le rattachement du groupe
            $daoLinkGroupe2Node = CopixDAOFactory::create("kernel|kernel_link_groupe2node");
            $daoLinkGroupe2Node->delete ($id);

            // On supprime les membres/droits
            $childs = Kernel::getNodeChilds("CLUB", $id);
            foreach ($childs AS $user) {
                Kernel::setLevel("CLUB", $id, $user["type"], $user["id"], 0);
            }

            // On supprime le groupe lui-m�me
            $dao->delete ($id);

        }
        return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('groupe||getListMy'));
    }



    /**
     * Page d'accueil d'un groupe
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2005/11/15
     * @param integer $id Id du groupe
     */
    public function getHome ()
    {
        $kernel_service = & CopixClassesFactory::Create ('kernel|kernel');
        $groupeService = & CopixClassesFactory::Create ('groupe|groupeService');

        $id = $this->getRequest ('id', null);
        $errors = array();


        $dao = CopixDAOFactory::create("groupe");

        $groupe = $dao->getGroupe($id);
        if (!$groupe)
        $errors[] = CopixI18N::get ('groupe|groupe.error.noGroup');

        $mondroit = $kernel_service->getLevel( "CLUB", $id );
        //print_r("mondroit=$mondroit");
        if (!$groupeService->canMakeInGroupe('VIEW_HOME', $mondroit))
        $errors[] = CopixI18N::get ('kernel|kernel.error.noRights');

        if ($errors) {
            return CopixActionGroup::process ('genericTools|Messages::getError', array ('message'=>implode('<br/>',$errors), 'back'=>CopixUrl::get('groupe||')));
        } else {

            // On r�cup�re les modules du groupe
            $his_modules = $kernel_service->getModEnabled ("club", $id);
            foreach ($his_modules as $k=>$node) {
                //print_r(explode("_",$node->module_type));
                list($tmp,$module) = explode("_",$node->module_type);
                $module = strtolower($module);
                $classeModule = CopixClassesFactory::create("$module|Kernel$module");
                $infos = $classeModule->getStats($node->module_id);
                //print_r($infos);
                $his_modules[$k]->infos = $infos;
                $his_modules[$k]->module_nom = Kernel::Code2Name ($node->module_type);
            }

            // Infos du groupe
            $classeModule = CopixClassesFactory::create("groupe|Kernelgroupe");
            $infos = $classeModule->getStats($id);
            $groupe[0]->infos = $infos;
            $groupe[0]->nbMembers = $infos['nbMembers']['name'];
            $userInfo = $kernel_service->getUserInfo("ID", $groupe[0]->createur);
            $groupe[0]->createur_nom = $userInfo["prenom"]." ".$userInfo["nom"];
            $groupe[0]->createur_infos = $userInfo;

            //print_r($groupe[0]);
            $tpl = new CopixTpl ();
            $tpl->assign ('TITLE_PAGE', $groupe[0]->titre);
            // $tpl->assign ('MENU', '<a href="'.CopixUrl::get ('groupe||getListMy').'">'.CopixI18N::get ('groupe|groupe.backMy').'</a>');
            $menu = array();
            //$menu[] = array('url' => CopixUrl::get ('groupe||getListMy'), 'txt'=>CopixI18N::get ('groupe|groupe.backMy'), 'size'=>120);
            $tpl->assign ('MENU', $menu);

            $tplHome = new CopixTpl ();
            $tplHome->assign ('groupe', $groupe[0]);
            $tplHome->assign ('canViewAdmin', ($groupeService->canMakeInGroupe('ADMIN', $mondroit)) ? 1 : 0);
            $tplHome->assign ('canUnsubscribeHimself', ($groupeService->canMakeInGroupe('UNSUBSCRIBE_HIMSELF', $mondroit)) ? 1 : 0);

            $tplHome->assign ('his_modules', $his_modules);
            $result = $tplHome->fetch('gethome.tpl');
            $tpl->assign ('MAIN', $result);

            $plugStats = CopixPluginRegistry::get ("stats|stats");
            $plugStats->setParams(array('module_id'=>$groupe[0]->id));

            return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
        }

    }


    /**
     * Page d'accueil de l'administration d'un groupe
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2005/11/16
     * @param integer $id Id du groupe
     */
    public function getHomeAdmin ()
    {
        $kernel_service = & CopixClassesFactory::Create ('kernel|kernel');
        $groupeService = & CopixClassesFactory::Create ('groupe|groupeService');

        $id = $this->getRequest ('id', null);
        $errors = array();

        $dao = CopixDAOFactory::create("groupe");

        $groupe = $dao->getGroupe($id);
        if (!$groupe)
        $errors[] = CopixI18N::get ('groupe|groupe.error.noGroup');

        $mondroit = $kernel_service->getLevel( "CLUB", $id );
        if (!$groupeService->canMakeInGroupe('ADMIN', $mondroit)) {
            return CopixActionGroup::process ('groupe|groupe::getHomeMembers', array ('id'=>$id));
            // $errors[] = CopixI18N::get ('kernel|kernel.error.noRights');
        }

        if ($errors) {
            return CopixActionGroup::process ('genericTools|Messages::getError', array ('message'=>implode('<br/>',$errors), 'back'=>CopixUrl::get('groupe||')));
        }

        CopixHTMLHeader::addCSSLink (_resource("styles/module_groupe.css"));
        CopixHTMLHeader::addCSSLink (_resource("styles/module_groupe_admin.css"));

        $tpl = new CopixTpl ();
        $tpl->assign ('TITLE_PAGE', $groupe[0]->titre);
        // $tpl->assign ('MENU', '<a href="'.CopixUrl::get ('groupe||getHome', array("id"=>$id)).'">'.CopixI18N::get ('groupe|groupe.backHome').'</a>');

        $menu = array();
        // $menu[] = array('txt'=>CopixI18N::get ('groupe|groupe.backHome'), 'url' => CopixUrl::get ('groupe||getHome', array("id"=>$id)), 'size'=>100);
        $tpl->assign ('MENU', $menu);


        $tplHome = new CopixTpl ();
        $tplHome->assign ('groupe', $groupe[0]);

        $result = $tplHome->fetch('gethomeadmin.tpl');
        $tpl->assign ('MAIN', $result);

        return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);

    }


    /**
     * Administration des membres d'un groupe
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2005/11/16
     * @param integer $id Id du groupe
     * @param string $membres (option) Liste des logins des nouveaux membres � inscrire s�par�s par des virgules �ventuellement)
     * @param array $errors (option) Erreurs rencontr�es
     */
    public function processGetHomeAdminMembers ()
    {
        CopixHTMLHeader::addJSLink (_resource("js/jquery/jquery.ui.datepicker-fr.js"));

        $dao = CopixDAOFactory::create("groupe");
        $kernel_service = & CopixClassesFactory::Create ('kernel|kernel');
        $groupeService = & CopixClassesFactory::Create ('groupe|groupeService');

        $id = $this->getRequest ('id', null);
        $errors = $this->getRequest ('errors', array());
        $membres = $this->getRequest ('membres', null);
        $debut = $this->getRequest ('debut', null);
        $fin = $this->getRequest ('fin', null);
        $debutW = $this->getRequest ('debutW', null);
        $finW = $this->getRequest ('finW', null);
        $membresW = $this->getRequest ('membresW', null);
        $critical_errors = array();

        $groupe = $dao->getGroupe($id);
        if (!$groupe)
        $critical_errors[] = CopixI18N::get ('groupe|groupe.error.noGroup');

        $mondroit = $kernel_service->getLevel( "CLUB", $id );
        if (!$groupeService->canMakeInGroupe('ADMIN', $mondroit))
        $critical_errors[] = CopixI18N::get ('kernel|kernel.error.noRights');

        if ($critical_errors) {
            return CopixActionGroup::process ('genericTools|Messages::getError', array ('message'=>implode('<br/>',$critical_errors), 'back'=>CopixUrl::get('groupe||')));
        } else {

            CopixHTMLHeader::addCSSLink (_resource("styles/module_groupe_admin.css"));

            $tpl = new CopixTpl ();
            $tpl->assign ('BODY_ON_LOAD', "setDatePicker('#debut,#fin,#debutW,#finW')");
            $tpl->assign ('TITLE_PAGE', $groupe[0]->titre.' - '.CopixI18N::get ('groupe|groupe.adminMembers'));
            // $tpl->assign ('MENU', '<a href="'.CopixUrl::get ('groupe||getHome', array("id"=>$id)).'">'.CopixI18N::get ('groupe|groupe.backHome').'</a> :: <a href="'.CopixUrl::get ('groupe||getHomeAdmin', array("id"=>$id)).'">'.CopixI18N::get ('groupe|groupe.backHomeAdmin').'</a>');

            $menu = array();
            // $menu[] = array('txt'=>CopixI18N::get ('groupe|groupe.backHome'), 'url' => CopixUrl::get ('groupe||getHome', array("id"=>$id)), 'size'=>100);
            //$menu[] = array('txt'=>CopixI18N::get ('groupe|groupe.backHomeAdmin'), 'url' => CopixUrl::get ('groupe||getHomeAdmin', array("id"=>$id)), 'size'=>190);
            $tpl->assign ('MENU', $menu);

            $childs = $kernel_service->getNodeChilds( "CLUB", $id );
            $waiting = array();
            foreach ($childs AS $k=>$v) {
                //print_r($v);
                $userInfo = $kernel_service->getUserInfo($v["type"], $v["id"]);
        //var_dump($userInfo);
                $childs[$k]["login"] = $userInfo["login"];
                $childs[$k]["nom"] = $userInfo["nom"];
                $childs[$k]["prenom"] = $userInfo["prenom"];
                $childs[$k]["droitnom"] = $groupeService->getRightName($v['droit']);

                if ($v['droit']==PROFILE_CCV_SHOW) {	// Membre en attente
                    //$childs[$k]['value'] =
                    $waiting[] = $childs[$k];
                    unset($childs[$k]);
                }
            }
            //print_r($childs);
            //      print_r($waiting);


            $tplHome = new CopixTpl ();
            $tplHome->assign ('groupe', $groupe[0]);
            $tplHome->assign ('his', _currentUser()->getExtra('type').'|'._currentUser()->getExtra('id'));
            $tplHome->assign ('list', $childs);
            $tplHome->assign ('today', date('Ymd'));
            $tplHome->assign ('listWaiting', $waiting);
            $tplHome->assign ('errors', $errors);
            $tplHome->assign ('membres', $membres);
            $tplHome->assign ('debut', $debut);
            $tplHome->assign ('fin', $fin);
            $tplHome->assign ('debutW', $debutW);
            $tplHome->assign ('finW', $finW);
            $tplHome->assign ('membresW', $membresW);
            $tplHome->assign ('linkpopup', CopixZone::process ('annuaire|linkpopup', array('field'=>'membres')));

            $result = $tplHome->fetch('gethomeadminmembers.tpl');
            $tpl->assign ('MAIN', $result);

            return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
        }

    }


    /**
     * Administration des modules d'un groupe
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2005/11/16
     * @see doFormAdminModules()
     * @param integer $id Id du groupe
     * @param integer $done Si 1, on vient d'une page ayant bien effectu� les modifications demand�es
     * @param array $errors (option) Erreurs rencontr�es
     */
    public function getHomeAdminModules ()
    {
        $dao = CopixDAOFactory::create("groupe");
        $kernel_service = & CopixClassesFactory::Create ('kernel|kernel');
        $groupeService = & CopixClassesFactory::Create ('groupe|groupeService');

        $id = $this->getRequest ('id', null);
        $done = ($id) ? $this->getRequest ('done', null) : null;
        $errors = $this->getRequest ('errors', array());
        $critical_errors = array();

        $groupe = $dao->getGroupe($id);
        if (!$groupe)
        $critical_errors[] = CopixI18N::get ('groupe|groupe.error.noGroup');


        $mondroit = $kernel_service->getLevel( "CLUB", $id );
        if (!$groupeService->canMakeInGroupe('ADMIN', $mondroit))
        $critical_errors[] = CopixI18N::get ('kernel|kernel.error.noRights');

        if ($critical_errors) {
            return CopixActionGroup::process ('genericTools|Messages::getError', array ('message'=>implode('<br/>',$critical_errors), 'back'=>CopixUrl::get('groupe||')));
        } else {

            // Tous les modules possibles
            $modules = $kernel_service->getModAvailable ("club");
            while (list($k,$tmp) = each($modules)) {
                if($tmp->module_type=='MOD_MAGICMAIL') { unset($modules[$k]); continue; }
                $modules[$k]->module_name = Kernel::Code2Name ($tmp->module_type);
                $modules[$k]->module_desc = Kernel::Code2Desc ($tmp->module_type);
            }
            //print_r($modules);

            // Ses modules
            $his_modules = array();
            $modEnabled = $kernel_service->getModEnabled ("club", $id);
            //print_r($modEnabled);
            foreach ($modEnabled as $tmp) {
                $his_modules[$tmp->module_type] = 1;
            }
            //print_r($his_modules);

            CopixHTMLHeader::addCSSLink (_resource("styles/module_groupe_admin.css"));

            $tpl = new CopixTpl ();
            $tpl->assign ('TITLE_PAGE', $groupe[0]->titre.' - '.CopixI18N::get ('groupe|groupe.adminModules'));
            // $tpl->assign ('MENU', '<a href="'.CopixUrl::get ('groupe||getHome', array("id"=>$id)).'">'.CopixI18N::get ('groupe|groupe.backHome').'</a> :: <a href="'.CopixUrl::get ('groupe||getHomeAdmin', array("id"=>$id)).'">'.CopixI18N::get ('groupe|groupe.backHomeAdmin').'</a>');

            $menu = array();
            // $menu[] = array('url' => CopixUrl::get ('groupe||getHome', array("id"=>$id)), 'txt'=>CopixI18N::get ('groupe|groupe.backHome'), 'size'=>100);
            // $menu[] = array('url' => CopixUrl::get ('groupe||getHomeAdmin', array("id"=>$id)), 'txt'=>CopixI18N::get ('groupe|groupe.backHomeAdmin'), 'size'=>195);
            $tpl->assign ('MENU', $menu);

            $tplForm = new CopixTpl ();
            $tplForm->assign ('id', $id);
            $tplForm->assign ('done', $done);
            $tplForm->assign ('groupe', $groupe[0]);
            $tplForm->assign ("modules", $modules);
            $tplForm->assign ("his_modules", $his_modules);
            $tplForm->assign ('errors', $errors);

            $result = $tplForm->fetch('gethomeadminmodules.tpl');
            $tpl->assign ('MAIN', $result);

            return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
        }

    }

    public function getHomeAdminMagicmail()
    {
        $kernel_service = & CopixClassesFactory::Create ('kernel|kernel');
        $groupeService = & CopixClassesFactory::Create ('groupe|groupeService');

        $id = $this->getRequest ('id', null);
        if( $id>0 ) {
            $mondroit = $kernel_service->getLevel( "CLUB", $id );
            if ($groupeService->canMakeInGroupe('ADMIN', $mondroit)) {
                die('ok');
            }
        }
    }


    /**
     * D�sinscription effective de membres
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2005/11/16
     * @param integer $id Id du groupe
     * @param array $membres Les membres � d�sinscrire (les valeurs sont de type USER_TYPE|USER_ID)
     */
    public function doUnsubscribe ()
    {
        $dao = CopixDAOFactory::create("groupe");
        $kernel_service = & CopixClassesFactory::Create ('kernel|kernel');
        $groupeService = & CopixClassesFactory::Create ('groupe|groupeService');

        $id = $this->getRequest ('id', null);
        $membres = $this->getRequest ('membres', array());
        $errors = array();

        $groupe = $dao->getGroupe($id);
        if (!$groupe)
        $errors[] = CopixI18N::get ('groupe|groupe.error.noGroup');

        $mondroit = $kernel_service->getLevel( "CLUB", $id );
        if (!$groupeService->canMakeInGroupe('ADMIN', $mondroit))
        $errors[] = CopixI18N::get ('kernel|kernel.error.noRights');

        if (in_array(_currentUser()->getExtra('type')."|"._currentUser()->getExtra('id'), $membres))
        $errors[] = CopixI18N::get ('groupe|groupe.error.memberNotHimselfUnsub');


        if ($errors) {
            return CopixActionGroup::process ('genericTools|Messages::getError', array ('message'=>implode('<br/>',$errors), 'back'=>CopixUrl::get('groupe||')));
        } else {

            foreach($membres as $membre) {
                list ($user_type,$user_id) = explode ("|", $membre);
                if ($user_type && $user_id) {
                    //print ("user_type=$user_type / user_id=$user_id");
                    $kernel_service->setLevel("CLUB", $id, $user_type, $user_id, 0);
                    CopixCache::clear ($user_type.'-'.$user_id, 'getnodeparents');
                    CopixCache::clear ($user_type.'-'.$user_id, 'getmynodes');
                }
            }

            $back = CopixUrl::get ('groupe||getHomeAdminMembers', array("id"=>$id));
            return new CopixActionReturn (COPIX_AR_REDIRECT, $back);
        }
    }


    /**
     * Inscription directe et effective de membres, � partir de leurs logins
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2005/11/16
     * @param integer $id Id du groupe
     * @param array $membres Les logins des membres � inscrire (s�par�s par des , ou ; si plusieurs)
     */
    public function doSubscribe ()
    {
        $dao = CopixDAOFactory::create("groupe");
        $kernel_service = & CopixClassesFactory::Create ('kernel|kernel');
        $groupeService = & CopixClassesFactory::Create ('groupe|groupeService');

        $id = $this->getRequest ('id', null);
        $membres = $this->getRequest ('membres', null);
        $errors = array();

        $req_debut = $this->getRequest ('debut', null);
        $req_fin = $this->getRequest ('fin', null);

        //$debut   = dateService::dateFrToDateBdd($req_debut);
        //$fin     = dateService::dateFrToDateBdd($req_fin);
        $debut     = CopixDateTime::dateToTimestamp($req_debut);
        $fin     = CopixDateTime::dateToTimestamp($req_fin);

        $groupe = $dao->getGroupe($id);
        if (!$groupe)
        $errors[] = CopixI18N::get ('groupe|groupe.error.noGroup');

        $mondroit = $kernel_service->getLevel( "CLUB", $id );
        if (!$groupeService->canMakeInGroupe('ADMIN', $mondroit))
        $errors[] = CopixI18N::get ('kernel|kernel.error.noRights');

        if ($errors) {
            return CopixActionGroup::process ('genericTools|Messages::getError', array ('message'=>implode('<br/>',$errors), 'back'=>CopixUrl::get('groupe||')));
        } else {

            //print_r($groupe);

            //print_r($membres);
            $tab_membres = $membres;
            $tab_membres = str_replace(array(" "), "", $tab_membres);
            $tab_membres = str_replace(array(",",";"), ",", $tab_membres);
            $tab_membres = explode (",", $tab_membres);

            $tabInscrits = array();
            // On v�rifie que les membres existent
            while (list(,$login) = each ($tab_membres)) {
                if (!$login) continue;
                $userInfo = $kernel_service->getUserInfo("LOGIN", $login, array('strict'=>true));
                //print_r("login=$login");
                //print_r($userInfo);
                if (!$userInfo)
                $errors[] = CopixI18N::get ('groupe|groupe.error.memberNoUser', array($login));
                elseif ($userInfo["user_id"] == $groupe[0]->createur)
                $errors[] = CopixI18N::get ('groupe|groupe.error.memberNotHimself');
                else {	// On regarde s'il est d�j� membre
                    $droit = $kernel_service->getLevel( "CLUB", $id, $userInfo["type"], $userInfo["id"]);
                    if ($droit)
                    $errors[] = CopixI18N::get ('groupe|groupe.error.memberAlready', array($login));
                    else	// OK
                    $tabInscrits[] = $userInfo;
                }
            }

            // Tests sur les dates
            if ($req_debut) {
                if (CopixDateTime::timestampToDate ($debut) === false)
                $errors[] = CopixI18N::get('groupe|groupe.error.formdatedeb');
            }
            if ($req_fin) {
                if (CopixDateTime::timestampToDate ($fin) === false)
                $errors[] = CopixI18N::get('groupe|groupe.error.formdatefin');
            }
            if ($req_debut && $req_fin && $debut && $fin && $debut>$fin){
                $errors[] = CopixI18N::get('groupe|groupe.error.inversiondate');
            }

            if ($errors) {
                return CopixActionGroup::process ('groupe|groupe::getHomeAdminMembers', array ('id'=>$id, 'membres'=>$membres, 'debut'=>$req_debut, 'fin'=>$req_fin, 'errors'=>$errors));

            } else {
                // On ins�re les �ventuels membres

                while (list(,$user) = each ($tabInscrits)) {
                    $kernel_service->setLevel("CLUB", $id, $user["type"], $user["id"], PROFILE_CCV_MEMBER, $debut, $fin);
                    CopixCache::clear ($user["type"].'-'.$user["id"], 'getnodeparents');
                    CopixCache::clear ($user["type"].'-'.$user["id"], 'getmynodes');
                }

                $back = CopixUrl::get ('groupe||getHomeAdminMembers', array("id"=>$id));
                return new CopixActionReturn (COPIX_AR_REDIRECT, $back);

            }

        }
    }


    /**
     * Traitement des inscriptions en attente
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2005/11/16
     * @param integer $id Id du groupe
     * @param array $membres Les logins des membres � inscrire (s�par�s par des , ou ; si plusieurs)
     */
    public function doSubscribeWaiting ()
    {
        $dao = CopixDAOFactory::create("groupe");
        $kernel_service = & CopixClassesFactory::Create ('kernel|kernel');
        $groupeService = & CopixClassesFactory::Create ('groupe|groupeService');
        $minimailService = & CopixClassesFactory::Create ('minimail|minimailService');

        $req_debut	= $this->getRequest ('debutW', null);
        $req_fin 		= $this->getRequest ('finW', null);

        //$debutW     = dateService::dateFrToDateBdd($req_debut);
        //$finW     	= dateService::dateFrToDateBdd($req_fin);
        $debutW     = CopixDateTime::dateToTimestamp($req_debut);
        $finW     	= CopixDateTime::dateToTimestamp($req_fin);

        $id = $this->getRequest ('id', null);
        $membres = $this->getRequest ('membres', null);
        $errors = array();

        $groupe = $dao->getGroupe($id);
        if (!$groupe)
        $errors[] = CopixI18N::get ('groupe|groupe.error.noGroup');

        $mondroit = $kernel_service->getLevel( "CLUB", $id );
        if (!$groupeService->canMakeInGroupe('ADMIN', $mondroit))
        $errors[] = CopixI18N::get ('kernel|kernel.error.noRights');

        if ($errors) {
            return CopixActionGroup::process ('genericTools|Messages::getError', array ('message'=>implode('<br/>',$errors), 'back'=>CopixUrl::get('groupe||')));
        } else {

            // Tests sur les dates
            if ($req_debut) {
                if (CopixDateTime::timestampToDate ($debutW) === false)
                $errors[] = CopixI18N::get('groupe|groupe.error.formdatedeb');
            }
            if ($req_fin) {
                if (CopixDateTime::timestampToDate ($finW) === false)
                $errors[] = CopixI18N::get('groupe|groupe.error.formdatefin');
            }
            if ($req_debut && $req_fin && $debutW && $finW && $debutW>$finW){
                $errors[] = CopixI18N::get('groupe|groupe.error.inversiondate');
            }

            if ($errors) {
                return CopixActionGroup::process ('groupe|groupe::getHomeAdminMembers', array ('id'=>$id, 'membresW'=>$membres, 'debutW'=>$req_debut, 'finW'=>$req_fin, 'errors'=>$errors));
            }

            foreach($membres as $membre=>$action) {
                list ($user_type,$user_id) = explode ("|", $membre);
                //$hisDroit = Kernel::getLevel( "CLUB", $id, $user_type, $user_id);
                //print_r("user_type=$user_type / user_id=$user_id");
                //continue;

                if ($action == 'WAIT') {
                    continue;
                } elseif ($action == '0') {	// On ne l'accepte pas
                    $kernel_service->setLevel("CLUB", $id, $user_type, $user_id, 0);
                    CopixCache::clear ($user_type.'-'.$user_id, 'getnodeparents');
                    CopixCache::clear ($user_type.'-'.$user_id, 'getmynodes');
                    // On l'informe par minimail
                    $userInfo = Kernel::getUserInfo($user_type, $user_id);
                    $his_nom = _currentUser()->getExtra('prenom')." "._currentUser()->getExtra('nom');
                    $msg_from_id = _currentUser ()->getId();
                    $msg_from_login = _currentUser()->getLogin();
                    $msg_title = CopixI18N::get ('groupe|groupe.msgJoin.nok.title', array($groupe[0]->titre));
          $format = CopixConfig::get ('minimail|default_format');
          if ($format == 'dokuwiki' || $format == 'wiki') {
                      $msg_body = CopixI18N::get ('groupe|groupe.msgJoin.nok.body', array($groupe[0]->titre, $his_nom));
                      $msg_body = str_replace("\\n", "\n", $msg_body);
          } else
                      $msg_body = CopixI18N::get ('groupe|groupe.msgJoin.nok.bodyHtml', array($groupe[0]->titre, $his_nom));
                    $msg_destin = array($userInfo["user_id"]=>1);
                    $minimailService->sendMinimail ($msg_title, $msg_body, $msg_from_id, $msg_destin, $format);
                } elseif ($action == '1') {	// On l'accepte !
                    $kernel_service->setLevel("CLUB", $id, $user_type, $user_id, 0);
                    $kernel_service->setLevel("CLUB", $id, $user_type, $user_id, PROFILE_CCV_MEMBER, $debutW, $finW);
                    CopixCache::clear ($user_type.'-'.$user_id, 'getnodeparents');
                    CopixCache::clear ($user_type.'-'.$user_id, 'getmynodes');
                    // On l'informe par minimail
                    $userInfo = Kernel::getUserInfo($user_type, $user_id);
                    $his_nom = _currentUser()->getExtra('prenom')." "._currentUser()->getExtra('nom');
                    $msg_from_id = _currentUser ()->getId();
                    $msg_from_login = _currentUser()->getLogin();
                    $msg_title = CopixI18N::get ('groupe|groupe.msgJoin.ok.title', array($groupe[0]->titre));
          $format = CopixConfig::get ('minimail|default_format');
          if ($format == 'dokuwiki' || $format == 'wiki') {
                      $msg_body = CopixI18N::get ('groupe|groupe.msgJoin.ok.body', array($groupe[0]->titre, $his_nom));
                      if ($debutW && $finW)
                        $msg_body .= CopixI18N::get ('groupe|groupe.msgJoin.ok.bodyDebutFin', array(CopixDateTime::timestampToDate($debutW), CopixDateTime::timestampToDate($finW), CopixUrl::get('groupe||getHomeAdmin', array("id"=>$id))));
                      elseif ($debutW)
                        $msg_body .= CopixI18N::get ('groupe|groupe.msgJoin.ok.bodyDebut', array(CopixDateTime::timestampToDate($debutW), CopixUrl::get('groupe||getHomeAdmin', array("id"=>$id))));
                      elseif ($finW)
                        $msg_body .= CopixI18N::get ('groupe|groupe.msgJoin.ok.bodyFin', array(CopixDateTime::timestampToDate($finW), CopixUrl::get('groupe||getHomeAdmin', array("id"=>$id))));
                      else
                        $msg_body .= CopixI18N::get ('groupe|groupe.msgJoin.ok.bodyIllimite', array(CopixUrl::get('groupe||getHomeAdmin', array("id"=>$id))));
                      $msg_body = str_replace("\\n", "\n", $msg_body);
          } else { // Format HTML
                      $msg_body = CopixI18N::get ('groupe|groupe.msgJoin.ok.bodyHtml', array($groupe[0]->titre, $his_nom));
                      if ($debutW && $finW)
                        $msg_body .= CopixI18N::get ('groupe|groupe.msgJoin.ok.bodyDebutFinHtml', array(CopixDateTime::timestampToDate($debutW), CopixDateTime::timestampToDate($finW), CopixUrl::get('groupe||getHomeAdmin', array("id"=>$id))));
                      elseif ($debutW)
                        $msg_body .= CopixI18N::get ('groupe|groupe.msgJoin.ok.bodyDebutHtml', array(CopixDateTime::timestampToDate($debutW), CopixUrl::get('groupe||getHomeAdmin', array("id"=>$id))));
                      elseif ($finW)
                        $msg_body .= CopixI18N::get ('groupe|groupe.msgJoin.ok.bodyFinHtml', array(CopixDateTime::timestampToDate($finW), CopixUrl::get('groupe||getHomeAdmin', array("id"=>$id))));
                      else
                        $msg_body .= CopixI18N::get ('groupe|groupe.msgJoin.ok.bodyIllimiteHtml', array(CopixUrl::get('groupe||getHomeAdmin', array("id"=>$id))));


          }

                    $msg_destin = array($userInfo["user_id"]=>1);
                    $minimailService->sendMinimail ($msg_title, $msg_body, $msg_from_id, $msg_destin, $format);
                }
            }

            $back = CopixUrl::get ('groupe||getHomeAdminMembers', array("id"=>$id));
            return new CopixActionReturn (COPIX_AR_REDIRECT, $back);

        }
    }


    /**
     * Effectue une demande d'inscription � un groupe pour l'utilisateur courant
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2005/11/16
     * @param integer $id Id du groupe
     */
    public function doJoin ()
    {
        if (!Kernel::is_connected()) return CopixActionGroup::process ('genericTools|Messages::getError', array ('message'=>CopixI18N::get ('kernel|kernel.error.nologin'), 'back'=>CopixUrl::get ('auth|default|login')));

        $dao = CopixDAOFactory::create("groupe");
        $kernel_service = & CopixClassesFactory::Create ('kernel|kernel');
        $minimail_service = & CopixClassesFactory::Create ('minimail|minimailservice');
        $groupeService = & CopixClassesFactory::Create ('groupe|groupeService');

        $id = $this->getRequest ('id', null);
        $errors = $oks = array();

        $groupe = $dao->getGroupe($id);
        if (!$groupe)
        $errors[] = CopixI18N::get ('groupe|groupe.error.noGroup');
        elseif (!$groupe[0]->is_open)
        $errors[] = CopixI18N::get ('groupe|groupe.error.private');

        $mondroit = $kernel_service->getLevel( "CLUB", $id );
        if ($mondroit)
        $errors[] = CopixI18N::get ('groupe|groupe.error.alreadyMember');

        if ($errors) {
            if( CopixConfig::exists('|can_group_showlist') && CopixConfig::get('|can_group_showlist') ) {
            return CopixActionGroup::process ('genericTools|Messages::getError', array ('message'=>implode('<br/>',$errors), 'back'=>CopixUrl::get('groupe||getListPublic')));
            } else {
            return CopixActionGroup::process ('genericTools|Messages::getError', array ('message'=>implode('<br/>',$errors), 'back'=>CopixUrl::get('||')));
            }
        } else {

            $kernel_service->setLevel("CLUB", $id, _currentUser()->getExtra('type'), _currentUser()->getExtra('id'), PROFILE_CCV_SHOW);
            CopixCache::clear (_currentUser()->getExtra('type').'-'._currentUser()->getExtra('id'), 'getnodeparents');
            CopixCache::clear (_currentUser()->getExtra('type').'-'._currentUser()->getExtra('id'), 'getmynodes');

            // On r�cup�re le propri�taire, afin de lui envoyer un message
            $childs = $kernel_service->getNodeChilds( "CLUB", $id );
            $trouve = false;

            foreach ($childs as $child) {
                if ($trouve)
                continue;
                if ($groupeService->canMakeInGroupe('ADMIN',$child["droit"])) // Propri�taire trouv� !
                $trouve = $child;
            }
            //var_dump($trouve);
            /*
            while (!$trouve && list(,$child)=each($childs)) {
            if ($groupeService->canMakeInGroupe('ADMIN',$child["droit"])) // Propri�taire trouv� !
            $trouve = $child;
            }
            */

            if ($trouve) {
                $userInfo = $kernel_service->getUserInfo($trouve["type"], $trouve["id"]);
                //print_r2($userInfo);
                if ($userInfo && $userInfo["user_id"]) {

                    $his_nom = _currentUser()->getExtra('prenom')." "._currentUser()->getExtra('nom')." ("._currentUser()->getExtra('type').")";

                    $msg_from_login = _currentUser()->getLogin();
                    $msg_title = CopixI18N::get ('groupe|groupe.msgJoin.title', array($groupe[0]->titre));
          $format = CopixConfig::get ('minimail|default_format');
          if ($format == 'dokuwiki' || $format == 'wiki') {
                      $msg_body = CopixI18N::get ('groupe|groupe.msgJoin.body', array(CopixUrl::get('groupe||getHomeAdminMembers', array("id"=>$id)),$his_nom));
                      $msg_body = str_replace("\\n", "\n", $msg_body);
          } else
                      $msg_body = CopixI18N::get ('groupe|groupe.msgJoin.bodyHtml', array(CopixUrl::get('groupe||getHomeAdminMembers', array("id"=>$id)),$his_nom));

                    //print_r($msg_body);
                    //die();
                    $msg_from_id = _currentUser ()->getId();
                    $msg_destin = array($userInfo["user_id"]=>1);

                    $send = $minimail_service->sendMinimail ($msg_title, $msg_body, $msg_from_id, $msg_destin, $format);
                    if ($send)
                    $oks[] = CopixI18N::get ('groupe|groupe.msgJoin.Ok');
                    else
                    $errors[] = CopixI18N::get ('groupe|groupe.error.sendJoin');


                } else
                $errors[] = CopixI18N::get ('groupe|groupe.error.owner');
            } else { // Pas de proprio, c'est pas normal
                $errors[] = CopixI18N::get ('groupe|groupe.error.noOwner');
            }

            $tpl = new CopixTpl ();
            $tpl->assign ('TITLE_PAGE', $groupe[0]->titre);
            // $tpl->assign ('MENU', '<a href="'.CopixUrl::get ('groupe||getListPublic').'">'.CopixI18N::get ('groupe|groupe.annuaire').'</a> :: <a href="'.CopixUrl::get ('groupe||getListMy').'">'.CopixI18N::get ('groupe|groupe.my').'</a>');

            $tplHome = new CopixTpl ();
            $tplHome->assign ('groupe', $groupe[0]);
            $tplHome->assign ('errors', $errors);
            $tplHome->assign ('oks', $oks);

            if( CopixConfig::exists('|can_group_showlist') && CopixConfig::get('|can_group_showlist') ) {
                $tplHome->assign ('can_group_showlist', 1);
            } else {
                $tplHome->assign ('can_group_showlist', 0);
            }

            $result = $tplHome->fetch('dojoin.tpl');
            $tpl->assign ('MAIN', $result);

            return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
        }

    }


    /**
     * Soumission du formulaire d'administration des modules
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2005/11/16
     * @see getHomeAdminModules()
     * @param integer $id Id du groupe
     * @param array $his_modules Les modules � ajouter. Le tableau contient dont les clefs correspondent aux codes des modules
     */
    public function doFormAdminModules ()
    {
        $dao = CopixDAOFactory::create("groupe");
        $kernel_service = & CopixClassesFactory::Create ('kernel|kernel');
        $groupeService = & CopixClassesFactory::Create ('groupe|groupeService');

        $id = $this->getRequest ('id', null);
        $his_modules = $this->getRequest ('his_modules', array());
        $errors = array();

        $groupe = $dao->getGroupe($id);
        if (!$groupe)
        $errors[] = CopixI18N::get ('groupe|groupe.error.noGroup');

        $mondroit = $kernel_service->getLevel( "CLUB", $id );
        if (!$groupeService->canMakeInGroupe('ADMIN', $mondroit))
        $errors[] = CopixI18N::get ('kernel|kernel.error.noRights');


        if ($errors) {
            return CopixActionGroup::process ('genericTools|Messages::getError', array ('message'=>implode('<br/>',$errors), 'back'=>CopixUrl::get('groupe||')));
        } else {

            // Tous les modules dispos
            $modules = $kernel_service->getModAvailable ("club");
            //print_r($modules);

            // On cherche ses modules
            $mod_enabled = $kernel_service->getModEnabled ("club", $id);

            // On ajoute les modules
            //print_r($his_modules);

            // On parcourt l'ensemble des modules ayant pu �tre coch�s/d�coch�s
            foreach ($modules as $tmp) {

                $moduleType = $tmp->module_type;
                list (,$module) = explode ("_", strtolower($moduleType));

                // On v�rifie quand m�me qu'un module de ce type n'existe pas d�j�
                reset ($mod_enabled);
                $deja = false;
                while (!$deja && list(,$mod) = each ($mod_enabled))
                $deja = (($mod->module_type == $moduleType) ? $mod->module_id : false);

                // print_r("<br/>moduleType=$moduleType / deja=$deja / ");


                if ($deja && (!isset($his_modules[$moduleType]) || !$his_modules[$moduleType])) { // Cocher -> d�cocher, on supprime le module
                    //print_r("Del");
                    $classeDel = CopixClassesFactory::create("$module|Kernel$module");
                    $del = $classeDel->delete($deja);
                    if ($del) {	// Suppression effectu�e, on d�tache le module du groupe
                        $unregister = $kernel_service->unregisterModule( $moduleType, $deja, "CLUB", $id );
                    }
                } elseif (!$deja && isset($his_modules[$moduleType])) { // D�cocher -> cocher, on instancie le module
                    // print_r("Add");
                    $classeNew = CopixClassesFactory::create("$module|Kernel$module");
                    $new = $classeNew->create(array('title'=>$groupe[0]->titre, 'node_type'=>'CLUB', 'node_id'=>$id));
                    // print_r("new=$new");
                    if ($new) {	// Module bien cr�e, on le rattache
                        $register = $kernel_service->registerModule( $moduleType, $new, "CLUB", $id );
                        // print_r("new=$new / register=$register");
                    }
                } else {	// Pas de changement
                    //print_r("Rien");
                }
            }

            $back = CopixUrl::get ('groupe||getHomeAdminModules', array("id"=>$id, "done"=>1));
            return new CopixActionReturn (COPIX_AR_REDIRECT, $back);
        }
    }

    /**
     * Affichage des membres d'un groupe
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2006/02/21
     * @param integer $id Id du groupe
     */
    public function processGetHomeMembers ()
    {
        $dao = CopixDAOFactory::create("groupe");
        $kernel_service = & CopixClassesFactory::Create ('kernel|kernel');
        $groupeService = & CopixClassesFactory::Create ('groupe|groupeService');

        $id = $this->getRequest ('id', null);
        $critical_errors = array();

        $groupe = $dao->getGroupe($id);
        if (!$groupe)
        $critical_errors[] = CopixI18N::get ('groupe|groupe.error.noGroup');

        $mondroit = $kernel_service->getLevel( "CLUB", $id );
        if (!$groupeService->canMakeInGroupe('VIEW_HOME', $mondroit))
        $critical_errors[] = CopixI18N::get ('kernel|kernel.error.noRights');

        if ($critical_errors) {
            return CopixActionGroup::process ('genericTools|Messages::getError', array ('message'=>implode('<br/>',$critical_errors), 'back'=>CopixUrl::get('groupe||')));
        } else {

            $tpl = new CopixTpl ();
            $tpl->assign ('TITLE_PAGE', $groupe[0]->titre.' - '.CopixI18N::get ('groupe|groupe.group.members'));
            // $tpl->assign ('MENU', '<a href="'.CopixUrl::get ('groupe||getHome', array("id"=>$id)).'">'.CopixI18N::get ('groupe|groupe.backHome').'</a>');

            $menu = array();
            // $menu[] = array('url' => CopixUrl::get ('groupe||getHome', array("id"=>$id)), 'txt'=>CopixI18N::get ('groupe|groupe.backHome'), 'size'=>100);
            $tpl->assign ('MENU', $menu);


            $childs = $kernel_service->getNodeChilds( "CLUB", $id );
            foreach ($childs AS $k=>$v) {

                $ok = true;
                if ($v['debut'] && $v['debut']>date("Ymd")) $ok = false;
                if ($v['fin']   && $v['fin']  <date("Ymd")) $ok = false;

                if (!$ok || !$groupeService->canMakeInGroupe('VIEW_HOME', $v['droit'])) {	// Membre en attente ou dont l'inscription n'est pas valide
                    unset($childs[$k]);
                    continue;
                }

                //print_r($v);
                $userInfo = $kernel_service->getUserInfo($v["type"], $v["id"]);
                $childs[$k]["login"] = $userInfo["login"];
                $childs[$k]["nom"] = $userInfo["nom"];
                $childs[$k]["prenom"] = $userInfo["prenom"];
                $childs[$k]["type"] = $userInfo["type"];
                $childs[$k]["droitnom"] = $groupeService->getRightName($v['droit']);
                //$childs[$k]['info'] = $userInfo;
            }
            //print_r($childs);
            $tplHome = new CopixTpl ();
            $tplHome->assign ('groupe', $groupe[0]);
            $tplHome->assign ('list', $childs);

            $result = $tplHome->fetch('gethomemembers.tpl');
            $tpl->assign ('MAIN', $result);

            return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
        }

    }


    /**
     * Modification d'un membre d'un groupe
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2007/01/09
     * @param integer $id Id du groupe
     * @param string $user_type Type du membre
     * @param integer $user_id Id du membre
     * @param array $errors (option) Erreurs rencontr�es
     * @param integer $droit (option) Valeur du droit si formulaire d�j� soumis
     * @param string $droit (option) Valeur du d�but de validit� si formulaire d�j� soumis
     * @param string $fin (option) Valeur de fin de validit� si formulaire d�j� soumis
     */
    public function processGetHomeAdminMember ()
    {
        CopixHTMLHeader::addJSLink (_resource("js/jquery/jquery.ui.datepicker-fr.js"));

        $dao = CopixDAOFactory::create("groupe");
        $kernel_service = & CopixClassesFactory::Create ('kernel|kernel');
        $groupeService = & CopixClassesFactory::Create ('groupe|groupeService');

        $id = $this->getRequest ('id', null);
        $errors = $this->getRequest ('errors', array());
        $user_type = $this->getRequest('user_type', null);
        $user_id = $this->getRequest('user_id', null);

        $droit = $this->getRequest('droit', null);
        $debut = $this->getRequest('debut', null);
        $fin =   $this->getRequest('fin', null);

        $critical_errors = array();

        $groupe = $dao->getGroupe($id);
        if (!$groupe)
        $critical_errors[] = CopixI18N::get ('groupe|groupe.error.noGroup');
        else {
            $mondroit = $kernel_service->getLevel( "CLUB", $id );
            if (!$groupeService->canMakeInGroupe('ADMIN', $mondroit))
            $critical_errors[] = CopixI18N::get ('kernel|kernel.error.noRights');
            else {
                // On v�rifie que le membre demand� fait bien partie du groupe /* et qu'il n'est pas le propri�taire */
                $dao = CopixDAOFactory::create("kernel|kernel_link_user2node");
                $his = $dao->get($user_type, $user_id, "CLUB", $id);
                //print_r($his);
                //$level = Kernel::getLevel ('CLUB', $id, $user_type, $user_id);
                if ($his->droit <= PROFILE_CCV_SHOW /* || $his->droit >= PROFILE_CCV_ADMIN */ )
                $critical_errors[] = CopixI18N::get ('kernel|kernel.error.noRights');
            }
        }

        if ($critical_errors) {
            return CopixActionGroup::process ('genericTools|Messages::getError', array ('message'=>implode('<br/>',$critical_errors), 'back'=>CopixUrl::get('groupe||')));
        } else {

            CopixHTMLHeader::addCSSLink (_resource("styles/module_groupe_admin.css"));

            if ($errors) {
                $his->droit = $droit;
                $his->debut = $debut;
                $his->fin = $fin;
            } else {
                //$his->debut = ($his->debut) ? CopixI18N::timestampToDate($his->debut) : null;
                //$his->fin = ($his->fin) ? CopixI18N::timestampToDate($his->fin) : null;
            }

            $userInfo = $kernel_service->getUserInfo($user_type, $user_id);
            //print_r($userInfo);

            $tpl = new CopixTpl ();
            $tpl->assign ('BODY_ON_LOAD', "setDatePicker('#debut,#fin')");
            $tpl->assign ('TITLE_PAGE', $groupe[0]->titre.' - '.$userInfo['prenom'].' '.$userInfo['nom']);
            // $tpl->assign ('MENU', '<a href="'.CopixUrl::get ('groupe||getHome', array("id"=>$id)).'">'.CopixI18N::get ('groupe|groupe.backHome').'</a> :: <a href="'.CopixUrl::get ('groupe||getHomeAdminMembers', array("id"=>$id)).'">'.CopixI18N::get ('groupe|groupe.backHomeAdminMembers').'</a>');
            $menu = array();
            // $menu[] = array('url' => CopixUrl::get ('groupe||getHome', array("id"=>$id)), 'txt'=>CopixI18N::get ('groupe|groupe.backHome'), 'size'=>100);
            //$menu[] = array('url' => CopixUrl::get ('groupe||getHomeAdminMembers', array("id"=>$id)), 'txt'=>CopixI18N::get ('groupe|groupe.backHomeAdminMembers'), 'size'=>120);
            $tpl->assign ('MENU', $menu);


            $tplHome = new CopixTpl ();
            $tplHome->assign ('groupe', $groupe[0]);
            $tplHome->assign ('errors', $errors);
            $tplHome->assign ('user', $userInfo);
            $tplHome->assign ('his', $his);
            $tplHome->assign ('values', array(
            PROFILE_CCV_READ=>$groupeService->getRightName(PROFILE_CCV_READ),
            PROFILE_CCV_MEMBER=>$groupeService->getRightName(PROFILE_CCV_MEMBER),
            PROFILE_CCV_MODERATE=>$groupeService->getRightName(PROFILE_CCV_MODERATE),
            PROFILE_CCV_ADMIN=>$groupeService->getRightName(PROFILE_CCV_ADMIN),
            ));


            $result = $tplHome->fetch('gethomeadminmember.tpl');
            $tpl->assign ('MAIN', $result);

            return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
        }

    }

    /**
     * Enregistrement de la modification d'un membre d'un groupe
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2007/01/09
     * @param integer $id Id du groupe
     * @param string $user_type Type du membre
     * @param integer $user_id Id du membre
     * @param integer $droit Son nouveau droit
     * @param string $debut Sa nouvelle date de d�but de validit�
     * @param string $fin Sa nouvelle date de fin de validit�
     */
    public function doModifyMember ()
    {
        $dao = CopixDAOFactory::create("groupe");
        $kernel_service = & CopixClassesFactory::Create ('kernel|kernel');
        $groupeService = & CopixClassesFactory::Create ('groupe|groupeService');

        $id = $this->getRequest ('id', null);
        $user_type = $this->getRequest('user_type', null);
        $user_id = $this->getRequest('user_id', null);
        $droit = $this->getRequest('droit', null);

        $req_debut = $this->getRequest ('debut', null);
        $req_fin = $this->getRequest ('fin', null);

        //$debut     = dateService::dateFrToDateBdd($req_debut);
        //$fin     = dateService::dateFrToDateBdd($req_fin);
        $debut     = CopixDateTime::dateToTimestamp($req_debut);
        $fin     = CopixDateTime::dateToTimestamp($req_fin);

        $critical_errors = $errors = array();

        $groupe = $dao->getGroupe($id);
        if (!$groupe)
        $critical_errors[] = CopixI18N::get ('groupe|groupe.error.noGroup');
        else {
            $mondroit = $kernel_service->getLevel( "CLUB", $id );
            if (!$groupeService->canMakeInGroupe('ADMIN', $mondroit))
            $critical_errors[] = CopixI18N::get ('kernel|kernel.error.noRights');
            else {
                // On v�rifie que le membre demand� fait bien partie du groupe /* et qu'il n'est pas le propri�taire */
                $level = Kernel::getLevel ('CLUB', $id, $user_type, $user_id);
                if ($level <= PROFILE_CCV_SHOW /* || $level >= PROFILE_CCV_ADMIN */)
                $critical_errors[] = CopixI18N::get ('kernel|kernel.error.noRights');
            }
        }

        if ($critical_errors) {
            return CopixActionGroup::process ('genericTools|Messages::getError', array ('message'=>implode('<br/>',$critical_errors), 'back'=>CopixUrl::get('groupe||')));
        } else {


            // Tests sur les dates
            if ($req_debut) {
                if (CopixDateTime::timestampToDate ($debut) === false)
                $errors[] = CopixI18N::get('groupe|groupe.error.formdatedeb');
            }
            if ($req_fin) {
                if (CopixDateTime::timestampToDate ($fin) === false)
                $errors[] = CopixI18N::get('groupe|groupe.error.formdatefin');
            }
            if ($req_debut && $req_fin && $debut && $fin && $debut>$fin){
                $errors[] = CopixI18N::get('groupe|groupe.error.inversiondate');
            }

            if ($droit && !$errors) {
                $dao = CopixDAOFactory::create("kernel|kernel_link_user2node");
                $his = $dao->get($user_type, $user_id, "CLUB", $id);
                $his->droit = $droit;
                $his->debut = ($debut) ? $debut : NULL;
                $his->fin = ($fin) ? $fin : NULL;
                $dao->update ($his);
            }

            if ($errors)
            return CopixActionGroup::process ('groupe|groupe::getHomeAdminMember', array ('id'=>$id, 'user_type'=>$user_type, 'user_id'=>$user_id, 'droit'=>$droit, 'debut'=>$req_debut, 'fin'=>$req_fin, 'errors'=>$errors));

            return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('groupe||getHomeAdminMembers', array("id"=>$id)));

        }

    }


    /**
     * D�sinscription de l'utilisateur courant d'un groupe. Renvoie sur la page demandant confirmation avant de supprimer, ou proc�de � la desinscription (si la confirmation a d�j� eu lieu).
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2007/05/30
     * @param integer $id Id du groupe
     * @param integer $confirm 1 si on revient apr�s confirmation, nul si on pose la question
     */
    public function doUnsubscribeHimself ()
    {
        $dao = CopixDAOFactory::create("groupe");
        $kernel_service = & CopixClassesFactory::Create ('kernel|kernel');
        $groupeService = & CopixClassesFactory::Create ('groupe|groupeService');

        $errors = array();
        $id = $this->getRequest('id', null);
        $confirm = $this->getRequest('confirm', 0);

        $groupe = $dao->getGroupe($id);

        if (!$groupe)
        $errors[] = CopixI18N::get ('groupe|groupe.error.noGroup');

        $mondroit = $kernel_service->getLevel( "CLUB", $id );
        if (!$groupeService->canMakeInGroupe('UNSUBSCRIBE_HIMSELF', $mondroit))
        $errors[] = CopixI18N::get ('kernel|kernel.error.noRights');

        if ($errors) {
            return CopixActionGroup::process ('genericTools|Messages::getError', array ('message'=>implode('<br/>',$errors), 'back'=>CopixUrl::get('groupe||')));
        } else {
            if ($confirm) {
                Kernel::setLevel("CLUB", $id, _currentUser()->getExtra('type'), _currentUser()->getExtra('id'), 0);
                return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('groupe||getListMy'));
            } else {
                return CopixActionGroup::process ('genericTools|Messages::getConfirm',
                array (
                        'title'=>$groupe[0]->titre,
                        'message'=>CopixI18N::get ('groupe|groupe.conf.UnsubscribeHimself'),
                        'confirm'=>CopixUrl::get('groupe||doUnsubscribeHimself', array('id'=>$id, 'confirm'=>1)),
                        'cancel'=>CopixUrl::get('groupe||getHome', array('id'=>$id)),
              // 'MENU'=>'<a href="'.CopixUrl::get ('groupe||getHome', array("id"=>$id)).'">'.CopixI18N::get ('groupe|groupe.backHome').'</a>',
                )
                );
            }
        }

    }





}

