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
* @version	$Id: commentservices.class.php,v 1.0
* @author Bertrand Yan
* Service pour les commentaires
*/

class CommentServices {

   /**
   * get all available format and their caption
   */
   function getFormatList () {
      $list     = explode(';', CopixConfig::get('comment|textFormatList'));
      $toReturn = array();
      foreach ((array)$list as $format){
         $toReturn[$format] = CopixI18N::get('comment|comment.format.'.$format);
      }
      return $toReturn;
   }

   /**
   * enbled to add a comment
   * @params string $id
   * @params strnig $type
   */
   function enableComment ($id, $type) {
      $arEnabled = $this->_getSession();
      $arEnabled[$type][$id] = true;
      $this->_setSession($arEnabled);
   }
   
   /**
   * tell if comment is enable on type and id
   * @params string $id
   * @params strnig $type
   */
   function canComment ($id, $type) {
      $arEnabled = $this->_getSession();
      return isset($arEnabled[$type][$id]);
   }
   
   /**
    * sets the current enable comments array.
    * @access: private.
    */
   function _setSession ($toSet){
      $_SESSION['MODULE_COMMENT_ENABLE_ARRAY'] = $toSet !== null ? serialize($toSet) : null;
   }

   /**
    * gets the current  enable comments array.
    * @access: private.
    */
   function _getSession () {
      return isset ($_SESSION['MODULE_COMMENT_ENABLE_ARRAY']) ? unserialize ($_SESSION['MODULE_COMMENT_ENABLE_ARRAY']) : null;
   }

}
?>
