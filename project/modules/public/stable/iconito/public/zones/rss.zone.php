<?php

/**
 * Zone qui affiche le RSS de tous les blogs du site
 * 
 * @package Iconito
 * @subpackage	Public
 */
class ZoneRss extends CopixZone {

	/**
	 * Affiche la liste des blogs ayant au moins un article
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2006/03/23
	 * @param string $kw Mot-clé pour la recherche (option)
	 */
	function _createContent (&$toReturn) {
		
		$blog = $this->getParam('blog',null);
    
		$tpl  = & new CopixTpl ();
		
	  //on récupère l'ensemble des articles du blog
    //$dao = _dao('blog|blogarticle');
		
		$dao     = _dao('blog|blogarticlecategory');
		$daoLink = _dao('blog|blogarticle_blogarticlecategory');
      
    //$arData = $dao->getAllArticlesFromBlog($blog->id_blog, NULL);
    //print_r($arData); 
    //$arData = $dao->getAllArticlesFromBlogByCritere($blog->id_blog, NULL);
    
		
		$critere = 'SELECT ART.id_bact, ART.name_bact, ART.url_bact, ART.date_bact, ART.time_bact, ART.sumary_bact, ART.sumary_html_bact, BLOG.url_blog FROM module_blog BLOG, module_blog_article ART WHERE ART.id_blog=BLOG.id_blog AND BLOG.is_public=1 ORDER BY ART.date_bact DESC, ART.time_bact DESC, ART.id_bact ASC LIMIT '.intval(CopixConfig::get('public|rss.nbArticles'));
		$arArticle = _doQuery($critere);
		foreach ($arArticle as $key=>$article) {
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
		
    //print_r($blog);
    $rss = array (
      'title' => CopixI18N::get ('public|public.rss.flux.title'),
      'link' => CopixUrl::get(),
      'description' => CopixI18N::get ('public|public.rss.flux.description'),
      'language' => 'fr-fr',
      'copyright' => "Iconito",
//      'webmaster' => $blog->name_blog,
      'generator' => "Iconito",
      'logo' => 0,
    );
		$tpl->assign ('rss' , $rss);	
		$tpl->assign ('blog' , $blog);
		$tpl->assign ('listArticle', $arArticle);
		

    $toReturn = $tpl->fetch('rss.tpl');
    return true;

	}
}
?>