<?php
/**
* @package   petiteenfance
* @subpackage kernel
* @version   $Id: combo_demande_etat.zone.php 37 2009-08-10 10:34:42Z cbeyer $
* @author   Christophe Beyer <cbeyer@cap-tic.fr>
* @copyright CAP-TIC
* @link      http://www.cap-tic.fr
*/

/**
 * Combo avec les etats des demandes
 * @author Christophe Beyer <cbeyer@cap-tic.fr>
 * @since 2009/07/08
 * @param string $name Nom HTML de la combo
 * @param string $selected Valeur courante selectionnee
 * @param string $extra (option) HTML supplementaire
 */
class ZoneCombo_demande_etat extends CopixZone {
   function _createContent (&$toReturn){
	 		
			$tpl = new CopixTpl();
			
			$pName = $this->getParam ('name');
			$pSelected = $this->getParam ('selected');
			$pExtra = $this->getParam ('extra');
			
			$list = _ioDAO ('kernel|demande_etat')->findAll ();
			
			$tpl->assign('list', $list);
			$tpl->assign('name', $pName);
			$tpl->assign('selected', $pSelected);
			
      $toReturn = $tpl->fetch('kernel|combo_demande_etat.tpl');

      return true;
   }
}


?>