CREATE TABLE instrutores (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(100) NOT NULL,
  especialidade VARCHAR(100),
  email VARCHAR(150),
  criado_em DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE membros (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(100) NOT NULL,
  data_nascimento DATE,
  email VARCHAR(150),
  telefone VARCHAR(20),
  instrutor_id INT,
  criado_em DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (instrutor_id) REFERENCES instrutores(id)
);

CREATE TABLE usuarios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(50) NOT NULL UNIQUE,
  senha VARCHAR(255) NOT NULL,
  criado_em DATETIME DEFAULT CURRENT_TIMESTAMP
);

ALTER TABLE instrutores
  ADD COLUMN data_nascimento DATE NULL AFTER nome;

-- Fiz isso para todos os Id's
UPDATE instrutores
  SET data_nascimento = '1980-01-01'
  WHERE id = 1;

ALTER TABLE instrutores
  MODIFY COLUMN data_nascimento DATE NOT NULL;

ALTER TABLE instrutores  
  ADD COLUMN foto MEDIUMBLOB NULL AFTER email;
  

ALTER TABLE membros  
  ADD COLUMN foto MEDIUMBLOB NULL AFTER instrutor_id;

-- MEMBROS
ALTER TABLE `membros`
  ADD COLUMN `status` ENUM('Ativo','Inativo') NOT NULL DEFAULT 'Ativo' AFTER `foto`,
  ADD COLUMN `endereco` VARCHAR(255)    NOT NULL DEFAULT ''   AFTER `status`,
  ADD COLUMN `emergencia_nome` VARCHAR(100) NOT NULL DEFAULT '' AFTER `endereco`,
  ADD COLUMN `emergencia_telefone` VARCHAR(20) NOT NULL DEFAULT '' AFTER `emergencia_nome`,
  ADD COLUMN `plano` ENUM('Mensal','Trimestral','Anual') NOT NULL DEFAULT 'Mensal' AFTER `emergencia_telefone`,
  ADD COLUMN `observacoes` TEXT         NULL                     AFTER `plano`,
  ADD COLUMN `data_inicio` DATE         NOT NULL DEFAULT CURRENT_DATE AFTER `criado_em`;

-- INSTRUTORES
ALTER TABLE `instrutores`
  ADD COLUMN `telefone` VARCHAR(20) NOT NULL DEFAULT '' AFTER `email`,
  ADD COLUMN `biografia` TEXT       NULL                 AFTER `telefone`,
  ADD COLUMN `disponibilidade` VARCHAR(50) NOT NULL DEFAULT '' AFTER `biografia`;



ALTER TABLE membros
  MODIFY instrutor_id INT NULL;
  
ALTER TABLE `membros`
  MODIFY `email` VARCHAR(150) NOT NULL;


ALTER TABLE `membros`
  DROP FOREIGN KEY `membros_ibfk_1`;


ALTER TABLE `membros`
  ADD CONSTRAINT `membros_ibfk_1`
    FOREIGN KEY (`instrutor_id`)
    REFERENCES `instrutores` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE;


ALTER TABLE `instrutores`
  ADD CONSTRAINT `uq_instrutores_email` UNIQUE (`email`);


ALTER TABLE `membros`
  ADD CONSTRAINT `uq_membros_email` UNIQUE (`email`);


-- Exemplos de dados para a tabela instrutores
INSERT INTO instrutores (nome, data_nascimento, especialidade, email, telefone, biografia, disponibilidade) VALUES
  ('Ana Silva', '1980-01-01',     'Pilates',      'ana.silva@academia.com',      '(41)99999-0001', 'Instrutora certificada em Pilates há 8 anos.',              '08:00-10:00'),
  ('Bruno Costa', '1980-01-01',   'Musculação',   'bruno.costa@academia.com',    '(41)99999-0002', 'Especialista em musculação e treinamento funcional.',        '14:00-16:00'),
  ('Carla Rezende', '1980-01-01', 'Yoga',         'carla.rezende@academia.com',  '(41)99999-0003', 'Professora de Yoga com foco em Hatha e Vinyasa.',            '06:00-08:00'),
  ('Diego Lima', '1980-01-01',    'CrossFit',     'diego.lima@academia.com',     '(41)99999-0004', 'Coach de Crossfit, ex-atleta de competições regionais.',     '18:00-20:00'),
  ('Eva Torres', '1980-01-01',    'Zumba',     'eva.torres@academia.com',     '(41)99999-0005', 'Instrutora de Spinning e condicionamento cardio.',            '20:00-22:00');


-- Exemplos de dados para a tabela membros
INSERT INTO membros (nome, data_nascimento, email, telefone, instrutor_id, plano) VALUES
  ('João Souza',        '1990-05-10', 'joao.souza@exemplo.com',       '(41)98889-1001',  1, 'Mensal'),
  ('Mariana Rocha',     '1985-11-22', 'mariana.rocha@exemplo.com',    '(41)98888-1002',  2, 'Anual'),
  ('Pedro Alves',       '2000-01-15', 'pedro.alves@exemplo.com',      '(41)98887-1003',  3, 'Trimestral'),
  ('Fernanda Martins',  '1995-07-30', 'fernanda.martins@exemplo.com', '(41)98886-1004',  4, 'Mensal'),
  ('Ricardo Gomes',     '1988-09-12', 'ricardo.gomes@exemplo.com',    '(41)98885-1005',  5, 'Anual'),
  ('Juliana Pereira',   '1993-02-25', 'juliana.pereira@exemplo.com',  '(41)98884-1006',  1, 'Mensal'),
  ('Lucas Fernandes',   '1992-12-05', 'lucas.fernandes@exemplo.com', '(41)98883-1007',  2, 'Trimestral'),
  ('Camila Andrade',    '1987-04-18', 'camila.andrade@exemplo.com',   '(41)98882-1008',  3, 'Anual'),
  ('Thiago Santos',     '1999-06-21', 'thiago.santos@exemplo.com',    '(41)98881-1009',  4, 'Mensal'),
  ('Patrícia Barros',   '1994-08-09', 'patricia.barros@exemplo.com',  '(41)98880-1010',  5, 'Trimestral');


-- Usuário de teste 
INSERT INTO usuarios (nome, senha) VALUES
  ('admin', '$2y$10$Wy4CrytjQAGB1XFoiVgkjeJ1..zS9qNi0MK9Mp0pKtV/lcgmHFqcG');


