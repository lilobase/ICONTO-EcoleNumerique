<?php
/**
 * @package     
 * @subpackage
 * @author      
 */

/**
 *
 */
_classInclude('agenda|dateservices');
 
class ZoneCalendar extends CopixZone {

	function _createContent (& $toReturn) {

	  $ppo = new CopixPPO ();                               
    
    // Récupération des paramètres
    if ( is_null($ppo->nid = $this->getParam('nid'))
      || is_null($ppo->day = $this->getParam('date_day')) 
      || is_null($ppo->month = $this->getParam('date_month')) 
      || is_null($ppo->year  = $this->getParam('date_year'))) {
        
      $toReturn = '';
    	return;  
    }
    
    $service = new DateService;
    $ppo->month_name = $service->moisNumericToMoisLitteral($ppo->month);
    
    // Timestamp du 1er du mois sélectionné
    $time = mktime(0, 0, 0, $ppo->month, 1, $ppo->year);
    
    // Récupération du timestamp du mois suivant & précédent
    $ppo->prevMonth = strtotime("-1 month", $time);
    $ppo->nextMonth = strtotime("+1 month", $time);
    
    $ppo->daysInMonth = date('t', $time); // Nombre de jours dans le mois
    $ppo->firstDayOfWeek = date('w', $time); // Premier jour de la semaine (format numérique)
    
    if ($ppo->firstDayOfWeek == 0) {
      
      $ppo->firstDayOfWeek = 7;
    }
    
    $ppo->daycode = 1;

    $toReturn = $this->_usePPO ($ppo, '_calendar.ptpl');
  }
}