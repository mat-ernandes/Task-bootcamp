<?php
require_once __DIR__ . '/env.php';
loadEnv(__DIR__ . '/../../.env');
class Database
{
    private string $host;
    private string $port;
    private string $dbname;
    private string $user;
    private string $password;

    public function __construct()
    {
        $this->host = getenv('DB_HOST') ?: 'localhost';
        $this->port = getenv('DB_PORT') ?: '5432';
        $this->dbname = getenv('DB_NAME') ?: '';
        $this->user = getenv('DB_USER') ?: '';
        $this->password = getenv('DB_PASS') ?: '';
    }


    public function connect(): PDO
    {
        try {
            $pdo = new PDO(
                "pgsql:host={$this->host};port={$this->port};dbname={$this->dbname}",
                $this->user,
                $this->password
            );

            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

            return $pdo;
        } catch (PDOException $e) {
            die('Erro na conexão com o banco de dados: ' . $e->getMessage());
        }
    }
}