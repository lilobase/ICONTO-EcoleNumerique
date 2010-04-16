<?php

/**
 * Surcharge de la DAO Kernel_bu_ecole_classe
 * 
 * @package Iconito
 * @subpackage Kernel
 */
class DAOKernel_bu_ecole_classe {

	/**
	 * Retourne les classes pour une école donnée
	 *
	 * @param int $idEcole Identifiant d'une école
	 * @return CopixDAORecordIterator
	 */
	public function getBySchool ($idEcole) {
		
		$criteria = _daoSp ();
		$criteria->addCondition ('ecole', '=', $idEcole);
		$criteria->orderBy (array ('id', 'DESC'));
		
		return $this->findBy ($criteria);
	}

}


?>
