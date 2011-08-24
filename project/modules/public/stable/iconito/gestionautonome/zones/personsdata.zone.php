<?php
/**
* @package    Iconito
* @subpackage Gestionautonome
* @author     Jérémy FOURNAISE
*/
class ZonePersonsData extends CopixZone {

	function _createContent (& $toReturn) {
	  
	  $ppo = new CopixPPO ();                               
	  
	  // Récupération des paramètres
	  $id        = $this->getParam ('node_id');
	  $ppo->type = $this->getParam ('node_type');
	  $ppo->tab  = ($this->getParam('tab')) ? $this->getParam('tab') : 0;                                 
    
    // Récupération de l'utilisateur connecté
	  $ppo->user = _currentUser ();
	  
	  if (!is_null($ppo->type) && !is_null($id)) {
                                
      $ppo->parent = Kernel::getNodeInfo ($ppo->type, $id);
      
	    $personnelDAO = _ioDAO ('kernel|kernel_bu_personnel');
	    switch ($ppo->type) {
	      
	      case 'BU_GRVILLE':
	        $ppo->persons = $personnelDAO->findCitiesAgentsByCitiesGroupId ($id);
	        break;
	      case 'BU_VILLE':
  	      $ppo->persons = $personnelDAO->findCityAgentsByCityId ($id);
  	      break;
  	    case 'BU_ECOLE':
    	    $ppo->persons = $personnelDAO->findAdministrationStaffAndPrincipalBySchoolId ($id);
    	    break;
    	  case 'BU_CLASSE':
    	    // Récupération des enseignants
    	    $ppo->persons = $personnelDAO->findTeachersByClassroomId ($id);
    	    
    	    // Récupération des élèves
    	    $studentDAO = _ioDAO ('kernel|kernel_bu_ele');
    	    $ppo->students = $studentDAO->getStudentsByClass ($id);
    	    
    	    // Récupération des parents
    	    $responsableDAO = _ioDAO ('kernel|kernel_bu_res');
    	    $ppo->responsables =$responsableDAO->getParentsInClasse($id);

    	    break;
	    }
    }

    $toReturn = $this->_usePPO ($ppo, '_persons_data.tpl');
  }
}