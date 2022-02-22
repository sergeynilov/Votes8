BEGIN TRANSACTION;
CREATE TABLE IF NOT EXISTS `votes` (
	`id`	integer NOT NULL PRIMARY KEY AUTOINCREMENT,
	`name`	varchar NOT NULL,
	`description`	text NOT NULL,
	`creator_id`	integer NOT NULL,
	`vote_category_id`	integer NOT NULL,
	`is_quiz`	tinyint ( 1 ) NOT NULL DEFAULT '0',
	`is_homepage`	tinyint ( 1 ) NOT NULL DEFAULT '0',
	`status`	varchar NOT NULL CHECK("status" in ( 'N' , 'A' , 'I' )),
	`ordering`	integer NOT NULL,
	`image`	varchar,
	`created_at`	datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`updated_at`	datetime,
	FOREIGN KEY(`vote_category_id`) REFERENCES `vote_categories`(`id`) on delete RESTRICT,
	FOREIGN KEY(`creator_id`) REFERENCES `users`(`id`) on delete RESTRICT
);
INSERT INTO `votes` (id,name,description,creator_id,vote_category_id,is_quiz,is_homepage,status,ordering,image,created_at,updated_at) VALUES (1,'To be or not to be ?','Still trying to find an answer Lorem  ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea  commodo consequat. Duis aute irure dolor in reprehenderit in voluptate  velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint  occaecat cupidatat non proident, sunt in culpa qui officia deserunt...',1,1,0,1,'A',1,'tobe.png','2018-08-03 06:25:22','2018-08-06 07:32:40'),
 (2,'Who Framed Roger Rabbit ?','Who Framed Roger Rabbit is a 1988 American live-action/animated fantasy film directed by Robert Zemeckis, produced by Frank Marshall and Robert Watts, and written by Jeffrey Price and Peter S. Seaman. The film is based on Gary K. Wolf''s 1981 novel Who Censored Roger Rabbit? The film stars Bob Hoskins, Christopher Lloyd, Charles Fleischer, Stubby Kaye, and Joanna Cassidy. Combining live-action and animation, the film is set in Hollywood during the late 1940s, where animated characters and people co-exist. The story follows Eddie Valiant, a private detective who must exonerate "Toon" (i.e. cartoon character) Roger Rabbit, who is accused of murdering a wealthy businessman.',1,2,1,1,'A',1,'title.jpg','2018-08-04 06:25:22','2018-08-04 06:25:22'),
 (3,'How many weeks in a year ?','how many weeks in a year',1,3,1,1,'I',3,NULL,'2018-08-03 06:25:22',NULL),
 (4,'Which fictional city is the home of Batman?','You must to know something about Batman...',1,2,1,1,'A',1,'city.jpg','2018-08-05 06:25:22','2018-08-08 11:39:53'),
 (5,'Who was known as the Maid of Orleans?','Question about France history lorem  ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod  tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim  veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea  commodo consequat. Duis aute irure dolor in reprehenderit in voluptate  velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint  occaecat cupidatat non proident, sunt in culpa qui officia deserunt  mollit anim id est laborum.',1,4,1,1,'A',5,NULL,'2018-08-06 06:25:22','2018-08-06 06:25:22'),
 (6,'rerr','sss',5,6,1,1,'A',2,NULL,'2018-08-04 12:00:37',NULL),
 (7,'AA','33',5,7,1,1,'A',3,NULL,'2018-08-04 12:07:14',NULL),
 (8,'AAx8','xxx88888888',5,1,0,0,'N',88,'a3_landscape_a3_lands.jpg','2018-08-04 12:13:12','2018-08-08 12:25:21'),
 (9,'aabb99','aabb 99',5,5,1,1,'A',99,NULL,'2018-08-08 15:16:56',NULL),
 (10,'10','10',5,5,1,1,'A',10,NULL,'2018-08-08 15:18:57',NULL),
 (11,'11','11',5,7,1,1,'A',11,'a5_landscape.jpg','2018-08-08 15:20:36',NULL);
