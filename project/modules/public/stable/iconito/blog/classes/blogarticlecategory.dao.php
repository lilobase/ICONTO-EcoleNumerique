<?php
/**
* @package	copix
* @version	$Id: blogarticlecategory.dao.class.php,v 1.7 2007-07-30 14:42:07 cbeyer Exp $
* @author	Sylvain DACLIN see copix.aston.fr for other contributors.
* @licence  http://www.gnu.org/licenses/lgpl.htmlGNU Leser General Public Licence, see LICENCE file
*/
class DAOBlogarticlecategory
{
   /**
    * get blog by name
    * @param  name
    * @return
    */
    public function getCategoryByName ($id_blog, $url_bacg)
    {
      $sp = _daoSp ();
      $sp->addCondition ('id_blog' , '=', $id_blog);
      $sp->addCondition ('url_bacg', '=', $url_bacg);

      if (count($arCat = $this->findBy ($sp)) > 0)  {
         return $arCat[0];
      }else{
         return false;
      }
    }

    /**
    * findAllOrder
    * @param
    * @return
    */
    public function findAllOrder ($id_blog)
    {
      $critere = ' SELECT cat.id_bacg as id_bacg, '.
                                           'art.id_bacg as link_art, '.
                                           'cat.id_blog as id_blog, '.
                                           'cat.order_bacg as order_bacg, '.
                                           'cat.name_bacg as name_bacg, '.
                                           'cat.url_bacg as url_bacg, '.
                                           'COUNT(cat.id_bacg) as nb_articles '.
                 ' FROM module_blog_articlecategory as cat LEFT JOIN module_blog_article_blogarticlecategory as art ON cat.id_bacg = art.id_bacg'.
                 ' WHERE cat.id_blog = '.$id_blog.
                               ' GROUP BY cat.id_bacg'.
                               ' ORDER BY cat.order_bacg ASC';
      return _doQuery($critere);
    }

    /**
    * findAllOrder
    * @param
    * @return
    */
    public function findAllOrderBy ($id_blog, $orderby)
    {
      $critere = ' SELECT id_bacg as id_bacg, '.
                                           'id_blog as id_blog, '.
                                           'order_bacg as order_bacg, '.
                                           'name_bacg as name_bacg, '.
                                           'url_bacg as url_bacg '.
                 ' FROM module_blog_articlecategory'.
                 ' WHERE id_blog = '.$id_blog.
                               ' ORDER BY '.$orderby;
      return _doQuery($critere);
    }

   /**
    * doUp
    * @param $category
    * @return
    */
   public function doUp ($id_blog, $category)
   {
      if (intVal($category->order_bacg) > 1) {
         // MoveUp previous menu
         $sqlSwap1 = 'UPDATE module_blog_articlecategory SET order_bacg='.$category->order_bacg.' WHERE id_blog='.$id_blog.' AND order_bacg='.(intval($category->order_bacg) - 1);
         _doQuery($sqlSwap1);
         // MoveDown this menu
         $sqlSwap2 = 'UPDATE module_blog_articlecategory SET order_bacg=order_bacg-1 WHERE id_blog='.$id_blog.' AND id_bacg='.$category->id_bacg;
         _doQuery($sqlSwap2);
      }
   }

   /**
    * @param $id_menu
    * doDown
    * @return
    */
   public function doDown ($id_blog, $category)
   {
      $RS = _doQuery('SELECT MAX(order_bacg) as max FROM module_blog_articlecategory WHERE id_blog='.$id_blog);
      if (isset($RS[0]))
         $maxOrder = $RS[0]->max;
      else
         return false;
       if ($category->order_bacg < $maxOrder) {
         // MoveDown next menu
         $sqlSwap1 = 'UPDATE module_blog_articlecategory SET order_bacg='.$category->order_bacg.' WHERE id_blog='.$id_blog.' AND order_bacg='.(intval($category->order_bacg) + 1);
         _doQuery($sqlSwap1);
         // MoveUp this menu
         $sqlSwap2 = 'UPDATE module_blog_articlecategory SET order_bacg=order_bacg+1 WHERE id_blog='.$id_blog.' AND id_bacg='.$category->id_bacg;
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
      $sql = 'SELECT max(order_bacg)+1 as max FROM module_blog_articlecategory WHERE id_blog='.$id_blog;
            $result = _doQuery($sql);
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
       $sqlDelete = 'DELETE FROM module_blog_articlecategory WHERE id_bacg=' . $item->id_bacg;
       _doQuery($sqlDelete);

       // Reorder
       $sqlOrdre = 'UPDATE module_blog_articlecategory SET order_bacg=order_bacg - 1 WHERE order_bacg > '.$item->order_bacg;
       _doQuery($sqlOrdre);
   }

        /**
    * Get all categories from a blog
    */
    public function getAllCategoriesFromBlog ($id_blog)
    {
      $sp = _daoSp ();
      $sp->addCondition ('id_blog', '=', $id_blog);
      $sp->orderBy ('order_bacg');

      return $this->findBy ($sp);
    }


    public function check ($record)
    {
            $result = $this->_compiled_check ($record);

            if (isset($result['url_bacg'])) {
                unset($result['url_bacg']);
            }

            if ($result === true){
                $result = array ();
            }


            return (count ($result)>0) ? $result : true;
        }

}

class DAORecordblogarticlecategory
{
}
