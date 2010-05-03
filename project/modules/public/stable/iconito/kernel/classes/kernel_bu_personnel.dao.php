<?php

/**
 * Surcharge de la DAO Kernel_bu_personnel
 * 
 * @package Iconito
 * @subpackage Kernel
 */
class DAOKernel_bu_personnel {

	/**
	 * Renvoie la liste du personnel école rattaché à une classe et ayant un compte utilisateur
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2006/01/20
	 * @param integer $classe Id de la classe
	 * @return mixed Objet DAO
	 */
	function getPersonnelInClasse ($classe) {
		$query = "SELECT P.numero AS id, P.nom, P.prenom1 as prenom, P.mel AS email, S.sexe, U.id_dbuser AS id_copix, U.login_dbuser AS login, LI.bu_type, LI.bu_id, PE.role, PR.nom_role, PR.nom_role_pluriel FROM kernel_bu_personnel P, kernel_bu_personnel_entite PE, kernel_bu_personnel_role PR, kernel_bu_sexe S, kernel_link_bu2user LI, dbuser U WHERE P.numero=PE.id_per AND PE.role=PR.id_role AND P.id_sexe=S.id_s AND LI.user_id=U.id_dbuser AND LI.bu_type='USER_ENS' AND LI.bu_id=P.numero AND PE.reference=".$classe." AND PE.type_ref='CLASSE' ORDER BY PR.priorite, P.nom, P.prenom1";
		return _doQuery($query);
	}
	
	/**
	 * Renvoie la liste du personnel école rattaché à une école et ayant un compte utilisateur
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2006/01/20
	 * @param integer $ecole Id de l'école
	 * @return mixed Objet DAO
	 */
	function getPersonnelInEcole ($ecole) {
		$query = "SELECT P.numero AS id, P.nom, P.prenom1 as prenom, P.mel AS email, S.sexe, U.id_dbuser AS id_copix, U.login_dbuser AS login, LI.bu_type, LI.bu_id, PE.role, PR.nom_role, PR.nom_role_pluriel FROM kernel_bu_personnel P, kernel_bu_personnel_entite PE, kernel_bu_personnel_role PR, kernel_bu_sexe S, kernel_link_bu2user LI, dbuser U WHERE P.numero=PE.id_per AND PE.role=PR.id_role AND P.id_sexe=S.id_s AND LI.user_id=U.id_dbuser AND LI.bu_type='USER_ENS' AND LI.bu_id=P.numero AND PE.reference=".$ecole." AND PE.type_ref='ECOLE' ORDER BY PR.priorite, P.nom, P.prenom1";
		//print_r($query);
		return _doQuery($query);
	}
	
	/**
	 * Renvoie la liste du personnel école rattaché aux écoles d'une ville et ayant un compte utilisateur
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2006/01/20
	 * @param integer $ville Id de la ville
	 * @return mixed Objet DAO
	 */
	function getPersonnelInVille ($ville) {
		$query = "SELECT P.numero AS id, P.nom, P.prenom1 as prenom, P.mel AS email, S.sexe, U.id_dbuser AS id_copix, U.login_dbuser AS login, LI.bu_type, LI.bu_id, PE.role, PR.nom_role, PR.nom_role_pluriel FROM kernel_bu_personnel P, kernel_bu_personnel_entite PE, kernel_bu_personnel_role PR, kernel_bu_sexe S, kernel_bu_ecole ECO, kernel_link_bu2user LI, dbuser U WHERE P.numero=PE.id_per AND PE.role=PR.id_role AND P.id_sexe=S.id_s AND LI.user_id=U.id_dbuser AND LI.bu_type='USER_ENS' AND LI.bu_id=P.numero AND PE.reference=ECO.numero AND PE.type_ref='ECOLE' AND ECO.id_ville=".$ville." ORDER BY PR.priorite, P.nom, P.prenom1";
		//print_r($query);
		return _doQuery($query);
	}
	

