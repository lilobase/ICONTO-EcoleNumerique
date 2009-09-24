<?php
/**
* @package  Iconito
* @subpackage Comptes
* @version   $Id: animateurs.actiongroup.php,v 1.1 2009-08-31 10:00:17 fmossmann Exp $
* @author   Frédéric Mossmann
* @copyright 2009 CAP-TIC
* @link      http://www.cap-tic.fr
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

/**
 * @author	Frédéric Mossmann
 */
class ActionGroupAnimateurs extends CopixActionGroup {

	public function beforeAction (){
		_currentUser()->assertCredential ('group:[current_user]');

	}

	/**
	 * list
	 * 
	 * Affiche le formulaire de modification d'un utilisateur extérieur
	 * 
	 * @package	Comptes
	 * @author	Frédéric Mossmann <fmossmann@cap-tic.fr>
	 */
	function getList() {
		if( Kernel::getLevel( 'ROOT', 0 ) < PROFILE_CCV_ADMIN )
			return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('||' ) );
		
		CopixHTMLHeader::addCSSLink (_resource("styles/module_comptes.css"));

		$tpl = & new CopixTpl ();
		$tplGrVilles = & new CopixTpl ();
		
		$userext_dao = & CopixDAOFactory::create("kernel|kernel_ext_user");

		$userext_item = $userext_dao->get( _request('id') );

		$tpl->assign ('TITLE_PAGE', CopixI18N::get ('comptes.moduleDescription')." &raquo; ".CopixI18N::get ('comptes.title.getuserextadd'));
					
		$result = '';
		$tpl->assign ('MAIN', $result );
		
		$menu=array();
		$menu[] = array( 'txt' => CopixI18N::get ('comptes.menu.return_getuserext'), 'url' => CopixUrl::get ('comptes||getUserExt') );
		$tpl->assign ('MENU', $menu );
		
		return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
	}

}

?>
