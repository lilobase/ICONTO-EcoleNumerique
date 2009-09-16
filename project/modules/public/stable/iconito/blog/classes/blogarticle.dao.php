<?php

require_once (COPIX_UTILS_PATH.'CopixDateTime.class.php');

/**
* @package	copix
* @version	$Id: blogarticle.dao.class.php,v 1.19 2009-01-09 16:06:15 cbeyer Exp $
* @author	Sylvain DACLIN see copix.aston.fr for other contributors.
* @copyright 2001-2005 Copix Team
* @link		http://copix.org
* @licence  http://www.gnu.org/licenses/lgpl.htmlGNU Leser General Public Licence, see LICENCE file
*/
class DAOBlogArticle {
	/**
    * findListMonthForArticle
    * @param 
    * @return
    */
	function findListMonthForArticle($id_blog){
		$dbw = & CopixDbFactory::getDbWidget ();
		$critere = ' SELECT art.date_bact as date_bact
    FROM module_blog_article as art WHERE art.id_blog = '.$id_blog.' ORDER BY art.date_bact DESC, art.id_bact DESC';
		
		return $dbw->fetchAll($critere);
	}
    /**
    * getAllArchivesFromBlog
    * @param 
    * @return
    */
    function getAllArchivesFromBlog ($id_blog) {
      $sp = & CopixDAOFactory::createSearchConditions ();
      $sp->addCondition ('id_blog', '=', $id_blog);
      $sp->addCondition ('is_online', '=', 1);
      $sp->addItemOrder ('date_bact', 'desc');
      $sp->addItemOrder ('id_bact', 'desc');

      $result = $this->_compiled->findBy ($sp);

      $arArchive = array();
      $lastMonth = null;
      foreach($result as $article) {
	      $monthYear = substr($article->date_bact,4,2).'/'.substr($article->date_bact,0,4);
	      if($monthYear != $lastMonth) {
	        $article->drawDate = CopixDateTime::YYYYMMtoYearMonthName(substr($article->date_bact,0,6));
	        $article->dateValue = substr($article->date_bact,0,6);
	        array_push($arArchive, $article);
					$lastMonth = $monthYear;
	      }
      }      
      return $arArchive;
    }
	/**
    * get article by name
    * @param  name
    * @return
    */
	function getArticleByUrl ($id_blog, $url_bact){
		$sp = & CopixDAOFactory::createSearchParams ();
		$sp->addCondition ('url_bact', '=', urlencode($url_bact));
		$sp->addCondition ('id_blog' , '=', $id_blog);
		$sp->addCondition ('is_online', '=', 1);
		//if (count($arArticle = $this->_compiled->findBy ($sp)) > 0)  {
		if ( $arArticle = $this->_compiled->findBy ($sp) )  {

			//on récupère les catégories liées
			$dao     = CopixDAOFactory::create('blog|blogarticlecategory');
			$daoLink = CopixDAOFactory::create('blog|blogarticle_blogarticlecategory');
			$article = $arArticle[0];

			$sp = & CopixDAOFactory::createSearchParams ();
			$sp->addCondition ('id_bact', '=', $article->id_bact);

			$article->categories = array();
			foreach ($daoLink->findBy($sp) as $object) {
				$article->categories[] = $dao->get($object->id_bacg);
			}

			return $article;
		}else{
			return false;
		}
	}

	/**
    * get article by name
    * @param  name
    * @return
    */
	function getArticleById ($id_blog, $id_bact){
		$sp = & CopixDAOFactory::createSearchParams ();
		$sp->addCondition ('id_bact', '=', $id_bact);
		$sp->addCondition ('id_blog' , '=', $id_blog);
		$sp->addCondition ('is_online', '=', 1);
		//if (count($arArticle = $this->_compiled->findBy ($sp)) > 0)  {
		if ( $arArticle = $this->_compiled->findBy ($sp) )  {

			//on récupère les catégories liées
			$dao     = CopixDAOFactory::create('blog|blogarticlecategory');
			$daoLink = CopixDAOFactory::create('blog|blogarticle_blogarticlecategory');
			$article = $arArticle[0];

			$sp = & CopixDAOFactory::createSearchParams ();
			$sp->addCondition ('id_bact', '=', $article->id_bact);

			$article->categories = array();
			foreach ($daoLink->findBy($sp) as $object) {
				$article->categories[] = $dao->get($object->id_bacg);
			}

			return $article;
		}else{
			return false;
		}
	}
	
