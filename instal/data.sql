SET CHARACTER SET 'utf8';

--
-- Contenu de la table `copixmodule`
--

INSERT INTO `copixmodule` (`name_cpm`, `path_cpm`, `version_cpm`) VALUES
('admin', 'project/modules/public/stable/standard/', '0'),
('auth', 'project/modules/public/stable/standard/', '0'),
('default', 'project/modules/public/stable/standard/', '0'),
('generictools', 'project/modules/public/stable/standard/', '0'),
('gestionautonome', 'project/modules/public/stable/iconito/gestionautonome', '0');

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
-- Contenu de la table `dbuser`
--

INSERT INTO `dbuser` (`id_dbuser`, `login_dbuser`, `password_dbuser`, `email_dbuser`, `enabled_dbuser`) VALUES
(1, 'admin', 'b28c27a4912ceec95a6733fb3058f2ac', '', 1);

--
-- Contenu de la table `kernel_bu_annee_scolaire`
--

INSERT INTO `kernel_bu_annee_scolaire` (`id_as`, `annee_scolaire`, `dateDebut`, `dateFin`, `current`) VALUES
(2011, '2011-2012', '2011-09-05', '2012-07-05', 1);

--
-- Contenu de la table `kernel_bu_classe_niveau`
--

INSERT INTO `kernel_bu_classe_niveau` (`id_n`, `niveau`, `id_cycle`, `niveau_court`) VALUES
(1, 'Toute petite section', 1, 'TPS'),
(2, 'Petite section', 1, 'PS'),
(3, 'Moyenne section', 1, 'MS'),
(4, 'Grande section', 1, 'GS'),
(5, 'Cours préparatoire', 2, 'CP'),
(6, 'Cours élémentaire 1er année', 2, 'CE1'),
(7, 'Cours élémentaire 2ème année', 3, 'CE2'),
(8, 'Cours moyen 1er année', 3, 'CM1'),
(9, 'Cours moyen 2ème année', 3, 'CM2');

--
-- Contenu de la table `kernel_bu_classe_type`
--

INSERT INTO `kernel_bu_classe_type` (`id_tycla`, `type_classe`) VALUES
(11, 'Ordinaire'),
(12, 'CLAD'),
(13, 'CLIS'),
(31, 'CLIN'),
(24, 'Groupe d''enseignement'),
(32, 'Regroupement d''adaptation'),
(33, 'Autre');

--
-- Contenu de la table `kernel_bu_cycle`
--

INSERT INTO `kernel_bu_cycle` (`id_c`, `cycle`) VALUES
(1, 'Maternelle'),
(2, 'Cycle 2'),
(3, 'Cycle 3');

--
-- Contenu de la table `kernel_bu_groupe_villes`
--

INSERT INTO `kernel_bu_groupe_villes` (`id_grv`, `nom_groupe`, `date_creation`) VALUES
(1, 'Les villes', NOW());

--
-- Contenu de la table `kernel_bu_lien_parental`
--

INSERT INTO `kernel_bu_lien_parental` (`id_pa`, `parente`) VALUES
(1, 'Mère'),
(2, 'Père'),
(3, 'Frère'),
(4, 'Soeur'),
(5, 'Grand-père'),
(6, 'Grand-mère'),
(7, 'Oncle'),
(8, 'Tante'),
(9, 'Collatéraux'),
(10, 'Tuteur'),
(11, 'Inconnu');

--
-- Contenu de la table `kernel_bu_personnel_role`
--

INSERT INTO `kernel_bu_personnel_role` (`id_role`, `nom_role`, `nom_role_pluriel`, `perimetre`, `priorite`) VALUES
(1, 'Enseignant', 'Enseignants', 'ECOLE|CLASSE', 2),
(2, 'Directeur', 'Directeurs', 'ECOLE|CLASSE', 1),
(3, 'Personnel administratif', 'Personnels administratif', 'ECOLE', 3),
(4, 'Agent de ville', 'Agents de ville', 'VILLE', 4),
(5, 'Agent de groupe de villes', 'Agents de groupes de villes', 'GVILLE', 5);

