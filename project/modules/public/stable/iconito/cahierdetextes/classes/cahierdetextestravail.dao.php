<?php

/**
* @package    Iconito
* @subpackage Cahierdetextes
*/

class DAORecordCahierDeTextesTravail {
  
}

class DAOCahierDeTextesTravail {

  const TYPE_EN_CLASSE = 0;
	const TYPE_A_FAIRE   = 1;
	
	/**
   * Retourne les travaux pour une élève donné
   *
   * @param int $idEleve
   * @param int $typeDeTravail
   *
   * @return array
   */
	public function findByEleveEtTypeDeTravail ($idEleve, $typeDeTravail = self::TYPE_EN_CLASSE, $date) {
	  
	  $sql = 'SELECT module_cahierdetextes_travail.id, module_cahierdetextes_travail.date_creation, module_cahierdetextes_travail.date_realisation, '
	    . ' module_cahierdetextes_travail.description, module_cahierdetextes_domaine.id as domaine_id, module_cahierdetextes_domaine.nom '
	    . ' FROM module_cahierdetextes_travail'
  	  . ' LEFT JOIN module_cahierdetextes_travail2eleve ON (module_cahierdetextes_travail.id = module_cahierdetextes_travail2eleve.module_cahierdetextes_travail_id)'
  	  . ' LEFT JOIN module_cahierdetextes_domaine ON (module_cahierdetextes_domaine.id = module_cahierdetextes_travail.module_cahierdetextes_domaine_id)'
  	  . ' WHERE module_cahierdetextes_travail2eleve.kernel_bu_eleve_idEleve=:idEleve'
  	  . ' AND module_cahierdetextes_travail.supprime = 0'
  	  . ' AND module_cahierdetextes_travail.a_faire = :typeDeTravail';
  	
  	if ($typeDeTravail == self::TYPE_EN_CLASSE) {
  	  
  	  $sql .= ' AND module_cahierdetextes_travail.date_creation=:date';
  	}
  	else {
  	  
  	  $sql .= ' AND module_cahierdetextes_travail.date_realisation=:date';
  	}
  	
  	$sql .= ' GROUP BY module_cahierdetextes_travail.id'
  	  . ' ORDER BY module_cahierdetextes_domaine.nom ASC';
  	  
    return _doQuery ($sql, array(':idEleve' => $idEleve, ':typeDeTravail' => $typeDeTravail, ':date' => date('d/m/Y', $date)));
	}
	
	/**
   * Retourne les travaux pour une classe donnée
   *
   * @param int $idClasse
   * @param int $typeDeTravail
   *
   * @return array
   */
	public function findByClasseEtTypeDeTravail ($idClasse, $typeDeTravail = self::TYPE_EN_CLASSE, $date) {
	  
	  $sql = 'SELECT module_cahierdetextes_travail.id, module_cahierdetextes_travail.date_creation, module_cahierdetextes_travail.date_realisation, '
	    . ' module_cahierdetextes_travail.description, module_cahierdetextes_domaine.id as domaine_id, module_cahierdetextes_domaine.nom '
	    . ' FROM module_cahierdetextes_travail'
  	  . ' LEFT JOIN module_cahierdetextes_domaine ON (module_cahierdetextes_domaine.id = module_cahierdetextes_travail.module_cahierdetextes_domaine_id)'
  	  . ' WHERE module_cahierdetextes_domaine.kernel_bu_ecole_classe_id=:idClasse'
  	  . ' AND module_cahierdetextes_travail.supprime = 0'
  	  . ' AND module_cahierdetextes_travail.a_faire = :typeDeTravail';
  	
  	if ($typeDeTravail == self::TYPE_EN_CLASSE) {
  	  
  	  $sql .= ' AND module_cahierdetextes_travail.date_creation=:date';
  	}
  	else {
  	  
  	  $sql .= ' AND module_cahierdetextes_travail.date_realisation=:date';
  	}
  	
  	$sql .= ' GROUP BY module_cahierdetextes_travail.id'
  	  . ' ORDER BY module_cahierdetextes_domaine.nom ASC';

  	return _doQuery ($sql, array(':idClasse' => $idClasse, ':typeDeTravail' => $typeDeTravail, ':date' => date('d/m/Y', $date)));
	}
	
	/**
   * Retourne les travaux pour un domaine donné
   *
   * @param int $domaineId
   *
   * @return CopixDAORecordIterator
   */
	public function findByDomaine ($domaineId) {
	  
	  $criteria = _daoSp ();
		$criteria->addCondition ('domaine_id', '=', $domaineId);
		
		return $this->findBy ($criteria);
	}
	
