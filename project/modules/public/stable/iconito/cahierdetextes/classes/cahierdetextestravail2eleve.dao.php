<?php

/**
* @package    Iconito
* @subpackage Cahierdetextes
*/

class DAORecordCahierDeTextesTravail2eleve {
  
}

class DAOCahierDeTextesTravail2eleve {

	/**
   * Retourne les identifiants des élèves concernés par un travail
   *
   * @param int $idTravail
   *
   * @return array
   */
	public function findElevesParTravail ($idTravail) {
	  
	  $toReturn = array();
	  
	  $sql = 'SELECT kernel_bu_eleve_idEleve as idEleve FROM module_cahierdetextes_travail2eleve WHERE module_cahierdetextes_travail_id = :idTravail';
	  
	  $resultats = _doQuery ($sql, array(':idTravail' => $idTravail));
	  foreach($resultats as $resultat) {
	    $toReturn[] = $resultat->idEleve;
	  }
	  
	  return empty($toReturn) ? null : $toReturn;
	}
}