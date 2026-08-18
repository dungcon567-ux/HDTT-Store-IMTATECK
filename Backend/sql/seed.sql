-- =====================================================================
-- HDTT Store - Dữ liệu mẫu (chạy SAU schema.sql)
-- Ảnh tham chiếu tới các file có sẵn trong thư mục /uploads
-- Tài khoản: admin / admin123   —   khachhang / user123
-- =====================================================================

USE `php-oop-basic`;

-- ===================== DANH MỤC =====================
INSERT IGNORE INTO categories (id, name) VALUES
(1, 'Áo'),
(2, 'Quần'),
(3, 'Giày');

-- ===================== MÀU =====================
INSERT IGNORE INTO color (id, name) VALUES
(1, 'Trắng'),
(2, 'Đen'),
(3, 'Xanh');

-- ===================== SIZE =====================
INSERT IGNORE INTO size (id, name) VALUES
(1, 'S'),
(2, 'M'),
(3, 'L');

-- ===================== SẢN PHẨM =====================
INSERT IGNORE INTO products (id, name, category_id, description) VALUES
(1, 'Áo thun cổ tròn',        1, 'Áo thun cotton 100%, form regular, thoáng mát, dễ phối đồ.'),
(2, 'Quần jean ống đứng',     2, 'Quần jean nam ống đứng, chất denim co giãn nhẹ, bền màu.'),
(3, 'Air Jordan 1',           3, 'Giày sneaker cổ cao phong cách streetwear, đế cao su bám tốt.'),
(4, 'Áo Thun Polo sang trọng',1, 'Áo polo cổ bẻ, vải cá sấu mềm mịn, lịch sự mà vẫn trẻ trung.'),
(5, 'Quần jean ống loe',      2, 'Quần jean ống loe retro, tôn dáng, phù hợp đi chơi.'),
(6, 'Converse 1970s',         3, 'Giày vải cổ điển, đế cao su lưu hoá, dễ phối mọi outfit.');

-- ===================== NGƯỜI DÙNG =====================
-- Mật khẩu đã băm bằng password_hash() (bcrypt)
INSERT IGNORE INTO users (id, username, password, email, std, diachi, role) VALUES
(1, 'admin',     '$2y$12$JrMOT1.WooktYKRwaFZjfeqzstLspBg2TVKBs8qW3MFY9Hm2JhVBK', 'admin@hdtt.local',  '0900000001', 'Hà Nội',  'admin'),
(2, 'khachhang', '$2y$12$bnVCE4exDdhYElhMkyKat.qp4PvB5Rl.FH80aJlLnTWS6OMjs21X.', 'khach@hdtt.local',  '0900000002', 'Hồ Chí Minh', 'user');
