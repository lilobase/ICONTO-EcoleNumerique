<?php
/**
 *
 * @package Iconito
 * @subpackage Agenda
 * @author Audrey Vassal
 * @copyright 2001-2005 CopixTeam
 * @link http://copix.org
 * @licence http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
 */

_classInclude('agenda|agendaauth');
_classInclude('agenda|dateservices');
_classInclude('agenda|agendatype');

class AgendaService
{
    /**
    * Récupération de tous les agendas en base de données
    * @author Audrey Vassal <avassal@sqli.com>
    * @since 2006/07/24
    * @return array tableau d'objet agenda
    */
    public function getAvailableAgenda ()
    {
            //var_dump($_SESSION);


      if (!_sessionGet ('modules|agenda|his')) {
                $serviceAuth   = new AgendaAuth;

                $res = array();

                $ags = array();

                // 1. Son agenda perso
                $userInfo = Kernel::getUserInfo();
                // Création des modules inexistants.
                Kernel::createMissingModules( $userInfo["type"], $userInfo["id"] );
                // Liste des modules activés.
                $modsList = Kernel::getModEnabled( $userInfo["type"], $userInfo["id"] );
                foreach ($modsList AS $modInfo) {
                    if( $modInfo->module_type == "MOD_AGENDA" && $modInfo->module_id) {
                        $ags[] = $modInfo->module_id;
                    }
                }

                // 2. Ceux de sa classe, son école, ses groupes...
                $mynodes = Kernel::getNodes();
                foreach ($mynodes as $nodes) {
                    foreach ($nodes as $node) {
                        //print_r($node);
            if (substr($node['type'],0,5)=='USER_') continue;
                        $modules = Kernel::getModEnabled ($node['type'], $node['id']);
                        $agenda = Kernel::filterModuleList ($modules, 'MOD_AGENDA');
                        if ($agenda && $serviceAuth->getCapability($agenda[0]->module_id) >= $serviceAuth->getRead())
                            $ags[] = $agenda[0]->module_id;
                    }
                }
                //print_r($ags);
        $daoAgenda = &CopixDAOFactory::getInstanceOf ('agenda|agenda');
                $agendas = $daoAgenda->findAgendasInIds($ags);

                foreach ($agendas as $agenda) {
          $tmp = new stdClass();
          $tmp->id_agenda = $agenda->id_agenda;
          $tmp->title_agenda = $agenda->title_agenda;
          $tmp->desc_agenda = $agenda->desc_agenda;
          $tmp->type_agenda = $agenda->type_agenda;
          /*
                    $tmp = array (
                        'id_agenda' => $agenda->id_agenda,
                        'title_agenda' => $agenda->title_agenda,
                        'desc_agenda' => $agenda->desc_agenda,
                        'type_agenda' => $agenda->type_agenda,
                    );
          */
                    $res[] = $tmp;
                }
        //die();
                //$sess = $daoAgenda->findAll ();
                _sessionSet ('modules|agenda|his', serialize($res));
            }

            return unserialize(_sessionGet ('modules|agenda|his'));
    }


    /**
    * Stock en session la liste des identifiants des agendas à afficher
    * @author Audrey Vassal <avassal@sqli.com>
    * @since 2006/07/28
    * @param array $pArIdAgenda tableau d'identifiant des éléments à afficher
    */
    public function setAgendaAffiches ($pArIdAgenda)
    {
        _sessionSet ('modules|agenda|affiches', $pArIdAgenda);
    }


    /**
    * Récupère de la session la liste des agendas à afficher
    * Si rien en session, revoie l'agenda personnel
    * @author Audrey Vassal <avassal@sqli.com>
    * @since 2006/07/28
    * @return array $arAgendaAffiches agendas à afficher
    */
    public function getAgendaAffiches ()
    {
        if (_sessionGet ('modules|agenda|affiches')){
           return (_sessionGet ('modules|agenda|affiches'));
        }else{
            $listAgendas = AgendaService::getAvailableAgenda();
      //print_r($listAgendas);
            $arAgendaAffiches = array();
            foreach($listAgendas as $agenda){
                if($agenda->type_agenda == AgendaType::getPersonnal()){
                    $arAgendaAffiches[$agenda->id_agenda] = $agenda->id_agenda;
                }
            }
            AgendaService::setAgendaAffiches($arAgendaAffiches);
         return $arAgendaAffiches;
        }
    }


    /**
    * Renvoie un tableau de titre d'agendas
    * @author Audrey Vassal <avassal@sqli.com>
    * @since 2006/08/07
    * @param array $pArIdAgenda tableau d'identifiants d'agenda
    * @return array $ArTilteAgenda tableau de titre d'agendas
    */
    public function getArTitleAgendaByArIdAgenda($pArIdAgenda)
    {
        $daoAgenda = &CopixDAOFactory::getInstanceOf ('agenda|agenda');
        if(count($pArIdAgenda)>0){
            foreach($pArIdAgenda as $id){
                $daoSearchParams = _daoSp ();
                $daoSearchParams->addCondition ('id_agenda', '=', $id);
                $arAgenda = $daoAgenda->findBy ($daoSearchParams);
                if(count($arAgenda)>0){
                    $ArTilteAgenda[$id] = $arAgenda[0]->title_agenda;
                }
            }
        }
        return $ArTilteAgenda;
    }


