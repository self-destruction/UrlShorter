/*creating db*/

CREATE DATABASE `url_shorter`;

USE `url_shorter`;

CREATE TABLE `user` (
  `login` VARCHAR(60) NOT NULL,
  `hash` CHAR(64) NOT NULL,
  `date_of_registration` DATETIME NOT NULL,
  `last_login` DATETIME NOT NULL,
  `count_of_urls` INT NOT NULL DEFAULT 0,
  PRIMARY KEY (`login`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `url` (
  `hash` CHAR(12) NOT NULL,
  `long_url` VARCHAR(500) NOT NULL,
  `user_login` VARCHAR(60) NOT NULL,
  `count_of_view` INT NOT NULL DEFAULT 0,
  `date_of_creation` DATETIME,
  PRIMARY KEY (`hash`),
  CONSTRAINT `fk_user_login` FOREIGN KEY (`user_login`) REFERENCES `user` (`login`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE INDEX `idx_user_login` ON `url` (`user_login`);

CREATE TABLE `transition` (
  `url_hash` CHAR(12) NOT NULL,
  `date_of_transition` DATETIME NOT NULL,
  `referer` VARCHAR(500),
  CONSTRAINT `fk_url_hash` FOREIGN KEY (`url_hash`) REFERENCES `url` (`hash`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE INDEX `idx_url_hash` ON `transition` (`url_hash`);

/*triggers*/

DELIMITER $$
CREATE TRIGGER `increasing_count_of_urls_for_the_user` AFTER INSERT ON `url`
FOR EACH ROW BEGIN
UPDATE `user` SET `count_of_urls` = `count_of_urls` + 1 WHERE `login` = NEW.`user_login`;
END$$
DELIMITER ;

DELIMITER $$
CREATE TRIGGER `decreasing_count_of_urls_for_the_user` AFTER DELETE ON `url`
FOR EACH ROW BEGIN
UPDATE `user` SET `count_of_urls` = `count_of_urls` - 1 WHERE `login` = OLD.`user_login`;
END$$
DELIMITER ;

DELIMITER $$
CREATE TRIGGER `increasing_count_of_views_for_the_url` AFTER INSERT ON `transition`
FOR EACH ROW BEGIN
UPDATE `url` SET `count_of_view` = `count_of_view` + 1 WHERE `hash` = NEW.`url_hash`;
END$$
DELIMITER ;

/*deleting */

DROP TRIGGER IF EXISTS `increasing_count_of_views_for_the_url`;
DROP TRIGGER IF EXISTS `decreasing_count_of_urls_for_the_user`;
DROP TRIGGER IF EXISTS `increasing_count_of_urls_for_the_user`;

DROP TABLE IF EXISTS `transition`;
DROP TABLE IF EXISTS `url`;
DROP TABLE IF EXISTS `user`;
DROP DATABASE IF EXISTS `url_shorter`;

/*test data*/

/*login - admin1, password - admin1234*/
INSERT INTO `user` VALUES (
	'admin1', '878a66ef3f84e0bb81d07ebafb4cfebae631eb593110351a232b2dbf01be1b4f', 
    '2017-08-25 13:00:41', '2017-09-01 18:22:30', 0
);
INSERT INTO `user` VALUES (
	'admin2', '878a66ef3f84e0bb81d07ebafb4cfebae631eb593110351a232b2dbf01be1b4f', 
    '2017-09-02 09:43:19', '2017-09-02 13:01:54', 0
);

INSERT INTO `url` VALUES (
	'EfTfj4RL2qzF', 'https://www.youtube.com/watch?v=qrwlk7_GF9g', 
    'admin1', 0, '2017-08-26 18:45:05'
);
INSERT INTO `url` VALUES (
	'fC3fj4Q32qCo', 'https://www.youtube.com/watch?v=qrwlk7_GF9g', 
    'admin1', 0, '2017-08-26 10:31:54'
);
INSERT INTO `url` VALUES (
	'R4IkETI0IXJV', 'https://www.youtube.com/watch?v=ccZKTJ8jTXY', 
    'admin2', 0, '2017-09-05 12:26:31'
);
INSERT INTO `url` VALUES (
	'HndKUw9dilMt', 
    'https://translate.google.ru/#ru/en/%D1%82%D0%B5%D1%81%D1%82%D0%BE%D0%B2%D1%8B%D0%B5%20%D0%B4%D0%B0%D0%BD%D0%BD%D1%8B%D0%B5', 
    'admin1', 0, '2017-08-26 18:46:58'
);
INSERT INTO `url` VALUES (
	'HguMh6tEnOMC', 
    'https://docs.google.com/document/d/1gbMrjapjTdnFqqZ1Fj7u3SL07ucyLBIViwiyFDRzgNY', 
    'admin1', 0, '2017-08-26 18:47:09'
);

INSERT INTO `transition` VALUES ('R4IkETI0IXJV', '2017-09-04 21:45:24', 'https://vk.com/tobeorgroup');
INSERT INTO `transition` VALUES ('R4IkETI0IXJV', '2017-09-04 21:56:32', 'https://vk.com/tobeorgroup');
INSERT INTO `transition` VALUES ('R4IkETI0IXJV', '2017-09-04 23:21:49', 'https://vk.com/tobeorgroup');
INSERT INTO `transition` VALUES ('R4IkETI0IXJV', '2017-09-04 04:15:44', 'https://vk.com/tobeorgroup');
INSERT INTO `transition` VALUES ('R4IkETI0IXJV', '2017-09-04 12:33:34', 'https://vk.com/tobeorgroup');
INSERT INTO `transition` VALUES ('R4IkETI0IXJV', '2017-09-04 15:00:25', 'https://vk.com/tobeorgroup');

INSERT INTO `transition` VALUES ('R4IkETI0IXJV', '2017-09-04 19:31:05', null);

INSERT INTO `transition` VALUES ('R4IkETI0IXJV', '2017-09-05 03:11:46', 'https://vk.com/public152285141');
INSERT INTO `transition` VALUES ('R4IkETI0IXJV', '2017-09-05 04:56:55', 'https://vk.com/public152285141');
INSERT INTO `transition` VALUES ('R4IkETI0IXJV', '2017-09-05 04:57:32', 'https://vk.com/public152285141');
INSERT INTO `transition` VALUES ('R4IkETI0IXJV', '2017-09-05 04:58:51', 'https://vk.com/public152285141');
INSERT INTO `transition` VALUES ('R4IkETI0IXJV', '2017-09-05 06:13:23', 
	'https://pikabu.ru/story/za_chto_ya_lyublyu_stokovyie_kartinki_5317508');
INSERT INTO `transition` VALUES ('R4IkETI0IXJV', '2017-09-05 07:09:13', 
	'https://pikabu.ru/story/za_chto_ya_lyublyu_stokovyie_kartinki_5317508');
INSERT INTO `transition` VALUES ('R4IkETI0IXJV', '2017-09-05 07:14:26', 
	'https://pikabu.ru/story/za_chto_ya_lyublyu_stokovyie_kartinki_5317508');
INSERT INTO `transition` VALUES ('R4IkETI0IXJV', '2017-09-05 07:56:49', 
	'https://pikabu.ru/story/za_chto_ya_lyublyu_stokovyie_kartinki_5317508');
    
INSERT INTO `transition` VALUES ('EfTfj4RL2qzF', '2017-08-26 02:52:10', 
	'https://e.mail.ru/messages/inbox/');
INSERT INTO `transition` VALUES ('EfTfj4RL2qzF', '2017-08-27 15:10:28', 
	'https://e.mail.ru/messages/inbox/');
INSERT INTO `transition` VALUES ('EfTfj4RL2qzF', '2017-08-28 13:20:31', 
	'https://e.mail.ru/messages/inbox/');