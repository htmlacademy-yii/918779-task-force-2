DROP DATABASE IF EXISTS taskforce;

CREATE DATABASE taskforce
	DEFAULT CHARACTER SET utf8;

USE taskforce;

CREATE TABLE city (
  id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
  title VARCHAR(255) NOT NULL
);

CREATE TABLE user (
  id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
  registration DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,
  name VARCHAR(128) NOT NULL,
  birthday DATE,
  avatar VARCHAR(255),
  phone VARCHAR(11) UNIQUE,
  email VARCHAR(128) NOT NULL UNIQUE,
  telegram VARCHAR(64) UNIQUE,
  rating INT,
  characteristic TEXT,
  city_id INT NOT NULL,
  specialty VARCHAR(255),
  role ENUM('customer', 'executor'),
  token TEXT,
  password CHAR(255) NOT NULL,
  FOREIGN KEY (city_id) REFERENCES city(id)
);

CREATE TABLE category (
  id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
  code VARCHAR(128) NOT NULL UNIQUE,
  title VARCHAR(128) NOT NULL
);

CREATE TABLE task (
  id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
  creation DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,
  title VARCHAR(255) NOT NULL,
  description TEXT NOT NULL,
  estimate INT,
  runtime DATE,
  city_id INT NOT NULL,
  lat DECIMAL(10, 8),
  lng DECIMAL(10, 8),
  user_id INT NOT NULL,
  category_id INT NOT NULL,
  status ENUM('new', 'canceled', 'working', 'done', 'failed'),
  FOREIGN KEY (user_id) REFERENCES user(id),
  FOREIGN KEY (category_id) REFERENCES category(id),
  FOREIGN KEY (city_id) REFERENCES city(id)
);

CREATE TABLE attachment (
  id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
  task_id INT NOT NULL,
  path VARCHAR(255) NOT NULL,
  FOREIGN KEY (task_id) REFERENCES task(id)
);

CREATE TABLE response (
  id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
  task_id INT NOT NULL,
  user_id INT NOT NULL,
  price INT NOT NULL,
  comment TEXT,
  FOREIGN KEY (task_id) REFERENCES task(id),
  FOREIGN KEY (user_id) REFERENCES user(id)
);

CREATE TABLE review (
  id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
  user_id INT NOT NULL,
  task_id INT NOT NULL,
  comment TEXT NOT NULL,
  stats INT NOT NULL,
  FOREIGN KEY (user_id) REFERENCES user(id),
  FOREIGN KEY (task_id) REFERENCES task(id)
);
