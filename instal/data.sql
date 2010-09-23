-- 
-- Contenu de la table `dbgroup`
-- 
SET CHARACTER SET 'utf8';

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

INSERT INTO `module_prefs_preferences` ( `user` , `module` , `code` , `value` ) VALUES (
'1', 'prefs', 'avatar', 'admin.png' );

--
-- Dumping data for table `kernel_bu_personnel_role`
--


INSERT INTO `kernel_bu_personnel_role` VALUES (1,'Enseignant','Enseignants','ECOLE|CLASSE',2),(2,'Directeur','Directeurs','ECOLE|CLASSE',1),(3,'Personnel administratif','Personnels administratif','ECOLE',3),(4,'Agent de ville','Agents de ville','VILLE',4),(5,'Agent de groupe de villes','Agents de groupes de villes','GVILLE',5);

--
-- Dumping data for table `kernel_bu_sexe`
--

INSERT INTO `kernel_bu_sexe` VALUES (1,'Masculin'),(2,'Fï¿½minin');


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
('BU_VILLE', 'MOD_TELEPROCEDURES'),
('CLUB', 'MOD_AGENDA'),
('CLUB', 'MOD_ALBUM'),
('CLUB', 'MOD_BLOG'),
('CLUB', 'MOD_FORUM'),
('CLUB', 'MOD_LISTE'),
('CLUB', 'MOD_MAGICMAIL'),
('CLUB', 'MOD_MALLE'),
('CLUB', 'MOD_QUIZ'),
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

-- 
-- Contenu de la table copixmodule
-- 

INSERT INTO `copixmodule` (`name_cpm`, `path_cpm`, `version_cpm`) VALUES 
    ('gestionautonome', 'project/modules/public/stable/iconito/gestionautonome', '0');

-- 
-- Contenu de la table annee_scolaire
-- 

INSERT INTO `kernel_bu_annee_scolaire` (`id_as`, `annee_scolaire`, `dateDebut`, `dateFin`, `current`) VALUES
(2010, '2010-2011', '2010-09-02', '2011-07-31', 1);

-- Possibilite d'ajouter un quiz dans les groupes


-- Premier groupe de villes

INSERT INTO  `kernel_bu_groupe_villes` (
`id_grv` ,
`nom_groupe` ,
`date_creation`
)
VALUES (
NULL ,  'Les villes',  NOW()
);

-- Cycles

INSERT INTO `kernel_bu_cycle` VALUES(1, 'Maternelle');
INSERT INTO `kernel_bu_cycle` VALUES(2, 'Cycle 2');
INSERT INTO `kernel_bu_cycle` VALUES(3, 'Cycle 3');

-- Types de classe

INSERT INTO `kernel_bu_classe_type` VALUES(11, 'Ordinaire');
INSERT INTO `kernel_bu_classe_type` VALUES(12, 'CLAD');
INSERT INTO `kernel_bu_classe_type` VALUES(13, 'CLIS');
INSERT INTO `kernel_bu_classe_type` VALUES(31, 'CLIN');
INSERT INTO `kernel_bu_classe_type` VALUES(24, 'Groupe d''enseignement');
INSERT INTO `kernel_bu_classe_type` VALUES(32, 'Regroupement d''adaptation');
INSERT INTO `kernel_bu_classe_type` VALUES(33, 'Autre');

-- Niveaux

INSERT INTO `kernel_bu_classe_niveau` VALUES(1, 'Toute petite section', 1, 'TPS');
INSERT INTO `kernel_bu_classe_niveau` VALUES(2, 'Petite section', 1, 'PS');
INSERT INTO `kernel_bu_classe_niveau` VALUES(3, 'Moyenne section', 1, 'MS');
INSERT INTO `kernel_bu_classe_niveau` VALUES(4, 'Grande section', 1, 'GS');
INSERT INTO `kernel_bu_classe_niveau` VALUES(5, 'Cours prÃ©paratoire', 2, 'CP');
INSERT INTO `kernel_bu_classe_niveau` VALUES(6, 'Cours Ã©lÃ©mentaire 1er annÃ©e', 2, 'CE1');
INSERT INTO `kernel_bu_classe_niveau` VALUES(7, 'Cours Ã©lÃ©mentaire 2Ã¨me annÃ©e', 3, 'CE2');
INSERT INTO `kernel_bu_classe_niveau` VALUES(8, 'Cours moyen 1er annÃ©e', 3, 'CM1');
INSERT INTO `kernel_bu_classe_niveau` VALUES(9, 'Cours moyen 2Ã¨me annÃ©e', 3, 'CM2');

