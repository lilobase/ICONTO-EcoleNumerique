<?php
/**
 * @package     
 * @subpackage  
 * @author      
 */

/**
 * 
 */
class ZoneShowTree extends CopixZone {

	function _createContent (& $toReturn) {
	  
	  $ppo = new CopixPPO ();                               
	  
	  // Récupération des paramètres
	  $ppo->root = $this->getParam ('root');

	  
	  // Récupération du noeud cible
	  $ppo->targetId   = $this->getParam ('targetId');
	  $ppo->targetType = $this->getParam ('targetType');
	  
	  $ppo->path = array ();
	  
	  $parents = Kernel::getNodeParents ($ppo->targetType, $ppo->targetId);
	 
	  if ($grCityId = $parents[0]['ALL']->grv_id_grv) {
	    
	    $ppo->path[] = array ('BU_GRVILLE', $grCityId);
	  }
	  elseif ($grCityId = $parents[0]['ALL']->vil_id_grville) {
	    
	    $ppo->path[] = array ('BU_GRVILLE', $grCityId);
	  }
	  
	  if ($cityId = $parents[0]['ALL']->vil_id_vi) {
	    
	    $ppo->path[] = array ('BU_VILLE', $cityId);
	  }
	  
	  if ($schoolId = $parents[0]['ALL']->eco_numero) {

  	  $ppo->path[] = array ('BU_ECOLE', $schoolId);
  	}

    $toReturn = $this->_usePPO ($ppo, '_show_tree.tpl');
  }
}