<?php
/**
* @package	copix
* @version   $Id: adminrss.actiongroup.php,v 1.4 2007-07-30 14:42:07 cbeyer Exp $
* @author	Vallat Cédric.
* @copyright 2001-2005 CopixTeam
* @link      http://copix.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

_classInclude('blog|blogauth');
_classInclude('blog|blogutils');

class ActionGroupAdminRss extends CopixActionGroup
{
    public function beforeAction ()
    {
        _currentUser()->assertCredential ('group:[current_user]');

    }

    /**
    * Préparation à l'affichage de la liste des blogs.
    */
    public function doPrepareEditRss()
    {
        $id_blog = $this->getRequest('id_blog', null);
        $blogDAO = CopixDAOFactory::create('blog|blog');
        $blog = $blogDAO->get($id_blog);

        if ($id_blog==null){
            return CopixActionGroup::process ('genericTools|Messages::getError',
            array ('message'=>CopixI18N::get ('blog.error.param'),
            'back'=>CopixUrl::get ('blog|admin|listBlog')));
        }

        if (!BlogAuth::canMakeInBlog('ADMIN_RSS',$blog)){
            return CopixActionGroup::process ('genericTools|Messages::getError',
            array ('message'=>CopixI18N::get ('blog.error.cannotManageRss'),
            'back'=>CopixUrl::get ('blog|admin|listBlog')));
        }

        $tpl = new CopixTpl ();

        $id_bfrs = $this->getRequest('id_bfrs', null);
        if($id_bfrs!=null) {
            // EDITION D'UN BLOG
            $fluxRssDAO = CopixDAOFactory::create('blog|blogfluxrss');
            $fluxRss = $fluxRssDAO->get($id_bfrs);
            //$tpl->assign ('TITLE_PAGE', CopixI18N::get('blog.get.edit.link.title'));
        } else {

            // CREATION D'UN BLOG
            $fluxRss = null;
            //$tpl->assign ('TITLE_PAGE', CopixI18N::get('blog.get.create.link.title'));
        }



        $tpl->assign ('TITLE_PAGE', $blog->name_blog);
//		$menu = '<a href="'.CopixUrl::get ('blog|admin|showBlog', array("id_blog"=>$id_blog, "kind"=>6)).'">'.CopixI18N::get('blog|blog.nav.rss').'</a>';
        $menu = getBlogAdminMenu($blog, 6);
        $tpl->assign ('MENU', $menu);
        $tpl->assign ('MAIN', CopixZone::process ('EditRss',
        array('id_blog'=>$id_blog,
        'id_bfrs'=>$id_bfrs,
        'fluxRss'=>$fluxRss,
        'kind'=>$this->getRequest('kind', '0')
        )));
        return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
    }


    /**
    * Validation d'une catégorie.
    */
    public function doValidRss()
    {
        $id_blog = $this->getRequest('id_blog', null);
        if ($id_blog == null){
            return CopixActionGroup::process ('genericTools|Messages::getError',
            array ('message'=>CopixI18N::get ('blog.error.param'),
            'back'=>CopixUrl::get ('blog|admin|listBlog')));
        }

        if (!BlogAuth::canMakeInBlog('ADMIN_RSS',create_blog_object($id_blog))){
            return CopixActionGroup::process ('genericTools|Messages::getError',
            array ('message'=>CopixI18N::get ('blog.error.cannotManageRss'),
            'back'=>CopixUrl::get ('blog|admin|listBlog')));
        }

        $tpl = new CopixTpl ();

        $fluxRssDAO = CopixDAOFactory::create('blog|blogfluxrss');
        $id_bfrs = $this->getRequest('id_bfrs', null); if(strlen($id_bfrs) == 0) $id_bfrs = null;
        if($id_bfrs != null) {
            // EDITION D'UNE Link
            $fluxRss = $fluxRssDAO->get($id_bfrs);
            $fluxRss->id_blog	 = $id_blog;
            $fluxRss->name_bfrs = $this->getRequest('name_bfrs');
            $fluxRss->url_bfrs	 = $this->getRequest('url_bfrs');
            $tpl->assign ('TITLE_PAGE', CopixI18N::get('blog.get.edit.rss.title'));
            $errors = _dao('blog|blogfluxrss')->check($fluxRss);
            if($errors!=1) {
                // Traitement des erreurs
                $showErrors =  true;
            } else {
                // Modification dans la base
                $fluxRssDAO->update($fluxRss);
                return new CopixActionReturn (COPIX_AR_REDIRECT,
                CopixUrl::get ('blog|admin|showBlog', array("id_blog"=>$id_blog, "kind"=>$this->getRequest('kind', '0'))));
            }
        } else {
            // CREATION D'UNE Link
            $fluxRss = CopixDAOFactory::createRecord('blogfluxrss');
            $fluxRss->id_blog	 = $id_blog;
            $fluxRss->name_bfrs  = $this->getRequest('name_bfrs');
            $fluxRss->url_bfrs	 = $this->getRequest('url_bfrs');
            $fluxRss->order_bfrs = $fluxRssDAO->getNewPos($id_blog);
            $tpl->assign ('TITLE_PAGE', CopixI18N::get('blog.get.create.rss.title'));
            $errors = _dao('blog|blogfluxrss')->check($fluxRss);
            if($errors!=1) {
                // Traitement des erreurs
                $showErrors =  true;
            } else {
                // Insertion dans la base
                $fluxRssDAO->insert($fluxRss);
                return new CopixActionReturn (COPIX_AR_REDIRECT,
                CopixUrl::get ('blog|admin|showBlog', array("id_blog"=>$id_blog, "kind"=>$this->getRequest('kind', '0'))));
            }
        }

        $tpl->assign ('MAIN', CopixZone::process ('EditRss',
                                                        array('id_blog'=>$id_blog,
                                                                'id_brfs'=>$id_bfrs,
                                                                'fluxRss'=>$fluxRss,
                                                                'errors'=>$errors,
                                                                'showErrors'=>$showErrors,
                                                                'kind'=>$this->getRequest('kind', '0')
                                                                )));
        return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
    }



    /**
    * Suppression d'un flux.
    */
    public function doDeleteRss ()
    {
        $id_bfrs = $this->getRequest('id_bfrs', null);
        if ($id_bfrs==null){
            return CopixActionGroup::process ('genericTools|Messages::getError',
            array ('message'=>CopixI18N::get ('blog.error.param'),
            'back'=>CopixUrl::get ('blog|admin|listBlog')));
        }

        $fluxRssDAO = & CopixDAOFactory::create ('blog|blogfluxrss');
        if (!$toDelete = $fluxRssDAO->get ($id_bfrs)){
            return CopixActionGroup::process ('genericTools|Messages::getError',
            array ('message'=>CopixI18N::get ('blog.error.cannotFindBlog'),
            'back'=>CopixUrl::get ('blog|admin|listBlog')));
        }

        $id_blog = $this->getRequest('id_blog', null);
        if($this->getRequest('confirm', null)!=null) {
            $fluxRssDAO->delete($toDelete);
            return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('blog|admin|showBlog', array("id_blog"=>$id_blog, "kind"=>$this->getRequest('kind', '0'))));
        }

        return CopixActionGroup::process ('genericTools|messages::getConfirm',
        array ('confirm'=>CopixUrl::get ('blog|admin|deleteRss',
        array('id_bfrs'=>$id_bfrs,
        'id_blog'=>$id_blog,
        'kind'=>$this->getRequest('kind', '0'),
        'confirm'=>1)),
        'cancel'=>CopixUrl::get ('blog|admin|showBlog', array("id_blog"=>$id_blog, "kind"=>$this->getRequest('kind', '0'))),
        'message'=>CopixI18N::get ('blog.messages.confirmDeleteRss'),
        'title'=>CopixI18N::get ('blog.get.delete.rss.title')));

    }


    /**
    * Moves a category up
    * @param _request('id') the article to moves up
    */
    public function doRssUp ()
    {
        $id_bfrs = $this->getRequest('id_bfrs', null);
        if ($id_bfrs==null){
            return CopixActionGroup::process ('genericTools|Messages::getError',
            array ('message'=>CopixI18N::get ('blog.error.param'),
            'back'=>CopixUrl::get ('blog|admin|listBlog')));
        }
        $id_blog = $this->getRequest('id_blog', null);

        $fluxRssDAO = & CopixDAOFactory::create ('blog|blogfluxrss');
        //does the menu exists ?
        if (($fluxRss = $fluxRssDAO->get ($id_bfrs)) == false){
            return CopixActionGroup::process ('genericTools|Messages::getError',
            array ('message'=>CopixI18N::get ('blog.error.cannotFindBlog'),
            'back'=>CopixUrl::get ('blog|admin|listBlog')));
        }

        $fluxRssDAO->doUp ($id_blog, $fluxRss);

        return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('blog|admin|showBlog', array("id_blog"=>$id_blog, "kind"=>$this->getRequest('kind', '0'))));
    }

    /**
    * Moves a category down
    * @param _request('id') the article to moves down
    */
    public function doRssDown ()
    {
        $id_bfrs = $this->getRequest('id_bfrs', null);
        if ($id_bfrs==null){
            return CopixActionGroup::process ('genericTools|Messages::getError',
            array ('message'=>CopixI18N::get ('blog.error.param'),
            'back'=>CopixUrl::get ('blog|admin|listBlog')));
        }
        $id_blog = $this->getRequest('id_blog', null);

        $fluxRssDAO = & CopixDAOFactory::create ('blog|blogfluxrss');
        //does the menu exists ?
        if (($fluxRss = $fluxRssDAO->get ($id_bfrs)) == false){
            return CopixActionGroup::process ('genericTools|Messages::getError',
            array ('message'=>CopixI18N::get ('blog.error.cannotFindBlog'),
            'back'=>CopixUrl::get ('blog|admin|listBlog')));
        }

        $fluxRssDAO->doDown ($id_blog, $fluxRss);

        return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('blog|admin|showBlog', array("id_blog"=>$id_blog, "kind"=>$this->getRequest('kind', '0'))));
    }
}
