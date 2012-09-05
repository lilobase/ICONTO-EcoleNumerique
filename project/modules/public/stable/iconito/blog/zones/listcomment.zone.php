<?php
/**
* @package Iconito
* @subpackage	Blog
* @version   $Id: listcomment.zone.php,v 1.5 2007-06-01 16:08:43 cbeyer Exp $
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

class ZoneListComment extends CopixZone
{
    public function _createContent (&$toReturn)
    {
        //Getting the user.
        //Create Services, and DAO
        $tpl = new CopixTpl ();

        $id_blog = $this->getParam('id_blog','');

        $tpl->assign ('resultats', $this->getParam('resultats',null));
        $tpl->assign ('id_bact', $this->getParam('id_bact',''));
        $tpl->assign ('id_blog', $id_blog);
        $tpl->assign ('errors', $this->getParam('errors',null));
        $tpl->assign ('showErrors', $this->getParam('showErrors',false));
        $tpl->assign ('toEdit', $this->getParam('toEdit',false));

        //capability
        $tpl->assign ('canManageComment' , BlogAuth::canMakeInBlog('ADMIN_COMMENTS',create_blog_object($id_blog)));

        // retour de la fonction :
        $toReturn = $tpl->fetch('comment.list.tpl');
        return true;
    }
}
