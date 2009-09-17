<?php
/**
 * @package     standard
 * @subpackage  auth
 * @author      Sébastien CAS
 */

/**
 * Zone affichant l'utilisateur connecté
 */
class ZoneUserLogged extends CopixZone {

	function _createContent (& $toReturn) {
			
		$ppo = new CopixPPO ();		
		
		$ppo->user = _currentUser ();
		
		$toReturn = $this->_usePPO ($ppo, 'userlogged.tpl');
	}
}