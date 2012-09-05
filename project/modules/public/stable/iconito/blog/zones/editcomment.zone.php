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

_classInclude('blog|blogauth');

class ZoneEditComment extends CopixZone
{
    public function _createContent (&$toReturn)
    {
        //Getting the user.
        //Create Services, and DAO
        $tpl = new CopixTpl ();

        $tpl->assign ('comment', $this->getParam('comment',null));
        $tpl->assign ('id_bact', $this->getParam('id_bact',''));
        $tpl->assign ('id_blog', $this->getParam('id_blog',''));
        $tpl->assign ('errors', $this->getParam('errors',null));
        $tpl->assign ('showErrors', $this->getParam('showErrors',false));
        $tpl->assign ('id_bacc', $this->getParam('id_bacc',false));

        // retour de la fonction :
        $toReturn = $tpl->fetch('comment.edit.tpl');
        return true;
    }
}
