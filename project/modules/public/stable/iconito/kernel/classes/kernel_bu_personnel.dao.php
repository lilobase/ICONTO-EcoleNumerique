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

        $sql .= ' AND P.nom LIKE \'' . addslashes($filters['lastname']) . '%\'';
      }
      if (isset ($filters['firstname'])) {

        $sql .= ' AND P.prenom1 LIKE \'' . addslashes($filters['firstname']) . '%\'';
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
     * Retourne les enseignants affectés ou non pour une année donnée (recherche par nom)
     *
     * @param int $grade Année scolaire
     * @param string $firstname Prénom
     * @param string $lastname Nom
     *
     * @return array
     */
    public function findTeachersByName($grade, $firstname = null, $lastname = null)
    {
        $DAOKernelBuPersonnelEntite = _ioDAO('kernel|kernel_bu_personnel_entite');
        
        $parameters = array('grade' => $grade);
        $sql = 'SELECT P.numero as user_id, LI.bu_type as user_type, P.nom, P.prenom1 AS prenom, P.id_sexe, EC.id as id_classe, EC.nom as nom_classe, E.nom as nom_ecole, V.nom as nom_ville, CN.id_n as id_niveau, CN.niveau as nom_niveau'
            .' FROM kernel_bu_personnel P'
            .' JOIN kernel_link_bu2user LI ON (P.numero=LI.bu_id AND LI.bu_type="USER_ENS")'
            .' LEFT JOIN kernel_bu_personnel_entite PE_TYPE_CLASSE ON (P.numero=PE_TYPE_CLASSE.id_per AND PE_TYPE_CLASSE.type_ref="CLASSE" AND (PE_TYPE_CLASSE.role='.DAOKernel_bu_personnel_entite::ROLE_TEACHER.' OR PE_TYPE_CLASSE.role='.DAOKernel_bu_personnel_entite::ROLE_PRINCIPAL.'))'
            .' LEFT JOIN kernel_bu_ecole_classe EC ON (EC.id=PE_TYPE_CLASSE.reference AND EC.annee_scol=:grade)'
            .' LEFT JOIN kernel_bu_ecole E ON (EC.ecole=E.numero)'
            .' LEFT JOIN kernel_bu_ecole_classe_niveau ECN ON (ECN.classe=EC.id)'
            .' LEFT JOIN kernel_bu_classe_niveau CN ON (CN.id_n=ECN.niveau)'
            .' LEFT JOIN kernel_bu_ville V ON (E.id_ville=V.id_vi)';

        $whereClause = array(); 
        if (null !== $firstname) {
            $whereClause[] = 'P.prenom1 LIKE :firstname';
            $parameters['firstname'] = $firstname.'%';
        }
        if (null !== $lastname) {
            $whereClause[] = 'P.nom LIKE :lastname';
            $parameters['lastname'] = $lastname.'%';
        }
        if (!empty($whereClause)) {
            $sql .= ' WHERE '.implode(' AND ', $whereClause);
        }
        
        $sql .= ' GROUP BY P.numero';
        
        return _doQuery($sql, $parameters);
    }
  
    /**
     * Retourne les enseignants affectés dans une école pour une année scolaire (recherche par structure)
     *
     * @param int $grade Année scolaire
     * @param int $school Ecole
     * @param int $classroom Classe
     * @param int $level Niveau
     * @param string $firstname Prénom
     * @param string $lastname Nom
     *
     * @return CopixDAORecordIterator
     */
    public function findTeachersAssignedToSchoolByStructure($grade, $school, $classroom = null, $level = null, $firstname = null, $lastname = null)
    {   
        $DAOKernelBuPersonnelEntite = _ioDAO('kernel|kernel_bu_personnel_entite');
        
        $parameters = array('school' => $school);
        $assignedTeachersIds = $this->findTeachersIdsAssigned($grade, $school, $classroom, $level, $firstname, $lastname);
        
        // Récupération des enseignants assignés à l'école mais sans affectation aux classes
        $sql = 'SELECT P.numero as user_id, LI.bu_type as user_type, P.nom, P.prenom1 AS prenom, P.id_sexe, "" as id_classe, "" as nom_classe, E.nom as nom_ecole, V.nom as nom_ville, "" as id_niveau, "" as nom_niveau'
            .' FROM kernel_bu_personnel P'
            .' JOIN kernel_link_bu2user LI ON (P.numero=LI.bu_id AND LI.bu_type="USER_ENS")'
            .' JOIN kernel_bu_personnel_entite PE_TYPE_ECOLE ON (P.numero=PE_TYPE_ECOLE.id_per AND PE_TYPE_ECOLE.type_ref="ECOLE")'
            .' JOIN kernel_bu_ecole E ON (PE_TYPE_ECOLE.reference=E.numero)'
            .' JOIN kernel_bu_ville V ON (E.id_ville=V.id_vi)';
        if (!empty($assignedTeachersIds)) {
            $sql .= ' WHERE id_per NOT IN ('.implode(',', $assignedTeachersIds).') AND';
        } else {
            $sql .= ' WHERE';
        }
        $sql .= ' PE_TYPE_ECOLE.reference=:school'
            .' AND (PE_TYPE_ECOLE.role='.DAOKernel_bu_personnel_entite::ROLE_TEACHER.' OR PE_TYPE_ECOLE.role='.DAOKernel_bu_personnel_entite::ROLE_PRINCIPAL.')';
        if (null !== $firstname) {
            $sql .= ' AND P.prenom1 LIKE :firstname';
            $parameters['firstname'] = addslashes($firstname).'%';
        }
        if (null !== $lastname) {
            $sql .= ' AND P.nom LIKE :lastname';
            $parameters['lastname'] = addslashes($lastname).'%';
        }
                
        // Récupération des enseignants assignés à au moins une classe de l'école
        if (!empty($assignedTeachersIds)) {
            $parameters['grade'] = $grade;
            $sql = 'SELECT * FROM ('.$sql.' UNION '
                .' SELECT P.numero as user_id, LI.bu_type as user_type, P.nom, P.prenom1 AS prenom, P.id_sexe, EC.id as id_classe, EC.nom as nom_classe, E.nom as nom_ecole, V.nom as nom_ville, CN.id_n as id_niveau, CN.niveau as nom_niveau'
                .' FROM kernel_bu_personnel P'
                .' JOIN kernel_link_bu2user LI ON (P.numero=LI.bu_id AND LI.bu_type="USER_ENS")'
                .' JOIN kernel_bu_personnel_entite PE_TYPE_CLASSE ON (P.numero=PE_TYPE_CLASSE.id_per AND PE_TYPE_CLASSE.type_ref="CLASSE")'
                .' JOIN kernel_bu_ecole_classe EC ON (EC.id=PE_TYPE_CLASSE.reference)'
                .' JOIN kernel_bu_ecole E ON (EC.ecole=E.numero)'
                .' JOIN kernel_bu_ville V ON (E.id_ville=V.id_vi)'
                .' JOIN kernel_bu_ecole_classe_niveau ECN ON (ECN.classe=EC.id)'
                .' JOIN kernel_bu_classe_niveau CN ON (CN.id_n=ECN.niveau)'
                .' WHERE id_per IN ('.implode(',', $assignedTeachersIds).')'
                .' AND EC.ecole=:school'
                .' AND PE_TYPE_CLASSE.role='.DAOKernel_bu_personnel_entite::ROLE_TEACHER
                .' AND EC.annee_scol=:grade';
            if (null !== $classroom) {
                $sql .= ' AND EC.id=:classroom';
                $parameters['classroom'] = $classroom;
            }
            if (null !== $level) {
                $sql .= ' AND CN.id_n=:level';
                $parameters['level'] = $level;
            }
            $sql .= ') as teachers';
        }
        
        $sql .= ' ORDER BY nom, prenom';
        
        return _doQuery($sql, $parameters);
    }
    
    /**
     * Retourne les enseignants affectés à une classe dans une école pour une année scolaire (recherche par structure)
     *
     * @param int $grade Année scolaire
     * @param int $school Ecole
     * @param int $classroom Classe
     * @param int $level Niveau
     * @param string $firstname Prénom
     * @param string $lastname Nom
     *
     * @return array
     */
    public function findTeachersAssignedToClassroomByStructure($grade, $school, $classroom = null, $level = null, $firstname = null, $lastname = null)
    {
        $DAOKernelBuPersonnelEntite = _ioDAO('kernel|kernel_bu_personnel_entite');
        
        $assignedTeachersIds = $this->findTeachersIdsAssigned($grade, $school, $classroom, $level, $firstname, $lastname);
        if (empty($assignedTeachersIds)) {
            return array();
        }
        
        $parameters = array('school' => $school, 'grade' => $grade);
        $sql = 'SELECT P.numero as user_id, LI.bu_type as user_type, P.nom, P.prenom1 AS prenom, P.id_sexe, EC.id as id_classe, EC.nom as nom_classe, E.nom as nom_ecole, V.nom as nom_ville, CN.id_n as id_niveau, CN.niveau as nom_niveau'
            .' FROM kernel_bu_personnel P'
            .' JOIN kernel_link_bu2user LI ON (P.numero=LI.bu_id AND LI.bu_type="USER_ENS")'
            .' JOIN kernel_bu_personnel_entite PE_TYPE_CLASSE ON (P.numero=PE_TYPE_CLASSE.id_per AND PE_TYPE_CLASSE.type_ref="CLASSE")'
            .' JOIN kernel_bu_ecole_classe EC ON (EC.id=PE_TYPE_CLASSE.reference)'
            .' JOIN kernel_bu_ecole E ON (EC.ecole=E.numero)'
            .' JOIN kernel_bu_ville V ON (E.id_ville=V.id_vi)'
            .' JOIN kernel_bu_ecole_classe_niveau ECN ON (ECN.classe=EC.id)'
            .' JOIN kernel_bu_classe_niveau CN ON (CN.id_n=ECN.niveau)';
        
        $sql .= ' WHERE id_per IN ('.implode(',', $assignedTeachersIds).')'
            .' AND EC.ecole=:school'
            .' AND PE_TYPE_CLASSE.role='.DAOKernel_bu_personnel_entite::ROLE_TEACHER
            .' AND EC.annee_scol=:grade';
            
        if (null !== $classroom) {
            $sql .= ' AND EC.id=:classroom';
            $parameters['classroom'] = $classroom;
        }
        if (null !== $level) {
            $sql .= ' AND CN.id_n=:level';
            $parameters['level'] = $level;
        }
        
        $sql .= ' ORDER BY P.nom, P.prenom1';
        
        return _doQuery($sql, $parameters);
    }
    
    /**
     * Retourne les identifiants des enseignants affectés à une classe d'une école
     *
     * @param int $grade Année scolaire
     * @param int $school Ecole
     * @param int $classroom Classe
     * @param int $level Niveau
     * @param string $firstname Prénom
     * @param string $lastname Nom
     *
     * @return array
     */
    protected function findTeachersIdsAssigned($grade, $school, $classroom = null, $level = null, $firstname = null, $lastname = null)
    {
        $DAOKernelBuPersonnelEntite = _ioDAO('kernel|kernel_bu_personnel_entite');
        
        $parameters = array(':school' => $school, ':grade' => $grade);    
        $sql = 'SELECT DISTINCT id_per'
            .' FROM kernel_bu_personnel P'
            .' JOIN kernel_bu_personnel_entite PE_TYPE_CLASSE ON (P.numero=PE_TYPE_CLASSE.id_per AND PE_TYPE_CLASSE.type_ref="CLASSE")'
            .' JOIN kernel_bu_ecole_classe EC ON (EC.id=PE_TYPE_CLASSE.reference)';
        
        if (null !== $level) {
            $sql .= ' JOIN kernel_bu_ecole_classe_niveau ECN ON (ECN.classe=EC.id)'
                .' JOIN kernel_bu_classe_niveau CN ON (CN.id_n=ECN.niveau)';
        }
        
        $sql .=' WHERE EC.ecole=:school'
            .' AND PE_TYPE_CLASSE.role='.DAOKernel_bu_personnel_entite::ROLE_TEACHER
            .' AND EC.annee_scol=:grade';
        
        if (null !== $classroom) {
            $sql .= ' AND EC.id=:classroom';
            $parameters['classroom'] = $classroom;
        }
        if (null !== $level) {
            $sql .= ' AND CN.id_n=:level';
            $parameters['level'] = $level;
        }
        if (null !== $firstname) {
            $sql .= ' AND P.prenom1 LIKE :firstname';
            $parameters['firstname'] = $firstname.'%';
        }
        if (null !== $lastname) {
            $sql .= ' AND P.nom LIKE :lastname';
            $parameters['lastname'] = $lastname.'%';
        }
        
        $ids = array();     
        $results = _doQuery($sql, $parameters);
        foreach ($results as $result) {
            $ids[] = $result->id_per;
        }

        return $ids;
    }
}
