<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


// Pour une date extraite de la base, au format AAAA-MM-JJ, renvoie la date bien formatee JJ/MM/AAAA avec une indication de l'age de a personne.
// On suppose donc que la date passee en parametre est une date de naissance
// L'age indique le nb d'annees et de mois

function smarty_modifier_date_age ($string)
{
    if (preg_match("/^([0-9]{4})-?([0-9]{2})-?([0-9]{2})$/", $string, $regs)) {

        //$res = "($string) ";
        $res = "";

        $toDayY = date("Y");
        $toDayM = date("m");
        $toDayD = date("d");

        $y = $regs[1];
        $m = $regs[2];
        $d = $regs[3];

        $res .= $d.'/'.$m.'/'.$y;

        $nb_annees = $toDayY - $y;
        $nb_mois = $toDayM - $m;
        $nb_jours = $toDayD - $d;

        if ($nb_mois<0) {
            $nb_mois = 12-($nb_mois*-1);
            $nb_annees--;
        } elseif ($nb_mois==0 && $nb_jours<0) { // Anniv a venir dans le mois
            $nb_mois = 11;
            $nb_annees--;
        }

        $res .= ' - '.$nb_annees.' ans';
        if ($nb_mois)
            $res .= ' et '.$nb_mois.' mois';

    } else
        $res = $string;

    return $res;
}


