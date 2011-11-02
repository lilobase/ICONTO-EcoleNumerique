<?php

/**
 * Zone qui affiche le RSS de tous les blogs du site
 * 
 * @package Iconito
 * @subpackage	Public
 */

_classInclude ('groupe|groupeservice');


class ZoneRss extends CopixZone {

	/**
	 * Affiche la liste des blogs ayant au moins un article
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2006/03/23
	 * @param string $kw Mot-cl� pour la recherche (option)
	 */
	function _createContent (&$toReturn) {
		
		$blog = $this->getParam('blog',null);
    
		$tpl  = new CopixTpl ();
		
	  //on r�cup�re l'ensemble des articles du blog
    //$dao = _dao('blog|blogarticle');
		
		$dao     = _dao('blog|blogarticlecategory');
		$daoLink = _dao('blog|blogarticle_blogarticlecategory');
      
    //$arData = $dao->getAllArticlesFromBlog($blog->id_blog, NULL);
    //print_r($arData); 
    //$arData = $dao->getAllArticlesFromBlogByCritere($blog->id_blog, NULL);

        $arTypes = array();
		if (CopixConfig::exists ('public|blogs.types') && CopixConfig::get ('public|blogs.types'))
			$arTypes = explode(",", CopixConfig::get ('public|blogs.types'));
		$arTypes[] = 'CLUB';

		
		$critere = 'SELECT ART.id_bact, ART.name_bact, ART.url_bact, ART.date_bact, ART.time_bact, ART.sumary_bact, ART.sumary_html_bact, BLOG.url_blog, KME.node_type AS parent_type, KME.node_id AS parent_id FROM module_blog BLOG, module_blog_article ART, kernel_mod_enabled KME WHERE ART.id_blog=BLOG.id_blog AND KME.module_id=BLOG.id_blog AND KME.module_type=\'MOD_BLOG\' AND BLOG.is_public=1 AND ART.is_online=1 AND KME.node_type IN (\''.implode('\',\'',$arTypes).'\') ORDER BY ART.date_bact DESC, ART.time_bact DESC, ART.id_bact ASC';
		
		if (Kernel::getKernelLimits('ville'))
			$critere .= ' LIMIT '.intval(CopixConfig::get('public|rss.nbArticles'));
		else
			$critere .= ' LIMIT '.intval(CopixConfig::get('public|rss.nbArticles'));
		
		$list = _doQuery($critere, array());
		//echo $critere;

        //_dump($list);
		
		$arArticle = array();

        //_dump($list);

		foreach ($list as $article) {
		
			$add = true;
			
			switch ($article->parent_type) {
				case 'CLUB' :
					if (Kernel::getKernelLimits('ville')) {
						$ville = GroupeService::getGroupeVille($article->parent_id);
						if (!in_array($ville, Kernel::getKernelLimits('ville_as_array')))
							$add = false;
					}
					break;
			}
		

			$sp = _daoSp ();
			$sp->addCondition ('id_bact', '=', $article->id_bact);
			$article->categories = array();
			foreach ($daoLink->findBy($sp) as $object) {
				$article->categories[] = $dao->get($object->id_bacg);
			}
			$date = array();
      $date['Y'] = substr($article->date_bact,0,4);
      $date['m'] = substr($article->date_bact,4,2);
      $date['d'] = substr($article->date_bact,6,2);
      $date['H'] = substr($article->time_bact,0,2);
      $date['i'] = substr($article->time_bact,2,2);
  		$article->dateRFC822 = gmdate('D, d M Y H:i:s',mktime($date['H'],$date['i'],0,$date['m'],$date['d'],$date['Y'])).' GMT';

			if ($add)
				$arArticle[] = $article;
		
		}
		
		if (Kernel::getKernelLimits('ville')) {
			$arArticle = array_slice ($arArticle, 0, intval(CopixConfig::get('public|rss.nbArticles')));
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
		
		//print_r($arArticle);
    $toReturn = $tpl->fetch('rss.tpl');
    return true;

	}
}
?>
