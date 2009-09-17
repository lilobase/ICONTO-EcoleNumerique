<?php
/**
 * Demo - Droits
 *
 * @package	Iconito
 * @subpackage	Kernel
 * @version   $Id: demo_auth.class.php,v 1.1 2006-10-26 16:28:27 cbeyer Exp $
 * @author	Christophe Beyer <cbeyer@cap-tic.fr>
 */


class Demo_Auth {

	/**
	 * Vérifie que l'usager courant peut installer la démo
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2006/10/26
	 * @return boolean True s'il a le droit, false sinon
	 */
	function canInstall () {
    //print_r($_SESSION);
		return (_currentUser()->user->getLogin() == 'admin');
	}

}

?>
