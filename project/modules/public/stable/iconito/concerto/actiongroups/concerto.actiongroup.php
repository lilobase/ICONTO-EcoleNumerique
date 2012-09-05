<?php

/**
* @package  Iconito
* @subpackage Concerto
* @version   $Id: concerto.actiongroup.php,v 1.6 2009-09-02 09:23:44 cbeyer Exp $
* @author   Frédéric Mossmann
* @copyright 2009 CAP-TIC
* @link      http://www.cap-tic.fr
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

/**
 * @author	Frédéric Mossmann
 */
class ActionGroupConcerto extends CopixActionGroup
{
    public function beforeAction ()
    {
        _currentUser()->assertCredential ('group:[current_user]');

    }

    /**
     * go
     *
     * Appel automatique, avec détection du noeud à afficher.
     * @author	Frédéric Mossmann
     * @since	10.03.2009
     *
     */
    public function go ()
    {
        if (!Kernel::is_connected()) return CopixActionGroup::process ('genericTools|Messages::getError', array ('message'=>CopixI18N::get ('kernel|kernel.error.nologin'), 'back'=>CopixUrl::get ('auth|default|login')));

        CopixHtmlHeader::addJSLink(CopixUrl::get().'js/iconito/module_concerto.js');



        if( !_request("id") || !ereg('^[0-9]+$', _request("id")) ) {
            return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('kernel||getNodes'));
        }

        $sql = 'SELECT login,password FROM kernel_bu_auth WHERE node_type=\'responsable\' AND node_id='._currentUser()->getExtra('id').' AND id=\''.addslashes(_request("id")).'\' AND service=\'concerto\'';
        $concerto = _doQuery($sql);
        if(!$concerto) {
            return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('kernel||getNodes'));
        }

        $tpl = new CopixTpl ();
        $tpl->assign ('TITLE_PAGE', CopixI18N::get ('concerto|concerto.title'));
        // $tpl->assign ('MENU', '<a href="'.CopixUrl::get ('groupe||getListPublic').'">'.CopixI18N::get ('groupe|groupe.annuaire').'</a> :: <a href="'.CopixUrl::get ('groupe||getListMy').'">'.CopixI18N::get ('groupe|groupe.my').'</a>');

        $tplListe = new CopixTpl ();
        $tplListe->assign ('login', $concerto[0]->login);
        $tplListe->assign ('password', $concerto[0]->password );
        $result = $tplListe->fetch("concerto-form.tpl");

        $tpl->assign ("MAIN", $result);
        $tpl->assign ('BODY_ON_LOAD', 'concerto_redirect();');

        return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);


    }

    public function logout ()
    {
        return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('kernel||'));
    }

    public function init ()
    {
        $path2data = realpath(COPIX_VAR_PATH)."/data/concerto/logins-concerto--".date('Y-m-d').".csv";

        if( CopixConfig::exists('|conf_ModConcerto_ip') ) {
            $ips = CopixConfig::get('|conf_ModConcerto_ip');
            $ips_array = explode( ";", $ips );

            if(!in_array($_SERVER["REMOTE_ADDR"], $ips_array)) die('Forbidden : bad IP address');
        } else die('Forbidden : check config');

        $comptes_service = & CopixClassesFactory::Create ('comptes|ComptesService');
        $user_dao = & CopixDAOFactory::create("kernel|kernel_copixuser");
        $bu_dao = & CopixDAOFactory::create("kernel|kernel_bu2user");

        // $sql = 'SELECT KB_RES.numero, KB_RES.nom, KB_RES.prenom1, K_LB2U.user_id, K_LB2U.bu_type, K_LB2U.bu_id FROM kernel_bu_responsable KB_RES LEFT JOIN kernel_link_bu2user K_LB2U ON KB_RES.numero=K_LB2U.bu_id AND K_LB2U.bu_type="USER_RES" WHERE K_LB2U.bu_type IS NULL';
        $sql = 'SELECT KB_RES.numero, KB_RES.nom, KB_RES.prenom1, K_LB2U.user_id, K_LB2U.bu_type, K_LB2U.bu_id, KB_SER.id_ext FROM kernel_bu_services KB_SER, kernel_bu_responsable KB_RES LEFT JOIN kernel_link_bu2user K_LB2U ON KB_RES.numero=K_LB2U.bu_id AND K_LB2U.bu_type="USER_RES" WHERE KB_SER.id_local=KB_RES.numero AND KB_SER.service="concerto" AND KB_SER.objet="responsable" AND K_LB2U.bu_type IS NULL';

        $a = _doQuery($sql);

        if( count($a)>0 ) {

            $user_infos = array();

            $file = fopen( $path2data, "a");
            fseek  ( $file, 0, SEEK_END );

// echo $path2data."<br/>";
// echo "Position=".ftell($file)."<br/>";

            if(ftell($file)==0) {
                $entete = array('id','login','password');
                fputcsv ( $file, $entete, ";" );
// echo "Position=".ftell($file)." (après ajout entête)<br/>";
            }


            $cpt=0;
// $limit=1;

            foreach( $a AS $a_key => $a_item ) {
                $user_infos['type'] = "USER_RES";
                $user_infos['id'] = $a_item->numero;
                $user_infos['nom'] = $a_item->nom;
                $user_infos['prenom'] = $a_item->prenom1;

                // $a[$a_key]->user_infos = Kernel::getUserInfo( "USER_RES", $a_item->numero );
                $a[$a_key]->login = $comptes_service->createLogin( $user_infos );
                // $a[$a_key]->user_infos = Kernel::getUserInfo( "USER_RES", $a_item->numero );
                // $a[$a_key]->login = $comptes_service->createLogin( $a[$a_key]->user_infos );
                $a[$a_key]->passwd = $comptes_service->createPasswd();


                // Création d'un login dans CopixUser
                $user_new = CopixDAOFactory::createRecord("kernel|kernel_copixuser");
                $user_new->login_dbuser = $a[$a_key]->login;
                $user_new->password_dbuser = md5($a[$a_key]->passwd);
                $user_new->email_dbuser = '';
                $user_new->enabled_dbuser = 1;
                $user_dao->insert( $user_new );

                // Création du lien entre l'utilisateur de la base unique et le login.
                $bu_new = CopixDAOFactory::createRecord("kernel|kernel_bu2user");
                $bu_new->user_id = $user_new->id_dbuser;
                $bu_new->bu_type = $user_infos['type'];
                $bu_new->bu_id = $user_infos['id'];
                $bu_dao->insert( $bu_new );

                $ligne = array($a[$a_key]->id_ext,$a[$a_key]->login,$a[$a_key]->passwd);
                fputcsv ( $file, $ligne, ";" );
                $cpt++;
// if( $cpt>=$limit) break;
            }

// echo "Position=".ftell($file)." (après enregistrement)<br/>";
            fclose($file);

        }

        /*
        $tplListe = new CopixTpl ();
        $tplListe->assign ('data', $a);
        $result = $tplListe->fetch("concerto-init.tpl");
        */

        if( $handle = fopen($path2data, "r") ) {
            $contents = fread($handle, filesize($path2data));
            fclose($handle);
        } else {
            $contents = 'vide '.$path2data;
        }

        echo "<pre>".$contents."</pre>";

        die();
    }
}

