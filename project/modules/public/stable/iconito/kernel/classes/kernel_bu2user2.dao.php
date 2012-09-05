<?php

/**
* @package    Iconito
* @subpackage kernel
*/

class DAOKernel_bu2user2
{
  /**
   * Récupère les liens d'un user
   *
   * @param int user_id
   */
  public function findByUserId ($user_id)
  {
    $sql = $this->_selectQuery.' WHERE user_id=:user_id';

    return _doQuery ($sql, array(':user_id' => $user_id));
  }
}