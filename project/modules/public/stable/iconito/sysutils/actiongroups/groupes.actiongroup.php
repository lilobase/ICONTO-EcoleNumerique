<?php
/**
 * Admin - ActionGroup
 *
 * @package	Iconito
 * @subpackage  Admin
 * @version     $Id$
 * @author      Frederic Mossmann <fmossmann@cap-tic.fr>
 * @copyright   2012 CAP-TIC
 * @link        http://www.cap-tic.fr
 */

_classInclude('sysutils|admin');

class ActionGroupGroupes extends EnicActionGroup
{
    public function beforeAction ()
    {
        _currentUser()->assertCredential ('group:[current_user]');
    }

    public function processDefault ()
    {
        if (!Kernel::isAdmin())
            return CopixActionGroup::process ('genericTools|Messages::getError', array ('message'=>CopixI18N::get ('kernel|kernel.error.noRights'), 'back'=>CopixUrl::get ()));

        $tplHome = new CopixTpl();

        $tpl = new CopixTpl ();
        $tpl->assign ('TITLE_PAGE', CopixI18N::get ('sysutils|admin.moduleDescription'));
        $tpl->assign ('MENU', Admin::getMenu('groupes'));

        /*
        $sql = "
            SELECT
                module_groupe_groupe.id AS groupe_id, module_groupe_groupe.titre AS groupe_titre, module_groupe_groupe.description AS description,
                GROUP_CONCAT(dbuser.login_dbuser ORDER BY dbuser.login_dbuser ASC SEPARATOR ' ') AS admins, COUNT(*) AS nb_admins
            FROM module_groupe_groupe
            LEFT JOIN kernel_link_user2node ON kernel_link_user2node.node_type='CLUB' AND kernel_link_user2node.node_id=module_groupe_groupe.id AND kernel_link_user2node.droit>60
            LEFT JOIN kernel_link_bu2user ON kernel_link_bu2user.bu_type=kernel_link_user2node.user_type AND kernel_link_bu2user.bu_id=kernel_link_user2node.user_id
            LEFT JOIN dbuser ON dbuser.id_dbuser=kernel_link_bu2user.user_id
            GROUP BY module_groupe_groupe.id
        ";
        */

        $sql = '
            SELECT
                module_groupe_groupe.id AS groupe_id, module_groupe_groupe.titre AS groupe_titre, module_groupe_groupe.description AS groupe_description,
                dbuser.id_dbuser AS admin_id, dbuser.login_dbuser AS admin_login,

                CASE kernel_link_bu2user.bu_type
                    WHEN "USER_ELE" THEN kernel_bu_eleve.nom
                    WHEN "USER_RES" THEN kernel_bu_responsable.nom
                    WHEN "USER_ENS" THEN kernel_bu_personnel.nom
                    WHEN "USER_VIL" THEN kernel_bu_personnel.nom
                    WHEN "USER_EXT" THEN kernel_ext_user.nom
                    ELSE "inconnu"
                END AS admin_nom,
                CASE kernel_link_bu2user.bu_type
                    WHEN "USER_ELE" THEN kernel_bu_eleve.prenom1
                    WHEN "USER_RES" THEN kernel_bu_responsable.prenom1
                    WHEN "USER_ENS" THEN kernel_bu_personnel.prenom1
                    WHEN "USER_VIL" THEN kernel_bu_personnel.prenom1
                    WHEN "USER_EXT" THEN kernel_ext_user.prenom
                    ELSE "inconnu"
                END AS admin_prenom,
                CASE kernel_link_bu2user.bu_type
                    WHEN "USER_ELE" THEN "eleve"
                    WHEN "USER_RES" THEN "responsable"
                    WHEN "USER_ENS" THEN "enseignant"
                    WHEN "USER_VIL" THEN "agent_ville"
                    WHEN "USER_EXT" THEN "personne_ext"
                    ELSE "inconnu"
                END AS admin_role

            FROM module_groupe_groupe
            LEFT JOIN kernel_link_user2node ON kernel_link_user2node.node_type="CLUB" AND kernel_link_user2node.node_id=module_groupe_groupe.id AND kernel_link_user2node.droit>60
            LEFT JOIN kernel_link_bu2user ON kernel_link_bu2user.bu_type=kernel_link_user2node.user_type AND kernel_link_bu2user.bu_id=kernel_link_user2node.user_id
            LEFT JOIN dbuser ON dbuser.id_dbuser=kernel_link_bu2user.user_id

            LEFT JOIN kernel_bu_eleve ON kernel_bu_eleve.idEleve=kernel_link_bu2user.bu_id
            LEFT JOIN kernel_bu_responsable ON kernel_bu_responsable.numero=kernel_link_bu2user.bu_id
            LEFT JOIN kernel_bu_personnel ON kernel_bu_personnel.numero=kernel_link_bu2user.bu_id
            LEFT JOIN kernel_ext_user ON kernel_ext_user.id=kernel_link_bu2user.bu_id

            ORDER BY kernel_link_bu2user.user_id IS NOT NULL, module_groupe_groupe.titre
        ';

        $groupes_list = _doQuery( $sql );

        $groupes_array = array();

        foreach( $groupes_list AS $groupes_item ) {
            if( !isset($groupes_array[$groupes_item->groupe_id])) {
                $groupes_array[$groupes_item->groupe_id] = new CopixPPO();

                $groupes_array[$groupes_item->groupe_id]->groupe_id = $groupes_item->groupe_id;
                $groupes_array[$groupes_item->groupe_id]->groupe_titre = $groupes_item->groupe_titre;
                $groupes_array[$groupes_item->groupe_id]->groupe_description = $groupes_item->groupe_description;
                $groupes_array[$groupes_item->groupe_id]->admins = array();
            }
            if($groupes_item->admin_id) {
                $groupes_array[$groupes_item->groupe_id]->admins[$groupes_item->admin_id] = new CopixPPO();
                $groupes_array[$groupes_item->groupe_id]->admins[$groupes_item->admin_id]->admin_id = $groupes_item->admin_id;
                $groupes_array[$groupes_item->groupe_id]->admins[$groupes_item->admin_id]->admin_login = $groupes_item->admin_login;
                $groupes_array[$groupes_item->groupe_id]->admins[$groupes_item->admin_id]->admin_nom = $groupes_item->admin_nom;
                $groupes_array[$groupes_item->groupe_id]->admins[$groupes_item->admin_id]->admin_prenom = $groupes_item->admin_prenom;
                $groupes_array[$groupes_item->groupe_id]->admins[$groupes_item->admin_id]->admin_role = $groupes_item->admin_role;
            }
        }

        // echo "<pre>"; print_r($groupes_array); die();

        $tplHome->assign('groupes_array', $groupes_array);

        if( isset($this->flash->new_admin_check) ) {
            $tplHome->assign('new_admin_check', $this->flash->new_admin_check);
        } else {
            $tplHome->assign('new_admin_check', false);
        }
        // module_groupe_groupe : id 	titre 	description 	is_open 	createur 	date_creation
        // kernel_link_user2node : user_type 	user_id 	node_type=CLUB 	node_id 	droit 	debut 	fin
        // kernel_link_bu2user : user_id 	bu_type 	bu_id
        // dbuser : id_dbuser 	login_dbuser 	password_dbuser 	email_dbuser 	enabled_dbuser

        $tpl->assign ('MAIN', $tplHome->fetch('sysutils|groupes-default.tpl'));

        return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
    }

