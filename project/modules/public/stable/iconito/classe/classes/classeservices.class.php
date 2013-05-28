<?php
/**
 *
 * @package Iconito
 * @subpackage Classe
 * @author Jérémy HUBERT
 */
class ClasseServices {
    /**
     * Check if pupil is allowed to access some module
     *
     * @param $action    L'accès demandé
     *
     * @return string
     */
    public function canAccess($action)
    {
        if (!Kernel::isEleve()) {
            return true;
        }

        $allow = $this->countClassesAllowing($action);

        return (bool)$allow[0]->nbAuthorizations;
    }

    /**
     * Returns count of classes which allows usage of module passed in arguments
     *
     * @param $action
     *
     * @return mixed
     */
    public function countClassesAllowing($action)
    {
        $eleveId = _currentUser()->getExtra('id');

        $critere = <<<SQL
            SELECT COUNT(*) AS nbAuthorizations
            FROM kernel_mod_enabled kme
            LEFT JOIN kernel_bu_ecole_classe kbec
                ON kme.node_id = kbec.id
            LEFT JOIN kernel_bu_eleve_affectation kbea
                ON kbea.classe = kbec.id
            WHERE kme.module_type = '{$action}'
            AND kme.module_id = 0
            AND kme.node_type = 'BU_CLASSE'
            AND kbea.current = 1
            AND kbea.eleve = {$eleveId}
            ;
SQL;
        return _doQuery($critere);
     }
}
