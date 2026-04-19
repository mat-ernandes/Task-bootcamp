-- =========================================
-- TASK BOOTCAMP - SCHEMA DATABASE
-- =========================================

DROP TABLE IF EXISTS tarefas CASCADE;
DROP TABLE IF EXISTS usuarios CASCADE;

CREATE TABLE usuarios (
    id SERIAL PRIMARY KEY,
    nome VARCHAR(150) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    senha VARCHAR(255) NOT NULL DEFAULT 'sem_login',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE tarefas (
    id SERIAL PRIMARY KEY,
    titulo VARCHAR(150) NOT NULL,
    descricao TEXT,
    prioridade VARCHAR(20) NOT NULL CHECK (prioridade IN ('Alta', 'Média', 'Baixa')),
    prazo DATE NOT NULL,
    status VARCHAR(30) NOT NULL DEFAULT 'Aguardando' CHECK (status IN ('Aguardando', 'Em Andamento', 'Concluído')),
    usuario_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_tarefas_usuarios
        FOREIGN KEY (usuario_id)
        REFERENCES usuarios(id)
        ON DELETE CASCADE
);

INSERT INTO usuarios (nome, email, senha) VALUES
('João Silva', 'joao.silva@taskbootcamp.com', 'sem_login'),
('Maria Souza', 'maria.souza@taskbootcamp.com', 'sem_login'),
('Ana Costa', 'ana.costa@taskbootcamp.com', 'sem_login'),
('Mateus Ernandes', 'mateus.ernandes@taskbootcamp.com', 'sem_login');

INSERT INTO tarefas (titulo, descricao, prioridade, prazo, status, usuario_id) VALUES
('Implementar cadastro de usuário', 'Criar a funcionalidade com validação backend', 'Alta', '2026-04-10', 'Em Andamento', 4),
('Criar protótipo do dashboard', 'Montar a interface inicial frontend com cartões de resumo e filtros', 'Média', '2026-03-25', 'Concluído', 4),
('Revisar documentação do projeto', 'Organizar escopo e preparar apresentação', 'Baixa', '2026-04-18', 'Aguardando', 4);