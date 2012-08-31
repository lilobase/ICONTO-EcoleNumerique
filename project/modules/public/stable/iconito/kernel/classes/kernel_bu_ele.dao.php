<?php

/**
 * Surcharge de la DAO Kernel_bu_ele
 * 
 * @package Iconito
 * @subpackage Kernel
 */
class DAOKernel_bu_ele {

	/**
	 * Renvoie la liste des élèves rattachés à une classe et ayant un compte utilisateur (facultatif : pour une année scolaire donnée)
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2006/01/19
	 *
	 * @param integer $classe     Id de la classe
	 * @param string  $anneeScol  Année scolaire
	 *
	 * @return mixed Objet DAO
	 */
	function getElevesInClasse ($classe, $anneeScol = null) {
	 
	  $sql = 'SELECT E.idEleve AS id, E.nom, E.prenom1 as prenom, S.sexe, E.date_nais AS date_naissance, EC.nom AS nom_classe, U.login_dbuser AS login, LI.bu_type, LI.bu_id, CN.niveau_court AS niveau, CN.id_n AS niveauId'
	    . ' FROM kernel_bu_eleve_affectation EA, kernel_bu_eleve E, kernel_bu_sexe S, kernel_bu_ecole_classe EC, kernel_bu_classe_niveau CN, kernel_link_bu2user LI, dbuser U'
	    . ' WHERE EC.id=EA.classe'
	    . ' AND EA.eleve=E.idEleve'
	    . ' AND EA.niveau=CN.id_n'
	    . ' AND E.id_sexe=S.id_s'
	    . ' AND LI.user_id=U.id_dbuser'
	    . ' AND LI.bu_type="USER_ELE"'
	    . ' AND LI.bu_id=E.idEleve'
	    . ' AND EA.current=1'
	    . ' AND EC.id=:classe';
	    
	  if (!is_null($anneeScol)) {
	    
	    $sql .= ' AND EA.annee_scol='.$anneeScol;
	  }
	  
	  $sql .= ' ORDER BY nom, prenom1';
	  
		return _doQuery($sql, array(':classe' => $classe));
	}
	
	/**
	 * Renvoie la liste des élèves rattachés à une école et ayant un compte utilisateur (facultatif : pour une année scolaire donnée)
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2006/01/19
	 *
	 * @param integer $ecole      Id de l'école
	 * @param string  $anneeScol  Année scolaire
	 *
	 * @return mixed Objet DAO
	 */
	function getElevesInEcole ($ecole, $anneeScol = null) {
	  
	  $sql = 'SELECT E.idEleve AS id, E.nom, E.prenom1 as prenom, S.sexe, E.date_nais AS date_naissance, EC.nom AS nom_classe, U.login_dbuser AS login, LI.bu_type, LI.bu_id, CN.niveau_court AS niveau, CN.id_n AS niveauId'
	    . ' FROM kernel_bu_eleve_affectation EA, kernel_bu_eleve E, kernel_bu_sexe S, kernel_bu_ecole_classe EC, kernel_bu_classe_niveau CN, kernel_link_bu2user LI, dbuser U'
	    . ' WHERE EC.id=EA.classe'
	    . ' AND EA.eleve=E.idEleve'
	    . ' AND EA.niveau=CN.id_n'
	    . ' AND E.id_sexe=S.id_s'
	    . ' AND LI.user_id=U.id_dbuser'
	    . ' AND LI.bu_type="USER_ELE"'
	    . ' AND LI.bu_id=E.idEleve'
	    . ' AND EA.current=1'
	    . ' AND EC.ecole=:ecole';
	    
	  if (!is_null($anneeScol)) {
	    
	    $sql .= ' AND EA.annee_scol='.$anneeScol;
	  }
	  
	  $sql .= ' ORDER BY nom, prenom1';
	    
	  return _doQuery($sql, array(':ecole' => $ecole));
	}
	
	/**
	 * Renvoie la liste des élèves rattachés à une ville et ayant un compte utilisateur
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2006/01/20
	 * @param integer $ville Id de la ville
	 * @return mixed Objet DAO
	 */
	function getElevesInVille ($ville) {
		$query = "SELECT E.idEleve AS id, E.nom, E.prenom1 as prenom, S.sexe, E.date_nais AS date_naissance, EC.nom AS nom_classe, U.login_dbuser AS login, LI.bu_type, LI.bu_id, CN.niveau_court FROM kernel_bu_eleve_affectation EA, kernel_bu_eleve E, kernel_bu_sexe S, kernel_bu_ecole_classe EC, kernel_bu_classe_niveau CN, kernel_bu_ecole ECO, kernel_link_bu2user LI, dbuser U WHERE EC.id=EA.classe AND EA.eleve=E.idEleve AND EA.niveau=CN.id_n AND E.id_sexe=S.id_s AND EC.ecole=ECO.numero AND LI.user_id=U.id_dbuser AND LI.bu_type='USER_ELE' AND LI.bu_id=E.idEleve AND EA.current=1 AND ECO.id_ville=".$ville." ORDER BY nom, prenom1";
		//print_r($query);
		return _doQuery($query);
	}
	

