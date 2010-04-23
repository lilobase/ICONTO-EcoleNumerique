<?php

/**
 * Surcharge de la DAO Kernel_bu_eleve_inscription
 * 
 * @package Iconito
 * @subpackage Kernel
 */
class DAOKernel_bu_ele_inscr {
	
	/**
	 * Retourne les enregistrements d'un élève
	 *
	 * @param int $studentId Identifiant d'un élève
	 * @return CopixDAORecordIterator
	 */
	public function getByStudent ($studentId) {
		
		$criteria = _daoSp ();
		$criteria->addCondition ('inscr_eleve', '=', $studentId);
		
		return $this->findBy ($criteria);
	}

}


?>
