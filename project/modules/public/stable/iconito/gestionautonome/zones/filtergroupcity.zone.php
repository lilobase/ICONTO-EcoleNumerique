<?php
/**
* @package    Iconito
* @subpackage Gestionautonome
* @author     Jérémy FOURNAISE
*/
class ZoneFilterGroupCity extends CopixZone
{
    public function _createContent (& $toReturn)
    {
        $ppo = new CopixPPO ();
        $ppo->conf = new CopixPPO ();
        $ppo->conf->directorGlobalAffectation = CopixConfig::get ('gestionautonome|directorGlobalAffectation');

        // Récupérations des filtres en session
        $ppo->selected    = $this->getParam ('selected', 0);
        $ppo->withLabel   = $this->getParam ('with_label', true);
        $ppo->withEmpty   = $this->getParam ('with_empty', true);
        $ppo->labelEmpty  = $this->getParam ('label_empty', null);
        $ppo->name        = $this->getParam ('name', null);

        $ppo->cityGroupsIds = array();
        $ppo->cityGroupsNames = array();

        $citiesGroupDAO = _ioDAO ('kernel|kernel_bu_groupe_villes');
        if (_currentUser ()->testCredential ('module:*||cities_group|create@gestionautonome') || (_currentUser()->isDirector && $ppo->conf->directorGlobalAffectation)) {
            $criteria = _daoSp ();
            $criteria->orderBy ('nom_groupe');
            $cityGroups = $citiesGroupDAO->findBy ($criteria);
        } else {
          $groups = _currentUser ()->getGroups ();
          $cityGroups = $citiesGroupDAO->findByUserGroups ($groups['gestionautonome|iconitogrouphandler']);
        }

        foreach ($cityGroups as $cityGroup) {
            $ppo->cityGroupsIds[]   = $cityGroup->id_grv;
            $ppo->cityGroupsNames[] = $cityGroup->nom_groupe;
        }

        $toReturn = $this->_usePPO ($ppo, '_filter_groupcity.tpl');
    }
}