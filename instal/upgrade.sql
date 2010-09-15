
-- UPDATED : cbeyer

ALTER TABLE `module_blog` ADD `template` VARCHAR( 30 ) NULL DEFAULT NULL COMMENT 'Template a utiliser, si different de blog_main.tpl';
ALTER TABLE `module_blog` ADD INDEX ( `url_blog` );  
DROP TABLE `module_welcome_homes`, `module_welcome_url`;
