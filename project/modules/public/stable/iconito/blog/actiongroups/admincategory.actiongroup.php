<?php
/**
* @package	copix
* @version   $Id: admincategory.actiongroup.php,v 1.7 2007-07-30 14:42:07 cbeyer Exp $
* @author	Vallat C�dric.
* @copyright 2001-2005 CopixTeam
* @link      http://copix.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

_classInclude('blog|blogauth');
_classInclude('blog|blogutils');

class ActionGroupAdminCategory extends CopixActionGroup
{
    public function beforeAction ()
    {
        _currentUser()->assertCredential ('group:[current_user]');

    }

    /**
    * Pr�paration � l'�dition d'une cat�gorie.
    */
    public function doPrepareEditCategory()
    {
        $id_blog = $this->getRequest('id_blog', null);
        $blogDAO = CopixDAOFactory::create('blog|blog');
        $blog = $blogDAO->get($id_blog);

        if ($id_blog==null){
            return CopixActionGroup::process ('genericTools|Messages::getError',
            array ('message'=>CopixI18N::get ('blog.error.param'),
            'back'=>CopixUrl::get ('blog|admin|listBlog')));
        }

        if (!BlogAuth::canMakeInBlog('ADMIN_CATEGORIES',$blog)){
            return CopixActionGroup::process ('genericTools|Messages::getError',
            array ('message'=>CopixI18N::get ('blog.error.cannotManageCategory'),
            'back'=>CopixUrl::get ('blog|admin|listBlog')));
        }

        $tpl = new CopixTpl ();

        $id_bacg = $this->getRequest('id_bacg', null);
        if($id_bacg!=null) {
            // EDITION D'UN BLOG
            $categoryDAO = CopixDAOFactory::create('blog|blogarticlecategory');
            $category = $categoryDAO->get($id_bacg);
            //$tpl->assign ('TITLE_PAGE', CopixI18N::get('blog.get.edit.category.title'));
        } else {
            // CREATION D'UN BLOG
            $category = null;
            //$tpl->assign ('TITLE_PAGE', CopixI18N::get('blog.get.create.category.title'));
        }



        $tpl->assign ('TITLE_PAGE', $blog->name_blog);
//		$menu = '<a href="'.CopixUrl::get ('blog|admin|showBlog', array("id_blog"=>$id_blog, "kind"=>1)).'">'.CopixI18N::get('blog|blog.nav.categories').'</a>';
        $menu = getBlogAdminMenu($blog, 1);
        $tpl->assign ('MENU', $menu);
        $tpl->assign ('MAIN', CopixZone::process ('EditCategory',
        array('id_blog'=>$id_blog,
        'id_bacg'=>$id_bacg,
        'category'=>$category,
        'kind'=>$this->getRequest('kind', '0')
        )));
        return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
    }


    /**
    * Validation d'une cat�gorie.
    */
    public function doValidCategory()
    {
        $id_blog = $this->getRequest('id_blog', null);
        if ($id_blog==null){
            return CopixActionGroup::process ('genericTools|Messages::getError',
            array ('message'=>CopixI18N::get ('blog.error.param'),
            'back'=>CopixUrl::get ('blog|admin|listBlog')));
        }

        if (!BlogAuth::canMakeInBlog('ADMIN_CATEGORIES',create_blog_object($id_blog))){
            return CopixActionGroup::process ('genericTools|Messages::getError',
            array ('message'=>CopixI18N::get ('blog.error.cannotManageCategory'),
            'back'=>CopixUrl::get ('blog|admin|listBlog')));
        }

        $tpl = new CopixTpl ();


        $categoryDAO = CopixDAOFactory::create('blog|blogarticlecategory');
        $id_bacg = $this->getRequest('id_bacg', null); if(strlen($id_bacg)==0) $id_bacg=null;
        if($id_bacg!=null) {
            // EDITION D'UNE CATEGORIE
            $category = $categoryDAO->get($id_bacg);
            $category->id_blog	 = $id_blog;
            $category->name_bacg = $this->getRequest('name_bacg');
            $category->url_bacg	 = $this->getRequest('url_bacg');
        if(strlen($category->url_bacg)==0) {
            $category->url_bacg = killBadUrlChars($category->name_bacg);
        }
            $tpl->assign ('TITLE_PAGE', CopixI18N::get('blog.get.edit.category.title'));
            $errors = _dao('blog|blogarticlecategory')->check($category);
            if($errors!=1) {
                // Traitement des erreurs
                $showErrors =  true;
            } else {
                // Modification dans la base
                $categoryDAO->update($category);
                return new CopixActionReturn (COPIX_AR_REDIRECT,
                CopixUrl::get ('blog|admin|showBlog', array("id_blog"=>$id_blog, "kind"=>$this->getRequest('kind', '0'))));
            }
        } else {
            // CREATION D'UNE CATEGORIE

            $category = CopixDAOFactory::createRecord('blogarticlecategory');
            $category->id_blog	 = $id_blog;
            $category->name_bacg = $this->getRequest('name_bacg');
            $category->order_bacg = $categoryDAO->getNewPos($id_blog);
        $category->url_bacg = killBadUrlChars($category->name_bacg);
        if(strlen($category->url_bacg)==0) {
            $category->url_bacg = killBadUrlChars($category->name_bacg);
        }

            $tpl->assign ('TITLE_PAGE', CopixI18N::get('blog.get.create.category.title'));
            $errors = _dao('blog|blogarticlecategory')->check($category);
            if($errors!=1) {
                // Traitement des erreurs
                $showErrors =  true;
            } else {
                // Insertion dans la base
                $categoryDAO->insert($category);
                $category->url_bacg = killBadUrlChars($category->id_bacg.'-'.$category->name_bacg);
                $categoryDAO->update($category);
                return new CopixActionReturn (COPIX_AR_REDIRECT,
                CopixUrl::get ('blog|admin|showBlog', array("id_blog"=>$id_blog, "kind"=>$this->getRequest('kind', '0'))));
            }
        }


        $tpl->assign ('MAIN', CopixZone::process ('EditCategory',
        array('id_blog'=>$id_blog,
        'id_bacg'=>$id_bacg,
        'category'=>$category,
        'errors'=>$errors,
        'showErrors'=>$showErrors,
        'kind'=>$this->getRequest('kind', '0')
        )));
        return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
    }



    /**
    * Suppression d'un blog.
    */
    public function doDeleteCategory ()
    {
        $id_bacg = $this->getRequest('id_bacg', null);
        if ($id_bacg==null){
            return CopixActionGroup::process ('genericTools|Messages::getError',
            array ('message'=>CopixI18N::get ('blog.error.param'),
            'back'=>CopixUrl::get ('blog|admin|listBlog')));
        }

        $categoryDAO = & CopixDAOFactory::create ('blog|blogarticlecategory');
        if (!$toDelete = $categoryDAO->get ($id_bacg)){
            return CopixActionGroup::process ('genericTools|Messages::getError',
            array ('message'=>CopixI18N::get ('blog.error.cannotFindBlog'),
            'back'=>CopixUrl::get ('blog|admin|listBlog')));
        }

        $id_blog = $this->getRequest('id_blog', null);
        if($this->getRequest('confirm', null)!=null) {
            $categoryDAO->delete($toDelete);
            return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('blog|admin|showBlog', array("id_blog"=>$id_blog, "kind"=>$this->getRequest('kind', '0'))));
        }

        return CopixActionGroup::process ('genericTools|messages::getConfirm',
        array ('confirm'=>CopixUrl::get ('blog|admin|deleteCategory',
        array('id_bacg'=>$id_bacg,
        'id_blog'=>$id_blog,
        'kind'=>$this->getRequest('kind', '0'),
        'confirm'=>1)),
        'cancel'=>CopixUrl::get ('blog|admin|showBlog', array("id_blog"=>$id_blog, "kind"=>$this->getRequest('kind', '0'))),
        'message'=>CopixI18N::get ('blog.messages.confirmDeleteCategory'),
        'title'=>CopixI18N::get ('blog.get.delete.category.title')));

    }


    /**
    * Moves a category up
    * @param _request('id') the article to moves up
    */
    public function doCategoryUp ()
    {
        $id_bacg = $this->getRequest('id_bacg', null);
        if ($id_bacg==null){
            return CopixActionGroup::process ('genericTools|Messages::getError',
            array ('message'=>CopixI18N::get ('blog.error.param'),
            'back'=>CopixUrl::get ('blog|admin|listBlog')));
        }
        $id_blog = $this->getRequest('id_blog', null);

        $categoryDAO = & CopixDAOFactory::create ('blog|blogarticlecategory');
        //does the menu exists ?
        if (($category = $categoryDAO->get ($id_bacg)) == false){
            return CopixActionGroup::process ('genericTools|Messages::getError',
            array ('message'=>CopixI18N::get ('blog.error.cannotFindBlog'),
            'back'=>CopixUrl::get ('blog|admin|listBlog')));
        }

        $categoryDAO->doUp ($id_blog, $category);

        return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('blog|admin|showBlog', array("id_blog"=>$id_blog, "kind"=>$this->getRequest('kind', '0'))));
    }

    /**
    * Moves a category down
    * @param _request('id') the article to moves down
    */
    public function doCategoryDown ()
    {
        $id_bacg = $this->getRequest('id_bacg', null);
        if ($id_bacg==null){
            return CopixActionGroup::process ('genericTools|Messages::getError',
            array ('message'=>CopixI18N::get ('blog.error.param'),
            'back'=>CopixUrl::get ('blog|admin|listBlog')));
        }
        $id_blog = $this->getRequest('id_blog', null);

        $categoryDAO = & CopixDAOFactory::create ('blog|blogarticlecategory');
        //does the menu exists ?
        if (($category = $categoryDAO->get ($id_bacg)) == false){
            return CopixActionGroup::process ('genericTools|Messages::getError',
            array ('message'=>CopixI18N::get ('blog.error.cannotFindBlog'),
            'back'=>CopixUrl::get ('blog|admin|listBlog')));
        }

        $categoryDAO->doDown ($id_blog, $category);

        return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('blog|admin|showBlog', array("id_blog"=>$id_blog, "kind"=>$this->getRequest('kind', '0'))));
    }



}
