<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty strip_tags modifier plugin
 *
 * Type:     modifier<br>
 * Name:     strip_tags<br>
 * Purpose:  strip html tags from text
 * @link http://smarty.php.net/manual/en/language.modifier.strip.tags.php
 *          strip_tags (Smarty online manual)
 * @author   Monte Ohrt <monte at ohrt dot com>
 * @param string
 * @param boolean
 * @return string
 */
function smarty_modifier_strip_tags($string, $replace_with_space = true)
{
    switch(func_num_args()) {
        case 1:
            $replace_with_space = true;
            break;
        case 2:
            $arg = func_get_arg(1);
            if($arg === 1 || $arg === true || $arg === '1' || $arg === 'true') {
                // for full legacy support || $arg === 'false' should be included
                $replace_with_space = true;
                $allowable_tags = '';
            } elseif($arg === 0 || $arg === false || $arg === '0' || $arg === 'false') {
                // for full legacy support || $arg === 'false' should be removed
                $replace_with_space = false;
                $allowable_tags = '';
            } else {
                $replace_with_space = true;
                $allowable_tags = $arg;
            }
            break;
        case 3:
            $replace_with_space = func_get_arg(1);
            $allowable_tags = func_get_arg(2);
            break;
    }
 
    if($replace_with_space) {
        $string = preg_replace('!(<[^>]*?>)!', '$1 ', $string);
    }
 
    $string = strip_tags($string, $allowable_tags);
 
    if($replace_with_space) {
        $string = preg_replace('!(<[^>]*?>) !', '$1', $string);
    }
 
    return $string;
}

/* vim: set expandtab: */

