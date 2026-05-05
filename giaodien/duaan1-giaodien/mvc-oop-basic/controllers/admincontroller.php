<?php

require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../models/User.php';

class admincontroller {

    private function requireAdmin() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user']) || ($_SESSION['role'] ?? '') !== 'admin') {
            header("Location: index.php?act=login");
            exit;
        }
    }

    public function dashboard() {
        $this->requireAdmin();
        $productModel = new Product();
        $keyword = trim($_GET['keyword'] ?? '');
        $products = $productModel->getAllProducts($keyword);

        require_once __DIR__ . '/../views/admin/main.php';
    }

    public function home() {
        $this->dashboard();
    }

    public function detail() {
        $this->requireAdmin();
        $id = $_GET['id'] ?? null;

        if (!$id || !is_numeric($id) || (int)$id <= 0) {
            echo "ID sản phẩm không hợp lệ!";
            return;
        }

        $productModel = new Product();
        $item = $productModel->getProductDetailById((int)$id);

        if ($item) {
            require_once __DIR__ . '/../views/admin/ViewProduct/DetailProduct.php';
        } else {
            echo "Không tìm thấy sản phẩm này trong cơ sở dữ liệu!";
        }
    }

    public function ProductUser() {
        $productModel = new Product();
        $keyword = trim($_GET['keyword'] ?? '');
        $variants = $productModel->getAllVariants($keyword);

        require_once __DIR__ . '/../views/admin/ProductUser.php';
    }

    public function login() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $error = "";
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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

                    $_SESSION['user'] = $user['username'];
                    $_SESSION['role'] = $user['role'];
                    $_SESSION['user_id'] = $user['id'];

                    if ($user['role'] === 'admin') {
                        header("Location: index.php?act=adminProduct");
                    } else {
                        header("Location: index.php?act=giaodien");
                    }
                    exit;
                } else {
                    $error = "Sai tài khoản hoặc mật khẩu!";
                }
            }
        }

        require_once __DIR__ . '/../views/admin/ViewProduct/login.php';
    }

    public function register() {
        $message = "";
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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

                $ok = $userModel->register(
                    $username,
                    $email,
                    $std,
                    $diachi,
                    $password
                );

                if ($ok) {
                    $message = "Đăng ký thành công!";
                } else {
                    $message = "Đăng ký thất bại!";
                }
            }
        }

        require_once __DIR__ . '/../views/admin/ViewProduct/register.php';
    }

    public function adminProduct() {
        $this->requireAdmin();

        $productModel = new Product();
        $keyword = trim($_GET['keyword'] ?? '');
        $products = $productModel->getAllProducts($keyword);

        require_once __DIR__ . '/../views/admin/ViewProduct/adminProduct.php';
    }

    public function addProduct() {
        $this->requireAdmin();

        $productModel = new Product();
        $categories = $productModel->getCategories();
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $category_id = (int)($_POST['category_id'] ?? 0);
            $description = trim($_POST['description'] ?? '');

            if ($name === '') {
                $errors[] = "Tên sản phẩm không được để trống.";
            }

            if ($category_id <= 0) {
                $errors[] = "Vui lòng chọn danh mục.";
            }

            if ($description === '') {
                $errors[] = "Mô tả không được để trống.";
            }

            if (empty($errors)) {
                $data = [
                    'name' => $name,
                    'category_id' => $category_id,
                    'description' => $description
                ];

                $ok = $productModel->addProduct($data);

                if ($ok) {
                    header("Location: index.php?act=adminProduct");
                    exit;
                } else {
                    $errors[] = "Thêm sản phẩm thất bại.";
                }
            }
        }

        require_once __DIR__ . '/../views/admin/ViewProduct/add_product.php';
    }

    public function editProduct() {
        $this->requireAdmin();

        $id = $_GET['id'] ?? null;

        if (!$id || !is_numeric($id) || (int)$id <= 0) {
            echo "ID sản phẩm không hợp lệ!";
            return;
        }

        $productModel = new Product();
        $product = $productModel->getProductById((int)$id);

        if (!$product) {
            echo "Không tìm thấy sản phẩm cần sửa!";
            return;
        }

        $categories = $productModel->getCategories();
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $category_id = (int)($_POST['category_id'] ?? 0);
            $description = trim($_POST['description'] ?? '');

            if ($name === '') {
                $errors[] = "Tên sản phẩm không được để trống.";
            }

            if ($category_id <= 0) {
                $errors[] = "Vui lòng chọn danh mục.";
            }

            if ($description === '') {
                $errors[] = "Mô tả không được để trống.";
            }

            if (empty($errors)) {
                $data = [
                    'name' => $name,
                    'category_id' => $category_id,
                    'description' => $description
                ];

                $ok = $productModel->updateProduct((int)$id, $data);

                if ($ok) {
                    header("Location: index.php?act=adminProduct");
                    exit;
                } else {
                    $errors[] = "Cập nhật sản phẩm thất bại.";
                }
            }

            $product['name'] = $name;
            $product['category_id'] = $category_id;
            $product['description'] = $description;
        }

        require_once __DIR__ . '/../views/admin/ViewProduct/editproduct.php';
    }

    public function deleteProduct() {
    $this->requireAdmin();

    $id = $_GET['id'] ?? null;

    if (!$id || !is_numeric($id) || (int)$id <= 0) {
        echo "ID sản phẩm không hợp lệ!";
        return;
    }

    $productModel = new Product();
    $product = $productModel->getProductById((int)$id);

    if (!$product) {
        echo "Không tìm thấy sản phẩm để xóa!";
        return;
    }

    if ($productModel->hasOrder((int)$id)) {
        echo "Không thể xóa vì sản phẩm đã có đơn hàng!";
        return;
    }

    $ok = $productModel->deleteProductSafe((int)$id);

    if ($ok) {
        header("Location: index.php?act=adminProduct");
        exit;
    }

    echo "Xóa sản phẩm thất bại!";
}

    public function users() {
        $this->requireAdmin();

        $userModel = new User();
        $users = $userModel->getAll();

        require_once __DIR__ . '/../views/admin/User/users.php';
    }

    public function deleteUser() {
        $id = $_GET['id'] ?? null;

        if (!$id || !is_numeric($id) || (int)$id <= 0) {
            echo "ID người dùng không hợp lệ!";
            return;
        }

        $userModel = new User();
        $user = $userModel->findById((int)$id);

        if (!$user) {
            echo "Không tìm thấy người dùng để xóa!";
            return;
        }

        $userModel->delete((int)$id);

        header("Location: index.php?act=users");
        exit;
    }

    public function editUser() {
        $id = $_GET['id'] ?? null;

        if (!$id || !is_numeric($id) || (int)$id <= 0) {
            echo "ID người dùng không hợp lệ!";
            return;
        }

        $userModel = new User();
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = trim($_POST['username'] ?? '');
            $email    = trim($_POST['email'] ?? '');
            $std      = trim($_POST['std'] ?? '');
            $diachi   = trim($_POST['diachi'] ?? '');
            $role     = trim($_POST['role'] ?? 'user');

            if ($username === '') {
                $errors[] = "Username không được để trống.";
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

            if (!in_array($role, ['user', 'admin'], true)) {
                $errors[] = "Vai trò không hợp lệ.";
            }

            // Không cho admin tự hạ vai trò chính mình
            if ((int)$id === (int)($_SESSION['user_id'] ?? 0) && $role !== 'admin') {
                $errors[] = "Bạn không thể tự hạ vai trò của chính mình.";
            }

            if (empty($errors)) {
                $userModel->update((int)$id, [
                    'username' => $username,
                    'email'    => $email,
                    'std'      => $std,
                    'diachi'   => $diachi,
                    'role'     => $role,
                ]);

                header("Location: index.php?act=users");
                exit;
            }
        }

        $user = $userModel->findById((int)$id);

        if (!$user) {
            echo "Không tìm thấy người dùng!";
            return;
        }

        require_once __DIR__ . '/../views/admin/User/EditUser.php';
    }

    public function CateProduct() {
        $this->requireAdmin();

        $productModel = new Product();
        $variants = $productModel->getAllVariants();

        require_once __DIR__ . '/../views/admin/ViewProduct/CateProduct.php';
    }

    public function addCateProduct() {
        $this->requireAdmin();

        $productModel = new Product();
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $product_id = $_POST['product_id'] ?? '';
            $color_id   = $_POST['color_id'] ?? '';
            $size_id    = $_POST['size_id'] ?? '';
            $price      = $_POST['price'] ?? '';
            $stock      = $_POST['stock'] ?? '';

            if ($product_id === '' || !is_numeric($product_id) || (int)$product_id <= 0) {
                $errors[] = "Vui lòng chọn sản phẩm.";
            }

            if ($color_id === '' || !is_numeric($color_id) || (int)$color_id <= 0) {
                $errors[] = "Vui lòng chọn màu sắc.";
            }

            if ($size_id === '' || !is_numeric($size_id) || (int)$size_id <= 0) {
                $errors[] = "Vui lòng chọn kích thước.";
            }

            if ($price === '' || !is_numeric($price) || (int)$price < 0) {
                $errors[] = "Giá sản phẩm phải là số và không được âm.";
            }

            if ($stock === '' || !is_numeric($stock) || (int)$stock < 0) {
                $errors[] = "Tồn kho phải là số và không được âm.";
            }

            $imageName = '';

            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $allowedExt = ['jpg', 'jpeg', 'png', 'webp'];
                $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));

                if (!in_array($ext, $allowedExt)) {
                    $errors[] = "Ảnh chỉ được phép là jpg, jpeg, png, webp.";
                }

                if ($_FILES['image']['size'] > 2 * 1024 * 1024) {
                    $errors[] = "Ảnh không được lớn hơn 2MB.";
                }

                if (@getimagesize($_FILES['image']['tmp_name']) === false) {
                    $errors[] = "File không phải ảnh hợp lệ.";
                }

                if (empty($errors)) {
                    $safeName = preg_replace('/[^a-zA-Z0-9_-]/', '', pathinfo($_FILES['image']['name'], PATHINFO_FILENAME));
                    $imageName = time() . '_' . ($safeName ?: 'img') . '.' . $ext;
                    move_uploaded_file($_FILES['image']['tmp_name'], 'uploads/' . $imageName);
                }
            } else {
                $errors[] = "Vui lòng chọn ảnh sản phẩm.";
            }

            if (empty($errors)) {
                $data = [
                    'product_id' => (int)$product_id,
                    'color_id'   => (int)$color_id,
                    'size_id'    => (int)$size_id,
                    'image'      => $imageName,
                    'price'      => (int)$price,
                    'stock'      => (int)$stock
                ];

                $productModel->addVariant($data);
                header("Location: index.php?act=CateProduct");
                exit;
            }
        }

        $products = $productModel->getProducts();
        $colors   = $productModel->getColors();
        $sizes    = $productModel->getSizes();

        require_once __DIR__ . '/../views/admin/ViewProduct/AddCateProduct.php';
    }

    public function editCateProduct() {
        $this->requireAdmin();

        $id = $_GET['id'] ?? null;

        if (!$id || !is_numeric($id) || (int)$id <= 0) {
            echo "ID danh mục sản phẩm không hợp lệ!";
            return;
        }

        $productModel = new Product();
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $product_id   = $_POST['product_id'] ?? '';
            $product_name = trim($_POST['product_name'] ?? '');
            $color_id     = $_POST['color_id'] ?? '';
            $size_id      = $_POST['size_id'] ?? '';
            $price        = $_POST['price'] ?? '';
            $stock        = $_POST['stock'] ?? '';
            $old_image    = $_POST['old_image'] ?? '';

            if ($product_id === '' || !is_numeric($product_id) || (int)$product_id <= 0) {
                $errors[] = "Sản phẩm không hợp lệ.";
            }

            if ($product_name === '') {
                $errors[] = "Tên sản phẩm không được để trống.";
            } elseif (mb_strlen($product_name) > 255) {
                $errors[] = "Tên sản phẩm không được quá 255 ký tự.";
            }

            if ($color_id === '' || !is_numeric($color_id) || (int)$color_id <= 0) {
                $errors[] = "Vui lòng chọn màu sắc.";
            }

            if ($size_id === '' || !is_numeric($size_id) || (int)$size_id <= 0) {
                $errors[] = "Vui lòng chọn kích thước.";
            }

            if ($price === '' || !is_numeric($price) || (int)$price < 0) {
                $errors[] = "Giá sản phẩm phải là số và không được âm.";
            }

            if ($stock === '' || !is_numeric($stock) || (int)$stock < 0) {
                $errors[] = "Tồn kho phải là số và không được âm.";
            }

            $imageName = $old_image;

            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $allowedExt = ['jpg', 'jpeg', 'png', 'webp'];
                $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));

                if (!in_array($ext, $allowedExt)) {
                    $errors[] = "Ảnh chỉ được phép là jpg, jpeg, png, webp.";
                }

                if ($_FILES['image']['size'] > 2 * 1024 * 1024) {
                    $errors[] = "Ảnh không được lớn hơn 2MB.";
                }

                if (@getimagesize($_FILES['image']['tmp_name']) === false) {
                    $errors[] = "File không phải ảnh hợp lệ.";
                }

                if (empty($errors)) {
                    $safeName = preg_replace('/[^a-zA-Z0-9_-]/', '', pathinfo($_FILES['image']['name'], PATHINFO_FILENAME));
                    $imageName = time() . '_' . ($safeName ?: 'img') . '.' . $ext;
                    move_uploaded_file($_FILES['image']['tmp_name'], 'uploads/' . $imageName);
                }
            }

            if (empty($errors)) {
                $data = [
                    'product_id' => (int)$product_id,
                    'color_id'   => (int)$color_id,
                    'size_id'    => (int)$size_id,
                    'image'      => $imageName,
                    'price'      => (int)$price,
                    'stock'      => (int)$stock
                ];

                $productModel->updateVariant((int)$id, $data);
                $productModel->updateProductName((int)$product_id, $product_name);

                header("Location: index.php?act=CateProduct");
                exit;
            }
        }

        $variant = $productModel->getVariantById((int)$id);

        if (!$variant) {
            echo "Không tìm thấy biến thể hoặc biến thể này đã bị ẩn!";
            return;
        }

        $products = $productModel->getProducts();
        $colors   = $productModel->getColors();
        $sizes    = $productModel->getSizes();

        require_once __DIR__ . '/../views/admin/ViewProduct/EditCateProduct.php';
    }

    public function deleteCateProduct() {
    $this->requireAdmin();

    $id = $_GET['id'] ?? null;

    if (!$id || !is_numeric($id) || (int)$id <= 0) {
        echo "ID danh mục sản phẩm không hợp lệ!";
        return;
    }

    $productModel = new Product();

    // Lấy thông tin variant còn hoạt động
    $variant = $productModel->getVariantById((int)$id);

    if (!$variant) {
        echo "Không tìm thấy dữ liệu để ẩn!";
        return;
    }

    $ok = $productModel->deleteVariant((int)$id);

    if ($ok) {
        header("Location: index.php?act=CateProduct");
        exit;
    }

    echo "Ẩn biến thể sản phẩm thất bại!";
    }

    public function donhang() {
    $this->requireAdmin();

    $productModel = new Product();
    $orders = $productModel->getAllOrders();

    require_once __DIR__ . '/../views/admin/Order/donhang.php';
}

