<?php

_classInclude ('blog|blogutils');
_classInclude ('blog|blogauth');
require_once (COPIX_UTILS_PATH.'../smarty_plugins/modifier.blog_format_article.php');
require_once (COPIX_UTILS_PATH.'../../smarty/plugins/shared.make_timestamp.php');

class KernelBlog {

	/* Crée un blog dans la base de données
	Renvoie son ID ou NULL si erreur
	$infos peut contenir
	$title = Titre du blog
	$author = Auteur du premier article (id user). Si non positionné, prend la session. Sinon, valeur par défaut (voir conf du module)
	*/
	function create ($infos=array()) {
		$blogDAO = CopixDAOFactory::create('blog|blog');
		
		$res = null;
		
		$tabBlogFunctions = returnAllBlogFunctions();
	
		$blog = CopixDAOFactory::createRecord('blog|blog');
		if ($infos['title'])
			$blog->name_blog = $infos['title'].( (isset($infos['subtitle']) && strlen($infos['subtitle'])>0) ? ' ('.$infos['subtitle'].')' : '');
		else
			$blog->name_blog = CopixI18N::get ('blog|blog.default.titre');
		$blog->id_ctpt     	   = 1;
		$blog->style_blog_file = 0;
		
		$blog->is_public       = isset($infos['is_public']) ? $infos['is_public'] : 1;
		$blog->has_comments_activated = 0;
		$blog->type_moderation_comments = CopixConfig::get ('blog|blog.default.type_moderation_comments');
		$blog->default_format_articles = CopixConfig::get ('blog|blog.default.default_format_articles');
		
		$blogDAO->insert($blog);
		
		if ($blog->id_blog !== NULL) {
    
      // On détermine l'URL titre
      $blog->url_blog        = KernelBlog::calcule_url_blog($blog->id_blog, $blog->name_blog);
      $blogDAO->update ($blog);
      
			// On ajoute une catégorie
			$categoryDAO = CopixDAOFactory::create('blog|blogarticlecategory');
			$category = CopixDAOFactory::createRecord('blog|blogarticlecategory');
			$category->id_blog	 = $blog->id_blog;
			$category->name_bacg = CopixI18N::get ('blog|blog.default.categorie');
			$category->url_bacg	 = killBadUrlChars($category->name_bacg) . date('YmdHis');
			$category->order_bacg = $categoryDAO->getNewPos($blog->id_blog);
			$categoryDAO->insert($category);
			
			if ($category->id_bacg!==NULL) {	// On ajoute un article
				$articleDAO = CopixDAOFactory::create('blog|blogarticle');
				$article = CopixDAOFactory::createRecord('blog|blogarticle');
				$article->id_blog	 = $blog->id_blog;
				$article->name_bact	 = CopixI18N::get ('blog|blog.default.article.titre');
				$article->format_bact	 = 'wiki';
				$article->sumary_bact	 = CopixI18N::get ('blog|blog.default.article.body');
				$article->sumary_html_bact = smarty_modifier_blog_format_article ($article->sumary_bact, $article->format_bact);
				$article->content_bact	 = '';
				$article->content_html_bact = smarty_modifier_blog_format_article ($article->content_bact, $article->format_bact);
				$article->is_online	 = 1;
				
				if (isset($infos['author']))
					$author_bact = $infos['author'];
				else {
					$user = BlogAuth::getUserInfos();
					$author_bact = ($user->userId) ? $user->userId : CopixConfig::get ('blog|blogDefaultArticleOwner');
				}

	      $article->author_bact = $author_bact;
				$article->date_bact = date('Ymd');
      	$article->time_bact = timeToBD(date('H:i'));
				$article->url_bact = killBadUrlChars($article->name_bact);
				$article->sticky_bact = 0;
				$articleDAO->insert($article);
				
				if ($article->id_bact!==NULL) {	// On relie l'article à la catégorie
  				$article->url_bact = killBadUrlChars($article->id_bact.'-'.$article->name_bact);
  				$articleDAO->update($article);

					$artctgDAO = CopixDAOFactory::create('blog|blogarticle_blogarticlecategory');
					$artctgDAO->deleteAndInsert($article->id_bact, array($category->id_bacg));
				}
			}
		}
		
		return ($blog->id_blog!==NULL) ? $blog->id_blog : NULL;
	}


	/**
	Renvoie différentes infos chiffrées d'un blog, dans un tableau
	*/
	function getStats ($id_blog) {
		$dao = CopixDAOFactory::create('blog|blogarticle');
		//var_dump($dao);
		$res = array();	
		$arData = $dao->getAllArticlesFromBlog($id_blog, NULL);
		$nbArticles = count($arData);
		$res['nbArticles'] = array ('name'=>CopixI18N::get ('blog|blog.stats.nbArticles', array($nbArticles)), 'value'=>$nbArticles);
		//print_r($arData);
		if ($nbArticles>0) {
			$date = BDToDateTime($arData[0]->date_bact, $arData[0]->time_bact, 'mysql');
			$mktime = smarty_make_timestamp($date);
			$date = CopixDateTime::mktimeToDatetime ($mktime);
			$res['lastUpdate'] = array (
				'name'=>CopixI18N::get ('blog|blog.stats.lastUpdate', array($date)),
				'value_order'=>$mktime,
			);
		}
		return $res;
	}


