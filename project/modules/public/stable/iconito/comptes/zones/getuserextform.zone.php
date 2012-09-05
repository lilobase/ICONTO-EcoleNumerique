<?php
/**
* @package  Iconito
* @subpackage Comptes
* @version   $Id: getuserextform.zone.php,v 1.1 2007-02-07 17:10:26 fmossmann Exp $
* @author   Frédéric Mossmann
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
class ZoneGetUserExtForm extends CopixZone
{
    public function _createContent (&$toReturn)
    {
        $tpl = new CopixTpl ();

        $tpl->assign('user',   $this->getParam('user') );
        $tpl->assign('mode',   $this->getParam('mode') );
        $tpl->assign('errors', $this->getParam('errors') );

        $toReturn = $tpl->fetch ('zonegetuserextform.tpl');

        return true;
    }
}
