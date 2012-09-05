<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty {adress_map} function plugin
 *
 * @author Christophe Beyer <cbeyer@cap-tic.fr>
 * @since 2011/11/02
 * @param string $address Adresse a afficher, sur une ligne
 * @param size Taille a utiliser, au format <largeur>x<hauteur> en pixels. Si vide, prend 200x200 par défaut
 */
function smarty_function_adress_map ($params, &$smarty)
{

    $address = (isset($params['address'])) ? $params['address'] : null;

    if (!$address)
        return '';

    $size = (isset($params['size'])) ? $params['size'] : '200x200';

    $url = 'http://maps.googleapis.com/maps/api/staticmap?size='.$size.'&markers=color:blue|'.urlencode($address).'&maptype=roadmap&zoom=13&sensor=false';

    $res = '';
    //$res .= '<a href="'.$url.'">'.$url.'</a> ';
    $res .= '<img src="'.$url.'" alt="Google Map" />';
    return $res;

}