	/**
	 * Renvoie la liste des élèves rattachés à un groupe de villes et ayant un compte utilisateur
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2006/01/20
	 * @param integer $grville Id du groupe de villes
	 * @return mixed Objet DAO
	 */
	function getElevesInGrville ($grville) {
		$sqlPlus = '';
		if ( Kernel::getKernelLimits('ville') )
			$sqlPlus .= ' AND VIL.id_vi IN ('.Kernel::getKernelLimits('ville').')';
		$query = "SELECT E.idEleve AS id, E.nom, E.prenom1 as prenom, S.sexe, E.date_nais AS date_naissance, EC.nom AS nom_classe, U.login_dbuser AS login, LI.bu_type, LI.bu_id, CN.niveau_court FROM kernel_bu_eleve_affectation EA, kernel_bu_eleve E, kernel_bu_sexe S, kernel_bu_ecole_classe EC, kernel_bu_classe_niveau CN, kernel_bu_ecole ECO, kernel_bu_ville VIL, kernel_link_bu2user LI, dbuser U WHERE EC.id=EA.classe AND EA.eleve=E.idEleve AND EA.niveau=CN.id_n AND E.id_sexe=S.id_s AND EC.ecole=ECO.numero AND ECO.id_ville=VIL.id_vi AND LI.user_id=U.id_dbuser AND LI.bu_type='USER_ELE' AND LI.bu_id=E.idEleve AND EA.current=1 AND VIL.id_grville=".$grville.$sqlPlus." ORDER BY nom, prenom1";
		//print_r($query);
		return _doQuery($query);
	}
	
	/**
	 * Retourne les élèves non affectés l'année A1 mais affecté l'année A2 (25/06/2012 - plus utilisé)
	 *
	 * @param int $classroomId  ID de la classe
	 * @param int $a1           ID de l'année de l'ancienne affectation
	 * @param int $a2           ID de l'année de la nouvelle affectation
	 *
	 * @return array
	 */
	function findOldStudentsAssignmentsForNewAssignement ($classroomId, $a1, $a2) {
	
	  $sql = 'SELECT eleve.idEleve AS id, eleve.nom AS nom, eleve.prenom1 AS prenom, niveau.niveau_court AS niveau, niveau.id_n AS niveauId '
	    .'FROM kernel_bu_eleve AS eleve '
	    .'JOIN kernel_bu_eleve_affectation AS affectation ON (eleve.idEleve=affectation.eleve) '
	    .'JOIN kernel_bu_classe_niveau AS niveau ON (niveau.id_n=affectation.niveau) '
	    .'LEFT JOIN kernel_bu_eleve_affectation AS affectation2 ON (eleve.idEleve=affectation2.eleve AND affectation2.annee_scol=:a2 AND affectation2.current=1) '
	    .'WHERE affectation.classe=:classroomId '
	    .'AND affectation.annee_scol=:a1 '
	    .'AND affectation2.id IS NULL '
	    .'GROUP BY eleve.idEleve '
	    .'ORDER BY eleve.nom, eleve.prenom1';
    
	  return _doQuery($sql, array(':classroomId' => $classroomId, ':a1' => $a1, ':a2' => $a2));
	}
	
