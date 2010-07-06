<?php
class ModMailExtPrefs {

	//return mailExt prefs
	function getPrefs($iDatas=null){
		$oReturn = array();

		$oReturn['name'] = 'MailExt';
		$oReturn['form'] = array(
			array(
				'type'=>'titre',
				'text'=>CopixI18N::get ('minimail|minimail.config.alerte.title'), // Alerte par email
				'expl'=>CopixI18N::get ('minimail|minimail.config.alerte.expl'), // 'Vous pouvez �tre alert� par un email � chaque fois que vous recevez un minimail',
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
	 * V�rifie que les valeurs saisies pour les pr�f�rences sont valides
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2006/05/05
	 * @param string $module Nom du module
	 * @param array $data Valeurs
	 * @return array Tableau d'erreurs ou tableau vide si pas d'erreurs
	 */
	function checkPrefs( $module, $data ) {
		$error = array();
		return( $error );
	}

	/**
	 * Enregistre les valeurs des pr�f�rences
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2006/05/05
	 * @param string $module Nom du module
	 * @param array $data Valeurs
	 */
	function setPrefs( $module, $data ) {
		if( !isset($data['alerte_minimail']) ) $data['alerte_minimail']=0;
		$pref_service = & CopixClassesFactory::Create ('prefs|prefs');
		$pref_service->setPrefs( $module, $data );
	}

}