<?php

class DAORecordKernel_bu_ville {
  
  protected $_citiesGroup = null;
  
  public function getCitiesGroup () {
    
    if (is_null($this->_citiesGroup)) {
      
      $citiesGroupsDAO = _ioDAO ('kernel_bu_groupe_villes');
      
      $this->_citiesGroup = $citiesGroupsDAO->get ($this->id_grville);
    }
    
    return $this->_citiesGroup;
  }
}
  
class DAOKernel_bu_ville {

	/**
	 * Retourne une ville par son canon
	 *
	 * @param int $canon Canon d'une ville
	 * @return CopixDAORecordIterator
	 */
	public function getByCanon ($canon) {
		
		$criteria = _daoSp ();
		$criteria->addCondition ('canon', '=', $canon);
		
		return $this->findBy ($criteria);
	}
	
	public function getByIdGrville ($id_grville) {
	  
	  $criteria = _daoSp ();
		$criteria->addCondition ('id_grville', '=', $id_grville);
		$criteria->orderBy ('nom');
		
		return $this->findBy ($criteria);
	}
}