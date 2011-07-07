<?php
/**
* @package    Iconito
* @subpackage Classeur
* @author     Jérémy FOURNAISE
*/
class ZoneArborescenceDossiers extends CopixZone {

	function _createContent (& $toReturn) {
	  
	  $ppo = new CopixPPO ();
	  
	  // Récupération des paramètres
	  $ppo->classeurId      = $this->getParam('classeurId');
	  $ppo->dossierId       = $this->getParam('dossierId');
	  $ppo->dossierCourant  = $this->getParam('dossierCourant');
	  $ppo->vue             = $this->getParam('vue');
	  
	  // Récupération des dossiers
	  $dossierDAO = _ioDAO('classeur|classeurdossier');
	  $ppo->dossiers = $dossierDAO->getEnfantsDirects($ppo->classeurId, $ppo->dossierId);

    _classInclude('classeurservice');
    $ppo->dossiersOuverts = ClasseurService::getFoldersTreeState ();
    if (!is_array($ppo->dossiersOuverts)) {
      
      $ppo->dossiersOuverts = array();
    }
    
	  $toReturn = $this->_usePPO ($ppo, '_arborescence_dossiers.tpl');
  }
}