<?php
/**
* @package    Iconito
* @subpackage Classeur
* @author     Jérémy FOURNAISE
*/
class ZoneAffichageMenu extends CopixZone
{
    public function _createContent (& $toReturn)
    {
      $ppo = new CopixPPO ();

      // Récupération des paramètres
      $ppo->classeurId = $this->getParam('classeurId');
      $ppo->dossierId  = $this->getParam('dossierId');
      $ppo->current    = $this->getParam('current');

      // Gestion des droits
      $ppo->niveauUtilisateur = Kernel::getLevel('MOD_CLASSEUR', $ppo->classeurId);
      $ppo->typeUtilisateur   = _currentUser()->getExtra('type');

      $ppo->vue = !is_null(_sessionGet('classeur|typeVue')) ? _sessionGet('classeur|typeVue') : 'liste';

      $ppo->conf_ModClasseur_options = (CopixConfig::exists ('default|conf_ModClasseur_options')) ? CopixConfig::get ('default|conf_ModClasseur_options') : 0;

      // L'album public est t-il publié ?
      $ppo->estPublic = false;
      if (!is_null($ppo->dossierId) && $ppo->dossierId != 0) {

        $dossierDAO = _ioDAO('classeur|classeurdossier');
        $ppo->dossier = $dossierDAO->get($ppo->dossierId);
        if ($ppo->dossier->public) {

          $ppo->estPublic = true;
        }
      } else {

        $classeurDAO = _ioDAO('classeur|classeur');
        $classeur = $classeurDAO->get($ppo->classeurId);
        if ($classeur->public) {

          $ppo->estPublic = true;
        }
      }

      $toReturn = $this->_usePPO ($ppo, '_affichage_menu.tpl');
  }
}
