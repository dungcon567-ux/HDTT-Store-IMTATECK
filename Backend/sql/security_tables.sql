-- Bảng chống brute-force đăng nhập (theo IP)
CREATE TABLE IF NOT EXISTS login_throttle (
    ip            VARCHAR(45) NOT NULL PRIMARY KEY,
    fail_count    INT NOT NULL DEFAULT 0,
    locked_until  INT NULL,               -- unix timestamp; NULL = không khóa
    updated_at    TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Bảng token đặt lại mật khẩu
CREATE TABLE IF NOT EXISTS password_resets (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    email       VARCHAR(255) NOT NULL,
    token_hash  CHAR(64) NOT NULL,         -- sha256 của token gửi cho user
    expires_at  INT NOT NULL,              -- unix timestamp hết hạn
    used        TINYINT(1) NOT NULL DEFAULT 0,
    created_at  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX (email), INDEX (token_hash)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