--
-- Contenu de la table `kernel_bu_sexe`
--

INSERT INTO `kernel_bu_sexe` (`id_s`, `sexe`) VALUES
(1, 'Masculin'),
(2, 'Féminin');

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
-- Contenu de la table `kernel_link_groupe2node`
--

INSERT INTO `kernel_link_groupe2node` (`groupe_id`, `node_type`, `node_id`) VALUES
(1, 'ROOT', 0);

--
-- Contenu de la table `kernel_link_user2node`
--

INSERT INTO `kernel_link_user2node` (`user_type`, `user_id`, `node_type`, `node_id`, `droit`, `debut`, `fin`) VALUES
('USER_EXT', 1, 'ROOT', 0, 70, NULL, NULL),
('USER_EXT', 1, 'CLUB', 1, 70, NULL, NULL);

--
-- Contenu de la table `kernel_mod_available`
--

INSERT INTO `kernel_mod_available` (`node`, `module`) VALUES
('BU_CLASSE', 'MOD_AGENDA'),
('BU_CLASSE', 'MOD_BLOG'),
('BU_CLASSE', 'MOD_CAHIERDETEXTES'),
('BU_CLASSE', 'MOD_CLASSEUR'),
('BU_CLASSE', 'MOD_QUIZ'),
('BU_ECOLE', 'MOD_AGENDA'),
('BU_ECOLE', 'MOD_BLOG'),
('BU_ECOLE', 'MOD_CLASSEUR'),
('BU_VILLE', 'MOD_BLOG'),
('BU_VILLE', 'MOD_CLASSEUR'),
('BU_VILLE', 'MOD_TELEPROCEDURES'),
('CLUB', 'MOD_AGENDA'),
('CLUB', 'MOD_BLOG'),
('CLUB', 'MOD_CLASSEUR'),
('CLUB', 'MOD_FORUM'),
('CLUB', 'MOD_LISTE'),
('CLUB', 'MOD_MAGICMAIL'),
('CLUB', 'MOD_QUIZ'),
('USER_%', 'MOD_AGENDA'),
('USER_%', 'MOD_CLASSEUR'),
('USER_%', 'MOD_MINIMAIL'),
('USER_ELE', 'MOD_CAHIERDETEXTES'),
('USER_ENS', 'MOD_WEBMAIL'),
('USER_EXT', 'MOD_WEBMAIL'),
('USER_RES', 'MOD_CORRESP'),
('USER_VIL', 'MOD_WEBMAIL');

--
-- Contenu de la table `kernel_mod_enabled`
--

INSERT INTO `kernel_mod_enabled` (`node_type`, `node_id`, `module_type`, `module_id`) VALUES
('CLUB', 1, 'MOD_CLASSEUR', 1),
('CLUB', 1, 'MOD_BLOG', 1);

--
-- Contenu de la table `module_blog`
--

INSERT INTO `module_blog` (`id_blog`, `name_blog`, `id_ctpt`, `logo_blog`, `url_blog`, `style_blog_file`, `is_public`, `has_comments_activated`, `type_moderation_comments`, `default_format_articles`, `template`) VALUES
(1, 'Edito', 1, NULL, 'edito', 0, 0, 0, 'POST', 'ckeditor', NULL);

--
-- Contenu de la table `module_blog_article`
--

