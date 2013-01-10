<?php

/**
* @package    Iconito
* @subpackage Classeur
* @author     Jérémy FOURNAISE <jeremy.fournaise@isics.fr>
*/

class ActionGroupDefault extends enicActionGroup
{
  public function beforeAction ($actionName)
  {
    // Contrôle d'accès au module
    if (!is_null($classeurId = _request ('classeurId', _request('id', null)))
      && ($actionName != 'sauvegardeEtatArbreClasseurs' && $actionName != 'sauvegardeEtatArbreDossiers')) {

      if (Kernel::getLevel('MOD_CLASSEUR', $classeurId) < PROFILE_CCV_READ) {

        return CopixActionGroup::process ('genericTools|Messages::getError', array ('message'=> CopixI18N::get ('kernel|kernel.error.noRights'), 'back' => CopixUrl::get('classeur||voirContenu', array('classeurId' => $classeurId))));
      }
    }

    $this->addJs('js/iconito/module_classeur.js');
  }

  /**
     * Affichage principal
     */
  public function processVoirContenu ()
  {
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
      $ppo->withSubLockers = Kernel::getLevel('MOD_CLASSEUR', $ppo->classeurId) >= PROFILE_CCV_PUBLISH ? true : false;

    $ppo->conf_ModClasseur_upload = (CopixConfig::exists ('default|conf_ModClasseur_upload')) ? CopixConfig::get ('default|conf_ModClasseur_upload') : 0;

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

      // Si type de vue spécifié, on le met en session et on le stocke en BDD
      $kernelConfUserDAO = _ioDAO('kernel|kernel_conf_user');
      if (!is_null($vue) && ($vue == 'liste' || $vue == 'vignette')) {

        _sessionSet('classeur|typeVue', $vue);

        $confVue = $kernelConfUserDAO->getByPathAndUserId('/module_classeur/vue', _currentUser()->getExtra('id'));
        if ($confVue) {

          $confVue->value = $vue;
          $kernelConfUserDAO->update ($confVue);
        } else {

          $kernelConfUser = _record ('kernel|kernel_conf_user');

          $kernelConfUser->path = '/module_classeur/vue';
          $kernelConfUser->id_dbuser = _currentUser()->getExtra('id');
          $kernelConfUser->value = $vue;

          $kernelConfUserDAO->insert ($kernelConfUser);
        }
      }

      // Récupération de la vue en session ou BDD
      if (!is_null(_sessionGet('classeur|typeVue'))) {

        $ppo->vue = _sessionGet('classeur|typeVue');
      } else {

        $confVue = $kernelConfUserDAO->getByPathAndUserId('/module_classeur/vue', _currentUser()->getExtra('id'));
        if ($confVue) {

          _sessionSet('classeur|typeVue', $vue);
          $ppo->vue = _sessionGet('classeur|typeVue');
        } else {

          _sessionSet('classeur|typeVue', 'liste');

          $kernelConfUser = _record ('kernel|kernel_conf_user');

          $kernelConfUser->path = '/module_classeur/vue';
          $kernelConfUser->id_dbuser = _currentUser()->getExtra('id');
          $kernelConfUser->value = 'liste';

          $kernelConfUserDAO->insert ($kernelConfUser);
        }
      }

        // Si tri spécifié, mise en session
        if (!is_null ($triColonne)) {

          ClasseurService::setContentSort ($triColonne, $triDirection);
        }

        // Ouverture du dossier courant dans l'arborescence
        if ($ppo->dossierId != 0) {

          classeurService::openTree($ppo->classeurId, $ppo->dossierId);
        }

        $modParentInfo = Kernel::getModParentInfo('MOD_CLASSEUR', $ppo->classeurId);
      $ppo->TITLE_PAGE = $modParentInfo['nom'];

    $classeurDAO = _ioDAO('classeur|classeur');
    $folderDAO   = _ioDAO('classeur|classeurdossier');
    $fichierDAO  = _ioDAO('classeur|classeurfichier');
    $classeur    = $classeurDAO->get($ppo->classeurId);
    $folder      = $folderDAO->get ($classeur->upload_db);
    if( $ppo->conf_ModClasseur_upload && $classeur->upload_fs ) {

        $nomClasseur = $classeur->id.'-'.$classeur->cle;
        // $extension  = strtolower(strrchr($fichier->fichier, '.'));
        // $nomFichier = $fichier->id.'-'.$fichier->cle.$extension;
        $path = realpath('./upload').'/'.$classeur->upload_fs;

        if($classeur->upload_fs && glob($path . '/*')) foreach(glob($path . '/*') as $file) {
            if (is_file($file)) {

                $fichier = _record('classeur|classeurfichier');

                $title = Kernel::stripText($file);

                $fichier->classeur_id   = $classeur->id;
                $fichier->dossier_id    = $classeur->upload_db; // TODO : Verifier existance
                $fichier->titre         = substr(basename($file), 0, 63);
                $fichier->commentaire   = '';
                $fichier->fichier       = basename($file);
                $fichier->taille        = file_exists($file) ? filesize($file) : 0;
                $fichier->type          = strtoupper(substr(strrchr($file, '.'), 1));
                $fichier->cle           = classeurService::createKey();
                $fichier->date_upload   = date('Y-m-d H:i:s');
                $fichier->user_type     = _currentUser()->getExtra('type');
                $fichier->user_id       = _currentUser()->getExtra('id');

                $fichierDAO->insert($fichier);

                $nomClasseur = $classeur->id.'-'.$classeur->cle;
                $nomFichier = $fichier->id.'-'.$fichier->cle;
                $extension = strtolower(strrchr($file, '.'));

                $dir = realpath('./static/classeur').'/'.$classeur->id.'-'.$classeur->cle.'/';

                // Déplacement du fichier temporaire dans le classeur
                copy($file, $dir.$fichier->id.'-'.$fichier->cle.$extension);

                // Suppression du fichier temporaire
                unlink($file);
            }

            if($classeur->upload_db) classeurService::updateFolderInfos($folder);
        }

// print_r($classeur);
// upload_fs
    }

    return _arPPO ($ppo, 'voir_contenu.tpl');
  }

  /**
     * DOSSIER - Edition d'un dossier
     */
  public function processEditerDossier ()
  {
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
    } else {

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

      // Création dans un casier non autorisé
      if ($ppo->parent->casier && $ppo->dossier->id == '') {

        return CopixActionGroup::process ('generictools|Messages::getError',
                array ('message' => CopixI18N::get ('kernel|kernel.error.errorOccurred'), 'back' => CopixUrl::get('classeur||voirContenu', array('classeurId' => $ppo->classeurId, 'dossierId' => $parentId))));
      }

      $ppo->path    = $ppo->parent->getPath();
    } else {

      $classeurDAO  = _ioDAO('classeur|classeur');
      $classeur     = $classeurDAO->get($ppo->classeurId);
      $ppo->path    = '/'.$classeur->titre.'/';
    }

    if (CopixRequest::isMethod ('post')) {

      $ppo->dossier->nom  = _request('dossier_nom', null);

      // Traitement des erreurs
      $ppo->erreurs = array ();

      if ($ppo->dossier->nom == '') {

        $ppo->erreurs[] = CopixI18N::get ('classeur|classeur.error.noName');
      }

      if (!empty ($ppo->erreurs)) {

        $modParentInfo = Kernel::getModParentInfo('MOD_CLASSEUR', $ppo->classeurId);
          $ppo->TITLE_PAGE = $modParentInfo['nom'];

        return _arPPO ($ppo, array ('template' => 'editer_dossier.tpl'));
      }

      if ($ppo->dossier->id == '') {

        $ppo->dossier->classeur_id    = $ppo->classeurId;
        $ppo->dossier->parent_id      = _request('parentId', 0);
        $ppo->dossier->cle            = classeurService::createKey();
        $ppo->dossier->date_creation  = date('Y-m-d H:i:s');
        $ppo->dossier->user_type      = _currentUser()->getExtra('type');
        $ppo->dossier->user_id        = _currentUser()->getExtra('id');
        $ppo->dossier->casier         = 0;

        // Insertion de l'enregistrement "dossier"
        $dossierDAO->insert ($ppo->dossier);

        // Mise à jour des informations du dossier (nombre de fichiers, taille, ...)
        classeurService::updateFolderInfos($ppo->dossier);

        $confirmMessage = CopixI18N::get ('classeur|classeur.message.confirmCreation');
      } else {

        // Mise à jour de l'enregistrement "dossier"
        $dossierDAO->update ($ppo->dossier);

        $confirmMessage = CopixI18N::get ('classeur|classeur.message.confirmUpdate');
      }

      return _arRedirect (CopixUrl::get ('classeur||voirContenu', array('classeurId' => $ppo->classeurId, 'dossierId' => isset($ppo->parent) ? $ppo->parent->id : 0, 'confirmMessage' => $confirmMessage)));
    }

