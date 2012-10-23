<?php

/**
 * @package    Iconito
 * @subpackage Gestionautonome
 * @author     Jérémy FOURNAISE
 */
class ZoneTreeActions extends CopixZone
{

    public function _createContent(& $toReturn)
    {
        $ppo = new CopixPPO ();

        // Récupération des paramètres
        $ppo->nodeId = $this->getParam('node_id');
        $ppo->nodeType = $this->getParam('node_type');

        $ppo->user = _currentUser();

        // Get vocabulary catalog to use
        $nodeVocabularyCatalogDAO = _ioDAO('kernel|kernel_i18n_node_vocabularycatalog');
        $ppo->vocabularyCatalog = $nodeVocabularyCatalogDAO->getCatalogForNode($ppo->nodeType, $ppo->nodeId);

        $toReturn = $this->_usePPO($ppo, '_tree_actions.tpl');
    }

}