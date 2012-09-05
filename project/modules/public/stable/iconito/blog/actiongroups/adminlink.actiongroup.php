<?php
/**
* @package	copix
* @version   $Id: adminlink.actiongroup.php,v 1.7 2007-07-30 14:42:07 cbeyer Exp $
* @author	Vallat Cédric.
* @copyright 2001-2005 CopixTeam
* @link      http://copix.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

_classInclude('blog|blogauth');
_classInclude('blog|blogutils');

class ActionGroupAdminLink extends CopixActionGroup
{
    public function beforeAction ()
    {
        _currentUser()->assertCredential ('group:[current_user]');

    }

    /**
    * Préparation à l'affichage de la liste des blogs.
    */
    public function doPrepareEditLink()
    {
        $id_blog = $this->getRequest('id_blog', null);
        $blogDAO = CopixDAOFactory::create('blog|blog');
        $blog = $blogDAO->get($id_blog);

        if ($id_blog==null){
            return CopixActionGroup::process ('genericTools|Messages::getError',
            array ('message'=>CopixI18N::get ('blog.error.param'),
            'back'=>CopixUrl::get ('blog|admin|listBlog')));
        }

        if (!BlogAuth::canMakeInBlog('ADMIN_LIENS',$blog)){
            return CopixActionGroup::process ('genericTools|Messages::getError',
            array ('message'=>CopixI18N::get ('blog.error.cannotManageLink'),
            'back'=>CopixUrl::get ('blog|admin|listBlog')));
        }

        $tpl = new CopixTpl ();

        $id_blnk = $this->getRequest('id_blnk', null);
        if($id_blnk!=null) {
            // EDITION D'UN BLOG
            $linkDAO = CopixDAOFactory::create('blog|bloglink');
            $link = $linkDAO->get($id_blnk);
            //$tpl->assign ('TITLE_PAGE', CopixI18N::get('blog.get.edit.link.title'));
        } else {
            // CREATION D'UN BLOG
            $link = null;
            //$tpl->assign ('TITLE_PAGE', CopixI18N::get('blog.get.create.link.title'));
        }



        $tpl->assign ('TITLE_PAGE', $blog->name_blog);
//		$menu = '<a href="'.CopixUrl::get ('blog|admin|showBlog', array("id_blog"=>$id_blog, "kind"=>2)).'">'.CopixI18N::get('blog|blog.nav.links').'</a>';
        $menu = getBlogAdminMenu($blog, 2);
        $tpl->assign ('MENU', $menu);
        $tpl->assign ('MAIN', CopixZone::process ('EditLink',
        array('id_blog'=>$id_blog,
        'id_blnk'=>$id_blnk,
        'link'=>$link,
        'kind'=>$this->getRequest('kind', '0')
        )));
        return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
    }


    /**
    * Validation d'une catégorie.
    */
    public function doValidLink()
    {
        $id_blog = $this->getRequest('id_blog', null);
        if ($id_blog==null){
            return CopixActionGroup::process ('genericTools|Messages::getError',
            array ('message'=>CopixI18N::get ('blog.error.param'),
            'back'=>CopixUrl::get ('blog|admin|listBlog')));
        }

        if (!BlogAuth::canMakeInBlog('ADMIN_LIENS',create_blog_object($id_blog))){
            return CopixActionGroup::process ('genericTools|Messages::getError',
            array ('message'=>CopixI18N::get ('blog.error.cannotManageLink'),
            'back'=>CopixUrl::get ('blog|admin|listBlog')));
        }

        $tpl = new CopixTpl ();

        $linkDAO = CopixDAOFactory::create('blog|bloglink');
        $id_blnk = $this->getRequest('id_blnk', null); if(strlen($id_blnk)==0) $id_blnk=null;
        if($id_blnk!=null) {
            // EDITION D'UNE Link
            $link = $linkDAO->get($id_blnk);
            $link->id_blog	 = $id_blog;
            $link->name_blnk = $this->getRequest('name_blnk');
            $link->url_blnk	 = $this->getRequest('url_blnk');
            $tpl->assign ('TITLE_PAGE', CopixI18N::get('blog.get.edit.link.title'));
            $errors = _dao('blog|bloglink')->check($link);
            if($errors!=1) {
                // Traitement des erreurs
                $showErrors =  true;
            } else {
                // Modification dans la base
                $linkDAO->update($link);
                return new CopixActionReturn (COPIX_AR_REDIRECT,
                CopixUrl::get ('blog|admin|showBlog', array("id_blog"=>$id_blog, "kind"=>$this->getRequest('kind', '0'))));
            }
        } else {
            // CREATION D'UNE Link
            $link = CopixDAOFactory::createRecord('bloglink');
            $link->id_blog	 = $id_blog;
            $link->name_blnk = $this->getRequest('name_blnk');
            $link->url_blnk	 = $this->getRequest('url_blnk');
            $link->order_blnk = $linkDAO->getNewPos($id_blog);
            $tpl->assign ('TITLE_PAGE', CopixI18N::get('blog.get.create.link.title'));
            $errors = _dao('blog|bloglink')->check($link);
            if($errors!=1) {
                // Traitement des erreurs
                $showErrors =  true;
            } else {
                // Insertion dans la base
                $linkDAO->insert($link);
                return new CopixActionReturn (COPIX_AR_REDIRECT,
                CopixUrl::get ('blog|admin|showBlog', array("id_blog"=>$id_blog, "kind"=>$this->getRequest('kind', '0'))));
            }
        }

        $tpl->assign ('MAIN', CopixZone::process ('EditLink',
        array('id_blog'=>$id_blog,
        'id_blnk'=>$id_blnk,
        'link'=>$link,
        'errors'=>$errors,
        'showErrors'=>$showErrors,
        'kind'=>$this->getRequest('kind', '0')
        )));
        return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
    }



    /**
    * Suppression d'un blog.
    */
    public function doDeleteLink ()
    {
        $id_blnk = $this->getRequest('id_blnk', null);
        if ($id_blnk==null){
            return CopixActionGroup::process ('genericTools|Messages::getError',
            array ('message'=>CopixI18N::get ('blog.error.param'),
            'back'=>CopixUrl::get ('blog|admin|listBlog')));
        }

        $linkDAO = & CopixDAOFactory::create ('blog|bloglink');
        if (!$toDelete = $linkDAO->get ($id_blnk)){
            return CopixActionGroup::process ('genericTools|Messages::getError',
            array ('message'=>CopixI18N::get ('blog.error.cannotFindBlog'),
            'back'=>CopixUrl::get ('blog|admin|listBlog')));
        }

        $id_blog = $this->getRequest('id_blog', null);
        if($this->getRequest('confirm', null)!=null) {
            $linkDAO->delete($toDelete);
            return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('blog|admin|showBlog', array("id_blog"=>$id_blog, "kind"=>$this->getRequest('kind', '0'))));
        }

        return CopixActionGroup::process ('genericTools|messages::getConfirm',
        array ('confirm'=>CopixUrl::get ('blog|admin|deleteLink',
        array('id_blnk'=>$id_blnk,
        'id_blog'=>$id_blog,
        'kind'=>$this->getRequest('kind', '0'),
        'confirm'=>1)),
        'cancel'=>CopixUrl::get ('blog|admin|showBlog', array("id_blog"=>$id_blog, "kind"=>$this->getRequest('kind', '0'))),
        'message'=>CopixI18N::get ('blog.messages.confirmDeleteLink'),
        'title'=>CopixI18N::get ('blog.get.delete.link.title')));

    }


    /**
    * Moves a category up
    * @param _request('id') the article to moves up
    */
    public function doLinkUp ()
    {
        $id_blnk = $this->getRequest('id_blnk', null);
        if ($id_blnk==null){
            return CopixActionGroup::process ('genericTools|Messages::getError',
            array ('message'=>CopixI18N::get ('blog.error.param'),
            'back'=>CopixUrl::get ('blog|admin|listBlog')));
        }
        $id_blog = $this->getRequest('id_blog', null);

        $linkDAO = & CopixDAOFactory::create ('blog|bloglink');
        //does the menu exists ?
        if (($link = $linkDAO->get ($id_blnk)) == false){
            return CopixActionGroup::process ('genericTools|Messages::getError',
            array ('message'=>CopixI18N::get ('blog.error.cannotFindBlog'),
            'back'=>CopixUrl::get ('blog|admin|listBlog')));
        }

        $linkDAO->doUp ($id_blog, $link);

        return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('blog|admin|showBlog', array("id_blog"=>$id_blog, "kind"=>$this->getRequest('kind', '0'))));
    }

    /**
    * Moves a category down
    * @param _request('id') the article to moves down
    */
    public function doLinkDown ()
    {
        $id_blnk = $this->getRequest('id_blnk', null);
        if ($id_blnk==null){
            return CopixActionGroup::process ('genericTools|Messages::getError',
            array ('message'=>CopixI18N::get ('blog.error.param'),
            'back'=>CopixUrl::get ('blog|admin|listBlog')));
        }
        $id_blog = $this->getRequest('id_blog', null);

        $linkDAO = & CopixDAOFactory::create ('blog|bloglink');
        //does the menu exists ?
        if (($link = $linkDAO->get ($id_blnk)) == false){
            return CopixActionGroup::process ('genericTools|Messages::getError',
            array ('message'=>CopixI18N::get ('blog.error.cannotFindBlog'),
            'back'=>CopixUrl::get ('blog|admin|listBlog')));
        }

        $linkDAO->doDown ($id_blog, $link);

        return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('blog|admin|showBlog', array("id_blog"=>$id_blog, "kind"=>$this->getRequest('kind', '0'))));
    }
}
