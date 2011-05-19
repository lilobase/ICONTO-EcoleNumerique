<?php
/**
* @package    Iconito
* @subpackage Cahierdetextes
* @author     Jérémy FOURNAISE
*/
class ZoneTravauxAVenir extends CopixZone {

	function _createContent (& $toReturn) {
	  
	  $ppo = new CopixPPO ();
	  
	  // Récupération des paramètres
	  $ppo->nid   = $this->getParam('nid');
	  $ppo->jour  = $this->getParam('date_jour');
	  $ppo->mois  = $this->getParam('date_mois');
	  $ppo->annee = $this->getParam('date_annee');
    
    $time = mktime(0, 0, 0, $ppo->mois, $ppo->jour, $ppo->annee);
    
    $ppo->typeUtilisateur = _currentUser()->getExtra('type');
	  
	  // Récupération des travaux à venir suivant le type de l'utilisateur courant
	  $travailDAO = _ioDAO ('cahierdetextes|cahierdetextestravail');
	  if ($ppo->typeUtilisateur == 'USER_ELE') {
	    
	    $ppo->travaux = $travailDAO->findTravauxAVenirParEleve(_currentUser()->getExtra('id'));
	  }
	  elseif ($ppo->typeUtilisateur == 'USER_RES') {
	    
	    $ppo->travaux = $travailDAO->findTravauxAVenirParEleve($ppo->nid);
	  }
	  elseif ($ppo->typeUtilisateur == 'USER_ENS') {
	    
	    $ppo->travaux = $travailDAO->findTravauxAVenirParClasse($ppo->nid);
	  }
    
	  $toReturn = $this->_usePPO ($ppo, '_travaux_a_venir.tpl');
  }
}