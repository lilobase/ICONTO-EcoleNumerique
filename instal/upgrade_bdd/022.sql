SET NAMES latin1;

DROP VIEW IF EXISTS `kernel_info_users`;

CREATE VIEW `kernel_info_users`
(user_type, user_id, user_id_copix, nom, prenom, id_sexe)
AS (SELECT 
 'USER_ELE' AS user_type,
 idEleve AS user_id,
 KLB2U.user_id AS user_id_copix,
 nom, 
prenom1 AS prenom,
id_sexe
FROM kernel_bu_eleve E
JOIN kernel_link_bu2user KLB2U ON KLB2U.bu_id=E.idEleve WHERE KLB2U.bu_type = 'USER_ELE'
)
UNION
(
SELECT 
 'USER_ENS' AS user_type,
 numero AS user_id,
 KLB2U.user_id AS user_id_copix,
 nom, 
prenom1 AS prenom,
id_sexe
FROM kernel_bu_personnel P
JOIN kernel_bu_personnel_entite P_ENTITE ON P.numero = P_ENTITE.id_per 
JOIN kernel_link_bu2user KLB2U ON KLB2U.bu_id=P.numero WHERE KLB2U.bu_type = 'USER_ENS'
AND role != 4 AND role != 5
)
UNION
(
SELECT 
 'USER_VIL' AS user_type,
 numero AS user_id,
 KLB2U.user_id AS user_id_copix,
 nom, 
prenom1 AS prenom,
id_sexe
FROM kernel_bu_personnel P
JOIN kernel_bu_personnel_entite P_ENTITE ON P.numero = P_ENTITE.id_per 
JOIN kernel_link_bu2user KLB2U ON KLB2U.bu_id=P.numero WHERE KLB2U.bu_type = 'USER_VIL'
AND (role = 4 OR role = 5)
)
UNION
(
SELECT 
 'USER_RES' AS user_type,
 numero AS user_id,
 KLB2U.user_id AS user_id_copix,
 nom, 
prenom1 AS prenom,
id_sexe
FROM kernel_bu_responsable R
JOIN kernel_link_bu2user KLB2U ON KLB2U.bu_id=R.numero WHERE KLB2U.bu_type = 'USER_RES'
)

UNION
(
SELECT 
 'USER_EXT' AS user_type,
 id AS user_id,
 KLB2U.user_id AS user_id_copix,
 nom, 
prenom AS prenom,
NULL AS id_sexe
FROM kernel_ext_user EXT
JOIN kernel_link_bu2user KLB2U ON KLB2U.bu_id=EXT.id WHERE KLB2U.bu_type = 'USER_EXT'
)
