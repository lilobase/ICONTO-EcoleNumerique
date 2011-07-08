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
   * Stock l'état de l'arbre des noeuds classeurs
   *
   * @param int $id ID du noeud de classeur
   */
  public static function setClasseursTreeState ($id) {
    
    $state = _sessionGet ('classeur|classeurs_tree_state');

    if (isset ($state[$id])) {
    
      unset ($state[$id]);
    }
    else {
      
      $state[$id] = 1;
    }

    _sessionSet ('classeur|classeurs_tree_state', $state);
  }
  
  /**
   * Retourne l'état de l'arbre des noeuds classeurs
   *
   * @return array
   */
  public static function getClasseursTreeState () {
    
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
    
    $treeState = _sessionGet ('classeur|folders_tree_state');
    if (!isset($treeState) || !is_array($treeState)) {
      
      return array();
    }
    
    return $treeState;
  }
  
  /**
   * Ouvre l'arborescence des classeurs / dossiers
   */
  public static function openTree($classeurId, $folderId) {
    
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
		
		unlink ($filepath);
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
    }
    else {
      
      $folder->classeur_id  = $targetId;
      $folder->parent_id    = 0;
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

    // Si classeurs différents on déplace le fichier
    if ($oldClasseur != $newClasseur) {
      
      $old_dir = realpath('./static/classeur').'/'.$oldClasseur->id.'-'.$oldClasseur->cle.'/';
      $new_dir = realpath('./static/classeur').'/'.$newClasseur->id.'-'.$newClasseur->cle.'/';
      
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
    $old_dir = realpath('./static/classeur').'/'.$oldClasseur->id.'-'.$oldClasseur->cle.'/';
    $new_dir = realpath('./static/classeur').'/'.$newClasseur->id.'-'.$newClasseur->cle.'/';
    
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
	public static function addFileToZip ($file, $zip) {
    
    // Récupération du classeur nécessaire pour déterminer le chemin du fichier
    $classeurDAO = _ioDAO('classeur|classeur');
    $classeur = $classeurDAO->get($file->classeur_id);
    
    // Path du fichier
    $dir        = realpath('./static/classeur').'/'.$classeur->id.'-'.$classeur->cle.'/';
    $extension  = strrchr($file->fichier, '.');
    
    $pathfile = $dir.$file->id.'-'.$file->cle.$extension;
    
    $zip->addFile($pathfile, $file->id.'-'.$file->fichier);
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
	 * Met à jour les informations d'un dossier (nb_dossiers / nb_fichiers & taille)
	 *
	 * @param DAORecordClasseurDossier $folder      Dossier à mettre à jour
	 */
	public static function updateFolderInfos ($folder) {
		
		$folderDAO = _ioDAO('classeur|classeurdossier');
		
		if ($folder) {
		  
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
	 */
	public static function updateFolderInfosWithDescendants ($folder) {
	  
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
	 */
	public static function getFilesInFolder($classeurId, $folderId = null, $files = array(), $withSubfolders = true) {
	  
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
	public static function setContentSort ($column, $direction) {
        
    $validSorts = array ('titre', 'type', 'date', 'taille');

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
	public static function getContentSort () {
	  
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
	public static function getFavoriteLink ($fileId) {
	  
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
   * @return string 
   */
	public static function getUrlOfFavorite ($file) {
	  
	  $classeurDAO = _ioDAO('classeur|classeur');
	  $classeur = $classeurDAO->get($file->classeur_id);
	  
	  $extension  = strrchr($file->fichier, '.');
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
      } 
      else {
        
        if ($firstLine9 == '[default]') {
          
          $line = (isset($lines[3])) ? $lines[3] : '';
          if ($line) {
            
            if (preg_match($regExpURL, $line, $regs)) {
              
              return $regs[2].$regs[3];
            }
          }
        } 
        else {
          
          $line = (isset($lines[0])) ? $lines[0] : '';
          if (preg_match($regExp, $line, $regs)) {
            
            return $regs[1].$regs[2];
          }
        }
      }
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
  public static function generateWebFile ($url) {
    
    $res = 
     "[DEFAULT]\n"
    ."BASEURL=".$url."\n"
    ."[InternetShortcut]\n"
    ."URL=".$url."\n"
    ."Modified=";
    
    return $res;
  }
  
  /**
	 * Retourne des infos sur un type MIME en clair
	 *
	 * A partir d'un type MIME ou d'une extension de fichier, retourne des infos en clair dans un tableau index� : type_txt = nom en clair en Fran�ais (ex: Document Word), type_icon = nom de l'image icone � utiliser (dans /www/img/malle/)
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2005/12/06
	 * @param string $mime_type Type MIME
	 * @return array Tableau index�
	 */
	public static function getTypeInfos ($mime_type, $file_name='') {

		$point = strrpos ($file_name, ".");
		if (!$mime_type) {
			$mime_type = strtolower(substr($file_name,$point+1));
		}

		switch (strtolower($mime_type)) {
			case "text/plain" :
			case "text/cpp" :
			case "txt" :
				$res = array('type_text'=>CopixI18N::get ('malle|mime.txt'), 'type_icon'=>'icon_file_txt.png', 'type_icon32'=>'icon_file_txt32.png', 'type_mime'=>'text/plain');
				break;
				
			case "text/richtext" :
			case "application/rtf" :
			case "rtf" :
				$res = array('type_text'=>CopixI18N::get ('malle|mime.rtf'), 'type_icon'=>'icon_file_txt.png', 'type_icon32'=>'icon_file_txt32.png', 'type_mime'=>'text/richtext');
				break;
				
			case "application/msword" :
			case "doc" :
			case "docx" :
			case "application/vnd.oasis.opendocument.text" :
			case "odt" :
				if (strtolower($mime_type)=='application/msword' || strtolower($mime_type)=='doc' || strtolower($mime_type)=='docx')
					$type_mime = 'application/msword';
				else
					$type_mime = 'application/vnd.oasis.opendocument.text';
				$res = array('type_text'=>CopixI18N::get ('malle|mime.doc'), 'type_icon'=>'icon_file_txt.png', 'type_icon32'=>'icon_file_txt32.png', 'type_mime'=>$type_mime);
				break;
			
			case "application/vnd.ms-powerpoint" :
			case "ppt" :
			case "pptx" :
			case "pps" :
			case "odg" :
				if (strtolower($mime_type)=='odg')
  				$type_mime = 'application/vnd.oasis.opendocument.graphics';		
				else
					$type_mime = 'application/vnd.ms-powerpoint';
				$res = array('type_text'=>CopixI18N::get ('malle|mime.presentation'), 'type_icon'=>'icon_file_presentation.png', 'type_icon32'=>'icon_file_presentation32.png', 'type_mime'=>$type_mime);
				break;
			
			case "image/jpeg" :
			case "jpg" :
			case "jpeg" :
				$res = array('type_text'=>CopixI18N::get ('malle|mime.image.jpg'), 'type_icon'=>'icon_file_image.png', 'type_icon32'=>'icon_file_image32.png', 'type_mime'=>'image/jpeg');
				break;

			case "image/png" :
			case "png" :
				$res = array('type_text'=>CopixI18N::get ('malle|mime.image.png'), 'type_icon'=>'icon_file_image.png', 'type_icon32'=>'icon_file_image32.png', 'type_mime'=>'image/png');
				break;

			case "image/gif" :
			case "gif" :
				$res = array('type_text'=>CopixI18N::get ('malle|mime.image.gif'), 'type_icon'=>'icon_file_image.png', 'type_icon32'=>'icon_file_image32.png', 'type_mime'=>'image/gif');
				break;

			case "image/bmp" :
			case "bmp" :
				$res = array('type_text'=>CopixI18N::get ('malle|mime.image.bmp'), 'type_icon'=>'icon_file_image.png', 'type_icon32'=>'icon_file_image32.png', 'type_mime'=>'image/bmp');
				break;

			case "audio/wav" : 
			case "wav" :
			case "audio/mpeg" : 
			case "mp3" : 
				if (strtolower($mime_type)=='audio/wav' || strtolower($mime_type)=='wav')
					$type_mime = 'audio/wav';
				else
					$type_mime = 'audio/mpeg';
				$res = array('type_text'=>CopixI18N::get ('malle|mime.sound'), 'type_icon'=>'icon_file_sound.png', 'type_icon32'=>'icon_file_sound32.png', 'type_mime'=>$type_mime);
				break;
				
			case "application/pdf" :
			case "pdf" :
				$res = array('type_text'=>CopixI18N::get ('malle|mime.pdf'), 'type_icon'=>'icon_file_pdf.png', 'type_icon32'=>'icon_file_pdf32.png', 'type_mime'=>'application/pdf');
				break;
				
			case "application/vnd.ms-excel" :
			case "xls" :
			case "xlsx" :
			case "application/vnd.oasis.opendocument.spreadsheet" :
			case "ods" :
        if (strtolower($mime_type)=='application/vnd.ms-excel' || strtolower($mime_type)=='xls' || strtolower($mime_type)=='xlsx')
  				$type_mime = 'application/vnd.ms-excel';		
				else
					$type_mime = 'application/vnd.oasis.opendocument.spreadsheet';
				$res = array('type_text'=>CopixI18N::get ('malle|mime.xls'), 'type_icon'=>'icon_file_spreadsheet.png', 'type_icon32'=>'icon_file_spreadsheet32.png', 'type_mime'=>$type_mime);
				break;
				
			case "video/mpeg" :
			case "video/x-ms-wmv" :
			case "mpg" :
			case "mpeg" :
			case "video/3gpp" :
			case "3gp" :
			case "video/quicktime" :
			case "mov" :
				if (strtolower($mime_type)=='video/3gpp' || strtolower($mime_type)=='3gp')
					$type_mime = 'video/3gpp';
				elseif (strtolower($mime_type)=='video/quicktime' || strtolower($mime_type)=='mov')
					$type_mime = 'video/quicktime';
				else
					$type_mime = 'video/mpeg';
				$res = array('type_text'=>CopixI18N::get ('malle|mime.video'), 'type_icon'=>'icon_file_video.png', 'type_icon32'=>'icon_file_video32.png', 'type_mime'=>$type_mime);
				break;

			case "application/zip" :
			case "zip" :
			case "application/forcedownload" :
				$res = array('type_text'=>CopixI18N::get ('malle|mime.zip'), 'type_icon'=>'icon_file_zip.png', 'type_icon32'=>'icon_file_zip32.png', 'type_mime'=>'application/zip');
				break;
			
            case "text/xml" :
				$res = array('type_text'=>CopixI18N::get ('malle|mime.xml'), 'type_icon'=>'icon_file_xml.png', 'type_icon32'=>'icon_file_xml32.png', 'type_mime'=>'text/xml');
				break;

            case "application/x-smarttech-notebook" :
            case "nbk" :
            case "xbk" :
            case "notebook" :
				$res = array('type_text'=>CopixI18N::get ('malle|mime.notebook'), 'type_icon'=>'icon_file_presentation.png', 'type_icon32'=>'icon_file_presentation32.png', 'type_mime'=>'application/x-smarttech-notebook');
				break;

			
			default :
				if ($point !== false) {
					$ext = strtolower(substr($file_name,$point+1));
					switch( $ext ) {
						case 'flv':
							$res = array('type_text'=>CopixI18N::get ('malle|mime.flv'), 'type_icon'=>'icon_file_video.png', 'type_icon32'=>'icon_file_video32.png');
							break;
						default:
							$res = array('type_text'=>CopixI18N::get ('malle|mime.default'), 'type_icon'=>'icon_file.png', 'type_icon32'=>'icon_file32.png');
							Logs::set (array('type'=>'INFO', 'message'=>"getTypeInfos ($mime_type, $file_name)"));
					}
				} else {
					$res = array('type_text'=>CopixI18N::get ('malle|mime.default'), 'type_icon'=>'icon_file.png', 'type_icon32'=>'icon_file32.png');			
					Logs::set (array('type'=>'INFO', 'message'=>"getTypeInfos ($mime_type, $file_name)"));
				}
				break;
				
		}
    //print_r($res);
		return $res;
	}
}