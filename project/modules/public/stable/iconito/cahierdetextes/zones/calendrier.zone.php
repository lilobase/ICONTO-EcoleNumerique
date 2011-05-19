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
 
class ZoneCalendrier extends CopixZone {

	function _createContent (& $toReturn) {

	  $ppo = new CopixPPO ();                               
    
    // Récupération des paramètres
    $ppo->nid   = $this->getParam('nid');
    $ppo->jour  = $this->getParam('date_jour');
    $ppo->mois  = $this->getParam('date_mois');
    $ppo->annee = $this->getParam('date_annee');
    
    $service = new DateService;
    $ppo->nomMois = $service->moisNumericToMoisLitteral($ppo->mois);
    
    // Timestamp du 1er du mois sélectionné
    $time = mktime(0, 0, 0, $ppo->mois, 1, $ppo->annee);
    
    // Récupération du timestamp du mois suivant & précédent
    $ppo->moisPrecedent = strtotime("-1 month", $time);
    $ppo->moisSuivant   = strtotime("+1 month", $time);
    
    $ppo->nombreDeJoursDansLeMois     = date('t', $time); // Nombre de jours dans le mois
    $ppo->codePremierJourDeLaSemaine  = date('w', $time); // Premier jour de la semaine (format numérique)
    
    if ($ppo->codePremierJourDeLaSemaine == 0) {
      
      $ppo->codePremierJourDeLaSemaine = 7;
    }
    
    $ppo->codePremierJourSemaine = 1;
    
    $ppo->typeUtilisateur = _currentUser()->getExtra('type');
    $travailDAO = _ioDAO ('cahierdetextes|cahierdetextestravail');
	  if ($ppo->typeUtilisateur == 'USER_ELE') {
	    
	    $ppo->travaux = $travailDAO->findByEleveEtMoisParJour(_currentUser()->getExtra('id'), $ppo->mois, $ppo->annee);
	  }
	  elseif ($ppo->typeUtilisateur == 'USER_RES') {
	    
	    $ppo->travaux = $travailDAO->findByEleveEtMoisParJour($ppo->nid, $ppo->mois, $ppo->annee);
	  }
	  elseif ($ppo->typeUtilisateur == 'USER_ENS') {
	    
	    $ppo->travaux = $travailDAO->findByClasseEtMoisParJour($ppo->nid, $ppo->mois, $ppo->annee);
	  }

    $toReturn = $this->_usePPO ($ppo, '_calendrier.ptpl');
  }
}