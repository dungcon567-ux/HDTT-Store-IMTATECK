<?php
class Product {
    private $conn;

    public function __construct() {
        $this->conn = new mysqli("localhost", "root", "", "php-oop-basic");

        if ($this->conn->connect_error) {
            die("Kết nối thất bại: " . $this->conn->connect_error);
        }

        $this->conn->set_charset("utf8mb4");
    }

    // =========================
    // SẢN PHẨM GỐC
    // =========================

    public function getProductDetailById($id) {
    $sql = "SELECT p.id, p.name AS product_name, p.description,
                   pv.image, pv.price, pv.stock,
                   c.name AS color_name, s.name AS size_name
            FROM products p
            LEFT JOIN product_variants pv ON p.id = pv.product_id AND pv.status = 1
            LEFT JOIN color c ON pv.color_id = c.id
            LEFT JOIN size s ON pv.size_id = s.id
            WHERE p.id = ?
            LIMIT 1";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
    }

    public function getAllProducts($keyword = '') {
        $keyword = trim($keyword);

        if ($keyword === '') {
            $sql = "SELECT p.*, c.name AS category_name
                    FROM products p
                    LEFT JOIN categories c ON p.category_id = c.id
                    ORDER BY p.id DESC";
            $result = $this->conn->query($sql);
        } else {
            $sql = "SELECT p.*, c.name AS category_name
                    FROM products p
                    LEFT JOIN categories c ON p.category_id = c.id
                    WHERE p.id = ?
                       OR p.name = ?
                       OR p.name LIKE ?
                       OR c.name LIKE ?
                       OR p.description LIKE ?
                    ORDER BY p.id DESC";

            $stmt = $this->conn->prepare($sql);
            $like = "%$keyword%";
            $id = is_numeric($keyword) ? (int)$keyword : 0;
            $stmt->bind_param("issss", $id, $keyword, $like, $like, $like);
            $stmt->execute();
            $result = $stmt->get_result();
        }

        $data = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }

        return $data;
    }

    public function getProductById($id) {
        $sql = "SELECT * FROM products WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function addProduct($data) {
        $sql = "INSERT INTO products(name, category_id, description) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sis", $data['name'], $data['category_id'], $data['description']);
        return $stmt->execute();
    }

    public function updateProductName($id, $name) {
        $sql = "UPDATE products SET name = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("si", $name, $id);
        return $stmt->execute();
    }

    public function updateProduct($id, $data) {
        $sql = "UPDATE products
                SET name = ?, category_id = ?, description = ?
                WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sisi", $data['name'], $data['category_id'], $data['description'], $id);
        return $stmt->execute();
    }

    public function deleteProduct($id) {
        $sql = "DELETE FROM products WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public function getCategories() {
        $sql = "SELECT * FROM categories ORDER BY id DESC";
        $result = $this->conn->query($sql);

        $data = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }

        return $data;
    }

    public function getProducts() {
        $sql = "SELECT id, name FROM products ORDER BY id DESC";
        $result = $this->conn->query($sql);

        $data = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }

        return $data;
    }

    public function hasOrder($productId) {
    $sql = "SELECT COUNT(*) AS total
            FROM order_details od
            JOIN product_variants pv ON od.variant_id = pv.id
            WHERE pv.product_id = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();

    return (int)$result['total'] > 0;
}

