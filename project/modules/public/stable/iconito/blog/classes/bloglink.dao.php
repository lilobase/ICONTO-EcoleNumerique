<?php
/**
* @package	copix
* @version	$Id: bloglink.dao.class.php,v 1.7 2007-07-30 14:42:07 cbeyer Exp $
* @author	Sylvain DACLIN see copix.aston.fr for other contributors.
* @copyright 2001-2005 CopixTeam
* @link		http://copix.org
* @licence  http://www.gnu.org/licenses/lgpl.htmlGNU Leser General Public Licence, see LICENCE file
*/
class DAOBlogLink {
    /**
    * findAllOrder
    * @param 
    * @return
    */
    function findAllOrder ($id_blog){
      $critere = ' SELECT link.id_blnk as id_blnk, '.
      									 'link.id_blog as id_blog, '. 	
      									 'link.order_blnk as order_blnk, '. 	
      									 'link.name_blnk as name_blnk, '. 	
      									 'link.url_blnk as url_blnk '. 	
                 ' FROM module_blog_link as link '.
                 ' WHERE link.id_blog = '.$id_blog.
		  					 ' ORDER BY link.order_blnk ASC';
      return _doQuery($critere);
    }
    
   /**
    * doUp
    * @param $link
    * @return 
    */
   function doUp ($id_blog, $link) {
      if (intVal($link->order_blnk) > 1) {
         $ct = & CopixDBFactory::getConnection ();
         // MoveUp previous menu
         $sqlSwap1 = 'UPDATE module_blog_link SET order_blnk='.$link->order_blnk.' WHERE id_blog='.$id_blog.' AND order_blnk='.(intval($link->order_blnk) - 1);
         $ct->doQuery($sqlSwap1);
         // MoveDown this menu
         $sqlSwap2 = 'UPDATE module_blog_link SET order_blnk=order_blnk-1 WHERE id_blog='.$id_blog.' AND id_blnk='.$link->id_blnk;
         $ct->doQuery($sqlSwap2);
      }
   }
   
   /**
    * @param $id_menu
    * doDown
    * @return
    */
   function doDown ($id_blog, $link) {
      $ct = & CopixDBFactory::getConnection ();
      $RS = $ct->doQuery('SELECT MAX(order_blnk) as max FROM module_blog_link WHERE id_blog='.$id_blog);
      if ($record = $RS->fetch()) {
         $maxOrder = $record->max;
      }else{
         return false;
      }
   	if ($link->order_blnk < $maxOrder) {
         // MoveDown next menu
         $sqlSwap1 = 'UPDATE module_blog_link SET order_blnk='.$link->order_blnk.' WHERE id_blog='.$id_blog.' AND order_blnk='.(intval($link->order_blnk) + 1);
         $ct->doQuery($sqlSwap1);
         // MoveUp this menu
         $sqlSwap2 = 'UPDATE module_blog_link SET order_blnk=order_blnk+1 WHERE id_blog='.$id_blog.' AND id_blnk='.$link->id_blnk;
         $ct->doQuery($sqlSwap2);
      }
   }
   
   /**
    * @param 
    * getNewPos
    * @return
    */
   function getNewPos($id_blog) {
      $sql = 'SELECT max(order_blnk)+1 as max FROM module_blog_link WHERE id_blog='.$id_blog;
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
   function delete ($item) {
       $ct = & CopixDBFactory::getConnection ();

       // Delete menu item
       $sqlDelete = 'DELETE FROM module_blog_link WHERE id_blnk=' . $item->id_blnk;
       $ct->doQuery($sqlDelete);
       
       // Reorder
       $sqlOrdre = 'UPDATE module_blog_link SET order_blnk=order_blnk - 1 WHERE order_blnk > '.$item->order_blnk;
       $ct->doQuery($sqlOrdre);
   }
    /**
    * Get all links from a blog
    */
    function getAllLinksFromBlog ($id_blog) {
      $sp = _daoSp ();
      $sp->addCondition ('id_blog', '=', $id_blog);
      $sp->orderBy ('order_blnk');
      return $this->findBy ($sp);
    }
}

?>
