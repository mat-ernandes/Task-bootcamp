<?php

require_once __DIR__ . '/../config/Database.php';

class Usuario
{
    private PDO $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->connect();
    }

    public function listar(): array
    {
        $sql = "SELECT * FROM usuarios ORDER BY nome";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarPorEmail(string $email): array|false
    {
        $sql = "SELECT * FROM usuarios WHERE LOWER(email) = LOWER(:email) LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':email' => trim($email)
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function cadastrar(string $nome, string $email): bool
    {
        $sql = "INSERT INTO usuarios (nome, email, senha) VALUES (:nome, :email, :senha)";
        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ':nome' => trim($nome),
            ':email' => strtolower(trim($email)),
            ':senha' => 'sem_login'
        ]);
    }

    public function buscarUltimo(): array|false
    {
        $sql = "SELECT * FROM usuarios ORDER BY id DESC LIMIT 1";
        $stmt = $this->conn->query($sql);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}