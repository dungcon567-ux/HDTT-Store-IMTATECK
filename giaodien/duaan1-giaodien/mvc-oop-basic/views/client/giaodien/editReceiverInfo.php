<?php
$pageTitle = 'Sửa thông tin nhận hàng #' . (int)$order['id'];
$activeNav = 'orders';
require_once __DIR__ . '/_header.php';

$subTotal = 0;
foreach ($orderDetails as $item) {
    $subTotal += $item['price'] * $item['quantity'];
}

$statusLabel = match ($order['status']) {
    'cho_xac_nhan'  => 'Chờ xác nhận',
    'da_dat_hang'   => 'Đã đặt hàng',
    'dang_lay_hang' => 'Đang lấy hàng',
    default         => $order['status'],
};
?>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1"><i class="fas fa-user-edit text-primary me-2"></i>Sửa thông tin nhận hàng <span class="text-muted">#<?= (int)$order['id'] ?></span></h2>
            <small class="text-muted">Cập nhật người nhận, số điện thoại, địa chỉ giao hàng</small>
        </div>
        <a href="/Duan1/giaodien/duaan1-giaodien/mvc-oop-basic/index.php?act=myOrders" class="btn btn-outline-secondary rounded-pill px-4">
            <i class="fas fa-arrow-left me-1"></i> Quay lại
        </a>
    </div>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger rounded-4">
            <ul class="mb-0">
                <?php foreach ($errors as $err): ?><li><?= htmlspecialchars($err) ?></li><?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="row g-4">
        <div class="col-lg-7">
            <form method="POST" action="/Duan1/giaodien/duaan1-giaodien/mvc-oop-basic/index.php?act=editReceiverInfo">
                <input type="hidden" name="order_id" value="<?= (int)$order['id'] ?>">

                <div class="card shadow-sm border-0 rounded-4 mb-3">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h4 class="mb-0"><i class="fas fa-truck text-primary me-2"></i>Thông tin nhận hàng</h4>
                            <span class="badge bg-info"><?= htmlspecialchars($statusLabel) ?></span>
                        </div>

                        <div class="alert alert-info rounded-3" style="font-size:13.5px">
                            <i class="fas fa-info-circle me-1"></i>
                            Bạn có thể chỉnh sửa thông tin nhận hàng khi đơn ở các trạng thái: <b>Chờ xác nhận / Đã đặt hàng / Đang lấy hàng</b>.
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Họ tên người nhận</label>
                            <input type="text" name="receiver_name" class="form-control rounded-pill"
                                   value="<?= htmlspecialchars($order['receiver_name']) ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Số điện thoại</label>
                            <input type="text" name="receiver_phone" class="form-control rounded-pill"
                                   value="<?= htmlspecialchars($order['receiver_phone']) ?>" required>
                            <small class="text-muted">9-11 chữ số</small>
                        </div>

                        <div class="mb-1">
                            <label class="form-label fw-bold mb-2">Địa chỉ nhận hàng</label>
                            <?php
                                $oldAddress = $order['receiver_address'];
                                $required = false;
                                require __DIR__ . '/_address_picker.php';
                            ?>
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary rounded-pill px-4 py-2">
                        <i class="fas fa-save me-2"></i>Lưu thay đổi
                    </button>
                    <a href="/Duan1/giaodien/duaan1-giaodien/mvc-oop-basic/index.php?act=myOrders" class="btn btn-outline-secondary rounded-pill px-4 py-2">
                        Hủy
                    </a>
                </div>
            </form>
        </div>

        <div class="col-lg-5">
            <div class="card shadow-sm border-0 rounded-4 sticky-top" style="top:20px">
                <div class="card-body p-4">
                    <h4 class="mb-3">Tóm tắt đơn hàng</h4>

                    <?php foreach ($orderDetails as $item): ?>
                        <div class="d-flex align-items-center border-bottom py-3">
                            <img src="/Duan1/giaodien/duaan1-giaodien/mvc-oop-basic/uploads/<?= htmlspecialchars($item['image'] ?? '') ?>" width="60" class="rounded me-3">
                            <div class="flex-grow-1">
                                <div class="fw-bold"><?= htmlspecialchars($item['product_name']) ?></div>
                                <div class="text-muted small">
                                    <?= htmlspecialchars($item['color_name'] ?? '') ?> ·
                                    Size <?= htmlspecialchars($item['size_name'] ?? '') ?> ·
                                    SL <?= (int)$item['quantity'] ?>
                                </div>
                            </div>
                            <div class="fw-bold text-primary">
                                <?= number_format($item['price'] * $item['quantity']) ?>đ
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <div class="pt-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Tạm tính</span>
                            <strong><?= number_format($subTotal) ?>đ</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Phí vận chuyển</span>
                            <strong><?= number_format($order['shipping_fee']) ?>đ</strong>
                        </div>
                        <div class="d-flex justify-content-between border-top pt-3">
                            <span class="fw-bold fs-5">Tổng cộng</span>
                            <strong class="text-danger fs-5"><?= number_format($order['total']) ?>đ</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/_footer.php'; ?>
