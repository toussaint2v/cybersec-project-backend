DROP TABLE IF EXISTS friends_invitations;

CREATE TABLE `friends_invitations` (
  `from` int NOT NULL,
  `to` int NOT NULL,
  `accepted` tinyint(1) NOT NULL,
  `opened` tinyint(1) NOT NULL,
  PRIMARY KEY (`from`,`to`),
  KEY `friends_invitations_users_to_fk` (`to`),
  CONSTRAINT `friends_invitations_users_from_fk` FOREIGN KEY (`from`) REFERENCES `users` (`id`),
  CONSTRAINT `friends_invitations_users_null_fk` FOREIGN KEY (`from`) REFERENCES `users` (`id`),
  CONSTRAINT `friends_invitations_users_to_fk` FOREIGN KEY (`to`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;