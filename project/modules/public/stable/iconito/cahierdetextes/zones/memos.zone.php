<?php
/**
* @package    Iconito
* @subpackage Cahierdetextes
* @author     Jérémy FOURNAISE
*/
class ZoneMemos extends CopixZone
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
    $cahierInfos = Kernel::getModParent('MOD_CAHIERDETEXTES', $ppo->cahierId);
    $nodeId = isset($cahierInfos[0]) ? $cahierInfos[0]->node_id : null;
    $ppo->niveauUtilisateur = Kernel::getLevel('MOD_CAHIERDETEXTES', $ppo->cahierId);
    $estAdmin = $ppo->niveauUtilisateur >= PROFILE_CCV_PUBLISH ? true : false;

      // Récupération des mémos suivant les accès de l'utilisateur courant (élève / responsable / enseignant)
      $memoDAO = _ioDAO ('cahierdetextes|cahierdetextesmemo');
      if ($estAdmin) {

        $ppo->memos = $memoDAO->findByClasse($nodeId, true);
      } elseif (Kernel::getLevel('MOD_CAHIERDETEXTES', $ppo->cahierId) == PROFILE_CCV_READ) {

        $ppo->memos = $memoDAO->findByEleve($ppo->eleve, true);
      } else {

        $ppo->memos = $memoDAO->findByEleve(_currentUser()->getExtra('id'), true);
      }

      $toReturn = $this->_usePPO ($ppo, '_memos.tpl');
  }
}