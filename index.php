<?php

require_once './Backend/commons/env.php';
require_once './Backend/commons/function.php';
require_once './Backend/controllers/admincontroller.php';
require_once './Backend/controllers/HomeController.php';
require_once './Backend/models/Product.php';
require_once './Backend/models/User.php';

// ===== Security headers (chống clickjacking, sniff MIME, rò rỉ referrer) =====
if (!headers_sent()) {
    header('X-Frame-Options: SAMEORIGIN');
    header('X-Content-Type-Options: nosniff');
    header('Referrer-Policy: strict-origin-when-cross-origin');
    header('X-XSS-Protection: 0');
    header('Permissions-Policy: geolocation=(), microphone=(), camera=()');
}

// ===== Cookie phiên an toàn (phải đặt TRƯỚC session_start) =====
if (session_status() === PHP_SESSION_NONE) {
    $__https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
            || (($_SERVER['SERVER_PORT'] ?? '') == 443);
    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => '/',
        'httponly' => true,          // JS không đọc được cookie phiên -> giảm rủi ro XSS đánh cắp session
        'secure'   => $__https,      // chỉ gửi qua HTTPS khi có HTTPS
        'samesite' => 'Lax',         // giảm rủi ro CSRF
    ]);
    ini_set('session.use_strict_mode', '1');
    session_start();
}

// ===== Chong CSRF toan cuc: moi POST phai kem token hop le (_csrf) =====
// Form nhung token bang ham csrf_field(). GET khong bi anh huong.
csrf_check();

$act = $_GET['act'] ?? '/';

switch ($act) {
    case '/':
    case 'giaodien':
        (new HomeController())->giaodien();
        break;

    // ===== CLIENT =====
    case 'detail':
        (new HomeController())->detailProduct();
        break;

    case 'addComment':
        (new HomeController())->addComment();
        break;

    case 'loginUser':
        (new HomeController())->loginUser();
        break;

    case 'registerUser':
        (new HomeController())->registerUser();
        break;

    case 'forgotPassword':
        (new HomeController())->forgotPassword();
        break;

    case 'resetPassword':
        (new HomeController())->resetPassword();
        break;

    case 'addToCart':
        (new HomeController())->addToCart();
        break;

    case 'cart':
        (new HomeController())->cart();
        break;

    case 'updateCart':
        (new HomeController())->updateCart();
        break;

    case 'deleteCart':
        (new HomeController())->deleteCart();
        break;

    case 'applyVoucher':
        (new HomeController())->applyVoucher();
        break;

    case 'removeVoucher':
        (new HomeController())->removeVoucher();
        break;

    case 'checkout':
        (new HomeController())->checkout();
        break;

    case 'placeOrder':
        (new HomeController())->placeOrder();
        break;

    case 'payGateway':
        (new HomeController())->payGateway();
        break;

    case 'vnpayCreate':
        (new HomeController())->vnpayCreate();
        break;

    case 'vnpayReturn':
        (new HomeController())->vnpayReturn();
        break;

    case 'payConfirm':
        (new HomeController())->payConfirm();
        break;

    case 'myOrders':
        (new HomeController())->myOrders();
        break;

    case 'profile':
        (new HomeController())->profile();
        break;

    case 'wishlist':
        (new HomeController())->wishlist();
        break;

    case 'toggleWishlist':
        (new HomeController())->toggleWishlist();
        break;

    case 'confirmOrder':
        (new HomeController())->confirmOrder();
        break;

    case 'editReceiverInfo':
        (new HomeController())->editReceiverInfo();
        break;

    case 'cancelOrder':
        (new HomeController())->cancelOrder();
        break;

    case 'reorder':
        (new HomeController())->reorder();
        break;

    // ===== ADMIN =====
    case 'admin':
        (new admincontroller())->dashboard();
        break;

    case 'login':
        (new admincontroller())->login();
        break;

    case 'register':
        (new admincontroller())->register();
        break;

    case 'logout':
        session_destroy();
        header('Location: index.php?act=giaodien');
        exit;

    case 'adminProduct':
        (new admincontroller())->adminProduct();
        break;

    case 'addProduct':
        (new admincontroller())->addProduct();
        break;

    case 'editProduct':
        (new admincontroller())->editProduct();
        break;

    case 'deleteProduct':
        (new admincontroller())->deleteProduct();
        break;

    case 'detailAdmin':
        (new admincontroller())->detail();
        break;

    case 'ProductUser':
        (new admincontroller())->ProductUser();
        break;

    case 'CateProduct':
        (new admincontroller())->CateProduct();
        break;

    case 'addCateProduct':
        (new admincontroller())->addCateProduct();
        break;

    case 'editCateProduct':
        (new admincontroller())->editCateProduct();
        break;

    case 'deleteCateProduct':
        (new admincontroller())->deleteCateProduct();
        break;

    case 'users':
        (new admincontroller())->users();
        break;

    case 'editUser':
        (new admincontroller())->editUser();
        break;

    case 'deleteUser':
        (new admincontroller())->deleteUser();
        break;
    case 'donhang':
        (new admincontroller())->donhang();
        break;

    case 'updateOrderStatus':
        (new admincontroller())->updateOrderStatus();
        break;

    case 'updatePaymentStatus':
        (new admincontroller())->updatePaymentStatus();
        break;

    case 'editOrder':
        (new admincontroller())->editOrder();
        break;

    case 'orderDetail':
        (new HomeController())->orderDetail();
        break;

    case 'detailOrder':
        (new admincontroller())->detailOrder();
        break;

    case 'thongke':
        (new admincontroller())->thongke();
        break;
    default:
        http_response_code(404);
        require_once __DIR__ . '/Frontend/views/client/giaodien/404page.php';
        break;
}