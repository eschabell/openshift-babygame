# Need to login to your 'babygame' database and then 
# run this script with:
#
# \. baby.sql
#
USE babygame;

DROP TABLE IF EXISTS guesses;
CREATE TABLE guesses
(
	timestamp timestamp,
	name varchar(50) NOT NULL,
	email varchar(50) NOT NULL,
	birthdate timestamp,
	birthsex varchar(4) NOT NULL,
	babyname varchar(100),
	PRIMARY KEY (timestamp, name)
);
