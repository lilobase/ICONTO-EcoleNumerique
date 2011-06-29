<?php
/**
* @package    Iconito
* @subpackage Classeur
* @author     Jérémy FOURNAISE
*/
class ZoneVueListe extends CopixZone {

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
    
    $dossierDAO = _ioDAO('classeur|classeurdossier');
		$fichierDAO = _ioDAO('classeur|classeurfichier');
		
		// Récupération des dossiers & des fichiers / favoris
		$ppo->dossiers = $dossierDAO->getEnfantsDirects($ppo->classeurId, $ppo->dossierId, array('colonne' => $ppo->tri['triDossiers'], 'direction' => $ppo->tri['triDirection']))->fetchAll();
		$ppo->fichiers = $fichierDAO->getParDossier($ppo->classeurId, $ppo->dossierId, array('colonne' => $ppo->tri['triFichiers'], 'direction' => $ppo->tri['triDirection']))->fetchAll();
		foreach ($ppo->fichiers as $cle => $fichier)
		{	
			$mime = classeurService::getTypeInfos ($fichier->type, $fichier->fichier);
			$ppo->fichiers[$cle]->type_text = $mime['type_text'];
		}
    
	  $toReturn = $this->_usePPO ($ppo, '_vue_liste.tpl');
  }
}