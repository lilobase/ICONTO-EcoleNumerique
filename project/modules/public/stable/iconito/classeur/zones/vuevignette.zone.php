<?php
/**
* @package    Iconito
* @subpackage Classeur
* @author     Jérémy FOURNAISE
*/
class ZoneVueVignette extends CopixZone {

	function _createContent (& $toReturn) {
	  
	  $ppo = new CopixPPO ();
	  
	  _classInclude('classeur|ClasseurService');
	  
	  // Récupération des paramètres
	  $ppo->classeurId      = $this->getParam('classeurId');
	  $ppo->dossierId       = $this->getParam('dossierId');
	  
	  // Gestion des droits
	  $ppo->niveauUtilisateur = Kernel::getLevel('MOD_CLASSEUR', $ppo->classeurId);
	  $ppo->typeUtilisateur   = _currentUser()->getExtra('type');
	  $ppo->idUtilisateur     = _currentUser()->getExtra('id');

    // Récupération des paramètres d'affichages
    $ppo->tri = ClasseurService::getContentSort ();
		
		// Récupération des dossiers & des fichiers / favoris
		$fichierDAO = _ioDAO('classeur|classeurfichier');
		$dossierDAO = _ioDAO('classeur|classeurdossier');
		
		if ($dossier = $dossierDAO->get($ppo->dossierId)) {
		  
		  if ($dossier->parent_id != 0) {
		   
		    $ppo->dossierParent = $dossierDAO->get($dossier->parent_id); 
		  }
		  else {
		    
		    $classeurDAO = _ioDAO('classeur|classeur');
		    $ppo->classeurParent = $classeurDAO->get($ppo->classeurId);
		    
		    $ppo->classeurParent->isPersonnel = ClasseurService::getClasseurPersonnelId () == $ppo->classeurParent->id;
		  }
		}
		
		$imgTypes = array('PNG', 'GIF', 'JPG', 'JPEG', 'png', 'gif', 'jpg', 'jpeg');
		$ppo->contenus = $dossierDAO->getContenus($ppo->classeurId, $ppo->dossierId, $ppo->tri);
		foreach ($ppo->contenus as $contenu) {
		  
		  if ($contenu->content_type == 'fichier' && in_array($contenu->type, $imgTypes)) {
		    
		    $fichier = $fichierDAO->get($contenu->id);
		    $contenu->lienMiniature = $fichier->getLienMiniature (90);
		  }
		}
		
	  $toReturn = $this->_usePPO ($ppo, '_vue_vignette.tpl');
  }
}