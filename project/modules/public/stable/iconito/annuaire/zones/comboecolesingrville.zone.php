<?php

/**
 * Zone qui affiche la liste déroulante avec toutes les écoles d'un groupe de villes
 *
 * @package Iconito
 * @subpackage	Annuaire
 */
class ZoneComboEcolesInGrville extends CopixZone
{
    /**
     * Affiche la liste déroulante avec toutes les écoles d'un groupe de villes
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2006/01/06
     * @param integer $grville Id du groupe de ville
     * @param integer $value Valeur actuelle de la combo
     * @param string $fieldName Nom du champ de type SELECT qui en résulte
     * @param string $attribs Attributs HTML de la liste (STYLE, ONCHANGE...)
     * @param array $linesSup Lignes supplémentaires à ajouter en haut de la liste au-dessus des dossiers (ex: "Choisissez l'école"). Chaque ligne est un tableau, de type array ("value"=>"", "libelle"=>"Choisissez")
     * @param string $droit Si on ne veut afficher que les ecoles sur lesquelles l'usager a un certain droit (VOIR / COMM, ou voir / communiquer)
     */
    public function _createContent (&$toReturn)
    {
        $annuaireService = & CopixClassesFactory::Create ('annuaire|AnnuaireService');

        $grville = ($this->getParam('grville')) ? $this->getParam('grville') : NULL;
        $value = ($this->getParam('value')) ? $this->getParam('value') : 0;
        $fieldName = ($this->getParam('fieldName')) ? $this->getParam('fieldName') : NULL;
        $attribs = ($this->getParam('attribs')) ? $this->getParam('attribs') : NULL;
        $linesSup = ($this->getParam('linesSup')) ? $this->getParam('linesSup') : NULL;
        $pDroit = $this->getParam('droit');

        $ecoles = $annuaireService->getEcolesInGrville ($grville, array('droit'=>$pDroit));

        $tpl = new CopixTpl ();
        $tpl->assign('items', $ecoles);
        $tpl->assign('value', $value);
        $tpl->assign('fieldName', $fieldName);
        $tpl->assign('attribs', $attribs);
        $tpl->assign('linesSup', $linesSup);

    $toReturn = $tpl->fetch ('comboecoles.tpl');
    return true;
    }

}