	/**
	 * Statistiques du module blog
	 *
	 * Renvoie des éléments chiffrés relatifs aux blogs et dédiés à un utilisateur système : nombre de blogs, d'articles...
	 *
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2007/03/20
	 * @return array Tableau dont les clefs représentent les libellés des stats et les valeurs les stats chiffrées. Clefs utilisées : ["nbForums"] ["nbTopics"] ["nbMessages"]
	 */
	function getStatsRoot () {
		$res = array();	
		$dbw = & CopixDbFactory::getDbWidget ();
		$sql = 'SELECT COUNT(id_blog) AS nb FROM module_blog';
		$a = $dbw->fetchFirst ($sql);
		$res['nbBlogs'] = array ('name'=>CopixI18N::get ('blog|blog.stats.nbBlogs', array($a->nb)));
		$sql = 'SELECT COUNT(id_bact) AS nb FROM module_blog_article';
		$a = $dbw->fetchFirst ($sql);
		$res['nbArticles'] = array ('name'=>CopixI18N::get ('blog|blog.stats.nbArticles', array($a->nb)));
		$sql = 'SELECT COUNT(id_bacc) AS nb FROM module_blog_articlecomment';
		$a = $dbw->fetchFirst ($sql);
		$res['nbComments'] = array ('name'=>CopixI18N::get ('blog|blog.stats.nbComments', array($a->nb)));
		return $res;
	}



	function delete ($id_blog) {
		
		//suppression du blog
		$blogDAO = & CopixDAOFactory::create ('blog|blog');
		$blogDAO->delete($id_blog);
		
		//suppression des pages liées au blog
		$daoPage = & CopixDAOFactory::getInstanceOf ('blog|blogpage');
		$record  = & CopixDAOFactory::createRecord ('blog|blogpage');
	
		$criteres = CopixDAOFactory::createSearchConditions();
		$criteres->addCondition('id_blog', '=', $id_blog);	
		$resultat = $daoPage->findBy($criteres);
		$daoPage = & CopixDAOFactory::create ('blog|blogpage');
		foreach($resultat as $page){
			$daoPage->delete($page);
		}	
		
		//suppression des liens liés au blog
		$daoLien = & CopixDAOFactory::getInstanceOf ('blog|bloglink');
		$record  = & CopixDAOFactory::createRecord ('blog|bloglink');
	
		$criteres = CopixDAOFactory::createSearchConditions();
		$criteres->addCondition('id_blog', '=', $id_blog);	
		$resultat = $daoLien->findBy($criteres);
		
		foreach($resultat as $lien){
			$daoLien->delete($lien);
		}	

		//suppression des catégories du blog
		$daoCategorie = & CopixDAOFactory::getInstanceOf ('blog|blogarticlecategory');
		$record  = & CopixDAOFactory::createRecord ('blog|blogarticlecategory');
	
		$criteres = CopixDAOFactory::createSearchConditions();
		$criteres->addCondition('id_blog', '=', $id_blog);	
		$resultat = $daoCategorie->findBy($criteres);
		
		foreach($resultat as $categorie){
			$daoCategorie->delete($categorie);
		}	

		//suppression des articles, des commentaires et des liens catégories / articles
		$arIdBact = array();	
		$daoArticle = & CopixDAOFactory::getInstanceOf ('blog|blogarticle');
		$record     = & CopixDAOFactory::createRecord ('blog|blogarticle');
	
		$criteres = CopixDAOFactory::createSearchConditions();
		$criteres->addCondition('id_blog', '=', $id_blog);	
		$resultat = $daoArticle->findBy($criteres);
		
		foreach($resultat as $article){
			$daoArticle->delete($article);
		}
		
		return true;
	}


