<?php
/**
* @package	copix
* @version   $Id: frontblog.actiongroup.php,v 1.30 2009-03-11 13:32:52 cbeyer Exp $
* @author	Vallat Cédric.
* @copyright 2001-2005 CopixTeam
* @link      http://copix.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
_classInclude('blog|blogauth');
_classInclude('blog|blogutils');
_classInclude('groupe|groupeservice');

class ActionGroupFrontBlog extends CopixActionGroup {


	/**
    * Afficage de la liste des articles d'un blog.
    */
	function processGetListArticle() {
		
		//var_dump($this);
		
		if (!_request('blog')){
			return CopixActionGroup::process ('genericTools|Messages::getError',
			array ('message'=>CopixI18N::get ('blog.error.missingParameters'),
			'back'=>CopixUrl::get('')));
		}

		//On verifie que le blog existe (on récupère le blog avec son nom)
		$dao = CopixDAOFactory::create('blog|blog');
		if (!$blog = $dao->getBlogByName (_request('blog'))){
			return CopixActionGroup::process ('genericTools|Messages::getError',
			array ('message'=>CopixI18N::get ('blog.error.unableToFindBlog'),
			'back'=>CopixUrl::get('')));
		}
		
		// On vérifie que le droit de lecture est présent		
		if (!BlogAuth::canMakeInBlog('READ',$blog)) {
			return CopixActionGroup::process ('genericTools|Messages::getError',
			array ('message'=>CopixI18N::get ('kernel|kernel.error.noRights'),
			'back'=>CopixUrl::get('')));
		}

		//si la catégorie est fournie on vérifie qu'elle existe
		if (null != ($cat = ($this->getRequest ('cat', null)))){
			$daoCat = CopixDAOFactory::create('blog|blogarticlecategory');
			if (!$cat = $daoCat->getCategoryByName ($cat)){
				return CopixActionGroup::process ('genericTools|Messages::getError',
				array ('message'=>CopixI18N::get ('blog.error.unableToFindCat'),
				'back'=>CopixUrl::get('blog||', array('blog'=>_request('blog')))));
			}
		}
	
		$menu = array();
		$parent = Kernel::getModParentInfo("MOD_BLOG", $blog->id_blog);
		$blog->parent = $parent;
		if ($parent['type']=='CLUB') {
			$droit = Kernel::getLevel($parent['type'], $parent['id']);
			if (GroupeService::canMakeInGroupe('VIEW_HOME', $droit))
				$menu[] = array('url'=>CopixUrl::get ('groupe||getHome', array("id"=>$parent['id'])), 'txt'=>CopixI18N::get ('blog.menuToGroup'));
		}
		if (BlogAuth::canMakeInBlog('ACCESS_ADMIN',$blog))
			$menu[] = array('url'=>CopixUrl::get ('admin|showBlog', array("id_blog"=>$blog->id_blog)), 'txt'=>CopixI18N::get ('blog.menuAdmin'));
		
		CopixHTMLHeader::addCSSLink (_resource("styles/module_blog.css"));
							
		$tpl = & new CopixTpl ();
		$tpl->assign ('TITLE_PAGE', $blog->name_blog);
		$tpl->assign ('blog', $blog);
		$tpl->assign ('MENU', $menu);
		$tpl->assign ('ListArticle' , CopixZone::process ('ListArticle' , array('blog'=>$blog, 'cat'=>$cat, 'date'=>$this->getRequest('date', null), 'critere'=>$this->getRequest('critere', null))));
		$tpl->assign ('ListLink'    , CopixZone::process ('ListLink'    , array('blog'=>$blog)));
		$tpl->assign ('ListCategory', CopixZone::process ('ListCategory', array('blog'=>$blog)));
		$tpl->assign ('ListArchive' , CopixZone::process ('ListArchive' , array('blog'=>$blog)));
		$tpl->assign ('ListPage'    , CopixZone::process ('ListPage'    , array('blog'=>$blog)));
		$tpl->assign ('ListSearch'  , CopixZone::process ('ListSearch'  , array('blog'=>$blog)));
		$tpl->assign ('ListFluxRss' , CopixZone::process ('ListFluxRss' , array('blog'=>$blog)));
		
		CopixHtmlHeader::addOthers ('<link rel="alternate" href="'.CopixUrl::get ('blog||rss', array("blog"=>$blog->url_blog)).'" type="application/rss+xml" title="'.htmlentities($blog->name_blog).'" />');
		$MAIN = $tpl->fetch('blog_main.tpl');
		
		$tpl->assign ('MAIN', $MAIN);
		$tpl->assign ('HEADER_MODE', 'compact');

		$plugStats = CopixPluginRegistry::get ("stats|stats");
		$plugStats->setParams(array('module_id'=>$blog->id_blog, 'parent_type'=>$parent['type'], 'parent_id'=>$parent['id']));

		if (1)
			return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
		else
			return new CopixActionReturn (COPIX_AR_DISPLAY_IN, $tpl, '|main_public.tpl');
	}

	/**
    * Affichage de l'article demandé pour le blog.
    */
	function getArticle() {		
		
		if (!_request('blog')){
			return CopixActionGroup::process ('genericTools|Messages::getError',
			array ('message'=>CopixI18N::get ('blog.error.missingParameters'),
			'back'=>CopixUrl::get('')));
		}

		//On verifit que le blog existe (on récupère le blog avec son nom)
		$dao = CopixDAOFactory::create('blog|blog');
		if (!$blog = $dao->getBlogByName (_request('blog'))){
			return CopixActionGroup::process ('genericTools|Messages::getError',
			array ('message'=>CopixI18N::get ('blog.error.unableToFindBlog'),
			'back'=>CopixUrl::get('')));
		}

		// On vérifie que le droit de lecture est présent		
		if (!BlogAuth::canMakeInBlog('READ',$blog)) {
			return CopixActionGroup::process ('genericTools|Messages::getError',
			array ('message'=>CopixI18N::get ('kernel|kernel.error.noRights'),
			'back'=>CopixUrl::get('')));
		}
		
		if (!_request('article')){
			return CopixActionGroup::process ('genericTools|Messages::getError',
			array ('message'=>CopixI18N::get ('blog.error.missingParameters'),
			'back'=>CopixUrl::get('blog||', array('blog'=>_request('blog')))));
		}
		
		$menu = array();
		$parent = Kernel::getModParentInfo( "MOD_BLOG", $blog->id_blog);
		//print_r($parent);
		$blog->parent = $parent;
		if ($parent['type']=='CLUB') {
			$droit = Kernel::getLevel($parent['type'], $parent['id']);
			//print_r($droit);
			if (GroupeService::canMakeInGroupe('VIEW_HOME', $droit))
				$menu[] = array('url'=>CopixUrl::get ('groupe||getHome', array("id"=>$parent['id'])), 'txt'=>CopixI18N::get ('blog.menuToGroup'));
		}
		if (BlogAuth::canMakeInBlog('ACCESS_ADMIN',$blog))
			$menu[] = array('url'=>CopixUrl::get ('admin|showBlog', array("id_blog"=>$blog->id_blog)), 'txt'=>CopixI18N::get ('blog.menuAdmin'));

		CopixHTMLHeader::addCSSLink (_resource("styles/module_blog.css"));
		
		$tpl = & new CopixTpl ();
		$tpl->assign ('blog', $blog);
		$tpl->assign ('MENU', $menu);
		$zoneArticle = CopixZone::process ('ShowArticle', array('blog'=>$blog, 'article'=>_request('article')));
		list ($title,$article) = explode ("{/}",$zoneArticle);
		$tpl->assign ('TITLE_PAGE', $title.' - '.$blog->name_blog);
		$tpl->assign ('Article', $article);
		$tpl->assign ('ListLink', CopixZone::process ('ListLink', array('blog'=>$blog)));
		$tpl->assign ('ListCategory', CopixZone::process ('ListCategory', array('blog'=>$blog)));
		$tpl->assign ('ListArchive', CopixZone::process ('ListArchive', array('blog'=>$blog)));
		$tpl->assign ('ListPage', CopixZone::process ('ListPage', array('blog'=>$blog)));
		$tpl->assign ('ListSearch', CopixZone::process ('ListSearch', array('blog'=>$blog)));
		
		CopixHtmlHeader::addOthers ('<link rel="alternate" href="'.CopixUrl::get ('blog||rss', array("blog"=>$blog->url_blog)).'" type="application/rss+xml" title="'.htmlentities($blog->name_blog).'" />');
		$MAIN = $tpl->fetch('blog_main.tpl');

		$tpl->assign ('MAIN', $MAIN);
		$tpl->assign ('HEADER_MODE', 'compact');
		
		$plugStats = CopixPluginRegistry::get ("stats|stats");
		$plugStats->setParams(array('module_id'=>$blog->id_blog, 'parent_type'=>$parent['type'], 'parent_id'=>$parent['id']));

		if (1)
			return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
		else
			return new CopixActionReturn (COPIX_AR_DISPLAY_IN, $tpl, '|main_public.tpl');
	}



	/**
    * Affichage de LA PAGE demandé pour le blog.
    */
	function getPage() {

		if (!_request('blog')){
			return CopixActionGroup::process ('genericTools|Messages::getError',
			array ('message'=>CopixI18N::get ('blog.error.missingParameters'),
			'back'=>CopixUrl::get('')));
		}

		//On verifit que le blog existe (on récupère le blog avec son nom)
		$dao = CopixDAOFactory::create('blog|blog');
		if (!$blog = $dao->getBlogByName (_request('blog'))){
			return CopixActionGroup::process ('genericTools|Messages::getError',
			array ('message'=>CopixI18N::get ('blog.error.unableToFindBlog'),
			'back'=>CopixUrl::get('')));
		}

		// On vérifie que le droit de lecture est présent		
		if (!BlogAuth::canMakeInBlog('READ',$blog)) {
			return CopixActionGroup::process ('genericTools|Messages::getError',
			array ('message'=>CopixI18N::get ('kernel|kernel.error.noRights'),
			'back'=>CopixUrl::get('')));
		}
		
		if (!_request('page')){
			return CopixActionGroup::process ('genericTools|Messages::getError',
			array ('message'=>CopixI18N::get ('blog.error.missingParameters'),
			'back'=>CopixUrl::get('blog||', array('blog'=>_request('blog')))));
		}

		$menu = array();
		$parent = Kernel::getModParentInfo( "MOD_BLOG", $blog->id_blog);
		//print_r($parent);
		$blog->parent = $parent;
		if ($parent['type']=='CLUB') {
			$droit = Kernel::getLevel($parent['type'], $parent['id']);
			//print_r($droit);
			if (GroupeService::canMakeInGroupe('VIEW_HOME', $droit))
				$menu[] = array('url'=>CopixUrl::get ('groupe||getHome', array("id"=>$parent['id'])), 'txt'=>CopixI18N::get ('blog.menuToGroup'));
		}
		if (BlogAuth::canMakeInBlog('ACCESS_ADMIN',$blog))
			$menu[] = array('url'=>CopixUrl::get ('admin|showBlog', array("id_blog"=>$blog->id_blog)), 'txt'=>CopixI18N::get ('blog.menuAdmin'));

		CopixHTMLHeader::addCSSLink (_resource("styles/module_blog.css"));
		
		$tpl = & new CopixTpl ();
		$tpl->assign ('TITLE_PAGE', $blog->name_blog);
		$tpl->assign ('blog', $blog);
		$tpl->assign ('MENU', $menu);
		$tpl->assign ('Page'        , CopixZone::process ('ShowPage'    , array('blog'=>$blog, 'page'=>_request('page'))));
		$tpl->assign ('ListLink'    , CopixZone::process ('ListLink'    , array('blog'=>$blog)));
		$tpl->assign ('ListCategory', CopixZone::process ('ListCategory', array('blog'=>$blog)));
		$tpl->assign ('ListArchive' , CopixZone::process ('ListArchive' , array('blog'=>$blog)));
		$tpl->assign ('ListPage'    , CopixZone::process ('ListPage'    , array('blog'=>$blog)));
		$tpl->assign ('ListSearch'  , CopixZone::process ('ListSearch'  , array('blog'=>$blog)));
		$tpl->assign ('ListFluxRss' , CopixZone::process ('ListFluxRss' , array('blog'=>$blog)));
		
		CopixHtmlHeader::addOthers ('<link rel="alternate" href="'.CopixUrl::get ('blog||rss', array("blog"=>$blog->url_blog)).'" type="application/rss+xml" title="'.htmlentities($blog->name_blog).'" />');
		$MAIN = $tpl->fetch('blog_main.tpl');

		$tpl->assign ('MAIN', $MAIN);
		$tpl->assign ('HEADER_MODE', 'compact');

		$plugStats = CopixPluginRegistry::get ("stats|stats");
		$plugStats->setParams(array('module_id'=>$blog->id_blog, 'parent_type'=>$parent['type'], 'parent_id'=>$parent['id']));

		if (1)
			return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
		else
			return new CopixActionReturn (COPIX_AR_DISPLAY_IN, $tpl, '|main_public.tpl');
	}

	
	/**
    * Affichage de LA PAGE demandé pour le blog.
    */
	function getFluxRss() {

		if (!_request('blog')){
			return CopixActionGroup::process ('genericTools|Messages::getError',
			array ('message'=>CopixI18N::get ('blog.error.missingParameters'),
			'back'=>CopixUrl::get('')));
		}

		//On verifit que le blog existe (on récupère le blog avec son nom)
		$dao = CopixDAOFactory::create('blog|blog');

		if (!$blog = $dao->getBlogById (_request('blog'))){
			return CopixActionGroup::process ('genericTools|Messages::getError',
			array ('message'=>CopixI18N::get ('blog.error.unableToFindBlog'),
			'back'=>CopixUrl::get('')));
		}

		// On vérifie que le droit de lecture est présent		
		if (!BlogAuth::canMakeInBlog('READ',$blog)) {
			return CopixActionGroup::process ('genericTools|Messages::getError',
			array ('message'=>CopixI18N::get ('kernel|kernel.error.noRights'),
			'back'=>CopixUrl::get('')));
		}
		
		if (!_request('id_bfrs')){
			return CopixActionGroup::process ('genericTools|Messages::getError',
			array ('message'=>CopixI18N::get ('blog.error.missingParameters'),
			'back'=>CopixUrl::get('blog||', array('blog'=>_request('blog')))));
		}

		$menu = array();
		$parent = Kernel::getModParentInfo( "MOD_BLOG", $blog->id_blog);
		//print_r($parent);
		$blog->parent = $parent;
		if ($parent['type']=='CLUB') {
			$droit = Kernel::getLevel($parent['type'], $parent['id']);
			//print_r($droit);
			if (GroupeService::canMakeInGroupe('VIEW_HOME', $droit))
				$menu[] = array('url'=>CopixUrl::get ('groupe||getHome', array("id"=>$parent['id'])), 'txt'=>CopixI18N::get ('blog.menuToGroup'));
		}
		if (BlogAuth::canMakeInBlog('ACCESS_ADMIN',$blog))
			$menu[] = array('url'=>CopixUrl::get ('admin|showBlog', array("id_blog"=>$blog->id_blog)), 'txt'=>CopixI18N::get ('blog.menuAdmin'));
	
		CopixHTMLHeader::addCSSLink (_resource("styles/module_blog.css"));
		
		$tpl = & new CopixTpl ();
		$tpl->assign ('TITLE_PAGE', $blog->name_blog);
		$tpl->assign ('blog', $blog);
		$tpl->assign ('MENU', $menu);
		$tpl->assign ('Flux'        , CopixZone::process ('ShowFluxRss' , array('blog'=>$blog, 'id_flux'=>_request('id_bfrs'))));
		$tpl->assign ('ListLink'    , CopixZone::process ('ListLink'    , array('blog'=>$blog)));
		$tpl->assign ('ListCategory', CopixZone::process ('ListCategory', array('blog'=>$blog)));
		$tpl->assign ('ListArchive' , CopixZone::process ('ListArchive' , array('blog'=>$blog)));
		$tpl->assign ('ListPage'    , CopixZone::process ('ListPage'    , array('blog'=>$blog)));
		$tpl->assign ('ListSearch'  , CopixZone::process ('ListSearch'  , array('blog'=>$blog)));
		$tpl->assign ('ListFluxRss' , CopixZone::process ('ListFluxRss' , array('blog'=>$blog)));
		
		CopixHtmlHeader::addOthers ('<link rel="alternate" href="'.CopixUrl::get ('blog||rss', array("blog"=>$blog->url_blog)).'" type="application/rss+xml" title="'.htmlentities($blog->name_blog).'" />');
		$MAIN = $tpl->fetch('blog_main.tpl');

		$tpl->assign ('MAIN', $MAIN);
		$tpl->assign ('HEADER_MODE', 'compact');

		if (1)
			return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
		else
			return new CopixActionReturn (COPIX_AR_DISPLAY_IN, $tpl, '|main_public.tpl');
	} 
	 
	 
	
	/**
    * apply updates to the edited object
    */
	function _validFromPostProperties (& $toUpdate){
		$arMaj = array ('id_bact', 'authorid_bacc', 'authorname_bacc', 'authoremail_bacc', 'authorweb_bacc', 'authorip_bacc', 'date_bacc', 'time_bacc', 'content_bacc');
		foreach ($arMaj as $var){
			if (isset ($this->vars[$var])){
				$toUpdate->$var = $this->vars[$var];
			}
		}
	}

	/**
    * Validation d'un commentaire.
    */
	function doValidComment() {
		
		$url_bact = _request('url_bact');
		
		//On verifit que le blog existe (on récupère le blog avec son nom)
		$dao = CopixDAOFactory::create('blog|blog');
		if (!$blog = $dao->getBlogByName (_request('blog'))){
			return CopixActionGroup::process ('genericTools|Messages::getError',
			array ('message'=>CopixI18N::get ('blog.error.unableToFindBlog'),
			'back'=>CopixUrl::get('')));
		}

		// On vérifie que le droit de lecture est présent		
		if (!BlogAuth::canMakeInBlog('READ',$blog)) {
			return CopixActionGroup::process ('genericTools|Messages::getError',
			array ('message'=>CopixI18N::get ('kernel|kernel.error.noRights'),
			'back'=>CopixUrl::get('')));
		}

		if (!$blog->has_comments_activated){
			return CopixActionGroup::process ('genericTools|Messages::getError',
			array ('message'=>CopixI18N::get ('blog.add.comment.closed'),
			'back'=>CopixUrl::get ('', array('blog'=>_request('blog')))));
		}

		$id_bact = $this->getRequest('id_bact', null);
		if (!BlogAuth::canComment($blog->id_blog)){
			return CopixActionGroup::process ('genericTools|Messages::getError',
			array ('message'=>CopixI18N::get ('blog.error.cannotManageComment'),
			'back'=>CopixUrl::get ('', array('blog'=>_request('blog')))));
		}
    
		$tpl = & new CopixTpl ();

		$commentDAO = CopixDAOFactory::create('blog|blogarticlecomment');
		$comment = CopixDAOFactory::createRecord('blogarticlecomment');
		$this->_validFromPostProperties($comment);
		$comment->date_bacc = date('Ymd');
		$comment->time_bacc = date('Hi');
		$comment->is_online = ($blog->type_moderation_comments != 'POST') ? 0 : 1;
		$comment->authorip_bacc = $_SERVER["REMOTE_ADDR"];

		CopixHTMLHeader::addCSSLink (_resource("styles/module_blog.css"));

		$tpl->assign ('blog', $blog);
		
		$errors = $comment->check();
		//print_r($comment);
		$showErrors =  false;
		if($errors!=1) {
			// Traitement des erreurs
			$showErrors =  true;
		} else {
			// Insertion dans la base
			$commentDAO->insert($comment);
		}
		
		$zoneArticle = CopixZone::process ('ShowArticle', array( 'blog'=>$blog, 'article'=>$this->getRequest('article', ''), 'errors'=>$errors, 'showErrors'=>$showErrors, 'comment'=>$comment));
		list ($title,$article) = explode ("{/}",$zoneArticle);
		$tpl->assign ('TITLE_PAGE', $title.' - '.$blog->name_blog);
		$tpl->assign ('Article', $article);
		$tpl->assign ('ListLink', CopixZone::process ('ListLink', array('blog'=>$blog)));
		$tpl->assign ('ListCategory', CopixZone::process ('ListCategory', array('blog'=>$blog)));
		$tpl->assign ('ListArchive', CopixZone::process ('ListArchive', array('blog'=>$blog)));
		$tpl->assign ('ListPage', CopixZone::process ('ListPage', array('blog'=>$blog)));
		$tpl->assign ('ListSearch', CopixZone::process ('ListSearch', array('blog'=>$blog)));
		
		
		if (!$showErrors) {
			if ($comment->is_online == 1)
				return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('blog||showArticle', array('blog'=>urlencode($blog->url_blog), 'article'=>_request('article'))).'#comments');
			else {
	//		print_r($blog);
				return CopixActionGroup::process ('genericTools|Messages::getInfo',
			array ('message'=>CopixI18N::get ('blog.comments.offline.info'),
			'back'=>CopixUrl::get('blog||showArticle', array('blog'=>$blog->url_blog, 'article'=>$url_bact))));
			}
					
		}
		
		$menu = array();
		$parent = Kernel::getModParentInfo( "MOD_BLOG", $blog->id_blog);
		//print_r($parent);
		$blog->parent = $parent;
		if ($parent['type']=='CLUB') {
			$droit = Kernel::getLevel($parent['type'], $parent['id']);
			//print_r($droit);
			if (GroupeService::canMakeInGroupe('VIEW_HOME', $droit))
				$menu[] = array('url'=>CopixUrl::get ('groupe||getHome', array("id"=>$parent['id'])), 'txt'=>CopixI18N::get ('blog.menuToGroup'));
		}
		if (BlogAuth::canMakeInBlog('ACCESS_ADMIN',$blog))
			$menu[] = array('url'=>CopixUrl::get ('admin|showBlog', array("id_blog"=>$blog->id_blog)), 'txt'=>CopixI18N::get ('blog.menuAdmin'));
		//print_r($menu);
		$tpl->assign ('MENU', $menu);
		
		CopixHtmlHeader::addOthers ('<link rel="alternate" href="'.CopixUrl::get ('blog||rss', array("blog"=>$blog->url_blog)).'" type="application/rss+xml" title="'.htmlentities($blog->name_blog).'" />');
		$MAIN = $tpl->fetch('blog_main.tpl');
		
		$tpl->assign ('MAIN', $MAIN);
		$tpl->assign ('HEADER_MODE', 'compact');
		
		if (1)
			return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
		else
			return new CopixActionReturn (COPIX_AR_DISPLAY_IN, $tpl, '|main_public.tpl');
	}   


	function go () {
		$id = $this->getRequest('id', null);
		$dao = CopixDAOFactory::create('blog|blog');
		if ($id && $blog=$dao->get($id)) {
			return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('blog||listArticle', array('blog'=>$blog->url_blog) ));
		}
		return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('||') );
	}
	
	
   /**
   * Renvoie la feuille de style d'un blog
	 * 
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2006/03/02
	 * @param integer $id_blog Id du blog
   */
	function getBlogCss () {
	
		$id_blog = $this->getRequest('id_blog', null);
		if($id_blog!=null) {
			$blogDAO = & CopixDAOFactory::create ('blog|blog');
			if ($blog = $blogDAO->get ($id_blog)){
				//print_r($blog);
				$css = CopixZone::process ('GetBlogCss', array('blog'=>$blog));
				return new CopixActionReturn (COPIX_AR_BINARY_CONTENT, $css, 'text/css');
			}
		}
		header("HTTP/1.0 404 Not Found");
		return new CopixActionReturn (COPIX_AR_NONE, 0);
		
	}
	
	/**
	* @since 28/08/2006
	* @author Audrey Vassal <avassal@sqli.com>
	*/
  /*
	function getRss() {
		$idFlux = $this->getRequest('id_flux_rss',null,true);		
		$dao = CopixDAOFactory::create('FluxRss');
		$urlFlux = $dao->get($idFlux);
		// récupération de l'url RSS
		
		
		// appel du service
		if($urlFlux->url_bfrs != null) {
			$arFeeds = FluxRSSServices::getRss($urlFlux->url_bfrs); 	
		}
		else {
			$arFeeds = array();
		}
		
		$tpl = & new CopixTpl();
      	$tpl->assign('arFeeds',$arFeeds);		
			
		return new CopixActionReturn(COPIX_AR_DISPLAY,$tpl);
	}
	*/

   /**
	 * Affichage du flux RSS d'un blog (flux sortant)
	 * 
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2006/11/28
	 * @param string $blog Url_blog du blog
   */
  function getBlogRss () {
		$blog = $this->getRequest('blog', null);
		if($blog!=null) {
			$blogDAO = & CopixDAOFactory::create ('blog|blog');
      if ($blog = $blogDAO->getBlogByName ($blog)) {
				$rss = CopixZone::process ('ListArticleRss', array('blog'=>$blog));
				return _arContent ($rss, array ('content-type'=>CopixMIMETypes::getFromExtension ('xml')));
			}
		}
		return CopixActionGroup::process ('genericTools|Messages::getError',
			array ('message'=>CopixI18N::get ('blog.error.unableToFindBlog'),
			'back'=>CopixUrl::get('')));
	}

   /**
	 * Affichage des derniers articles d'un blog au format Javascript
	 * 
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2006/12/19
	 * @param string $blog Url_blog du blog
	 * @param integer $nb (option) Nombre d'articles a afficher. Si null, prend nbJsArticles dans la conf
   * @param integer $colonnes (option) Nb de colonnes. Par defaut : 1
	 * @param integer $chapo (option) Si on veut afficher les chapos. Par defaut : 0
	 * @param integer $hr (option) Si on veut afficher un HR entre les des articles. Par defaut : 0
   */
  function getBlogJs () {
		$blog = $this->getRequest('blog', null);
		$nb = $this->getRequest('nb', null);
		$colonnes = $this->getRequest('colonnes', null);
		$chapo = $this->getRequest('chapo', null);
		$hr = $this->getRequest('hr', null);
		if($blog!=null) {
			$blogDAO = & CopixDAOFactory::create ('blog|blog');
      if ($blog = $blogDAO->getBlogByName ($blog)) {
				$rss = CopixZone::process ('ListArticleJs', array('blog'=>$blog, 'nb'=>$nb, 'colonnes'=>$colonnes, 'chapo'=>$chapo, 'hr'=>$hr));
				return new CopixActionReturn (COPIX_AR_BINARY_CONTENT, trim($rss), 'text/html');
			}
		}
		return CopixActionGroup::process ('genericTools|Messages::getError',
			array ('message'=>CopixI18N::get ('blog.error.unableToFindBlog'),
			'back'=>CopixUrl::get('')));
	}


   /**
	 * Affichage des dernieres pages d'un blog au format Javascript
	 * 
	 * @author Christophe Beyer <cbeyer@cap-tic.fr>
	 * @since 2009/01/23
	 * @param string $blog Url_blog du blog
	 * @param integer $nb (option) Nombre de pages a afficher. Si null, prend nbJsArticles dans la conf
   * @param integer $colonnes (option) Nb de colonnes. Par defaut : 1
	 * @param integer $content (option) Si on veut afficher les contenus des pages. Par defaut : 0
	 * @param integer $hr (option) Si on veut afficher un HR entre les des pages. Par defaut : 0
   */
  function getBlogJsPages () {
    $blog = $this->getRequest('blog', null);
		$nb = $this->getRequest('nb', null);
		$colonnes = $this->getRequest('colonnes', null);
		$content = $this->getRequest('content', null);
		$hr = $this->getRequest('hr', null);
		if($blog!=null) {
			$blogDAO = & CopixDAOFactory::create ('blog|blog');
      if ($blog = $blogDAO->getBlogByName ($blog)) {
				$rss = CopixZone::process ('ListPageJs', array('blog'=>$blog, 'nb'=>$nb, 'colonnes'=>$colonnes, 'content'=>$content, 'hr'=>$hr));
				return new CopixActionReturn (COPIX_AR_BINARY_CONTENT, trim($rss), 'text/html');
			}
		}
		return CopixActionGroup::process ('genericTools|Messages::getError',
			array ('message'=>CopixI18N::get ('blog.error.unableToFindBlog'),
			'back'=>CopixUrl::get('')));
	}

}
?>
