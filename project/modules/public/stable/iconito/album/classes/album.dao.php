<?php
/**
* @package  Iconito
* @subpackage Album
* @version   $Id: album.dao.class.php,v 1.2 2006-01-16 16:57:32 fmossmann Exp $
* @author   Frédéric Mossmann
* @copyright 2005 CDC-TIC
* @link      http://www.cdc-tic.fr
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/



class DAOAlbum
{
    // Le nb de photos d'un album photos
    public function getNbPhotosInAlbum ($id_album)
    {
        $critere = 'SELECT COUNT(PHO.id) AS nb FROM module_album_photos PHO WHERE PHO.id_album='.$id_album.'';
        return _doQuery($critere);
    }


}




