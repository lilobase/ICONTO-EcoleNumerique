<?php
/**
* @package		inscription
* @author	Christophe Beyer
* @copyright 2007 CAP-TIC
* @link      http://www.cap-tic.fr
* @license  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

/**
 * Les champs d'un groupe
 * @package inscription
 * @param integer $groupe Id du groupe de champs
 * @param integer $parent Id du parent de champs
 * @param array $values Valeurs pour remplir/cocher. Cle:index du champ; Valeur=valeur du champ
 * @param varchar $benef_type Type de beneficiaire concerne (eleve|responsable)
 * @since 2009/07/13
 */

class ZoneInfos_benef_champs extends CopixZone {
   function _createContent (&$toReturn){
	 		
			$pGroupe = $this->getParam ('groupe');
			$pParent = $this->getParam ('parent');
			$pValues = $this->getParam ('values');
	 		$pBenefType = $this->getParam ('benef_type');
			$pSuffixe = $this->getParam ('suffixe');
			
	 		$ppo = new CopixPPO ();
			$ppo->values = $pValues;
			$ppo->default = array();
			$ppo->suffixe = $pSuffixe;
			
			$sql = "SELECT CH.* FROM pe_infos_champs CH WHERE CH.groupe=:groupe AND CH.parent=:parent AND (CH.benef_type IS NULL OR CH.benef_type=:benef_type)";
			$ppo->list = _doQuery ($sql, array(':groupe'=>$pGroupe, ':parent'=>$pParent, ':benef_type'=>$pBenefType));
			
			// On remplit les eventuelles valeurs par defaut
			foreach ($ppo->list as $champ) {
				if ($champ->par_defaut && !isset($ppo->values[$champ->id]))	{
					$ppo->values[$champ->id] = $champ->par_defaut;
					$ppo->default[] = $champ->id;
				}
			}
			
			//print_r($ppo->default);
			
			$tpl = new CopixTpl();
			$tpl->assign('ppo', $ppo);
      $toReturn = $tpl->fetch('instruction|infos_benef_champs.tpl');

      return true;
   }
}


?>