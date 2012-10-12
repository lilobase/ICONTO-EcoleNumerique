<?php

/**
 * Surcharge de la DAO Kernel_bu_ele
 *
 * @package Iconito
 * @subpackage Kernel
 */
class DAOKernel_bu_ele
{
    /**
     * Renvoie la liste des élèves rattachés à une classe et ayant un compte utilisateur (facultatif : pour une année scolaire donnée)
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2006/01/19
     *
     * @param integer $classe     Id de la classe
     * @param string  $anneeScol  Année scolaire
     *
     * @return mixed Objet DAO
     */
    public function getElevesInClasse ($classe, $anneeScol = null)
    {
      $sql = 'SELECT E.idEleve AS id, E.nom, E.prenom1 as prenom, S.sexe, E.date_nais AS date_naissance, EC.nom AS nom_classe, U.login_dbuser AS login, LI.bu_type, LI.bu_id, CN.niveau_court AS niveau, CN.id_n AS niveauId'
        . ' FROM kernel_bu_eleve_affectation EA, kernel_bu_eleve E, kernel_bu_sexe S, kernel_bu_ecole_classe EC, kernel_bu_classe_niveau CN, kernel_link_bu2user LI, dbuser U'
        . ' WHERE EC.id=EA.classe'
        . ' AND EA.eleve=E.idEleve'
        . ' AND EA.niveau=CN.id_n'
        . ' AND E.id_sexe=S.id_s'
        . ' AND LI.user_id=U.id_dbuser'
        . ' AND LI.bu_type="USER_ELE"'
        . ' AND LI.bu_id=E.idEleve'
        . ' AND EA.current=1'
        . ' AND EC.id=:classe';

      if (!is_null($anneeScol)) {

        $sql .= ' AND EA.annee_scol='.$anneeScol;
      }

      $sql .= ' ORDER BY nom, prenom1';

        return _doQuery($sql, array(':classe' => $classe));
    }

    /**
     * Renvoie la liste des élèves rattachés à une école et ayant un compte utilisateur (facultatif : pour une année scolaire donnée)
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2006/01/19
     *
     * @param integer $ecole      Id de l'école
     * @param string  $anneeScol  Année scolaire
     *
     * @return mixed Objet DAO
     */
    public function getElevesInEcole ($ecole, $anneeScol = null)
    {
      $sql = 'SELECT E.idEleve AS id, E.nom, E.prenom1 as prenom, S.sexe, E.date_nais AS date_naissance, EC.nom AS nom_classe, U.login_dbuser AS login, LI.bu_type, LI.bu_id, CN.niveau_court AS niveau, CN.id_n AS niveauId'
        . ' FROM kernel_bu_eleve_affectation EA, kernel_bu_eleve E, kernel_bu_sexe S, kernel_bu_ecole_classe EC, kernel_bu_classe_niveau CN, kernel_link_bu2user LI, dbuser U'
        . ' WHERE EC.id=EA.classe'
        . ' AND EA.eleve=E.idEleve'
        . ' AND EA.niveau=CN.id_n'
        . ' AND E.id_sexe=S.id_s'
        . ' AND LI.user_id=U.id_dbuser'
        . ' AND LI.bu_type="USER_ELE"'
        . ' AND LI.bu_id=E.idEleve'
        . ' AND EA.current=1'
        . ' AND EC.ecole=:ecole';

      if (!is_null($anneeScol)) {

        $sql .= ' AND EA.annee_scol='.$anneeScol;
      }

      $sql .= ' ORDER BY nom, prenom1';

      return _doQuery($sql, array(':ecole' => $ecole));
    }

