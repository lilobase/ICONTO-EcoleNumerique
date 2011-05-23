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
   * @param int $eleveId
   * @param int $typeDeTravail
   *
   * @return array
   */
	public function findByEleveEtTypeDeTravail ($eleveId, $typeDeTravail = self::TYPE_EN_CLASSE, $date) {
	  
	  $sql = 'SELECT module_cahierdetextes_travail.id, module_cahierdetextes_travail.date_creation, module_cahierdetextes_travail.date_realisation, '
	    . ' module_cahierdetextes_travail.description, module_cahierdetextes_domaine.id as domaine_id, module_cahierdetextes_domaine.nom '
	    . ' FROM module_cahierdetextes_travail'
  	  . ' LEFT JOIN module_cahierdetextes_travail2eleve ON (module_cahierdetextes_travail.id = module_cahierdetextes_travail2eleve.module_cahierdetextes_travail_id)'
  	  . ' LEFT JOIN module_cahierdetextes_domaine ON (module_cahierdetextes_domaine.id = module_cahierdetextes_travail.module_cahierdetextes_domaine_id)'
  	  . ' WHERE module_cahierdetextes_travail2eleve.kernel_bu_eleve_idEleve=:eleveId'
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
  	  
    return _doQuery ($sql, array(':eleveId' => $eleveId, ':typeDeTravail' => $typeDeTravail, ':date' => date('Ymd', $date)));
	}
	
	/**
   * Retourne les travaux pour une classe donnée
   *
   * @param int $classeId
   * @param int $typeDeTravail
   *
   * @return array
   */
	public function findByClasseEtTypeDeTravail ($classeId, $typeDeTravail = self::TYPE_EN_CLASSE, $date) {
	  
	  $sql = 'SELECT module_cahierdetextes_travail.id, module_cahierdetextes_travail.date_creation, module_cahierdetextes_travail.date_realisation, '
	    . ' module_cahierdetextes_travail.description, module_cahierdetextes_domaine.id as domaine_id, module_cahierdetextes_domaine.nom '
	    . ' FROM module_cahierdetextes_travail'
  	  . ' LEFT JOIN module_cahierdetextes_domaine ON (module_cahierdetextes_domaine.id = module_cahierdetextes_travail.module_cahierdetextes_domaine_id)'
  	  . ' WHERE module_cahierdetextes_domaine.kernel_bu_ecole_classe_id=:classeId'
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

  	return _doQuery ($sql, array(':classeId' => $classeId, ':typeDeTravail' => $typeDeTravail, ':date' => date('Ymd', $date)));
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
	public function findByEleveEtMoisParJour ($eleveId, $mois, $annee) {

	  $toReturn = array();
	  
	  $sql = 'SELECT module_cahierdetextes_travail.id, module_cahierdetextes_travail.date_creation, module_cahierdetextes_travail.date_realisation, '
	    . ' module_cahierdetextes_travail.description '
	    . ' FROM module_cahierdetextes_travail'
  	  . ' LEFT JOIN module_cahierdetextes_travail2eleve ON (module_cahierdetextes_travail.id = module_cahierdetextes_travail2eleve.module_cahierdetextes_travail_id)'
  	  . ' WHERE module_cahierdetextes_travail2eleve.kernel_bu_eleve_idEleve=:eleveId'
  	  . ' AND module_cahierdetextes_travail.supprime = 0'
  	  . ' AND module_cahierdetextes_travail.date_realisation LIKE "'.$annee.$mois.'%"'
  	  . ' GROUP BY module_cahierdetextes_travail.id';
  	  
    $results = _doQuery ($sql, array(':eleveId' => $eleveId));

    foreach ($results as $result) {
      
      $jour = substr($result->date_realisation, 6, 2);
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
	public function findByClasseEtMoisParJour ($classeId, $mois, $annee) {
	  
	  $toReturn = array();
	  
	  $sql = 'SELECT module_cahierdetextes_travail.id, module_cahierdetextes_travail.date_creation, module_cahierdetextes_travail.date_realisation, '
	    . ' module_cahierdetextes_travail.description '
	    . ' FROM module_cahierdetextes_travail'
	    . ' LEFT JOIN module_cahierdetextes_domaine ON (module_cahierdetextes_domaine.id = module_cahierdetextes_travail.module_cahierdetextes_domaine_id)'
  	  . ' WHERE module_cahierdetextes_domaine.kernel_bu_ecole_classe_id=:classeId'
  	  . ' AND module_cahierdetextes_travail.supprime = 0'
  	  . ' AND module_cahierdetextes_travail.date_realisation LIKE "'.$annee.$mois.'%"'
  	  . ' GROUP BY module_cahierdetextes_travail.id';

  	$results = _doQuery ($sql, array(':classeId' => $classeId));
  	
  	foreach ($results as $result) {
      
      $jour = substr($result->date_realisation, 6, 2);
      $toReturn[$jour][] = $result;
    }
    
    return $toReturn;
	}
	
	/**
   * Retourne les travaux par jour pour une classe, une date et un intervalle donnés
   *
   * @param int    $classeId
   * @param string $date
   * @param int    $intervalle
   *
   * @return array
   */
	public function findByClasseDateEtIntervalleParJourEtType ($classeId, $date, $intervalle) {
	  
	  $toReturn = array();
    
    // Récupération des dates pour calcul de l'intervalle de récupération des travaux
	  $timestamp = mktime(0, 0, 0, substr($date, 4, 2), substr($date, 6, 2), substr($date, 0, 4));
	  $dateDeb   = date('Ymd', $timestamp);
	  $dateFin   = date('Ymd', strtotime('+'.$intervalle.' day', $timestamp));
	  
	  $sql = 'SELECT module_cahierdetextes_travail.id, module_cahierdetextes_travail.a_faire, module_cahierdetextes_travail.date_creation, '
	    . ' module_cahierdetextes_travail.date_realisation, module_cahierdetextes_travail.description, module_cahierdetextes_domaine.nom '
	    . ' FROM module_cahierdetextes_travail'
	    . ' LEFT JOIN module_cahierdetextes_domaine ON (module_cahierdetextes_domaine.id = module_cahierdetextes_travail.module_cahierdetextes_domaine_id)'
	    . ' WHERE module_cahierdetextes_domaine.kernel_bu_ecole_classe_id=:classeId'
	    . ' AND module_cahierdetextes_travail.supprime = 0'
	    . ' AND ((module_cahierdetextes_travail.a_faire = 0'
	    . ' AND module_cahierdetextes_travail.date_creation >= :dateDeb'
	    . ' AND module_cahierdetextes_travail.date_creation <= :dateFin)'
	    . ' OR (module_cahierdetextes_travail.a_faire = 1'
	    . ' AND module_cahierdetextes_travail.date_realisation >= :dateDeb'
	    . ' AND module_cahierdetextes_travail.date_realisation <= :dateFin))'
	    . ' GROUP BY module_cahierdetextes_travail.id'
	    . ' ORDER BY date_creation, date_realisation';
	  
	  $results = _doQuery ($sql, array(':classeId' => $classeId, ':dateDeb' => $dateDeb, ':dateFin' => $dateFin));
	  
	  foreach ($results as $result) {

	    if ($result->a_faire) {

	      $toReturn[$result->date_realisation][$result->a_faire][] = $result;
	    }
	    else {

	      $toReturn[$result->date_creation][$result->a_faire][] = $result;
	    }
	  }

	  return $toReturn;
	}
	
	/**
   * Retourne les travaux par jour pour un élève, une date et un intervalle donnés
   *
   * @param int     $eleveId
   * @param string  $date
   * @param int     $intervalle
   *
   * @return array
   */
	public function findByEleveDateEtIntervalleParJourEtType ($eleveId, $date, $intervalle) {
	  
	  $toReturn = array();
    
    // Récupération des dates pour calcul de l'intervalle de récupération des travaux
	  $timestamp = mktime(0, 0, 0, substr($date, 4, 2), substr($date, 6, 2), substr($date, 0, 4));
	  $dateDeb   = date('Ymd', $timestamp);
	  $dateFin   = date('Ymd', strtotime('+'.$intervalle.' day', $timestamp));
	  
	  $sql = 'SELECT module_cahierdetextes_travail.id, module_cahierdetextes_travail.a_faire, module_cahierdetextes_travail.date_creation, '
	    . ' module_cahierdetextes_travail.date_realisation, module_cahierdetextes_travail.description, module_cahierdetextes_domaine.nom '
	    . ' FROM module_cahierdetextes_travail'
	    . ' LEFT JOIN module_cahierdetextes_domaine ON (module_cahierdetextes_domaine.id = module_cahierdetextes_travail.module_cahierdetextes_domaine_id)'
	    . ' LEFT JOIN module_cahierdetextes_travail2eleve ON (module_cahierdetextes_travail.id = module_cahierdetextes_travail2eleve.module_cahierdetextes_travail_id)'
  	  . ' WHERE module_cahierdetextes_travail2eleve.kernel_bu_eleve_idEleve=:eleveId'
	    . ' AND module_cahierdetextes_travail.supprime = 0'
	    . ' AND ((module_cahierdetextes_travail.a_faire = 0'
	    . ' AND module_cahierdetextes_travail.date_creation >= :dateDeb'
	    . ' AND module_cahierdetextes_travail.date_creation <= :dateFin)'
	    . ' OR (module_cahierdetextes_travail.a_faire = 1'
	    . ' AND module_cahierdetextes_travail.date_realisation >= :dateDeb'
	    . ' AND module_cahierdetextes_travail.date_realisation <= :dateFin))'
	    . ' GROUP BY module_cahierdetextes_travail.id'
	    . ' ORDER BY date_creation DESC, date_realisation ASC';
	  
	  $results = _doQuery ($sql, array(':eleveId' => $eleveId, ':dateDeb' => $dateDeb, ':dateFin' => $dateFin));
	  
	  foreach ($results as $result) {

	    if ($result->a_faire) {

	      $toReturn[$result->date_realisation][$result->a_faire][] = $result;
	    }
	    else {

	      $toReturn[$result->date_creation][$result->a_faire][] = $result;
	    }
	  }

	  return $toReturn;
	}
	
	/**
   * Retourne les travaux par domaine pour une classe, une date, un intervalle et un domaine donné
   *
   * @param int     $classeId
   * @param string  $date
   * @param int     $intervalle
   *
   * @return array
   */
	public function findByClasseDateIntervalleEtDomaineParDomaineEtType ($classeId, $date, $intervalle, $domaineId) {
	  
	  $toReturn = array();
    
    // Récupération des dates pour calcul de l'intervalle de récupération des travaux
	  $timestamp = mktime(0, 0, 0, substr($date, 4, 2), substr($date, 6, 2), substr($date, 0, 4));
	  $dateDeb   = date('Ymd', $timestamp);
	  $dateFin   = date('Ymd', strtotime('+'.$intervalle.' day', $timestamp));
	  
	  $sql = 'SELECT module_cahierdetextes_travail.id, module_cahierdetextes_travail.a_faire, module_cahierdetextes_travail.date_creation, '
	    . ' module_cahierdetextes_travail.date_realisation, module_cahierdetextes_travail.description, module_cahierdetextes_domaine.nom '
	    . ' FROM module_cahierdetextes_travail'
	    . ' LEFT JOIN module_cahierdetextes_domaine ON (module_cahierdetextes_domaine.id = module_cahierdetextes_travail.module_cahierdetextes_domaine_id)'
	    . ' WHERE module_cahierdetextes_domaine.kernel_bu_ecole_classe_id=:classeId'
	    . ' AND module_cahierdetextes_travail.supprime = 0'
	    . ' AND ((module_cahierdetextes_travail.a_faire = 0'
	    . ' AND module_cahierdetextes_travail.date_creation >= :dateDeb'
	    . ' AND module_cahierdetextes_travail.date_creation <= :dateFin)'
	    . ' OR (module_cahierdetextes_travail.a_faire = 1'
	    . ' AND module_cahierdetextes_travail.date_realisation >= :dateDeb'
	    . ' AND module_cahierdetextes_travail.date_realisation <= :dateFin))';
	  
	  if (!is_null($domaineId)) {
	    
	    $sql .= ' AND module_cahierdetextes_domaine.id = '.$domaineId;
	  }
	    
	  $sql .= ' GROUP BY module_cahierdetextes_travail.id'
	    . ' ORDER BY date_creation, date_realisation';
	  
	  $results = _doQuery ($sql, array(':classeId' => $classeId, ':dateDeb' => $dateDeb, ':dateFin' => $dateFin));
	  
	  foreach ($results as $result) {
	    
	    $toReturn[$result->nom][$result->a_faire][] = $result;
	  }

	  return $toReturn;
	}
	
	/**
   * Retourne les travaux par domaine pour un élève, une date, un intervalle et un domaine donné
   *
   * @param int     $eleveId
   * @param string  $date
   * @param int     $intervalle
   *
   * @return array
   */
	public function findByEleveDateIntervalleEtDomaineParDomaineEtType ($eleveId, $date, $intervalle, $domaineId) {
	  
	  $toReturn = array();
    
    // Récupération des dates pour calcul de l'intervalle de récupération des travaux
	  $timestamp = mktime(0, 0, 0, substr($date, 4, 2), substr($date, 6, 2), substr($date, 0, 4));
	  $dateDeb   = date('Ymd', $timestamp);
	  $dateFin   = date('Ymd', strtotime('+'.$intervalle.' day', $timestamp));
	  
	  $sql = 'SELECT module_cahierdetextes_travail.id, module_cahierdetextes_travail.a_faire, module_cahierdetextes_travail.date_creation, '
	    . ' module_cahierdetextes_travail.date_realisation, module_cahierdetextes_travail.description, module_cahierdetextes_domaine.nom '
	    . ' FROM module_cahierdetextes_travail'
	    . ' LEFT JOIN module_cahierdetextes_domaine ON (module_cahierdetextes_domaine.id = module_cahierdetextes_travail.module_cahierdetextes_domaine_id)'
	    . ' LEFT JOIN module_cahierdetextes_travail2eleve ON (module_cahierdetextes_travail.id = module_cahierdetextes_travail2eleve.module_cahierdetextes_travail_id)'
  	  . ' WHERE module_cahierdetextes_travail2eleve.kernel_bu_eleve_idEleve=:eleveId'
	    . ' AND module_cahierdetextes_travail.supprime = 0'
	    . ' AND ((module_cahierdetextes_travail.a_faire = 0'
	    . ' AND module_cahierdetextes_travail.date_creation >= :dateDeb'
	    . ' AND module_cahierdetextes_travail.date_creation <= :dateFin)'
	    . ' OR (module_cahierdetextes_travail.a_faire = 1'
	    . ' AND module_cahierdetextes_travail.date_realisation >= :dateDeb'
	    . ' AND module_cahierdetextes_travail.date_realisation <= :dateFin))';
	    
	  if (!is_null($domaineId)) {
	    
	    $sql .= ' AND module_cahierdetextes_domaine.id = '.$domaineId;
	  }
	  
	  $sql .= ' GROUP BY module_cahierdetextes_travail.id'
	    . ' ORDER BY date_creation, date_realisation';
	  
	  $results = _doQuery ($sql, array(':eleveId' => $eleveId, ':dateDeb' => $dateDeb, ':dateFin' => $dateFin));
	  
	  foreach ($results as $result) {

	    $toReturn[$result->nom][$result->a_faire][] = $result;
	  }

	  return $toReturn;
	}
	
	/**
   * Retourne x prochains jours de travaux d'un élève
   *
   * @param int     $eleveId
   * @param int     $nbJours
   *
   * @return array
   */
	public function findTravauxAVenirParEleve ($eleveId, $nbJours = 3) {
	  
    $toReturn = array();
	  
	  $sql = 'SELECT module_cahierdetextes_travail.id, module_cahierdetextes_travail.date_creation, module_cahierdetextes_travail.date_realisation, '
	    . ' module_cahierdetextes_travail.description, module_cahierdetextes_domaine.nom '
	    . ' FROM module_cahierdetextes_travail'
  	  . ' LEFT JOIN module_cahierdetextes_travail2eleve ON (module_cahierdetextes_travail.id = module_cahierdetextes_travail2eleve.module_cahierdetextes_travail_id)'
  	  . ' LEFT JOIN module_cahierdetextes_domaine ON (module_cahierdetextes_domaine.id = module_cahierdetextes_travail.module_cahierdetextes_domaine_id)'
  	  . ' WHERE module_cahierdetextes_travail2eleve.kernel_bu_eleve_idEleve=:eleveId'
  	  . ' AND module_cahierdetextes_travail.supprime = 0'
  	  . ' AND module_cahierdetextes_travail.date_realisation > '.date('Ymd')
  	  . ' AND module_cahierdetextes_travail.a_faire = '.self::TYPE_A_FAIRE
      . ' GROUP BY module_cahierdetextes_travail.date_realisation, module_cahierdetextes_domaine.nom'
  	  . ' ORDER BY module_cahierdetextes_travail.date_realisation ASC';
  	  
    $results = _doQuery ($sql, array(':eleveId' => $eleveId));

    foreach ($results as $result) {
      
      // On ne souhaite que les travaux à venir sur $nbJours jours
      if (count($toReturn) == $nbJours) {
        
        break;
      }
      
      $toReturn[$result->date_realisation][] = $result;
    }
    
    return $toReturn;
	}
	
	/**
   * Retourne x prochains jours de travaux d'un classe
   *
   * @param int     $classeId
   * @param int     $nbJours
   *
   * @return array
   */
	public function findTravauxAVenirParClasse ($classeId, $nbJours = 3) {
	  
	  $toReturn = array();
	  
	  $sql = 'SELECT module_cahierdetextes_travail.id, module_cahierdetextes_travail.date_creation, module_cahierdetextes_travail.date_realisation, '
	    . ' module_cahierdetextes_travail.description, module_cahierdetextes_domaine.nom '
	    . ' FROM module_cahierdetextes_travail'
	    . ' LEFT JOIN module_cahierdetextes_domaine ON (module_cahierdetextes_domaine.id = module_cahierdetextes_travail.module_cahierdetextes_domaine_id)'
  	  . ' WHERE module_cahierdetextes_domaine.kernel_bu_ecole_classe_id=:classeId'
  	  . ' AND module_cahierdetextes_travail.supprime = 0'
  	  . ' AND module_cahierdetextes_travail.date_realisation > '.date('Ymd')
  	  . ' AND module_cahierdetextes_travail.a_faire = '.self::TYPE_A_FAIRE
      . ' GROUP BY module_cahierdetextes_travail.date_realisation, module_cahierdetextes_domaine.nom'
  	  . ' ORDER BY module_cahierdetextes_travail.date_realisation ASC';

  	$results = _doQuery ($sql, array(':classeId' => $classeId));
  	
  	foreach ($results as $result) {
      
      // On ne souhaite que les travaux à venir sur $nbJours jours
      if (count($toReturn) == $nbJours) {
        
        break;
      }
      
      $toReturn[$result->date_realisation][] = $result;
    }
    
    return $toReturn;
	}
}