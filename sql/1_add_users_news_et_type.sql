CREATE TABLE `users`( `user_id` INT NOT NULL AUTO_INCREMENT,
 `user_login` VARCHAR(255),
  `user_pass` VARCHAR(255),
   `user_mail` VARCHAR(255),
    `user_nom` VARCHAR(255),
     `user_prenom` VARCHAR(255),
      `user_type` INT DEFAULT 0,
       PRIMARY KEY (`user_id`) );

CREATE TABLE `news`( `news_id` INT NOT NULL AUTO_INCREMENT, `news_type` INT, `news_title` TEXT, `news_title_contains` TEXT, `news_img` VARCHAR(512), `news_contains` TEXT, `news_user_id` INT, `news_create` DATE, `news_update` DATE, `news_difuse` INT DEFAULT 0, PRIMARY KEY (`news_id`) ); 

CREATE TABLE `news_type`( `nt_id` INT NOT NULL AUTO_INCREMENT, `nt_type` VARCHAR(255), PRIMARY KEY (`nt_id`) ); 