<?php

/**
* @package    Iconito
* @subpackage Cahier
*/

class DAORecordCahier_domaine {
  
  public function __toString () {
	
		return $this->nom;
	}
}

class DAOCahier_domaine {

  /**
	 * Retourne les domaines d'une classe
	 *
	 * @param int $idClass Identifiant d'une classe
	 * @return CopixDAORecordIterator
	 */
  public function findByIdClass($idClass) {
    
    $criteria = _daoSp ();
		$criteria->addCondition ('classe_id', '=', $idClass);
		$criteria->orderBy (array ('nom', 'DESC'));
		
		return $this->findBy ($criteria);
  }
  
  /**
	 * Retourne le domaine correspondant au nom indiqué
	 *
	  * @param string $name
 	 * @return DAORecordCahier_domaine
	 */
  public function getByName($name) {
    
    $sql = $this->_selectQuery
			   . ' WHERE nom = :name';

	  $results = new CopixDAORecordIterator (_doQuery ($sql, array (':name' => $name)), $this->getDAOId ());
		
		return isset ($results[0]) ? $results[0] : false;
  }
}