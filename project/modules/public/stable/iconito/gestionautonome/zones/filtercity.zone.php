<?php

/**
 * @package    Iconito
 * @subpackage Gestionautonome
 * @author     Jérémy FOURNAISE
 */
class ZoneFilterCity extends CopixZone
{

    public function _createContent(& $toReturn)
    {
        $ppo = new CopixPPO ();
        $ppo->conf = new CopixPPO ();
        $ppo->conf->directorGlobalAffectation = CopixConfig::get('gestionautonome|directorGlobalAffectation');

        // Récupérations des filtres en session
        $ppo->selected = $this->getParam('selected', null);
        $ppo->withLabel = $this->getParam('with_label', true);
        $ppo->withEmpty = $this->getParam('with_empty', true);
        $ppo->labelEmpty = $this->getParam('label_empty', null);
        $ppo->name = $this->getParam('name', null);


        if (!is_null($cityGroupId = $this->getParam('city_group_id', null))) {
            $cityDAO = _dao('kernel|kernel_bu_ville');
            if (_currentUser()->testCredential('module:cities_group|'.$cityGroupId.'|city|create@gestionautonome') || (_currentUser()->isDirector && $ppo->conf->directorGlobalAffectation)) {
                $cities = $cityDAO->getByIdGrville($cityGroupId);
            } else {
                $groups = _currentUser()->getGroups();
                $cities = $cityDAO->findByCitiesGroupIdAndUserGroups($cityGroupId, $groups['gestionautonome|iconitogrouphandler']);
            }

            $ppo->citiesIds = array();
            $ppo->citiesNames = array();

            foreach ($cities as $city) {
                $ppo->citiesIds[] = $city->id_vi;
                $ppo->citiesNames[] = $city->nom;
            }
        }

        $toReturn = $this->_usePPO($ppo, '_filter_city.tpl');
    }

}
