<?php
/**
* @package  copix
* @version   $Id: adminarticle.actiongroup.php,v 1.21 2008-12-15 16:53:11 cbeyer Exp $
* @author Vallat C�dric.
* @copyright 2001-2005 CopixTeam
* @link      http://copix.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

_classInclude('blog|blogauth');
_classInclude('blog|blogutils');
require_once (COPIX_UTILS_PATH.'../smarty_plugins/modifier.blog_format_article.php');

class ActionGroupAdminArticle extends CopixActionGroup
{
    public function beforeAction ()
    {
        _currentUser()->assertCredential ('group:[current_user]');

    }

  /**
    * Pr�paration � l'�dition d'un article.
    */
  public function doPrepareEditArticle()
  {
    CopixHTMLHeader::addJSLink (_resource("js/jquery/jquery.ui.datepicker-fr.js"));

        $id_blog = $this->getRequest('id_blog', null);
        $blogDAO = CopixDAOFactory::create('blog|blog');
        $blog = $blogDAO->get($id_blog);

        if ($id_blog == null) {
            return CopixActionGroup::process ('genericTools|Messages::getError',
            array ('message'=>CopixI18N::get ('blog.error.param'),
            'back'=>CopixUrl::get ('blog|admin|listBlog')));
        }

        if (!BlogAuth::canMakeInBlog('ADMIN_ARTICLES',$blog)){
            return CopixActionGroup::process ('genericTools|Messages::getError',
            array ('message'=>CopixI18N::get ('blog.error.cannotManageArticle'),
            'back'=>CopixUrl::get ('blog|admin|listBlog')));
        }

        $tpl = new CopixTpl ();

        $tabSelectCat = array();
        $id_bact = $this->getRequest('id_bact', null);
        $article = CopixDAOFactory::createRecord('blogarticle');

        // Pr�paration du filtre CATEGORIES
        $blogArticleCategoryDAO = CopixDAOFactory::create('blog|blogarticlecategory');
        $resArticleCategory = $blogArticleCategoryDAO->findAllOrder($id_blog);

        if($id_bact!=null) {
            // EDITION D'UN BILLET
            $articleDAO = CopixDAOFactory::create('blog|blogarticle');
            $article = $articleDAO->get($id_bact);
            $article->time_bact = BDToTime($article->time_bact);
            // Recherche des cat�gories correspondantes � cet article
            $artctgDAO = CopixDAOFactory::create('blog|blogarticle_blogarticlecategory');
            $tabSelectCat = $artctgDAO->findIdCategoryForArticle($article->id_bact);
            //var_dump($tabSelectCat);
            //$tpl->assign ('TITLE_PAGE', CopixI18N::get('blog.get.edit.article.title'));

            // Si l'article est en ligne, il faut les droits de modération pour le modifier.
            if ($article->is_online && !BlogAuth::canMakeInBlog('ADMIN_ARTICLE_DELETE',$blog)){
                return CopixActionGroup::process ('genericTools|Messages::getError',
                array ('message'=>CopixI18N::get ('blog.error.cannotManageArticle'),
                'back'=>CopixUrl::get ('blog|admin|listBlog')));
            }
        } else{
            // CREATION D'UN BILLET
            //$article->date_bact = date('Ymd');
            //$article->time_bact = date('H:i');
            //$tpl->assign ('TITLE_PAGE', CopixI18N::get('blog.get.create.article.title'));
            //$article = $this->_getSessionArticle();
            $article->is_online = CopixConfig::get ('blog|blog.default.default_is_online_article');
            $article->format_bact = $blog->default_format_articles;
            if (count($resArticleCategory)==1) {
                $tabSelectCat[] = $resArticleCategory[0]->id_bacg;
            }
            //print_r($article);
            //die();
            //$tabSelectCat = $article->tabSelectCat;
        }

    $tpl->assign ('BODY_ON_LOAD', "setDatePicker('#date_bact')");
        $tpl->assign ('TITLE_PAGE', $blog->name_blog);
//		$menu = '<a href="'.CopixUrl::get ('blog|admin|showBlog', array("id_blog"=>$id_blog, "kind"=>0)).'">'.CopixI18N::get('blog|blog.nav.articles').'</a>';
        $menu = getBlogAdminMenu($blog, 0);

        $tpl->assign ('MENU', $menu);



        $tabArticleCategory = array();
        foreach($resArticleCategory as $cat) {
            if(in_array($cat->id_bacg, $tabSelectCat)) $cat->selected = true;
                else $cat->selected = false;
            array_push($tabArticleCategory, $cat);
        }

        $kind = $this->getRequest('kind', '0');

        $tpl->assign ('MAIN', CopixZone::process ('EditArticle', array('id_blog'=>$id_blog,
                                                    'id_bact'=>$id_bact,
                                                    'article'=>$article,
                                                    'kind'=>$kind,
                                                    'tabArticleCategory'=>$tabArticleCategory,
                                                    )));

        return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
  }

  public function doValidEditArticle()
  {
          $this->_validFromPostProperties($article);


        // Cat�gories coch�es...
        $tabSelectCat = array();
        if(_request('tabSelectCat')) {
          $tabSelectCat = (array) _request('tabSelectCat');
        }

        $article->tabSelectCat = $tabSelectCat;
        //$this->_setSessionArticle($article);

        return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get('blog|admin|prepareEditArticle', array('kind'=>_request('kind'), 'id_blog'=>_request('id_blog'))));
  }

  /**
    * Validation d'un article.
    */
  public function doValidArticle()
  {
    CopixHTMLHeader::addJSLink (_resource("js/jquery/jquery.ui.datepicker-fr.js"));

    $id_blog = $this->getRequest('id_blog', null);
        $go = $this->getRequest('go', 'preview');
    //die ("go=$go");

    if ($id_blog==null){
      return CopixActionGroup::process ('genericTools|Messages::getError',
      array ('message'=>CopixI18N::get ('blog.error.param'),
      'back'=>CopixUrl::get ('blog|admin|listBlog')));
    }

    if (!BlogAuth::canMakeInBlog('ADMIN_ARTICLES',create_blog_object($id_blog))){
      return CopixActionGroup::process ('genericTools|Messages::getError',
      array ('message'=>CopixI18N::get ('blog.error.cannotManageCategory'),
      'back'=>CopixUrl::get ('blog|admin|listBlog')));
    }

    $tpl = new CopixTpl ();

    // On r�cup�re l'utilisateur connect�
    $user = BlogAuth::getUserInfos();

    $articleDAO = CopixDAOFactory::create('blog|blogarticle');
    // Cat�gories coch�es...
    $tabSelectCat = array();
    if(_request('tabSelectCat')) {
      $tabSelectCat = (array) _request('tabSelectCat');
    }
    $id_bact = $this->getRequest('id_bact', null);
      if(strlen($id_bact)==0) $id_bact=null;
        $showErrors = false;

    if($id_bact!=null) {
      // EDITION D'UN ARTICLE
      $article = $articleDAO->get($id_bact);
      $this->_validFromPostProperties($article);
      if (!$article->date_bact) $article->date_bact = date('d/m/Y');
      if (!$article->time_bact) $article->time_bact = date('H:i');
      $article->date_bact = CopixDateTime::dateToTimestamp($article->date_bact);
      $article->time_bact = timeToBD($article->time_bact);
      $article->author_bact = $user->userId;
      $tpl->assign ('TITLE_PAGE', CopixI18N::get('blog.get.edit.article.title'));
            //print_r($article);
      $errors = $articleDAO->check($article);
      if(count($tabSelectCat)==0) {
        $errors = array();
        array_push($errors, CopixI18N::get ('blog.error.nocategoryselect'));
      }
      if($errors!=1) {
        // Traitement des erreurs
        $showErrors =  true;
      } elseif ($go=='save') {
        // Modification dans la base
                $article->url_bact = killBadUrlChars($article->id_bact.'-'.$article->name_bact);
                $article->sumary_html_bact = smarty_modifier_blog_format_article ($article->sumary_bact, $article->format_bact);
                $article->content_html_bact = smarty_modifier_blog_format_article ($article->content_bact, $article->format_bact);
        $articleDAO->update($article);
        // Insertion dans la base blogarticle_blogarticlecategory
        $artctgDAO = CopixDAOFactory::create('blog|blogarticle_blogarticlecategory');
        $artctgDAO->deleteAndInsert($article->id_bact, $tabSelectCat);

        return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('blog|admin|showBlog', array("id_blog"=>$id_blog, "kind"=>$this->getRequest('kind', '0'))));
      }
    } else {
      // CREATION D'UN ARTICLE
      $article = CopixDAOFactory::createRecord('blogarticle');
      $this->_validFromPostProperties($article);
      if (!$article->date_bact) $article->date_bact = date('d/m/Y');
      if (!$article->time_bact) $article->time_bact = date('H:i');
      $article->date_bact = CopixDateTime::dateToTimestamp($article->date_bact);
      $article->time_bact = timeToBD($article->time_bact);
      $article->author_bact = $user->userId;


      $tpl->assign ('TITLE_PAGE', CopixI18N::get('blog.get.create.article.title'));
      $errors = $articleDAO->check($article);
      if(count($tabSelectCat)==0) {
        $errors = array();
        array_push($errors, CopixI18N::get ('blog.error.nocategoryselect'));
      }
      if($errors!=1) {
        // Traitement des erreurs
        $showErrors =  true;
      } elseif ($go=='save') {
        // Insertion dans la base
                $article->sumary_html_bact = smarty_modifier_blog_format_article ($article->sumary_bact, $article->format_bact);
                $article->content_html_bact = smarty_modifier_blog_format_article ($article->content_bact, $article->format_bact);
        $articleDAO->insert($article);
                $article->url_bact = killBadUrlChars($article->id_bact.'-'.$article->name_bact);
                $articleDAO->update($article);
        // Insertion dans la base blogarticle_blogarticlecategory
        $artctgDAO = CopixDAOFactory::create('blog|blogarticle_blogarticlecategory');
        $artctgDAO->deleteAndInsert($article->id_bact, $tabSelectCat);

        return new CopixActionReturn (COPIX_AR_REDIRECT,
        CopixUrl::get ('blog|admin|showBlog', array("id_blog"=>$id_blog, "kind"=>$this->getRequest('kind', '0'))));
      }
    }

    // Pr�paration du filtre CATEGORIES
    $blogArticleCategoryDAO = CopixDAOFactory::create('blog|blogarticlecategory');
    $resArticleCategory = $blogArticleCategoryDAO->findAllOrder($id_blog);
    $tabArticleCategory = array();
    foreach($resArticleCategory as $cat) {
      if(in_array($cat->id_bacg, $tabSelectCat)) $cat->selected = true; else $cat->selected = false;
      array_push($tabArticleCategory, $cat);
    }
    $article->time_bact = BDToTime($article->time_bact);

    $tpl->assign ('BODY_ON_LOAD', "setDatePicker('#date_bact')");
    $tpl->assign ('MAIN', CopixZone::process ('EditArticle', array('id_blog'=>$id_blog,
                                                                    'id_bact'=>$id_bact,
                                                                    'article'=>$article,
                                                                    'kind'=>$this->getRequest('kind', '0'),
                                                                    'errors'=>$errors,
                                                                    'showErrors'=>$showErrors,
                                                                    'tabArticleCategory'=>$tabArticleCategory,
                                    'preview'=>(($go=='preview') ? 1 : 0),
                                                                    )));
    return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
  }



  /**
    * apply updates to the edited object
    */
  public function _validFromPostProperties (& $toUpdate)
  {
    $arMaj = array ('id_blog', 'name_bact', 'sumary_bact', 'content_bact', 'date_bact', 'time_bact', 'author_bact', 'url_bact', 'format_bact');
    foreach ($arMaj as $var){
            if ($var == 'date_bact')
          $toUpdate->$var = Kernel::_validDateProperties(_request($var));
            else
          $toUpdate->$var = _request($var);
    }

    if(strlen($toUpdate->url_bact)==0 && strlen($toUpdate->name_bact)>0) {
        $toUpdate->url_bact = killBadUrlChars($toUpdate->name_bact);
    }
    if(_request('sticky_bact')) $toUpdate->sticky_bact = _request('sticky_bact'); else $toUpdate->sticky_bact = 0;
    if(_request('is_online')) $toUpdate->is_online = _request('is_online'); else $toUpdate->is_online = 0;
  }

  /**
    * Suppression d'un article.
    */
  public function doDeleteArticle ()
  {
    $id_bact = $this->getRequest('id_bact', null);
    $id_blog = $this->getRequest('id_blog', null);

    if ($id_bact==null) {
      return CopixActionGroup::process ('genericTools|Messages::getError',
      array ('message'=>CopixI18N::get ('blog.error.param'),
      'back'=>CopixUrl::get ('blog|admin|listBlog')));
    }

    $articleDAO = & CopixDAOFactory::create ('blog|blogarticle');
    if (!$toDelete = $articleDAO->get ($id_bact)) {
      return CopixActionGroup::process ('genericTools|Messages::getError',
      array ('message'=>CopixI18N::get ('blog.error.cannotFindBlog'),
      'back'=>CopixUrl::get ('blog|admin|listBlog')));
    }

        if (!BlogAuth::canMakeInBlog('ADMIN_ARTICLE_DELETE',create_blog_object($id_blog))) {
      return CopixActionGroup::process ('genericTools|Messages::getError',
      array ('message'=>CopixI18N::get ('kernel|kernel.error.noRights'),
      'back'=>CopixUrl::get ('blog||')));
    }


    $urlReturn = CopixUrl::get ('blog|admin|showBlog', array('id_blog'=>$id_blog,
    'kind'=>$this->getRequest('kind', '0'),
    'selectCategory'=>$this->getRequest('selectCategory', ''),
    'selectMonth'=>$this->getRequest('selectMonth', '')));

    if($this->getRequest('confirm', null)!=null) {
      $articleDAO->delete($toDelete);
      return new CopixActionReturn (COPIX_AR_REDIRECT, $urlReturn);
    }

    return CopixActionGroup::process ('genericTools|messages::getConfirm',
    array ('confirm'=>CopixUrl::get ('blog|admin|deleteArticle',
    array('id_bact'=>$id_bact,
    'id_blog'=>$id_blog,
    'kind'=>$this->getRequest('kind', '0'),
    "selectCategory"=>$this->getRequest('selectCategory', ''),
    "selectMonth"=>$this->getRequest('selectMonth', ''),
    'confirm'=>1)),
    'cancel'=>$urlReturn,
    'message'=>CopixI18N::get ('blog.messages.confirmDeleteArticle'),
    'title'=>CopixI18N::get ('blog.get.delete.article.title')));

  }


  /**
    * Mise en session des param�tres de l'article
    * @access : private.
    */
    public function _setSessionArticle ($toSet)
    {
    }


    /**
    * R�cup�ration en session des param�tres de l'article
    * @access : private.
    */
    public function _getSessionArticle ()
    {
    }
}
