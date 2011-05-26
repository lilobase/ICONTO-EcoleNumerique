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
		
		$return = null;
		
		$dao = _dao("cahierdetextes|cahierdetextes");
		$new = _record("cahierdetextes|cahierdetextes");
		
		$dao->insert ($new);
		
		if ($new->id !== null) {

			$return = $new->id;
		}
		
		return $return;
	}
}

