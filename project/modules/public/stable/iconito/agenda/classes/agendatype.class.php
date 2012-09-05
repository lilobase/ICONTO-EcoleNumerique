<?php
/**
* @package  Iconito
* @subpackage Agenda
* @author   Audrey Vassal
* @copyright 2001-2005 CopixTeam
* @link      http://copix.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class AgendaType
{
    public function getPersonnal()
    {
        return 10;
    }

    public function getClassRoom()
    {
        return 20;
    }

    public function getSchool()
    {
        return 30;
    }

    public function getWorkGroups()
    {
        return 40;
    }

    public function getOthers()
    {
        return 100;
    }

    /**
    * Fonction qui renvoie la couleur d'affichage d'un agenda en fonction de son type
    * @author Audrey Vassal <avassal@sqli.com>
    * @param integer $agendaType identifiant du type de l'agenda
    * @return array Tableau de couleur en code Hexa
    */
    public function getColors ($agendaType)
    {
        $arColor = array();

        if($agendaType == $this->getPersonnal()){
            $arColor[] = 'AFC3A3';
            $arColor[] = 'BFD5B2';
        }

        if($agendaType == $this->getClassRoom()){
            $arColor[] = 'D5BCB2';
            $arColor[] = 'E8C3B4';
            $arColor[] = 'F3C2AE';
        }

        if($agendaType == $this->getSchool()){
            $arColor[] = 'AFC7CC';
            $arColor[] = 'B9D2D8';
        }

        if($agendaType == $this->getWorkGroups()){
            $arColor[] = 'D8B9C5';
            $arColor[] = 'E8CAD5';
            $arColor[] = 'EEBFD0';
            $arColor[] = 'E1A5BB';
            $arColor[] = 'E1A5BB';
            $arColor[] = 'F1AAC4';
            $arColor[] = 'DF97B2';
            $arColor[] = 'F499BA';
            $arColor[] = 'E284A7';
            $arColor[] = 'F482AC';
        }

        if($agendaType == $this->getOthers()){
            $arColor[] = 'E4E8CA';
            $arColor[] = 'D9DDC0';
            $arColor[] = 'EBF0C9';
        }

        return $arColor;
    }

    /**
    * A partir d'un noeud (node_type/node_id), détermine le type d'agenda. Utile notamment à la création d'un agenda, pour bien positionner le champ type dans la BDD
    * @author Christophe Beyer <cbeyer@cap-tic.fr>
    * @param string $node_type Type du noeud de rattachement
    * @param integer $node_id Id du noeud
    * @return integer Type correspondant
    */
    public function getAgendaTypeForNode ($node_type, $node_id)
    {
        switch ($node_type) {
            case "USER_ELE" :
            case "USER_RES" :
            case "USER_EXT" :
            case "USER_ENS" :
            case "USER_VIL" :
                $agendaType = AgendaType::getPersonnal();
                break;
            case "BU_CLASSE" :
                $agendaType = AgendaType::getClassRoom();
                break;
            case "BU_ECOLE" :
                $agendaType = AgendaType::getSchool();
                break;
            case "CLUB" :
                $agendaType = AgendaType::getWorkGroups();
                break;
            default :
                $agendaType = AgendaType::getOthers();
                break;
        }
        return $agendaType;
    }

}
