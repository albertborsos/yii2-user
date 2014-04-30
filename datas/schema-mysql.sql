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