<?php

/**
 * Surcharge de la DAO Kernel_bu_ecole
 * 
 * @package Iconito
 * @subpackage Kernel
 */

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


?>
