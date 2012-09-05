<?php
/**
* @package    Iconito
* @subpackage Gestionautonome
* @author     Jérémy FOURNAISE
*/
class ZoneSchool extends CopixZone
{
  /**
   * Affichage des écoles
   */
    public function _createContent (& $toReturn)
    {
      $ppo = new CopixPPO ();

      if (is_null($cityId = $this->getParam('city_id'))) {

        $toReturn = '';
        return;
      }

      $schoolDAO = _ioDAO ('kernel|kernel_bu_ecole');

      if (_currentUser ()->testCredential ('module:city|'.$cityId.'|school|create@gestionautonome')) {

        $ppo->schools = $schoolDAO->getByCity ($cityId);
      } else {

      $groups = _currentUser ()->getGroups ();
      $ppo->schools = $schoolDAO->findByCityIdAndUserGroups ($cityId, $groups['gestionautonome|iconitogrouphandler']);
    }

      // Récupération des noeuds ouvert
      $ppo->nodes = _sessionGet('schools_nodes');
      if (is_null($ppo->nodes)) {

        $ppo->nodes = array();
      }

    $toReturn = $this->_usePPO ($ppo, '_school.tpl');
  }
}