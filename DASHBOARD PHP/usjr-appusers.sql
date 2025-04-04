use usjr;

DROP TABLE IF EXISTS `appusers`;

CREATE TABLE `appusers`(
	`uid` INT AUTO_INCREMENT, PRIMARY KEY(`uid`),
    `name` VARCHAR(45) NOT NULL,
    `password` VARCHAR(100) NOT NULL
);

describe appusers;

SELECT * FROM appusers;