	/**
	 * Renvoie la liste du personnel école rattaché aux écoles des villes d'un groupe de villes et ayant un compte utilisateur
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2006/01/20
	 * @param integer $grville Id du groupe de villes
	 * @return mixed Objet DAO
	 */
	function getPersonnelInGrville ($grville) {
		$sqlPlus = '';
		if ( Kernel::getKernelLimits('ville') )
			$sqlPlus .= ' AND VIL.id_vi IN ('.Kernel::getKernelLimits('ville').')';
		$query = "SELECT P.numero AS id, P.nom, P.prenom1 as prenom, P.mel AS email, S.sexe, U.id_dbuser AS id_copix, U.login_dbuser AS login, LI.bu_type, LI.bu_id, PE.role, PR.nom_role, PR.nom_role_pluriel FROM kernel_bu_personnel P, kernel_bu_personnel_entite PE, kernel_bu_personnel_role PR, kernel_bu_sexe S, kernel_bu_ecole ECO, kernel_bu_ville VIL, kernel_link_bu2user LI, dbuser U WHERE P.numero=PE.id_per AND PE.role=PR.id_role AND P.id_sexe=S.id_s AND LI.user_id=U.id_dbuser AND LI.bu_type='USER_ENS' AND LI.bu_id=P.numero AND PE.reference=ECO.numero AND PE.type_ref='ECOLE' AND ECO.id_ville=VIL.id_vi AND VIL.id_grville=".$grville.$sqlPlus." ORDER BY PR.priorite, P.nom, P.prenom1";
		//print_r($query);
		return _doQuery($query);
	}	


	/**
	 * Renvoie la liste du personnel administratif rattaché à une école et ayant un compte utilisateur
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2006/01/19
	 * @param integer $ecole Id de l'école
	 * @return mixed Objet DAO
	 */
	function getPersonnelAdmInEcole ($ecole) {
		$query = "SELECT P.numero AS id, P.nom, P.prenom1 as prenom, P.mel AS email, S.sexe, U.id_dbuser AS id_copix, U.login_dbuser AS login, LI.bu_type, LI.bu_id, PE.role, PR.nom_role, PR.nom_role_pluriel FROM kernel_bu_personnel P, kernel_bu_personnel_entite PE, kernel_bu_personnel_role PR, kernel_bu_sexe S, kernel_link_bu2user LI, dbuser U WHERE P.numero=PE.id_per AND PE.role=PR.id_role AND P.id_sexe=S.id_s AND LI.user_id=U.id_dbuser AND LI.bu_type='USER_ADM' AND LI.bu_id=P.numero AND PE.reference=".$ecole." AND PE.type_ref='ECOLE' ORDER BY PR.priorite, P.nom, P.prenom1";
		//print_r($query);
		return _doQuery($query);
	}
	
	/**
	 * Renvoie la liste du personnel administratif rattaché aux écoles d'une ville et ayant un compte utilisateur
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2006/01/19
	 * @param integer $ville Id de la ville
	 * @return mixed Objet DAO
	 */
	function getPersonnelAdmInVille ($ville) {
		$query = "SELECT P.numero AS id, P.nom, P.prenom1 as prenom, P.mel AS email, S.sexe, U.id_dbuser AS id_copix, U.login_dbuser AS login, LI.bu_type, LI.bu_id, PE.role, PR.nom_role, PR.nom_role_pluriel FROM kernel_bu_personnel P, kernel_bu_personnel_entite PE, kernel_bu_personnel_role PR, kernel_bu_sexe S, kernel_bu_ecole ECO, kernel_link_bu2user LI, dbuser U WHERE P.numero=PE.id_per AND PE.role=PR.id_role AND P.id_sexe=S.id_s AND LI.user_id=U.id_dbuser AND LI.bu_type='USER_ADM' AND LI.bu_id=P.numero AND PE.reference=ECO.numero AND PE.type_ref='ECOLE' AND ECO.id_ville=".$ville." ORDER BY PR.priorite, P.nom, P.prenom1";
		//print_r($query);
		return _doQuery($query);
	}
	

