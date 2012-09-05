<?php

/**
* @package    Iconito
* @subpackage Cahierdetextes
*/

class DAORecordCahierDeTextesDomaine
{
  public function __toString ()
  {
        return $this->nom;
    }
}

class DAOCahierDeTextesDomaine
{
  /**
     * Retourne les domaines d'une classe
     *
     * @param int $idClasse Identifiant d'une classe
     *
     * @return CopixDAORecordIterator
     */
  public function findByClasse($idClasse)
  {
    $criteria = _daoSp ();
        $criteria->addCondition ('classe_id', '=', $idClasse);
        $criteria->orderBy (array ('nom', 'ASC'));

        return $this->findBy ($criteria);
  }

  /**
     * Retourne le domaine correspondant au nom indiqué
     *
     * @param string $nom
     *
      * @return DAORecordCahier_domaine
     */
  public function getByClasseEtNom($classe, $nom)
  {
    $sql = $this->_selectQuery
               . ' WHERE nom = :nom'
               . ' AND kernel_bu_ecole_classe_id = :classe';

      $resultats = new CopixDAORecordIterator (_doQuery ($sql, array (':classe' => $classe, ':nom' => $nom)), $this->getDAOId ());

        return isset ($resultats[0]) ? $resultats[0] : false;
  }
}