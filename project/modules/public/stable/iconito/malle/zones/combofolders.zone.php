<?php

/**
 * Zone ComboFolders, qui affiche la liste déroulante avec tous les dossiers d'une malle
 *
 * @package Iconito
 * @subpackage	Malle
 */
class ZoneComboFolders extends CopixZone
{
    /**
     * Affiche la liste déroulante avec tous les dossiers d'une malle
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2005/12/07
     * @param integer $malle Id de la malle
     * @param integer $folder Id du dossier (0 si racine)
     * @param string $fieldName Nom du champ de type SELECT qui en résulte
     * @param string $attribs Attributs HTML de la liste (STYLE, ONCHANGE...)
     * @param array $linesSup Lignes supplémentaires à ajouter en haut de la liste au-dessus des dossiers (ex: "Choisissez le dossier"). Chaque ligne est un tableau, de type array ("value"=>"", "libelle"=>"Choisissez")
     */
    public function _createContent (&$toReturn)
    {
        //$daoFolders = _dao("malle|malle_folders");
        $malleService = & CopixClassesFactory::Create ('malle|malleService');

        $tpl = new CopixTpl ();
        $res = array();
        $malle = ($this->getParam('malle')) ? $this->getParam('malle') : NULL;
        $folder = ($this->getParam('folder')) ? $this->getParam('folder') : 0;
        $fieldName = ($this->getParam('fieldName')) ? $this->getParam('fieldName') : NULL;
        $attribs = ($this->getParam('attribs')) ? $this->getParam('attribs') : NULL;
        $linesSup = ($this->getParam('linesSup')) ? $this->getParam('linesSup') : NULL;

        $res = $malleService->buildComboFolders ($malle);
        //print_r($res);
        $tpl->assign('combofolders', $res);
        $tpl->assign('folder', $folder);
        $tpl->assign('fieldName', $fieldName);
        $tpl->assign('attribs', $attribs);
        $tpl->assign('linesSup', $linesSup);

    $toReturn = $tpl->fetch ('combofolders.tpl');
    return true;
    }

}