	/**
	 * Retourne les élèves avec et sans affectation pour une année donnée
	 *
	 * @param array   $filters  Filtres de récupération des élèves
	 * @param string  $grade    Année scolaire
	 *
	 * @return array
	 */
	function findStudentsForAssignment ($filters = array (), $grade) {
    
    $sql = 'SELECT E.idEleve as user_id, "USER_ELE" as user_type, E.nom, E.prenom1 as prenom, LI.bu_type, LI.bu_id, EC.id as id_classe, EC.nom as nom_classe, CN.niveau_court AS nom_niveau, CN.id_n AS id_niveau, SUM(EA.current) AS is_affect, EA.*
      FROM kernel_bu_eleve E
      JOIN kernel_link_bu2user LI ON (LI.bu_id=E.idEleve) 
      JOIN dbuser U ON (U.id_dbuser=LI.user_id)
      JOIN kernel_bu_eleve_admission EAD ON (EAD.eleve=E.idEleve)
      JOIN kernel_bu_ecole ECO ON (ECO.numero=EAD.etablissement)
      JOIN kernel_bu_ville V ON (V.id_vi=ECO.id_ville)
      JOIN kernel_bu_groupe_villes GV ON (GV.id_grv=V.id_grville)
      JOIN kernel_bu_ecole_classe EC ON (EC.ecole=ECO.numero)
      JOIN kernel_bu_eleve_affectation EA ON (EA.eleve=E.idEleve AND EA.classe=EC.id AND EA.annee_scol = '.$grade.')
      JOIN kernel_bu_classe_niveau CN ON (EA.niveau=CN.id_n)
      WHERE LI.bu_type="USER_ELE"
      AND EA.current = (SELECT MAX(EA2.current) FROM kernel_bu_eleve_affectation EA2 WHERE EA2.eleve=E.idEleve AND EA2.annee_scol = '.$grade.')';

    if (isset ($filters['grade'])) {
      
      $sql .= ' AND EC.annee_scol='.$filters['grade'];
    }
    
    if (isset ($filters['level'])) {
	    
	    $sql .= ' AND EA.niveau='.$filters['level'];
	  }
	  if (isset ($filters['classroom'])) {
    
      $sql .= ' AND EC.id='.$filters['classroom'];
    }
    if (isset ($filters['school'])) {
      
      $sql .= ' AND ECO.numero='.$filters['school'];
    }
    if (isset ($filters['city'])) {
      
      $sql .= ' AND V.id_vi='.$filters['city'];
    }
    if (isset ($filters['cityGroup'])) {
      
      $sql .= ' AND GV.id_grv='.$filters['cityGroup'];
    }
    
    if (isset ($filters['lastname'])) {
	    
	    $sql .= ' AND E.nom LIKE \'' . $filters['lastname'] . '%\''; 
	  }
	  if (isset ($filters['firstname'])) {
	    
	    $sql .= ' AND E.prenom1 LIKE \'' . $filters['firstname'] . '%\''; 
	  }
    
    $sql .= ' GROUP BY E.idEleve';    
    $sql .= ' ORDER BY CN.id_n, EC.nom, E.nom, E.prenom1';
    
    return _doQuery($sql);
  }
  
  /**
	 * Retourne les élèves d'une classe donnée
	 *
	 * @param integer $classId   Identifiant de la classe
	 */
  function getStudentsByClass ($classId) {
    
    $sql = 'SELECT E.idEleve, E.idEleve as id, E.nom, E.prenom1, E.prenom1 as prenom, E.id_sexe, CN.niveau_court, U.login_dbuser AS login, LI.bu_type, LI.bu_id, CL.nom as nom_classe, CN.niveau_court AS niveau, CN.id_n AS niveauId' 
      . ' FROM kernel_bu_eleve E, kernel_bu_eleve_affectation A, kernel_link_bu2user LI, dbuser U, kernel_bu_classe_niveau CN, kernel_bu_ecole_classe CL'
		  . ' WHERE E.idEleve = A.eleve'
		  . ' AND A.classe = CL.id'
		  . ' AND A.classe=:id'
		  . ' AND A.current=1'
		  . ' AND LI.bu_type = "USER_ELE"'
		  . ' AND LI.bu_id=E.idEleve'
		  . ' AND U.id_dbuser = LI.user_id'
		  . ' AND A.niveau=CN.id_n'
		  . ' GROUP BY E.idEleve'
      . ' ORDER BY E.nom, E.prenom1';  

    return _doQuery ($sql, array (':id' => $classId));
  }
  
  /**
	 * Retourne les identifiants des élèves d'une classe donnée
	 *
	 * @param integer $classId   Identifiant de la classe
	 *
	 * return array Identifiants des élèves
	 */
  function getStudentIdsByClass ($classId) {
    
    $toReturn = array();
    
    $sql = 'SELECT E.idEleve' 
      . ' FROM kernel_bu_eleve E, kernel_bu_eleve_affectation A, kernel_link_bu2user LI, dbuser U, kernel_bu_classe_niveau CN, kernel_bu_ecole_classe CL'
		  . ' WHERE E.idEleve = A.eleve'
		  . ' AND A.classe = CL.id'
		  . ' AND A.classe=:id'
		  . ' AND A.current=1'
		  . ' AND LI.bu_type = "USER_ELE"'
		  . ' AND LI.bu_id=E.idEleve'
		  . ' AND U.id_dbuser = LI.user_id'
		  . ' AND A.niveau=CN.id_n'
		  . ' GROUP BY E.idEleve'
		  . ' ORDER BY E.nom, E.prenom1'; 

    $results = _doQuery ($sql, array (':id' => $classId));
    
    foreach ($results as $result) {
      
      $toReturn[] = $result->idEleve;
    }
    
    return $toReturn;
  }
  
