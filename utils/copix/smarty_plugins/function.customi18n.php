<?php
/**
 * @package 	copix
 * @subpackage	smarty_plugins
 * @author		Jérémy FOURNAISE
 * @copyright	2011 - Isics
 * @link		http://www.isics.fr
*/

/**
 * Plugin smarty type fonction
 * Purpose:  I18N interface for CopiX.
 *
 * Input:    key      = (required  name of the select box
 *           bundle   = (optional) values to display the values captions will be
 *                        html_escaped, not the ids
 *           lang      = (optional) id of the selected element
 *           assign   = (optional) name of the template variable we'll assign
 *                      the output to instead of displaying it directly
 *
 * Examples:
 */
function smarty_function_customi18n($params, & $me)
{
    if (isset ($params['assign'])){
        $assignVar = $params['assign'];
        unset ($params['assign']);
        $me->assign ($assignVar, _tag ('customi18n', $params));
        return;
    }else{
    $res = _tag ('customi18n', $params);
    if (isset ($params['addslashes']) && $params['addslashes'])
      $res = addslashes($res);
        return $res;
    }
}
