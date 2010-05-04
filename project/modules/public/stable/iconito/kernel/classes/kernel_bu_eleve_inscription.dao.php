<?php

/**
 * Surcharge de la DAO Kernel_bu_eleve_inscription
 * 
 * @package Iconito
 * @subpackage Kernel
 */
class DAOKernel_bu_eleve_inscription {
	
	/**
	 * Retourne les enregistrements d'un élève pour une école donnée
	 *
	 * @param int $studentId Identifiant d'un élève
	 * @param int $schoolid  Identifiant d'un école
	 * @return CopixDAORecordIterator
	 */
	public function getByStudentAndSchool ($studentId, $schoolId) {
		
		$criteria = _daoSp ();
		$criteria->addCondition ('inscr_eleve', '=', $studentId);
		$criteria->addCondition ('inscr_etablissement', '=', $schoolId);
		
		return $this->findBy ($criteria);
	}

}


?>
