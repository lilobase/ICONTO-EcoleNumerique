<?php
/**
* @package   petiteenfance
* @subpackage kernel
* @version   $Id: demande.dao.php 63 2009-08-18 14:07:04Z cbeyer $
* @author   Christophe Beyer <cbeyer@cap-tic.fr>
* @copyright CAP-TIC
* @link      http://www.cap-tic.fr
*/

//_classInclude ("kernel|Tools");

class DAORecordCommission {

	/**
	 * Recupere les demandes a raccrocher a une commission et les enregistre a la commission. A n'appeller qu'une fois, de suite apres l'enregistrement d'une commission
	 * 
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2009/08/19
	 * @return integer Nb de dossiers rattaches
	 */	
	public function save2Demandes () {
		$criteres = _daoSp ()->orderBy ('date');
		$list = _ioDAO ('kernel|demande')->findBy ($criteres);
		
		$nb_dossiers = 0;
		
		foreach ($list as $demande) {
			$record = _record ('kernel|commission2demande');
			$record->commission = $this->id;
			$record->demande = $demande->id;
			$record->decision = DAORecordCommission2Demande::DECISION_AUCUN;
			//Tools::print_r2($demande);
			//Tools::print_r2($record);
			if (_ioDAO ('kernel|commission2demande')->insert($record))
				$nb_dossiers++;
		}
		return $nb_dossiers;
		//Tools::print_r2($list);
	}
}

class DAOCommission {
	


	/**
	 * Surcharge check
	 * 
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2009/08/18
	 * @param object $record Recordset a verifier
	 * @param boolean $update Si c'est le check fait par l'update
	 * @return mixed Tableau d'erreur ou rien si OK
	 */	
	public function check ($record, $update=true) {
		//Tools::print_r2($record);
		$check = $this->_compiled_check($record);
		if (!$update) {
			if (!$record->date) {
				if (!is_array($check)) $check=array();
				$check['date'] = "Date obligatoire";
			} elseif (!CopixDateTime::dateToYYYYMMDD ($record->date,'/')) {
				if (!is_array($check)) $check=array();
				$check['date'] = "Date invalide";
			}
		}
		return $check;
	}
	

	/**
	 * Surcharge get
	 * 
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2009/08/18
	 * @param integer $id Id de la demande
	 * @return mixed Recordset de la demande, ou false si inexistant
	 */	
	public function get ($id) {
		$get = $this->_compiled_get($id);
		if ($get) {
			$tsDate = CopixDateTime::yyyymmddhhiissToTimeStamp($get->date);
			$get->date_fr = date('d/m/Y',$tsDate);
		}
		return $get;
	}
	
	/**
	 * Surcharge update
	 * 
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2009/08/18
	 * @param object $record Recordset a mettre a jour
	 * @param boolean $reformat S'il faut reformater les dates (cas courant du formulaire complet de modif)
	 * @return integer Nb de lignes modifiees
	 */	
	public function update ($record, $reformat=true) {
		if ($reformat) {
			$date = CopixDateTime::dateToYYYYMMDD ($record->date,'/');
			$record->date = $date.'000000';
		}
		return $this->_compiled_update($record);
	}



	/**
	 * Surcharge insert
	 * 
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2009/08/18
	 * @param object $record Recordset a mettre a jour
	 * @return integer Nb de lignes modifiees
	 */	
	public function insert ($record) {
		$date = CopixDateTime::dateToYYYYMMDD ($record->date,'/');
		$record->date = $date.'000000';
		return $this->_compiled_insert($record);
	}


	/**
	 * Calcule le nb de dossiers traites dans une commission et la met a jour
	 * 
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2009/08/18
	 * @param integer $id Id de la commission
	 * @return integer Nb de dossiers traites
	 */	
	public function calculeNbDossiersTraites ($id) {

		$ppo->commission2demande = _record ('kernel|commission2demande');
		$criteres = _daoSp ();
		$criteres->addCondition('commission', '=', $id);
		$criteres->addCondition('decision', '!=', DAORecordCommission2Demande::DECISION_AUCUN);
		$list = _ioDAO ('kernel|commission2demande')->findBy ($criteres);
		$nb_dossiers_traites = count($list);

		$sql = "UPDATE pe_commission SET nb_dossiers_traites=:nb_dossiers_traites WHERE id=:id";
		_doQuery ($sql, array(':nb_dossiers_traites'=>$nb_dossiers_traites, ':id'=>$id));
		
		return $nb_dossiers_traites;

		//return $this->_compiled_insert($record);
	}

} ?>