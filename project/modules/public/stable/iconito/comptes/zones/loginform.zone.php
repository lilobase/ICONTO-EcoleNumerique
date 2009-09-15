<?php
/**
* @package  Iconito
* @subpackage Comptes
* @version   $Id: loginform.zone.php,v 1.2 2006-03-03 14:44:07 fmossmann Exp $
* @author   Frédéric Mossmann
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
class ZoneLoginForm extends CopixZone {
	function _createContent (&$toReturn) {
		$tpl = & new CopixTpl ();
		
		$tpl->assign('users', $this->params['users'] );
		$tpl->assign('type',   $this->params['type']   );
		$tpl->assign('id',     $this->params['id']     );
		
		$toReturn = $tpl->fetch ('getLoginForm.tpl');
		
		return true;
	}
}
?>
