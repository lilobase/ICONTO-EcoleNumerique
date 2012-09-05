<?php
/**
* @package    Iconito
* @subpackage Cahierdetextes
* @author     Jérémy FOURNAISE
*/
class ZoneAffichageFichiers extends CopixZone
{
    public function _createContent (& $toReturn)
    {
      $ppo = new CopixPPO ();

      // Récupération des paramètres
      $nodeType = $this->getParam('nodeType');
      $nodeId   = $this->getParam('nodeId');

      $fichierMalleDAO     = _ioDAO('malle|malle_files');
    $fichierClasseurDAO  = _ioDAO('classeur|classeurfichier');

      $fichiers = array();
      if ($nodeType == 'travail') {

        // Récupération des liens travail - fichiers
        $travail2fichiersDAO  = _ioDAO ('cahierdetextes|cahierdetextestravail2files');
        $fichiers             = $travail2fichiersDAO->retrieveByTravail ($nodeId);
      } elseif ($nodeType == 'memo') {

        // Récupération des liens mémo - fichiers
        $memo2fichiersDAO   = _ioDAO ('cahierdetextes|cahierdetextesmemo2files');
        $fichiers           = $memo2fichiersDAO->retrieveByMemo ($nodeId);
      }

      // Récupération des fichiers
      $ppo->fichiers = array();
    foreach ($fichiers as $fichier) {

      if ($fichier->module_file == 'MOD_MALLE') {

        if ($file = $fichierMalleDAO->get($fichier->file_id)) {

          $ppo->fichiers[] = $file;
        }
      } elseif ($fichier->module_file == 'MOD_CLASSEUR') {

        if ($file = $fichierClasseurDAO->get($fichier->file_id)) {

          $ppo->fichiers[] = $file;
        }
      }
    }

    $toReturn = $this->_usePPO ($ppo, '_affichage_fichiers.tpl');
  }
}