    /**
     * Renvoie la liste des élèves rattachés à une ville et ayant un compte utilisateur
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2006/01/20
     * @param integer $ville Id de la ville
     * @return mixed Objet DAO
     */
    public function getElevesInVille ($ville)
    {
        $query = "SELECT E.idEleve AS id, E.nom, E.prenom1 as prenom, S.sexe, E.date_nais AS date_naissance, EC.nom AS nom_classe, U.login_dbuser AS login, LI.bu_type, LI.bu_id, CN.niveau_court FROM kernel_bu_eleve_affectation EA, kernel_bu_eleve E, kernel_bu_sexe S, kernel_bu_ecole_classe EC, kernel_bu_classe_niveau CN, kernel_bu_ecole ECO, kernel_link_bu2user LI, dbuser U, kernel_bu_annee_scolaire AN WHERE EC.id=EA.classe AND EA.eleve=E.idEleve AND EA.niveau=CN.id_n AND E.id_sexe=S.id_s AND EC.ecole=ECO.numero AND LI.user_id=U.id_dbuser AND LI.bu_type='USER_ELE' AND LI.bu_id=E.idEleve AND EA.current=1 AND ECO.id_ville=".$ville." AND EC.annee_scol=AN.id_as AND AN.current=1 ORDER BY nom, prenom1";

        return _doQuery($query);
    }


    /**
     * Renvoie la liste des élèves rattachés à un groupe de villes et ayant un compte utilisateur
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2006/01/20
     * @param integer $grville Id du groupe de villes
     * @return mixed Objet DAO
     */
    public function getElevesInGrville ($grville)
    {
        $sqlPlus = '';
        if ( Kernel::getKernelLimits('ville') )
            $sqlPlus .= ' AND VIL.id_vi IN ('.Kernel::getKernelLimits('ville').')';
        $query = "SELECT E.idEleve AS id, E.nom, E.prenom1 as prenom, S.sexe, E.date_nais AS date_naissance, EC.nom AS nom_classe, U.login_dbuser AS login, LI.bu_type, LI.bu_id, CN.niveau_court FROM kernel_bu_eleve_affectation EA, kernel_bu_eleve E, kernel_bu_sexe S, kernel_bu_ecole_classe EC, kernel_bu_classe_niveau CN, kernel_bu_ecole ECO, kernel_bu_ville VIL, kernel_link_bu2user LI, dbuser U, kernel_bu_annee_scolaire AN WHERE EC.id=EA.classe AND EA.eleve=E.idEleve AND EA.niveau=CN.id_n AND E.id_sexe=S.id_s AND EC.ecole=ECO.numero AND ECO.id_ville=VIL.id_vi AND LI.user_id=U.id_dbuser AND LI.bu_type='USER_ELE' AND LI.bu_id=E.idEleve AND EA.current=1 AND VIL.id_grville=".$grville.$sqlPlus." AND EC.annee_scol=AN.id_as AND AN.current=1 ORDER BY nom, prenom1";
        //print_r($query);
        return _doQuery($query);
    }

  /**
     * Retourne les élèves d'une classe donnée
     *
     * @param integer $classId   Identifiant de la classe
     */
  public function getStudentsByClass ($classId)
  {
    $sql = 'SELECT E.idEleve, E.idEleve as id, E.nom, E.prenom1, E.prenom1 as prenom, E.id_sexe, CN.niveau_court, U.login_dbuser AS login, LI.bu_type, LI.bu_id, CL.nom as nom_classe, CN.niveau_court AS niveau, CN.id_n AS niveauId'
      . ' FROM kernel_bu_eleve E, kernel_bu_eleve_affectation A, kernel_link_bu2user LI, dbuser U, kernel_bu_classe_niveau CN, kernel_bu_ecole_classe CL'
          . ' WHERE E.idEleve = A.eleve'
          . ' AND A.classe = CL.id'
          . ' AND A.classe=:id'
          . ' AND A.current=1'
          . ' AND LI.bu_type = "USER_ELE"'
          . ' AND LI.bu_id=E.idEleve'
          . ' AND U.id_dbuser = LI.user_id'
          . ' AND A.niveau=CN.id_n'
          . ' GROUP BY E.idEleve'
      . ' ORDER BY E.nom, E.prenom1';

    return _doQuery ($sql, array (':id' => $classId));
  }

