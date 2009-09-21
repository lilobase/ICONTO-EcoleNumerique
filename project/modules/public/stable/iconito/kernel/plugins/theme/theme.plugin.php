<?php

/**
 * Plugin gerant le theme utilise
 * 
 * @package	Iconito
 * @subpackage kernel
 * @version   $Id: prefs.actiongroup.php,v 1.8 2007-12-20 09:46:27 fmossmann Exp $
 * @author   Christophe Beyer <cbeyer@cap-tic.fr>
 * @copyright 2009 CAP-TIC
 * @link      http://www.cap-tic.fr
 * @since 2009/09/21
*/

_classInclude ('kernel|kernel');

class PluginTheme extends CopixPlugin {

    function beforeProcess (&$execParam){
			CopixTpl::setTheme(Kernel::getTheme());
    }

}

?>
