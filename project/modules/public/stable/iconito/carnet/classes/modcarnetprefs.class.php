<?php

/**
 * Gestion des préférences du module Minimail
 *
 * @package Iconito
 * @subpackage	Carnet
 */
class ModCarnetPrefs
{
    /**
     * Renvoie les préférences du module
     *
     * @author Frederic Mossmann <fmossmann@cap-tic.fr>
     * @since 2006/12/01
     * @param array $data (option) Tableau avec les données (venues de la base)
     * @return array Tableau de tableaux avec toutes les préférences
     */
    public function getPrefs ( $data=null )
    {
        $toReturn = array();


        return( $toReturn );
    }

    /**
     * Vérifie que les valeurs saisies pour les préférences sont valides
     *
     * @author Frederic Mossmann <fmossmann@cap-tic.fr>
     * @since 2006/12/01
     * @param string $module Nom du module
     * @param array $data Valeurs
     * @return array Tableau d'erreurs ou tableau vide si pas d'erreurs
     */
    public function checkPrefs( $module, $data )
    {
        $error = array();
        return( $error );
    }

    /**
     * Enregistre les valeurs des préférences
     *
     * @author Frederic Mossmann <fmossmann@cap-tic.fr>
     * @since 2006/12/01
     * @param string $module Nom du module
     * @param array $data Valeurs
     */
    public function setPrefs( $module, $data )
    {
        if( !isset($data['alerte_carnet']) ) $data['alerte_carnet']=0;
        $pref_service = & CopixClassesFactory::Create ('prefs|prefs');
        $pref_service->setPrefs( $module, $data );
    }

}

