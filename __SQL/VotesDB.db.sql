BEGIN TRANSACTION;
CREATE TABLE IF NOT EXISTS `votes` (
	`id`	integer NOT NULL PRIMARY KEY AUTOINCREMENT,
	`name`	varchar NOT NULL,
	`description`	text NOT NULL,
	`creator_id`	integer NOT NULL,
	`vote_category_id`	integer NOT NULL,
	`is_quiz`	tinyint ( 1 ) NOT NULL DEFAULT '0',
	`status`	varchar NOT NULL CHECK("status" in ( 'N' , 'A' , 'I' )),
	`image`	varchar,
	`created_at`	datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`updated_at`	datetime,
	FOREIGN KEY(`creator_id`) REFERENCES `users`(`id`) on delete RESTRICT,
	FOREIGN KEY(`vote_category_id`) REFERENCES `vote_categories`(`id`) on delete RESTRICT
);
INSERT INTO `votes` (id,name,description,creator_id,vote_category_id,is_quiz,status,image,created_at,updated_at) VALUES (1,'To be or not to be ?','Still trying to find an answer',1,1,0,'A','tobe.png','2018-07-14 11:56:26',NULL),
 (2,'Who Framed Roger Rabbit ?','Who Framed Roger Rabbit is a 1988 American live-action/animated fantasy film directed by Robert Zemeckis, produced by Frank Marshall and Robert Watts, and written by Jeffrey Price and Peter S. Seaman. The film is based on Gary K. Wolf''s 1981 novel Who Censored Roger Rabbit? The film stars Bob Hoskins, Christopher Lloyd, Charles Fleischer, Stubby Kaye, and Joanna Cassidy. Combining live-action and animation, the film is set in Hollywood during the late 1940s, where animated characters and people co-exist. The story follows Eddie Valiant, a private detective who must exonerate "Toon" (i.e. cartoon character) Roger Rabbit, who is accused of murdering a wealthy businessman.',1,2,1,'N',NULL,'2018-07-14 11:56:26',NULL),
 (3,'How many weeks in a year ?','how many weeks in a year',1,3,1,'I',NULL,'2018-07-14 11:56:26',NULL);
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
INSERT INTO `vote_items` (id,vote_id,name,ordering,is_correct,image,created_at,updated_at) VALUES (1,1,'To be...',1,0,'to_be.png','2018-07-14 11:56:26',NULL),
 (2,1,'Not to be...',2,0,NULL,'2018-07-14 11:56:26',NULL),
 (3,1,'That is really a big question...',3,0,NULL,'2018-07-14 11:56:26',NULL),
 (4,2,'Cloverleaf Industry Company',1,0,NULL,'2018-07-14 11:56:26',NULL),
 (5,2,'Judge Doom',2,1,'judge_doom.png','2018-07-14 11:56:27',NULL),
 (6,2,'Marvin Acme',3,0,'marvin-acme.png','2018-07-14 11:56:27',NULL),
 (7,2,'Eddie Valiant',4,0,'eddie_valiant.png','2018-07-14 11:56:27',NULL),
 (8,3,'42',1,0,NULL,'2018-07-14 11:56:27',NULL),
 (9,3,'50',2,0,NULL,'2018-07-14 11:56:27',NULL),
 (10,3,'52',3,1,NULL,'2018-07-14 11:56:27',NULL),
 (11,3,'100',4,0,NULL,'2018-07-14 11:56:27',NULL);
CREATE TABLE IF NOT EXISTS `vote_categories` (
	`id`	integer NOT NULL PRIMARY KEY AUTOINCREMENT,
	`name`	varchar NOT NULL,
	`active`	tinyint ( 1 ) NOT NULL DEFAULT '0',
	`created_at`	datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`updated_at`	datetime
);
INSERT INTO `vote_categories` (id,name,active,created_at,updated_at) VALUES (1,'Classic literature',1,'2018-07-14 11:56:26','2018-07-14 11:56:26'),
 (2,'Cartoons',1,'2018-07-14 11:56:26','2018-07-14 11:56:26'),
 (3,'Earth&World',0,'2018-07-14 11:56:26','2018-07-14 11:56:26'),
 (4,'4',1,'2018-07-23 06:09:05',NULL),
 (5,'55',0,'2018-07-23 06:09:15',NULL);
