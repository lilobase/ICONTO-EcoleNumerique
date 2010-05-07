<?php

/**
 * Surcharge de la DAO Kernel_bu_ele
 * 
 * @package Iconito
 * @subpackage Kernel
 */
class DAOKernel_bu_ele {

	/**
	 * Renvoie la liste des élèves rattachés à une classe et ayant un compte utilisateur
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2006/01/19
	 * @param integer $classe Id de la classe
	 * @return mixed Objet DAO
	 */
	function getElevesInClasse ($classe) {
		$query = "SELECT E.idEleve AS id, E.nom, E.prenom1 as prenom, S.sexe, E.date_nais AS date_naissance, EC.nom AS nom_classe, U.login_dbuser AS login, LI.bu_type, LI.bu_id FROM kernel_bu_eleve_affectation EA, kernel_bu_eleve E, kernel_bu_sexe S, kernel_bu_ecole_classe EC, kernel_link_bu2user LI, dbuser U WHERE EC.id=EA.classe AND EA.eleve=E.idEleve AND E.id_sexe=S.id_s AND LI.user_id=U.id_dbuser AND LI.bu_type='USER_ELE' AND LI.bu_id=E.idEleve AND EC.id=".$classe." ORDER BY nom, prenom1";
		return _doQuery($query);
	}
	
	/**
	 * Renvoie la liste des élèves rattachés à une école et ayant un compte utilisateur
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2006/01/19
	 * @param integer $ecole Id de l'école
	 * @return mixed Objet DAO
	 */
	function getElevesInEcole ($ecole) {
		$query = "SELECT E.idEleve AS id, E.nom, E.prenom1 as prenom, S.sexe, E.date_nais AS date_naissance, EC.nom AS nom_classe, U.login_dbuser AS login, LI.bu_type, LI.bu_id, CN.niveau_court FROM kernel_bu_eleve_affectation EA, kernel_bu_eleve E, kernel_bu_sexe S, kernel_bu_ecole_classe EC, kernel_bu_classe_niveau CN, kernel_link_bu2user LI, dbuser U WHERE EC.id=EA.classe AND EA.eleve=E.idEleve AND EA.niveau=CN.id_n AND E.id_sexe=S.id_s AND LI.user_id=U.id_dbuser AND LI.bu_type='USER_ELE' AND LI.bu_id=E.idEleve AND EC.ecole=".$ecole." ORDER BY nom, prenom1";
		//print_r($query);
		return _doQuery($query);
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
		$query = "SELECT E.idEleve AS id, E.nom, E.prenom1 as prenom, S.sexe, E.date_nais AS date_naissance, EC.nom AS nom_classe, U.login_dbuser AS login, LI.bu_type, LI.bu_id, CN.niveau_court FROM kernel_bu_eleve_affectation EA, kernel_bu_eleve E, kernel_bu_sexe S, kernel_bu_ecole_classe EC, kernel_bu_classe_niveau CN, kernel_bu_ecole ECO, kernel_link_bu2user LI, dbuser U WHERE EC.id=EA.classe AND EA.eleve=E.idEleve AND EA.niveau=CN.id_n AND E.id_sexe=S.id_s AND EC.ecole=ECO.numero AND LI.user_id=U.id_dbuser AND LI.bu_type='USER_ELE' AND LI.bu_id=E.idEleve AND ECO.id_ville=".$ville." ORDER BY nom, prenom1";
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
		$query = "SELECT E.idEleve AS id, E.nom, E.prenom1 as prenom, S.sexe, E.date_nais AS date_naissance, EC.nom AS nom_classe, U.login_dbuser AS login, LI.bu_type, LI.bu_id, CN.niveau_court FROM kernel_bu_eleve_affectation EA, kernel_bu_eleve E, kernel_bu_sexe S, kernel_bu_ecole_classe EC, kernel_bu_classe_niveau CN, kernel_bu_ecole ECO, kernel_bu_ville VIL, kernel_link_bu2user LI, dbuser U WHERE EC.id=EA.classe AND EA.eleve=E.idEleve AND EA.niveau=CN.id_n AND E.id_sexe=S.id_s AND EC.ecole=ECO.numero AND ECO.id_ville=VIL.id_vi AND LI.user_id=U.id_dbuser AND LI.bu_type='USER_ELE' AND LI.bu_id=E.idEleve AND VIL.id_grville=".$grville.$sqlPlus." ORDER BY nom, prenom1";
		//print_r($query);
		return _doQuery($query);
	}
	
