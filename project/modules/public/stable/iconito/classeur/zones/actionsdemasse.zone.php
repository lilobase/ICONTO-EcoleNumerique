<?php
/**
* @package    Iconito
* @subpackage Classeur
* @author     Jérémy FOURNAISE
*/
class ZoneActionsDeMasse extends CopixZone
{
    public function _createContent (& $toReturn)
    {
      $ppo = new CopixPPO ();

      // Récupération des paramètres
      $ppo->classeurId          = $this->getParam('classeurId');
      $ppo->dossierId           = $this->getParam('dossierId');
      $ppo->idClasseurPersonnel = _sessionGet('classeur|idClasseurPersonnel');

      // Gestion des droits
      $ppo->niveauUtilisateur = Kernel::getLevel('MOD_CLASSEUR', $ppo->classeurId);

      // Récupération du dossier courant
      $dossierDAO = _ioDAO('classeur|classeurdossier');
      $ppo->dossier = $dossierDAO->get($ppo->dossierId);

      $toReturn = $this->_usePPO ($ppo, '_actions_de_masse.tpl');
  }
}