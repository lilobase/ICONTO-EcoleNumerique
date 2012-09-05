<?php
/**
* @package    Iconito
* @subpackage Classeur
* @author     Jérémy FOURNAISE
*/
class ZoneSelectionClasseurs extends CopixZone
{
    public function _createContent (& $toReturn)
    {
      $ppo = new CopixPPO ();

      // Récupération des paramètres
      $ppo->classeurId      = $this->getParam('classeurId');
      $ppo->targetType      = $this->getParam('targetType');
      $ppo->targetId        = $this->getParam('targetId');
      $ppo->withMainLocker  = $this->getParam('withMainLocker', true);
      $ppo->withSubLockers  = $this->getParam('withSubLockers', Kernel::getLevel('MOD_CLASSEUR', $ppo->classeurId) >= PROFILE_CCV_PUBLISH ? true : false);

      // Récupération des classeurs accessibles à l'utilisateur pour les actions de masse (copie / déplacement)
    $classeurIds = array();

      $nodes = Kernel::getMyNodes (_currentUser()->getExtra('type'), _currentUser()->getExtra('id'));
    foreach ($nodes as $node) {

      $modules = Kernel::getModEnabled($node->type, $node->id, _currentUser()->getExtra('type'), _currentUser()->getExtra('id'));
      foreach ($modules as $module) {

        if ($module->module_type == "MOD_CLASSEUR") {

          // Identification du classeur personnel de l'utilisateur
          if (strpos($module->node_type, 'USER_') !== false
            && ($module->node_type == _currentUser()->getExtra('type') && $module->node_id == _currentUser()->getExtra('id'))) {

            $ppo->classeurPersonnel = $module->module_id;
            $classeurIds[] = $module->module_id;
          } elseif (strpos($module->node_type, 'USER_') == false
            && Kernel::getLevel('MOD_CLASSEUR', $module->module_id) >= PROFILE_CCV_MEMBER) {

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