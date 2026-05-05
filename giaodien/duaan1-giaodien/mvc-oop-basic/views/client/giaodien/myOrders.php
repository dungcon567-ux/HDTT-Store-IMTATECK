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

function badgeOrder($status) {
    return match ($status) {
        'cho_xac_nhan'    => 'bg-warning text-dark',
        'da_dat_hang'     => 'bg-primary text-white',
        'dang_lay_hang'   => 'bg-info text-white',
        'dang_van_chuyen' => 'bg-info text-white',
        'da_van_chuyen'   => 'bg-primary text-white',
        'hoan_thanh'      => 'bg-success text-white',
        'da_huy'          => 'bg-danger text-white',
        default           => 'bg-secondary text-white',
    };
}

function badgePayment($paymentStatus) {
    return match ($paymentStatus) {
        'paid'           => 'bg-success',
        'dang_hoan_tien' => 'bg-warning text-dark',
        'da_hoan_tien'   => 'bg-info text-white',
        default          => 'bg-warning text-dark',
    };
}

$pageTitle = 'Đơn hàng của tôi';
$activeNav = 'orders';
require_once __DIR__ . '/_header.php';
?>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0"><i class="fas fa-box me-2 text-primary"></i>Đơn hàng của tôi</h2>
        <a href="/Duan1/giaodien/duaan1-giaodien/mvc-oop-basic/index.php?act=giaodien" class="btn btn-outline-primary rounded-pill px-4">
            <i class="fas fa-arrow-left me-1"></i> Tiếp tục mua hàng
        </a>
    </div>

    <?php if (!empty($orders)): ?>
        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Mã đơn</th>
                                <th>Người nhận</th>
                                <th>SĐT</th>
                                <th>Địa chỉ</th>
                                <th>Tổng tiền</th>
                                <th>Phí ship</th>
                                <th>Trạng thái đơn</th>
                                <th>Thanh toán</th>
                                <th>Hình thức</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orders as $order): ?>
                                <tr>
                                    <td><strong>#<?= $order['id'] ?></strong></td>
                                    <td><?= htmlspecialchars($order['receiver_name']) ?></td>
                                    <td><?= htmlspecialchars($order['receiver_phone']) ?></td>
                                    <td><?= htmlspecialchars($order['receiver_address']) ?></td>
                                    <td class="text-primary fw-bold"><?= number_format($order['total']) ?>đ</td>
                                    <td><?= number_format($order['shipping_fee']) ?>đ</td>
                                    <td>
                                        <span class="badge <?= badgeOrder($order['status']) ?>">
                                            <?= hienThiTrangThaiDonHang($order['status']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge <?= badgePayment($order['payment_status']) ?>">
                                            <?= hienThiTrangThaiThanhToan($order['payment_status']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?= ($order['payment_method'] === 'cod') ? 'COD' : htmlspecialchars($order['payment_method']) ?>
                                    </td>
                                    <td style="white-space:nowrap">
                                        <a href="/Duan1/giaodien/duaan1-giaodien/mvc-oop-basic/index.php?act=orderDetail&id=<?= $order['id'] ?>"
                                           class="btn btn-sm btn-primary rounded-pill px-3"
                                           title="Xem chi tiết đơn hàng">
                                            <i class="fas fa-eye me-1"></i> Chi tiết
                                        </a>

                                        <?php if ($order['status'] === 'cho_xac_nhan'): ?>
                                            <a href="/Duan1/giaodien/duaan1-giaodien/mvc-oop-basic/index.php?act=confirmOrder&id=<?= $order['id'] ?>"
                                               class="btn btn-sm btn-success rounded-pill"
                                               title="Hoàn tất đặt hàng">
                                                <i class="fas fa-check-circle"></i>
                                            </a>
                                        <?php endif; ?>

                                        <?php
                                            $editableInfo  = ['cho_xac_nhan', 'da_dat_hang', 'dang_lay_hang'];
                                            $cancelable    = ['cho_xac_nhan', 'da_dat_hang', 'dang_lay_hang'];
                                            $cantCancelYet = ['dang_van_chuyen', 'da_van_chuyen'];
                                            $reorderable   = ['da_huy', 'hoan_thanh'];
                                        ?>

                                        <?php if (in_array($order['status'], $editableInfo, true)): ?>
                                            <a href="/Duan1/giaodien/duaan1-giaodien/mvc-oop-basic/index.php?act=editReceiverInfo&id=<?= $order['id'] ?>"
                                               class="btn btn-sm btn-warning rounded-pill text-white"
                                               title="Sửa thông tin nhận hàng">
                                                <i class="fas fa-user-edit"></i>
                                            </a>
                                        <?php endif; ?>
                                        <?php if (in_array($order['status'], $cancelable, true)): ?>
                                            <a href="/Duan1/giaodien/duaan1-giaodien/mvc-oop-basic/index.php?act=cancelOrder&id=<?= $order['id'] ?>"
                                               class="btn btn-sm btn-danger rounded-pill"
                                               title="Hủy đơn hàng"
                                               onclick="return confirm('Bạn có chắc muốn hủy đơn hàng #<?= (int)$order['id'] ?>? <?= $order['payment_method']!=='cod' && $order['payment_status']==='paid' ? 'Hệ thống sẽ xử lý hoàn tiền.' : 'Hành động này không thể hoàn tác.' ?>');">
                                                <i class="fas fa-times"></i> Hủy
                                            </a>
                                        <?php elseif (in_array($order['status'], $cantCancelYet, true)): ?>
                                            <button type="button"
                                                    class="btn btn-sm btn-outline-secondary rounded-pill"
                                                    disabled
                                                    title="Đơn đang vận chuyển, không thể hủy">
                                                <i class="fas fa-ban"></i> Hủy
                                            </button>
                                        <?php endif; ?>

                                        <?php if (in_array($order['status'], $reorderable, true)): ?>
                                            <a href="/Duan1/giaodien/duaan1-giaodien/mvc-oop-basic/index.php?act=reorder&id=<?= $order['id'] ?>"
                                               class="btn btn-sm btn-warning rounded-pill text-white"
                                               title="Mua lại sản phẩm"
                                               onclick="return confirm('Thêm các sản phẩm trong đơn này vào giỏ hàng?');">
                                                <i class="fas fa-redo"></i> Mua lại
                                            </a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="alert alert-info text-center p-5">
            <i class="fas fa-box-open fa-3x mb-3 d-block"></i>
            Bạn chưa có đơn hàng nào.
            <div class="mt-3">
                <a href="/Duan1/giaodien/duaan1-giaodien/mvc-oop-basic/index.php?act=giaodien" class="btn btn-primary rounded-pill px-4">
                    Mua sắm ngay
                </a>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/_footer.php'; ?>
