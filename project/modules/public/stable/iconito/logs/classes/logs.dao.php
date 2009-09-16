<?php

/**
 * Surcharge de la DAO Logs
 * 
 * @package Iconito
 * @subpackage Logs
 */
class DAOLogs {

	/**
	 * Renvoie les infos de la dernière connexion d'un login
	 * 
	 * @author Frédéric Mossmann <fmossmann@cap-tic.fr>
	 * @since 2006/05/10
	 * @param string $login Login de l'utilisateur
	 * @return mixed Objet DAO
	 */
	
	function lastLogin ($login) {
	
		$dao = _dao("logs|logs");

		$criteres = _daoSearchConditions();
		$criteres->addCondition('logs_type', '=', 'LOG');
		$criteres->addCondition('logs_mod_name', '=', 'auth');
		$criteres->addCondition('logs_mod_action', '=', 'in');
		$criteres->addCondition('logs_message', '=', 'Login ok: '.$login);
		$criteres->addItemOrder( 'logs_date', 'DESC' );
		$lastlog = $dao->findBy($criteres);
		
		$criteres = _daoSearchConditions();
		$criteres->addCondition('logs_type', '=', 'LOG');
		$criteres->addCondition('logs_mod_name', '=', 'auth');
		$criteres->addCondition('logs_mod_action', '=', 'in');
		$criteres->addCondition('logs_message', ' LIKE ', 'Login failed: '.$login.'/%');
		$criteres->addItemOrder( 'logs_date', 'DESC' );
		$lastfailed = $dao->findBy($criteres);

		$return=false;
		if( sizeof($lastlog)>0 ) {
			$return['nb']   = sizeof($lastlog);
			$return['last'] = $lastlog[0];
			if( sizeof($lastfailed)>0 ) {
				$return['failed'] = $lastfailed[0];
			}
		}
		return( $return );
	}

/*
	<method name="lastLogin" type="select">
		<parameters>
			<parameter name="login" />
		</parameters>
		<conditions>
			<condition property="logs_type"  operator="=" value="LOG" />
			<condition property="logs_mod_name"  operator="=" value="auth" />
			<condition property="logs_mod_action"  operator="=" value="in" />
			<condition property="logs_message"  operator="=" value="Login ok: $login" />
		</conditions>
		<order>
			<orderitem property="logs_date" way="desc" />
		</order>
	</method>
*/

}




?>