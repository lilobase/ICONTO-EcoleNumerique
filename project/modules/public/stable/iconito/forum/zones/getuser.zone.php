<?php

/**
 * Zone affichant une fiche détaillée d'un utilisateur
 *
 * @package Iconito
 * @subpackage	Annuaire
 */
class ZoneGetUser extends CopixZone
{
    /**
     * Affiche la fiche détaillée d'un utilisateur (login, nom, prénom...)
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2006/01/04
     * @param string $type Type de personne (USER_ELE, USER_ELE...)
     * @param integer $id Id
     */
    public function _createContent (&$toReturn)
    {
        $type = ($this->getParam('type')) ? $this->getParam('type') : NULL;
        $id = ($this->getParam('id')) ? $this->getParam('id') : NULL;


        if ($type && $id) {
            $usr = Kernel::getUserInfo ($type, $id);
            //print_r($usr);

            /*
            $res = '<?xml version="1.0" ?>
            <person>
            <login>'.$login.'</login>
            <sexe>'.$usr['sexe'].'</sexe>
            <firstname>'.$usr['prenom'].'</firstname>
            <name>'.$usr['nom'].'</name>
            </person>
            ';
            */

            $tpl = new CopixTpl ();
            $tpl->assign('usr', $usr);
        //$toReturn = utf8_encode($tpl->fetch ('getuser.tpl'));
        $toReturn = $tpl->fetch ('getuser.tpl');
            //$toReturn = $res;
        }
    return true;
    }

}

