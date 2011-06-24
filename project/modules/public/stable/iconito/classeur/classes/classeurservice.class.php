<?php

/**
* @package    Iconito
* @subpackage Classeur
* @author     Jérémy FOURNAISE
*/

class ClasseurService {
  
  /**
   * Méthode de génération d'une clé utilisée pour les dossiers et fichiers du classeur
   *
   * @return string
   */
  public static function createKey () {
    
	  return substr(md5(microtime()), 0, 10);
  }
  
  /**
   * Stock l'état de l'arbre des noeuds dossiers
   *
   * @param int $id ID du noeud de dossier
   */
  public static function setFoldersTreeState ($id) {
    
    $state = _sessionGet ('classeur|folders_tree_state');

    if (isset ($state[$id])) {
    
      unset ($state[$id]);
    }
    else {
      
      $state[$id] = 1;
    }

    _sessionSet ('classeur|folders_tree_state', $state);
  }
  
  /**
   * Retourne l'état de l'arbre des noeuds dossiers
   *
   * @return array
   */
  public static function getFoldersTreeState () {
    
    return _sessionGet ('classeur|folders_tree_state');
  }
  
  /**
  * Renvoie le type MIME d'un fichier
  *
  * @param  string $filename Nom du fichier
  *
  * @return string
  */
  public static function getMimeType ($filename) {
    
    $point = strrpos ($filename, '.');
    
    if ($point !== false) {
      
      $ext = substr($filename, $point+1);
      $ext = strtolower($ext);
    }
    else {
      
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
	public static function deleteFolder ($folder, $withFiles = true) {
		
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
	public static function deleteFile ($file) {
		
		$fileDAO      = _ioDAO('classeur|classeurfichier');
		$classeurDAO  = _ioDAO('classeur|classeur');
		
		// Récupération du classeur du fichier
		$classeur = $classeurDAO->get ($file->classeur_id);
		
		// On supprime le fichier
		$extension  = strrchr($file->fichier, '.');
		$filename   = $file->id.'-'.$file->cle;
		$filepath   = realpath('./static/classeur').'/'.$classeur->id.'-'.$classeur->cle.'/'.$file->id.'-'.$file->cle.$extension;
		
		@unlink ($filepath);
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
	public static function moveFolder ($folder, $targetType, $targetId, $withFiles = true) {
	  
	  $folderDAO    = _ioDAO('classeur|classeurdossier');
    $fileDAO      = _ioDAO('classeur|classeurfichier');
		
		// Pour chaque sous dossiers on rappelle la méthode
		$subfolders = $folderDAO->getEnfantsDirects ($folder->classeur_id, $folder->id);
		foreach ($subfolders as $subfolder) {
		  
		  self::moveFolder ($subfolder, $targetType, $targetId);
		}

    // Déplacement du dossier
    if ($targetType == 'dossier') {
      
      $targetFolder = $folderDAO->get($targetId);
      
      $folder->classeur_id = $targetFolder->classeur_id;
      $folder->parent_id   = $targetId;
    }
    else {
      
      $folder->classeur_id  = $targetId;
      $folder->parent_id    = 0;
    }
    
    // Mise à jour du dossier après déplacement
    $folderDAO->update($folder);
    
    // Récupération des fichiers du dossier pour le déplacement
    if ($withFiles) {
      
  		$files = $fileDAO->getParDossier ($folder->classeur_id, $folder->id);
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
	public static function moveFile ($file, $targetType, $targetId) {
	  
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
    }
    else {
      
      $newClasseur = $classeurDAO->get($targetId);
      
      $file->classeur_id  = $targetId;
      $file->dossier_id   = 0;
    }
    
    $fileDAO->update($file);
    
    // Copie physique du fichier
    $old_dir = $_SERVER['DOCUMENT_ROOT'].'static/classeur/'.$oldClasseur->id.'-'.$oldClasseur->cle.'/';
    $new_dir = $_SERVER['DOCUMENT_ROOT'].'static/classeur/'.$newClasseur->id.'-'.$newClasseur->cle.'/';
    
    if ($old_dir != $new_dir) {
      
      if (!file_exists($new_dir)) {

        mkdir($new_dir, 0755, true);
      }

      $extension = strrchr($file->fichier, '.');
      copy($old_dir.$file->id.'-'.$file->cle.$extension, $new_dir.$file->id.'-'.$file->cle.$extension);
      unlink($old_dir.$file->id.'-'.$file->cle.$extension);
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
	public static function copyFolder ($folder, $targetType, $targetId, $withFiles = true) {
	  
	  $folderDAO    = _ioDAO('classeur|classeurdossier');
    $fileDAO      = _ioDAO('classeur|classeurfichier');
		
		// Copie du dossier
    if ($targetType == 'dossier') {
      
      $targetFolder = $folderDAO->get($targetId);
      
      $clone = clone $folder;
      $clone->classeur_id = $targetFolder->classeur_id;
      $clone->parent_id   = $targetId;
    }
    else {
      
      $clone = clone $folder;
      $clone->classeur_id = $targetId;
      $clone->parent_id   = 0;
    }
    
    // Insertion du nouveau dossier
    $folderDAO->insert($clone);
		
		// Pour chaque sous dossiers on rappelle la méthode
		$subfolders = $folderDAO->getEnfantsDirects ($folder->classeur_id, $folder->id);
		foreach ($subfolders as $subfolder) {
		  
		  self::copyFolder ($subfolder, 'dossier', $clone->id);
		}
    
    // Récupération des fichiers du dossier pour la copie
    if ($withFiles) {
      
  		$files = $fileDAO->getParDossier ($folder->classeur_id, $folder->id);
  		foreach($files as $file) {

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
	public static function copyFile ($file, $targetType, $targetId) {
	  
	  $classeurDAO  = _ioDAO('classeur|classeur');
	  $folderDAO    = _ioDAO('classeur|classeurdossier');
    $fileDAO      = _ioDAO('classeur|classeurfichier');
    
    // Récupération du classeur
    $oldClasseur = $classeurDAO->get($file->classeur_id);
    
    // Copie de l'enregistrement fichier
    $clone = clone $file;
    $clone->cle = self::createKey();
    
    if ($targetType == 'dossier') {
      
      $targetFolder = $folderDAO->get($targetId);
      $newClasseur  = $classeurDAO->get($targetFolder->classeur_id);
      
      $clone->classeur_id = $targetFolder->classeur_id;
      $clone->dossier_id  = $targetFolder->id;
    }
    else {
      
      $newClasseur  = $classeurDAO->get($targetId);
      
      $clone->classeur_id  = $targetId;
      $clone->dossier_id   = 0;
    }
    
    // Insertion du nouveau fichier
    $fileDAO->insert($clone);
    
    // Copie physique du fichier
    $old_dir = $_SERVER['DOCUMENT_ROOT'].'static/classeur/'.$oldClasseur->id.'-'.$oldClasseur->cle.'/';
    $new_dir = $_SERVER['DOCUMENT_ROOT'].'static/classeur/'.$newClasseur->id.'-'.$newClasseur->cle.'/';
    
    if (!file_exists($new_dir)) {
      
      mkdir($new_dir, 0755, true);
    }
    
    $extension = strrchr($file->fichier, '.');
    copy($old_dir.$file->id.'-'.$file->cle.$extension, $new_dir.$clone->id.'-'.$clone->cle.$extension);
	}
	
	/**
	 * Ajoute le contenu d'un dossier dans une archive ZIP
	 *
	 * @param DAORecordClasseurDossier $folder      Dossier à ajouter
	 * @param ZipArchive               $zip         Archive ZIP à laquelle ajouter le contenu du dossier
	 */
	public static function addFolderToZip ($folder, $zip) {
  
    $folderDAO = _ioDAO('classeur|classeurdossier');
    $fileDAO   = _ioDAO('classeur|classeurfichier');
    
    // Pour chaque sous dossiers on rappelle la méthode
		$subfolders = $folderDAO->getEnfantsDirects ($folder->classeur_id, $folder->id);
		foreach ($subfolders as $subfolder) {
		  
		  self::addFolderToZip ($subfolder, $zip);
		}
		
		$files = $fileDAO->getParDossier ($folder->classeur_id, $folder->id);
		foreach($files as $file) {

		  self::addFileToZip ($file, $zip);
		}
  }
  
  /**
	 * Ajoute un fichier dans une archive ZIP
	 *
	 * @param DAORecordClasseurFichier $file        Fichier à ajouter
	 * @param ZipArchive               $zip         Archive ZIP à laquelle ajouter le contenu du dossier
	 */
	public static function addFileToZip ($file, $zip) {
    
    // Récupération du classeur nécessaire pour déterminer le chemin du fichier
    $classeurDAO = _ioDAO('classeur|classeur');
    $classeur = $classeurDAO->get($file->classeur_id);
    
    // Path du fichier
    $dir        = $_SERVER['DOCUMENT_ROOT'].'static/classeur/'.$classeur->id.'-'.$classeur->cle.'/';
    $extension  = strrchr($file->fichier, '.');
    
    $pathfile = $dir.$file->id.'-'.$file->cle.$extension;
    
    $zip->addFile($pathfile, $file->fichier);
  }
  
  /**
	 * Teste si le folder1 est un descendant du folder2
	 *
	 * @param DAORecordClasseurDossier  $folder1     Dossier 1
	 * @param DAORecordClasseurDossier  $folder2     Dossier 2
	 *
	 * @return bool True si le folder1 est un descendant du folder2
	 */
  public static function isDescendantOf ($folder1, $folder2) {
    
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
   * Stock en session le tri pour l'affichage des contenus du classeur
   *
   * @param string $triDossiers   Colonne sur laquelle trier les dossiers
   * @param string $triFichiers   Colonne sur laquelle trier les fichiers
   * @param string $triDirection  Direction du tri
   */
	public static function setContentSort ($folderSort, $fileSort, $direction) {
    
    $validFolderSorts = array ('nom', 'date_creation');
    $validFileSorts   = array ('titre', 'taille', 'type', 'date_upload');
    
    if (!in_array($fileSort, $validFileSorts)) {
      
      $fileSort = 'titre';
    }
    if (!in_array($folderSort, $validFolderSorts)) {
      
      $folderSort = 'nom';
    }
    
    _sessionSet ('classeur|tri_affichage_contenu', array ('triDossiers' => $folderSort, 'triFichiers' => $fileSort, 'triDirection' => $direction));
  }
  
  /**
   * Retourne le tri pour l'affichage des contenus du classeur
   *
   * @return array 
   */
	public static function getContentSort () {
	  
	  $sort = _sessionGet ('classeur|tri_affichage_contenu');
    if (is_null($sort)) {
      
      return array ('triDossiers' => 'nom', 'triFichiers' => 'titre', 'triDirection' => 'ASC');
    }

    return $sort;
	}
}