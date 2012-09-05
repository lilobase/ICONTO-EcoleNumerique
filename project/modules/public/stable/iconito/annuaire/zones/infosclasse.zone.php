<?php

/**
 * Zone qui affiche les infos d'une classe (enseignant et �l�ves)
 *
 * @package Iconito
 * @subpackage	Annuaire
 */
class ZoneInfosClasse extends CopixZone
{
    /**
     * Affiche les infos d'une classe (enseignant et �l�ves)
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2006/01/18
     * @param integer $rClasse Recordset de la classe
     */
    public function _createContent (&$toReturn)
    {
        $tpl = new CopixTpl ();

        $annuaireService = & CopixClassesFactory::Create ('annuaire|AnnuaireService');

        $rClasse = ($this->getParam('rClasse')) ? $this->getParam('rClasse') : NULL;

        if ($rClasse) {
            $classe = $rClasse['id'];

            $enseignants = $annuaireService->getEnseignantInClasse ($classe);
            $eleves = $annuaireService->getElevesInClasse ($classe);

            $rClasse["eleves"] = $eleves;
            $rClasse["enseignants"] = $enseignants;

            $matrix =& enic::get('matrixCache');
                        $matrix->display();
            $droit = $matrix->classe($classe)->_right->USER_ENS->voir;
            if (!$droit) $rClasse["enseignants"] = 'NONE';
            $canWrite = $matrix->classe($classe)->_right->USER_ENS->communiquer;
            $tpl->assign ('canWriteUSER_ENS', $canWrite);

            $droit = $matrix->classe($classe)->_right->USER_ELE->voir;
            if (!$droit) $rClasse["eleves"] = 'NONE';
            $canWrite = $matrix->classe($classe)->_right->USER_ELE->communiquer;
            $tpl->assign ('canWriteUSER_ELE', $canWrite);



            $tpl->assign ('classe', $rClasse);
        $toReturn = $tpl->fetch ('infosclasse.tpl');
        }

    return true;
    }

}


