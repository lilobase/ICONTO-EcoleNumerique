<?php
/**
* @package    Iconito
* @subpackage Gestionautonome
* @author     Sébastien CAS
*/
class ZoneStudents extends CopixZone {

	function _createContent (& $toReturn) {
	  
	  $ppo = new CopixPPO ();                               
	  
	  // Récupération des paramètres
	  $ppo->nodeId = $this->getParam ('nodeId');
	  $ppo->nodeType = $this->getParam ('nodeType');
	  $ppo->personId = $this->getParam ('personId');
    
    $eleveDAO = _ioDAO('kernel|kernel_bu_ele');
    $ppo->students = $eleveDAO->getStudentsByPersonInChargeId($ppo->personId);

    $ppo->user = _currentUser();
    
    $toReturn = $this->_usePPO ($ppo, '_students.tpl');
  }
}