#
# Table structure for table blog
#

# Table blog
# - id_blog : Id unique du blog
# - name_blog : Nom du blog
# - id_ctpt : id du thème (copixtheme) du blog
# - logo_blog : emplacement du logo du blog
# - url_blog : nom apparaissant dans l'url

DROP TABLE IF EXISTS `blog`;
CREATE TABLE `blog` (
`id_blog` BIGINT( 20 ) NOT NULL AUTO_INCREMENT ,
`name_blog` VARCHAR( 100 ) NOT NULL ,
`id_ctpt` BIGINT( 20 ) NOT NULL ,
`logo_blog` VARCHAR( 100 ) ,
`url_blog` VARCHAR( 100 ) NOT NULL ,
PRIMARY KEY ( `id_blog` )
);


# Table blogarticlecategory
# - id_bacg : Id unique de la catégorie de l'article
# - id_blog : Id du Blog (table blog)
# - order_bacg : ordre des catégories
# - name_bacg : nom de la catégorie de l'article
# - url_bacg : nom apparaissant dans l'url

DROP TABLE IF EXISTS `blogarticlecategory`;
CREATE TABLE `blogarticlecategory` (
`id_bacg` BIGINT( 20 ) NOT NULL AUTO_INCREMENT ,
`id_blog` BIGINT( 20 ) NOT NULL ,
`order_bacg` BIGINT( 10 ) NOT NULL DEFAULT 0,
`name_bacg` VARCHAR( 100 ) NOT NULL ,
`url_bacg` VARCHAR( 100 ) NOT NULL ,
PRIMARY KEY ( `id_bacg` )
);


# Table blogarticle
# - id_bact : Id unique de l'article
# - id_blog : Id du blog (table blog)
# - name_bact : nom de l'article
# - sumary_bact : chapô de l'article
# - content_bact : Contenu de l'article
# - author_bact : Auteur de l'article
# - date_bact : date de l'article
# - url_bact : nom apparaissant dans l'url
# - sticky_bact : Page ou article

DROP TABLE IF EXISTS `blogarticle`;
CREATE TABLE `blogarticle` (
`id_bact` BIGINT( 20 ) NOT NULL AUTO_INCREMENT,
`id_blog` BIGINT( 20 ) NOT NULL ,
`name_bact` VARCHAR( 100 ) NOT NULL ,
`sumary_bact` TEXT,
`content_bact` TEXT,
`author_bact` VARCHAR( 50 ) NOT NULL ,
`date_bact` VARCHAR( 8 ) NOT NULL ,
`time_bact` VARCHAR( 5 ) NOT NULL ,
`url_bact` VARCHAR( 100 ) NOT NULL ,
`sticky_bact` INT( 1 ) DEFAULT '0' NOT NULL ,
PRIMARY KEY ( `id_bact` )
);


# Table blogpage
# - id_bpge : Id unique de l'article
# - id_blog : Id du blog (table blog)
# - name_bpge : nom de l'article
# - content_bpge : Contenu de l'article
# - author_bpge : Auteur de l'article
# - date_bpge : date de l'article
# - url_bpge : nom apparaissant dans l'url
# - order_bpge : ordre des pages

DROP TABLE IF EXISTS `blogpage`;
CREATE TABLE `blogpage` (
`id_bpge` BIGINT( 20 ) NOT NULL AUTO_INCREMENT,
`id_blog` BIGINT( 20 ) NOT NULL ,
`name_bpge` VARCHAR( 100 ) NOT NULL ,
`content_bpge` TEXT,
`author_bpge` VARCHAR( 50 ) NOT NULL ,
`date_bpge` VARCHAR( 8 ) NOT NULL ,
`url_bpge` VARCHAR( 100 ) NOT NULL ,
`order_bpge` BIGINT( 10 ) NOT NULL DEFAULT 0,
PRIMARY KEY ( `id_bpge` )
);


# Table blogarticle_blogarticlecategory
# - id_bacg : Id de la catégorie de l'article (table blogarticlecategory)
# - id_bacg : Id de l'article (table blogarticle)

DROP TABLE IF EXISTS `blogarticle_blogarticlecategory`;
CREATE TABLE `blogarticle_blogarticlecategory` (
`id_bact` BIGINT( 20 ) NOT NULL ,
`id_bacg` BIGINT( 20 ) NOT NULL ,
PRIMARY KEY ( `id_bact` , `id_bacg` )
);


