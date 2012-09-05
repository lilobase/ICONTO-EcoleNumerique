<?php

/**
 * Surcharge de la DAO malle_malles
 *
 * @package Iconito
 * @subpackage	Malle
 */
class DAOMalle_Malles
{
    /**
     * Renvoie des stats sur les fichiers d'une malle : nb de fichiers (nbFiles), taille occupée (taille)
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2005/12/07
     * @param integer $malle Id de la malle
     * @return mixed Objet DAO.
     */
    public function getNbsFilesInMalle ($malle)
    {
        $critere = 'SELECT COUNT(id) AS nbFiles, SUM(taille) AS taille FROM module_malle_files FIL WHERE FIL.malle='.$malle.'';
        return _doQuery($critere);
    }


    /**
     * Renvoie des stats sur les dossiers d'une malle : nb de dossiers (nbFolders)
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2005/12/07
     * @param integer $malle Id de la malle
     * @return mixed Objet DAO.
     */
    public function getNbsFoldersInMalle ($malle)
    {
        $critere = 'SELECT COUNT(id) AS nbFolders FROM module_malle_folders FOL WHERE FOL.malle='.$malle.'';
        return _doQuery($critere);
    }


}




