<?php
/**
* @package  Iconito
* @subpackage Comptes
* @version   $Id: navigation.zone.php,v 1.2 2006-04-03 16:05:47 fmossmann Exp $
* @author   Frédéric Mossmann.
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
class ZoneNavigation extends CopixZone {
	function _createContent (&$toReturn) {
		$tpl = & new CopixTpl ();
		
		$tree = Kernel::getTree();
		
		$tpl->assign('tree', $tree );

        switch($this->getParam ('TYPE_')) {
            case 'BU_VILLE':
                $toReturn = $tpl->fetch ('navigation_ville.tpl');
                break;

            case 'BU_ECOLE':
                $toReturn = $tpl->fetch ('navigation_ecole.tpl');
                break;

            case 'BU_CLASSE':
                $toReturn = $tpl->fetch ('navigation_classe.tpl');
                break;

            default:
                $toReturn = $tpl->fetch ('navigation.tpl');
                break;
        }
		
		// $toReturn = '<pre>'.print_r($tree,true).'</pre>';
		return true;
	}
}
?>