	/*
	Publication distante (autre module).
	id du blog + données -> infos sur la nouvelle données dans le blog
	*/
	function publish ($id, $data) {
	
		$articleDAO = CopixDAOFactory::create('blog|blogarticle');
		$article = CopixDAOFactory::createRecord('blog|blogarticle');
		$article->id_blog	 = $id;
		$article->name_bact	 = $data['title'];
		$article->format_bact = 'wiki';
		$article->sumary_bact	 = $data['body'];
		$article->sumary_html_bact = smarty_modifier_blog_format_article ($article->sumary_bact, $article->format_bact);
		$article->content_bact	 = '';
		$article->content_html_bact = smarty_modifier_blog_format_article ($article->content_bact, $article->format_bact);
 		$article->author_bact = 'Publication par mail...';
		$article->date_bact = CopixI18N::dateToBD(date('d/m/Y'));
		$article->time_bact = timeToBD(date('H:i'));
		$article->url_bact = killBadUrlChars($article->name_bact);
		$article->sticky_bact = 0;
		$article->is_online = 1;
		$articleDAO->insert($article);
		
		$article->url_bact = killBadUrlChars($article->id_bact.'-'.$article->name_bact);
		$articleDAO->update($article);

		/*
		if ($article->id_bact!==NULL) {	// On relie l'article à la catégorie
			$artctgDAO = CopixDAOFactory::create('blog|blogarticle_blogarticlecategory');
			$artctgDAO->deleteAndInsert($article->id_bact, array($category->id_bacg));
		}
		*/
		
		return( "yo".print_r( $article, true )."yo" );
		// $article['title']  -> titre (ou nom à défaut)
		// $article['body'] -> texte
		
		/*
		if( !isset($image['file']) || trim($image['file'])=='' 
		||  !isset($image['data']) ||      $image['data'] =='' ) {
			return false;
		}
		
		$album_dao = CopixDAOFactory::create("album|album");
		$album = $album_dao->get($id);
		if( $album==null ) {
			return false;
		}

		$ext='';
		switch( strtolower(strrchr($image['file'], ".")) ) {
			case '.jpg':
			case '.jpeg':
			case '.jpe':
				$ext="jpg";
				break;
			case '.gif':
				$ext="gif";
				break;
			case '.png':
				$ext="png";
				break;
			default:
				continue;
				break;
		}

		if( $ext != '' ) {
			$album_service = & CopixClassesFactory::Create ('album|album');

			$photo_dao = & CopixDAOFactory::create("album|photo");
			$nouvelle_photo = CopixDAOFactory::createRecord("album|photo");
			$nouvelle_photo->photo_album = $album->album_id;
			if( trim($image['title']) != '' )
				$nouvelle_photo->photo_nom = $image['title'];
			else
				$nouvelle_photo->photo_nom = $image['file'];
			$nouvelle_photo->photo_comment = '';
			$nouvelle_photo->photo_date = date("Y-m-d H:i:s");
			$nouvelle_photo->photo_ext = $ext;
			$nouvelle_photo->photo_cle = $album_service->createKey();
			$photo_dao->insert( $nouvelle_photo );
			if( $nouvelle_photo->photo_id ) {
				$path2data = realpath("static");
				$path2album = $path2data."/album/".$album->album_id."_".$album->album_cle;
				$photofile = $path2album."/".$nouvelle_photo->photo_id."_".$nouvelle_photo->photo_cle.'.'.$ext;
				$file = fopen( $photofile, 'w' );
				fwrite( $file, $image['data'] );
				fclose( $file );
			}
			
			$ok = $album_service->createThumbnails(
				$album->album_id.'_'.$album->album_cle ,
				$nouvelle_photo->photo_id.'_'.$nouvelle_photo->photo_cle ,
				$ext );
			
			if( $ok ) {
				$results = array(
					'title'     => $nouvelle_photo->photo_nom,
					'album_id'  => $album->album_id,
					'album_key' => $album->album_cle,
					'photo_id'  => $nouvelle_photo->photo_id,
					'photo_key' => $nouvelle_photo->photo_cle,
					'photo_ext' => $ext,
				);
			} else {
				$photo_dao->delete( $nouvelle_photo->photo_id );
			}
			
			return $results;
		}
		*/

		return false;
		
	}
  

  /**
   * Détermine l' "url_blog" unique d'un blog, à partir de son titre
	 * 
	 * A partir du titre d'un blog, en déduit son "url_blog". Regarde dans la BDD si un blog de même titre n'existe pas déjà pour rendre cette valeur unique (au besoin, ajoute un numéro à la fin pour le rendre unique). Ne doit être appellé qu'à la création d'un blog.
	 * 
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2006/10/23
	 * @param integer $id Id du blog
	 * @param string $titre Titre du blog
	 * @return string url_blog à stocker dans la BDD. Chaine vide si problème
   */
  function calcule_url_blog ($id, $titre) {
    $dbw  = & CopixDbFactory::getDbWidget ();
    if (strlen($titre)>97) $titre = substr($titre, 0, 97);
    //print_r("titre=$titre<br>");
    $titre = killBadUrlChars ($titre);
    $exists = true;
  	$fusible = 1;
	  while ($exists && $fusible<1000) {
  		$id_nom = $titre.(($fusible>1) ? $fusible : "");
			$sql = "SELECT id_blog FROM module_blog WHERE url_blog='".addslashes($id_nom)."'";
  		if ($id) $sql .= " AND id_blog!=$id";
		  //print_r ("sql=$sql<br>");
		  $p = $dbw->fetchAll($sql);
  		$exists = ($p) ? true : false;
		  $fusible++;
  	}
  	if (!$exists)		$res = $id_nom;
    else            $res = '';
    return $res;
  }
  
}

?>