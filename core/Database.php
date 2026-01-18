<?php

namespace Core;

use PDO;
use PDOException;

class Database
{
    private static ?PDO $connection = null;
    private PDO $pdo;

    public function __construct()
    {
        if (self::$connection === null) {
            $this->connect();
        }
        $this->pdo = self::$connection;
    }

    private function connect(): void
    {
        $config = require __DIR__ . '/../config/database.php';

        try {
            if ($config['connection'] === 'sqlite') {
                $path = $config['sqlite']['path'];
                if (!str_starts_with($path, '/')) {
                    $path = __DIR__ . '/../' . $path;
                }
                $dsn = "sqlite:" . $path;
                self::$connection = new PDO($dsn);
                if (function_exists('auth_log')) {
                    $exists = is_file($path);
                    $size = $exists ? (string) filesize($path) : '0';
                    auth_log('DB sqlite path: ' . $path . ' | exists=' . (int) $exists . ' | size=' . $size);
                }

                // Enable WAL mode for better concurrency
                self::$connection->exec('PRAGMA journal_mode=WAL;');
                self::$connection->exec('PRAGMA synchronous=NORMAL;');
                self::$connection->exec('PRAGMA foreign_keys=ON;');
            } else {
                $dsn = "mysql:host={$config['mysql']['host']};port={$config['mysql']['port']};dbname={$config['mysql']['database']};charset=utf8mb4";
                self::$connection = new PDO(
                    $dsn,
                    $config['mysql']['username'],
                    $config['mysql']['password'],
                    [PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"]
                );
            }

            self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            self::$connection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }

    public function query(string $sql, array $params = []): \PDOStatement
    {
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            // Log error
            error_log("Database query error: " . $e->getMessage());
            throw $e;
        }
    }

    public function select(string $table, array $conditions = [], array $columns = ['*']): array
    {
        $sql = "SELECT " . implode(', ', $columns) . " FROM {$table}";

        if (!empty($conditions)) {
            $where = [];
            foreach (array_keys($conditions) as $key) {
                $where[] = "{$key} = ?";
            }
            $sql .= " WHERE " . implode(' AND ', $where);
        }

        $stmt = $this->query($sql, array_values($conditions));
        return $stmt->fetchAll();
    }

    public function selectOne(string $table, array $conditions = [], array $columns = ['*']): ?array
    {
        $results = $this->select($table, $conditions, $columns);
        return $results[0] ?? null;
    }

    public function insert(string $table, array $data): int|string
    {
        $columns = array_keys($data);
        $placeholders = array_fill(0, count($data), '?');

        $sql = "INSERT INTO {$table} (" . implode(', ', $columns) . ")
                VALUES (" . implode(', ', $placeholders) . ")";

        $this->query($sql, array_values($data));

        return $this->pdo->lastInsertId();
    }

    public function update(string $table, array $data, array $conditions): int
    {
        $set = [];
        foreach (array_keys($data) as $key) {
            $set[] = "{$key} = ?";
        }

        $where = [];
        foreach (array_keys($conditions) as $key) {
            $where[] = "{$key} = ?";
        }

        $sql = "UPDATE {$table} SET " . implode(', ', $set);

        if (!empty($where)) {
            $sql .= " WHERE " . implode(' AND ', $where);
        }

        $params = array_merge(array_values($data), array_values($conditions));
        $stmt = $this->query($sql, $params);

        return $stmt->rowCount();
    }

    public function delete(string $table, array $conditions): int
    {
        $where = [];
        foreach (array_keys($conditions) as $key) {
            $where[] = "{$key} = ?";
        }

        $sql = "DELETE FROM {$table}";

        if (!empty($where)) {
            $sql .= " WHERE " . implode(' AND ', $where);
        }

        $stmt = $this->query($sql, array_values($conditions));
        return $stmt->rowCount();
    }

    public function beginTransaction(): bool
    {
        return $this->pdo->beginTransaction();
    }

    public function commit(): bool
    {
        return $this->pdo->commit();
    }

    public function rollback(): bool
    {
        return $this->pdo->rollBack();
    }

    public function lastInsertId(): string
    {
        return $this->pdo->lastInsertId();
    }

    public function getPDO(): PDO
    {
        return $this->pdo;
    }
}
