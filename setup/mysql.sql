
CREATE DATABASE IF NOT EXISTS `senpa` ;
CREATE TABLE IF NOT EXISTS `senpa`.`admins` (
   `user_id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
   `user_name` VARCHAR( 64 ) NOT NULL ,
   `user_email` VARCHAR( 64 ) NOT NULL ,
   `user_pass` VARCHAR( 256 ) NOT NULL ,
   `user_status` CHAR( 8 ) NOT NULL ,
   `user_lang` CHAR( 2 ) NOT NULL ,
   `last_login` VARCHAR( 24 ) ,
   `created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
   `modified` VARCHAR( 24 ) ,
   `crypt_key` VARCHAR( 24 ) ,
    UNIQUE (`user_name`),
    UNIQUE (`user_email`)
) ENGINE = MYISAM ;
CREATE TABLE IF NOT EXISTS `senpa`.`passwd` (
  `pass_id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
  `user_id` INT( 11 ) NOT NULL,
  `created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `expiration` VARCHAR( 12 ) NOT NULL ,
  `group_name` VARCHAR( 8 ) NOT NULL ,
  `site_name` VARCHAR( 64 ) NOT NULL ,
  `login_name` VARCHAR( 64 ) NOT NULL ,
  `login_pass` VARCHAR( 128 ) NOT NULL ,
  `site_url` VARCHAR( 1024 ) NOT NULL,
  `totp_name` VARCHAR( 256) NOT NULL,
  `totp_time` INT( 2 ),
  `totp_lengh` INT( 2 ),
  `comments` TEXT( 2048 )
) ENGINE = MYISAM ;
CREATE TABLE IF NOT EXISTS `senpa`.`activation` (
  `act_id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
  `user_id` INT( 11 ) NOT NULL,
  `created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `token` VARCHAR( 64 ) NOT NULL ,
  `type` VARCHAR( 10 ) NOT NULL ,
  `user_email` VARCHAR( 64 ) NOT NULL ,
  `status` VARCHAR( 4 )
) ENGINE = MYISAM ;
CREATE TABLE IF NOT EXISTS `senpa`.`license` (
  `lic_id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
  `user_id` INT( 11 ) NOT NULL,
  `created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `expires` VARCHAR( 10 ) NOT NULL ,
  `token` VARCHAR( 32 ) NOT NULL ,
  `type` CHAR( 1 ) NOT NULL ,
  `user_email` VARCHAR( 64 ) NOT NULL ,
  `status` VARCHAR( 4 )
) ENGINE = MYISAM ;
