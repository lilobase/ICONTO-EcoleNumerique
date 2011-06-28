<?php

/**
* @package    Iconito
* @subpackage Classeur
*/

class DAORecordClasseurFichier {
  
  public function __toString () {
	
		return $this->titre;
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
	function getNombreEtTailleParDossier ($idClasseur, $idDossier) {
	  
	  $sql = 'SELECT COUNT(id) AS nb_fichiers, SUM(taille) AS taille'
	      . ' FROM module_classeur_fichier'
	      . ' WHERE module_classeur_id=:idClasseur'
	      . ' AND module_classeur_dossier_id=:idDossier';
	      
		return _doQuery($sql, array(':idClasseur' => $idClasseur, ':idDossier' => $idDossier));
	}
}