<?php

/**
 * MẪU cấu hình môi trường.
 * Copy file này thành `env.php` rồi điền giá trị thật.
 * `env.php` đã được .gitignore để không lộ secret lên git.
 */

date_default_timezone_set('Asia/Ho_Chi_Minh');

// ===== Base path động (chạy được dù đặt tên thư mục gì) =====
$__basePath = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/'));
if (substr($__basePath, -1) !== '/') {
    $__basePath .= '/';
}
define('BASE_PATH', $__basePath);

$__scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$__host   = $_SERVER['HTTP_HOST'] ?? 'localhost';
define('BASE_URL'       , $__scheme . '://' . $__host . BASE_PATH);
define('BASE_URL_ADMIN' , BASE_URL . '?act=admin');

// ===== Database =====
define('DB_HOST'    , 'localhost');
define('DB_PORT'    , 3306);
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');            // <-- điền mật khẩu DB thật
define('DB_NAME'    , 'php-oop-basic');

define('PATH_ROOT'  , __DIR__ . '/../../');

// ===== VNPAY (lấy từ tài khoản merchant của bạn) =====
define('VNPAY_TMN_CODE'    , 'YOUR_TMN_CODE');
define('VNPAY_HASH_SECRET' , 'YOUR_HASH_SECRET');   // <-- KHÔNG commit giá trị thật
define('VNPAY_URL'         , 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html');