    /**
    * Indique la date fin de fin de répétition quand l'utilisateur demande à répéter un certain nbe de fois
    * @author Audrey Vassal <avassal@sqli.com>
    * @since 2006/07/26
    * @param integer $pNbFois nombre de fois que l'évènement est répété
    * @param integer $pFrequence fréquence à laquelle l'évènement est répété (jour, semaine, mois, annee)
    * @param date $pDateDebutEvent $pDateDebutEvent date du début de l'évènement, format JJ/MM/AAAA
    * @return date (au format yyyymmdd) $dateFinEvent date à laquelle se termine l'évènement
    */
    public function getDateEndRepeatByNbFois ($pNbFois, $pFrequence, $pDateDebutEvent)
    {
            //var_dump($pDateDebutEvent);
            //echo "getDateEndRepeatByNbFois ($pNbFois, $pFrequence, $pDateDebutEvent)";

      $serviceDate = new DateService;

            /*
            $dateI18N = CopixDateTime::dateToTimestamp ($pDateDebutEvent);
            $date2 = $serviceDate->dateBddToDateFr($dateI18N);
            */
            $date2 = $pDateDebutEvent;

            //var_dump($dateI18N);
            //var_dump($date2);

                // Evènement se répète tous les jours
        if ($pFrequence == 'everyday_event') {
            $dateFinEvent = $serviceDate->addToDate ($date2, $pNbFois, 0, 0, '/');
        }
        // Evènement se répète toutes les semaines
        if ($pFrequence == 'everyweek_event') {
            $dateFinEvent = $serviceDate->addToDate ($date2, $pNbFois * 7, 0, 0, '/');
        }
        // Evènement se répète tous les mois
        if ($pFrequence == 'everymonth_event') {
            $dateFinEvent = $serviceDate->addToDate ($date2, 0, $pNbFois, 0, '/');
        }
        // Evènement se répète toutes les années
        if ($pFrequence == 'everyyear_event') {
            $dateFinEvent = $serviceDate->addToDate ($date2, 0, 0, $pNbFois, '/');
        }

                //var_dump($dateFinEvent);
        return $dateFinEvent;
    }


    /*
    * Fonction qui récupère tous les évènements de l'agenda affiché, à la semaine affichée
    * @param  integer $pIdAgenda identifiant de l'agenda concerné
    * @param  integer $pDateDeb date de début de la semaine affichée au format yyyymmdd
    * @param  integer $pDateFin date de fin de la semaine affichée au format yyyymmdd
    * @return array $arResultat tableau d'évènements
    */
    public function checkEventOfAgendaInBdd ($pIdAgenda, $pDateDeb, $pDateFin)
    {
        $query = 'SELECT * FROM module_agenda_event where id_agenda = ' . $pIdAgenda . '
                    AND (((datefin_event >= ' . $pDateDeb . ') OR (endrepeatdate_event >= ' . $pDateDeb . '))
                        AND (datedeb_event <= ' . $pDateFin . '))' ;

        $result = _doQuery($query);

                $arResultat = array();
                foreach ($result as $r)
            $arResultat[] = $r;
        return $arResultat;
    }


    /**
    * Fonction qui récupère les leçons en base pour une période donnée et les classe par jour
    * @param array $pArAgendas Tableau des agendas concernes
    * @param date $pDateDeb date de début de la période au format yyyymmdd
    * @param date $pDateFin date de fin de la période au format yyyymmdd
    * @return array $arLeconsByDays les leçons de la période classées par jour
    */
    public function getLeconsByDay($pArAgendas, $pDateDeb, $pDateFin)
    {
        $dateService  = new DateService;
        $dateCourante = $pDateDeb;
        $daoLecon     = & CopixDAOFactory::getInstanceOf ('lecon');

        while($dateCourante <= $pDateFin){

            $sql = "SELECT LEC.* FROM module_agenda_lecon LEC WHERE LEC.date_lecon='".$dateCourante."' AND LEC.id_agenda IN (".implode(',',$pArAgendas).")";
            $resultat = _doQuery($sql);

            if (count($resultat) > 0){//modification
                $arLeconsByDays[$dateCourante] = $resultat[0];
            } else{
                $arLeconsByDays[$dateCourante] = null;
            }

            //on incrémente le nombre de jours de 1 à chaque passage
            $dateCourante = $dateService->addToDate($dateService->dateBddToDateFr($dateCourante), 1, 0, 0, '/');
            $dateCourante = $dateService->dateFrToDateBdd($dateCourante);
        }
        return $arLeconsByDays;
    }


