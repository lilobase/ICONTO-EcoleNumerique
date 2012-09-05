<?php
/**
* @package    Iconito
* @subpackage Gestionautonome
* @author     Jérémy FOURNAISE
*/
class ZoneCity extends CopixZone
{
  /**
   * Affichage des villes
   */
    public function _createContent (& $toReturn)
    {
      $ppo = new CopixPPO ();

      if (is_null($citiesGroupId = $this->getParam('cities_group_id'))) {

        $toReturn = '';
        return;
      }

      $cityDAO = _ioDAO ('kernel|kernel_bu_ville');

      if (_currentUser ()->testCredential ('module:cities_group|'.$citiesGroupId.'|city|create@gestionautonome')) {

      $ppo->cities = $cityDAO->getByIdGrville ($citiesGroupId);
      } else {

      $groups = _currentUser ()->getGroups ();
      $ppo->cities = $cityDAO->findByCitiesGroupIdAndUserGroups ($citiesGroupId, $groups['gestionautonome|iconitogrouphandler']);
    }

      // Récupération des noeuds ouvert
      $ppo->nodes = _sessionGet('cities_nodes');
      if (is_null($ppo->nodes)) {

        $ppo->nodes = array();
      }

    $toReturn = $this->_usePPO ($ppo, '_city.tpl');
  }
}