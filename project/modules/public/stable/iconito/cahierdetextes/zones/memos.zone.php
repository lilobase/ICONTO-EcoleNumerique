<?php
/**
* @package    Iconito
* @subpackage Cahierdetextes
* @author     Jérémy FOURNAISE
*/
class ZoneMemos extends CopixZone {

	function _createContent (& $toReturn) {

	  $ppo = new CopixPPO ();
	  
	  // Récupération des paramètres
	  $ppo->nid   = $this->getParam('nid');
	  $ppo->jour  = $this->getParam('date_jour');
	  $ppo->mois  = $this->getParam('date_mois');
	  $ppo->annee = $this->getParam('date_annee');
    
    $time = mktime(0, 0, 0, $ppo->mois, $ppo->jour, $ppo->annee);
	  
	  // Récupération des mémos suivant le type de l'utilisateur courant
	  $memoDAO = _ioDAO ('cahierdetextes|cahierdetextesmemo');
	  if (Kernel::isEleve()) {
	    
	    $ppo->memos = $memoDAO->findByEleve(_currentUser()->getExtra('id'), true);
	  }
	  elseif (Kernel::isParent()) {
	    
	    $ppo->memos = $memoDAO->findByEleve($ppo->nid, true);
	  }
	  elseif (Kernel::isEnseignant()) {
	    
	    $ppo->memos = $memoDAO->findByClasse($ppo->nid, true);
	  }

	  $toReturn = $this->_usePPO ($ppo, '_memos.tpl');
  }
}