    /**
    * Fonction qui organise par jour, les élèments ayant lieu dans période donnée
    * @author Audrey Vassal <avassal@sqli.com>
    * @since 2006/08/03
    * @param array $arEventsSemaine tous les évènements ayant lieu dans la semaine
    * @param array $dateDebutSemaine date de début de la semaine au format yyyymmdd
    * @param array $dateFinSemaine date de fin de la semaine au format yyyymmdd
    * @return array $arEventByDay les évènement de la semaine classés par jour
    */
    public function getEventsByDay($arEventsSemaine, $dateDebutSemaine, $dateFinSemaine)
    {
        $dateCourante = $dateDebutSemaine;
        $dateService = new DateService;
        $noEvent = true;//variable à true s'il n'y a pas d'évènements dans la semaine
        $arEventByDay = array();

        while($dateCourante <= $dateFinSemaine){
            foreach($arEventsSemaine as $idAgenda=>$arEvents){
                foreach((array)$arEvents as $event){
                $noEvent = false;
                        if($event->endrepeatdate_event == null){//cas des évènements qui ne se répètent pas
                            if($event->datedeb_event == $event->datefin_event){//l'évènement se déroule entièrement dans la même journée
                                if(($dateCourante >= $event->datedeb_event) && ($event->datefin_event >= $dateCourante)){
                                    $arEventByDay[$dateCourante]->events[$event->heuredeb_event.$event->id_event] = $event;
                                }
                            } else{//l'évènement se déroule sur plusieurs jours
                                $eventDuplicate = $event;//on copie l'évènement pour travailler dessus
                                if($dateCourante == $event->datedeb_event && $dateCourante < $event->datefin_event){//premier jour de l'évènement
                                    $eventDuplicate->heurefin_event = '24:00';
                                    $arEventByDay[$dateCourante]->events[$eventDuplicate->heuredeb_event.$event->id_event] = $eventDuplicate;
                                }
                                if($dateCourante > $event->datedeb_event && $dateCourante < $event->datefin_event){
                                    $eventDuplicate->heuredeb_event = '00:00';
                                    $eventDuplicate->heurefin_event = '24:00';
                                    $arEventByDay[$dateCourante]->events[$eventDuplicate->heuredeb_event.$event->id_event] = $eventDuplicate;
                                }
                                if($dateCourante > $event->datedeb_event && $dateCourante == $event->datefin_event){//dernier jour de l'évènement
                                    $eventDuplicate->heuredeb_event = '00:00';
                                    $arEventByDay[$dateCourante]->events[$eventDuplicate->heuredeb_event.$event->id_event] = $eventDuplicate;
                                }
                            }
                        } else{//cas des évènements qui se répètent
                            //si la date de fin de répétition se situe en plein dans l'évènement, on ne commence pas l'évènement
                            $nbJours = $dateService->getNombreJoursEcoulesEntreDeuxDates($event->datefin_event, $event->datedeb_event);
                            $dateCourantePlusNbJours = $dateService->dateBddToDateFr($dateCourante);
                            $dateCourantePlusNbJours = $dateService->addToDate($dateCourantePlusNbJours, $nbJours, 0, 0);
                            $dateCourantePlusNbJours = $dateService->dateFrToDateBdd($dateCourantePlusNbJours);
                            //évènement qui se répète tous les jours
                            if($event->everyday_event == 1 && $dateCourantePlusNbJours <= $event->endrepeatdate_event && $event->datedeb_event <= $dateCourante){
                                if($event->datedeb_event == $event->datefin_event){//l'évènement se déroule entièrement dans la même journée
                                    $arEventByDay[$dateCourante]->events[$event->heuredeb_event.$event->id_event] = $event;
                                } else{//l'évènement se déroule sur plusieurs jours
                                    $eventDuplicate = $event;//on copie l'évènement pour travailler dessus
                                    if($dateCourante == $event->datedeb_event && $dateCourante < $event->datefin_event && $dateCourante < $event->endrepeatdate_event){//premier jour de l'évènement
                                        $eventDuplicate->heurefin_event = '24:00';
                                        $arEventByDay[$dateCourante]->events[$eventDuplicate->heuredeb_event.$event->id_event] = $eventDuplicate;
                                    } else if($dateCourante > $event->datedeb_event && $dateCourante == $event->endrepeatdate_event){//dernier jour de l'évènement
                                        $eventDuplicate->heuredeb_event = '00:00';
                                        $arEventByDay[$dateCourante]->events[$eventDuplicate->heuredeb_event.$event->id_event] = $eventDuplicate;
                                    } else{
                                        //fin de l'évènement de la journée précédente
                                        $eventDuplicate->heuredeb_event = '00:00';
                                        $eventDuplicate->heurefin_event = $event->heurefin_event;
                                        $arEventByDay[$dateCourante]->events[$eventDuplicate->heuredeb_event.$event->id_event] = $eventDuplicate;
                                        //début de l'évènement
                                        $eventDuplicate->heuredeb_event = $event->heuredeb_event;
                                        $eventDuplicate->heurefin_event = '24:00';
                                        $arEventByDay[$dateCourante]->events[$eventDuplicate->heuredeb_event.$event->id_event] = $eventDuplicate;
                                    }
                                }
                            }
                            if($event->everyweek_event == 1 && $dateCourantePlusNbJours <= $event->endrepeatdate_event && $event->datedeb_event <= $dateCourante){
                                $dateCouranteTimestamp = $dateService->dateAndHoureBdToTimestamp($dateCourante, null);
                                $jourCourant           = date('w', $dateCouranteTimestamp);
                                $jourDebutEvent        = date('w', $dateService->dateAndHoureBdToTimestamp($event->datedeb_event, null));
                                $jourFinEvent          = date('w', $dateService->dateAndHoureBdToTimestamp($event->datefin_event, null));
                                if($event->datedeb_event == $event->datefin_event){//l'évènement se déroule entièrement dans la même journée
                                    //si l'évènement se déroule entièrement dans la même semaine
                                    if($dateService->dateToWeeknum($dateService->dateAndHoureBdToTimestamp($event->datedeb_event, null)) == $dateService->dateToWeeknum($dateService->dateAndHoureBdToTimestamp($event->datefin_event, null))){
                                        if(($jourDebutEvent-1 <= $jourCourant-1) && ($jourCourant-1 <= $jourFinEvent-1)){
                                            $arEventByDay[$dateCourante]->events[$event->heuredeb_event.$event->id_event] = $event;
                                        }
                                    } else{//l'évènement commence en fin de semaine et se termine en début de semaine suivante
                                        if(($jourCourant <= $jourFinEvent) || ($jourCourant == $jourDebutEvent)){
                                            $arEventByDay[$dateCourante]->events[$event->heuredeb_event.$event->id_event] = $event;
                                        }
                                    }
                                } else{//l'évènement se déroule sur plusieurs jours
                                    $eventDuplicate = $event;//on copie l'évènement pour travailler dessus
                                    if($jourCourant == $jourDebutEvent){//premier jour de l'évènement
                                        $eventDuplicate->heurefin_event = '24:00';
                                        $arEventByDay[$dateCourante]->events[$eventDuplicate->heuredeb_event.$event->id_event] = $eventDuplicate;
                                    } else if($jourCourant == $jourFinEvent){//dernier jour de l'évènement
                                        $eventDuplicate->heuredeb_event = '00:00';
                                        $arEventByDay[$dateCourante]->events[$eventDuplicate->heuredeb_event.$event->id_event] = $eventDuplicate;
                                    }
                                    //l'évènement se déroule tout dans la même semaine
                                    else if($dateService->dateToWeeknum($dateService->dateAndHoureBdToTimestamp($event->datedeb_event, null)) == $dateService->dateToWeeknum($dateService->dateAndHoureBdToTimestamp($event->datefin_event, null))){
                                        if($jourDebutEvent < $jourCourant && $jourCourant < $jourFinEvent){
                                            $eventDuplicate->heuredeb_event = '00:00';
                                            $eventDuplicate->heurefin_event = '24:00';
                                            $arEventByDay[$dateCourante]->events[$eventDuplicate->heuredeb_event.$event->id_event] = $eventDuplicate;
                                        }
                                    }
                                    //l'évènement commence en fin de semaine et se termine en début de semaine suivante
                                    else if($dateService->dateToWeeknum($dateService->dateAndHoureBdToTimestamp($event->datedeb_event, null)) != $dateService->dateToWeeknum($dateService->dateAndHoureBdToTimestamp($event->datefin_event, null))){
                                        if ($jourCourant < $jourFinEvent){
                                        $eventDuplicate->heuredeb_event = '00:00';
                                        $eventDuplicate->heurefin_event = '24:00';
                                        $arEventByDay[$dateCourante]->events[$eventDuplicate->heuredeb_event.$event->id_event] = $eventDuplicate;
                                        }
                                    }
                                }
                            }
                            //on ne compare que les jours pour un évènement qui se répète tous les mois
                            if($event->everymonth_event == 1 && $dateCourantePlusNbJours <= $event->endrepeatdate_event && $event->datedeb_event <= $dateCourante){
                                if($event->datedeb_event == $event->datefin_event){//l'évènement se déroule entièrement dans la même journée
                                    //si l'évènement se déroule entièrement dans le même mois
                                    if(date('m', $dateService->dateAndHoureBdToTimestamp($event->datedeb_event, null)) == date('m', $dateService->dateAndHoureBdToTimestamp($event->datefin_event, null))){
                                        if((substr($event->datedeb_event, 6, 2) <= substr($dateCourante, 6, 2)) && (substr($dateCourante, 6, 2) <= substr($event->datefin_event, 6, 2))){
                                            $arEventByDay[$dateCourante]->events[$event->heuredeb_event.$event->id_event] = $event;
                                        }
                                    } else{//l'évènement commence à la fin d'un mois et se termine au début du mois suivant
                                        if(( (substr($event->datedeb_event, 6, 2) <= substr($dateCourante, 6, 2)) &&  (substr($dateCourante, 6, 2) <= 31) ) || ( (1 <= substr($dateCourante, 6, 2)) && (substr($dateCourante, 6, 2) <= substr($event->datefin_event, 6, 2)) )){
                                            $arEventByDay[$dateCourante]->events[$event->heuredeb_event.$event->id_event] = $event;
                                        }
                                    }
                                } else{//l'évènement se déroule sur plusieurs jours
                                    $eventDuplicate = $event;//on copie l'évènement pour travailler dessus
                                    if(substr($event->datedeb_event, 6, 2) == substr($dateCourante, 6, 2)){//premier jour de l'évènement
                                        $eventDuplicate->heurefin_event = '24:00';
                                        $arEventByDay[$dateCourante]->events[$eventDuplicate->heuredeb_event.$event->id_event] = $eventDuplicate;
                                    } else if(substr($event->datefin_event, 6, 2) == substr($dateCourante, 6, 2)){//dernier jour de l'évènement
                                        $eventDuplicate->heuredeb_event = '00:00';
                                        $arEventByDay[$dateCourante]->events[$eventDuplicate->heuredeb_event.$event->id_event] = $eventDuplicate;
                                    } else if(date('m', $dateService->dateAndHoureBdToTimestamp($event->datedeb_event, null)) == date('m', $dateService->dateAndHoureBdToTimestamp($event->datefin_event, null))){//l'évènement se déroule entièrement dans le même mois
                                        if((substr($event->datedeb_event, 6, 2) < substr($dateCourante, 6, 2)) && (substr($event->datefin_event, 6, 2) > substr($dateCourante, 6, 2))){
                                            $eventDuplicate->heuredeb_event = '00:00';
                                            $eventDuplicate->heurefin_event = '24:00';
                                            $arEventByDay[$dateCourante]->events[$eventDuplicate->heuredeb_event.$event->id_event] = $eventDuplicate;
                                        }
                                    } else if(date('m', $dateService->dateAndHoureBdToTimestamp($event->datedeb_event, null)) != date('m', $dateService->dateAndHoureBdToTimestamp($event->datefin_event, null))){//l'évènement commence à la fin d'un mois et se termine au début du mois suivant
                                        if(( (substr($event->datedeb_event, 6, 2) < substr($dateCourante, 6, 2)) &&  (substr($dateCourante, 6, 2) <= 31) ) || ( (1 <= substr($dateCourante, 6, 2)) && (substr($dateCourante, 6, 2) < substr($event->datefin_event, 6, 2)))){
                                            $eventDuplicate->heuredeb_event = '00:00';
                                            $eventDuplicate->heurefin_event = '24:00';
                                            $arEventByDay[$dateCourante]->events[$eventDuplicate->heuredeb_event.$event->id_event] = $eventDuplicate;
                                        }
                                    }
                                }
                            }
                            //on ne compare que les jours et mois pour un évènement qui se répète toutes les années
                            if($event->everyyear_event == 1 && $dateCourantePlusNbJours <= $event->endrepeatdate_event && $event->datedeb_event <= $dateCourante){
                                if($event->datedeb_event == $event->datefin_event){//l'évènement se déroule entièrement dans la même journée
                                    //si l'évènement se déroule entièrement dans la même année
                                    if(date('Y', $dateService->dateAndHoureBdToTimestamp($event->datedeb_event, null)) == date('Y', $dateService->dateAndHoureBdToTimestamp($event->datefin_event, null))){
                                        if((substr($event->datedeb_event, 4, 4) <= substr($dateCourante, 4, 4)) && (substr($dateCourante, 4, 4) <= substr($event->datefin_event, 4, 4))){
                                            $arEventByDay[$dateCourante]->events[$event->heuredeb_event.$event->id_event] = $event;
                                        }
                                    } else{//l'évènement commence à la fin d'une année et se termine au début de l'année suivante
                                        if(( (substr($event->datedeb_event, 4, 4) <= substr($dateCourante, 4, 4)) &&  (substr($dateCourante, 4, 4) <= 1231) ) || ( (101 <= substr($dateCourante, 4, 4)) && (substr($dateCourante, 4, 4) <= substr($event->datefin_event, 4, 4)) )){
                                            $arEventByDay[$dateCourante]->events[$event->heuredeb_event.$event->id_event] = $event;
                                        }
                                    }
                                } else{//l'évènement se déroule sur plusieurs jours
                                    $eventDuplicate = $event;//on copie l'évènement pour travailler dessus
                                    if(substr($event->datedeb_event, 4, 4) == substr($dateCourante, 4, 4)){//premier jour de l'évènement
                                        $eventDuplicate->heurefin_event = '24:00';
                                        $arEventByDay[$dateCourante]->events[$eventDuplicate->heuredeb_event.$event->id_event] = $eventDuplicate;
                                    } else if(substr($event->datefin_event, 4, 4) == substr($dateCourante, 4, 4)){//dernier jour de l'évènement
                                        $eventDuplicate->heuredeb_event = '00:00';
                                        $arEventByDay[$dateCourante]->events[$eventDuplicate->heuredeb_event.$event->id_event] = $eventDuplicate;
                                    }
                                    //si l'évènement se déroule entièrement dans la même année
                                    else if(date('Y', $dateService->dateAndHoureBdToTimestamp($event->datedeb_event, null)) == date('Y', $dateService->dateAndHoureBdToTimestamp($event->datefin_event, null))){
                                        if((substr($event->datedeb_event, 4, 4) < substr($dateCourante, 4, 4)) && (substr($event->datefin_event, 4, 4) > substr($dateCourante, 4, 4))){
                                            $eventDuplicate->heuredeb_event = '00:00';
                                            $eventDuplicate->heurefin_event = '24:00';
                                            $arEventByDay[$dateCourante]->events[$eventDuplicate->heuredeb_event.$event->id_event] = $eventDuplicate;
                                        }
                                    } else if(date('Y', $dateService->dateAndHoureBdToTimestamp($event->datedeb_event, null)) != date('Y', $dateService->dateAndHoureBdToTimestamp($event->datefin_event, null))){//l'évènement commence à la fin d'une année et se termine au début de l'année suivante
                                        if(((substr($event->datedeb_event, 4, 4) < substr($dateCourante, 4, 4)) &&  (substr($dateCourante, 4, 4) <= 1231) ) || ( (101 <= substr($dateCourante, 4, 4)) && (substr($dateCourante, 4, 4) < substr($event->datefin_event, 4, 4)))){
                                            $eventDuplicate->heuredeb_event = '00:00';
                                            $eventDuplicate->heurefin_event = '24:00';
                                            $arEventByDay[$dateCourante]->events[$eventDuplicate->heuredeb_event.$event->id_event] = $eventDuplicate;
                                        }
                                    }
                                }
                            }
                        }
                    //si pas d'évènement ce jour là
                    if(!isset($arEventByDay[$dateCourante]) || count($arEventByDay[$dateCourante]) == 0){
                        $arEventByDay[$dateCourante] = null;
                    }
                }
            }

            //on incrémente le nombre de jours de 1 à chaque passage
            $dateCourante = $dateService->addToDate($dateService->dateBddToDateFr($dateCourante), 1, 0, 0, '/');
            $dateCourante = $dateService->dateFrToDateBdd($dateCourante);
        }

        //si pas d'évènements de la semaine, ne passe pas dans le foreach
        //donc on construit un tableau vide pour pouvoir insérer les cases transparentes par la suite
        if($noEvent == true){
            $dateCourante = $dateDebutSemaine;
            while($dateCourante <= $dateFinSemaine){
                $arEventByDay[$dateCourante] = null;
                //on incrémente le nombre de jours de 1 à chaque passage
                $dateCourante = $dateService->addToDate($dateService->dateBddToDateFr($dateCourante), 1, 0, 0, '/');
                $dateCourante = $dateService->dateFrToDateBdd($dateCourante);
            }
        }
        return $arEventByDay;
    }

