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
		
		return $this->findBy ($criteria);
  }
}