<?php
/**
* @package    Iconito
* @subpackage Gestionautonome
* @author     Jérémy FOURNAISE
*/
class ZoneFilterSchool extends CopixZone {

	function _createContent (& $toReturn) {
	  
	  $ppo = new CopixPPO ();                               

    // Récupérations des filtres en session
	  $ppo->selected = $this->getParam ('selected', null);
	  $ppo->withLabel = $this->getParam ('with_label', true);
	  
	  if (!is_null ($cityId = $this->getParam('city_id', null))) {
	    
	    // Récupération des écoles de la ville sélectionnée pour liste déroulante
	    $schoolDAO = _dao ('kernel|kernel_bu_ecole');
	    if (_currentUser ()->testCredential ('module:city|'.$cityId.'|school|create@gestionautonome')) {

  	    $schools = $schoolDAO->getByCity ($cityId);
  	  }
  	  else {

        $groups = _currentUser ()->getGroups ();
        $schools = $schoolDAO->findByCityIdAndUserGroups ($cityId, $groups['gestionautonome|iconitogrouphandler']);
      }
      
      $ppo->schoolsIds = array('');
      $ppo->schoolsNames = array('');
    
  	  foreach ($schools as $school) {
	    
  	    $ppo->schoolsIds[]   = $school->numero;
  	    $ppo->schoolsNames[] = $school->nom;
  	  }
    }
    
    $toReturn = $this->_usePPO ($ppo, '_filter_school.tpl');
  }
}