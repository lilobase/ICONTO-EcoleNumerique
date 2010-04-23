<?php
/**
 * @package     
 * @subpackage  
 * @author      
 */

/**
 * 
 */
class ZonePersonsInCharge extends CopixZone {

	function _createContent (& $toReturn) {
	  
	  $ppo = new CopixPPO ();                               
	  
	  // Récupération des paramètres
	  $ppo->nodeId    = $this->getParam ('nodeId');
	  $ppo->nodeType  = $this->getParam ('nodeType');
	  $ppo->studentId = $this->getParam ('studentId');
	  
	  // Récupérations des responsables de l'élève
	  $personsInChargeDAO = _ioDAO ('kernel|kernel_bu_res');
	  $ppo->persons = $personsInChargeDAO->getByStudent ($ppo->studentId); 

    $toReturn = $this->_usePPO ($ppo, '_persons_in_charge.tpl');
  }
}