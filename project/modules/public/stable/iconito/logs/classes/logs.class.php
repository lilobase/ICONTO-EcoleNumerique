<?php
/**
 * Logs - Classes
 *
 * Fonctions d'enregistrement et de recherche d'evenements.
 * @package	Iconito
 * @subpackage	Logs
 * @version   $Id: logs.class.php,v 1.3 2006-05-03 08:58:50 fmossmann Exp $
 * @author	Frédéric Mossmann <fmossmann@cap-tic.fr>
 */


class Logs {

	function set( $data=null ) {
		$dao = _dao("logs|logs");
		$nouveau = _daoRecord("logs|logs");
		
		$types = array( 'ERROR', 'DEBUG', 'INFO', 'ALERT', 'LOG', 'AUTO' );
		
		if( isset($data['type']) && in_array( $data['type'], $types ) )
			$nouveau->logs_type = $data['type'];
		else
			$nouveau->logs_type = 'UNKNOWN';
		
		if( isset($data['message']) ) $nouveau->logs_message = $data['message'];
		
		$nouveau->logs_mod_name   = (isset($_GET['module'])?$_GET['module']:'kernel');
		$nouveau->logs_mod_action = (isset($_GET['action'])?$_GET['action']:'default');
		
		$user = Kernel::getSessionBU();
		if( sizeof($user) && isset($user['id']) ) {
			$nouveau->logs_user_id    = $user['id'];
			$nouveau->logs_user_login = $user['login'];
			
		}
		
		// Node
		
		$nouveau->logs_date = date("Y-m-j H:i:s", time());
		$nouveau->logs_user_ip = $_SERVER['REMOTE_ADDR'];
		$nouveau->logs_url = $_SERVER['QUERY_STRING'];
		
		// Kernel::MyDebug( $nouveau );
		
		$dao->insert( $nouveau );
	}

}

?>
