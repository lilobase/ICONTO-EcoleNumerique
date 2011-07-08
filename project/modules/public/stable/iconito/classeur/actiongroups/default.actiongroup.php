<?php

/**
* @package    Iconito
* @subpackage Classeur
* @author     Jérémy FOURNAISE
*/

class ActionGroupDefault extends enicActionGroup {
  
  public function beforeAction ($actionName) {
    
    // Contrôle d'accès au module
    if (!is_null($classeurId = _request ('classeurId', _request('id', null)))
      && ($actionName != 'sauvegardeEtatArbreClasseurs' && $actionName != 'sauvegardeEtatArbreDossiers')) {
        
      if (Kernel::getLevel('MOD_CLASSEUR', $classeurId) < PROFILE_CCV_READ) {

        return CopixActionGroup::process ('genericTools|Messages::getError',
   	     array ('message'=> CopixI18N::get ('kernel|kernel.error.noRights'), 'back' => CopixUrl::get('classeur||voirContenu', array('classeurId' => $classeurId))));
      }
    }
    
    $this->addJs('js/iconito/module_classeur.js');
  }
  
  /**
	 * Affichage principal
	 */
  public function processVoirContenu () {
    
    $ppo = new CopixPPO ();
    
    _classInclude('classeur|classeurService');
    
    // Récupération des paramètres
    $ppo->classeurId      = _request('classeurId', null);
    $ppo->dossierId       = _request('dossierId', 0);
    $ppo->confirmMessage  = _request('confirmMessage', null);
    $ppo->errorMessage    = _request('errorMessage', null);
    $vue                  = _request('vue', null);
    
    // Paramètres de tri
    $triColonne    = _request('triColonne', null);
    $triDirection  = _request('triDirection', 'ASC');
    
    // Gestion des droits
	  $ppo->niveauUtilisateur = Kernel::getLevel('MOD_CLASSEUR', $ppo->classeurId);
    
    // Récupération de l'identifiant du classeur personnel si non disponible en session
		if (is_null($ppo->idClasseurPersonnel = _sessionGet('classeur|idClasseurPersonnel'))) {
		  
			// Création des modules inexistants.
			Kernel::createMissingModules(_currentUser()->getExtra('type'), _currentUser()->getExtra('id'));
			$modsList = Kernel::getModEnabled (_currentUser()->getExtra('type'), _currentUser()->getExtra('id'));
			foreach ($modsList as $modInfo) {
			  
				if ($modInfo->module_type == 'MOD_CLASSEUR' && $modInfo->module_id) {
				  
				  _sessionSet('classeur|idClasseurPersonnel', $modInfo->module_id);
				  $ppo->idClasseurPersonnel = _sessionGet('classeur|idClasseurPersonnel');
				}
			}
		}
		
		// Si ID du classeur non spécifié, on récupère l'identifiant du classeur perso disponible en session
	  if (is_null($ppo->classeurId)) {
	    
	    $ppo->classeurId = _sessionGet('classeur|idClasseurPersonnel');
	  }
	  
	  // Si type de vue spécifié, on le met en session
	  if (!is_null($vue) && ($vue == 'liste' || $vue == 'vignette')) {
	    
	    _sessionSet('classeur|typeVue', $vue);
	  }
	  $ppo->vue = !is_null(_sessionGet('classeur|typeVue')) ? _sessionGet('classeur|typeVue') : 'liste';
		
		// Si tri spécifié, mise en session
		if (!is_null ($triColonne)) {
      
		  ClasseurService::setContentSort ($triColonne, $triDirection);
		}
		
		// Ouverture du dossier courant dans l'arborescence
		if ($ppo->dossierId != 0) {
		  
		  classeurService::openTree($ppo->classeurId, $ppo->dossierId);
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
	  
	  _classInclude('classeur|classeurservice');
	  
	  // Récupération du dossier
	  $dossierDAO = _ioDAO('classeur|classeurdossier');
	  if (!is_null($dossierId = _request('dossierId', null))) {
      
      $ppo->dossier = $dossierDAO->get($dossierId);
    }
    else {
      
      $ppo->dossier = _record ('classeur|classeurdossier');
      
      $ppo->dossier->nb_dossiers = 0;
      $ppo->dossier->nb_fichiers = 0;
      $ppo->dossier->taille      = 0;
    }
    
    // Contrôle d'accès : si pas d'accès & non propriétaire du dossier
	  if (Kernel::getLevel('MOD_CLASSEUR', $ppo->classeurId) < PROFILE_CCV_MEMBER 
	    && ($ppo->dossier->user_type != _currentUser()->getExtra('type') 
	      || $ppo->dossier->user_id != _currentUser()->getExtra('id'))) {
	    
	    return CopixActionGroup::process ('genericTools|Messages::getError', 
	      array ('message'=> CopixI18N::get ('kernel|kernel.error.noRights'), 'back' => CopixUrl::get('classeur||voirContenu', array('classeurId' => $ppo->classeurId))));
	  }

    // Récupération du dossier parent
    if (!is_null($parentId  = _request('parentId', null)) && $parentId != 0) {
      
      $ppo->parent  = $dossierDAO->get($parentId);
      $ppo->path    = $ppo->parent->getPath();
    }
    else {
      
      $classeurDAO  = _ioDAO('classeur|classeur');
      $classeur     = $classeurDAO->get($ppo->classeurId);
      $ppo->path    = '/'.$classeur->titre.'/';
    }
    
    if (CopixRequest::isMethod ('post')) {
  	  
      $ppo->dossier->classeur_id    = $ppo->classeurId;
      $ppo->dossier->parent_id      = _request('parentId', 0);
      $ppo->dossier->nom            = _request('dossier_nom', null);
      $ppo->dossier->cle            = classeurService::createKey();
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
        
        // Mise à jour des informations du dossier (nombre de fichiers, taille, ...)
        classeurService::updateFolderInfos($ppo->dossier);
        
        $confirmMessage = CopixI18N::get ('classeur|classeur.message.confirmCreation');
      }
      else {
        
        // Mise à jour de l'enregistrement "dossier"
        $dossierDAO->update ($ppo->dossier);
        
        $confirmMessage = CopixI18N::get ('classeur|classeur.message.confirmUpdate');
      }
      
      return _arRedirect (CopixUrl::get ('classeur||voirContenu', array('classeurId' => $ppo->classeurId, 'dossierId' => isset($ppo->parent) ? $ppo->parent->id : 0, 'confirmMessage' => $confirmMessage)));
    }
    
    return _arPPO ($ppo, array ('template' => 'editer_dossier.tpl'));
  }
  
