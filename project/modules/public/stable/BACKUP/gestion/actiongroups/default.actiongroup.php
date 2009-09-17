<?php
/**
* @package   petiteenfance
* @subpackage gestion
* @version   $Id: default.actiongroup.php 52 2009-08-13 14:14:21Z fmossmann $
* @author   Christophe Beyer <cbeyer@cap-tic.fr>
* @copyright CAP-TIC
* @link      http://www.cap-tic.fr
*/

class ActionGroupDefault extends CopixActionGroup {

	/**
	 * Verifie les droits
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2009/08/06
	 */
	public function beforeAction (){
		_currentUser()->assertCredential ('group:[current_user]');
	}


	/**
	 * Accueil
	 * 
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2009/08/06
	 */
	public function processDefault () {
		// return _arRedirect (_url ('instruction|dossiers|'));
		
		$ppo = new CopixPPO ();
		$ppo->TITLE_PAGE = "Gestion";
		
		// Menu
		$ppo->MENU = CopixZone::process (
			'kernel|menu', array (
				'level_0' => 'gestion',
				'level_1' => ''
			)
		);
		
		// Fil d'ariane
 		$cBc = CopixClassesFactory::getInstanceOf ('genericTools|CopixBreadcrumbs', 'main');
 		//$cBc->addItem (CopixUrl::get ('kernel||'), 'Administration');
 		$cBc->addItem (null, 'Gestion');
		
		return _arPPO ($ppo, 'default.tpl');
		
	}
	
	


}

?>