-- Liens parentaux

--
-- Contenu de la table `lien_parental`
--

INSERT INTO `kernel_bu_lien_parental` (`id_pa`, `parente`) VALUES
(1, 'Mère'),
(2, 'Père'),
(3, 'FrÃ¨re'), 
(4, 'Soeur'),
(5, 'Grand-père'),
(6, 'Grand-mère'),
(7, 'Oncle'),
(8, 'Tante'),
(9, 'Collatéraux'),
(10, 'Tuteur'),
(11, 'Inconnu');


-- Config contact du module d'aide

-- INSERT INTO `module_contacts_types` (`id`, `contact`, `nom`, `is_default`, `ordre`) VALUES (1, 1, 'Anomalie', NULL, 1), (2, 1, 'Suggestion', NULL, 2), (3, 1, 'Comment faire ?', NULL, 3), (4, 1, 'Autre', 1, 4);


-- Accueil

INSERT INTO `module_groupe_groupe` (`id`, `titre`, `description`, `is_open`, `createur`, `date_creation`) VALUES
(1, 'Edito', 'Groupe destiné à gérer la page d''accueil', 0, 1, DATE_ADD(NOW(), INTERVAL -1 MINUTE));
INSERT INTO `kernel_link_groupe2node` (`groupe_id`, `node_type`, `node_id`) VALUES
(1, 'ROOT', 0);
INSERT INTO `kernel_link_user2node` (`user_type`, `user_id`, `node_type`, `node_id`, `droit`, `debut`, `fin`) VALUES
('USER_EXT', 1, 'CLUB', 1, 70, NULL, NULL);
INSERT INTO `kernel_mod_enabled` (`node_type`, `node_id`, `module_type`, `module_id`) VALUES
('CLUB', 1, 'MOD_ALBUM', 1),
('CLUB', 1, 'MOD_BLOG', 1),
('CLUB', 1, 'MOD_MALLE', 1);
INSERT INTO `module_album_albums` (`id`, `nom`, `prefs`, `date`, `cle`, `public`) VALUES
(1, 'Edito', '', DATE_ADD(NOW(), INTERVAL -1 MINUTE), 'b3ce1d6dcb', 0);
INSERT INTO `module_album_dossiers` (`id`, `id_album`, `id_parent`, `nom`, `commentaire`, `date`, `cle`, `public`) VALUES
(1, 1, 0, 'Photos défilantes', '', DATE_ADD(NOW(), INTERVAL -1 MINUTE), 'eefc8f8c50', 0),
(2, 1, 0, 'Illustrations accueil', '', DATE_ADD(NOW(), INTERVAL -1 MINUTE), '3e1a0664bc', 0);
INSERT INTO `module_album_photos` (`id`, `id_album`, `id_dossier`, `nom`, `commentaire`, `date`, `ext`, `cle`, `public`) VALUES
(1, 1, 2, 'Boîte', NULL, DATE_ADD(NOW(), INTERVAL -1 MINUTE), 'png', '2c8c12e0c6', NULL),
(21, 1, 1, 'Un joli dessin', 'Un joli dessin', DATE_ADD(NOW(), INTERVAL -1 MINUTE), 'jpg', '7a646493bc', NULL),
(22, 1, 1, 'Une famille', 'Une famille', DATE_ADD(NOW(), INTERVAL -1 MINUTE), 'jpg', 'ce1960caf0', NULL);
INSERT INTO `module_blog` (`id_blog`, `name_blog`, `id_ctpt`, `logo_blog`, `url_blog`, `style_blog_file`, `is_public`, `has_comments_activated`, `type_moderation_comments`, `default_format_articles`, `template`) VALUES
(1, 'Edito', 1, NULL, 'edito', 0, 0, 0, 'POST', 'ckeditor', NULL);
INSERT INTO `module_blog_articlecategory` (`id_bacg`, `id_blog`, `order_bacg`, `name_bacg`, `url_bacg`) VALUES
(1, 1, 1, 'Actualités', 'actualites20100921103303');
INSERT INTO `module_malle_malles` (`id`, `titre`, `date_creation`, `cle`) VALUES
(1, 'Edito', DATE_ADD(NOW(), INTERVAL -1 MINUTE), '7cfbb3fbc2');
INSERT INTO `module_blog_page` (`id_bpge`, `id_blog`, `name_bpge`, `content_bpge`, `content_html_bpge`, `format_bpge`, `author_bpge`, `date_bpge`, `url_bpge`, `order_bpge`, `is_online`) VALUES
(1, 1, 'Bienvenue à Iconito Ecole Numérique 2010 !', '<p>\r\n	<img align="left" alt="Bo%C3%AEte" border="0" src="<PATH>static/album/1_b3ce1d6dcb/1_2c8c12e0c6_240.png" />Iconito Ecole Num&eacute;rique est un portail &eacute;ducatif comprenant un ensemble d&#39;outils et de ressources &agrave; destination des enseignants et des &eacute;l&egrave;ves, mais aussi des parents et des autres intervenants du syst&egrave;me scolaire.</p>\r\n<p>\r\n	Il est d&eacute;velopp&eacute; sous licence libre (GNU GPL). Vous trouverez plus d&#39;informations sur le site d&eacute;di&eacute; <strong><a href="http://www.iconito.fr/" target="_blank" title="Site web d''iconito">iconito.fr</a></strong></p>\r\n<p>\r\n	Cette &eacute;dition 2010 vous propose une nouvelle approche ergonomique que nous esp&eacute;rons plus claire qu&#39;avant. De nouvelles fonctionnalit&eacute;s sont &eacute;galement disponibles, dont la Gestion des Usagers int&eacute;gr&eacute;e ou les Quiz dans les groupes de travail. Pour d&#39;autres informations, n&#39;h&eacute;sitez pas &agrave; consulter notre <strong><a href="http://www.iconito.fr/telechargement/documentation/62-utilisation-ecole-numerique/154-apercu-des-nouveautes-diconito-ecole-numerique-2010" target="_blank" title="Nouveautés Iconito Ecole Numérique 2010">aper&ccedil;u des nouveaut&eacute;s Iconito Ecole Num&eacute;rique 2010</a></strong></p>\r\n', '<p>\r\n	<img align="left" alt="Bo%C3%AEte" border="0" src="<PATH>static/album/1_b3ce1d6dcb/1_2c8c12e0c6_240.png" />Iconito Ecole Num&eacute;rique est un portail &eacute;ducatif comprenant un ensemble d&#39;outils et de ressources &agrave; destination des enseignants et des &eacute;l&egrave;ves, mais aussi des parents et des autres intervenants du syst&egrave;me scolaire.</p>\r\n<p>\r\n	Il est d&eacute;velopp&eacute; sous licence libre (GNU GPL). Vous trouverez plus d&#39;informations sur le site d&eacute;di&eacute; <strong><a href="http://www.iconito.fr/" target="_blank" title="Site web d''iconito">iconito.fr</a></strong></p>\r\n<p>\r\n	Cette &eacute;dition 2010 vous propose une nouvelle approche ergonomique que nous esp&eacute;rons plus claire qu&#39;avant. De nouvelles fonctionnalit&eacute;s sont &eacute;galement disponibles, dont la Gestion des Usagers int&eacute;gr&eacute;e ou les Quiz dans les groupes de travail. Pour d&#39;autres informations, n&#39;h&eacute;sitez pas &agrave; consulter notre <strong><a href="http://www.iconito.fr/telechargement/documentation/62-utilisation-ecole-numerique/154-apercu-des-nouveautes-diconito-ecole-numerique-2010" target="_blank" title="Nouveautés Iconito Ecole Numérique 2010">aper&ccedil;u des nouveaut&eacute;s Iconito Ecole Num&eacute;rique 2010</a></strong></p>\r\n', 'ckeditor', 1, DATE_FORMAT(NOW(),'%Y%m%d'), 'bienvenue', 1, 1);



