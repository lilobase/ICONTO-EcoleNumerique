<?php

_ioDAO('cahierdetextes|cahierdetextesmemo');

/**
 * Classe de services sur les mémos
 */
class CahierDeTextesMemoServices
{
    /**
     * Retourne la liste
     *
     * @param DAORecordCahierDeTextesMemo $memo
     */
    public static function getFichiersForList(DAORecordCahierDeTextesMemo $memo)
    {
        $fichiers = array();

        // Récupération des fichiers liés au mémo
        $fichierMalleDAO    = _ioDAO('malle|malle_files');
        $fichierClasseurDAO = _ioDAO('classeur|classeurfichier');

        $memo2fichiersDAO = _ioDAO ('cahierdetextes|cahierdetextesmemo2files');
        $memo2fichiers    = $memo2fichiersDAO->retrieveByMemo ($memo->id);

        foreach($memo2fichiers as $memo2fichier) {
            if ($memo2fichier->module_file == 'MOD_MALLE') {
                if ($fichier = $fichierMalleDAO->get($memo2fichier->file_id)) {
                    $fichiers[] = array('type' => $memo2fichier->module_file, 'id' => $memo2fichier->file_id, 'nom' => $fichier->nom);
                }
            }
            elseif ($memo2fichier->module_file == 'MOD_CLASSEUR') {
                if ($fichier = $fichierClasseurDAO->get ($memo2fichier->file_id)) {
                    $fichiers[] = array('type' => $memo2fichier->module_file, 'id' => $memo2fichier->file_id, 'nom' => $fichier);
                }
            }
        }

        return $fichiers;
    }
}