    public function processAdd_Admin ()
    {
        if (!Kernel::isAdmin())
            return CopixActionGroup::process ('genericTools|Messages::getError', array ('message'=>CopixI18N::get ('kernel|kernel.error.noRights'), 'back'=>CopixUrl::get ()));

        $groupe_id = _request("groupe");

        $tplHome = new CopixTpl();

        $tpl = new CopixTpl ();
        $tpl->assign ('TITLE_PAGE', CopixI18N::get ('sysutils|admin.moduleDescription'));
        $tpl->assign ('MENU', Admin::getMenu('groupes'));

        $tplHome->assign('groupe_id', $groupe_id);

        $new_admin_check = array();
        if( _request('save',0) ) {
            $new_admins = _request('new_admins');
            $new_admins = preg_split("/[\s,]+/", $new_admins);

            foreach( $new_admins AS $new_admin ) {
                if( trim($new_admin)=='' ) continue;
                $sql = "
                    SELECT
                        dbuser.id_dbuser, dbuser.login_dbuser,
                        kernel_link_bu2user.bu_type, kernel_link_bu2user.bu_id,
                        kernel_link_user2node.droit, kernel_link_user2node.debut, kernel_link_user2node.fin
                    FROM dbuser
                    JOIN kernel_link_bu2user ON kernel_link_bu2user.user_id=dbuser.id_dbuser
                    LEFT JOIN kernel_link_user2node ON kernel_link_user2node.user_type=kernel_link_bu2user.bu_type AND kernel_link_user2node.user_id=kernel_link_bu2user.bu_id AND kernel_link_user2node.node_type='CLUB' AND kernel_link_user2node.node_id=:node_id
                    WHERE dbuser.login_dbuser=:login_dbuser
                ";
                $new_admin_check[$new_admin] = _doQuery( $sql, array(':login_dbuser'=>$new_admin, ':node_id'=>$groupe_id) );

                if( !count($new_admin_check[$new_admin]) ) { // login inconnu
                    // NE RIEN FAIRE !!!
                } elseif( !$new_admin_check[$new_admin][0]->bu_type ) { // login non rattaché
                    // NE RIEN FAIRE !!!
                } elseif( $new_admin_check[$new_admin][0]->droit ) { // déjà inscrit

                    // Modifier le droit actuel -> admin
                    $sql = "DELETE FROM kernel_link_user2node WHERE user_type=:user_type AND user_id=:user_id AND node_type=:node_type AND node_id=:node_id";
                    _doQuery( $sql, array(
                        ':user_type'=>$new_admin_check[$new_admin][0]->bu_type,
                        ':user_id'=>$new_admin_check[$new_admin][0]->bu_id,
                        ':node_type'=>'CLUB',
                        ':node_id'=>$groupe_id,
                    ));

                    $sql = "
                        INSERT INTO kernel_link_user2node ( user_type,  user_id,  node_type,  node_id,  droit)
                        VALUES                            (:user_type, :user_id, :node_type, :node_id, :droit)";
                    _doQuery( $sql, array(
                        ':user_type'=>$new_admin_check[$new_admin][0]->bu_type,
                        ':user_id'=>$new_admin_check[$new_admin][0]->bu_id,
                        ':node_type'=>'CLUB',
                        ':node_id'=>$groupe_id,
                        ':droit'=>70
                    ));

                } else {

                    // Ajouter le droit -> admin
                    $sql = "
                        INSERT INTO kernel_link_user2node ( user_type,  user_id,  node_type,  node_id,  droit)
                        VALUES                            (:user_type, :user_id, :node_type, :node_id, :droit)";
                    _doQuery( $sql, array(
                        ':user_type'=>$new_admin_check[$new_admin][0]->bu_type,
                        ':user_id'=>$new_admin_check[$new_admin][0]->bu_id,
                        ':node_type'=>'CLUB',
                        ':node_id'=>$groupe_id,
                        ':droit'=>70
                    ));

                }
            }
            $this->flash->new_admin_check = $new_admin_check;

            $tplHome->assign('new_admin_check', $new_admin_check);

            // echo "<pre>"; print_r($new_admin_check); echo "</pre>";


            return _arRedirect (_url ('sysutils|groupes|'));

            $tpl->assign ('MAIN', $tplHome->fetch('sysutils|groupes-addadmin-do.tpl'));
            return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);



        }


        $sql = "SELECT * FROM module_groupe_groupe WHERE id=:id";
        $groupe_infos = _doQuery( $sql, array(':id'=>$groupe_id) );

        if (!$groupe_infos)
            return CopixActionGroup::process ('genericTools|Messages::getError', array ('message'=>CopixI18N::get ('kernel|kernel.error.noRights'), 'back'=>CopixUrl::get ()));

        $tplHome->assign('groupe_id', $groupe_id);
        $tplHome->assign('groupe_infos', $groupe_infos);

        $tpl->assign ('MAIN', $tplHome->fetch('sysutils|groupes-addadmin.tpl'));

        return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
    }





}
