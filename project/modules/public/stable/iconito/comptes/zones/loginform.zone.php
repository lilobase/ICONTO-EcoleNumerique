<?php
/**
* @package  Iconito
* @subpackage Comptes
* @version   $Id: loginform.zone.php,v 1.2 2006-03-03 14:44:07 fmossmann Exp $
* @author   Frédéric Mossmann
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
class ZoneLoginForm extends CopixZone
{
    public function _createContent (&$toReturn)
    {
        $tpl = new CopixTpl ();

        $tpl->assign('users', $this->getParam('users') );
        $tpl->assign('type',  $this->getParam('type' ) );
        $tpl->assign('id',    $this->getParam('id'   ) );
        $tpl->assign('reset', $this->getParam('reset') );

        $toReturn = $tpl->fetch ('getloginform.tpl');

        return true;
    }
}