		/**
    * Get all article from a blog
    */
	function getAllArticlesFromBlog ($id_blog, $date=null) {
		$sp = _daoSp ();
		$sp->addCondition ('id_blog', '=', $id_blog);
		$sp->addCondition ('is_online', '=', 1);
	        if($date!=null) {
                   $sp->addCondition ('date_bact', 'LIKE', $date.'%');
                }	
		$sp->orderBy (array('date_bact', 'desc'));
		$sp->orderBy (array('time_bact', 'desc'));
		$sp->orderBy (array('id_bact', 'desc'));

		$arArticle = $this->findBy ($sp);

		//on récupère les catégories liées
		$dao     = CopixDAOFactory::create('blog|blogarticlecategory');
		$daoLink = CopixDAOFactory::create('blog|blogarticle_blogarticlecategory');
		foreach ($arArticle as $key=>$article){
			$sp = & CopixDAOFactory::createSearchParams ();
			$sp->addCondition ('id_bact', '=', $article->id_bact);

			$arArticle[$key]->categories = array();
			foreach ($daoLink->findBy($sp) as $object) {
				$arArticle[$key]->categories[] = $dao->get($object->id_bacg);
			}
			$date = array();
      $date['Y'] = substr($article->date_bact,0,4);
      $date['m'] = substr($article->date_bact,4,2);
      $date['d'] = substr($article->date_bact,6,2);
      $date['H'] = substr($article->time_bact,0,2);
      $date['i'] = substr($article->time_bact,2,2);
  		$arArticle[$key]->dateRFC822 = gmdate('D, d M Y H:i:s',mktime($date['H'],$date['i'],0,$date['m'],$date['d'],$date['Y'])).' GMT';

		}

		return $arArticle;
	}

	/**
    * Get all article from a blog bu cat
    */
	function getAllArticlesFromBlogByCat ($id_blog, $id_bacg) {
	
		//on récupère les identifiants d'article correspondant à la catégorie
		$daoLink = CopixDAOFactory::create('blog|blogarticle_blogarticlecategory');
		$sp      = & CopixDAOFactory::createSearchParams ();
		$sp->addCondition ('id_bacg', '=', $id_bacg);
		$arID    = array();
		foreach ($daoLink->findBy($sp) as $object) {
			$arID[] = $object->id_bact;
		}
		if ($arID == array()) {
			return array();
		}

		$sp = & CopixDAOFactory::createSearchConditions ();
		$sp->addCondition ('id_blog', '=', $id_blog);
		$sp->addCondition ('id_bact', '=', $arID);
		$sp->addCondition ('is_online', '=', 1);
		$sp->addItemOrder ('date_bact', 'desc');
		$sp->addItemOrder ('time_bact', 'desc');
		$sp->addItemOrder ('id_bact', 'desc');

		$arArticle = $this->_compiled->findBy ($sp);

		//on récupère les catégories liées
		$dao     = CopixDAOFactory::create('blog|blogarticlecategory');
		foreach ($arArticle as $key=>$article){
			$sp = & CopixDAOFactory::createSearchParams ();
			$sp->addCondition ('id_bact', '=', $article->id_bact);
			$arArticle[$key]->categories = array();
			foreach ($daoLink->findBy($sp) as $object) {
				$arArticle[$key]->categories[] = $dao->get($object->id_bacg);
			}
		}
		return $arArticle;
	}
	
	
	/**
    * Get all article from a blog by critere
	* @param string $critere mot du critere de recherche des articles
	* @return array $arArticle articles
    */
	function getAllArticlesFromBlogByCritere($id_blog, $critere){
		$arResultat = array();	
		$arCritere = explode(" ", $critere);
		$daoLink = CopixDAOFactory::create('blog|blogarticle_blogarticlecategory');
		$dao     = CopixDAOFactory::create('blog|blogarticlecategory');
		
		
		$arIds = array();
		foreach($arCritere as $word) {

			/*
			$query = 'SELECT * FROM module_blog_article where id_blog = ' . $id_blog . '
						AND is_online=1 
						AND ((name_bact LIKE \'%' . $word . '%\') OR (sumary_bact LIKE \'%' . $word . '%\')
							OR (content_bact LIKE \'%' . $word . '%\')) 
							ORDER BY date_bact DESC, time_bact DESC, id_bact DESC' ;
							
	        $ct = CopixDBFactory::getConnection ($this->_connectionName);
	        $result = $ct->doQuery ($query);
	
	        while ($r = $result->fetch ()) {
	            $arResultat[] = $r;
	        }
				*/

			$arArticle = $this->_compiled->findByCritere ($id_blog, '%'.$word.'%');
			
			foreach ($arArticle as $key=>$article){
				//var_dump($article->id_bact);
				//var_dump($article);
				if (!in_array($article->id_bact, $arIds)) {
					$sp = & CopixDAOFactory::createSearchParams ();
					$sp->addCondition ('id_bact', '=', $article->id_bact);
					$arArticle[$key]->categories = array();
					foreach ($daoLink->findBy($sp) as $object) {
						$arArticle[$key]->categories[] = $dao->get($object->id_bacg);
					}
					$arIds[] = $article->id_bact;
					$arResultat[] = $article;
				}
			}
		}
		//var_dump($arArticle);
		//$arResultat = $arArticle;
		/*
		//on supprime les doublons
		$arResultSansDoublon = array();
		foreach($arResultat as $key=>$value){
			if( !isset($arResultSansDoublon[$value->id_bact])){
				$arResultSansDoublon[$value->id_bact] = $value;
			}
		}
		
		// on renseigne les catégories
		foreach ($arResultSansDoublon as $key=>$article){
			$sp = & CopixDAOFactory::createSearchParams ();
			$sp->addCondition ('id_bact', '=', $article->id_bact);

			$arResultSansDoublon[$key]->categories = array();
			foreach ($daoLink->findBy($sp) as $object) {
				$arResultSansDoublon[$key]->categories[] = $dao->get($object->id_bacg);
			}
		}
    return $arResultSansDoublon;
		*/
		return $arResultat;
	}

