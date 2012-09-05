<?php
/**
* @package Iconito
* @subpackage	Blog
* @version   $Id: showblogcategory.zone.php,v 1.4 2007-06-01 16:08:43 cbeyer Exp $
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

class ZoneShowBlogCategory extends CopixZone
{
   public function _createContent (&$toReturn)
   {
      //Getting the user.
      //Create Services, and DAO
      $tpl = new CopixTpl ();

            $id_blog = $this->getParam('id_blog', '');
      //capability
      //$tpl->assign ('canManageCategory' , BlogAuth::canMakeInBlog('ADMIN_CATEGORIES',create_blog_object($id_blog)));

      $tpl->assign ('id_blog', $id_blog);
      $tpl->assign ('kind', $this->getParam('kind', ''));

      // Recherche de toutes les catégories de la base
      $blogArticleCategoryDAO = _dao('blog|blogarticlecategory');
            $tabArticleCategory = $blogArticleCategoryDAO->findAllOrder($id_blog);

      $tpl->assign ('tabArticleCategory', $tabArticleCategory);

      // retour de la fonction :
      $toReturn = $tpl->fetch('blog.show.category.tpl');
      return true;
   }
}
