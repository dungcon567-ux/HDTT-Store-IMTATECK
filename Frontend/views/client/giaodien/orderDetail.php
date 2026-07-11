<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>

<?php
function hienThiTrangThaiDonHang($status) {
    return match ($status) {
        'cho_xac_nhan'    => 'Chờ xác nhận',
        'da_dat_hang'     => 'Đã đặt hàng',
        'dang_lay_hang'   => 'Đang lấy hàng',
        'dang_van_chuyen' => 'Đang vận chuyển',
        'da_van_chuyen'   => 'Đã vận chuyển',
        'hoan_thanh'      => 'Hoàn thành',
        'da_huy'          => 'Đã hủy',
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

$pageTitle = 'Chi tiết đơn hàng #' . (int)$order['id'];
$activeNav = 'orders';
require_once __DIR__ . '/_header.php';
?>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0"><i class="fas fa-receipt me-2 text-primary"></i>Chi tiết đơn hàng #<?= $order['id'] ?></h2>
        <a href="/Duan1/index.php?act=myOrders" class="btn btn-outline-secondary rounded-pill px-4">
            <i class="fas fa-arrow-left me-1"></i> Quay lại
        </a>
    </div>

    <div class="row g-4">
        <div class="col-lg-5">
            <div class="card shadow-sm border-0 rounded-4 h-100">
                <div class="card-header bg-primary text-white rounded-top-4">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Thông tin đơn hàng</h5>
                </div>
                <div class="card-body">
                    <p><strong>Người đặt:</strong> <?= htmlspecialchars($order['username'] ?? '-') ?></p>
                    <hr>
                    <p><strong>Người nhận:</strong> <?= htmlspecialchars($order['receiver_name']) ?></p>
                    <p><strong>Số điện thoại:</strong> <?= htmlspecialchars($order['receiver_phone']) ?></p>
                    <p><strong>Địa chỉ:</strong> <?= htmlspecialchars($order['receiver_address']) ?></p>
                    <hr>
                    <p><strong>Trạng thái đơn:</strong>
                        <span class="badge bg-info"><?= hienThiTrangThaiDonHang($order['status']) ?></span>
                    </p>
                    <p><strong>Thanh toán:</strong>
                        <span class="badge <?= $order['payment_status'] === 'paid' ? 'bg-success' : 'bg-warning text-dark' ?>">
                            <?= hienThiTrangThaiThanhToan($order['payment_status']) ?>
                        </span>
                    </p>
                    <p><strong>Phương thức:</strong> <?= $order['payment_method'] === 'cod' ? 'Thanh toán khi nhận hàng' : htmlspecialchars($order['payment_method']) ?></p>
                    <hr>
                    <p><strong>Phí ship:</strong> <?= number_format($order['shipping_fee']) ?>đ</p>
                    <p class="mb-0 fs-5"><strong>Tổng tiền:</strong>
                        <span class="text-danger fw-bold"><?= number_format($order['total']) ?>đ</span>
                    </p>
                </div>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-light rounded-top-4">
                    <h5 class="mb-0"><i class="fas fa-box me-2"></i>Sản phẩm trong đơn</h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($orderDetails)): ?>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Ảnh</th>
                                        <th>Sản phẩm</th>
                                        <th>Màu</th>
                                        <th>Size</th>
                                        <th>Giá</th>
                                        <th>SL</th>
                                        <th>Thành tiền</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($orderDetails as $item): ?>
                                        <tr>
                                            <td>
                                                <img src="/Duan1/uploads/<?= htmlspecialchars($item['image']) ?>"
                                                     width="70" class="rounded">
                                            </td>
                                            <td><?= htmlspecialchars($item['product_name']) ?></td>
                                            <td><?= htmlspecialchars($item['color_name']) ?></td>
                                            <td><?= htmlspecialchars($item['size_name']) ?></td>
                                            <td><?= number_format($item['price']) ?>đ</td>
                                            <td><?= (int)$item['quantity'] ?></td>
                                            <td class="text-primary fw-bold"><?= number_format($item['price'] * $item['quantity']) ?>đ</td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info mb-0">Không có sản phẩm trong đơn hàng.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/_footer.php'; ?>
