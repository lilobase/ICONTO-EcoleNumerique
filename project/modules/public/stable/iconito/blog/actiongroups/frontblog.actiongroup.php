<?php
/**
* @package	copix
* @version   $Id: frontblog.actiongroup.php,v 1.30 2009-03-11 13:32:52 cbeyer Exp $
* @author	Vallat C�dric.
* @copyright 2001-2005 CopixTeam
* @link      http://copix.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
_classInclude('blog|blogauth');
_classInclude('blog|blogutils');
_classInclude('groupe|groupeservice');

class ActionGroupFrontBlog extends EnicActionGroup
{
    public function beforeAction ()
    {
        //_currentUser()->assertCredential ('group:[current_user]');
        $this->addJs('js/iconito/module_blog.js');
    }


    /**
    * Afficage de la liste des articles d'un blog.
    */
    public function processGetListArticle()
    {
        //var_dump($this);

        if (!_request('blog') && !_request('ecole')){
            return CopixActionGroup::process ('genericTools|Messages::getError',
            array ('message'=>CopixI18N::get ('blog.error.missingParameters'),
            'back'=>CopixUrl::get('||')));
        }

        //On verifie que le blog existe (on r�cup�re le blog avec son nom )
        if (_request('blog')) {
            $dao = CopixDAOFactory::create('blog|blog');
            if (!$blog = $dao->getBlogByName (_request('blog'))){
                return CopixActionGroup::process ('genericTools|Messages::getError',
                    array ('message'=>CopixI18N::get ('blog.error.unableToFindBlog'),
                    'back'=>CopixUrl::get('||')));
            }
        }


        //On verifie que le blog existe (on r�cup�re le blog l'id de l'�cole )
        if (CopixRequest::getInt('ecole')) {
            $blog = false;

            $mod = Kernel::getModEnabled ('BU_ECOLE', CopixRequest::getInt('ecole'), 'MOD_BLOG');
            if ($mod) {
                $mod = Kernel::filterModuleList ($mod, 'MOD_BLOG');
                if ($mod) {
                    if ($blog = _ioDAO('blog|blog')->getBlogById ($mod[0]->module_id)) {
                        //print_r($blog);

                    }
                }
            }
            if (!$blog)
                return CopixActionGroup::process ('genericTools|Messages::getError',
                    array ('message'=>CopixI18N::get ('blog.error.unableToFindBlog'),
                    'back'=>CopixUrl::get('||')));
        }


        // On v�rifie que le droit de lecture est pr�sent
        if (!BlogAuth::canMakeInBlog('READ',$blog)) {
            return CopixActionGroup::process ('genericTools|Messages::getError',
            array ('message'=>CopixI18N::get ('kernel|kernel.error.noRights'),
            'back'=>CopixUrl::get('||')));
        }

        //si la cat�gorie est fournie on v�rifie qu'elle existe
        if (null != ($cat = ($this->getRequest ('cat', null)))){
            $daoCat = CopixDAOFactory::create('blog|blogarticlecategory');
            if (!$cat = $daoCat->getCategoryByName ($blog->id_blog, $cat)){
                return CopixActionGroup::process ('genericTools|Messages::getError',
                array ('message'=>CopixI18N::get ('blog.error.unableToFindCat'),
                'back'=>CopixUrl::get('blog||', array('blog'=>_request('blog')))));
            }
        }

//		$menu = array();
        $parent = Kernel::getModParentInfo("MOD_BLOG", $blog->id_blog);
        $blog->parent = $parent;
/*		if ($parent['type']=='CLUB') {
            $droit = Kernel::getLevel($parent['type'], $parent['id']);
            if (GroupeService::canMakeInGroupe('VIEW_HOME', $droit))
                $menu[] = array('url'=>CopixUrl::get ('groupe||getHome', array("id"=>$parent['id'])), 'txt'=>CopixI18N::get ('blog.menuToGroup'));
        }
        if (BlogAuth::canMakeInBlog('ACCESS_ADMIN',$blog))
            $menu[] = array('url'=>CopixUrl::get ('admin|showBlog', array("id_blog"=>$blog->id_blog)), 'txt'=>CopixI18N::get ('blog.menuAdmin'));
*/
        $menu=array();
        if (BlogAuth::canMakeInBlog('ACCESS_ADMIN',$blog)) $menu = getBlogAdminMenu($blog);

        CopixHTMLHeader::addCSSLink (CopixUrl::get('blog||getBlogCss', array('id_blog'=>$blog->id_blog)));

        $tpl = new CopixTpl ();
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

    if ($blog->template)
          $MAIN = $tpl->fetch($blog->template);
    else
          $MAIN = $tpl->fetch('blog_main.tpl');

        $tpl->assign ('MAIN', $MAIN);
        $tpl->assign ('HEADER_MODE', 'compact');

        $plugStats = CopixPluginRegistry::get ("stats|stats");
        $plugStats->setParams(array('module_id'=>$blog->id_blog, 'parent_type'=>$parent['type'], 'parent_id'=>$parent['id']));

        return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
    }

    /**
    * Affichage de l'article demand� pour le blog.
    */
    public function getArticle()
    {
        if (!_request('blog')){
            return CopixActionGroup::process ('genericTools|Messages::getError',
            array ('message'=>CopixI18N::get ('blog.error.missingParameters'),
            'back'=>CopixUrl::get('')));
        }

        //On verifit que le blog existe (on r�cup�re le blog avec son nom)
        $dao = CopixDAOFactory::create('blog|blog');
        if (!$blog = $dao->getBlogByName (_request('blog'))){
            return CopixActionGroup::process ('genericTools|Messages::getError',
            array ('message'=>CopixI18N::get ('blog.error.unableToFindBlog'),
            'back'=>CopixUrl::get('')));
        }

        // On v�rifie que le droit de lecture est pr�sent
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

//		$menu = array();
        $parent = Kernel::getModParentInfo( "MOD_BLOG", $blog->id_blog);
        //print_r($parent);
        $blog->parent = $parent;
/*		if ($parent['type']=='CLUB') {
            $droit = Kernel::getLevel($parent['type'], $parent['id']);
            //print_r($droit);
            if (GroupeService::canMakeInGroupe('VIEW_HOME', $droit))
                $menu[] = array('url'=>CopixUrl::get ('groupe||getHome', array("id"=>$parent['id'])), 'txt'=>CopixI18N::get ('blog.menuToGroup'));
        }
        if (BlogAuth::canMakeInBlog('ACCESS_ADMIN',$blog))
            $menu[] = array('url'=>CopixUrl::get ('admin|showBlog', array("id_blog"=>$blog->id_blog)), 'txt'=>CopixI18N::get ('blog.menuAdmin'));
*/
        $menu=array();
        if (BlogAuth::canMakeInBlog('ACCESS_ADMIN',$blog)) $menu = getBlogAdminMenu($blog);


    CopixHTMLHeader::addCSSLink (CopixUrl::get('blog||getBlogCss', array('id_blog'=>$blog->id_blog)));

        $tpl = new CopixTpl ();
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

    if ($blog->template)
          $MAIN = $tpl->fetch($blog->template);
    else
          $MAIN = $tpl->fetch('blog_main.tpl');

        $tpl->assign ('MAIN', $MAIN);
        $tpl->assign ('HEADER_MODE', 'compact');

        $plugStats = CopixPluginRegistry::get ("stats|stats");
        $plugStats->setParams(array('module_id'=>$blog->id_blog, 'parent_type'=>$parent['type'], 'parent_id'=>$parent['id']));

        return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
    }



    /**
    * Affichage de LA PAGE demand� pour le blog.
    */
    public function getPage()
    {
        if (!_request('blog')){
            return CopixActionGroup::process ('genericTools|Messages::getError',
            array ('message'=>CopixI18N::get ('blog.error.missingParameters'),
            'back'=>CopixUrl::get('')));
        }

        //On verifit que le blog existe (on r�cup�re le blog avec son nom)
        $dao = CopixDAOFactory::create('blog|blog');
        if (!$blog = $dao->getBlogByName (_request('blog'))){
            return CopixActionGroup::process ('genericTools|Messages::getError',
            array ('message'=>CopixI18N::get ('blog.error.unableToFindBlog'),
            'back'=>CopixUrl::get('')));
        }

        // On v�rifie que le droit de lecture est pr�sent
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

//		$menu = array();
        $parent = Kernel::getModParentInfo( "MOD_BLOG", $blog->id_blog);
        //print_r($parent);
        $blog->parent = $parent;
/*
        if ($parent['type']=='CLUB') {
            $droit = Kernel::getLevel($parent['type'], $parent['id']);
            //print_r($droit);
            if (GroupeService::canMakeInGroupe('VIEW_HOME', $droit))
                $menu[] = array('url'=>CopixUrl::get ('groupe||getHome', array("id"=>$parent['id'])), 'txt'=>CopixI18N::get ('blog.menuToGroup'));
        }
        if (BlogAuth::canMakeInBlog('ACCESS_ADMIN',$blog))
            $menu[] = array('url'=>CopixUrl::get ('admin|showBlog', array("id_blog"=>$blog->id_blog)), 'txt'=>CopixI18N::get ('blog.menuAdmin'));
*/
        $menu=array();
        if (BlogAuth::canMakeInBlog('ACCESS_ADMIN',$blog)) $menu = getBlogAdminMenu($blog);

    CopixHTMLHeader::addCSSLink (CopixUrl::get('blog||getBlogCss', array('id_blog'=>$blog->id_blog)));

        $tpl = new CopixTpl ();
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

    if ($blog->template)
          $MAIN = $tpl->fetch($blog->template);
    else
          $MAIN = $tpl->fetch('blog_main.tpl');

        $tpl->assign ('MAIN', $MAIN);
        $tpl->assign ('HEADER_MODE', 'compact');

        $plugStats = CopixPluginRegistry::get ("stats|stats");
        $plugStats->setParams(array('module_id'=>$blog->id_blog, 'parent_type'=>$parent['type'], 'parent_id'=>$parent['id']));

        return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
    }


    /**
    * Affichage de LA PAGE demand� pour le blog.
    */
    public function getFluxRss()
    {
        if (!_request('blog')){
            return CopixActionGroup::process ('genericTools|Messages::getError',
            array ('message'=>CopixI18N::get ('blog.error.missingParameters'),
            'back'=>CopixUrl::get('')));
        }

        //On verifit que le blog existe (on r�cup�re le blog avec son nom)
        $dao = CopixDAOFactory::create('blog|blog');

        if (!$blog = $dao->getBlogById (_request('blog'))){
            return CopixActionGroup::process ('genericTools|Messages::getError',
            array ('message'=>CopixI18N::get ('blog.error.unableToFindBlog'),
            'back'=>CopixUrl::get('')));
        }

        // On v�rifie que le droit de lecture est pr�sent
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

//		$menu = array();
        $parent = Kernel::getModParentInfo( "MOD_BLOG", $blog->id_blog);
        //print_r($parent);
        $blog->parent = $parent;
/*
        if ($parent['type']=='CLUB') {
            $droit = Kernel::getLevel($parent['type'], $parent['id']);
            //print_r($droit);
            if (GroupeService::canMakeInGroupe('VIEW_HOME', $droit))
                $menu[] = array('url'=>CopixUrl::get ('groupe||getHome', array("id"=>$parent['id'])), 'txt'=>CopixI18N::get ('blog.menuToGroup'));
        }
        if (BlogAuth::canMakeInBlog('ACCESS_ADMIN',$blog))
            $menu[] = array('url'=>CopixUrl::get ('admin|showBlog', array("id_blog"=>$blog->id_blog)), 'txt'=>CopixI18N::get ('blog.menuAdmin'));

*/
        $menu=array();
        if (BlogAuth::canMakeInBlog('ACCESS_ADMIN',$blog)) $menu = getBlogAdminMenu($blog);

    CopixHTMLHeader::addCSSLink (CopixUrl::get('blog||getBlogCss', array('id_blog'=>$blog->id_blog)));

        $tpl = new CopixTpl ();
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

    if ($blog->template)
          $MAIN = $tpl->fetch($blog->template);
    else
          $MAIN = $tpl->fetch('blog_main.tpl');

        $tpl->assign ('MAIN', $MAIN);
        $tpl->assign ('HEADER_MODE', 'compact');

        return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
    }



    /**
    * apply updates to the edited object
    */
    public function _validFromPostProperties (& $toUpdate)
    {
        $arMaj = array ('id_bact', 'authorid_bacc', 'authorname_bacc', 'authoremail_bacc', 'authorweb_bacc', 'authorip_bacc', 'date_bacc', 'time_bacc', 'content_bacc');
        foreach ($arMaj as $var){
            if (_request($var)){
                $toUpdate->$var = _request($var);
            }
        }
    }

    /**
    * Validation d'un commentaire.
    */
    public function doValidComment()
    {
        if (Kernel::isSpam())
            return new CopixActionReturn (CopixActionReturn::HTTPCODE, CopixHTTPHeader::get404 (), "Page introuvable");

        $url_bact = _request('url_bact');

        //On verifit que le blog existe (on r�cup�re le blog avec son nom)
        $dao = CopixDAOFactory::create('blog|blog');
        if (!$blog = $dao->getBlogByName (_request('blog'))){
            return CopixActionGroup::process ('genericTools|Messages::getError',
            array ('message'=>CopixI18N::get ('blog.error.unableToFindBlog'),
            'back'=>CopixUrl::get('')));
        }

        // On v�rifie que le droit de lecture est pr�sent
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

        $tpl = new CopixTpl ();

        $commentDAO = CopixDAOFactory::create('blog|blogarticlecomment');
        $comment = CopixDAOFactory::createRecord('blog|blogarticlecomment');
        $this->_validFromPostProperties($comment);
        $comment->date_bacc = date('Ymd');
        $comment->time_bacc = date('Hi');
        $comment->is_online = ($blog->type_moderation_comments != 'POST') ? 0 : 1;
        $comment->authorip_bacc = $_SERVER["REMOTE_ADDR"];

        CopixHTMLHeader::addCSSLink (CopixUrl::get('blog||getBlogCss', array('id_blog'=>$blog->id_blog)));

        $tpl->assign ('blog', $blog);

        $errors = $commentDAO->check($comment);
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
            if ($comment->is_online == 1){
                return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('blog||showArticle', array('blog'=>urlencode($blog->url_blog), 'article'=>_request('article'))).'#comments');
                        } else {

            return CopixActionGroup::process ('genericTools|Messages::getInformation',
            array ('message'=>CopixI18N::get ('blog.comments.offline.info'),
            'continue'=>CopixUrl::get('blog|default|showArticle', array('blog'=>$blog->url_blog, 'article'=>$url_bact))));
            }

        }

