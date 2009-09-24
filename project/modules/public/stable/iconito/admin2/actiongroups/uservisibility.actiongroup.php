<?php
/**
 * Admin - ActionGroup
 *
 * @package	Iconito
 * @subpackage  Admin
 * @version     $Id: uservisibility.actiongroup.php,v 1.3 2007-01-15 14:49:30 fmossmann Exp $
 * @author      Frédéric Mossmann <fmossmann@cap-tic.fr>
 * @copyright   2006 CAP-TIC
 * @link        http://www.cap-tic.fr
 */

_classInclude('admin2|admin');

class ActionGroupUservisibility extends CopixActionGroup {

	/**
	 * Affiche la matrice des droits de visibilité entre utilisateurs
	 * 
	 * @author Frédéric Mossmann <fmossmann@cap-tic.fr>
	 * @since 2006/12/07
	 */
	function view () {
		if (!Admin::canAdmin())
			return CopixActionGroup::process ('genericTools|Messages::getError', array ('message'=>CopixI18N::get ('kernel|kernel.error.noRights'), 'back'=>CopixUrl::get ()));

		$user_types = array(
			'USER_VIL',
			'USER_ENS',
			'USER_ADM',
			'USER_RES',
			'USER_ELE',
			'USER_EXT',
		);
		
		$visibility_matrice = array();
		foreach( $user_types AS $src ) {
			foreach( $user_types AS $dst ) {
				$visibility_matrice[$src][$dst] = Kernel::getUserTypeVisibility( $dst, $src );
			}
		}
		
		
		$tplUserVisibility = & new CopixTpl();

		$tpl = & new CopixTpl ();
		$tpl->assign ('TITLE_PAGE', CopixI18N::get ('admin2|admin.moduleDescription'));

		$tplUserVisibility->assign ('visibility', $visibility_matrice);
		$tplUserVisibility->assign ('user_types', $user_types);

		$tpl->assign ('MAIN', $tplUserVisibility->fetch('admin2|uservisibility.tpl'));
		
		return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
	}
}
?>
