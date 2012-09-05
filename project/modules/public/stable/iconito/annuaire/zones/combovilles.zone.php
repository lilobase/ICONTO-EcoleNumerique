<?php

/**
 * Zone qui affiche la liste déroulante avec toutes les villes d'un groupe de ville
 *
 * @package Iconito
 * @subpackage	Annuaire
 */
class ZoneComboVilles extends CopixZone
{
    /**
     * Affiche la liste déroulante avec toutes les villes d'un groupe de ville
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2006/01/17
     * @param integer $grville Id du groupe de ville
     * @param integer $value Valeur actuelle de la combo
     * @param string $fieldName Nom du champ de type SELECT qui en résulte
     * @param string $attribs Attributs HTML de la liste (STYLE, ONCHANGE...)
     * @param array $linesSup Lignes supplémentaires à ajouter en haut de la liste au-dessus des dossiers (ex: "Choisissez la ville"). Chaque ligne est un tableau, de type array ("value"=>"", "libelle"=>"Choisissez")
     * @param string $droit Si on ne veut afficher que les villes sur lesquelles l'usager a un certain droit (VOIR / COMM, ou voir / communiquer)
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

        if ($ville_as_array = Kernel::getKernelLimits('ville_as_array')) {
            $villes = $annuaireService->getVilles ($ville_as_array, array('droit'=>$pDroit));
        } else
            $villes = $annuaireService->getVillesInGrville ($grville, array('droit'=>$pDroit));

        $tpl = new CopixTpl ();
        $tpl->assign('items', $villes);
        $tpl->assign('value', $value);
        $tpl->assign('fieldName', $fieldName);
        $tpl->assign('attribs', $attribs);
        $tpl->assign('linesSup', $linesSup);

    $toReturn = $tpl->fetch ('combovilles.tpl');
    return true;
    }

}