INSERT INTO `module_blog_article` (`id_bact`, `id_blog`, `name_bact`, `sumary_bact`, `sumary_html_bact`, `content_bact`, `content_html_bact`, `format_bact`, `author_bact`, `date_bact`, `time_bact`, `url_bact`, `sticky_bact`, `is_online`) VALUES
(1, 1, 'L''école du Bois Fleuri se met à Iconito', '<p>\r\n	A partir d&#39;aujourd&#39;hui, Iconito est d&eacute;ploy&eacute; &agrave; l&#39;&eacute;cole du Bois Fleuri. Gr&acirc;ce notamment &agrave; l&#39;implication du directeur M. Franc et des enseignants, les parents d&#39;&eacute;l&egrave;ves vont pouvoir :</p>\r\n<ul>\r\n	<li>\r\n		suivre les aventures de leurs enfants lors des sorties et des classes vertes</li>\r\n	<li>\r\n		communiquer avec les enseignants</li>\r\n	<li>\r\n		obtenir des informations pratiques sur l&#39;&eacute;cole</li>\r\n</ul>\r\n', '<p>\r\n	A partir d&#39;aujourd&#39;hui, Iconito est d&eacute;ploy&eacute; &agrave; l&#39;&eacute;cole du Bois Fleuri. Gr&acirc;ce notamment &agrave; l&#39;implication du directeur M. Franc et des enseignants, les parents d&#39;&eacute;l&egrave;ves vont pouvoir :</p>\r\n<ul>\r\n	<li>\r\n		suivre les aventures de leurs enfants lors des sorties et des classes vertes</li>\r\n	<li>\r\n		communiquer avec les enseignants</li>\r\n	<li>\r\n		obtenir des informations pratiques sur l&#39;&eacute;cole</li>\r\n</ul>\r\n', '<br />\r\n', '<br />\r\n', 'ckeditor', 1, '20111004', '0722', '1_lecole_du_bois_fleuri_se_met_iconito', 0, 1),
(2, 1, 'Démarrage du projet', '<p>\r\n	Apr&egrave;s une installation facile et rapide, Iconito est install&eacute; dans notre commune. Les &eacute;coles devraient rapidement s&#39;approprier l&#39;outil et permettre &agrave; tous les acteurs du monde &eacute;ducatif d&#39;en profiter...</p>\r\n', '<p>\r\n	Apr&egrave;s une installation facile et rapide, Iconito est install&eacute; dans notre commune. Les &eacute;coles devraient rapidement s&#39;approprier l&#39;outil et permettre &agrave; tous les acteurs du monde &eacute;ducatif d&#39;en profiter...</p>\r\n', '<br />\r\n', '<br />\r\n', 'ckeditor', 1, '20111004', '0709', '2_demarrage_du_projet', 0, 1);

--
-- Contenu de la table `module_blog_articlecategory`
--

INSERT INTO `module_blog_articlecategory` (`id_bacg`, `id_blog`, `order_bacg`, `name_bacg`, `url_bacg`) VALUES
(1, 1, 1, 'Actualités', 'actualites20100921103303');

--
-- Contenu de la table `module_blog_article_blogarticlecategory`
--

INSERT INTO `module_blog_article_blogarticlecategory` (`id_bact`, `id_bacg`) VALUES
(1, 1),
(2, 1);

--
-- Contenu de la table `module_blog_page`
--