  /**
	 * DOSSIER - Déplacement d'un dossier
	 */
  public function processDeplacerDossier () {
    
    $dossierDAO = _ioDAO('classeur|classeurdossier');
 	  $fichierDAO = _ioDAO('classeur|classeurfichier');
 	  
    if (is_null($ppo->classeurId     = _request ('classeurId', null))
     || is_null($ppo->dossierParent  = $dossierDAO->get(_request('parentId', null)))
     || is_null($ppo->dossier        = $dossierDAO->get (_request ('dossierId', null)))) {
       
      return CopixActionGroup::process ('generictools|Messages::getError',
   		  array ('message' => CopixI18N::get ('kernel|kernel.error.errorOccurred'), 'back' => CopixUrl::get('')));
 	  }
 	  elseif (Kernel::getLevel('MOD_CLASSEUR', $ppo->classeurId) < PROFILE_CCV_PUBLISH 
	    && ($ppo->dossier->user_type != _currentUser()->getExtra('type') 
	      || $ppo->dossier->user_id != _currentUser()->getExtra('id'))) {
	    
	    return CopixActionGroup::process ('genericTools|Messages::getError', 
	      array ('message'=> CopixI18N::get ('kernel|kernel.error.noRights'), 'back' => CopixUrl::get('classeur||voirContenu', array('classeurId' => $ppo->classeurId))));
	  }
 	  
 	  if (CopixRequest::isMethod ('post')) {
    
      _classInclude('classeur|classeurservice');

      if (!is_null($destination = _request('destination', null))) {
        
        list($ppo->destinationType, $ppo->destinationId) = explode('-', $destination);
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

      // Maj des informations du dossier
      classeurService::updateFolderInfos($ppo->dossierParent);
      
      // Redirection
      $confirmMessage = CopixI18N::get ('classeur|classeur.message.confirmMove');
      if ($ppo->destinationType == 'dossier') {
        
        classeurService::updateFolderInfos($dossierDestination);
        
        return _arRedirect (CopixUrl::get ('classeur||voirContenu', array('classeurId' => $dossierDestination->classeur_id, 'dossierId' => $ppo->destinationId, 'confirmMessage' => $confirmMessage)));
      }
      else {
        
        return _arRedirect (CopixUrl::get ('classeur||voirContenu', array('classeurId' => $ppo->destinationId, 'confirmMessage' => $confirmMessage)));
      }
    }
 	  
 	  return _arPPO ($ppo, array ('template' => 'deplacer_dossier.tpl'));
  }
  
  /**
	 * DOSSIER - Suppression d'un dossier
	 */
  public function processSupprimerDossier () {
    
    _classInclude('classeur|classeurservice');
	  $dossierDAO = _ioDAO ('classeur|classeurdossier');
	  $fichierDAO = _ioDAO ('classeur|classeurfichier');
	  
    if (is_null($classeurId = _request ('classeurId'))
     || is_null($dossier  = $dossierDAO->get(_request ('dossierId', null)))) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message' => CopixI18N::get ('kernel|kernel.error.errorOccurred'), 'back' => CopixUrl::get('')));
	  }
	  elseif (Kernel::getLevel('MOD_CLASSEUR', $classeurId) < PROFILE_CCV_PUBLISH 
	    && ($dossier->user_type != _currentUser()->getExtra('type') 
	      || $dossier->user_id != _currentUser()->getExtra('id'))) {
	    
	    return CopixActionGroup::process ('genericTools|Messages::getError', 
	      array ('message'=> CopixI18N::get ('kernel|kernel.error.noRights'), 'back' => CopixUrl::get('classeur||voirContenu', array('classeurId' => $classeurId))));
	  }
	  
	  // Suppression
	  classeurService::deleteFolder($dossier, true);
	  
	  // Maj des informations de dossier
	  if ($dossier->parent_id != 0) {
	    
	    $dossierParent = $dossierDAO->get($dossier->parent_id);
      classeurService::updateFolderInfos($dossierParent);
	  }
    
    $confirmMessage = CopixI18N::get ('classeur|classeur.message.confirmDelete');
    
    return _arRedirect (CopixUrl::get ('classeur||voirContenu', array('classeurId' => $classeurId, 'dossierId' => isset($dossierParent) ? $dossierParent->id : 0, 'confirmMessage' => $confirmMessage)));
  }
  
  /**
	 * FICHIER - Edition de fichiers
	 */
  public function processEditerFichiers () {
    
    $ppo = new CopixPPO ();
    
    if (is_null($ppo->classeurId = _request ('classeurId'))) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message' => CopixI18N::get ('kernel|kernel.error.errorOccurred'), 'back' => CopixUrl::get('')));
	  }
	  elseif (Kernel::getLevel('MOD_CLASSEUR', $ppo->classeurId) < PROFILE_CCV_MEMBER) {
	    
	    return CopixActionGroup::process ('genericTools|Messages::getError', 
	      array ('message'=> CopixI18N::get ('kernel|kernel.error.noRights'), 'back' => CopixUrl::get('classeur||voirContenu', array('classeurId' => $ppo->classeurId))));
	  }
	  
