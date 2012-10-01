<?php

/**
 * Surcharge de la DAO Kernel_bu_personnel
 *
 * @package Iconito
 * @subpackage Kernel
 */
class DAOKernel_bu_personnel
{
    /**
     * Renvoie la liste du personnel école rattaché à une classe et ayant un compte utilisateur
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2006/01/20
     * @param integer $classe Id de la classe
     * @return mixed Objet DAO
     */
    public function getPersonnelInClasse ($classe)
    {
        $query = "SELECT P.numero AS id, P.nom, P.prenom1 as prenom, P.mel AS email, S.sexe, U.id_dbuser AS id_copix, U.login_dbuser AS login, LI.bu_type, LI.bu_id, PE.role, PR.nom_role, PR.nom_role_pluriel FROM kernel_bu_personnel P, kernel_bu_personnel_entite PE, kernel_bu_personnel_role PR, kernel_bu_sexe S, kernel_link_bu2user LI, dbuser U WHERE P.numero=PE.id_per AND PE.role=PR.id_role AND P.id_sexe=S.id_s AND LI.user_id=U.id_dbuser AND LI.bu_type='USER_ENS' AND LI.bu_id=P.numero AND PE.reference=".$classe." AND PE.type_ref='CLASSE' ORDER BY PR.priorite, P.nom, P.prenom1";
        return _doQuery($query);
    }

    /**
     * Renvoie la liste du personnel école rattaché à une école et ayant un compte utilisateur
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2006/01/20
     * @param integer $ecole Id de l'école
     * @return mixed Objet DAO
     */
    public function getPersonnelInEcole ($ecole)
    {
        $query = "SELECT P.numero AS id, P.nom, P.prenom1 as prenom, P.mel AS email, S.sexe, U.id_dbuser AS id_copix, U.login_dbuser AS login, LI.bu_type, LI.bu_id, PE.role, PR.nom_role, PR.nom_role_pluriel FROM kernel_bu_personnel P, kernel_bu_personnel_entite PE, kernel_bu_personnel_role PR, kernel_bu_sexe S, kernel_link_bu2user LI, dbuser U WHERE P.numero=PE.id_per AND PE.role=PR.id_role AND P.id_sexe=S.id_s AND LI.user_id=U.id_dbuser AND LI.bu_type='USER_ENS' AND LI.bu_id=P.numero AND PE.reference=".$ecole." AND PE.type_ref='ECOLE' ORDER BY PR.priorite, P.nom, P.prenom1";
        //print_r($query);
        return _doQuery($query);
    }

    /**
     * Renvoie la liste du personnel école rattaché aux écoles d'une ville et ayant un compte utilisateur
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2006/01/20
     * @param integer $ville Id de la ville
     * @return mixed Objet DAO
     */
    public function getPersonnelInVille ($ville)
    {
        $query = "SELECT P.numero AS id, P.nom, P.prenom1 as prenom, P.mel AS email, S.sexe, U.id_dbuser AS id_copix, U.login_dbuser AS login, LI.bu_type, LI.bu_id, PE.role, PR.nom_role, PR.nom_role_pluriel FROM kernel_bu_personnel P, kernel_bu_personnel_entite PE, kernel_bu_personnel_role PR, kernel_bu_sexe S, kernel_bu_ecole ECO, kernel_link_bu2user LI, dbuser U WHERE P.numero=PE.id_per AND PE.role=PR.id_role AND P.id_sexe=S.id_s AND LI.user_id=U.id_dbuser AND LI.bu_type='USER_ENS' AND LI.bu_id=P.numero AND PE.reference=ECO.numero AND PE.type_ref='ECOLE' AND ECO.id_ville=".$ville." ORDER BY PR.priorite, P.nom, P.prenom1";
        //print_r($query);
        return _doQuery($query);
    }


