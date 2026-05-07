<?php
$pageTitle = 'Kết quả thanh toán';
$activeNav = 'orders';
require_once __DIR__ . '/_header.php';

// $vnpResult được set từ HomeController::vnpayReturn()
$vnpResult = $vnpResult ?? ['success' => true, 'message' => 'Thanh toán thành công.', 'code' => '00'];
$success   = $vnpResult['success'] === true;

$vnp_TransactionNo = $_GET['vnp_TransactionNo'] ?? '';
$vnp_BankCode      = $_GET['vnp_BankCode']      ?? '';
$vnp_CardType      = $_GET['vnp_CardType']      ?? '';
$vnp_PayDate       = $_GET['vnp_PayDate']       ?? '';
$vnp_TxnRef        = $_GET['vnp_TxnRef']        ?? '';

$payDateFmt = '';
if ($vnp_PayDate && strlen($vnp_PayDate) === 14) {
    $payDateFmt = substr($vnp_PayDate, 6, 2) . '/' . substr($vnp_PayDate, 4, 2) . '/' . substr($vnp_PayDate, 0, 4)
                . ' ' . substr($vnp_PayDate, 8, 2) . ':' . substr($vnp_PayDate, 10, 2) . ':' . substr($vnp_PayDate, 12, 2);
}
?>

