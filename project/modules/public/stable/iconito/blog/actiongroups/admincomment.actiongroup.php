<?php
/**
* @package  copix
* @version   $Id: admincomment.actiongroup.php,v 1.9 2007-06-01 16:08:43 cbeyer Exp $
* @author Vallat C�dric.
* @copyright 2001-2005 CopixTeam
* @link      http://copix.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

_classInclude('blog|blogauth');
_classInclude('blog|blogutils');

class ActionGroupAdminComment extends CopixActionGroup
{
    public function beforeAction ()
    {
        _currentUser()->assertCredential ('group:[current_user]');

    }

    /**
    * Pr�paration � l'affichage de la liste des commentaires.
    */
    public function getListComment()
    {
        $id_bact = $this->getRequest('id_bact', null);
        $id_blog = $this->getRequest('id_blog', '');

        if ($id_bact==null){
            return CopixActionGroup::process ('genericTools|Messages::getError',
            array ('message'=>CopixI18N::get ('blog.error.param'),
            'back'=>CopixUrl::get ('blog|admin|listBlog')));
        }

        if (!BlogAuth::canMakeInBlog('ADMIN_ARTICLES',create_blog_object($id_blog))) {
      return CopixActionGroup::process ('genericTools|Messages::getError',
      array ('message'=>CopixI18N::get ('kernel|kernel.error.noRights'),
      'back'=>CopixUrl::get ('blog||')));
    }


        // Recherche de tous les commentaires de la base
        $commentDAO = CopixDAOFactory::create('blog|blogarticlecomment');
        $res = $commentDAO->findCommentOrderBy($id_bact, NULL);
        $resultats = array();
        foreach($res as $r) {
            $r->time_bacc = BDToTime($r->time_bacc);
            array_push($resultats, $r);
        }

        // On r�cup�re l'utilisateur connect�
        $user = BlogAuth::getUserInfos();
        $toEdit = CopixDAOFactory::createRecord('blogarticlecomment');
    $toEdit->authorid_bacc = $user->userId;
    $toEdit->authorname_bacc = $user->name;
    $toEdit->authoremail_bacc = $user->email;
    $toEdit->authorweb_bacc = $user->web;

        $tpl = new CopixTpl ();
        $tpl->assign ('TITLE_PAGE', CopixI18N::get('blog.get.list.comment.title'));

        //creation of blog object for menu
        $blogDAO = CopixDAOFactory::create('blog|blog');
        $blog = $blogDAO->get($id_blog);

        $menu = getBlogAdminMenu($blog);
        $tpl->assign ('MENU', $menu);


        $tpl->assign ('MAIN', CopixZone::process ('ListComment',
        array('resultats'=>$resultats,
        'id_bact'=>$id_bact,
        'id_blog'=>$id_blog,
        'toEdit'=>$toEdit
        )));
        return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
    }

    /**
    * Suppression d'un comment.
    */
    public function doDeleteComment ()
    {
        $id_bacc = $this->getRequest('id_bacc', null);
        if ($id_bacc==null){
            return CopixActionGroup::process ('genericTools|Messages::getError',
            array ('message'=>CopixI18N::get ('blog.error.param'),
            'back'=>CopixUrl::get ('blog|admin|listBlog')));
        }

        $commentDAO = & CopixDAOFactory::create ('blog|blogarticlecomment');
        if (!$toDelete = $commentDAO->get ($id_bacc)){
            return CopixActionGroup::process ('genericTools|Messages::getError',
            array ('message'=>CopixI18N::get ('blog.error.cannotFindBlog'),
            'back'=>CopixUrl::get ('blog|admin|listBlog')));
        }

        $id_bact = $this->getRequest('id_bact', null);
        if($this->getRequest('confirm', null)!=null) {
            $commentDAO->delete($toDelete->id_bacc);
            return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get ('blog|admin|listComment', array("id_bact"=>$id_bact, "id_blog"=>$this->getRequest('id_blog', ''))));
        }

        return CopixActionGroup::process ('genericTools|messages::getConfirm',
        array ('confirm'=>CopixUrl::get ('blog|admin|deleteComment',
        array('id_bact'=>$id_bact,
        'id_bacc'=>$id_bacc,
        'id_blog'=>$this->getRequest('id_blog', ''),
        'confirm'=>1)),
        'cancel'=>CopixUrl::get ('blog|admin|listComment', array("id_bact"=>$id_bact, 'id_blog'=>$this->getRequest('id_blog', ''))),
        'message'=>CopixI18N::get ('blog.messages.confirmDeleteComment'),
        'title'=>CopixI18N::get ('blog.get.delete.comment.title')));

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
        $toUpdate->is_online = 1;
    }

    /**
    * Validation d'un commentaire (=ajout d'un comm)
    */
    public function doValidComment()
    {
        $id_blog = $this->getRequest('id_blog', null);
        $id_bact = $this->getRequest('id_bact', null);
        if (!BlogAuth::canMakeInBlog('ADMIN_ARTICLES',create_blog_object($id_blog))){
            return CopixActionGroup::process ('genericTools|Messages::getError',
            array ('message'=>CopixI18N::get ('blog.error.cannotManageComment'),
            'back'=>CopixUrl::get ('blog|admin|listBlog')));
        }

        $tpl = new CopixTpl ();

        // On r�cup�re l'utilisateur connect�
        $commentDAO = CopixDAOFactory::create('blog|blogarticlecomment');
        // CREATION D'UN COMM
        $comment = CopixDAOFactory::createRecord('blogarticlecomment');
        $this->_validFromPostProperties($comment);
        $comment->date_bacc = date('Ymd');
        $comment->time_bacc = date('Hi');
        $comment->authorip_bacc = $_SERVER["REMOTE_ADDR"];

        $tpl->assign ('TITLE_PAGE', CopixI18N::get('blog.get.create.comment.title'));
        $errors = _dao('blog|blogarticlecomment')->check($comment);
        if($errors!=1) {
            // Traitement des erreurs
            $showErrors =  true;
        } else {
            // Insertion dans la base
            $commentDAO->insert($comment);
            return new CopixActionReturn (COPIX_AR_REDIRECT,
            CopixUrl::get ('blog|admin|listComment', array("id_blog"=>$id_blog, "id_bact"=>$id_bact)));
        }

        // Recherche de tous les blogs de la base
        $commentDAO = CopixDAOFactory::create('blog|blogarticlecomment');
        $res = $commentDAO->findCommentOrderBy($id_bact, NULL);
        $resultats = array();
        foreach($res as $r) {
            $r->time_bacc = BDToTime($r->time_bacc);
            array_push($resultats, $r);
        }

        $tpl->assign ('MAIN', CopixZone::process ('ListComment',
        array('resultats'=>$resultats,
        'id_bact'=>$id_bact,
        'id_blog'=>$this->getRequest('id_blog', ''),
        'errors'=>$errors,
        'showErrors'=>$showErrors,
        'toEdit'=>$comment
        )));
        return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
    }

    /**
    * Pr�paration � l'�dition d'un commentaire.
    */
    public function doPrepareEditComment()
    {
        $id_blog = $this->getRequest('id_blog', null); if(strlen($id_blog)==0) $id_blog=null;
        $id_bact = $this->getRequest('id_bact', null); if(strlen($id_bact)==0) $id_bact=null;
        $id_bacc = $this->getRequest('id_bacc', null); if(strlen($id_bacc)==0) $id_bacc=null;
        if (($id_blog==null)||($id_bact==null)||($id_bacc==null)){
            return CopixActionGroup::process ('genericTools|Messages::getError',
            array ('message'=>CopixI18N::get ('blog.error.param'),
            'back'=>CopixUrl::get ('blog|admin|listBlog')));
        }

        if (!BlogAuth::canMakeInBlog('ADMIN_COMMENTS',create_blog_object($id_blog))){
            return CopixActionGroup::process ('genericTools|Messages::getError',
            array ('message'=>CopixI18N::get ('blog.error.cannotManageComment'),
            'back'=>CopixUrl::get ('blog|admin|listBlog')));
        }

        $tpl = new CopixTpl ();

        if (BlogAuth::canMakeInBlog('ADMIN_COMMENTS',create_blog_object($id_blog)))
//			$menu = '<a href="'.CopixUrl::get ('blog|admin|showBlog', array("id_blog"=>$id_blog, "kind"=>0)).'">'.CopixI18N::get('blog|blog.nav.articles').'</a>';
        $menu = getBlogAdminMenu($blog);
        $tpl->assign ('MENU', $menu);


        // EDITION D'UN BLOG
        $articleDAO = CopixDAOFactory::create('blog|blogarticle');
        $article = $articleDAO->get($id_bact);
        $commentDAO = CopixDAOFactory::create('blog|blogarticlecomment');
        $comment = $commentDAO->get($id_bacc);
        $comment->name_bact = $article->name_bact;
        $comment->time_bacc = BDToTime($comment->time_bacc);
        $tpl->assign ('TITLE_PAGE', CopixI18N::get('blog.get.edit.comment.title'));

        $tpl->assign ('MAIN', CopixZone::process ('EditComment',
        array('id_blog'=>$id_blog,
        'id_bact'=>$id_bact,
        'id_bacc'=>$id_bacc,
        'comment'=>$comment
        )));
        return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
    }


    /**
    * Validation d'une cat�gorie.
    */
    public function doValidModifyComment()
    {
        $id_blog = $this->getRequest('id_blog', null); if(strlen($id_blog)==0) $id_blog=null;
        $id_bact = $this->getRequest('id_bact', null); if(strlen($id_bact)==0) $id_bact=null;
        $id_bacc = $this->getRequest('id_bacc', null); if(strlen($id_bacc)==0) $id_bacc=null;
        if (($id_blog==null)||($id_bact==null)||($id_bacc==null)){
            return CopixActionGroup::process ('genericTools|Messages::getError',
            array ('message'=>CopixI18N::get ('blog.error.param'),
            'back'=>CopixUrl::get ('blog|admin|listBlog')));
        }

        if (!BlogAuth::canMakeInBlog('ADMIN_COMMENTS',create_blog_object($id_blog))){
            return CopixActionGroup::process ('genericTools|Messages::getError',
            array ('message'=>CopixI18N::get ('blog.error.cannotManageComment'),
            'back'=>CopixUrl::get ('blog|admin|listBlog')));
        }

        $tpl = new CopixTpl ();

        $commentDAO = CopixDAOFactory::create('blog|blogarticlecomment');
        // EDITION D'UNE CATEGORIE
        $comment = $commentDAO->get($id_bacc);
        $this->_validFromPostProperties($comment);

        $tpl->assign ('TITLE_PAGE', CopixI18N::get('blog.get.edit.comment.title'));
        $errors = _dao('blog|blogarticlecomment')->check($comment);
        if($errors!=1) {
            // Traitement des erreurs
            $showErrors =  true;
        } else {
            // Modification dans la base
            $commentDAO->update($comment);
            return new CopixActionReturn (COPIX_AR_REDIRECT,
            CopixUrl::get ('blog|admin|listComment', array("id_blog"=>$id_blog, "id_bact"=>$id_bact)));
        }

        $articleDAO = CopixDAOFactory::create('blog|blogarticle');
        $article = $articleDAO->get($id_bact);
        $comment->name_bact = $article->name_bact;
        $comment->time_bacc = BDToTime($comment->time_bacc);

        $tpl->assign ('MAIN', CopixZone::process ('EditComment',
        array('id_blog'=>$id_blog,
        'id_bact'=>$id_bact,
        'id_bacc'=>$id_bacc,
        'comment'=>$comment,
        'errors'=>$errors,
        'showErrors'=>$showErrors
        )));

        return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
    }


   /**
   * Mise en ligne d'un commentaire
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2007/05/15
     * @param integer $id_blog Id du blog
     * @param integer $id_bact Id de l'article
     * @param integer $id_bacc Id du commentaire
     * @todo V�rifier droits sur blog
   */
    public function doOnlineComment()
    {
        $id_bacc = $this->getRequest('id_bacc', null);
        $id_bact = $this->getRequest('id_bact', null);
        $id_blog = $this->getRequest('id_blog', null);

        if ($id_bacc==null){
            return CopixActionGroup::process ('genericTools|Messages::getError',
            array ('message'=>CopixI18N::get ('blog.error.param'),
            'back'=>CopixUrl::get ('blog|admin|listBlog')));
        }

        $commentDAO = & CopixDAOFactory::create ('blog|blogarticlecomment');
        if (!$item = $commentDAO->get ($id_bacc)){
            return CopixActionGroup::process ('genericTools|Messages::getError',
            array ('message'=>CopixI18N::get ('blog.error.cannotFindBlog'),
            'back'=>CopixUrl::get ('blog|admin|listBlog')));
        }

        $item->is_online = 1;
        $commentDAO->update ($item);

        //print_r($item);
        return new CopixActionReturn (COPIX_AR_REDIRECT,
            CopixUrl::get ('blog|admin|listComment', array("id_blog"=>$id_blog, "id_bact"=>$id_bact)));

    }

   /**
   * Mise hors ligne d'un commentaire
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2007/05/15
     * @param integer $id_blog Id du blog
     * @param integer $id_bact Id de l'article
     * @param integer $id_bacc Id du commentaire
     * @todo V�rifier droits sur blog
   */
    public function doOfflineComment()
    {
        $id_bacc = $this->getRequest('id_bacc', null);
        $id_bact = $this->getRequest('id_bact', null);
        $id_blog = $this->getRequest('id_blog', null);

        if ($id_bacc==null){
            return CopixActionGroup::process ('genericTools|Messages::getError',
            array ('message'=>CopixI18N::get ('blog.error.param'),
            'back'=>CopixUrl::get ('blog|admin|listBlog')));
        }

        $commentDAO = & CopixDAOFactory::create ('blog|blogarticlecomment');
        if (!$item = $commentDAO->get ($id_bacc)){
            return CopixActionGroup::process ('genericTools|Messages::getError',
            array ('message'=>CopixI18N::get ('blog.error.cannotFindBlog'),
            'back'=>CopixUrl::get ('blog|admin|listBlog')));
        }

        $item->is_online = 0;
        $commentDAO->update ($item);

        //print_r($item);
        return new CopixActionReturn (COPIX_AR_REDIRECT,
            CopixUrl::get ('blog|admin|listComment', array("id_blog"=>$id_blog, "id_bact"=>$id_bact)));

    }



}
