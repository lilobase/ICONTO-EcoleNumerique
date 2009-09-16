<?php
/**
* @package   petiteenfance
* @subpackage kernel
* @version   $Id: demande.dao.php 74 2009-08-27 07:44:51Z cbeyer $
* @author   Christophe Beyer <cbeyer@cap-tic.fr>
* @copyright CAP-TIC
* @link      http://www.cap-tic.fr
*/

//_classInclude ("kernel|Tools");

class DAORecordDemande {

	/**
	 * Modification de l'etat d'une demande. Enregistre la nouvelle valeur et la stocke dans l'historique
	 * 
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2009/08/13
	 * @param $etat Nouvel etat de la demande
	 * @return none
	 */
	public function setNewEtat ($etat) {
		if ($this->etat != $etat) {
			$this->etat = $etat;
			_ioDAO('kernel|demande')->update($this);
			// Ajout dans l'historique
			$record = _record ('kernel|demande_historique_etat');
			$record->demande = $this->id;
			$record->etat = $this->etat;
			$record->date = CopixDateTime::timeStampToyyyymmddhhiiss (time ());
			$record->auteur = _currentUser()->getLogin();
			_ioDAO ('kernel|demande_historique_etat')->insert($record);
		}
		
	}
}

class DAODemande {
	
	
	/**
	 * Recherche de demandes
	 * 
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2009/08/06
	 * @param object $filtre Tableau de filtrage :
	 						[structure] = id de la structure sur laquelle on est
	 */
	public function find ($filtre, $options=array()) {
   
    $string = CopixDB::getConnection ('petiteenfance')->getProfile ()->getConnectionStringParts ();
    $peDBName = $string['dbname'];
    
    $string = CopixDB::getConnection ('viescolaire')->getProfile ()->getConnectionStringParts ();
    $vsDBName = $string['dbname'];
    
		$params = array();
		
		$sql = "
			SELECT
				demande.*,
				eleve.idEleve AS benef_id,
				eleve.nom,
				eleve.prenom1 AS prenom,
				eleve.id_sexe AS sexe_id,
				eleve.date_nais,
				sexe.sexe AS sexe_nom,
				demande_etat.nom AS demande_etat_nom,
				demande2structure.ordre,
				demande2structure.structure,
				structure.nom AS structure_nom,
				structure_type.nom AS structure_type_nom
			FROM
				`$vsDBName`.eleve AS eleve,
				`$vsDBName`.sexe AS sexe,
				`$peDBName`.pe_demande AS demande,
				`$peDBName`.pe_demande_etat AS demande_etat,
				`$peDBName`.pe_demande2structure AS demande2structure,
				`$peDBName`.pe_structure AS structure,
				`$peDBName`.pe_structure_type AS structure_type
			WHERE
				demande.enfant = eleve.idEleve
				AND eleve.id_sexe = sexe.id_s 
				AND demande.etat = demande_etat.id
				AND demande2structure.demande=demande.id
				AND demande2structure.structure=structure.id
				AND structure.type=structure_type.id
			";
		
		if ($filtre->structure) {
			$sql .= " AND demande2structure.structure=:structure";
			$params[':structure'] = $filtre->structure;
		}
		if ($filtre->ordre) {
			$sql .= " AND demande2structure.ordre=:ordre";
			$params[':ordre'] = $filtre->ordre;
		}
		if ($filtre->statut) {
			$sql .= " AND demande.etat=:etat";
			$params[':etat'] = $filtre->statut;
		}
		if ($filtre->nom) {
			$sql .= " AND (eleve.nom LIKE :nom OR eleve.nom_jf LIKE :nom)";
			$params[':nom'] = $filtre->nom.'%';
		}
		if ($filtre->prenom) {
			$sql .= " AND (eleve.prenom1 LIKE :prenom)";
			$params[':prenom'] = $filtre->prenom.'%';
		}
		if ($filtre->date_nais) {
			if ( ($ts=CopixDateTime::dateTotimestamp($filtre->date_nais)) != false) {
				$sql .= " AND eleve.date_nais = :date_nais";
				$params[':date_nais'] = date('Y-m-d',$ts);
			}
		}
			
		if ($filtre->id) {
			$sql .= " AND demande.id=:id";
			$params[':id'] = $filtre->id;
		}

		
		//print_r($filtre);
    $sql .= " ORDER BY structure.nom, eleve.nom, eleve.prenom1";
		
    return _doQuery ($sql, $params);
    //return new CopixDAORecordIterator (_doQuery ($sql, $params), $this->getDAOId ());
  }



