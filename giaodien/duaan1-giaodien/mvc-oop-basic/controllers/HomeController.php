<?php

require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../models/User.php';

class HomeController
{
    public function __construct()
    {
    }

    private function requireLogin()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?act=loginUser");
            exit;
        }
    }

    public function home()
    {
        $this->giaodien();
    }

    public function giaodien()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $productModel = new Product();
        $keyword    = trim($_GET['keyword'] ?? '');
        $categoryId = (int)($_GET['category'] ?? 0);
        $variants = $productModel->getAllVariants($keyword, $categoryId);
        $currentCategory = $categoryId;

        // Gom variants theo product
        $grouped = [];
        foreach ($variants as $v) {
            $pid = (int)$v['product_id'];
            if (!isset($grouped[$pid])) {
                $grouped[$pid] = [
                    'product_id'   => $pid,
                    'product_name' => $v['product_name'],
                    'description'  => $v['description'],
                    'image'        => $v['image'],
                    'category_id'  => $v['category_id'] ?? 0,
                    'min_price'    => (int)$v['price'],
                    'max_price'    => (int)$v['price'],
                    'total_stock'  => 0,
                    'colors'       => [],
                    'sizes'        => [],
                    'variant_count'=> 0,
                ];
            }
            $g = &$grouped[$pid];
            $g['min_price']    = min($g['min_price'], (int)$v['price']);
            $g['max_price']    = max($g['max_price'], (int)$v['price']);
            $g['total_stock'] += (int)$v['stock'];
            $g['colors'][$v['color_name']] = true;
            $g['sizes'][$v['size_name']]   = true;
            $g['variant_count']++;
        }
        $allProducts = array_values($grouped);

        $perPage = 8;
        $totalItems = count($allProducts);
        $totalPages = max(1, (int)ceil($totalItems / $perPage));
        $page = max(1, min($totalPages, (int)($_GET['page'] ?? 1)));
        $offset = ($page - 1) * $perPage;
        $products = array_slice($allProducts, $offset, $perPage);

        $topSellers = $productModel->getTopSellingProducts(8);

        require_once __DIR__ . '/../views/client/giaodien/index.php';
    }

    public function detailProduct()
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $id = (int)($_GET['id'] ?? 0);

    if ($id <= 0) {
        echo "ID sản phẩm không hợp lệ!";
        return;
    }

    $productModel = new Product();
    $variants = $productModel->getProductDetailWithVariants($id);

    if (empty($variants)) {
        echo "Không tìm thấy sản phẩm!";
        return;
    }

    $firstVariant = $variants[0];

    $colors = [];
    $sizes = [];

    foreach ($variants as $item) {
        if (!empty($item['color_id']) && !empty($item['color_name'])) {
            $colors[$item['color_id']] = $item['color_name'];
        }

        if (!empty($item['size_id']) && !empty($item['size_name'])) {
            $sizes[$item['size_id']] = $item['size_name'];
        }
    }

    // Sản phẩm cùng loại
    $product = $productModel->getProductById($id);
    $relatedProducts = $product
        ? $productModel->getRelatedProducts($id, (int)$product['category_id'], 4)
        : [];

    // Bình luận
    $comments = $productModel->getCommentsByProduct($id);

    require_once __DIR__ . '/../views/client/giaodien/detailProduct.php';
    }

    public function addComment()
    {
        $this->requireLogin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?act=giaodien");
            exit;
        }

        $productId = (int)($_POST['product_id'] ?? 0);
        $rating    = (int)($_POST['rating'] ?? 5);
        $content   = trim($_POST['content'] ?? '');

        if ($productId <= 0 || $content === '') {
            header("Location: index.php?act=detail&id={$productId}#comments");
            exit;
        }

        if ($rating < 1) $rating = 1;
        if ($rating > 5) $rating = 5;

        $productModel = new Product();
        $productModel->addComment($_SESSION['user_id'], $productId, $rating, $content);

        header("Location: index.php?act=detail&id={$productId}#comments");
        exit;
    }

    public function loginUser()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $error = "";
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';

            if ($username === '') {
                $errors[] = "Username không được để trống.";
            }

            if ($password === '') {
                $errors[] = "Mật khẩu không được để trống.";
            }

            if (empty($errors)) {
                $userModel = new User();
                $user = $userModel->login($username, $password);

                if ($user) {
                    session_regenerate_id(true);

                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user'] = $user['username'];
                    $_SESSION['role'] = $user['role'];

                    if ($user['role'] === 'admin') {
                        header("Location: index.php?act=adminProduct");
                        exit;
                    }

                    header("Location: index.php?act=giaodien");
                    exit;
                } else {
                    $error = "Sai tài khoản hoặc mật khẩu!";
                }
            }
        }

        require_once __DIR__ . '/../views/client/giaodien/loginUser.php';
    }

    public function registerUser()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $message = "";
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $email    = trim($_POST['email'] ?? '');
            $std      = trim($_POST['std'] ?? '');
            $diachi   = trim($_POST['diachi'] ?? '');
            $password = $_POST['password'] ?? '';

            if ($username === '') {
                $errors[] = "Username không được để trống.";
            } elseif (mb_strlen($username) < 3) {
                $errors[] = "Username phải có ít nhất 3 ký tự.";
            }

            if ($email === '') {
                $errors[] = "Email không được để trống.";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Email không hợp lệ.";
            }

            if ($std === '') {
                $errors[] = "Số điện thoại không được để trống.";
            } elseif (!preg_match('/^[0-9]{9,11}$/', $std)) {
                $errors[] = "Số điện thoại phải từ 9 đến 11 chữ số.";
            }

            if ($diachi === '') {
                $errors[] = "Địa chỉ không được để trống.";
            }

            if ($password === '') {
                $errors[] = "Mật khẩu không được để trống.";
            } elseif (strlen($password) < 6) {
                $errors[] = "Mật khẩu phải có ít nhất 6 ký tự.";
            }

            if (empty($errors)) {
                $userModel = new User();
                $ok = $userModel->register($username, $email, $std, $diachi, $password);

                if ($ok) {
                    header("Location: index.php?act=loginUser");
                    exit;
                } else {
                    $message = "Đăng ký thất bại!";
                }
            }
        }

        require_once __DIR__ . '/../views/client/giaodien/registerUser.php';
    }

    public function addToCart()
    {
        $this->requireLogin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?act=giaodien");
            exit;
        }

        $productId = (int)($_POST['product_id'] ?? 0);
        $colorId   = (int)($_POST['color_id'] ?? 0);
        $sizeId    = (int)($_POST['size_id'] ?? 0);
        $quantity  = (int)($_POST['quantity'] ?? 1);

        if ($productId <= 0 || $colorId <= 0 || $sizeId <= 0 || $quantity <= 0) {
            echo "Dữ liệu thêm giỏ hàng không hợp lệ!";
            return;
        }

        $productModel = new Product();
        $variant = $productModel->getVariantByProductColorSize($productId, $colorId, $sizeId);

        if (!$variant) {
            echo "Không tìm thấy biến thể sản phẩm!";
            return;
        }

        if ($quantity > (int)$variant['stock']) {
            echo "Số lượng vượt quá tồn kho!";
            return;
        }

        $productModel->addToCart($_SESSION['user_id'], $variant['id'], $quantity);

        header("Location: index.php?act=cart");
        exit;
    }

    public function cart()
{
    $this->requireLogin();

    $productModel = new Product();
    $cartItems = $productModel->getCartByUser($_SESSION['user_id']);

    require_once __DIR__ . '/../views/client/giaodien/cart.php';
    }

    public function updateCart()
{
    $this->requireLogin();

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location: index.php?act=cart");
        exit;
    }

    $cartId = (int)($_POST['cart_id'] ?? 0);
    $quantity = (int)($_POST['quantity'] ?? 1);

    if ($cartId <= 0 || $quantity <= 0) {
        echo "<script>alert('Số lượng không hợp lệ'); window.location='index.php?act=cart';</script>";
        exit;
    }

    $productModel = new Product();
    $cartItem = $productModel->getCartItemById($cartId, $_SESSION['user_id']);

    if (!$cartItem) {
        echo "<script>alert('Không tìm thấy sản phẩm trong giỏ hàng'); window.location='index.php?act=cart';</script>";
        exit;
    }

    if ($quantity > (int)$cartItem['stock']) {
        echo "<script>alert('Số lượng vượt quá tồn kho của sản phẩm " . addslashes($cartItem['product_name']) . "'); window.location='index.php?act=cart';</script>";
        exit;
    }

    $productModel->updateCartQuantity($cartId, $_SESSION['user_id'], $quantity);

    header("Location: index.php?act=cart");
    exit;
}

    public function deleteCart()
    {
        $this->requireLogin();

        $cartId = (int)($_GET['id'] ?? 0);

        if ($cartId > 0) {
            $productModel = new Product();
            $productModel->deleteCartItem($cartId, $_SESSION['user_id']);
        }

        header("Location: index.php?act=cart");
        exit;
    }

    public function checkout()
    {
        $this->requireLogin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?act=cart");
            exit;
        }

        $selectedCartIds = $_POST['selected_cart'] ?? [];

        if (empty($selectedCartIds)) {
            echo "<script>alert('Bạn chưa chọn sản phẩm để thanh toán'); window.location='index.php?act=cart';</script>";
            exit;
        }

        $productModel = new Product();
        $userModel = new User();

        $checkoutItems = $productModel->getCartItemsByIds($_SESSION['user_id'], $selectedCartIds);

        if (empty($checkoutItems)) {
            echo "<script>alert('Không có sản phẩm hợp lệ để thanh toán'); window.location='index.php?act=cart';</script>";
            exit;
        }

        $user = $userModel->getUserById($_SESSION['user_id']);

        $subTotal = 0;
        foreach ($checkoutItems as $item) {
            $subTotal += $item['price'] * $item['quantity'];
        }

        $shippingFee = 30000;
        $grandTotal = $subTotal + $shippingFee;

        require_once __DIR__ . '/../views/client/giaodien/checkout.php';
    }

    public function placeOrder()
    {
        $this->requireLogin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: index.php?act=cart");
            exit;
        }

        $selectedCartIds = $_POST['selected_cart'] ?? [];
        $receiverName    = trim($_POST['receiver_name'] ?? '');
        $receiverPhone   = trim($_POST['receiver_phone'] ?? '');
        $receiverAddress = trim($_POST['receiver_address'] ?? '');
        $paymentMethod   = trim($_POST['payment_method'] ?? 'cod');

        if (empty($selectedCartIds)) {
            echo "<script>alert('Không có sản phẩm nào được chọn'); window.location='index.php?act=cart';</script>";
            exit;
        }

        $errors = [];

        if ($receiverName === '') {
            $errors[] = "Họ tên người nhận không được để trống.";
        }

        if ($receiverPhone === '') {
            $errors[] = "Số điện thoại người nhận không được để trống.";
        } elseif (!preg_match('/^[0-9]{9,11}$/', $receiverPhone)) {
            $errors[] = "Số điện thoại người nhận phải từ 9 đến 11 số.";
        }

        if ($receiverAddress === '') {
            $errors[] = "Địa chỉ nhận hàng không được để trống.";
        }

        $allowMethods = ['cod', 'momo', 'zalopay', 'vnpay'];
        if (!in_array($paymentMethod, $allowMethods, true)) {
            $errors[] = "Phương thức thanh toán không hợp lệ.";
        }

        $productModel = new Product();
        $userModel = new User();

        $checkoutItems = $productModel->getCartItemsByIds($_SESSION['user_id'], $selectedCartIds);
        $user = $userModel->getUserById($_SESSION['user_id']);

        if (empty($checkoutItems)) {
            echo "<script>alert('Sản phẩm thanh toán không hợp lệ'); window.location='index.php?act=cart';</script>";
            exit;
        }

        $subTotal = 0;
        foreach ($checkoutItems as $item) {
            if ($item['quantity'] > $item['stock']) {
                $errors[] = "Sản phẩm " . $item['product_name'] . " không đủ tồn kho.";
            }
            $subTotal += $item['price'] * $item['quantity'];
        }

        $shippingFee = 30000;
        $grandTotal = $subTotal + $shippingFee;

        if (!empty($errors)) {
            require_once __DIR__ . '/../views/client/giaodien/checkout.php';
            return;
        }

        $orderId = $productModel->createOrder([
            'user_id' => $_SESSION['user_id'],
            'total' => $grandTotal,
            'status' => 'cho_xac_nhan',
            'payment_status' => 'unpaid',
            'online' => $paymentMethod === 'cod' ? 'no' : 'yes',
            'receiver_name' => $receiverName,
            'receiver_phone' => $receiverPhone,
            'receiver_address' => $receiverAddress,
            'shipping_fee' => $shippingFee,
            'payment_method' => $paymentMethod,
        ]);

        if (!$orderId) {
            echo "Tạo đơn hàng thất bại!";
            return;
        }

        foreach ($checkoutItems as $item) {
            $productModel->addOrderDetail(
                $orderId,
                $item['variant_id'],
                $item['quantity'],
                $item['price']
            );

            $productModel->updateVariantStock(
                $item['variant_id'],
                $item['quantity']
            );
        }

        $productModel->removeManyCartItems($_SESSION['user_id'], $selectedCartIds);

        if ($paymentMethod !== 'cod') {
            header("Location: index.php?act=payGateway&order_id={$orderId}");
            exit;
        }

        echo "<script>alert('Đặt hàng thành công'); window.location='index.php?act=myOrders';</script>";
        exit;
    }

    public function payGateway()
    {
        $this->requireLogin();

        $orderId = (int)($_GET['order_id'] ?? 0);
        if ($orderId <= 0) {
            header("Location: index.php?act=myOrders");
            exit;
        }

        $productModel = new Product();
        $order = $productModel->getOrderByIdAndUser($orderId, $_SESSION['user_id']);

        if (!$order) {
            echo "Không tìm thấy đơn hàng!";
            return;
        }

        if ($order['payment_status'] === 'paid') {
            header("Location: index.php?act=orderDetail&id={$orderId}");
            exit;
        }

        if ($order['payment_method'] === 'cod') {
            header("Location: index.php?act=orderDetail&id={$orderId}");
            exit;
        }

        require_once __DIR__ . '/../views/client/giaodien/payGateway.php';
    }

    public function payConfirm()
    {
        $this->requireLogin();

        $orderId = (int)($_POST['order_id'] ?? $_GET['order_id'] ?? 0);
        $action  = $_POST['action'] ?? $_GET['action'] ?? '';

        if ($orderId <= 0) {
            header("Location: index.php?act=myOrders");
            exit;
        }

        $productModel = new Product();
        $order = $productModel->getOrderByIdAndUser($orderId, $_SESSION['user_id']);

        if (!$order || $order['payment_method'] === 'cod') {
            header("Location: index.php?act=myOrders");
            exit;
        }

        if ($action === 'success') {
            // Đánh dấu đã thanh toán đồng thời chuyển trạng thái đơn sang "đã đặt hàng"
            $productModel->markOrderPaid($orderId);
            $productModel->updateOrderStatusById($orderId, 'da_dat_hang');

            // Cập nhật lại biến $order để view hiển thị đúng
            $order['payment_status'] = 'paid';
            $order['status'] = 'da_dat_hang';

            require_once __DIR__ . '/../views/client/giaodien/payResult.php';
            return;
        }

        if ($action === 'cancel') {
            $resultStatus = 'cancel';
            require_once __DIR__ . '/../views/client/giaodien/payResult.php';
            return;
        }

        header("Location: index.php?act=payGateway&order_id={$orderId}");
        exit;
    }

    public function myOrders()
    {
    $this->requireLogin();

    $productModel = new Product();
    $orders = $productModel->getOrderHistoryByUser($_SESSION['user_id']);

    require_once __DIR__ . '/../views/client/giaodien/myOrders.php';
    }
    public function confirmOrder()
    {
        $this->requireLogin();

        $orderId = (int)($_GET['id'] ?? $_POST['order_id'] ?? 0);
        if ($orderId <= 0) {
            header("Location: index.php?act=myOrders");
            exit;
        }

        $productModel = new Product();
        $order = $productModel->getOrderByIdAndUser($orderId, $_SESSION['user_id']);

        if (!$order) {
            echo "Không tìm thấy đơn hàng!";
            return;
        }

        if ($order['status'] !== 'cho_xac_nhan') {
            header("Location: index.php?act=orderDetail&id={$orderId}");
            exit;
        }

        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $receiverName    = trim($_POST['receiver_name'] ?? '');
            $receiverPhone   = trim($_POST['receiver_phone'] ?? '');
            $receiverAddress = trim($_POST['receiver_address'] ?? '');

            if ($receiverName === '') {
                $errors[] = "Họ tên người nhận không được để trống.";
            }
            if ($receiverPhone === '') {
                $errors[] = "Số điện thoại không được để trống.";
            } elseif (!preg_match('/^[0-9]{9,11}$/', $receiverPhone)) {
                $errors[] = "Số điện thoại phải từ 9 đến 11 số.";
            }
            if ($receiverAddress === '') {
                $errors[] = "Địa chỉ không được để trống.";
            }

            $needsPayment = $order['payment_method'] !== 'cod' && $order['payment_status'] !== 'paid';
            if ($needsPayment) {
                $errors[] = "Đơn hàng thanh toán online cần được thanh toán trước khi hoàn tất. Vui lòng thanh toán bên dưới.";
            }

            if (empty($errors)) {
                $productModel->updateOrderInfo($orderId, [
                    'receiver_name'    => $receiverName,
                    'receiver_phone'   => $receiverPhone,
                    'receiver_address' => $receiverAddress,
                    'shipping_fee'     => (float)$order['shipping_fee'],
                    'payment_method'   => $order['payment_method'],
                    'payment_status'   => $order['payment_status'],
                    'status'           => 'da_dat_hang',
                ]);

                echo "<script>alert('Đã hoàn tất đặt hàng!'); window.location='index.php?act=myOrders';</script>";
                exit;
            }

            $order['receiver_name']    = $receiverName;
            $order['receiver_phone']   = $receiverPhone;
            $order['receiver_address'] = $receiverAddress;
        }

        $orderDetails = $productModel->getOrderDetails($orderId);

        require_once __DIR__ . '/../views/client/giaodien/confirmOrder.php';
    }

    public function wishlist()
    {
        $this->requireLogin();

        $productModel = new Product();
        $items = $productModel->getWishlistByUser($_SESSION['user_id']);

        require_once __DIR__ . '/../views/client/giaodien/wishlist.php';
    }

    public function toggleWishlist()
    {
        $this->requireLogin();

        $productId = (int)($_GET['id'] ?? $_POST['product_id'] ?? 0);
        $back      = $_GET['back'] ?? $_POST['back'] ?? 'giaodien';

        if ($productId <= 0) {
            header("Location: index.php?act=giaodien");
            exit;
        }

        $productModel = new Product();
        if ($productModel->isInWishlist($_SESSION['user_id'], $productId)) {
            $productModel->removeWishlist($_SESSION['user_id'], $productId);
        } else {
            $productModel->addWishlist($_SESSION['user_id'], $productId);
        }

        // Redirect về trang gốc + anchor sản phẩm
        $redirect = match ($back) {
            'wishlist' => 'index.php?act=wishlist',
            'detail'   => 'index.php?act=detail&id=' . $productId,
            default    => 'index.php?act=giaodien',
        };
        header("Location: {$redirect}");
        exit;
    }

    public function profile()
    {
        $this->requireLogin();

        $userModel = new User();
        $productModel = new Product();
        $userId = (int)$_SESSION['user_id'];

        $user = $userModel->findById($userId);
        if (!$user) {
            echo "Không tìm thấy tài khoản!";
            return;
        }

        $errors = [];
        $success = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? 'update_info';

            if ($action === 'update_info') {
                $username = trim($_POST['username'] ?? '');
                $email    = trim($_POST['email'] ?? '');
                $std      = trim($_POST['std'] ?? '');
                $diachi   = trim($_POST['diachi'] ?? '');

                if ($username === '') $errors[] = "Tên đăng nhập không được để trống.";
                elseif (mb_strlen($username) < 3) $errors[] = "Tên đăng nhập phải có ít nhất 3 ký tự.";

                if ($email === '') $errors[] = "Email không được để trống.";
                elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Email không hợp lệ.";

                if ($std === '') $errors[] = "Số điện thoại không được để trống.";
                elseif (!preg_match('/^[0-9]{9,11}$/', $std)) $errors[] = "Số điện thoại phải từ 9 đến 11 số.";

                if ($diachi === '') $errors[] = "Địa chỉ không được để trống.";

                $avatarName = $user['avatar'];

                if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === 0) {
                    $allowedExt = ['jpg', 'jpeg', 'png', 'webp'];
                    $ext = strtolower(pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION));

                    if (!in_array($ext, $allowedExt)) {
                        $errors[] = "Ảnh đại diện chỉ chấp nhận jpg/jpeg/png/webp.";
                    } elseif ($_FILES['avatar']['size'] > 2 * 1024 * 1024) {
                        $errors[] = "Ảnh đại diện không được lớn hơn 2MB.";
                    } elseif (@getimagesize($_FILES['avatar']['tmp_name']) === false) {
                        $errors[] = "File không phải ảnh hợp lệ.";
                    } else {
                        $safe = preg_replace('/[^a-zA-Z0-9_-]/', '', pathinfo($_FILES['avatar']['name'], PATHINFO_FILENAME));
                        $avatarName = 'avatar_' . $userId . '_' . time() . '_' . ($safe ?: 'img') . '.' . $ext;
                        $uploadDir = __DIR__ . '/../uploads/';
                        if (!move_uploaded_file($_FILES['avatar']['tmp_name'], $uploadDir . $avatarName)) {
                            $errors[] = "Tải ảnh thất bại.";
                            $avatarName = $user['avatar'];
                        }
                    }
                }

                if (empty($errors)) {
                    $userModel->updateProfile($userId, [
                        'username' => $username,
                        'email'    => $email,
                        'std'      => $std,
                        'diachi'   => $diachi,
                        'avatar'   => $avatarName,
                    ]);
                    $_SESSION['user'] = $username;
                    $_SESSION['user_avatar'] = $avatarName;
                    $success = "Cập nhật thông tin thành công!";
                    $user = $userModel->findById($userId);
                }
            } elseif ($action === 'change_password') {
                $oldPass = $_POST['old_password'] ?? '';
                $newPass = $_POST['new_password'] ?? '';
                $confirm = $_POST['confirm_password'] ?? '';

                if (!password_verify($oldPass, $user['password'])) {
                    $errors[] = "Mật khẩu hiện tại không đúng.";
                }
                if (strlen($newPass) < 6) {
                    $errors[] = "Mật khẩu mới phải có ít nhất 6 ký tự.";
                }
                if ($newPass !== $confirm) {
                    $errors[] = "Xác nhận mật khẩu không khớp.";
                }

                if (empty($errors)) {
                    $userModel->updatePassword($userId, password_hash($newPass, PASSWORD_DEFAULT));
                    $success = "Đổi mật khẩu thành công!";
                }
            }
        }

        // Thống kê đơn hàng
        $orders = $productModel->getOrderHistoryByUser($userId);
        $totalOrders = count($orders);
        $completedOrders = 0;
        $totalSpent = 0;
        foreach ($orders as $o) {
            if ($o['status'] === 'hoan_thanh') $completedOrders++;
            if ($o['payment_status'] === 'paid') $totalSpent += (int)$o['total'];
        }

        require_once __DIR__ . '/../views/client/giaodien/profile.php';
    }

    public function editReceiverInfo()
    {
        $this->requireLogin();

        $orderId = (int)($_GET['id'] ?? $_POST['order_id'] ?? 0);
        if ($orderId <= 0) {
            header("Location: index.php?act=myOrders");
            exit;
        }

        $productModel = new Product();
        $order = $productModel->getOrderByIdAndUser($orderId, $_SESSION['user_id']);

        if (!$order) {
            echo "Không tìm thấy đơn hàng!";
            return;
        }

        $editableStatuses = ['cho_xac_nhan', 'da_dat_hang', 'dang_lay_hang'];
        if (!in_array($order['status'], $editableStatuses, true)) {
            echo "<script>alert('Đơn ở trạng thái này không thể sửa thông tin nhận hàng.'); window.location='index.php?act=myOrders';</script>";
            exit;
        }

        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $receiverName    = trim($_POST['receiver_name'] ?? '');
            $receiverPhone   = trim($_POST['receiver_phone'] ?? '');
            $receiverAddress = trim($_POST['receiver_address'] ?? '');

            if ($receiverName === '') {
                $errors[] = "Họ tên người nhận không được để trống.";
            }
            if ($receiverPhone === '') {
                $errors[] = "Số điện thoại không được để trống.";
            } elseif (!preg_match('/^[0-9]{9,11}$/', $receiverPhone)) {
                $errors[] = "Số điện thoại phải từ 9 đến 11 số.";
            }
            // Cho phép giữ nguyên địa chỉ cũ nếu user không chọn lại
            if ($receiverAddress === '') {
                $receiverAddress = $order['receiver_address'];
            }
            if ($receiverAddress === '') {
                $errors[] = "Địa chỉ không được để trống.";
            }

            if (empty($errors)) {
                $productModel->updateOrderInfo($orderId, [
                    'receiver_name'    => $receiverName,
                    'receiver_phone'   => $receiverPhone,
                    'receiver_address' => $receiverAddress,
                    'shipping_fee'     => (float)$order['shipping_fee'],
                    'payment_method'   => $order['payment_method'],
                    'payment_status'   => $order['payment_status'],
                    'status'           => $order['status'],
                ]);

                echo "<script>alert('Đã cập nhật thông tin nhận hàng!'); window.location='index.php?act=myOrders';</script>";
                exit;
            }

            $order['receiver_name']    = $receiverName;
            $order['receiver_phone']   = $receiverPhone;
            $order['receiver_address'] = $receiverAddress;
        }

        $orderDetails = $productModel->getOrderDetails($orderId);

        require_once __DIR__ . '/../views/client/giaodien/editReceiverInfo.php';
    }

    public function cancelOrder()
    {
        $this->requireLogin();

        $orderId = (int)($_GET['id'] ?? $_POST['order_id'] ?? 0);
        if ($orderId <= 0) {
            header("Location: index.php?act=myOrders");
            exit;
        }

        $productModel = new Product();
        $order = $productModel->getOrderByIdAndUser($orderId, $_SESSION['user_id']);

        if (!$order) {
            echo "<script>alert('Không tìm thấy đơn hàng!'); window.location='index.php?act=myOrders';</script>";
            exit;
        }

        $cancelable = ['cho_xac_nhan', 'da_dat_hang', 'dang_lay_hang'];

        if (!in_array($order['status'], $cancelable, true)) {
            $msg = match ($order['status']) {
                'dang_van_chuyen', 'da_van_chuyen' => 'Đơn hàng đang vận chuyển, không thể hủy nữa.',
                'hoan_thanh'                       => 'Đơn hàng đã hoàn thành, không thể hủy.',
                'da_huy'                           => 'Đơn hàng đã được hủy trước đó.',
                default                            => 'Trạng thái đơn không cho phép hủy.',
            };
            echo "<script>alert(" . json_encode($msg) . "); window.location='index.php?act=myOrders';</script>";
            exit;
        }

        // Hoàn lại tồn kho
        $details = $productModel->getOrderDetails($orderId);
        foreach ($details as $item) {
            $productModel->restoreVariantStock($item['variant_id'], (int)$item['quantity']);
        }

        // Đổi đơn sang đã hủy. Nếu đơn online đã thanh toán → payment_status = đang hoàn tiền
        $isOnlinePaid = $order['payment_method'] !== 'cod' && $order['payment_status'] === 'paid';
        $newPaymentStatus = $isOnlinePaid ? 'dang_hoan_tien' : $order['payment_status'];

        $productModel->updateOrderInfo($orderId, [
            'receiver_name'    => $order['receiver_name'],
            'receiver_phone'   => $order['receiver_phone'],
            'receiver_address' => $order['receiver_address'],
            'shipping_fee'     => (float)$order['shipping_fee'],
            'payment_method'   => $order['payment_method'],
            'payment_status'   => $newPaymentStatus,
            'status'           => 'da_huy',
        ]);

        $msg = $isOnlinePaid
            ? "Đơn #{$orderId} đã được hủy. Hệ thống đang xử lý hoàn tiền."
            : "Đã hủy đơn hàng #{$orderId} thành công.";

        echo "<script>alert(" . json_encode($msg) . "); window.location='index.php?act=myOrders';</script>";
        exit;
    }

    public function reorder()
    {
        $this->requireLogin();

        $orderId = (int)($_GET['id'] ?? 0);
        if ($orderId <= 0) {
            header("Location: index.php?act=myOrders");
            exit;
        }

        $productModel = new Product();
        $order = $productModel->getOrderByIdAndUser($orderId, $_SESSION['user_id']);

        if (!$order) {
            echo "<script>alert('Không tìm thấy đơn hàng!'); window.location='index.php?act=myOrders';</script>";
            exit;
        }

        $details = $productModel->getOrderDetails($orderId);
        if (empty($details)) {
            echo "<script>alert('Đơn hàng không có sản phẩm để mua lại.'); window.location='index.php?act=myOrders';</script>";
            exit;
        }

        $added = 0;
        $skipped = 0;
        foreach ($details as $item) {
            $variantId = (int)$item['variant_id'];
            $quantity  = (int)$item['quantity'];

            // Kiểm tra biến thể còn tồn tại + còn hàng
            $variant = $productModel->getVariantById($variantId);
            if (!$variant || (int)$variant['status'] !== 1 || (int)$variant['stock'] <= 0) {
                $skipped++;
                continue;
            }

            $finalQty = min($quantity, (int)$variant['stock']);
            $productModel->addToCart($_SESSION['user_id'], $variantId, $finalQty);
            $added++;
        }

        if ($added === 0) {
            echo "<script>alert('Tất cả sản phẩm trong đơn đã hết hàng hoặc không còn bán.'); window.location='index.php?act=myOrders';</script>";
            exit;
        }

        $note = $skipped > 0 ? " ({$skipped} sản phẩm đã hết hàng và bị bỏ qua)" : '';
        echo "<script>alert('Đã thêm {$added} sản phẩm vào giỏ hàng{$note}.'); window.location='index.php?act=cart';</script>";
        exit;
    }

    public function orderDetail()
{
    $this->requireLogin();

    $orderId = (int)($_GET['id'] ?? 0);

    if ($orderId <= 0) {
        echo "ID đơn hàng không hợp lệ!";
        return;
    }

    $productModel = new Product();

    $order = $productModel->getOrderByIdAndUser($orderId, $_SESSION['user_id']);

    if (!$order) {
        echo "Không tìm thấy đơn hàng của bạn!";
        return;
    }

    $orderDetails = $productModel->getOrderDetails($orderId);

    require_once __DIR__ . '/../views/client/giaodien/orderDetail.php';
}
}