  /**
     * Retourne les identifiants des élèves d'une classe donnée
     *
     * @param integer $classId   Identifiant de la classe
     *
     * return array Identifiants des élèves
     */
  public function getStudentIdsByClass ($classId)
  {
    $toReturn = array();

    $sql = 'SELECT E.idEleve'
      . ' FROM kernel_bu_eleve E, kernel_bu_eleve_affectation A, kernel_link_bu2user LI, dbuser U, kernel_bu_classe_niveau CN, kernel_bu_ecole_classe CL'
          . ' WHERE E.idEleve = A.eleve'
          . ' AND A.classe = CL.id'
          . ' AND A.classe=:id'
          . ' AND A.current=1'
          . ' AND LI.bu_type = "USER_ELE"'
          . ' AND LI.bu_id=E.idEleve'
          . ' AND U.id_dbuser = LI.user_id'
          . ' AND A.niveau=CN.id_n'
          . ' GROUP BY E.idEleve'
          . ' ORDER BY E.nom, E.prenom1';

    $results = _doQuery ($sql, array (':id' => $classId));

    foreach ($results as $result) {

      $toReturn[] = $result->idEleve;
    }

    return $toReturn;
  }

  /**
     * Retourne les élèves sous la responsabilité d'une personne
     *
     * @param integer $personId   Identifiant du responsable
     */
  public function getStudentsByPersonInChargeId ($personId)
  {
    $sql = 'SELECT kernel_bu_eleve.*, u.login_dbuser AS login, kernel_bu_lien_parental.parente as link'
      . ' FROM kernel_bu_eleve, kernel_bu_responsables, kernel_link_bu2user li, dbuser u, kernel_bu_lien_parental'
          . ' WHERE kernel_bu_responsables.id_responsable=:personId'
          . ' AND kernel_bu_responsables.id_par=kernel_bu_lien_parental.id_pa'
          . ' AND kernel_bu_responsables.type_beneficiaire="eleve"'
          . ' AND kernel_bu_responsables.id_beneficiaire=kernel_bu_eleve.idEleve'
          . ' AND li.bu_type="USER_ELE"'
          . ' AND li.bu_id=kernel_bu_eleve.idEleve'
          . ' AND u.id_dbuser = li.user_id'
          . ' ORDER BY kernel_bu_eleve.nom, kernel_bu_eleve.prenom1';

    return _doQuery ($sql, array (':personId' => $personId));
  }

  /**
     * Retourne les élèves sans affectation d'une école donnée (25/06/2012 - plus utilisé)
     *
     * @param integer $schoolId   Identifiant de l'école
     */
  public function getStudentsWithoutAssignmentBySchool ($schoolId)
  {
    $sql = 'SELECT E.idEleve, E.idEleve as id, E.nom, E.prenom1, E.prenom1 as prenom, E.id_sexe, U.login_dbuser AS login, LI.bu_type, LI.bu_id, CL.nom as nom_classe'
      . ' FROM kernel_bu_eleve E, kernel_link_bu2user LI, dbuser U, kernel_bu_ecole EC'
      . ' LEFT JOIN kernel_bu_ecole_classe CL ON EC.numero = CL.ecole'
      . ' LEFT JOIN kernel_bu_eleve_affectation A ON A.classe = CL.id AND A.current = 0'
          . ' WHERE EC.numero = :schoolId'
          . ' AND E.idEleve = A.eleve'
          . ' AND A.classe = CL.id'
          . ' AND LI.bu_type = "USER_ELE"'
          . ' AND LI.bu_id=E.idEleve'
          . ' AND U.id_dbuser = LI.user_id'
          . ' GROUP BY E.idEleve'
          . ' HAVING SUM(A.current) = 0';

        $sql .= ' ORDER BY E.nom, E.prenom1';

    return _doQuery ($sql, array (':schoolId' => $schoolId));
  }

