<?php
/**
* @package  Iconito
* @subpackage Comptes
* @version   $Id: ien.actiongroup.php,v 1.1 2009-08-31 10:00:17 fmossmann Exp $
* @author   Frédéric Mossmann
* @copyright 2012 CAP-TIC
* @link      http://www.cap-tic.fr
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

/**
 * @author	Frédéric Mossmann
 */
class ActionGroupIen extends enicActionGroup
{
    private $menu;

    public function beforeAction ()
    {
        _currentUser()->assertCredential ('group:[current_user]');
        $this->menu = array();

        if(Kernel::isAdmin() || _currentUser()->hasAssistance('can_comptes') ) {
            if( CopixConfig::exists('kernel|gestionAutonomeEnabled') && CopixConfig::get('kernel|gestionAutonomeEnabled') ) {
            $this->menu[] = array( 'txt' => CopixI18N::get('comptes|comptes.menu.getUsers'), 'url' => CopixUrl::get ('gestionautonome||showTree'), 'type'=>'users');
            } else {
            $this->menu[] = array( 'txt' => CopixI18N::get('comptes|comptes.menu.getUsers'), 'url' => CopixUrl::get ('comptes||'), 'type'=>'users');
            }
            $this->menu[] = array( 'txt' => CopixI18N::get('comptes|comptes.menu.getExt'), 'url' => CopixUrl::get ('comptes||getUserExt'), 'type'=>'acl');
        }
        if(Kernel::isAdmin()) {
            $this->menu[] = array( 'txt' => CopixI18N::get('comptes|comptes.menu.getRoles'), 'url' => CopixUrl::get ('comptes||getRoles'), 'type'=> 'acl', 'current'=>'current');
            $this->menu[] = array( 'txt' => CopixI18N::get('comptes|comptes.menu.manageGrades'), 'url' => CopixUrl::get ('gestionautonome||manageGrades'), 'type'=>'agendalist');
        }

        $session = _sessionGet ('modules|comptes|doLoginCreate|success');
        if( $session && is_array($session) && sizeof($session) ) {
            $this->menu[] = array( 'txt' => CopixI18N::get('comptes.strings.showloginresult', sizeof($session) ), 'url' => CopixUrl::get ('comptes||getLoginResult'), 'size'=>145 );
        }
    }

    /**
     * list
     *
     * Affiche le formulaire de modification d'un utilisateur extérieur
     *
     * @package	Comptes
     * @author	Frédéric Mossmann <fmossmann@cap-tic.fr>
     */
    public function getList()
    {
        if( !Kernel::isAdmin() )
            return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('||' ) );

        CopixHTMLHeader::addCSSLink (_resource("styles/module_comptes.css"));

        $tpl = new CopixTpl ();
        $tplIen = new CopixTpl ();

        $ien_dao = & CopixDAOFactory::create("kernel|kernel_ien");
        $iens = $ien_dao->findAll();
        $ppo->iens = array();

        foreach( $iens AS $ien ) {
            $ien->user_infos = Kernel::getUserInfo( $ien->user_type, $ien->user_id );
            $ien->regroupements = new CopixPPO();
            $ien->regroupements->grvilles = array();
            $ien->regroupements->grecoles = array();
            $ppo->iens[$ien->user_type."-".$ien->user_id] = $ien;
        }

        $ien2regroupements_dao = & CopixDAOFactory::create("kernel|kernel_ien2regroupements");
        $ien2regroupements = $ien2regroupements_dao->findAll();

        // echo "<pre>"; print_r($ien2regroupements); die("</pre>");

        $comptes_service = & CopixClassesFactory::Create ('comptes|ComptesService');
        $grvilles = $comptes_service->getGrvillesList();
        $grecoles = $comptes_service->getGrecolesList();

        $ppo->grvilles = array();
        foreach( $grvilles AS $grville ) {
            $ppo->grvilles[$grville->id] = $grville;
        }

        $ppo->grecoles = array();
        foreach( $grecoles AS $grecole ) {
            $ppo->grecoles[$grecole->id] = $grecole;
        }

        foreach( $ien2regroupements AS $ien2gr) {
            if($ien2gr->regroupement_type=='ecoles')
                $ppo->iens[$ien2gr->user_type."-".$ien2gr->user_id]->regroupements->grecoles[$ien2gr->regroupement_id] = $ppo->grecoles[$ien2gr->regroupement_id];
            if($ien2gr->regroupement_type=='villes')
                $ppo->iens[$ien2gr->user_type."-".$ien2gr->user_id]->regroupements->grvilles[$ien2gr->regroupement_id] = $ppo->grvilles[$ien2gr->regroupement_id];
        }

