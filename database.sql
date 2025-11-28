CREATE DATABASE IF NOT EXISTS livraria CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE livraria;

CREATE TABLE IF NOT EXISTS autores (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(150) NOT NULL,
  nacionalidade VARCHAR(100),
  criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS livros (
  id INT AUTO_INCREMENT PRIMARY KEY,
  titulo VARCHAR(200) NOT NULL,
  ano INT,
  autor_id INT NOT NULL,
  criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (autor_id) REFERENCES autores(id) ON DELETE CASCADE
) ENGINE=InnoDB;

INSERT INTO autores (nome, nacionalidade) VALUES
('Gabriel García Márquez','Colômbia'),
('Clarice Lispector','Brasil');

INSERT INTO livros (titulo, ano, autor_id) VALUES
('Cem Anos de Solidão',1967,1),
('A Paixão Segundo G.H.',1964,2);
