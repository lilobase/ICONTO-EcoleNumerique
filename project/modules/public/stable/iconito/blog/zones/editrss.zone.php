<?php
/**
* @package Iconito
* @subpackage	Blog
* @author	Vallat Cédric.
* @copyright 2001-2005 CopixTeam
* @link      http://copix.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

/**
* Administration pannel
* @param id_head // the current copixheading indice can be null if racine
*/
class ZoneEditRss extends CopixZone
{
    public function _createContent (&$toReturn)
    {
        //Getting the user.
        //Create Services, and DAO
        $tpl = new CopixTpl ();

        $tpl->assign ('rss', $this->getParam('fluxRss',null));
        $tpl->assign ('id_blog', $this->getParam('id_blog', ''));
        $tpl->assign ('id_bfrs', $this->getParam('id_bfrs', ''));
        $tpl->assign ('errors', $this->getParam('errors', ''));
        $tpl->assign ('showErrors', $this->getParam('showErrors', ''));
        $tpl->assign ('kind', $this->getParam('kind', ''));

        // retour de la fonction :
        $toReturn = $tpl->fetch('rss.edit.tpl');
        return true;
    }
}