CREATE TABLE IF NOT EXISTS `vote_items` (
	`id`	integer NOT NULL PRIMARY KEY AUTOINCREMENT,
	`vote_id`	integer,
	`name`	varchar NOT NULL,
	`ordering`	integer NOT NULL,
	`is_correct`	tinyint ( 1 ) NOT NULL DEFAULT '0',
	`image`	varchar,
	`created_at`	datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`updated_at`	datetime,
	FOREIGN KEY(`vote_id`) REFERENCES `votes`(`id`) on delete RESTRICT
);
INSERT INTO `vote_items` (id,vote_id,name,ordering,is_correct,image,created_at,updated_at) VALUES (1,1,'To be...',1,0,'to_be.png','2018-08-03 06:25:22',NULL),
 (2,1,'Not to be...',2,0,NULL,'2018-08-03 06:25:22',NULL),
 (3,1,'That is really a big question...',3,0,NULL,'2018-08-03 06:25:22',NULL),
 (4,2,'Cloverleaf Industry Company',1,0,NULL,'2018-08-03 06:25:22',NULL),
 (5,2,'Judge Doom',2,1,'judge_doom.png','2018-08-03 06:25:22',NULL),
 (6,2,'Marvin Acme',3,0,'marvin-acme.png','2018-08-03 06:25:22',NULL),
 (7,2,'Eddie Valiant',4,0,'eddie_valiant.png','2018-08-03 06:25:22',NULL),
 (8,3,'42',1,0,NULL,'2018-08-03 06:25:22',NULL),
 (9,3,'50',2,0,NULL,'2018-08-03 06:25:22',NULL),
 (10,3,'52',3,1,NULL,'2018-08-03 06:25:22',NULL),
 (11,3,'100',4,0,NULL,'2018-08-03 06:25:22',NULL),
 (12,4,'London',1,0,'london.png','2018-08-03 06:25:22',NULL),
 (13,4,'Gotham City',2,1,'gotham.png','2018-08-03 06:25:22',NULL),
 (14,4,'Rome',3,0,'rome.png','2018-08-03 06:25:23',NULL),
 (15,5,'Joan of Arc',1,1,'joan_of_arc.png','2018-08-03 06:25:23',NULL),
 (16,5,'Margaret Thatcher',2,0,'margaret_thatcher.png','2018-08-03 06:25:23',NULL),
 (17,5,'Madeleine Albright',3,0,'','2018-08-03 06:25:23',NULL),
 (18,5,'Condoleezza Rice',4,0,'condoleezza_ice.png','2018-08-03 06:25:23',NULL);
CREATE TABLE IF NOT EXISTS `vote_categories` (
	`id`	integer NOT NULL PRIMARY KEY AUTOINCREMENT,
	`name`	varchar NOT NULL,
	`active`	tinyint ( 1 ) NOT NULL DEFAULT '0',
	`created_at`	datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`updated_at`	datetime
);
INSERT INTO `vote_categories` (id,name,active,created_at,updated_at) VALUES (1,'Classic literature',1,'2018-08-03 06:25:22',NULL),
 (2,'Movie&Cartoons',1,'2018-08-02 06:25:22',NULL),
 (3,'Earth&World',0,'2018-08-04 06:25:22',NULL),
 (4,'History',1,'2018-08-03 06:25:22',NULL),
 (5,'aabb',1,'2018-08-01 06:59:03','2018-08-03 07:47:11'),
 (6,'aaaa',0,'2018-08-06 06:59:09',NULL),
 (7,'aazzzz',1,'2018-08-03 07:01:39',NULL);
