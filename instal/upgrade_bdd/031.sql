-- Autorisation par défaut des minimails pour toutes les classes
INSERT INTO `kernel_mod_enabled`
    SELECT 'BU_CLASSE', id, 'MOD_MINIMAIL', 0
    FROM `kernel_bu_ecole_classe`
    WHERE annee_scol IN (
        SELECT id_as AS annee from `kernel_bu_annee_scolaire` WHERE current = 1
    )
;