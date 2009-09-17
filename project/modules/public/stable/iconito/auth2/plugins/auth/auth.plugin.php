<?php
/**
* @package	copix
* @subpackage auth
* @version	$Id: auth.plugin.php,v 1.3 2006-03-01 10:36:15 cbeyer Exp $
* @author	Croes Gérald, Bertrand Yan see copix.aston.fr for other contributors.
* @copyright 2001-2005 CopixTeam
* @link      http://copix.aston.fr
* @link      http://copix.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
class PluginAuth extends CopixPlugin {
    /**
	 * Includes the user class definition
	 * @access public
	 */
    function beforeSessionStart (){
        $sel = CopixClassesFactory::fileInclude($this->config->class_name);
    }

    /**
	 * IPCheck if needed, creating the user object if not yet.
	 * @param	CopixAction	$action	le descripteur de page détécté.
	 * @access public
	 */
    function beforeProcess (&$execParam){
        //Do we check the ip ?
        if (CopixConfig::get ('auth|secureSessionWithIp')){
            if (! isset ($_SESSION[$this->config->secure_with_ip_name])){
                $_SESSION[$this->config->secure_with_ip_name] = $this->_getIpForSecure ();
            }else{
                if (($_SESSION[$this->config->secure_with_ip_name] != $this->_getIpForSecure ())){
                    session_destroy ();
                    $execParam = $this->config->sessionCrackRedirect;
                    return false;
                }
            }
        }

        //Creating the user's object if needed
        if (! isset ($_SESSION[$this->config->session_name])){
            $_SESSION[$this->config->session_name] = & CopixClassesFactory::create($this->config->class_name);
        }
    }

    /**
    * Getting IP adress of the user
    * @return string
    * @access private
    */
    function _getIpForSecure (){
        //this method is heavily based on the article found on
        // phpbuilder.com, and from the comments on the official phpdoc.
        if (isset ($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR']){
            $IP_ADDR = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }else if (isset ($_SERVER['HTTP_CLIENT_IP']) && $_SERVER['HTTP_CLIENT_IP']){
            $IP_ADDR =  $_SERVER['HTTP_CLIENT_IP'];
        }else{
            $IP_ADDR = $_SERVER['REMOTE_ADDR'];
        }

        // get server ip and resolved it
        $FIRE_IP_ADDR = $_SERVER['REMOTE_ADDR'];
        $ip_resolved = gethostbyaddr($FIRE_IP_ADDR);
        // builds server ip infos string
        $FIRE_IP_LITT = ($FIRE_IP_ADDR != $ip_resolved && $ip_resolved) ? $FIRE_IP_ADDR." - ". $ip_resolved : $FIRE_IP_ADDR;
        // builds client ip full infos string
        $toReturn = ($IP_ADDR != $FIRE_IP_ADDR) ? "$IP_ADDR | $FIRE_IP_LITT" : $FIRE_IP_LITT;
        return $toReturn;//$toReturn;
    }

    /**
    * Récupération de l'objet utilisateur.
    */
    function & getUser (){
      if (!isset ($_SESSION[$this->config->session_name])){
         $_SESSION[$this->config->session_name] = & CopixClassesFactory::create($this->config->class_name);
      }
      return $_SESSION[$this->config->session_name];
    }
}
?>
