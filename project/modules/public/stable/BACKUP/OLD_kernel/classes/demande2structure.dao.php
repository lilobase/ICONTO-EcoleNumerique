<?php
/**
* @package   petiteenfance
* @subpackage kernel
* @version   $Id$
* @author   Christophe Beyer <cbeyer@cap-tic.fr>
* @copyright CAP-TIC
* @link      http://www.cap-tic.fr
*/

//_classInclude ("kernel|Tools");

class DAORecordDemande2Structure {

	
}

class DAODemande2Structure {
	
	
	/**
	 * Enregistrement des choix pour une demande
	 * 
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2009/08/13
	 * @param integer $demande Id de la demande
	 * @param array $choix Tableau avec les choix. Cles = numero du choix, Valeurs = id de la structure
	 * @return integer Nb de choix enregistres
	 */
	public function saveForDemande ($demande, $choix) {
		$res = 0;
		_doQuery ("DELETE FROM pe_demande2structure WHERE demande=:demande", array(':demande'=>$demande));
		foreach ($choix as $ordre=>$structure) {
			if (!$structure)
				continue;
			$record = _record ('kernel|demande2structure');
			$record->demande = $demande;
			$record->structure = $structure;
			$record->ordre = $ordre;
			_ioDAO ('kernel|demande2structure')->insert($record);
			$res++;
		}
		return $res;
	}
	
	

} ?>