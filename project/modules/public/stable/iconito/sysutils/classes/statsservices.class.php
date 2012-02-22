<?php

/**
 * Admin - Classes
 *
 * @package	Iconito
 * @subpackage  Admin
 * @version     $Id: statsservices.class.php,v 1.1 2007-03-20 10:53:13 cbeyer Exp $
 * @author      Christophe Beyer <cbeyer@cap-tic.fr>
 * @copyright   2007 CAP-TIC
 * @link        http://www.cap-tic.fr
 */
class StatsServices
{

    function human_file_size($size)
    {
        $filesizename = array(" Bytes", " KB", " MB", " GB", " TB", " PB", " EB", " ZB", " YB");
        return ($size) ? round($size / pow(1024, ($i = floor(log($size, 1024)))), 2) .
            $filesizename[$i] : "0";
    }

}