# Table blogarticlecomment
# - id_bacc : Id unique du commentaire
# - id_bact : Id de l'article (table blogarticle)
# - authorname_bacc : nom de l'auteur
# - authoremail_bacc : email de l'auteur
# - authorweb_bacc : adresse internet de l'auteur
# - authorip_bacc : Ip de la machine laissant le commentaire
# - date_bacc : date de création du commentaire
# - time_bacc : heure de création du commentaire
# - content_bacc : Contenu du commentaire

DROP TABLE IF EXISTS `blogarticlecomment`;
CREATE TABLE `blogarticlecomment` (
`id_bacc` BIGINT( 20 ) NOT NULL AUTO_INCREMENT,
`id_bact` BIGINT( 20 ) NOT NULL ,
`authorname_bacc` VARCHAR( 50 ) NOT NULL ,
`authoremail_bacc` VARCHAR( 50 ) ,
`authorweb_bacc` VARCHAR( 100 ) ,
`authorip_bacc` VARCHAR( 15 ) NOT NULL ,
`date_bacc` VARCHAR( 8 ) NOT NULL ,
`time_bacc` VARCHAR( 8 ) NOT NULL ,
`content_bacc` TEXT NOT NULL ,
PRIMARY KEY ( `id_bacc` )
);


# Table bloglink
# - id_blnk : Id unique du lien
# - id_blog : Id du blog (table blog)
# - order_blnk : ordre des liens
# - name_blnk : nom du lien
# - url_blnk : nom apparaissant dans l'url

DROP TABLE IF EXISTS `bloglink`;
CREATE TABLE `bloglink` (
`id_blnk` BIGINT( 20 ) NOT NULL AUTO_INCREMENT,
`id_blog` BIGINT( 20 ) NOT NULL ,
`order_blnk` BIGINT( 10 ) NOT NULL DEFAULT 0,
`name_blnk` VARCHAR( 100 ) NOT NULL ,
`url_blnk` VARCHAR( 100 ) NOT NULL ,
PRIMARY KEY ( `id_blnk` )
);


# Table blogfunctions
# - id_blog : Id du blog (table blog)
# - article_bfct : 1 si la fonction article est active sur ce blog
# - archive_bfct : 1 si la fonction archive est active sur ce blog
# - find_bfct : 1 si la fonction de recherche est active sur ce blog
# - link_bfct : 1 si la fonction lien est active sur ce blog
# - rss_bfct : 1 si la fonction flux RSS est active sur ce blog
# - photo_bfct : 1 si la fonction photo est active sur ce blog
# - option_bfct : 1 si la fonction option est active sur ce blog

DROP TABLE IF EXISTS `blogfunctions`;
CREATE TABLE `blogfunctions` (
`id_blog` BIGINT( 20 ) NOT NULL ,
`article_bfct` INT( 1 ) NOT NULL ,
`archive_bfct` INT( 1 ) NOT NULL ,
`find_bfct` INT( 1 ) NOT NULL ,
`link_bfct` INT( 1 ) NOT NULL ,
`rss_bfct` INT( 1 ) NOT NULL ,
`photo_bfct` INT( 1 ) NOT NULL ,
`option_bfct` INT( 1 ) NOT NULL ,
PRIMARY KEY ( `id_blog` )
);


# Table blogrss
# - id_bfrs : Id unique du lien RSS
# - id_blog : Id du blog (table blog)
# - name_bfrs : nom du lien flux rss
# - order_bfrs : ordre du lien flux rss
# - url_bfrs : URL du flux RSS
DROP TABLE IF EXISTS `blogfluxrss`;
CREATE TABLE `blogfluxrss` (
`id_bfrs` BIGINT( 20 ) NOT NULL AUTO_INCREMENT,
`id_blog` BIGINT( 20 ) NOT NULL ,
`name_bfrs` VARCHAR( 255 ) NOT NULL,
`order_bfrs` BIGINT( 10 ) NOT NULL DEFAULT 0,
`url_bfrs` VARCHAR( 255 ) NOT NULL,
PRIMARY KEY ( `id_bfrs` )
);