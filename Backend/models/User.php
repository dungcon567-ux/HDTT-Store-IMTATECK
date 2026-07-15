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

    public function login(string $email, string $password): array|false
    {
        $user = $this->findByEmail($email);

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

    // ===== CHỐNG BRUTE-FORCE ĐĂNG NHẬP (theo IP) =====

    /** Trả về số GIÂY còn bị khóa (0 = không khóa). */
    public function loginLockRemaining(string $ip): int
    {
        $stmt = $this->conn->prepare("SELECT locked_until FROM login_throttle WHERE ip = ?");
        $stmt->execute([$ip]);
        $row = $stmt->fetch();
        if (!$row || empty($row['locked_until'])) {
            return 0;
        }
        $remain = (int)$row['locked_until'] - time();
        return $remain > 0 ? $remain : 0;
    }

    /** Ghi nhận 1 lần đăng nhập sai; khóa tạm khi vượt ngưỡng. */
    public function recordLoginFail(string $ip, int $maxFails = 5, int $lockMinutes = 15): void
    {
        $stmt = $this->conn->prepare("SELECT fail_count FROM login_throttle WHERE ip = ?");
        $stmt->execute([$ip]);
        $row = $stmt->fetch();
        $count = $row ? (int)$row['fail_count'] + 1 : 1;
        $lockedUntil = $count >= $maxFails ? time() + $lockMinutes * 60 : null;

        $stmt = $this->conn->prepare("
            INSERT INTO login_throttle (ip, fail_count, locked_until)
            VALUES (:ip, :c, :lu)
            ON DUPLICATE KEY UPDATE fail_count = :c2, locked_until = :lu2
        ");
        $stmt->execute([
            ':ip' => $ip, ':c' => $count, ':lu' => $lockedUntil,
            ':c2' => $count, ':lu2' => $lockedUntil,
        ]);
    }

    /** Xóa bộ đếm khi đăng nhập thành công. */
    public function clearLoginThrottle(string $ip): void
    {
        $stmt = $this->conn->prepare("DELETE FROM login_throttle WHERE ip = ?");
        $stmt->execute([$ip]);
    }

    // ===== QUÊN / ĐẶT LẠI MẬT KHẨU =====

    public function createPasswordReset(string $email, string $tokenHash, int $expiresAt): void
    {
        // Vô hiệu hóa các token cũ chưa dùng của email này
        $this->conn->prepare("UPDATE password_resets SET used = 1 WHERE email = ? AND used = 0")
                   ->execute([$email]);
        $stmt = $this->conn->prepare("
            INSERT INTO password_resets (email, token_hash, expires_at)
            VALUES (?, ?, ?)
        ");
        $stmt->execute([$email, $tokenHash, $expiresAt]);
    }

    /** Tìm token hợp lệ (chưa dùng, chưa hết hạn). */
    public function findValidReset(string $tokenHash): array|false
    {
        $stmt = $this->conn->prepare("
            SELECT * FROM password_resets
            WHERE token_hash = ? AND used = 0 AND expires_at > ?
            LIMIT 1
        ");
        $stmt->execute([$tokenHash, time()]);
        return $stmt->fetch();
    }

    public function markResetUsed(int $id): void
    {
        $this->conn->prepare("UPDATE password_resets SET used = 1 WHERE id = ?")->execute([$id]);
    }

    public function updatePasswordByEmail(string $email, string $newHash): bool
    {
        $stmt = $this->conn->prepare("UPDATE users SET password = ? WHERE email = ?");
        return $stmt->execute([$newHash, $email]);
    }

    // ===== SỔ ĐỊA CHỈ =====

    public function getAddresses(int $userId): array
    {
        $stmt = $this->conn->prepare("SELECT * FROM addresses WHERE user_id = ? ORDER BY is_default DESC, id DESC");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public function addAddress(int $userId, array $d): bool
    {
        // Nếu là địa chỉ mặc định đầu tiên (chưa có địa chỉ nào) -> tự đặt mặc định
        $count = $this->conn->prepare("SELECT COUNT(*) FROM addresses WHERE user_id = ?");
        $count->execute([$userId]);
        $isDefault = ((int)$count->fetchColumn() === 0) ? 1 : (int)($d['is_default'] ?? 0);
        if ($isDefault) {
            $this->conn->prepare("UPDATE addresses SET is_default = 0 WHERE user_id = ?")->execute([$userId]);
        }
        $stmt = $this->conn->prepare("INSERT INTO addresses (user_id, receiver_name, receiver_phone, address, is_default) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([$userId, $d['receiver_name'], $d['receiver_phone'], $d['address'], $isDefault]);
    }

    public function deleteAddress(int $id, int $userId): bool
    {
        $stmt = $this->conn->prepare("DELETE FROM addresses WHERE id = ? AND user_id = ?");
        return $stmt->execute([$id, $userId]);
    }

    public function setDefaultAddress(int $id, int $userId): bool
    {
        $this->conn->prepare("UPDATE addresses SET is_default = 0 WHERE user_id = ?")->execute([$userId]);
        $stmt = $this->conn->prepare("UPDATE addresses SET is_default = 1 WHERE id = ? AND user_id = ?");
        return $stmt->execute([$id, $userId]);
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