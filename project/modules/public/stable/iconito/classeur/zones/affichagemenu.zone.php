<?php
/**
* @package    Iconito
* @subpackage Classeur
* @author     Jérémy FOURNAISE
*/
class ZoneAffichageMenu extends CopixZone {

	function _createContent (& $toReturn) {
	  
	  $ppo = new CopixPPO ();
	  
	  // Récupération des paramètres
	  $ppo->classeurId = $this->getParam('classeurId');
	  $ppo->dossierId  = $this->getParam('dossierId');
	  
	  // Gestion des droits
	  $ppo->niveauUtilisateur = Kernel::getLevel('MOD_CLASSEUR', $ppo->classeurId);
	  $ppo->typeUtilisateur   = _currentUser()->getExtra('type');
    
	  $toReturn = $this->_usePPO ($ppo, '_affichage_menu.tpl');
  }
}