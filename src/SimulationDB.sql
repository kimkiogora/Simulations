CREATE TABLE `contacts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_name` varchar(50) DEFAULT NULL,
  `contacts` longblob,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB
