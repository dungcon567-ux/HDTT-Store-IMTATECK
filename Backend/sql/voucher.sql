CREATE TABLE IF NOT EXISTS vouchers (
    id           INT AUTO_INCREMENT PRIMARY KEY,
    code         VARCHAR(50) NOT NULL UNIQUE,
    type         ENUM('percent','fixed') NOT NULL DEFAULT 'percent',
    value        INT NOT NULL,                 -- percent (0-100) hoặc số tiền VND
    min_order    INT NOT NULL DEFAULT 0,       -- đơn tối thiểu để áp dụng
    max_discount INT NULL,                     -- trần giảm (cho loại percent)
    quantity     INT NOT NULL DEFAULT 0,       -- số lượt còn lại
    expires_at   INT NULL,                     -- unix timestamp hết hạn (NULL = vô hạn)
    active       TINYINT(1) NOT NULL DEFAULT 1,
    created_at   TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Thêm cột lưu giảm giá vào đơn hàng (an toàn, mặc định 0)
SET @c := (SELECT COUNT(*) FROM information_schema.columns WHERE table_schema='php-oop-basic' AND table_name='orders' AND column_name='discount');
SET @s := IF(@c=0, 'ALTER TABLE orders ADD COLUMN discount INT NOT NULL DEFAULT 0, ADD COLUMN voucher_code VARCHAR(50) NULL', 'SELECT 1');
PREPARE st FROM @s; EXECUTE st; DEALLOCATE PREPARE st;

-- Seed vài mã mẫu
INSERT IGNORE INTO vouchers (code, type, value, min_order, max_discount, quantity, expires_at) VALUES
 ('GIAM10',   'percent', 10, 200000, 50000, 100, NULL),
 ('SALE50K',  'fixed',   50000, 300000, NULL, 100, NULL),
 ('FREESHIP', 'fixed',   30000, 0,      NULL, 200, NULL);
