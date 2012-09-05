<?php
/**
* @package    Iconito
* @subpackage Gestionautonome
* @author     Jérémy FOURNAISE
*/
class ZoneAccountsInfo extends CopixZone
{
    public function _createContent (& $toReturn)
    {
      $ppo = new CopixPPO ();

    $toReturn = $this->_usePPO ($ppo, '_accounts_info.tpl');
  }
}