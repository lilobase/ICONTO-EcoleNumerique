<?php


/**
 * plugin Smarty "render" permettant de convertir en HTML du texte a partir d'un format gere par l'application
 * Example:  {$text|render:"wiki"}  {$text|render:"dokuwiki"}
 * @author Christophe Beyer <cbeyer@cap-tic.fr>
 * @since 2008/12/09
 * @param string $string Chaine a "rendre"
 * @param string $format a utiliser. Si le format n'est pas gere, on affiche une erreur
 * @return string
 */

function smarty_modifier_render ($string, $format)
{
    switch ($format) {
        case "dokuwiki" :
            require_once('modifier.dokuwiki.php');
            $res = smarty_modifier_dokuwiki ($string);
            break;
        case "wiki" :
            require_once('modifier.wiki.php');
            $res = smarty_modifier_wiki ($string);
            break;
        case "fckeditor" :
        case "html" :
        case "ckeditor" :
            $res = $string;
            break;
        default :
            $res = "Error / smarty_modifier_render / ".$format;
            break;
    }
    return $res;
}

