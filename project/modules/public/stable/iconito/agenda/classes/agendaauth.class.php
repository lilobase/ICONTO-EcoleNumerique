<?php
/**
* @package  Iconito
* @subpackage Agenda
* @author   Audrey Vassal
* @copyright 2001-2005 CopixTeam
* @link      http://copix.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class AgendaAuth
{
    /**
    * Retourne le niveau de droit de la personne connectée
    * @param integer $idAgenda l'identifiant de l'agenda
    */
    public function getCapability($idAgenda)
    {
        if (!$idAgenda) return 0;


        if (!($d=_sessionGet ('modules|agenda|rights|'.$idAgenda))) {
            $d = Kernel::getModRight('MOD_AGENDA', $idAgenda);
            _sessionSet ('modules|agenda|rights|'.$idAgenda, $d);
        }

    if ($d >= PROFILE_CCV_MEMBER)
      $res = AgendaAuth::getModerate();
    //elseif ($d >= PROFILE_CCV_VALID)
    //  $res = $this->getWriteAgenda();
    elseif ($d >= PROFILE_CCV_READ)
      $res = AgendaAuth::getRead();
    else
      $res = AgendaAuth::getNone();

    //print_r ("idAgenda=$idAgenda / res=$res<br>");
        return $res;
    }

    /*
    * @access : static
    */
    public function getNone()
    {
        return 0;
    }

    /*
    * @access : static
    */
    public function getRead()
    {
        return 10;
    }

    /*
    * @access : static
    */
    public function getWriteAgenda()
    {
        return 20;
    }

    /*
    * @access : static
    */
    public function getWriteLecon()
    {
        return 30;
    }

    /*
    * @access : static
    */
    public function getModerate()
    {
        return 40;
    }
}
