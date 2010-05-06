<?php

class DAORecordKernel_bu_ville {
  
  protected $_citiesGroup = null;
  
  public function getCitiesGroup () {
    
    if (is_null($this->_citiesGroup)) {
      
      $citiesGroupsDAO = _ioDAO ('kernel|kernel_bu_groupe_villes');
      
      $this->_citiesGroup = $citiesGroupsDAO->get ($this->id_grville);
    }
    
    return $this->_citiesGroup;
  }
}
  
class DAOKernel_bu_ville {

	/**
	 * Retourne une ville par son canon
	 *
	 * @param int $canon Canon d'une ville
	 * @return CopixDAORecordIterator
	 */
	public function getByCanon ($canon) {
		
		$criteria = _daoSp ();
		$criteria->addCondition ('canon', '=', $canon);
		
		return $this->findBy ($criteria);
	}
	
	public function getByIdGrville ($id_grville) {
	  
	  $criteria = _daoSp ();
		$criteria->addCondition ('id_grville', '=', $id_grville);
		$criteria->orderBy ('nom');
		
		return $this->findBy ($criteria);
	}
	
	/**
	 * Retourne les villes accessibles pour un utilisateur
	 *
	 * @param int    $citiesGroupid   Identifiant du groupe de ville
	 * @param int    $userId   Identifiant de l'utilisateur
	 * @param string $userType Type de l'utilisateur
	 * @return CopixDAORecordIterator
	 */
	public function findByUserIdAndUserType ($citiesGroupId, $userId, $userType) {
		
		$sql = $this->_selectQuery
      . ', kernel_bu_personnel_entite PE, kernel_link_bu2user LI '
      . 'WHERE id_grville ='.$citiesGroupId.' ' 
      . 'AND LI.user_id ='.$userId.' ' 
      . 'AND PE.id_per = LI.bu_id '
      . 'AND LI.bu_type = "'.$userType.'"'; 

      
    switch ($userType) {
      
      case 'USER_VIL':
        $sql .= ' AND ((PE.type_ref = "GVILLE" AND kernel_bu_ville.id_grville = PE.reference)'; // Agent GRVille
        $sql .= ' OR (PE.type_ref = "VILLE" AND kernel_bu_ville.id_vi = PE.reference))'; // Agent Ville 
        break;
      case 'USER_ADM':
        $sql .= ' AND (PE.type_ref = "ECOLE" AND kernel_bu_ville.id_vi IN (SELECT EC.id_ville FROM kernel_bu_ecole EC WHERE PE.reference=EC.numero))'; // Personnel Administratif 
        break;
      case 'USER_ENS':
        $sql .= ' AND ((PE.type_ref = "ECOLE" AND kernel_bu_ville.id_vi IN (SELECT EC.id_ville FROM kernel_bu_ecole EC WHERE PE.reference=EC.numero))';
        $sql .= ' OR (PE.type_ref = "CLASSE" AND kernel_bu_ville.id_vi IN (SELECT EC.id_ville FROM kernel_bu_ecole EC WHERE EC.numero IN (SELECT ecole FROM kernel_bu_ecole_classe WHERE id=PE.reference))))';
        break;
    }

    return new CopixDAORecordIterator (_doQuery ($sql), $this->getDAOId ());
	}
}