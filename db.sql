CREATE DATABASE IF NOT EXISTS test;

USE test;

CREATE TABLE category (
id INT( 11 ) NOT NULL AUTO_INCREMENT ,
par_id INT( 11 )  NOT NULL DEFAULT '0' ,
name VARCHAR( 255 ) NOT NULL ,
PRIMARY KEY ( id ) ,
INDEX ( par_id )
); 


INSERT INTO category (id, par_id, name) VALUES 
(1, 0, 'ime1'),
(2, 1, 'ime2'),
(3, 0, 'ime3'),
(4, 3, 'ime4'),
(5, 2, 'ime5'),
(6, 5, 'ime6'),
(11, 5, 'OK'),
(12, 3, 'OK1'),
(14, 4, 'podkateg4_1'),
(15, 14, 'podkateg4_1_1'),
(17, 15, 'podkateg4_1_1_2'),
(18, 15, 'podkateg4_1_1_3');


CREATE TABLE product (
id INT( 11 ) NOT NULL AUTO_INCREMENT ,
cat_id INT( 11 ) NOT NULL ,
name VARCHAR( 255 ) NOT NULL ,
PRIMARY KEY ( id ) ,
INDEX ( cat_id )
);

INSERT INTO product (cat_id, name) VALUES 
(1, 'proiz1'),
(1, 'proiz2'),
(4, 'proiz3'),
(3, 'proiz4'),
(6, 'proiz5'),
(6, 'proiz6'),
(6, 'proiz7'),
(2, 'proiz8'),
(3, 'proiz9'),
(2, 'proiz10'),
(5, 'proiz11'),
(14, 'prod cat 14_1'),
(14, 'prod cat 14_2'),
(14, 'prod cat 14_3'),
(17, 'prod cat 17_3'),
(17, 'prod cat 17_4'),
(15, 'prod cat 15_3'),
(15, 'prod cat 15_1');

CREATE TABLE users (
id INT( 11 ) NOT NULL AUTO_INCREMENT ,
username VARCHAR( 255 ) NOT NULL ,
password VARCHAR( 255 ) NOT NULL ,
PRIMARY KEY ( id )
);

INSERT INTO users (id, username, password) VALUES 
(1, 'test', 'test');