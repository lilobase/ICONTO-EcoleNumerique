<?php
/**
* @package		inscription
* @author	Christophe Beyer
* @copyright 2007 CAP-TIC
* @link      http://www.cap-tic.fr
* @license  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

/**
 * Les choix d'un champ
 * @package inscription
 * @param object $champ Recordset du champ
 * @param array $values Valeurs pour remplir/cocher. Cle:index du champ; Valeur=valeur du champ
 * @since 2009/07/15
 */

class ZoneInfos_benef_choix extends CopixZone {
   function _createContent (&$toReturn){
	 		
			$pChamp = $this->getParam ('champ');
	 		$pValues = $this->getParam ('values');
			
	 		$ppo = new CopixPPO ();
			$ppo->rChamp = $pChamp;
			$ppo->values = $pValues;
			
			$sql = "SELECT CH.* FROM peri_infos_benef_choix CH WHERE CH.champ=:champ";
			$ppo->list = _doQuery ($sql, array(':champ'=>$pChamp->id));
			
			//print_r($ppo->list);
			
			$tpl = new CopixTpl();
			$tpl->assign('ppo', $ppo);
      $toReturn = $tpl->fetch('inscription|infos_benef_choix.tpl');

      return true;
   }
}


?>