public function detailOrder()
{
    $this->requireAdmin();

    $orderId = (int)($_GET['id'] ?? 0);

    if ($orderId <= 0) {
        echo "ID đơn hàng không hợp lệ!";
        return;
    }

    $productModel = new Product();
    $order = $productModel->getOrderById($orderId);

    if (!$order) {
        echo "Không tìm thấy đơn hàng!";
        return;
    }

    $orderDetails = $productModel->getOrderDetails($orderId);

    require_once __DIR__ . '/../views/admin/Order/detailOrder.php';
}

public function thongke()
{
    $this->requireAdmin();

    $productModel = new Product();
    $thongke = $productModel->getThongKeDoanhThu();

    require_once __DIR__ . '/../views/admin/Order/thongke.php';
}

public function editOrder() {
    $this->requireAdmin();

    $orderId = (int)($_GET['id'] ?? 0);

    if ($orderId <= 0) {
        echo "ID đơn hàng không hợp lệ!";
        return;
    }

    $productModel = new Product();
    $order = $productModel->getOrderById($orderId);

    if (!$order) {
        echo "Không tìm thấy đơn hàng!";
        return;
    }

    $errors = [];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $receiverName    = trim($_POST['receiver_name'] ?? '');
        $receiverPhone   = trim($_POST['receiver_phone'] ?? '');
        $receiverAddress = trim($_POST['receiver_address'] ?? '');
        $shippingFee     = (float)($_POST['shipping_fee'] ?? 0);
        $paymentMethod   = trim($_POST['payment_method'] ?? 'cod');
        $paymentStatus   = trim($_POST['payment_status'] ?? 'unpaid');
        $status          = trim($_POST['status'] ?? 'cho_xac_nhan');

        // Chỉ cho sửa thông tin nhận hàng khi đơn ở 3 trạng thái đầu
        $editableStatuses = ['cho_xac_nhan', 'da_dat_hang', 'dang_lay_hang'];
        $canEditReceiver  = in_array($order['status'], $editableStatuses, true);

        if (!$canEditReceiver) {
            $changed = $receiverName !== $order['receiver_name']
                    || $receiverPhone !== $order['receiver_phone']
                    || $receiverAddress !== $order['receiver_address'];
            if ($changed) {
                $errors[] = "Đơn ở trạng thái này không được phép sửa thông tin người nhận.";
            }
            // Bắt buộc giữ nguyên dữ liệu cũ
            $receiverName    = $order['receiver_name'];
            $receiverPhone   = $order['receiver_phone'];
            $receiverAddress = $order['receiver_address'];
        }

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

        if ($shippingFee < 0) {
            $errors[] = "Phí vận chuyển không được âm.";
        }

        $allowPayMethod = ['cod'];
        if (!in_array($paymentMethod, $allowPayMethod, true)) {
            $errors[] = "Phương thức thanh toán không hợp lệ.";
        }

        $allowPayStatus = ['unpaid', 'paid', 'dang_hoan_tien', 'da_hoan_tien'];
        if (!in_array($paymentStatus, $allowPayStatus, true)) {
            $errors[] = "Trạng thái thanh toán không hợp lệ.";
        }

        $allowStatus = [
            'cho_xac_nhan','da_dat_hang','dang_lay_hang','dang_van_chuyen',
            'da_van_chuyen','hoan_thanh','da_huy'
        ];
        if (!in_array($status, $allowStatus, true)) {
            $errors[] = "Trạng thái đơn hàng không hợp lệ.";
        }

        if ($order['status'] === 'hoan_thanh' && $status !== 'hoan_thanh') {
            $errors[] = "Đơn hàng đã hoàn thành, không thể đổi trạng thái.";
        }

        if ($order['status'] === 'da_huy' && $status !== 'da_huy') {
            $errors[] = "Đơn hàng đã bị hủy, không thể chuyển sang trạng thái khác.";
        }

        if ($order['payment_status'] === 'paid' && $paymentStatus !== 'paid' && $order['status'] !== 'da_huy') {
            $errors[] = "Đơn đã thanh toán, không thể đổi về chưa thanh toán.";
        }

        $isOnlinePaidPlaced = $order['payment_method'] !== 'cod'
            && $order['payment_status'] === 'paid'
            && $order['status'] === 'da_dat_hang';

        if ($isOnlinePaidPlaced && $paymentStatus !== 'paid') {
            $errors[] = "Đơn thanh toán online đã hoàn tất ở trạng thái 'Đã đặt hàng' — không thể đổi trạng thái thanh toán.";
        }

        // COD: chỉ cho đổi payment_status khi status được chuyển sang 'hoan_thanh' (Model tự auto set 'paid')
        if ($order['payment_method'] === 'cod' && $paymentStatus !== $order['payment_status'] && $status !== 'hoan_thanh') {
            $errors[] = "Đơn COD — trạng thái thanh toán chỉ tự cập nhật khi đơn chuyển sang 'Hoàn thành'. Vui lòng giữ nguyên trạng thái thanh toán.";
        }

        if ($status === 'hoan_thanh') {
            $paymentStatus = 'paid';
        }

        if (empty($errors)) {
            $productModel->updateOrderInfo($orderId, [
                'receiver_name'    => $receiverName,
                'receiver_phone'   => $receiverPhone,
                'receiver_address' => $receiverAddress,
                'shipping_fee'     => $shippingFee,
                'payment_method'   => $paymentMethod,
                'payment_status'   => $paymentStatus,
                'status'           => $status,
            ]);

            header("Location: index.php?act=donhang");
            exit;
        }

        $order['receiver_name']    = $receiverName;
        $order['receiver_phone']   = $receiverPhone;
        $order['receiver_address'] = $receiverAddress;
        $order['shipping_fee']     = $shippingFee;
        $order['payment_method']   = $paymentMethod;
        $order['payment_status']   = $paymentStatus;
        $order['status']           = $status;
    }

    require_once __DIR__ . '/../views/admin/Order/editOrder.php';
}

