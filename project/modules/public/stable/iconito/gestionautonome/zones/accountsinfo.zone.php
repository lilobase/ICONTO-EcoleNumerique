<?php
/**
 * @package     
 * @subpackage
 * @author      
 */

/**
 *
 */
class ZoneAccountsInfo extends CopixZone {

	function _createContent (& $toReturn) {
	  
	  $ppo = new CopixPPO ();                               
    
    $ppo->sessionId = $this->getParam ('sessionId');

    $toReturn = $this->_usePPO ($ppo, '_accounts_info.tpl');
  }
}