        // echo "<pre>"; print_r($ppo->ien); die("</pre>");

        // $comptes_service = & CopixClassesFactory::Create ('comptes|ComptesService');
        // $ppo->grvilles = $comptes_service->getGrvillesList();


        /*
        echo "<pre>";
        print_r($ppo->ien);
        print_r($ppo->grvilles);
        die();
        */

        $tplIen->assign('ppo', $ppo);
        $result = $tplIen->fetch("ien-list.tpl");

        $tpl->assign ('TITLE_PAGE', CopixI18N::get ('comptes.moduleDescription')." &raquo; ".CopixI18N::get ('comptes.title.ien_list'));
        $tpl->assign ('MAIN', $result );

        $tpl->assign ('MENU', $this->menu );

        return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
    }





    public function getEdit()
    {
        if( !Kernel::isAdmin() )
            return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('||' ) );

        CopixHTMLHeader::addCSSLink (_resource("styles/module_comptes.css"));

        $tpl = new CopixTpl ();
        $tplIen = new CopixTpl ();

        $comptes_service = & CopixClassesFactory::Create ('comptes|ComptesService');
        $ien_dao = & CopixDAOFactory::create("kernel|kernel_ien");
        $ien2regroupements_dao = & CopixDAOFactory::create("kernel|kernel_ien2regroupements");

        $pUserType = _request('user_type');
        $pUserId = _request('user_id');
        $pSave = _request('save');

        $user = Kernel::getUserInfo();

        $ppo->pouvoirs = array(
            array('id'=>'can_connect',        'nom'=>'Se connecter en tant qu\'un enseignant/directeur' ),
            array('id'=>'can_tableaubord',    'nom'=>'Tableau de bord des usages'),
            array('id'=>'can_comptes',        'nom'=>'Administration des comptes d\'acc&egrave;s'),
        );

        $grvilles = $comptes_service->getGrvillesList();
        $ppo->grvilles = array();
        foreach( $grvilles AS $grville ) {
            $ppo->grvilles[$grville->id] = $grville;
        }

        $grecoles = $comptes_service->getGrecolesList();
        $ppo->grecoles = array();
        foreach( $grecoles AS $grecole ) {
            $ppo->grecoles[$grecole->id] = $grecole;
        }

        if( !$pUserType || !$pUserId ) {
            return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('comptes|ien|list'));
        }

        $ppo->ien = $ien_dao->get($pUserType, $pUserId);

        $new = false;
        if(!$ppo->ien) {
            $ppo->ien = _record("kernel|kernel_ien");

            $ppo->ien->user_type = $pUserType;
            $ppo->ien->user_id = $pUserId;
            $ppo->ien->can_connect = 0;
            $ppo->ien->can_tableaubord = 0;
            $ppo->ien->can_comptes = 0;
            $ppo->ien->is_visibleannuaire = 0;
            $ppo->ien->updated_at = date("Y-m-d H:i:s");
            $ppo->ien->updated_by = $user['login'];
            $new=true;
            // return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('comptes|ien|list'));
        }

        if($pSave==1) {

            foreach( $ppo->pouvoirs AS $pouvoir ) {
                $ppo->ien->$pouvoir['id'] = _request('pouvoir_'.$pouvoir['id'])*1;
            }

            $ppo->ien->is_visibleannuaire = _request('annuaire')*1;

            $ppo->ien->updated_at = date("Y-m-d H:i:s");
            $ppo->ien->updated_by = $user['login'];

            if($new) $ien_dao->insert($ppo->ien);
                     $ien_dao->update($ppo->ien);

            $ien2regroupements_dao->deleteByUser($pUserType, $pUserId);

            $ien2regroupements  = _record("kernel|kernel_ien2regroupements");


            $ien2regroupements->user_type = $pUserType;
            $ien2regroupements->user_id   = $pUserId;

            $ien2regroupements->regroupement_type   = 'villes';
            foreach( $ppo->grvilles AS $grville ) {
                if( _request('groupe_villes_'.$grville->id)==1 ) {
                    $ien2regroupements->regroupement_id = $grville->id;
                    _dao("kernel|kernel_ien2regroupements")->insert($ien2regroupements);
                }
            }

            $ien2regroupements->regroupement_type   = 'ecoles';
            foreach( $ppo->grecoles AS $grecole ) {
                if( _request('groupe_ecoles_'.$grecole->id)==1 ) {
                    $ien2regroupements->regroupement_id = $grecole->id;
                    _dao("kernel|kernel_ien2regroupements")->insert($ien2regroupements);
                }
            }

            return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('comptes|ien|list' ) );
        }

        $ppo->ien->user_infos = Kernel::getUserInfo( $pUserType, $pUserId );

        $ien_regroupements = $ien2regroupements_dao->findByUser($pUserType, $pUserId);
        $ppo->ien_grville = array();
        foreach( $ien_regroupements AS $ien_regroupement ) {
            if($ien_regroupement->regroupement_type=='villes')
                $ppo->ien_grville[$ien_regroupement->regroupement_id] = 1;
            if($ien_regroupement->regroupement_type=='ecoles')
                $ppo->ien_grecole[$ien_regroupement->regroupement_id] = 1;

        }

        $tplIen->assign('ppo', $ppo);
        $result = $tplIen->fetch("ien-edit.tpl");

        $tpl->assign ('TITLE_PAGE', CopixI18N::get ('comptes.moduleDescription')." &raquo; ".CopixI18N::get ('comptes.title.ien_edit'));
        $tpl->assign ('MAIN', $result );

        $tpl->assign ('MENU', $this->menu );

        return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);


    }


    public function getNew()
    {
        if( !Kernel::isAdmin() )
            return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('||' ) );

        CopixHTMLHeader::addCSSLink (_resource("styles/module_comptes.css"));

        $tpl = new CopixTpl ();
        $tplIen = new CopixTpl ();

        $ien_dao = _dao("kernel|kernel_ien");
        $ien = $ien_dao->findAll();
        $ppo->ien = array();

        foreach( $ien AS $ien ) {
            $ppo->ien[$ien->user_type."-".$ien->user_id] = $ien;
        }

        //// Personnes externes ///////////////////////////////////////
        $userext_dao = _dao("kernel|kernel_ext_user");
        $list = $userext_dao->listUsers();
        $user_key = 0;
        foreach( $list AS $user_val ) {
            $ppo->userext[$user_key] = $user_val;
            $user_key++;
        }
        foreach( $ppo->userext AS $user_key => $user_val ) {
            if( isset($ppo->ien["USER_EXT-".$user_val->ext_id]) ) {
                // Si la personne est déjà ien
                unset($ppo->userext[$user_key]);
            } else {
                $ppo->userext[$user_key]->user_infos = Kernel::getUserInfo( 'USER_EXT', $user_val->ext_id );
                if( !isset($ppo->userext[$user_key]->user_infos['login']) ) {
                    // Si la personne n'a pas de login de type enseignant
                    unset($ppo->userext[$user_key]);
                }
            }
        }



        $sql = "
            SELECT PER.nom AS nom, PER.prenom1 AS prenom,
                   B2U.bu_type AS bu_type, B2U.bu_id AS bu_id,
                   USR.login_dbuser
            FROM kernel_bu_personnel PER
            JOIN kernel_bu_personnel_entite ENT ON PER.numero=ENT.id_per
            JOIN kernel_link_bu2user B2U ON PER.numero=B2U.bu_id AND B2U.bu_type IN ('USER_VIL','USER_ENS','USER_ADM')
            JOIN dbuser USR ON B2U.user_id=USR.id_dbuser
            -- WHERE PER.deleted=0
            GROUP BY bu_type,bu_id
        ";
        $pers = _doQuery ($sql);
        $ppo->pers = array();

        foreach($pers AS $pers_item) {
            $ppo->pers[$pers_item->bu_type][$pers_item->bu_id] = $pers_item;
        }


        /*
        echo "<pre>";
        // print_r($ppo->ien);
        print_r($ppo->userext);
        // print_r($ppo->userens);
        die();
        */

        $tplIen->assign('ppo', $ppo);
        $result = $tplIen->fetch("ien-new.tpl");

        $tpl->assign ('TITLE_PAGE', CopixI18N::get ('comptes.moduleDescription')." &raquo; ".CopixI18N::get ('comptes.title.ien_list'));
        $tpl->assign ('MAIN', $result );

        $tpl->assign ('MENU', $this->menu );

        return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
    }

    public function getDelete()
    {
        if( !Kernel::isAdmin() )
            return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('||' ) );

        $pUserType = _request('user_type');
        $pUserId = _request('user_id');

        if( !$pUserType || !$pUserId ) {
            return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('comptes|ien|list'));
        }

        $ien_dao = & CopixDAOFactory::create("kernel|kernel_ien");
        // $ien2grville_dao = & CopixDAOFactory::create("kernel|kernel_ien2grville");
        $ien2regroupements_dao = & CopixDAOFactory::create("kernel|kernel_ien2regroupements");

        $ien_dao->delete($pUserType, $pUserId);
        // $ien2grville_dao->deleteByUser($pUserType, $pUserId);
        $ien2regroupements_dao->deleteByUser($pUserType, $pUserId);

        return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('comptes|ien|list'));
    }
}

