CREATE TABLE `config` (
  `config_id`  INT(11)                       NOT NULL,
  `config_var` VARCHAR(256) COLLATE utf8_bin NOT NULL,
  `config_val` VARCHAR(256) COLLATE utf8_bin NOT NULL
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8
  COLLATE = utf8_bin;

ALTER TABLE `config`
  ADD PRIMARY KEY (`config_id`);

ALTER TABLE `config`
  MODIFY `config_id` INT(11) NOT NULL AUTO_INCREMENT;

INSERT INTO config (config_var, config_val) VALUES ('db_version', '0');
INSERT INTO config (config_var, config_val) VALUES ('site_version', '1');