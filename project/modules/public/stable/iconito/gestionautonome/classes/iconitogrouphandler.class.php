<?php

/**
 * Gestion personnalisée des droits Iconito
 */
class IconitoGroupHandler implements ICopixGroupHandler {
  
  /**
	 * Récupération des groupes pour un identifiant d'utilisateur donné
	 *
	 * @param	string	$pUserId	l'identifiant de l'utilisateur, null si on test pour un utilisateur non connecté
	 *
	 * @return array of groups
	 */
	public function getUserGroups ($pUserId, $pUserHandler) {
		
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
		  
		  if (isset($grouped_links[$link->bu_type])) {
		    
		    $grouped_links[$link->bu_type][] = $link->bu_id;
		  }
		  else {
		   
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
		            $groups['teacher_'.$entity->reference] = 'Enseignant';
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
	public function getInformations ($pGroupId) {

	  return 'Aucune information complémentaire';
	}
}