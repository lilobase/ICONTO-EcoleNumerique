<?php

/**
 * @package    Iconito
 * @subpackage Gestionautonome
 * @author     Jérémy FOURNAISE
 */
class ZoneSearchResult extends CopixZone
{

    public function _createContent(& $toReturn)
    {
        $ppo = new CopixPPO ();

        $ppo->matchedNodes = $this->getParam('matched_nodes');

        $ppo->total = count($ppo->matchedNodes['cities_groups'])
            + count($ppo->matchedNodes['cities'])
            + count($ppo->matchedNodes['schools'])
            + count($ppo->matchedNodes['classrooms']);

        $toReturn = $this->_usePPO($ppo, '_search_result.tpl');
    }

}