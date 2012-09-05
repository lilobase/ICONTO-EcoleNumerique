<?php

/**
* @package    Iconito
* @subpackage Classeur
* @author     Jérémy FOURNAISE
*/

class ClasseurService
{
  /**
   * Retourne l'ID du classeur personnel
   *
   * @return int ou false si classeur perso non récupéré
   */
  public static function getClasseurPersonnelId ()
  {
    $nodes = Kernel::getMyNodes (_currentUser()->getExtra('type'), _currentUser()->getExtra('id'));
    foreach ($nodes as $node) {

      $modules = Kernel::getModEnabled($node->type, $node->id, _currentUser()->getExtra('type'), _currentUser()->getExtra('id'));
      foreach ($modules as $module) {

        if ($module->module_type == "MOD_CLASSEUR") {
          // Identification du classeur personnel de l'utilisateur
          if (strpos($module->node_type, 'USER_') !== false
            && ($module->node_type == _currentUser()->getExtra('type') && $module->node_id == _currentUser()->getExtra('id'))) {

            return $module->module_id;
          }
        }
      }
    }

    return false;
  }

  /**
   * Méthode de génération d'une clé utilisée pour les dossiers et fichiers du classeur
   *
   * @return string
   */
  public static function createKey ()
  {
      return substr(md5(microtime()), 0, 10);
  }

  /**
   * Stock l'état de l'arbre des noeuds classeurs
   *
   * @param int $id ID du noeud de classeur
   */
  public static function setClasseursTreeState ($id)
  {
    $state = _sessionGet ('classeur|classeurs_tree_state');

    if (isset ($state[$id])) {

      unset ($state[$id]);
    } else {

      $state[$id] = 1;
    }

    _sessionSet ('classeur|classeurs_tree_state', $state);
  }

  /**
   * Retourne l'état de l'arbre des noeuds classeurs
   *
   * @return array
   */
  public static function getClasseursTreeState ()
  {
    $treeState = _sessionGet ('classeur|classeurs_tree_state');
    if (!isset($treeState) || !is_array($treeState)) {

      return array();
    }

    return $treeState;
  }

  /**
   * Stock l'état de l'arbre des noeuds dossiers
   *
   * @param int $id ID du noeud de dossier
   */
  public static function setFoldersTreeState ($id)
  {
    $state = _sessionGet ('classeur|folders_tree_state');

    if (isset ($state[$id])) {

      unset ($state[$id]);
    } else {

      $state[$id] = 1;
    }

    _sessionSet ('classeur|folders_tree_state', $state);
  }

  /**
   * Retourne l'état de l'arbre des noeuds dossiers
   *
   * @return array
   */
  public static function getFoldersTreeState ()
  {
    $treeState = _sessionGet ('classeur|folders_tree_state');
    if (!isset($treeState) || !is_array($treeState)) {

      return array();
    }

    return $treeState;
  }

  /**
   * Ouvre l'arborescence des classeurs / dossiers
   */
  public static function openTree($classeurId, $folderId)
  {
    $folderDAO = _ioDAO('classeur|classeurdossier');
      $folder = $folderDAO->get($folderId);

      $openFolders = classeurService::getFoldersTreeState ();
      if (!in_array($folder->id, array_keys($openFolders))) {

          classeurService::setFoldersTreeState ($folder->id);
        }

      while ($folder->parent_id != 0) {


          if (!in_array($folder->parent_id, array_keys($openFolders))) {

            classeurService::setFoldersTreeState ($folder->parent_id);
          }
          $folder = $folderDAO->get($folder->parent_id);
      }

      $openClasseurs = classeurService::getClasseursTreeState ();
      if (!in_array($classeurId, array_keys($openClasseurs))) {

        classeurService::setClasseursTreeState ($classeurId);
      }
  }

  /**
  * Renvoie le type MIME d'un fichier
  *
  * @param  string $filename Nom du fichier
  *
  * @return string
  */
  public static function getMimeType ($filename)
  {
    $point = strrpos ($filename, '.');

    if ($point !== false) {

      $ext = substr($filename, $point+1);
      $ext = strtolower($ext);
    } else {

      $ext = $filename;
    }

    return CopixMIMETypes::getFromExtension ($ext);
  }