    /**
    * Fonction qui ordonne les évènements par heure de début
    * @author Audrey Vassal <avassal@sqli.com>
    * @since 2006/08/03
    * @param array $pArEventByDay tableau d'évènements classés par jour
    * @return array $pArEventByDay tableau des évènements ordonnés par heure de début
    */
    public function getEventsInOrderByDay($pArEventByDay)
    {
        foreach((array)$pArEventByDay as $date=>$jour){
            if($pArEventByDay[$date] != null){
                ksort($pArEventByDay[$date]->events);
            }
        }
        return $pArEventByDay;
    }

    /**
    * Fonction qui donne le type de l'agenda
    * @author Audrey Vassal <avassal@sqli.com>
    * @since 2006/08/03
    * @param integer $pIdAgenda l'identifiant de l'agenda
    * @return integer $typeAgenda l'identifiant du type de l'agenda
    */
    public function getTypeAgendaByIdAgenda($pIdAgenda)
    {
        $daoSearchParams = _daoSp ();
        $daoSearchParams->addCondition ('id_agenda', '=', $pIdAgenda);

        $daoAgenda = & CopixDAOFactory::getInstanceOf ('agenda|agenda');
        $arAgenda  = $daoAgenda->findBy ($daoSearchParams);
        if(count($arAgenda)>0){
            $typeAgenda = $arAgenda[0]->type_agenda;
        } else{
            $typeAgenda = AgendaType::getOthers();
        }
        return $typeAgenda;
    }

