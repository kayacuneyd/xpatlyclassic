<?php

namespace Models;

use Core\Database;

abstract class Model
{
    protected static string $table;
    protected static Database $db;

    public static function getDB(): Database
    {
        if (!isset(self::$db)) {
            self::$db = new Database();
        }
        return self::$db;
    }

    public static function all(): array
    {
        $db = self::getDB();
        return $db->select(static::$table);
    }

    public static function find(int $id): ?array
    {
        $db = self::getDB();
        return $db->selectOne(static::$table, ['id' => $id]);
    }

    public static function where(array $conditions): array
    {
        $db = self::getDB();
        return $db->select(static::$table, $conditions);
    }

    public static function first(array $conditions): ?array
    {
        $db = self::getDB();
        return $db->selectOne(static::$table, $conditions);
    }

    public static function create(array $data): int|string
    {
        // Add timestamps
        if (!isset($data['created_at'])) {
            $data['created_at'] = date('Y-m-d H:i:s');
        }
        if (!isset($data['updated_at'])) {
            $data['updated_at'] = date('Y-m-d H:i:s');
        }

        $db = self::getDB();
        return $db->insert(static::$table, $data);
    }

    public static function update(int $id, array $data): int
    {
        // Update timestamp
        $data['updated_at'] = date('Y-m-d H:i:s');

        $db = self::getDB();
        return $db->update(static::$table, $data, ['id' => $id]);
    }

    public static function delete(int $id): int
    {
        $db = self::getDB();
        return $db->delete(static::$table, ['id' => $id]);
    }

    public static function query(string $sql, array $params = []): \PDOStatement
    {
        $db = self::getDB();
        return $db->query($sql, $params);
    }
}
