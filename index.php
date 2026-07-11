<?php

require_once './Backend/commons/env.php';
require_once './Backend/commons/function.php';
require_once './Backend/controllers/admincontroller.php';
require_once './Backend/controllers/HomeController.php';
require_once './Backend/models/Product.php';
require_once './Backend/models/User.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

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
        echo '404 - Không tìm thấy trang';
        break;
}