  /**
     * Suppression d'un dossier
     *
     * @param DAORecordClasseurDossier $folder      Dossier à supprimer
     * @param bool                     $withFiles   Supprimer les fichiers du dossier ?
     */
    public static function deleteFolder (DAORecordClasseurDossier $folder, $withFiles = true)
    {
        $folderDAO    = _ioDAO('classeur|classeurdossier');
        $classeurDAO  = _ioDAO('classeur|classeur');

        // Récupération du classeur du dossier
        $classeur = $classeurDAO->get ($folder->classeur_id);

        // Si les fichiers du dossier doivent être supprimés
        if ($withFiles) {

          $fileDAO = _ioDAO('classeur|classeurfichier');
          $files = $fileDAO->getParDossier ($classeur->id, $folder->id);
          foreach ($files as $file) {

            self::deleteFile($file);
          }
        }

        // Pour chaque sous dossiers on rappelle la méthode
        $subfolders = $folderDAO->getEnfantsDirects ($classeur->id, $folder->id);
        foreach ($subfolders as $subfolder) {

          self::deleteFolder ($subfolder, true);
        }

        // On supprime le dossier
        $folderDAO->delete ($folder->id);
    }


  /**
     * Suppression d'un fichier
     *
     * @param DAORecordClasseurFichier $file  Fichier à supprimer
     */
    public static function deleteFile (DAORecordClasseurFichier $file)
    {
        $fileDAO      = _ioDAO('classeur|classeurfichier');
        $classeurDAO  = _ioDAO('classeur|classeur');

        // Récupération du classeur du fichier
        $classeur = $classeurDAO->get ($file->classeur_id);

        // On supprime le fichier
        $extension  = strtolower(strrchr($file->fichier, '.'));
        $filename   = $file->id.'-'.$file->cle;
        $filepath   = realpath('./static/classeur').'/'.$classeur->id.'-'.$classeur->cle.'/'.$file->id.'-'.$file->cle.$extension;

        if (file_exists($filepath)) {

          unlink ($filepath);
        }

        $fileDAO->delete ($file->id);
    }

    /**
     * Déplacement d'un dossier
     *
     * @param DAORecordClasseurDossier $folder      Dossier à déplacer
     * @param string                   $targetType  Type du noeud destination
     * @param int                      $targetId    Identifiant du noeud destination
     * @param bool                     $withFiles   Déplacer les fichiers contenus ?
     */
    public static function moveFolder (DAORecordClasseurDossier $folder, $targetType, $targetId, $withFiles = true)
    {
      $folderDAO    = _ioDAO('classeur|classeurdossier');
    $fileDAO      = _ioDAO('classeur|classeurfichier');

        // Pour chaque sous dossiers on rappelle la méthode
        $subfolders = $folderDAO->getEnfantsDirects ($folder->classeur_id, $folder->id);
        foreach ($subfolders as $subfolder) {

          self::moveFolder ($subfolder, 'dossier', $folder->id);
        }

        // Récupération des fichiers du dossier pour le déplacement
    if ($withFiles) {

          $files = $fileDAO->getParDossier ($folder->classeur_id, $folder->id);
        }

    // Déplacement du dossier
    if ($targetType == 'dossier') {

      $targetFolder = $folderDAO->get($targetId);

      $folder->classeur_id = $targetFolder->classeur_id;
      $folder->parent_id   = $targetId;
      $folder->casier      = $targetFolder->casier;
    } else {

      $folder->classeur_id  = $targetId;
      $folder->parent_id    = 0;
      $folder->casier       = 0;
    }

    // Mise à jour du dossier après déplacement
    $folderDAO->update($folder);

    // Modification des fichiers du dossier
    if ($withFiles) {

          foreach($files as $file) {

            self::moveFile ($file, 'dossier', $folder->id);
          }
        }
    }