public function deleteProductSafe($productId) {
    if ($this->hasOrder($productId)) {
        return false;
    }

    $this->conn->begin_transaction();

    try {
        $sqlCart = "DELETE c
                    FROM cart c
                    JOIN product_variants pv ON c.variant_id = pv.id
                    WHERE pv.product_id = ?";
        $stmtCart = $this->conn->prepare($sqlCart);
        $stmtCart->bind_param("i", $productId);
        $stmtCart->execute();

        $sqlVariant = "UPDATE product_variants SET status = 0 WHERE product_id = ?";
        $stmtVariant = $this->conn->prepare($sqlVariant);
        $stmtVariant->bind_param("i", $productId);
        $stmtVariant->execute();

        $sqlProduct = "DELETE FROM products WHERE id = ?";
        $stmtProduct = $this->conn->prepare($sqlProduct);
        $stmtProduct->bind_param("i", $productId);
        $stmtProduct->execute();

        $this->conn->commit();
        return true;
    } catch (Exception $e) {
        $this->conn->rollback();
        throw $e;
    }
    }

    // =========================
    // BIẾN THỂ SẢN PHẨM
    // =========================

    public function getAllVariants($keyword = '', $categoryId = 0) {
    $keyword = trim($keyword);
    $categoryId = (int)$categoryId;

    $base = "SELECT
                pv.id,
                pv.product_id,
                pv.image,
                pv.price,
                pv.stock,
                p.name AS product_name,
                p.category_id,
                p.description,
                c.name AS color_name,
                s.name AS size_name
            FROM product_variants pv
            JOIN products p ON pv.product_id = p.id
            JOIN color c ON pv.color_id = c.id
            JOIN size s ON pv.size_id = s.id
            WHERE pv.status = 1";

    if ($keyword === '' && $categoryId <= 0) {
        $sql = $base . " ORDER BY pv.id DESC";
        $result = $this->conn->query($sql);
    } elseif ($keyword === '' && $categoryId > 0) {
        $sql = $base . " AND p.category_id = ? ORDER BY pv.id DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $categoryId);
        $stmt->execute();
        $result = $stmt->get_result();
    } elseif ($keyword !== '' && $categoryId <= 0) {
        $sql = $base . " AND (
                            pv.id = ?
                            OR p.name LIKE ?
                            OR c.name LIKE ?
                            OR s.name LIKE ?
                        ) ORDER BY pv.id DESC";
        $stmt = $this->conn->prepare($sql);
        $id = is_numeric($keyword) ? (int)$keyword : 0;
        $like = "%$keyword%";
        $stmt->bind_param("isss", $id, $like, $like, $like);
        $stmt->execute();
        $result = $stmt->get_result();
    } else {
        $sql = $base . " AND p.category_id = ?
                         AND (
                            pv.id = ?
                            OR p.name LIKE ?
                            OR c.name LIKE ?
                            OR s.name LIKE ?
                         ) ORDER BY pv.id DESC";
        $stmt = $this->conn->prepare($sql);
        $id = is_numeric($keyword) ? (int)$keyword : 0;
        $like = "%$keyword%";
        $stmt->bind_param("iisss", $categoryId, $id, $like, $like, $like);
        $stmt->execute();
        $result = $stmt->get_result();
    }

    $data = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    }

    return $data;
    }

    public function getVariantById($id) {
    $sql = "SELECT pv.*, p.name AS product_name, c.name AS color_name, s.name AS size_name
            FROM product_variants pv
            JOIN products p ON pv.product_id = p.id
            JOIN color c ON pv.color_id = c.id
            JOIN size s ON pv.size_id = s.id
            WHERE pv.id = ? AND pv.status = 1";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
    }

    public function addVariant($data) {
        $sql = "INSERT INTO product_variants(product_id, color_id, size_id, image, price, stock)
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param(
            "iiisii",
            $data['product_id'],
            $data['color_id'],
            $data['size_id'],
            $data['image'],
            $data['price'],
            $data['stock']
        );
        return $stmt->execute();
    }

    public function updateVariant($id, $data) {
        $sql = "UPDATE product_variants
                SET product_id = ?, color_id = ?, size_id = ?, image = ?, price = ?, stock = ?
                WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param(
            "iiisiii",
            $data['product_id'],
            $data['color_id'],
            $data['size_id'],
            $data['image'],
            $data['price'],
            $data['stock'],
            $id
        );
        return $stmt->execute();
    }

    public function deleteVariant($id) {
    $sql = "UPDATE product_variants SET status = 0 WHERE id = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("i", $id);
    return $stmt->execute();
    }

    public function getColors() {
        $sql = "SELECT id, name FROM color ORDER BY id DESC";
        $result = $this->conn->query($sql);

        $data = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        return $data;
    }

    public function getSizes() {
        $sql = "SELECT id, name FROM size ORDER BY id DESC";
        $result = $this->conn->query($sql);

        $data = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        return $data;
    }

    // =========================
    // USER
    // =========================

    public function getUserById($id) {
        $sql = "SELECT * FROM users WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function updateUser($id, $data) {
        $sql = "UPDATE users SET username=?, email=?, std=?, diachi=? WHERE id=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param(
            "ssssi",
            $data['username'],
            $data['email'],
            $data['std'],
            $data['diachi'],
            $id
        );
        return $stmt->execute();
    }

    // =========================
    // CLIENT / CART / ORDER
    // =========================

    public function getProductDetailWithVariants($productId) {
    $sql = "SELECT 
                p.id AS product_id,
                p.name AS product_name,
                p.description,
                pv.id AS variant_id,
                pv.image,
                pv.price,
                pv.stock,
                c.id AS color_id,
                c.name AS color_name,
                s.id AS size_id,
                s.name AS size_name
            FROM products p
            JOIN product_variants pv ON p.id = pv.product_id
            JOIN color c ON pv.color_id = c.id
            JOIN size s ON pv.size_id = s.id
            WHERE p.id = ? AND pv.status = 1
            ORDER BY pv.id DESC";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $result = $stmt->get_result();

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    return $data;
    }

    public function getVariantByProductColorSize($productId, $colorId, $sizeId) {
    $sql = "SELECT *
            FROM product_variants
            WHERE product_id = ? AND color_id = ? AND size_id = ? AND status = 1
            LIMIT 1";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("iii", $productId, $colorId, $sizeId);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
    }

    public function addToCart($userId, $variantId, $quantity) {
        $checkSql = "SELECT id, quantity FROM cart WHERE user_id = ? AND variant_id = ?";
        $checkStmt = $this->conn->prepare($checkSql);
        $checkStmt->bind_param("ii", $userId, $variantId);
        $checkStmt->execute();
        $exists = $checkStmt->get_result()->fetch_assoc();

        if ($exists) {
            $newQty = $exists['quantity'] + $quantity;
            $sql = "UPDATE cart SET quantity = ? WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("ii", $newQty, $exists['id']);
            return $stmt->execute();
        }

        $sql = "INSERT INTO cart(user_id, variant_id, quantity) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("iii", $userId, $variantId, $quantity);
        return $stmt->execute();
    }

    public function getCartByUser($userId) {
    $sql = "SELECT 
                cart.id,
                cart.quantity,
                pv.id AS variant_id,
                pv.image,
                pv.price,
                pv.stock,
                p.name AS product_name,
                c.name AS color_name,
                s.name AS size_name
            FROM cart
            JOIN product_variants pv ON cart.variant_id = pv.id
            JOIN products p ON pv.product_id = p.id
            JOIN color c ON pv.color_id = c.id
            JOIN size s ON pv.size_id = s.id
            WHERE cart.user_id = ? AND pv.status = 1
            ORDER BY cart.id DESC";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    return $data;
    }

    public function updateCartQuantity($cartId, $userId, $quantity) {
    $sql = "UPDATE cart SET quantity = ? WHERE id = ? AND user_id = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("iii", $quantity, $cartId, $userId);
    return $stmt->execute();
    }

    public function deleteCartItem($cartId, $userId) {
        $sql = "DELETE FROM cart WHERE id = ? AND user_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $cartId, $userId);
        return $stmt->execute();
    }

    public function getCartItemsByIds($userId, $cartIds) {
    if (empty($cartIds)) {
        return [];
    }

    $cartIds = array_map('intval', $cartIds);
    $placeholders = implode(',', array_fill(0, count($cartIds), '?'));
    $types = 'i' . str_repeat('i', count($cartIds));
    $params = array_merge([$userId], $cartIds);

    $sql = "SELECT 
                cart.id,
                cart.quantity,
                pv.id AS variant_id,
                pv.image,
                pv.price,
                pv.stock,
                p.id AS product_id,
                p.name AS product_name,
                c.name AS color_name,
                s.name AS size_name
            FROM cart
            JOIN product_variants pv ON cart.variant_id = pv.id
            JOIN products p ON pv.product_id = p.id
            JOIN color c ON pv.color_id = c.id
            JOIN size s ON pv.size_id = s.id
            WHERE cart.user_id = ?
              AND cart.id IN ($placeholders)
              AND pv.status = 1
            ORDER BY cart.id DESC";

    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();

    $result = $stmt->get_result();
    $data = [];

    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    return $data;
    }

    public function createOrder($data) {
    $sql = "INSERT INTO orders (
                user_id,
                total,
                shipping_fee,
                payment_method,
                status,
                payment_status,
                receiver_name,
                receiver_phone,
                receiver_address,
                online
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param(
        "iddsssssss",
        $data['user_id'],
        $data['total'],
        $data['shipping_fee'],
        $data['payment_method'],
        $data['status'],
        $data['payment_status'],
        $data['receiver_name'],
        $data['receiver_phone'],
        $data['receiver_address'],
        $data['online']
    );

    if ($stmt->execute()) {
        return $this->conn->insert_id;
    }

    return false;
}

    public function addOrderDetail($orderId, $variantId, $quantity, $price) {
        $sql = "INSERT INTO order_details(order_id, variant_id, quantity, price)
                VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("iiii", $orderId, $variantId, $quantity, $price);
        return $stmt->execute();
    }

    public function updateVariantStock($variantId, $quantityBought) {
        $sql = "UPDATE product_variants
                SET stock = stock - ?
                WHERE id = ? AND stock >= ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("iii", $quantityBought, $variantId, $quantityBought);
        return $stmt->execute();
    }

    // ========== WISHLIST ==========

    public function getWishlistByUser($userId) {
        $sql = "SELECT
                    w.id AS wishlist_id, w.created_at,
                    p.id AS product_id, p.name AS product_name, p.category_id, p.description,
                    (SELECT pv.image FROM product_variants pv
                     WHERE pv.product_id = p.id AND pv.status = 1 LIMIT 1) AS image,
                    (SELECT MIN(pv.price) FROM product_variants pv
                     WHERE pv.product_id = p.id AND pv.status = 1) AS min_price,
                    (SELECT MAX(pv.price) FROM product_variants pv
                     WHERE pv.product_id = p.id AND pv.status = 1) AS max_price,
                    (SELECT SUM(pv.stock) FROM product_variants pv
                     WHERE pv.product_id = p.id AND pv.status = 1) AS total_stock
                FROM wishlist w
                JOIN products p ON p.id = w.product_id
                WHERE w.user_id = ?
                ORDER BY w.id DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = [];
        while ($row = $result->fetch_assoc()) $data[] = $row;
        return $data;
    }

    public function getWishlistProductIds($userId) {
        $sql = "SELECT product_id FROM wishlist WHERE user_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $ids = [];
        while ($row = $result->fetch_assoc()) $ids[] = (int)$row['product_id'];
        return $ids;
    }

    public function addWishlist($userId, $productId) {
        $sql = "INSERT IGNORE INTO wishlist (user_id, product_id) VALUES (?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $userId, $productId);
        return $stmt->execute();
    }

    public function removeWishlist($userId, $productId) {
        $sql = "DELETE FROM wishlist WHERE user_id = ? AND product_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $userId, $productId);
        return $stmt->execute();
    }

    public function isInWishlist($userId, $productId) {
        $sql = "SELECT 1 FROM wishlist WHERE user_id = ? AND product_id = ? LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $userId, $productId);
        $stmt->execute();
        return (bool)$stmt->get_result()->fetch_assoc();
    }

    public function countWishlist($userId) {
        $sql = "SELECT COUNT(*) AS c FROM wishlist WHERE user_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        return (int)($row['c'] ?? 0);
    }

    public function getRelatedProducts($productId, $categoryId, $limit = 4) {
        $sql = "SELECT
                    p.id AS product_id,
                    p.name AS product_name,
                    (SELECT pv2.image FROM product_variants pv2
                     WHERE pv2.product_id = p.id AND pv2.status = 1 LIMIT 1) AS image,
                    (SELECT MIN(pv3.price) FROM product_variants pv3
                     WHERE pv3.product_id = p.id AND pv3.status = 1) AS min_price,
                    (SELECT MAX(pv3.price) FROM product_variants pv3
                     WHERE pv3.product_id = p.id AND pv3.status = 1) AS max_price,
                    (SELECT SUM(pv4.stock) FROM product_variants pv4
                     WHERE pv4.product_id = p.id AND pv4.status = 1) AS total_stock
                FROM products p
                WHERE p.category_id = ? AND p.id <> ?
                HAVING image IS NOT NULL
                ORDER BY p.id DESC
                LIMIT ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("iii", $categoryId, $productId, $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = [];
        while ($row = $result->fetch_assoc()) $data[] = $row;
        return $data;
    }

    public function getCommentsByProduct($productId) {
        $sql = "SELECT r.id, r.rating, r.content, r.created_at,
                       u.username, u.avatar
                FROM reviews r
                LEFT JOIN users u ON r.user_id = u.id
                WHERE r.product_id = ?
                ORDER BY r.created_at DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $productId);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = [];
        while ($row = $result->fetch_assoc()) $data[] = $row;
        return $data;
    }

    public function addComment($userId, $productId, $rating, $content) {
        $sql = "INSERT INTO reviews (user_id, product_id, rating, content) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("iiis", $userId, $productId, $rating, $content);
        return $stmt->execute();
    }

    public function getTopSellingProducts($limit = 8) {
        $sql = "SELECT
                    p.id AS product_id,
                    p.name AS product_name,
                    p.category_id,
                    p.description,
                    (SELECT pv2.image
                     FROM product_variants pv2
                     WHERE pv2.product_id = p.id AND pv2.status = 1
                     LIMIT 1) AS image,
                    (SELECT MIN(pv3.price)
                     FROM product_variants pv3
                     WHERE pv3.product_id = p.id AND pv3.status = 1) AS min_price,
                    (SELECT MAX(pv3.price)
                     FROM product_variants pv3
                     WHERE pv3.product_id = p.id AND pv3.status = 1) AS max_price,
                    (SELECT SUM(pv4.stock)
                     FROM product_variants pv4
                     WHERE pv4.product_id = p.id AND pv4.status = 1) AS total_stock,
                    COALESCE(SUM(od.quantity), 0) AS sold_qty
                FROM products p
                LEFT JOIN product_variants pv ON pv.product_id = p.id AND pv.status = 1
                LEFT JOIN order_details od ON od.variant_id = pv.id
                LEFT JOIN orders o ON o.id = od.order_id AND o.status <> 'da_huy'
                GROUP BY p.id, p.name, p.category_id, p.description
                HAVING image IS NOT NULL
                ORDER BY sold_qty DESC, p.id DESC
                LIMIT ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $limit);
        $stmt->execute();
        $result = $stmt->get_result();

        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    }

    public function restoreVariantStock($variantId, $quantity) {
        $sql = "UPDATE product_variants SET stock = stock + ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $quantity, $variantId);
        return $stmt->execute();
    }

    public function removeManyCartItems($userId, $cartIds) {
        if (empty($cartIds)) {
            return false;
        }

        $placeholders = implode(',', array_fill(0, count($cartIds), '?'));
        $types = str_repeat('i', count($cartIds) + 1);

        $sql = "DELETE FROM cart WHERE user_id = ? AND id IN ($placeholders)";
        $stmt = $this->conn->prepare($sql);
        $params = array_merge([$userId], $cartIds);
        $stmt->bind_param($types, ...$params);
        return $stmt->execute();
    }

    public function getOrdersByUser($userId) {
        $sql = "SELECT * FROM orders WHERE user_id = ? ORDER BY id DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        return $data;
    }

    //=========================

    public function getCartItemById($cartId, $userId) {
    $sql = "SELECT 
                c.id,
                c.quantity,
                pv.stock,
                p.name AS product_name
            FROM cart c
            JOIN product_variants pv ON c.variant_id = pv.id
            JOIN products p ON pv.product_id = p.id
            WHERE c.id = ? AND c.user_id = ? AND pv.status = 1
            LIMIT 1";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("ii", $cartId, $userId);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
    }

