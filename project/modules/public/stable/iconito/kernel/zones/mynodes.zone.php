<?php


class ZoneMyNodes extends CopixZone {

	function _createContent (&$toReturn) {
		$tpl = & new CopixTpl ();
		$toReturn = "";
		
		if( !_currentUser()->isConnected() ) return true;
		
		$data_old = Kernel::getMyNodes();
		foreach( $data_old AS $key=>$val ) {
			$data_new[$val->type][$val->id] = $val;
		}
		
		// die( "<pre>".print_r( $data_new, true )."</pre>" );
		
		$tpl->assign('nodes', $data_new);
		
		if( _currentUser()->getExtraHome('type') && _currentUser()->getExtraHome('id') ) {
			$tpl->assign('home', _currentUser()->getExtraHome('type')."/"._currentUser()->getExtraHome('id'));
		}
		
		if (_currentUser()->getExtraHome('type')) {
			$modules = Kernel::getModEnabled( _currentUser()->getExtraHome('type'), _currentUser()->getExtraHome('id') );
			$tpl->assign('modules', $modules);
		}
		
    $toReturn = $tpl->fetch ('getmynodes.tpl');
    return true;

	}



}
?>
