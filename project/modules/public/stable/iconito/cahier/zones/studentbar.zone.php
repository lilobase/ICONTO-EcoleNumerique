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
 
class ZoneStudentBar extends CopixZone {

	function _createContent (& $toReturn) {

	  $ppo = new CopixPPO ();                               
    
    // Récupération des paramètres    
    if ( is_null($ppo->nid = $this->getParam('nid'))
      || is_null($day = $this->getParam('date_day'))
      || is_null($month = $this->getParam('date_month'))
      || is_null($year  = $this->getParam('date_year'))) {
        
      $toReturn = '';
      return;  
    }
    
    // Timestamp de la date sélectionnée
    $ppo->selectedDate = mktime(0, 0, 0, $month, $day, $year);
    $week_number       = date('W', $ppo->selectedDate);
    
    $service  = new DateService;
    
    // Récupération des timestamp des jours de la semaine
		$ppo->mon = $service->numweekToDate($week_number, $year, 1);
		$ppo->tue = $service->numweekToDate($week_number, $year, 2);
		$ppo->wed = $service->numweekToDate($week_number, $year, 3);
		$ppo->thu = $service->numweekToDate($week_number, $year, 4);
		$ppo->fri = $service->numweekToDate($week_number, $year, 5);
		$ppo->sat = $service->numweekToDate($week_number, $year, 6);

    $ppo->prevWeek = strtotime("-1 week", $ppo->mon);
    $ppo->nextWeek = strtotime("+1 week", $ppo->mon);

    $toReturn = $this->_usePPO ($ppo, '_student_bar.tpl');
  }
}