<?php

/**
 * Fonctions diverses du module Fiches ecoles
 *
 * @package Iconito
 * @subpackage Fichesecoles
 */
class FichesEcolesService
{
    /**
     * Determine si l'usager peut afficher ou modifier la fiche d'une ecole
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2008/08/04
     * @param integer $pId Id de l'ecole
     * @param string $pAction Indication eventuelle sur une action precise.
     * @return boolean True s'il a le droit, false sinon
     */
    public function canMakeInFicheEcole ($pId, $pAction)
    {
        $can = false;

        //echo "canMakeInFicheEcole ($pId, $pAction)";
        $session = Kernel::getSessionBU();
        $myNodes = Kernel::getMyNodes();

        if ($pAction=="VIEW")
            return true;

        //var_dump($myNodes);
        //var_dump($session);

        switch ($session['type']) {
            case 'USER_ENS':
                foreach ($myNodes as $node) {
                    if ($node->type=='BU_ECOLE' && $node->id==$pId && $node->droit>=70) {
                        if ($pAction == 'MODIFY')
                            $can = true;
                    }
                }
                break;
            case 'USER_VIL':
                if ($pAction == 'MODIFY_VILLE') // Court-circuite du reste, canMakeInFicheEcole(MODIFY) ayant forcement ete fait avant
                    return true;

                foreach ($myNodes as $node) {
                    if ($node->type=='BU_ECOLE' && $node->id==$pId && $node->droit>=70)
                        $can = true;
                }
                if (!$can) { // On verifie si l'ecole est dans sa ville
                    $rEcole = Kernel::getNodeInfo ('BU_ECOLE', $pId, false);
                    reset ($myNodes);
                    //var_dump($myNodes);
                    //var_dump($rEcole['ALL']);
                    foreach ($myNodes as $node) {
                        if ($node->type=='BU_VILLE' && $node->id==$rEcole['ALL']->vil_id_vi && $node->droit>=70)
                            $can = true;
                    }
                }
                break;

        }

        return $can;
    }

    /**
     * Propage les infos d'une ville a toutes les ecoles de la ville
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2008/11/26
     * @param object $rEcole Recordset de l'ecole d'origine. On a besoin de ->id_ville
     * @param object $rForm Recordset de la fiche servant de reference. On a besoin de ->zone_ville_titre et ->zone_ville_texte
     * @return none
     */
    public function propageZoneVille ($rEcole, $rForm)
    {
        $daoSearchParams = _daoSp ();
        $daoSearchParams->addCondition ('ecole_id_ville', '=', $rEcole->id_ville);
        $dao = _dao('fichesecoles|fiches_ecoles');
        $arFiches  = $dao->findBy ($daoSearchParams);
        foreach ($arFiches as $fiche) {
            $fiche->zone_ville_titre = $rForm->zone_ville_titre;
            $fiche->zone_ville_texte = $rForm->zone_ville_texte;
            $dao->update ($fiche);
        }
    }

    /**
     * Renvoie la valeur courante des infos d'une ville
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2008/11/26
     * @param object $rEcole Recordset de l'ecole d'origine. On a besoin de ->id_ville
     * @return array Tableau avec [zone_ville_titre] et [zone_ville_texte]
     */
    public function getZoneVille ($rEcole)
    {
        //var_dump($rEcole);
        $res = array('zone_ville_titre'=>null, 'zone_ville_texte'=>null);
        $daoSearchParams = _daoSp ();
        $daoSearchParams->addCondition ('ecole_id_ville', '=', $rEcole->id_ville);
        $dao = _dao('fichesecoles|fiches_ecoles');
        $arFiches = $dao->findBy ($daoSearchParams);
        foreach ($arFiches as $r) {
            $res['zone_ville_titre'] = $r->zone_ville_titre;
            $res['zone_ville_texte'] = $r->zone_ville_texte;
            break;
        }
        return $res;
    }


}


