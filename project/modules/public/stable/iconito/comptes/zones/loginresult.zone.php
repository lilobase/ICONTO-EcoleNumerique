<?php
/**
* @package  Iconito
* @subpackage Comptes
* @version   $Id: loginresult.zone.php,v 1.1 2006-03-06 16:18:31 fmossmann Exp $
* @author   Frédéric Mossmann
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
class ZoneLoginResult extends CopixZone {
	function _createContent (&$toReturn) {
		$tpl = & new CopixTpl ();
		
		$tpl->assign('format', $this->getParam('format') );
		$tpl->assign('type',   $this->getParam('type')   );
		$tpl->assign('id',     $this->getParam('id')     );
		$tpl->assign('logins', $_SESSION['modules']['comptes']['doLoginCreate']['success'] );
		
		switch( $this->getParam('format') ) {
			case 'html':
				$toReturn = $tpl->fetch ('LoginResult-html.tpl');
				break;
			case 'text':
				$toReturn = $tpl->fetch ('LoginResult-text.tpl');
				return new CopixActionReturn (COPIX_AR_DOWNLOAD_CONTENT, $toReturn, 'logins.txt');
				break;
			case 'cvs':
				break;
			case 'xml':
				break;
			case 'pdf':
				break;
			case 'session':
				$toReturn = '<pre>'.print_r( $_SESSION['modules']['comptes']['doLoginCreate']['success'], true ).'</pre>';
				break;
			default:
				break;
		}
		
		return true;
	}
}
?>