    /**
     * Déplacement d'un fichier
     *
     * @param DAORecordClasseurFichier $file        Fichier à déplacer
     * @param string                   $targetType  Type du noeud destination
     * @param int                      $targetId    Identifiant du noeud destination
     */
    public static function moveFile (DAORecordClasseurFichier $file, $targetType, $targetId)
    {
      $classeurDAO  = _ioDAO('classeur|classeur');
      $folderDAO    = _ioDAO('classeur|classeurdossier');
    $fileDAO      = _ioDAO('classeur|classeurfichier');

    // Récupération du classeur
    $oldClasseur = $classeurDAO->get($file->classeur_id);
    if ($targetType == 'dossier') {

      $targetFolder = $folderDAO->get($targetId);
      $newClasseur  = $classeurDAO->get($targetFolder->classeur_id);

      $file->classeur_id  = $targetFolder->classeur_id;
      $file->dossier_id   = $targetFolder->id;
    } else {

      $newClasseur = $classeurDAO->get($targetId);

      $file->classeur_id  = $targetId;
      $file->dossier_id   = 0;
    }

    $fileDAO->update($file);

    // Si classeurs différents on déplace le fichier
    if ($oldClasseur != $newClasseur) {

      $old_dir = realpath('./static/classeur').'/'.$oldClasseur->id.'-'.$oldClasseur->cle.'/';
      $new_dir = realpath('./static/classeur').'/'.$newClasseur->id.'-'.$newClasseur->cle.'/';

      if (!file_exists($new_dir)) {

        mkdir($new_dir, 0755, true);
      }

      $extension = strtolower(strrchr($file->fichier, '.'));
      copy($old_dir.$file->id.'-'.$file->cle.$extension, $new_dir.$file->id.'-'.$file->cle.$extension);
      unlink($old_dir.$file->id.'-'.$file->cle.$extension);
    }

    // Déplacement d'un document d'un élève dans un casier par un enseignant
    if ($targetType == 'dossier' && $targetFolder->casier && !$targetFolder->isCasierPrincipal () && $file->user_type == 'USER_ELE') {

      // On vérifie si un travail à rendre correspondant à ce casier existe
      $travailDAO = _ioDAO ('cahierdetextes|cahierdetextestravail');
      if ($travail = $travailDAO->findTravailARendreByCasier ($targetFolder->id)) {

        // Sauvegarde de la date de rendu du travail
        $travail2eleveDAO = _ioDAO ('cahierdetextes|cahierdetextestravail2eleve');
        if ($suivi = $travail2eleveDAO->getByTravailAndEleve ($travail->id, $file->user_id)) {

          $suivi->rendu_le = date('Y-m-d H:i:s');

          $travail2eleveDAO->update ($suivi);
        } else {

          $suivi = _record ('cahierdetextes|cahierdetextestravail2eleve');

          $suivi->travail_id = $travail->id;
          $suivi->eleve_id = $file->user_id;
          $suivi->rendu_le = date('Y-m-d H:i:s');

          $travail2eleveDAO->insert ($suivi);
        }
      }
    }
    }

    /**
     * Copie d'un dossier
     *
     * @param DAORecordClasseurDossier $folder      Dossier à déplacer
     * @param string                   $targetType  Type du noeud destination
     * @param int                      $targetId    Identifiant du noeud destination
     * @param bool                     $withFiles   Déplacer les fichiers contenus ?
     */
    public static function copyFolder (DAORecordClasseurDossier $folder, $targetType, $targetId, $withFiles = true)
    {
      $folderDAO    = _ioDAO('classeur|classeurdossier');
    $fileDAO      = _ioDAO('classeur|classeurfichier');

        // Copie du dossier
    if ($targetType == 'dossier') {

      $targetFolder = $folderDAO->get($targetId);

      $clone = clone $folder;
      $clone->classeur_id = $targetFolder->classeur_id;
      $clone->parent_id   = $targetId;
    } else {

      $clone = clone $folder;
      $clone->classeur_id = $targetId;
      $clone->parent_id   = 0;
    }

    // Insertion du nouveau dossier
    $folderDAO->insert($clone);

        // Pour chaque sous dossiers on rappelle la méthode
        $subfolders = $folderDAO->getEnfantsDirects ($folder->classeur_id, $folder->id);
        foreach ($subfolders as $subfolder) {

          // En cas de copie, le copieur devient le propriétaire de la copie
          $subfolder->user_type = $folder->user_type;
          $subfolder->user_id   = $folder->user_id;

          self::copyFolder ($subfolder, 'dossier', $clone->id);
        }

    // Récupération des fichiers du dossier pour la copie
    if ($withFiles) {

          $files = $fileDAO->getParDossier ($folder->classeur_id, $folder->id);
          foreach($files as $file) {

            // En cas de copie, le copieur devient le propriétaire de la copie
            $file->user_type = $folder->user_type;
            $file->user_id   = $folder->user_id;

            self::copyFile ($file, 'dossier', $clone->id);
          }
    }
    }

