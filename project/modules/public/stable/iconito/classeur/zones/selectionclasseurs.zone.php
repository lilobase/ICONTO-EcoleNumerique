<?php
/**
* @package    Iconito
* @subpackage Classeur
* @author     Jérémy FOURNAISE
*/
class ZoneSelectionClasseurs extends CopixZone {

	function _createContent (& $toReturn) {
	  
	  $ppo = new CopixPPO ();
	  
	  // Récupération des paramètres
	  $ppo->classeurId      = $this->getParam('classeurId');
	  $ppo->targetType      = $this->getParam('targetType');
	  $ppo->targetId        = $this->getParam('targetId');
	  
	  // Récupération des classeurs accessibles à l'utilisateur
    $classeurIds = array();
    $nodes = Kernel::getMyNodes (_currentUser()->getExtra('type'), _currentUser()->getExtra('id'));
    foreach ($nodes as $node) {
      
      $modules = Kernel::getModEnabled($node->type, $node->id, _currentUser()->getExtra('type'), _currentUser()->getExtra('id'));
      foreach ($modules as $module) {
        
        if ($module->module_type == "MOD_CLASSEUR") {
          
          if (strpos($module->node_type, 'USER_') !== false) {
            
            $ppo->classeurPersonnel = $module->module_id;
          }
          
          $classeurIds[] = $module->module_id;
        }
      }
    }
    
    $ppo->classeurs = array();
    $classeurDAO = _dao("classeur|classeur");
    foreach ($classeurIds as $classeurId) {
      
      $classeur = $classeurDAO->get($classeurId);
      $ppo->classeurs[] = $classeur;
    }

	  $toReturn = $this->_usePPO ($ppo, '_selection_classeurs.tpl');
  }
}