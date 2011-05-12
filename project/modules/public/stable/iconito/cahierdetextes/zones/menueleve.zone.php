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
 
class ZoneMenuEleve extends CopixZone {

	function _createContent (& $toReturn) {

	  $ppo = new CopixPPO ();                               
    
    // Récupération des paramètres    
    $ppo->nid = $this->getParam('nid');
    $jour     = $this->getParam('date_jour');
    $mois     = $this->getParam('date_mois');
    $annee    = $this->getParam('date_annee');
    
    // Timestamp de la date sélectionnée
    $ppo->dateSelectionnee = mktime(0, 0, 0, $mois, $jour, $annee);
    $numeroSemaine         = date('W', $ppo->dateSelectionnee);
    
    $service  = new DateService;
    
    // Récupération des timestamp des jours de la semaine
		$ppo->lun = $service->numweekToDate($numeroSemaine, $annee, 1);
		$ppo->mar = $service->numweekToDate($numeroSemaine, $annee, 2);
		$ppo->mer = $service->numweekToDate($numeroSemaine, $annee, 3);
		$ppo->jeu = $service->numweekToDate($numeroSemaine, $annee, 4);
		$ppo->ven = $service->numweekToDate($numeroSemaine, $annee, 5);
		$ppo->sam = $service->numweekToDate($numeroSemaine, $annee, 6);

    $ppo->semainePrecedente = strtotime("-1 week", $ppo->lun);
    $ppo->semaineSuivante   = strtotime("+1 week", $ppo->lun);

    $toReturn = $this->_usePPO ($ppo, '_menu_eleve.tpl');
  }
}