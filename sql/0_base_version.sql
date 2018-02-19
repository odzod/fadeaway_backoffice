CREATE TABLE `config`( `current_version` INT DEFAULT 0, `site_version` INT DEFAULT 1 ); 

ALTER TABLE `config` CHANGE `current_version` `config_id` INT(11) NOT NULL AUTO_INCREMENT, CHANGE `site_version` `config_var` VARCHAR(255) NULL, ADD COLUMN `config_val` VARCHAR(255) NULL AFTER `config_var`, ADD PRIMARY KEY (`config_id`); 

INSERT INTO `config` (`config_id`, `config_var`, `config_val`) VALUES (NULL, 'db_version', '0'); 
INSERT INTO `config` (`config_var`, `config_val`) VALUES ('site_version', '1'); 