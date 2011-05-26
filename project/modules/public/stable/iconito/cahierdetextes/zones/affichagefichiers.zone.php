<?php
/**
* @package    Iconito
* @subpackage Cahierdetextes
* @author     Jérémy FOURNAISE
*/
class ZoneAffichageFichiers extends CopixZone {

	function _createContent (& $toReturn) {

	  $ppo = new CopixPPO ();
	  
	  // Récupération des paramètres
	  $nodeType = $this->getParam('nodeType');
	  $nodeId   = $this->getParam('nodeId');
	  
	  $filesDAO = _ioDAO('malle|malle_files');
	  
	  $fichiers = array();
	  if ($nodeType == 'travail') {
	    
	    // Récupération des liens travail - fichiers
  	  $travail2fichiersDAO  = _ioDAO ('cahierdetextes|cahierdetextestravail2files');
  	  $fichiers             = $travail2fichiersDAO->retrieveByTravail ($nodeId);
	  }
	  elseif ($nodeType == 'memo') {
	    
	    // Récupération des liens mémo - fichiers
  	  $memo2fichiersDAO   = _ioDAO ('cahierdetextes|cahierdetextesmemo2files');
  	  $fichiers           = $memo2fichiersDAO->retrieveByMemo ($nodeId);
	  }
	  
	  // Récupération des fichiers
	  $ppo->fichiers = array();
    foreach ($fichiers as $fichier) {

      if ($file = $filesDAO->get($fichier->file_id)) {

        $ppo->fichiers[] = $file;
      }
    }
    
    $toReturn = $this->_usePPO ($ppo, '_affichage_fichiers.tpl');
  }
}