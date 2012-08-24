
--
-- Structure de la table 'module_rssmix'
--

CREATE TABLE IF NOT EXISTS `module_rssmix` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(255) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
);

--
-- Contenu de la table 'module_rssmix'
--

INSERT INTO module_rssmix VALUES(1, 'http://iconito.fr/?option=com_content&view=category&id=79&format=feed&type=rss', 'Ecole Numérique', null);