<?php
/**
 * @package     
 * @subpackage  
 * @author      
 */

/**
 * 
 */
class ZoneShowTreeChildren extends CopixZone {

	function _createContent (& $toReturn) {
	  
	  $ppo = new CopixPPO ();                               
	  
	  // Récupération des paramètres
	  $node = $this->getParam('node');
	  $ppo->grade = $this->getParam('grade');
	  
	  if (!is_null ($node)) {
	    
	    // Récupération des fils
	    $ppo->child = Kernel::getNodeChilds ($node['type'], $node['id'], true, array('annee' => $ppo->grade));
	    $ppo->child = Kernel::filterNodeList ($ppo->child, 'BU_*' ); 
	  }
	  
	  // Récupération du noeud cible
	  $ppo->targetId   = $this->getParam ('targetId');
	  $ppo->targetType = $this->getParam ('targetType');

	  $ppo->path = $this->getParam ('path');
	  
	  $toReturn = $this->_usePPO ($ppo, '_show_tree_children.tpl');
  }
}