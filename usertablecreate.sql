CREATE TABLE `users` (
 `userid` int(10) unsigned NOT NULL AUTO_INCREMENT,
 `username` varchar(100) NOT NULL,
 `password` varchar(100) NOT NULL,
 `wins` int(100) DEFAULT NULL,
 `losses` int(100) DEFAULT NULL,
 PRIMARY KEY (`userid`)
)


INSERT INTO users VALUES
  (1, "pongnoob23", "1234", 0, 0)
