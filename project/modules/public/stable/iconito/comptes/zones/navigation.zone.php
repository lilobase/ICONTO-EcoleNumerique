<?php
/**
* @package  Iconito
* @subpackage Comptes
* @version   $Id: navigation.zone.php,v 1.2 2006-04-03 16:05:47 fmossmann Exp $
* @author   Frédéric Mossmann.
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
class ZoneNavigation extends CopixZone
{
    public function _createContent (&$toReturn)
    {
        $tpl = new CopixTpl ();

        $tree = Kernel::getTree();

        $tpl->assign('tree', $tree );

        $toReturn = $tpl->fetch ('navigation.tpl');

        // $toReturn = '<pre>'.print_r($tree,true).'</pre>';
        return true;
    }
}
