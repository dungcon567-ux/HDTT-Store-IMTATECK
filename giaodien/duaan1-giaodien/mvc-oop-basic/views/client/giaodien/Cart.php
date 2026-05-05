<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>

<?php
function hienThiTrangThaiDonHang($status) {
    return match ($status) {
        'cho_xac_nhan'   => 'Chờ xác nhận',
        'da_dat_hang'    => 'Đã đặt hàng',
        'dang_lay_hang'  => 'Đang lấy hàng',
        'dang_van_chuyen'=> 'Đang vận chuyển',
        'da_van_chuyen'  => 'Đã vận chuyển',
        'hoan_thanh'     => 'Hoàn thành',
        'da_huy'         => 'Đã hủy',
        default => 'Không xác định',
    };
}

function hienThiTrangThaiThanhToan($paymentStatus) {
    return match ($paymentStatus) {
        'unpaid'         => 'Chưa thanh toán',
        'paid'           => 'Đã thanh toán',
        'dang_hoan_tien' => 'Đang hoàn tiền',
        'da_hoan_tien'   => 'Đã hoàn tiền',
        default          => 'Không xác định',
    };
}

$pageTitle = 'Giỏ hàng';
$activeNav = 'cart';
require_once __DIR__ . '/_header.php';
?>

<style>
    .section-card {
        border: none;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.06);
        overflow: hidden;
    }
    .section-header {
        background: linear-gradient(45deg, #3b82f6, #6366f1);
        color: white;
        padding: 18px 24px;
    }
</style>

<div class="container py-5">
    <div class="card section-card mb-5">
        <div class="section-header">
            <h3 class="mb-0"><i class="fas fa-shopping-cart me-2"></i>Giỏ hàng của bạn</h3>
        </div>
        <div class="card-body p-4">

            <?php if (!empty($cartItems)): ?>
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="checkAll"></th>
                            <th>Ảnh</th>
                            <th>Sản phẩm</th>
                            <th>Màu</th>
                            <th>Size</th>
                            <th>Giá</th>
                            <th>Số lượng</th>
                            <th>Tạm tính</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $total = 0; ?>
                        <?php foreach ($cartItems as $item): ?>
                            <?php $subtotal = $item['price'] * $item['quantity']; ?>
                            <?php $total += $subtotal; ?>
                            <tr>
                                <td>
                                    <input type="checkbox" form="checkoutForm" name="selected_cart[]" value="<?= $item['id'] ?>" class="item-check">
                                </td>
                                <td>
                                    <img src="/Duan1/giaodien/duaan1-giaodien/mvc-oop-basic/uploads/<?= htmlspecialchars($item['image']) ?>" width="80" class="rounded">
                                </td>
                                <td><?= htmlspecialchars($item['product_name']) ?></td>
                                <td><?= htmlspecialchars($item['color_name']) ?></td>
                                <td><?= htmlspecialchars($item['size_name']) ?></td>
                                <td><?= number_format($item['price']) ?>đ</td>
                                <td>
                                    <form method="POST" action="/Duan1/giaodien/duaan1-giaodien/mvc-oop-basic/index.php?act=updateCart" class="d-flex gap-2 align-items-start">
                                        <input type="hidden" name="cart_id" value="<?= $item['id'] ?>">
                                        <div>
                                            <input type="number"
                                                name="quantity"
                                                value="<?= $item['quantity'] ?>"
                                                min="1"
                                                max="<?= (int)$item['stock'] ?>"
                                                class="form-control"
                                                style="width: 90px;">
                                            <small class="text-muted">Còn lại: <?= max(0, (int)$item['stock'] - (int)$item['quantity']) ?>/<?= (int)$item['stock'] ?></small>
                                        </div>
                                        <button type="submit" class="btn btn-sm btn-primary">Cập nhật</button>
                                    </form>
                                </td>
                                <td><?= number_format($subtotal) ?>đ</td>
                                <td>
                                    <a href="/Duan1/giaodien/duaan1-giaodien/mvc-oop-basic/index.php?act=deleteCart&id=<?= $item['id'] ?>"
                                       class="btn btn-sm btn-danger"
                                       onclick="return confirm('Xóa sản phẩm này khỏi giỏ hàng?')">
                                        Xóa
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <form method="POST" action="/Duan1/giaodien/duaan1-giaodien/mvc-oop-basic/index.php?act=checkout" id="checkoutForm">
                <div class="text-end mt-4">
                    <h4>Tổng tiền giỏ hàng: <span class="text-primary"><?= number_format($total) ?>đ</span></h4>
                    <button type="submit" class="btn btn-success rounded-pill px-4 mt-3">
                        <i class="fas fa-credit-card me-2"></i>Thanh toán
                    </button>
                </div>
            </form>

            <?php else: ?>
                <div class="alert alert-warning mb-0 text-center">
                    <i class="fas fa-shopping-cart fa-2x mb-3 d-block"></i>
                    Giỏ hàng đang trống.
                </div>
            <?php endif; ?>

            <div class="mt-3 text-end">
                <a href="/Duan1/giaodien/duaan1-giaodien/mvc-oop-basic/index.php?act=myOrders" class="btn btn-outline-primary rounded-pill px-4">
                    <i class="fas fa-box me-1"></i> Lịch sử đơn hàng
                </a>
            </div>
        </div>
    </div>

    <div class="mt-4">
        <a href="/Duan1/giaodien/duaan1-giaodien/mvc-oop-basic/index.php?act=giaodien" class="btn btn-secondary rounded-pill px-4">
            <i class="fas fa-arrow-left me-1"></i> Tiếp tục mua hàng
        </a>
    </div>
</div>

<script>
document.getElementById('checkAll')?.addEventListener('change', function () {
    document.querySelectorAll('.item-check').forEach(item => {
        item.checked = this.checked;
    });
});
</script>

<?php require_once __DIR__ . '/_footer.php'; ?>
