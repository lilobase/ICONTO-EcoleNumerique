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
_classInclude ('prefs|prefs');
_classInclude ('logs|logs');

class PluginTheme extends CopixPlugin
{
    public function beforeProcess (&$execParam)
    {
            CopixTpl::setTheme(Kernel::getTheme());
    }

        function beforeDisplay (& $display)
        {
            $HTTP_USER_AGENT = (isset($_SERVER["HTTP_USER_AGENT"])) ? $_SERVER["HTTP_USER_AGENT"] : '';
            if (preg_match("/MSIE 6.0/", $HTTP_USER_AGENT)) {
                CopixHTMLHeader::addCSSLink (_resource("styles/theme_ie.css"));
                CopixHTMLHeader::addCSSLink (_resource("styles/theme_ie6.css"));
            } elseif (preg_match("/MSIE 7.0/", $HTTP_USER_AGENT)) {
                CopixHTMLHeader::addCSSLink (_resource("styles/theme_ie.css"));
                CopixHTMLHeader::addCSSLink (_resource("styles/theme_ie7.css"));
            }
        }

}

