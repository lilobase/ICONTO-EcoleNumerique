<?php
/**
* @package    Iconito
* @subpackage kernel
*
* @author Jérémy Fournaise <jeremy.fournaise@isics.fr>
*/

class DAORecordKernel_i18n_vocabulary_catalog
{
  public function __toString ()
  {
    return $this->name_vc;
  }
}

class DAOKernel_i18n_vocabulary_catalog
{
  /**
   * Retourne le catalogue de vocabulaire par défaut
   *
   * @return DAORecordKernel_i18n_vocabulary_catalog or false
   */
  public function getDefaultCatalog()
  {
    $criteria = _daoSp ();
      $criteria->addCondition ('id_vc', '=', CopixConfig::get('kernel|defaultVocabularyCatalog'));

      $results = $this->findBy ($criteria);

      return isset ($results[0]) ? $results[0] : false;
  }

  /**
   * Retourne le catalogue de vocabulaire à partir de son nom
   *
   * @return DAORecordKernel_i18n_vocabulary_catalog or false
   */
  public function findByName($name)
  {
    $criteria = _daoSp ();
      $criteria->addCondition ('name_vc', '=', $name);

      $results = $this->findBy ($criteria);

      return isset ($results[0]) ? $results[0] : false;
  }
}