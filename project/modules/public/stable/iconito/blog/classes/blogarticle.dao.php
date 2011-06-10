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
� � * findListMonthForArticle
� � * @param 
� � * @return
� � */
	function findListMonthForArticle($id_blog){
		$critere = 'SELECT art.date_bact as date_bact
    FROM module_blog_article as art WHERE art.id_blog = '.$id_blog.' ORDER BY art.date_bact DESC, art.id_bact DESC';
		return _doQuery($critere);
	}
    /**
    * getAllArchivesFromBlog
    * @param 
    * @return
    */
    function getAllArchivesFromBlog ($id_blog) {
      $sp = _daoSp();
      $sp->addCondition ('id_blog', '=', $id_blog);
      $sp->addCondition ('is_online', '=', 1);
      $sp->orderBy (array('date_bact', 'desc'));
      $sp->orderBy (array('id_bact', 'desc'));

      $result = $this->findBy ($sp);

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
		$sp = _daoSp ();
		$sp->addCondition ('url_bact', '=', urlencode($url_bact));
		$sp->addCondition ('id_blog' , '=', $id_blog);
		$sp->addCondition ('is_online', '=', 1);

		if ( $arArticle = $this->findBy ($sp) )  {

			//on r�cup�re les cat�gories li�es
			$dao     = _dao('blog|blogarticlecategory');
			$daoLink = _dao('blog|blogarticle_blogarticlecategory');
			$article = $arArticle[0];

			$sp = _daoSp ();
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
    $article = $this->get ($id_bact);
    
		if ( $article && $article->id_blog==$id_blog && $article->is_online )  {

			//on r�cup�re les cat�gories li�es
			$dao     = _dao('blog|blogarticlecategory');
			$daoLink = _dao('blog|blogarticle_blogarticlecategory');

			$sp = _daoSp ();
			$sp->addCondition ('id_bact', '=', $article->id_bact);
			$article->categories = array();
			foreach ($daoLink->findBy($sp) as $object) {
				$article->categories[] = $dao->get($object->id_bacg);
			}
			return $article;
		} else {
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

		//on r�cup�re les cat�gories li�es
		$dao     = _dao('blog|blogarticlecategory');
		$daoLink = _dao('blog|blogarticle_blogarticlecategory');
		foreach ($arArticle as $key=>$article){
			$sp = _daoSp ();
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
	
		//on r�cup�re les identifiants d'article correspondant � la cat�gorie
		$daoLink = _dao('blog|blogarticle_blogarticlecategory');
		$sp      = _daoSp ();
		$sp->addCondition ('id_bacg', '=', $id_bacg);
		$arID    = array();
		foreach ($daoLink->findBy($sp) as $object) {
			$arID[] = $object->id_bact;
		}
		if ($arID == array()) {
			return array();
		}

		$sp = _daoSp ();
		$sp->addCondition ('id_blog', '=', $id_blog);
		$sp->addCondition ('id_bact', '=', $arID);
		$sp->addCondition ('is_online', '=', 1);
		$sp->orderBy (array('date_bact', 'desc'));
		$sp->orderBy (array('time_bact', 'desc'));
		$sp->orderBy (array('id_bact', 'desc'));

		$arArticle = $this->findBy ($sp);

		//on r�cup�re les cat�gories li�es
		$dao     = _dao('blog|blogarticlecategory');
		foreach ($arArticle as $key=>$article){
			$sp = _daoSp ();
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
		$daoLink = _dao('blog|blogarticle_blogarticlecategory');
		$dao     = _dao('blog|blogarticlecategory');
		
		
		$arIds = array();
		foreach($arCritere as $word) {

			$arArticle = $this->findByCritere ($id_blog, '%'.$word.'%');
			
			foreach ($arArticle as $key=>$article){
				//var_dump($article->id_bact);
				//var_dump($article);
				if (!in_array($article->id_bact, $arIds)) {
					$sp = _daoSp ();
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
		
		return $arResultat;
	}

	/**
    * findAllOrder
    * @param 
    * @return
    */
	function findArticles ($id_blog, $id_bacg, $query, $orderby = ' art.date_bact DESC, art.time_bact DESC, art.id_bact DESC'){
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
		return _doQuery($critere);
	}


	function findCategoriesForArticle ($id_bact){
		$critere = ' SELECT ctg.id_bacg as id_bacg, '.
		'ctg.name_bacg as name_bacg, '.
		'ctg.url_bacg as url_bacg '.
		' FROM module_blog_articlecategory as ctg LEFT JOIN module_blog_article_blogarticlecategory as artctg ON ctg.id_bacg = artctg.id_bacg '.
		' WHERE artctg.id_bact = '.$id_bact;
		return _doQuery($critere);
	}

	/**
    * find old article
    * @param 
    * @return
    */
	function findOldArticle ($id_blog){
		$critere = ' SELECT MIN(art.date_bact) as min '.
		' FROM module_blog_article as art '.
		' WHERE art.id_blog = '.$id_blog.'
			AND is_online=1';
		$result = _doQuery($critere);
		if ($result && $result[0]->min > 0) {
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

		// Delete link with category table
		$sqlDelete = 'DELETE FROM module_blog_article_blogarticlecategory WHERE id_bact=' . $item->id_bact;
		_doQuery($sqlDelete);

		// Delete article comment
		$sqlDelete = 'DELETE FROM module_blog_articlecomment WHERE id_bact=' . $item->id_bact;
		_doQuery($sqlDelete);

		// Delete article
		$sqlDelete = 'DELETE FROM module_blog_article WHERE id_bact=' . $item->id_bact;
		_doQuery($sqlDelete);
	}

}

class DAORecordblogarticle {
}
?>
