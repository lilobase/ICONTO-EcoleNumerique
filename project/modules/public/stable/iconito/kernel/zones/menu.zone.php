<?php
/**
 * @package petiteenfance
 * @subpackage kernel
 * @version   $Id: menu.zone.php 38 2009-08-10 15:22:15Z cbeyer $
 * @author   Christophe Beyer <cbeyer@cap-tic.fr>
 * @copyright CAP-TIC
 * @link      http://www.cap-tic.fr
 */

/**
 * Zone affichant le menu
 */
class ZoneMenu extends CopixZone {

	function _createContent (& $toReturn) {
			
		$ppo = new CopixPPO ();		
		$pMenu = $this->getParam ('MENU');
		
		// Si le menu est défini à partir d'un tableau, création du HTML pour affichage.
		if( is_array($pMenu) ) {
			$out = '';
			$sep = '';
			foreach( $pMenu AS $key=>$val ) {
				$out .= $sep; $sep=' :: ';
				
				$color = '';
				if( isset($val['color'])) $color=' style="color: '.$val['color'].'"';

				$target = '';
				if( isset($val['target'])) $color=' target="'.$val['target'].'"';
				
				if( isset($val['url']) && trim($val['url'])!="" ) $out .= '<a'.$color.' href="'.$val['url'].'">';
				$out .= $val['txt'];
				if( isset($val['url']) && trim($val['url'])!="" ) $out .= '</a>';
			}
			$ppo->menu = $out;
		} else {
			$ppo->menu = $pMenu;
		}

		
		
		
		$toReturn = $this->_usePPO ($ppo, 'menu.tpl');
		
		return true;
	}
} ?>
