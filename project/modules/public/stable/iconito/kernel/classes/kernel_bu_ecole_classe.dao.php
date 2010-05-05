<?php

class DAORecordKernel_bu_ecole_classe {
  
  protected $_levels = null;
  
  public function __toString () {
    
    return $this->nom.' ('.implode(' - ', $this->getLevels ()->fetchAll ()).')';
  }
  
  public function getLevels () {
    
    if (is_null($this->_levels)) {
      
      $levelDAO = _ioDAO ('kernel|kernel_bu_classe_niveau');
      $this->_levels = $levelDAO->findByClassId ($this->id);
    }
    
    return $this->_levels;
  }
}
  
class DAOKernel_bu_ecole_classe {

	/**
	 * Retourne les classes pour une école donnée
	 *
	 * @param int $idEcole Identifiant d'une école
	 * @param int $grade
	 *
	 * @return CopixDAORecordIterator
	 */
	public function getBySchool ($idEcole, $grade = null) {
		
		$criteria = _daoSp ();
		$criteria->addCondition ('ecole', '=', $idEcole);
		if (!is_null($grade)) {
		  
		  $criteria->addCondition ('annee_scol', '=', $grade);
		}
		  
		$criteria->orderBy (array ('id', 'DESC'));
		
		return $this->findBy ($criteria);
	}
}