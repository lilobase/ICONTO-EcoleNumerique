--
-- Contenu de la table `modulecredentialsvalues`
--

INSERT INTO `modulecredentialsvalues` (`id_mcv`, `value_mcv`, `id_mc`, `level_mcv`) VALUES
(1, 'create', 1, 1),
(2, 'update', 1, 2),
(3, 'delete', 1, 3),
(4, 'create', 2, 1),
(5, 'update', 2, 2),
(6, 'delete', 2, 3),
(7, 'create', 3, 1),
(8, 'update', 3, 2),
(9, 'delete', 3, 3),
(10, 'create', 4, 1),
(11, 'update', 4, 2),
(12, 'delete', 4, 3),
(13, 'create', 5, 1),
(14, 'update', 5, 2),
(15, 'delete', 5, 3),
(16, 'create', 6, 1),
(17, 'update', 6, 2),
(18, 'delete', 6, 3),
(19, 'create', 7, 1),
(20, 'update', 7, 2),
(21, 'delete', 7, 3),
(22, 'create', 8, 1),
(23, 'update', 8, 2),
(24, 'delete', 8, 3),
(25, 'create', 9, 1),
(26, 'update', 9, 2),
(27, 'delete', 9, 3),
(28, 'create', 10, 1),
(29, 'update', 10, 2),
(30, 'delete', 10, 3),
(31, 'create', 11, 1),
(32, 'update', 11, 2),
(33, 'delete', 11, 3);

--
-- Contenu de la table `modulecredentialsgroups`
--

INSERT INTO `modulecredentialsgroups` (`id_mcg`, `id_mc`, `id_mcv`, `handler_group`, `id_group`) VALUES
(1, 1, 3, 'auth|dbgrouphandler', '3'),
(2, 2, 6, 'auth|dbgrouphandler', '3'),
(3, 3, 9, 'auth|dbgrouphandler', '3'),
(4, 5, 15, 'auth|dbgrouphandler', '3'),
(5, 6, 18, 'auth|dbgrouphandler', '3'),
(6, 7, 21, 'auth|dbgrouphandler', '3'),
(7, 8, 24, 'auth|dbgrouphandler', '3'),
(8, 9, 27, 'auth|dbgrouphandler', '3'),
(9, 10, 30, 'auth|dbgrouphandler', '3'),
(10, 11, 33, 'auth|dbgrouphandler', '3'),
(11, 12, NULL, 'auth|dbgrouphandler', '3'),
(12, 3, 9, 'auth|dbgrouphandler', '4'),
(13, 4, 12, 'auth|dbgrouphandler', '4'),
(23, 9, 27, 'auth|dbgrouphandler', '6'),
(22, 4, 12, 'auth|dbgrouphandler', '6'),
(16, 7, 21, 'auth|dbgrouphandler', '4'),
(17, 8, 24, 'auth|dbgrouphandler', '4'),
(18, 9, 27, 'auth|dbgrouphandler', '4'),
(19, 10, 30, 'auth|dbgrouphandler', '4'),
(20, 11, 33, 'auth|dbgrouphandler', '4'),
(21, 12, NULL, 'auth|dbgrouphandler', '4'),
(24, 10, 30, 'auth|dbgrouphandler', '6'),
(25, 11, 33, 'auth|dbgrouphandler', '6'),
(26, 12, NULL, 'auth|dbgrouphandler', '6'),
(32, 11, 32, 'auth|dbgrouphandler', '7'),
(33, 3, 8, 'auth|dbgrouphandler', '8'),
(29, 12, NULL, 'auth|dbgrouphandler', '7'),
(30, 4, 12, 'auth|dbgrouphandler', '3'),
(31, 10, 29, 'auth|dbgrouphandler', '7'),
(34, 4, 12, 'auth|dbgrouphandler', '8'),
(35, 7, 21, 'auth|dbgrouphandler', '8'),
(36, 8, 24, 'auth|dbgrouphandler', '8'),
(37, 9, 27, 'auth|dbgrouphandler', '8'),
(38, 10, 30, 'auth|dbgrouphandler', '8'),
(39, 11, 33, 'auth|dbgrouphandler', '8'),
(40, 12, NULL, 'auth|dbgrouphandler', '8'),
(41, 4, 11, 'auth|dbgrouphandler', '9'),
(42, 9, 27, 'auth|dbgrouphandler', '9'),
(43, 10, 30, 'auth|dbgrouphandler', '9'),
(44, 11, 33, 'auth|dbgrouphandler', '9'),
(45, 12, NULL, 'auth|dbgrouphandler', '9'),
(46, 8, 24, 'auth|dbgrouphandler', '9');

--
-- Contenu de la table `modulecredentials`
--

INSERT INTO `modulecredentials` (`id_mc`, `module_mc`, `name_mc`) VALUES
(1, 'gestionautonome', 'cities_group'),
(2, 'gestionautonome', 'city'),
(3, 'gestionautonome', 'school'),
(4, 'gestionautonome', 'classroom'),
(5, 'gestionautonome', 'cities_group_agent'),
(6, 'gestionautonome', 'city_agent'),
(7, 'gestionautonome', 'administration_staff'),
(8, 'gestionautonome', 'principal'),
(9, 'gestionautonome', 'teacher'),
(10, 'gestionautonome', 'student'),
(11, 'gestionautonome', 'person_in_charge'),
(12, 'gestionautonome', 'access');

INSERT INTO `dbgroup` (`id_dbgroup`, `caption_dbgroup`, `description_dbgroup`, `superadmin_dbgroup`, `public_dbgroup`, `registered_dbgroup`) VALUES
(3, 'cities_group_agent', NULL, 0, 0, 0),
(4, 'city_agent', NULL, 0, 0, 0),
(5, 'administration_staff', NULL, 0, 0, 0),
(6, 'principal', NULL, 0, 0, 0),
(7, 'teacher', NULL, 0, 0, 0),
(8, 'cities_group_animator', NULL, 0, 0, 0),
(9, 'schools_group_animator', NULL, 0, 0, 0);