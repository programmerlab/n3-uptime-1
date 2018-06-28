CREATE TABLE IF NOT EXISTS `domains` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `domain` varchar(255) NOT NULL,
  `expected_status` int(3) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `created` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY (`domain`)
) ENGINE=InnoDB AUTO_INCREMENT=20938 DEFAULT CHARSET=utf8;

