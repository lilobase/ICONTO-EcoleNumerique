<?php
/**
* @package petiteenfance
* @subpackage instruction
* @author	Christophe Beyer
* @copyright 2007 CAP-TIC
* @link      http://www.cap-tic.fr
* @license  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

/**
 * Les groupes d'infos d'un beneficiaire
 * @package inscription
 * @param array $values Valeurs pour remplir/cocher. Cle:index du champ; Valeur=valeur du champ
 * @param varchar $benef_type Type de beneficiaire concerne (eleve|responsable)
 * @since 2009/07/15
 */

class ZoneInfos_benef_groupes extends CopixZone {
   function _createContent (&$toReturn){
	 		
			$pValues = $this->getParam ('values');
			$pBenefType = $this->getParam ('benef_type');
			$pSuffixe = $this->getParam ('suffixe');
	 
			$ppo = new CopixPPO ();
			$ppo->values = $pValues;
			$ppo->suffixe = $pSuffixe;
			
			//$sql = "SELECT * FROM peri_infos_benef_groupes GR";
			$sql = "SELECT DISTINCT(GR.id), GR.* FROM pe_infos_groupes GR, pe_infos_champs CH WHERE CH.groupe=GR.id AND (CH.benef_type IS NULL OR CH.benef_type=:benef_type)";
			$ppo->list = _doQuery ($sql, array(':benef_type'=>$pBenefType));
		  $ppo->benef_type = $pBenefType;
			
			$tpl = new CopixTpl();
			$tpl->assign('ppo', $ppo);
      $toReturn = $tpl->fetch('instruction|infos_benef_groupes.tpl');

      return true;
   }
}


?>