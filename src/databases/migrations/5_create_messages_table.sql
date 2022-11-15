DROP TABLE IF EXISTS messages;

CREATE TABLE `messages` (
  `id` int NOT NULL AUTO_INCREMENT,
  `from` int DEFAULT NULL,
  `to` int DEFAULT NULL,
  `content` varchar(64) NOT NULL,
  `opened` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `messages_id_uindex` (`id`),
  KEY `messages_users_id_fk` (`from`),
  KEY `messages_users_id_fk_2` (`to`),
  CONSTRAINT `messages_users_id_fk` FOREIGN KEY (`from`) REFERENCES `users` (`id`),
  CONSTRAINT `messages_users_id_fk_2` FOREIGN KEY (`to`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;