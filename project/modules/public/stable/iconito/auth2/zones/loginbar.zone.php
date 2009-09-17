<?php
/**
* @package	copix
* @subpackage auth
* @version	$Id: loginbar.zone.php,v 1.8 2007-11-08 10:16:15 cbeyer Exp $
* @author	Croes Gérald, Bertrand Yan see copix.aston.fr for other contributors.
* @copyright 2001-2005 CopixTeam
* @link      http://copix.aston.fr
* @link      http://copix.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
class ZoneLoginBar extends CopixZone {
    function _createContent (& $toReturn){
        $tpl = & new CopixTpl ();

        $plugAuth = CopixPluginRegistry::get ("auth|auth");
        $user      = & $plugAuth->getUser();

		if ($user->isConnected ()){
            $tpl->assign ('user', $user);
            			
			if( isset($_SESSION["user"]->_isIdentified) && $_SESSION["user"]->_isIdentified==true && isset($_SESSION["user"]->bu) ) {
				$linkedstr="";
	
				$linkedstr="\n<!-- [".$_SESSION["user"]->bu["login"]." - #"._currentUser ()->getId()." - ".$_SESSION["user"]->bu["type"]."/".$_SESSION["user"]->bu["id"]."] -->\n";
				
				$tpl->assign ('login', ucfirst(strtolower($_SESSION["user"]->bu["prenom"]))." ".ucfirst(strtolower($_SESSION["user"]->bu["nom"]).$linkedstr) );
			} else $tpl->assign ('login', $user->login);
			
        }else{
            $tpl->assign ('user', null);
            $tpl->assign ('login', @$this->getParam('login'));
        }
				
				$tpl->assign ('url', htmlentities(CopixUrl::get().'index.php?'.$_SERVER["QUERY_STRING"]));

        $tpl->assign ('failed', @$this->getParam('failed'));
        $tpl->assign ('showLostPassword', CopixConfig::get ('auth|enableSendLostPassword'));

        $tpl->assign ('showRememberMe', false);

        $toReturn = $tpl->fetch ('login.bar.tpl');
        return true;
    }
}
?>
