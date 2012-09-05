<?php

/**
 * Zone affichant une fiche détaillée d'un utilisateur
 *
 * @package Iconito
 * @subpackage	Annuaire
 */
class ZoneGetUserProfil extends CopixZone
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
        $annuaireService = & CopixClassesFactory::Create ('annuaire|AnnuaireService');

        $type = ($this->getParam('type')) ? $this->getParam('type') : NULL;
        $id = ($this->getParam('id')) ? $this->getParam('id') : NULL;


        $canWrite = $canView = false;
        if ($type && $id) {
            $usr = Kernel::getUserInfo ($type, $id);
            $usr['type_nom'] = Kernel::Code2Name ($usr['type']);

            //Kernel::myDebug($usr);
            $droits = Kernel::getUserInfoMatrix ($usr);
            $canView = $droits['voir'];
            $canWrite = $droits['communiquer'];

            if ($canView) {

                // Avatar
                $avatar = '';
                if (isset($usr['user_id']))
                    $avatar = Prefs::get('prefs', 'avatar', $usr['user_id']);
                $usr['avatar'] = ($avatar) ? CopixConfig::get ('prefs|avatar_path').$avatar : '';

                $parents = $enfants = array();
                if ($type == 'USER_ELE') { // Pour un élève, on cherche ses parents
                    $parents = $annuaireService->getParentsFromEleve ($id);
                } elseif ($type == 'USER_RES') { // Pour un parent, on cherche ses enfants
                    $enfants = $annuaireService->getEnfantsFromParent ($id);
                }
            } else
                $usr = $parents = $enfants = false;

            $tpl = new CopixTpl ();
            $tpl->assign('usr', $usr);
            $tpl->assign('canWrite', $canWrite);
            $tpl->assign('parents', $parents);
            $tpl->assign('enfants', $enfants);
        $toReturn = $tpl->fetch ('getuserprofilzone.tpl');

        }
    return true;
    }

}

