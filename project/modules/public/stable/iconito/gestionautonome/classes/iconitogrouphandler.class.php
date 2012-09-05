<?php

/**
 * Gestion personnalisée des droits Iconito
 */
class IconitoGroupHandler implements ICopixGroupHandler
{
  /**
     * Récupération des groupes pour un identifiant d'utilisateur donné
     *
     * @param	string	$pUserId	l'identifiant de l'utilisateur, null si on test pour un utilisateur non connecté
     *
     * @return array of groups
     */
    public function getUserGroups ($pUserId, $pUserHandler)
    {
        if (is_null ($pUserId)) {

          return array();
        }

    $groups = array();

    $linkBu2UserDAO = _ioDAO ('kernel|kernel_bu2user2');
    $personnelEntiteDAO = _ioDAO ('kernel|kernel_bu_personnel_entite');

        /*
         * Récupération des liens de l'utilisateur
         * On groupe les liens par type d'affectation
         */
        $links = $linkBu2UserDAO->findByUserId ($pUserId);
        $grouped_links = array();
        foreach ($links as $link) {
          // stdClass Object ( [user_id] => 12 [bu_type] => USER_ENS [bu_id] => 1 )



            // Recherche des droits de directeur (gestion des comptes) pour les animateurs

            // kernel_animateurs : user_type 	user_id 	can_connect 	can_tableaubord 	can_comptes 	is_visibleannuaire
            // kernel_animateurs2regroupements : user_type 	user_id 	regroupement_type 	regroupement_id
            // module_regroupements_grecoles2ecoles : id_groupe 	id_ecole
            // module_regroupements_grvilles2villes : id_groupe 	id_ville
            // kernel_bu_ecole : numero 	RNE 	code_ecole_vaccination 	type 	nom 	num_rue 	num_seq 	adresse1 	adresse2 	code_postal 	commune 	tel 	web 	mail 	num_intranet 	numordre 	num_plan_interactif 	id_ville

            // Groupe d'ecoles
            $sql = "
                SELECT MRE.id_ecole AS ecole
                FROM kernel_animateurs KA
                JOIN kernel_animateurs2regroupements KAR
                  ON KA.user_type=KAR.user_type AND KA.user_id=KAR.user_id
                JOIN module_regroupements_grecoles2ecoles MRE
                  ON KAR.regroupement_type='ecoles' AND KAR.regroupement_id=MRE.id_groupe
                WHERE KA.user_type   = :user_type
                  AND KA.user_id     = :user_id
                  AND KA.can_comptes = 1
            ";
            $anim_infos = _doQuery ($sql, array(
                ':user_type' => $link->bu_type,
                ':user_id'   => $link->bu_id
            ) );

            foreach( $anim_infos AS $anim_ecoles ) {
                $groups['schools_group_animator_'.$anim_ecoles->ecole] = 'Directeur';
            }

            // Groupe de villes
            $sql = "
                SELECT KBE.numero AS ecole
                FROM kernel_animateurs KA
                JOIN kernel_animateurs2regroupements KAR
                  ON KA.user_type=KAR.user_type AND KA.user_id=KAR.user_id
                JOIN module_regroupements_grvilles2villes MRV
                  ON KAR.regroupement_type='villes' AND KAR.regroupement_id=MRV.id_groupe
                JOIN kernel_bu_ecole KBE
                  ON MRV.id_ville=KBE.id_ville
                WHERE KA.user_type   = :user_type
                  AND KA.user_id     = :user_id
                  AND KA.can_comptes = 1
            ";
            $anim_infos = _doQuery ($sql, array(
                ':user_type' => $link->bu_type,
                ':user_id'   => $link->bu_id
            ) );

            foreach( $anim_infos AS $anim_ecoles ) {
                $groups['cities_group_animator_'.$anim_ecoles->ecole] = 'Directeur';
            }


            // echo "<pre>"; print_r($groups); die("</pre>");


          if (isset($grouped_links[$link->bu_type])) {

            $grouped_links[$link->bu_type][] = $link->bu_id;
          } else {

            $grouped_links[$link->bu_type] = array($link->bu_id);
          }
        }

        // Pour chaque lien, on récupère les affectations de la personne
        foreach ($grouped_links as $key => $links) {

          switch ($key) {

            case 'USER_EXT':
                // A voir
                break;
            case 'USER_ENS':
            case 'USER_VIL':
            case 'USER_ADM':
              $entities = $personnelEntiteDAO->findReferenceAndRoleByIds ($links);
              foreach ($entities as $entity) {

                switch ($entity->role) {

                  case 1:
                    if ($entity->type_ref == "CLASSE") {

                      $groups['teacher_'.$entity->reference] = 'Enseignant';
                    } else {
                      $groups['teacher_school_'.$entity->reference] = 'Enseignant';
                    }

                    break;
                  case 2:
                    $groups['principal_'.$entity->reference] = 'Directeur';
                    break;
                  case 3:
                    $groups['administration_staff_'.$entity->reference] = 'Personnel administratif';
                    break;
                  case 4:
                    $groups['city_agent_'.$entity->reference] = 'Agent de ville';
                    break;
                  case 5:
                $groups['cities_group_agent_'.$entity->reference] = 'Agent de groupe de villes';
                    break;
                }
              }
          }
        }

        return $groups;
    }

    /**
     * Récupère les informations sur un groupe donné
     */
    public function getInformations ($pGroupId)
    {
      return 'Aucune information complémentaire';
    }
}