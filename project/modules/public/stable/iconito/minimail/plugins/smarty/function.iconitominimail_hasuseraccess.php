<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

/**
 * Smarty {iconitominimail_hasuseraccess} function plugin
 *
 * @param array Format: array('var' => variable name, 'value' => value to assign)
 * @param Smarty
 *
 * @return bool
 */
function smarty_function_iconitominimail_hasuseraccess($params, &$smarty)
{
    _classInclude('minimail|MinimailService');

    return MinimailService::hasUserAccess();
}
