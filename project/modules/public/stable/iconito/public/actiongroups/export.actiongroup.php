<?php

/**
 * Actiongroup Export du module Public
 * 
 * @package Iconito
 * @subpackage Public
 */

_classInclude('sysutils|admin');

class ActionGroupExport extends EnicActionGroup {

	public function beforeAction() {
		_currentUser()->assertCredential ('group:[current_user]');
	}

	function processDefault() {
		die('Oh oh oh...');
	}

	function processCerise() {
		if (!Admin::canAdmin())
			  return CopixActionGroup::process ('genericTools|Messages::getError', array ('message'=>CopixI18N::get ('kernel|kernel.error.noRights'), 'back'=>CopixUrl::get ()));
		

		// ECOLES
		$sql = "
			SELECT
				kernel_bu_ecole.numero AS id,
				kernel_bu_ecole.RNE AS rne,
				kernel_bu_ecole.type,
				kernel_bu_ecole.nom,
				kernel_bu_ville.nom AS ville_nom
			FROM kernel_bu_ecole
			LEFT JOIN kernel_bu_ville
				ON kernel_bu_ecole.id_ville=kernel_bu_ville.id_vi
			ORDER BY id";
		$ecolesList = _doQuery ($sql);
		// echo "<pre>"; print_r($ecolesList);
		
		// CLASSES
		$sql = "
			SELECT
				kernel_bu_ecole_classe.id,
				kernel_bu_ecole_classe.ecole,
				kernel_bu_ecole_classe.nom
			FROM kernel_bu_ecole_classe
			JOIN kernel_bu_annee_scolaire
				ON kernel_bu_ecole_classe.annee_scol=kernel_bu_annee_scolaire.id_as
			WHERE kernel_bu_annee_scolaire.current=1
			  AND kernel_bu_ecole_classe.is_validee=1
			  AND kernel_bu_ecole_classe.is_supprimee=0
			ORDER BY id
		";
		$classesList = _doQuery ($sql);
		
		// echo "<pre>"; print_r($classesList);
		
		$in_classe = array();
		foreach( $classesList AS $classe ) $in_classe[] = $classe->id;
		$in_classe = implode(",",$in_classe);
		
		// echo "<pre>"; print_r($in_classe);
		
		// kernel_bu_ecole_classe : id 	ecole 	nom 	annee_scol 	is_validee 	is_supprimee
		// kernel_bu_annee_scolaire : id_as 	annee_scolaire 	dateDebut 	dateFin 	current
		
		// ENSEIGNANTS
		// LIEN ENSEIGNANTS-CLASSE
		
		if($in_classe=="") {
			$enseignantsRoleList = array();
		} else {
			$sql = "
				SELECT
					kernel_bu_personnel.numero AS id,
					kernel_bu_personnel.nom,
					kernel_bu_personnel.nom_jf,
					kernel_bu_personnel.prenom1 AS prenom,
					kernel_bu_personnel.civilite,
					kernel_bu_personnel.mel AS email,
					kernel_bu_personnel_entite.reference AS id_classe,
					dbuser.id_dbuser AS user_id,
					dbuser.login_dbuser AS user_login
					-- ,dbuser.password_dbuser
					-- ,kernel_bu_personnel_entite.reference
				FROM kernel_bu_personnel
				JOIN kernel_bu_personnel_entite
					ON kernel_bu_personnel.numero=kernel_bu_personnel_entite.id_per
				LEFT JOIN kernel_link_bu2user ON kernel_link_bu2user.bu_type='USER_ENS' AND kernel_link_bu2user.bu_id=kernel_bu_personnel.numero
				LEFT JOIN dbuser ON kernel_link_bu2user.user_id=dbuser.id_dbuser
				WHERE kernel_bu_personnel_entite.type_ref='CLASSE' AND kernel_bu_personnel_entite.role=1
				  AND kernel_bu_personnel_entite.reference IN (".$in_classe.")
			";
			$enseignantsRoleList = _doQuery ($sql);
		}
		
		// echo "<pre>"; print_r($enseignantsRoleList);
		
		$enseignantsList = array();
		$linkEnseignantsClasseList = array();
		foreach( $enseignantsRoleList AS $ensrole ) {
			$new_role = new CopixPPO();
			$new_role->id_enseignant = $ensrole->id;
			$new_role->id_classe = $ensrole->id_classe;
			$linkEnseignantsClasseList[] = $new_role;

			if(!isset($enseignantsList[$ensrole->id])) {
				unset($ensrole->id_classe);
				$enseignantsList[$ensrole->id] = $ensrole;
			}
		}

		// echo "<pre>"; print_r($enseignantsList);
		// echo "<pre>"; print_r($linkEnseignantsClasseList);
		
		// kernel_bu_personnel : numero 	nom 	nom_jf 	prenom1 	civilite 	id_sexe 	date_nais 	cle_privee 	profession 	tel_dom 	tel_gsm 	tel_pro 	mel 	num_rue 	num_seq 	adresse1 	adresse2 	code_postal 	commune 	id_ville 	pays 	challenge 	dateChallenge
		// kernel_bu_personnel_entite : id_per 	reference 	type_ref 	role (type_ref=ECOLE/CLASSE)
		// kernel_bu_personnel_role : id_role 	nom_role 	nom_role_pluriel 	perimetre 	priorite (id_role=1 ENS; id_role=2 DIR)
		// kernel_link_bu2user : user_id 	bu_type 	bu_id
		// dbuser : id_dbuser 	login_dbuser 	password_dbuser 	email_dbuser 	enabled_dbuser
		
		
		// ELEVES
		
		// kernel_bu_eleve : idEleve 	numero 	INE 	nom 	nom_jf 	prenom1 	prenom2 	prenom3 	civilite 	id_sexe 	pays_nais 	nationalite 	dep_nais 	com_nais 	date_nais 	annee_france 	num_rue 	num_seq 	adresse1 	adresse2 	code_postal 	commune 	id_ville 	pays 	hors_scol 	id_directeur 	observations 	flag 	adresse_tmp 	date_tmp 	ele_last_update
		// kernel_bu_eleve_admission : numero 	eleve 	etablissement 	annee_scol 	id_niveau 	etat_eleve 	date 	date_effet 	code_radiation 	previsionnel
		// kernel_bu_eleve_affectation : id 	eleve 	annee_scol 	classe 	niveau 	dateDebut 	current 	previsionnel_cl

		if($in_classe=="") {
			$eleveList = array();
		} else {
			$sql = "
				SELECT
					kernel_bu_eleve.idEleve AS id_eleve,
					kernel_bu_eleve.nom AS nom_eleve,
					kernel_bu_eleve.prenom1 AS prenom_eleve,
					kernel_bu_eleve.id_sexe AS id_sexe_eleve,
					kernel_bu_eleve.date_nais AS date_nais_eleve,
					
					kernel_bu_sexe.sexe AS sexe_eleve,
					
					kernel_bu_eleve_affectation.classe AS id_classe_eleve,
					
					dbuser_eleve.id_dbuser AS id_dbuser_eleve,
					dbuser_eleve.login_dbuser AS login_dbuser_eleve,
					dbuser_eleve.password_dbuser AS password_dbuser_eleve,
					
					
					kernel_bu_responsable.numero AS id_resp,
					kernel_bu_responsable.nom AS nom_resp,
					kernel_bu_responsable.prenom1 AS prenom_resp,
					
					dbuser_resp.id_dbuser AS id_dbuser_resp,
					dbuser_resp.login_dbuser AS login_dbuser_resp,
					dbuser_resp.password_dbuser AS password_dbuser_resp
					
				FROM kernel_bu_eleve
				
				JOIN kernel_bu_sexe
				  ON kernel_bu_sexe.id_s=kernel_bu_eleve.id_sexe
				
				JOIN kernel_bu_eleve_affectation
				  ON kernel_bu_eleve.idEleve=kernel_bu_eleve_affectation.eleve
				  
				JOIN kernel_bu_annee_scolaire
				  ON kernel_bu_eleve_affectation.annee_scol=kernel_bu_annee_scolaire.id_as
			
				LEFT JOIN kernel_link_bu2user kernel_link_bu2user_eleve ON kernel_link_bu2user_eleve.bu_type='USER_ELE' AND kernel_link_bu2user_eleve.bu_id=kernel_bu_eleve.idEleve
				LEFT JOIN dbuser dbuser_eleve ON kernel_link_bu2user_eleve.user_id=dbuser_eleve.id_dbuser
				
				LEFT JOIN kernel_bu_responsables
				       ON kernel_bu_responsables.id_beneficiaire=kernel_bu_eleve.idEleve
				      AND kernel_bu_responsables.type_beneficiaire='eleve'
				      AND kernel_bu_responsables.type='responsable'
				  
				LEFT JOIN kernel_bu_responsable
				       ON kernel_bu_responsable.numero=kernel_bu_responsables.id_responsable

				LEFT JOIN kernel_link_bu2user kernel_link_bu2user_resp ON kernel_link_bu2user_resp.bu_type='USER_RES' AND kernel_link_bu2user_resp.bu_id=kernel_bu_responsable.numero
				LEFT JOIN dbuser dbuser_resp ON kernel_link_bu2user_resp.user_id=dbuser_resp.id_dbuser
				       
				
				WHERE kernel_bu_annee_scolaire.current=1
				  AND kernel_bu_eleve_affectation.current=1
			";
			$eleveList = _doQuery ($sql);
		}
		
		$elevesList = array();
		$responsablesList = array();
		$linkElevesResponsablesList = array();
		foreach( $eleveList AS $eleve ) {
			
			if( $eleve->id_eleve && $eleve->id_resp ) {
				$new_linkElevesResponsablesList = new CopixPPO();
				$new_linkElevesResponsablesList->id_eleve = $eleve->id_eleve;
				$new_linkElevesResponsablesList->id_responsable = $eleve->id_resp;
				$linkElevesResponsablesList[] = $new_linkElevesResponsablesList;
			}
			
			if($eleve->id_eleve && !isset($elevesList[$eleve->id_eleve])) {
				$new_elevesList = new CopixPPO();
				$new_elevesList->id = $eleve->id_eleve;
				$new_elevesList->nom = $eleve->nom_eleve;
				$new_elevesList->prenom = $eleve->prenom_eleve;
				$new_elevesList->classe = $eleve->id_classe_eleve;
				$new_elevesList->user_id = $eleve->id_dbuser_eleve;
				$new_elevesList->user_login = $eleve->login_dbuser_eleve;
				$elevesList[$eleve->id_eleve] = $new_elevesList;
			}
			if($eleve->id_resp && !isset($responsablesList[$eleve->id_resp])) {
				$new_responsablesList = new CopixPPO();
				$new_responsablesList->id = $eleve->id_resp;
				$new_responsablesList->nom = $eleve->nom_resp;
				$new_responsablesList->prenom = $eleve->prenom_resp;
				$new_responsablesList->user_id = $eleve->id_dbuser_resp;
				$new_responsablesList->user_login = $eleve->login_dbuser_resp;
				$responsablesList[$eleve->id_resp] = $new_responsablesList;
			}
		}
		
		echo "<pre>"; print_r($eleveList);
		// echo "<pre>"; print_r($responsablesList);
		// echo "<pre>"; print_r($linkElevesResponsablesList);
		
		
		$xmlstr = '<iconito></iconito>';
		$export = new SimpleXMLElement($xmlstr);
		
		$xml_items = $export->addChild('ecoles');
		if(1) foreach( $ecolesList AS $item ) {
			$xml_ecole = $xml_items->addChild('ecole');
			$xml_ecole->addAttribute('id',$item->id);
			$xml_ecole->addChild('id',$item->id);
			$xml_ecole->addChild('rne',$item->rne);
			$xml_ecole->addChild('type',$item->type);
			$xml_ecole->addChild('nom',$item->nom);
			$xml_ecole->addChild('ville_nom',$item->ville_nom);
		}
		
		$xml_items = $export->addChild('classes');
		if(1) foreach( $classesList AS $item ) {
			$xml_item = $xml_items->addChild('classe');
			$xml_item->addAttribute('id',$item->id);
			$xml_item->addChild('id',$item->id);
			$xml_item->addChild('nom',$item->nom);
			$xml_item->addChild('ecole',$item->ecole);
		}
				
		$xml_items = $export->addChild('enseignants');
		if(1) foreach( $enseignantsList AS $item ) {
			$xml_item = $xml_items->addChild('enseignant');
			$xml_item->addAttribute('id',$item->id);
			$xml_item->addChild('id',$item->id);
			$xml_item->addChild('nom',$item->nom);
			$xml_item->addChild('nom_jf',$item->nom_jf);
			$xml_item->addChild('prenom',$item->prenom);
			$xml_item->addChild('civilite',$item->civilite);
			$xml_item->addChild('email',$item->email);
			$xml_item->addChild('user_id',$item->user_id);
			$xml_item->addChild('user_login',$item->user_login);
		}
		
		$xml_items = $export->addChild('liens_classe_enseignant');
		if(1) foreach( $linkEnseignantsClasseList AS $item ) {
			$xml_item = $xml_items->addChild('lien_classe_enseignant');
			$xml_item->addChild('id_enseignant',$item->id_enseignant);
			$xml_item->addChild('id_classe',$item->id_classe);
		}
		
		$xml_items = $export->addChild('eleves');
		if(1) foreach( $elevesList AS $item ) {
			$xml_item = $xml_items->addChild('eleve');
			$xml_item->addAttribute('id',$item->id);
			$xml_item->addChild('id',$item->id);
			$xml_item->addChild('nom',$item->nom);
			$xml_item->addChild('prenom',$item->prenom);
			$xml_item->addChild('classe',$item->classe);
			$xml_item->addChild('user_id',$item->user_id);
			$xml_item->addChild('user_login',$item->user_login);
		}
		
		$xml_items = $export->addChild('responsables');
		if(1) foreach( $responsablesList AS $item ) {
			$xml_item = $xml_items->addChild('responsable');
			$xml_item->addAttribute('id',$item->id);
			$xml_item->addChild('id',$item->id);
			$xml_item->addChild('nom',$item->nom);
			$xml_item->addChild('nom_jf',$item->nom_jf);
			$xml_item->addChild('prenom',$item->prenom);
			$xml_item->addChild('user_id',$item->user_id);
			$xml_item->addChild('user_login',$item->user_login);
		}
		
		$xml_items = $export->addChild('liens_eleve_responsable');
		if(1) foreach( $linkElevesResponsablesList AS $item ) {
			$xml_item = $xml_items->addChild('lien_eleve_responsable');
			$xml_item->addChild('id_eleve',$item->id_eleve);
			$xml_item->addChild('id_responsable',$item->id_responsable);
		}
		
		$export_xml = $export->asXML();
		
		header ("Content-Type:text/xml");
		die($export_xml);
		
	}
  
}

?>
