<?php
/**
 * Regroupements - ActionGroup
 *
 * @package	Iconito
 * @subpackage  Regroupements
 * @version     $Id$
 * @author      Frederic Mossmann <fmossmann@cap-tic.fr>
 * @copyright   2006 CAP-TIC
 * @link        http://www.cap-tic.fr
 */

class ActionGroupDefault extends CopixActionGroup {

	public function beforeAction (){
		_currentUser()->assertCredential ('group:[current_user]');

	}



   function getHomePage () {
   		if( Kernel::getLevel( 'ROOT', 0 ) < PROFILE_CCV_ADMIN )
			return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('||' ) );
			
		$tpl = & new CopixTpl ();
		$tplGrVilles = & new CopixTpl ();
		
		// CopixHTMLHeader::addCSSLink (_resource("styles/module_grvilles.css"));

		// $tpl->assign ('TITLE_PAGE', CopixI18N::get ('grvilles|grvilles.module.titre'));
		
		$dao_grvilles = CopixDAOFactory::create("regroupements|grvilles");
		$grvilles = $dao_grvilles->findAll();
		$tplGrVilles->assign ( 'GRVILLES', count($grvilles) );
				
		$main = $tplGrVilles->fetch ('default.tpl');
		
		$tpl->assign ( 'MAIN', $main );
		
		
		return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
	}
	
}
?>