    /**
    * Fonction qui retourne un tableau associant l'identifiant de l'évènement et sa couleur d'affichage
    * @author Audrey Vassal <avassal@sqli.com>
    * @since 2006/08/08
    * @param integer $pIdAgenda l'identifiant de l'agenda
    * @return array $arColor tableau de code ascii de la couleur
    */
    public function getColorAgendaByIdAgenda ($pIdAgenda)
    {
        $agendaType = new AgendaType;

        $typeAgenda = $this->getTypeAgendaByIdAgenda($pIdAgenda);
        $arColor    = $agendaType->getColors($typeAgenda);

        return $arColor;
    }


    /**
    * Fonction qui retourne un tableau associant l'identifiant de l'évènement et sa couleur d'affichage
    * @author Audrey Vassal <avassal@sqli.com>
    * @since 2006/08/08
    * @param array $pArEvent le tableau d'évènements
    * @return array $arColorEvent tableau associant la couleur d'affichage à un évènement
    */
    public function getColorByIdEvent ($pArEvent)
    {
        $arColorEvent = array();
        foreach((array)$pArEvent as $event){
            $arColorEvent[$event->id_event] = $this->getColorAgendaByIdAgenda($event->id_agenda);
        }
        return $arColorEvent;
    }


    /**
    * Fonction qui retourne un tableau associant l'identifiant de l'évènement et sa couleur d'affichage
    * @author Audrey Vassal <avassal@sqli.com>
    * @since 2006/08/08
    * @param array $pArIdAgenda tableau d'identifiant d'agenda
    * @return array $arTitleAgenda tableau associant l'identifiant de l'agenda à son titre
    */
    public function getTilteAgendaByIdAgenda ($pArIdAgenda)
    {
        foreach($pArIdAgenda as $key=>$idAgenda){
            $daoSearchParams = _daoSp ();
            $daoSearchParams->addCondition ('id_agenda', '=', $idAgenda);
            $daoAgenda = & CopixDAOFactory::getInstanceOf ('agenda|agenda');
            $arAgenda  = $daoAgenda->findBy ($daoSearchParams);

            if(count($arAgenda)>0){
                $arTitleAgenda[$key] = $arAgenda[0]->title_agenda;
            }
        }
        return $arTitleAgenda;
    }

