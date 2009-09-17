<?php
/**
* @package   petiteenfance
* @subpackage instruction
* @version   $Id: dossiers.actiongroup.php 64 2009-08-18 15:56:50Z cbeyer $
* @author   Christophe Beyer <cbeyer@cap-tic.fr>
* @copyright CAP-TIC
* @link      http://www.cap-tic.fr
*/

_classInclude("kernel|Tools");

class ActionGroupDossiers extends CopixActionGroup {


	/**
	 * Verifie les droits
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2009/08/06
	 */
	public function beforeAction (){
		//_currentUser()->assertCredential ('group:[current_user]');
	}

	/**
	 * Liste des dossiers
	 * 
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2009/08/06
	 */
	public function processDefault () {
		
		//echo date('d/m/Y H:i:s',1249561384);
    $ppo = new CopixPPO ();
		$ppo->TITLE_PAGE = "Dossiers";
		
		$ppo->BODY_ON_LOAD = "$('form').nom.focus();";
		
		$ppo->MENU = CopixZone::process ('kernel|menu', array ('level_0' => 'instruction',
      'level_1' => 'dossiers'));
		
		// Fil d'ariane
 		$cBc = CopixClassesFactory::getInstanceOf ('genericTools|CopixBreadcrumbs', 'main');
 		$cBc->addItem (CopixUrl::get ('instruction|dossiers|'), 'Dossiers');
 		$cBc->addItem (null, 'Liste de dossiers');
		
		

		return _arPPO ($ppo, 'dossiers.tpl');
	}
	
	
	
}

?>