  /**
     * Retourne les élèves avec ou sans affectation pour une année donnée
     *
     * @param string  $grade    Année scolaire
     * @param array   $filters  Filtres de récupération des élèves
     *
     * @return array
     */
    public function findStudentsForAssignment ($grade, $filters = array ())
    {
    // Récupération des élèves qui ont leur dernière affectation qui correspond aux critères demandés
    $sql = 'SELECT E.idEleve as id, E.nom as nom, E.prenom1 as prenom, LI.bu_type as user_type, LI.bu_id as user_id, EC.id as id_classe, EC.nom as nom_classe, '
      .'CN.niveau_court AS nom_niveau, CN.id_n AS id_niveau, ECO.numero as id_ecole, ECO.nom as nom_ecole, '
      .'V.id_vi as id_ville, V.nom as nom_ville, GV.id_grv as id_groupevilles, GV.nom_groupe as nom_groupevilles, EA.*, max(EA2.id) AS max_ea_id '
      .'FROM kernel_bu_eleve E '
      .'JOIN kernel_link_bu2user LI ON LI.bu_id=E.idEleve '
      .'JOIN dbuser U ON U.id_dbuser=LI.user_id '
      .'JOIN kernel_bu_eleve_affectation EA ON (EA.eleve=E.idEleve AND EA.annee_scol = '.$grade.') '
      .'JOIN kernel_bu_classe_niveau CN ON EA.niveau=CN.id_n '
      .'JOIN kernel_bu_ecole_classe EC ON EC.id=EA.classe '
      .'JOIN kernel_bu_ecole ECO ON ECO.numero=EC.ecole '
      .'JOIN kernel_bu_ville V ON V.id_vi=ECO.id_ville '
      .'JOIN kernel_bu_groupe_villes GV ON GV.id_grv=V.id_grville '
      .'JOIN kernel_bu_eleve_affectation EA2 ON (EA2.eleve=E.idEleve AND EA2.annee_scol = '.$grade.') '
      .'WHERE LI.bu_type="USER_ELE" ';

    if (isset ($filters['level']) && !is_null ($filters['level'])) {

      $sql .= ' AND EA.niveau='.$filters['level'];
    }
    if (isset ($filters['classroom']) && !is_null ($filters['classroom'])) {

      $sql .= ' AND EC.id='.$filters['classroom'];
    }
    if (isset ($filters['school']) && !is_null ($filters['school'])) {

      $sql .= ' AND ECO.numero='.$filters['school'];
    }
    if (isset ($filters['city']) && !is_null ($filters['city'])) {

      $sql .= ' AND V.id_vi='.$filters['city'];
    }
    if (isset ($filters['cityGroup']) && !is_null ($filters['cityGroup'])) {

      $sql .= ' AND GV.id_grv='.$filters['cityGroup'];
    }
    if (isset ($filters['lastname']) && !is_null ($filters['lastname'])) {

      $sql .= ' AND E.nom LIKE \'' . $filters['lastname'] . '%\'';
    }
    if (isset ($filters['firstname']) && !is_null ($filters['firstname'])) {

      $sql .= ' AND E.prenom1 LIKE \'' . $filters['firstname'] . '%\'';
    }

    $sql .= ' GROUP BY E.idEleve, EA.id HAVING EA.id = max_ea_id';
    $sql .= ' ORDER BY CN.id_n, EC.nom, E.nom, E.prenom1';

    return _doQuery($sql);
  }

