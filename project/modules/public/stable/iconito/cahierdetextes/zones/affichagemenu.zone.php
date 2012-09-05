<?php
/**
* @package    Iconito
* @subpackage Cahierdetextes
* @author     Jérémy FOURNAISE
*/
class ZoneAffichageMenu extends CopixZone
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
    $ppo->current   = $this->getParam('current');
    $ppo->vue       = $this->getParam('vue');

    $cahierInfos = Kernel::getModParent('MOD_CAHIERDETEXTES', $ppo->cahierId);
    $ppo->estAdmin = Kernel::getLevel('MOD_CAHIERDETEXTES', $ppo->cahierId) >= PROFILE_CCV_PUBLISH ? true : false;

    $toReturn = $this->_usePPO ($ppo, '_affichage_menu.tpl');
  }
}