//		$menu = array();
        $parent = Kernel::getModParentInfo( "MOD_BLOG", $blog->id_blog);
        //print_r($parent);
        $blog->parent = $parent;
/*
        if ($parent['type']=='CLUB') {
            $droit = Kernel::getLevel($parent['type'], $parent['id']);
            //print_r($droit);
            if (GroupeService::canMakeInGroupe('VIEW_HOME', $droit))
                $menu[] = array('url'=>CopixUrl::get ('groupe||getHome', array("id"=>$parent['id'])), 'txt'=>CopixI18N::get ('blog.menuToGroup'));
        }
        if (BlogAuth::canMakeInBlog('ACCESS_ADMIN',$blog))
            $menu[] = array('url'=>CopixUrl::get ('admin|showBlog', array("id_blog"=>$blog->id_blog)), 'txt'=>CopixI18N::get ('blog.menuAdmin'));
*/
        //print_r($menu);
        $menu=array();
        if (BlogAuth::canMakeInBlog('ACCESS_ADMIN',$blog)) $menu = getBlogAdminMenu($blog);

        $tpl->assign ('MENU', $menu);

        CopixHtmlHeader::addOthers ('<link rel="alternate" href="'.CopixUrl::get ('blog||rss', array("blog"=>$blog->url_blog)).'" type="application/rss+xml" title="'.htmlentities($blog->name_blog).'" />');

    if ($blog->template)
          $MAIN = $tpl->fetch($blog->template);
    else
          $MAIN = $tpl->fetch('blog_main.tpl');

        $tpl->assign ('MAIN', $MAIN);
        $tpl->assign ('HEADER_MODE', 'compact');

        return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
    }


    public function go ()
    {
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
    public function getBlogCss ()
    {
        $id_blog = $this->getRequest('id_blog', null);
        $res = '';
        if($id_blog!=null) {
            $blogDAO = & CopixDAOFactory::create ('blog|blog');
            if ($blog = $blogDAO->get ($id_blog)){
                //print_r($blog);
                $res = CopixZone::process ('GetBlogCss', array('blog'=>$blog));
            }
        }
        header("Content-Type: text/css");
        echo $res;
        return new CopixActionReturn (COPIX_AR_NONE, 0);

    }


   /**
     * Affichage du flux RSS d'un blog (flux sortant)
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2006/11/28
     * @param string $blog Url_blog du blog
   */
  public function getBlogRss ()
  {
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
     * @param integer $article (option) Id d'un article precis a afficher
   * @param integer $colonnes (option) Nb de colonnes. Par defaut : 1
     * @param integer $chapo (option) Si on veut afficher les chapos. Par defaut : 0
     * @param integer $hr (option) Si on veut afficher un HR entre les des articles. Par defaut : 0
   */
  public function getBlogJs ()
  {
        $blog = $this->getRequest('blog', null);
        $nb = $this->getRequest('nb', null);
        $colonnes = $this->getRequest('colonnes', null);
        $chapo = $this->getRequest('chapo', null);
        $hr = $this->getRequest('hr', null);
    $article = $this->getRequest('article', null);
    $showtitle = $this->getRequest('showtitle');
    $showdate = $this->getRequest('showdate');
    $showcategorie = $this->getRequest('showcategorie');
        if($blog!=null) {
            $blogDAO = & CopixDAOFactory::create ('blog|blog');
      if ($blog = $blogDAO->getBlogByName ($blog)) {
                $rss = CopixZone::process ('ListArticleJs', array('blog'=>$blog, 'nb'=>$nb, 'colonnes'=>$colonnes, 'chapo'=>$chapo, 'hr'=>$hr, 'article'=>$article, 'showtitle'=>$showtitle, 'showdate'=>$showdate, 'showcategorie'=>$showcategorie));
                header("Content-Type: text/html");
                echo trim($rss);
                return new CopixActionReturn (COPIX_AR_NONE, 0);
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
     * @param integer $page (option) Id d'une page precise a afficher
   * @param integer $colonnes (option) Nb de colonnes. Par defaut : 1
     * @param integer $content (option) Si on veut afficher les contenus des pages. Par defaut : 0
     * @param integer $hr (option) Si on veut afficher un HR entre les des pages. Par defaut : 0
     * @param boolean $showtitle (option) Si on veut afficher le titre des articles. Par defaut : true
     * @param integer $truncate (option) Limit de cesure du texte. Par defaut : 0 (pas de cesure)
   */
  public function getBlogJsPages ()
  {
    $blog = $this->getRequest('blog', null);
        $nb = $this->getRequest('nb', null);
        $colonnes = $this->getRequest('colonnes', null);
        $content = $this->getRequest('content', null);
        $hr = $this->getRequest('hr', null);
        $page = $this->getRequest('page', null);
    $showtitle = $this->getRequest('showtitle');
    $truncate = $this->getRequest('truncate');
        if($blog!=null) {
            $blogDAO = & CopixDAOFactory::create ('blog|blog');
      if ($blog = $blogDAO->getBlogByName ($blog)) {
                $rss = CopixZone::process ('ListPageJs', array('blog'=>$blog, 'nb'=>$nb, 'colonnes'=>$colonnes, 'content'=>$content, 'hr'=>$hr, 'page'=>$page, 'showtitle'=>$showtitle, 'truncate'=>$truncate));
                header("Content-Type: text/html");
                echo trim($rss);
                return new CopixActionReturn (COPIX_AR_NONE, 0);
            }
        }
        return CopixActionGroup::process ('genericTools|Messages::getError',
            array ('message'=>CopixI18N::get ('blog.error.unableToFindBlog'),
            'back'=>CopixUrl::get('')));
    }

    /**
    * Afficher le logo.
    */
    public function logo()
    {
        $id_blog = $this->getRequest('id_blog', null);
        if($id_blog!=null) {
            $blogDAO = & CopixDAOFactory::create ('blog|blog');
            if ($blog = $blogDAO->get ($id_blog)){
                $file = COPIX_VAR_PATH.CopixConfig::get ('blog|logoPath').$blog->logo_blog;
                //print_r("file=$file");
                if (file_exists($file)) {
                    $format_pict = strrchr($blog->logo_blog,'.');
                    header("Content-Type: image/".substr($format_pict,1));
                    readfile($file, 'r+');
                    return new CopixActionReturn (COPIX_AR_NONE, 0);
                }
            }
        }
        header("HTTP/1.0 404 Not Found");
        return new CopixActionReturn (COPIX_AR_NONE, 0);
    }

}
