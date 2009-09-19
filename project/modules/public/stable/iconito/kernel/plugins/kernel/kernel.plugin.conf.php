<?php
/**
* @package	copix
* @subpackage auth
* @version	$Id: kernel.plugin.conf.php,v 1.1 2005-12-19 15:32:54 fmossmann Exp $
* @author	Croes Gérald,  Bertrand Yan see copix.aston.fr for other contributors.
* @copyright 2001-2005 CopixTeam
* @link      http://copix.aston.fr
* @link      http://copix.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class PluginConfigKernel {
	/**
	* The session variable the user will be stored in
	* @var string
	*/
    var $session_name = 'user';

	/**
	* The CopixUser classname we'll use
	* @var string
	*/
    var $class_name = 'auth|projectuser';

	/**
	* What if the session was hi-jacked ?
	* @var CopixAction
	*/
    var $sessionCrackRedirect;

	/**
	* The session variable the session will be stored in
	* @var string
	*/
	var $secure_with_ip_name = 'PLUGIN_AUTH_SECURE_WITH_IP';

    /**
    * @constructor
    */
	/*
    function PluginConfigAuth (){
	    $this->sessionCrackRedirect = & new CopixAction ('auth|login', 'doLogout');
    }
	*/
}
?>
