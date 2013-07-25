<?php
/**
* @package    Iconito
* @subpackage Cahierdetextes
* @author     Jérémy FOURNAISE
*/
class ZoneAffichageMemo extends CopixZone
{
    public function _createContent (& $toReturn)
    {
      // Récupération des paramètres
      $ppo  = $this->getParam('ppo');
      $ppo->memo = $this->getParam('memo');

      $toReturn = $this->_usePPO ($ppo, '_memo'.($ppo->memoContext == 'ecole' ? '_directeur' : '').'.tpl');
  }
}