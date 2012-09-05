<?php
/**
* @package  Iconito
* @subpackage Album
* @version   $Id: albumlist.zone.php,v 1.2 2007-06-15 15:32:43 fmossmann Exp $
* @author   Frédéric Mossmann
* @copyright 2007 CAP-TIC
* @link      http://www.cap-tic.fr
* @link      http://www.iconito.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
class ZoneAlbumList extends CopixZone
{
    public function _createContent (&$toReturn)
    {
        $tpl = new CopixTpl ();

        $albumlist = array();

        $tpl->assign('albumlist', $albumlist );

      // retour de la fonction :
      $toReturn = $tpl->fetch ('albumlist.tpl');
      return true;
   }
}
