<?php
/**
* @package	copix
* @subpackage auth
* @version	$Id: loginform.zone.php,v 1.7 2006-04-27 13:39:30 fmossmann Exp $
* @author	Croes Gérald, Bertrand Yan see copix.aston.fr for other contributors.
* @copyright 2001-2005 CopixTeam
* @link      http://copix.aston.fr
* @link      http://copix.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
class ZoneLoginForm extends CopixZone {
    function _createContent (& $toReturn){
        $tpl = & new CopixTpl ();
        $plugAuth = CopixPluginRegistry::get ("auth|auth");
        $user      = & $plugAuth->getUser();

        if ($user->isConnected ()){
            $tpl->assign ('user', $user);
        }else{
            $tpl->assign ('user', null);
            $tpl->assign ('login', isset ($this->getParam('login')) ? htmlentities($this->getParam('login')) : null);
        }

	    $tpl->assign ('failed', isset ($this->getParam('failed')) ? $this->getParam('failed') : 0);
        $tpl->assign ('showLostPassword', CopixConfig::get ('auth|enableSendLostPassword'));
        // $tpl->assign ('showRememberMe', ($GLOBALS['COPIX']['COORD']->isRegistered ('auth|reconnect')));

        $toReturn = $tpl->fetch ('login.form.tpl');
        return true;
    }
}
?>