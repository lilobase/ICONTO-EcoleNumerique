<?php
/**
 * Admin - ActionGroup
 *
 * @package	Iconito
 * @subpackage  Admin
 * @version     $Id: admin.actiongroup.php,v 1.2 2007-03-20 10:53:13 cbeyer Exp $
 * @author      Christophe Beyer <cbeyer@cap-tic.fr>
 * @copyright   2006 CAP-TIC
 * @link        http://www.cap-tic.fr
 */

_classInclude('admin2|admin');

class ActionGroupAdmin extends CopixActionGroup {

   function home () {
    
    if (!Admin::canAdmin())
		  return CopixActionGroup::process ('genericTools|Messages::getError', array ('message'=>CopixI18N::get ('kernel|kernel.error.noRights'), 'back'=>CopixUrl::get ()));
    
		$tplHome = & new CopixTpl();

		$tpl = & new CopixTpl ();
		$tpl->assign ('TITLE_PAGE', CopixI18N::get ('admin2|admin.moduleDescription'));
		$tpl->assign ('MENU', Admin::getMenu());
		
		$tpl->assign ('MAIN', $tplHome->fetch('admin2|home.tpl'));
		
		return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
	}
	
	function processPhpinfo (){
		$ppo = new CopixPPO ();
		$ppo->TITLE_PAGE = 'PHPInfo';
		$ppo->MENU = Admin::getMenu();
		$ppo->CopixVersion = COPIX_VERSION;

		ob_start();                                                                                                       
		phpinfo();                                                                                                        
		$info = ob_get_contents();                                                                                        
		ob_end_clean();                                                                                                   
		$ppo->phpinfo = preg_replace('%^.*<body>(.*)</body>.*$%ms', '$1', $info);

		return _arPpo ($ppo, 'phpinfo.tpl');
	}
	
	
}
?>