    /**
     * Renvoie la liste du personnel école rattaché aux écoles des villes d'un groupe de villes et ayant un compte utilisateur
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2006/01/20
     * @param integer $grville Id du groupe de villes
     * @return mixed Objet DAO
     */
    public function getPersonnelInGrville ($grville)
    {
        $sqlPlus = '';
        if ( Kernel::getKernelLimits('ville') )
            $sqlPlus .= ' AND VIL.id_vi IN ('.Kernel::getKernelLimits('ville').')';
        $query = "SELECT P.numero AS id, P.nom, P.prenom1 as prenom, P.mel AS email, S.sexe, U.id_dbuser AS id_copix, U.login_dbuser AS login, LI.bu_type, LI.bu_id, PE.role, PR.nom_role, PR.nom_role_pluriel FROM kernel_bu_personnel P, kernel_bu_personnel_entite PE, kernel_bu_personnel_role PR, kernel_bu_sexe S, kernel_bu_ecole ECO, kernel_bu_ville VIL, kernel_link_bu2user LI, dbuser U WHERE P.numero=PE.id_per AND PE.role=PR.id_role AND P.id_sexe=S.id_s AND LI.user_id=U.id_dbuser AND LI.bu_type='USER_ENS' AND LI.bu_id=P.numero AND PE.reference=ECO.numero AND PE.type_ref='ECOLE' AND ECO.id_ville=VIL.id_vi AND VIL.id_grville=".$grville.$sqlPlus." ORDER BY PR.priorite, P.nom, P.prenom1";
        //print_r($query);
        return _doQuery($query);
    }


    /**
     * Renvoie la liste du personnel administratif rattaché à une école et ayant un compte utilisateur
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2006/01/19
     * @param integer $ecole Id de l'école
     * @return mixed Objet DAO
     */
    public function getPersonnelAdmInEcole ($ecole)
    {
        $query = "SELECT P.numero AS id, P.nom, P.prenom1 as prenom, P.mel AS email, S.sexe, U.id_dbuser AS id_copix, U.login_dbuser AS login, LI.bu_type, LI.bu_id, PE.role, PR.nom_role, PR.nom_role_pluriel FROM kernel_bu_personnel P, kernel_bu_personnel_entite PE, kernel_bu_personnel_role PR, kernel_bu_sexe S, kernel_link_bu2user LI, dbuser U WHERE P.numero=PE.id_per AND PE.role=PR.id_role AND P.id_sexe=S.id_s AND LI.user_id=U.id_dbuser AND LI.bu_type='USER_ADM' AND LI.bu_id=P.numero AND PE.reference=".$ecole." AND PE.type_ref='ECOLE' ORDER BY PR.priorite, P.nom, P.prenom1";
        //print_r($query);
        return _doQuery($query);
    }

    /**
     * Renvoie la liste du personnel administratif rattaché aux écoles d'une ville et ayant un compte utilisateur
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2006/01/19
     * @param integer $ville Id de la ville
     * @return mixed Objet DAO
     */
    public function getPersonnelAdmInVille ($ville)
    {
        $query = "SELECT P.numero AS id, P.nom, P.prenom1 as prenom, P.mel AS email, S.sexe, U.id_dbuser AS id_copix, U.login_dbuser AS login, LI.bu_type, LI.bu_id, PE.role, PR.nom_role, PR.nom_role_pluriel FROM kernel_bu_personnel P, kernel_bu_personnel_entite PE, kernel_bu_personnel_role PR, kernel_bu_sexe S, kernel_bu_ecole ECO, kernel_link_bu2user LI, dbuser U WHERE P.numero=PE.id_per AND PE.role=PR.id_role AND P.id_sexe=S.id_s AND LI.user_id=U.id_dbuser AND LI.bu_type='USER_ADM' AND LI.bu_id=P.numero AND PE.reference=ECO.numero AND PE.type_ref='ECOLE' AND ECO.id_ville=".$ville." ORDER BY PR.priorite, P.nom, P.prenom1";
        //print_r($query);
        return _doQuery($query);
    }


    /**
     * Renvoie la liste du personnel administratif rattaché aux écoles des villes d'un groupe de villes et ayant un compte utilisateur
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2006/01/19
     * @param integer $grville Id du groupe de villes
     * @return mixed Objet DAO
     */
    public function getPersonnelAdmInGrville ($grville)
    {
        $sqlPlus = '';
        if ( Kernel::getKernelLimits('ville') )
            $sqlPlus .= ' AND VIL.id_vi IN ('.Kernel::getKernelLimits('ville').')';
        $query = "SELECT P.numero AS id, P.nom, P.prenom1 as prenom, P.mel AS email, S.sexe, U.id_dbuser AS id_copix, U.login_dbuser AS login, LI.bu_type, LI.bu_id, PE.role, PR.nom_role, PR.nom_role_pluriel FROM kernel_bu_personnel P, kernel_bu_personnel_entite PE, kernel_bu_personnel_role PR, kernel_bu_sexe S, kernel_bu_ecole ECO, kernel_bu_ville VIL, kernel_link_bu2user LI, dbuser U WHERE P.numero=PE.id_per AND PE.role=PR.id_role AND P.id_sexe=S.id_s AND LI.user_id=U.id_dbuser AND LI.bu_type='USER_ADM' AND LI.bu_id=P.numero AND PE.reference=ECO.numero AND PE.type_ref='ECOLE' AND ECO.id_ville=VIL.id_vi AND VIL.id_grville=".$grville.$sqlPlus." ORDER BY PR.priorite, P.nom, P.prenom1";
        //print_r($query);
        return _doQuery($query);
    }


