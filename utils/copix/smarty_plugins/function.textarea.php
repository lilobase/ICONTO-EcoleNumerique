<?php
/**
 * @package 	copix
 * @subpackage	smarty_plugins
 * @author		Christophe Beyer <cbeyer@cap-tic.fr>
 * @copyright	CAP-TIC
 * @link			http://www.cap-tic.fr
 * @since 2009/08/13
 */

/**
 * Plugin smarty type fonction
 * Generation d'un textarea
 *
 */
function smarty_function_textarea($params, $me)
{
    if (isset ($params['assign'])) {
        $me->assign($params['assign'], _tag ('textarea', $params));
    }else {
        return _tag ('textarea', $params);
    }
}

