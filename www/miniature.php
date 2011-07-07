<?php

  /**
  * Script de récupération d'une miniature 
  * avec génération via GD si la miniature n'existe pas
  *
  * @author     Jérémy FOURNAISE
  */
  
  // La génération de miniatures nécessite que GD soit installé
  if (!extension_loaded('gd'))
  {
    die ('GD not enabled. Check your php.ini file.');
  }
  
  // Types de fichier gérés et liste des loaders GD
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
  
  // Path de l'image demandée
  $filepath         = str_replace ("//", "/", $_SERVER['DOCUMENT_ROOT'].'/static/'.$_GET['url']);
  
  // Paramètres de l'image
  $imageName        = substr(strrchr($filepath, '/'), 1);
  $extension        = strrchr($imageName, '.');
  $sizeAndExtension = strrchr($filepath, '_');
  if ($sizeAndExtension) {
    
    $size           = substr($sizeAndExtension, 0, strpos($sizeAndExtension, '.'));
    $originalPath   = substr($filepath, 0, strpos($filepath, $size)).$extension;
  }
  else {
    
    $originalPath   = $filepath;
  }
  $originalName     = substr(strrchr($originalPath, '/'), 1);
  
  // Récupération des informations de l'image d'origine (taille / mime-type)
  if (!file_exists($originalPath)) {

    die (sprintf('Could not load image %s', $originalPath));
  }
  else {
    
    $imgData = getimagesize($originalPath);
  }
  
  // La taille demandée est t-elle correcte ?
  $minWidth = $size ? strlen(substr($size, 2)): 0;
  if ($minWidth && $minWidth > 1 && $minWidth < 4) {
    
    $minWidth = substr($size, 2);
  }
  // Taille incorrecte, on retourne l'image source
  else {
    
    header('Content-type: '.$imgData['mime']);
    readfile($originalPath);
  }
  
  if (in_array($imgData['mime'], $imgTypes)) {
    
    $loader = $imgLoaders[$imgData['mime']];
    if(!function_exists($loader)) {
      
      die (sprintf('Function %s not available. Please enable the GD extension.', $loader));
    }
    
    $source       = $loader($originalPath);
    $sourceWidth  = $imgData[0];
    $sourceHeight = $imgData[1];
    $sourceMime   = $imgData['mime'];
    $minHeight    = $sourceHeight * $minWidth / $sourceWidth;
    $thumbnail    = imagecreatetruecolor($minWidth, $minHeight);
    if ($imgData[0] == $minWidth && $imgData[1] == $minHeight) {
      
      header('Content-type: '.$imgData['mime']);
      readfile($originalPath);
    }
    else {
      imagecopyresampled($thumbnail, $source, 0, 0, 0, 0, $minWidth, $minHeight, $imgData[0], $imgData[1]);
      $creator = $imgCreators[$imgData['mime']];
      $creator($thumbnail, $filepath);
      
      if (file_exists($filepath)) {
        
        header('Content-type: '.$imgData['mime']);
        readfile($filepath);
      }
      else {
        
        die ('Could not create thumbnail');
      }
    }
  }
  else {
    
    die (sprintf('Image MIME type %s not supported', $imgData['mime']));
  }
?>