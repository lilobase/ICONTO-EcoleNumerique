<?php
/**
* @package	copix
* @version	$Id: blogpage.dao.class.php,v 1.8 2007-09-04 09:59:54 cbeyer Exp $
* @author	Sylvain DACLIN see copix.aston.fr for other contributors.
* @copyright 2001-2005 CopixTeam
* @link		http://copix.org
* @licence  http://www.gnu.org/licenses/lgpl.htmlGNU Leser General Public Licence, see LICENCE file
*/
class DAOBlogPage
{
    /**
    * get blog by name
    * @param  name
    * @return
    */
    public function getPageByUrl ($id_blog, $url_bpge)
    {
      $sp = _daoSp ();
      $sp->addCondition ('url_bpge', '=', $url_bpge);
      $sp->addCondition ('id_blog' , '=', $id_blog);
      $sp->addCondition ('is_online', '=', 1);

      if (count($arPage = $this->findBy ($sp)) > 0)  {
         return $arPage[0];
      }else{
         return false;
      }
    }

    /**
    * Get all article from a blog
    */
    public function getAllPagesFromBlog ($id_blog)
    {
      $sp = _daoSp ();
      $sp->addCondition ('id_blog', '=', $id_blog);
      $sp->addCondition ('is_online', '=', 1);
      $sp->orderBy ('order_bpge');

      return $this->findBy ($sp);
    }

    /**
    * findAllOrder
    * @param
    * @return
    */
    public function findAllOrder ($id_blog)
    {
      $critere = ' SELECT pge.id_bpge as id_bpge, '.
                                           'pge.id_blog as id_blog, '.
                                           'pge.name_bpge as name_bpge, '.
                                           'pge.content_bpge as content_bpge, '.
                                           'pge.author_bpge as author_bpge, '.
                                           'pge.date_bpge as date_bpge, '.
                                           'pge.url_bpge as url_bpge, '.
                                           'pge.is_online as is_online '.
                 ' FROM module_blog_page as pge '.
                 ' WHERE pge.id_blog = '.$id_blog.
                               ' ORDER BY pge.order_bpge ASC';
      return _doQuery($critere);
    }

   /**
    * doUp
    * @param $page
    * @return
    */
   public function doUp ($id_blog, $page)
   {
      if (intVal($page->order_bpge) > 1) {
         // MoveUp previous menu
         $sqlSwap1 = 'UPDATE module_blog_page SET order_bpge='.$page->order_bpge.' WHERE id_blog='.$id_blog.' AND order_bpge='.(intval($page->order_bpge) - 1);
         _doQuery($sqlSwap1);
         // MoveDown this menu
         $sqlSwap2 = 'UPDATE module_blog_page SET order_bpge=order_bpge-1 WHERE id_blog='.$id_blog.' AND id_bpge='.$page->id_bpge;
         _doQuery($sqlSwap2);
      }
   }

   /**
    * @param $id_menu
    * doDown
    * @return
    */
   public function doDown ($id_blog, $page)
   {
      $RS = _doQuery('SELECT MAX(order_bpge) as max FROM module_blog_page WHERE id_blog='.$id_blog);
      if (isset($RS[0]))
         $maxOrder = $RS[0]->max;
      else
         return false;
       if ($page->order_bpge < $maxOrder) {
         // MoveDown next menu
         $sqlSwap1 = 'UPDATE module_blog_page SET order_bpge='.$page->order_bpge.' WHERE id_blog='.$id_blog.' AND order_bpge='.(intval($page->order_bpge) + 1);
         _doQuery($sqlSwap1);
         // MoveUp this menu
         $sqlSwap2 = 'UPDATE module_blog_page SET order_bpge=order_bpge+1 WHERE id_blog='.$id_blog.' AND id_bpge='.$page->id_bpge;
         _doQuery($sqlSwap2);
      }
   }

   /**
    * @param
    * getNewPos
    * @return
    */
   public function getNewPos($id_blog)
   {
      $sql = 'SELECT max(order_bpge)+1 as max FROM module_blog_page WHERE id_blog='.$id_blog;
      $result = _doQuery ($sql);
      if ($result && $result[0]->max > 0) {
         return $result[0]->max;
      }else{
         return 1;
      }
   }

   /**
    * @param
    * delete
    * @return
    */
   public function delete ($item)
   {
       // Delete menu item
       $sqlDelete = 'DELETE FROM module_blog_page WHERE id_bpge=' . $item->id_bpge;
       _doQuery($sqlDelete);

       // Reorder
       $sqlOrdre = 'UPDATE module_blog_page SET order_bpge=order_bpge - 1 WHERE order_bpge > '.$item->order_bpge;
       _doQuery($sqlOrdre);
   }

}

class DAORecordblogpage
{
}
