# Query to create the database
CREATE DATABASE parkingtec;

# Query to create tables for users registration and user comments
CREATE TABLE users (
	firstName VARCHAR(100) NOT NULL,
	lastName VARCHAR(100) NOT NULL,
	email VARCHAR(100) NOT NULL,
	idNumber VARCHAR(100) NOT NULL,
	parkingSpace Varchar(100) NOT NULL,
	carModel VARCHAR(100) NOT NULL,
	carNumber VARCHAR(100) NOT NULL,
	pass VARCHAR(100) NOT NULL,
);

#Query to create a table for the users comments
CREATE TABLE parkingslots (
	ID INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
	userOwner VARCHAR(100) NOT NULL,
	parkingSlot VARCHAR(100) NOT NULL,
	status INT(11) NOT NULL,
);

#Query to create a table for the friend Requests
CREATE TABLE changelost (
	ID INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
	requestingUser VARCHAR(100) NOT NULL,
	Owner VARCHAR(100) NOT NULL,
	space VARCHAR(100) NOT NULL
);

# Query to create a test user
INSERT INTO users (firstName, lastName, idNummber, parkingSpace, carModel, carNumber, pass)
VALUES ('Gerardo', 'Gutiérrez', 'A00815174', 'E3', 'Tsuru', 'JE9091', 'hola');



