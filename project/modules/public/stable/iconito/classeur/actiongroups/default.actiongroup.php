<?php

/**
* @package    Iconito
* @subpackage Classeur
* @author     Jérémy FOURNAISE
*/

class ActionGroupDefault extends enicActionGroup {
  
  public function beforeAction ($actionName) {
    
    $this->addJs('js/iconito/module_classeur.js');
  }
  
  /**
	 * Affichage principal
	 */
  public function processVoirContenu () {
    
    $ppo = new CopixPPO ();
    
    _classInclude('classeur|classeurService');
    
    // Récupération des paramètres
    $ppo->classeurId    = _request('classeurId', null);
    $ppo->dossierId     = _request('dossierId', 0);
    $ppo->success       = _request('success', null);
    $ppo->vue           = _request('vue', 'liste');
    
    // Paramètres de tri
    $triDossiers   = _request('triDossiers', null);
    $triFichiers   = _request('triFichiers', null);
    $triDirection  = _request('triDirection', 'ASC');
    
    // Si l'ID de classeur n'est pas spécifié, récupération du classeur perso
		if (is_null($ppo->classeurId)) {
		  
			$userInfo = Kernel::getUserInfo();
			Kernel::createMissingModules ($userInfo["type"], $userInfo["id"]);
			$modsList = Kernel::getModEnabled ($userInfo["type"], $userInfo["id"]);
			foreach ($modsList as $modInfo) {
			  
				if ($modInfo->module_type == "MOD_CLASSEUR" && $modInfo->module_id) {
				  
					$urlReturn = CopixUrl::get ('classeur||voirContenu', array('classeurId' => $modInfo->module_id));
					return new CopixActionReturn (COPIX_AR_REDIRECT, $urlReturn);
				}
			}
		}
		
		if (!is_null ($triDossiers) || !is_null ($triFichiers)) {
		  
		  ClasseurService::setContentSort ($triDossiers, $triFichiers, $triDirection);
		}

    return _arPPO ($ppo, 'voir_contenu.tpl');
  }
  
  /**
	 * DOSSIER - Edition d'un dossier
	 */
  public function processEditerDossier () {
    
    $ppo = new CopixPPO ();
    
    if (is_null($ppo->classeurId  = _request ('classeurId'))) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message' => CopixI18N::get ('kernel|kernel.error.errorOccurred'), 'back' => CopixUrl::get('')));
	  }

    // Récupération du dossier
    $dossierDAO = _ioDAO('classeur|classeurdossier');
    if (!is_null($parentId  = _request('parentId', null)) && $parentId != 0) {
      
      $ppo->parent  = $dossierDAO->get($parentId);
      $ppo->path    = $ppo->parent->getPath();
    }
    else {
      
      $classeurDAO  = _ioDAO('classeur|classeur');
      $classeur     = $classeurDAO->get($ppo->classeurId);
      $ppo->path    = '/'.$classeur->titre.'/';
    }
    
    if (!is_null($dossierId = _request('dossierId', null))) {
      
      $ppo->dossier = $dossierDAO->get($dossierId);
    }
    else {
      
      $ppo->dossier = _record ('classeur|classeurdossier');
    }
    
    if (CopixRequest::isMethod ('post')) {
  	  
      $ppo->dossier->classeur_id    = $ppo->classeurId;
      $ppo->dossier->parent_id      = _request('parentId', 0);
      $ppo->dossier->nom            = _request('dossier_nom', null);
      $ppo->dossier->date_creation  = date('Y-m-d H:i:s');
      $ppo->dossier->user_type      = _currentUser()->getExtra('type');
      $ppo->dossier->user_id        = _currentUser()->getExtra('id');
      
      // Traitement des erreurs
      $ppo->erreurs = array ();

      if ($ppo->dossier->nom == '') {

        $ppo->erreurs[] = CopixI18N::get ('classeur|classeur.error.noName');
      }
      
      if (!empty ($ppo->erreurs)) {
        
        return _arPPO ($ppo, array ('template' => 'editer_dossier.tpl'));
      }
      
      if ($ppo->dossier->id == '') {
        
        // Insertion de l'enregistrement "dossier"
        $dossierDAO->insert ($ppo->dossier);
      }
      else {
        
        // Mise à jour de l'enregistrement "dossier"
        $dossierDAO->update ($ppo->dossier);
      }
      
      return _arRedirect (CopixUrl::get ('classeur||voirContenu', array('classeurId' => $ppo->classeurId, 'dossierId' => $ppo->parent->id, 'success' => true)));
    }
    
