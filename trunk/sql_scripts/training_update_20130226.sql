ALTER TABLE `employees` ADD INDEX ( `email` , `password` ) ;

ALTER TABLE `login_status` ADD INDEX ( `signature` ) ;

ALTER TABLE `evaluations` ADD INDEX ( `rank` ) ;