	  // Récupération de l'identifiant du fichier (si modification)
    $fichierId = _request('fichierId', null);
    if (!is_null($fichierId)) {
      
      $fichierDAO = _ioDAO('classeur|classeurfichier');
      $ppo->fichier = $fichierDAO->get($fichierId);
      
      // Contrôle d'accès
      if (Kernel::getLevel('MOD_CLASSEUR', $ppo->classeurId) < PROFILE_CCV_MEMBER 
  	    && ($ppo->fichier->user_type != _currentUser()->getExtra('type') 
  	      || $ppo->fichier->user_id != _currentUser()->getExtra('id'))) {

  	    return CopixActionGroup::process ('genericTools|Messages::getError', 
  	      array ('message'=> CopixI18N::get ('kernel|kernel.error.noRights'), 'back' => CopixUrl::get('classeur||voirContenu', array('classeurId' => $ppo->classeurId))));
  	  }
    }
    else {
      
      // Chargement JS & CSS pour uploadify
  	  $this->addJs('js/uploadify/swfobject.js');
  	  $this->addJs('js/uploadify/jquery.uploadify.v2.1.4.min.js');
  	  
  	  $this->addCss('styles/uploadify.css');
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
          
          $dir = realpath('./static/classeur').'/'.$classeur->id.'-'.$classeur->cle.'/';
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
        
        $confirmMessage = CopixI18N::get ('classeur|classeur.message.confirmUpdate');
      }
      // Sinon, upload multiple
      else {
        
        // Récupération du dossier temporaire
        $ppo->dossierTmp = _request('dossierTmp', null);
        
        // Contrôle : upload effectué / dossier temporaire créé ?
        $fichiersPhysiques = array();
        $fichiersZip       = array();
        if (!is_dir($ppo->dossierTmp)) {
          
          $ppo->erreurs[] = CopixI18N::get ('classeur|classeur.error.noFiles');
        }
        elseif ($handle = opendir($ppo->dossierTmp)) {
          
          while (($file = readdir($handle)) !== false) {
            
            if ($file != '.' && $file != '..') {

              $extension = strrchr($file, '.');
              if ($extension == '.zip' && _request('with_decompress', false)) {
                
                $fichiersZip[] = $file;
              }
              else {
                
                $fichiersPhysiques[] = $file;
              }
            }
          }
        }
        
        // Traitement des erreurs
        $ppo->erreurs = array ();
        
        if (!is_null(_request('fichier_titre', null)) && strlen(_request('fichier_titre')) > 64) {
          
          $ppo->erreurs[] = CopixI18N::get ('classeur|classeur.error.titleTooLong', array('size' => 64));
        }
        if (empty($fichiersPhysiques) && empty($fichiersZip)) {
          
          $ppo->erreurs[] = CopixI18N::get ('classeur|classeur.error.noFiles');
        }

        if (!empty ($ppo->erreurs)) {

          return _arPPO ($ppo, array ('template' => 'editer_fichiers.tpl'));
        }

        // Création du répertoire
        $dir = realpath('./static/classeur').'/'.$classeur->id.'-'.$classeur->cle.'/';
        if (!file_exists($dir)) {
          
          mkdir($dir, 0755, true);
        }
        
        $fichierDAO = _ioDAO('classeur|classeurfichier');
        // Décompression des archives ZIP et ajout aux fichiers physiques à déplacer
        foreach ($fichiersZip as $fichierZip) {
          
          $zip = new ZipArchive;
          if ($zip->open($ppo->dossierTmp.'/'.$fichierZip) === true) {

            for ($i = 0; $i < $zip->numFiles; $i++) {
              
              // On ignore les fichiers 
              if (!strstr($zip->getNameIndex($i), '_MACOSX')) {
                
                $zip->extractTo($ppo->dossierTmp, array($zip->getNameIndex($i)));
                $fichiersPhysiques[] = $zip->getNameIndex($i);
              }
            }
            
            $zip->close();
            unlink($ppo->dossierTmp.'/'.$fichierZip);
          }
        }
        
        // Copie des fichiers dans le rep classeur et suppression des fichiers TMP
        foreach ($fichiersPhysiques as $fichierPhysique) {
        
          $fichier = _record('classeur|classeurfichier');
        
          $fichier->classeur_id   = $classeur->id;
          $fichier->dossier_id    = isset($ppo->dossierId) ? $ppo->dossierId : 0;
          $fichier->titre         = !is_null(_request('fichier_titre', null)) ? _request('fichier_titre') : substr($fichierPhysique, 0, 63);
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
        
        // Mise à jour des informations du dossier parent
        classeurService::updateFolderInfos($ppo->dossier);
        
        $confirmMessage = CopixI18N::get ('classeur|classeur.message.success');
      }
      
      return _arRedirect (CopixUrl::get ('classeur||voirContenu', array('classeurId' => $ppo->classeurId, 'dossierId' => $ppo->dossierId, 'confirmMessage' => $confirmMessage)));
    }
    
