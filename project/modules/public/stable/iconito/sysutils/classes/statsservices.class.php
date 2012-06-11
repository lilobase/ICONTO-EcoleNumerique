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

    function human2octets($chaine)
    {
        $ok = preg_match('/^(\d+)([K|k|M|m|G|g])/', $chaine, $size);
        if ($ok) {
            switch($size[2]) {
                case "k":
                case "K":
                    return (int)($size[1]*1024);
                case "m":
                case "M":
                    return (int)($size[1]*1024*1024);
                case "g":
                case "G":
                    return (int)($size[1]*1024*1024*1024);
                default :
                    return $chaine;
            }
        } else {
            return $chaine;
        }
    }

}
