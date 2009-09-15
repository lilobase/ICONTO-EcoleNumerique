<?php
/**
 * Fonctions diverses du module Malle
 * 
 * @package Iconito
 * @subpackage	Malle
 */
 
require_once (COPIX_MODULE_PATH.'logs/'.COPIX_CLASSES_DIR.'logs.class.php');
 
class MalleService {

	/**
	 * Retourne des infos sur un type MIME en clair
	 *
	 * A partir d'un type MIME ou d'une extension de fichier, retourne des infos en clair dans un tableau indexé : type_txt = nom en clair en Français (ex: Document Word), type_icon = nom de l'image icone à utiliser (dans /www/img/malle/)
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2005/12/06
	 * @param string $mime_type Type MIME
	 * @return array Tableau indexé
	 */
	function getTypeInfos ($mime_type, $file_name='') {
		//print_r("getTypeInfos ($mime_type)");

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
			case "application/vnd.oasis.opendocument.text" :
			case "ods" :
				if (strtolower($mime_type)=='application/msword' || strtolower($mime_type)=='doc')
					$type_mime = 'application/msword';
				else
					$type_mime = 'application/vnd.oasis.opendocument.text';
				$res = array('type_text'=>CopixI18N::get ('malle|mime.doc'), 'type_icon'=>'icon_file_txt.png', 'type_icon32'=>'icon_file_txt32.png', 'type_mime'=>$type_mime);
				break;
			
			case "application/vnd.ms-powerpoint" :
			case "ppt" :
			case "pps" :
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
			case "application/vnd.oasis.opendocument.spreadsheet" :
			case "ods" :
				$res = array('type_text'=>CopixI18N::get ('malle|mime.xls'), 'type_icon'=>'icon_file_spreadsheet.png', 'type_icon32'=>'icon_file_spreadsheet32.png');
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
		return $res;
	}
	

	/**
	 * Met à jour les infos d'un répertoire (nb de fichiers, de dossiers, taille), et se  propage vers le "haut" jusqu'à la racine
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2005/12/08
	 * @param integer $malle	Id de la malle
	 * @param integer $folder	Id du dossier (ou 0 si racine)
	 */
	function update_infos_for_folder ($malle, $folder) {
		
		$daoFolders = CopixDAOFactory::create("malle|malle_folders");
		
		$rFolder = $daoFolders->get ($folder);
		if ($rFolder) {
			$i=0;
			$fusible = 99;
	    while ($rFolder->parent != 0 && $i<$fusible) {
				$rFolder = $daoFolders->get ($rFolder->parent);
      	$i++;
    	}
			malleService::update_infos_for_folder_desc ($malle, $rFolder->id);
		}
	}


	/**
	 * Met à jour les infos d'un répertoire (nb de fichiers, de dossiers, taille), et se  propage vers le "bas" dans ses sous-répertoires (fonction récursive)
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2005/12/08
	 * @param integer $malle	Id de la malle
	 * @param integer $folder	Id du dossier (ou 0 si racine)
	 * @return array Tableau avec les infos du dossier courant : nb (nb de fichiers), nb_folders (nb de dossiers), taille (taille en octets)
	 */
	function update_infos_for_folder_desc ($malle, $folder) {
		
		$daoFiles = CopixDAOFactory::create("malle|malle_files");
		$daoFolders = CopixDAOFactory::create("malle|malle_folders");
		//$malleService = & CopixClassesFactory::Create ('malle|malleService');
		
		$infos = $daoFiles->getNbFilesInFolder ($malle, $folder);
		//print_r($infos);
		$infos2 = $daoFiles->getNbFoldersInFolder ($malle, $folder);
		//print_r($infos);
	
		$res["nb"] 		= $infos[0]->nb;
		$res["taille"] = $infos[0]->taille;
		//$res["date_maj"] = $infos[0]->date_maj;
		$res["nb_folders"] = $infos2[0]->nb;

		// Les répertoires en-dessous"
		$folders = $daoFolders->getFoldersInFolder($malle, $folder);
		foreach ($folders as $rep) {
			//print_r($rep);
			$tmp = MalleService::update_infos_for_folder_desc ($malle, $rep->id); 
			$res["nb"] 		+= $tmp["nb"];
			$res["taille"] += $tmp["taille"];
			$res["nb_folders"] += $tmp["nb_folders"];
			//if ($tmp["date_maj"]>$res["date_maj"]) $res["date_maj"]=$tmp["date_maj"];
		}
		
		$obj = $daoFolders->get ($folder);
		if ($obj) {
			//print_r($res);
			$obj->nb_files = $res["nb"]*1;
			$obj->nb_folders = $res["nb_folders"]*1;
			$obj->taille = $res["taille"]*1;
			$daoFolders->update ($obj);		
		}
		/*
		//$date_maj = ($res["date_maj"]) ? "'".$res["date_maj"]."'" : "NULL";
		$sql = "UPDATE blog_photos_albums SET nb_photos=".($res["nb"]*1).", poids=".($res["poids"]*1).", date_maj=".$date_maj." WHERE id=".$album."";
		//deb ("sql=$sql");
		runQuery ($sql);
		*/
		return $res;
	}


