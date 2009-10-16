-- 
-- Contenu de la table `dbuser`
-- 

INSERT INTO `dbuser` (`id_dbuser`, `login_dbuser`, `password_dbuser`, `email_dbuser`, `enabled_dbuser`) VALUES 
(1, 'admin', '21232f297a57a5a743894a0e4a801fc3', '', 1);

-- 
-- Contenu de la table `kernel_ext_user`
-- 

INSERT INTO `kernel_ext_user` (`id`, `nom`, `prenom`, `description`) VALUES 
(1, 'Root', '', '');

-- 
-- Contenu de la table `kernel_link_bu2user`
-- 

INSERT INTO `kernel_link_bu2user` (`user_id`, `bu_type`, `bu_id`) VALUES 
(1, 'USER_EXT', 1);

-- 
-- Contenu de la table `kernel_link_user2node`
-- 

INSERT INTO `kernel_link_user2node` (`user_type`, `user_id`, `node_type`, `node_id`, `droit`, `debut`, `fin`) VALUES 
('USER_EXT', 1, 'ROOT', 0, 70, NULL, NULL);

-- 
-- Contenu de la table `kernel_mod_available`
-- 

INSERT INTO `kernel_mod_available` (`node`, `module`) VALUES 
('BU_CLASSE', 'MOD_AGENDA'),
('BU_CLASSE', 'MOD_ALBUM'),
('BU_CLASSE', 'MOD_BLOG'),
('BU_CLASSE', 'MOD_MALLE'),
('BU_ECOLE', 'MOD_AGENDA'),
('BU_ECOLE', 'MOD_ALBUM'),
('BU_ECOLE', 'MOD_BLOG'),
('BU_ECOLE', 'MOD_MALLE'),
('BU_VILLE', 'MOD_ALBUM'),
('BU_VILLE', 'MOD_BLOG'),
('BU_VILLE', 'MOD_MALLE'),
('CLUB', 'MOD_AGENDA'),
('CLUB', 'MOD_ALBUM'),
('CLUB', 'MOD_BLOG'),
('CLUB', 'MOD_FORUM'),
('CLUB', 'MOD_LISTE'),
('CLUB', 'MOD_MAGICMAIL'),
('CLUB', 'MOD_MALLE'),
('USER_%', 'MOD_AGENDA'),
('USER_%', 'MOD_MALLE'),
('USER_%', 'MOD_MINIMAIL'),
('USER_ENS', 'MOD_WEBMAIL'),
('USER_EXT', 'MOD_WEBMAIL'),
('USER_RES', 'MOD_CORRESP'),
('USER_VIL', 'MOD_WEBMAIL');

-- 
-- Contenu de la table `module_teleprocedure_statu`
-- 

INSERT INTO `module_teleprocedure_statu` (`idstat`, `nom`) VALUES 
(1, 'Nouveau'),
(2, 'En cours'),
(3, 'Clos');