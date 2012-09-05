<?php
/**
* @package    Iconito
* @subpackage Cahierdetextes
* @author     Jérémy FOURNAISE
*/
class ZoneTravauxAFaire extends CopixZone
{
    public function _createContent (& $toReturn)
    {
      $ppo = new CopixPPO ();

      // Récupération des paramètres
      $ppo->cahierId  = $this->getParam('cahierId');
      $ppo->jour      = $this->getParam('date_jour');
      $ppo->mois      = $this->getParam('date_mois');
      $ppo->annee     = $this->getParam('date_annee');
      $ppo->vue       = $this->getParam('vue');
      $ppo->eleve     = $this->getParam('eleve');

    $time = mktime(0, 0, 0, $ppo->mois, $ppo->jour, $ppo->annee);

    $cahierInfos = Kernel::getModParent('MOD_CAHIERDETEXTES', $ppo->cahierId);
    $nodeId = isset($cahierInfos[0]) ? $cahierInfos[0]->node_id : null;
    $ppo->niveauUtilisateur = Kernel::getLevel('MOD_CAHIERDETEXTES', $ppo->cahierId);
    $ppo->estAdmin = $ppo->niveauUtilisateur >= PROFILE_CCV_PUBLISH ? true : false;

      // Récupération des travaux suivant les accès de l'utilisateur courant (enseignant / eleve - responsable)
      $travailDAO = _ioDAO ('cahierdetextes|cahierdetextestravail');
      if ($ppo->estAdmin) {

        $ppo->travaux = $travailDAO->findByClasseEtTypeDeTravail($nodeId, DAOCahierDeTextesTravail::TYPE_A_FAIRE, $time);
      } elseif ($ppo->niveauUtilisateur == PROFILE_CCV_READ) {

        $ppo->travaux = $travailDAO->findByEleveEtTypeDeTravail($ppo->eleve, DAOCahierDeTextesTravail::TYPE_A_FAIRE, $time);
      } else {

        $ppo->travaux = $travailDAO->findByEleveEtTypeDeTravail(_currentUser()->getExtra('id'), DAOCahierDeTextesTravail::TYPE_A_FAIRE, $time);
      }

      $toReturn = $this->_usePPO ($ppo, '_travaux_a_faire.tpl');
  }
}