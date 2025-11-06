CREATE DATABASE sistema_login;
USE sistema_login;

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    senha VARCHAR(255)
);
ALTER TABLE usuarios ADD COLUMN role ENUM('usuario','admin') NOT NULL DEFAULT 'usuario';
ALTER TABLE usuarios ADD foto VARCHAR(255) NULL;
INSERT INTO usuarios (nome, email, senha, role) VALUES ('Artur Samuel', 'arturrsamuel1@gmail.com', '$2y$10$cFzfDqttUlc8qF6zZVApHeCvKzTOlfnJRVJVwatrTjksanifAs0Jq', 'admin');
INSERT INTO usuarios (nome, email, senha, role) VALUES ('Snake Sabotage', 'snakesabotage@gmail.com', 'arturamo', 'admin');



select*from usuarios;

CREATE TABLE eventos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(150) NOT NULL,
    descricao TEXT NOT NULL,
    data_evento DATE NOT NULL,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

select*from eventos;

