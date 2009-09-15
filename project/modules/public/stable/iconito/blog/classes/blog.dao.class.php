<?php
/**
* @package	copix
* @version	$Id: blog.dao.class.php,v 1.9 2006-10-09 16:21:31 cbeyer Exp $
* @author	Sylvain DACLIN see copix.aston.fr for other contributors.
* @copyright 2001-2005 CopixTeam
* @link		http://copix.org
* @licence  http://www.gnu.org/licenses/lgpl.htmlGNU Leser General Public Licence, see LICENCE file
*/
class DAOBlog {
	/**
    * get blog by name
    * @param  name
    * @return
    */
	function getBlogByName ($url_blog){
		$sp = & CopixDAOFactory::createSearchParams ();
		$sp->addCondition ('url_blog', '=', $url_blog);

		if (count($arBlog = $this->_compiled->findBy ($sp)) > 0)  {
			return $arBlog[0];
		}else{
			return false;
		}
	}
	
	
	/**
    * get blog by name
    * @param  name
    * @return
    */
	function getBlogById ($id_blog){
		$sp = & CopixDAOFactory::createSearchParams ();
		$sp->addCondition ('id_blog', '=', $id_blog);
		
		if (count($arBlog = $this->_compiled->findBy ($sp)) > 0)  {
			return $arBlog[0];
		}else{
			return false;
		}
	}	
	

	/**
    * @param 
    * delete
    * @return
    */
	function delete ($id_blog) {
		$ct = & CopixDBFactory::getConnection ();

		// Delete item
		$sqlDelete = 'DELETE FROM module_blog WHERE id_blog=' . $id_blog;
		$ct->doQuery($sqlDelete);

		// Delete item
		$sqlDelete = 'DELETE FROM module_blog_functions WHERE id_blog=' . $id_blog;
		$ct->doQuery($sqlDelete);
	}
}


class DAORecordBlog {
	function check (){
		$result = $this->_compiled->_compiled_check ();

		if ($result === true){
			$result = array ();
		}
		if(!empty($this->_compiled->url_blog)) {
			if(empty($this->_compiled->id_blog)) {
				// Création
				$sqlRequest = 'SELECT id_blog FROM module_blog WHERE url_blog=\'' . $this->_compiled->url_blog.'\'';
			} else {
				// Edition
				$sqlRequest = 'SELECT id_blog FROM module_blog WHERE id_blog!=' . $this->_compiled->id_blog.' AND url_blog=\'' . $this->_compiled->url_blog.'\'';
			}
			// Vérification de l'unicité de l'url
			$dbw  = & CopixDbFactory::getDbWidget ();
			if(($DBresult = $dbw->fetchAll($sqlRequest)) && (count($DBresult)>0) ) {
				require_once (COPIX_CORE_PATH . 'CopixErrorObject.class.php');
				$errorObject = new CopixErrorObject ();
				$errorObject->addError ('blog.edit.tpl', CopixI18N::get('blog|blog.dao.url.exist'));
				$result = array_merge ($errorObject->asArray(), $result);
			}
		}

		return (count ($result)>0) ? $result : true;
	}
}
?>