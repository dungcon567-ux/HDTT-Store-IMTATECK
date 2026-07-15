<?php

// ===== CSRF: chống giả mạo yêu cầu (Cross-Site Request Forgery) =====
// Sinh/nhớ token trong session, nhúng vào form, và kiểm tra khi POST.
function csrf_token(): string
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (empty($_SESSION['_csrf'])) {
        $_SESSION['_csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['_csrf'];
}

// In ra sẵn thẻ <input hidden> để dán vào trong <form>
function csrf_field(): string
{
    return '<input type="hidden" name="_csrf" value="' . htmlspecialchars(csrf_token(), ENT_QUOTES) . '">';
}

// Gọi ở ĐẦU nhánh xử lý POST; sai/thiếu token -> chặn ngay.
function csrf_check(): void
{
    if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
        return;
    }
    $sent = $_POST['_csrf'] ?? '';
    if (!is_string($sent) || $sent === ''
        || empty($_SESSION['_csrf'])
        || !hash_equals($_SESSION['_csrf'], $sent)) {
        http_response_code(419);
        die('Yêu cầu không hợp lệ hoặc phiên đã hết hạn (CSRF). Vui lòng tải lại trang và thử lại.');
    }
}

// Tính số tiền giảm từ 1 voucher cho subtotal (0 nếu không đủ điều kiện).
function calc_voucher_discount(array $v, int $subtotal): int
{
    if ($subtotal < (int)$v['min_order']) {
        return 0;
    }
    if (($v['type'] ?? 'fixed') === 'percent') {
        $d = (int) floor($subtotal * (int)$v['value'] / 100);
        if (!empty($v['max_discount'])) {
            $d = min($d, (int)$v['max_discount']);
        }
    } else {
        $d = (int)$v['value'];
    }
    return max(0, min($d, $subtotal)); // không vượt quá subtotal
}

// Hỗ trợ show bất kỳ data nào
function debug($data)
{
    echo "<pre>";

    print_r($data);

    die;
}

// Kết nối CSDL qua PDO
function connectDB() {
    // Kết nối CSDL
    $host = DB_HOST;
    $port = DB_PORT;
    $dbname = DB_NAME;

    try {
        $conn = new PDO("mysql:host=$host;port=$port;dbname=$dbname", DB_USERNAME, DB_PASSWORD);

        // cài đặt chế độ báo lỗi là xử lý ngoại lệ
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // cài đặt chế độ trả dữ liệu
        $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    
        return $conn;
    } catch (PDOException $e) {
        debug("Connection failed: " . $e->getMessage());
    }
}