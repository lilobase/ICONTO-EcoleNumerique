<?php
/**
* @package    Iconito
* @subpackage Classeur
* @author     Jérémy FOURNAISE
*/
class ZoneArborescenceDossiers extends CopixZone
{
    public function _createContent (& $toReturn)
    {
      $ppo = new CopixPPO ();

      // Récupération des paramètres
      $ppo->classeurId      = $this->getParam('classeurId');
      $ppo->dossierId       = $this->getParam('dossierId', null);
      $ppo->dossierCourant  = $this->getParam('dossierCourant');

      // Paramètres pour la vue popup
      $ppo->field           = $this->getParam('field');
      $ppo->format          = $this->getParam('format');
      $ppo->withPersonal    = $this->getParam('withPersonal', true);
      $ppo->moduleType      = $this->getParam('moduleType', null);
      $ppo->moduleId        = $this->getParam('moduleId', null);

      $ppo->estAdmin = Kernel::getLevel('MOD_CLASSEUR', $ppo->classeurId) >= PROFILE_CCV_PUBLISH;
      $ppo->withLockers = $ppo->estAdmin || is_null ($ppo->dossierId);

      // Récupération des dossiers
      $dossierDAO = _ioDAO('classeur|classeurdossier');
      $ppo->dossiers = $dossierDAO->getEnfantsDirects($ppo->classeurId, $ppo->dossierId, $ppo->withLockers);

    _classInclude('classeurservice');
    $ppo->dossiersOuverts = ClasseurService::getFoldersTreeState ();
    if (!is_array($ppo->dossiersOuverts)) {

      $ppo->dossiersOuverts = array();
    }

      $toReturn = $this->_usePPO ($ppo, '_arborescence_dossiers.tpl');
  }
}