<?php

class DAORecordKernel_bu_classe_niveau {

  public function __toString () {
    
    return $this->niveau_court;
  }
}

class DAOKernel_bu_classe_niveau {
  
  /**
   * Retourne tous les niveaux d'une classe
   *
   * @param int $classId
   *
   * @return CopixDAORecordIterator
   */
  public function findByClassId ($classId) {
    
    $sql = $this->_selectQuery
      . ' , kernel_bu_ecole_classe_niveau '
      . 'WHERE kernel_bu_classe_niveau.id_n=kernel_bu_ecole_classe_niveau.niveau '
      . 'AND kernel_bu_ecole_classe_niveau.classe=:classId '
      . 'GROUP BY kernel_bu_classe_niveau.id_n '
      . 'ORDER BY kernel_bu_classe_niveau.id_n';

    return new CopixDAORecordIterator (_doQuery ($sql, array (':classId' => $classId)), $this->getDAOId ());
  }
  
  /**
   * Retourne tous les niveaux disponibles dans une école
   *
   * @param int $schoolId
   * @param int $gradeId
   *
   * @return CopixDAORecordIterator
   */
  public function findBySchoolId ($schoolId, $gradeId = null) {
    
    $sql = $this->_selectQuery
      . ' , kernel_bu_ecole_classe_niveau, kernel_bu_ecole_classe '
      . 'WHERE kernel_bu_classe_niveau.id_n=kernel_bu_ecole_classe_niveau.niveau '
      . 'AND kernel_bu_ecole_classe_niveau.classe=kernel_bu_ecole_classe.id '
      . 'AND kernel_bu_ecole_classe.ecole=:schoolId';
      
      if (!is_null($gradeId)) {
        
        $sql .= ' AND kernel_bu_ecole_classe.annee_scol = '.$gradeId;
      }
    
    $sql .= ' GROUP BY kernel_bu_classe_niveau.id_n '
      . 'ORDER BY kernel_bu_classe_niveau.id_n';

    return new CopixDAORecordIterator (_doQuery ($sql, array (':schoolId' => $schoolId)), $this->getDAOId ());
  }
}