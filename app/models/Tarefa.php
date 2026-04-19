<?php

require_once __DIR__ . '/../config/Database.php';

class Tarefa
{
    private PDO $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->connect();
    }

    public function cadastrar(array $dados): bool
    {
        $sql = "INSERT INTO tarefas (titulo, descricao, prioridade, prazo, status, usuario_id)
                VALUES (:titulo, :descricao, :prioridade, :prazo, :status, :usuario_id)";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ':titulo' => $dados['titulo'],
            ':descricao' => $dados['descricao'],
            ':prioridade' => $dados['prioridade'],
            ':prazo' => $dados['prazo'],
            ':status' => $dados['status'],
            ':usuario_id' => $dados['usuario_id']
        ]);
    }

    public function listarComUsuarios(): array
    {
        $sql = "SELECT 
                    t.id,
                    t.titulo,
                    t.descricao,
                    t.prioridade,
                    t.prazo,
                    t.status,
                    t.usuario_id,
                    u.nome AS responsavel
                FROM tarefas t
                INNER JOIN usuarios u ON u.id = t.usuario_id
                ORDER BY t.id DESC";

        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarPorId(int $id): array|false
    {
        $sql = "SELECT * FROM tarefas WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function buscarUltimaComUsuario(): array|false
    {
        $sql = "SELECT 
                    t.id,
                    t.titulo,
                    t.descricao,
                    t.prioridade,
                    t.prazo,
                    t.status,
                    t.usuario_id,
                    u.nome AS responsavel
                FROM tarefas t
                INNER JOIN usuarios u ON u.id = t.usuario_id
                ORDER BY t.id DESC
                LIMIT 1";

        $stmt = $this->conn->query($sql);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function atualizar(int $id, array $dados): bool
    {
        $sql = "UPDATE tarefas
                SET titulo = :titulo,
                    descricao = :descricao,
                    prioridade = :prioridade,
                    prazo = :prazo,
                    status = :status,
                    usuario_id = :usuario_id
                WHERE id = :id";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ':titulo' => $dados['titulo'],
            ':descricao' => $dados['descricao'],
            ':prioridade' => $dados['prioridade'],
            ':prazo' => $dados['prazo'],
            ':status' => $dados['status'],
            ':usuario_id' => $dados['usuario_id'],
            ':id' => $id
        ]);
    }

    public function excluir(int $id): bool
    {
        $sql = "DELETE FROM tarefas WHERE id = :id";
        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ':id' => $id
        ]);
    }
}