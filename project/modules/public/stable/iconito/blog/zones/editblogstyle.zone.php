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

class ZoneEditBlogStyle extends CopixZone
{
    public function _createContent (&$toReturn)
    {
        //Getting the user.
        //Create Services, and DAO
        $tpl = new CopixTpl ();

        $tpl->assign ('blog', $this->getParam('blog',null));
        $tpl->assign ('kind', $this->getParam('kind',null));
        $tpl->assign ('style_blog_file_src', $this->getParam('style_blog_file_src',null));
        $tpl->assign ('id_blog', $this->getParam('id_blog', ''));
        $tpl->assign ('errors', $this->getParam('errors', ''));
        $tpl->assign ('showErrors', $this->getParam('showErrors', ''));


        // retour de la fonction :
        $toReturn = $tpl->fetch('blog.editstyle.tpl');
        return true;
    }
}
