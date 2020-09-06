CREATE DATABASE Ger_Fin;
USE ger_fin;
CREATE TABLE TRANSACTION ( 

id INT NOT NULL AUTO_INCREMENT,
tipo VARCHAR (255),
descricao VARCHAR (255),
valor DECIMAL (12, 2),
PRIMARY KEY (ID)
);
SELECT * from transaction
INSERT INTO transaction (tipo,descricao,valor) values("teste1","teste2","90.50");