	/**
	 * Renvoie la liste du personnel administratif rattaché aux écoles des villes d'un groupe de villes et ayant un compte utilisateur
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2006/01/19
	 * @param integer $grville Id du groupe de villes
	 * @return mixed Objet DAO
	 */
	function getPersonnelAdmInGrville ($grville) {
		$sqlPlus = '';
		if ( Kernel::getKernelLimits('ville') )
			$sqlPlus .= ' AND VIL.id_vi IN ('.Kernel::getKernelLimits('ville').')';
		$query = "SELECT P.numero AS id, P.nom, P.prenom1 as prenom, P.mel AS email, S.sexe, U.id_dbuser AS id_copix, U.login_dbuser AS login, LI.bu_type, LI.bu_id, PE.role, PR.nom_role, PR.nom_role_pluriel FROM kernel_bu_personnel P, kernel_bu_personnel_entite PE, kernel_bu_personnel_role PR, kernel_bu_sexe S, kernel_bu_ecole ECO, kernel_bu_ville VIL, kernel_link_bu2user LI, dbuser U WHERE P.numero=PE.id_per AND PE.role=PR.id_role AND P.id_sexe=S.id_s AND LI.user_id=U.id_dbuser AND LI.bu_type='USER_ADM' AND LI.bu_id=P.numero AND PE.reference=ECO.numero AND PE.type_ref='ECOLE' AND ECO.id_ville=VIL.id_vi AND VIL.id_grville=".$grville.$sqlPlus." ORDER BY PR.priorite, P.nom, P.prenom1";
		//print_r($query);
		return _doQuery($query);
	}	

	
	/**
	 * Renvoie la liste des agents de villes rattachés a une ville et ayant un compte utilisateur
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2007/11/06
	 * @param integer $ville Id de la ville
	 * @return mixed Objet DAO
	 */
	function getPersonnelVilInVille ($ville) {
		$query = "SELECT P.numero AS id, P.nom, P.prenom1 as prenom, P.mel AS email, S.sexe, U.id_dbuser AS id_copix, U.login_dbuser AS login, LI.bu_type, LI.bu_id, PE.role, PR.nom_role, PR.nom_role_pluriel FROM kernel_bu_personnel P, kernel_bu_personnel_entite PE, kernel_bu_personnel_role PR, kernel_bu_sexe S, kernel_bu_ville VIL, kernel_link_bu2user LI, dbuser U WHERE P.numero=PE.id_per AND PE.role=PR.id_role AND P.id_sexe=S.id_s AND LI.user_id=U.id_dbuser AND LI.bu_type='USER_VIL' AND LI.bu_id=P.numero AND PE.reference=VIL.id_vi AND PE.type_ref='VILLE' AND VIL.id_vi=".$ville." ORDER BY PR.priorite, P.nom, P.prenom1";
		//print_r($query);
		return _doQuery($query);
	}


	/**
	 * Renvoie la liste des agents de ville rattachés aux villes d'un groupe de villes et ayant un compte utilisateur
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2007/11/06
	 * @param integer $grville Id du groupe de villes
	 * @return mixed Objet DAO
	 */
	function getPersonnelVilInGrville ($grville) {
		$sqlPlus = '';
		if ( Kernel::getKernelLimits('ville') )
			$sqlPlus .= ' AND VIL.id_vi IN ('.Kernel::getKernelLimits('ville').')';
		$query = "SELECT P.numero AS id, P.nom, P.prenom1 as prenom, P.mel AS email, S.sexe, U.id_dbuser AS id_copix, U.login_dbuser AS login, LI.bu_type, LI.bu_id, PE.role, PR.nom_role, PR.nom_role_pluriel FROM kernel_bu_personnel P, kernel_bu_personnel_entite PE, kernel_bu_personnel_role PR, kernel_bu_sexe S, kernel_bu_ville VIL, kernel_link_bu2user LI, dbuser U WHERE P.numero=PE.id_per AND PE.role=PR.id_role AND P.id_sexe=S.id_s AND LI.user_id=U.id_dbuser AND LI.bu_type='USER_VIL' AND LI.bu_id=P.numero AND PE.reference=VIL.id_vi AND PE.type_ref='VILLE' AND VIL.id_grville=".$grville.$sqlPlus." ORDER BY PR.priorite, P.nom, P.prenom1";
		//print_r($query);
		return _doQuery($query);
	}
	
