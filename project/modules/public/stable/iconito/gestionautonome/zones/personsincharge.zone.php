<?php

/**
 * @package    Iconito
 * @subpackage Gestionautonome
 * @author     Jérémy FOURNAISE
 */
class ZonePersonsInCharge extends CopixZone
{

    public function _createContent(& $toReturn)
    {
        $ppo = new CopixPPO ();

        // Récupération des paramètres
        $ppo->nodeId = $this->getParam('nodeId');
        $ppo->nodeType = $this->getParam('nodeType');
        $ppo->studentId = $this->getParam('studentId');

        $ppo->user = _currentUser();

        // Récupérations des responsables de l'élève
        $personsInChargeDAO = _ioDAO('kernel|kernel_bu_res');
        $ppo->persons = $personsInChargeDAO->getByStudent($ppo->studentId);

        // Fonctionnalité de rattachement de parents à un élève activé ?
        $ppo->personInChargeLinkingEnabled = false;
        if (CopixConfig::exists('gestionautonome|personInChargeLinkingEnabled') && CopixConfig::get('gestionautonome|personInChargeLinkingEnabled')) {

            $ppo->personInChargeLinkingEnabled = true;
        }

        // Get vocabulary catalog to use
        $nodeVocabularyCatalogDAO = _ioDAO('kernel|kernel_i18n_node_vocabularycatalog');
        $ppo->vocabularyCatalog = $nodeVocabularyCatalogDAO->getCatalogForNode($ppo->nodeType, $ppo->nodeId);

        $toReturn = $this->_usePPO($ppo, '_persons_in_charge.tpl');
    }

}