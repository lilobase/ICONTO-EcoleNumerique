<?php
/**
* @package    Iconito
* @subpackage Gestionautonome
* @author     Jérémy FOURNAISE
*/
class ZoneTreeActions extends CopixZone {

	function _createContent (& $toReturn) {
	  
	  $ppo = new CopixPPO ();                               
	  
	  // Récupération des paramètres
	  $ppo->nodeId = $this->getParam ('node_id');
	  $ppo->nodeType = $this->getParam ('node_type');
	  
	  $ppo->user = _currentUser ();
	  
    $toReturn = $this->_usePPO ($ppo, '_tree_actions.tpl');
  }
}