    /**
     * Copie d'un fichier
     *
     * @param DAORecordClasseurFichier $file        Fichier à déplacer
     * @param string                   $targetType  Type du noeud destination
     * @param int                      $targetId    Identifiant du noeud destination
     */
    public static function copyFile (DAORecordClasseurFichier $file, $targetType, $targetId)
    {
      $classeurDAO  = _ioDAO('classeur|classeur');
      $folderDAO    = _ioDAO('classeur|classeurdossier');
    $fileDAO      = _ioDAO('classeur|classeurfichier');

    // Récupération du classeur
    $oldClasseur  = $classeurDAO->get($file->classeur_id);
    $old_dir      = realpath('./static/classeur').'/'.$oldClasseur->id.'-'.$oldClasseur->cle.'/';
    $extension    = strtolower(strrchr($file->fichier, '.'));

    // Copie du fichier uniquement s'il existe bien
    if (file_exists($old_dir.$file->id.'-'.$file->cle.$extension)) {

      // Copie de l'enregistrement fichier
      $clone = clone $file;
      $clone->cle = self::createKey();

      if ($targetType == 'dossier') {

        $targetFolder = $folderDAO->get($targetId);
        $newClasseur  = $classeurDAO->get($targetFolder->classeur_id);

        $clone->classeur_id = $targetFolder->classeur_id;
        $clone->dossier_id  = $targetFolder->id;
      } else {

        $newClasseur  = $classeurDAO->get($targetId);

        $clone->classeur_id  = $targetId;
        $clone->dossier_id   = 0;
      }

      // Insertion du nouveau fichier
      $fileDAO->insert($clone);

      // Copie physique du fichier

      $new_dir = realpath('./static/classeur').'/'.$newClasseur->id.'-'.$newClasseur->cle.'/';

      if (!file_exists($new_dir)) {

        mkdir($new_dir, 0755, true);
      }

      copy($old_dir.$file->id.'-'.$file->cle.$extension, $new_dir.$clone->id.'-'.$clone->cle.$extension);
    }
    }

    /**
     * Ajoute le contenu d'un dossier dans une archive ZIP
     *
     * @param DAORecordClasseurDossier $folder      Dossier à ajouter
     * @param ZipArchive               $zip         Archive ZIP à laquelle ajouter le contenu du dossier
     */
    public static function addFolderToZip (DAORecordClasseurDossier $folder, $zip)
    {
    $folderDAO = _ioDAO('classeur|classeurdossier');
    $fileDAO   = _ioDAO('classeur|classeurfichier');

    $zip->addEmptyDir(substr($folder->getPath(true), 1));
    $files = $fileDAO->getParDossier ($folder->classeur_id, $folder->id);
        foreach($files as $file) {

      if (!$file->estUnFavori()) {

        self::addFileToZip ($file, $zip);
      }
        }

    // Pour chaque sous dossiers on rappelle la méthode
        $subfolders = $folderDAO->getEnfantsDirects ($folder->classeur_id, $folder->id);
        foreach ($subfolders as $subfolder) {

          self::addFolderToZip ($subfolder, $zip);
        }
  }

  /**
     * Ajoute un fichier dans une archive ZIP
     *
     * @param DAORecordClasseurFichier $file        Fichier à ajouter
     * @param ZipArchive               $zip         Archive ZIP à laquelle ajouter le contenu du dossier
     */
    public static function addFileToZip (DAORecordClasseurFichier $file, $zip)
    {
    // Récupération du classeur nécessaire pour déterminer le chemin du fichier
    $classeurDAO = _ioDAO('classeur|classeur');
    $classeur = $classeurDAO->get($file->classeur_id);

    // Path du fichier
    $dir        = realpath('./static/classeur').'/'.$classeur->id.'-'.$classeur->cle.'/';
    $extension  = strtolower(strrchr($file->fichier, '.'));

    $pathfile = $dir.$file->id.'-'.$file->cle.$extension;

    if (file_exists($pathfile)) {

      $filename = substr($file->fichier, 0, strrpos($file->fichier, '.'));
      $zip->addFile($pathfile, substr($file->getPath(true), 1).$file->id.'-'.$file->fichier);
    }
  }

