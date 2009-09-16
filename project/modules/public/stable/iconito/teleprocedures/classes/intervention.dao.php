<?php
/**
* @package	copix
* @version	$Id: intervention.dao.class.php,v 1.3 2009-04-01 14:48:57 cbeyer Exp $
* @author	Christophe Beyer <cbeyer@cap-tic.fr>
* @copyright 2009 CAP-TIC
* @link		http://www.iconito.org
* @licence  http://www.gnu.org/licenses/lgpl.htmlGNU Leser General Public Licence, see LICENCE file
*/

include_once (COPIX_UTILS_PATH.'CopixDate.lib.php');

class DAORecordIntervention {
	
	
	/**
   * Ajout d'une info supplementaire dans une intervention. Le message est ajoute avec l'utilisateur courante, a la date du moment
	 * 
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2009/03/04
	 * @param string $info_message Champ message
	 * @param string $info_message Champ commentaire
	 * @return integer Id de l'info ajoutee
   */
	function insertInfoSupp ($info_message, $info_commentaire) {
	
		//$this->_compiled
		
		$daoInfoSupp = _dao("teleprocedures|infosupp");
		$daoIntervention = _dao("teleprocedures|intervention");
		
		$rForm = _daoRecord('teleprocedures|infosupp');
		
		//var_dump($this);
		
		$session = Kernel::getSessionBU();
		
		$rForm->idinter = $this->_compiled->idinter;
		$rForm->iduser = $session['user_id'];
		$rForm->dateinfo = date('Y-m-d H:i:s');
		if ($info_message)
			$rForm->info_message = $info_message;
		if ($info_commentaire)
			$rForm->info_commentaire = $info_commentaire;
		$daoInfoSupp->insert ($rForm);
		
		if ($rForm->idinfo > 0 && $rForm->info_message) { // MAJ uniquement si echange
			$this->_compiled->datederniere = $rForm->dateinfo;
			$daoIntervention->update ($this->_compiled);
		}
		return $rForm->idinfo;
	}
	
	
}

class DAOIntervention {
	
	
	function get ($id) {
		$get = false;
		$sp = _daoSp ();
    $sp->addCondition ('idinter', '=', $id);
    if (count($r = $this->_compiled->findBy ($sp)) > 0)  {
			$get = $r[0];
			$now = date('d/m/Y');
			$datederniere = dateMySQLToFr($get->datederniere);
			$get->depuis = round(timeBetween($datederniere,$now)/(60*60*24));
			//var_dump($get->depuis);
		}
		return $get;
	}
}

?>