	/**
    * findAllOrder
    * @param 
    * @return
    */
	function findArticles ($id_blog, $id_bacg, $query, $orderby = ' art.date_bact DESC, art.time_bact DESC, art.id_bact DESC'){
		$dbw  = & CopixDbFactory::getDbWidget ();

		$critere = ' SELECT DISTINCT art.id_bact as id_bact, '.
		'art.id_blog as id_blog, '.
		'art.name_bact as name_bact, '.
		'art.sumary_html_bact as sumary_html_bact, '.
		'art.content_html_bact as content_html_bact, '.
		'art.author_bact as author_bact, '.
		'art.date_bact as date_bact, '.
		'art.time_bact as time_bact, '.
		'art.url_bact as url_bact, '.
		'art.sticky_bact as sticky_bact, '.
		'art.is_online as is_online '.
		' FROM module_blog_article as art LEFT JOIN module_blog_article_blogarticlecategory as artctg ON art.id_bact = artctg.id_bact '.
		' WHERE art.id_blog = '.$id_blog;

		$clause = ' AND ';
		if($id_bacg!=NULL) {
			$critere = $critere.$clause.' AND   artctg.id_bacg = '.$id_bacg;
		}
		if($query!=NULL) {
			$critere = $critere.$clause.$query;
		}
		if($orderby !=NULL) {
			$critere = $critere.' order by '.$orderby;
		}
		return $dbw->fetchAll($critere);
	}


	function findCategoriesForArticle ($id_bact){
		$dbw  = & CopixDbFactory::getDbWidget ();

		$critere = ' SELECT ctg.id_bacg as id_bacg, '.
		'ctg.name_bacg as name_bacg, '.
		'ctg.url_bacg as url_bacg '.
		' FROM module_blog_articlecategory as ctg LEFT JOIN module_blog_article_blogarticlecategory as artctg ON ctg.id_bacg = artctg.id_bacg '.
		' WHERE artctg.id_bact = '.$id_bact;

		return $dbw->fetchAll($critere);
	}

	/**
    * find old article
    * @param 
    * @return
    */
	function findOldArticle ($id_blog){
		$dbw  = & CopixDbFactory::getDbWidget ();

		$critere = ' SELECT MIN(art.date_bact) as min '.
		' FROM module_blog_article as art '.
		' WHERE art.id_blog = '.$id_blog.'
			AND is_online=1';

		if (($result = $dbw->fetchAll($critere)) && $result[0]->min > 0) {
			return $result[0]->min;
		}else{
			return 0;
		}
	}
	/**
    * delete article and comments
    * @param 
    * @return
    */
	function delete ($item){
		$ct = & CopixDBFactory::getConnection ();

		// Delete link with category table
		$sqlDelete = 'DELETE FROM module_blog_article_blogarticlecategory WHERE id_bact=' . $item->id_bact;
		$ct->doQuery($sqlDelete);

		// Delete article comment
		$sqlDelete = 'DELETE FROM module_blog_articlecomment WHERE id_bact=' . $item->id_bact;
		$ct->doQuery($sqlDelete);

		// Delete article
		$sqlDelete = 'DELETE FROM module_blog_article WHERE id_bact=' . $item->id_bact;
		$ct->doQuery($sqlDelete);
	}

}

class DAORecordblogarticle {
	function check (){
		$result = $this->_compiled->_compiled_check ();

		if ($result === true){
			$result = array ();
		}

		if( (!empty($this->_compiled->url_bact)) && (!empty($this->_compiled->id_blog))) {
			if(empty($this->_compiled->id_bact)) {
				// Création
				$sqlRequest = 'SELECT id_bact FROM module_blog_article WHERE '.
				' id_blog=' . $this->_compiled->id_blog.
				' AND url_bact=\'' . $this->_compiled->url_bact.'\'';
			} else {
				// Edition
				$sqlRequest = 'SELECT id_bact FROM module_blog_article WHERE '.
				' id_blog=' . $this->_compiled->id_blog.
				' AND id_bact!=' . $this->_compiled->id_bact.
				' AND url_bact=\'' . $this->_compiled->url_bact.'\'';
			}
			// Vérification de l'unicité de l'url
			$dbw  = & CopixDbFactory::getDbWidget ();

			if(($DBresult = $dbw->fetchAll($sqlRequest)) && (count($DBresult)>0) ) {
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