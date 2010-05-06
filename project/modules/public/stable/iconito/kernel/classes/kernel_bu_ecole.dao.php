<?php

class DAORecordKernel_bu_ecole {
  
  protected $_city = null;
  
  public function getCity () {
    
    if (is_null($this->_city)) {
      
      $cityDAO = _ioDAO ('kernel|kernel_bu_ville');
      
      $this->_city = $cityDAO->get ($this->id_ville);
    }
    
    return $this->_city;
  }
}
  
class DAOKernel_bu_ecole {

	/**
	 * Retourne les classes pour une ville donnée
	 *
	 * @param int $idVille Identifiant d'une ville
	 * @return CopixDAORecordIterator
	 */
	public function getByCity ($idVille) {
		
		$criteria = _daoSp ();
		$criteria->addCondition ('id_ville', '=', $idVille);
		
		return $this->findBy ($criteria);
	}
	
	/**
	 * Retourne les écoles accessibles pour un utilisateur
	 *
	 * @param int    $cityId   Identifiant de la ville
	 * @param int    $userId   Identifiant de l'utilisateur
	 * @param string $userType Type de l'utilisateur
	 * @return CopixDAORecordIterator
	 */
	public function findByUserIdAndUserType ($cityId, $userId, $userType) {
		
		$sql = $this->_selectQuery
      . ', kernel_bu_personnel_entite PE, kernel_link_bu2user LI '
      . 'WHERE id_ville ='.$cityId.' ' 
      . 'AND LI.user_id ='.$userId.' ' 
      . 'AND PE.id_per = LI.bu_id '
      . 'AND LI.bu_type = "'.$userType.'"'; 

      
    switch ($userType) {
      
      case 'USER_VIL':
        $sql .= ' AND ((PE.type_ref = "GVILLE" AND numero IN (SELECT numero FROM kernel_bu_ecole WHERE id_ville IN (SELECT id_vi FROM kernel_bu_ville WHERE id_grville = PE.reference)))'; // Agent GRVille
        $sql .= ' OR (PE.type_ref = "VILLE" AND numero IN (SELECT ecole FROM kernel_bu_ecole WHERE id_ville = PE.reference))'; // Agent Ville
        break;
      case 'USER_ADM':
        $sql .= ' AND (PE.type_ref = "ECOLE" AND PE.reference=numero)'; // Personnel Administratif
        break;
      case 'USER_ENS':
        $sql .= ' AND ((PE.type_ref = "ECOLE" AND PE.reference=numero)';
        $sql .= ' OR (PE.type_ref = "CLASSE" AND numero IN (SELECT ecole FROM kernel_bu_ecole_classe WHERE id=PE.reference)))';
        break;
    }

    return new CopixDAORecordIterator (_doQuery ($sql), $this->getDAOId ());
	}
}