public function updatePaymentStatus() {
    $this->requireAdmin();

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location: index.php?act=donhang");
        exit;
    }

    $orderId = (int)($_POST['order_id'] ?? 0);
    $payment = trim($_POST['payment_status'] ?? '');

    $allowPay = ['unpaid', 'paid', 'dang_hoan_tien', 'da_hoan_tien'];

    if ($orderId <= 0 || !in_array($payment, $allowPay, true)) {
        $_SESSION['order_flash'] = ['type' => 'error', 'msg' => 'Dữ liệu cập nhật thanh toán không hợp lệ!'];
        header("Location: index.php?act=donhang");
        exit;
    }

    $productModel = new Product();
    $order = $productModel->getOrderById($orderId);

    if (!$order) {
        $_SESSION['order_flash'] = ['type' => 'error', 'msg' => 'Không tìm thấy đơn hàng!'];
        header("Location: index.php?act=donhang");
        exit;
    }

    $isOnlinePaidPlaced = $order['payment_method'] !== 'cod'
        && $order['payment_status'] === 'paid'
        && $order['status'] === 'da_dat_hang';

    if ($isOnlinePaidPlaced && $payment !== 'paid') {
        $_SESSION['order_flash'] = [
            'type' => 'error',
            'msg'  => 'Đơn #' . (int)$orderId . ' thanh toán online đã hoàn thành — không thể thay đổi trạng thái thanh toán.',
        ];
        header("Location: index.php?act=donhang");
        exit;
    }

    // COD: không cho admin tự đổi payment_status. Chỉ được auto-update khi status = hoan_thanh
    if ($order['payment_method'] === 'cod') {
        $_SESSION['order_flash'] = [
            'type' => 'error',
            'msg'  => 'Đơn #' . (int)$orderId . ' thanh toán COD — trạng thái thanh toán chỉ tự cập nhật khi đơn chuyển sang "Hoàn thành".',
        ];
        header("Location: index.php?act=donhang");
        exit;
    }

    $productModel->updateOrderInfo($orderId, [
        'receiver_name'    => $order['receiver_name'],
        'receiver_phone'   => $order['receiver_phone'],
        'receiver_address' => $order['receiver_address'],
        'shipping_fee'     => (float)$order['shipping_fee'],
        'payment_method'   => $order['payment_method'],
        'payment_status'   => $payment,
        'status'           => $order['status'],
    ]);

    $_SESSION['order_flash'] = [
        'type' => 'success',
        'msg'  => "Đã cập nhật trạng thái thanh toán đơn #{$orderId}.",
    ];
    header("Location: index.php?act=donhang");
    exit;
}

