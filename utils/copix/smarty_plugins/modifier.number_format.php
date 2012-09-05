<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

_classInclude ('Kernel|Tools');

/**
 * Smarty plugin
 *
 * Type:     modifier<br>
 * Name:     number_format<br>
 * Purpose:  Convertit un nombre au format standard
 * @link http://fr.php.net/manual/fr/function.number-format.php
 * @author  Christophe Beyer <cbeyer at cap-tic dot fr>
 * @param decimal $number Nombre
 * @param string $format Format, number par defaut. Si words, affiche la somme en toutes lettres
 * @return string
 */
function smarty_modifier_number_format ($number, $format='number')
{
    return Tools::format_somme ($number, $format);
}

/* vim: set expandtab: */

