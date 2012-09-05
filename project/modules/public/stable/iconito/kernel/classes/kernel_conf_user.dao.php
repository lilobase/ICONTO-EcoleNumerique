<?php
/**
* @package    Iconito
* @subpackage kernel
*/

class DAOKernel_conf_user
{
  /**
     * Retourne la valeur de configuration d'un utilisateur
     *
     * @param string $path    Path de l'élément de configuration à récuperer
     * @param int    $userId  Identifiant du dbuser
     *
     * @return string or false
     */
  public function getByPathAndUserId ($path, $userId)
  {
    $sql = $this->_selectQuery
      . ' WHERE path=:path '
      . 'AND id_dbuser=:userId';

    $results = _doQuery ($sql, array (':path' => $path, ':userId' => $userId));

    return isset ($results[0]) ? $results[0] : false;
  }
}