public function getAllOrders() {
    $sql = "SELECT 
                o.*,
                u.username
            FROM orders o
            LEFT JOIN users u ON o.user_id = u.id
            ORDER BY o.id DESC";
    $result = $this->conn->query($sql);

    $data = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    }
    return $data;
}

public function getOrderById($id) {
    $sql = "SELECT o.*, u.username, u.email AS user_email, u.std AS user_phone
            FROM orders o
            LEFT JOIN users u ON o.user_id = u.id
            WHERE o.id = ? LIMIT 1";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

public function updateOrderInfo($id, $data) {
    if (($data['status'] ?? '') === 'hoan_thanh') {
        $data['payment_status'] = 'paid';
    }

    // Đơn online khi chuyển sang "đã đặt hàng" → tự động đánh dấu đã thanh toán
    if (($data['status'] ?? '') === 'da_dat_hang'
        && ($data['payment_method'] ?? 'cod') !== 'cod') {
        $data['payment_status'] = 'paid';
    }

    $sql = "UPDATE orders
            SET receiver_name = ?,
                receiver_phone = ?,
                receiver_address = ?,
                shipping_fee = ?,
                payment_method = ?,
                payment_status = ?,
                status = ?
            WHERE id = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param(
        "sssdsssi",
        $data['receiver_name'],
        $data['receiver_phone'],
        $data['receiver_address'],
        $data['shipping_fee'],
        $data['payment_method'],
        $data['payment_status'],
        $data['status'],
        $id
    );
    return $stmt->execute();
}

public function markOrderPaid($id) {
    $sql = "UPDATE orders SET payment_status = 'paid' WHERE id = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}

public function updateOrderStatusById($id, $status) {
    if ($status === 'hoan_thanh') {
        $sql = "UPDATE orders
                SET status = ?, payment_status = 'paid'
                WHERE id = ?";
    } elseif ($status === 'da_dat_hang') {
        // Nếu đơn này là online (không phải COD) → đánh dấu đã thanh toán luôn
        $sql = "UPDATE orders
                SET status = ?,
                    payment_status = CASE WHEN payment_method <> 'cod' THEN 'paid' ELSE payment_status END
                WHERE id = ?";
    } else {
        $sql = "UPDATE orders
                SET status = ?
                WHERE id = ?";
    }

    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("si", $status, $id);
    return $stmt->execute();
}

public function getOrderHistoryByUser($userId) {
    $sql = "SELECT *
            FROM orders
            WHERE user_id = ?
            ORDER BY id DESC";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    return $data;
}

public function getOrderDetails($orderId) {
    $sql = "SELECT 
                od.*,
                p.name AS product_name,
                pv.image,
                c.name AS color_name,
                s.name AS size_name
            FROM order_details od
            JOIN product_variants pv ON od.variant_id = pv.id
            JOIN products p ON pv.product_id = p.id
            LEFT JOIN color c ON pv.color_id = c.id
            LEFT JOIN size s ON pv.size_id = s.id
            WHERE od.order_id = ?
            ORDER BY od.id DESC";

    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("i", $orderId);
    $stmt->execute();
    $result = $stmt->get_result();

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    return $data;
}

public function getOrderByIdAndUser($orderId, $userId) {
    $sql = "SELECT o.*, u.username, u.email AS user_email
            FROM orders o
            LEFT JOIN users u ON o.user_id = u.id
            WHERE o.id = ? AND o.user_id = ?
            LIMIT 1";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("ii", $orderId, $userId);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

public function getThongKeDoanhThu() {
    $data = [];

    $sqlTongDon = "SELECT COUNT(*) AS total_orders FROM orders";
    $resultTongDon = $this->conn->query($sqlTongDon);
    $data['total_orders'] = $resultTongDon ? (int)$resultTongDon->fetch_assoc()['total_orders'] : 0;

    $sqlHoanThanh = "SELECT COUNT(*) AS completed_orders 
                     FROM orders 
                     WHERE status = 'hoan_thanh'";
    $resultHoanThanh = $this->conn->query($sqlHoanThanh);
    $data['completed_orders'] = $resultHoanThanh ? (int)$resultHoanThanh->fetch_assoc()['completed_orders'] : 0;

    $sqlDoanhThu = "SELECT COALESCE(SUM(total), 0) AS revenue
                    FROM orders
                    WHERE status = 'hoan_thanh'";
    $resultDoanhThu = $this->conn->query($sqlDoanhThu);
    $data['revenue'] = $resultDoanhThu ? (float)$resultDoanhThu->fetch_assoc()['revenue'] : 0;

    $sqlChuaThanhToan = "SELECT COUNT(*) AS unpaid_orders
                         FROM orders
                         WHERE payment_status = 'unpaid'";
    $resultChuaThanhToan = $this->conn->query($sqlChuaThanhToan);
    $data['unpaid_orders'] = $resultChuaThanhToan ? (int)$resultChuaThanhToan->fetch_assoc()['unpaid_orders'] : 0;

    // chỉ chạy thống kê theo ngày khi bảng orders có cột created_at
    $checkColumn = $this->conn->query("SHOW COLUMNS FROM orders LIKE 'created_at'");
    $data['daily_revenue'] = [];

    if ($checkColumn && $checkColumn->num_rows > 0) {
        $sqlTheoNgay = "SELECT DATE(created_at) AS order_date, COALESCE(SUM(total),0) AS daily_revenue
                        FROM orders
                        WHERE status = 'hoan_thanh'
                        GROUP BY DATE(created_at)
                        ORDER BY order_date DESC
                        LIMIT 10";
        $resultTheoNgay = $this->conn->query($sqlTheoNgay);

        if ($resultTheoNgay) {
            while ($row = $resultTheoNgay->fetch_assoc()) {
                $data['daily_revenue'][] = $row;
            }
        }
    }

    return $data;
}
}