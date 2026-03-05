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
INSERT INTO usuarios (nome, email, senha, role) VALUES ('Ulisses Roque Tomaz', 'ulisses@gmail.com', '$2y$10$SsKavSelFefe.qkmG227cuczxxEbjHEFX.97VgWb5ccogg9f7YX6u', 'admin');
-- Senha: ulissestomaz --

select*from usuarios;

CREATE TABLE eventos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(150) NOT NULL,
    descricao TEXT NOT NULL,
    data_evento DATE NOT NULL,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

ALTER TABLE eventos ADD imagem VARCHAR(255) NULL;
select*from eventos;

CREATE TABLE escala_multimidia (
    id INT AUTO_INCREMENT PRIMARY KEY,
    data_servico DATE NOT NULL,
    funcao VARCHAR(50) NOT NULL,
    usuario_id INT NOT NULL,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

ALTER TABLE escala_multimidia 
ADD turno VARCHAR(20) NOT NULL AFTER data_servico;
select*from escala_multimidia;

