<?php
/**
* @package	copix
* @subpackage comment
* @version	$Id: quickadmincomment.listener.class.php,v 1.1 2005-12-19 15:32:53 fmossmann Exp $
* @author	Bertrand Yan see copix.aston.fr for other contributors.
* @copyright 2001-2005 CopixTeam
* @link      http://copix.aston.fr
* @link      http://copix.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/
/**
* Listener for QuickAdmin and the comments.
*/
class ListenerQuickAdminComment extends CopixListener {

   /**
   * Publicator will see all picture they could publish
   */
   function performQuickAdminBrowsing ($event, & $eventResponse){
      if (CopixUserProfile::valueOf ('site', 'siteAdmin') >= PROFILE_CCV_MODERATE) {
         $content = CopixZone::process ('comment|CommentQuickAdmin');
         if (strlen($content) > 0) {
            $eventResponse->add (array ('caption'=>CopixI18N::get('comment|comment.shortDescription'), 'module'=>'comment' ,'content'=>$content));
         }
      }
   }
}
?>
