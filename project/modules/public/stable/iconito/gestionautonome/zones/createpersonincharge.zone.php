<?php

/**
 * @package    Iconito
 * @subpackage Gestionautonome
 * @author     Jérémy FOURNAISE
 */
class ZoneCreatePersonInCharge extends CopixZone
{

    public function _createContent(& $toReturn)
    {
        $ppo = new CopixPPO ();

        // Récupération des paramètres
        $ppo->nodeId = $this->getParam('nodeId');
        $ppo->nodeType = $this->getParam('nodeType');
        $ppo->cpt = $this->getParam('cpt');
        $ppo->studentId = $this->getParam('studentId', false);

        // Récupération des relations
        $parentLinkDAO = _ioDAO('kernel_bu_lien_parental');
        $parentLinks = $parentLinkDAO->findAll();

        $ppo->linkNames = array();
        $ppo->linkIds = array();

        foreach ($parentLinks as $parentLink) {

            $ppo->linkNames[] = $parentLink->parente;
            $ppo->linkIds[] = $parentLink->id_pa;
        }

        $ppo->genderNames = array('Homme', 'Femme');
        $ppo->genderIds = array('1', '2');

        // Récupération des responsables de l'élève
        if (false !== $ppo->studentId) {

            $personsInChargeDAO = _ioDAO('kernel|kernel_bu_res');
            $ppo->persons = $personsInChargeDAO->getByStudent($ppo->studentId);
        } else {

            // Récupération des responsables en session (devant être créés lors de la création de l'élève)
            $ppo->personsInSession = _sessionGet('modules|gestionautonome|tmpAccount');
        }

        $ppo->user = _currentUser();

        // Get vocabulary catalog to use
        $nodeVocabularyCatalogDAO = _ioDAO('kernel|kernel_i18n_node_vocabularycatalog');
        $ppo->vocabularyCatalog = $nodeVocabularyCatalogDAO->getCatalogForNode($ppo->nodeType, $ppo->nodeId);

        $toReturn = $this->_usePPO($ppo, '_create_person_in_charge.tpl');
    }

}