<?php
/**
 * @package     
 * @subpackage
 * @author      
 */

/**
 *
 */
class ZoneMultipleAccountsInfo extends CopixZone {

	function _createContent (& $toReturn) {
	  
	  $ppo = new CopixPPO ();                               

    $toReturn = $this->_usePPO ($ppo, '_multiple_accounts_info.tpl');
  }
}