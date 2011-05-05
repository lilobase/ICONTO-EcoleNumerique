<?php

/**
* @package    Iconito
* @subpackage Cahier
*/

class DAORecordCahier_travail {
  
}

class DAOCahier_travail {

  const TYPE_CLASSROOM = 0;
	const TYPE_TODO      = 1;
	
	/**
   * Retourne les travaux pour une élève donné
   *
   * @param int $studentId
   * @param int $typeOfWorks
   *
   * @return array
   */
	public function findWorksForStudent ($studentId, $typeOfWorks = self::TYPE_CLASSROOM, $date) {
	  
	  $sql = 'SELECT module_cahier_travail.id, module_cahier_travail.date_creation, module_cahier_travail.date_realisation, '
	    . ' module_cahier_travail.description, module_cahier_domaine.id as domaine_id, module_cahier_domaine.nom '
	    . ' FROM module_cahier_travail'
  	  . ' LEFT JOIN module_cahier_travail2eleve ON (module_cahier_travail.id = module_cahier_travail2eleve.module_cahier_travail_id)'
  	  . ' LEFT JOIN module_cahier_domaine ON (module_cahier_domaine.id = module_cahier_travail.module_cahier_domaine_id)'
  	  . ' WHERE module_cahier_travail2eleve.kernel_bu_eleve_idEleve=:studentId'
  	  . ' AND module_cahier_travail.supprime = 0'
  	  . ' AND module_cahier_travail.a_faire = :todo';
  	
  	if ($typeOfWorks == self::TYPE_CLASSROOM) {
  	  
  	  $sql .= ' AND module_cahier_travail.date_creation=:date';
  	}
  	else {
  	  
  	  $sql .= ' AND module_cahier_travail.date_realisation=:date';
  	}
  	
  	$sql .= ' GROUP BY module_cahier_domaine.nom';
  	  
    $results = _doQuery ($sql, array(':studentId' => $studentId, ':todo' => $typeOfWorks, ':date' => date('d/m/Y', $date)));

    $return = array();
    foreach ($results as $result) {
      
      $return[$result->nom][] = $result;
    }

  	return $return;
	}
	
	/**
   * Retourne les travaux pour une classe donnée
   *
   * @param int $classId
   * @param int $typeOfWorks
   *
   * @return array
   */
	public function findWorksForClassroom ($classId, $typeOfWorks = self::TYPE_CLASSROOM, $date) {
	  
	  $sql = 'SELECT module_cahier_travail.id, module_cahier_travail.date_creation, module_cahier_travail.date_realisation, '
	    . ' module_cahier_travail.description, module_cahier_domaine.id as domaine_id, module_cahier_domaine.nom '
	    . ' FROM module_cahier_travail'
  	  . ' LEFT JOIN module_cahier_domaine ON (module_cahier_domaine.id = module_cahier_travail.module_cahier_domaine_id)'
  	  . ' WHERE module_cahier_domaine.kernel_bu_ecole_classe_id=:classId'
  	  . ' AND module_cahier_travail.supprime = 0'
  	  . ' AND module_cahier_travail.a_faire = :todo';
  	
  	if ($typeOfWorks == self::TYPE_CLASSROOM) {
  	  
  	  $sql .= ' AND module_cahier_travail.date_creation=:date';
  	}
  	else {
  	  
  	  $sql .= ' AND module_cahier_travail.date_realisation=:date';
  	}
  	
  	$sql .= ' GROUP BY module_cahier_travail.id'
  	  . ' ORDER BY module_cahier_domaine.nom ASC';

  	return _doQuery ($sql, array(':classId' => $classId, ':todo' => $typeOfWorks, ':date' => date('d/m/Y', $date)));
  	
  	// $return = array();
  	//     foreach ($results as $result) {
  	//       
  	//       $return[$result->nom][] = $result; 
  	//     }
  	// 
  	//     return $return;
	}
}