  /**
     * Récupération du contenu du dossier temporaire (utilisé pour les archives ZIP)
     *
     * @param array   $datas      Liste des fichiers / dossiers récupérés dans le dossier TMP
     * @param string  $folder     Dossier temporaire
     * @param array   $excluded   Fichiers / dossiers à exclude
     *
     * @return array
     */
  public static function getFilesInTmpFolder ($datas, $folder, $excluded = array())
  {
    if ($handle = opendir($folder)) {

      while (($file = readdir($handle)) !== false) {

        if ($file != '.' && $file != '..'
          && !in_array($file, $excluded) && !strstr($file, '_MACOSX')) {

          if (is_dir($folder.'/'.$file)) {

            $datas[$folder]['folders'][] = $file;
            $datas = self::getFilesInTmpFolder($datas, $folder.'/'.$file, $excluded);
          } else {

            $datas[$folder]['files'][] = $file;
          }
        }
      }
    }

    return $datas;
  }

  /**
     * Upload d'un fichier dans un classeur
     *
     * @param string                    $file       Path du fichier uploadé
     * @param string                    $name       Nom du fichier uploadé
     * @param DAORecordClasseur         $classeur   Classeur ou envoyer le fichier
     * @param DAORecordClasseurDossier  $dossier    Dossier ou envoyer le fichier
     *
     * @return DAORecordClasseurFichier
     */
  public function uploadFile ($file, $name, DAORecordClasseur $classeur, $dossier = null)
  {
    $dir = realpath('./static/classeur').'/'.$classeur->id.'-'.$classeur->cle.'/';
    $extension = strtolower(strrchr($name, '.'));

    $fichierDAO = _ioDAO ('classeur|classeurfichier');
    $fichier    = _record ('classeur|classeurfichier');

    $fichier->classeur_id   = $classeur->id;
    $fichier->dossier_id    = $dossier ? $dossier->id : 0;
    $fichier->titre         = substr(substr($name, 0, strrpos($name, '.')), 0, 63);
    $fichier->commentaire   = '';
    $fichier->taille        = filesize($file);
    $fichier->type          = strtoupper(substr(strrchr($name, '.'), 1));
    $fichier->cle           = self::createKey();
    $fichier->date_upload   = date('Y-m-d H:i:s');
    $fichier->user_type     = _currentUser()->getExtra('type');
    $fichier->user_id       = _currentUser()->getExtra('id');

    if (isset($dossier) && $dossier->casier) {

      $fichier->fichier = $fichier->titre.'_'._currentUser()->getExtra('prenom').'_'._currentUser()->getExtra('nom').$extension;
    } else {

      $fichier->fichier = $name;
    }

    $fichierDAO->insert($fichier);

        $fichierPhysique = $dir.$fichier->id.'-'.$fichier->cle.$extension;
        move_uploaded_file ($file, $fichierPhysique);

        return $fichier;
  }

  /**
     * Minimail de confirmation de l'upload dans le cas d'un envoie dans un casier
     *
     * @param string  $filename   Nom du fichier
     */
  public static function sendLockerUploadConfirmation ($fileName)
  {
    _classInclude('minimail|minimailService');

    $msg_title    = CopixI18N::get ('classeur|classeur.message.confirmUploadLockerTitle', date('d/m/Y'));
    $msg_body     = CopixI18N::get ('classeur|classeur.message.confirmUploadLockerBody', array(date('d/m/Y'), $fileName));

    MinimailService::sendMinimail ($msg_title, $msg_body, CopixConfig::get('minimail|system_sender_id'), array(_currentUser ()->getId() => 1), CopixConfig::get ('minimail|default_format'));
  }

  /**
     * Teste si le folder1 est un descendant du folder2
     *
     * @param DAORecordClasseurDossier  $folder1     Dossier 1
     * @param DAORecordClasseurDossier  $folder2     Dossier 2
     *
     * @return bool True si le folder1 est un descendant du folder2
     */
  public static function isDescendantOf ($folder1, $folder2)
  {
    $folderDAO = _ioDAO('classeur|classeurdossier');

        if ($folder1 == $folder2) {

          return true;
        }

        while ($folder1->parent_id != 0) {

            if ($folder1->parent_id == $folder2->id) {

              return true;
            }

            $folder1 = $folderDAO->get($folder1->parent_id);
        }

        return false;
  }

