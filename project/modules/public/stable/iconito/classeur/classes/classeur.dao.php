<?php

/**
* @package    Iconito
* @subpackage Classeur
*/

class DAORecordClasseur {
  
  public function __toString () {
	
		return $this->titre;
	}
	
	/**
   * Indique si le classeur a des dossiers
   * Retourne true s'il y a des dossiers, false sinon
   */
	public function hasDossiers () {
	  
	  $dossierDAO = _ioDAO('classeur|classeurdossier');
	  
	  return count($dossierDAO->getEnfantsDirects($this->id)->fetchAll()) > 0 ? true : false;
	}
}

class DAOClasseur {
  
}