    /**
     * Renvoie la liste des agents de villes rattachés a une ville et ayant un compte utilisateur
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2007/11/06
     * @param integer $ville Id de la ville
     * @return mixed Objet DAO
     */
    public function getPersonnelVilInVille ($ville)
    {
        $query = "SELECT P.numero AS id, P.nom, P.prenom1 as prenom, P.mel AS email, S.sexe, U.id_dbuser AS id_copix, U.login_dbuser AS login, LI.bu_type, LI.bu_id, PE.role, PR.nom_role, PR.nom_role_pluriel FROM kernel_bu_personnel P, kernel_bu_personnel_entite PE, kernel_bu_personnel_role PR, kernel_bu_sexe S, kernel_bu_ville VIL, kernel_link_bu2user LI, dbuser U WHERE P.numero=PE.id_per AND PE.role=PR.id_role AND P.id_sexe=S.id_s AND LI.user_id=U.id_dbuser AND LI.bu_type='USER_VIL' AND LI.bu_id=P.numero AND PE.reference=VIL.id_vi AND PE.type_ref='VILLE' AND VIL.id_vi=".$ville." ORDER BY PR.priorite, P.nom, P.prenom1";
        //print_r($query);
        return _doQuery($query);
    }


    /**
     * Renvoie la liste des agents de ville rattachés aux villes d'un groupe de villes et ayant un compte utilisateur
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2007/11/06
     * @param integer $grville Id du groupe de villes
     * @return mixed Objet DAO
     */
    public function getPersonnelVilInGrville ($grville)
    {
        $sqlPlus = '';
        if ( Kernel::getKernelLimits('ville') )
            $sqlPlus .= ' AND VIL.id_vi IN ('.Kernel::getKernelLimits('ville').')';
        $query = "SELECT P.numero AS id, P.nom, P.prenom1 as prenom, P.mel AS email, S.sexe, U.id_dbuser AS id_copix, U.login_dbuser AS login, LI.bu_type, LI.bu_id, PE.role, PR.nom_role, PR.nom_role_pluriel FROM kernel_bu_personnel P, kernel_bu_personnel_entite PE, kernel_bu_personnel_role PR, kernel_bu_sexe S, kernel_bu_ville VIL, kernel_link_bu2user LI, dbuser U WHERE P.numero=PE.id_per AND PE.role=PR.id_role AND P.id_sexe=S.id_s AND LI.user_id=U.id_dbuser AND LI.bu_type='USER_VIL' AND LI.bu_id=P.numero AND PE.reference=VIL.id_vi AND PE.type_ref='VILLE' AND VIL.id_grville=".$grville.$sqlPlus." ORDER BY PR.priorite, P.nom, P.prenom1";
        //print_r($query);
        return _doQuery($query);
    }

