CREATE DATABASE ger_fin;
USE ger_fin;
CREATE TABLE usuarios ( 
    id BIGINT NOT NULL AUTO_INCREMENT,
    nome VARCHAR (255),
    senha VARCHAR (255),
    PRIMARY KEY (ID)
);
CREATE TABLE movimentos ( 
    id INT NOT NULL AUTO_INCREMENT,
    tipo VARCHAR (255),
    descricao VARCHAR (255),
    valor DECIMAL (12, 2),
    datahoramovimento DATETIME,
    usuario_id bigint,
    PRIMARY KEY (ID),
    FOREIGN KEY (usuario_id) references usuarios (id) 
);