    $modParentInfo = Kernel::getModParentInfo('MOD_CLASSEUR', $ppo->classeurId);
      $ppo->TITLE_PAGE = $modParentInfo['nom'];

    return _arPPO ($ppo, array ('template' => 'editer_dossier.tpl'));
  }

  /**
     * DOSSIER - Déplacement d'un dossier
     */
  public function processDeplacerDossier ()
  {
    $dossierDAO = _ioDAO('classeur|classeurdossier');
       $fichierDAO = _ioDAO('classeur|classeurfichier');

    if (is_null($ppo->classeurId     = _request ('classeurId', null))
     || is_null($ppo->dossierParent  = $dossierDAO->get(_request('parentId', null)))
     || is_null($ppo->dossier        = $dossierDAO->get (_request ('dossierId', null)))) {

      return CopixActionGroup::process ('generictools|Messages::getError',
             array ('message' => CopixI18N::get ('kernel|kernel.error.errorOccurred'), 'back' => CopixUrl::get('')));
       } elseif (Kernel::getLevel('MOD_CLASSEUR', $ppo->classeurId) < PROFILE_CCV_PUBLISH
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
      } elseif ($ppo->destinationType == 'dossier' && classeurService::isDescendantOf($dossierDestination, $ppo->dossier)) {

        $ppo->erreurs[] = CopixI18N::get ('classeur|classeur.error.destinationUnauthorized');
      }

      if (!empty ($ppo->erreurs)) {

        $modParentInfo = Kernel::getModParentInfo('MOD_CLASSEUR', $ppo->classeurId);
          $ppo->TITLE_PAGE = $modParentInfo['nom'];

        return _arPPO ($ppo, array ('template' => 'deplacer_dossier.tpl'));
      }

      // Déplacement des dossiers
      classeurService::moveFolder($ppo->dossier, $ppo->destinationType, $ppo->destinationId);

      if ($ppo->dossierParent) {

        // Maj des informations du dossier
        classeurService::updateFolderInfos($ppo->dossierParent);
      }

      // Redirection
      $confirmMessage = CopixI18N::get ('classeur|classeur.message.confirmMove');
      if ($ppo->destinationType == 'dossier') {

        classeurService::updateFolderInfos($dossierDestination);

        return _arRedirect (CopixUrl::get ('classeur||voirContenu', array('classeurId' => $dossierDestination->classeur_id, 'dossierId' => $ppo->destinationId, 'confirmMessage' => $confirmMessage)));
      } else {

        return _arRedirect (CopixUrl::get ('classeur||voirContenu', array('classeurId' => $ppo->destinationId, 'confirmMessage' => $confirmMessage)));
      }
    }

    $modParentInfo = Kernel::getModParentInfo('MOD_CLASSEUR', $ppo->classeurId);
      $ppo->TITLE_PAGE = $modParentInfo['nom'];

       return _arPPO ($ppo, array ('template' => 'deplacer_dossier.tpl'));
  }

  /**
     * DOSSIER - Suppression d'un dossier
     */
  public function processSupprimerDossier ()
  {
    _classInclude('classeur|classeurservice');
      $dossierDAO = _ioDAO ('classeur|classeurdossier');
      $fichierDAO = _ioDAO ('classeur|classeurfichier');

    if (is_null($classeurId = _request ('classeurId'))
     || is_null($dossier  = $dossierDAO->get(_request ('dossierId', null)))) {

        return CopixActionGroup::process ('generictools|Messages::getError',
              array ('message' => CopixI18N::get ('kernel|kernel.error.errorOccurred'), 'back' => CopixUrl::get('')));
      } elseif (Kernel::getLevel('MOD_CLASSEUR', $classeurId) < PROFILE_CCV_PUBLISH
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
    public function processEditerFichiers ()
    {
        _classInclude('sysutils|StatsServices');
        $confirmMessage = '';
        
        CopixHTMLHeader::addJSLink(_resource('js/uploadify/jquery.uploadify.v2.1.4.min.js'));
        CopixHTMLHeader::addJSLink(_resource('js/uploadify/swfobject.js'));
        
        $ppo = new CopixPPO();
        $ppo->conf = new CopixPPO();
        $ppo->conf->max_file_size = StatsServices::human2octets(ini_get('upload_max_filesize'));
        
        if (is_null($ppo->classeurId = _request('classeurId'))) {
            return CopixActionGroup::process('generictools|Messages::getError', array(
                'message' => CopixI18N::get('kernel|kernel.error.errorOccurred'),
                'back' => CopixUrl::get('')
            ));
        }
        
        // Récupération du dossier courant
        $dossierDAO = _ioDAO('classeur|classeurdossier');
        $ppo->dossier = null;
        if (!is_null($ppo->dossierId = _request('dossierId', null)) && $ppo->dossierId != 0) {
            if (!$ppo->dossier = $dossierDAO->get($ppo->dossierId)) {
                return CopixActionGroup::process('generictools|Messages::getError', array(
                    'message' => CopixI18N::get('kernel|kernel.error.errorOccurred'),
                    'back' => CopixUrl::get('')
                ));
            }
        }
        
        // Contrôle des droits d'accès
        if ((Kernel::getLevel('MOD_CLASSEUR', $ppo->classeurId) < PROFILE_CCV_MEMBER && $ppo->dossier->casier && $ppo->dossier->parent_id != 0)
            || Kernel::getLevel('MOD_CLASSEUR', $ppo->classeurId) < PROFILE_CCV_MEMBER && !$ppo->dossier->casier) {
            return CopixActionGroup::process('genericTools|Messages::getError', array (
                'message'=> CopixI18N::get('kernel|kernel.error.noRights'),
                'back' => CopixUrl::get('classeur||voirContenu', array('classeurId' => $ppo->classeurId))
            ));
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
                return CopixActionGroup::process('genericTools|Messages::getError', array(
                    'message'=> CopixI18N::get('kernel|kernel.error.noRights'),
                    'back' => CopixUrl::get('classeur||voirContenu', array('classeurId' => $ppo->classeurId))
                    
                ));
            }
        }
        
        // Récupération du classeur
        $classeurDAO = _ioDAO('classeur|classeur');
        $classeur = $classeurDAO->get($ppo->classeurId);
        
        // Récupération du path
        if (!is_null($ppo->dossier)) {
          $ppo->path = $ppo->dossier->getPath();
        } else {
          $ppo->path = '/'.$classeur->titre.'/';
        }
        
        // Dossier temporaire pour l'upload des fichiers
        _classInclude('classeur|classeurService');
        
        $dossierTmp = classeurService::getTmpFolder();
        $ppo->dossierTmp = $dossierTmp.classeurService::createKey();
        $ppo->maxSizeLimit = Kernel::return_bytes(ini_get('upload_max_filesize'));
        
        // Soumission du formulaire
        if (CopixRequest::isMethod ('post')) {
            $fichierDAO = _ioDAO('classeur|classeurfichier');
            // S'il s'agit d'une modification de fichier
            if (!is_null(_request('fichierId', null))) {
                $ppo->fichier->titre       = _request('fichier_titre', null);
                $ppo->fichier->commentaire = _request('fichier_commentaire', null);

                // Contrôle upload du fichier
                if (!is_null ($_FILES['fichier']['tmp_name']) && is_uploaded_file($_FILES['fichier']['tmp_name'])) {
                    $dir = realpath('./static/classeur').'/'.$classeur->id.'-'.$classeur->cle.'/';
                    $oldExtension = strtolower(strrchr($ppo->fichier->fichier, '.'));
                    $fichierPhysique = $dir.$ppo->fichier->id.'-'.$ppo->fichier->cle.$oldExtension;
                    // Suppression de l'ancien fichier
                    if (file_exists($fichierPhysique)) {
                        unlink($fichierPhysique);
                    }
                    
                    $extension = strtolower(strrchr($_FILES['fichier']['name'], '.'));
                    // Nom particulier dans le cas d'un casier (nom de l'élève présent dans le nom du fichier)
                    if (isset($ppo->dossier) && $ppo->dossier->casier) {
                      $user = Kernel::getUserInfo($ppo->fichier->user_type, $ppo->fichier->user_id);
                      $ppo->fichier->fichier = $ppo->fichier->titre.'_'.$user['prenom'].'_'.$user['nom'].$extension;
                    } else {
                      $ppo->fichier->fichier = $_FILES['fichier']['name'];
                    }
                    
                    $ppo->fichier->taille = filesize($_FILES['fichier']['tmp_name']);
                    $ppo->fichier->type   = strtoupper(substr(strrchr($_FILES['fichier']['name'], '.'), 1));
                    
                    $fichierPhysique = $dir.$ppo->fichier->id.'-'.$ppo->fichier->cle.$extension;
                    move_uploaded_file ($_FILES['fichier']['tmp_name'], $fichierPhysique);
                } else {
                
                    // Nom particulier dans le cas d'un casier (nom de l'élève présent dans le nom du fichier)
                    if (isset($ppo->dossier) && $ppo->dossier->casier) {
                        $dir = realpath('./static/classeur').'/'.$classeur->id.'-'.$classeur->cle.'/';
                        $extension = strtolower(strrchr($ppo->fichier->fichier, '.'));
                        $user = Kernel::getUserInfo($ppo->fichier->user_type, $ppo->fichier->user_id);
                        $ppo->fichier->fichier = $ppo->fichier->titre.'_'.$user['prenom'].'_'.$user['nom'].$extension;
                    }
                }
                
                // Mise à jour de l'enregistrement fichier
                $fichierDAO->update($ppo->fichier);
                $confirmMessage = CopixI18N::get ('classeur|classeur.message.confirmUpdate');
            } else {
                // Récupération du dossier temporaire
                $ppo->dossierTmp = _request('dossierTmp', null);
                
                // Traitement des erreurs
                $ppo->erreurs = array ();
                
                // Récupération des fichiers uploadés via uploadify
                $uploadedFiles = array();
                $uploadedFiles = ClasseurService::getFilesInTmpFolder($uploadedFiles, $ppo->dossierTmp);
                if (!is_uploaded_file ($_FILES['fichier']['tmp_name']) && empty($uploadedFiles)) {
                    $ppo->erreurs[] = CopixI18N::get ('classeur|classeur.error.noFiles');
                }

                if (!is_null(_request('fichier_titre', null)) && strlen(_request('fichier_titre')) > 64) {
                    $ppo->erreurs[] = CopixI18N::get ('classeur|classeur.error.titleTooLong', array('size' => 64));
                }
                
                if (!empty ($ppo->erreurs)) {
                    $modParentInfo = Kernel::getModParentInfo('MOD_CLASSEUR', $ppo->classeurId);
                    $ppo->TITLE_PAGE = $modParentInfo['nom'];
                    
                    return _arPPO ($ppo, array ('template' => 'editer_fichiers.tpl'));
                }
                
                // Création du répertoire
                $dir = realpath('./static/classeur').'/'.$classeur->id.'-'.$classeur->cle.'/';
                if (!file_exists($dir)) {
                    mkdir($dir, 0755, true);
                }
                
                // Traitement de l'upload multiple (prédomine sur l'upload simple)
                if (!empty($uploadedFiles)) {
                    foreach ($uploadedFiles as $folder) {
                        foreach ($folder['files'] as $file) {
                            ClasseurService::uploadFile($ppo->dossierTmp.'/'.$file, $file, $classeur, $ppo->dossier);
                        }
                    }
                } else {
                    // S'il s'agit d'une archive ZIP
                    if (strtolower(strrchr($_FILES['fichier']['name'], '.')) == '.zip' && _request('with_decompress', false)) {
                        $zip = new ZipArchive;
                        if ($zip->open($_FILES['fichier']['tmp_name']) === true) {
                            // Décompression de l'archive dans le dossier temporaire
                            $zip->extractTo($ppo->dossierTmp);
                            $zip->close();
                            
                            // Copie des fichiers provenant de l'archive ZIP
                            $correspondanceDossiers = array();
                            $datas = array();
                            $datas = classeurService::getFilesInTmpFolder($datas, $ppo->dossierTmp);
                            foreach ($datas as $dossierParent => $data) {
                                if (!empty($data['folders'])) {
                                    foreach($data['folders'] as $folder) {
                                        $dossier = _record ('classeur|classeurdossier');
                                        
                                        $dossier->nb_dossiers   = 0;
                                        $dossier->nb_fichiers   = 0;
                                        $dossier->taille        = 0;
                                        $dossier->classeur_id   = $classeur->id;
                                        $dossier->parent_id     = $dossierParent == $ppo->dossierTmp ? $ppo->dossierId : $correspondanceDossiers[$dossierParent]->id;
                                        $dossier->nom           = $folder;
                                        $dossier->cle           = classeurService::createKey();
                                        $dossier->date_creation = date('Y-m-d H:i:s');
                                        $dossier->user_type     = _currentUser()->getExtra('type');
                                        $dossier->user_id       = _currentUser()->getExtra('id');
                                        $dossier->casier        = 0;
                                        
                                        $dossierDAO->insert($dossier);
                                        classeurService::updateFolderInfos($dossier);
                                        $correspondanceDossiers[$dossierParent.'/'.$folder] = $dossier;
                                    }
                                }
                                
                                if (!empty($data['files'])) {
                                    foreach ($data['files'] as $file) {
                                        $fichier = _record('classeur|classeurfichier');
                                        $extension = strtolower(strrchr($file, '.'));
                                        $filename = substr($file, 0, strrpos($file, '.'));
                                        
                                        $title = Kernel::stripText($filename).$extension;
                                        
                                        $fichier->classeur_id   = $classeur->id;
                                        $fichier->dossier_id    = $dossierParent == $ppo->dossierTmp ? $ppo->dossierId : $correspondanceDossiers[$dossierParent]->id;
                                        $fichier->titre         = substr($title, 0, 63);
                                        $fichier->commentaire   = '';
                                        $fichier->fichier       = $title;
                                        $fichier->taille        = file_exists($dossierParent.'/'.$file) ? filesize($dossierParent.'/'.$file) : 0;
                                        $fichier->type          = strtoupper(substr(strrchr($file, '.'), 1));
                                        $fichier->cle           = classeurService::createKey();
                                        $fichier->date_upload   = date('Y-m-d H:i:s');
                                        $fichier->user_type     = _currentUser()->getExtra('type');
                                        $fichier->user_id       = _currentUser()->getExtra('id');
                                        
                                        $fichierDAO->insert($fichier);
                                        
                                        if (isset($correspondanceDossiers[$dossierParent])) {
                                            classeurService::updateFolderInfos($correspondanceDossiers[$dossierParent]);
                                        }
                                        
                                        $nomClasseur = $classeur->id.'-'.$classeur->cle;
                                        $nomFichier = $fichier->id.'-'.$fichier->cle;
                                        
                                        // Déplacement du fichier temporaire dans le classeur
                                        copy($dossierParent.'/'.$file, $dir.$fichier->id.'-'.$fichier->cle.$extension);
                                        // Suppression du fichier temporaire
                                        unlink($dossierParent.'/'.$file);
                                    }
                                }
                            }
                        }
                    } else {
                        $fichier = ClasseurService::uploadFile($_FILES['fichier']['tmp_name'], $_FILES['fichier']['name'], $classeur, $ppo->dossier);
                        if (null !== $ppo->dossier && $ppo->dossier->casier) {
                            // Minimail de confirmation dans le cas de l'upload d'un fichier dans un casier
                            classeurService::sendLockerUploadConfirmation($fichier->fichier);
                            $confirmMessage = CopixI18N::get('classeur|classeur.message.confirmUploadLockerMessage', array($fichier->fichier));
                        }
                    }
                }
            }
            
            // Message de confirmation générique si non déjà défini
            if ('' == $confirmMessage) {
                $confirmMessage = CopixI18N::get ('classeur|classeur.message.success');
            }
            
            // Mise à jour des informations du dossier parent
            if (null !== $ppo->dossier) {
                classeurService::updateFolderInfos($ppo->dossier);
            }
            
            // Suppression du dossier TMP
            classeurService::rmdir_recursive($ppo->dossierTmp);
            
            return _arRedirect (CopixUrl::get ('classeur||voirContenu', array('classeurId' => $ppo->classeurId, 'dossierId' => $ppo->dossierId, 'confirmMessage' => $confirmMessage)));
        }
        
        $modParentInfo = Kernel::getModParentInfo('MOD_CLASSEUR', $ppo->classeurId);
        $ppo->TITLE_PAGE = $modParentInfo['nom'];
        
        return _arPPO ($ppo, array ('template' => 'editer_fichiers.tpl'));
    }

  /**
     * FICHIER - Déplacement d'un fichier
     */
  public function processDeplacerFichier ()
  {
      $ppo = new CopixPPO();
      
    $classeurDAO = _ioDAO('classeur|classeur');
    $dossierDAO  = _ioDAO('classeur|classeurdossier');
       $fichierDAO  = _ioDAO('classeur|classeurfichier');

    if (is_null($ppo->classeur = $classeurDAO->get(_request ('classeurId', null)))
     || is_null($ppo->fichier = $fichierDAO->get(_request ('fichierId', null)))) {

      return CopixActionGroup::process ('generictools|Messages::getError',
             array ('message' => CopixI18N::get ('kernel|kernel.error.errorOccurred'), 'back' => CopixUrl::get('')));
       } elseif (Kernel::getLevel('MOD_CLASSEUR', $ppo->classeur->id) < PROFILE_CCV_PUBLISH
        && ($ppo->fichier->user_type != _currentUser()->getExtra('type')
          || $ppo->fichier->user_id != _currentUser()->getExtra('id'))) {

        return CopixActionGroup::process ('genericTools|Messages::getError',
          array ('message'=> CopixI18N::get ('kernel|kernel.error.noRights'), 'back' => CopixUrl::get('classeur||voirContenu', array('classeurId' => $ppo->classeur->id))));
      }

      $ppo->dossierId = _request ('dossierId', null);
      if (!is_null ($ppo->dossierId)) {

        $ppo->dossier = $dossierDAO->get($ppo->dossierId);
      }

      // Droits d'accès pour le déplacement d'un fichier dans les sous dossiers d'un casier
      $ppo->withSubLockers = Kernel::getLevel('MOD_CLASSEUR', $ppo->classeur->id) >= PROFILE_CCV_PUBLISH ? true : false;

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
      } elseif ($ppo->destinationType == 'dossier' && $ppo->destinationId == $ppo->fichier->dossier_id) {

        $ppo->erreurs[] = CopixI18N::get ('classeur|classeur.error.destinationUnauthorized');
      }

      if (!empty ($ppo->erreurs)) {

        $modParentInfo = Kernel::getModParentInfo('MOD_CLASSEUR', $ppo->classeurId);
          $ppo->TITLE_PAGE = $modParentInfo['nom'];

        return _arPPO ($ppo, array ('template' => 'deplacer_fichier.tpl'));
      }

      // Déplacement du fichier
      classeurService::moveFile($ppo->fichier, $ppo->destinationType, $ppo->destinationId);

      // Maj des informations des dossiers
      if ($ppo->dossier) {

        classeurService::updateFolderInfos($ppo->dossier);
      }

      // Redirection
      $confirmMessage = CopixI18N::get ('classeur|classeur.message.confirmMove');
      if ($ppo->destinationType == 'dossier') {

        classeurService::updateFolderInfos($dossierDestination);
        return _arRedirect (CopixUrl::get ('classeur||voirContenu', array('classeurId' => $dossierDestination->classeur_id, 'dossierId' => $ppo->destinationId, 'confirmMessage' => $confirmMessage)));
      } else {

        return _arRedirect (CopixUrl::get ('classeur||voirContenu', array('classeurId' => $ppo->destinationId, 'confirmMessage' => $confirmMessage)));
      }
    }

    $modParentInfo = Kernel::getModParentInfo('MOD_CLASSEUR', $ppo->classeur->id);
      $ppo->TITLE_PAGE = $modParentInfo['nom'];

       return _arPPO ($ppo, array ('template' => 'deplacer_fichier.tpl'));
  }

  /**
     * FICHIER - Suppression d'un fichier
     */
  public function processSupprimerFichier ()
  {
    _classInclude('classeur|classeurservice');
    $dossierDAO = _ioDAO ('classeur|classeurdossier');
      $fichierDAO = _ioDAO ('classeur|classeurfichier');

    if (is_null($classeurId = _request ('classeurId'))
     || is_null($fichier  = $fichierDAO->get(_request ('fichierId', null)))) {

        return CopixActionGroup::process ('generictools|Messages::getError',
              array ('message' => CopixI18N::get ('kernel|kernel.error.errorOccurred'), 'back' => CopixUrl::get('')));
      } elseif (Kernel::getLevel('MOD_CLASSEUR', $classeurId) < PROFILE_CCV_PUBLISH
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
  public function processEditerFavori ()
  {
    $classeurDAO = _ioDAO('classeur|classeur');

    if (is_null($ppo->classeur = $classeurDAO->get(_request ('classeurId', null)))) {

        return CopixActionGroup::process ('generictools|Messages::getError',
              array ('message' => CopixI18N::get ('kernel|kernel.error.errorOccurred'), 'back' => CopixUrl::get('')));
      } elseif (Kernel::getLevel('MOD_CLASSEUR', $ppo->classeur->id) < PROFILE_CCV_MEMBER) {

        return CopixActionGroup::process ('genericTools|Messages::getError',
          array ('message'=> CopixI18N::get ('kernel|kernel.error.noRights'), 'back' => CopixUrl::get('classeur||voirContenu', array('classeurId' => $ppo->classeur->id))));
      }

      // Récupération du dossier pour définition du path
    $dossierDAO = _ioDAO('classeur|classeurdossier');
    if (!is_null($ppo->dossierId  = _request('dossierId', null)) && $ppo->dossierId != 0) {

      $ppo->dossier = $dossierDAO->get($ppo->dossierId);

      // Ajout d'un favori dans un casier non autorisé
      if ($ppo->dossier->casier) {

        return CopixActionGroup::process ('generictools|Messages::getError',
                array ('message' => CopixI18N::get ('kernel|kernel.error.errorOccurred'), 'back' => CopixUrl::get('')));
      }

      $ppo->path = $ppo->dossier->getPath();
    } else {

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

        _classInclude('classeur|classeurservice');
      $fichierDAO = _ioDAO('classeur|classeurfichier');

      $ppo->lien = _request('favori_adresse', null);

      if (is_null($favoriId = _request('favoriId', null))) {

        $ppo->favori = _record('classeur|classeurfichier');
      } else {

        $ppo->favori = $fichierDAO->get($favoriId);
      }

      $ppo->favori->classeur_id   = $ppo->classeur->id;
      $ppo->favori->dossier_id    = !is_null($dossierId = _request('dossierId', null)) ? $dossierId : 0;
      $ppo->favori->titre         = _request('favori_titre', null);
      $ppo->favori->commentaire   = null;
      $ppo->favori->fichier       = Kernel::simpleName($ppo->favori->titre).'.web';
      $ppo->favori->taille        = 0;
      $ppo->favori->type          = 'Favori';
      $ppo->favori->cle           = classeurService::createKey();
      $ppo->favori->date_upload   = date('Y-m-d H:i:s');
      $ppo->favori->user_type     = _currentUser()->getExtra('type');
      $ppo->favori->user_id       = _currentUser()->getExtra('id');

      // Traitement des erreurs
      $ppo->erreurs = array ();

      if (_request('favori_titre', null) == '') {

        $ppo->erreurs[] = CopixI18N::get ('classeur|classeur.error.noTitle');
      }
      if (_request('favori_adresse', null) == '' || _request('favori_adresse', null) == 'http://') {

        $ppo->erreurs[] = CopixI18N::get ('classeur|classeur.error.noAddress');
      }

      if (!empty ($ppo->erreurs)) {

        $modParentInfo = Kernel::getModParentInfo('MOD_CLASSEUR', $ppo->classeur->id);
          $ppo->TITLE_PAGE = $modParentInfo['nom'];

        return _arPPO ($ppo, array ('template' => 'editer_favori.tpl'));
      }

      if (is_null($ppo->favori->id)) {

        $fichierDAO->insert ($ppo->favori);
        $confirmMessage = CopixI18N::get ('classeur|classeur.message.confirmCreation');
      } else {

        $fichierDAO->update ($ppo->favori);
        $confirmMessage = CopixI18N::get ('classeur|classeur.message.confirmUpdate');
      }

      // Création du fichier
      $contenu = classeurService::generateWebFile($ppo->lien);

      $extension  = strtolower(strrchr($ppo->favori->fichier, '.'));
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

      return _arRedirect (CopixUrl::get ('classeur||voirContenu', array('classeurId' => $ppo->classeur->id, 'dossierId' => $ppo->favori->dossier_id, 'confirmMessage' => $confirmMessage)));
    }

    $modParentInfo = Kernel::getModParentInfo('MOD_CLASSEUR', $ppo->classeur->id);
      $ppo->TITLE_PAGE = $modParentInfo['nom'];

      return _arPPO ($ppo, array ('template' => 'editer_favori.tpl'));
  }

  /**
     * ACTION DE MASSE - Suppression d'un ou de plusieurs fichiers / dossiers
     */
  public function processSupprimerContenu ()
  {
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
         if (!$dossier->isCasierPrincipal ()) {

           classeurService::deleteFolder($dossier);
         }
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
  public function processDeplacerContenu ()
  {
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
         } else {

           $ppo->dossierIds = $dossierIds;
           $dossierIds = explode ($ppo->dossierIds, ',');
         }
         foreach ($dossierIds as $dossierId) {

        if ($dossier = $dossierDAO->get ($dossierId)) {

             $nomsContenus[] = $dossier->nom;
        }
         }
       }

       // Récupération des identifiants de fichier à déplacer
       $fichierIds = _request ('fichiers', array());
       if (!is_null($fichierIds)) {

         if (is_array($fichierIds)) {

           $ppo->fichierIds = implode($fichierIds, ',');
         } else {

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
       } else {

         $ppo->nomsContenus = _request('fichiersSelectionnes', null);
       }

       if (CopixRequest::isMethod ('post')) {

      _classInclude('classeur|classeurservice');

      $arFichierIds       = !is_null(_request('fichiers', null)) ? explode (',', _request('fichiers', null)) : array();
      $arDossierIds       = !is_null(_request('dossiers', null)) ? explode (',', _request('dossiers', null)) : array();

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
      } elseif ($ppo->destinationType == 'dossier' && in_array($ppo->destinationId, $arDossierIds)) {

        $ppo->erreurs[] = CopixI18N::get ('classeur|classeur.error.destinationUnauthorized');
      }
      if (empty($arFichierIds) && empty($arDossierIds)) {

        $ppo->erreurs[] = CopixI18N::get ('classeur|classeur.error.noContentSelected');
      }

      if (!empty ($ppo->erreurs)) {

        $modParentInfo = Kernel::getModParentInfo('MOD_CLASSEUR', $ppo->classeur->id);
          $ppo->TITLE_PAGE = $modParentInfo['nom'];

        return _arPPO ($ppo, array ('template' => 'deplacer_contenu.tpl'));
      }

      // Déplacement des dossiers
      if (!empty($arDossierIds)) {

        $dossiers = array();
        foreach ($arDossierIds as $arDossierId) {

          $dossier = $dossierDAO->get($arDossierId);
          if (($ppo->destinationType == 'dossier' && !classeurService::isDescendantOf($dossierDestination, $dossier))
            || $ppo->destinationType == 'classeur') {

            if ($dossier = $dossierDAO->get($arDossierId)) {

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
      if ($ppo->dossierParent) {

        classeurService::updateFolderInfos($ppo->dossierParent);
      }

      // Redirection
      $confirmMessage = CopixI18N::get ('classeur|classeur.message.confirmMove');
      if ($ppo->destinationType == 'dossier') {

        classeurService::updateFolderInfos($dossierDestination);

        return _arRedirect (CopixUrl::get ('classeur||voirContenu', array('classeurId' => $dossierDestination->classeur_id, 'dossierId' => $ppo->destinationId, 'confirmMessage' => $confirmMessage)));
      } else {

        return _arRedirect (CopixUrl::get ('classeur||voirContenu', array('classeurId' => $ppo->destinationId, 'confirmMessage' => $confirmMessage)));
      }
    }

    $modParentInfo = Kernel::getModParentInfo('MOD_CLASSEUR', $ppo->classeur->id);
      $ppo->TITLE_PAGE = $modParentInfo['nom'];

       return _arPPO ($ppo, array ('template' => 'deplacer_contenu.tpl'));
  }

  /**
     * ACTION DE MASSE - Copie d'un ou de plusieurs fichiers / dossiers
     */
  public function processCopierContenu ()
  {
    $classeurDAO = _ioDAO('classeur|classeur');
    $dossierDAO  = _ioDAO('classeur|classeurdossier');
    $fichierDAO  = _ioDAO('classeur|classeurfichier');

    if (is_null($ppo->classeur = $classeurDAO->get(_request ('classeurId', null)))
     || is_null($ppo->dossier  = $dossierDAO->get(_request ('dossierId', null)))) {

      return CopixActionGroup::process ('generictools|Messages::getError',
             array ('message' => CopixI18N::get ('kernel|kernel.error.errorOccurred'), 'back' => CopixUrl::get('')));
       }

    $nomsContenus = array();

       // Récupération des identifiants de dossier à copier
       $dossierIds = _request ('dossiers', array());
       if (!is_null($dossierIds)) {

         // Si copie d'un seul dossier, on vérifie s'il s'agit d'un casier et que l'utilisateur à la permission de le copier
         if (count($dossierIds) == 1) {
        $dossier = $dossierDAO->get($dossierIds[0]);

        // Seul l'enseignant peut copier un casier
           if ($dossier->casier && Kernel::getLevel('MOD_CLASSEUR', $ppo->classeur->id) < PROFILE_CCV_PUBLISH) {

             return CopixActionGroup::process ('generictools|Messages::getError',
                 array ('message' => CopixI18N::get ('classeur|classeur.error.copyLocker'), 'back' => CopixUrl::get('classeur||voirContenu', array('classeurId' => $ppo->classeur->id))));
           } else {

             $nomsContenus[] = $dossier->nom;
           }
      } else {

           $ppo->dossierIds = implode($dossierIds, ',');

           foreach ($dossierIds as $dossierId) {

          $dossier = $dossierDAO->get ($dossierId);
             if (!$dossier->casier) {

               $nomsContenus[] = $dossier->nom;
             } else {

               unset($ppo->dossiers[$dossierId]);
             }
           }
         }
       }

       // Récupération des identifiants de fichier à déplacer
       $fichierIds = _request ('fichiers', array());
       if (!is_null($fichierIds)) {

         if (is_array($fichierIds)) {

           $ppo->fichierIds = implode($fichierIds, ',');
         } else {

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
       } else {

         $ppo->nomsContenus = _request('fichiersSelectionnes', null);
       }

       if (CopixRequest::isMethod ('post')) {

      _classInclude('classeur|classeurservice');

      $arFichierIds       = !is_null(_request('fichiers', null)) ? explode (',', _request('fichiers')) : array();
      $arDossierIds       = !is_null(_request('dossiers', null)) ? explode (',', _request('dossiers')) : array();

      if (!is_null($destination = _request('destination', null))) {

        list ($destinationType, $destinationId) = explode('-', $destination);
        if ($destinationType == 'dossier') {

          $dossierDestination = $dossierDAO->get($destinationId);
        }
      }

      // Traitement des erreurs
      $ppo->erreurs = array ();
      if (!isset($destinationType) || !isset($destinationId)
        || is_null($destinationType) || is_null($destinationId)) {

        $ppo->erreurs[] = CopixI18N::get ('classeur|classeur.error.noDestination');
      } elseif ($destinationType == 'dossier' && in_array($destinationId, $arDossierIds)) {

        $ppo->erreurs[] = CopixI18N::get ('classeur|classeur.error.destinationUnauthorized');
      }
      if (empty($arFichierIds) && empty($arDossierIds)) {

        $ppo->erreurs[] = CopixI18N::get ('classeur|classeur.error.noContentSelected');
      }

      if (!empty ($ppo->erreurs)) {

        $modParentInfo = Kernel::getModParentInfo('MOD_CLASSEUR', $ppo->classeur->id);
          $ppo->TITLE_PAGE = $modParentInfo['nom'];

        return _arPPO ($ppo, array ('template' => 'copier_contenu.tpl'));
      }

      // Copie des dossiers
      if (!empty($arDossierIds)) {

        $dossiers = array();
        foreach ($arDossierIds as $arDossierId) {

          $dossier = $dossierDAO->get($arDossierId);
          if (($destinationType == 'dossier' && !classeurService::isDescendantOf($dossierDestination, $dossier))
            || $destinationType == 'classeur') {

            // On ne copie que les dossiers pouvant l'être
            if ($dossier = $dossierDAO->get($arDossierId) && !$dossier->casier) {

              // En cas de copie, le copieur devient le propriétaire de la copie
              $dossier->user_type = _currentUser()->getExtra('type');
              $dossier->user_id   = _currentUser()->getExtra('id');

              classeurService::copyFolder($dossier, $destinationType, $destinationId);
            }
          }
        }
      }

      // Copie des fichiers
      if (!empty($arFichierIds)) {
        foreach ($arFichierIds as $arFichierId) {

          $fichier = $fichierDAO->get($arFichierId);

          // En cas de copie, le copieur devient le propriétaire de la copie
          $fichier->user_type = _currentUser()->getExtra('type');
          $fichier->user_id   = _currentUser()->getExtra('id');

          classeurService::copyFile($fichier, $destinationType, $destinationId);
        }
      }

      // Redirection
      $confirmMessage = CopixI18N::get ('classeur|classeur.message.confirmCopy');
      if ($destinationType == 'dossier') {

        classeurService::updateFolderInfos($dossierDestination);
        return _arRedirect (CopixUrl::get ('classeur||voirContenu',
          array('classeurId' => $dossierDestination->classeur_id, 'dossierId' => $destinationId, 'confirmMessage' => $confirmMessage)));
      } else {

        return _arRedirect (CopixUrl::get ('classeur||voirContenu', array('classeurId' => $destinationId, 'confirmMessage' => $confirmMessage)));
      }
    }

    $modParentInfo = Kernel::getModParentInfo('MOD_CLASSEUR', $ppo->classeur->id);
      $ppo->TITLE_PAGE = $modParentInfo['nom'];

    return _arPPO ($ppo, array ('template' => 'copier_contenu.tpl'));
  }

  /**
     * ACTION DE MASSE - Téléchargement d'un ou de plusieurs fichiers / dossiers (Archive ZIP)
     */
  public function processTelechargerContenu ()
  {
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

       $classeurDAO = _ioDAO('classeur|classeur');
    $classeur    = $classeurDAO->get($ppo->classeurId);

    $cptFavori = 0;

    if (empty($dossierIds) && count($fichierIds) == 1) {

      // Récupération du fichier
      $fichier = $fichierDAO->get($fichierIds[0]);

      if ($fichier->estUnFavori()) {

        return _arRedirect (CopixUrl::get ('classeur||voirContenu', array('classeurId' => $fichier->classeur_id, 'dossierId' => $fichier->dossier_id, 'errorMessage' => CopixI18N::get ('classeur|classeur.error.downloadFavorite'))));
      }

      // Path du fichier
      $dir        = realpath('./static/classeur').'/'.$classeur->id.'-'.$classeur->cle.'/';
      $extension  = strtolower(strrchr($fichier->fichier, '.'));
      $pathfile   = $dir.$fichier->id.'-'.$fichier->cle.$extension;

      return _arFile ($pathfile, array ('filename' => $fichier->fichier, 'content-type' => classeurService::getMimeType($fichier->fichier), 'content-disposition' => 'attachment'));
    }

    // Path de l'archive ZIP temporaire
    $dossierTmp = classeurService::getTmpFolder();

    if ($ppo->dossierId != 0) {

      $dossier = $dossierDAO->get($ppo->dossierId);
      $fichierZip = str_replace('/', '-', $dossier->nom).'.zip';
    } else {

      $fichierZip = str_replace('/', '-', $classeur->titre).'.zip';
    }

        if (file_exists($dossierTmp.$fichierZip)) {

          unlink ($dossierTmp.$fichierZip);
        }

    $zip = new ZipArchive();
    if ($zip->open($dossierTmp.$fichierZip, ZIPARCHIVE::CREATE) === true) {

      if (!empty($dossierIds)) {

        $ppo->dossierIds = implode($dossierIds, ',');
        if (count($ppo->dossierIds) == 1) {

          $dossier = $dossierDAO->get($dossierIds[0]);

          // Seul l'enseignant peut télécharger un casier
             if ($dossier->casier && Kernel::getLevel('MOD_CLASSEUR', $ppo->classeurId) < PROFILE_CCV_PUBLISH) {

               return CopixActionGroup::process ('generictools|Messages::getError',
                   array ('message' => CopixI18N::get ('classeur|classeur.error.downloadLocker'), 'back' => CopixUrl::get('classeur||voirContenu', array('classeurId' => $ppo->classeurId))));
             } else {

               classeurService::addFolderToZip($dossier, $zip);
             }
        } else {

          foreach ($dossierIds as $dossierId) {

               $dossier = $dossierDAO->get ($dossierId);
               if (!$dossier->casier || ($dossier->casier && Kernel::getLevel('MOD_CLASSEUR', $ppo->classeurId) >= PROFILE_CCV_PUBLISH)) {

                   classeurService::addFolderToZip($dossier, $zip);
               }
             }
        }
      }

         $ppo->fichierIds = implode($fichierIds, ',');
         foreach ($fichierIds as $fichierId) {

           $fichier = $fichierDAO->get($fichierId);
           if (!$fichier->estUnFavori()) {

             classeurService::addFileToZip($fichier, $zip);
           } else {

             $cptFavori++;
           }
         }
    }

    $zip->close();

    if ($cptFavori != 0 && $cptFavori == count($fichierIds)) {

      return _arRedirect (CopixUrl::get ('classeur||voirContenu', array('classeurId' => $fichier->classeur_id, 'dossierId' => $fichier->dossier_id, 'errorMessage' => CopixI18N::get ('classeur|classeur.error.downloadFavorite'))));
    }

    return _arFile ($dossierTmp.$fichierZip, array ('filename' => $fichierZip, 'content-type' => classeurService::getMimeType($fichierZip), 'content-disposition' => 'attachment'));
  }

  /**
     * FICHIER - Téléchargement d'un fichier
     */
  public function processTelechargerFichier ()
  {
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

    $extension  = strtolower(strrchr($fichier->fichier, '.'));
        $nomFichier = $fichier->id.'-'.$fichier->cle.$extension;

        $pathFichier = realpath('./static/classeur').'/'.$classeur->id.'-'.$classeur->cle.'/'.($nomFichier);
        if (!file_exists($pathFichier)) {

          return CopixActionGroup::process ('generictools|Messages::getError',
               array ('message' => CopixI18N::get ('classeur|classeur.error.noFile'), 'back' => CopixUrl::get('classeur||')));
        }

    $this->flash->addCycle();

        return _arFile ($pathFichier, array ('filename' => $fichier->fichier, 'content-type' => classeurService::getMimeType($pathFichier), 'content-disposition' => 'attachement'));
  }

  /**
     * Edition d'un album public
     */
  public function processEditerAlbumPublic ()
  {
    $classeurDAO = _ioDAO('classeur|classeur');

    if (is_null($ppo->classeur = $classeurDAO->get(_request ('classeurId', null)))) {

      return CopixActionGroup::process ('generictools|Messages::getError',
             array ('message' => CopixI18N::get ('kernel|kernel.error.errorOccurred'), 'back' => CopixUrl::get('')));
       } elseif (Kernel::getLevel('MOD_CLASSEUR', $ppo->classeur->id) < PROFILE_CCV_MODERATE) {

         return CopixActionGroup::process ('genericTools|Messages::getError',
          array ('message'=> CopixI18N::get ('kernel|kernel.error.noRights'), 'back' => CopixUrl::get('classeur||voirContenu', array('classeurId' => $ppo->classeur->id))));
       }

       $ppo->confirmMessage = _request('confirmMessage', null);

       _classInclude('classeur|classeurservice');
       $dossierDAO  = _ioDAO('classeur|classeurdossier');
    $fichierDAO  = _ioDAO('classeur|classeurfichier');

    if (($ppo->dossierId = _request('dossierId', 0)) != 0) {

      $ppo->dossier = $dossierDAO->get($ppo->dossierId);
      if ($ppo->dossier->casier) {

        return CopixActionGroup::process ('genericTools|Messages::getError',
            array ('message'=> CopixI18N::get ('classeur|classeur.error.albumLocker'), 'back' => CopixUrl::get('classeur||voirContenu', array('classeurId' => $ppo->classeur->id, 'dossierId' => $ppo->dossier->id))));
      }
      $ppo->album = $ppo->dossier;
      if ($ppo->album->public == 1) {

        $ppo->albumUrl = CopixUrl::get ().'static/classeur/'.$ppo->classeur->id.'-'.$ppo->classeur->cle.'/'.$ppo->album->id.'-'.$ppo->album->cle.'/index.html';
      }
    } else {

      $ppo->album = $ppo->classeur;
      if ($ppo->album->public == 1) {

        $ppo->albumUrl = CopixUrl::get ().'static/classeur/'.$ppo->album->id.'-'.$ppo->album->cle.'/index.html';
      }
    }

    $ppo->images    = array();
    $ppo->documents = array();

    $ppo->fichiers = classeurService::getFilesInFolder($ppo->classeur->id, $ppo->dossierId);
    foreach($ppo->fichiers as $fichier) {

      if ($fichier->estUneImage()) {

        $ppo->images[] = $fichier;
      } else {

        $ppo->documents[] = $fichier;
      }
    }

       $modParentInfo = Kernel::getModParentInfo('MOD_CLASSEUR', $ppo->classeur->id);
      $ppo->TITLE_PAGE = $modParentInfo['nom'];

    return _arPPO ($ppo, array ('template' => 'editer_album_public.tpl'));
  }

  /**
     * Publication d'un album public
     */
  public function processPublierAlbum ()
  {
    set_time_limit(0);

    $classeurDAO  = _ioDAO('classeur|classeur');
    $dossierDAO   = _ioDAO('classeur|classeurdossier');

    if (is_null($classeur = $classeurDAO->get(_request ('classeurId', null)))
     || is_null($dossierId = _request ('dossierId', null))) {

      return CopixActionGroup::process ('generictools|Messages::getError',
             array ('message' => CopixI18N::get ('kernel|kernel.error.errorOccurred'), 'back' => CopixUrl::get('')));
       } elseif (Kernel::getLevel('MOD_CLASSEUR', $classeur->id) < PROFILE_CCV_MODERATE) {

         return CopixActionGroup::process ('genericTools|Messages::getError',
          array ('message'=> CopixI18N::get ('kernel|kernel.error.noRights'), 'back' => CopixUrl::get('classeur||voirContenu', array('classeurId' => $classeur->id))));
       }

       $path2classeur = realpath('./static/classeur').'/'.$classeur->id.'-'.$classeur->cle;
       if ($dossierId != 0) {

      $album        = $dossierDAO->get($dossierId);
      $albumType    = 'dossier';
      $path2album   = $path2classeur.'/'.$album->id.'-'.$album->cle;
      $path2public  = CopixUrl::get ().'static/classeur/'.$classeur->id.'-'.$classeur->cle.'/'.$album->id.'-'.$album->cle;
    } else {

      $album        = $classeur;
      $albumType    = 'classeur';
      $path2album   = $path2classeur;
      $path2public  = CopixUrl::get ().'static/classeur/'.$album->id.'-'.$album->cle;
    }

    // Récupération des images à inclure dans l'album
    _classInclude('classeur|classeurservice');
    $fichiers = classeurService::getFilesInFolder($classeur->id, $dossierId);
    foreach($fichiers as $fichier) {

      if ($fichier->estUneImage()) {

        $image = new CopixPPO();
        $extension = strtolower(strrchr($fichier->fichier, '.'));
        $image = $fichier->id.'-'.$fichier->cle.$extension;
        $images[] = $image;
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

            $this->copyResized( $path2classeur.'/'.$image, $path2album.'/images/'.$image, 800 );
        }

        // Création du fichier index.html nécessaire à l'affichage de l'album
        $file_html = fopen($path2album.'/index.html', 'w');
        $tplHtml = new CopixTpl ();
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
        } else {

          $classeurDAO->update($album);
        }

        $confirmMessage = CopixI18N::get ('classeur|classeur.message.confirmPublished');

        return _arRedirect (CopixUrl::get ('classeur||editerAlbumPublic', array('classeurId' => $classeur->id, 'dossierId' => $dossierId, 'confirmMessage' => $confirmMessage)));
  }

  private function copyResized ($from, $to, $size)
  {
      $imgTypes = array(
      'image/jpeg',
      'image/pjpeg',
      'image/png',
      'image/gif',
    );

    $imgLoaders = array(
      'image/jpeg'  => 'imagecreatefromjpeg',
      'image/pjpeg' => 'imagecreatefromjpeg',
      'image/png'   => 'imagecreatefrompng',
      'image/gif'   => 'imagecreatefromgif',
    );

    $imgCreators = array(
      'image/jpeg'  => 'imagejpeg',
      'image/pjpeg' => 'imagejpeg',
      'image/png'   => 'imagepng',
      'image/gif'   => 'imagegif',
    );
        $imgData = getimagesize($from);

    if ($imgData[0] > $imgData[1]) {

      $width = $size;
      $height = $imgData[1] * $width / $imgData[0];
    } else {

      $height = $size;
      $width = $imgData[0] * $height / $imgData[1];
    }

    if (in_array($imgData['mime'], $imgTypes)) {

      $loader = $imgLoaders[$imgData['mime']];
      if(!function_exists($loader)) {

        die (sprintf('Function %s not available. Please enable the GD extension.', $loader));
      }

      $source       = $loader($from);
      $thumbnail    = imagecreatetruecolor($width, $height);
      imagecopyresampled($thumbnail, $source, 0, 0, 0, 0, $width, $height, $imgData[0], $imgData[1]);

      $creator = $imgCreators[$imgData['mime']];
      $creator($thumbnail, $to);
    }
  }

  /**
     * Dépublication d'un album public
     */
  public function processDepublierAlbum ()
  {
    $classeurDAO  = _ioDAO('classeur|classeur');
    $dossierDAO   = _ioDAO('classeur|classeurdossier');

    if (is_null($classeur = $classeurDAO->get(_request ('classeurId', null)))
     || is_null($dossierId = _request ('dossierId', null))) {

      return CopixActionGroup::process ('generictools|Messages::getError',
             array ('message' => CopixI18N::get ('kernel|kernel.error.errorOccurred'), 'back' => CopixUrl::get('')));
       } elseif (Kernel::getLevel('MOD_CLASSEUR', $classeur->id) < PROFILE_CCV_MODERATE) {

         return CopixActionGroup::process ('genericTools|Messages::getError',
          array ('message'=> CopixI18N::get ('kernel|kernel.error.noRights'), 'back' => CopixUrl::get('classeur||voirContenu', array('classeurId' => $classeur->id))));
       }

       $path2classeur = realpath('./static/classeur').'/'.$classeur->id.'-'.$classeur->cle;
       if ($dossierId != 0) {

      $album        = $dossierDAO->get($dossierId);
      $albumType    = 'dossier';
      $path2album   = $path2classeur.'/'.$album->id.'-'.$album->cle;
      $path2public  = CopixUrl::get ().'static/classeur/'.$classeur->id.'-'.$classeur->cle.'/'.$album->id.'-'.$album->cle;
    } else {

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
        } else {

          $classeurDAO->update($album);
        }

        $confirmMessage = CopixI18N::get ('classeur|classeur.message.confirmUnpublished');

        return _arRedirect (CopixUrl::get ('classeur||editerAlbumPublic', array('classeurId' => $classeur->id, 'dossierId' => $dossierId, 'confirmMessage' => $confirmMessage)));
  }

  public function processEnvoieFichierPopup ()
  {
        $ppo = new CopixPPO ();

    if (is_null($ppo->classeurId = _request ('classeurId'))) {

        return CopixActionGroup::process ('generictools|Messages::getError',
              array ('message' => CopixI18N::get ('kernel|kernel.error.errorOccurred'), 'back' => CopixUrl::get('')));
      } elseif (Kernel::getLevel('MOD_CLASSEUR', $ppo->classeurId) < PROFILE_CCV_MEMBER) {

        return CopixActionGroup::process ('genericTools|Messages::getError',
          array ('message'=> CopixI18N::get ('kernel|kernel.error.noRights'), 'back' => CopixUrl::get('classeur||voirContenu', array('classeurId' => $ppo->classeurId))));
      }

    $field     = _request('field', null);
        $format    = _request('format', null);
        $moduleType = _request('moduleType', null);
        $moduleId   = _request('moduleId', null);

    // Récupération du classeur
    $classeurDAO = _ioDAO('classeur|classeur');
    $classeur = $classeurDAO->get($ppo->classeurId);

    // Récupération du dossier pour définition du path
    $dossierDAO = _ioDAO('classeur|classeurdossier');
    if (!is_null($ppo->dossierId  = _request('dossierId', null)) && $ppo->dossierId != 0) {

      if ($ppo->dossier = $dossierDAO->get($ppo->dossierId)) {

        $ppo->path = $ppo->dossier->getPath();
      } else {

        $ppo->path = '/';
      }
    } else {

      $ppo->path = '/'.$classeur->titre.'/';
    }

    // Dossier temporaire pour l'upload des fichiers
    _classInclude('classeur|classeurService');

    $dossierTmp = classeurService::getTmpFolder();
    $ppo->dossierTmp = $dossierTmp.classeurService::createKey();

    $fichierDAO = _ioDAO('classeur|classeurfichier');

    if ($_FILES['fichiers']['name'][0] != "") {

      // Contrôle upload du fichier
      if (is_uploaded_file($_FILES['fichiers']['tmp_name'][0])) {

        $dir = realpath('./static/classeur').'/'.$classeur->id.'-'.$classeur->cle.'/';

                $ppo->fichier = _record('classeur|classeurfichier');

                $ppo->fichier->classeur_id   = $classeur->id;
        $ppo->fichier->dossier_id    = isset($ppo->dossierId) ? $ppo->dossierId : 0;
        $ppo->fichier->titre         = substr($_FILES['fichiers']['name'][0], 0, 63);
        $ppo->fichier->commentaire   = '';
        $ppo->fichier->fichier       = $_FILES['fichiers']['name'][0];
        $ppo->fichier->taille        = filesize($_FILES['fichiers']['tmp_name'][0]);
        $ppo->fichier->type          = strtoupper(substr(strrchr($_FILES['fichiers']['name'][0], '.'), 1));
        $ppo->fichier->cle           = classeurService::createKey();
        $ppo->fichier->date_upload   = date('Y-m-d H:i:s');
        $ppo->fichier->user_type     = _currentUser()->getExtra('type');
        $ppo->fichier->user_id       = _currentUser()->getExtra('id');

        $fichierDAO->insert($ppo->fichier);

        $extension = strtolower(strrchr($_FILES['fichiers']['name'][0], '.'));
              $fichierPhysique = $dir.$ppo->fichier->id.'-'.$ppo->fichier->cle.$extension;
              move_uploaded_file ($_FILES['fichiers']['tmp_name'][0], $fichierPhysique);
          }
    }

    $urlReturn = ($field && $format) ? CopixUrl::get ('classeur||getClasseurPopup', array('classeurId' => $classeur->id, 'dossierId'=>$ppo->dossierId, 'field'=>$field, 'format'=>$format, 'moduleType' => $moduleType, 'moduleId' => $moduleId)) : CopixUrl::get ('classeur||getClasseurPopup', array('classeurId' => $classeur->id, 'dossierId'=>$ppo->dossierId));

    return new CopixActionReturn (COPIX_AR_REDIRECT, $urlReturn);
    }

  /**
   * AJAX - Met à jour l'état de l'arbre des classeurs
   */
  public function processSauvegardeEtatArbreClasseurs ()
  {
    if (is_null ($id = _request ('id', null))) {

      return new CopixActionReturn (CopixActionReturn::HTTPCODE,
        array('Content-Type: text/plain; charset=utf-8', 'HTTP/1.1 404 Not found'), CopixI18N::get ('kernel|kernel.error.errorOccurred'));
    }

    _classInclude ('classeur|classeurservice');
    ClasseurService::setClasseursTreeState ($id);

    $this->flash->addCycle();

    return new CopixActionReturn (CopixActionReturn::HTTPCODE,
      array('Content-Type: text/html; charset=utf-8', 'HTTP/1.1 200 OK'), CopixI18N::get ('classeur|classeur.message.stateChanged'));
  }

  /**
   * AJAX - Met à jour l'état de l'arbre des dossiers
   */
  public function processSauvegardeEtatArbreDossiers ()
  {
    if (is_null ($id = _request ('id', null))) {

      return new CopixActionReturn (CopixActionReturn::HTTPCODE,
        array('Content-Type: text/plain; charset=utf-8', 'HTTP/1.1 404 Not found'), CopixI18N::get ('kernel|kernel.error.errorOccurred'));
    }

    _classInclude ('classeur|classeurservice');
    ClasseurService::setFoldersTreeState ($id);

    $this->flash->addCycle();

    return new CopixActionReturn (CopixActionReturn::HTTPCODE,
      array('Content-Type: text/html; charset=utf-8', 'HTTP/1.1 200 OK'), CopixI18N::get ('classeur|classeur.message.stateChanged'));
  }

  /**
     * Affichage du classeur en popup pour intégration dans les autres modules
     */
  public function processGetClasseurPopup ()
  {
    $classeurDAO = _ioDAO('classeur|classeur');
    if (is_null($ppo->classeur = $classeurDAO->get(_request ('classeurId', null)))) {

      return CopixActionGroup::process ('generictools|Messages::getError',
             array ('message' => CopixI18N::get ('kernel|kernel.error.errorOccurred'), 'back' => CopixUrl::get('')));
       }

       $ppo->dossierId     = _request('dossierId', 0);
       $ppo->field         = _request('field', null);
        $ppo->format        = _request('format', null);
        $ppo->withPersonal  = _request('withPersonal', true);
        $ppo->moduleType    = _request('moduleType', null);
        $ppo->moduleId      = _request('moduleId', null);

        $ppo->niveauUtilisateur = Kernel::getLevel('MOD_CLASSEUR', $ppo->classeur->id);

       _classInclude('classeur|classeurService');
       $dossierDAO = _ioDAO('classeur|classeurdossier');
        $fichierDAO = _ioDAO('classeur|classeurfichier');

        // Récupération des dossiers & des fichiers / favoris
        $ppo->dossiers = $dossierDAO->getEnfantsDirects($ppo->classeur->id, $ppo->dossierId)->fetchAll();
        $ppo->fichiers = $fichierDAO->getParDossier($ppo->classeur->id, $ppo->dossierId)->fetchAll();

        foreach ($ppo->fichiers as $fichier) {

          $fichier->fullUrl = $fichier->getLienMiniature();
          $fichier->url = substr($fichier->getLienMiniature(), 0, strlen($fichier->getLienMiniature()) - strlen(strrchr($fichier->getLienMiniature(), '.')));
          $fichier->extension = $fichier->getExtension();
        }

        // Ouverture du dossier courant dans l'arborescence
        if ($ppo->dossierId != 0) {

          classeurService::openTree($ppo->classeur->id, $ppo->dossierId);
          $dossier = $dossierDAO->get($ppo->dossierId);
          if ($dossier->parent_id != 0) {

            $ppo->dossierParent = $dossierDAO->get($dossier->parent_id);
          } else {

            $ppo->classeurParent = $ppo->classeur;
          }
        }

        // Dossier temporaire pour l'upload des fichiers
    _classInclude('classeur|classeurService');

    $dossierTmp = classeurService::getTmpFolder();
    $ppo->dossierTmp = $dossierTmp.classeurService::createKey();

        CopixHTMLHeader::addJSLink (_resource('js/iconito/module_classeur.js'));

       return _arPPO ($ppo, array ('template'=>'classeur_popup.tpl', 'mainTemplate'=>'main|main_popup.php'));
  }

  /**
   * Méthode de redirection avec l'identifiant du classeur
   */
  public function go ()
  {
    return _arRedirect (CopixUrl::get ('classeur||voirContenu', array('classeurId' => _request ('id', null))));
    }
}
