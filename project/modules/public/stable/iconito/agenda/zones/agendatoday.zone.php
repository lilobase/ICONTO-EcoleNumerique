<?php
/**
* Zone du module Agenda
* @package  Iconito
* @subpackage Agenda
* @author   Audrey Vassal
* @copyright 2001-2005 CopixTeam
* @link      http://copix.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

_classInclude('agenda|agendaservices');
_classInclude('agenda|dateservices');

class ZoneAgendaToday extends CopixZone
{
    public function _createContent (&$toReturn)
    {
        $agendaService = new AgendaService;
        $serviceDate   = new DateService;

        //on détermine le jour d'affichage
        if ($this->getParam('day') == null){
            $day = date('Ymd');

        }else{
            $day = $this->getParam('day');
        }

        //on récupère les évènements de la journée
        foreach($this->getParam('arAgendasAffiches') as $idAgenda){
            $arEventsSemaine[$idAgenda] = $agendaService->checkEventOfAgendaInBdd($idAgenda, $day, $day);
        }

        //on ordonne les évènements par ordre croissant d'heure de début d'évènement dans la journée
        $arEventByDay = $agendaService->getEventsByDay($arEventsSemaine, $day, $day);
        $arEventByDay = $agendaService->getEventsInOrderByDay($arEventByDay);

        //on simplifie le tableau pour le passer à la zone
        $arDayEvent = $arEventByDay[$day]->events;
        //on récupère la couleur d'affichage de chaque évènement
        //$arColorByEvent = $agendaService->getColorByIdEvent($arDayEvent);

        $arAgendas = $agendaService->getTilteAgendaByIdAgenda($this->getParam('arAgendasAffiches'));
        //on récupère la couleur d'affichage pour chaque agenda
        $boolCroise = array();
        $daoAgenda = & CopixDAOFactory::getInstanceOf ('agenda|agenda');

        foreach($this->getParam('arAgendasAffiches') as $id){
            $agenda = $daoAgenda->get($id);
            $boolCroise[$agenda->type_agenda] = $boolCroise[$agenda->type_agenda] == false;
            $colors = $agendaService->getColorAgendaByIdAgenda($id);
            $arColorAgenda[$id] = $boolCroise[$agenda->type_agenda] ? $colors[0] : $colors[1];
        }
        $arEventToDisplay = array();
        foreach($arDayEvent as $event){
            $event->color = $arColorAgenda[$event->id_agenda];
            $arEventToDisplay[] = $event;
        }

        $jour  = substr($day,  6, 2);
        $mois  = $serviceDate->moisNumericToMoisLitteral(substr($day, 4, 2));
        $annee = substr($day,  0, 4);

        $tpl = new CopixTpl ();

        $tpl->assign('jour' , $jour);
        $tpl->assign('mois' , $mois);
        $tpl->assign('annee', $annee);

        //$tpl->assign('arEvent'       , $arDayEvent);
        $tpl->assign('arEvent'       , $arEventToDisplay);
        $tpl->assign('arAgendas'     , $arAgendas);
        $tpl->assign('arColorByEvent', $arColorByEvent);
        $tpl->assign('arColorAgenda' , $arColorAgenda);

        $toReturn = $tpl->fetch ('aujourdhui.agenda.tpl');
        return true;
    }
}