	function findStudentsForAssignment ($reference, $typeRef, $filters = array ()) {
    
    $sql = 'SELECT E.idEleve, E.nom, E.prenom1, E.id_sexe, E.date_nais, EC.nom as eco_nom, U.login_dbuser, LI.bu_type, LI.bu_id
      FROM kernel_bu_eleve E
      JOIN kernel_link_bu2user LI ON (LI.bu_id=E.idEleve) 
      JOIN dbuser U ON (U.id_dbuser=LI.user_id)
      JOIN kernel_bu_eleve_admission EAD ON (EAD.eleve=E.idEleve)
      JOIN kernel_bu_ecole ECO ON (ECO.numero=EAD.etablissement)
      JOIN kernel_bu_ville V ON (V.id_vi=ECO.id_ville)
      JOIN kernel_bu_groupe_villes GV ON (GV.id_grv=V.id_grville)
      JOIN kernel_bu_ecole_classe EC ON (EC.ecole=ECO.numero)
      JOIN kernel_bu_eleve_affectation EA ON (EA.eleve=E.idEleve AND EA.classe=EC.id)';

    // Eleves sans affectation
    if (!isset ($filters['withAssignment'])) {
           
      // $sql .= ' JOIN kernel_bu_eleve_affectation EA ON (EA.eleve=E.idEleve AND EA.current=1)';
      // $sql .= ' WHERE EA.id IS NULL';       
      
      //$sql .= ' AND (SELECT kernel_bu_eleve_admission.etat_eleve FROM kernel_bu_eleve_admission WHERE kernel_bu_eleve_admission.eleve=E.idEleve AND kernel_bu_eleve_admission.annee_scol='.$filters['grade'].' GROUP BY etablissement HAVING MAX(kernel_bu_eleve_admission.numero)) = 3';
    }
    // Eleves avec affectation(s)
    else {
      
      $sql .= ' WHERE EA.current = 1';
    }
    
	  if (isset ($filters['class'])) {
    
      $sql .= ' AND EC.id='.$filters['class']; 
    }
    elseif (isset ($filters['school'])) {
      
      $sql .= ' AND ECO.numero='.$filters['school'];
    }
    elseif (isset ($filters['city'])) {
      
      $sql .= ' AND ECO.id_ville='.$filters['city'];
    }
    elseif (isset ($filters['groupcity'])) {
      
      $sql .= ' AND GV.id_grv='.$filters['groupcity'];
    }
    
    if (isset ($filters['lastname'])) {
	    
	    $sql .= ' AND E.nom LIKE \'' . $filters['lastname'] . '%\''; 
	  }
	  if (isset ($filters['firstname'])) {
	    
	    $sql .= ' AND E.prenom1 LIKE \'' . $filters['firstname'] . '%\''; 
	  }
  
	  $sql .= ' AND LI.bu_type="USER_ELE"';
    // $sql .= ' AND E.idEleve NOT IN (SELECT eleve FROM kernel_bu_eleve_affectation WHERE classe='.$reference.' AND current=1)';   
    $sql .= ' GROUP BY E.idEleve';
    $sql .= ' ORDER BY E.nom, E.prenom1';
    var_dump($sql);
    return _doQuery($sql);
  }
  
  /**
	 * Retourne les élèves d'une classe donnée
	 *
	 * @param integer $classId   Identifiant de la classe
	 */
  function getStudentsByClass ($classId) {
    
    $sql = 'SELECT E.idEleve, E.nom, E.prenom1, E.id_sexe, CN.niveau_court, U.login_dbuser AS login, LI.bu_type, LI.bu_id, CL.nom as nom_classe' 
      . ' FROM kernel_bu_eleve E, kernel_bu_eleve_affectation A, kernel_link_bu2user LI, dbuser U, kernel_bu_classe_niveau CN, kernel_bu_ecole_classe CL'
		  . ' WHERE E.idEleve = A.eleve'
		  . ' AND A.classe = CL.id'
		  . ' AND A.classe=:id'
		  . ' AND A.current=1'
		  . ' AND LI.bu_type = "USER_ELE"'
		  . ' AND LI.bu_id=E.idEleve'
		  . ' AND U.id_dbuser = LI.user_id'
		  . ' AND A.niveau=CN.id_n';
		  
		$sql .= ' ORDER BY E.nom, E.prenom1';  

    return _doQuery ($sql, array (':id' => $classId));
  }
}


?>
