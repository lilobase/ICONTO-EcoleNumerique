<?php
/**
* @package	copix
* @version   $Id: adminpage.actiongroup.php,v 1.12 2007-09-07 14:14:07 cbeyer Exp $
* @author	Vallat C�dric.
* @copyright 2001-2005 CopixTeam
* @link      http://copix.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

_classInclude('blog|blogauth');
_classInclude('blog|blogutils');
require_once (COPIX_UTILS_PATH.'../smarty_plugins/modifier.blog_format_article.php');

class ActionGroupAdminPage extends CopixActionGroup
{
    public function beforeAction ()
    {
        _currentUser()->assertCredential ('group:[current_user]');

    }

    /**
    * Pr�paration � l'�dition d'une page.
    */
    public function doPrepareEditPage()
    {
        $id_blog = $this->getRequest('id_blog', null);
        $blogDAO = CopixDAOFactory::create('blog|blog');
        $blog = $blogDAO->get($id_blog);

        if ($id_blog==null){
            return CopixActionGroup::process ('genericTools|Messages::getError',
            array ('message'=>CopixI18N::get ('blog.error.param'),
            'back'=>CopixUrl::get ('blog|admin|listBlog')));
        }

        if (!BlogAuth::canMakeInBlog('ADMIN_PAGES',$blog)){
            return CopixActionGroup::process ('genericTools|Messages::getError',
            array ('message'=>CopixI18N::get ('blog.error.cannotManagePage'),
            'back'=>CopixUrl::get ('blog|admin|listBlog')));
        }

        $tpl = new CopixTpl ();

        $id_bpge = $this->getRequest('id_bpge', null);
        $page = null;

        if($id_bpge!=null) {
            // EDITION D'UNE PAGE
            $pageDAO     = CopixDAOFactory::create('blog|blogpage');
            $page = $pageDAO->get($id_bpge);
        } else{
            $page->is_online = CopixConfig::get ('blog|blog.default.default_is_online_page');
            $page->format_bpge = $blog->default_format_articles;
        }

        $tpl->assign ('TITLE_PAGE', $blog->name_blog);
//		$menu = '<a href="'.CopixUrl::get ('blog|admin|showBlog', array("id_blog"=>$id_blog, "kind"=>5)).'">'.CopixI18N::get('blog|blog.nav.pages').'</a>';
        $menu = getBlogAdminMenu($blog, 5);
        $tpl->assign ('MENU', $menu);
        $tpl->assign ('MAIN', CopixZone::process ('EditPage', array('id_blog'=>$id_blog,
                                                                                                                                    'id_bpge'=>$id_bpge,
                                                                                                                                    'page'=>$page,
                                                                                                                                    'kind'=>$this->getRequest('kind', '0'))));
            return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
    }

    /**
       * apply updates to the edited object
       */
    public function _validFromPostProperties (& $toUpdate)
    {
        $arMaj = array ('id_blog', 'name_bpge', 'content_bpge', 'author_bpge', 'date_bpge', 'url_bpge', 'format_bpge');
        foreach ($arMaj as $var){
            if (_request($var)){
                $toUpdate->$var = _request($var);
            }
        }
    if(strlen($toUpdate->url_bpge)==0 && strlen($toUpdate->name_bpge)>0) {
        $toUpdate->url_bpge = killBadUrlChars($toUpdate->name_bpge);
    }
        if(_request('is_online')) $toUpdate->is_online = _request('is_online'); else $toUpdate->is_online = 0;
    }

    public function doValidEditPage()
    {
        $this->_validFromPostProperties($page);
        //$this->_setSessionPage($page);
        return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get('blog|admin|prepareEditPage', array('kind'=>_request('kind'), 'id_blog'=>_request('id_blog'), 'id_bpge'=>_request('id_bpge'))));
    }

    /**
    * Validation d'une page.
    */
    public function doValidPage()
    {
        $id_blog = $this->getRequest('id_blog', null);
        $go = $this->getRequest('go', 'preview');
    //die ("go=$go");

        if ($id_blog==null){
            return CopixActionGroup::process ('genericTools|Messages::getError',
            array ('message'=>CopixI18N::get ('blog.error.param'),
            'back'=>CopixUrl::get ('blog|admin|listBlog')));
        }


        if (!BlogAuth::canMakeInBlog('ADMIN_PAGES',create_blog_object($id_blog))){
            return CopixActionGroup::process ('genericTools|Messages::getError',
            array ('message'=>CopixI18N::get ('blog.error.cannotManagePage'),
            'back'=>CopixUrl::get ('blog|admin|listBlog')));
        }

        $tpl = new CopixTpl ();
        $showErrors = false;

        $pageDAO = CopixDAOFactory::create('blog|blogpage');
        $id_bpge = $this->getRequest('id_bpge', null); if(strlen($id_bpge)==0) $id_bpge=null;
        // On r�cup�re l'utilisateur connect�
        $user = BlogAuth::getUserInfos();
        if($id_bpge!=null) {
            // EDITION D'UNE PAGE
            $page = $pageDAO->get($id_bpge);
            $this->_validFromPostProperties($page);

            $tpl->assign ('TITLE_PAGE', CopixI18N::get('blog.get.edit.page.title'));
            $errors = _dao('blog|blogpage')->check($page);
            if($errors!=1) {
                // Traitement des erreurs
                $showErrors =  true;
            } elseif ($go=='save') {
                // Modification dans la base
                $page->content_html_bpge = smarty_modifier_blog_format_article ($page->content_bpge, $page->format_bpge);
                $pageDAO->update($page);
                return new CopixActionReturn (COPIX_AR_REDIRECT,
                CopixUrl::get ('blog|admin|showBlog', array("id_blog"=>$id_blog, "kind"=>$this->getRequest('kind', '0'))));
            }
        } else {
            // CREATION D'UNE PAGE
            $page = CopixDAOFactory::createRecord('blogpage');
            $this->_validFromPostProperties($page);
            $page->order_bpge = $pageDAO->getNewPos($id_blog);
            $page->date_bpge = date('Ymd');
            $page->author_bpge = $user->userId;

            $tpl->assign ('TITLE_PAGE', CopixI18N::get('blog.get.create.page.title'));
            $errors = _dao('blog|blogpage')->check($page);
            if($errors!=1) {
                // Traitement des erreurs
                $showErrors =  true;
            } elseif ($go=='save') {
                // Insertion dans la base
                $page->content_html_bpge = smarty_modifier_blog_format_article ($page->content_bpge, $page->format_bpge);
                $pageDAO->insert($page);
                $page->url_bpge = killBadUrlChars($page->id_bpge.'-'.$page->name_bpge);
                $pageDAO->update($page);

                //on vide la session
                //$this->_setSessionPage(null);
                return new CopixActionReturn (COPIX_AR_REDIRECT,
                CopixUrl::get ('blog|admin|showBlog', array("id_blog"=>$id_blog, "kind"=>$this->getRequest('kind', '0'))));
            }
        }

        $tpl->assign ('MAIN', CopixZone::process ('EditPage',
        array('id_blog'=>$id_blog,
        'id_bpge'=>$id_bpge,
        'page'=>$page,
        'errors'=>$errors,
        'showErrors'=>$showErrors,
        'kind'=>$this->getRequest('kind', '0'),
    'preview'=>(($go=='preview') ? 1 : 0),
        )));
        return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
    }



    /**
    * Suppression d'un blog.
    */
    public function doDeletePage ()
    {
        $id_bpge = $this->getRequest('id_bpge', null);
        if ($id_bpge==null){
            return CopixActionGroup::process ('genericTools|Messages::getError',
            array ('message'=>CopixI18N::get ('blog.error.param'),
            'back'=>CopixUrl::get ('blog|admin|listBlog')));
        }

        $pageDAO = & CopixDAOFactory::create ('blog|blogpage');
        if (!$toDelete = $pageDAO->get ($id_bpge)){
            return CopixActionGroup::process ('genericTools|Messages::getError',
            array ('message'=>CopixI18N::get ('blog.error.cannotFindBlog'),
            'back'=>CopixUrl::get ('blog|admin|listBlog')));
        }

        $id_blog = $this->getRequest('id_blog', null);
        if($this->getRequest('confirm', null)!=null) {
            $pageDAO->delete($toDelete);
            return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('blog|admin|showBlog', array("id_blog"=>$id_blog, "kind"=>$this->getRequest('kind', '0'))));
        }

        return CopixActionGroup::process ('genericTools|messages::getConfirm',
        array ('confirm'=>CopixUrl::get ('blog|admin|deletePage',
        array('id_bpge'=>$id_bpge,
        'id_blog'=>$id_blog,
        'kind'=>$this->getRequest('kind', '0'),
        'confirm'=>1)),
        'cancel'=>CopixUrl::get ('blog|admin|showBlog', array("id_blog"=>$id_blog, "kind"=>$this->getRequest('kind', '0'))),
        'message'=>CopixI18N::get ('blog.messages.confirmDeletePage'),
        'title'=>CopixI18N::get ('blog.get.delete.page.title')));

    }


    /**
    * Moves a page up
    * @param _request('id') the article to moves up
    */
    public function doPageUp ()
    {
        $id_bpge = $this->getRequest('id_bpge', null);
        if ($id_bpge==null){
            return CopixActionGroup::process ('genericTools|Messages::getError',
            array ('message'=>CopixI18N::get ('blog.error.param'),
            'back'=>CopixUrl::get ('blog|admin|listBlog')));
        }
        $id_blog = $this->getRequest('id_blog', null);

        $pageDAO = & CopixDAOFactory::create ('blog|blogpage');
        //does the menu exists ?
        if (($page = $pageDAO->get ($id_bpge)) == false){
            return CopixActionGroup::process ('genericTools|Messages::getError',
            array ('message'=>CopixI18N::get ('blog.error.cannotFindBlog'),
            'back'=>CopixUrl::get ('blog|admin|listBlog')));
        }

        $pageDAO->doUp ($id_blog, $page);

        return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('blog|admin|showBlog', array("id_blog"=>$id_blog, "kind"=>$this->getRequest('kind', '0'))));
    }

    /**
    * Moves a page down
    * @param _request('id') the article to moves down
    */
    public function doPageDown ()
    {
        $id_bpge = $this->getRequest('id_bpge', null);
        if ($id_bpge==null){
            return CopixActionGroup::process ('genericTools|Messages::getError',
            array ('message'=>CopixI18N::get ('blog.error.param'),
            'back'=>CopixUrl::get ('blog|admin|listBlog')));
        }
        $id_blog = $this->getRequest('id_blog', null);

        $pageDAO = & CopixDAOFactory::create ('blog|blogpage');
        //does the menu exists ?
        if (($page = $pageDAO->get ($id_bpge)) == false){
            return CopixActionGroup::process ('genericTools|Messages::getError',
            array ('message'=>CopixI18N::get ('blog.error.cannotFindBlog'),
            'back'=>CopixUrl::get ('blog|admin|listBlog')));
        }

        $pageDAO->doDown ($id_blog, $page);

        return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('blog|admin|showBlog', array("id_blog"=>$id_blog, "kind"=>$this->getRequest('kind', '0'))));
    }




    /**
    * Mise en session des param�tres de l'article
    * @access : private.
    */
    public function _setSessionPage ($toSet)
    {
    }


    /**
    * R�cup�ration en session des param�tres de l'article
    * @access : private.
    */
    public function _getSessionPage ()
    {
    }
}
