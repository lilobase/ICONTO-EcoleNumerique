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
	  $ppo->current    = $this->getParam('current');
	  
	  // Gestion des droits
	  $ppo->niveauUtilisateur = Kernel::getLevel('MOD_CLASSEUR', $ppo->classeurId);
	  $ppo->typeUtilisateur   = _currentUser()->getExtra('type');
	  
	  $ppo->vue = !is_null(_sessionGet('classeur|typeVue')) ? _sessionGet('classeur|typeVue') : 'liste';
    
	  $toReturn = $this->_usePPO ($ppo, '_affichage_menu.tpl');
  }
}