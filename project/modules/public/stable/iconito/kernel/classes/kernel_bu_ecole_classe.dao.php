<?php

class DAORecordKernel_bu_ecole_classe {
  
  protected $_levels = null;
  protected $_school = null;
  
  public function __toString () {

    return $this->nom.' ('.implode(' - ', $this->getLevels ()->fetchAll ()).')';
  }
  
  public function getSchool () {
    
    if (is_null($this->_school)) {
      
      $schoolDAO = _ioDAO ('kernel|kernel_bu_ecole');
      
      $this->_school = $schoolDAO->get ($this->ecole);
    }
    
    return $this->_school;
  }
  
  public function getLevels () {
    
    if (is_null($this->_levels)) {
      
      $levelDAO = _ioDAO ('kernel|kernel_bu_classe_niveau');
      $this->_levels = $levelDAO->findByClassId ($this->id);
    }
    
    return $this->_levels;
  }
}
  
class DAOKernel_bu_ecole_classe {

	/**
	 * Retourne les classes pour une école donnée
	 *
	 * @param int $idEcole Identifiant d'une école
	 * @param int $grade
	 *
	 * @return CopixDAORecordIterator
	 */
	public function getBySchool ($idEcole, $grade = null) {
		
		$criteria = _daoSp ();
		$criteria->addCondition ('ecole', '=', $idEcole);
		if (!is_null($grade)) {
		  
		  $criteria->addCondition ('annee_scol', '=', $grade);
		}
		  
		$criteria->orderBy (array ('id', 'DESC'));
		
		return $this->findBy ($criteria);
	}
	
	/**
	 * Retourne les classes accessibles pour un utilisateur
	 *
	 * @param int    $schoolId   Identifiant de la ville
	 * @param int    $userId   Identifiant de l'utilisateur
	 * @param string $userType Type de l'utilisateur
	 * @return CopixDAORecordIterator
	 */
	public function findByUserIdAndUserType ($schoolId, $userId, $userType, $grade) {
		
		$sql = $this->_selectQuery
      . ', kernel_bu_personnel_entite PE, kernel_link_bu2user LI '
      . 'WHERE ecole ='.$schoolId.' '
      . 'AND kernel_bu_ecole_classe.annee_scol='.$grade.' ' 
      . 'AND LI.user_id ='.$userId.' ' 
      . 'AND PE.id_per = LI.bu_id '
      . 'AND LI.bu_type = "'.$userType.'"'; 

    switch ($userType) {
      
      case 'USER_VIL':
        $sql .= ' AND ((PE.type_ref = "GVILLE" AND kernel_bu_ecole_classe.id IN (SELECT id FROM kernel_bu_ecole_classe WHERE ecole IN (SELECT id FROM kernel_bu_ecole WHERE id_ville IN (SELECT id_vi FROM kernel_bu_ville WHERE id_grville = PE.reference))))'; // Agent GRVille
        $sql .= ' OR (PE.type_ref = "VILLE" AND kernel_bu_ecole_classe.id IN (SELECT id FROM kernel_bu_ecole_classe WHERE ecole IN (SELECT id FROM kernel_bu_ecole WHERE id_ville = PE.reference))))'; // Agent Ville
        break;
      case 'USER_ADM':
        $sql .= ' AND (PE.type_ref = "ECOLE" AND kernel_bu_ecole_classe.id IN (SELECT id FROM kernel_bu_ecole_classe WHERE ecole=PE.reference)'; // Personnel Administratif
        break;
      case 'USER_ENS':
        $sql .= ' AND ((PE.type_ref = "ECOLE" AND kernel_bu_ecole_classe.id IN (SELECT id FROM kernel_bu_ecole_classe WHERE ecole=PE.reference))';
        $sql .= ' OR (PE.type_ref = "CLASSE" AND kernel_bu_ecole_classe.id=PE.reference))';
        break;
    }
    
    return new CopixDAORecordIterator (_doQuery ($sql), $this->getDAOId ());
	}
}