<?php

/**
 * Gestion des préférences du module Minimail
 *
 * @package Iconito
 * @subpackage	Minimail
 */
class ModMinimailPrefs
{
    /**
     * Renvoie les préférences du module
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2006/05/05
     * @param array $data (option) Tableau avec les données (venues de la base)
     * @return array Tableau de tableaux avec toutes les préférences
     */
    public function getPrefs ( $data=null )
    {
        $toReturn = array();

        $toReturn['name'] = 'Minimail';
        $toReturn['form'] = array(
            array(
                'type'=>'titre',
                'text'=>CopixI18N::get ('minimail|minimail.config.alerte.title'), // Alerte par email
                'expl'=>CopixI18N::get ('minimail|minimail.config.alerte.expl'), // 'Vous pouvez être alerté par un email à chaque fois que vous recevez un minimail',
            ),
            array(
                'code'=>'alerte_minimail',
                'type'=>'checkbox',
                'text'=>CopixI18N::get ('minimail|minimail.config.alerte.active'),
                'value'=>($data['alerte_minimail']?true:false)
            ),
        );
        return( $toReturn );
    }

    /**
     * Vérifie que les valeurs saisies pour les préférences sont valides
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2006/05/05
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
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2006/05/05
     * @param string $module Nom du module
     * @param array $data Valeurs
     */
    public function setPrefs( $module, $data )
    {
        if( !isset($data['alerte_minimail']) ) $data['alerte_minimail']=0;
        $pref_service = & CopixClassesFactory::Create ('prefs|prefs');
        $pref_service->setPrefs( $module, $data );
    }

}

