<?php
$pageTitle = 'Kết quả thanh toán';
$activeNav = 'orders';
require_once __DIR__ . '/_header.php';

$success = !isset($resultStatus) || $resultStatus !== 'cancel';
$methodName = match ($order['payment_method']) {
    'momo'    => 'MoMo',
    'zalopay' => 'ZaloPay',
    'vnpay'   => 'VNPay',
    default   => 'Cổng thanh toán',
};
?>

<style>
    .result-wrap{
        max-width:560px;margin:60px auto;
        background:#fff;border-radius:24px;
        box-shadow:0 12px 36px rgba(0,0,0,.06);
        overflow:hidden;
        border:1px solid #ececec;
    }
    .result-icon-wrap{
        padding:40px 20px 24px;text-align:center;
        background:<?= $success ? 'linear-gradient(135deg,#d1fae5,#a7f3d0)' : 'linear-gradient(135deg,#fee2e2,#fecaca)' ?>;
    }
    .result-circle{
        width:90px;height:90px;border-radius:50%;
        background:<?= $success ? '#10b981' : '#ef4444' ?>;
        color:#fff;display:inline-flex;align-items:center;justify-content:center;
        font-size:42px;
        box-shadow:0 10px 30px <?= $success ? 'rgba(16,185,129,.4)' : 'rgba(239,68,68,.4)' ?>;
        animation:pop .5s ease;
    }
    @keyframes pop{0%{transform:scale(0)}80%{transform:scale(1.1)}100%{transform:scale(1)}}
    .result-body{padding:30px 36px}
    .result-body h2{font-weight:800;text-align:center;margin:0 0 8px}
    .result-body .lead{text-align:center;color:#6c757d;margin:0 0 24px}
    .info-line{display:flex;justify-content:space-between;padding:10px 0;border-bottom:1px dashed #e5e7eb;font-size:14px}
    .info-line:last-child{border:0}
    .info-line span:last-child{font-weight:700;color:#0f172a}
    .actions{display:flex;gap:10px;margin-top:24px}
    .actions a{flex:1;text-align:center}
</style>

<div class="result-wrap">
    <div class="result-icon-wrap">
        <div class="result-circle">
            <i class="fas <?= $success ? 'fa-check' : 'fa-times' ?>"></i>
        </div>
    </div>

    <div class="result-body">
        <?php if ($success): ?>
            <h2 style="color:#065f46">Thanh toán thành công!</h2>
            <p class="lead">Cảm ơn bạn đã mua hàng tại HDTT Store.<br>Đơn hàng đã được xác nhận và sẽ sớm được giao.</p>
        <?php else: ?>
            <h2 style="color:#991b1b">Giao dịch đã hủy</h2>
            <p class="lead">Đơn hàng vẫn được tạo nhưng chưa thanh toán.<br>Bạn có thể thanh toán lại hoặc chọn COD trong lịch sử đơn.</p>
        <?php endif; ?>

        <div class="info-line"><span>Mã đơn hàng</span><span>#<?= (int)$order['id'] ?></span></div>
        <div class="info-line"><span>Phương thức</span><span><?= htmlspecialchars($methodName) ?></span></div>
        <div class="info-line"><span>Tổng tiền</span><span class="text-primary"><?= number_format($order['total']) ?>đ</span></div>
        <div class="info-line"><span>Trạng thái</span>
            <span>
                <?php if ($success): ?>
                    <span class="badge bg-success">Đã thanh toán</span>
                <?php else: ?>
                    <span class="badge bg-warning text-dark">Chưa thanh toán</span>
                <?php endif; ?>
            </span>
        </div>

        <div class="actions">
            <a href="/Duan1/giaodien/duaan1-giaodien/mvc-oop-basic/index.php?act=orderDetail&id=<?= (int)$order['id'] ?>"
               class="btn btn-primary rounded-pill px-4 py-2">
                <i class="fas fa-receipt me-2"></i>Xem đơn
            </a>
            <a href="/Duan1/giaodien/duaan1-giaodien/mvc-oop-basic/index.php?act=giaodien"
               class="btn btn-outline-secondary rounded-pill px-4 py-2">
                Tiếp tục mua sắm
            </a>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/_footer.php'; ?>
