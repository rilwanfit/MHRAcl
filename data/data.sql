CREATE TABLE `role` (
  `rid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `role_name` varchar(45) NOT NULL,
  `status` enum('Active','Inactive') NOT NULL DEFAULT 'Active',
  `created_at` date DEFAULT NULL,
  `updated_at` date DEFAULT NULL,
  PRIMARY KEY (`rid`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE TABLE `user_role` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `role_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE TABLE `resource` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `resource_name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE TABLE `permission` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `permission_name` varchar(45) NOT NULL,
  `resource_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE TABLE `role_permission` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `role_id` int(10) unsigned NOT NULL,
  `permission_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


/* Add Roles */

INSERT INTO `role` (`role_name`, `status`) VALUES ('Guest', 'Active');
INSERT INTO `role` (`role_name`, `status`) VALUES ('Adminstrator', 'Active');

/* Add Rresorces */

INSERT INTO `resource` (`resource_name`) VALUES ('Application\\Controller\\Index');
INSERT INTO `resource` (`resource_name`) VALUES ('MHRUser\\Controller\\Index');


/* Add Users */
INSERT INTO `user` (`email`, `password`, `active`) VALUES ('example.1@example.com', 'd7d833534a39afbac08ec536bed7ae9eeac45638', '1');
INSERT INTO `user` (`email`, `password`, `active`) VALUES ('example.2@example.com', 'd7d833534a39afbac08ec536bed7ae9eeac45638', '1');
INSERT INTO `user` (`email`, `password`, `active`) VALUES ('example.3@example.com', 'd7d833534a39afbac08ec536bed7ae9eeac45638', '1');

/* Add User Roles */
INSERT INTO `user_role` (`user_id`, `role_id`) VALUES (1, 1);
INSERT INTO `user_role` (`user_id`, `role_id`) VALUES (2, 2);
INSERT INTO `user_role` (`user_id`, `role_id`) VALUES (3, 3);

/* Add Permissions */
INSERT INTO `permission` (`permission_name`, `resource_id`) VALUES ('index', 1);
INSERT INTO `permission` (`permission_name`, `resource_id`) VALUES ('index', 2);
INSERT INTO `permission` (`permission_name`, `resource_id`) VALUES ('show', 1);
INSERT INTO `permission` (`permission_name`, `resource_id`) VALUES ('test', 1);

/* Add User Role Permissions */
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (1, 1);
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (1, 2);
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (1, 3);
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (1, 4);
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (2, 1);
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (2, 2);
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (3, 1);
INSERT INTO `role_permission` (`role_id`, `permission_id`) VALUES (3, 3);