CREATE TABLE IF NOT EXISTS `users_groups` (
	`id`	integer NOT NULL PRIMARY KEY AUTOINCREMENT,
	`user_id`	integer NOT NULL,
	`group_id`	integer NOT NULL,
	`created_at`	datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
	FOREIGN KEY(`group_id`) REFERENCES `groups`(`id`) on delete RESTRICT,
	FOREIGN KEY(`user_id`) REFERENCES `users`(`id`) on delete RESTRICT
);
INSERT INTO `users_groups` (id,user_id,group_id,created_at) VALUES (1,1,1,'2018-08-03 06:25:23'),
 (2,1,2,'2018-08-03 06:25:23'),
 (3,2,1,'2018-08-03 06:25:23'),
 (4,2,2,'2018-08-03 06:25:23'),
 (5,3,1,'2018-08-03 06:25:23'),
 (6,3,2,'2018-08-03 06:25:23'),
 (7,4,1,'2018-08-03 06:25:23'),
 (8,4,2,'2018-08-03 06:25:23');
CREATE TABLE IF NOT EXISTS `users` (
	`id`	integer NOT NULL PRIMARY KEY AUTOINCREMENT,
	`name`	varchar NOT NULL,
	`email`	varchar NOT NULL,
	`password`	varchar NOT NULL,
	`remember_token`	varchar,
	`created_at`	datetime,
	`updated_at`	datetime,
	`status`	varchar NOT NULL DEFAULT 'N' CHECK("status" in ( 'N' , 'A' , 'I' ))
);
INSERT INTO `users` (id,name,email,password,remember_token,created_at,updated_at,status) VALUES (2,'Pat Longred','pat_longred@site.com','$2y$10$HwCGnQ8mV3ugk/POMimcBeZFI054mRK3qkpeckbuoQ/eyRfPZrG8S',NULL,NULL,NULL,'A'),
 (3,'Tony Black','tony_black@site.com','$2y$10$S.wn.8EIY01jiR0whXe8F.U97eQSBdyf4xwXaxma4.407BoKAO0Ka',NULL,NULL,NULL,'A'),
 (4,'Adam Lang','adam_lang@site.com','$2y$10$pvDgkZYpa3M50sFfYjn6he9zVZ0wgbg4q3b2ItKynzVzM4McCsp9i',NULL,NULL,NULL,'I'),
 (5,'admin','admin@mail.com','$2y$10$PI2WdPtiIo.HPnhs7MEoxesV36.d7GMmP6tT1aspaX./6KDxIgmG.','xq4gKMaHLKAWl82547bi2s7xzWGCdtUVWTNv7EFDW4qbhI0mHBGPICGa37ft',NULL,NULL,'N');
CREATE TABLE IF NOT EXISTS `settings` (
	`id`	integer NOT NULL PRIMARY KEY AUTOINCREMENT,
	`name`	varchar NOT NULL,
	`value`	varchar NOT NULL,
	`created_at`	datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`updated_at`	datetime
);
INSERT INTO `settings` (id,name,value,created_at,updated_at) VALUES (1,'site_name','Select & Vote !','2018-08-03 06:38:00',NULL),
 (2,'copyright_text','All rights reserved','2018-08-03 06:38:00',NULL),
 (3,'site_heading','Make your choice!','2018-08-03 06:38:00',NULL),
 (4,'site_subheading','Vote''em all !','2018-08-03 06:38:00',NULL),
 (5,'contact_us_email','vote@vote.com','2018-08-03 06:38:00',NULL),
 (6,'contact_us_phone','Chicago, US','2018-08-03 06:38:00',NULL),
 (7,'home_page_items_per_page','8','2018-08-03 06:38:00',NULL),
 (8,'backend_per_page','5','2018-08-03 06:44:26',NULL);
CREATE TABLE IF NOT EXISTS `password_resets` (
	`email`	varchar NOT NULL,
	`token`	varchar NOT NULL,
	`created_at`	datetime
);
CREATE TABLE IF NOT EXISTS `migrations` (
	`id`	integer NOT NULL PRIMARY KEY AUTOINCREMENT,
	`migration`	varchar NOT NULL,
	`batch`	integer NOT NULL
);
INSERT INTO `migrations` (id,migration,batch) VALUES (52,'2014_10_12_000000_create_users_table',1),
 (53,'2014_10_12_100000_create_password_resets_table',1),
 (54,'2018_01_01_145312_create_settings_table',1),
 (55,'2018_07_13_051201_create_vote_categories_table',1),
 (56,'2018_07_13_150151_create_votes_table',1),
 (57,'2018_07_13_152716_create_vote_items_table',1),
 (58,'2018_07_14_083547_modify_users_table_status',1),
 (59,'2018_07_14_083752_create_users_groups_table',1);