    return _arPPO ($ppo, array ('template' => 'editer_fichiers.tpl'));
  }
  
  /**
	 * FICHIER - Déplacement d'un fichier
	 */
  public function processDeplacerFichier () {
    
    $classeurDAO = _ioDAO('classeur|classeur');
    $dossierDAO  = _ioDAO('classeur|classeurdossier');
 	  $fichierDAO  = _ioDAO('classeur|classeurfichier');
 	  
    if (is_null($ppo->classeur    = $classeurDAO->get(_request ('classeurId', null)))
     || is_null($ppo->dossier     = $dossierDAO->get(_request ('dossierId', null)))
     || is_null($ppo->fichier     = $fichierDAO->get(_request ('fichierId', null)))) {
       
      return CopixActionGroup::process ('generictools|Messages::getError',
   		  array ('message' => CopixI18N::get ('kernel|kernel.error.errorOccurred'), 'back' => CopixUrl::get('')));
 	  }
 	  elseif (Kernel::getLevel('MOD_CLASSEUR', $ppo->classeur->id) < PROFILE_CCV_PUBLISH 
	    && ($ppo->fichier->user_type != _currentUser()->getExtra('type') 
	      || $ppo->fichier->user_id != _currentUser()->getExtra('id'))) {

	    return CopixActionGroup::process ('genericTools|Messages::getError', 
	      array ('message'=> CopixI18N::get ('kernel|kernel.error.noRights'), 'back' => CopixUrl::get('classeur||voirContenu', array('classeurId' => $ppo->classeur->id))));
	  }
 	  
 	  if (CopixRequest::isMethod ('post')) {
    
      _classInclude('classeur|classeurservice');
      
      if (!is_null($destination = _request('destination', null))) {
        
        list($ppo->destinationType, $ppo->destinationId) = explode ('-', $destination);
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
      
      // Maj des informations des dossiers 
      classeurService::updateFolderInfos($ppo->dossier);
            
      // Redirection
      $confirmMessage = CopixI18N::get ('classeur|classeur.message.confirmMove');
      if ($ppo->destinationType == 'dossier') {
        
        classeurService::updateFolderInfos($dossierDestination);
        return _arRedirect (CopixUrl::get ('classeur||voirContenu', array('classeurId' => $dossierDestination->classeur_id, 'dossierId' => $ppo->destinationId, 'confirmMessage' => $confirmMessage)));
      }
      else {
        
        return _arRedirect (CopixUrl::get ('classeur||voirContenu', array('classeurId' => $ppo->destinationId, 'confirmMessage' => $confirmMessage)));
      }
    }
    
 	  return _arPPO ($ppo, array ('template' => 'deplacer_fichier.tpl'));
  }
  
  /**
	 * FICHIER - Suppression d'un fichier
	 */
  public function processSupprimerFichier () {
    
    _classInclude('classeur|classeurservice');
    $dossierDAO = _ioDAO ('classeur|classeurdossier');
	  $fichierDAO = _ioDAO ('classeur|classeurfichier');
	  
    if (is_null($classeurId = _request ('classeurId'))
     || is_null($fichier  = $fichierDAO->get(_request ('fichierId', null)))) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message' => CopixI18N::get ('kernel|kernel.error.errorOccurred'), 'back' => CopixUrl::get('')));
	  }
	  elseif (Kernel::getLevel('MOD_CLASSEUR', $classeurId) < PROFILE_CCV_PUBLISH 
	    && ($fichier->user_type != _currentUser()->getExtra('type') 
	      || $fichier->user_id != _currentUser()->getExtra('id'))) {

	    return CopixActionGroup::process ('genericTools|Messages::getError', 
	      array ('message'=> CopixI18N::get ('kernel|kernel.error.noRights'), 'back' => CopixUrl::get('classeur||voirContenu', array('classeurId' => $classeurId))));
	  }
	  
	  classeurService::deleteFile($fichier);
	  
	  // Maj des informations de dossier
	  $dossierId = _request ('dossierId', 0);
	  if ($dossierId != 0) {
	    
	    $dossier = $dossierDAO->get($dossierId);
      classeurService::updateFolderInfos($dossier);
	  }
    
    $confirmMessage = CopixI18N::get ('classeur|classeur.message.confirmDelete');
	  
	  return _arRedirect (CopixUrl::get ('classeur||voirContenu', array('classeurId' => $classeurId, 'dossierId' => $dossierId, 'confirmMessage' => $confirmMessage)));
  }
  
  /**
	 * FICHIER - Edition de favoris
	 */
  public function processEditerFavori () {
    
    $classeurDAO = _ioDAO('classeur|classeur');
    
    if (is_null($ppo->classeur = $classeurDAO->get(_request ('classeurId', null)))) {
	    
	    return CopixActionGroup::process ('generictools|Messages::getError',
  			array ('message' => CopixI18N::get ('kernel|kernel.error.errorOccurred'), 'back' => CopixUrl::get('')));
	  }
	  elseif (Kernel::getLevel('MOD_CLASSEUR', $ppo->classeur->id) < PROFILE_CCV_MEMBER) {
	    
	    return CopixActionGroup::process ('genericTools|Messages::getError', 
	      array ('message'=> CopixI18N::get ('kernel|kernel.error.noRights'), 'back' => CopixUrl::get('classeur||voirContenu', array('classeurId' => $ppo->classeur->id))));
	  }
	  
	  // Récupération du dossier pour définition du path
    $dossierDAO = _ioDAO('classeur|classeurdossier');
    if (!is_null($ppo->dossierId  = _request('dossierId', null)) && $ppo->dossierId != 0) {
      
      $ppo->dossier = $dossierDAO->get($ppo->dossierId);
      $ppo->path = $ppo->dossier->getPath();
    }
    else {
      
      $ppo->path = '/'.$ppo->classeur->titre.'/';
    }
    
	  // Récupération de l'identifiant du favori (si modification)
    $favoriId = _request('favoriId', null);
    if (!is_null($favoriId)) {
      
      $fichierDAO = _ioDAO('classeur|classeurfichier');
      $ppo->favori = $fichierDAO->get($favoriId);
      
      // Contrôle d'accès
      if (Kernel::getLevel('MOD_CLASSEUR', $ppo->classeur->id) < PROFILE_CCV_MEMBER 
  	    && ($ppo->favori->user_type != _currentUser()->getExtra('type') 
  	      || $ppo->favori->user_id != _currentUser()->getExtra('id'))) {

  	    return CopixActionGroup::process ('genericTools|Messages::getError', 
  	      array ('message'=> CopixI18N::get ('kernel|kernel.error.noRights'), 'back' => CopixUrl::get('classeur||voirContenu', array('classeurId' => $ppo->classeur->id))));
  	  }
  	  
  	  // Récupération de l'url du favori
  	  _classInclude('classeur|classeurservice');
  	  $ppo->lien = $ppo->favori->getLienFavori();
    }
	  
	  if (CopixRequest::isMethod ('post')) {
    
      $ppo->lien = _request('favori_adresse', null);
      
      // Traitement des erreurs
      $ppo->erreurs = array ();
      
      if (_request('favori_titre', null) == '') {

        $ppo->erreurs[] = CopixI18N::get ('classeur|classeur.error.noTitle');
      }
      if (_request('favori_adresse', null) == '') {

        $ppo->erreurs[] = CopixI18N::get ('classeur|classeur.error.noAddress');
      }
      elseif (!fopen(_request('favori_adresse', null), 'r')) {
        
        $ppo->erreurs[] = CopixI18N::get ('classeur|classeur.error.invalidAddress');
      }

      if (!empty ($ppo->erreurs)) {

        return _arPPO ($ppo, array ('template' => 'editer_favori.tpl'));
      }
      
      _classInclude('classeur|classeurservice');
      $fichierDAO = _ioDAO('classeur|classeurfichier');
      
      if (is_null($favoriId = _request('favoriId', null))) {
        
        $ppo->favori = _record('classeur|classeurfichier');
      }
      else {
        
        $ppo->favori = $fichierDAO->get($favoriId);
      }
      
      $ppo->favori->classeur_id   = $ppo->classeur->id;
      $ppo->favori->dossier_id    = !is_null($dossierId = _request('dossierId', null)) ? $dossierId : 0;
      $ppo->favori->titre         = _request('favori_titre', null);
      $ppo->favori->commentaire   = null;
      $ppo->favori->fichier       = Kernel::simpleName($ppo->favori->titre).'.web';
      $ppo->favori->taille        = 0;
      $ppo->favori->type          = 'application/octet-stream';
      $ppo->favori->cle           = classeurService::createKey();
      $ppo->favori->date_upload   = date('Y-m-d H:i:s');
      $ppo->favori->user_type     = _currentUser()->getExtra('type');
      $ppo->favori->user_id       = _currentUser()->getExtra('id');
      
      if (is_null($ppo->favori->id)) {
        
        $fichierDAO->insert ($ppo->favori);
      }
      else {
        
        $fichierDAO->update ($ppo->favori);
      }
      
      // Création du fichier
      $contenu = classeurService::generateWebFile($ppo->lien);
      
      $extension  = strrchr($ppo->favori->fichier, '.');
      $nomFichier = $ppo->favori->id.'-'.$ppo->favori->cle.$extension;
      $pathFichier = realpath('./static/classeur').'/'.$ppo->classeur->id.'-'.$ppo->classeur->cle.'/'.($nomFichier);
      
      if ($handler = fopen($pathFichier, 'w+')) {
        
        if (fwrite($handler, $contenu)) {
          
          $ppo->favori->taille = filesize ($pathFichier);
          $fichierDAO->update ($ppo->favori);
        }
        
        fclose($handler);
      }
      
      if ($ppo->favori->dossier_id != 0) {
        
        $dossierDAO = _ioDAO('classeur|classeurdossier');
        $dossier    = $dossierDAO->get($ppo->favori->dossier_id);
        classeurService::updateFolderInfos ($dossier);
      }
      
      $confirmMessage = CopixI18N::get ('classeur|classeur.message.confirmCreation');
    
      return _arRedirect (CopixUrl::get ('classeur||voirContenu', array('classeurId' => $ppo->classeur->id, 'dossierId' => $ppo->favori->dossier_id, 'confirmMessage' => $confirmMessage)));
    }
	  
	  return _arPPO ($ppo, array ('template' => 'editer_favori.tpl'));
  }
  
  /**
	 * ACTION DE MASSE - Suppression d'un ou de plusieurs fichiers / dossiers
	 */
  public function processSupprimerContenu () {
    
    $dossierDAO = _ioDAO ('classeur|classeurdossier');
    
    if (is_null($classeurId = _request ('classeurId'))) {
       
      return CopixActionGroup::process ('generictools|Messages::getError',
   		  array ('message' => CopixI18N::get ('kernel|kernel.error.errorOccurred'), 'back' => CopixUrl::get('')));
 	  }
 	  
 	  // Contrôle d'accès : droit de publication sur le classeur nécessaire pour pouvoir y supprimer du contenu
 	  if (Kernel::getLevel('MOD_CLASSEUR', $classeurId) < PROFILE_CCV_PUBLISH) {
 	    
 	    return CopixActionGroup::process ('genericTools|Messages::getError', 
	      array ('message'=> CopixI18N::get ('kernel|kernel.error.noRights'), 'back' => CopixUrl::get('classeur||voirContenu', array('classeurId' => $classeurId))));
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
 	  
 	  // Maj des informations de dossier
 	  $dossierParentId = _request ('dossierId', 0);
 	  if ($dossierParentId != 0) {
 	    
 	    $dossierParent = $dossierDAO->get($dossierParentId);
 	    classeurService::updateFolderInfos($dossierParent);
 	  }
    
    $confirmMessage = CopixI18N::get ('classeur|classeur.message.confirmDelete');
 	  
 	  return _arRedirect (CopixUrl::get ('classeur||voirContenu', array('classeurId' => $classeurId, 'dossierId' => $dossierParentId, 'confirmMessage' => $confirmMessage)));
  }
  
  /**
	 * ACTION DE MASSE - Déplacement d'un ou de plusieurs fichiers / dossiers
	 */
  public function processDeplacerContenu () {
    
    $classeurDAO = _ioDAO('classeur|classeur');
    $dossierDAO  = _ioDAO('classeur|classeurdossier');
    $fichierDAO  = _ioDAO('classeur|classeurfichier');
    
    if (is_null($ppo->classeur = $classeurDAO->get(_request ('classeurId', null)))
     || is_null($ppo->dossierParent = $dossierDAO->get(_request ('dossierId', null)))) {
       
      return CopixActionGroup::process ('generictools|Messages::getError',
   		  array ('message' => CopixI18N::get ('kernel|kernel.error.errorOccurred'), 'back' => CopixUrl::get('')));
 	  }  
 	  // Contrôle d'accès : droit de publication sur le classeur nécessaire pour pouvoir y déplacer du contenu
 	  elseif (Kernel::getLevel('MOD_CLASSEUR', $ppo->classeur->id) < PROFILE_CCV_PUBLISH) {
 	    
 	    return CopixActionGroup::process ('genericTools|Messages::getError', 
	      array ('message'=> CopixI18N::get ('kernel|kernel.error.noRights'), 'back' => CopixUrl::get('classeur||voirContenu', array('classeurId' => $ppo->classeur->id))));
 	  }
 	  
 	  $nomsContenus = array();
 	  
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

   	      $nomsContenus[] = $fichier->fichier;
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
      
      // Récupération des informations sur le noeud de destination
      if (!is_null($destination = _request('destination', null))) {
        
        list($ppo->destinationType, $ppo->destinationId) = explode('-', $destination);
        if ($ppo->destinationType == 'dossier') {
          
          $dossierDestination = $dossierDAO->get($ppo->destinationId);
        }
      }
      
      // Traitement des erreurs
      $ppo->erreurs = array ();
      if (!isset($ppo->destinationType) || !isset($ppo->destinationId) 
        || is_null($ppo->destinationType) || is_null($ppo->destinationId)) {
          
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
          if (($ppo->destinationType == 'dossier' && !classeurService::isDescendantOf($dossierDestination, $dossier)) 
            || $ppo->destinationType == 'classeur') {
            
            $dossier = $dossierDAO->get($arDossierId);
            // On ne déplace que les dossiers pouvant l'être
            if ($dossier) {
              
              classeurService::moveFolder($dossier, $ppo->destinationType, $ppo->destinationId);
            }
          }
        }
      }
      
      // Déplacement des fichiers
      if (!empty($arFichierIds)) {
        
        foreach ($arFichierIds as $arFichierId) {
          
          $fichier = $fichierDAO->get($arFichierId);
          classeurService::moveFile($fichier, $ppo->destinationType, $ppo->destinationId);
        }
      }
      
      // Maj des informations de dossier
      classeurService::updateFolderInfos($ppo->dossierParent);
      
      // Redirection
      $confirmMessage = CopixI18N::get ('classeur|classeur.message.confirmMove');
      if ($ppo->destinationType == 'dossier') {
        
        classeurService::updateFolderInfos($dossierDestination);
        
        return _arRedirect (CopixUrl::get ('classeur||voirContenu', array('classeurId' => $dossierDestination->classeur_id, 'dossierId' => $ppo->destinationId, 'confirmMessage' => $confirmMessage)));
      }
      else {
        
        return _arRedirect (CopixUrl::get ('classeur||voirContenu', array('classeurId' => $ppo->destinationId, 'confirmMessage' => $confirmMessage)));
      }
    }
 	  
 	  return _arPPO ($ppo, array ('template' => 'deplacer_contenu.tpl'));
  }
  
  /**
	 * ACTION DE MASSE - Copie d'un ou de plusieurs fichiers / dossiers
	 */
  public function processCopierContenu () {
    
    $classeurDAO = _ioDAO('classeur|classeur');
    $dossierDAO  = _ioDAO('classeur|classeurdossier');
    $fichierDAO  = _ioDAO('classeur|classeurfichier');
    
    if (is_null($ppo->classeur = $classeurDAO->get(_request ('classeurId', null)))
     || is_null($ppo->dossier  = $dossierDAO->get(_request ('dossierId', null)))) {
       
      return CopixActionGroup::process ('generictools|Messages::getError',
   		  array ('message' => CopixI18N::get ('kernel|kernel.error.errorOccurred'), 'back' => CopixUrl::get('')));
 	  }
  
    $nomsContenus = array(); 	  
 	  
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
 	      
 	      $nomsContenus[] = $fichier->fichier;
 	    }
 	  }
 	  
 	  $ppo->nomsContenus = implode($nomsContenus, ', ');
 	  
 	  if (CopixRequest::isMethod ('post')) {
      
      _classInclude('classeur|classeurservice');
      
      $fichierIds         = _request('fichierIds', null);
      $dossierIds         = _request('dossierIds', null);
      
      if (!is_null($destination = _request('destination', null))) {
        
        list ($destinationType, $destinationId) = explode('-', $destination);
        if ($destinationType == 'dossier') {
          
          $dossierDestination = $dossierDAO->get($destinationId);
        }
      }
      
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
      $confirmMessage = CopixI18N::get ('classeur|classeur.message.confirmCopy');
      if ($destinationType == 'dossier') {
        
        classeurService::updateFolderInfos($dossierDestination);
        return _arRedirect (CopixUrl::get ('classeur||voirContenu', 
          array('classeurId' => $dossierDestination->classeur_id, 'dossierId' => $destinationId, 'confirmMessage' => $confirmMessage)));
      }
      else {
        
        return _arRedirect (CopixUrl::get ('classeur||voirContenu', array('classeurId' => $destinationId, 'confirmMessage' => $confirmMessage)));
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
  
    if (empty($dossierIds) && count($fichierIds) == 1) {
      
      // Récupération du fichier
      $fichier = $fichierDAO->get($fichierIds[0]);
      
      if ($fichier->estUnFavori()) {
        
        return _arRedirect (CopixUrl::get ('classeur||voirContenu', array('classeurId' => $fichier->classeur_id, 'dossierId' => $fichier->dossier_id, 'errorMessage' => CopixI18N::get ('classeur|classeur.error.downloadFavorite'))));
      }
      
      // Récupération du classeur nécessaire pour déterminer le chemin du fichier
      $classeurDAO = _ioDAO('classeur|classeur');
      $classeur    = $classeurDAO->get($fichier->classeur_id);

      // Path du fichier
      $dir        = realpath('./static/classeur').'/'.$classeur->id.'-'.$classeur->cle.'/';
      $extension  = strrchr($fichier->fichier, '.');
      $pathfile   = $dir.$fichier->id.'-'.$fichier->cle.$extension;
      
      return _arFile ($pathfile, array ('filename' => $fichier->fichier, 'content-type' => classeurService::getMimeType($fichier->fichier), 'content-disposition' => 'attachment'));
    }
    
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
   	    if (!$fichier->estUnFavori()) {
   	      
   	      classeurService::addFileToZip($fichier, $zip);
   	    }
   	  }
    }
    
    $zip->close();
    
    return _arFile ($dossierTmp.$fichierZip, array ('filename' => $fichierZip, 'content-type' => classeurService::getMimeType($fichierZip), 'content-disposition' => 'attachment'));
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
	 * Edition d'un album public
	 */
  public function processEditerAlbumPublic () {
    
    $classeurDAO = _ioDAO('classeur|classeur');
    
    if (is_null($ppo->classeur = $classeurDAO->get(_request ('classeurId', null)))) {
       
      return CopixActionGroup::process ('generictools|Messages::getError',
   		  array ('message' => CopixI18N::get ('kernel|kernel.error.errorOccurred'), 'back' => CopixUrl::get('')));
 	  }
 	  elseif (_currentUser()->getExtra('type') != 'USER_ENS') {
 	    
 	    return CopixActionGroup::process ('genericTools|Messages::getError', 
	      array ('message'=> CopixI18N::get ('kernel|kernel.error.noRights'), 'back' => CopixUrl::get('classeur||voirContenu', array('classeurId' => $ppo->classeur->id))));
 	  }
 	  
 	  $ppo->confirmMessage = _request('confirmMessage', null);
 	  
 	  _classInclude('classeur|classeurservice');
 	  $dossierDAO  = _ioDAO('classeur|classeurdossier');
    $fichierDAO  = _ioDAO('classeur|classeurfichier');
    
    if (($ppo->dossierId = _request('dossierId', 0)) != 0) {
      
      $ppo->dossier = $dossierDAO->get($ppo->dossierId);
      $ppo->album = $ppo->dossier;
      if ($ppo->album->public == 1) {
        
        $ppo->albumUrl = CopixUrl::get ().'static/classeur/'.$ppo->classeur->id.'-'.$ppo->classeur->cle.'/'.$ppo->album->id.'-'.$ppo->album->cle.'/index.html';
      }
    }
    else {
      
      $ppo->album = $ppo->classeur;
      if ($ppo->album->public == 1) {
        
        $ppo->albumUrl = CopixUrl::get ().'static/classeur/'.$ppo->album->id.'-'.$ppo->album->cle.'/index.html';
      }
    }
    
    $ppo->images    = array();
    $ppo->documents = array();
    
    $ppo->fichiers = classeurService::getFilesInFolder($ppo->classeur->id, $ppo->dossierId);
    foreach($ppo->fichiers as $fichier) {
      
      if (strstr($fichier->type, 'image')) {
        
        $ppo->images[] = $fichier;
      }
      else {
        
        $ppo->documents[] = $fichier;
      }
    }
 	  
    return _arPPO ($ppo, array ('template' => 'editer_album_public.tpl'));
  }
  
  /**
	 * Publication d'un album public
	 */
  public function processPublierAlbum () {
    
    $classeurDAO  = _ioDAO('classeur|classeur');
    $dossierDAO   = _ioDAO('classeur|classeurdossier');
    
    if (is_null($classeur = $classeurDAO->get(_request ('classeurId', null)))
     || is_null($dossierId = _request ('dossierId', null))) {
       
      return CopixActionGroup::process ('generictools|Messages::getError',
   		  array ('message' => CopixI18N::get ('kernel|kernel.error.errorOccurred'), 'back' => CopixUrl::get('')));
 	  }
 	  elseif (_currentUser()->getExtra('type') != 'USER_ENS') {
 	    
 	    return CopixActionGroup::process ('genericTools|Messages::getError', 
	      array ('message'=> CopixI18N::get ('kernel|kernel.error.noRights'), 'back' => CopixUrl::get('classeur||voirContenu', array('classeurId' => $classeur->id))));
 	  }
 	  
 	  $path2classeur = realpath('./static/classeur').'/'.$classeur->id.'-'.$classeur->cle;
 	  if ($dossierId != 0) {
 	    
      $album        = $dossierDAO->get($dossierId);
      $albumType    = 'dossier';
      $path2album   = $path2classeur.'/'.$album->id.'-'.$album->cle;
      $path2public  = CopixUrl::get ().'static/classeur/'.$classeur->id.'-'.$classeur->cle.'/'.$album->id.'-'.$album->cle;
    }
    else {
      
      $album        = $classeur;
      $albumType    = 'classeur';
      $path2album   = $path2classeur;
      $path2public  = CopixUrl::get ().'static/classeur/'.$album->id.'-'.$album->cle;
    }
    
    // Récupération des images à inclure dans l'album
    _classInclude('classeur|classeurservice');
    $fichiers = classeurService::getFilesInFolder($classeur->id, $dossierId);
    foreach($fichiers as $fichier) {
      
      if (strstr($fichier->type, 'image')) {
        
        $extension = strrchr($fichier->fichier, '.');
        $images[] = $fichier->id.'-'.$fichier->cle.$extension;
      }
    }
    
    // Création des dossiers de l'album si nécessaire et copie des images à publier
    if (is_dir($path2album.'/images/')) {
      
      if ($handle = opendir($path2album.'/images/')) {

        while (($file = readdir($handle)) !== false) {

           if ($file != '.' && $file != '..') {

             unlink($path2album.'/images/'.$file);
           }
        }

        closedir($handle);
        rmdir($path2album.'/images/');
        unlink($path2album.'/index.html');
      }
    }
 
    if (!is_dir($path2album)) {
      
      mkdir($path2album, 0775);
    }
    if (!is_dir($path2album.'/images/')) {
      
      mkdir($path2album.'/images/', 0775);
    }
    
		chmod($path2album, 0775);
		chmod($path2album.'/images/', 0775);
		
		foreach ($images as $image) {
		  
			copy($path2classeur.'/'.$image, $path2album.'/images/'.$image);
		}
		
		// Création du fichier index.html nécessaire à l'affichage de l'album
		$file_html = fopen($path2album.'/index.html', 'w');
		$tplHtml = & new CopixTpl ();
		$tplHtml->assign ('path2public', $path2public);
		$tplHtml->assign ('images', $images);
		$tplHtml->assign ('album_id', $album->id);
		$tplHtml->assign ('album_key', $album->cle);
		$tplHtml->assign ('album_titre', $classeur->titre);
		$tplHtml->assign ('dossier_id', $dossierId);
		if($dossierId != 0) {
		  
		  $tplHtml->assign ('dossier_nom', $album);
		}
		$result = $tplHtml->fetch('album_html.tpl');
		fwrite($file_html, $result);
		fclose($file_html);
		
		// Edition de l'album : date de publication et publication à 1
		$album->date_publication = date('Y-m-d H:i:s');
		$album->public = 1;
		if ($albumType == 'dossier') {
		  
		  $dossierDAO->update($album);
		}
		else {
		  
		  $classeurDAO->update($album);
		}
		
		$confirmMessage = CopixI18N::get ('classeur|classeur.message.confirmPublished');
		
		return _arRedirect (CopixUrl::get ('classeur||editerAlbumPublic', array('classeurId' => $classeur->id, 'dossierId' => $dossierId, 'confirmMessage' => $confirmMessage)));	  
  }
  
  /**
	 * Dépublication d'un album public
	 */
  public function processDepublierAlbum () {
    
    $classeurDAO  = _ioDAO('classeur|classeur');
    $dossierDAO   = _ioDAO('classeur|classeurdossier');
    
    if (is_null($classeur = $classeurDAO->get(_request ('classeurId', null)))
     || is_null($dossierId = _request ('dossierId', null))) {
       
      return CopixActionGroup::process ('generictools|Messages::getError',
   		  array ('message' => CopixI18N::get ('kernel|kernel.error.errorOccurred'), 'back' => CopixUrl::get('')));
 	  }
 	  elseif (_currentUser()->getExtra('type') != 'USER_ENS') {
 	    
 	    return CopixActionGroup::process ('genericTools|Messages::getError', 
	      array ('message'=> CopixI18N::get ('kernel|kernel.error.noRights'), 'back' => CopixUrl::get('classeur||voirContenu', array('classeurId' => $classeur->id))));
 	  }
 	  
 	  $path2classeur = realpath('./static/classeur').'/'.$classeur->id.'-'.$classeur->cle;
 	  if ($dossierId != 0) {
 	    
      $album        = $dossierDAO->get($dossierId);
      $albumType    = 'dossier';
      $path2album   = $path2classeur.'/'.$album->id.'-'.$album->cle;
      $path2public  = CopixUrl::get ().'static/classeur/'.$classeur->id.'-'.$classeur->cle.'/'.$album->id.'-'.$album->cle;
    }
    else {
      
      $album        = $classeur;
      $albumType    = 'classeur';
      $path2album   = $path2classeur;
      $path2public  = CopixUrl::get ().'static/classeur/'.$album->id.'-'.$album->cle;
    }
    
    if ($handle = opendir($path2album.'/images/')) {
      
      while (($file = readdir($handle)) !== false) {
        
         if ($file != '.' && $file != '..') {
           
           unlink($path2album.'/images/'.$file);
         }
      }
      
      closedir($handle);
      rmdir($path2album.'/images/');
      unlink($path2album.'/index.html');
    }    
    
    $album->date_publication = null;
		$album->public = 0;
		if ($albumType == 'dossier') {
		  
		  $dossierDAO->update($album);
		}
		else {
		  
		  $classeurDAO->update($album);
		}
		
		$confirmMessage = CopixI18N::get ('classeur|classeur.message.confirmUnpublished');
		
		return _arRedirect (CopixUrl::get ('classeur||editerAlbumPublic', array('classeurId' => $classeur->id, 'dossierId' => $dossierId, 'confirmMessage' => $confirmMessage)));
  }
  
  /**
   * AJAX - Met à jour l'état de l'arbre des classeurs
   */
  public function processSauvegardeEtatArbreClasseurs () {
    
    if (is_null ($id = _request ('id', null))) {
      
      return new CopixActionReturn (CopixActionReturn::HTTPCODE, 
        array('Content-Type: text/plain; charset=utf-8', 'HTTP/1.1 404 Not found'), CopixI18N::get ('kernel|kernel.error.errorOccurred'));
    }
    
    _classInclude ('classeur|classeurservice');
    ClasseurService::setClasseursTreeState ($id);
    
    return new CopixActionReturn (CopixActionReturn::HTTPCODE, 
      array('Content-Type: text/html; charset=utf-8', 'HTTP/1.1 200 OK'), CopixI18N::get ('classeur|classeur.message.stateChanged'));
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
	 * Affichage du classeur en popup pour intégration dans les autres modules
	 */
  public function processGetClasseurPopup () {
    
    $classeurDAO = _ioDAO('classeur|classeur');
    if (is_null($ppo->classeur = $classeurDAO->get(_request ('classeurId', null)))) {
       
      return CopixActionGroup::process ('generictools|Messages::getError',
   		  array ('message' => CopixI18N::get ('kernel|kernel.error.errorOccurred'), 'back' => CopixUrl::get('')));
 	  }
 	  
 	  $ppo->dossierId = _request('dossierId', 0);
 	  $ppo->field     = _request('field', null);
		$ppo->format    = _request('format', null);
 	  
 	  _classInclude('classeur|classeurService');
 	  $dossierDAO = _ioDAO('classeur|classeurdossier');
		$fichierDAO = _ioDAO('classeur|classeurfichier');
		
		// Récupération des dossiers & des fichiers / favoris
		$ppo->dossiers = $dossierDAO->getEnfantsDirects($ppo->classeur->id, $ppo->dossierId)->fetchAll();
		$ppo->fichiers = $fichierDAO->getParDossier($ppo->classeur->id, $ppo->dossierId)->fetchAll();
		
		// Récupération des classeurs accessibles à l'utilisateur
    $classeurIds = array();
    $nodes = Kernel::getMyNodes (_currentUser()->getExtra('type'), _currentUser()->getExtra('id'));
    foreach ($nodes as $node) {
      
      $modules = Kernel::getModEnabled($node->type, $node->id, _currentUser()->getExtra('type'), _currentUser()->getExtra('id'));
      foreach ($modules as $module) {
        if ($module->module_type == 'MOD_CLASSEUR') {
          // Identification du classeur personnel de l'utilisateur
          if (strpos($module->node_type, 'USER_') !== false 
            && ($module->node_type == _currentUser()->getExtra('type') && $module->node_id == _currentUser()->getExtra('id'))) {
                
                $ppo->classeurPersonnel = $module->module_id;
                $classeurIds[] = $module->module_id;
          }
          elseif (!strpos($module->node_type, 'USER_')
            && Kernel::getLevel('MOD_CLASSEUR', $module->module_id) > PROFILE_CCV_NONE) {
            
            $classeurIds[] = $module->module_id;
          }
        }
      }
    }
    
    // Dédoublonnage des classeurs (responsables avec plusieurs enfants)
    $classeurIds = array_unique($classeurIds);
    
    $ppo->classeurs = array();
    $classeurDAO = _ioDAO('classeur|classeur');
    foreach ($classeurIds as $classeurId) {
      
      $classeur = $classeurDAO->get($classeurId);
      $ppo->classeurs[] = $classeur;
    }
		
		CopixHTMLHeader::addJSLink (_resource('js/iconito/module_classeur.js')); 
 	  
 	  return _arPPO ($ppo, array ('template'=>'classeur_popup.tpl', 'mainTemplate'=>'main|main_popup.php'));
  }
  
  /**
   * Méthode de redirection avec l'identifiant du classeur
   */
  public function go () {

    return _arRedirect (CopixUrl::get ('classeur||voirContenu', array('classeurId' => _request ('id', null))));
	}
}