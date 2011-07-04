<?php

/**
* @package    Iconito
* @subpackage Classeur
*/

class DAORecordClasseurFichier {
  
  public function __toString () {
	
		return !is_null($this->titre) ? $this->titre : $this->fichier;
	}
	
	/**
  * Determine si un fichier est un raccourci internet. 
  * Se base sur le nom du fichier en verifiant s'il a l'extention .web
  *
  * @return bool True s'il s'agit d'un favori / false sinon
  */
  public function estUnFavori () {
    
    if (substr($this->fichier, -4) == '.web') {
      
      return true;
    }
    
    return false;
  }
  
  public function getLienFavori () {
    
    $toReturn = null;
    
    if ($this->estUnFavori ()) {
      
      $toReturn = classeurService::getUrlOfFavorite($this);
    }
    
    return $toReturn;
  }
  
  public function getDownloadUrl () {
    
    $url = CopixURL::get ('classeur||telechargerFichier', array('classeurId' => $this->classeur_id, 'fichierId' => $this->id));

    return $url;
  }
}

class DAOClasseurFichier {
  
  /**
   * Retourne les fichiers d'un dossier
   *
   * @param int   $idClasseur
   * @param int   $idDossier
   *
   * @return CopixDAORecordIterator
   */
  public function getParDossier ($idClasseur, $idDossier = null, $tri = array()) {
    
    $criteria = _daoSp ();
		$criteria->addCondition ('classeur_id', '=', $idClasseur);
		if (!is_null($idDossier)) {
		  
		  $criteria->addCondition ('dossier_id', '=', $idDossier);
		}
		else {
		  
		  $criteria->addCondition ('dossier_id', '=', 0);
		}
		
		if (!empty($tri)) {
		  
		  $criteria->orderBy(array($tri['colonne'], $tri['direction']));
		}
		
		return $this->findBy ($criteria);
  }
  
  /**
   * Retourne le nombre de fichiers et la taille qu'ils occupent pour un dossier
   *
   * @param int   $idClasseur   Identifiant du classeur
   * @param int   $idDossier    Identifiant du dossier
   *
   * @return CopixDAORecordIterator
   */
	public function getNombreEtTailleParDossier ($idClasseur, $idDossier) {
	  
	  $sql = 'SELECT COUNT(id) AS nb_fichiers, SUM(taille) AS taille'
	      . ' FROM module_classeur_fichier'
	      . ' WHERE module_classeur_id=:idClasseur'
	      . ' AND module_classeur_dossier_id=:idDossier';
	      
		return _doQuery($sql, array(':idClasseur' => $idClasseur, ':idDossier' => $idDossier));
	}
}