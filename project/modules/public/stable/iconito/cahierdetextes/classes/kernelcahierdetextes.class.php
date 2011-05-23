<?php
/**
* @package    Iconito
* @subpackage Cahierdetextes
* @author     Jérémy FOURNAISE
*/


class KernelCahierDeTextes {

  /* 
	 * Crée un cahier de textes
	 * Renvoie son ID ou NULL si erreur
	*/
	function create () {
		$return = NULL;
		$dao = _dao("cahierdetextes|cahierdetextes");
		$new = _record("cahierdetextes|cahierdetextes");
		$dao->insert ($new);
		if ($new->id!==NULL) {

				$return = $new->id;
		}
		return $return;
	}
}