    /**
     * Renvoie la liste des personnes pouvant être assignés à un noeud
     *
     * @param integer $reference Id du noeud reference
     * @param string  $typeRef   Type du noeud reference
     * @param array   $filters   Filtres
     */
    public function findPersonnelsForAssignment ($reference, $typeRef, $filters = array ())
    {
    // Recherche sur le personnel ayant au moins une affectation
      if (isset ($filters['withAssignment'])) {

        $sql = 'SELECT P.numero, P.nom, P.prenom1, P.id_sexe, U.id_dbuser, U.login_dbuser, LI.bu_type, LI.bu_id, PE.role, PR.nom_role, PE.reference, PE.type_ref
          FROM kernel_bu_personnel P
          JOIN kernel_bu_personnel_entite PE ON (P.numero=PE.id_per)
          JOIN kernel_bu_personnel_role PR ON (PE.role=PR.id_role)
          JOIN kernel_link_bu2user LI ON (P.numero=LI.bu_id)
          JOIN dbuser U ON (LI.user_id=U.id_dbuser)';

      if (isset ($filters['groupcity'])) {

        if (isset ($filters['city'])) {

          if (isset ($filters['school'])) {

            if (isset ($filters['class'])) {

                $sql .= ' WHERE PE.reference='.$filters['class'];
                $sql .= ' AND PE.type_ref="CLASSE"';
              } elseif ($typeRef == "ECOLE" || $typeRef == "CLASSE") {

              $sql .= ' LEFT JOIN kernel_bu_ecole_classe C ON (PE.reference=C.id AND C.ecole='.$filters['school'].')';

              if ($filters['user_type'] == "USER_ADM") {

                $sql .= ' WHERE (PE.reference='.$filters['school'];
                $sql .= ' AND C.id IS NULL)';
                $sql .= ' AND PE.type_ref="ECOLE"';
              } elseif ($filters['user_type'] == "USER_ENS") {

                $sql .= ' WHERE ((PE.reference='.$filters['school'].' AND PE.type_ref="ECOLE")';
                $sql .= ' OR (C.id IS NOT NULL AND PE.type_ref="CLASSE"))';
              }
            }
          } elseif ($typeRef == "GVILLE") {

            $sql .= ' WHERE ((PE.reference='.$filters['groupcity'].' AND PE.type_ref="GVILLE")';
            $sql .= ' OR (PE.reference='.$filters['city'].' AND PE.type_ref="VILLE"))';
          } elseif ($typeRef == "VILLE") {

            $sql .= ' WHERE (PE.reference='.$filters['city'].' AND PE.type_ref="VILLE")';
          } elseif ($typeRef == "ECOLE" || $typeRef == "CLASSE") {

            $sql .= ' JOIN kernel_bu_ecole E ON (E.id_ville='.$filters['city'].')';
            $sql .= ' LEFT JOIN kernel_bu_ecole_classe C ON (PE.reference=C.id AND E.numero=C.ecole)';
            $sql .= ' WHERE ((PE.reference=E.numero AND PE.type_ref="ECOLE")';
            $sql .= ' OR (C.id IS NOT NULL AND PE.type_ref="CLASSE"))';
          }
        } elseif ($typeRef == "GVILLE" || $typeRef == "VILLE") {

          $sql .= ' JOIN kernel_bu_ville V ON (V.id_grville = '.$filters['groupcity'].')';
          $sql .= ' WHERE ((PE.reference='.$filters['groupcity'].' AND PE.type_ref="GVILLE")';
          $sql .= ' OR (V.id_vi IS NOT NULL AND PE.type_ref="VILLE"))';
        } elseif ($typeRef == "ECOLE" || $typeRef == "CLASSE") {

          $sql .= ' JOIN kernel_bu_ville V ON (V.id_grville='.$filters['groupcity'].')';
          $sql .= ' JOIN kernel_bu_ecole E ON (E.id_ville=V.id_vi)';
          $sql .= ' LEFT JOIN kernel_bu_ecole_classe C ON (C.ecole=E.numero)';
          $sql .= ' WHERE ((PE.reference=E.numero AND PE.type_ref="ECOLE")';
          $sql .= ' OR (C.id IS NOT NULL AND PE.type_ref="CLASSE"))';
        }
      }

      $sql .= ' AND LI.bu_type="'.$filters['user_type'].'"';
      }
      // Personnel sans affectation
      else {

        $sql = 'SELECT P.numero, P.nom, P.prenom1, P.id_sexe, U.id_dbuser, U.login_dbuser, LI.bu_type, LI.bu_id, PE.role, PR.nom_role, PE.reference, PE.type_ref
          FROM kernel_bu_personnel P, kernel_bu_personnel_entite PE, kernel_bu_personnel_role PR, kernel_link_bu2user LI, dbuser U
          WHERE P.numero NOT IN (SELECT kernel_bu_personnel_entite.id_per FROM kernel_bu_personnel_entite)
          AND P.numero=LI.bu_id
          AND LI.user_id=U.id_dbuser
        AND LI.bu_type="'.$filters['user_type'].'"';
      }

      if (isset ($filters['lastname'])) {

        $sql .= ' AND P.nom LIKE \'' . $filters['lastname'] . '%\'';
      }
      if (isset ($filters['firstname'])) {

        $sql .= ' AND P.prenom1 LIKE \'' . $filters['firstname'] . '%\'';
      }

        // Ne pas afficher les personnes déjà affectés au noeud
        $sql .= ' AND (PE.reference != '.$reference;
        $sql .= ' OR PE.type_ref != "'.$typeRef.'"';
        $sql .= ' OR (PE.reference = '.$reference.' AND PE.type_ref != "'.$typeRef.'")';
        $sql .= ' OR (PE.reference != '.$reference.' AND PE.type_ref = "'.$typeRef.'"))';

      $sql .= ' GROUP BY P.numero';
      $sql .= ' ORDER BY PR.priorite, P.nom, P.prenom1';

        return _doQuery($sql);
    }

