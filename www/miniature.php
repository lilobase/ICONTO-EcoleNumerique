<?php

  /**
  * Script de récupération d'une miniature
  * avec génération via GD si la miniature n'existe pas
  *
  * @author     Jérémy FOURNAISE
  */

  // La génération de miniatures nécessite que GD soit installé
  if (!extension_loaded('gd')) {
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
  $filepath         = str_replace ("//", "/", realpath("static").'/'.$_GET['url']);

  // Paramètres de l'image
  $imageName        = substr(strrchr($filepath, '/'), 1);
  $extension        = strrchr($imageName, '.');

  $sizeAndExtension = strrchr($imageName, '_');
  $size             = substr($sizeAndExtension, 0, strpos($sizeAndExtension, '.'));

  // Si le nom de l'image contient un underscore, vérification s'il s'agit d'une indication de taille pour la miniature à générer
  if ($sizeAndExtension) {

    // Regex : mode square, size d'au moins 2 chiffres
    if (preg_match("/^_s([0-9]{2,})$/", $size, $regs)) {

      $mode           = "square";
      $size           = $regs[1];
      $originalPath   = substr($filepath, 0, strpos($filepath, $regs[0])).$extension;
    }
    // Regex : mode normal, size d'au moins 2 chiffres
    elseif (preg_match("/^_([0-9]{2,})$/", $size, $regs)) {

      $mode           = "normal";
      $size           = $regs[1];
      $originalPath   = substr($filepath, 0, strpos($filepath, $regs[0])).$extension;
    } else {

      $mode           = "normal";
      $size           = null;
      $originalPath   = $filepath;
    }
  } else {

    $mode           = "normal";
    $size           = null;
    $originalPath   = $filepath;
  }

  $originalName     = substr(strrchr($originalPath, '/'), 1);

  // Récupération des informations de l'image d'origine (taille / mime-type)
  if (!file_exists($originalPath)) {

    if (file_exists($filepath)) {

      $originalPath = $filepath;
    } else {

      die (sprintf('Could not load image %s', $originalPath));
    }
  }

  $imgData = getimagesize($originalPath);

  // Format de l'image demandé ?
  //  - s => square (format carré)
  if ($mode == "square") {

    $width = $size;
    $height = $size;

    // Si la largeur est plus importante que la largeur
    if ($imgData[0] > $imgData[1]) {

      $square_x = round($imgData[0]-$imgData[1])/2;
      $square_y = 0;
          $square_size = $imgData[1];
    } else {

      $square_x = 0;
            $square_y = round($imgData[1]-$imgData[0])/2;
            $square_size = $imgData[0];
    }

    $thumbnail_size = $size;
  } else {

    $square_y = 0;
    $square_x = 0;

    // Si la largeur est plus importante que la largeur
    if ($imgData[0] > $imgData[1]) {

      if (!is_null($size)) {

        $width = $size;
      } else {

        $width = $imgData[0];
      }

      $height = $imgData[1] * $width / $imgData[0];
    } else {

      if (!is_null($size)) {

        $height = $size;
      } else {

        $height = $imgData[1];
      }

      $width = $imgData[0] * $height / $imgData[1];
    }
  }

  if (in_array($imgData['mime'], $imgTypes)) {

    $loader = $imgLoaders[$imgData['mime']];
    if(!function_exists($loader)) {

      die (sprintf('Function %s not available. Please enable the GD extension.', $loader));
    }

    $source       = $loader($originalPath);
    $sourceMime   = $imgData['mime'];
    $thumbnail    = imagecreatetruecolor($width, $height);
    if ($imgData[0] == $width && $imgData[1] == $height) {

      header('Content-type: '.$imgData['mime']);
      readfile($originalPath);
    } else {

      // Génération de la miniature pour le mode square (carré)
      if ($mode == "square") {

        $thumbnail    = imagecreatetruecolor($thumbnail_size, $thumbnail_size);
        imagecopyresampled($thumbnail, $source, 0, 0, $square_x, $square_y, $thumbnail_size, $thumbnail_size, $square_size, $square_size);
      }
      // Génération de la miniature normale (ratio largeur / hauteur conservé)
      else {

        $thumbnail    = imagecreatetruecolor($width, $height);
        imagecopyresampled($thumbnail, $source, 0, 0, $square_x, $square_y, $width, $height, $imgData[0], $imgData[1]);
      }

      $creator = $imgCreators[$imgData['mime']];
      $creator($thumbnail, $filepath);

      if (file_exists($filepath)) {

        header('Content-type: '.$imgData['mime']);
        readfile($filepath);
      } else {

        die ('Could not create thumbnail');
      }
    }
  } else {

    die (sprintf('Image MIME type %s not supported', $imgData['mime']));
  }
