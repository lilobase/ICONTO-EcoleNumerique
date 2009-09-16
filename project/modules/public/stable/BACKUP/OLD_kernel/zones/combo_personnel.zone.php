<?php
/**
* @package   petiteenfance
* @subpackage kernel
* @version   $Id: combo_personnel.zone.php,v 1.3 2009-04-08 14:42:52 cbeyer Exp $
* @author   Christophe Beyer <cbeyer@cap-tic.fr>
* @copyright CAP-TIC
* @link      http://www.cap-tic.fr
*/

/**
 * Combo avec le personnel (pour le SSO)
 * @param string $name Nom HTML de la combo
 * @param string $selected Valeur courante selectionnee
 * @param string $extra (option) HTML supplementaire
 * @param string $entite (option) Si on veut limiter a un role dans une entite
 * @since 2009/08/18
 */
class ZoneCombo_personnel extends CopixZone {
   function _createContent (&$toReturn){
	 		
			$tpl = new CopixTpl();
			
			$pName = $this->getParam ('name');
			$pSelected = $this->getParam ('selected');
			$pExtra = $this->getParam ('extra');
			$pDisabled = $this->getParam ('disabled');
			$pEntite = $this->getParam ('entite');
			
			$criteria = _daoSp ();
			if ($pEntite) {

	    	$criteria->orderBy ('nom', 'prenom');
				$criteria->addCondition('personnel_entite_type_ref', '=', $pEntite);
			  $list = _ioDAO ('kernel|personnel2entite','viescolaire')->findBy ($criteria);

			} else {
	    	$criteria->orderBy ('nom', 'prenom');
			  $list = _ioDAO ('kernel|personnel','viescolaire')->findBy ($criteria);
			}
			
			$tpl->assign('list', $list);
			$tpl->assign('name', $pName);
			$tpl->assign('selected', $pSelected);
			
      $toReturn = $tpl->fetch('kernel|combo_personnel.tpl');

      return true;
   }
}


?>