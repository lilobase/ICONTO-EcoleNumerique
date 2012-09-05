<?php
/**
* @package    Iconito
* @subpackage Cahierdetextes
* @author     Jérémy FOURNAISE
*/
class ZoneMenuEnseignant extends CopixZone
{
    public function _createContent (& $toReturn)
    {
      $ppo = new CopixPPO ();

    // Récupération des paramètres
    $ppo->cahierId  = $this->getParam('cahierId');
    $ppo->jour      = $this->getParam('date_jour');
    $ppo->mois      = $this->getParam('date_mois');
    $ppo->annee     = $this->getParam('date_annee');
    $ppo->current   = $this->getParam('current');
    $ppo->vue       = $this->getParam('vue') ? $this->getParam('vue') : 'jour';

    $toReturn = $this->_usePPO ($ppo, '_menu_enseignant.tpl');
  }
}