  /**
	 * Retourne les élèves sous la responsabilité d'une personne
	 *
	 * @param integer $personId   Identifiant du responsable
	 */
  function getStudentsByPersonInChargeId ($personId) {
    
    $sql = 'SELECT kernel_bu_eleve.*, u.login_dbuser AS login, kernel_bu_lien_parental.parente as link' 
      . ' FROM kernel_bu_eleve, kernel_bu_responsables, kernel_link_bu2user li, dbuser u, kernel_bu_lien_parental'
		  . ' WHERE kernel_bu_responsables.id_responsable=:personId'
		  . ' AND kernel_bu_responsables.id_par=kernel_bu_lien_parental.id_pa'
		  . ' AND kernel_bu_responsables.type_beneficiaire="eleve"'
		  . ' AND kernel_bu_responsables.id_beneficiaire=kernel_bu_eleve.idEleve'
		  . ' AND li.bu_type="USER_ELE"'
		  . ' AND li.bu_id=kernel_bu_eleve.idEleve'
		  . ' AND u.id_dbuser = li.user_id'
		  . ' ORDER BY kernel_bu_eleve.nom, kernel_bu_eleve.prenom1'; 

    return _doQuery ($sql, array (':personId' => $personId));
  }
  
  /**
	 * Retourne les élèves sans affectation d'une école donnée (25/06/2012 - plus utilisé)
	 *
	 * @param integer $schoolId   Identifiant de l'école
	 */
  function getStudentsWithoutAssignmentBySchool ($schoolId) {
    
    $sql = 'SELECT E.idEleve, E.idEleve as id, E.nom, E.prenom1, E.prenom1 as prenom, E.id_sexe, U.login_dbuser AS login, LI.bu_type, LI.bu_id, CL.nom as nom_classe' 
      . ' FROM kernel_bu_eleve E, kernel_link_bu2user LI, dbuser U, kernel_bu_ecole EC'
      . ' LEFT JOIN kernel_bu_ecole_classe CL ON EC.numero = CL.ecole'
      . ' LEFT JOIN kernel_bu_eleve_affectation A ON A.classe = CL.id AND A.current = 0'
		  . ' WHERE EC.numero = :schoolId'
		  . ' AND E.idEleve = A.eleve'
		  . ' AND A.classe = CL.id'
		  . ' AND LI.bu_type = "USER_ELE"'
		  . ' AND LI.bu_id=E.idEleve'
		  . ' AND U.id_dbuser = LI.user_id'
		  . ' GROUP BY E.idEleve'
		  . ' HAVING SUM(A.current) = 0';
		  
		$sql .= ' ORDER BY E.nom, E.prenom1';  

    return _doQuery ($sql, array (':schoolId' => $schoolId));
  }
  
