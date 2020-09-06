CREATE DATABASE Ger_Fin;
USE ger_fin;
CREATE TABLE movimento ( 
id INT NOT NULL AUTO_INCREMENT,
tipo VARCHAR (255),
descricao VARCHAR (255),
valor DECIMAL (12, 2),
PRIMARY KEY (ID)
);
ALTER TABLE movimento ADD datahoramovimento DATETIME;