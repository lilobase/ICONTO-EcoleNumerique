<?php
/**
* @filesource
* @package : copix
* @subpackage : agenda
* @author : Audrey Vassal
* surcharge  pour les dao
*/
class DAOAgenda {

/**
	* Récupération d'une liste d'agendas parmi une liste d'ids
	* @author Christophe Beyer <cbeyer@cap-tic.fr>
	* @since 2006/08/24
    */
	function findAgendasInIds ($ids) {
    /* Ne marche plus en PHP5
		$sp = & _daoSearchConditions ();
		$sp->startGroup ('OR');
		foreach ($ids as $id) {
			$sp->addCondition ('id_agenda', '=', $id);
		}
		$sp->endGroup ();
		return $this->_compiled->findBy ($sp);
    */
		$critere = 'SELECT AG.* FROM module_agenda_agenda AG WHERE AG.id_agenda IN ('.implode(', ',$ids).')';
		return _doQuery($critere);
	}

	/**
	 * Renvoie des stats sur les évènements d'un agenda : nb d'évènements (nbEvenements)
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2006/10/06
	 * @param integer $agenda Id de l'agenda
	 * @return mixed Objet DAO.
	 */
	function getNbsEvenementsInAgenda ($agenda) {
		$critere = 'SELECT COUNT(id_event) AS nbEvenements FROM module_agenda_event EV WHERE EV.id_agenda='.$agenda.'';
		return _doQuery($critere);
	}  
  
}
?>