  /**
     * Retourne les élèves à assigner (manageAssignments)
     *
     * @param array   $filters   Filtres de récupération des élèves
     *
     * return CopixDAORecordIterator
     */
  public function findForManageAssignments ($grade, $filters = array ())
  {
    $sql = 'SELECT E.idEleve as id, E.nom as nom, E.prenom1 as prenom, LI.bu_type as user_type, LI.bu_id as user_id, EC.id as id_classe, EC.nom as nom_classe,'
      . 'CN.niveau_court AS nom_niveau, CN.id_n AS id_niveau, ECO.numero as id_ecole, ECO.nom as nom_ecole, '
      . 'V.id_vi as id_ville, V.nom as nom_ville, GV.id_grv as id_groupevilles, GV.nom_groupe as nom_groupevilles, EA.*, max(EA2.id) AS max_ea_id '
      . 'FROM kernel_link_bu2user LI '
      . 'JOIN dbuser U ON U.id_dbuser=LI.user_id '
      . 'JOIN kernel_bu_eleve E ON LI.bu_id=E.idEleve '
      . 'JOIN kernel_bu_eleve_affectation EA ON (EA.eleve=E.idEleve AND EA.annee_scol = '.$grade.') '
      . 'JOIN kernel_bu_classe_niveau CN ON EA.niveau=CN.id_n '
      . 'JOIN kernel_bu_ecole_classe EC ON EC.id=EA.classe '
      . 'JOIN kernel_bu_ecole ECO ON ECO.numero=EC.ecole '
      . 'JOIN kernel_bu_ville V ON V.id_vi=ECO.id_ville '
      . 'JOIN kernel_bu_groupe_villes GV ON GV.id_grv=V.id_grville '
      . 'JOIN kernel_bu_eleve_affectation EA2 ON (EA2.eleve=E.idEleve AND EA2.annee_scol = '.$grade.') ';

    if (isset ($filters['destinationGrade'])) {

      $sql .= ' LEFT JOIN kernel_bu_eleve_affectation EA_dest ON (EA_dest.eleve=E.idEleve AND EA_dest.current = 1 AND EA_dest.annee_scol='.$filters['destinationGrade'].')';
    }

    $sql .= ' WHERE  LI.bu_type="USER_ELE" ';

    if (isset ($filters['destinationGrade']) && !is_null ($filters['destinationGrade'])) {

      $sql .= ' AND EA_dest.eleve IS NULL';
    }

      if (isset ($filters['originClassroom']) && !is_null ($filters['originClassroom'])) {

      $sql .= ' AND EC.id='.$filters['originClassroom'];
    } elseif (isset ($filters['originSchool']) && !is_null ($filters['originSchool'])) {

      $sql .= ' AND ECO.numero='.$filters['originSchool'];
    } elseif (isset ($filters['originCity']) && !is_null ($filters['originCity'])) {

      $sql .= ' AND V.id_vi='.$filters['originCity'];
    } elseif (isset ($filters['originCityGroup']) && !is_null ($filters['originCityGroup'])) {

      $sql .= ' AND GV.id_grv='.$filters['originCityGroup'];
    }

    if (isset ($filters['originLastname']) && !is_null ($filters['originLastname'])) {

        $sql .= ' AND E.nom LIKE \'' . $filters['originLastname'] . '%\'';
      }
      if (isset ($filters['originFirstname']) && !is_null ($filters['originFirstname'])) {

        $sql .= ' AND E.prenom1 LIKE \'' . $filters['originFirstname'] . '%\'';
      }
      if (isset ($filters['originGrade']) && !is_null ($filters['originGrade'])) {

        $sql .= ' AND EA.annee_scol='.$filters['originGrade'];
      }
      if (isset ($filters['originLevel']) && !is_null ($filters['originLevel'])) {

        $sql .= ' AND EA.niveau='.$filters['originLevel'];
      }

    $sql .= ' GROUP BY E.idEleve, EA.id HAVING EA.id = max_ea_id'
      . ' ORDER BY CN.id_n, EC.nom, E.nom, E.prenom1';

    return _doQuery($sql);
  }

