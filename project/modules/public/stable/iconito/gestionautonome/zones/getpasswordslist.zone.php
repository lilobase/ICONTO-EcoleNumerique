<?php
/**
* @package    Iconito
* @subpackage Gestionautonome
* @author     Sébastien CAS
*/
class ZoneGetPasswordsList extends CopixZone
{
    public function _createContent (& $toReturn)
    {
      $toReturn = '';

      $passwordsList = _sessionGet ('modules|gestionautonome|passwordsList');
      if (!empty($passwordsList)) {

        foreach ($passwordsList as $line) {

          if (!empty($line)) {

            $ppo = new CopixPPO ();
            $toReturn = $this->_usePPO ($ppo, '_get_passwords_list.tpl');
            break;
          }
        }
      }
  }
}