<?php
/**
* @package Iconito
* @subpackage Agenda
* @author Audrey Vassal
* @copyright 2001-2005 CopixTeam
* @link http://copix.org
* @licence http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

_classInclude('agenda|dateservices');

class ExportService
{
    /**
    * Fonction qui retourne un fichier texte contenant les évènements exportés d'après une période et les agendas concernés
    * @author Audrey Vassal <avassal@sqli.com>
    * @since 2006/08/17
    * @param array $pArEvents tableau d'évènements à exporter, classés par ordre croissant de début
    * @return string $content chaine de caractère représentant le contenu du fichier iCal exporté
    */
    public function getFileICal($pArEvents)
    {
        $serviceDate = new DateService;

        $content  = 'BEGIN:VCALENDAR' . "\n";
        $content .= 'VERSION:2.0' . "\n";
        $content .= 'X-WR-TIMEZONE;VALUE=TEXT:'. CopixConfig::get('agenda|timeZone') . "\n";
        $content .= 'METHOD:PUBLISH' . "\n";

        foreach((array)$pArEvents as $jour=>$day){

            if (!isset($day->events))
                continue;



            foreach( $day->events as $event ) {



                $content .= 'BEGIN:VEVENT' . "\n";
                $content .= 'SUMMARY:'.$event->title_event . "\n";
                if($event->alldaylong_event == 1){//si l'évènement se déroule toute la journée
                    $dateCourante = $jour;
                    $content .= 'DTSTART;VALUE=DATE:' . $dateCourante . "\n" ;
                    $dateFin = $serviceDate->dateBddToDateFr($dateCourante);
                    $dateFin = $serviceDate->addToDate($dateFin, 1, 0, 0);
                    $dateFin = $serviceDate->dateFrToDateBdd($dateFin);
                    $content .= 'DTEND;VALUE=DATE:' .  $dateFin . "\n";
                } else if($event->endrepeatdate_event != null){//si c'est un évènement qui se répète
                    $content .= 'DTSTART;TZID=' . CopixConfig::get('agenda|timeZone') . ':' . $jour . 'T' . $serviceDate->heureWithSeparateurToheureWithoutSeparateur($event->heuredeb_event) . '00Z' . "\n";
                    $content .= 'DTEND;TZID=' . CopixConfig::get('agenda|timeZone') . ':' . $jour . 'T' . $serviceDate->heureWithSeparateurToheureWithoutSeparateur($event->heurefin_event) . '00Z' . "\n";
                } else{//cas d'un évènement classique
                    //var_dump($event);
                    $content .= 'DTSTART;TZID=' . CopixConfig::get('agenda|timeZone') . ':' .$event->datedeb_event . 'T' . $serviceDate->heureWithSeparateurToheureWithoutSeparateur($event->heuredeb_event) . '00Z' . "\n";
                    $content .= 'DTEND;TZID=' . CopixConfig::get('agenda|timeZone') . ':' .$event->datefin_event . 'T' . $serviceDate->heureWithSeparateurToheureWithoutSeparateur($event->heurefin_event) . '00Z' . "\n";
                }
                if($event->desc_event != null){
                    $content .= 'DESCRIPTION:' . $event->desc_event . "\n";
                }
                if($event->place_event != null){
                    $content .= 'LOCATION:' . $event->place_event . "\n";
                }
                $content .= 'END:VEVENT' . "\n";
            }
        }
        $content .= 'END:VCALENDAR';
        return $content;
    }
}
