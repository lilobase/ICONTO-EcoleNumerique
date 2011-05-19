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
    
    // Récupération des domaines de la classe
	  $domaineDAO = _ioDAO('cahierdetextes|cahierdetextesdomaine');
	  $ppo->domaines = $domaineDAO->findByClasse($ppo->nid);
	  
	  $toReturn = $this->_usePPO ($ppo, '_liste_domaines.tpl');
  }
}