    /**
     * Retourne une personne suivant son id et son type
     *
     * @param integer $id   Identifiant de la personne
     * @param string  $type Type de compte
     */
    public function findPersonnelWithAccountByIdAndType ($id, $type)
    {
      $sql = 'SELECT P.numero, P.nom, P.prenom1, P.date_nais, P.mel, U.id_dbuser, U.login_dbuser, LI.bu_type, LI.bu_id, PE.role, PR.nom_role
        FROM kernel_bu_personnel P, kernel_bu_personnel_entite PE, kernel_bu_personnel_role PR, kernel_link_bu2user LI, dbuser U
        WHERE P.numero=PE.id_per
        AND PE.role=PR.id_role
        AND LI.user_id=U.id_dbuser
        AND LI.bu_id=P.numero
        AND P.numero='.$id.'
        AND LI.bu_type="'.$type.'"
        ORDER BY PR.priorite, P.nom, P.prenom1';

        $results = _doQuery($sql);

        return isset ($results[0]) ? $results[0] : false;
    }

    /**
     * Retourne les agents de groupe de villes pour un groupe de ville donné
     *
     * @param integer $citiesGroupId   Identifiant du groupe de ville
     */
    public function findCitiesAgentsByCitiesGroupId ($citiesGroupId)
    {
      $sql = 'SELECT P.numero, P.nom, P.prenom1, P.id_sexe, U.id_dbuser, U.login_dbuser, LI.bu_type, PE.role, PR.nom_role
              FROM kernel_bu_personnel P, kernel_bu_personnel_entite PE, kernel_bu_personnel_role PR, kernel_link_bu2user LI, dbuser U
              WHERE P.numero=PE.id_per
              AND PE.role=PR.id_role
              AND LI.user_id=U.id_dbuser
              AND LI.bu_id=P.numero
              AND LI.bu_type="USER_VIL"
              AND PE.type_ref="GVILLE"
              AND PE.reference='.$citiesGroupId.'
              ORDER BY P.nom, P.prenom1';

      return _doQuery($sql);
    }

    /**
     * Retourne les agents de villes pour une ville donnée
     *
     * @param integer $cityId   Identifiant de la ville
     */
    public function findCityAgentsByCityId ($cityId)
    {
      $sql = 'SELECT P.numero, P.nom, P.prenom1, P.id_sexe, U.id_dbuser, U.login_dbuser, LI.bu_type, PE.role, PR.nom_role
              FROM kernel_bu_personnel P, kernel_bu_personnel_entite PE, kernel_bu_personnel_role PR, kernel_link_bu2user LI, dbuser U
              WHERE P.numero=PE.id_per
              AND PE.role=PR.id_role
              AND LI.user_id=U.id_dbuser
              AND LI.bu_id=P.numero
              AND LI.bu_type="USER_VIL"
              AND PE.type_ref="VILLE"
              AND PE.reference='.$cityId.'
              ORDER BY P.nom, P.prenom1';

      return _doQuery($sql);
    }

    /**
     * Retourne le personnel administratif et les directeurs d'une école
     *
     * @param integer $schoolId   Identifiant de l'école
     */
    public function findAdministrationStaffAndPrincipalBySchoolId ($schoolId)
    {
      $sql = 'SELECT P.numero, P.nom, P.prenom1, P.id_sexe, U.id_dbuser, U.login_dbuser, LI.bu_type, PE.role, PR.nom_role
              FROM kernel_bu_personnel P, kernel_bu_personnel_entite PE, kernel_bu_personnel_role PR, kernel_link_bu2user LI, dbuser U
              WHERE P.numero=PE.id_per
              AND PE.role=PR.id_role
              AND LI.user_id=U.id_dbuser
              AND LI.bu_id=P.numero
            AND (LI.bu_type="USER_ADM" OR LI.bu_type="USER_ENS")
              AND PE.type_ref="ECOLE"
              AND PE.reference='.$schoolId.'
              ORDER BY P.nom, P.prenom1';

      return _doQuery($sql);
    }

