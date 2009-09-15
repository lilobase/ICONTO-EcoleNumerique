<?php
/**
* @filesource
* @package : copix
* @subpackage : comment
* @author : Bertrand Yan
*/

/**
* @package copix
* @subpackage comment
* @version	$Id: comment.actiongroup.php,v 1.0
* @author Bertrand Yan
*/

class ActionGroupComment extends CopixActionGroup {

   function doDelete() {
      if ((!isset($this->vars['back'])) || (!isset($this->vars['id_cmt'])) || (!isset($this->vars['type_cmt'])) || (!isset($this->vars['position_cmt']))){
         return CopixActionGroup::process ('genericTools|Messages::getError',
         array ('message'=>CopixI18N::get ('comment.error.missingParameter')));
      }

      $dao    = & COPIXDAOFactory::create ('Comment');
      if (!($toDelete = $dao->get ($this->vars['id_cmt'], $this->vars['type_cmt'], $this->vars['position_cmt']))) {
         return CopixActionGroup::process ('genericTools|Messages::getError',
         array ('message'=>CopixI18N::get ('comment.error.unabletoGetComment')));
      }

      $plugAuth = & $GLOBALS['COPIX']['COORD']->getPlugin ('auth|auth');
      $user     = & $plugAuth->getUser ();
      //check right
      if ((CopixUserProfile::valueOf ('site','siteAdmin') < PROFILE_CCV_MODERATE) && ($user->login != $toDelete->author_cmt)) {
            return CopixActionGroup::process ('genericTools|Messages::getError',
            array ('message'=>CopixI18N::get ('comment.error.unableToUpdate')));
      }
      
      if (isset($this->vars['backToComment']) && strlen($this->vars['backToComment']) > 0) {
         $cancelUrl = CopixUrl::get('comment||', array('back'=>urlencode($this->vars['back']), 'id'=>$toDelete->id_cmt, 'type'=>$toDelete->type_cmt));
      }else{
         $cancelUrl = $back;
      }
      
      //Confirmation screen ?
      if (!isset ($this->vars['confirm'])){
      	return CopixActionGroup::process ('genericTools|Messages::getConfirm',
      		array ('title'=>CopixI18N::get ('comment.title.confirmDelete'),
      		'message'=>CopixI18N::get ('comment.message.confirmDelete'),
      		'confirm'=>CopixUrl::get('comment||delete', array('back'=>urlencode($this->vars['back']), 'backToComment'=>$this->vars['backToComment'], 'id_cmt'=>$toDelete->id_cmt, 'type_cmt'=>$toDelete->type_cmt, 'position_cmt'=>$toDelete->position_cmt, 'confirm'=>'1')),
      		'cancel'=>$cancelUrl));
      }

      //Delete comment
      $dao->delete($toDelete->id_cmt, $toDelete->type_cmt, $toDelete->position_cmt);
      
      if (isset($this->vars['backToComment']) && strlen($this->vars['backToComment']) > 0) {
         return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get('comment||', array('back'=>$this->vars['back'], 'id'=>$toDelete->id_cmt, 'type'=>$toDelete->type_cmt)));
      }else{
         return new CopixActionReturn (COPIX_AR_REDIRECT, $this->vars['back']);
      }
   }


   /**
   * Prepare to edit comment
   */
   function doPrepareEdit () {
      if ((!isset($this->vars['back'])) ||(!isset($this->vars['id_cmt']))|| (!isset($this->vars['type_cmt'])) || (!isset($this->vars['position_cmt']))){
         return CopixActionGroup::process ('genericTools|Messages::getError',
         array ('message'=>CopixI18N::get ('comment.error.missingParameter')));
      }
      
      $dao    = & COPIXDAOFactory::create ('Comment');
      if (!($toEdit = $dao->get ($this->vars['id_cmt'], $this->vars['type_cmt'], $this->vars['position_cmt']))) {
         return CopixActionGroup::process ('genericTools|Messages::getError',
         array ('message'=>CopixI18N::get ('comment.error.unabletoGetComment')));
      }

      $plugAuth = & $GLOBALS['COPIX']['COORD']->getPlugin ('auth|auth');
      $user     = & $plugAuth->getUser ();
      //check right
      if ((CopixUserProfile::valueOf ('site','siteAdmin') < PROFILE_CCV_MODERATE) && ($user->login != $toEdit->author_cmt)) {
            return CopixActionGroup::process ('genericTools|Messages::getError',
            array ('message'=>CopixI18N::get ('comment.error.unableToUpdate')));
      }
      
      $toEdit->INTERNAL_COPIX_IsNew   = false;
      $toEdit->back           = $this->vars['back'];
      $toEdit->backToComment  = isset($this->vars['backToComment']);
      $this->_setSessionComment ($toEdit);
      return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get('comment||edit'));
   }

   /**
   * Prepare to add comment
   * @param string $module module name
   * @param string $id given identifier
   * @param string $back url to go back
   */
   function doPrepareAdd () {
      if ((!isset($this->vars['type'])) || (!isset($this->vars['id'])) || (!isset($this->vars['back']))){
         return CopixActionGroup::process ('genericTools|Messages::getError',
         array ('message'=>CopixI18N::get ('comment.error.missingParameter')));
      }

      $newComment                 = & COPIXDAOFactory::createRecord ('Comment');
      $newComment->id_cmt         = $this->vars['id'];
      $newComment->type_cmt       = $this->vars['type'];
      $newComment->INTERNAL_COPIX_IsNew   = true;
      $newComment->back           = $this->vars['back'];
      $newComment->backToComment  = isset($this->vars['backToComment']);
      $this->_setSessionComment ($newComment);
      return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get('comment||edit'));
   }

   /**
   * get edit screen for comment
   *
   */
   function getEdit () {
      if (!$toEdit = $this->_getSessionComment ()){
         return CopixActionGroup::process ('genericTools|Messages::getError',
         array ('message'=>CopixI18N::get ('comment.error.unableToGetSession')));
      }

      $tpl = & new CopixTpl ();
      $tpl->assign ('TITLE_PAGE', strlen ($toEdit->__INTERNAL_COPIX_IsNew) !== true ? CopixI18N::get ('comment.titlePage.update') : CopixI18N::get ('comment.titlePage.add'));

      $tpl->assign ('MAIN', CopixZone::process ('AddComment', array ('toEdit'=>$toEdit,'e'=>isset ($this->vars['e']))));
      return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);
   }

   /*
   * Cancel the edition...... empty the session data
   */
   function doCancelEdit (){
      if (!$toEdit = $this->_getSessionComment ()){
         return CopixActionGroup::process ('genericTools|Messages::getError',
         array ('message'=>CopixI18N::get ('comment.error.unableToGetSession')));
      }
      $this->_setSessionComment(null);
      if ($toEdit->backToComment) {
         return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get('comment||', array('back'=>$toEdit->back, 'id'=>$toEdit->id_cmt, 'type'=>$toEdit->type_cmt)));
      }else{
         return new CopixActionReturn (COPIX_AR_REDIRECT, $toEdit->back);
      }
   }

   /**
   * apply updates on the edited comment.
   * save to datebase if ok and save file.
   */
   function doValid (){
      $plugAuth = & $GLOBALS['COPIX']['COORD']->getPlugin ('auth|auth');
      $user     = & $plugAuth->getUser ();
      if ($user->isConnected()) {
         if (!$toValid = $this->_getSessionComment()){
            return CopixActionGroup::process ('genericTools|Messages::getError',
            array ('message'=>CopixI18N::get ('comment.error.unableToGetSession')));
         }

         $services = & CopixClassesFactory::create ('comment|commentservices');
         if (!$services->canComment($toValid->id_cmt, $toValid->type_cmt)){
            return CopixActionGroup::process ('genericTools|Messages::getError',
            array ('message'=>CopixI18N::get ('comment.error.unableToComment')));
         }
         $dao = & CopixDAOFactory::create ('Comment');
         $this->_validFromForm($toValid);
         //inserting or updating.
         if ($toValid->INTERNAL_COPIX_IsNew !== true){
            if ($toValid->check () !== true){
               $this->_setSessionComment($toValid);
               return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get('comment||edit', array('e'=>'1')));
            }
            $dao->update ($toValid);
         }else{
            $toValid->date_cmt     = date('Ymd');
            $toValid->author_cmt   = $user->login;
            $toValid->position_cmt = $dao->getNextPosition($toValid->type_cmt, $toValid->id_cmt);
            if ($toValid->check () !== true){
               $this->_setSessionComment($toValid);
               return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get('comment||edit', array('e'=>'1')));
            }
            $dao->insert ($toValid);
         }


         // Gestion du notifier pour les projets koyo //
         CopixEventNotifier::notify (new CopixEvent ('AddComment',
            array ('id'=>$toValid->id_cmt,
                   'type'=>$toValid->type_cmt)));

         $this->_setSessionComment (null);
         if ($toValid->backToComment) {
            return new CopixActionReturn (COPIX_AR_REDIRECT, CopixUrl::get('comment||', array('back'=>urlencode($toValid->back), 'id'=>$toValid->id_cmt, 'type'=>$toValid->type_cmt)));
         }else{
            return new CopixActionReturn (COPIX_AR_REDIRECT, $toValid->back);
         }
      }else{
          return CopixActionGroup::process ('genericTools|Messages::getError',
            array ('message'=>CopixI18N::get ('comment|comment.messages.needLogin')));
      }
   }


   /**
   * Get comment list
   * @param string $type type name
   * @param string $id given identifier
   * @param string $back url to go back
   */
   function getList () {
      if ((!isset($this->vars['type'])) || (!isset($this->vars['id']))){
         return CopixActionGroup::process ('genericTools|Messages::getError',
         array ('message'=>CopixI18N::get ('comment.error.missingParameter')));
      }

      $tpl = & new CopixTpl ();
      $tpl->assign ('TITLE_PAGE', CopixI18N::get ('comment.titlePage.list'));

      $tpl->assign ('MAIN', CopixZone::process ('CommentList', array ('type'=>$this->vars['type'],'id'=>$this->vars['id'],'back'=>$this->vars['back'],'perPage'=>$this->vars['perPage'])));
      return new CopixActionReturn (COPIX_AR_DISPLAY, $tpl);

   }

   /**
   * updates informations on a single comment object from the vars.
   * @access: private.
   */
   function _validFromForm (& $toUpdate){
      $toCheck = array ('title_cmt', 'content_cmt', 'textformat_cmt');
      foreach ($toCheck as $elem){
         if (isset ($this->vars[$elem])){
            $toUpdate->$elem = $this->vars[$elem];
         }
      }
   }


   /**
   * gets the current edited comment.
   * @access: private.
   */
   function _getSessionComment () {
      CopixDAOFactory::fileInclude ('Comment');
      return isset ($_SESSION['MODULE_COMMENT_EDITED_COMMENT']) ? unserialize ($_SESSION['MODULE_COMMENT_EDITED_COMMENT']) : null;
   }

   /**
   * sets the current edited comment.
   * @access: private.
   */
   function _setSessionComment ($toSet){
      $_SESSION['MODULE_COMMENT_EDITED_COMMENT'] = $toSet !== null ? serialize($toSet) : null;
   }
}
?>
