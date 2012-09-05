<?php
/**
* @package  Iconito
* @subpackage Comptes
* @version   $Id: comptes.actiongroup.php,v 1.35 2009-08-31 10:00:17 fmossmann Exp $
* @author   Frédéric Mossmann
* @copyright 2006 CAP-TIC
* @link      http://www.cap-tic.fr
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

/**
 * @author	Frédéric Mossmann
 */
class ActionGroupAdmins extends enicActionGroup
{
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
        //CopixHTMLHeader::addCSSLink (_resource("styles/module_comptes.css"));
    }

    /**
     * Admins
     *
     * Affiche la liste des administrateurs
     *
     * @package	Comptes
     * @author	Frédéric Mossmann <fmossmann@cap-tic.fr>
     */
    public function processDefault()
    {
        if(!Kernel::isAdmin())
            return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('||' ) );

        $roles = _request('role');
        $new_admin = _request('new_admins');

        if($roles) {
            $sql = "
                SELECT dbuser.*, kernel_link_bu2user.*
                FROM dbuser
                JOIN kernel_link_bu2user ON dbuser.id_dbuser=kernel_link_bu2user.user_id
                WHERE dbuser.id_dbuser IN (".implode(',',array_keys($roles)).")
            ";
            $admins_mod = _doQuery ($sql);

            foreach( $admins_mod AS $admins_mod_item ) {
                $sql = "
                    DELETE FROM kernel_link_user2node
                    WHERE node_type='ROOT' AND node_id=0
                    AND   user_type=:bu_type AND user_id=:bu_id
                ";
                _doQuery ($sql, array(':bu_type'=>$admins_mod_item->bu_type, ':bu_id'=>$admins_mod_item->bu_id));

                $sql = "
                    DELETE FROM dbgroup_users
                    WHERE id_dbgroup=:id_dbgroup AND userhandler_dbgroup=:userhandler_dbgroup AND user_dbgroup=:user_dbgroup";
                $params = array(
                    ':id_dbgroup' => 1,
                    ':userhandler_dbgroup' => 'auth|dbuserhandler',
                    ':user_dbgroup' => $admins_mod_item->id_dbuser
                );
                _doQuery ($sql, $params);

                if($roles[$admins_mod_item->id_dbuser]) {
                    $sql = "
                        INSERT INTO kernel_link_user2node
                            ( user_type,  user_id,  node_type,  node_id,  droit)
                        VALUES
                            (:user_type, :user_id, :node_type, :node_id, :droit)
                        ";
                    $params = array(
                        ':user_type' => $admins_mod_item->bu_type,
                        ':user_id'   => $admins_mod_item->bu_id,
                        ':node_type' => 'ROOT',
                        ':node_id'   => 0,
                        ':droit'     => $roles[$admins_mod_item->id_dbuser]
                    );
                    _doQuery ($sql, $params);

                    $sql = "
                        INSERT INTO dbgroup_users
                            ( id_dbgroup,  userhandler_dbgroup,  user_dbgroup)
                        VALUES
                            (:id_dbgroup, :userhandler_dbgroup, :user_dbgroup)
                        ";
                    $params = array(
                        ':id_dbgroup' => 1,
                        ':userhandler_dbgroup' => 'auth|dbuserhandler',
                        ':user_dbgroup' => $admins_mod_item->id_dbuser
                    );
                    _doQuery ($sql, $params);
                }
            }
        }

        if($new_admin) {
            $new_admin_array = split(',', $new_admin);
            $new_admin_array_clean = array_map( 'trim', $new_admin_array );

            $sql_newadmins = "OR dbuser.login_dbuser IN (";
            $first = true;
            foreach( $new_admin_array_clean AS $new_admin_array_item ) { $sql_newadmins.= (!$first?", ":"")."'".addslashes($new_admin_array_item)."'"; $first=false; }
            $sql_newadmins.= ")";
        } else {
            $sql_newadmins = '';
        }

        $sql = "
            SELECT dbuser.*, kernel_link_bu2user.*, kernel_link_user2node.*
            FROM dbuser
            JOIN kernel_link_bu2user ON dbuser.id_dbuser=kernel_link_bu2user.user_id
            LEFT JOIN kernel_link_user2node ON kernel_link_bu2user.bu_type=kernel_link_user2node.user_type AND kernel_link_bu2user.bu_id=kernel_link_user2node.user_id AND kernel_link_user2node.node_type='ROOT' AND kernel_link_user2node.node_id=0
            WHERE (node_type='ROOT' AND node_id=0)
            ".$sql_newadmins."
            ORDER BY kernel_link_user2node.droit DESC,dbuser.login_dbuser
        ";

        $admins = _doQuery ($sql);

        foreach( $admins AS &$admin ) {
            $admin->user_infos = Kernel::getUserInfo( $admin->bu_type, $admin->bu_id );
        }
        /*
        dbuser : id_dbuser 	login_dbuser 	password_dbuser 	email_dbuser 	enabled_dbuser
        kernel_link_bu2user : user_id 	bu_type 	bu_id
        kernel_link_user2node : user_type 	user_id 	node_type 	node_id 	droit 	debut 	fin
        */

        $tpl = new CopixTpl ();

        $tplListe = new CopixTpl ();
        $tplListe->assign ("admins", $admins);
        $tplListe->assign ("user_id", _currentUser()->getExtra('user_id'));
        $tplListe->assign ("admin_fonctionnel", Kernel::isAdminFonctionnel());

        $main = $tplListe->fetch("admins-list.tpl");

        $tpl->assign ('TITLE_PAGE', CopixI18N::get ('comptes.moduleDescription')." &raquo; ".CopixI18N::get ('comptes.title.admins'));
        $tpl->assign ('MAIN', $main );

        $tpl->assign ('MENU', $this->menu );


        return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
    }


    /**
     * Admins : ajouter des admins
     *
     * Propose l'ajout de personnes comme animateur
     *
     * @package	Comptes
     * @author	Frédéric Mossmann <fmossmann@cap-tic.fr>
     */
    public function processNew()
    {
        if( !Kernel::isAdmin() )
            return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('||' ) );

        $tpl = new CopixTpl ();

        $tplListe = new CopixTpl ();
        $main = $tplListe->fetch("admins-new.tpl");

        $tpl->assign ('TITLE_PAGE', CopixI18N::get ('comptes.moduleDescription')." &raquo; ".CopixI18N::get ('comptes.title.admins'));
        $tpl->assign ('MAIN', $main );

        $tpl->assign ('MENU', $this->menu );


        return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
    }


}
