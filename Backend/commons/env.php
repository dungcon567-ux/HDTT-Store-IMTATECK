<?php

// Múi giờ Việt Nam (cần thiết cho VNPAY và logic ngày tháng)
date_default_timezone_set('Asia/Ho_Chi_Minh');

// Biến môi trường, dùng chung toàn hệ thống
// Khai báo dưới dạng HẰNG SỐ để không phải dùng $GLOBALS

define('BASE_URL'       , 'http://localhost/duaan1/');
define('BASE_URL_ADMIN' , 'http://localhost/duaan1/?mode=admin/');

define('DB_HOST'    , 'localhost');
define('DB_PORT'    , 3306);
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME'    , 'php-oop-basic');

define('PATH_ROOT'    , __DIR__ . '/../../');

// ===== VNPAY Sandbox =====
define('VNPAY_TMN_CODE'    , 'JHH18UX3');
define('VNPAY_HASH_SECRET' , '3J3JSBUIN4Q5EBZ5C4Z0229X2BPNIIZ7');
define('VNPAY_URL'         , 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html');
// Return URL được build động trong HomeController dựa theo $_SERVER['HTTP_HOST']
// nên hoạt động trên cả localhost lẫn ngrok mà không cần đổi file này.