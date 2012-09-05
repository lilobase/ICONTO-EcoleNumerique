<?php

/**
* @version   $Id: function.copixconf.php,v 1.1 2009-07-23 13:27:00 cbeyer Exp $
* @author   Christophe Beyer <cbeyer@cap-tic.fr>
* @copyright CAP-TIC
* @link      http://www.cap-tic.fr
*/


/**
 * Valeur de la conf pour un parametre
 *
 * @author Christophe Beyer <cbeyer@cap-tic.fr>
 * @param string $parameter
 * @since 2009/08/13
 */

function smarty_function_copixconf ($params, &$smarty)
{
    $conf = (CopixConfig::exists($params['parameter'])) ? CopixConfig::get($params['parameter']) : null;

    if (isset ($params['assign'])){
        $assignVar = $params['assign'];
        //unset ($params['assign']);
        $smarty->assign ($assignVar, $conf);
        return;
    } else
        return $conf;

}
