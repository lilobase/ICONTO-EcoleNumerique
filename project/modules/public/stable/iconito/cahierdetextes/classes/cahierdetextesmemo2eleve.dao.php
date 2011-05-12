<?php

/**
* @package    Iconito
* @subpackage Cahierdetextes
*/

class DAORecordCahierDeTextesMemo2eleve {
  
}

class DAOCahierDeTextesMemo2eleve {

	/**
   * Retourne les identifiants des élèves concernés par un memo
   *
   * @param int $idMemo
   *
   * @return array
   */
	public function findElevesParMemo ($idMemo) {
	  
	  $toReturn = array();
	  
	  $sql = 'SELECT kernel_bu_eleve_idEleve as idEleve FROM module_cahierdetextes_memo2eleve WHERE module_cahierdetextes_memo_id = :idMemo';
	  
	  $resultats = _doQuery ($sql, array(':idMemo' => $idMemo));
	  foreach($resultats as $resultat) {
	    $toReturn[] = $resultat->idEleve;
	  }
	  
	  return empty($toReturn) ? null : $toReturn;
	}
}