CREATE TABLE IF NOT EXISTS `groups` (
	`id`	integer NOT NULL PRIMARY KEY AUTOINCREMENT,
	`name`	varchar NOT NULL,
	`description`	varchar NOT NULL,
	`created_at`	datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
);
INSERT INTO `groups` (id,name,description,created_at) VALUES (1,'Admin','Administrator','2018-08-03 06:25:23'),
 (2,'Manager','Manager description...','2018-08-03 06:25:23'),
 (3,'User','User description...','2018-08-03 06:25:23');
CREATE INDEX IF NOT EXISTS `votes_vote_category_id_status_name_index` ON `votes` (
	`vote_category_id`,
	`status`,
	`name`
);
CREATE INDEX IF NOT EXISTS `votes_ordering_status_index` ON `votes` (
	`ordering`,
	`status`
);
CREATE UNIQUE INDEX IF NOT EXISTS `votes_name_unique` ON `votes` (
	`name`
);
CREATE INDEX IF NOT EXISTS `votes_is_quiz_status_index` ON `votes` (
	`is_quiz`,
	`status`
);
CREATE INDEX IF NOT EXISTS `votes_is_homepage_status_index` ON `votes` (
	`is_homepage`,
	`status`
);
CREATE INDEX IF NOT EXISTS `votes_creator_id_status_name_index` ON `votes` (
	`creator_id`,
	`status`,
	`name`
);
CREATE INDEX IF NOT EXISTS `votes_created_at_index` ON `votes` (
	`created_at`
);
CREATE INDEX IF NOT EXISTS `vote_items_vote_id_ordering_index` ON `vote_items` (
	`vote_id`,
	`ordering`
);
CREATE UNIQUE INDEX IF NOT EXISTS `vote_items_vote_id_name_unique` ON `vote_items` (
	`vote_id`,
	`name`
);
CREATE INDEX IF NOT EXISTS `vote_items_vote_id_is_correct_index` ON `vote_items` (
	`vote_id`,
	`is_correct`
);
CREATE INDEX IF NOT EXISTS `vote_items_created_at_index` ON `vote_items` (
	`created_at`
);
CREATE UNIQUE INDEX IF NOT EXISTS `vote_categories_name_unique` ON `vote_categories` (
	`name`
);
CREATE INDEX IF NOT EXISTS `vote_categories_created_at_index` ON `vote_categories` (
	`created_at`
);
CREATE INDEX IF NOT EXISTS `vote_categories_active_name_index` ON `vote_categories` (
	`active`,
	`name`
);
CREATE INDEX IF NOT EXISTS `users_status_index` ON `users` (
	`status`
);
CREATE UNIQUE INDEX IF NOT EXISTS `users_groups_user_id_group_id_unique` ON `users_groups` (
	`user_id`,
	`group_id`
);
CREATE INDEX IF NOT EXISTS `users_groups_created_at_index` ON `users_groups` (
	`created_at`
);
CREATE UNIQUE INDEX IF NOT EXISTS `users_email_unique` ON `users` (
	`email`
);
CREATE UNIQUE INDEX IF NOT EXISTS `settings_name_unique` ON `settings` (
	`name`
);
CREATE INDEX IF NOT EXISTS `settings_created_at_index` ON `settings` (
	`created_at`
);
CREATE INDEX IF NOT EXISTS `password_resets_email_index` ON `password_resets` (
	`email`
);
CREATE UNIQUE INDEX IF NOT EXISTS `groups_name_unique` ON `groups` (
	`name`
);
CREATE UNIQUE INDEX IF NOT EXISTS `groups_description_unique` ON `groups` (
	`description`
);
CREATE INDEX IF NOT EXISTS `groups_created_at_index` ON `groups` (
	`created_at`
);
COMMIT;
