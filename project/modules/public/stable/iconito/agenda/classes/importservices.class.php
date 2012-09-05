<?php
/**
* @package    Iconito
* @subpackage Agenda
* @author     Jérémy FOURNAISE <jeremy.fournaise@isics.fr>
*/

require_once (COPIX_ICALENDAR_PATH.'SG_iCal.php');

class ImportServices
{
    /**
     * Fonction qui insère en base de données les évènements du fichier iCal
     *
     * @param file $iCalFile   Fichier iCalendar contenant des événements à importer
     * @param int  $agendaId   Identifiant de l'agenda dans lequel doivent être insérés les évènements
     * @param bool $withPurge  Purge le calendrier de l'agenda sur la période concernée
     *
     * @return int Nombre d'insertions effectuées
     */
    public static function import($iCalFile, $agendaId, $withPurge = false)
    {
      $ical = new SG_iCal($iCalFile);
      $events = $ical->getEvents();

      $eventsCounter = 0;
      if (is_array($events) && !empty($events)) {

        // Récupération de la première et dernière date du calendrier importé
      foreach ($events as $event) {

        if (!isset($startDate) && !isset($endDate)) {

          $startDate = $event->getStart();
          $endDate   = $event->getEnd();
        }

        $startDate = min($event->getStart(), $startDate);
        $endDate   = max($event->getEnd(), $endDate);
      }
        // Si purge de l'agenda demandé...
        if ($withPurge) {
          try {
            _classInclude('agenda|AgendaService');
            AgendaService::purgeAgendaByDateInterval($agendaId, date('Ymd', $startDate), date('Ymd', $endDate), date('H:i', $startDate), date('H:i', $endDate));
          } catch (Exception $e) {

            throw new Exception('Error during agenda purge.');
          }
        }

        $eventDAO = _ioDAO('event');

        foreach($events as $event) {

        // Recherche si l'événement existe déjà en base
        $criteres = _daoSp();
        $criteres->addCondition('id_agenda'     , '=', $agendaId);
        $criteres->addCondition('title_event'   , '=', $event->getSummary());
        $criteres->addCondition('datedeb_event' , '=', date('Ymd', $event->getStart()));
        $criteres->addCondition('datefin_event' , '=', date('Ymd', $event->getEnd()));
        if ($event->isWholeDay()) {

          $criteres->addCondition('alldaylong_event', '=', 1);
        } else {

          $criteres->addCondition('heuredeb_event', '=', date('H:i', $event->getStart()));
        }

        $resultat = $eventDAO->findBy($criteres);

        // Si l'évenement n'est pas présent en base on le sauvegarde tel qu'elle
        if (count($resultat) < 1) {
          $record = self::createEvent($agendaId, $event);
          $eventDAO->insert($record);
          $eventsCounter ++;
        } else {
          // Sinon, on applique un traitement spécifique aux événements récurrents
          if($event->getProperty('recurrence')) {
            $findSame = false;

            // On cherche la date de fin de récurrence
            if($event->getProperty('freq')) {
              $endRepeatdateEvent = date('Ymd', $event->getProperty('freq')->lastOccurrence());
            } else {
                $endRepeatdateEvent = 99999999;
            }

            // Si dans les résultat, une date de fin de récurrence est trouvé, alors l'évenements est déjà en base
            foreach($resultat as $eventSame) {
              if ($eventSame->endrepeatdate_event == $endRepeatdateEvent || $eventSame->endrepeatdate_event == date('Ymd', $endDate)) {
                $findSame = true;
              }
            }

            // Sinon, il a pus être purger, on vérifie donc si sa date de fin de récurrence est égale à la date de début de purge
            if ($resultat[0]->endrepeatdate_event == date('Ymd', $startDate) && !($findSame)) {
              $record = self::createEvent($agendaId, $event);

              // Dans se cas, la récurrence prendra fin à la date de fin de purge, car un nouvel événement est entré en base pour la suite
              $record->endrepeatdate_event = date('Ymd', $endDate);
              $eventDAO->insert($record);
            }
          }
        }
        }
    }

      return $eventsCounter;
    }

    /**
     * Fonction qui insère en base de données les évènements du fichier iCal
     *
     * @param int  $agendaId   Identifiant de l'agenda dans lequel doivent être insérés les évènements
     * @param SG_iCal_Freq $event Evenement à enregistrer
     *
     * @return Recorde Evenement à sauvegarder
     */
    public static function createEvent($agendaId, $event)
    {
    $record = _record('event');

    $record->id_agenda            = $agendaId;
    $record->title_event          = $event->getSummary();
    $record->desc_event           = $event->getDescription();
    $record->place_event          = $event->getLocation();
    $record->datedeb_event        = date('Ymd', $event->getStart());
    $record->heuredeb_event       = date('H:i', $event->getStart());
    $record->heurefin_event       = date('H:i', $event->getEnd());
    $record->alldaylong_event     = $event->isWholeDay() ? 1 : 0;
    $record->everyday_event       = 0;
    $record->everyweek_event      = 0;
    $record->everymonth_event     = 0;
    $record->everyyear_event      = 0;
    $record->endrepeatdate_event  = null;

    if ($event->isWholeDay()) {
      $record->datefin_event = date('Ymd', strtotime('-1 day', $event->getEnd()));
    } else {
      $record->datefin_event = date('Ymd', $event->getEnd());
    }

    // Récupération de la fréquence de l'événement
    if ($eventRecurrence = $event->getProperty('recurrence')) {

      switch ($eventRecurrence->getFreq()) {
        case 'DAILY':
          $record->everyday_event = 1;
          break;
        case 'WEEKLY':
          $record->everyweek_event = 1;
          break;
        case 'MONTHLY':
          $record->everymonth_event = 1;
          break;
        case 'YEARLY':
          $record->everyyear_event = 1;
          break;
      }

      // Si l'évenement s'arrête après un certain nombre de fois, on récupère la dernière date
      if ($eventFrequence = $event->getProperty('freq')) {

        $record->endrepeatdate_event = date('Ymd', $eventFrequence->lastOccurrence());
      } elseif($eventRecurrence->getUntil()) {
        // Sinon on récupérère la date de dernière récurrence, on vérifie que l'attribut rrule possède la date
        if (preg_match('/UNTIL/', $eventRecurrence->rrule) > 0) {
          $record->endrepeatdate_event = date_create_from_format('Ymd\THisO', $eventRecurrence->getUntil())->format('Ymd');
        } else {
          // Sinon on ne récupère pas la date donné par SG_iCal, car l'évenement ne se termine pas et SG_iCal l'ajoute forcement à 3ans plus tard
          $record->endrepeatdate_event = '99999999';
        }
      } else {
        $record->endrepeatdate_event = '99999999';
      }
    }

    return $record;
    }
}