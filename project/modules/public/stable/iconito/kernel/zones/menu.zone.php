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
		$pPopup = $this->getParam ('popup');
		$pCanClose = $this->getParam ('canClose', true); // Seulement si popup, true par defaut
		
		if ($pCanClose===null) $pCanClose = true;
		// Si le menu est defini a partir d'un tableau, creation du HTML pour affichage.
		if( is_array($pMenu) ) {
			$out = '';
			$sep = '';
			$out .= '<ul>';
			foreach( $pMenu AS $key=>$val ) {
								
				$color = '';
				if( isset($val['color'])) $color=' style="color: '.$val['color'].'"';

				$target = '';
				if( isset($val['target'])) $color=' target="'.$val['target'].'"';

				//get type for item, 'nd generate associate class
				$class = (isset($val['type'])) ? 'class="'.$val['type'].'"' : '';
				
				$out .= '<li>';
				if( isset($val['url']) && trim($val['url'])!="" ) $out .= '<a '.$class.' '.$color.' href="'.$val['url'].'">';
				$out .= '<span class="valign"></span>';
				$out .= '<span>'.$val['txt'].'</span>';
				if( isset($val['url']) && trim($val['url'])!="" ) $out .= '</a>';
				$out .= '</li>';
			}
			$out .= '</ul>';
			$ppo->menu = $out;
		} else {
			$ppo->menu = '<ul><li>'.$pMenu.'</li></ul>';
		}
		
		if ($pPopup && $pCanClose) {
			$ppo->menu .= ($ppo->menu) ? ' :: ' : '';
			$ppo->menu .= '<a href="javascript:self.close();">'.CopixI18N::get('kernel|kernel.popup.close').'</a>';
		}
		
		if (!isset($ppo->menu)) $ppo->menu = 'submenu is empty';
		
		$toReturn = $this->_usePPO ($ppo, 'menu.tpl');
		
		return true;
	}
} ?>