  /**
	 * Retourne les élèves à assigner (manageAssignments)
	 *                                             
	 * @param array   $filters   Filtres de récupération des élèves
	 *
	 * return CopixDAORecordIterator
	 */
  public function findForManageAssignments ($filters = array ()) {
    
    $sql = 'SELECT E.idEleve as user_id, "USER_ELE" as user_type, E.nom, E.prenom1 as prenom, LI.bu_type, LI.bu_id, EC.id as id_classe, EC.nom as nom_classe, CN.niveau_court AS nom_niveau, CN.id_n AS id_niveau, SUM(EA.current) AS is_affect'
      . ' FROM kernel_bu_eleve E'
      . ' JOIN kernel_link_bu2user LI ON (LI.bu_id=E.idEleve)'
      . ' JOIN dbuser U ON (U.id_dbuser=LI.user_id)'
      . ' JOIN kernel_bu_eleve_admission EAD ON (EAD.eleve=E.idEleve)'
      . ' JOIN kernel_bu_ecole ECO ON (ECO.numero=EAD.etablissement)'
      . ' JOIN kernel_bu_ville V ON (V.id_vi=ECO.id_ville)'
      . ' JOIN kernel_bu_groupe_villes GV ON (GV.id_grv=V.id_grville)'
      . ' JOIN kernel_bu_ecole_classe EC ON (EC.ecole=ECO.numero)'
      . ' JOIN kernel_bu_eleve_affectation EA ON (EA.eleve=E.idEleve AND EA.classe=EC.id)'
      . ' JOIN kernel_bu_classe_niveau CN ON (EA.niveau=CN.id_n)';
      
    if (isset ($filters['destinationGrade'])) {
      
      $sql .= ' LEFT JOIN kernel_bu_eleve_affectation EA2 ON (EA2.eleve=E.idEleve AND EA2.current = 1 AND EA2.annee_scol='.$filters['destinationGrade'].')';
    }
      
    $sql .= ' WHERE LI.bu_type="USER_ELE"';
    
    if (isset ($filters['destinationGrade'])) {
      
      $sql .= ' AND EA2.eleve IS NULL';
    }
    
	  if (isset ($filters['originClassroom'])) {
    
      $sql .= ' AND EC.id='.$filters['originClassroom'];
      $sql .= ' AND EA.current = 1';
    }
    elseif (isset ($filters['originSchool'])) {
      
      $sql .= ' AND ECO.numero='.$filters['originSchool'];
    }
    elseif (isset ($filters['originCity'])) {
      
      $sql .= ' AND V.id_vi='.$filters['originCity'];
    }
    elseif (isset ($filters['originCityGroup'])) {
      
      $sql .= ' AND GV.id_grv='.$filters['originCityGroup'];
    }
    
    if (isset ($filters['originLastname'])) {
	    
	    $sql .= ' AND E.nom LIKE \'' . $filters['originLastname'] . '%\''; 
	  }
	  if (isset ($filters['originFirstname'])) {
	    
	    $sql .= ' AND E.prenom1 LIKE \'' . $filters['originFirstname'] . '%\''; 
	  }
	  if (isset ($filters['originGrade'])) {
	    
	    $sql .= ' AND EA.annee_scol='.$filters['originGrade'];
	  }
	  if (isset ($filters['originLevel'])) {
	    
	    $sql .= ' AND EA.niveau='.$filters['originLevel'];
	  }
    
    $sql .= ' GROUP BY E.idEleve'
      . ' ORDER BY CN.id_n, EC.nom, E.nom, E.prenom1';
    
    return _doQuery($sql);
  }
  
  /**
	 * Retourne les élèves assignés (manageAssignments)
	 *                                             
	 * @param array   $filters   Filtres de récupération des élèves
	 *
	 * return CopixDAORecordIterator
	 */
  function findAssigned ($filters = array ()) {
	  
	  $sql = 'SELECT E.idEleve as user_id, "USER_ELE" as user_type, E.nom, E.prenom1 as prenom, LI.bu_type, LI.bu_id, EC.id as id_classe, EC.nom as nom_classe, CN.niveau_court AS nom_niveau, CN.id_n AS id_niveau, SUM(EA.current) AS is_affect'
      . ' FROM kernel_bu_eleve E'
      . ' JOIN kernel_link_bu2user LI ON (LI.bu_id=E.idEleve)'
      . ' JOIN dbuser U ON (U.id_dbuser=LI.user_id)'
      . ' JOIN kernel_bu_eleve_admission EAD ON (EAD.eleve=E.idEleve)'
      . ' JOIN kernel_bu_ecole ECO ON (ECO.numero=EAD.etablissement)'
      . ' JOIN kernel_bu_ville V ON (V.id_vi=ECO.id_ville)'
      . ' JOIN kernel_bu_groupe_villes GV ON (GV.id_grv=V.id_grville)'
      . ' JOIN kernel_bu_ecole_classe EC ON (EC.ecole=ECO.numero)'
      . ' JOIN kernel_bu_eleve_affectation EA ON (EA.eleve=E.idEleve AND EA.classe=EC.id AND EA.current=1)'
      . ' JOIN kernel_bu_classe_niveau CN ON (EA.niveau=CN.id_n)';
      
    $sql .= ' WHERE LI.bu_type="USER_ELE"';
    
    if (isset ($filters['grade'])) {
      
      $sql .= ' AND EA.annee_scol='.$filters['grade'];
    }
    
	  if (isset ($filters['classroom'])) {
    
      $sql .= ' AND EC.id='.$filters['classroom'];
      $sql .= ' AND EA.current = 1';
    }
    elseif (isset ($filters['school'])) {
      
      $sql .= ' AND ECO.numero='.$filters['school'];
    }
    elseif (isset ($filters['city'])) {
      
      $sql .= ' AND V.id_vi='.$filters['city'];
    }
    elseif (isset ($filters['cityGroup'])) {
      
      $sql .= ' AND GV.id_grv='.$filters['cityGroup'];
    }
    
    if (isset ($filters['level'])) {
	    
	    $sql .= ' AND EA.niveau='.$filters['level'];
	  }
    
    $sql .= ' GROUP BY E.idEleve'
      . ' ORDER BY CN.id_n, EC.nom, E.nom, E.prenom1';

    return _doQuery($sql);
	}
}