    /**
    * Renvoie le titre à afficher et le parent de l'agenda. Se base sur la session. Deux cas :
    1. On affiche 1 seul agenda : on va chercher le parent, notamment pour en déduire le titre
    2. On affiche plusieurs agendas : le titre devient générique ("Agendas"), et il n'y a pas de parent direct
    * @author Christophe Beyer <cbeyer@cap-tic.fr>
    * @since 2006/11/23
    * @return array Tableau avec ['title'] (le titre) et ['parent'] (tout le parent, si on n'affiche un seul agenda)
    */
  public function getCurrentTitle ()
  {
    $res = array();
    $listAgendasAffiches = $this->getAgendaAffiches();
    if (count($listAgendasAffiches)==1) {
      if ($record = _ioDAO('agenda|agenda')->get(array_shift($listAgendasAffiches)))
        $res['title'] = $record->title_agenda;
      else
        $res['title'] = CopixI18N::get ('agenda|agenda.moduleDescription');
    } else {
      $res['title'] = CopixI18N::get ('agenda|agenda.titlePage.agendas');
    }
    return $res;
  }



    /**
     * Renvoie le menu des agendas
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2010/10/20
     * @param string $iCurrent Onglet a allumer
     * @return array Tableau du menu a afficher
     */
  public function getAgendaMenu ($iCurrent)
  {
    $menu = array();

    // Affichage hebdomadaire
        $menu_txt = CopixI18N::get('agenda.menu.back');
        $menu_type = 'week';
        $menu_url = CopixUrl::get ('agenda|agenda|vueSemaine');
        $menu[] = array('txt'=>$menu_txt,'type' => $menu_type, 'current' => ($iCurrent==$menu_type), 'url' => $menu_url);

    // Liste des agendas (popup)
        $menu_txt = CopixI18N::get ('agenda|agenda.menu.agendalist');
        $menu_type = 'agendalist';
        $menu_behavior = 'fancybox';
        $menu_url = CopixUrl::get ('agenda|agenda|agendaList');
        $menu[] = array('txt'=>$menu_txt,'type' => $menu_type, 'current' => ($iCurrent==$menu_type), 'behavior' => $menu_behavior, 'url' => $menu_url);

    // Nouvel evenement
    $listAgendasAffiches = AgendaService::getAgendaAffiches();
    $ableToWrite = false;
    $ableToModerate = false;
        //on vérifie les droits des utilisateurs sur la liste des agendas affichés
        foreach((array)$listAgendasAffiches as $id_agenda){
            //on vérifie si l'utilisateur a les droits d'écriture sur un des agendas affiché
            if(AgendaAuth::getCapability($id_agenda) >= AgendaAuth::getWriteAgenda()){
                $ableToWrite = true;
            }
            if(AgendaAuth::getCapability($id_agenda) >= AgendaAuth::getModerate()){
                $ableToModerate = true;
            }
        }
        if($ableToWrite) {
          $menu_txt = CopixI18N::get('agenda.menu.ajoutEvent');
      $menu_type = 'create';
          $menu_url = CopixUrl::get ('agenda|event|create');
          $menu[] = array('txt'=>$menu_txt,'type' => $menu_type, 'current' => ($iCurrent==$menu_type), 'url' => $menu_url);
        }
        if($ableToModerate) {
          $menu_txt = CopixI18N::get('agenda.menu.import');
      $menu_type = 'import';
          $menu_url = CopixUrl::get ('agenda|importexport|prepareImport');
          $menu[] = array('txt'=>$menu_txt,'type' => $menu_type, 'current' => ($iCurrent==$menu_type), 'url' => $menu_url);
        }

    // Export
        $menu_txt = CopixI18N::get('agenda.menu.export');
        $menu_type = 'export';
        $menu_url = CopixUrl::get ('agenda|importexport|prepareExport');
        $menu[] = array('txt'=>$menu_txt,'type' => $menu_type, 'current' => ($iCurrent==$menu_type), 'url' => $menu_url);


    return $menu;

  }

