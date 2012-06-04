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
   *
   * @param bool  $withLocker Prendre en compte les dossiers du type "casier"
   *
   * @return bool
   */
	public function hasDossiers ($withLocker = true) {
	  
	  $dossierDAO = _ioDAO('classeur|classeurdossier');
	  
	  return count($dossierDAO->getEnfantsDirects($this->id, null, $withLocker)->fetchAll()) > 0 ? true : false;
	}
}

class DAOClasseur {
  
}