	/**
	 * Suppression d'un fichier
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2005/12/08
	 * @param object rFile recordset du fichier (récupéré par DAO)
	 */
	function deleteFile ($rFile) {
		$daoFiles = CopixDAOFactory::create("malle|malle_files");
		
		// On supprime le fichier
		$fichier = $rFile->id.'_'.$rFile->fichier;
		$fullFile = realpath('./static/malle').'/'.$rFile->malle.'_'.$rFile->malle_cle.'/'.($fichier);
		@unlink ($fullFile);
		$daoFiles->delete ($rFile->id);
	}

	/**
	 * Suppression d'un répertoire. Supprime aussi ses sous-répertoires
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2005/12/08
	 * @param object $rFile recordset du dossier (récupéré par DAO)
	 */
	function deleteFolder ($rFolder) {
		
		$daoFolders = CopixDAOFactory::create("malle|malle_folders");
		$daoFiles = CopixDAOFactory::create("malle|malle_files");
		
		// On prend tous les sous-dossiers
		$folders = $daoFolders->getFoldersInFolder($rFolder->malle, $rFolder->id);
		foreach ($folders as $folder) {
			MalleService::deleteFolder ($folder);
		}
		
		// Pour chacun, on supprime tous les fichiers
		$files = $daoFiles->getFilesInFolder($rFolder->malle, $rFolder->id);
		foreach ($files as $file) {
			MalleService::deleteFile ($file);
		}
		
		$daoFolders->delete ($rFolder->id);
	}

	/**
	 * L'arborescence des dossiers d'une malle, dans un tableau
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2005/12/07
	 * @param integer $malle	Id de la malle
	 * @param integer $folder (option, 0 par défaut) Id du dossier (ou 0 si racine)
	 * @param integer $niveau (option, 1 par défaut)	Profondeur dans l'arbre
	 * @return array Tableau avec l'arborescence. Chaque dossier correspond à une entrée dans le tableau, comprenant un tableau indexé comme suit : id (id du dossier), nom (son nom), niveau (profondeur par rapport à la racine)
	 */
	function buildComboFolders ($malle, $folder=0, $niveau=1) {
		//print_r("buildComboFolders ($malle, $folder, $res)");
		$daoFolders = CopixDAOFactory::create("malle|malle_folders");
		$res = array();
		// On lit les dossiers de cette malle
		$folders = $daoFolders->getFoldersInFolder($malle, $folder);
		foreach ($folders as $rFolder) {
			//print_r($rFolder);
			$tmp = array('id'=>$rFolder->id, 'nom'=>$rFolder->nom, 'niveau'=>$niveau);
			$res[] = $tmp;
			$res = array_merge($res, malleService::buildComboFolders ($malle, $rFolder->id, $niveau+1));
		}
		return $res;
	}

