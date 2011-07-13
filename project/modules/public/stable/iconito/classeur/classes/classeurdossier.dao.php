<?php

/**
* @package    Iconito
* @subpackage Classeur
*/

class DAORecordClasseurDossier {
  
  public function __toString () {
	
		return $this->nom;
	}
	
	/**
   * Retourne le path complet du dossier
   * sous la forme : /Classeur/Dossier1/Dossier2/...
   */
	public function getPath () {
	  
	  $paths = array();
	  
	  $classeurDAO = _ioDAO('classeur|classeur');
	  $dossierDAO = _ioDAO('classeur|classeurdossier');

	  $paths[] = $this->nom;
	  $dossier = $this;
	  
	  while ($dossier->parent_id != 0) {
	    
	    $dossier = $dossierDAO->get($dossier->parent_id);
	    $paths[] = $dossier->nom;
	  }
	  
	  $classeur = $classeurDAO->get($dossier->classeur_id);
	  $paths[] = $classeur->titre;
	  
	  return '/'.implode('/', array_reverse($paths)).'/';
	}
	
	public function hasSousDossiers () {
	  
	  $dossierDAO = _ioDAO('classeur|classeurdossier');
	  
	  return count($dossierDAO->getEnfantsDirects($this->classeur_id, $this->id)->fetchAll()) > 0 ? true : false;
	}
}

class DAOClasseurDossier {
	
  /**
   * Retourne les dossiers directs du classeur ou dossier donné
   *
   * @param int   $idClasseur   Identifiant du classeur
   * @param int   $idDossier    Identifiant du dossier
   *
   * @return CopixDAORecordIterator
   */
  public function getEnfantsDirects($idClasseur, $idDossier = null, $tri = array()) {
    
    $criteria = _daoSp ();
		$criteria->addCondition ('classeur_id', '=', $idClasseur);
		if (!is_null($idDossier)) {
		  
		  $criteria->addCondition ('parent_id', '=', $idDossier);
		}
		else {
		  
		  $criteria->addCondition ('parent_id', '=', 0);
		}
		
		if (!empty($tri)) {
		  
		  $criteria->orderBy(array($tri['colonne'], $tri['direction']));
		}
		else {
		  
		  $criteria->orderBy(array('nom', 'ASC'));
		}
		
		return $this->findBy ($criteria);
  }
  
  /**
   * Retourne les contenus (dossiers / fichiers) d'un classeur / dossier
   *
   * @param int     $idClasseur Identifiant du classeur
   * @param int     $idDossier  Identifiant du dossier
   * @param array   $tri        Paramètres du tri
   *
   * @return array
   */
  public function getContenus ($idClasseur, $idDossier = null, $tri = array()) {
    
    $toReturn = array();
    
    $sql = 'SELECT id, id AS dossier_id, parent_id AS parent_id, nom AS titre, nom AS fichier, nb_dossiers, nb_fichiers, taille, "---" AS type, date_creation AS date, user_type, user_id, "dossier" AS content_type'
        . ' FROM module_classeur_dossier'
        . ' WHERE module_classeur_id = :idClasseur';
    if (!is_null($idDossier)) {
      
      $sql .= ' AND parent_id = :idDossier';
    }
    
    $sql .= ' UNION';
    
    $sql .= ' SELECT id, module_classeur_dossier_id AS dossier_id, module_classeur_dossier_id AS parent_id, titre, fichier, "" AS nb_dossiers, "" AS nb_fichiers, taille, type, date_upload AS date, user_type, user_id, "fichier" AS content_type'
        . ' FROM module_classeur_fichier'
        . ' WHERE module_classeur_id = :idClasseur';
    if (!is_null($idDossier)) {
      
      $sql .= ' AND module_classeur_dossier_id = :idDossier';
    }
    
    if (!empty($tri)) {
      
      $sql .= ' ORDER BY '.$tri['colonne'].' '.$tri['direction'];
    }
    else {
      
      $sql .= ' ORDER BY titre ASC';
    }
    
    // Ajout d'un champ lien pour les favoris
    $results = _doQuery($sql, array (':idClasseur' => $idClasseur, ':idDossier' => $idDossier));
    foreach ($results as $key => $result) {
      if ($result->content_type == 'fichier' 
        && substr($result->fichier, -4) == '.web') {
        
        $result->lien = ClasseurService::getFavoriteLink ($result->id);
      }
      
      $toReturn[] = $result;
    }
    
    return $toReturn;
  }

	/**
	 * Renvoie le nombre de sous-répertoires contenus dans un répertoire
	 *
	 * @param int   $idClasseur   Identifiant du classeur
   * @param int   $idDossier    Identifiant du dossier
   *
   * @return int
	 */
	function getNombreEnfantsDirects ($idClasseur, $idDossier) {
	  
		$results = array();
		
		$sql = 'SELECT COUNT(id) AS nb_dossiers'
		    . ' FROM module_classeur_dossier'
		    . ' WHERE module_classeur_id = :idClasseur'
		    . ' AND parent_id = :idDossier';
		    
	  $results = _doQuery($sql, array(':idClasseur' => $idClasseur, ':idDossier' => $idDossier));
	  
	  return $results[0] ? $results[0]->nb_dossiers : 0;
	}
}