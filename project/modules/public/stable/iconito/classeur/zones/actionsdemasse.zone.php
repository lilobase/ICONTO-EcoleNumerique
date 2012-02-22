<?php
/**
* @package    Iconito
* @subpackage Classeur
* @author     Jérémy FOURNAISE
*/
class ZoneActionsDeMasse extends CopixZone {

	function _createContent (& $toReturn) {
	  
	  $ppo = new CopixPPO ();
	  
	  // Récupération des paramètres
	  $ppo->classeurId          = $this->getParam('classeurId');
	  $ppo->dossierId           = $this->getParam('dossierId');
	  $ppo->idClasseurPersonnel = _sessionGet('classeur|idClasseurPersonnel');
	  
	  // Gestion des droits
	  $ppo->niveauUtilisateur = Kernel::getLevel('MOD_CLASSEUR', $ppo->classeurId);
    
	  $toReturn = $this->_usePPO ($ppo, '_actions_de_masse.tpl');
  }
}