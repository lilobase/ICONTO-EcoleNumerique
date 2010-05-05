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
      . 'ORDER BY kernel_bu_classe_niveau.id_n';

    return new CopixDAORecordIterator (_doQuery ($sql, array (':classId' => $classId)), $this->getDAOId ());
  }
}