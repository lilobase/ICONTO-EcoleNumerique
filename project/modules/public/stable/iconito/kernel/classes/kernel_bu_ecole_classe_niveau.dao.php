<?php

/**
 * Surcharge de la DAO Kernel_bu_ecole_classe_niveau
 * 
 * @package Iconito
 * @subpackage Kernel
 */
class DAOKernel_bu_ecole_classe_niveau {

	/**
	 * Retourne les associations classe-niveau pour une classe donnée
	 *
	 * @param int $idClasse Identifiant d'une classe
	 * @return CopixDAORecordIterator
	 */
	public function getByClass ($idClass) {
		
		$criteria = _daoSp ();
		$criteria->addCondition ('classe', '=', $idClass);
		
		return $this->findBy ($criteria);
	}

}


?>
