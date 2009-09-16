<?php
/**
* @package	copix
* @version	$Id: blogarticlecategory.dao.class.php,v 1.7 2007-07-30 14:42:07 cbeyer Exp $
* @author	Sylvain DACLIN see copix.aston.fr for other contributors.
* @licence  http://www.gnu.org/licenses/lgpl.htmlGNU Leser General Public Licence, see LICENCE file
*/
class DAOBlogarticlecategory {
   /**
    * get blog by name
    * @param  name
    * @return
    */
    function getCategoryByName ($url_bacg){
      $sp = _daoSp ();
      $sp->addCondition ('url_bacg', '=', $url_bacg);

      if (count($arCat = $this->_compiled->findBy ($sp)) > 0)  {
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
    function findAllOrder ($id_blog){
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
    function findAllOrderBy ($id_blog, $orderby){
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
   function doUp ($id_blog, $category) {
      if (intVal($category->order_bacg) > 1) {
         $ct = & CopixDBFactory::getConnection ();
         // MoveUp previous menu
         $sqlSwap1 = 'UPDATE module_blog_articlecategory SET order_bacg='.$category->order_bacg.' WHERE id_blog='.$id_blog.' AND order_bacg='.(intval($category->order_bacg) - 1);
         $ct->doQuery($sqlSwap1);
         // MoveDown this menu
         $sqlSwap2 = 'UPDATE module_blog_articlecategory SET order_bacg=order_bacg-1 WHERE id_blog='.$id_blog.' AND id_bacg='.$category->id_bacg;
         $ct->doQuery($sqlSwap2);
      }
   }
   
   /**
    * @param $id_menu
    * doDown
    * @return
    */
   function doDown ($id_blog, $category) {
      $ct = & CopixDBFactory::getConnection ();
      $RS = $ct->doQuery('SELECT MAX(order_bacg) as max FROM module_blog_articlecategory WHERE id_blog='.$id_blog);
      if ($record = $RS->fetch()) {
         $maxOrder = $record->max;
      }else{
         return false;
      }
   	if ($category->order_bacg < $maxOrder) {
         // MoveDown next menu
         $sqlSwap1 = 'UPDATE module_blog_articlecategory SET order_bacg='.$category->order_bacg.' WHERE id_blog='.$id_blog.' AND order_bacg='.(intval($category->order_bacg) + 1);
         $ct->doQuery($sqlSwap1);
         // MoveUp this menu
         $sqlSwap2 = 'UPDATE module_blog_articlecategory SET order_bacg=order_bacg+1 WHERE id_blog='.$id_blog.' AND id_bacg='.$category->id_bacg;
         $ct->doQuery($sqlSwap2);
      }
   }
   
   /**
    * @param 
    * getNewPos
    * @return
    */
   function getNewPos($id_blog) {
      $sql = 'SELECT max(order_bacg)+1 as max FROM module_blog_articlecategory WHERE id_blog='.$id_blog;
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
       $sqlDelete = 'DELETE FROM module_blog_articlecategory WHERE id_bacg=' . $item->id_bacg;
       $ct->doQuery($sqlDelete);
       
       // Reorder
       $sqlOrdre = 'UPDATE module_blog_articlecategory SET order_bacg=order_bacg - 1 WHERE order_bacg > '.$item->order_bacg;
       $ct->doQuery($sqlOrdre);
   } 

		/**
    * Get all categories from a blog
    */
    function getAllCategoriesFromBlog ($id_blog) {
      $sp = & _daoSearchConditions ();
      $sp->addCondition ('id_blog', '=', $id_blog);
      $sp->addItemOrder ('order_bacg', 'ASC');

      return $this->_compiled->findBy ($sp);
    }
}

class DAORecordblogarticlecategory {
		function check (){
			$result = $this->_compiled->_compiled_check ();

			if ($result === true){
				$result = array ();
			}

			if( (!empty($this->_compiled->url_bacg)) && (!empty($this->_compiled->id_blog))) {
				if(empty($this->_compiled->id_bacg)) {
					// Création 
					$sqlRequest = 'SELECT id_bacg FROM module_blog_articlecategory WHERE '.
														' id_blog=' . $this->_compiled->id_blog.
														' AND url_bacg=\'' . $this->_compiled->url_bacg.'\'';
				} else {
					// Edition
					$sqlRequest = 'SELECT id_bacg FROM module_blog_articlecategory WHERE '.
														' id_blog=' . $this->_compiled->id_blog.
														' AND id_bacg!=' . $this->_compiled->id_bacg.
														' AND url_bacg=\'' . $this->_compiled->url_bacg.'\'';
				}
				// Vérification de l'unicité de l'url
      	$DBresult = _doQuery($sqlRequest);
				if(count($DBresult)>0) {
					require_once (COPIX_CORE_PATH . 'CopixErrorObject.class.php');
					$errorObject = new CopixErrorObject ();
					$errorObject->addError ('blog.edit.tpl', CopixI18N::get('blog.dao.url.exist'));
					$result = array_merge ($errorObject->asArray(), $result);
				}
			}			

			return (count ($result)>0) ? $result : true;
		} 

}
?>