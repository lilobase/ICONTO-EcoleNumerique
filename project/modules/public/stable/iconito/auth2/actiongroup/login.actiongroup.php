<?php
/**
* @package	copix
* @subpackage auth
* @version	$Id: login.actiongroup.php,v 1.12 2007-09-07 14:14:04 cbeyer Exp $
* @author	Croes Gérald, Bertrand Yan see copix.aston.fr for other contributors.
* @copyright 2001-2005 CopixTeam
* @link      http://copix.aston.fr
* @link      http://copix.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

require_once (COPIX_MODULE_PATH.'logs/'.COPIX_CLASSES_DIR.'logs.class.php');

class ActionGroupLogin extends CopixActionGroup {
    /**
    * Try to log in
    * @param string $login the login
    * @param string $password the password
    * @param string $key (option) Clé, en cas de SSO
    * @param string $node_type (option) Type du noeud à atteindre après connexion
    * @param integer $node_id (option) Id du noeud à atteindre après connexion
    * @param string $module_type (option) Type du module du noeud à atteindre (MOB_BLOG,...)

    * @return Object CopixActionReturn
    */
    function doLogin (){
        $plugAuth = & CopixCoordination::getPlugin ('auth|auth');
        
        $_SESSION['user']->_isIdentified = 0;
        
				$node_type = $this->getRequest('node_type');
				$node_id = $this->getRequest('node_id');
				$module_type = $this->getRequest('module_type');
				$auth_url_return = $this->getRequest('auth_url_return');
				$key = $this->getRequest('key');
				$login = $this->getRequest('login');
				$password = $this->getRequest('password');
				
        // On regarde si on veut se rendre sur un noeud ou un module particulier
        if ($node_type && $node_id) {

          $url_return = CopixUrl::get ('kernel||doSelectHome', array('type'=>$node_type, 'id'=>$node_id));

          if ($module_type) { // Un module du noeud
            $nodes_all = Kernel::getModEnabled($node_type, $node_id);
            //print_r($nodes_all);
            $the_node = Kernel::filterModuleList ($nodes_all, $module_type);
            //print_r($the_node);
            if ($node = $the_node[0]) {
              $tmp = explode ("_", $node->module_type);
              $module = strtolower($tmp[1]);
              $url_return = CopixUrl::get ($module.'||go', array('id'=>$node->module_id));
            }
          }
          $auth_url_return = $url_return;
        }
//die ($url_return);

        $user     = & $plugAuth->getUser();
        if ($key)
          $user->loginSso ($login, $key);
        else
          $user->login ($login, $password);

        if ((intval (CopixConfig::get ('auth|enableAfterLoginOverride')) == 1) && isset($auth_url_return) && !empty ($auth_url_return)){
            $url_return = $auth_url_return;
        }else{
            $url_return = CopixConfig::get('auth|afterLogin');
        }

        //check if the url return is correct.
        if ((strpos ($url_return, 'http://') === false)){
            $url_return = CopixUrl::get ().$url_return;
        }

        if (!$user->isConnected ()){
			Logs::set( array('type'=>'LOG', 'message'=>'Login failed: '.$login.'/'.$password) );
            sleep (intval(CopixConfig::get ('auth|intervalBetweenFailedLogin')));
            return CopixActionGroup::process ('auth|Login::getLoginForm', array ('login'=>$login, 'failed'=>1));
        } else {
					$_SESSION['user']->annee_scol = 2007;
				}
			Logs::set( array('type'=>'LOG', 'message'=>'Login ok: '.$login) );
			return new CopixActionReturn (COPIX_AR_REDIRECT, $url_return);
    }

    /**
    * Logs out
    * @return Object CopixActionReturn
    */
    function doLogout (){
        $plugAuth = & CopixCoordination::getPlugin ('auth|auth');
        $user     = & $plugAuth->getUser();
        $user->logout ();
				
				$auth_url_return = $this->getRequest('auth_url_return');
				
        if ((intval (CopixConfig::get ('auth|enableAfterLogoutOverride')) == 1) && isset($auth_url_return) && !empty ($auth_url_return)){
            $url_return = $auth_url_return;
        }else{
            $url_return = CopixConfig::get('auth|afterLogout');
        }
        //check if the url return is correct.
        if ((strpos ($url_return, 'http://') === false)){
            $url_return = CopixUrl::get ().$url_return;
        }
				
				Logs::set( array('type'=>'LOG', 'message'=>'Logout: '.$_SESSION["user"]->bu["login"]) );
		
        return new CopixActionReturn (COPIX_AR_REDIRECT, $url_return);
    }

    /**
    * Shows the login form
    * @return Object CopixActionReturn
    */
    function getLoginForm() {
        $tpl = & new CopixTpl ();
        $login = $this->getRequest('login');
        $failed = $this->getRequest('failed');
        $tpl->assign ('TITLE_PAGE', CopixI18N::get ('auth.titlePage.login'));
        $tpl->assignZone ('MAIN', 'auth|loginForm', array ('login'=>$login, 'failed'=>($failed)));

        return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
    }
}
?>