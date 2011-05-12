<?php

/**
* @package    Iconito
* @subpackage Cahierdetextes
*/

class DAORecordCahierDeTextesDomaine {
  
  public function __toString () {
	
		return $this->nom;
	}
}

class DAOCahierDeTextesDomaine {

  /**
	 * Retourne les domaines d'une classe
	 *
	 * @param int $idClasse Identifiant d'une classe
	 *
	 * @return CopixDAORecordIterator
	 */
  public function findByClasse($idClasse) {
    
    $criteria = _daoSp ();
		$criteria->addCondition ('classe_id', '=', $idClasse);
		$criteria->orderBy (array ('nom', 'ASC'));
		
		return $this->findBy ($criteria);
  }
  
  /**
	 * Retourne le domaine correspondant au nom indiqué
	 *
	 * @param string $nom
	 *
 	 * @return DAORecordCahier_domaine
	 */
  public function getByNom($nom) {
    
    $sql = $this->_selectQuery
			   . ' WHERE nom = :nom';

	  $resultats = new CopixDAORecordIterator (_doQuery ($sql, array (':nom' => $nom)), $this->getDAOId ());
		
		return isset ($resultats[0]) ? $resultats[0] : false;
  }
}