    return _arPPO ($ppo, array ('template' => 'editer_dossier.tpl', 'mainTemplate' => 'main|main_fancy.php'));
  }
  
  /**
	 * DOSSIER - Déplacement d'un dossier
	 */
  public function processDeplacerDossier () {
    
    $dossierDAO = _ioDAO('classeur|classeurdossier');
 	  $fichierDAO = _ioDAO('classeur|classeurfichier');
 	  
    if (is_null($ppo->classeurId  = _request ('classeurId', null))
     || is_null($ppo->parentId    = _request('parentId', null))
     || is_null($ppo->dossier     = $dossierDAO->get (_request ('dossierId', null)))) {
       
      return CopixActionGroup::process ('generictools|Messages::getError',
   		  array ('message' => CopixI18N::get ('kernel|kernel.error.errorOccurred'), 'back' => CopixUrl::get('')));
 	  }
 	  
 	  if (CopixRequest::isMethod ('post')) {
    
      _classInclude('classeur|classeurservice');

      $destination        = !is_null(_request('destination', null)) ? explode('-', _request('destination', null)) : null;
      if (is_array($destination) && !empty($destination)) {
        
        $ppo->destinationType    = $destination[0];
        $ppo->destinationId      = $destination[1];
        
        if ($ppo->destinationType == 'dossier') {
          
          $dossierDestination = $dossierDAO->get($ppo->destinationId);
        }
      }
      
      // Traitement des erreurs
      $ppo->erreurs = array ();
      if (!isset($ppo->destinationType) || !isset($ppo->destinationId) || is_null($ppo->destinationType) || is_null($ppo->destinationId)) {
          
        $ppo->erreurs[] = CopixI18N::get ('classeur|classeur.error.noDestination');
      }
      elseif ($ppo->destinationType == 'dossier' && classeurService::isDescendantOf($dossierDestination, $ppo->dossier)) {

        $ppo->erreurs[] = CopixI18N::get ('classeur|classeur.error.destinationUnauthorized');
      }
      
      if (!empty ($ppo->erreurs)) {
        
        return _arPPO ($ppo, array ('template' => 'deplacer_dossier.tpl'));
      }
      
      // Déplacement des dossiers
      classeurService::moveFolder($ppo->dossier, $ppo->destinationType, $ppo->destinationId);
      
      // Redirection
      if ($ppo->destinationType == 'dossier') {
        
        return _arRedirect (CopixUrl::get ('classeur||voirContenu', array('classeurId' => $dossierDestination->classeur_id, 'dossierId' => $ppo->destinationId, 'success' => true)));
      }
      else {
        
        return _arRedirect (CopixUrl::get ('classeur||voirContenu', array('classeurId' => $ppo->destinationId, 'success' => true)));
      }
    }
 	  
 	  return _arPPO ($ppo, array ('template' => 'deplacer_dossier.tpl'));
  }
  
  /**
	 * DOSSIER - Suppression d'un dossier
	 */
  public function processSupprimerDossier () {
    
    if (is_null($classeurId = _request ('classeurId'))
     || is_null($dossierId  = _request ('dossierId'))) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message' => CopixI18N::get ('kernel|kernel.error.errorOccurred'), 'back' => CopixUrl::get('')));
	  }
	  
	  _classInclude('classeur|classeurservice');
	  $dossierDAO = _ioDAO ('classeur|classeurdossier');
	  $fichierDAO = _ioDAO ('classeur|classeurfichier');
	  
	  // Récupération du dossier à supprimer
	  $dossier  = $dossierDAO->get($dossierId);
	  $parentId = $dossier->parent_id;
	  
	  // Suppression
	  classeurService::deleteFolder($dossier, true);
    
    return _arRedirect (CopixUrl::get ('classeur||voirContenu', array('classeurId' => $classeurId, 'dossierId' => $parentId, 'success' => true)));
  }
  
  /**
	 * FICHIER - Edition de fichiers
	 */
  public function processEditerFichiers () {
    
    $ppo = new CopixPPO ();
    
    if (is_null($ppo->classeurId  = _request ('classeurId'))) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message' => CopixI18N::get ('kernel|kernel.error.errorOccurred'), 'back' => CopixUrl::get('')));
	  }
	  
	  // Récupération de l'identifiant du fichier (si modification)
    $fichierId = _request('fichierId', null);
    if (!is_null($fichierId)) {
      
      $fichierDAO = _ioDAO('classeur|classeurfichier');
      $ppo->fichier = $fichierDAO->get($fichierId);
    }
    else {
      
      // Chargement JS pour uploadify
  	  $this->addJs('js/uploadify/swfobject.js');
  	  $this->addJs('js/uploadify/jquery.uploadify.v2.1.4.min.js');
    }
    
    // Récupération du classeur
    $classeurDAO = _ioDAO('classeur|classeur');
    $classeur = $classeurDAO->get($ppo->classeurId);
    
    // Récupération du dossier pour définition du path
    $dossierDAO = _ioDAO('classeur|classeurdossier');
    if (!is_null($ppo->dossierId  = _request('dossierId', null)) && $ppo->dossierId != 0) {
      
      $ppo->dossier = $dossierDAO->get($ppo->dossierId);
      $ppo->path = $ppo->dossier->getPath();
    }
    else {
      
      $ppo->path = '/'.$classeur->titre.'/';
    }
    
    // Dossier temporaire pour l'upload des fichiers
    _classInclude('classeur|classeurService');
    
    $dossierTmp = sys_get_temp_dir();
    if (substr($dossierTmp, -1) != '/') {
      
      $dossierTmp = $dossierTmp.'/';
    }
    $ppo->dossierTmp = $dossierTmp.classeurService::createKey();
    
    
    // Soumission du formulaire
    if (CopixRequest::isMethod ('post')) {
      
      // S'il s'agit d'une modification de fichier
      if (!is_null(_request('fichierId', null))) {
        
        // Contrôle upload du fichier
        if (is_uploaded_file($_FILES['fichiers']['tmp_name'][0])) {
          
          $dir = $_SERVER['DOCUMENT_ROOT'].'static/classeur/'.$classeur->id.'-'.$classeur->cle.'/';
          $extension = strrchr($ppo->fichier->fichier, '.');
  				$fichierPhysique = $dir.$ppo->fichier->id.'-'.$ppo->fichier->cle.$extension;
  				// Suppression de l'ancien fichier
  				if (file_exists($fichierPhysique)) {
  				  
  					unlink($fichierPhysique);
  				}
  				
  				$ppo->fichier->fichier       = $_FILES['fichiers']['name'][0];
          $ppo->fichier->taille        = filesize($_FILES['fichiers']['tmp_name'][0]);
          $ppo->fichier->type          = classeurService::getMimeType($_FILES['fichiers']['name'][0]);
          
          $extension = strrchr($_FILES['fichiers']['name'][0], '.');
  				$fichierPhysique = $dir.$ppo->fichier->id.'-'.$ppo->fichier->cle.$extension;
  				move_uploaded_file ($_FILES['fichiers']['tmp_name'][0], $fichierPhysique);
  			}
  			
  			$ppo->fichier->titre         = _request('fichier_titre', null);
        $ppo->fichier->commentaire   = _request('fichier_commentaire', null);
        
        // Mise à jour de l'enregistrement fichier
        $fichierDAO->update($ppo->fichier);
      }
      // Sinon, upload multiple
      else {
        
        // Récupération du dossier temporaire
        $ppo->dossierTmp = _request('dossierTmp', null);
        
        // Contrôle : upload effectué / dossier temporaire créé ?
        $fichiersPhysiques = array();
        if (!is_dir($ppo->dossierTmp)) {
          
          $ppo->erreurs[] = CopixI18N::get ('classeur|classeur.error.noFiles');
        }
        elseif ($handle = opendir($ppo->dossierTmp)) {
          
          while (($file = readdir($handle)) !== false) {
            
            if ($file != '.' && $file != '..') {

              $fichiersPhysiques[] = $file;
            }
          }
        }
        
        // Traitement des erreurs
        $ppo->erreurs = array ();
         
        if (empty($fichiersPhysiques)) {
          
          $ppo->erreurs[] = CopixI18N::get ('classeur|classeur.error.noFiles');
        }
        if (_request('fichier_titre', null) == '') {

          $ppo->erreurs[] = CopixI18N::get ('classeur|classeur.error.noTitle');
        }

        if (!empty ($ppo->erreurs)) {

          return _arPPO ($ppo, array ('template' => 'editer_fichiers.tpl'));
        }

        // Création du répertoire
        $dir = $_SERVER['DOCUMENT_ROOT'].'static/classeur/'.$classeur->id.'-'.$classeur->cle.'/';
        if (!file_exists($dir)) {
          
          mkdir($dir, 0755, true);
        }
        
        // Copie des fichiers dans le rep classeur et suppression des fichiers TMP
        $fichierDAO = _ioDAO('classeur|classeurfichier');
        foreach ($fichiersPhysiques as $fichierPhysique) {
        
          $fichier = _record('classeur|classeurfichier');
        
          $fichier->classeur_id   = $classeur->id;
          $fichier->dossier_id    = isset($ppo->dossierId) ? $ppo->dossierId : 0;
          $fichier->titre         = _request('fichier_titre', null);
          $fichier->commentaire   = _request('fichier_commentaire', null);
          $fichier->fichier       = $fichierPhysique;
          $fichier->taille        = filesize($ppo->dossierTmp.'/'.$fichierPhysique);
          $fichier->type          = classeurService::getMimeType($fichierPhysique);
          $fichier->cle           = classeurService::createKey();
          $fichier->date_upload   = date('Y-m-d H:i:s');
          $fichier->user_type     = _currentUser()->getExtra('type');
          $fichier->user_id       = _currentUser()->getExtra('id');
        
          $fichierDAO->insert($fichier);
        
          $nomClasseur = $classeur->id.'-'.$classeur->cle;
          $nomFichier = $fichier->id.'-'.$fichier->cle;
          $extension = strrchr($fichierPhysique, '.');
        
          // Déplacement du fichier temporaire dans le classeur
          copy($ppo->dossierTmp.'/'.$fichierPhysique, $dir.$fichier->id.'-'.$fichier->cle.$extension);
          
          // Suppression du fichier temporaire
          unlink($ppo->dossierTmp.'/'.$fichierPhysique);
        }

        // Suppression du dossier TMP
        rmdir($ppo->dossierTmp);
      }
      
      return _arRedirect (CopixUrl::get ('classeur||voirContenu', array('classeurId' => $ppo->classeurId, 'dossierId' => $ppo->dossierId, 'success' => true)));
    }
    
    return _arPPO ($ppo, array ('template' => 'editer_fichiers.tpl'));
  }
  
  /**
	 * FICHIER - Déplacement d'un fichier
	 */
  public function processDeplacerFichier () {
    
    $dossierDAO = _ioDAO('classeur|classeurdossier');
 	  $fichierDAO = _ioDAO('classeur|classeurfichier');
 	  
    if (is_null($ppo->classeurId  = _request ('classeurId', null))
     || is_null($ppo->dossierId   = _request ('dossierId', null))
     || is_null($ppo->fichier     = $fichierDAO->get(_request ('fichierId', null)))) {
       
      return CopixActionGroup::process ('generictools|Messages::getError',
   		  array ('message' => CopixI18N::get ('kernel|kernel.error.errorOccurred'), 'back' => CopixUrl::get('')));
 	  }
 	  
 	  if (CopixRequest::isMethod ('post')) {
    
      _classInclude('classeur|classeurservice');
      
      $destination        = !is_null(_request('destination', null)) ? explode('-', _request('destination', null)) : null;
      if (is_array($destination) && !empty($destination)) {
        
        $ppo->destinationType    = $destination[0];
        $ppo->destinationId      = $destination[1];
        
        if ($ppo->destinationType == 'dossier') {
          
          $dossierDestination = $dossierDAO->get($ppo->destinationId);
        }
      }
      
      // Traitement des erreurs
      $ppo->erreurs = array ();
      if (!isset($ppo->destinationType) || !isset($ppo->destinationId) || is_null($ppo->destinationType) || is_null($ppo->destinationId)) {
          
        $ppo->erreurs[] = CopixI18N::get ('classeur|classeur.error.noDestination');
      }
      elseif ($ppo->destinationType == 'dossier' && $ppo->destinationId == $ppo->fichier->dossier_id) {

        $ppo->erreurs[] = CopixI18N::get ('classeur|classeur.error.destinationUnauthorized');
      }
      
      if (!empty ($ppo->erreurs)) {
        
        return _arPPO ($ppo, array ('template' => 'deplacer_fichier.tpl'));
      }
      
      // Déplacement du fichier
      classeurService::moveFile($ppo->fichier, $ppo->destinationType, $ppo->destinationId);
      
      // Redirection
      if ($ppo->destinationType == 'dossier') {
        
        return _arRedirect (CopixUrl::get ('classeur||voirContenu', array('classeurId' => $dossierDestination->classeur_id, 'dossierId' => $ppo->destinationId, 'success' => true)));
      }
      else {
        
        return _arRedirect (CopixUrl::get ('classeur||voirContenu', array('classeurId' => $ppo->destinationId, 'success' => true)));
      }
    }
 	  
 	  return _arPPO ($ppo, array ('template' => 'deplacer_fichier.tpl'));
  }
  
  /**
	 * FICHIER - Suppression d'un fichier
	 */
  public function processSupprimerFichier () {
    
    if (is_null($classeurId = _request ('classeurId'))
     || is_null($dossierId  = _request ('dossierId'))
     || is_null($fichierId  = _request ('fichierId'))) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message' => CopixI18N::get ('kernel|kernel.error.errorOccurred'), 'back' => CopixUrl::get('')));
	  }
	  
	  _classInclude('classeur|classeurservice');
	  $fichierDAO = _ioDAO ('classeur|classeurfichier');
	  
	  // Récupération du fichier à supprimer
	  $fichier  = $fichierDAO->get($fichierId);
	  classeurService::deleteFile($fichier);
	  
	  return _arRedirect (CopixUrl::get ('classeur||voirContenu', array('classeurId' => $classeurId, 'dossierId' => $dossierId, 'success' => true)));
  }
  
  /**
	 * FICHIER - Edition de favoris
	 */
  public function processEditerFavori () {
    
    $ppo = new CopixPPO ();
    
    if (is_null($ppo->classeurId  = _request ('classeurId'))) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message' => CopixI18N::get ('kernel|kernel.error.errorOccurred'), 'back' => CopixUrl::get('')));
	  }
	  
	  return _arPPO ($ppo, array ('template' => 'editer_favori.tpl'));
  }
  
  /**
	 * ACTION DE MASSE - Suppression d'un ou de plusieurs fichiers / dossiers
	 */
  public function processSupprimerContenu () {
    
    if (is_null($classeurId = _request ('classeurId'))
     || is_null($parentId  = _request ('dossierId'))) {
       
      return CopixActionGroup::process ('generictools|Messages::getError',
   		  array ('message' => CopixI18N::get ('kernel|kernel.error.errorOccurred'), 'back' => CopixUrl::get('')));
 	  }
 	  
 	  _classInclude('classeur|classeurservice');
 	  
 	  $dossierDAO = _ioDAO('classeur|classeurdossier');
 	  $dossierIds = _request ('dossiers', array());
 	  foreach ($dossierIds as $dossierId) {
 	    
 	    $dossier = $dossierDAO->get ($dossierId);
 	    classeurService::deleteFolder($dossier);
 	  }
 	  
 	  $fichierDAO = _ioDAO('classeur|classeurfichier');
 	  $fichierIds = _request ('fichiers', array());
 	  foreach ($fichierIds as $fichierId) {
 	    
 	    $fichier = $fichierDAO->get ($fichierId);
 	    classeurService::deleteFile($fichier);
 	  }
 	  
 	  return _arRedirect (CopixUrl::get ('classeur||voirContenu', array('classeurId' => $classeurId, 'dossierId' => $parentId, 'success' => true)));
  }
  
  /**
	 * ACTION DE MASSE - Déplacement d'un ou de plusieurs fichiers / dossiers
	 */
  public function processDeplacerContenu () {
    
    if (is_null($ppo->classeurId = _request ('classeurId'))
     || is_null($ppo->dossierId  = _request ('dossierId'))) {
       
      return CopixActionGroup::process ('generictools|Messages::getError',
   		  array ('message' => CopixI18N::get ('kernel|kernel.error.errorOccurred'), 'back' => CopixUrl::get('')));
 	  }
 	  
 	  $nomsContenus = array();
 	  
 	  $dossierDAO = _ioDAO('classeur|classeurdossier');
 	  $fichierDAO = _ioDAO('classeur|classeurfichier');
 	  
 	  // Récupération des identifiants de dossier à déplacer
 	  $dossierIds = _request ('dossiers', array());
 	  if (!is_null($dossierIds)) {
 	    
 	    if (is_array($dossierIds)) {
 	      
 	      $ppo->dossierIds = implode($dossierIds, ',');
 	    }
 	    else {
 	      
 	      $ppo->dossierIds = $dossierIds;
 	      $dossierIds = explode ($ppo->dossierIds, ',');
 	    }
   	  foreach ($dossierIds as $dossierId) {

   	    $dossier = $dossierDAO->get ($dossierId);
   	    if ($dossier) {

   	      $nomsContenus[] = $dossier->nom;
   	    }
   	  }
 	  }
 	  
 	  // Récupération des identifiants de fichier à déplacer
 	  $fichierIds = _request ('fichiers', array());
 	  if (!is_null($fichierIds)) {
 	    
 	    if (is_array($fichierIds)) {
 	      
 	      $ppo->fichierIds = implode($fichierIds, ',');
 	    }
 	    else {
 	      
 	      $ppo->fichierIds = $fichierIds;
 	      $fichierIds = explode ($ppo->fichierIds, ',');
 	    }
   	  foreach ($fichierIds as $fichierId) {

   	    $fichier = $fichierDAO->get($fichierId);
   	    if ($fichier) {

   	      $nomsContenus[] = $fichier->titre;
   	    }
   	  }
 	  }
 	  
 	  if (!empty($nomsContenus)) {
 	    
 	    $ppo->nomsContenus = implode($nomsContenus, ', ');
 	  }
 	  else {
 	    
 	    $ppo->nomsContenus = _request('fichiersSelectionnes', null);
 	  }
 	  
 	  
 	  if (CopixRequest::isMethod ('post')) {
    
      _classInclude('classeur|classeurservice');
      
      $arFichierIds       = !is_null(_request('fichiers', null)) ? explode (',', _request('fichiers', null)) : null;
      $arDossierIds       = !is_null(_request('dossiers', null)) ? explode (',', _request('dossiers', null)) : null;
      
      $destination        = !is_null(_request('destination', null)) ? explode('-', _request('destination', null)) : null;
      if (is_array($destination) && !empty($destination)) {
        
        $ppo->destinationType    = $destination[0];
        $ppo->destinationId      = $destination[1];
        
        $dossierDestination = $dossierDAO->get($ppo->destinationId);
      }
      
      // Traitement des erreurs
      $ppo->erreurs = array ();
      if (!isset($ppo->destinationType) || !isset($ppo->destinationId) || is_null($ppo->destinationType) || is_null($ppo->destinationId)) {
          
        $ppo->erreurs[] = CopixI18N::get ('classeur|classeur.error.noDestination');
      }
      elseif ($ppo->destinationType == 'dossier' && in_array($ppo->destinationId, $arDossierIds)) {

        $ppo->erreurs[] = CopixI18N::get ('classeur|classeur.error.destinationUnauthorized');
      }
      if (empty($arFichierIds) && empty($arDossierIds)) {
        
        $ppo->erreurs[] = CopixI18N::get ('classeur|classeur.error.noContentSelected');
      }
      
      if (!empty ($ppo->erreurs)) {
        
        return _arPPO ($ppo, array ('template' => 'deplacer_contenu.tpl'));
      }
      
      // Déplacement des dossiers
      if (!empty($arDossierIds)) {
        
        $dossiers = array();
        foreach ($arDossierIds as $arDossierId) {
          
          $dossier = $dossierDAO->get($arDossierId);
          if ($ppo->destinationType == 'dossier') {
            
            if (!classeurService::isDescendantOf($dossier, $dossierDestination)) {
              
              $dossiers[] = $dossierDAO->get($arDossierId);
            }
          }
        }
      }
      
      foreach ($dossiers as $dossier) {
        
        classeurService::moveFolder($dossier, $ppo->destinationType, $ppo->destinationId);
      }
      
      // Déplacement des fichiers
      if (!empty($arFichierIds)) {
        
        foreach ($arFichierIds as $arFichierId) {
          
          $fichier = $fichierDAO->get($arFichierId);
          classeurService::moveFile($fichier, $ppo->destinationType, $ppo->destinationId);
        }
      }
      
      // Redirection
      if ($ppo->destinationType == 'dossier') {
        
        return _arRedirect (CopixUrl::get ('classeur||voirContenu', array('classeurId' => $dossierDestination->classeur_id, 'dossierId' => $ppo->destinationId, 'success' => true)));
      }
      else {
        
        return _arRedirect (CopixUrl::get ('classeur||voirContenu', array('classeurId' => $ppo->destinationId, 'success' => true)));
      }
    }
 	  
 	  return _arPPO ($ppo, array ('template' => 'deplacer_contenu.tpl'));
  }
  
  /**
	 * ACTION DE MASSE - Copie d'un ou de plusieurs fichiers / dossiers
	 */
  public function processCopierContenu () {
    
    if (is_null($ppo->classeurId = _request ('classeurId'))
     || is_null($ppo->dossierId  = _request ('dossierId'))) {
       
      return CopixActionGroup::process ('generictools|Messages::getError',
   		  array ('message' => CopixI18N::get ('kernel|kernel.error.errorOccurred'), 'back' => CopixUrl::get('')));
 	  }
  
    $nomsContenus = array();
 	  
 	  $dossierDAO = _ioDAO('classeur|classeurdossier');
 	  $fichierDAO = _ioDAO('classeur|classeurfichier');
 	  
 	  $dossierIds = _request ('dossiers', array());
 	  $ppo->dossierIds = implode($dossierIds, ',');
 	  foreach ($dossierIds as $dossierId) {
 	    
 	    $dossier = $dossierDAO->get ($dossierId);
 	    if ($dossier) {
 	      
 	      $nomsContenus[] = $dossier->nom;
 	    }
 	  }
 	  
 	  $fichierIds = _request ('fichiers', array());
 	  $ppo->fichierIds = implode($fichierIds, ',');
 	  foreach ($fichierIds as $fichierId) {
 	    
 	    $fichier = $fichierDAO->get($fichierId);
 	    if ($fichier) {
 	      
 	      $nomsContenus[] = $fichier->titre;
 	    }
 	  }
 	  
 	  $ppo->nomsContenus = implode($nomsContenus, ', ');
 	  
 	  if (CopixRequest::isMethod ('post')) {
      
      _classInclude('classeur|classeurservice');
      
      $fichierIds         = _request('fichierIds', null);
      $dossierIds         = _request('dossierIds', null);
      
      $destination        = explode('-', _request('destination', null));
      $destinationType    = $destination[0];
      $destinationId      = $destination[1];
      
      $dossierDestination = $dossierDAO->get($destinationId);
      
      // Copie des fichiers
      if (!is_null($fichierIds)) {
        
        $arFichierIds = explode (',', $fichierIds);
        foreach ($arFichierIds as $arFichierId) {
          
          $fichier = $fichierDAO->get($arFichierId);
          classeurService::copyFile($fichier, $destinationType, $destinationId);
        }
      }
      
      // Copie des dossiers
      if (!is_null($dossierIds)) {
        
        $arDossierIds = explode (',', $dossierIds);
        foreach ($arDossierIds as $arDossierId) {
          
          $dossier = $dossierDAO->get($arDossierId);
          classeurService::copyFolder($dossier, $destinationType, $destinationId);
        }
      }
      
      // Redirection
      if ($destinationType == 'dossier') {
        
        return _arRedirect (CopixUrl::get ('classeur||voirContenu', array('classeurId' => $dossierDestination->classeur_id, 'dossierId' => $destinationId, 'success' => true)));
      }
      else {
        
        return _arRedirect (CopixUrl::get ('classeur||voirContenu', array('classeurId' => $destinationId, 'success' => true)));
      }
    }
    
    return _arPPO ($ppo, array ('template' => 'copier_contenu.tpl'));
  }
  
  /**
	 * ACTION DE MASSE - Téléchargement d'un ou de plusieurs fichiers / dossiers (Archive ZIP)
	 */
  public function processTelechargerContenu () {
    
    if (is_null($ppo->classeurId = _request ('classeurId'))
     || is_null($ppo->dossierId  = _request ('dossierId'))) {
       
      return CopixActionGroup::process ('generictools|Messages::getError',
   		  array ('message' => CopixI18N::get ('kernel|kernel.error.errorOccurred'), 'back' => CopixUrl::get('')));
 	  }
 	  
 	  _classInclude('classeur|classeurservice');
 	  $fichierDAO = _ioDAO('classeur|classeurfichier');
 	  $dossierDAO = _ioDAO('classeur|classeurdossier');
 	  
 	  // Récupération des paramètres
 	  $dossierIds = _request ('dossiers', array());
 	  $fichierIds = _request ('fichiers', array());
  
    // Path de l'archive ZIP temporaire
    $dossierTmp = sys_get_temp_dir();
    if (substr($dossierTmp, -1) != '/') {
      
      $dossierTmp = $dossierTmp.'/';
    }
    
    $fichierZip = 'archive'.mt_rand(100000,9999999).'.zip';
		if (file_exists($dossierTmp.$fichierZip)) {
		  
		  unlink ($dossierTmp.$fichierZip);
		}
			
    $zip = new ZipArchive();
    if ($zip->open($dossierTmp.$fichierZip, ZIPARCHIVE::CREATE) === true) {
      
      $ppo->dossierIds = implode($dossierIds, ',');
   	  foreach ($dossierIds as $dossierId) {
   	    
   	    $dossier = $dossierDAO->get ($dossierId);
   	    classeurService::addFolderToZip($dossier, $zip);
   	  }
   	  
   	  $ppo->fichierIds = implode($fichierIds, ',');
   	  foreach ($fichierIds as $fichierId) {

   	    $fichier = $fichierDAO->get($fichierId);
   	    classeurService::addFileToZip($fichier, $zip);
   	  }
    }
    
    $zip->close();
    
    return _arFile ($dossierTmp.$fichierZip, array ('filename' => $fichierZip, 'content-type' => classeurService::getMimeType($fichierZip)));
  }
  
  /**
	 * FICHIER - Téléchargement d'un fichier
	 */
  public function processTelechargerFichier () {
    
    $classeurDAO = _ioDAO('classeur|classeur');	 	
	 	$fichierDAO = _ioDAO('classeur|classeurfichier');
	 	
	 	$classeur = $classeurDAO->get (_request ('classeurId', null));
	 	$fichier = $fichierDAO->get (_request ('fichierId', null));
	 	
    if (!$classeur || !$fichier) {
    
       return CopixActionGroup::process ('generictools|Messages::getError',
   			array ('message' => CopixI18N::get ('kernel|kernel.error.errorOccurred'), 'back' => CopixUrl::get('classeur||')));
    }

    _classInclude('classeur|classeurService');
    
		$nomClasseur = $classeur->id.'-'.$classeur->cle;
    
    $extension  = strrchr($fichier->fichier, '.');
		$nomFichier = $fichier->id.'-'.$fichier->cle.$extension;
		
		$pathFichier = realpath('./static/classeur').'/'.$classeur->id.'-'.$classeur->cle.'/'.($nomFichier);
		if (!file_exists($pathFichier)) {
		  
		  return CopixActionGroup::process ('generictools|Messages::getError',
   			array ('message' => CopixI18N::get ('classeur|classeur.error.noFile'), 'back' => CopixUrl::get('classeur||')));
		}

		return _arFile ($pathFichier, array ('filename' => $fichier->fichier, 'content-type' => classeurService::getMimeType($pathFichier), 'content-disposition' => 'attachement'));
  }
  
  /**
   * AJAX - Met à jour l'état de l'arbre des dossiers
   */
  public function processSauvegardeEtatArbreDossiers () {
    
    if (is_null ($id = _request ('id', null))) {
      
      return new CopixActionReturn (CopixActionReturn::HTTPCODE, 
        array('Content-Type: text/plain; charset=utf-8', 'HTTP/1.1 404 Not found'), CopixI18N::get ('kernel|kernel.error.errorOccurred'));
    }
    
    _classInclude ('classeur|classeurservice');
    ClasseurService::setFoldersTreeState ($id);
    
    return new CopixActionReturn (CopixActionReturn::HTTPCODE, 
      array('Content-Type: text/html; charset=utf-8', 'HTTP/1.1 200 OK'), CopixI18N::get ('classeur|classeur.message.stateChanged'));
  }
  
  /**
   * Méthode de redirection avec l'identifiant du classeur
   */
  public function go () {

    return _arRedirect (CopixUrl::get ('classeur||voirContenu', array('classeurId' => _request ('id', null))));
	}
}