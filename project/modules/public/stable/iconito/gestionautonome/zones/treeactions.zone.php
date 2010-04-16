<?php
/**
 * @package     
 * @subpackage
 * @author      
 */

/**
 *
 */
class ZoneTreeActions extends CopixZone {

	function _createContent (& $toReturn) {
	  
	  $ppo = new CopixPPO ();                               
	  
	  // Récupération des paramètres
	  $ppo->nodeId = $this->getParam ('nodeId');
	  $ppo->nodeType = $this->getParam ('nodeType');
	  
    $toReturn = $this->_usePPO ($ppo, '_tree_actions.tpl');
  }
}