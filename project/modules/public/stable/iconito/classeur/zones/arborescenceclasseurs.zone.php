<?php
/**
* @package    Iconito
* @subpackage Classeur
* @author     Jérémy FOURNAISE
*/
class ZoneArborescenceClasseurs extends enicZone
{
    public function _createContent (& $toReturn)
    {
      $ppo = new CopixPPO ();

      // Récupération des paramètres
      $ppo->classeurId      = $this->getParam('classeurId');
      $ppo->dossierCourant  = $this->getParam('dossierCourant');

      // Paramètres pour la vue popup
      $ppo->field           = $this->getParam('field');
      $ppo->format          = $this->getParam('format');
      $ppo->withPersonal    = $this->getParam('withPersonal', true);
      $ppo->moduleType      = $this->getParam('moduleType', null);
      $ppo->moduleId        = $this->getParam('moduleId', null);

      // Récupération des classeurs accessibles à l'utilisateur
    $classeurIds = array();
    if ($ppo->moduleType == 'MOD_CAHIERDETEXTES') {

      $node = Kernel::getModParentInfo($ppo->moduleType, $ppo->moduleId);
      $modules = Kernel::getModEnabled($node['type'], $node['id'], _currentUser()->getExtra('type'), _currentUser()->getExtra('id'));
      foreach ($modules as $module) {

        if ($module->module_type == "MOD_CLASSEUR") {
          // Identification du classeur personnel de l'utilisateur
          if (strpos($module->node_type, 'USER_') !== false
            && ($module->node_type == _currentUser()->getExtra('type') && $module->node_id == _currentUser()->getExtra('id'))) {

            if ($ppo->withPersonal) {

              $ppo->classeurPersonnel = $module->module_id;
              $classeurIds[] = $module->module_id;
            }
          } elseif (!strpos($module->node_type, 'USER_')
            && Kernel::getLevel('MOD_CLASSEUR', $module->module_id) >= PROFILE_CCV_READ) {

            $classeurIds[] = $module->module_id;
          }
        }
      }
    } else {

      $nodes = Kernel::getMyNodes (_currentUser()->getExtra('type'), _currentUser()->getExtra('id'));
      foreach ($nodes as $node) {

        $modules = Kernel::getModEnabled($node->type, $node->id, _currentUser()->getExtra('type'), _currentUser()->getExtra('id'));
        foreach ($modules as $module) {

          if ($module->module_type == "MOD_CLASSEUR") {
            // Identification du classeur personnel de l'utilisateur
            if (strpos($module->node_type, 'USER_') !== false
              && ($module->node_type == _currentUser()->getExtra('type') && $module->node_id == _currentUser()->getExtra('id'))) {

              if ($ppo->withPersonal) {

                $ppo->classeurPersonnel = $module->module_id;
                $classeurIds[] = $module->module_id;
              }
            } elseif (!strpos($module->node_type, 'USER_')
              && Kernel::getLevel('MOD_CLASSEUR', $module->module_id) >= PROFILE_CCV_READ) {

              $classeurIds[] = $module->module_id;
            }
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

    $this->flash->addCycle();

      $toReturn = $this->_usePPO ($ppo, '_arborescence_classeurs.tpl');
  }
}