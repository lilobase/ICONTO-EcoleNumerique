<?php

/**
 * Zone qui affiche la liste déroulante avec toutes les classes d'une ville
 *
 * @package Iconito
 * @subpackage	Annuaire
 */
class ZoneComboClassesInVille extends CopixZone
{
    /**
     * Affiche la liste déroulante avec toutes les classes d'une ville
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2006/01/06
     * @param integer $ville Id de la ville
     * @param integer $value Valeur actuelle de la combo
     * @param string $fieldName Nom du champ de type SELECT qui en résulte
     * @param string $attribs Attributs HTML de la liste (STYLE, ONCHANGE...)
     * @param array $linesSup Lignes supplémentaires à ajouter en haut de la liste au-dessus des dossiers (ex: "Choisissez l'école"). Chaque ligne est un tableau, de type array ("value"=>"", "libelle"=>"Choisissez")
     */
    public function _createContent (&$toReturn)
    {
        $annuaireService = & CopixClassesFactory::Create ('annuaire|AnnuaireService');

        $ville = ($this->getParam('ville')) ? $this->getParam('ville') : NULL;
        $value = ($this->getParam('value')) ? $this->getParam('value') : 0;
        $fieldName = ($this->getParam('fieldName')) ? $this->getParam('fieldName') : NULL;
        $attribs = ($this->getParam('attribs')) ? $this->getParam('attribs') : NULL;
        $linesSup = ($this->getParam('linesSup')) ? $this->getParam('linesSup') : NULL;

        $classes = $annuaireService->getClassesInVille ($ville, array('getNodeInfo_light'=>1));
         //echo "<pre>"; print_r($classes); die();
        $tpl = new CopixTpl ();
        $tpl->assign('items', $classes);
        $tpl->assign('value', $value);
        $tpl->assign('fieldName', $fieldName);
        $tpl->assign('attribs', $attribs);
        $tpl->assign('linesSup', $linesSup);

    $toReturn = $tpl->fetch ('comboclasses.tpl');
    return true;
    }

}


