<?php

/**
* @package    Iconito
* @subpackage kernel
* @author     Sébastien CAS
*/

class DAOKernel_bu2user2 {

  /**
   * Récupère les liens d'un user
   *
   * @param int user_id
   */
  public function findByUserId ($user_id) {
  
    $sql = $this->_selectQuery.' WHERE user_id=:user_id';
    
    return $results = _doQuery ($sql, array(':user_id' => $user_id));
  }
}