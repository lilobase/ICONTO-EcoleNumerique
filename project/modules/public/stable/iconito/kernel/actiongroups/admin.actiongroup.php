<?php
/**
 * Kernel - ActionGroup
 *
 * Fonctions du coeur d'Iconito : Gestion des utilisateurs, des liens avec les entités, de l'accès à la base éléve, des droits.
 * @package	Iconito
 * @subpackage	Kernel
 * @version   $Id: kernel.actiongroup.php,v 1.50 2009-07-10 09:13:20 cbeyer Exp $
 * @author	Frédéric Mossmann <fmossmann@cap-tic.fr>
 */

_classInclude('sysutils|admin');

class ActionGroupAdmin extends CopixActionGroup
{
    public function beforeAction ()
    {
        _currentUser()->assertCredential ('group:[current_user]');
    }



    public function processDefault ()
    {
        return _arRedirect (_url ('|getHome'));
    }



    public function processDoCreateAccounts ()
    {
        $comptes_service = & CopixClassesFactory::Create ('comptes|ComptesService');

        if (!Admin::canAdmin())
              return CopixActionGroup::process ('genericTools|Messages::getError', array ('message'=>CopixI18N::get ('kernel|kernel.error.noRights'), 'back'=>CopixUrl::get ()));

        $limit = $this->getRequest ('limit', 5);
        $password = $this->getRequest ('password', '123456');

        $ppo = new CopixPPO();

        // dbuser : id_dbuser 	login_dbuser 	password_dbuser 	email_dbuser 	enabled_dbuser
        // kernel_link_bu2user : user_id 	bu_type 	bu_id


        $sql_params = array();
        $sql = '
            SELECT
                E.idEleve, E.nom, E.prenom1 AS prenom,
                KLB2U.*
            FROM
                kernel_bu_eleve E
            LEFT JOIN
                kernel_link_bu2user KLB2U ON E.idEleve=KLB2U.bu_id AND KLB2U.bu_type="USER_ELE"
            WHERE bu_type IS NULL';
        $ppo->user_ele = CopixDB::getConnection ()->doQuery ($sql, $sql_params);

        $sql = '
            SELECT
                P.numero, P.nom, P.prenom1 AS prenom, P.civilite, P.id_sexe,
                PE.role,
                KLB2U.*
            FROM
                kernel_bu_personnel_entite PE, kernel_bu_personnel P
            LEFT JOIN
                kernel_link_bu2user KLB2U ON P.numero=KLB2U.bu_id AND KLB2U.bu_type="USER_ENS"
            WHERE PE.type_ref="ECOLE" AND (PE.role=1 OR PE.role=2) AND PE.id_per=P.numero
            AND bu_type IS NULL';
        $ppo->user_ens = CopixDB::getConnection ()->doQuery ($sql, $sql_params);

        $sql = '
            SELECT
                P.numero, P.nom, P.prenom1 AS prenom, P.civilite, P.id_sexe,
                PE.role,
                KLB2U.*
            FROM
                kernel_bu_personnel_entite PE, kernel_bu_personnel P
            LEFT JOIN
                kernel_link_bu2user KLB2U ON P.numero=KLB2U.bu_id AND KLB2U.bu_type="USER_VIL"
            WHERE PE.type_ref="VILLE" AND PE.role=4 AND PE.id_per=P.numero
            AND bu_type IS NULL';
        $ppo->user_vil = CopixDB::getConnection ()->doQuery ($sql, $sql_params);

        $sql = '
            SELECT
                R.numero, R.nom, R.prenom1 AS prenom, R.civilite, R.id_sexe,
                KLB2U.*
            FROM
                kernel_bu_responsable R
            LEFT JOIN
                kernel_link_bu2user KLB2U ON R.numero=KLB2U.bu_id AND KLB2U.bu_type="USER_RES"
            WHERE bu_type IS NULL';
        $ppo->user_res = CopixDB::getConnection ()->doQuery ($sql, $sql_params);

        $fusible = $limit;
        echo "<pre>";

        foreach( $ppo->user_ele AS $eleve ) {
            if($fusible--<=0) break;

            $user_infos = Kernel::getUserInfo( 'USER_ELE', $eleve->idEleve );
            $login = $comptes_service->createLogin( $user_infos );


            $user_new = CopixDAOFactory::createRecord("kernel|kernel_copixuser");
            $user_new->login_dbuser = $login;
            $user_new->password_dbuser = md5($password);
            $user_new->email_dbuser = '';
            $user_new->enabled_dbuser = 1;

            _dao("kernel|kernel_copixuser")->insert( $user_new );

            $bu_new = _record("kernel|kernel_bu2user2");
            $bu_new->user_id = $user_new->id_dbuser;
            $bu_new->bu_type = 'USER_ELE';
            $bu_new->bu_id = $eleve->idEleve;

            _dao("kernel|kernel_bu2user2")->insert( $bu_new );

            echo $eleve->nom.";".$eleve->prenom.";".$login.";".$password."\n";
        }

        foreach( $ppo->user_ens AS $ens ) {
            if($fusible--<=0) break;

            $user_infos = Kernel::getUserInfo( 'USER_ENS', $ens->numero );
            $login = $comptes_service->createLogin( $user_infos );


            $user_new = CopixDAOFactory::createRecord("kernel|kernel_copixuser");
            $user_new->login_dbuser = $login;
            $user_new->password_dbuser = md5($password);
            $user_new->email_dbuser = '';
            $user_new->enabled_dbuser = 1;

            _dao("kernel|kernel_copixuser")->insert( $user_new );

            $bu_new = _record("kernel|kernel_bu2user2");
            $bu_new->user_id = $user_new->id_dbuser;
            $bu_new->bu_type = 'USER_ENS';
            $bu_new->bu_id = $ens->numero;

            _dao("kernel|kernel_bu2user2")->insert( $bu_new );

            echo $ens->nom.";".$ens->prenom.";".$login.";".$password."\n";
        }

        foreach( $ppo->user_vil AS $vil ) {
            if($fusible--<=0) break;

            $user_infos = Kernel::getUserInfo( 'USER_VIL', $vil->numero );
            $login = $comptes_service->createLogin( $user_infos );


            $user_new = CopixDAOFactory::createRecord("kernel|kernel_copixuser");
            $user_new->login_dbuser = $login;
            $user_new->password_dbuser = md5($password);
            $user_new->email_dbuser = '';
            $user_new->enabled_dbuser = 1;

            _dao("kernel|kernel_copixuser")->insert( $user_new );

            $bu_new = _record("kernel|kernel_bu2user2");
            $bu_new->user_id = $user_new->id_dbuser;
            $bu_new->bu_type = 'USER_VIL';
            $bu_new->bu_id = $vil->numero;

            _dao("kernel|kernel_bu2user2")->insert( $bu_new );

            echo $vil->nom.";".$vil->prenom.";".$login.";".$password."\n";
        }

        foreach( $ppo->user_res AS $res ) {
            if($fusible--<=0) break;

            $user_infos = Kernel::getUserInfo( 'USER_RES', $res->numero );
            $login = $comptes_service->createLogin( $user_infos );


            $user_new = CopixDAOFactory::createRecord("kernel|kernel_copixuser");
            $user_new->login_dbuser = $login;
            $user_new->password_dbuser = md5($password);
            $user_new->email_dbuser = '';
            $user_new->enabled_dbuser = 1;

            _dao("kernel|kernel_copixuser")->insert( $user_new );

            $bu_new = _record("kernel|kernel_bu2user2");
            $bu_new->user_id = $user_new->id_dbuser;
            $bu_new->bu_type = 'USER_RES';
            $bu_new->bu_id = $res->numero;

            _dao("kernel|kernel_bu2user2")->insert( $bu_new );

            echo $res->nom.";".$res->prenom.";".$login.";".$password."\n";
        }

        echo "</pre>";

        die();

        /*
         *
         *
         *
         * 				$user_infos = Kernel::getUserInfo( $user_type, $user_id );

                // V�rification de l'existance d'un login.
                // -> Si c'est le cas, il ne faut pas proposer un nouveau login.
                $bu_user = $bu_dao->getByBUID( $user_type, $user_id );

                if( !count($bu_user) ) {

                    $user_infos['login']  = $comptes_service->createLogin( $user_infos );



                        $user_new = CopixDAOFactory::createRecord("kernel|kernel_copixuser");
                        $user_new->login_dbuser = $pLogin[$typeid];
                        $user_new->password_dbuser = md5($pPasswd[$typeid]);
                        $user_new->email_dbuser = '';
                        $user_new->enabled_dbuser = 1;



                                                        if( $user_dao->insert( $user_new ) ) {

                            // Cr�ation du lien entre l'utilisateur de la base unique et le login.
                            $bu_new = _record("kernel|kernel_bu2user2");
                            $bu_new->user_id = $user_new->id_dbuser;
                            $bu_new->bu_type = $user_type;
                            $bu_new->bu_id = $user_id;

                            // Enregistrement et v�rification de l'insertion.
                            if( _dao("kernel|kernel_bu2user2")->insert( $bu_new ) ) {



        */
    }



}
