<?php

/**
* @package    Iconito
* @subpackage Cahierdetextes
*/

class DAORecordCahierDeTextesMemo2eleve
{
}

class DAOCahierDeTextesMemo2eleve
{
    /**
   * Retourne les identifiants des élèves concernés par un memo
   *
   * @param int $idMemo
   *
   * @return array
   */
    public function findElevesParMemo ($idMemo)
    {
      $toReturn = array();

      $sql = 'SELECT kernel_bu_eleve_idEleve as idEleve FROM module_cahierdetextes_memo2eleve WHERE module_cahierdetextes_memo_id = :idMemo GROUP BY idEleve';

      $resultats = _doQuery ($sql, array(':idMemo' => $idMemo));
      foreach($resultats as $resultat) {
        $toReturn[] = $resultat->idEleve;
      }

      return empty($toReturn) ? null : $toReturn;
    }

    /**
   * Retourne le suivi des élèves concernés par un memo
   *
   * @param int $idMemo
   *
   * @return array
   */
    public function findSuiviElevesParMemo ($idMemo)
    {
      $toReturn = array();

      $sql = 'SELECT E.idEleve, E.nom, E.prenom1, CN.niveau_court, U.login_dbuser AS login, LI.bu_type, LI.bu_id, CL.nom as nom_classe, M2E.signe_le, M2E.commentaire'
      . ' FROM kernel_bu_eleve E, kernel_bu_eleve_affectation A, kernel_link_bu2user LI, dbuser U, kernel_bu_classe_niveau CN, kernel_bu_ecole_classe CL, module_cahierdetextes_memo as M, module_cahierdetextes_memo2eleve AS M2E'
          . ' WHERE E.idEleve = A.eleve'
          . ' AND A.eleve = M2E.kernel_bu_eleve_idEleve'
          . ' AND A.classe = CL.id'
          . ' AND A.niveau = CN.id_n'
          . ' AND A.current = 1'
          . ' AND A.eleve = LI.bu_id'
          . ' AND LI.user_id = U.id_dbuser'
          . ' AND LI.bu_type = "USER_ELE"'
          . ' AND M2E.module_cahierdetextes_memo_id = :idMemo'
          . ' AND M.id = M2E.module_cahierdetextes_memo_id'
          . ' AND M.kernel_bu_ecole_classe_id = CL.id'
          . ' AND CL.annee_scol = A.annee_scol'
          . ' GROUP BY E.idEleve'
          . ' ORDER BY E.nom, E.prenom1';

      return _doQuery ($sql, array(':idMemo' => $idMemo));
    }

    /**
   * Retourne le nombre d'élèves concernés par un memo
   *
   * @param int $idMemo
   *
   * @return int
   */
    public function retrieveNombreElevesConcernesParMemo ($idMemo)
    {
      $results = array();

      $sql = 'SELECT COUNT(module_cahierdetextes_memo_id) as counter'
      . ' FROM module_cahierdetextes_memo2eleve'
          . ' WHERE module_cahierdetextes_memo_id = :idMemo';

      $results = _doQuery ($sql, array(':idMemo' => $idMemo));

      return $results[0] ? $results[0]->counter : 0;
    }
}