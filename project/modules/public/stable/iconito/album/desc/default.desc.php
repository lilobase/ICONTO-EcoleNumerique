<?php
/**
* @package	copix
* @subpackage exemple
* @version   $Id: default.desc.php,v 1.8 2007-07-04 10:20:20 fmossmann Exp $
* @author	Jouanneau Laurent, see copix.aston.fr for other contributors.
* @copyright 2001-2005 CopixTeam
* @link      http://copix.aston.fr
* @link      http://copix.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

$list = new CopixAction ('Album', 'getList');
$album = new CopixAction ('Album', 'getAlbum');
$modalbum = new CopixAction ('Album', 'getModAlbum');
$addalbum = new CopixAction ('Album', 'getAddAlbum');
$delalbum = new CopixAction ('Album', 'getDelAlbum');
$photo = new CopixAction ('Album', 'getPhoto');
$modphoto = new CopixAction ('Album', 'getModPhoto');
$addphoto = new CopixAction ('Album', 'getAddPhoto');
$doaddphoto = new CopixAction ('Album', 'doAddPhoto');
$delphoto = new CopixAction ('Album', 'getDelPhoto');
$dodelphoto = new CopixAction ('Album', 'doDelPhoto');
$file = new CopixAction ('Album', 'getFile');
$vignettes = new CopixAction ('Album', 'vignettes');

$publier = new CopixAction ('Album', 'doPublier');
$depublier = new CopixAction ('Album', 'doDepublier');

$editphotos = new CopixAction ('Album', 'getEditPhotos');
$doeditphotos = new CopixAction ('Album', 'doEditPhotos');

$dofolder = new CopixAction ('Album', 'doFolder');

$addzip = new CopixAction ('Album', 'getAddZip');
$doaddzip = new CopixAction ('Album', 'processDoAddZip');


$debug = new CopixAction ('Album', 'debug');

$go = new CopixAction ('Album', 'go');

$popup = new CopixAction ('Album', 'popup');
$getpopup = new CopixAction ('Album', 'getPopup');

$default    = & $list;
