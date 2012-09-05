<?php
/**
* @package    Iconito
* @subpackage Cahierdetextes
* @author     Jérémy FOURNAISE
*/
class ZoneListeDomaines extends CopixZone
{
    public function _createContent (& $toReturn)
    {
      $ppo = new CopixPPO ();

      // Récupération des paramètres
      $ppo->cahierId  = $this->getParam('cahierId');
      $ppo->jour      = $this->getParam('date_jour');
    $ppo->mois      = $this->getParam('date_mois');
    $ppo->annee     = $this->getParam('date_annee');

    $cahierInfos = Kernel::getModParent('MOD_CAHIERDETEXTES', $ppo->cahierId);

    // Récupération des domaines de la classe
      $domaineDAO = _ioDAO('cahierdetextes|cahierdetextesdomaine');
      $ppo->domaines = $domaineDAO->findByClasse($cahierInfos[0]->node_id);

      $toReturn = $this->_usePPO ($ppo, '_liste_domaines.tpl');
  }
}