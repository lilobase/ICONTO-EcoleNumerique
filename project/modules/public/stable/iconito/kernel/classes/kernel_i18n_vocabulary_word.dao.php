<?php
/**
* @package    Iconito
* @subpackage kernel
*
* @author Jérémy Fournaise <jeremy.fournaise@isics.fr>
*/

class DAOKernel_i18n_vocabulary_word
{
  const INDEFINITE_ARTICLE  = "indefinite";
  const DEFINITE_ARTICLE    = "definite";

  /**
   * Retourne les mots pour un catalogue et une langue donnée
   *
   * @param int     $vocabularyCatalogId
   * @param string  $lang
   *
   * @return CopixDAORecordIterator
   */
  public function findByCatalogAndLang($vocabularyCatalogId, $lang)
  {
    $criteria = _daoSp ();
      $criteria->addCondition ('vocabulary_catalog_id', '=', $vocabularyCatalogId);
      $criteria->addCondition ('lang_word', '=', $lang);

      return $this->findBy ($criteria);
  }
}