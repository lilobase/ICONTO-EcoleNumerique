<?php
/**
* @package Iconito
* @subpackage	Blog
* @version   $Id: editlink.zone.php,v 1.4 2007-06-01 16:08:43 cbeyer Exp $
* @author	Vallat Cédric.
* @copyright 2001-2005 CopixTeam
* @link      http://copix.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

/**
* Administration pannel
* @param id_head // the current copixheading indice can be null if racine
*/
class ZoneEditLink extends CopixZone
{
    public function _createContent (&$toReturn)
    {
        //Getting the user.
        //Create Services, and DAO
        $tpl = new CopixTpl ();

        $tpl->assign ('link', $this->getParam('link',null));
        $tpl->assign ('id_blog', $this->getParam('id_blog', ''));
        $tpl->assign ('id_blnk', $this->getParam('id_blnk', ''));
        $tpl->assign ('errors', $this->getParam('errors', ''));
        $tpl->assign ('showErrors', $this->getParam('showErrors', ''));
        $tpl->assign ('kind', $this->getParam('kind', ''));

        // retour de la fonction :
        $toReturn = $tpl->fetch('link.edit.tpl');
        return true;
    }
}