public function updateOrderStatus() {
    $this->requireAdmin();

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location: index.php?act=donhang");
        exit;
    }

    $orderId = (int)($_POST['order_id'] ?? 0);
    $status = trim($_POST['status'] ?? '');

    $allowStatus = [
        'cho_xac_nhan',
        'da_dat_hang',
        'dang_lay_hang',
        'dang_van_chuyen',
        'da_van_chuyen',
        'hoan_thanh',
        'da_huy'
    ];

    if ($orderId <= 0 || !in_array($status, $allowStatus, true)) {
        $_SESSION['order_flash'] = ['type' => 'error', 'msg' => 'Dữ liệu cập nhật không hợp lệ!'];
        header("Location: index.php?act=donhang");
        exit;
    }

    $productModel = new Product();
    $order = $productModel->getOrderById($orderId);

    if (!$order) {
        $_SESSION['order_flash'] = ['type' => 'error', 'msg' => 'Không tìm thấy đơn hàng!'];
        header("Location: index.php?act=donhang");
        exit;
    }

    if ($order['status'] === 'hoan_thanh' && $status !== 'hoan_thanh') {
        $_SESSION['order_flash'] = [
            'type' => 'error',
            'msg'  => 'Đơn hàng #' . (int)$orderId . ' đã hoàn thành, không thể đổi sang trạng thái khác.'
        ];
        header("Location: index.php?act=donhang");
        exit;
    }

    if ($order['status'] === 'da_huy' && $status !== 'da_huy') {
        $_SESSION['order_flash'] = [
            'type' => 'error',
            'msg'  => 'Đơn hàng #' . (int)$orderId . ' đã bị hủy, không thể chuyển sang trạng thái khác.'
        ];
        header("Location: index.php?act=donhang");
        exit;
    }

    $productModel->updateOrderStatusById($orderId, $status);

    $_SESSION['order_flash'] = [
        'type' => 'success',
        'msg'  => 'Đã cập nhật trạng thái đơn hàng #' . (int)$orderId . '.'
    ];
    header("Location: index.php?act=donhang");
    exit;
    }
}