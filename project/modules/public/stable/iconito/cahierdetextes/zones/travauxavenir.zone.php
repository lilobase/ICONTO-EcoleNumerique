<?php
/**
* @package    Iconito
* @subpackage Cahierdetextes
* @author     Jérémy FOURNAISE
*/
class ZoneTravauxAVenir extends CopixZone
{
    public function _createContent (& $toReturn)
    {
      $ppo = new CopixPPO ();

      // Récupération des paramètres
      $ppo->cahierId  = $this->getParam('cahierId');
      $ppo->jour      = $this->getParam('date_jour');
      $ppo->mois      = $this->getParam('date_mois');
      $ppo->annee     = $this->getParam('date_annee');
      $ppo->eleve     = $this->getParam('eleve');

    $time = mktime(0, 0, 0, $ppo->mois, $ppo->jour, $ppo->annee);

    $cahierInfos            = Kernel::getModParent('MOD_CAHIERDETEXTES', $ppo->cahierId);
    $estAdmin               = Kernel::getLevel('MOD_CAHIERDETEXTES', $ppo->cahierId) >= PROFILE_CCV_PUBLISH ? true : false;
      $ppo->niveauUtilisateur = Kernel::getLevel('MOD_CAHIERDETEXTES', $ppo->cahierId);
      $nodeId                 = isset($cahierInfos[0]) ? $cahierInfos[0]->node_id : null;

      // Récupération des travaux suivant les accès de l'utilisateur courant (élève / responsable / enseignant)
      $travailDAO = _ioDAO ('cahierdetextes|cahierdetextestravail');
      if ($estAdmin) {

        $ppo->travaux = $travailDAO->findTravauxAVenirParClasse($nodeId);
      } elseif ($ppo->niveauUtilisateur == PROFILE_CCV_READ) {

      $ppo->travaux = $travailDAO->findTravauxAVenirParEleve($ppo->eleve);
    } else {

        $ppo->travaux = $travailDAO->findTravauxAVenirParEleve(_currentUser()->getExtra('id'));
      }

      $toReturn = $this->_usePPO ($ppo, '_travaux_a_venir.tpl');
  }
}