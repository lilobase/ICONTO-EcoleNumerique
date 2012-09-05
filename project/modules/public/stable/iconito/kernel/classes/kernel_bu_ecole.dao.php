<?php

class DAORecordKernel_bu_ecole
{
  protected $_city = null;

  public function __toString ()
  {
    return $this->nom;
  }

  public function getCity ()
  {
    if (is_null($this->_city)) {

      $cityDAO = _ioDAO ('kernel|kernel_bu_ville');

      $this->_city = $cityDAO->get ($this->id_ville);
    }

    return $this->_city;
  }


  /**
   * Determine si l'ecole a une adresse renseignee ou non
   *
   * @author Christophe Beyer <cbeyer@cap-tic.fr>
   * @since 2011/01/31
   * @return boolean True si au moins un champ de l'adresse est renseigne, false sinon
   */
  public function hasAdresse()
  {
      $oHas = false;
      if ($this->num_rue || $this->num_seq || $this->adresse1 || $this->adresse2 || $this->code_postal || $this->commune) {
          $oHas = true;
      }
      return $oHas;
  }


  /**
   * L'adresse de l'ecole en une ligne
   *
   * @author Christophe Beyer <cbeyer@cap-tic.fr>
   * @since 2011/11/02
   * @return string L'adresse
   */
  public function getFullAddress()
  {
      $address = AnnuaireService::googleMapsFormatAdresse('ecole', $this);
      return $address;
  }
}

class DAOKernel_bu_ecole
{
    /**
     * Retourne les classes pour une ville donnée
     *
     * @param int $idVille Identifiant d'une ville
     *
     * @return CopixDAORecordIterator
     */
    public function getByCity ($idVille)
    {
        $criteria = _daoSp ();
        $criteria->addCondition ('id_ville', '=', $idVille);
        $criteria->orderBy ('nom');

        return $this->findBy ($criteria);
    }

    /**
     * Retourne les écoles d'une ville accessibles pour un utilisateur
     *
     * @param int   $cityId  Identifiant de la ville
     * @param array $groups  Groupes
   *
     * @return CopixDAORecordIterator
     */
    public function findByCityIdAndUserGroups ($cityId, $groups)
    {
        $groupsIds = array(
      'schoolsIds'      => array(),
      'classroomsIds'   => array()
    );

    foreach ($groups as $key => $group) {

      $id = substr($key, strrpos($key, '_')+1);

      if (preg_match('/^administration_staff/', $key)) {

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

    if (empty ($groupsIds['schoolsIds']) && empty ($groupsIds['classroomsIds'])) {

      return array();
    }

        $sql = $this->_selectQuery.' '
          . 'LEFT JOIN kernel_bu_ecole_classe ON kernel_bu_ecole_classe.ecole = kernel_bu_ecole.numero '
          . 'WHERE kernel_bu_ecole.id_ville='.$cityId;

        $conditions = array();
        if (!empty ($groupsIds['schoolsIds'])) {

          $conditions[] = 'kernel_bu_ecole.numero IN ('.implode(',', $groupsIds['schoolsIds']).')';
        }
        if (!empty ($groupsIds['classroomsIds'])) {

          $conditions[] = 'kernel_bu_ecole_classe.id IN ('.implode(',', $groupsIds['classroomsIds']).')';
        }

        $sql .= ' AND ('.implode(' OR ', $conditions).')';
        $sql .= ' GROUP BY kernel_bu_ecole.numero';
        $sql .= ' ORDER BY kernel_bu_ecole_classe.nom';

    return new CopixDAORecordIterator (_doQuery ($sql), $this->getDAOId ());
    }
}