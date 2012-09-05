<?php
/**
* @package  Iconito
* @subpackage Comptes
* @version   $Id: userlist.zone.php,v 1.1 2006-03-01 16:39:03 fmossmann Exp $
* @author   Frédéric Mossmann
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
class ZoneUserList extends CopixZone
{
    public function _createContent (&$toReturn)
    {
        $tpl = new CopixTpl ();

        $tpl->assign('childs', $this->getParam('childs') );
        $tpl->assign('type',   $this->getParam('type')   );
        $tpl->assign('id',     $this->getParam('id')     );

        $toReturn = $tpl->fetch ('userlist.tpl');

        return true;
    }
}
