<?php
/**
* @package    Iconito
* @subpackage kernel
*/

class DAOKernel_copixuser
{
    /**
     * Retourne un user selon un identifiant
     *
     * @param string $login
     *
     * @return DAORecordDBUser or false
     */
    public function getByUserLogin ($login)
    {
        $criteria = _daoSp ();

        $criteria->addCondition ('login_dbuser', '=', $login);

        $results = $this->findBy ($criteria);

        return isset ($results[0]) ? $results[0] : false;
    }

  /**
     * Retourne un user selon un identifiant de personne et un type
     *
     * @param int $id
     * @param string $type
     *
     * @return DAORecordDBUser or false
     */
  public function getUserByBuIdAndBuType ($id, $type)
  {
    $sql = $this->_selectQuery
      . ', kernel_link_bu2user '
      . 'WHERE copixuser.id_dbuser=kernel_link_bu2user.user_id '
      . 'AND kernel_link_bu2user.bu_id=:id '
      . 'AND kernel_link_bu2user.bu_type=:type';

    $results = new CopixDAORecordIterator (_doQuery ($sql, array (':id' => $id, ':type' => $type)), $this->getDAOId ());

    return isset ($results[0]) ? $results[0] : false;
  }
}