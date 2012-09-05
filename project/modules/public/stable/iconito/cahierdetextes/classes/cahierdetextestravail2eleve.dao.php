<?php

/**
* @package    Iconito
* @subpackage Cahierdetextes
*/

class DAORecordCahierDeTextesTravail2eleve
{
}

class DAOCahierDeTextesTravail2eleve
{
    /**
   * Retourne les élèves concernés par un travail
   *
   * @param int $idTravail
   *
   * @return CopixDAORecordIterator
   */
    public function findElevesParTravail ($idTravail)
    {
      $toReturn = array();

      $sql = 'SELECT E.idEleve, E.nom, E.prenom1, CN.niveau_court, U.login_dbuser AS login, LI.bu_type, LI.bu_id, CL.nom as nom_classe, T.rendu_le'
      . ' FROM kernel_bu_eleve E, kernel_bu_eleve_affectation A, kernel_link_bu2user LI, dbuser U, kernel_bu_classe_niveau CN,'
      . ' kernel_bu_ecole_classe CL, module_cahierdetextes_travail2eleve AS T, module_cahierdetextes_travail as travail, module_cahierdetextes_domaine D'
          . ' WHERE E.idEleve = A.eleve'
          . ' AND E.idEleve = T.kernel_bu_eleve_idEleve'
          . ' AND A.classe = CL.id'
          . ' AND LI.bu_type = "USER_ELE"'
          . ' AND LI.bu_id=E.idEleve'
          . ' AND U.id_dbuser = LI.user_id'
          . ' AND A.niveau=CN.id_n'
          . ' AND A.current = 1'
          . ' AND T.module_cahierdetextes_travail_id = :idTravail'
          . ' AND travail.id = T.module_cahierdetextes_travail_id'
          . ' AND travail.module_cahierdetextes_domaine_id = D.id'
          . ' AND D.kernel_bu_ecole_classe_id = CL.id'
          . ' GROUP BY E.idEleve'
          . ' ORDER BY E.nom, E.prenom1';

      return _doQuery ($sql, array(':idTravail' => $idTravail));
    }

    /**
   * Retourne les identifiants des élèves concernés par un travail
   *
   * @param int $idTravail
   *
   * @return array
   */
    public function findEleveIdsParTravail ($idTravail)
    {
      $toReturn = array();

      $sql = 'SELECT kernel_bu_eleve_idEleve as idEleve'
        . ' FROM module_cahierdetextes_travail2eleve'
        . ' WHERE module_cahierdetextes_travail_id = :idTravail'
        . ' GROUP BY idEleve';

      $resultats = _doQuery ($sql, array(':idTravail' => $idTravail));
      foreach($resultats as $resultat) {
        $toReturn[] = $resultat->idEleve;
      }

      return empty($toReturn) ? null : $toReturn;
    }


    /**
     * Retourne le lien d'un travail avec un élève
     *
     * @param int $travailId Identifiant du travail
     * @param int $eleveId   Identifiant de l'élève
     *
     * @return DAORecordCahierDeTextesTravail2eleve or false
     */
    public function getByTravailAndEleve ($travailId, $eleveId)
    {
        $criteria = _daoSp ();
        $criteria->addCondition ('travail_id', '=', $travailId);
        $criteria->addCondition ('eleve_id', '=', $eleveId);

        $results = $this->findBy ($criteria);

        return isset ($results[0]) ? $results[0] : false;
    }
}