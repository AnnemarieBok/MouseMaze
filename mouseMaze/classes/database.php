<?php
class Database {
    private $pdo;
    private $databaseName;

    public function __construct($databaseName) {
        $this->databaseName = $databaseName;
        $host = 'localhost';
        $username = 'root';
        $password = '';
        $charset = 'utf8mb4';

        try {
            $dsn = "mysql:host=$host;dbname={$this->databaseName};charset=$charset";
            $this->pdo = new PDO($dsn, $username, $password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }

    // General query method
    public function query($sql, $params = []) {
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            echo "Query failed: " . $e->getMessage();
            return false;
        }
    }

    // INSERT
    public function insert($table, $data) {
        $columns = implode(", ", array_keys($data));
        $placeholders = ":" . implode(", :", array_keys($data));
        $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($data);
    }

    // SELECT (fetch all)
    public function select($table, $where = "", $params = []) {
        $sql = "SELECT * FROM $table";
        if ($where) {
            $sql .= " WHERE $where";
        }
        $stmt = $this->query($sql, $params);
        return $stmt ? $stmt->fetchAll(PDO::FETCH_ASSOC) : [];
    }

    // SELECT one row
    public function selectOne($table, $where, $params = []) {
        $sql = "SELECT * FROM $table WHERE $where LIMIT 1";
        $stmt = $this->query($sql, $params);
        return $stmt ? $stmt->fetch(PDO::FETCH_ASSOC) : null;
    }

    // UPDATE
    public function update($table, $data, $where, $params = []) {
        $set = "";
        foreach ($data as $column => $value) {
            $set .= "$column = :$column, ";
        }
        $set = rtrim($set, ", ");
        $sql = "UPDATE $table SET $set WHERE $where";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(array_merge($data, $params));
    }

    // DELETE
    public function delete($table, $where, $params = []) {
        $sql = "DELETE FROM $table WHERE $where";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }
}