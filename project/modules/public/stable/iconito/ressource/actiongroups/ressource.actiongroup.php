<?php

_classInclude('ressource|ressource');

/**
 * Actiongroup du module Ressource
 *
 * @package Iconito
 * @subpackage	Ressource
 */
class ActionGroupRessource extends CopixActionGroup
{
    public function beforeAction ()
    {
        _currentUser()->assertCredential ('group:[current_user]');

    }

    public function go()
    {
        if( (_request("id")) ) {
            return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('ressource||getSearchAdvanced', array('id'=>_request("id")) ));
        } else {

            // Patch
            return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('ressource||getSearchAdvanced', array('id'=>2) ));

            return CopixActionGroup::process ('genericTools|Messages::getError',
            array ('message'=>CopixI18N::get ('ressource.error.numBiblioNonDef'),
            'back'=>CopixUrl::get ('||')));
        }
    }

    public function getList ()
    {
        $tpl = new CopixTpl ();

        if( !(_request("id")) )
            return CopixActionGroup::process ('genericTools|Messages::getError',
            array ('message'=>CopixI18N::get ('ressource.error.numBiblioNonDef'),
            'back'=>CopixUrl::get ('||')));

        $id = _request("id");


        if( 0 && ! Ressource::checkRight( "ANNU", $id, PROFILE_CCV_READ ) )
            return CopixActionGroup::process ('genericTools|Messages::getError',
            array ('message'=>CopixI18N::get ('ressource.error.accesInterdit'),
            'back'=>CopixUrl::get ('||')));


        $tpl->assign ('TITLE_PAGE', CopixI18N::get ('ressource.title.module').' &raquo; '.CopixI18N::get ('ressource.title.liste'));

        // $menu = '<a href="'.CopixUrl::get('ressource||getSearchAdvanced', array('id'=>$id)).'">'.CopixI18N::get ('ressource.menu.parCriteres').'</a>';
        $menu[] = array('txt'=>CopixI18N::get ('ressource.menu.parCriteres'), 'size' => 130, 'url' => CopixUrl::get('ressource||getSearchAdvanced', array('id'=>$id)));

        if( Ressource::checkRight( "ANNU", $id, PROFILE_CCV_WRITE ) ) {
            // $menu .= ' :: <a href="'.CopixUrl::get('ressource||getRessource', array('id'=>$id,'mode'=>'new') ).'">'.CopixI18N::get ('ressource.menu.ajouterRessource').'</a>';
            $menu[] = array('txt'=>CopixI18N::get ('ressource.menu.ajouterRessource'), 'type'=>'create', 'size' => 130, 'url' => CopixUrl::get('ressource||getRessource', array('id'=>$id,'mode'=>'new') ));
        }
        $tpl->assign ('MENU', $menu);



        $tplList = new CopixTpl ();

        $ressource_dao = CopixDAOFactory::create("ressource_ressources");
        $ressource_list = $ressource_dao->getByAnnu($id);
        $tplList->assign ('ressource_list', $ressource_list);

        if( (_request("refreshtags")) && _request("refreshtags")=="yes" ) {
            set_time_limit( 0 );
            foreach( $ressource_list AS $key=>$val ) {
                Ressource::savetags( $val->ressources_id );
            }
        }

        $tplList->assign ('id', $id);
        $result = $tplList->fetch("getlist.tpl");
        $tpl->assign ('MAIN', $result);

        return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
    }




    public function getRessource ()
    {
        $tpl = new CopixTpl ();
        $tplMain = new CopixTpl ();

        $mode = _request("mode", "view");

        if( 0 && !(_request("id")) )
            return CopixActionGroup::process ('genericTools|Messages::getError',
            array ('message'=>CopixI18N::get ('ressource.error.numBiblioNonDef'),
            'back'=>CopixUrl::get ('||')));

        $id = _request("id");

        $ressource_dao = CopixDAOFactory::create("ressource_ressources");


        // Liste des domaines par niveaux
        $niveau_dao = CopixDAOFactory::create("ressource_niveaux");
        $domaine_dao = CopixDAOFactory::create("ressource_domaines");
        $niveau_list = $niveau_dao->getOrdre();
        foreach( $niveau_list AS $niveau_key=>$niveau_val ) {
            $niveau_list[$niveau_key]->domaines = $domaine_dao->getByNiveau($niveau_val->niveaux_id);
        }
        $tplMain->assign ('niveau_list', $niveau_list);

        // Liste des fonctions
        $fonction_dao = CopixDAOFactory::create("ressource_fonctions");
        $fonction_list = $fonction_dao->getOrdre();
        $tplMain->assign ('fonction_list', $fonction_list);

        // Liste des contenus
        $contenu_dao = CopixDAOFactory::create("ressource_contenus");
        $contenu_list = $contenu_dao->getOrdre();
        $tplMain->assign ('contenu_list', $contenu_list);



        if( $mode=="view" || $mode=="edit" ) {

            // if( $mode=="view" ) $minright=PROFILE_CCV_SHOW;
            if( $mode=="view" ) $minright=0;
            else                $minright=PROFILE_CCV_PUBLISH;

            if( ! Ressource::checkRight( "RES", $id, $minright ) )
                return CopixActionGroup::process ('genericTools|Messages::getError',
                array ('message'=>CopixI18N::get ('ressource.error.accesInterdit'),
                'back'=>CopixUrl::get ('||')));


            $ressource = $ressource_dao->get($id);

            $tags_dao = CopixDAOFactory::create("ressource_tags");
            $tags = $tags_dao->getTagsForRessource( $id, $ressource->ressources_id_annu );

            $tplMain->assign ('ressource', $ressource);
            $tplMain->assign ('tags_list', $tags);

            $tpl->assign ('TITLE_PAGE', CopixI18N::get ('ressource.title.module').' &raquo; '.$ressource->ressources_nom);

            $menu = array();
            if( $mode=="view" && Ressource::checkRight( "RES", $id, PROFILE_CCV_MODERATE ) ) {
                $menu[] = array(
                    'url' => CopixUrl::get('ressource||getRessource', array('id'=>$ressource->ressources_id,'mode'=>'edit') ),
                    'txt' => CopixI18N::get ('ressource.menu.modifierRessource')
                );
            }
            if( $mode=="edit" && Ressource::checkRight( "RES", $id, PROFILE_CCV_MODERATE ) ) {
                $menu[] = array(
                    'url' => CopixUrl::get('ressource||getRessource', array('id'=>$ressource->ressources_id,'mode'=>'del') ),
                    'txt' => CopixI18N::get ('ressource.menu.effacerRessource')
                );
            }
            $menu[] = array(
                'url' => CopixUrl::get('|getSearchAdvanced', array('id'=>$ressource->ressources_id_annu)),
                'txt' => CopixI18N::get ('ressource.menu.parCriteres')
            );
            $menu[] = array(
                'url' => CopixUrl::get('ressource||getList', array('id'=>$ressource->ressources_id_annu) ),
                'txt' => CopixI18N::get ('ressource.menu.retourListe')
            );
            if( Ressource::checkRight( "RES", $id, PROFILE_CCV_WRITE ) ) {
                $menu[] = array(
                    'url' => CopixUrl::get('ressource||getRessource', array('id'=>$ressource->ressources_id_annu,'mode'=>'new') ),
                    'txt' => CopixI18N::get ('ressource.menu.ajouterRessource')
                );
            }

            // Liste des domaines sélectionnées
            $domainesel_dao = CopixDAOFactory::create("ressource_res2domaine");
            $domainesel_tmp = $domainesel_dao->getByRessource($ressource->ressources_id);
            foreach( $domainesel_tmp AS $domainesel_key=>$domainesel_val ) {
                $domainesel_list[$domainesel_val->domaines_id] = true;
            }
            $tplMain->assign ('domainesel_list', $domainesel_list);


            // Liste des fonctions sélectionnées
            $fonctionsel_dao = CopixDAOFactory::create("ressource_res2fonction");
            $fonctionsel_tmp = $fonctionsel_dao->getByRessource($ressource->ressources_id);
            foreach( $fonctionsel_tmp AS $fonctionsel_key=>$fonctionsel_val ) {
                $fonctionsel_list[$fonctionsel_val->fonctions_id] = true;
            }
            $tplMain->assign ('fonctionsel_list', $fonctionsel_list);

            // Liste des contenus sélectionnées
            $contenusel_dao = CopixDAOFactory::create("ressource_res2contenu");
            $contenusel_tmp = $contenusel_dao->getByRessource($ressource->ressources_id);
            foreach( $contenusel_tmp AS $contenusel_key=>$contenusel_val ) {
                $contenusel_list[$contenusel_val->contenus_id] = true;
            }
            $tplMain->assign ('contenusel_list', $contenusel_list);

            $tplMain->assign ('annu_id', $ressource->ressources_id_annu);
            $tplMain->assign ('res_id', $id);
            if( $mode=="edit" ) $tplMain->assign ('edit', true);
        }

        if( $mode=="new" ) {

            if( ! Ressource::checkRight( "ANNU", $id, PROFILE_CCV_WRITE ) )
                return CopixActionGroup::process ('genericTools|Messages::getError',
                array ('message'=>CopixI18N::get ('ressource.error.accesInterdit'),
                'back'=>CopixUrl::get ('||')));

            $ressource = CopixDAOFactory::createRecord("ressource_ressources");
            $userinfo = Kernel::getSessionBU();
            if( isset($userinfo['nom']) && isset($userinfo['prenom']) ) {
                $ressource->ressources_submit_user = trim( $userinfo['prenom'].' '.$userinfo['nom'] );
            }

            $tplMain->assign ('ressource', $ressource);
            $tplMain->assign ('annu_id', $id);
            $tplMain->assign ('res_id', 0);
            $tplMain->assign ('edit', true);

            $tpl->assign ('TITLE_PAGE', CopixI18N::get ('ressource.title.module').' &raquo; '.CopixI18N::get ('ressource.title.nouvelleRessource'));
            $menu = array();
            $menu[] = array(
                'url' => CopixUrl::get('ressource||getList', array('id'=>$id) ),
                'txt' => CopixI18N::get ('ressource.menu.retourListe')
            );
        }

        if( $mode=="del" ) {

            if( ! Ressource::checkRight( "RES", $id, PROFILE_CCV_MODERATE ) )
                return CopixActionGroup::process ('genericTools|Messages::getError',
                array ('message'=>CopixI18N::get ('ressource.error.accesInterdit'),
                'back'=>CopixUrl::get ('||')));

            $ressource = CopixDAOFactory::createRecord("ressource_ressources");
            $ressource = $ressource_dao->get($id);

            // DELETE
            if( _request("confirm") == md5(_currentUser()->getLogin().$ressource->ressources_id) ) {
                die( "DELETE" );
            }


            $tpl->assign ('TITLE_PAGE', CopixI18N::get ('ressource.title.delete', array($ressource->ressources_nom)));
            $menu = array();
            $menu[] = array(
                'url' => CopixUrl::get('ressource||getRessource', array('id'=>$ressource->ressources_id,'mode'=>'edit') ),
                'txt' => CopixI18N::get ('ressource.menu.retourRessource')
            );
            $menu[] = array(
                'url' => CopixUrl::get('ressource||getList', array('id'=>$ressource->ressources_id_annu) ),
                'txt' => CopixI18N::get ('ressource.menu.retourListe')
            );


            return CopixActionGroup::process ('genericTools|Messages::getConfirm',
                array (
                    // 'message'=>'Merci de confirmer l\'effacement de '.$ressource->ressources_nom,
                    'title' => CopixI18N::get( 'ressource.alerte.effaceRessource', array($ressource->ressources_nom) ),
                    'confirm' => CopixUrl::get( 'ressource||getRessource', array('id'=>$ressource->ressources_id,'mode'=>'del','confirm'=>md5( _currentUser()->getLogin().$ressource->ressources_id ) ) ),
                    'cancel'=>CopixUrl::get('ressource||getRessource', array('id'=>$ressource->ressources_id) ),
                    'back'=>CopixUrl::get ('||')));

        }


        // Liste des ressources
        $result = $tplMain->fetch("getressource.tpl");
        $tpl->assign ('MENU', $menu );
        $tpl->assign ('MAIN', $result);
        return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
    }


    public function doRessourceSave ()
    {
        // Modification

        $res_id = _request("res_id", 0);

        if( trim(_request("nom"))=="" ) return false;
        if( !$res_id ) return false;

        $ressource_dao = CopixDAOFactory::create("ressource_ressources");
        if( $res_id>0 )
            $ressource = $ressource_dao->get($res_id);
        else {
            $ressource = CopixDAOFactory::createRecord("ressource_ressources");
            $ressource->ressources_id_annu = _request("annu_id");
            $ressource->ressources_submit_user = _request("submit_user");
            $ressource->ressources_submit_date = date("Y-m-d H:i:s");
            $ressource->ressources_valid_user  = "";
            $ressource->ressources_valid_date  = date("Y-m-d H:i:s");
                    }
        $ressource->ressources_nom = _request("nom");
        $ressource->ressources_url = _request("url");
        $ressource->ressources_description = _request("description");
        $ressource->ressources_mots = _request("mots");
        $ressource->ressources_auteur = _request("auteur");
        $ressource->ressources_submit_user = _request("submit_user");
        if( $res_id>0 )
            $ressource_dao->update( $ressource );
        else {
            $ressource_dao->insert( $ressource );
            $res_id = $ressource->ressources_id;
        }

        $fonction_dao = CopixDAOFactory::create("ressource_res2fonction");
        $fonction_enr = $fonction_dao->delByRessource(_request("res_id"));
        $fonction_new = CopixDAOFactory::createRecord("ressource_res2fonction");
        foreach( _request("fonction") AS $fonction_item ) {
            $fonction_new->res2fonction_id_ressource = _request("res_id");
            $fonction_new->res2fonction_id_fonction = $fonction_item;
            $fonction_dao->insert( $fonction_new );
        }

        $contenu_dao = CopixDAOFactory::create("ressource_res2contenu");
        $contenu_enr = $contenu_dao->delByRessource(_request("res_id"));
        $contenu_new = CopixDAOFactory::createRecord("ressource_res2contenu");
        foreach( _request("contenu") AS $contenu_item ) {
            $contenu_new->res2contenu_id_ressource = _request("res_id");
            $contenu_new->res2contenu_id_contenu = $contenu_item;
            $contenu_dao->insert( $contenu_new );
        }

        $domaine_dao = CopixDAOFactory::create("ressource_res2domaine");
        $domaine_enr = $domaine_dao->delByRessource(_request("res_id"));
        $domaine_new = CopixDAOFactory::createRecord("ressource_res2domaine");
        foreach( _request("domaine") AS $domaine_item ) {
            $domaine_new->res2domaine_id_ressource = _request("res_id");
            $domaine_new->res2domaine_id_domaine = $domaine_item;
            $domaine_dao->insert( $domaine_new );
        }

        Ressource::savetags( _request("res_id") );

        return new CopixActionReturn (COPIX_AR_REDIRECT,
            CopixUrl::get ('ressource||getRessource', array('id'=>_request("res_id")) ));
    }


    public function getSearch()
    {
        $tpl = new CopixTpl ();
        $tplMain = new CopixTpl ();



        $params = array( 'text'=>_request("text") );
        $ressources = Ressource::searchRessources( $params, _request("id") );

        $tplMain->assign ('ressource_list', $ressources);

        $result = $tplMain->fetch("getlist.tpl");
        $tpl->assign ('MAIN', $result);
        return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
    }




    public function getSearchAdvanced ()
    {
        $tpl = new CopixTpl ();
        $tplMain = new CopixTpl ();

        if( 0 && !(_request("id")) )
            return CopixActionGroup::process ('genericTools|Messages::getError',
            array ('message'=>CopixI18N::get ('ressource.error.numBiblioNonDef'),
            'back'=>CopixUrl::get ('||')));

        $id = _request("id");
        $fonctions = $this->getRequest('fonctions');
        $contenus = $this->getRequest('contenus');
        $domaines = $this->getRequest('domaines');
        $licences = $this->getRequest('licences');
        $search = $this->getRequest('search');
        $mot = $this->getRequest('mot');
        //print_r($domaines);

        //$ressource_dao = CopixDAOFactory::create("ressource_ressources");
        $params = array();

        // Liste des domaines par niveaux
        $niveau_dao  = CopixDAOFactory::create("ressource_niveaux");
        $domaine_dao = CopixDAOFactory::create("ressource_domaines");
        $licence_dao = CopixDAOFactory::create("ressource_licences");

        $niveau_list = $niveau_dao->getOrdre();
        foreach( $niveau_list AS $niveau_key=>$niveau_val ) {
            $niveau_list[$niveau_key]->domaines = $domaine_dao->getByNiveau($niveau_val->niveaux_id);
        }
        $tplMain->assign ('niveau_list', $niveau_list);

        // Liste des fonctions
        $fonction_dao = CopixDAOFactory::create("ressource_fonctions");
        $fonction_list = $fonction_dao->getOrdre();
        $tplMain->assign ('fonction_list', $fonction_list);

        // Liste des contenus
        $contenu_dao = CopixDAOFactory::create("ressource_contenus");
        $contenu_list = $contenu_dao->getOrdre();
        $tplMain->assign ('contenu_list', $contenu_list);

        // Liste des licences
        $licence_dao = CopixDAOFactory::create("ressource_licences");
        $licence_list = $licence_dao->getOrdre();
        $tplMain->assign ('licence_list', $licence_list);

        // Mot clé saisi
        if ($mot) {
            $params['mot'] = $mot;
        }

        // Liste des fonctions sélectionnées
        $fonctionsel_list = array();
        if ($fonctions) {
            $params['fonctions'] = $fonctions;
            $fonctionsel_list[$fonctions] = 1;
        }

        // Liste des contenus sélectionnés
        $contenusel_list = array();
        if ($contenus) {
            $params['contenus'] = $contenus;
            $contenusel_list[$contenus] = 1;
        }

        // Liste des licences sélectionnés
        $licencesel_list = array();
        if ($licences) {
            $params['licences'] = $licences;
            $licencesel_list[$licences] = 1;
        }

        // Liste des domaines sélectionnés
        $valDomaines = $domainesel_list = array();
        if (is_array($domaines)) {
            foreach ($domaines as $tmp) {
                if ($tmp) {
                    $valDomaines[]=$tmp;
                    $domainesel_list[$tmp] = 1;
                }
            }
        }
        if ($valDomaines) {
            $params['domaines'] = $valDomaines;
        }
        //print_r($domainesel_list);



        $tplMain->assign ('fonctionsel_list', $fonctionsel_list);
        $tplMain->assign ('contenusel_list', $contenusel_list);
        $tplMain->assign ('domainesel_list', $domainesel_list);
        $tplMain->assign ('licencesel_list', $licencesel_list);
        $tplMain->assign ('params', $params);
        $tplMain->assign ('id', $id);
        $tplMain->assign ('search', $search);
        $tplMain->assign ('mot', $mot);

        // On cherche les ressources correspondant aux critères
        $ressources = array();
        if ($params) {
            $ressources = Ressource::searchAdvancedRessources($params, $id);
            $menu[] = array(
                'url' => CopixUrl::get('|getSearchAdvanced', array('id'=>$id)),
                'txt' => CopixI18N::get ('ressource.menu.parCriteres')
            );

        }
        $tplMain->assign ('ressource_list', $ressources);

    //	print_r($niveau_list);

        $result = $tplMain->fetch("ressource|getsearchadvanced.tpl");
        $tpl->assign ('TITLE_PAGE', CopixI18N::get ('ressource.title.module').' &raquo; '.CopixI18N::get ('ressource.title.recherche'));

        $menu[] = array(
            'url' => CopixUrl::get('|getList', array('id'=>$id)),
            'txt' => CopixI18N::get ('ressource.menu.retourListe')
        );
        if( Ressource::checkRight( "ANNU", $id, PROFILE_CCV_WRITE ) )
            $menu[] = array(
                'url' => CopixUrl::get('ressource||getRessource', array('id'=>$id,'mode'=>'new') ),
                'txt' => CopixI18N::get ('ressource.menu.ajouterRessource')
            );

        $tpl->assign ('MENU', $menu);

        $tpl->assign ('MAIN', $result);
        return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
    }




    public function getTag ()
    {
        $tpl = new CopixTpl ();
        $tpl->assign ('TITLE_PAGE', CopixI18N::get ('ressource.title.module').' &raquo; '.CopixI18N::get ('ressource.title.tag').' : "'._request("tag").'"');

        // Kernel::MyDebug( Ressource::alltags() );

        $ressource_list = Ressource::tag2ressources( _request("tag"), _request("id") );

        $tplList = new CopixTpl ();

        $tplList->assign ('ressource_list', $ressource_list);

        $alltags = Ressource::similarTags( _request("tag"), _request("id") );
        $tplList->assign ('tags_list', $alltags );

        $tplList->assign ('annu_id', _request("id"));

        $menu = array();
        $menu[] = array(
            'url' => CopixUrl::get('|getSearchAdvanced', array('id'=>_request("id"))),
            'txt' => CopixI18N::get ('ressource.menu.parCriteres')
        );
        $menu[] = array(
            'url' => CopixUrl::get('ressource||getList', array('id'=>_request("id")) ),
            'txt' => CopixI18N::get ('ressource.menu.retourListe')
        );
        if( Ressource::checkRight( "ANNU", _request("id"), PROFILE_CCV_WRITE ) ) {
            $menu[] = array(
                'url' => CopixUrl::get('ressource||getRessource', array('id'=>_request("id"),'mode'=>'new') ),
                'txt' => CopixI18N::get ('ressource.menu.ajouterRessource')
            );
        }


        $result = $tplList->fetch("gettag.tpl");
        $tpl->assign ('MENU', $menu);
        $tpl->assign ('MAIN', $result);

        return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
    }

}