	/**
   * Retourne les travaux par jour pour un élève et un mois donnés
   *
   * @param int $eleveId
   * @param int $mois
   * @param int $annee
   *
   * @return array
   */
	public function findByEleveEtMoisParJour ($idEleve, $mois, $annee) {
	  
	  $toReturn = array();
	  
	  $sql = 'SELECT module_cahierdetextes_travail.id, module_cahierdetextes_travail.date_creation, module_cahierdetextes_travail.date_realisation, '
	    . ' module_cahierdetextes_travail.description '
	    . ' FROM module_cahierdetextes_travail'
  	  . ' LEFT JOIN module_cahierdetextes_travail2eleve ON (module_cahierdetextes_travail.id = module_cahierdetextes_travail2eleve.module_cahierdetextes_travail_id)'
  	  . ' WHERE module_cahierdetextes_travail2eleve.kernel_bu_eleve_idEleve=:idEleve'
  	  . ' AND module_cahierdetextes_travail.supprime = 0'
  	  . ' AND module_cahierdetextes_travail.date_realisation LIKE \'%'.'/'.$mois.'/'.$annee.'\''
  	  . ' GROUP BY module_cahierdetextes_travail.id';
  	  
    $results = _doQuery ($sql, array(':idEleve' => $idEleve));

    foreach ($results as $result) {
      
      $jour = substr($result->date_realisation, 0, 2);
      $toReturn[$jour][] = $result;
    }
    
    return $toReturn;
	}
	
	/**
   * Retourne les travaux par jour pour une classe et un mois donnés
   *
   * @param int $classeId
   * @param int $mois
   * @param int $annee
   *
   * @return array
   */
	public function findByClasseEtMoisParJour ($idClasse, $mois, $annee) {
	  
	  $toReturn = array();
	  
	  $sql = 'SELECT module_cahierdetextes_travail.id, module_cahierdetextes_travail.date_creation, module_cahierdetextes_travail.date_realisation, '
	    . ' module_cahierdetextes_travail.description '
	    . ' FROM module_cahierdetextes_travail'
	    . ' LEFT JOIN module_cahierdetextes_domaine ON (module_cahierdetextes_domaine.id = module_cahierdetextes_travail.module_cahierdetextes_domaine_id)'
  	  . ' WHERE module_cahierdetextes_domaine.kernel_bu_ecole_classe_id=:idClasse'
  	  . ' AND module_cahierdetextes_travail.supprime = 0'
  	  . ' AND module_cahierdetextes_travail.date_realisation LIKE \'%'.'/'.$mois.'/'.$annee.'\''
  	  . ' GROUP BY module_cahierdetextes_travail.id';

  	$results = _doQuery ($sql, array(':idClasse' => $idClasse));
  	
  	foreach ($results as $result) {
      
      $jour = substr($result->date_realisation, 0, 2);
      $toReturn[$jour][] = $result;
    }
    
    return $toReturn;
	}
	
	/**
   * Retourne les travaux par jour pour une classe et un mois donnés
   *
   * @param int $classeId
   * @param int $mois
   * @param int $annee
   *
   * @return array
   */
	public function findByClasseDateEtIntervalParJourEtType ($idClasse, $timestamp, $intervalle) {
	  
	  $toReturn = array();
	  
	  $dateDeb = date('d/m/Y', $timestamp);
	  $dateFin = date('d/m/Y', strtotime('+'.$intervalle.' day', $timestamp));
	        
	  $sql = 'SELECT module_cahierdetextes_travail.id, module_cahierdetextes_travail.a_faire, module_cahierdetextes_travail.date_creation, '
	    . ' module_cahierdetextes_travail.date_realisation, module_cahierdetextes_travail.description, module_cahierdetextes_domaine.nom '
	    . ' FROM module_cahierdetextes_travail'
	    . ' LEFT JOIN module_cahierdetextes_domaine ON (module_cahierdetextes_domaine.id = module_cahierdetextes_travail.module_cahierdetextes_domaine_id)'
	    . ' WHERE module_cahierdetextes_domaine.kernel_bu_ecole_classe_id=:idClasse'
	    . ' AND module_cahierdetextes_travail.supprime = 0'
	    . ' AND ((module_cahierdetextes_travail.date_creation >= :dateDeb'
	    . ' AND module_cahierdetextes_travail.date_creation <= :dateFin)'
	    . ' OR (module_cahierdetextes_travail.date_realisation >= :dateDeb'
	    . ' AND module_cahierdetextes_travail.date_realisation <= :dateFin))'
	    . ' GROUP BY module_cahierdetextes_travail.id';
	  
	  $results = _doQuery ($sql, array(':idClasse' => $idClasse, ':dateDeb' => $dateDeb, ':dateFin' => $dateFin));
	  
	  foreach ($results as $result) {
	    
	    
	    if ($result->a_faire) {
	      
	      list($jour, $mois, $annee) = explode('/', $result->date_realisation);
        $date = mktime(0, 0, 0, $mois, $jour, $annee);
	      $toReturn[$date][$result->a_faire][] = $result;
	    }
	    else {
	      
	      list($jour, $mois, $annee) = explode('/', $result->date_creation);
        $date = mktime(0, 0, 0, $mois, $jour, $annee);
	      $toReturn[$date][$result->a_faire][] = $result;
	    }
	  }

	  return $toReturn;
	}
}