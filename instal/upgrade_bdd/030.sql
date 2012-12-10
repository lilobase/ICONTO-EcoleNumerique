ALTER TABLE `kernel_notifications_lastvisit` ADD INDEX ( `user_id` , `node_type` , `node_id` , `module_type` , `module_id` );
ALTER TABLE `kernel_link_user2node` ADD INDEX `node` ( `node_type` , `node_id` ) ;
ALTER TABLE `kernel_link_user2node` ADD INDEX `user` ( `user_type` , `user_id` ) ;
