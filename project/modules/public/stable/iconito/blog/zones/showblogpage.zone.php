<?php
/**
* @package Iconito
* @subpackage	Blog
* @version   $Id: showblogpage.zone.php,v 1.4 2007-06-01 16:08:43 cbeyer Exp $
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

class ZoneShowBlogPage extends CopixZone
{
    public function _createContent (&$toReturn)
    {
        //Getting the user.
        //Create Services, and DAO
        $tpl = new CopixTpl ();

        $id_blog = $this->getParam('id_blog', '');
        //capability
        //$tpl->assign ('canManagePage' , BlogAuth::canMakeInBlog('ADMIN_PAGES',create_blog_object($id_blog)));

        $tpl->assign ('id_blog', $id_blog);
        $tpl->assign ('kind', $this->getParam('kind', ''));

        // Recherche de tous les pages de la base
        $blogPageDAO = _dao('blog|blogpage');
        $tabPages = $blogPageDAO->findAllOrder($id_blog);
        $tpl->assign ('tabPages', $tabPages);

        // retour de la fonction :
        $toReturn = $tpl->fetch('blog.show.page.tpl');
        return true;
    }
}