	function findPersonnelsForAssignment ($reference, $typeRef, $filters = array ()) {

	  if (isset ($filters['withAssignment'])) {
	    
	    $sql = 'SELECT P.numero, P.nom, P.prenom1, P.id_sexe, U.id_dbuser, U.login_dbuser, LI.bu_type, LI.bu_id, PE.role, PR.nom_role, PE.reference, PE.type_ref 
  	    FROM kernel_bu_personnel P
  	    JOIN kernel_bu_personnel_entite PE ON (P.numero=PE.id_per)
  	    JOIN kernel_bu_personnel_role PR ON (PE.role=PR.id_role)
  	    JOIN kernel_link_bu2user LI ON (P.numero=LI.bu_id)
  	    JOIN dbuser U ON (LI.user_id=U.id_dbuser)';
  	    
      if (isset ($filters['groupcity'])) {
        
        if (isset ($filters['city'])) {
          
          if (isset ($filters['school'])) {
            
            if (isset ($filters['class'])) {
	           
	            $sql .= ' WHERE PE.reference='.$filters['class'];
	            $sql .= ' AND PE.type_ref="CLASSE"';
	          }
	          elseif ($typeRef == "ECOLE") {                           
	            
              $sql .= ' JOIN kernel_bu_ecole_classe C ON (PE.reference=C.id AND C.ecole='.$filters['school'].')';
               
              if ($filters['user_type'] == "USER_ADM") {
                
                $sql .= ' WHERE (PE.reference='.$filters['school'];
                $sql .= ' AND C.id IS NULL)';
                $sql .= ' AND PE.type_ref="ECOLE"';
              }
              elseif ($filters['user_type'] == "USER_ENS") {
                
                $sql .= ' WHERE ((PE.reference='.$filters['school'].' AND PE.type_ref="ECOLE")';
                $sql .= ' OR (C.id IS NOT NULL AND PE.type_ref="CLASSE"))';
              } 
            }
            elseif ($typeRef == "CLASSE") {

               $sql .= ' JOIN kernel_bu_ecole_classe C ON (PE.reference=C.id AND C.ecole='.$filters['school'].')';
               $sql .= ' WHERE ((PE.reference='.$filters['school'].' AND PE.type_ref="ECOLE")';
               $sql .= ' OR (C.id IS NOT NULL AND PE.type_ref="CLASSE"))';
            }
          }
          elseif ($typeRef == "GVILLE") {
            
            $sql .= ' WHERE ((PE.reference='.$filters['groupcity'].' AND PE.type_ref="GVILLE")';
            $sql .= ' OR (PE.reference='.$filters['city'].' AND PE.type_ref="VILLE"))';
          }
          elseif ($typeRef == "VILLE") {
            
            $sql .= ' WHERE (PE.reference='.$filters['city'].' AND PE.type_ref="VILLE")';
          }
          elseif ($typeRef == "ECOLE") {

            $sql .= ' JOIN kernel_bu_ecole E ON (PE.reference=E.numero)';
            $sql .= ' WHERE E.id_ville='.$filters['city'];
            $sql .= ' AND PE.type_ref="ECOLE"';
          }
          elseif ($typeRef == "CLASSE") {

            $sql .= ' JOIN kernel_bu_ecole_classe C ON (PE.reference=C.id)';
            $sql .= ' JOIN kernel_bu_ecole E ON (C.ecole=E.numero)';
            $sql .= ' WHERE E.id_ville='.$filters['city'];
            $sql .= ' AND PE.type_ref="CLASSE"';
          }
        }
        elseif ($typeRef == "GVILLE" || $typeRef == "VILLE") {
          
          $sql .= ' JOIN kernel_bu_ville V ON (PE.reference=V.id_vi)';
          $sql .= ' WHERE ((PE.reference='.$filters['groupcity'].' AND PE.type_ref="GVILLE")';
          $sql .= ' OR (V.id_vi IS NOT NULL AND PE.type_ref="VILLE"))';
        }
        elseif ($typeRef == "VILLE") {
          
          $sql .= ' JOIN kernel_bu_ville V ON (PE.reference=V.id_vi)';
          $sql .= ' WHERE V.id_grville='.$filters['groupcity'];
          $sql .= ' AND PE.type_ref="VILLE"';
        }
        elseif ($typeRef == "ECOLE") {
          
          $sql .= ' JOIN kernel_bu_ecole E ON (PE.reference=E.numero)';
          $sql .= ' JOIN kernel_bu_ville V ON (E.id_ville=V.id_vi)';
          $sql .= ' WHERE V.id_grville='.$filters['groupcity'];
          $sql .= ' PE.type_ref="ECOLE"';
        }
        elseif ($typeRef == "CLASSE") {
          
          $sql .= ' JOIN kernel_bu_ecole_classe C ON (PE.reference=C.id)';
          $sql .= ' JOIN kernel_bu_ecole E ON (C.ecole=E.numero)';
          $sql .= ' JOIN kernel_bu_ville V ON (E.id_ville=V.id_vi)';
          $sql .= ' WHERE V.id_grville='.$filters['groupcity'];
          $sql .= ' AND PE.type_ref="CLASSE"';
        }
      }
      
      $sql .= ' AND LI.bu_type="'.$filters['user_type'].'"';
	  }
	  else {
	    
	    $sql = 'SELECT P.numero, P.nom, P.prenom1, P.id_sexe, U.id_dbuser, U.login_dbuser, LI.bu_type, LI.bu_id, PE.role, PR.nom_role, PE.reference, PE.type_ref 
  	    FROM kernel_bu_personnel P, kernel_bu_personnel_entite PE, kernel_bu_personnel_role PR, kernel_link_bu2user LI, dbuser U 
  	    WHERE P.numero NOT IN (SELECT kernel_bu_personnel_entite.id_per FROM kernel_bu_personnel_entite)
  	    AND P.numero=LI.bu_id
    	  AND LI.user_id=U.id_dbuser
        AND LI.bu_type="'.$filters['user_type'].'"';
	  }
	  
	  if (isset ($filters['lastname'])) {
	    
	    $sql .= ' AND P.nom LIKE \'' . $filters['lastname'] . '%\''; 
	  }
	  if (isset ($filters['firstname'])) {
	    
	    $sql .= ' AND P.prenom1 LIKE \'' . $filters['firstname'] . '%\''; 
	  }
		
	  $sql .= ' GROUP BY P.numero';
	  $sql .= ' ORDER BY PR.priorite, P.nom, P.prenom1';

		return _doQuery($sql);
	}
	
	function findPersonnelWithAccountByIdAndType ($id, $type) {
	  
	  $sql = 'SELECT P.numero, P.nom, P.prenom1, P.date_nais, P.mel, U.id_dbuser, U.login_dbuser, LI.bu_type, LI.bu_id, PE.role, PR.nom_role 
	    FROM kernel_bu_personnel P, kernel_bu_personnel_entite PE, kernel_bu_personnel_role PR, kernel_link_bu2user LI, dbuser U 
  	  WHERE P.numero=PE.id_per 
  	  AND PE.role=PR.id_role  
  	  AND LI.user_id=U.id_dbuser
  	  AND LI.bu_id=P.numero
  	  AND P.numero='.$id.'
  	  AND LI.bu_type="'.$type.'"
  	  ORDER BY PR.priorite, P.nom, P.prenom1';

		$results = _doQuery($sql);

		return isset ($results[0]) ? $results[0] : false;
	}

}

?>
