<?php
/**
* @package   petiteenfance
* @subpackage kernel
* @version   $Id: eleve.dao.php 37 2009-08-10 10:34:42Z cbeyer $
* @author   Christophe Beyer <cbeyer@cap-tic.fr>
* @copyright CAP-TIC
* @link      http://www.cap-tic.fr
*/

//_classInclude ("kernel|Tools");

class DAORecordEleve {

	const PERSONNE_TYPE = 'eleve';
	
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
		//print_r($this);
		$res = $this->prenom . ' ' . $this->nom;
		$res .= ' <img src="'._resource ('/themes/vaccination/img/icon_sexe_s_'.$this->id_sexe.'.gif').'" width="16" height="16" alt="'.$this->id_sexe.'" title="'.htmlentities($this->sexe_nom).'" /> ';
		if ($this->date_nais>0) {
			$res .= ($this->id_sexe==1) ? 'n&eacute;' : 'n&eacute;e';
			$res .= ' le '.Tools::_date2Html($this->date_nais);
			$age = Tools::getAge($this->date_nais);
			if ($age>0) {
				$res .= ($age>1) ? ' (~'.$age.' ans)' : ' (~'.$age.' an)';
			}
		}
		return $res;
	}	

	
}

class DAOEleve {
	
	/**
	 * Formate les infos nom/prenom 
	 * 
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
 	 * @since 2009/04/27
	 * @return string Chaine unique
	 */
	public function toString ($nom, $prenom) {
		return $prenom.' '.$nom;
	}
	
	
	
	
	
} ?>