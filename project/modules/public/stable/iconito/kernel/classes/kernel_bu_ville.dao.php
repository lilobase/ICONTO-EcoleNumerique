<?php

class DAORecordKernel_bu_ville
{
  protected $_citiesGroup = null;

  public function getCitiesGroup ()
  {
    if (is_null($this->_citiesGroup)) {

      $citiesGroupsDAO = _ioDAO ('kernel|kernel_bu_groupe_villes');

      $this->_citiesGroup = $citiesGroupsDAO->get ($this->id_grville);
    }

    return $this->_citiesGroup;
  }
}

class DAOKernel_bu_ville
{
    /**
     * Retourne une ville par son canon
     *
     * @param string $canon Canon d'une ville
     *
     * @return CopixDAORecordIterator
     */
    public function getByCanon ($canon)
    {
        $criteria = _daoSp ();
        $criteria->addCondition ('canon', '=', $canon);

        return $this->findBy ($criteria);
    }

    /**
     * Retourne les villes faisant partie d'un même groupe de villes
     *
     * @param int $idGrville ID du groupe de villes
     *
     * @return CopixDAORecordIterator
     */
    public function getByIdGrville ($idGrville)
    {
      $criteria = _daoSp ();
        $criteria->addCondition ('id_grville', '=', $idGrville);
        $criteria->orderBy ('nom');

        return $this->findBy ($criteria);
    }

    /**
     * Retourne les villes d'un groupe de ville accessibles pour un utilisateur
     *
     * @param int   $citiesGroupId  Identifiant du groupe de ville
     * @param array $groups         Groupes
   *
      * @return CopixDAORecordIterator
     */
    public function findByCitiesGroupIdAndUserGroups ($citiesGroupId, $groups)
    {
        $groupsIds = array(
      'citiesIds'       => array(),
      'schoolsIds'      => array(),
      'classroomsIds'   => array()
    );

    foreach ($groups as $key => $group) {

      $id = substr($key, strrpos($key, '_')+1);

      if (preg_match('/^city_agent/', $key)) {

        $groupsIds['citiesIds'][] = $id;
      } elseif (preg_match('/^administration_staff/', $key)) {

        $groupsIds['schoolsIds'][] = $id;
      } elseif (preg_match('/^principal/', $key)) {

        $groupsIds['schoolsIds'][] = $id;
      } elseif (preg_match('/^teacher_school/', $key)) {

        $groupsIds['schoolsIds'][] = $id;
      } elseif (preg_match('/^teacher/', $key)) {

        $groupsIds['classroomsIds'][] = $id;
      } elseif (preg_match('/^schools_group_animator/', $key)) {

        $groupsIds['schoolsIds'][] = $id;
      } elseif (preg_match('/^cities_group_animator/', $key)) {

        $groupsIds['schoolsIds'][] = $id;
      }
    }

    if (empty ($groupsIds['citiesIds']) && empty ($groupsIds['schoolsIds']) && empty ($groupsIds['classroomsIds'])) {

      return array();
    }

        $sql = $this->_selectQuery.' '
          . 'LEFT JOIN kernel_bu_ecole ON kernel_bu_ecole.id_ville = kernel_bu_ville.id_vi '
          . 'LEFT JOIN kernel_bu_ecole_classe ON kernel_bu_ecole_classe.ecole = kernel_bu_ecole.numero '
          . 'WHERE kernel_bu_ville.id_grville='.$citiesGroupId;

        $conditions = array();
        if (!empty ($groupsIds['citiesIds'])) {

          $conditions[] = 'kernel_bu_ville.id_vi IN ('.implode(',', $groupsIds['citiesIds']).')';
        }
        if (!empty ($groupsIds['schoolsIds'])) {

          $conditions[] = 'kernel_bu_ecole.numero IN ('.implode(',', $groupsIds['schoolsIds']).')';
        }
        if (!empty ($groupsIds['classroomsIds'])) {

          $conditions[] = 'kernel_bu_ecole_classe.id IN ('.implode(',', $groupsIds['classroomsIds']).')';
        }

        $sql .= ' AND ('.implode(' OR ', $conditions).')';
        $sql .= ' GROUP BY kernel_bu_ville.id_vi';
        $sql .= ' ORDER BY kernel_bu_ville.nom';

    return new CopixDAORecordIterator (_doQuery ($sql), $this->getDAOId ());
    }
}