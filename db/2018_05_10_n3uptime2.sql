CREATE TABLE IF NOT EXISTS `domains` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `domain` varchar(255) NOT NULL,
  `expected_status` int(3) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `created` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY (`domain`)
) ENGINE=InnoDB AUTO_INCREMENT=20938 DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `scans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `domain_id` int(11) NOT NULL,
  `created` int(11) DEFAULT NULL,
  `response_time` timestamp NULL DEFAULT NULL,
  `path` varchar(255) DEFAULT NULL,
  `headers` text,
  PRIMARY KEY (`id`),
  KEY `domain_id` (`domain_id`),
  CONSTRAINT `domain_id_fk` FOREIGN KEY (`domain_id`) REFERENCES `domains` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=434420 DEFAULT CHARSET=latin1;

