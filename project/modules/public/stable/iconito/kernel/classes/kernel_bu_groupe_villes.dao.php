<?php
  
class DAOKernel_bu_groupe_villes {

	/**
	 * Retourne les groupes de villes accessibles pour un utilisateur
	 *
	 * @param int    $userId   Identifiant de l'utilisateur
	 * @param string $userType Type de l'utilisateur
	 * @return CopixDAORecordIterator
	 */
	public function findByUserIdAndUserType ($userId, $userType) {
		
		$sql = $this->_selectQuery
      . ', kernel_bu_personnel_entite PE, kernel_link_bu2user LI '
      . 'WHERE LI.user_id ='.$userId.' ' 
      . 'AND PE.id_per = LI.bu_id '
      . 'AND LI.bu_type = "'.$userType.'"'; 

      
    switch ($userType) {
      
      case 'USER_VIL':
        $sql .= ' AND ((PE.type_ref = "GVILLE" AND kernel_bu_groupe_villes.id_grv = PE.reference)'; // Agent GRVille
        $sql .= ' OR (PE.type_ref = "VILLE" AND kernel_bu_groupe_villes.id_grv IN (SELECT V.id_grville FROM kernel_bu_ville V WHERE V.id_vi = PE.reference)))'; // Agent Ville
        break;
      case 'USER_ADM':
        $sql .= ' AND (PE.type_ref = "ECOLE" AND kernel_bu_groupe_villes.id_grv IN (SELECT V.id_grville FROM kernel_bu_groupe_villes, kernel_bu_ville V, kernel_bu_ecole EC WHERE PE.reference=EC.numero AND EC.id_ville=V.id_vi))'; // Personnel Administratif
        break;
      case 'USER_ENS':
        $sql .= ' AND ((PE.type_ref = "ECOLE" AND kernel_bu_groupe_villes.id_grv IN (SELECT V.id_grville FROM kernel_bu_ville V, kernel_bu_ecole EC WHERE PE.reference=EC.numero AND EC.id_ville=V.id_vi))';
        $sql .= ' OR (PE.type_ref = "CLASSE" AND kernel_bu_groupe_villes.id_grv IN (SELECT V.id_grville FROM kernel_bu_ville V, kernel_bu_ecole EC WHERE EC.id_ville = V.id_vi AND EC.numero IN (SELECT ecole FROM kernel_bu_ecole_classe WHERE id=PE.reference))))';
        break;
    }
    
    return new CopixDAORecordIterator (_doQuery ($sql), $this->getDAOId ());
	}
}