INSERT INTO `module_blog_page` (`id_bpge`, `id_blog`, `name_bpge`, `content_bpge`, `content_html_bpge`, `format_bpge`, `author_bpge`, `date_bpge`, `url_bpge`, `order_bpge`, `is_online`) VALUES
(1, 1, 'Bienvenue à Iconito Ecole Numérique 2011 !', '<p>\r\n	<img class="left" alt="Bo&icirc;te" src="/static/classeur/1-49376fcb9d/3-2c8c12e0c6_240.png" />Iconito Ecole Num&eacute;rique est un portail &eacute;ducatif comprenant un ensemble d&#39;outils et de ressources &agrave; destination des enseignants et des &eacute;l&egrave;ves, mais aussi des parents et des autres intervenants du syst&egrave;me scolaire.</p>\r\n<p>\r\n	Il est d&eacute;velopp&eacute; sous licence libre (GNU GPL). Vous trouverez plus d&#39;informations sur le site d&eacute;di&eacute; <strong><a href="http://www.iconito.fr/" target="_blank" title="Site web d''iconito">iconito.fr</a></strong></p>\r\n<p>\r\n	Cette &eacute;dition 2011 vous propose une nouvelle approche ergonomique que nous esp&eacute;rons plus claire qu&#39;avant. De nouvelles fonctionnalit&eacute;s sont &eacute;galement disponibles, dont la Gestion des Usagers int&eacute;gr&eacute;e ou les Quiz dans les groupes de travail. Pour d&#39;autres informations, n&#39;h&eacute;sitez pas &agrave; consulter notre <strong><a href="http://www.iconito.fr/telechargement/documentation/62-utilisation-ecole-numerique/154-apercu-des-nouveautes-diconito-ecole-numerique-2010" target="_blank" title="Nouveautés Iconito Ecole Numérique 2011">aper&ccedil;u des nouveaut&eacute;s Iconito Ecole Num&eacute;rique 2011</a></strong></p>\r\n', '<p>\r\n	<img class="left" alt="Bo&icirc;te" src="/static/album/1_b3ce1d6dcb/1_2c8c12e0c6_240.png" />Iconito Ecole Num&eacute;rique est un portail &eacute;ducatif comprenant un ensemble d&#39;outils et de ressources &agrave; destination des enseignants et des &eacute;l&egrave;ves, mais aussi des parents et des autres intervenants du syst&egrave;me scolaire.</p>\r\n<p>\r\n	Il est d&eacute;velopp&eacute; sous licence libre (GNU GPL). Vous trouverez plus d&#39;informations sur le site d&eacute;di&eacute; <strong><a href="http://www.iconito.fr/" target="_blank" title="Site web d''iconito">iconito.fr</a></strong></p>\r\n<p>\r\n	Cette &eacute;dition 2011 vous propose une nouvelle approche ergonomique que nous esp&eacute;rons plus claire qu&#39;avant. De nouvelles fonctionnalit&eacute;s sont &eacute;galement disponibles, dont la Gestion des Usagers int&eacute;gr&eacute;e ou les Quiz dans les groupes de travail. Pour d&#39;autres informations, n&#39;h&eacute;sitez pas &agrave; consulter notre <strong><a href="http://www.iconito.fr/telechargement/documentation/62-utilisation-ecole-numerique/154-apercu-des-nouveautes-diconito-ecole-numerique-2010" target="_blank" title="Nouveautés Iconito Ecole Numérique 2011">aper&ccedil;u des nouveaut&eacute;s Iconito Ecole Num&eacute;rique 2011</a></strong></p>\r\n', 'ckeditor', 1, '20111004', 'bienvenue', 1, 1);

--
-- Contenu de la table `module_classeur`
--

INSERT INTO `module_classeur` (`id`, `titre`, `cle`, `date_creation`, `date_publication`, `public`) VALUES
(1, 'Edito', '49376fcb9d', '2011-10-04 10:52:39', NULL, NULL);

--
-- Contenu de la table `module_classeur_dossier`
--

INSERT INTO `module_classeur_dossier` (`id`, `module_classeur_id`, `parent_id`, `nom`, `nb_dossiers`, `nb_fichiers`, `taille`, `cle`, `date_creation`, `user_type`, `user_id`, `date_publication`, `public`) VALUES
(1, 1, 0, 'Photos défilantes', 0, 2, 154131, 'eefc8f8c50', '2011-10-04 09:24:55', '', 0, NULL, NULL),
(2, 1, 0, 'Illustrations accueil', 0, 1, 19905, '3e1a0664bc', '2011-10-04 09:24:55', '', 0, NULL, NULL);

--
-- Contenu de la table `module_classeur_fichier`
--

