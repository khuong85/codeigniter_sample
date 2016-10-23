ALTER TABLE `employees` CHANGE `password` `password` VARCHAR( 50 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;

ALTER TABLE `projects` ADD `start_date` DATE NOT NULL AFTER `name` ,ADD `end_date` DATE NOT NULL AFTER `start_date`;