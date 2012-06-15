<?php
/**
* @package    Iconito
* @subpackage Classeur
* @author     Jérémy FOURNAISE
*/
class ZoneSelectionDossiers extends CopixZone {

	function _createContent (& $toReturn) {
	  
	  $ppo = new CopixPPO ();
	  
	  // Récupération des paramètres
	  $ppo->classeurId      = $this->getParam('classeurId');
	  $ppo->dossierId       = $this->getParam('dossierId');
	  $ppo->targetType      = $this->getParam('targetType');
	  $ppo->targetId        = $this->getParam('targetId');
	  $ppo->alwaysOpen      = $this->getParam('alwaysOpen');
	  $ppo->withLocker      = $this->getParam('withLocker', true);
	  
	  // Récupération des dossiers
	  $dossierDAO = _ioDAO('classeur|classeurdossier');
	  $ppo->dossiers = $dossierDAO->getEnfantsDirects($ppo->classeurId, $ppo->dossierId, $ppo->withLocker);

    _classInclude('classeurservice');
    $ppo->dossiersOuverts = ClasseurService::getFoldersTreeState ();
    if (!is_array($ppo->dossiersOuverts)) {
      
      $ppo->dossiersOuverts = array();
    }
    
	  $toReturn = $this->_usePPO ($ppo, '_selection_dossiers.tpl');
  }
}
