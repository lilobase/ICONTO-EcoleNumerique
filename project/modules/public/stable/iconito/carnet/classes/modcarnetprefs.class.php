<?php

/**
 * Gestion des préférences du module Minimail
 * 
 * @package Iconito
 * @subpackage	Carnet
 */
class ModCarnetPrefs {

	/**
	 * Renvoie les préférences du module
	 *
	 * @author Frederic Mossmann <fmossmann@cap-tic.fr>
	 * @since 2006/12/01
	 * @param array $data (option) Tableau avec les données (venues de la base)
	 * @return array Tableau de tableaux avec toutes les préférences
	 */
	function getPrefs ( $data=null ) {
		$toReturn = array();

    /*
		$toReturn['name'] = 'Cahier de liaison';
		$toReturn['form'] = array(
			array(
				'type'=>'titre',
				'text'=>CopixI18N::get ('carnet|carnet.config.alerte.title'), // Alerte par email
				'expl'=>CopixI18N::get ('carnet|carnet.config.alerte.expl'), // 'Vous pouvez être alerté par un email à chaque fois que vous recevez un minimail',
			),
			array(
				'code'=>'alerte_carnet',
				'type'=>'checkbox',
				'text'=>CopixI18N::get ('carnet|carnet.config.alerte.active'),
				'value'=>($data['alerte_carnet']?true:false)
			),
		);
    */
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
	function checkPrefs( $module, $data ) {
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
	function setPrefs( $module, $data ) {
		if( !isset($data['alerte_carnet']) ) $data['alerte_carnet']=0;
		$pref_service = & CopixClassesFactory::Create ('prefs|prefs');
		$pref_service->setPrefs( $module, $data );
	}
		
}

?>
