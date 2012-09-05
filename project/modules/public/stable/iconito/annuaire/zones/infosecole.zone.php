<?php

/**
 * Zone qui affiche les infos d'une �cole (coordonn�es, directeur, classes...)
 *
 * @package Iconito
 * @subpackage	Annuaire
 */
class ZoneInfosEcole extends CopixZone
{
    /**
     * Affiche les infos d'une �cole (coordonn�es, directeur, classes...)
     *
     * @author Christophe Beyer <cbeyer@cap-tic.fr>
     * @since 2006/01/18
     * @param integer $rEcole Recordset de l'�cole
     */
    public function _createContent (&$toReturn)
    {
        $tpl = new CopixTpl ();

        $annuaireService = & CopixClassesFactory::Create ('annuaire|AnnuaireService');

        $rEcole = ($this->getParam('rEcole')) ? $this->getParam('rEcole') : NULL;

        if ($rEcole) {
            //print_r($rEcole);

            $ecole = $rEcole['id'];

            // BOOST 2.5s
            if( $this->getParam('classes') )
                $classes = $this->getParam('classes');
            else
                $classes = $annuaireService->getClassesInEcole($ecole, array('droit'=>'VOIR'));

            $matrix = & enic::get('matrixCache');

            $droit = $matrix->ecole($ecole)->_right->USER_DIR->voir;
            if ($droit) {
                $rEcole['directeur'] = $annuaireService->getDirecteurInEcole($ecole);
                $canWrite = $matrix->ecole($ecole)->_right->USER_DIR->communiquer;
                $tpl->assign ('canWriteUSER_DIR', $canWrite);
            }

            //$droit = $matrix->ecole($ecole)->_right->USER_ADM->voir;
            $droit = 1;
            if ($droit) {
                $rEcole['administratif'] = $annuaireService->getAdministratifInEcole($ecole);
                $canWrite = $matrix->ecole($ecole)->_right->USER_DIR->communiquer;
                $tpl->assign ('canWriteUSER_ADM', $canWrite);
            }

            $tpl->assign ('canWriteUSER_ENS', $matrix->ecole($ecole)->_right->USER_ENS->communiquer);

            //$rEcole['directeur'] = $annuaireService->checkVisibility( $rEcole['directeur'] );

            //$rEcole['administratif'] = $annuaireService->checkVisibility( $rEcole['administratif'] );


            $tpl->assign ('ecole', $rEcole);
            $tpl->assign ('classes', $classes);

            // BOOST 1s
            $tpl->assign ('comboecoles', CopixZone::process ('annuaire|comboecolesinville', array('droit'=>'VOIR', 'ville'=>$rEcole['ALL']->vil_id_vi, 'value'=>$ecole, 'fieldName'=>'ecole', 'attribs'=>'CLASS="annu_combo_popup" ONCHANGE="if (this.value) this.form.submit();"')));

        $toReturn = $tpl->fetch ('infosecole.tpl');

        }

    return true;
    }

}


