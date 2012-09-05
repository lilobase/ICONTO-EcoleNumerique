<?php

/**
 * Zone qui affiche une liste déroulante vide
 *
 * @package Iconito
 * @subpackage	Annuaire
 */
class ZoneComboEmpty extends CopixZone
{
    /**
     * Affiche une liste déroulante vide
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2006/01/19
     * @param integer $value Valeur actuelle de la combo
     * @param string $fieldName Nom du champ de type SELECT qui en résulte
     * @param string $attribs Attributs HTML de la liste (STYLE, ONCHANGE...)
     */
    public function _createContent (&$toReturn)
    {
        $value = ($this->getParam('value')) ? $this->getParam('value') : 0;
        $fieldName = ($this->getParam('fieldName')) ? $this->getParam('fieldName') : NULL;
        $attribs = ($this->getParam('attribs')) ? $this->getParam('attribs') : NULL;

        $tpl = new CopixTpl ();
        $tpl->assign('value', $value);
        $tpl->assign('fieldName', $fieldName);
        $tpl->assign('attribs', $attribs);

    $toReturn = $tpl->fetch ('comboempty.tpl');
    return true;
    }

}


