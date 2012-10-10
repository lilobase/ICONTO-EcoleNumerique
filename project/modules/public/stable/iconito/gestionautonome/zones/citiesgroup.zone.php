<?php

/**
 * @package    Iconito
 * @subpackage Gestionautonome
 * @author     Jérémy FOURNAISE
 */
class ZoneCitiesGroup extends CopixZone
{

    /**
     * Affichage des groupes de villes
     */
    public function _createContent(& $toReturn)
    {
        $ppo = new CopixPPO ();

        $citiesGroupDAO = _ioDAO('kernel|kernel_bu_groupe_villes');

        if (_currentUser()->testCredential('module:*||cities_group|create@gestionautonome')) {

            $criteria = _daoSp();
            $criteria->orderBy('nom_groupe');
            $ppo->citiesGroups = $citiesGroupDAO->findBy($criteria);
        } else {

            $groups = _currentUser()->getGroups();
            $ppo->citiesGroups = $citiesGroupDAO->findByUserGroups($groups['gestionautonome|iconitogrouphandler']);
        }

        // Récupération des noeuds ouvert
        $ppo->nodes = _sessionGet('cities_groups_nodes');
        if (is_null($ppo->nodes)) {

            $ppo->nodes = array();
        }

        $toReturn = $this->_usePPO($ppo, '_cities_group.tpl');
    }

}