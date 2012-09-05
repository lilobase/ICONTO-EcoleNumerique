<?php

/**
 * Surcharge de la DAO malle_files
 *
 * @package Iconito
 * @subpackage	Malle
 */
class DAOMalle_Files
{
    /**
     * Renvoie le nb de fichiers et la taille qu'ils occupent au sein d'un dossier d'une malle
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2005/12/07
     * @param integer $malle Id de la malle
     * @param integer $folder Id du dossier (0 si racine)
     * @return mixed Objet DAO
     */
    public function getNbFilesInFolder ($malle, $folder)
    {
        $critere = 'SELECT COUNT(id) AS nb, SUM(taille) AS taille, MAX(date_upload) AS date_maj FROM module_malle_files FIL WHERE FIL.malle='.$malle.' AND FIL.folder='.$folder.'';
        return _doQuery($critere);
    }

    /**
     * Renvoie le nb de sous-répertoires contenus dans un répertoire
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2005/12/07
     * @param integer $malle Id de la malle
     * @param integer $folder Id du dossier (0 si racine)
     * @return mixed Objet DAO
     */
    public function getNbFoldersInFolder ($malle, $folder)
    {
        $critere = 'SELECT COUNT(id) AS nb FROM module_malle_folders FOL WHERE FOL.malle='.$malle.' AND FOL.parent='.$folder.'';
        return _doQuery($critere);
    }

    /**
  * Determine si un fichier fait bien partie de la malle indiquée
  *
  * @author Jérémy FOURNAISE
  *
  * @param integer $fileId  Identifiant du fichier
  * @param integer $malle   Identifiant de la malle
  *
  * @return boolean True si le fichier appartient à la malle, false sinon
  */
  public function isFileOfMalle ($fileId, $malleId)
  {
    if ($file = $this->get($fileId)) {

      if ($file->malle == $malleId) {

        return true;
      }
    }

    return false;
  }
}

class DAORecordMalle_Files
{
  public function __toString ()
  {
        return $this->nom;
    }

  /**
  * Determine si un fichier est un raccourci internet. Se base sur le nom du fichier en verifiant s'il a l'extention .web
  *
  * @author Christophe Beyer <cbeyer@cap-tic.fr>
  * @since 2010/09/15
  * @return boolean True si c'est un lien, false sinon
  */
  public function isLink ()
  {
    $oRes = false;
    if (substr($this->fichier,-4) == '.web') {
      $oRes = true;
    }
    return $oRes;
  }

  public function getDownloadUrl ()
  {
    $url = CopixURL::get ('malle|default|doDownloadFile', array('id' => $this->malle, 'file' => $this->id));

    return $url;
  }
}

