<?php
/**
* @package   petiteenfance
* @subpackage kernel
* @version   $Id: combo_structure.zone.php 63 2009-08-18 14:07:04Z cbeyer $
* @author   Christophe Beyer <cbeyer@cap-tic.fr>
* @copyright CAP-TIC
* @link      http://www.cap-tic.fr
*/

/**
 * Combo avec les structures
 * @author Christophe Beyer <cbeyer@cap-tic.fr>
 * @since 2009/08/10
 * @param string $name Nom HTML de la combo
 * @param string $selected Valeur courante selectionnee
 * @param string $extra (option) HTML supplementaire
 */
class ZoneCombo_structure extends CopixZone {
   function _createContent (&$toReturn){
	 		
			$tpl = new CopixTpl();
			
			$pName = $this->getParam ('name');
			$pSelected = $this->getParam ('selected');
			$pExtra = $this->getParam ('extra');
			
			$criteres = _daoSp ()->orderBy ('nom');
			$list = _ioDAO ('kernel|structure')->findBy ($criteres);
			
			$tpl->assign('list', $list);
			$tpl->assign('name', $pName);
			$tpl->assign('selected', $pSelected);
			
      $toReturn = $tpl->fetch('kernel|combo_structure.tpl');

      return true;
   }
}


?>