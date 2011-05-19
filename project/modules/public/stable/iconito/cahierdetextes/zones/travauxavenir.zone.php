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
	  
	  // Récupération des travaux suivant le type de l'utilisateur courant
	  $travailDAO = _ioDAO ('cahierdetextes|cahierdetextestravail');
	  if ($ppo->typeUtilisateur == 'USER_ELE') {
	    
	    $ppo->travaux = $travailDAO->findByEleveEtTypeDeTravail(_currentUser()->getExtra('id'), DAOCahierDeTextesTravail::TYPE_EN_CLASSE, $time);
	  }
	  elseif ($ppo->typeUtilisateur == 'USER_RES') {
	    
	    $ppo->travaux = $travailDAO->findByEleveEtTypeDeTravail($ppo->nid, DAOCahierDeTextesTravail::TYPE_EN_CLASSE, $time);
	  }
	  elseif ($ppo->typeUtilisateur == 'USER_ENS') {
	    
	    $ppo->travaux = $travailDAO->findByClasseEtTypeDeTravail($ppo->nid, DAOCahierDeTextesTravail::TYPE_EN_CLASSE, $time);
	  }

	  $toReturn = $this->_usePPO ($ppo, '_travaux_a_venir.tpl');
  }
}