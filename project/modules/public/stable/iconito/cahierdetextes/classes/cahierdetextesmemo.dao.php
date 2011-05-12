<?php

/**
* @package    Iconito
* @subpackage Cahierdetextes
*/

class DAORecordCahierDeTextesMemo {
  
}

class DAOCahierDeTextesMemo {

  /**
   * Retourne les mémos pour un élève donné
   *
   * @param int $idEleve
   *
   * @return CopixDAORecordIterator
   */
  public function findByEleve ($idEleve) {
    
    $sql = 'SELECT '
  	  . ' LEFT JOIN module_cahierdetextes_memo2eleve ON (cahierdetextesmemo.id = module_cahierdetextes_memo2eleve.module_cahierdetextes_memo_id)'
  	  . ' WHERE module_cahierdetextes_memo2eleve.kernel_bu_eleve_idEleve=:idEleve'
  	  . ' AND cahierdetextesmemo.supprime = 0'
      . ' GROUP BY cahierdetextesmemo.id'
      . ' ORDER BY cahierdetextesmemo.date_creation ASC';

  	return _doQuery ($sql, array(':idEleve' => $idEleve));
  }
  
  /**
   * Retourne les mémos pour une classe donnée
   *
   * @param int $idClasse
   *
   * @return CopixDAORecordIterator
   */
  public function findByClasse ($idClasse) {
    
    $sql = $this->_selectQuery
  	  . ' WHERE kernel_bu_ecole_classe_id=:idClasse'
  	  . ' AND supprime = 0'
      . ' GROUP BY id'
      . ' ORDER BY date_creation ASC';

  	return _doQuery ($sql, array(':idClasse' => $idClasse));
  }
  
  /**
   * Retourne le nombre de mémos non signés d'un élève
   *
   * @param int $idEleve
   *
   * @return int
   */
  public function retrieveNombreMemosNonSignesParEleve ($idEleve) {
    
    $sql = 'SELECT COUNT(cahierdetextesmemo.id) as counter FROM module_cahierdetextes_memo AS cahierdetextesmemo'
  	  . ' LEFT JOIN module_cahierdetextes_memo2eleve ON (cahierdetextesmemo.id = module_cahierdetextes_memo2eleve.module_cahierdetextes_memo_id)'
  	  . ' WHERE module_cahierdetextes_memo2eleve.kernel_bu_eleve_idEleve=:idEleve'
  	  . ' AND cahierdetextesmemo.supprime = 0'
  	  . ' AND cahierdetextesmemo.avec_signature = 1'
  	  . ' AND module_cahierdetextes_memo2eleve.signe_le IS NULL'
      . ' GROUP BY cahierdetextesmemo.id'
      . ' ORDER BY cahierdetextesmemo.date_creation ASC';
      
    $results = _doQuery ($sql, array(':idEleve' => $idEleve));
    
    return $results[0]->counter;
  }
}