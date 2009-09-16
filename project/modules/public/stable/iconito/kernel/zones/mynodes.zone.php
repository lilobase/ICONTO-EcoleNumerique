<?php


class ZoneMyNodes extends CopixZone {

	function _createContent (&$toReturn) {
		$tpl = & new CopixTpl ();
		$toReturn = "";
		
		if( !isset($_SESSION["user"]->bu ) ) return true;
		
		$data_old = Kernel::getMyNodes();
		foreach( $data_old AS $key=>$val ) {
			$data_new[$val->type][$val->id] = $val;
		}
		
		// die( "<pre>".print_r( $data_new, true )."</pre>" );
		
		$tpl->assign('nodes', $data_new);
		
		if( isset($_SESSION["user"]->home) && isset($_SESSION["user"]->home["type"]) && isset($_SESSION["user"]->home["id"]) ) {
			$tpl->assign('home', $_SESSION["user"]->home["type"]."/".$_SESSION["user"]->home["id"]);
		}
		
		if (isset($_SESSION["user"]->home["type"])) {
			$modules = Kernel::getModEnabled( $_SESSION["user"]->home["type"], $_SESSION["user"]->home["id"] );
			$tpl->assign('modules', $modules);
		}
		
    $toReturn = $tpl->fetch ('getmynodes.tpl');
    return true;

	}



}
?>
