<?php

class DAORecordKernel_bu_ecole {
  
  protected $_city = null;
  
  public function getCity () {
    
    if (is_null($this->_city)) {
      
      $cityDAO = _ioDAO ('kernel|kernel_bu_ville');
      
      $this->_city = $cityDAO->get ($this->id_ville);
    }
    
    return $this->_city;
  }
}
  
class DAOKernel_bu_ecole {

	/**
	 * Retourne les classes pour une ville donnée
	 *
	 * @param int $idVille Identifiant d'une ville
	 * @return CopixDAORecordIterator
	 */
	public function getByCity ($idVille) {
		
		$criteria = _daoSp ();
		$criteria->addCondition ('id_ville', '=', $idVille);
		
		return $this->findBy ($criteria);
	}
}