<link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,700;9..144,900&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
    :root{
        --ed-cream:#F5F1EA;--ed-cream-2:#FBF6ED;--ed-paper:#FFFEFB;
        --ed-ink:#2A1F14;--ed-ink-soft:#5C4A33;--ed-rust:#C2410C;--ed-mustard:#CA8A04;--ed-cocoa:#94715A;
    }
    body{background:var(--ed-cream)!important;color:var(--ed-ink)!important;font-family:'Inter',system-ui,sans-serif!important}

    .res-wrap{max-width:680px;margin:60px auto;padding:0 20px}
    .res-card{
        background:var(--ed-paper);border:1px solid rgba(42,31,20,.1);
        border-radius:8px;overflow:hidden;
        box-shadow:0 20px 50px rgba(42,31,20,.08);
        animation:resPop .5s cubic-bezier(.16,1,.3,1);
    }
    @keyframes resPop{from{opacity:0;transform:translateY(20px)}to{opacity:1;transform:none}}

    .res-icon-wrap{
        padding:50px 20px 30px;text-align:center;position:relative;overflow:hidden;
        background:<?= $success
            ? 'linear-gradient(135deg,#D1FAE5 0%,#A7F3D0 100%)'
            : 'linear-gradient(135deg,#FEE2E2 0%,#FECACA 100%)' ?>;
    }
    .res-icon-wrap::before{
        content:"";position:absolute;width:280px;height:280px;border-radius:50%;
        background:<?= $success ? 'rgba(16,185,129,.15)' : 'rgba(239,68,68,.15)' ?>;
        top:-100px;left:50%;transform:translateX(-50%);filter:blur(40px);
    }
    .res-circle{
        position:relative;z-index:2;
        width:96px;height:96px;border-radius:50%;
        background:<?= $success ? '#10B981' : '#EF4444' ?>;
        color:#fff;display:inline-flex;align-items:center;justify-content:center;
        font-size:42px;
        box-shadow:0 12px 32px <?= $success ? 'rgba(16,185,129,.45)' : 'rgba(239,68,68,.45)' ?>;
        animation:resCircle .55s cubic-bezier(.16,1,.3,1);
    }
    @keyframes resCircle{0%{transform:scale(0) rotate(-180deg)}80%{transform:scale(1.15)}100%{transform:scale(1)}}

    .res-body{padding:30px 36px 36px}
    .res-body h2{
        font-family:'Fraunces',serif;font-style:italic;font-weight:900;
        text-align:center;margin:0 0 8px;font-size:30px;letter-spacing:-.5px;
        color:<?= $success ? '#065F46' : '#991B1B' ?>;
    }
    .res-lead{text-align:center;color:var(--ed-ink-soft);margin:0 0 24px;font-size:14.5px;line-height:1.6}
    .res-message{
        text-align:center;padding:11px 14px;border-radius:8px;margin-bottom:22px;
        background:<?= $success ? '#D1FAE5' : '#FEE2E2' ?>;
        color:<?= $success ? '#065F46' : '#991B1B' ?>;
        font-size:13px;font-weight:600;
    }

    .res-info{margin-bottom:24px}
    .res-info-title{
        font-family:'Fraunces',serif;font-style:italic;font-weight:700;
        font-size:16px;color:var(--ed-ink);margin:0 0 12px;
        display:flex;align-items:center;gap:8px;
    }
    .res-info-title i{color:var(--ed-rust);font-size:14px}
    .res-row{
        display:flex;justify-content:space-between;align-items:center;
        padding:10px 0;border-bottom:1px dashed rgba(42,31,20,.1);font-size:13.5px;color:var(--ed-ink-soft);
    }
    .res-row:last-child{border-bottom:0}
    .res-row b{color:var(--ed-ink);font-weight:700}
    .res-row .price{font-family:'Fraunces',serif;font-style:italic;font-weight:800;color:var(--ed-rust);font-size:18px}
    .res-row .mono{font-family:'JetBrains Mono','Courier New',monospace;font-size:12.5px;background:var(--ed-cream-2);padding:2px 8px;border-radius:4px}

    .res-badge{display:inline-flex;align-items:center;gap:6px;padding:4px 10px;border-radius:999px;font-size:11.5px;font-weight:700;letter-spacing:.5px}
    .res-badge.ok{background:#D1FAE5;color:#065F46}
    .res-badge.no{background:#FEE2E2;color:#991B1B}

    .res-actions{display:flex;gap:10px;margin-top:8px;flex-wrap:wrap}
    .res-btn{
        flex:1;min-width:140px;padding:12px 18px;border-radius:6px;
        font-size:12px;font-weight:700;letter-spacing:1px;text-transform:uppercase;
        text-decoration:none;display:inline-flex;align-items:center;justify-content:center;gap:8px;
        transition:all .2s;font-family:inherit;
    }
    .res-btn.primary{background:var(--ed-ink);color:#fff;border:1.5px solid var(--ed-ink)}
    .res-btn.primary:hover{background:var(--ed-rust);border-color:var(--ed-rust);color:#fff}
    .res-btn.outline{background:transparent;color:var(--ed-ink);border:1.5px solid var(--ed-ink)}
    .res-btn.outline:hover{background:var(--ed-ink);color:#fff}
    .res-btn.retry{background:var(--ed-rust);color:#fff;border:1.5px solid var(--ed-rust)}
    .res-btn.retry:hover{background:#9A330A;color:#fff}

    .vnp-footer{
        text-align:center;margin-top:18px;padding-top:18px;
        border-top:1px dashed rgba(42,31,20,.1);
        font-size:11px;color:var(--ed-cocoa);letter-spacing:.5px;
    }
    .vnp-footer i{color:#10B981;margin-right:4px}
</style>

<div class="res-wrap">
    <div class="res-card">
        <div class="res-icon-wrap">
            <div class="res-circle">
                <i class="fas <?= $success ? 'fa-check' : 'fa-times' ?>"></i>
            </div>
        </div>

        <div class="res-body">
            <?php if ($success): ?>
                <h2>Thanh toán thành công!</h2>
                <p class="res-lead">Cảm ơn bạn đã mua hàng tại HDTT Store.<br>Đơn hàng của bạn đang được xử lý.</p>
            <?php else: ?>
                <h2>Thanh toán không thành công</h2>
                <p class="res-lead">Giao dịch của bạn không hoàn tất.<br>Bạn có thể thử thanh toán lại hoặc chọn phương thức khác.</p>
            <?php endif; ?>

            <div class="res-message">
                <i class="fas fa-<?= $success ? 'info-circle' : 'exclamation-triangle' ?>"></i>
                <?= htmlspecialchars($vnpResult['message']) ?>
            </div>

            <div class="res-info">
                <h3 class="res-info-title"><i class="fas fa-receipt"></i> Thông tin đơn hàng</h3>
                <div class="res-row"><span>Mã đơn hàng</span><b>#<?= (int)$order['id'] ?></b></div>
                <div class="res-row"><span>Người nhận</span><b><?= htmlspecialchars($order['receiver_name']) ?></b></div>
                <div class="res-row"><span>Tổng tiền</span><b class="price"><?= number_format($order['total']) ?>đ</b></div>
                <div class="res-row">
                    <span>Trạng thái thanh toán</span>
                    <span class="res-badge <?= $success ? 'ok' : 'no' ?>">
                        <i class="fas fa-<?= $success ? 'check-circle' : 'times-circle' ?>"></i>
                        <?= $success ? 'Đã thanh toán' : 'Chưa thanh toán' ?>
                    </span>
                </div>
            </div>

            <?php if ($vnp_TransactionNo || $vnp_BankCode || $vnp_TxnRef): ?>
            <div class="res-info">
                <h3 class="res-info-title"><i class="fas fa-shield-alt"></i> Chi tiết giao dịch VNPAY</h3>
                <?php if ($vnp_TxnRef): ?>
                    <div class="res-row"><span>Mã tham chiếu</span><span class="mono"><?= htmlspecialchars($vnp_TxnRef) ?></span></div>
                <?php endif; ?>
                <?php if ($vnp_TransactionNo): ?>
                    <div class="res-row"><span>Mã giao dịch VNPAY</span><span class="mono"><?= htmlspecialchars($vnp_TransactionNo) ?></span></div>
                <?php endif; ?>
                <?php if ($vnp_BankCode): ?>
                    <div class="res-row"><span>Ngân hàng</span><b><?= htmlspecialchars($vnp_BankCode) ?></b></div>
                <?php endif; ?>
                <?php if ($vnp_CardType): ?>
                    <div class="res-row"><span>Loại thẻ</span><b><?= htmlspecialchars($vnp_CardType) ?></b></div>
                <?php endif; ?>
                <?php if ($payDateFmt): ?>
                    <div class="res-row"><span>Thời gian</span><b><?= htmlspecialchars($payDateFmt) ?></b></div>
                <?php endif; ?>
                <?php if (!empty($vnpResult['code'])): ?>
                    <div class="res-row"><span>Mã phản hồi</span><span class="mono"><?= htmlspecialchars($vnpResult['code']) ?></span></div>
                <?php endif; ?>
            </div>
            <?php endif; ?>

            <div class="res-actions">
                <?php if ($success): ?>
                    <a href="/Duan1/giaodien/duaan1-giaodien/mvc-oop-basic/index.php?act=orderDetail&id=<?= (int)$order['id'] ?>" class="res-btn primary">
                        <i class="fas fa-receipt"></i> Xem đơn
                    </a>
                    <a href="/Duan1/giaodien/duaan1-giaodien/mvc-oop-basic/index.php?act=giaodien" class="res-btn outline">
                        <i class="fas fa-shopping-bag"></i> Tiếp tục mua
                    </a>
                <?php else: ?>
                    <a href="/Duan1/giaodien/duaan1-giaodien/mvc-oop-basic/index.php?act=payGateway&order_id=<?= (int)$order['id'] ?>" class="res-btn retry">
                        <i class="fas fa-redo"></i> Thử lại
                    </a>
                    <a href="/Duan1/giaodien/duaan1-giaodien/mvc-oop-basic/index.php?act=myOrders" class="res-btn outline">
                        <i class="fas fa-box"></i> Đơn hàng của tôi
                    </a>
                <?php endif; ?>
            </div>

            <div class="vnp-footer">
                <i class="fas fa-shield-alt"></i> Bảo mật bởi VNPAY · Chữ ký HMAC-SHA512 đã xác thực
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/_footer.php'; ?>