    /**
     * Retourne les enseignants d'une classe
     *
     * @param integer $classroomId   Identifiant de la classe
     */
    public function findTeachersByClassroomId ($classroomId)
    {
      $sql = 'SELECT P.numero, P.nom, P.prenom1, P.id_sexe, U.id_dbuser, U.login_dbuser, LI.bu_type, PE.role, PR.nom_role
              FROM kernel_bu_personnel P, kernel_bu_personnel_entite PE, kernel_bu_personnel_role PR, kernel_link_bu2user LI, dbuser U
              WHERE P.numero=PE.id_per
              AND PE.role=PR.id_role
              AND LI.user_id=U.id_dbuser
              AND LI.bu_id=P.numero
            AND LI.bu_type="USER_ENS"
              AND PE.type_ref="CLASSE"
              AND PE.reference='.$classroomId.'
              ORDER BY P.nom, P.prenom1';

      return _doQuery($sql);
    }

    /**
     * Retourne une personne via son id et son type
     *
     * @param integer   $id   Identifiant de la personne
     * @param string    $type Type de la personne
     *
     * return CopixDAORecordIterator
     */
    public function getByIdAndType ($id, $type)
    {
      $sql = $this->_selectQuery
      . ' JOIN kernel_link_bu2user LI ON (LI.bu_id=kernel_bu_personnel.numero)'
      . ' WHERE LI.bu_id=:id'
      . ' AND LI.bu_type=:type';

    $results = _doQuery($sql, array (':id' => $id, 'type' => $type));

        return isset ($results[0]) ? $results[0] : false;
    }

    /**
     * Retourne les enseignants pouvant être assignés (manageAssignments)
     *
     * @param array   $filters   Filtres de récupération des enseignants
     *
     * return CopixDAORecordIterator
     */
    public function findTeachersForManageAssignments ($filters = array ())
    {
      $personEntityDAO = _ioDAO ('kernel|kernel_bu_personnel_entite');

      $sql = 'SELECT P.numero as user_id, "USER_ENS" as user_type, P.nom, P.prenom1 AS prenom, P.id_sexe, U.id_dbuser, U.login_dbuser, LI.bu_type, LI.bu_id, EC.id as id_classe, EC.nom as nom_classe, "" AS id_niveau, "" AS nom_niveau, EC.id AS is_affect, PE.*'
        . ' FROM kernel_bu_personnel P'
        . ' LEFT JOIN kernel_bu_personnel_entite PE ON (P.numero=PE.id_per)'
        . ' JOIN kernel_bu_personnel_role PR ON (PE.role=PR.id_role)'
        . ' JOIN kernel_link_bu2user LI ON (P.numero=LI.bu_id AND LI.bu_type = "USER_ENS")'
        . ' JOIN dbuser U ON (LI.user_id=U.id_dbuser)';

        if (isset($filters['originClassroom']) && !is_null ($filters['originClassroom'])) {
          $sql .= ' JOIN kernel_bu_ecole_classe EC ON (EC.id='.$filters['originClassroom'].')';
        } elseif (isset($filters['originSchool']) && !is_null ($filters['originSchool'])) {
          $sql .= ' JOIN kernel_bu_ecole ECO ON (ECO.numero='.$filters['originSchool'].')'
            . ' JOIN kernel_bu_ecole_classe EC ON (EC.ecole = ECO.numero)'
            . ' LEFT JOIN kernel_bu_personnel_entite PE2 ON (P.numero=PE2.id_per AND PE2.type_ref = "CLASSE" AND PE2.reference IN (SELECT id FROM kernel_bu_ecole_classe WHERE ecole = '.$filters['originSchool'].' AND annee_scol='.$filters['originGrade'].'))';
        }

        $sql .= ' JOIN kernel_bu_ecole_classe_niveau ECN ON (ECN.classe=EC.id)'
        . ' JOIN kernel_bu_classe_niveau CN ON (CN.id_n=ECN.niveau)'
        . ' WHERE (PR.id_role='.DAOKernel_bu_personnel_entite::ROLE_TEACHER
        . ' OR PR.id_role='.DAOKernel_bu_personnel_entite::ROLE_PRINCIPAL.')'
        . ' AND EC.annee_scol='.$filters['originGrade'];

      if (isset($filters['originClassroom']) && !is_null ($filters['originClassroom'])) {

        $sql .= ' AND PE.reference = EC.id AND PE.type_ref = "CLASSE"';
      } elseif (isset($filters['originSchool']) && !is_null ($filters['originSchool'])) {

        $sql .= ' AND ((PE.reference = ECO.numero AND PE.type_ref = "ECOLE" AND PE2.reference IS NULL)'
          . ' OR (PE.reference = EC.id AND PE.type_ref = "CLASSE"))';
      }

      if (isset ($filters['originLevel']) && !is_null ($filters['originLevel'])) {

          $sql .= ' AND ECN.niveau='.$filters['originLevel'];
        }
        if (isset ($filters['originLastname']) && !is_null ($filters['originLastname'])) {

          $sql .= ' AND P.nom LIKE \'' . $filters['originLastname'] . '%\'';
        }
        if (isset ($filters['originFirstname']) && !is_null ($filters['originFirstname'])) {

          $sql .= ' AND P.prenom1 LIKE \'' . $filters['originFirstname'] . '%\'';
        }

      $sql .= ' GROUP BY PE.id_per,PE.reference'
        . ' ORDER BY EC.nom, P.nom, P.prenom1';

    return _doQuery($sql);
    }

