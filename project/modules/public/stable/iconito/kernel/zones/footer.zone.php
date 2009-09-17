<?php


class ZoneFooter extends CopixZone {

	function _createContent (&$toReturn) {
		$tpl = & new CopixTpl ();
		$toReturn = "";
		
		// Si le fichier de template n'existe pas, pas d'erreur.
		$tpl_file = 'modules/kernel/templates/footer.tpl';
		if( !file_exists( $tpl_file ) ) return true;
		
		// Récupération des infos de géolocalisation.
		$whereami = Kernel::whereAmI();
		
		if( isset( $whereami['BU_GRVILLE'] ) ) {
			$tpl->assign('grville_id', $whereami['BU_GRVILLE']['id'] );
			$tpl->assign('grville_nom', $whereami['BU_GRVILLE']['nom'] );
		}
		
		if( isset( $whereami['BU_VILLE'] ) ) {
			$tpl->assign('ville_id', $whereami['BU_VILLE']['id'] );
			$tpl->assign('ville_nom', $whereami['BU_VILLE']['nom'] );
		}
		
		if( isset( $whereami['BU_ECOLE'] ) ) {
			$tpl->assign('ecole_id', $whereami['BU_ECOLE']['id'] );
			$tpl->assign('ecole_nom', $whereami['BU_ECOLE']['nom'] );
		}
		
		if( isset( $whereami['BU_CLASSE'] ) ) {
			$tpl->assign('classe_id', $whereami['BU_CLASSE']['id'] );
			$tpl->assign('classe_nom', $whereami['BU_CLASSE']['nom'] );
		}
		
		// Récupération des valeurs d'URL.
		if( isset( $_GET['module'] ) ) {
			$tpl->assign('module', $_GET['module']);
		}
		
		if( isset( $_GET['action'] ) ) {
			$tpl->assign('action', $_GET['action']);
		}
		
		// Récupération des valeurs de sessions (personne).
		if( isset($_SESSION['user']) && isset($_SESSION['user']->bu) && (_currentUser()->getExtra('type')) ) {
			$tpl->assign('user_type', _currentUser()->getExtra('type') );
			$tpl->assign('user_id', $_SESSION['user']->bu['id'] );
		}
		
		$toReturn = $tpl->fetch ('kernel|footer.tpl');
		return true;
	}



}
?>
