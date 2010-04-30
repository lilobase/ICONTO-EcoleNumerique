<?php
/**
 * @package     
 * @subpackage
 * @author      
 */

/**
 *
 */
class ZoneCreatePersonInCharge extends CopixZone {

	function _createContent (& $toReturn) {

	  $ppo = new CopixPPO ();                               
	  
	  // Récupération des paramètres
	  $ppo->nodeId = $this->getParam ('nodeId');
	  $ppo->nodeType = $this->getParam ('nodeType');
	  $ppo->cpt = $this->getParam ('cpt');
	  
	  // Récupération des relations    
	  $parentLinkDAO = _ioDAO ('kernel_bu_lien_parental'); 
	  $parentLinks = $parentLinkDAO->findAll ();
	  
	  $ppo->linkNames = array ();
	  $ppo->linkIds   = array ();
	  
	  foreach ($parentLinks as $parentLink) {

      $ppo->linkNames[] = $parentLink->parente;
      $ppo->linkIds[]   = $parentLink->id_pa;
    }
    
    $ppo->genderNames = array ('Homme', 'Femme');
    $ppo->genderIds = array ('0', '1');
    
    $session = _sessionGet ('modules|gestionautonome|tmpAccount');
		$ppo->personsInSession = $session[$ppo->nodeType.'-'.$ppo->nodeId];

    $toReturn = $this->_usePPO ($ppo, '_create_person_in_charge.tpl');
  }
}