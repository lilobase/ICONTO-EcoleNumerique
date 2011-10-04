<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * @author Arnaud LEMAIRE <alemaire@cap-tic.fr>
 * @copyright (c) 2010 CAP-TIC
 */
class ZoneDashboardClasse extends enicZone {
    
    public function _createContent(&$toReturn){

        //load TPL
        $tpl = & new CopixTpl();

        //get the id for current zone
        $idZone = $this->getParam('idZone');

        //check the matrix right for current classe :
        if($this->matrix->classe($idZone)->_right->eleve->voir){
            $annuaireService =& CopixClassesFactory::Create ('annuaire|AnnuaireService');

            if( CopixConfig::exists('default|conf_Ceriseprim_actif') && CopixConfig::get ('default|conf_Ceriseprim_actif') ) {
            	$user = _currentUser ();
            	// print_r($idZone);
            	if($user->getExtra('type')=='USER_ENS') {
            		
            		$sql = "
            			SELECT *
            			FROM kernel_bu_ecole
            			JOIN kernel_bu_ecole_classe ON kernel_bu_ecole_classe.ecole=kernel_bu_ecole.numero
            			WHERE kernel_bu_ecole_classe.id=:id
            		";
            		$params = array(':id'=>$this->getParam('idZone'));
            		
            		$ecoles_list = _doQuery ($sql, $params);
            		
            		// print_r($ecoles_list);
            		
            		if(count($ecoles_list)) {
            			
	            		$url = CopixConfig::get ('default|conf_Ceriseprim_url')."/".$ecoles_list[0]->RNE."/ico.php?user=personnel-".$user->getExtra('id')."&date=".date('Y-m-d')."&key=".md5($ecoles_list[0]->RNE."personnel-".$user->getExtra('id').date('Y-m-d').CopixConfig::get ('default|conf_Ceriseprim_secret'));
	            		
	            		// https://www.cerise-prim.fr/0400178B/ico.php?user=personnel-706&date=2011-10-04&key=0e757c8e973cbbee17c48dc1ad9bb5ba


            			$tpl->assign('ceriseprim', $url);
            		}
            	}
            }
// die('stop');
            

            $elevesDatas = $annuaireService->getElevesInClasse($idZone);

            $tpl->assign('eleves', $elevesDatas);

            //return the html content
            $toReturn = $tpl->fetch ('zone.dashboard.classe.tpl');
            return true;
        }
        
        //if the uses have no right : display a default tpl
        $toReturn = $tpl->fetch ('zone.dashboard.noRight.tpl');
        return true;
    }

}
?>
