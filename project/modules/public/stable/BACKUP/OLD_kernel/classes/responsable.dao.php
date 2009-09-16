<?php
/**
* @package   petiteenfance
* @subpackage kernel
* @version   $Id: responsable.dao.php,v 1.7 2009-08-07 15:47:05 cbeyer Exp $
* @author   Christophe Beyer <cbeyer@cap-tic.fr>
* @copyright CAP-TIC
* @link      http://www.cap-tic.fr
*/

_classInclude ("kernel|Tools");

class DAORecordResponsable {

	const PERSONNE_TYPE = 'responsable';
	
	public $personne_type = self::PERSONNE_TYPE;
	
	public function __toString () {
		
		return $this->prenom . ' ' . $this->nom;
	}	
	
	/**
	 * Nom complet, avec nom,prenom,sexe,date de naissance
	 * @since 2009/03/25
	 * @return $string Chaine HTML
	 */
	public function __toString2 () {
		$res = $this->prenom . ' ' . $this->nom;
		$res .= ' <img src="'._resource ('/themes/vaccination/img/icon_sexe_s_'.$this->id_sexe.'.gif').'" width="16" height="16" alt="'.$this->id_sexe.'" title="'.htmlentities($this->sexe_nom).'" /> ';
		if ($this->date_nais>0) {
			$res .= ($this->id_sexe==1) ? 'n&eacute;e' : 'n&eacute;e';
			$res .= ' le '.Tools::_date2Html($this->date_nais);
			$age = Tools::getAge($this->date_nais);
			if ($age>0) {
				$res .= ($age>1) ? ' (~'.$age.' ans)' : ' (~'.$age.' an)';
			}
		}
		return $res;
	}	

}

class DAOResponsable {

	/**
	 * Les responsables d'un enfant/eleve
	 * 
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
 	 * @since 2009/06/09
	 * @param integer $id_eleve Id de l'eleve
	 * @param object $filtre Filtre d'options
	 * @return list Liste des responsables
	 */
	public function getEleveResponsables ($id_eleve, $filtre) {
		
		$criteres = _daoSp ()
			->addCondition('responsables_type', '=', DAORecordResponsable::PERSONNE_TYPE)
			->addCondition('responsables_id_beneficiaire', '=', $id_eleve)
			->addCondition('responsables_type_beneficiaire', '=', 'eleve')
		;
		
		if (isset($filtre['auth_parentale']))
			$criteres->addCondition('responsables_auth_parentale', '=', $filtre['auth_parentale']);
		
		$results = _ioDAO ('kernel|responsable2responsables','viescolaire')->findBy ($criteres);
		
		return $results;
	
	
	}
	
}

?>