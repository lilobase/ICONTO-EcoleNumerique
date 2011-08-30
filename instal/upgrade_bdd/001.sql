UPDATE  `module_rightmatrix`
SET `node_type` = 'BU_ECOLE'
WHERE
`module_rightmatrix`.`user_type_out` ="USER_RES" AND
`node_type` = 'BU_CLASSE'
LIMIT 2 ;
