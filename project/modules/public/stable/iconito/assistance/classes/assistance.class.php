<?php
/**
 * Assistance - Classes
 *
 * @package	Iconito
 * @subpackage  Assistance
 * @version     $Id: assistance.class.php,v 1.1 2009-09-30 10:06:20 fmossmann Exp $
 * @author      Frederic Mossmann <fmossmann@cap-tic.fr>
 * @copyright   2006 CAP-TIC
 * @link        http://www.cap-tic.fr
 */


class Assistance {
	function getAssistanceUsers() {
		
		$animateur_dao = & CopixDAOFactory::create("kernel|kernel_animateurs");
		$animateurs2regroupements_dao = & CopixDAOFactory::create("kernel|kernel_animateurs2regroupements");
		$grvilles_gr2ville_dao = & CopixDAOFactory::create("regroupements|grvilles_gr2ville");
		$grecoles_gr2ecole_dao = & CopixDAOFactory::create("regroupements|grecoles_gr2ecole");
		$prefs_dao = & CopixDAOFactory::create("prefs|prefs");
		
		$ecoles_dao = & CopixDAOFactory::create("kernel|kernel_tree_eco");
		$personnels_dao = & CopixDAOFactory::create("kernel|kernel_bu_personnel");
		
		$animateur = $animateur_dao->get(_currentUser()->getExtra("type"), _currentUser()->getExtra("id"));
		// echo "<pre>"; print_r($animateur); die("</pre>");
		
		
		$regroupements_list = $animateurs2regroupements_dao->findByUser(_currentUser()->getExtra("type"), _currentUser()->getExtra("id"));
		// echo "<pre>"; print_r($regroupements_list); die("</pre>");
		
		$users=array();
		
		// Pour chaque regroupement
		foreach($regroupements_list AS $regroupement_item) {
			
			// Si c'est un groupe de villes...
			if($regroupement_item->regroupement_type=='villes') {
				// Pour toutes les villes du grvilles
				$villes = $grvilles_gr2ville_dao->findByGroupe($regroupement_item->regroupement_id);
				foreach( $villes AS $ville ) {
					
					// Si on n'a jamais traité la ville (qui peut être dans plusieurs regroupements)
					if(!isset($users[$ville->id_ville])) {
						$users[$ville->id_ville] = array();
						
						// On cherche les ecoles de la ville (format DAO)
						$ecoles = $ecoles_dao->getByVille($ville->id_ville);
						// On traite la sortie du DAO pour avoir un array propre
						foreach( $ecoles AS $ecole ) {
							$users[$ville->id_ville][$ecole->eco_numero] = $ecole;
							$users[$ville->id_ville][$ecole->eco_numero]->personnels = $personnels_dao->getPersonnelInEcole($ecole->eco_numero);
						}
						
						// echo "<pre>"; print_r($users[$ville->id_ville]); echo("</pre>");
					}
				}
			}
			
			// Si c'est un groupe d'ecoles...
			if($regroupement_item->regroupement_type=='ecoles') {
				$ecoles = $grecoles_gr2ecole_dao->findByGroupe($regroupement_item->regroupement_id);
				// echo "<pre>"; print_r($ecoles); echo("</pre>");
				
				foreach( $ecoles AS $ecole ) {
					$ecole_info = $ecoles_dao->get($ecole->id_ecole);
					$ecole_info->personnels = $personnels_dao->getPersonnelInEcole($ecole->id_ecole);
					
					// echo "<pre>"; print_r($ecole_info); echo("</pre>");
					
					if(!isset($users[$ecole_info->vil_id_vi])) $users[$ecole_info->vil_id_vi] = array();
					$users[$ecole_info->vil_id_vi][$ecole_info->eco_numero] = $ecole_info;
					// echo "<pre>"; print_r($users); die("</pre>");
					
				}
			}
		}
		// echo "<pre>"; print_r($users); die("</pre>");
		
		$default_assistance = CopixConfig::exists('|conf_assistance_default')?CopixConfig::get('|conf_assistance_default'):0;
		foreach($users AS $ville_id => $ville) foreach($ville AS $ecole_id => $ecole) foreach($ecole->personnels AS $personnel_id => $personnel) {
			$assistance = $prefs_dao->get( $personnel->id_copix, 'prefs', 'assistance' );
			if( $assistance === false ) $user_assistance = $default_assistance;
			elseif( $assistance->prefs_value == "1" ) $user_assistance = 1;
			else $user_assistance = 0;
			// echo "<pre>"; print_r($personnel); echo("</pre>");
			// echo "<pre>"; print_r($assistance); echo("</pre>");
			
			$users[$ville_id][$ecole_id]->personnels[$personnel_id]->assistance = $user_assistance;
		}
		
		return $users;
	}
	
}

?>