  /**
     * Retourne les élèves assignés
     *
     * @param array $groups   Groupes
     * @param array $filters  Filtres de récupération des élèves
     *
     * @return CopixDAORecordIterator
     */
  public function findAssigned ($filters = array (), $groups = null)
  {
    if (!is_null ($groups)) {

      $groupsIds = array();

      foreach ($groups as $key => $group) {

        $id = substr($key, strrpos($key, '_')+1);

        if (preg_match('/^teacher/', $key)) {

          $groupsIds[] = $id;
        } elseif (preg_match('/^schools_group_animator/', $key)) {

          $groupsIds[] = $id;
        } elseif (preg_match('/^cities_group_animator/', $key)) {

          $groupsIds[] = $id;
        }
      }
    }

      $sql = 'SELECT E.idEleve as user_id, "USER_ELE" as user_type, E.nom, E.prenom1 as prenom, LI.bu_type, LI.bu_id, EC.id as id_classe, EC.nom as nom_classe, CN.niveau_court AS nom_niveau, CN.id_n AS id_niveau, SUM(EA.current) AS is_affect'
      . ' FROM kernel_bu_eleve E'
      . ' JOIN kernel_link_bu2user LI ON (LI.bu_id=E.idEleve)'
      . ' JOIN dbuser U ON (U.id_dbuser=LI.user_id)'
      . ' JOIN kernel_bu_eleve_admission EAD ON (EAD.eleve=E.idEleve)'
      . ' JOIN kernel_bu_ecole ECO ON (ECO.numero=EAD.etablissement)'
      . ' JOIN kernel_bu_ville V ON (V.id_vi=ECO.id_ville)'
      . ' JOIN kernel_bu_groupe_villes GV ON (GV.id_grv=V.id_grville)'
      . ' JOIN kernel_bu_ecole_classe EC ON (EC.ecole=ECO.numero)'
      . ' JOIN kernel_bu_eleve_affectation EA ON (EA.eleve=E.idEleve AND EA.classe=EC.id AND EA.current=1)'
      . ' JOIN kernel_bu_classe_niveau CN ON (EA.niveau=CN.id_n)'
      . ' WHERE LI.bu_type="USER_ELE"';

    if (isset ($filters['grade'])) {

      $sql .= ' AND EA.annee_scol='.$filters['grade'];
    }
      if (isset ($filters['classroom'])) {

      $sql .= ' AND EC.id='.$filters['classroom'];
    } elseif (!is_null ($groups)) {

      $sql .= ' AND EC.id IN ('.implode(',', $groupsIds).')';
    }

    if (isset ($filters['school'])) {

      $sql .= ' AND ECO.numero='.$filters['school'];
    } elseif (isset ($filters['city'])) {

      $sql .= ' AND V.id_vi='.$filters['city'];
    } elseif (isset ($filters['cityGroup'])) {

      $sql .= ' AND GV.id_grv='.$filters['cityGroup'];
    }
    if (isset ($filters['level'])) {

        $sql .= ' AND EA.niveau='.$filters['level'];
      }

    $sql .= ' GROUP BY E.idEleve ORDER BY CN.id_n, EC.nom, E.nom, E.prenom1';

    return _doQuery($sql);
    }
    
    /**
     * Retourne les élèves avec ou sans affectations pour une année donnée
     *
     * @param int $grade Année scolaire
     * @param string $firstname Prénom
     * @param string $lastname Nom
     *
     * @return array
     */
    public function findStudentsForAssignmentByName($grade, $firstname = null, $lastname = null)
    {
        // Récupération des élèves qui ont leur dernière affectation qui correspond aux critères demandés
        $parameters = array();
        $sql = 'SELECT E.idEleve as id, E.nom as nom, E.prenom1 as prenom, LI.bu_type as user_type, LI.bu_id as user_id, EC.id as id_classe, EC.nom as nom_classe, '
            .'CN.niveau_court AS nom_niveau, CN.id_n AS id_niveau, ECO.numero as id_ecole, ECO.nom as nom_ecole, '
            .'V.id_vi as id_ville, V.nom as nom_ville, GV.id_grv as id_groupevilles, GV.nom_groupe as nom_groupevilles, EA.*, max(EA2.id) AS max_ea_id '
            .'FROM kernel_bu_eleve E '
            .'JOIN kernel_link_bu2user LI ON LI.bu_id=E.idEleve '
            .'LEFT JOIN kernel_bu_eleve_affectation EA ON (EA.eleve=E.idEleve AND EA.annee_scol = '.$grade.') '
            .'LEFT JOIN kernel_bu_classe_niveau CN ON EA.niveau=CN.id_n '
            .'LEFT JOIN kernel_bu_ecole_classe EC ON EC.id=EA.classe '
            .'LEFT JOIN kernel_bu_ecole ECO ON ECO.numero=EC.ecole '
            .'LEFT JOIN kernel_bu_ville V ON V.id_vi=ECO.id_ville '
            .'LEFT JOIN kernel_bu_groupe_villes GV ON GV.id_grv=V.id_grville '
            .'LEFT JOIN kernel_bu_eleve_affectation EA2 ON (EA2.eleve=E.idEleve AND EA2.annee_scol = '.$grade.') '
            .'WHERE LI.bu_type="USER_ELE" ';
    
        if (null !== $lastname) {
          $sql .= ' AND E.nom LIKE \''.$lastname.'%\'';
        }
        if (null !== $firstname) {
          $sql .= ' AND E.prenom1 LIKE \''.$firstname.'%\'';
        }
    
        $sql .= ' GROUP BY E.idEleve, EA.id HAVING EA.id = max_ea_id';
        $sql .= ' ORDER BY CN.id_n, EC.nom, E.nom, E.prenom1';
    
        return _doQuery($sql);
    }
    
