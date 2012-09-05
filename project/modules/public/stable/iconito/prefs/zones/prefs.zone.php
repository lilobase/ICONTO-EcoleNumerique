<?php
/**
* @package  Iconito
* @subpackage Prefs
* @version   $Id: prefs.zone.php,v 1.3 2007-12-20 09:46:27 fmossmann Exp $
* @author   Frédéric Mossmann
* @copyright 2005 CAP-TIC
* @link      http://www.cap-tic.fr
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

_classInclude('prefs|prefs');

class ZonePrefs extends CopixZone
{
    public function _createContent (&$toReturn)
    {
        $tpl = new CopixTpl ();

        $tpl->assign ("prefs", $this->getParam('prefs'));

        $msg = $this->getParam('msg');
        if( $msg && $msg=='save' ) {
            $tpl->assign ('msg', array(
                'type'  => 'ok',
                'image_url' => _resource('img/prefs/smiley_black.png'),
                'image_alt' => CopixI18N::get ('prefs.msg.prefsrecorded_alt'),
                'value' => CopixI18N::get ('prefs.msg.prefsrecorded')
            ) );
        }

        $toReturn = $tpl->fetch("getprefs.tpl");
        return true;
    }
}
