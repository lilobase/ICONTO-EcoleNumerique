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

function smarty_function_help ($params, &$smarty)
{
    if (empty($params['mode'])) {
      $smarty->trigger_error("mailto: missing 'text' parameter");
    return;
  } else {
      $mode = trim($params['mode']);
  }

    switch ($mode) {
        case 'tooltip' :
      include_once (COPIX_PROJECT_PATH.'../utils/copix/smarty_plugins/function.tooltip.php');
      $res = '';
      $text_tooltip = isset($params['text_i18n']) ? CopixI18N::get($params['text_i18n']) : $params['text'];
      $res .= smarty_function_tooltip (array(
        'text' => '<img width="14" height="14" hspace="2" src="'.CopixUrl::getResource ("img/aide/bulle.gif").'" border="0" alt="'.htmlentities(CopixI18N::get ('kernel|kernel.help')).'" title="'.htmlentities(CopixI18N::get ('kernel|kernel.help')).'" />',
        'text_tooltip' => $text_tooltip,
      ),$smarty);
            break;

    }

    return $res;
}