  /**
     * Met à jour les informations d'un dossier (nb_dossiers / nb_fichiers & taille)
     *
     * @param DAORecordClasseurDossier $folder      Dossier à mettre à jour
     */
    public static function updateFolderInfos (DAORecordClasseurDossier $folder)
    {
        $folderDAO = _ioDAO('classeur|classeurdossier');

        if ($folder->parent_id != 0) {

        while ($folder->parent_id != 0) {

                $folder = $folderDAO->get ($folder->parent_id);
        }

            self::updateFolderInfosWithDescendants ($folder);
        }
    }


    /**
     * Met à jour les informations d'un dossier (nb_dossiers / nb_fichiers & taille)
     * et de ses descendants
     *
     * @param DAORecordClasseurDossier $folder      Dossier à mettre à jour
     *
     * @return array
     */
    public static function updateFolderInfosWithDescendants (DAORecordClasseurDossier $folder)
    {
        $fileDAO = _ioDAO('classeur|classeurfichier');
        $folderDAO = _ioDAO('classeur|classeurdossier');

        $toReturn['nb_folders']  = $folderDAO->getNombreEnfantsDirects ($folder->classeur_id, $folder->id);

        $filesDatas = $fileDAO->getNombreEtTailleParDossier ($folder->classeur_id, $folder->id);
        $toReturn['nb_files'] 	= $filesDatas[0]->nb_fichiers;
        $toReturn['size']       = $filesDatas[0]->taille;

        // Récupération des dossiers enfants
        $subfolders = $folderDAO->getEnfantsDirects($folder->classeur_id, $folder->id);
        foreach ($subfolders as $subfolder) {

            $tmp = self::updateFolderInfosWithDescendants ($subfolder);
            $toReturn['nb_folders']   += $tmp['nb_folders'];
            $toReturn['nb_files'] 		+= $tmp['nb_files'];
            $toReturn['size']         += $tmp['size'];
        }

        // Mise à jour du dossier
        $folder->nb_dossiers = $toReturn['nb_folders'] * 1;
        $folder->nb_fichiers = $toReturn['nb_files'] * 1;
        $folder->taille      = $toReturn['size'] * 1;
        $folderDAO->update ($folder);

        return $toReturn;
    }

    /**
     * Récupère tous les fichiers se trouvant dans un dossier
     *
     * @param int     $classeurId         Identifiant du classeur
     * @param int     $folderId           Identifiant du dossier
     * @param Array   $files              Fichiers trouvés
     * @param Bool    $withSubfolders     Rechercher également dans les sous dossiers du dossier indiqué ?
     *
     * @return array
     */
    public static function getFilesInFolder($classeurId, $folderId = null, $files = array(), $withSubfolders = true)
    {
      $fileDAO   = _ioDAO('classeur|classeurfichier');
        $folderDAO = _ioDAO('classeur|classeurdossier');

        // Récupération des fichiers du dossier et ajout au tableau $files
      $folderFiles = $fileDAO->getParDossier ($classeurId, $folderId);
        foreach ($folderFiles as $file) {

          $files[] = $file;
        }

    // Pour chaque sous dossiers on rappelle la méthode
    if ($withSubfolders && !is_null($folderId)) {

      $subfolders = $folderDAO->getEnfantsDirects ($classeurId, $folderId);
          foreach ($subfolders as $subfolder) {

            $files = self::getFilesInFolder($classeurId, $subfolder->id, $files);
          }
    }

        return $files;
    }

    /**
   * Stock en session le tri pour l'affichage des contenus du classeur
   *
   * @param string $folderColumn   Colonne sur laquelle trier le contenu
   * @param string $triDirection  Direction du tri
   */
    public static function setContentSort ($column, $direction)
    {
    $validSorts = array ('titre', 'origine', 'type', 'date', 'taille');

    if (!in_array($column, $validSorts)) {

      $column = 'titre';
    }

    _sessionSet ('classeur|tri_affichage_contenu', array ('colonne' => $column, 'direction' => $direction));
  }

  /**
   * Retourne le tri pour l'affichage des contenus du classeur
   *
   * @return array
   */
    public static function getContentSort ()
    {
      $sort = _sessionGet ('classeur|tri_affichage_contenu');
    if (is_null($sort)) {

      return array ('colonne' => 'titre', 'direction' => 'ASC');
    }

    return $sort;
    }

