<?php

/**
* @version   $Id: function.tooltip.php 37 2009-08-10 10:34:42Z cbeyer $
* @author   Christophe Beyer <cbeyer@cap-tic.fr>
* @copyright CAP-TIC
* @link      http://www.cap-tic.fr
*/


/**
 * Tooltip
 *
 * @author Christophe Beyer <cbeyer@cap-tic.fr>
 * @param string $text Texte a afficher
 * @param string $text_tooltip Texte a afficher dans la tooltip
 * @param string $assign Si assignation dans une variable
 * @since 2009/08/10
 */

function smarty_function_tooltip ($params, &$smarty)
{
    $res = '<a class="tooltip" href="javascript:none();">'.$params['text'].'<em><span></span>'.$params['text_tooltip'].'</em></a>
    ';

    if (isset ($params['assign'])) {
        $smarty->assign($params['assign'], $res);
        return '';
    } else{
        return $res;
    }

}
