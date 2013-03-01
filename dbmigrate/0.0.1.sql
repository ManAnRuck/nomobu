/* PROJECTS TABLE */
CREATE TABLE `projects` (`id` int(11) unsigned NOT NULL AUTO_INCREMENT,`name` varchar(255) DEFAULT NULL,PRIMARY KEY (`id`)) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

/* STATES TABLE */
CREATE TABLE `states` (`id` int(11) unsigned NOT NULL AUTO_INCREMENT,`name` varchar(255) DEFAULT NULL,`closes` smallint(1) unsigned DEFAULT '0',`system` smallint(1) DEFAULT '0',PRIMARY KEY (`id`)) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

/* TICKETS TABLE */
CREATE TABLE `tickets` (`id` int(11) unsigned NOT NULL AUTO_INCREMENT,`name` varchar(255) DEFAULT NULL,`description` mediumtext,`states_id` int(11) DEFAULT NULL,`projects_id` int(11) DEFAULT NULL,`attached_to` int(11) DEFAULT NULL,`updated` datetime DEFAULT NULL,`types_id` int(11) DEFAULT NULL,`created` datetime DEFAULT NULL,`created_by` int(11) DEFAULT NULL,PRIMARY KEY (`id`)) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

/* TYPES TABLE */
CREATE TABLE `types` (`id` int(11) unsigned NOT NULL AUTO_INCREMENT,`name` varchar(255) DEFAULT NULL,`system` smallint(1) DEFAULT '0',PRIMARY KEY (`id`)) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

/* UPDATES TABLE */
CREATE TABLE `updates` (`id` int(11) unsigned NOT NULL AUTO_INCREMENT,`tickets_id` int(11) DEFAULT NULL,`description` mediumtext,`updated` datetime DEFAULT NULL,`states_id` int(11) DEFAULT NULL,`attached_to` int(11) DEFAULT NULL,`created_by` int(11) DEFAULT NULL,PRIMARY KEY (`id`)) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

/* USERS TABLE */
CREATE TABLE `users` (`id` int(11) unsigned NOT NULL AUTO_INCREMENT,`name` varchar(255) DEFAULT NULL,`email` mediumtext,`password` varchar(255) DEFAULT NULL,`updated` datetime DEFAULT NULL,PRIMARY KEY (`id`)) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/* CREATE STATES */
INSERT INTO `states` (`name`, `closes`, `system`) VALUES ('Open',0,1), ('In Progress',0,1), ('Done',0,1), ('Closed',1,1);

/* CREATE TYPES */
INSERT INTO `types` (`name`, `system`) VALUES ('Bug',1), ('Feature',1), ('Todo',1), ('Improvement',1);