	/**
	 * Une demande complete selon son ID et une structure
	 * 
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2009/08/10
	 * @param integer $id Id de la demande
	 * @param integer $structure Id de la structure
	 * @param array options Options :
	 						[choix] = Si l'on veut ajouter les choix
	 * @return mixed Recordset de la demande, ou false si inexistant
	 */
	public function getFull ($id, $structure, $options=array()) {
   	
		$get = false;
		
		$filtre = new CopixPPO ();
		$filtre->id = $id;
		//$filtre->structure = $structure;
		
		$find = $this->find ($filtre);
		
    if ($find) {
			$get = $find[0];
			$tsDate = CopixDateTime::yyyymmddhhiissToTimeStamp($get->date);
			$get->date_fr = date('d/m/Y',$tsDate);
			$get->date_entree_fr = '';
			if ($get->date_entree && ($tsDate = CopixDateTime::yyyymmddhhiissToTimeStamp($get->date_entree))) {
				$get->date_entree_fr = date('d/m/Y',$tsDate);
			}
			if (isset($options['choix']) && $options['choix']) {
				$list = _ioDAO ('kernel|demande2structure')->findByDemande($id);
				$get->choix = $list;
			}
		}
			
    return $get;
  }

	/**
	 * Surcharge check
	 * 
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2009/08/11
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
				$check['date'] = "Date obligatoire";
			} elseif (!CopixDateTime::dateToYYYYMMDD ($record->date,'/')) {
				if (!is_array($check)) $check=array();
				$check['date'] = "Date invalide";
			}
			if ($record->date_entree && !CopixDateTime::dateToYYYYMMDD ($record->date_entree,'/')) {
				if (!is_array($check)) $check=array();
				$check['date_entree'] = "Date d'entr&eacute;e invalide";
			}
			if (CopixConfig::get('kernel|demandeChoix')) {
				$checkChoix = false;
				$probDoublon = false;
				$deja = array();
				foreach ($record->choix as $ordre=>$structure) {
					if ($structure)
						$checkChoix = true;
					if ($structure && !isset($deja[$structure]))
						$deja[$structure] = 1;
					elseif ($structure)
						$probDoublon = true;
				}
				if (!$checkChoix) {
					if (!is_array($check)) $check=array();
					$check['choix'] = "Au moins un choix est obligatoire";
				} elseif ($probDoublon) {
					if (!is_array($check)) $check=array();
					$check['choix'] = "Impossible de choisir 2 fois le m&ecirc;me choix";
				}
			}
			if (!$record->id && $record->enfant) { // Verif si enfant existant
				if (!$r = _ioDAO('kernel|eleve','viescolaire')->get($record->enfant)) {
					if (!is_array($check)) $check=array();
					$check['enfant'] = "Enfant inexistant";
				}
			
			}
		}
		return $check;
	}
	
	/**
	 * Surcharge get
	 * 
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2009/08/11
	 * @param integer $id Id de la demande
	 * @return mixed Recordset de la demande, ou false si inexistant
	 */	
	public function get ($id) {
		$get = $this->_compiled_get($id);
		if ($get) {
			$tsDate = CopixDateTime::yyyymmddhhiissToTimeStamp($get->date);
			$get->date_fr = date('d/m/Y',$tsDate);
			$get->date_entree_fr = '';
			if ($get->date_entree && ($tsDate = CopixDateTime::yyyymmddhhiissToTimeStamp($get->date_entree))) {
				$get->date_entree_fr = date('d/m/Y',$tsDate);
			}
			//print_r($get);
		}
		return $get;
	}
	
	/**
	 * Surcharge update
	 * 
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2009/08/11
	 * @param object $record Recordset a mettre a jour
	 * @return integer Nb de lignes modifiees
	 */	
	public function update ($record) {
		$date = CopixDateTime::dateToYYYYMMDD ($record->date,'/');
		$record->date = $date.'000000';
		if ($date = CopixDateTime::dateToYYYYMMDD ($record->date_entree,'/'))
			$record->date_entree = $date.'000000';
		return $this->_compiled_update($record);
	}

} ?>