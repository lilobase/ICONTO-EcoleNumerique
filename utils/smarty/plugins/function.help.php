<?php

/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty {help} function plugin
 *
 * Type:     function<br>
 * Name:     help<br>
 */

function smarty_function_help ($params, &$smarty) {

	if (empty($params['mode'])) {
  	$smarty->trigger_error("mailto: missing 'text' parameter");
    return;
  } else {
  	$mode = trim($params['mode']);
  }

	switch ($mode) {
		case 'bulle' :
			$res = '';
			$code = 'help'.mt_rand();
			$res .= '<span style="visibility: hidden; display: none;" ID="'.$code.'"><div align="RIGHT" style="font-size:80%;"><a href="#" onClick="return hideHelp();">'.CopixI18N::get ('kernel|kernel.popup.close').'</a></div>'.CopixI18N::get ($params['text']).'</span>';
			$res .= '<a href="javascript:viewHelp(\''.$code.'\');"><img width="14" height="14" hspace="2" src="'.CopixUrl::getResource ("img/aide/bulle.gif").'" border="0" alt="'.htmlentities(CopixI18N::get ('kernel|kernel.help')).'" title="'.htmlentities(CopixI18N::get ('kernel|kernel.help')).'" /></a>';
			break;
	}

	return $res;
}

?>

