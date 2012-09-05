<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty plugin
 *
 * Type:     modifier<br>
 * Name:     human_file_size<br>
 * Date:     Dec 08, 2005
 * Purpose:  convert file size (octets) in "human" file sizes (KB, MB...)
 * Input:<br>
 *         - contents = contents to replace
 * Example:  {$text|nl2br}
 * @link http://smarty.php.net/manual/en/language.modifier.nl2br.php
 *          nl2br (Smarty online manual)
 * @version  1.0
 * @author   Christophe Beyer <cbeyer at cap-tic dot fr>
 * @param integer
 * @return string
 */
function smarty_modifier_human_file_size($size)
{
    $filesizename = array(" Bytes", " KB", " MB", " GB", " TB", " PB", " EB",
" ZB", " YB");
    return ($size) ? round($size/pow(1024, ($i = floor(log($size, 1024)))), 2) .
$filesizename[$i] : "-";
}

/* vim: set expandtab: */

