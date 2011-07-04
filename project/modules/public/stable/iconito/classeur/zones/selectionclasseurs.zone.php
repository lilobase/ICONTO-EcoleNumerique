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
	  
	  // Récupération des classeurs accessibles à l'utilisateur pour les actions de masse (copie / déplacement)
    $classeurIds = array();
    
    $typeUtilisateur = _currentUser()->getExtra('type');
	  if ($typeUtilisateur == 'USER_ELE') {
	    
	    $ppo->classeurPersonnel = _sessionGet('classeur|idClasseurPersonnel');
	    $classeurIds[] = $ppo->classeurPersonnel;
	  }
	  else {
	    
	    $nodes = Kernel::getMyNodes (_currentUser()->getExtra('type'), _currentUser()->getExtra('id'));
      foreach ($nodes as $node) {

        $modules = Kernel::getModEnabled($node->type, $node->id, _currentUser()->getExtra('type'), _currentUser()->getExtra('id'));
        foreach ($modules as $module) {

          if ($module->module_type == "MOD_CLASSEUR") {

            // Identification du classeur personnel de l'utilisateur
            if (strpos($module->node_type, 'USER_') !== false && $module->node_id == _currentUser()->getExtra('id')) {

              $ppo->classeurPersonnel = $module->module_id;
            }

            $classeurIds[] = $module->module_id;
          }
        }
      }
	  }
	  
    // Dédoublonnage des classeurs (responsables avec plusieurs enfants)
    $classeurIds = array_unique($classeurIds);
    
    $ppo->classeurs = array();
    $classeurDAO = _dao("classeur|classeur");
    foreach ($classeurIds as $classeurId) {
      
      $classeur = $classeurDAO->get($classeurId);
      $ppo->classeurs[] = $classeur;
    }
    
    _classInclude('classeurservice');
    $ppo->classeursOuverts = ClasseurService::getClasseursTreeState ();
    if (!is_array($ppo->classeursOuverts)) {
      
      $ppo->classeursOuverts = array();
    }

	  $toReturn = $this->_usePPO ($ppo, '_selection_classeurs.tpl');
  }
}