<?php

/**
* @package    Iconito
* @subpackage Classeur
*/

class DAORecordClasseurFichier
{
  protected $imgTypes = array(
    'image/jpeg',
    'image/pjpeg',
    'image/png',
    'image/gif',
  );

  public function __toString ()
  {
        return !is_null($this->titre) ? $this->titre : $this->fichier;
    }

  /**
   * Retourne le path complet du fichier
   * sous la forme : /Classeur/Dossier1/Dossier2/...
   *
   * @param bool  $withStripText  StripText du path
   *
   * @return string
   */
    public function getPath ($withStripText = true)
    {
      $paths = array();

      $classeurDAO = _ioDAO('classeur|classeur');
      $dossierDAO = _ioDAO('classeur|classeurdossier');

      if ($this->dossier_id != 0) {

        $dossier = $dossierDAO->get($this->dossier_id);
        $paths[] = $withStripText ? Kernel::stripText($dossier->nom) : $dossier->nom;

        while ($dossier->parent_id != 0) {

          $dossier = $dossierDAO->get($dossier->parent_id);
          $paths[] = $withStripText ? Kernel::stripText($dossier->nom) : $dossier->nom;
        }
      }

      $classeur = $classeurDAO->get($this->classeur_id);
      $paths[] = $withStripText ? Kernel::stripText($classeur->titre) : $classeur->titre;

      return '/'.implode('/', array_reverse($paths)).'/';
    }

    /**
  * Détermine si un fichier est un raccourci internet.
  * Se base sur le nom du fichier en verifiant s'il a l'extention .web
  *
  * @return bool True s'il s'agit d'un favori / false sinon
  */
  public function estUnFavori ()
  {
    if (substr($this->fichier, -4) == '.web') {

      return true;
    }

    return false;
  }

  /**
  * Retourne l'adresse du favori
  *
  * @return string Adresse du favori
  */
  public function getLienFavori ()
  {
    $toReturn = null;

    if ($this->estUnFavori ()) {

      $toReturn = classeurService::getUrlOfFavorite($this);
    }

    return $toReturn;
  }

  /**
  * Détermine si un fichier est une image.
  * Se base sur le mime type du fichier
  *
  * @return bool True s'il s'agit d'une image / false sinon
  */
  public function estUneImage ()
  {
    $mimeType = CopixMIMETypes::getFromExtension($this->type);
    if (in_array($mimeType, $this->imgTypes)) {

      return true;
    }

    return false;
  }

  /**
   * Retourne une Url pour générer la miniature
   *
   * @param int    $largeur  Largeur
   * @param string $mode     Mode
   *
   * @return string
   */
  public function getLienMiniature($largeur = null, $mode = 's')
  {
    $toReturn = null;

    $classeurDAO = _ioDAO('classeur|classeur');
    $classeur = $classeurDAO->get ($this->classeur_id);

    $nomClasseur = $classeur->id.'-'.$classeur->cle;

    $extension  = strtolower(strrchr($this->fichier, '.'));
    if (!is_null($largeur)) {

      $largeur = '_'.$mode.$largeur;
    }

      $nomFichier = $this->id.'-'.$this->cle.$largeur.$extension;

      $toReturn = CopixUrl::get ().'index.php/static/classeur/'.$classeur->id.'-'.$classeur->cle.'/'.($nomFichier);

    return $toReturn;
  }

  /**
   * Retourne le chemin physique du fichier
   *
   * @return string Path du fichier
   */
  public function getLienPhysique()
  {
    $classeurDAO = _ioDAO('classeur|classeur');
    $classeur = $classeurDAO->get ($this->classeur_id);

    $extension = strrchr($this->fichier, '.');

    return realpath('./static/classeur').'/'.$classeur->id.'-'.$classeur->cle.'/'.$this->id.'-'.$this->cle.$extension;
  }

  /**
  * Retourne le lien de téléchargement du fichier
  *
  * @return string Lien de téléchargement
  */
  public function getDownloadUrl ()
  {
    $url = CopixURL::get ('classeur||telechargerFichier', array('classeurId' => $this->classeur_id, 'fichierId' => $this->id));

    return $url;
  }

  /**
  * Retourne l'extension du fichier
  *
  * @return string Extension du fichier
  */
  public function getExtension ()
  {
    return strtolower(strtoupper(substr(strrchr($this->fichier, '.'), 1)));
  }
}

class DAOClasseurFichier
{
  /**
   * Retourne les fichiers d'un dossier
   *
   * @param int   $idClasseur
   * @param int   $idDossier
   * @param array $tri          Tableau spécifiant la colonne et la direction à utiliser pour le tri
   *
   * @return CopixDAORecordIterator
   */
  public function getParDossier ($idClasseur, $idDossier = null, $tri = array())
  {
    $criteria = _daoSp ();
        $criteria->addCondition ('classeur_id', '=', $idClasseur);
        if (!is_null($idDossier)) {

          $criteria->addCondition ('dossier_id', '=', $idDossier);
        } else {

          $criteria->addCondition ('dossier_id', '=', 0);
        }

        if (!empty($tri)) {

          $criteria->orderBy(array($tri['colonne'], $tri['direction']));
        }

        return $this->findBy ($criteria);
  }

  /**
   * Retourne le nombre de fichiers et la taille qu'ils occupent pour un dossier
   *
   * @param int   $idClasseur   Identifiant du classeur
   * @param int   $idDossier    Identifiant du dossier
   *
   * @return CopixDAORecordIterator
   */
    public function getNombreEtTailleParDossier ($idClasseur, $idDossier)
    {
      $sql = 'SELECT COUNT(id) AS nb_fichiers, SUM(taille) AS taille'
          . ' FROM module_classeur_fichier'
          . ' WHERE module_classeur_id=:idClasseur'
          . ' AND module_classeur_dossier_id=:idDossier';

        return _doQuery($sql, array(':idClasseur' => $idClasseur, ':idDossier' => $idDossier));
    }
}