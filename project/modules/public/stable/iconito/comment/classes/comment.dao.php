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
* @version	$Id: DAOComment.class.php,v 1.0
* @author Bertrand Yan
* surcharge  pour les dao
*/
class DAOComment {
   /**
   * get next position
   * @param string $id identifiant
   * @param string $type
   */
   function getNextPosition ($type, $id) {
      $query = 'select MAX(position_cmt) as max_pos from "comment" where id_cmt=\''.$id.'\' and type_cmt=\''.$type.'\'';
      $dbWidget = & CopixDBFactory::getDbWidget ();
      $count = $dbWidget->fetchFirst ($query);

      $toReturn = $count->max_pos + 1;
      return $toReturn;
   }
   
   /**
   * get nb comment for an id and a type
   * @param string $id identifiant
   * @param string $type
   */
   function getNbComment ($id, $type) {
      $query = 'select count(id_cmt) as nbComment from "comment" where id_cmt=\''.$id.'\' and type_cmt=\''.$type.'\'';
      $dbWidget = & CopixDBFactory::getDbWidget ();
      $count = $dbWidget->fetchFirst ($query);

      $toReturn = $count->nbComment;
      return $toReturn;
   }

}
?>
