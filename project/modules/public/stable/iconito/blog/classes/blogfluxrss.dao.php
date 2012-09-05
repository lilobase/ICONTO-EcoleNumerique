<?php
/**
* @package	copix
* @version	$Id: blogfluxrss.dao.class.php,v 1.3 2007-07-30 14:42:07 cbeyer Exp $
* @author	Sylvain DACLIN see copix.aston.fr for other contributors.
* @copyright 2001-2005 CopixTeam
* @link		http://copix.org
* @licence  http://www.gnu.org/licenses/lgpl.htmlGNU Leser General Public Licence, see LICENCE file
*/
class DAOBlogFluxRss
{
/**
    * findAllOrder
    * @param
    * @return
    */
    public function findAllOrder ($id_blog)
    {
        $critere = ' SELECT fluxrss.id_bfrs as id_bfrs, '.
                            'fluxrss.id_blog as id_blog, '.
                            'fluxrss.order_bfrs as order_bfrs, '.
                            'fluxrss.name_bfrs as name_bfrs, '.
                            'fluxrss.url_bfrs as url_bfrs '.
                    ' FROM module_blog_fluxrss as fluxrss '.
                    ' WHERE fluxrss.id_blog = '.$id_blog.
                    ' ORDER BY fluxrss.order_bfrs ASC';
        return _doQuery($critere);
    }


   /**
    * doUp
    * @param $link
    * @return
    */
   public function doUp ($id_blog, $fluxRss)
   {
      if (intVal($fluxRss->order_bfrs) > 1) {
         // MoveUp previous menu
         $sqlSwap1 = 'UPDATE module_blog_fluxrss SET order_bfrs='.$fluxRss->order_bfrs.' WHERE id_blog='.$id_blog.' AND order_bfrs='.(intval($fluxRss->order_bfrs) - 1);
         _doQuery($sqlSwap1);
         // MoveDown this menu
         $sqlSwap2 = 'UPDATE module_blog_fluxrss SET order_bfrs=order_bfrs-1 WHERE id_blog='.$id_blog.' AND id_bfrs='.$fluxRss->id_bfrs;
         _doQuery($sqlSwap2);
      }
   }

   /**
    * @param $id_menu
    * doDown
    * @return
    */
   public function doDown ($id_blog, $fluxRss)
   {
      $RS = _doQuery('SELECT MAX(order_bfrs) as max FROM module_blog_fluxrss WHERE id_blog='.$id_blog);
      if (isset($RS[0]))
         $maxOrder = $RS[0]->max;
      else
         return false;
       if ($link->order_blnk < $maxOrder) {
         // MoveDown next menu
         $sqlSwap1 = 'UPDATE module_blog_fluxrss SET order_bfrs='.$fluxRss->order_bfrs.' WHERE id_blog='.$id_blog.' AND order_bfrs='.(intval($fluxRss->order_bfrs) + 1);
         _doQuery($sqlSwap1);
         // MoveUp this menu
         $sqlSwap2 = 'UPDATE module_blog_fluxrss SET order_bfrs=order_bfrs+1 WHERE id_blog='.$id_blog.' AND id_bfrs='.$fluxRss->id_bfrs;
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
      $sql = 'SELECT max(order_bfrs)+1 as max FROM module_blog_fluxrss WHERE id_blog='.$id_blog;
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
       $sqlDelete = 'DELETE FROM module_blog_fluxrss WHERE id_bfrs=' . $item->id_bfrs;
       _doQuery($sqlDelete);

       // Reorder
       $sqlOrdre = 'UPDATE module_blog_fluxrss SET order_bfrs=order_bfrs - 1 WHERE order_bfrs > '.$item->order_bfrs;
       _doQuery($sqlOrdre);
   }

    /**
    * Get all links from a blog
    */
    public function getAllFluxRssFromBlog ($id_blog)
    {
      $sp = _daoSp ();
      $sp->addCondition ('id_blog', '=', $id_blog);
      $sp->orderBy ('order_bfrs');

      return $this->findBy ($sp);
    }


    /**
    * Get all links from a blog
    */
    public function getFluxById ($id_bfrs)
    {
        $sp = _daoSp ();
        $sp->addCondition ('id_bfrs', '=', $id_bfrs);

        if (count($arFlux = $this->findBy ($sp)) > 0)  {
            return $arFlux[0];
        }else{
            return false;
        }
    }
}

