<?php

/**
* @package   copix
* @subpackage plugins
* @version   $Id: stats.plugin.conf.php,v 1.1 2007-06-12 14:33:36 cbeyer Exp $
* @author   Christophe Beyer
* @copyright CAP-TIC
* @link      http://www.cap-tic.fr
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

class PluginConfigStats
{
    /*
        Détermine si l'on active le cache.
        Si true, un seul passage dans une action précise n'est enregistrée par session
        Si false, chaque passage est enregistré (y compris les rafraîchissements successifs)
    */
    public $cache = true;
}

