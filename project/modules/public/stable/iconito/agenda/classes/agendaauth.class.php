<?php
/**
* @package  Iconito
* @subpackage Agenda
* @author   Audrey Vassal
* @copyright 2001-2005 CopixTeam
* @link      http://copix.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class AgendaAuth {

	/**
	* Retourne le niveau de droit de la personne connectée
	* @param integer $idAgenda l'identifiant de l'agenda
	*/
	function getCapability($idAgenda){
		
		//print_r("rights=");
		
		if ($idAgenda && !_sessionGet ('modules|agenda|rights|'.$idAgenda)) {
			$droit = Kernel::getModRight('MOD_AGENDA', $idAgenda);
			_sessionSet ('modules|agenda|rights|'.$idAgenda, $droit);
		}

    $d = _sessionGet ('modules|agenda|rights|'.$idAgenda);
    
    if ($d >= PROFILE_CCV_MEMBER)
      $res = $this->getModerate();
    //elseif ($d >= PROFILE_CCV_VALID)
    //  $res = $this->getWriteAgenda();
    elseif ($d >= PROFILE_CCV_READ)
      $res = $this->getRead();
    else
      $res = $this->getNone();
		
    //print_r ("idAgenda=$idAgenda / res=$res<br>");
		return $res;
	}
	
	/*
	* @access : static
	*/
	function getNone(){
		return 0;	
	}
	
	/*
	* @access : static
	*/
	function getRead(){
		return 10;	
	}
	
	/*
	* @access : static
	*/
	function getWriteAgenda(){
		return 20;	
	}
	
	/*
	* @access : static
	*/
	function getWriteLecon(){
		return 30;	
	}
	
	/*
	* @access : static
	*/
	function getModerate(){
		return 40;	
	}
}
?>
