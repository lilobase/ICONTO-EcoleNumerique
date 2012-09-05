<?php

/**
* @version   $Id: function.icon.php 37 2009-08-10 10:34:42Z cbeyer $
* @author   Christophe Beyer <cbeyer@cap-tic.fr>
* @copyright CAP-TIC
* @link      http://www.cap-tic.fr
*/


/**
 * Icones pour differentes actions
 *
 * @author Christophe Beyer <cbeyer@cap-tic.fr>
 * @param string $action Action correspondante : modify,
 * @param string $title Titre de l'action. Sera place en TITLE sur l'image
 * @param boolean $legende Si on veut afficher la legende pluttot que l'image (<IMG...>). Par defaut : false
 * @since 2009/03/14
 */

function smarty_function_icon ($params, &$smarty)
{
    $img = false;
    $folder = 'webicons2';
    switch ($params['action']) {
    /*
        case "modify" : $img = "Modify.png"; $title = "Modifier"; break;
        case "delete" : $img = "Delete.png"; $title = "Supprimer"; break;
        case "add" : $img = "Add.png"; $title = "Ajouter"; break;
        case "details" : $img = "Info.png"; $title = "D&eacute;tails"; break;
        case "search" : $img = "Search.png"; $title = "Rechercher"; break;
        case "stats" : $img = "Bar-Chart.png"; $title = "Stats"; break;
        case "back" : $img = "Back.png"; $title = "Retour"; break;
        case "warning" : $img = "Warning.png"; $title = "Warning"; break;
        case "next" : $img = "Next.png"; $title = "Suite"; break;
        */
        case "modify" : $img = $folder."/reply.gif"; $title = "Modifier"; break;
        case "delete" : $img = $folder."/action_delete.gif"; $title = "Supprimer"; break;
        case "add" : $img = $folder."/action_add.gif"; $title = "Ajouter"; break;
        case "details" : $img = $folder."/file.gif"; $title = "D&eacute;tails"; break;
        case "search" : $img = $folder."/search.gif"; $title = "Rechercher"; break;
        case "stats" : $img = "webicons1/Bar-Chart.png"; $title = "Stats"; break;
        case "back" : $img = $folder."/arrow_back.gif"; $title = "Retour"; break;
        case "warning" : $img = "webicons1/Warning.png"; $title = "Warning"; break;
        case "next" : $img = $folder."/arrow_next.gif"; $title = "Suite"; break;
        case "checked" : $img = $folder."/action_check.gif"; $title = ""; break;
        case "print" : $img = "coquette3/printer.png"; $title = "Imprimer"; break;
    }
    $width = $height = 16;
    if ($params['size'])
        list ($width,$height) = explode('x',$params['size']);


    $title = ($params['title']) ? $params['title'] : $title;

    $res = '';
    $src = CopixUrl::getResource('img/'.$img);
    if ($img && $params['legende'])
        $res = $title;
    elseif ($img)
        $res = '<img src="'.$src.'" width="'.$width.'" height="'.$height.'" alt="'.$img.'" title="'.$title.'" />';
    else
        $res = 'smarty_function_icon : ERROR (action='.$params['action'].')';

    if (isset ($params['assign'])) {
        $smarty->assign($params['assign'], $res);
        return '';
    } elseif (isset ($params['src'])) {
        return $src;
    } else{
        return $res;
    }

}
