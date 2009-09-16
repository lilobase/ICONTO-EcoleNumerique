<?php
/**
* @package   petiteenfance
* @subpackage kernel
* @version   $Id: default.actiongroup.php 65 2009-08-19 09:57:37Z cbeyer $
* @author   Christophe Beyer <cbeyer@cap-tic.fr>
* @copyright CAP-TIC
* @link      http://www.cap-tic.fr
*/

_classInclude ("kernel|Tools");

class ActionGroupDefault extends CopixActionGroup {

	/**
	 * Verifie les droits
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2009/03/30
	 */
	public function beforeAction (){
		_currentUser()->assertCredential ('group:[Admin]');
	}



	/**
	 * Accueil de l'admin
	 * 
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2009/02/13
	 */
	public function processDefault () {
		
		$ppo = new CopixPPO ();
		$ppo->TITLE_PAGE = "Administration";
		
		// Menu
		$ppo->MENU = CopixZone::process (
			'kernel|menu', array (
				'level_0' => 'kernel',
				'level_1' => ''
			)
		);
		
		// Fil d'ariane
 		$cBc = CopixClassesFactory::getInstanceOf ('genericTools|CopixBreadcrumbs', 'main');
 		//$cBc->addItem (CopixUrl::get ('kernel||'), 'Administration');
 		$cBc->addItem (null, 'Administration');
		
		return _arPPO ($ppo, 'default.tpl');
	}
	

/**
	 * PhpInfo
	 * 
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2009/02/26
	 */
	public function processPhpInfo () {
		
		$ppo = new CopixPpo ();
		phpinfo();
		die();
		return _arDirectPPO ($ppo, 'generictools|blank.tpl');
	}
	
	
	
	
}

?>