  /**
     * Ajoute/Modifie un événement à un agenda à partir d'un travail issue du module cahierdetextes
     *
     * @author Jérémy FOURNAISE
     *
     * @param int $agendaId Identifiant de l'agenda dans lequel ajouter l'événement
     * @param obj $work     Travail à ajouter sur l'agenda
     */
  public function editEventToAgendaWithWork ($agendaId, $work)
  {
    // S'il s'agit d'un travail à faire : création d'un lien travail-agenda dans module_agenda_work
    if ($work->a_faire) {

      $agendaWorkDAO = _ioDAO ('agenda|work');

      if (!$agendaWorkDAO->get($work->id, $agendaId)) {

        $agenda2work = _record ('agenda|work');

        $agenda2work->travail_id  = $work->id;
        $agenda2work->agenda_id   = $agendaId;

        $agendaWorkDAO->insert ($agenda2work);
      }
    }
    // S'il s'agit d'un travail en classe : création d'un événement dans module_agenda_event
    else {

      $eventDAO  = _ioDAO ('agenda|event');
      $workDAO   = _ioDAO ('cahierdetextes|cahierdetextestravail');

      // Récupération de l'event si déjà disponible
      if (!($event = $eventDAO->get($work->event_id))) {

        $event = _record ('agenda|event');
      }

      // Récupération du domaine (nécessaire pour définir le titre de l'événement)
      $domainDAO = _ioDAO ('cahierdetextes|cahierdetextesdomaine');
      if (!is_null($domain = $domainDAO->get($work->domaine_id))) {

        $event->title_event = $domain->nom;
      }

      $event->id_agenda           = $agendaId;
          $event->title_event         = !is_null($event->title_event) ? $event->title_event : '';
          $event->desc_event          = strip_tags($work->description);
          $event->place_event         = null;
          $event->datedeb_event       = $work->date_creation;
          $event->datefin_event       = $work->date_creation;
          $event->alldaylong_event    = 1;
          $event->everyday_event      = 0;
          $event->everyweek_event     = 0;
          $event->everymonth_event    = 0;
          $event->everyyear_event     = 0;
          $event->endrepeatdate_event = null;

          if (is_null($event->id_event)) {

            // Sauvegarde de l'événement
            $eventDAO->insert($event);
          } else {

            // Mise à jour de l'événement
            $eventDAO->update($event);
          }

          // Mise à jour du travail (sauvegarde de l'identifiant de l'événement)
          $work->event_id = $event->id_event;
          $workDAO->update($work);
    }
  }

