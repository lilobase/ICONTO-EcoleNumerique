<?php
/**
 * Demo - Outils
 *
 * @package	Iconito
 * @subpackage	Sysutils
 * @version   $Id: demo_tools.class.php,v 1.7 2006-12-05 16:37:13 cbeyer Exp $
 * @author	Christophe Beyer <cbeyer@cap-tic.fr>
 */


class Demo_Tools
{
    /**
     * Met en place un dossier contenant des fichiers du jeu d'essai
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2006/10/26
   * @param string $name Nom du dossier
     */
    public function installFolder ($name, $demo=true)
    {
    $src = '../instal/demo/'.$name; // Source
    $dst = '../'.$name; // Destination
    if (!$demo) { // Installation de depart, depuis /www/install
      $src = '../../instal/'.$name; // Source
      $dst = '../../'.$name; // Destination
    }
    if (is_dir($src)) {
      Demo_Tools::dircopy ($src, $dst);
      $oRes = true;
    } else {
      $oRes = false;
    }
    return $oRes;
    }


  // A function to copy files from one directory to another one, including subdirectories and
  // nonexisting or newer files. Function returns number of files copied.
  // This function is PHP implementation of Windows xcopy  A:\dir1\* B:\dir2 /D /E /F /H /R /Y
  // Syntaxis: [$number =] dircopy($sourcedirectory, $destinationdirectory [, $verbose]);
  // Example: $num = dircopy('A:\dir1', 'B:\dir2', 1);
  // http://fr.php.net/manual/fr/function.copy.php

  public function dircopy($srcdir, $dstdir, $verbose = false)
  {
    $num = 0;
    if(!is_dir($dstdir)) { mkdir($dstdir); chmod($dstdir, 0777); }
    if($curdir = opendir($srcdir)) {
     while($file = readdir($curdir)) {
       if($file != '.' && $file != '..') {
         $srcfile = $srcdir . '/' . $file;
         $dstfile = $dstdir . '/' . $file;
         if(is_file($srcfile)) {
           /*if(is_file($dstfile)) $ow = filemtime($srcfile) - filemtime($dstfile); else $ow = 1;*/
           /*if($ow > 0) {*/
             if($verbose) echo "Copying '$srcfile' to '$dstfile'...";
             if(copy($srcfile, $dstfile)) {
               touch($dstfile, filemtime($srcfile)); $num++;
               if($verbose) echo "OK\n";
             } else echo "Error: File '$srcfile' could not be copied!\n";
           /*}*/
         } else if(is_dir($srcfile) && $file != 'CVS' && $file != '.svn') {
           $num += Demo_Tools::dircopy($srcfile, $dstfile, $verbose);
         }
       }
     }
     closedir($curdir);
    }
    return $num;
  }

  public function dirmove($srcdir, $dstdir, $verbose = false)
  {
    $num = 0;
    if(!is_dir($dstdir)) { mkdir($dstdir); chmod($dstdir, 0777); }
    if($curdir = opendir($srcdir)) {
     while($file = readdir($curdir)) {
       if($file != '.' && $file != '..') {
         $srcfile = $srcdir . '/' . $file;
         $dstfile = $dstdir . '/' . $file;
         if(is_file($srcfile)) {
           /*if(is_file($dstfile)) $ow = filemtime($srcfile) - filemtime($dstfile); else $ow = 1;*/
           /*if($ow > 0) {*/
             if($verbose) echo "Moving '$srcfile' to '$dstfile'...";
             if(rename($srcfile, $dstfile)) {
               /*touch($dstfile, filemtime($srcfile));*/ $num++;
               if($verbose) echo "OK\n";
             } else echo "Error: File '$srcfile' could not be moved!\n";
           /*}*/
         } else if(is_dir($srcfile) && $file != 'CVS' && $file != '.svn') {
           $num += Demo_Tools::dirmove($srcfile, $dstfile, $verbose);
         }
       }
     }
     closedir($curdir);
    }
    @rmdir($srcdir);
    return $num;
  }

  // Suppression d'un dossier et de ses sous-dossiers
  // $dir = dossier à supprimer, sans / à la fin
  public function dirdelete ($dir)
  {
    if ($handle = opendir("$dir")) {
     while (false !== ($item = readdir($handle))) {
       if ($item != "." && $item != "..") {
         if (is_dir("$dir/$item")) {
           Demo_Tools::dirdelete ("$dir/$item");
         } else {
           unlink("$dir/$item");
           //echo " removing $dir/$item<br>\n";
         }
       }
     }
     closedir($handle);
     @rmdir($dir);
     //echo "removing $dir<br>\n";
    }
  }

  // Vidage d'un dossier et de ses sous-dossiers : tous les fichiers situés sous ce dossier et en-dessous sont supprimés. On ne parcourt pas les dossiers CVS, ni SVN, et les fichiers .dummy_file et .cvsignore ne sont pas supprimés
  // $dir = dossier à vider, sans / à la fin
  // $options['delete'] = permet de supprimer les sous-dossiers mais pas le dossier de depart
  public function dirempty ($dir, $options=array())
  {
    $niveau = isset($options['niveau']) ? $options['niveau'] : 0;
    if ($handle = opendir("$dir")) {
     while (false !== ($item = readdir($handle))) {
       if ($item != "." && $item != "..") {
         if (is_dir("$dir/$item")) {
           if ($niveau>0 && isset($options['delete']) && $options['delete'])
             Demo_Tools::dirdelete ("$dir/$item");
           elseif ($item != "CVS" && $item != ".svn")
             Demo_Tools::dirempty ("$dir/$item", array('niveau'=>$niveau+1));
         } elseif (is_file("$dir/$item")) {
           if ($item != ".dummy_file" && $item != ".cvsignore") {
             unlink("$dir/$item");
             //echo " emptying $dir/$item<br/>\n";
           }
         }
       }
     }
     closedir($handle);
    }
  }


  // Retourne la taille d'un répertoire
  public function dirSize($path, $recursive=true)
  {
    $result = 0;
    if(!is_dir($path) || !is_readable($path))
      return 0;
    $fd = dir($path);
    while($file = $fd->read()) {
      if(($file != ".") && ($file != "..")) {
        if(is_dir($path.'/'.$file))
          $result += $recursive ? Demo_Tools::dirSize($path.'/'.$file) : 0;
        else
          $result += filesize($path.'/'.$file);
      }
    }
    $fd->close();
    return $result;
  }

}

