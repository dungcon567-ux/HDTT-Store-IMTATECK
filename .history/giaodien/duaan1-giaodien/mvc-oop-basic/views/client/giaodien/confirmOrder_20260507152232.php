<?php
$pageTitle = 'Hoàn tất đặt hàng #' . (int)$order['id'];
$activeNav = 'orders';
require_once __DIR__ . '/_header.php';

$subTotal = 0;
foreach ($orderDetails as $item) {
    $subTotal += $item['price'] * $item['quantity'];
}

$isOnline   = $order['payment_method'] !== 'cod';
$isPaid     = $order['payment_status'] === 'paid';
$needsPay   = $isOnline && !$isPaid;
$transferNote = 'HDTT' . $order['id'];

$qrStaticPath = __DIR__ . '/../../../uploads/qr_zalopay.png';
if ($order['payment_method'] === 'zalopay' && file_exists($qrStaticPath)) {
    $qrUrl = '/Duan1/giaodien/duaan1-giaodien/mvc-oop-basic/uploads/qr_zalopay.png';
} else {
    // Fallback: VietQR động (chuẩn NAPAS 247 - app nào cũng quét được)
    $qrUrl  = 'https://img.vietqr.io/image/MOMO-PSP2604910500000353-compact2.png'
            . '?amount=' . (int)$order['total']
            . '&addInfo=' . urlencode($transferNote)
            . '&accountName=' . urlencode('DO VIET DUNG');
}
?>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1"><i class="fas fa-clipboard-check text-primary me-2"></i>Hoàn tất đặt hàng <span class="text-muted">#<?= (int)$order['id'] ?></span></h2>
            <small class="text-muted">Kiểm tra và bổ sung thông tin trước khi xác nhận đơn hàng</small>
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
            <form method="POST" action="/Duan1/giaodien/duaan1-giaodien/mvc-oop-basic/index.php?act=confirmOrder">
                <input type="hidden" name="order_id" value="<?= (int)$order['id'] ?>">

                <div class="card shadow-sm border-0 rounded-4 mb-3">
                    <div class="card-body p-4">
                        <h4 class="mb-3"><i class="fas fa-truck text-primary me-2"></i>Thông tin nhận hàng</h4>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Họ tên người nhận</label>
                            <input type="text" name="receiver_name" class="form-control rounded-pill"
                                   value="<?= htmlspecialchars($order['receiver_name']) ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Số điện thoại</label>
                            <input type="text" name="receiver_phone" class="form-control rounded-pill"
                                   value="<?= htmlspecialchars($order['receiver_phone']) ?>" required>
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

                <div class="card shadow-sm border-0 rounded-4 mb-3">
                    <div class="card-body p-4">
                        <h4 class="mb-3"><i class="fas fa-credit-card text-primary me-2"></i>Phương thức thanh toán</h4>
                        <div class="d-flex align-items-center gap-3 p-3 rounded-3" style="background:#f8f9fa">
                            <div style="width:42px;height:42px;border-radius:10px;background:#0d6efd;color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700">
                                <?php
                                    $iconMap = ['cod'=>'fa-money-bill-wave','momo'=>'M','zalopay'=>'Z','vnpay'=>'fa-credit-card'];
                                    $methodNames = ['cod'=>'Thanh toán khi nhận hàng (COD)','momo'=>'MoMo','zalopay'=>'ZaloPay','vnpay'=>'VNPay'];
                                    $iconV = $iconMap[$order['payment_method']] ?? 'fa-money';
                                ?>
                                <?php if (str_starts_with($iconV, 'fa-')): ?>
                                    <i class="fas <?= $iconV ?>"></i>
                                <?php else: ?>
                                    <?= $iconV ?>
                                <?php endif; ?>
                            </div>
                            <div class="flex-grow-1">
                                <div class="fw-bold"><?= htmlspecialchars($methodNames[$order['payment_method']] ?? $order['payment_method']) ?></div>
                                <small class="text-muted">
                                    <?= $isPaid
                                        ? '<span class="text-success"><i class="fas fa-check-circle"></i> Đã thanh toán</span>'
                                        : '<span class="text-warning"><i class="fas fa-clock"></i> Chưa thanh toán</span>' ?>
                                </small>
                            </div>
                        </div>
                    </div>
                </div>

                <?php if ($needsPay): ?>
                    <div class="card shadow-sm border-0 rounded-4 mb-3" style="border:2px solid #f59e0b !important">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-3">
                                <h4 class="mb-0 flex-grow-1"><i class="fas fa-qrcode text-warning me-2"></i>Quét QR để thanh toán</h4>
                                <span class="badge bg-warning text-dark"><i class="fas fa-clock me-1"></i> Chưa thanh toán</span>
                            </div>

                            <div class="alert alert-warning rounded-3 mb-3" style="font-size:13.5px">
                                <i class="fas fa-exclamation-triangle me-1"></i>
                                Đơn hàng <b>không thể hoàn tất</b> nếu chưa thanh toán. Vui lòng quét QR bên dưới hoặc bấm <b>Thanh toán ngay</b>.
                            </div>

                            <div class="row g-3 align-items-center">
                                <div class="col-md-5 text-center">
                                    <div style="border:3px solid #f1f5f9;border-radius:14px;padding:8px;background:#fff;display:inline-block">
                                        <img src="<?= $qrUrl ?>" alt="QR thanh toán" style="width:220px;height:220px;object-fit:contain;border-radius:8px">
                                    </div>
                                    <div class="mt-2 d-flex justify-content-center gap-2" style="font-size:11px;font-weight:700">
                                        <span style="color:#a50064">momo</span>
                                        <span style="color:#dc2626">VietQR</span>
                                        <span style="color:#0068ff">napas 247</span>
                                    </div>
                                </div>

                                <div class="col-md-7">
                                    <div class="p-3 rounded-3" style="background:linear-gradient(135deg,#fff7f9,#ffe4ec);border:1px solid #fbcfe8">
                                        <div class="fw-bold" style="color:#831843;font-size:15px">ĐỖ VIẾT DŨNG</div>
                                        <div style="color:#9d174d;font-family:monospace;font-size:13px">STK: *******353</div>
                                    </div>

                                    <div class="mt-2 p-2 rounded-3" style="background:#f8f9fa">
                                        <small class="text-muted d-block mb-1">Số tiền cần chuyển</small>
                                        <div class="fw-bold text-danger" style="font-size:20px"><?= number_format($order['total']) ?>đ</div>
                                    </div>

                                    <div class="mt-2 p-2 rounded-3" style="background:#fef3c7;border:1px dashed #f59e0b">
                                        <small style="color:#92400e;font-weight:600">
                                            <i class="fas fa-pen me-1"></i>Nội dung chuyển khoản:
                                        </small>
                                        <div class="d-flex align-items-center gap-2 mt-1 px-2 py-1 rounded" style="background:#fff">
                                            <code class="fw-bold" id="noteCopy"><?= htmlspecialchars($transferNote) ?></code>
                                            <button type="button" onclick="copyNote()"
                                                class="btn btn-sm btn-warning ms-auto" style="padding:2px 8px;font-size:11px">
                                                <i class="far fa-copy"></i> Copy
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-3 d-flex flex-wrap gap-2">
                                <a href="/Duan1/giaodien/duaan1-giaodien/mvc-oop-basic/index.php?act=payGateway&order_id=<?= (int)$order['id'] ?>"
                                   class="btn btn-warning rounded-pill px-4 py-2 text-white fw-bold">
                                    <i class="fas fa-mobile-alt me-2"></i>Thanh toán ngay
                                </a>
                                <small class="text-muted align-self-center">Mở cổng thanh toán đầy đủ với đếm ngược 15:00</small>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="d-flex gap-2">
                    <?php if ($needsPay): ?>
                        <button type="submit" class="btn btn-secondary rounded-pill px-4 py-2" disabled
                                title="Vui lòng thanh toán trước khi hoàn tất">
                            <i class="fas fa-lock me-2"></i>Cần thanh toán trước
                        </button>
                    <?php else: ?>
                        <button type="submit" class="btn btn-primary rounded-pill px-4 py-2">
                            <i class="fas fa-check-circle me-2"></i>Xác nhận đặt hàng
                        </button>
                    <?php endif; ?>
                    <a href="/Duan1/giaodien/duaan1-giaodien/mvc-oop-basic/index.php?act=myOrders" class="btn btn-outline-secondary rounded-pill px-4 py-2">
                        Hủy
                    </a>
                </div>
            </form>

            <script>
            function copyNote(){
                const t = document.getElementById('noteCopy').innerText;
                navigator.clipboard.writeText(t);
                event.target.closest('button').innerHTML = '<i class="fas fa-check"></i> Đã copy';
                setTimeout(()=> event.target.closest('button').innerHTML = '<i class="far fa-copy"></i> Copy', 1500);
            }
            </script>
        </div>

        <div class="col-lg-5">
            <div class="card shadow-sm border-0 rounded-4 sticky-top" style="top:20px">
                <div class="card-body p-4">
                    <h4 class="mb-3">Sản phẩm trong đơn</h4>

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
