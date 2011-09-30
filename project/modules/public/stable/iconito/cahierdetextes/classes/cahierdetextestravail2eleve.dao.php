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
	  
	  $sql = 'SELECT E.idEleve, E.nom, E.prenom1, CN.niveau_court, U.login_dbuser AS login, LI.bu_type, LI.bu_id, CL.nom as nom_classe' 
      . ' FROM kernel_bu_eleve E, kernel_bu_eleve_affectation A, kernel_link_bu2user LI, dbuser U, kernel_bu_classe_niveau CN, kernel_bu_ecole_classe CL, module_cahierdetextes_travail2eleve AS T'
		  . ' WHERE E.idEleve = A.eleve'
		  . ' AND E.idEleve = T.kernel_bu_eleve_idEleve'
		  . ' AND A.classe = CL.id'
		  . ' AND LI.bu_type = "USER_ELE"'
		  . ' AND LI.bu_id=E.idEleve'
		  . ' AND U.id_dbuser = LI.user_id'
		  . ' AND A.niveau=CN.id_n'
		  . ' AND T.module_cahierdetextes_travail_id = :idTravail'
		  . ' GROUP BY E.idEleve'
		  . ' ORDER BY E.nom, E.prenom1';
	  
	  return _doQuery ($sql, array(':idTravail' => $idTravail));
	}
}