    /**
   * Récupère l'adresse web d'un favori - Fonction raccourcie
   *
   * @return string
   */
    public static function getFavoriteLink ($fileId)
    {
      $fileDAO = _ioDAO('classeur|classeurfichier');
      $file = $fileDAO->get ($fileId);

      if ($file) {

        return self::getUrlOfFavorite($file);
      }

      return null;
  }

    /**
   * Récupère l'adresse web d'un favori
   *
   * @param DAORecordClasseurFichier $file
   *
   * @return string
   */
    public static function getUrlOfFavorite (DAORecordClasseurFichier $file)
    {
      $classeurDAO = _ioDAO('classeur|classeur');
      $classeur = $classeurDAO->get($file->classeur_id);

      $extension  = strtolower(strrchr($file->fichier, '.'));
    $nomFichier = $file->id.'-'.$file->cle.$extension;

    $pathFichier = realpath('./static/classeur').'/'.$classeur->id.'-'.$classeur->cle.'/'.($nomFichier);
      if (file_exists($pathFichier)) {

      $regExp =     '@^(http[s]?:\/\/)([_a-zA-Z0-9-.?%#&=\/]+)@i';
      $regExpURL =  '@^(URL=)(http[s]?:\/\/)([_a-zA-Z0-9-.?%#&=\/]+)@i';

      $content = file_get_contents ($pathFichier);

      $lines = explode ("\n",$content);

      $firstLine = (isset($lines[0])) ? $lines[0] : '';
      $firstLine9 = strtolower(substr($firstLine,0,9));

      if ($firstLine9 == '[internet') {

        $line = (isset($lines[1])) ? $lines[1] : '';
        if ($line) {

          if (preg_match($regExpURL, $line, $regs)) {

            return $regs[2].$regs[3];
          }
        }
      } else {

        if ($firstLine9 == '[default]') {

          $line = (isset($lines[3])) ? $lines[3] : '';
          if ($line) {

            if (preg_match($regExpURL, $line, $regs)) {

              return $regs[2].$regs[3];
            }
          }
        } else {

          $line = (isset($lines[0])) ? $lines[0] : '';
          if (preg_match($regExp, $line, $regs)) {

            return $regs[1].$regs[2];
          }
        }
      }
      }
    }

    /**
   * Fonction récursive de suppression d'un répertoire
   *
   * @param string  $dir  Dossier à vider
   */
    public static function rmdir_recursive($dir)
  {
      if (file_exists($dir)) {

      $dir_content = scandir($dir);

      if ($dir_content !== false) {

        foreach ($dir_content as $entry) {
          if (!in_array($entry, array('.','..'))) {
            $entry = $dir . '/' . $entry;
            if (!is_dir($entry)) {

              unlink($entry);
            } else {
              self::rmdir_recursive($entry);
            }
          }
        }
      }

      rmdir($dir);
    }
  }

    //////////////////////////////////////////////
    // Récupération de méthodes du module malle //
    //////////////////////////////////////////////

    /**
  * Genere le contenu d'un ficher type .web, contenant un raccourci vers un site
  *
  * @author Christophe Beyer <cbeyer@cap-tic.fr>
  * @since 2010/09/16
  * @param string $url URL du lien
  * @link http://www.cyanwerks.com/file-format-url.html
  */
  public static function generateWebFile ($url)
  {
    $res =
     "[DEFAULT]\n"
    ."BASEURL=".$url."\n"
    ."[InternetShortcut]\n"
    ."URL=".$url."\n"
    ."Modified=";

    return $res;
  }


  /**
  * Renvoit le dossier temporaire a utiliser pour l'upload. A utiliser a la place de sys_get_temp_dir().
  *
  * @author Christophe Beyer <cbeyer@cap-tic.fr>
  * @since 2011/08/17
  * @return string Chemin absolu vers le dossier temporaire. Finit par un /
  */
  public static function getTmpFolder()
  {
      if (isset($_ENV['DYLD_LIBRARY_PATH']) && $_ENV['DYLD_LIBRARY_PATH']== '/Applications/MAMP/Library/lib:') // Patch MAMP
          $dossierTmp = '/tmp';
      else
          $dossierTmp = sys_get_temp_dir();
      if (substr($dossierTmp, -1) != '/') {
          $dossierTmp = $dossierTmp.'/';
      }
      return $dossierTmp;
  }



}