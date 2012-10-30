<?php

/**
 * Zone qui affiche la liste déroulante avec tous les groupes de villes
 */
class ZoneComboGrVilles extends CopixZone
{

    /**
     * Affiche la liste déroulante avec tous les groupes de villes
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2012/10/29
     * @param integer $value Valeur actuelle de la combo
     * @param string $fieldName Nom du champ de type SELECT qui en résulte
     * @param string $attribs Attributs HTML de la liste (STYLE, ONCHANGE...)
     * @param array $linesSup Lignes supplémentaires à ajouter en haut de la liste au-dessus des dossiers (ex: "Choisissez le groupe"). Chaque ligne est un tableau, de type array ("value"=>"", "libelle"=>"Choisissez")
     * @param string $droit Si on ne veut afficher que les groupes sur lesquelles l'usager a un certain droit (VOIR / COMM, ou voir / communiquer)
     */
    public function _createContent(&$toReturn)
    {
        $annuaireService = & CopixClassesFactory::Create('annuaire|AnnuaireService');

        $value = ($this->getParam('value')) ? $this->getParam('value') : 0;
        $fieldName = ($this->getParam('fieldName')) ? $this->getParam('fieldName') : NULL;
        $attribs = ($this->getParam('attribs')) ? $this->getParam('attribs') : NULL;
        $linesSup = ($this->getParam('linesSup')) ? $this->getParam('linesSup') : NULL;
        $pDroit = $this->getParam('droit');

        $ville_as_array = Kernel::getKernelLimits('ville_as_array');
        if ($ville_as_array) {
            $groupes = $annuaireService->getGrVilles($ville_as_array, array('droit' => $pDroit));
        } else {
            $groupes = $annuaireService->getGrVilles(null, array('droit' => $pDroit));
        }

        $tpl = new CopixTpl ();
        $tpl->assign('items', $groupes);
        $tpl->assign('value', $value);
        $tpl->assign('fieldName', $fieldName);
        $tpl->assign('attribs', $attribs);
        $tpl->assign('linesSup', $linesSup);

        $toReturn = $tpl->fetch('combogrvilles.tpl');
        return true;
    }

}