    /**
     * Retourne les enseignants assignés (manageAssignments)
     *
     * @param array   $groups     Groupes
     * @param array   $filters    Filtres de récupération des enseignants
     *
     * return CopixDAORecordIterator
     */
    public function findAssignedTeachers ($filters = array (), $groups = null)
    {
      if (!is_null ($groups)) {

        $groupsIds = array();

      foreach ($groups as $key => $group) {

        $id = substr($key, strrpos($key, '_')+1);

        if (preg_match('/^teacher/', $key)) {

          $groupsIds[] = $id;
        }
      }
    }

      $personEntityDAO = _ioDAO ('kernel|kernel_bu_personnel_entite');

      $sql = 'SELECT P.numero as user_id, "USER_ENS" as user_type, P.nom, P.prenom1 AS prenom, P.id_sexe, U.id_dbuser, U.login_dbuser, LI.bu_type, LI.bu_id, EC.id as id_classe, EC.nom as nom_classe, "" AS id_niveau, "" AS nom_niveau, EC.id AS is_affect'
        . ' FROM kernel_bu_personnel P'
        . ' LEFT JOIN kernel_bu_personnel_entite PE ON (P.numero=PE.id_per)'
        . ' JOIN kernel_bu_personnel_role PR ON (PE.role=PR.id_role)'
        . ' JOIN kernel_link_bu2user LI ON (P.numero=LI.bu_id)'
        . ' JOIN dbuser U ON (LI.user_id=U.id_dbuser)';

        if (isset($filters['destinationClassroom'])) {

          $sql .= ' JOIN kernel_bu_ecole_classe EC ON (EC.id='.$filters['destinationClassroom'].')';
        } elseif (isset($filters['destinationSchool'])) {

          $sql .= ' JOIN kernel_bu_ecole ECO ON (ECO.numero='.$filters['destinationSchool'].')';
          $sql .= ' JOIN kernel_bu_ecole_classe EC ON (EC.ecole=ECO.numero)';
        }

        $sql .= ' JOIN kernel_bu_ecole_classe_niveau ECN ON (ECN.classe=EC.id)'
        . ' JOIN kernel_bu_classe_niveau CN ON (CN.id_n=ECN.niveau)'
        . ' WHERE PR.id_role='.DAOKernel_bu_personnel_entite::ROLE_TEACHER
        . ' AND PE.type_ref="CLASSE"'
        . ' AND PE.reference=EC.id'
        . ' AND EC.annee_scol='.$filters['destinationGrade'];

      if (isset ($filters['destinationLevel'])) {

          $sql .= ' AND ECN.niveau='.$filters['destinationLevel'];
        }
      if (!is_null ($groups)) {

        $sql .= ' AND EC.id IN ('.implode(',', $groupsIds).')';
      }

      $sql .= ' GROUP BY PE.id_per,PE.reference'
        . ' ORDER BY P.nom, P.prenom1';

    return _doQuery($sql);
    }

}
