<?php
/**
* @package	copix
* @version	$Id: blogfluxrss.dao.class.php,v 1.3 2007-07-30 14:42:07 cbeyer Exp $
* @author	Sylvain DACLIN see copix.aston.fr for other contributors.
* @copyright 2001-2005 CopixTeam
* @link		http://copix.org
* @licence  http://www.gnu.org/licenses/lgpl.htmlGNU Leser General Public Licence, see LICENCE file
*/
class DAOBlogFluxRss {
/**
	* findAllOrder
	* @param 
	* @return
	*/
	function findAllOrder ($id_blog){
		$dbw  = & CopixDbFactory::getDbWidget ();
		
		$critere = ' SELECT fluxrss.id_bfrs as id_bfrs, '.
							'fluxrss.id_blog as id_blog, '. 	
							'fluxrss.order_bfrs as order_bfrs, '. 	
							'fluxrss.name_bfrs as name_bfrs, '. 	
							'fluxrss.url_bfrs as url_bfrs '. 	
					' FROM module_blog_fluxrss as fluxrss '.
					' WHERE fluxrss.id_blog = '.$id_blog.
					' ORDER BY fluxrss.order_bfrs ASC';
		return $dbw->fetchAll($critere);
	}
	
	 
   /**
    * doUp
    * @param $link
    * @return 
    */
   function doUp ($id_blog, $fluxRss) {
      if (intVal($fluxRss->order_bfrs) > 1) {
         $ct = & CopixDBFactory::getConnection ();
         // MoveUp previous menu
         $sqlSwap1 = 'UPDATE module_blog_fluxrss SET order_bfrs='.$fluxRss->order_bfrs.' WHERE id_blog='.$id_blog.' AND order_bfrs='.(intval($fluxRss->order_bfrs) - 1);
         $ct->doQuery($sqlSwap1);
         // MoveDown this menu
         $sqlSwap2 = 'UPDATE module_blog_fluxrss SET order_bfrs=order_bfrs-1 WHERE id_blog='.$id_blog.' AND id_bfrs='.$fluxRss->id_bfrs;
         $ct->doQuery($sqlSwap2);
      }
   }
   
   /**
    * @param $id_menu
    * doDown
    * @return
    */
   function doDown ($id_blog, $fluxRss) {
      $ct = & CopixDBFactory::getConnection ();
      $RS = $ct->doQuery('SELECT MAX(order_bfrs) as max FROM module_blog_fluxrss WHERE id_blog='.$id_blog);
      if ($record = $RS->fetch()) {
         $maxOrder = $record->max;
      }else{
         return false;
      }
   	if ($link->order_blnk < $maxOrder) {
         // MoveDown next menu
         $sqlSwap1 = 'UPDATE module_blog_fluxrss SET order_bfrs='.$fluxRss->order_bfrs.' WHERE id_blog='.$id_blog.' AND order_bfrs='.(intval($fluxRss->order_bfrs) + 1);
         $ct->doQuery($sqlSwap1);
         // MoveUp this menu
         $sqlSwap2 = 'UPDATE module_blog_fluxrss SET order_bfrs=order_bfrs+1 WHERE id_blog='.$id_blog.' AND id_bfrs='.$fluxRss->id_bfrs;
         $ct->doQuery($sqlSwap2);
      }
   }
   
   /**
    * @param 
    * getNewPos
    * @return
    */
   function getNewPos($id_blog) {
      $sql = 'SELECT max(order_bfrs)+1 as max FROM module_blog_fluxrss WHERE id_blog='.$id_blog;
      $dbWidget = & CopixDBFactory::getDbWidget ();
      if (($result = $dbWidget->fetchFirst ($sql)) && $result->max > 0) {
         return $result->max;
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
       $sqlDelete = 'DELETE FROM module_blog_fluxrss WHERE id_bfrs=' . $item->id_bfrs;
       $ct->doQuery($sqlDelete);
       
       // Reorder
       $sqlOrdre = 'UPDATE module_blog_fluxrss SET order_bfrs=order_bfrs - 1 WHERE order_bfrs > '.$item->order_bfrs;
       $ct->doQuery($sqlOrdre);
   }
   
    /**
    * Get all links from a blog
    */
    function getAllFluxRssFromBlog ($id_blog) {
      $sp = & CopixDAOFactory::createSearchConditions ();
      $sp->addCondition ('id_blog', '=', $id_blog);
      $sp->addItemOrder ('order_bfrs', 'ASC');

      return $this->_compiled->findBy ($sp);
    }
	
	
	/**
    * Get all links from a blog
    */
    function getFluxById ($id_bfrs) {
		$sp = & CopixDAOFactory::createSearchParams ();
		$sp->addCondition ('id_bfrs', '=', $id_bfrs);
		
		if (count($arFlux = $this->_compiled->findBy ($sp)) > 0)  {
			return $arFlux[0];
		}else{
			return false;
		}
    }
}

?>