CREATE TABLE IF NOT EXISTS `users_groups` (
	`id`	integer NOT NULL PRIMARY KEY AUTOINCREMENT,
	`user_id`	integer NOT NULL,
	`group_id`	integer NOT NULL,
	`created_at`	datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
	FOREIGN KEY(`user_id`) REFERENCES `users`(`id`) on delete RESTRICT,
	FOREIGN KEY(`group_id`) REFERENCES `groups`(`id`) on delete RESTRICT
);
INSERT INTO `users_groups` (id,user_id,group_id,created_at) VALUES (1,1,1,'2018-07-14 12:19:08'),
 (2,1,2,'2018-07-14 12:19:08'),
 (3,2,1,'2018-07-14 12:19:08'),
 (4,2,2,'2018-07-14 12:19:08'),
 (5,3,1,'2018-07-14 12:19:08'),
 (6,3,2,'2018-07-14 12:19:08'),
 (7,4,1,'2018-07-14 12:19:08'),
 (8,4,2,'2018-07-14 12:19:08');
CREATE TABLE IF NOT EXISTS `users` (
	`id`	INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
	`name`	VARCHAR ( 255 ) NOT NULL COLLATE BINARY,
	`email`	VARCHAR ( 255 ) NOT NULL COLLATE BINARY,
	`password`	VARCHAR ( 255 ) NOT NULL COLLATE BINARY,
	`remember_token`	VARCHAR ( 255 ) DEFAULT NULL COLLATE BINARY,
	`created_at`	DATETIME DEFAULT NULL,
	`updated_at`	DATETIME DEFAULT NULL,
	`status`	varchar NOT NULL DEFAULT 'N' CHECK("status" in ( 'N' , 'A' , 'I' ))
);
INSERT INTO `users` (id,name,email,password,remember_token,created_at,updated_at,status) VALUES (1,'admin','admin@mail.com','$2y$10$gHefLgYEq1nTMLfoGi27CO9FahpZRQyN4oBNIkf3RrUXlFG0609zG','dNlKHeLSUYFrzIFGC172eNwjpG7MZ4Kx1I6lUZbNu6Nenul2Rt28cuGTizDC','2018-07-14 07:34:26','2018-07-14 07:34:26','N'),
 (2,'Pat Longred','pat_longred@site.com','$2y$10$4HKtZ2FHSm6zpax1.WHnJOOlZsoWC/P4E5CfFH1Rbe3.aPCihkas6',NULL,NULL,NULL,'A'),
 (3,'Tony Black','tony_black@site.com','$2y$10$IySJYsCtNX4JPIeIi3imu.scg1S1d.4/GbTDjFUq1EFe.SgT0DRmu',NULL,NULL,NULL,'A'),
 (4,'Adam Lang','adam_lang@site.com','$2y$10$o9NDYNJZruEuDseNl1MlOeh.jtOh2q7R3/DuGtW6PWp7jogNJy3sm',NULL,NULL,NULL,'I');
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
INSERT INTO `migrations` (id,migration,batch) VALUES (12,'2014_10_12_000000_create_users_table',1),
 (13,'2014_10_12_100000_create_password_resets_table',1),
 (35,'2018_07_13_051201_create_vote_categories_table',2),
 (36,'2018_07_13_150151_create_votes_table',2),
 (37,'2018_07_13_152716_create_vote_items_table',2),
 (38,'2018_07_14_083547_modify_users_table_status',2),
 (42,'2018_07_14_083752_create_users_groups_table',3);
CREATE TABLE IF NOT EXISTS `groups` (
	`id`	integer NOT NULL PRIMARY KEY AUTOINCREMENT,
	`name`	varchar NOT NULL,
	`description`	varchar NOT NULL,
	`created_at`	datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
);
INSERT INTO `groups` (id,name,description,created_at) VALUES (1,'Admin','Administrator','2018-07-14 12:19:07'),
 (2,'Manager','Manager description...','2018-07-14 12:19:07'),
 (3,'User','User description...','2018-07-14 12:19:07');
CREATE INDEX IF NOT EXISTS `votes_vote_category_id_status_name_index` ON `votes` (
	`vote_category_id`,
	`status`,
	`name`
);
CREATE UNIQUE INDEX IF NOT EXISTS `votes_name_unique` ON `votes` (
	`name`
);
CREATE INDEX IF NOT EXISTS `votes_is_quiz_status_index` ON `votes` (
	`is_quiz`,
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
