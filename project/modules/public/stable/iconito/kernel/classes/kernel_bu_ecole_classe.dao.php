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
		
		$sql = $this->_selectQuery
		  . ', kernel_bu_ecole_classe_niveau '
		  . 'WHERE kernel_bu_ecole_classe.id=kernel_bu_ecole_classe_niveau.classe '
		  . 'AND kernel_bu_ecole_classe.ecole=:idEcole';

		if (!is_null($grade)) {
		  
		  $sql .= ' AND kernel_bu_ecole_classe.annee_scol='.$grade;
		}
		
		$sql .= ' GROUP BY kernel_bu_ecole_classe.id '
		  . 'ORDER BY kernel_bu_ecole_classe_niveau.niveau, kernel_bu_ecole_classe.nom';
		
		return new CopixDAORecordIterator (_doQuery ($sql, array(':idEcole' => $idEcole)), $this->getDAOId ());
	}
	
	/**
	 * Retourne les classes accessibles pour un utilisateur
	 *
	 * @param array $groups  Groupes
	 * @param int $grade
   *
	 * @return CopixDAORecordIterator
	 */
	public function findByUserGroups ($groups, $grade = null) {
		
		$groupsIds = array(
      'citiesGroupsIds' => array(),
      'citiesIds'       => array(),
      'schoolsIds'      => array(),
      'classroomsIds'   => array()
    );
    
    foreach ($groups as $key => $group) {
      
      $id = substr($key, strrpos($key, '_')+1);
      
      if (preg_match('/^cities_group_agent/', $key)) {
        
        $groupsIds['citiesGroupsIds'][] = $id;
      }
      elseif (preg_match('/^city_agent/', $key)) {
        
        $groupsIds['citiesIds'][] = $id;
      }
      elseif (preg_match('/^administration_staff/', $key)) {
        
        $groupsIds['schoolsIds'][] = $id;
      }
      elseif (preg_match('/^principal/', $key)) {
        
        $groupsIds['schoolsIds'][] = $id;
      }
      elseif (preg_match('/^teacher/', $key)) {
        
        $groupsIds['classroomsIds'][] = $id;
      }
    }
    
    if (empty ($groupsIds['citiesGroupsIds']) && empty ($groupsIds['citiesIds'])
      && empty ($groupsIds['schoolsIds']) && empty ($groupsIds['classroomsIds'])) {
      
      return array();
    }
    
		$sql = $this->_selectQuery
		  . ', kernel_bu_groupe_villes, kernel_bu_ville, kernel_bu_ecole, kernel_bu_ecole_classe_niveau '
		  . 'WHERE kernel_bu_groupe_villes.id_grv=kernel_bu_ville.id_grville '
		  . 'AND kernel_bu_ville.id_vi=kernel_bu_ecole.id_ville '
		  . 'AND kernel_bu_ecole.numero=kernel_bu_ecole_classe.ecole '
		  . 'AND kernel_bu_ecole_classe.id=kernel_bu_ecole_classe_niveau.classe';
		
		if (!is_null($grade)) {
		  
		  $sql .= ' AND kernel_bu_ecole_classe.annee_scol='.$grade;
		}
		
		$conditions = array();
		if (!empty ($groupsIds['citiesGroupsIds'])) {
		  
		  $conditions[] = 'kernel_bu_groupe_villes.id_grv IN ('.implode(',', $groupsIds['citiesGroupsIds']).')';
		}
		if (!empty ($groupsIds['citiesIds'])) {
		  
		  $conditions[] = 'kernel_bu_ville.id_vi IN ('.implode(',', $groupsIds['citiesIds']).')';
		}
		if (!empty ($groupsIds['schoolsIds'])) {
		  
		  $conditions[] = 'kernel_bu_ecole.numero IN ('.implode(',', $groupsIds['schoolsIds']).')';
		}
		if (!empty ($groupsIds['classroomsIds'])) {
		  
		  $conditions[] = 'kernel_bu_ecole_classe.id IN ('.implode(',', $groupsIds['classroomsIds']).')';
		}
		
		$sql .= ' AND ('.implode('OR', $conditions).')';
		$sql .= ' GROUP BY kernel_bu_ecole.numero, kernel_bu_ecole_classe.id';
		$sql .= ' ORDER BY kernel_bu_ecole_classe_niveau.niveau, kernel_bu_ecole_classe.nom';
    
    return new CopixDAORecordIterator (_doQuery ($sql), $this->getDAOId ());
	}
}