INSERT INTO `module_classeur_fichier` (`id`, `module_classeur_id`, `module_classeur_dossier_id`, `titre`, `commentaire`, `fichier`, `taille`, `type`, `cle`, `date_upload`, `user_type`, `user_id`) VALUES
(1, 1, 1, 'Un joli dessin', 'Un joli dessin', 'Un joli dessin.jpg', 107484, 'JPG', '7a646493bc', '2011-10-04 09:24:55', '', 0),
(2, 1, 1, 'Une famille', 'Une famille', 'Une famille.jpg', 46647, 'JPG', 'ce1960caf0', '2011-10-04 09:24:55', '', 0),
(3, 1, 2, 'Boîte', NULL, 'Boîte.png', 19905, 'PNG', '2c8c12e0c6', '2011-10-04 09:24:55', '', 0);

--
-- Contenu de la table `module_groupe_groupe`
--

INSERT INTO `module_groupe_groupe` (`id`, `titre`, `description`, `is_open`, `createur`, `date_creation`) VALUES
(1, 'Edito', 'Groupe destiné à gérer la page d''accueil', 0, 1, NOW());

--
-- Contenu de la table `module_prefs_preferences`
--

INSERT INTO `module_prefs_preferences` (`user`, `module`, `code`, `value`) VALUES
(1, 'prefs', 'avatar', 'admin.png');

--
-- Contenu de la table `module_rightmatrix`
--

INSERT INTO `module_rightmatrix` (`id`, `user_type_in`, `user_type_out`, `right`, `node_type`) VALUES
(14, 'USER_ENS', 'USER_VIL', 'VOIR', 'BU_VILLE'),
(13, 'USER_ENS', 'USER_DIR', 'COMM', 'BU_VILLE'),
(12, 'USER_ENS', 'USER_DIR', 'VOIR', 'BU_VILLE'),
(6, 'USER_ENS', 'USER_RES', 'VOIR', 'BU_ECOLE'),
(7, 'USER_ENS', 'USER_RES', 'COMM', 'BU_ECOLE'),
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
(24, 'USER_DIR', 'USER_RES', 'COMM', 'BU_ECOLE'),
(25, 'USER_DIR', 'USER_RES', 'VOIR', 'BU_ECOLE'),
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
(39, 'USER_ELE', 'USER_RES', 'VOIR', 'BU_ECOLE'),
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
(53, 'USER_ENS', 'USER_EXT', 'COMM', 'ROOT'),
(54, 'USER_ATI', 'USER_ENS', 'VOIR', 'BU_GRVILLE'),
(55, 'USER_ATI', 'USER_ENS', 'COMM', 'BU_GRVILLE'),
(56, 'USER_ATI', 'USER_DIR', 'VOIR', 'BU_GRVILLE'),
(57, 'USER_ATI', 'USER_DIR', 'COMM', 'BU_GRVILLE'),
(58, 'USER_ADM', 'USER_ENS', 'VOIR', 'BU_ECOLE'),
(59, 'USER_ADM', 'USER_ENS', 'COMM', 'BU_ECOLE'),
(60, 'USER_ADM', 'USER_DIR', 'VOIR', 'BU_ECOLE'),
(61, 'USER_ADM', 'USER_DIR', 'COMM', 'BU_ECOLE'),
(62, 'USER_ENS', 'USER_ADM', 'VOIR', 'BU_ECOLE'),
(63, 'USER_ENS', 'USER_ADM', 'COMM', 'BU_ECOLE'),
(64, 'USER_DIR', 'USER_ADM', 'VOIR', 'BU_ECOLE'),
(65, 'USER_DIR', 'USER_ADM', 'COMM', 'BU_ECOLE');

--
-- Contenu de la table `module_teleprocedure_statu`
--

INSERT INTO `module_teleprocedure_statu` (`idstat`, `nom`) VALUES
(1, 'Nouveau'),
(2, 'En cours'),
(3, 'Clos');

--
-- Contenu de la table `kernel_version_bdd`
--

INSERT INTO `kernel_version_bdd` (`id`, `version`, `date`, `ip`) VALUES ('', 10, NOW(), 'install');