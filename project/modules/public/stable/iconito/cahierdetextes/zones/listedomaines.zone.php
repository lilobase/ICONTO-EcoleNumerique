<?php
/**
* @package    Iconito
* @subpackage Cahierdetextes
* @author     Jérémy FOURNAISE
*/
class ZoneListeDomaines extends CopixZone {

	function _createContent (& $toReturn) {

	  $ppo = new CopixPPO ();
	  
	  // Récupération des paramètres 
	  $ppo->nid = $this->getParam('nid');
	  $ppo->jour  = $this->getParam('date_jour');
    $ppo->mois  = $this->getParam('date_mois');
    $ppo->annee = $this->getParam('date_annee');
    
    // Récupération des domaines de la classe
	  $domaineDAO = _ioDAO('cahierdetextes|cahierdetextesdomaine');
	  $ppo->domaines = $domaineDAO->findByClasse($ppo->nid);
	  
	  $toReturn = $this->_usePPO ($ppo, '_liste_domaines.tpl');
  }
}