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
		$ppo->animateur = (_sessionGet('user_animateur')) ? 1 : 0;
		$type = $ppo->user->getExtra('type');
		$sexe = ($ppo->user->getExtra('sexe')==2) ? 2 : '';
		$ppo->usertype = _i18n('kernel|kernel.usertypes.'.strtolower($type).$sexe);
		
		$toReturn = $this->_usePPO ($ppo, 'userlogged.tpl');
	}
}