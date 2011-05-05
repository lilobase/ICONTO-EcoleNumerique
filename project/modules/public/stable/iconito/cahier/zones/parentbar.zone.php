<?php
/**
 * @package     
 * @subpackage
 * @author      
 */

/**
 *
 */
 
class ZoneParentBar extends CopixZone {

	function _createContent (& $toReturn) {

	  $ppo = new CopixPPO ();

    $toReturn = $this->_usePPO ($ppo, '_parent_bar.tpl');
  }
}