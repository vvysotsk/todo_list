CREATE DATABASE test;
use test;
CREATE TABLE users (
  id int NOT NULL AUTO_INCREMENT,
  login varchar(100) DEFAULT NULL,
  passwd varchar(100) DEFAULT NULL,
  status BIT(1) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;
INSERT INTO users (`login`, `passwd`) VALUES ('admin', '123');
CREATE TABLE tasks (
  id int NOT NULL AUTO_INCREMENT,
  username varchar(100) DEFAULT NULL,
  email varchar(100) DEFAULT NULL,
  description varchar(255) DEFAULT NULL,
  status BIT(1) DEFAULT 0,
  changed BIT(1) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;
