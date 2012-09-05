<?php
/**
* @package    Iconito
* @subpackage Cahierdetextes
* @author     Jérémy FOURNAISE
*/
class ZoneMenuParent extends CopixZone
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

    // Récupération du nombre de mémo en attente de signature
    $memoDAO = _ioDAO ('cahierdetextes|cahierdetextesmemo');
    $ppo->nombreMemos = $memoDAO->retrieveNombreMemosNonSignesParEleve($ppo->eleve);

    $toReturn = $this->_usePPO ($ppo, '_menu_parent.tpl');
  }
}