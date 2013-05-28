<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

/**
 * Smarty {has_classroom_access} function plugin
 *
 * Type:     function<br>
 * Name:     assign<br>
 * Purpose:  check if a user can access to a module by its classroom access
 * @param array Format: array('var' => variable name, 'value' => value to assign)
 * @param Smarty
 */
function smarty_function_has_classroom_access($params, &$smarty)
{
    extract($params);

    if (!in_array('module', array_keys($params))) {
        $smarty->trigger_error("has_classroom_access: missing 'module' parameter");
        return;
    }

    _classInclude('classe|ClasseServices');
    $classeService = new ClasseServices();

    $smarty->assign('access', $classeService->aAcces($module));

}