<?php
/**
 *
 * @package Iconito
 * @subpackage Classe
 * @author Jérémy HUBERT
 */
class ClasseServices {
    /**
     * Vérifie si un élève a les droits d'accès à un module
     *
     * @param $module L'accès demandé
     *
     * @return string
     */
    public function canAccess($module)
    {
        if (!Kernel::isEleve()) {
            return true;
        }

        _classInclude('classe|ClasseServices');
        $daoClass = _ioDAO('kernel|kernel_bu_ecole_classe');
        $allow = $daoClass->getCountAllowingModule($module);

        return (bool)$allow[0]->nbAuthorizations;
     }
}
