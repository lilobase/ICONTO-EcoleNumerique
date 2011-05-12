<?php
/**
 * @package     
 * @subpackage
 * @author      
 */

/**
 *
 */
class ZoneMemos extends CopixZone {

	function _createContent (& $toReturn) {
	  
	  CopixHtmlHeader::addJSLink(CopixUrl::get().'js/jquery.easynews.js');
	  
	  $ppo = new CopixPPO ();
	  
	  // Récupération des paramètres
	  $ppo->nid   = $this->getParam('nid');
	  $ppo->jour  = $this->getParam('date_jour');
	  $ppo->mois  = $this->getParam('date_mois');
	  $ppo->annee = $this->getParam('date_annee');
    
    $time = mktime(0, 0, 0, $ppo->mois, $ppo->jour, $ppo->annee);

    // S'il s'agit d'un parent, récupération de l'identifiant de l'élève nécessaire
    if (Kernel::isParent()) {
      
      $affectationEleveDAO = _ioDAO('kernel|kernel_bu_ele_affect');
      $assignationCourante = $affectationEleveDAO->getCurrentAffectByStudent ($ppo->nid);
      
      $idClasse = $assignationCourante->affect_classe;
      $idEleve  = $ppo->nid;
    }
	  
	  $memoDAO = _ioDAO ('cahierdetextes|cahierdetextesmemo');
	  if (Kernel::isEleve()) {
	    
	    $ppo->memos = $memoDAO->findByEleve(_currentUser()->getExtra('id'), $time);
	  }
	  elseif (Kernel::isParent()) {
	    
	    $ppo->memos = $memoDAO->findByEleve($idEleve, $time);
	  }
	  elseif (Kernel::isEnseignant()) {
	    
	    $ppo->memos = $memoDAO->findByClasse($ppo->nid, $time);
	  }

	  $toReturn = $this->_usePPO ($ppo, '_memos.tpl');
  }
}