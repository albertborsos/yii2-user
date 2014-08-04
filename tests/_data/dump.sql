-- Create syntax for TABLE 'tbl_user_usercookies'
CREATE TABLE `tbl_user_usercookies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `cookie_id` varchar(50) DEFAULT NULL,
  `status` varchar(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Create syntax for TABLE 'tbl_user_userdetails'
CREATE TABLE `tbl_user_userdetails` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `name_first` varchar(100) DEFAULT NULL COMMENT 'Keresztnév',
  `name_last` varchar(100) DEFAULT NULL COMMENT 'Vezetéknév',
  `sex` varchar(20) DEFAULT NULL COMMENT 'Nem',
  `country` varchar(100) DEFAULT NULL COMMENT 'Ország',
  `county` varchar(100) DEFAULT NULL COMMENT 'Megye',
  `postal_code` varchar(10) DEFAULT NULL COMMENT 'Irsz',
  `city` varchar(100) DEFAULT NULL COMMENT 'Város',
  `email` varchar(100) DEFAULT NULL COMMENT 'E-mail',
  `phone_1` varchar(30) DEFAULT NULL COMMENT 'Telefonszám/FAX',
  `phone_2` varchar(30) DEFAULT NULL COMMENT 'Mobil',
  `website` varchar(255) DEFAULT NULL COMMENT 'weboldal',
  `comment_private` mediumtext COMMENT 'Privát megjegyzés',
  `google_profile` varchar(255) DEFAULT NULL COMMENT 'Google+ profil',
  `facebook_profile` varchar(255) DEFAULT NULL COMMENT 'Facebook profil',
  `status` varchar(1) DEFAULT NULL COMMENT 'Státusz',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Create syntax for TABLE 'tbl_user_users'
CREATE TABLE `tbl_user_users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(255) DEFAULT NULL COMMENT 'E-mail cím',
  `password_hash` varchar(255) DEFAULT NULL COMMENT 'Jelszó',
  `auth_key` varchar(255) DEFAULT NULL COMMENT 'Authentikációs kulcs',
  `password_reset_token` varchar(255) DEFAULT NULL COMMENT 'Jelszóemlékeztető kulcs',
  `username` varchar(50) DEFAULT NULL COMMENT 'Felhasználónév',
  `created_at` datetime DEFAULT NULL COMMENT 'Regisztráció ideje',
  `activated_at` datetime DEFAULT NULL COMMENT 'Aktiválás ideje',
  `updated_at` datetime DEFAULT NULL COMMENT 'Módosítás ideje',
  `status` varchar(1) DEFAULT NULL COMMENT 'Státusz',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Create syntax for TABLE 'tbl_user_usersessions'
CREATE TABLE `tbl_user_usersessions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `session_id` varchar(50) DEFAULT NULL,
  `status` varchar(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `tbl_user_users` (`id`, `email`, `password_hash`, `auth_key`, `password_reset_token`, `username`, `created_at`, `activated_at`, `updated_at`, `status`)
VALUES
	(1,'albertborsos@me.com','$2y$13$ATfxN7nHdGIC.eZj5THf5.BRh0toZ51/4p5DtUTCanA/VMgLsweW.',NULL,NULL,'albertborsos','2014-04-30 22:41:27','2014-04-30 22:41:28','2014-04-30 22:41:28','a');

INSERT INTO `tbl_user_userdetails` (`id`, `user_id`, `name_first`, `name_last`, `sex`, `country`, `county`, `postal_code`, `city`, `email`, `phone_1`, `phone_2`, `website`, `comment_private`, `google_profile`, `facebook_profile`, `status`)
VALUES
  (1, 1, 'Albert', 'Borsos', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'a');


/**
 * Database schema required by \yii\rbac\DbManager.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @author Alexander Kochetov <creocoder@gmail.com>
 * @link http://www.yiiframework.com/
 * @copyright 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 * @since 2.0
 */

drop table if exists `auth_assignment`;
drop table if exists `auth_item_child`;
drop table if exists `auth_item`;
drop table if exists `auth_rule`;

create table `auth_rule`
(
   `name`                 varchar(64) not null,
   `data`                 text,
   `created_at`           integer,
   `updated_at`           integer,
    primary key (`name`)
) engine InnoDB;

create table `auth_item`
(
   `name`                 varchar(64) not null,
   `type`                 integer not null,
   `description`          text,
   `rule_name`            varchar(64),
   `data`                 text,
   `created_at`           integer,
   `updated_at`           integer,
   primary key (`name`),
   foreign key (`rule_name`) references `auth_rule` (`name`) on delete set null on update cascade,
   key `type` (`type`)
) engine InnoDB;

create table `auth_item_child`
(
   `parent`               varchar(64) not null,
   `child`                varchar(64) not null,
   primary key (`parent`, `child`),
   foreign key (`parent`) references `auth_item` (`name`) on delete cascade on update cascade,
   foreign key (`child`) references `auth_item` (`name`) on delete cascade on update cascade
) engine InnoDB;

create table `auth_assignment`
(
   `item_name`            varchar(64) not null,
   `user_id`              varchar(64) not null,
   `created_at`           integer,
   primary key (`item_name`, `user_id`),
   foreign key (`item_name`) references `auth_item` (`name`) on delete cascade on update cascade
) engine InnoDB;

INSERT INTO `auth_item` (`name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`)
VALUES
  ('admin', 2, 'Adminisztrátor', NULL, NULL, NULL, NULL),
  ('editor', 2, 'Szerkesztő', NULL, NULL, NULL, NULL),
  ('guest', 2, 'Vendég', NULL, NULL, NULL, NULL),
  ('reader', 2, 'Olvasó', NULL, NULL, NULL, NULL);

INSERT INTO `auth_item_child` (`parent`, `child`)
VALUES
  ('admin', 'editor'),
  ('editor', 'reader'),
  ('reader', 'guest');

INSERT INTO `auth_assignment` (`item_name`, `user_id`, `created_at`)
VALUES
  ('admin', '1', 1406725195);
