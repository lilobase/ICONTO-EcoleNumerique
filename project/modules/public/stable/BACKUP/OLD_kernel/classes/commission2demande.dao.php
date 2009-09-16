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

class DAORecordCommission2Demande {

	const DECISION_AUCUN = 0;
	const DECISION_ACCEPTE = 1;
	const DECISION_AJOURNE = 2;
	const DECISION_REFUSE = 3;
	
	/**
	 * Le nom en clair d'une decision, selon son ID
	 * 
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2009/08/20
	 * @param integer $decision Id de decision
	 * @param string $mode Mode
	 * @return string Nom de la decision
	 */
	public function getDecisionNom ($decision, $mode='') {
		$res = 'Probl&egrave;me';
		switch ($mode) {
			case 'listEndCommission' :
				switch ($decision) {
					case DAORecordCommission2Demande::DECISION_AUCUN : $res = "Sans d&eacute;cision"; break;
					case DAORecordCommission2Demande::DECISION_ACCEPTE : $res = "D&eacute;cisions d'acceptation"; break;
					case DAORecordCommission2Demande::DECISION_AJOURNE : $res = "D&eacute;cisions d'ajournement"; break;
					case DAORecordCommission2Demande::DECISION_REFUSE : $res = "D&eacute;cisions de refus"; break;
				}
				break;
			default :
				switch ($decision) {
					case DAORecordCommission2Demande::DECISION_AUCUN : $res = 'Aucune'; break;
					case DAORecordCommission2Demande::DECISION_ACCEPTE : $res = 'Accept&eacute;'; break;
					case DAORecordCommission2Demande::DECISION_AJOURNE : $res = 'Ajourn&eacute;e'; break;
					case DAORecordCommission2Demande::DECISION_REFUSE : $res = 'Refus&eacute;'; break;
				}
		}
		return $res;
	}
	
	
	/**
	 * S'il est possible de placer une decision sur un dossier, selon la commission
	 * 
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2009/08/20
	 * @param object $rCommission Recordset de la commission
	 * @return boolean True si la demande n'a pas encore de decision, false sinon
	 */	
	public function canSetDecision ($rCommission) {
		return (!$rCommission->est_finie);
	}

}

class DAOCommission2Demande {
	
	/**
	 * Liste detaillee et triee des dossiers d'une commission. Pour chaque demande, on recupere les infos sur la demande, l'enfant, les responsables, les choix...
	 * 
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2009/08/19
	 * @param object $rCommission Recordset de la commission
	 * @param array $options Options. [demande] permet de ne prendre qu'un dossier precis
	 * @return array Tableau avec les dossiers
	 */	
	public function getListeDemandes ($rCommission, $filtre=object) {
		
		
		$string = CopixDB::getConnection ('petiteenfance')->getProfile ()->getConnectionStringParts ();
    $peDBName = $string['dbname'];
    
    $string = CopixDB::getConnection ('viescolaire')->getProfile ()->getConnectionStringParts ();
    $vsDBName = $string['dbname'];
    
		$params = array(':commission' => $rCommission->id);
		
		$sql = "
		SELECT
			demande.*,
			eleve.nom AS enfant_nom,
			eleve.prenom1 AS enfant_prenom,
			eleve.num_rue AS enfant_num_rue,
			eleve.num_seq AS enfant_num_seq,
			eleve.adresse1 AS enfant_adresse1,
			eleve.adresse2 AS enfant_adresse2,
			eleve.code_postal AS enfant_code_postal,
			eleve.commune AS enfant_commune,
			eleve.id_sexe AS sexe_id,
			eleve.date_nais,
			sexe.sexe AS sexe_nom,
			demande2structure.structure AS demande2structure_structure,
			demande2structure.ordre AS demande2structure_ordre,
			commission2demande.decision,
			commission2demande.date AS decision_date,
			commission2demande.motif AS decision_motif
		FROM
			`$vsDBName`.eleve AS eleve,
			`$vsDBName`.sexe AS sexe,
			`$peDBName`.pe_commission2demande AS commission2demande,
			`$peDBName`.pe_demande AS demande,
			`$peDBName`.pe_demande2structure AS demande2structure
		WHERE
					demande.enfant = eleve.idEleve
			AND eleve.id_sexe = sexe.id_s 
			AND commission2demande.demande = demande.id
			AND commission2demande.commission=:commission
			AND demande2structure.demande = demande.id";
		
		if (isset($filtre) && $filtre->demande) {
			$params[':demande'] = $filtre->demande;
			$sql .= " AND demande.id=:demande";
		}
		
		if (isset($filtre) && $filtre->nom) {
			$sql .= " AND (eleve.nom LIKE :nom OR eleve.nom_jf LIKE :nom)";
			$params[':nom'] = $filtre->nom.'%';
		}
		if (isset($filtre) && $filtre->prenom) {
			$sql .= " AND (eleve.prenom1 LIKE :prenom)";
			$params[':prenom'] = $filtre->prenom.'%';
		}
		if (isset($filtre) && $filtre->decision) {
			$sql .= " AND (commission2demande.decision IN (".implode(",",array_keys($filtre->decision))."))";
		}
		
		if ($filtre->order == 'decision') {
			$sql .= " ORDER BY commission2demande.decision DESC";
		
		}
		

		$list = _doQuery ($sql, $params);
		
		$res = array();
		foreach ($list as $r) {
			if (!isset($res[$r->id])) {
				$r->choix = array();
				$r->decision_nom = DAORecordCommission2Demande::getDecisionNom($r->decision);
				$r->decision_date_fr = ($r->decision_date && ($tsDate = CopixDateTime::yyyymmddhhiissToTimeStamp($r->decision_date))) ? date('d/m/Y',$tsDate) : '';
				$r->date_entree_fr = ($r->date_entree && ($tsDate = CopixDateTime::yyyymmddhhiissToTimeStamp($r->date_entree))) ? date('d/m/Y',$tsDate) : '';
				$res[$r->id] = $r;
			}
			
			$res[$r->id]->choix[$r->demande2structure_ordre] = $r->demande2structure_structure;
			unset($res[$r->id]->demande2structure_structure);
			unset($res[$r->id]->demande2structure_ordre);
			
			//Tools::print_r2($r);
		}

		//Tools::print_r2($res);
		
		return $res;
		
	}



	/**
	 * Surcharge check
	 * 
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2009/08/24
	 * @param object $record Recordset a verifier
	 * @param boolean $update Si c'est l'update fait par l'update
	 * @return mixed Tableau d'erreur ou rien si OK
	 */	
	public function check ($record, $update=true) {
		//Tools::print_r2($record);
		$check = $this->_compiled_check($record);
		if (!$update) {
			if (!$record->date) {
				if (!is_array($check)) $check=array();
				$check['date'] = "Date de d&eacute;cision obligatoire";
			} elseif (!CopixDateTime::dateToYYYYMMDD ($record->date,'/')) {
				if (!is_array($check)) $check=array();
				$check['date'] = "Date de d&eacute;cision invalide";
			}
		}
		return $check;
	}

	/**
	 * Surcharge update
	 * 
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2009/08/24
	 * @param object $record Recordset a mettre a jour
	 * @return integer Nb de lignes modifiees
	 */	
	public function update ($record) {
		$date = CopixDateTime::dateToYYYYMMDD ($record->date,'/');
		$record->date = $date.'000000';
		return $this->_compiled_update($record);
	}




} ?>