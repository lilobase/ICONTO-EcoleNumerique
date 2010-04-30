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
	  
	  // Récupération du noeud ROOT
	  $groupcity = Kernel::getNodeChilds ('ROOT', 0);
	  $groupcity = Kernel::filterNodeList ($groupcity, 'BU_GRVILLE');
    
    $ppo->root = $groupcity[0];
    
    // Récupération de l'année scolaire
    $ppo->grade  = $this->getParam ('grade');
	  
	  // Récupération du noeud cible
	  $ppo->targetId   = $this->getParam ('targetId');
	  $ppo->targetType = $this->getParam ('targetType');
	  
	  $ppo->path = array ();
	  
	  if (isset ($ppo->targetType) && isset($ppo->targetId)) {
	    
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
	  }
	  
    $toReturn = $this->_usePPO ($ppo, '_show_tree.tpl');
  }
}