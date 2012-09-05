<?php

/**
* @package    Iconito
* @subpackage Cahierdetextes
*/

class DAORecordCahierDeTextesMemo
{
}

class DAOCahierDeTextesMemo
{
  /**
   * Retourne les mémos pour un élève donné
   *
   * @param int $idEleve
   *
   * @return CopixDAORecordIterator
   */
  public function findByEleve ($idEleve, $current = false)
  {
    $sql = 'SELECT cahierdetextesmemo.*, module_cahierdetextes_memo2eleve.signe_le, module_cahierdetextes_memo2eleve.commentaire'
      . ' FROM module_cahierdetextes_memo AS cahierdetextesmemo'
        . ' LEFT JOIN module_cahierdetextes_memo2eleve ON (cahierdetextesmemo.id = module_cahierdetextes_memo2eleve.module_cahierdetextes_memo_id)'
        . ' WHERE module_cahierdetextes_memo2eleve.kernel_bu_eleve_idEleve=:idEleve'
        . ' AND cahierdetextesmemo.supprime = 0';

      if ($current) {

        $sql .= ' AND (cahierdetextesmemo.date_validite >= '.date('Ymd')
          . ' OR cahierdetextesmemo.date_validite IS NULL)';
      }

    $sql .= ' GROUP BY cahierdetextesmemo.id'
      . ' ORDER BY cahierdetextesmemo.date_creation DESC, cahierdetextesmemo.id DESC';

      return _doQuery ($sql, array(':idEleve' => $idEleve));
  }

  /**
   * Retourne les mémos pour une classe donnée
   *
   * @param int   $idClasse
   * @param bool  $current (mémos valide à la date du jour ?)
   *
   * @return CopixDAORecordIterator
   */
  public function findByClasse ($idClasse, $current = false)
  {
    $criteria = _daoSp ();
        $criteria->addCondition ('classe_id', '=', $idClasse);
        $criteria->addCondition ('supprime', '=', 0);
        if ($current) {
          $criteria->startGroup ()
               ->addCondition ('date_validite', '>=', date('Ymd'))
               ->addCondition ('date_validite', '=', null, 'or')
               ->endGroup ();
        }
        $criteria->groupBy ('id');
        $criteria->orderBy (array ('date_creation', 'DESC'));
        $criteria->orderBy (array ('id' , 'DESC'));

        return $this->findBy ($criteria);
  }

  /**
   * Retourne le nombre de mémos non signés d'un élève
   *
   * @param int $idEleve
   *
   * @return int
   */
  public function retrieveNombreMemosNonSignesParEleve ($idEleve)
  {
    $results = array();

    $sql = 'SELECT COUNT(cahierdetextesmemo.id) as counter FROM module_cahierdetextes_memo AS cahierdetextesmemo'
        . ' LEFT JOIN module_cahierdetextes_memo2eleve ON (cahierdetextesmemo.id = module_cahierdetextes_memo2eleve.module_cahierdetextes_memo_id)'
        . ' WHERE module_cahierdetextes_memo2eleve.kernel_bu_eleve_idEleve=:idEleve'
        . ' AND cahierdetextesmemo.supprime = 0'
        . ' AND cahierdetextesmemo.avec_signature = 1'
        . ' AND module_cahierdetextes_memo2eleve.signe_le IS NULL';

    $results = _doQuery ($sql, array(':idEleve' => $idEleve));

    return $results[0] ? $results[0]->counter : 0;
  }
}