  /**
   * Purge les évènements d'un agenda sur une période donnée
   *
   * @author Jérémy FOURNAISE <jeremy.fournaise@isics.fr>
   *
   * @param integer  $pIdAgenda    Identifiant de l'agenda à purger
   * @param string   $pDateDebut   Date de début de la période à purger (format Ymd)
   * @param string   $pDateFin     Date de fin de la période à purger (format Ymd)
   * @param string   $pHeureDebut  Heure pour la date de début de la période (format H:i)
   * @param string   $pHeureFin    Heure pour la date de fin de la période (format H:i)
   */
  public function purgeAgendaByDateInterval ($pIdAgenda, $pDateDebut, $pDateFin, $pHeureDebut = '00:00', $pHeureFin = '00:00')
  {
    $dateServices = new DateService;

    $eventDAO = _ioDAO('agenda|event');
    $events = $eventDAO->findByAgendaAndDateInterval($pIdAgenda, $pDateDebut, $pDateFin);
    foreach ($events as $event) {

      // Cas d'un événement qui ne se répète pas
      if (is_null($event->endrepeatdate_event) || $event->endrepeatdate_event < $pDateFin || ($event->endrepeatdate_event == $pDateFin && $event->heuredeb_event >= $pHeureFin)) {
              if (($event->datefin_event > $pDateDebut && $event->datedeb_event < $pDateFin)
                || ($event->datedeb_event == $pDateDebut && $event->heuredeb_event >= $pHeureDebut)
                || ($event->datefin_event == $pDateFin && $event->heuredeb_event <= $pHeureFin)
                || (($event->datefin_event == $pDateFin || $event->datedeb_event == $pDateDebut) && $event->alldaylong_event == 1)) {
          $eventDAO->delete($event->id_event);
              }
          } else {

        $duplicateEvent = clone $event;
        $mkDateDebEvent = mktime(0, 0, 0, substr($duplicateEvent->datedeb_event, 4, 2), substr($duplicateEvent->datedeb_event, 6, 2), substr($duplicateEvent->datedeb_event, 0, 4));
        $mkDateFin = mktime(0, 0, 0, substr($pDateFin, 4, 2), substr($pDateFin, 6, 2), substr($pDateFin, 0, 4));

              // Création d'un autre événement qui commence après la période concernée s'il se poursuivait après l'intervalle donné
              if ($duplicateEvent->endrepeatdate_event > $pDateFin || ($duplicateEvent->endrepeatdate_event == $pDateFin && $duplicateEvent->heuredeb_event >= $pHeureFin)) {

              $record = _record ('event');

              // Evénement qui se répète tous les jours
              if ($duplicateEvent->everyday_event == 1) {

                if ($duplicateEvent->heuredeb_event < $pHeureFin || $duplicateEvent->alldaylong_event == 1) {

              // La date de début devient la date de la fin de période + 1 jour
              $record->datedeb_event = date('Ymd', mktime(0, 0, 0, substr($pDateFin, 4, 2), substr($pDateFin, 6, 2) + 1, substr($pDateFin, 0, 4)));
            } else {

                        $record->datedeb_event = $pDateFin;
                      }
              }
              // Evénement qui se répète toutes les semaines
              elseif ($duplicateEvent->everyweek_event == 1) {

                if (date('w', $mkDateDebEvent) == date('w', $mkDateFin)) {

                          if ($duplicateEvent->heuredeb_event < $pHeureFin || $duplicateEvent->alldaylong_event == 1) {

                          $record->datedeb_event = date('Ymd', mktime(0, 0, 0, substr($pDateFin, 4, 2), substr($pDateFin, 6, 2) + 7, substr($pDateFin, 0, 4)));
                        } else {

                        $record->datedeb_event = $pDateFin;
                      }
                    } else {

                          $record->datedeb_event = $dateServices->getDayOfWeekAfterDate($pDateFin, date('w', $dateServices->dateAndHoureBdToTimestamp($pDateFin, null)));
                    }
              }
              // Evénement qui se répète tous les mois
              elseif ($duplicateEvent->everymonth_event == 1) {

                if (date('md', $mkDateDebEvent) == date('md', $mkDateFin)) {

                          if ($duplicateEvent->heuredeb_event < $pHeureFin || $duplicateEvent->alldaylong_event == 1) {

                            $record->datedeb_event = date('Ymd', mktime(0, 0, 0, substr($pDateFin, 4, 2) + 1, substr($pDateFin, 6, 2), substr($pDateFin, 0, 4)));
                          } else {

                              $record->datedeb_event = $pDateFin;
                          }
                      } else {

                        $record->datedeb_event = $dateServices->getDayOfMonthAfterDate($pDateFin, substr($duplicateEvent->datedeb_event, 6, 2));
                      }
              }
              // Evénement qui se répète tous les ans
              elseif ($duplicateEvent->everyyear_event == 1) {

                if (date('Ymd', $mkDateDebEvent) == date('Ymd', $mkDateFin)) {

                          if ($duplicateEvent->heuredeb_event < $pHeureFin || $duplicateEvent->alldaylong_event == 1) {

                            $record->datedeb_event = date('Ymd', mktime(0, 0, 0, substr($pDateFin, 4, 2), substr($pDateFin, 6, 2), substr($pDateFin, 0, 4) + 1));
                          } else {

                            $record->datedeb_event = $pDateFin;
                          }
                      } else {

                          $record->datedeb_event = $dateServices->getDayOfYearAfterDate($pDateFin, substr($duplicateEvent->datedeb_event, 4, 4));
                      }
              }

                  $nbJour = $dateServices->getNombreJoursEcoulesEntreDeuxDates($duplicateEvent->datefin_event, $duplicateEvent->datedeb_event);
                  $record->datefin_event = $dateServices->dateFrToDateBdd($dateServices->addToDate($dateServices->dateBddToDateFr($record->datedeb_event), $nbJour, 0, 0));

                  $record->id_agenda            = $duplicateEvent->id_agenda;
                  $record->title_event          = $duplicateEvent->title_event;
                  $record->desc_event           = $duplicateEvent->desc_event;
                  $record->place_event          = $duplicateEvent->place_event;
                  $record->heuredeb_event       = $duplicateEvent->heuredeb_event;
                  $record->heurefin_event       = $duplicateEvent->heurefin_event;
                  $record->alldaylong_event     = $duplicateEvent->alldaylong_event;
                  $record->everyday_event       = $duplicateEvent->everyday_event;
                  $record->everyweek_event      = $duplicateEvent->everyweek_event;
                  $record->everymonth_event     = $duplicateEvent->everymonth_event;
                  $record->everyyear_event      = $duplicateEvent->everyyear_event;
                  $record->endrepeatdate_event  = $duplicateEvent->endrepeatdate_event;

          $eventDAO->insert ($record);

          // Modification de la date de fin de répétition de l'événement
          if ($event->endrepeatdate_event >= $pDateFin) {

            $event->endrepeatdate_event = $pDateDebut;
          }

          $eventDAO->update ($event);
            }
          }
    }
  }

}
