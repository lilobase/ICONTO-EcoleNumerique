<?php
/**
* @package   petiteenfance
* @subpackage instruction
* @version   $Id: default.actiongroup.php 36 2009-08-10 10:33:06Z cbeyer $
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
		
    return _arRedirect (_url ('instruction|dossiers|'));
		
	}
	
	
}

?>