	/**
	 * Teste si un dossier est situé "plus bas" qu'un autre dossier (cad figure dans son arborescence)
	 *
	 * Sert notamment de vérification avant de déplacer/copier un dossier
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2005/12/06
	 * @param integer $folder1	Id du premier dossier
	 * @param integer $folder2	Id du deuxième dossier
	 * @return bool true si $folder1 est dans l'arborescence descendante partant de $folder2, false sinon
	 */
	function isFolderUnderFolder ($folder1, $folder2) {
		//print_r("isFolderUnderFolder ($folder1, $folder2)");
		$daoFolders = CopixDAOFactory::create("malle|malle_folders");

		$fini = $res = false;

		$rFolder = $daoFolders->get($folder1);
		$fusible = 99;
		// On remonte de $folder1 jusqu'à la racine ou jusqu'à trouver $folder2
		while (!$fini && $fusible) {
			
			if ($rFolder->parent==0 || $rFolder->parent==$folder2)
				$fini = true;
			
			if ($rFolder->parent==$folder2)
				$res = true;
			
			if ($rFolder->parent)
				$rFolder = $daoFolders->get($rFolder->parent);

			$fusible--;
		}	
		if ($folder1 == $folder2)
			$res = true;
		//die("res=$res");
		return $res;
	}


	/**
	 * Copie un fichier dans un autre dossier
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2005/12/07
	 * @param object $rFile recordset du fichier "source" à copier (obtenu par DAO)
	 * @param integer $folderDest Id du répertoire destination
	 * @return true si l'opération s'est bien passée, false sinon
	 */
	function copyFile ($rFile, $folderDest) {
		//print_r($rFile);
		$res = false;
		$daoFiles = CopixDAOFactory::create("malle|malle_files");
		
		// On insère d'abord dans la base
		$new = CopixDAOFactory::createRecord("malle|malle_files");
		$new->malle = $rFile->malle;
		$new->folder = $folderDest;
		$new->nom = $rFile->nom;
		$new->fichier = $rFile->fichier;
		$new->taille = $rFile->taille;
		$new->type = $rFile->type;
		$new->cle = MalleService::createKey();
		$new->date_upload = date("Y-m-d H:i:s");
		$daoFiles->insert ($new);
				
		if ($new->id!==NULL) {
			$fromFileName = $rFile->id."_".$rFile->fichier;
			$toFileName = $new->id."_".$new->fichier;
			$from = realpath('./static/malle').'/'.$rFile->malle.'_'.$rFile->malle_cle.'/'.($fromFileName); 
      $to 	= realpath('./static/malle').'/'.$rFile->malle.'_'.$rFile->malle_cle.'/'.($toFileName); 
      if (copy($from, $to)) {
				$res = true;
			} else {
				$daoFiles->delete ($new->id);
			}
		}
		return $res;
	}


	/**
	 * Copie un dossier et son contenu dans un autre dossier
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2005/12/07
	 * @param object $rFolder recordset du dossier "source" à copier (obtenu par DAO)
	 * @param integer $folderDest Id du répertoire destination
	 * @return mixed id du dossier crée, ou NULL si problème
	 */
	function copyFolder ($rFolder, $folderDest) {
		//print_r($rFolder);
		$res = NULL;
		$daoFolders = CopixDAOFactory::create("malle|malle_folders");
		$daoFiles = CopixDAOFactory::create("malle|malle_files");
		
		// On insère d'abord dans la base
		$new = CopixDAOFactory::createRecord("malle|malle_folders");
		$new->malle = $rFolder->malle;
		$new->parent = $folderDest;
		$new->nom = $rFolder->nom;
		$new->nb_folders = 0;
		$new->nb_files = 0;
		$new->taille = 0;
		$new->date_creation = date("Y-m-d H:i:s");
		$daoFolders->insert ($new);
				
		if ($new->id!==NULL) {
			
			// On cherche ses fichiers, à copier aussi
			$files = $daoFiles->getFilesInFolder($rFolder->malle, $rFolder->id);
			foreach ($files as $file) {
				malleService::copyFile ($file, $new->id);
			}
			
			// On parcourt ensuite ses sous-dossiers, pour lesquels on fait aussi la copie
			$folders = $daoFolders->getFoldersInFolder($rFolder->malle, $rFolder->id);
			foreach ($folders as $folder) {
				malleService::copyFolder ($folder, $new->id);
			}
			
      if (1) {
				$res = $new->id;
			} else {
				$daoFolders->delete ($new->id);
			}
		}
		return $res;
	}