INSERT INTO `module_blog_article` (`id_bact`, `id_blog`, `name_bact`, `sumary_bact`, `sumary_html_bact`, `content_bact`, `content_html_bact`, `format_bact`, `author_bact`, `date_bact`, `time_bact`, `url_bact`, `sticky_bact`, `is_online`) VALUES
(1, 1, 'L''école du Bois Fleuri se met à Iconito', '<p>\r\n	A partir d&#39;aujourd&#39;hui, Iconito est d&eacute;ploy&eacute; &agrave; l&#39;&eacute;cole du Bois Fleuri. Gr&acirc;ce notamment &agrave; l&#39;implication du directeur M. Franc et des enseignants, les parents d&#39;&eacute;l&egrave;ves vont pouvoir :</p>\r\n<ul>\r\n	<li>\r\n		suivre les aventures de leurs enfants lors des sorties et des classes vertes</li>\r\n	<li>\r\n		communiquer avec les enseignants</li>\r\n	<li>\r\n		obtenir des informations pratiques sur l&#39;&eacute;cole</li>\r\n</ul>\r\n', '<p>\r\n	A partir d&#39;aujourd&#39;hui, Iconito est d&eacute;ploy&eacute; &agrave; l&#39;&eacute;cole du Bois Fleuri. Gr&acirc;ce notamment &agrave; l&#39;implication du directeur M. Franc et des enseignants, les parents d&#39;&eacute;l&egrave;ves vont pouvoir :</p>\r\n<ul>\r\n	<li>\r\n		suivre les aventures de leurs enfants lors des sorties et des classes vertes</li>\r\n	<li>\r\n		communiquer avec les enseignants</li>\r\n	<li>\r\n		obtenir des informations pratiques sur l&#39;&eacute;cole</li>\r\n</ul>\r\n', '<br />\r\n', '<br />\r\n', 'ckeditor', 1, DATE_FORMAT(NOW(),'%Y%m%d'), '0722', '1_lecole_du_bois_fleuri_se_met_iconito', 0, 1);
INSERT INTO `module_blog_article_blogarticlecategory` VALUES (1, 1);
INSERT INTO `module_blog_article` (`id_bact`, `id_blog`, `name_bact`, `sumary_bact`, `sumary_html_bact`, `content_bact`, `content_html_bact`, `format_bact`, `author_bact`, `date_bact`, `time_bact`, `url_bact`, `sticky_bact`, `is_online`) VALUES
(2, 1, 'Démarrage du projet', '<p>\r\n	Apr&egrave;s une installation facile et rapide, Iconito est install&eacute; dans notre commune. Les &eacute;coles devraient rapidement s&#39;approprier l&#39;outil et permettre &agrave; tous les acteurs du monde &eacute;ducatif d&#39;en profiter...</p>\r\n', '<p>\r\n	Apr&egrave;s une installation facile et rapide, Iconito est install&eacute; dans notre commune. Les &eacute;coles devraient rapidement s&#39;approprier l&#39;outil et permettre &agrave; tous les acteurs du monde &eacute;ducatif d&#39;en profiter...</p>\r\n', '<br />\r\n', '<br />\r\n', 'ckeditor', 1, DATE_FORMAT(NOW(),'%Y%m%d'), '0709', '2_demarrage_du_projet', 0, 1);
INSERT INTO `module_blog_article_blogarticlecategory` VALUES (2, 1);




