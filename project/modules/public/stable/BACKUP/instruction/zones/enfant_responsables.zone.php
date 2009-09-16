<?php
/**
* @package   petiteenfance
* @subpackage instruction
* @version   $Id: enfant_responsables.zone.php,v 1.2 2009-06-09 15:15:22 cbeyer Exp $
* @author   Christophe Beyer <cbeyer@cap-tic.fr>
* @copyright CAP-TIC
* @link      http://www.cap-tic.fr
*/

/**
 * Les responsables d'un enfant
 * @param integer $enfant Id de l'enfant
 * @param integer $auth_parentale Si on veut limiter a ceux ayant l'autorite parentale (1 ou 0)
 * @since 2009/08/17
 */
class ZoneEnfant_responsables extends CopixZone {
   function _createContent (&$toReturn){
	 		
			$tpl = new CopixTpl();
			
			$pId = $this->getParam ('enfant');
			$pAuthParentale = $this->getParam ('auth_parentale');

			$filtre = array();
			
			if (strlen($pAuthParentale))
				$filtre['auth_parentale'] = $pAuthParentale;
			
			$getEleveResponsables = _ioDAO('kernel|responsable')->getEleveResponsables($pId, $filtre);
			
			$list = array();
			
			//Tools::print_r2($list);
			foreach ($getEleveResponsables as $resp) {
			
				$infosValeurs = _dao ('kernel|infos_valeurs')->getForBenef ($resp->responsables_type, $resp->id);
				$resp->infosValeurs = $infosValeurs;
				$list[] = $resp;
			}
			
			$tpl->assign('list', $list);
			
      $toReturn = $tpl->fetch('instruction|enfant_responsables.tpl');

      return true;
   }
}


?>