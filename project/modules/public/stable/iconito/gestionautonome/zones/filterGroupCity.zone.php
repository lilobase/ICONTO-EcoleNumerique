<?php
/**
 * @package     
 * @subpackage
 * @author      
 */

/**
 *
 */
class ZoneFilterGroupCity extends CopixZone {

	function _createContent (& $toReturn) {
	  
	  $ppo = new CopixPPO ();                               
    
	  // Récupérations des filtres en session
	  $ppo->selected = $this->getParam ('selected', null);
	  
    $ppo->cityGroupsIds = array('');
	  $ppo->cityGroupsNames = array('');
	  
	  $cityGroups = _ioDAO ('kernel_bu_groupe_villes')->findAll ();
	  foreach ($cityGroups as $cityGroup) {
	    
	    $ppo->cityGroupsIds[]   = $cityGroup->id_grv;
	    $ppo->cityGroupsNames[] = $cityGroup->nom_groupe;
	  }
	  
    $toReturn = $this->_usePPO ($ppo, '_filter_groupcity.tpl');
  }
}