<?php

class User
{
    private PDO $conn;

    public function __construct()
    {
        $this->conn = $this->connect();
    }

    private function connect(): PDO
    {
        $host = defined('DB_HOST') ? DB_HOST : '127.0.0.1';
        $dbname = defined('DB_NAME') ? DB_NAME : 'php-oop-basic';
        $user = defined('DB_USERNAME') ? DB_USERNAME : (defined('DB_USER') ? DB_USER : 'root');
        $pass = defined('DB_PASSWORD') ? DB_PASSWORD : (defined('DB_PASS') ? DB_PASS : '');

        $dsn = "mysql:host={$host};dbname={$dbname};charset=utf8mb4";

        return new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
    }

    public function getAll(): array
    {
        $stmt = $this->conn->query("
            SELECT id, username, email, std, diachi, role
            FROM users
            ORDER BY id DESC
        ");

        return $stmt->fetchAll();
    }

    public function findById(int $id): array|false
    {
        $stmt = $this->conn->prepare("
            SELECT *
            FROM users
            WHERE id = ?
            LIMIT 1
        ");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function findByUsername(string $username): array|false
    {
        $stmt = $this->conn->prepare("
            SELECT *
            FROM users
            WHERE username = ?
            LIMIT 1
        ");
        $stmt->execute([$username]);
        return $stmt->fetch();
    }

    public function findByEmail(string $email): array|false
    {
        $stmt = $this->conn->prepare("
            SELECT *
            FROM users
            WHERE email = ?
            LIMIT 1
        ");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    public function create(array $data): bool
    {
        $stmt = $this->conn->prepare("
            INSERT INTO users (username, password, email, std, diachi, role)
            VALUES (:username, :password, :email, :std, :diachi, :role)
        ");

        return $stmt->execute([
            ':username' => $data['username'],
            ':password' => $data['password'],
            ':email'    => $data['email'],
            ':std'      => $data['std'],
            ':diachi'   => $data['diachi'],
            ':role'     => $data['role'] ?? 'user',
        ]);
    }

    public function register(string $username, string $email, string $std, string $diachi, string $password): bool
    {
        if ($this->findByUsername($username)) {
            return false;
        }

        if ($this->findByEmail($email)) {
            return false;
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        return $this->create([
            'username' => $username,
            'password' => $hashedPassword,
            'email'    => $email,
            'std'      => $std,
            'diachi'   => $diachi,
            'role'     => 'user'
        ]);
    }

    public function login(string $username, string $password): array|false
    {
        $user = $this->findByUsername($username);

        if (!$user) {
            return false;
        }

        if (!isset($user['password']) || $user['password'] === '') {
            return false;
        }

        if (password_verify($password, $user['password'])) {
            return $user;
        }

        return false;
    }

    public function update(int $id, array $data): bool
    {
        if (isset($data['role'])) {
            $stmt = $this->conn->prepare("
                UPDATE users
                SET username = ?, email = ?, std = ?, diachi = ?, role = ?
                WHERE id = ?
            ");

            return $stmt->execute([
                $data['username'],
                $data['email'],
                $data['std'],
                $data['diachi'],
                $data['role'],
                $id
            ]);
        }

        $stmt = $this->conn->prepare("
            UPDATE users
            SET username = ?, email = ?, std = ?, diachi = ?
            WHERE id = ?
        ");

        return $stmt->execute([
            $data['username'],
            $data['email'],
            $data['std'],
            $data['diachi'],
            $id
        ]);
    }

    public function updateProfile(int $id, array $data): bool
    {
        $stmt = $this->conn->prepare("
            UPDATE users
            SET username = ?, email = ?, std = ?, diachi = ?, avatar = ?
            WHERE id = ?
        ");
        return $stmt->execute([
            $data['username'],
            $data['email'],
            $data['std'],
            $data['diachi'],
            $data['avatar'],
            $id,
        ]);
    }

    public function updatePassword(int $id, string $newPasswordHash): bool
    {
        $stmt = $this->conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        return $stmt->execute([$newPasswordHash, $id]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->conn->prepare("
            DELETE FROM users
            WHERE id = ?
        ");

        return $stmt->execute([$id]);
    }

    // ===== HÀM USER CONTROLLER nhé các bạn =====

    public function getAllUsers(): array
    {
        return $this->getAll();
    }

    public function getUserById(int $id): array|false
    {
        return $this->findById($id);
    }

    public function updateUser(int $id, array $data): bool
    {
        return $this->update($id, $data);
    }

    public function deleteUser(int $id): bool
    {
        return $this->delete($id);
    }
}