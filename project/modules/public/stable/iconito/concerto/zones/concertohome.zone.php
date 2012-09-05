<?php

/**
 * Zone qui affiche les groupes de l'utilisateur courant
 *
 * @package Iconito
 * @subpackage	Concerto
 */
class ZoneConcertoHome extends CopixZone
{
    /**
     * Affiche la liste des groupes de l'utilisateur courant
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2006/03/23
     * @param string $kw Mot-clé pour la recherche (option)
     */
    public function _createContent (&$toReturn)
    {
    $toReturn = '';

    $tpl = new CopixTpl ();
    if( CopixConfig::exists('|conf_ModConcerto') && CopixConfig::get('|conf_ModConcerto') && _currentUser()->getExtra('type')=='USER_RES') {
      $new_module = null;
      $sql = 'SELECT id,login,password FROM kernel_bu_auth WHERE node_type=\'responsable\' AND node_id='._currentUser()->getExtra('id').' AND service=\'concerto\'';
      $concerto = _doQuery($sql);
      if( $concerto ) {
        $new_module = _record("kernel|kernel_mod_enabled");
        $new_module->node_type = _currentUser()->getExtra('type');
        $new_module->node_id = _currentUser()->getExtra('id');
        $new_module->module_type = "MOD_CONCERTO";
        $new_module->module_id = 0;
        $modules[] = $new_module;
        $tpl->assign ('concerto', $new_module);
        $tpl->assign ('concerto_data', $concerto);
            $toReturn = $tpl->fetch('concerto|concerto-home.tpl');
      }
    }

        return true;

    }
}