	/**
	 * Gestion des droits dans une malle
	 *
	 * Teste si l'usager peut effectuer une certaine opération par rapport à son droit. Le droit sur la malle nécessite d'être connu, renvoyé par le kernel avant l'entrée dans cette fonction.
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2005/12/08
	 * @param string $action Action pour laquelle on veut tester le droit
	 * @param integer $droit Le droit de l'usager
	 * @return bool true s'il a le droit d'effectuer l'action, false sinon
	 */
	function canMakeInMalle ($action, $droit) {
		$can = false;
		switch ($action) {
			case "READ" :
			case "FILE_DOWNLOAD" :
			case "ITEM_DOWNLOAD_ZIP" :
				$can = ($droit >= PROFILE_CCV_READ);
				break;
			case "FILE_UPLOAD" :
				$can = ($droit >= PROFILE_CCV_MEMBER);
				break;
			case "FOLDER_CREATE" :
			case "ITEM_DELETE" : // Supprimer fichier/dossier
			case "ITEM_MOVE" : // Déplacer fichier/dossier
			case "ITEM_COPY" : // Copier fichier/dossier
			case "ITEM_RENAME" : // Renommer fichier/dossier
				$can = ($droit >= PROFILE_CCV_MODERATE);
				break;
		}
		return $can;
	}



	/**
	 * Renvoie le chemin vers le répertoire temporaire du serveur, dans lequel Apache a les droits d'écriture
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2006/04/20
	 * @return string Chemin complet. Ne finit PAS par un slash
	 */
	function getTmpFolder () {
		if (getenv('TMP'))
			$res = getenv('TMP');
		else
			$res = "/tmp";
		return $res;
	}
	

	/**
	 * Efface un dossier et tout son contenu
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2006/04/21
	 * @param string $dir Chemin complet
	 * @return boolean True si suppression bien passée, false sinon
	 */

	function deleteDir($dir)
	{
  	if (substr($dir, strlen($dir)-1, 1) != '/')
	  	$dir .= '/';

   //echo $dir;

   if ($handle = opendir($dir))
   {
       while ($obj = readdir($handle))
       {
           if ($obj != '.' && $obj != '..')
           {
               if (is_dir($dir.$obj))
               {
                   if (!MalleService::deleteDir($dir.$obj))
                       return false;
               }
               elseif (is_file($dir.$obj))
               {
                   if (!unlink($dir.$obj))
                       return false;
               }
           }
       }

       closedir($handle);

       if (!@rmdir($dir))
           return false;
       return true;
   }
   return false;
	}
	
	
	/**
	 * Liste des fichiers dans un dossier et ses sous-dossiers
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2006/05/09
	 * @param integer $malle Id de la malle
	 * @param integer $folder Id du dossier de départ
	 * @param integer $files Fichiers trouvés. Tableau passé par référence et complété au fur et à mesure, contenant donc les résultats à exploiter 
	 * @param string $path Chemin (type x/dossier/photos), utilisé pour parcourir les sous-dossiers, uniquement utilisé pour info
	 */
	function getFilesInFolder ($malle, $folder, &$files, $path='') {
		
		$daoFiles = CopixDAOFactory::create("malle|malle_files");
		$daoFolders = CopixDAOFactory::create("malle|malle_folders");
		
		$obj = $daoFolders->get ($folder);
		
		$path = ($path) ? $path.'/'.$obj->nom : $obj->nom;

		// Les fichiers
		$list = $daoFiles->getFilesInFolder ($malle, $folder);
		foreach ($list as $f) {
			//print_r($f);
			$files[] = array (
				'id' => $f->id,
				'malle' => $f->malle,
				'folder' => $f->folder,
				'folder_name' => $obj->nom,
				'folder_path' => $path,
				'nom' => $f->nom,
				'fichier' => $f->fichier,
			);
		}
		
		// Les dossiers "en-dessous"
		$folders = $daoFolders->getFoldersInFolder($malle, $folder);
		foreach ($folders as $rep) {
			MalleService::getFilesInFolder ($malle, $rep->id, $files, $path); 
		}
	}

	/**
	 * Génération d'une clé hexa de 10 caractères aléatoires
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2006/11/23
	 * @return string Clé hexa de 10 caractères aléatoires
	 */
	function createKey () {
		return substr( md5(microtime()), 0, 10 );
	}



}


?>