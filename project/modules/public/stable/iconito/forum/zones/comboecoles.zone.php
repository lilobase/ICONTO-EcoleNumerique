<?php

/**
 * Zone qui affiche la liste déroulante avec toutes les écoles d'une ville
 *
 * @package Iconito
 * @subpackage	Forum
 */
class ZoneComboEcoles extends CopixZone
{
    /**
     * Affiche la liste déroulante avec toutes les écoles d'une ville
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2006/01/06
     * @param integer $ville Id de la ville
     * @param integer $value Valeur actuelle de la combo
     * @param string $fieldName Nom du champ de type SELECT qui en résulte
     * @param string $attribs Attributs HTML de la liste (STYLE, ONCHANGE...)
     */
    public function _createContent (&$toReturn)
    {
        $tpl = new CopixTpl ();
        $ville = ($this->getParam('ville')) ? $this->getParam('ville') : NULL;
        $value = ($this->getParam('value')) ? $this->getParam('value') : 0;
        $fieldName = ($this->getParam('fieldName')) ? $this->getParam('fieldName') : NULL;
        $attribs = ($this->getParam('attribs')) ? $this->getParam('attribs') : NULL;

        $ecoles = array();
        $childs = Kernel::getNodeChilds ('BU_VILLE', $ville);
        foreach ($childs as $child) {
            if ($child['type']=='BU_ECOLE') {
                $node = Kernel::getNodeInfo ($child['type'], $child['id'], false);
                //print_r($node);
                $ecoles[] = array('id'=>$child['id'], 'nom'=>$node['nom']);
            }
        }

        $tpl->assign('items', $ecoles);
        $tpl->assign('value', $value);
        $tpl->assign('fieldName', $fieldName);
        $tpl->assign('attribs', $attribs);

    $toReturn = $tpl->fetch ('comboecoles.tpl');
    return true;
    }

}


