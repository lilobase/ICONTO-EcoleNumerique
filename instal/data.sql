-- 
-- Contenu de la table `dbgroup`
-- 

INSERT INTO `dbgroup` (`id_dbgroup`, `caption_dbgroup`, `description_dbgroup`, `superadmin_dbgroup`, `public_dbgroup`, `registered_dbgroup`) VALUES
(1, 'Admin', 'Groupe administrateur', 1, 0, 0),
(2, 'current_user', 'Utilisation classique de l''application', 0, 0, 1);

-- 
-- Contenu de la table `dbgroup_users`
-- 

INSERT INTO `dbgroup_users` (`id_dbgroup`, `userhandler_dbgroup`, `user_dbgroup`) VALUES
(1, 'auth|dbuserhandler', '1');

-- 
-- Contenu de la table `copixmodule`
-- 

INSERT INTO `copixmodule` VALUES('admin', 'project/modules/public/stable/standard/', '0');
INSERT INTO `copixmodule` VALUES('auth', 'project/modules/public/stable/standard/', '0');
INSERT INTO `copixmodule` VALUES('default', 'project/modules/public/stable/standard/', '0');
INSERT INTO `copixmodule` VALUES('generictools', 'project/modules/public/stable/standard/', '0');

-- 
-- Contenu de la table `dbuser`
-- 

INSERT INTO `dbuser` (`id_dbuser`, `login_dbuser`, `password_dbuser`, `email_dbuser`, `enabled_dbuser`) VALUES 
(1, 'admin', '21232f297a57a5a743894a0e4a801fc3', '', 1);

--
-- Dumping data for table `kernel_bu_personnel_role`
--


INSERT INTO `kernel_bu_personnel_role` VALUES (1,'Enseignant','Enseignants','ECOLE|CLASSE',2),(2,'Directeur','Directeurs','ECOLE|CLASSE',1),(3,'Personnel administratif','Personnels administratif','ECOLE',3),(4,'Agent de ville','Agents de ville','VILLE',4),(5,'Agent de groupe de villes','Agents de groupes de villes','GVILLE',5);

--
-- Dumping data for table `kernel_bu_sexe`
--

INSERT INTO `kernel_bu_sexe` VALUES (1,'Masculin'),(2,'Féminin');


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

--
-- Contenu de la table `module_rightmatrix`
--

INSERT INTO `module_rightmatrix` (`id`, `user_type_in`, `user_type_out`, `right`, `node_type`) VALUES
(14, 'USER_ENS', 'USER_VIL', 'VOIR', 'BU_VILLE'),
(13, 'USER_ENS', 'USER_DIR', 'COMM', 'BU_VILLE'),
(12, 'USER_ENS', 'USER_DIR', 'VOIR', 'BU_VILLE'),
(6, 'USER_ENS', 'USER_RES', 'VOIR', 'BU_ECOLE'),
(7, 'USER_ENS', 'USER_RES', 'COMM', 'BU_CLASSE'),
(8, 'USER_ENS', 'USER_ELE', 'VOIR', 'BU_ECOLE'),
(9, 'USER_ENS', 'USER_ELE', 'COMM', 'BU_CLASSE'),
(10, 'USER_ENS', 'USER_ENS', 'VOIR', 'BU_VILLE'),
(11, 'USER_ENS', 'USER_ENS', 'COMM', 'BU_VILLE'),
(16, 'USER_DIR', 'USER_VIL', 'COMM', 'BU_VILLE'),
(17, 'USER_DIR', 'USER_VIL', 'VOIR', 'BU_VILLE'),
(18, 'USER_DIR', 'USER_DIR', 'COMM', 'BU_VILLE'),
(19, 'USER_DIR', 'USER_DIR', 'VOIR', 'BU_VILLE'),
(20, 'USER_DIR', 'USER_ENS', 'COMM', 'BU_VILLE'),
(21, 'USER_DIR', 'USER_ENS', 'VOIR', 'BU_VILLE'),
(22, 'USER_DIR', 'USER_ELE', 'COMM', 'BU_ECOLE'),
(23, 'USER_DIR', 'USER_ELE', 'VOIR', 'BU_ECOLE'),
(24, 'USER_DIR', 'USER_RES', 'COMM', 'BU_CLASSE'),
(25, 'USER_DIR', 'USER_RES', 'VOIR', 'BU_CLASSE'),
(26, 'USER_VIL', 'USER_VIL', 'COMM', 'BU_VILLE'),
(27, 'USER_VIL', 'USER_VIL', 'VOIR', 'BU_VILLE'),
(28, 'USER_VIL', 'USER_DIR', 'COMM', 'BU_VILLE'),
(29, 'USER_VIL', 'USER_DIR', 'VOIR', 'BU_VILLE'),
(30, 'USER_VIL', 'USER_ENS', 'VOIR', 'BU_VILLE'),
(31, 'USER_VIL', 'USER_ELE', 'VOIR', 'BU_VILLE'),
(32, 'USER_VIL', 'USER_RES', 'VOIR', 'BU_VILLE'),
(33, 'USER_ELE', 'USER_DIR', 'COMM', 'BU_ECOLE'),
(34, 'USER_ELE', 'USER_DIR', 'VOIR', 'BU_ECOLE'),
(35, 'USER_ELE', 'USER_ENS', 'VOIR', 'BU_ECOLE'),
(36, 'USER_ELE', 'USER_ELE', 'VOIR', 'BU_ECOLE'),
(37, 'USER_ELE', 'USER_ENS', 'COMM', 'BU_CLASSE'),
(38, 'USER_ELE', 'USER_ELE', 'COMM', 'BU_CLASSE'),
(39, 'USER_ELE', 'USER_RES', 'VOIR', 'BU_CLASSE'),
(40, 'USER_RES', 'USER_VIL', 'VOIR', 'BU_VILLE'),
(41, 'USER_RES', 'USER_DIR', 'COMM', 'BU_ECOLE'),
(42, 'USER_RES', 'USER_DIR', 'VOIR', 'BU_ECOLE'),
(43, 'USER_RES', 'USER_ENS', 'VOIR', 'BU_ECOLE'),
(44, 'USER_RES', 'USER_ENS', 'COMM', 'BU_CLASSE'),
(45, 'USER_RES', 'USER_ELE', 'VOIR', 'BU_CLASSE'),
(46, 'USER_RES', 'USER_RES', 'COMM', 'BU_CLASSE'),
(47, 'USER_RES', 'USER_RES', 'VOIR', 'BU_CLASSE'),
(48, 'USER_VIL', 'USER_EXT', 'VOIR', 'ROOT'),
(49, 'USER_VIL', 'USER_EXT', 'COMM', 'ROOT'),
(50, 'USER_DIR', 'USER_EXT', 'VOIR', 'ROOT'),
(51, 'USER_DIR', 'USER_EXT', 'COMM', 'ROOT'),
(52, 'USER_ENS', 'USER_EXT', 'VOIR', 'ROOT'),
(53, 'USER_ENS', 'USER_EXT', 'COMM', 'ROOT');
