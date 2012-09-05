<?php
/**
 * Regroupements - ActionGroup
 *
 * @package	Iconito
 * @subpackage  Regroupements
 * @version     $Id$
 * @author      Frederic Mossmann <fmossmann@cap-tic.fr>
 * @copyright   2006 CAP-TIC
 * @link        http://www.cap-tic.fr
 */

class ActionGroupEcoles extends CopixActionGroup
{
    public function beforeAction ()
    {
        _currentUser()->assertCredential ('group:[current_user]');

    }



   public function getGroup ()
   {
           if(!Kernel::isAdmin())
            return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('||' ) );

        $tpl = new CopixTpl ();
        $tplGroupes = new CopixTpl ();

        CopixHTMLHeader::addCSSLink (_resource("styles/module_regroupements.css"));

        $regroupements_service = & CopixClassesFactory::Create ('regroupements|regroupements');

        $tpl->assign ('TITLE_PAGE', CopixI18N::get ('regroupements|regroupements.ecoles.titre'));
        $tpl->assign ('MENU', $regroupements_service->getMenu() );

        $dao_grecoles_gr2ecole = CopixDAOFactory::create("regroupements|grecoles_gr2ecole");
        $dao_grecoles = CopixDAOFactory::create("regroupements|grecoles");
        $dao_ecoles = CopixDAOFactory::create("kernel|kernel_tree_eco");
        $ecoles = $dao_ecoles->findAll();

        $ecolesByVille = array();
        foreach( $ecoles AS $ecole ) {
            if(!isset($ecolesByVille[$ecole->vil_id_vi])) {
                $ecolesByVille[$ecole->vil_id_vi] = new CopixPPO();
                $ecolesByVille[$ecole->vil_id_vi]->info = $ecole;
                $ecolesByVille[$ecole->vil_id_vi]->ecoles = array();

            }
            $ecolesByVille[$ecole->vil_id_vi]->ecoles[] = $ecole;
        }

        $tplGroupes->assign ('ecoles', $ecoles );
        $tplGroupes->assign ('ecolesByVille', $ecolesByVille );

        if( _request("delete") ) {
            $dao_grecoles->delete(_request("delete"));
            $dao_grecoles_gr2ecole->deleteByGroupe(_request("delete"));
            return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('regroupements|ecoles|'));
        }

        if( _request("save") && _request("save")==1 ) {
            $date = date("Y-m-d H:i:s");
            $user = Kernel::getUserInfo();

            if(_request("form_id")>0) {
                $groupe_infos  = $dao_grecoles->get(_request("form_id"));
                $groupe_infos->nom = _request("form_nom");
                $groupe_infos->updated_at = $date;
                $groupe_infos->updated_by = $user['login'];
                $dao_grecoles->update($groupe_infos);
                $dao_grecoles_gr2ecole->deleteByGroupe(_request("form_id"));
            } else {
                $groupe_infos  = CopixDAOFactory::createRecord("regroupements|grecoles");
                $groupe_infos->nom = _request("form_nom");
                if($groupe_infos->nom=='') $groupe_infos->nom='Sans nom';
                $groupe_infos->updated_at = date("Y-m-d H:i:s");
                $groupe_infos->updated_by = $user['login'];
                $dao_grecoles->insert($groupe_infos);
            }

            $grecoles_gr2ecole  = _record("regroupements|grecoles_gr2ecole");
            $grecoles_gr2ecole->id_groupe = $groupe_infos->id;
            $grecoles_gr2ecole->updated_at = $date;
            $grecoles_gr2ecole->updated_by = $user['login'];
            foreach( $ecoles AS $ecole ) {
                if( _request("ecole_".$ecole->eco_numero)==1 ) {
                    $grecoles_gr2ecole->id_ecole = $ecole->eco_numero;
                    _dao("regroupements|grecoles_gr2ecole")->insert($grecoles_gr2ecole);
                }
            }

            if(_request("form_id")==0) {
                return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('regroupements|ecoles|', array('groupe'=>$groupe_infos->id ) ));
            }
        }


        if( _request("groupe") ) {
            $tplGroupes->assign ('grecoles_id', _request("groupe") );
            $tplGroupes->assign ('grecoles_form', true );
            if(_request("groupe")>0) {
                // Edition d'un groupe
                $groupe_infos  = $dao_grecoles->get(_request("groupe"));
                $grecoles_ecoles_raw = $dao_grecoles_gr2ecole->findByGroupe(_request("groupe"));

                // Tableau indexé par id de ecole
                $grecoles_ecoles = array();
                foreach( $grecoles_ecoles_raw AS $grecoles_ecoles_item ) {
                    $grecoles_ecoles[$grecoles_ecoles_item->id_ecole] = $grecoles_ecoles_item;
                }
                $tplGroupes->assign ('grecoles_infos', $groupe_infos );
                $tplGroupes->assign ('grecoles_ecoles', $grecoles_ecoles );
            } else {
                // Création d'un nouveau groupe
            }
        }

        $grecoles_list = $dao_grecoles->findAll();
        // echo "<pre>"; print_r($grecoles_list); echo "</pre>";

        // print_r($grecoles_list);
        $tplGroupes->assign ('grecoles_list', $grecoles_list );

        $main = $tplGroupes->fetch ('getgrecoles.tpl');

        $tpl->assign ( 'MAIN', $main );


        return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
    }

}
