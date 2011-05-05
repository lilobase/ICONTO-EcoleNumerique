<?php
/**
 * @package     
 * @subpackage
 * @author      
 */

/**
 *
 */
 
class ZoneTeacherBar extends CopixZone {

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
    
    $toReturn = $this->_usePPO ($ppo, '_teacher_bar.tpl');
  }
}