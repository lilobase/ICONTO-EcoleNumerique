<?php
/**
* @package    Iconito
* @subpackage Cahierdetextes
* @author     Jérémy FOURNAISE
*/
class ZoneCalendrier extends CopixZone
{
    public function _createContent (& $toReturn)
    {
    _classInclude('agenda|dateservices');

      $ppo = new CopixPPO ();

    // Récupération des paramètres
    $ppo->cahierId  = $this->getParam('cahierId');
    $ppo->jour      = $this->getParam('date_jour');
    $ppo->mois      = $this->getParam('date_mois');
    $ppo->annee     = $this->getParam('date_annee');
    $ppo->eleve     = $this->getParam('eleve');

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

    // Récupération des travaux suivant les accès de l'utilisateur courant (élève / responsable / enseignant)
    $cahierInfos = Kernel::getModParent('MOD_CAHIERDETEXTES', $ppo->cahierId);
    $nodeId = isset($cahierInfos[0]) ? $cahierInfos[0]->node_id : null;
    $ppo->estAdmin = Kernel::getLevel('MOD_CAHIERDETEXTES', $ppo->cahierId) >= PROFILE_CCV_PUBLISH ? true : false;

    $travailDAO = _ioDAO ('cahierdetextes|cahierdetextestravail');
      if ($ppo->estAdmin) {

        $ppo->travaux = $travailDAO->findByClasseEtMoisParJour($nodeId, $ppo->mois, $ppo->annee);
      } elseif (Kernel::getLevel('MOD_CAHIERDETEXTES', $ppo->cahierId) == PROFILE_CCV_READ) {

        $ppo->travaux = $travailDAO->findByEleveEtMoisParJour($ppo->eleve, $ppo->mois, $ppo->annee);
      } else {

        $ppo->travaux = $travailDAO->findByEleveEtMoisParJour(_currentUser()->getExtra('id'), $ppo->mois, $ppo->annee);
      }

    $toReturn = $this->_usePPO ($ppo, '_calendrier.ptpl');
  }
}