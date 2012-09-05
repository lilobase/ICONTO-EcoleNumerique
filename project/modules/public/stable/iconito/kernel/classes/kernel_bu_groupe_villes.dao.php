<?php

class DAOKernel_bu_groupe_villes
{
    /**
     * Retourne les groupes de villes accessibles pour un utilisateur
     *
     * @param array $groups  Groupes
   *
     * @return CopixDAORecordIterator
     */
    public function findByUserGroups ($groups)
    {
    $groupsIds = array(
      'citiesGroupsIds' => array(),
      'citiesIds'       => array(),
      'schoolsIds'      => array(),
      'classroomsIds'   => array()
    );

    foreach ($groups as $key => $group) {

      $id = substr($key, strrpos($key, '_')+1);

      if (preg_match('/^cities_group_agent/', $key)) {

        $groupsIds['citiesGroupsIds'][] = $id;
      } elseif (preg_match('/^city_agent/', $key)) {

        $groupsIds['citiesIds'][] = $id;
      } elseif (preg_match('/^administration_staff/', $key)) {

        $groupsIds['schoolsIds'][] = $id;
      } elseif (preg_match('/^principal/', $key)) {

        $groupsIds['schoolsIds'][] = $id;
      } elseif (preg_match('/^teacher_school/', $key)) {

        $groupsIds['schoolsIds'][] = $id;
      } elseif (preg_match('/^teacher/', $key)) {

        $groupsIds['classroomsIds'][] = $id;
      } elseif (preg_match('/^cities_group_animator/', $key)) {

        $groupsIds['schoolsIds'][] = $id;
      } elseif (preg_match('/^schools_group_animator/', $key)) {

        $groupsIds['schoolsIds'][] = $id;
      }
    }

    if (empty ($groupsIds['citiesGroupsIds']) && empty ($groupsIds['citiesIds'])
      && empty ($groupsIds['schoolsIds']) && empty ($groupsIds['classroomsIds'])) {

      return array();
    }

        $sql = $this->_selectQuery
          . ', kernel_bu_ville '
          . 'LEFT JOIN kernel_bu_ecole ON kernel_bu_ecole.id_ville = kernel_bu_ville.id_vi '
          . 'LEFT JOIN kernel_bu_ecole_classe ON kernel_bu_ecole_classe.ecole = kernel_bu_ecole.numero '
          . 'WHERE kernel_bu_groupe_villes.id_grv=kernel_bu_ville.id_grville';

        $conditions = array();
        if (!empty ($groupsIds['citiesGroupsIds'])) {

          $conditions[] = 'kernel_bu_groupe_villes.id_grv IN ('.implode(',', $groupsIds['citiesGroupsIds']).')';
        }
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
        $sql .= ' GROUP BY kernel_bu_groupe_villes.id_grv';
        $sql .= ' ORDER BY kernel_bu_groupe_villes.nom_groupe';

    return new CopixDAORecordIterator (_doQuery ($sql), $this->getDAOId ());
    }
}