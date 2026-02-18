<?php

class DatabaseSessionHandler implements SessionHandlerInterface
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function open($savePath, $sessionName): bool
    {
        return true;
    }

    public function close(): bool
    {
        return true;
    }

    public function read($id): string|false
    {
        try {
            $stmt = $this->pdo->prepare("SELECT data FROM sessions WHERE id = :id AND last_accessed > NOW() - INTERVAL '1 DAY'");
            $stmt->execute([':id' => $id]);
            if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                return $row['data'];
            }
        } catch (PDOException $e) {
            // Table might not exist yet
            return '';
        }
        return '';
    }

    public function write($id, $data): bool
    {
        // Only save session if user is logged in (contains 'user|')
        if (strpos($data, 'user|') === false) {
            // If session exists but user logged out (or never logged in), remove it
            $this->destroy($id);
            return true;
        }

        try {
            // PostgreSQL specific upsert
            $stmt = $this->pdo->prepare("
                INSERT INTO sessions (id, data, last_accessed) 
                VALUES (:id, :data, NOW()) 
                ON CONFLICT (id) DO UPDATE SET data = :data, last_accessed = NOW()
            ");
            return $stmt->execute([':id' => $id, ':data' => $data]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function destroy($id): bool
    {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM sessions WHERE id = :id");
            return $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function gc($max_lifetime): int|false
    {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM sessions WHERE last_accessed < NOW() - INTERVAL '1 DAY'");
            $stmt->execute();
            return $stmt->rowCount();
        } catch (PDOException $e) {
            return false;
        }
    }
}
