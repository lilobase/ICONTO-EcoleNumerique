<?php
/**
 * @package     
 * @subpackage
 * @author      
 */

/**
 *
 */

class ZoneShowStudents extends CopixZone {

	function _createContent (& $toReturn) {

	  $ppo = new CopixPPO ();                               
    
    // Récupération des paramètres    
    if (is_null($nid = $this->getParam('nid'))) {
        
      $toReturn = '';
      return;  
    }

    $studentDAO = _ioDAO ('kernel|kernel_bu_ele');
    $ppo->students = $studentDAO->getStudentsByClass ($nid);
    
    $ppo->selectedStudentIds = $this->getParam('selectedStudentIds');
    if (is_null($ppo->selectedStudentIds) || !is_array($ppo->selectedStudentIds)) {
      
      $ppo->selectedStudentIds = array();
    }
    
    $toReturn = $this->_usePPO ($ppo, '_show_students.tpl');
  }
}