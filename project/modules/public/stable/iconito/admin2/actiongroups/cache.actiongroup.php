<?php
/**
 * Admin - ActionGroup
 *
 * @package	Iconito
 * @subpackage  Admin
 * @version     $Id: cache.actiongroup.php,v 1.3 2007-03-20 10:53:13 cbeyer Exp $
 * @author      Christophe Beyer <cbeyer@cap-tic.fr>
 * @copyright   2006 CAP-TIC
 * @link        http://www.cap-tic.fr
 */

_classInclude('admin2|cacheservices');
_classInclude('admin2|admin');

class ActionGroupCache extends CopixActionGroup {


	/**
	 * Renvoie les infos sur le cache
	 * 
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2007/03/19
	 */
	function info () {

		if (!Admin::canAdmin())
			return CopixActionGroup::process ('genericTools|Messages::getError', array ('message'=>CopixI18N::get ('kernel|kernel.error.noRights'), 'back'=>CopixUrl::get ()));
		
		$tpl = & new CopixTpl ();
		$tpl->assign ('TITLE_PAGE', CopixI18N::get ('admin2|admin.menu.cache'));
		$tpl->assign ('MENU', Admin::getMenu());
		
		$tplCache = & new CopixTpl();
		$tplCache->assign ('info', CopixZone::process('admin2|cacheStatus'));
		
		$tpl->assign ('MAIN', $tplCache->fetch('admin2|cache.info.tpl'));
		
		return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
		
	}

	/**
	 * Efface le cache de Copix (dossiers et BDD)
	 * 
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2006/12/05
	 */
	function clear () {

		if (!Admin::canAdmin())
			return CopixActionGroup::process ('genericTools|Messages::getError', array ('message'=>CopixI18N::get ('kernel|kernel.error.noRights'), 'back'=>CopixUrl::get ()));

		//CacheServices::clearCache ();
		CacheServices::clearConfDB ();
		return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('admin2||'));
	}

}
?>
