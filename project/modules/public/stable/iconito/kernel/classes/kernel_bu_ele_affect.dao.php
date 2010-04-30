<?php

/**
 * Surcharge de la DAO Kernel_bu_ele_affect
 * 
 * @package Iconito
 * @subpackage Kernel
 */
class DAOKernel_bu_ele_affect {

	/**
	 * Retourne l'affectation pour un élève et une classe
	 *
	 * @param int $studentId Identifiant d'un élève
	 * @param int $classId   Identifiant d'une classe
	 * @return DAORecord
	 */
	public function getByStudentAndClass ($studentId, $classId) {
		
		$criteria = _daoSp ();
		$criteria->addCondition ('affect_eleve', '=', $studentId);
		$criteria->addCondition ('affect_classe', '=', $classId);
		$criteria->addCondition ('affect_current', '=', 1);
		
		$results = $this->findBy ($criteria);
		
		return isset ($results[0]) ? $results[0] : false;
	}
	
	/**
	 * Retourne les associations d'un élève
	 *
	 * @param int $studentId Identifiant d'un élève
	 * @return CopixDAORecordIterator
	 */
	public function getByStudent ($studentId) {
		
		$criteria = _daoSp ();
		$criteria->addCondition ('affect_eleve', '=', $studentId);
		
		return $this->findBy ($criteria);
	}

}


?>
