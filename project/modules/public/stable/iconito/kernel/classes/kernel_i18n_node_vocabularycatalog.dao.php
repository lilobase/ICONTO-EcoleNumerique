<?php
/**
* @package    Iconito
* @subpackage kernel
*
* @author Jérémy Fournaise <jeremy.fournaise@isics.fr>
*/

class DAOKernel_i18n_node_vocabularycatalog
{
  /**
   * Retourne le catalogue de vocabulaire à utiliser pour un noeud donné
   * Catalogue indiqué en base ou catalogue par défaut si non disponible
   *
   * @param string  $nodeType Type du noeud
   * @param int     $nodeId   Identifiant du noeud
   *
   * @return DAORecordKernel_i18n_node_vocabularycatalog
   */
  public function getCatalogForNode ($nodeType, $nodeId)
  {
    $vocabularyCatalogDAO = _ioDAO('kernel|kernel_i18n_vocabulary_catalog');

    $nodeVocabularyCatalog = $this->get($nodeType, $nodeId);
        if($nodeVocabularyCatalog) {

          $vocabularyCatalog = $vocabularyCatalogDAO->get($nodeVocabularyCatalog->vocabulary_catalog_id);
        } else {

          $vocabularyCatalog = $vocabularyCatalogDAO->getDefaultCatalog();
        }

        return $vocabularyCatalog;
  }
}