    /**
     * Retourne les élèves à assigner (manageAssignments)
     *
     * @param int $grade Année scolaire
     * @param int $destinationGrade Année scolaire de destination
     * @param string $firstname Prénom
     * @param string $lastname Nom
     *
     * @return array
     */
    public function findForManageAssignmentsByName ($grade, $destinationGrade, $firstname = null, $lastname = null)
    {
        $sql = 'SELECT E.idEleve as id, E.nom as nom, E.prenom1 as prenom, LI.bu_type as user_type, LI.bu_id as user_id, EC.id as id_classe, EC.nom as nom_classe,'
            .'CN.niveau_court AS nom_niveau, CN.id_n AS id_niveau, ECO.numero as id_ecole, ECO.nom as nom_ecole, '
            .'V.id_vi as id_ville, V.nom as nom_ville, GV.id_grv as id_groupevilles, GV.nom_groupe as nom_groupevilles, EA.*, max(EA2.id) AS max_ea_id '
            .'FROM kernel_link_bu2user LI '
            .'LEFT JOIN kernel_bu_eleve E ON LI.bu_id=E.idEleve '
            .'LEFT JOIN kernel_bu_eleve_affectation EA ON (EA.eleve=E.idEleve AND EA.annee_scol = '.$grade.') '
            .'LEFT JOIN kernel_bu_classe_niveau CN ON EA.niveau=CN.id_n '
            .'LEFT JOIN kernel_bu_ecole_classe EC ON EC.id=EA.classe '
            .'LEFT JOIN kernel_bu_ecole ECO ON ECO.numero=EC.ecole '
            .'LEFT JOIN kernel_bu_ville V ON V.id_vi=ECO.id_ville '
            .'LEFT JOIN kernel_bu_groupe_villes GV ON GV.id_grv=V.id_grville '
            .'LEFT JOIN kernel_bu_eleve_affectation EA2 ON (EA2.eleve=E.idEleve AND EA2.annee_scol = '.$grade.') '
            .'LEFT JOIN kernel_bu_eleve_affectation EA_dest ON (EA_dest.eleve=E.idEleve AND EA_dest.current = 1 AND EA_dest.annee_scol='.$destinationGrade.') '
            .'WHERE  LI.bu_type="USER_ELE" '
            .'AND EA_dest.eleve IS NULL';
      
        if (null !== $lastname) {
            $sql .= ' AND E.nom LIKE \''.$lastname.'%\'';
        }
        if (null !== $firstname) {
            $sql .= ' AND E.prenom1 LIKE \''.$firstname.'%\'';
        }
      
        $sql .= ' GROUP BY E.idEleve, EA.id HAVING EA.id = max_ea_id'
            . ' ORDER BY CN.id_n, EC.nom, E.nom, E.prenom1';

        return _doQuery($sql);
    }
}
