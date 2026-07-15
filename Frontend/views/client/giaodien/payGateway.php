<?php
$pageTitle = 'Thanh toán đơn hàng #' . $order['id'];
$activeNav = 'orders';
require_once __DIR__ . '/_header.php';

// Tính thời gian còn lại (server-side) để countdown không reset khi reload
// Mốc: created_at của đơn + 30 phút (khớp vnp_ExpireDate)
$payWindowSec = 30 * 60;
$createdAt    = !empty($order['created_at']) ? strtotime($order['created_at']) : time();
$expireTs     = $createdAt + $payWindowSec;
$remainSec    = max(0, $expireTs - time());
$isExpired    = $remainSec <= 0;
?>

<link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,700;9..144,900&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
    :root{
        --ed-cream:#F5F1EA;--ed-cream-2:#FBF6ED;--ed-cream-3:#F5E9D8;
        --ed-paper:#FFFEFB;--ed-ink:#2A1F14;--ed-ink-soft:#5C4A33;
        --ed-rust:#C2410C;--ed-mustard:#CA8A04;--ed-cocoa:#94715A;
        --vnp-blue:#005baa;--vnp-blue-2:#00a4e4;
    }
    body{background:var(--ed-cream)!important;color:var(--ed-ink)!important;font-family:'Inter',system-ui,sans-serif!important}

    .pay-wrap{max-width:1000px;margin:0 auto;padding:50px 20px}
    .pay-head{margin-bottom:32px;text-align:center}
    .pay-eyebrow{
        display:inline-flex;align-items:center;gap:8px;
        padding:6px 14px;border-radius:999px;
        background:var(--ed-ink);color:var(--ed-cream-2);
        font-size:11.5px;font-weight:700;letter-spacing:2px;text-transform:uppercase;margin-bottom:14px;
    }
    .pay-title{font-family:'Fraunces',serif;font-style:italic;font-weight:900;font-size:42px;letter-spacing:-1px;color:var(--ed-ink);margin:0}
    .pay-title span{color:var(--ed-rust)}
    .pay-sub{font-family:'Fraunces',serif;font-style:italic;color:var(--ed-ink-soft);margin-top:6px;font-size:15px}

    .pay-grid{display:grid;grid-template-columns:1.2fr 1fr;gap:24px}
    @media(max-width:900px){.pay-grid{grid-template-columns:1fr}}

    .pay-section{
        background:var(--ed-paper);border:1px solid rgba(42,31,20,.1);
        border-radius:8px;overflow:hidden;
    }
    .pay-section-head{
        padding:18px 24px;border-bottom:1px solid rgba(42,31,20,.08);background:var(--ed-cream-2);
    }
    .pay-section-head h3{
        font-family:'Fraunces',serif;font-style:italic;font-weight:700;
        font-size:20px;margin:0;color:var(--ed-ink);
        display:flex;align-items:center;gap:10px;
    }
    .pay-section-head h3 i{color:var(--ed-rust)}

    .pay-section-body{padding:22px 24px}

    /* Order summary */
    .pay-row{
        display:flex;justify-content:space-between;align-items:center;
        padding:11px 0;border-bottom:1px dashed rgba(42,31,20,.1);
        font-size:14px;color:var(--ed-ink-soft);
    }
    .pay-row:last-child{border-bottom:0}
    .pay-row b{color:var(--ed-ink);font-weight:700}
    .pay-total-row{
        margin-top:8px;padding-top:14px;border-top:2px solid var(--ed-ink);
        display:flex;justify-content:space-between;align-items:baseline;
    }
    .pay-total-label{font-family:'Fraunces',serif;font-style:italic;font-size:18px;color:var(--ed-ink);font-weight:700}
    .pay-total-num{font-family:'Fraunces',serif;font-weight:900;font-size:30px;color:var(--ed-rust);letter-spacing:-1px}

    /* Items list */
    .pay-items{margin-top:18px}
    .pay-item{
        display:grid;grid-template-columns:60px 1fr auto;gap:12px;
        padding:10px 0;border-bottom:1px dashed rgba(42,31,20,.08);align-items:center;
    }
    .pay-item:last-child{border-bottom:0}
    .pay-item img{width:60px;height:60px;object-fit:cover;border-radius:6px;background:var(--ed-cream-2)}
    .pay-item-name{font-size:13.5px;font-weight:600;color:var(--ed-ink);margin-bottom:2px;line-height:1.3}
    .pay-item-meta{font-size:11.5px;color:var(--ed-cocoa)}
    .pay-item-price{font-family:'Fraunces',serif;font-style:italic;font-weight:800;color:var(--ed-rust);font-size:14px;text-align:right}

    /* VNPAY card */
    .vnp-brand{
        display:flex;align-items:center;gap:14px;
        padding:18px;border-radius:8px;
        background:linear-gradient(135deg,var(--vnp-blue) 0%,var(--vnp-blue-2) 100%);
        color:#fff;margin-bottom:16px;
    }
    .vnp-brand-logo{
        width:54px;height:54px;border-radius:10px;
        background:rgba(255,255,255,.2);backdrop-filter:blur(10px);
        display:flex;align-items:center;justify-content:center;
        font-weight:900;font-size:22px;letter-spacing:-1px;flex-shrink:0;
    }
    .vnp-brand-info strong{display:block;font-size:18px;font-weight:800;letter-spacing:-.3px}
    .vnp-brand-info small{display:block;font-size:12px;opacity:.92;margin-top:2px}

    .pay-banks{
        display:flex;flex-wrap:wrap;gap:8px;margin-bottom:16px;
        padding:14px;background:var(--ed-cream-2);border-radius:8px;border:1px dashed rgba(42,31,20,.12);
    }
    .pay-banks-label{
        width:100%;font-size:11px;font-weight:700;letter-spacing:1px;color:var(--ed-cocoa);
        text-transform:uppercase;margin-bottom:6px;
    }
    .pay-banks .bank{
        padding:5px 11px;border-radius:4px;background:#fff;
        font-size:11.5px;font-weight:700;color:var(--ed-ink);letter-spacing:.3px;
        border:1px solid rgba(42,31,20,.08);
    }

    .vnp-btn{
        width:100%;padding:16px;border:0;border-radius:6px;
        background:linear-gradient(135deg,var(--vnp-blue),var(--vnp-blue-2));
        color:#fff;font-size:13.5px;font-weight:700;letter-spacing:1.5px;text-transform:uppercase;
        cursor:pointer;font-family:inherit;
        display:inline-flex;align-items:center;justify-content:center;gap:10px;
        box-shadow:0 12px 28px rgba(0,91,170,.3);transition:all .25s;
    }
    .vnp-btn:hover{transform:translateY(-2px);box-shadow:0 16px 36px rgba(0,91,170,.45)}

    .vnp-info{
        margin-top:14px;padding:14px;
        background:#FEF3C7;border:1px dashed #F59E0B;border-radius:8px;
        font-size:12.5px;color:#92400E;line-height:1.5;
    }
    .vnp-info i{color:#D97706;margin-right:5px}
    .vnp-info code{background:#FFFEFB;padding:1px 6px;border-radius:4px;font-size:11.5px;color:#92400E}

    .vnp-secure{
        text-align:center;margin-top:14px;padding-top:14px;border-top:1px dashed rgba(42,31,20,.1);
        font-size:11.5px;color:var(--ed-cocoa);
    }
    .vnp-secure i{color:#10b981;margin-right:4px}

    .pay-cancel{
        display:inline-flex;align-items:center;gap:6px;
        margin-top:14px;padding:9px 18px;border-radius:6px;
        background:transparent;color:var(--ed-ink-soft);
        border:1px solid rgba(42,31,20,.15);
        font-size:12px;font-weight:600;letter-spacing:.5px;
        text-decoration:none;transition:all .2s;
    }
    .pay-cancel:hover{background:var(--ed-cream-2);color:var(--ed-ink)}

    .countdown-bar{
        display:inline-flex;align-items:center;gap:6px;
        padding:5px 12px;border-radius:999px;
        background:#FEE2E2;color:#991B1B;
        font-size:12px;font-weight:700;letter-spacing:.5px;
        margin-top:10px;
    }
</style>

<div class="pay-wrap">
    <div class="pay-head">
        <span class="pay-eyebrow"><i class="fas fa-credit-card"></i> Thanh toán an toàn</span>
        <h1 class="pay-title">Hoàn tất <span>thanh toán</span></h1>
        <p class="pay-sub">Đơn hàng #<?= (int)$order['id'] ?> · Cổng thanh toán VNPAY Sandbox</p>
        <div class="countdown-bar" id="cdBar">
            <i class="far fa-clock"></i>
            <?php if ($isExpired): ?>
                <span>Phiên thanh toán đã hết hạn — vui lòng đặt đơn mới</span>
            <?php else: ?>
                Phiên thanh toán hết hạn sau <span id="cd"><?= sprintf('%02d:%02d', floor($remainSec / 60), $remainSec % 60) ?></span>
            <?php endif; ?>
        </div>
    </div>

    <div class="pay-grid">
        <!-- LEFT: Order summary -->
        <div class="pay-section">
            <div class="pay-section-head">
                <h3><i class="fas fa-receipt"></i> Chi tiết đơn hàng #<?= (int)$order['id'] ?></h3>
            </div>
            <div class="pay-section-body">
                <div class="pay-row"><span><i class="fas fa-user me-2"></i>Người nhận</span><b><?= htmlspecialchars($order['receiver_name']) ?></b></div>
                <div class="pay-row"><span><i class="fas fa-phone me-2"></i>Số điện thoại</span><b><?= htmlspecialchars($order['receiver_phone']) ?></b></div>
                <div class="pay-row"><span><i class="fas fa-map-marker-alt me-2"></i>Địa chỉ</span><b style="text-align:right;max-width:60%"><?= htmlspecialchars($order['receiver_address']) ?></b></div>

                <?php if (!empty($orderDetails)): ?>
                <div class="pay-items">
                    <?php foreach ($orderDetails as $it): ?>
                        <div class="pay-item">
                            <img loading="lazy" src="<?= BASE_PATH ?>uploads/<?= htmlspecialchars($it['image']) ?>" alt="">
                            <div>
                                <div class="pay-item-name"><?= htmlspecialchars($it['product_name']) ?></div>
                                <div class="pay-item-meta">
                                    <?= htmlspecialchars($it['color_name'] ?? '') ?> · Size <?= htmlspecialchars($it['size_name'] ?? '') ?>
                                    · SL <?= (int)$it['quantity'] ?>
                                </div>
                            </div>
                            <div class="pay-item-price"><?= number_format($it['price'] * $it['quantity']) ?>đ</div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <div class="pay-row" style="margin-top:14px"><span>Phí vận chuyển</span><b><?= number_format($order['shipping_fee']) ?>đ</b></div>

                <div class="pay-total-row">
                    <span class="pay-total-label">Tổng thanh toán</span>
                    <span class="pay-total-num"><?= number_format($order['total']) ?>đ</span>
                </div>
            </div>
        </div>

        <!-- RIGHT: VNPAY -->
        <div class="pay-section">
            <div class="pay-section-head">
                <h3><i class="fas fa-shield-alt"></i> Phương thức thanh toán</h3>
            </div>
            <div class="pay-section-body">
                <div class="vnp-brand">
                    <div class="vnp-brand-logo">VNP</div>
                    <div class="vnp-brand-info">
                        <strong>VNPAY</strong>
                        <small>Cổng thanh toán điện tử</small>
                    </div>
                </div>

                <div class="pay-banks">
                    <span class="pay-banks-label">Hỗ trợ ngân hàng & ví</span>
                    <span class="bank">VietcomBank</span>
                    <span class="bank">VietinBank</span>
                    <span class="bank">BIDV</span>
                    <span class="bank">Agribank</span>
                    <span class="bank">Techcombank</span>
                    <span class="bank">VPBank</span>
                    <span class="bank">MBBank</span>
                    <span class="bank">ACB</span>
                    <span class="bank">Visa/Master</span>
                    <span class="bank">QR Pay</span>
                </div>

                <form method="POST" action="<?= BASE_PATH ?>index.php?act=vnpayCreate">
                        <?= csrf_field() ?>
                    <input type="hidden" name="order_id" value="<?= (int)$order['id'] ?>">
                    <button type="submit" class="vnp-btn" id="vnpBtn" <?= $isExpired ? 'disabled' : '' ?>>
                        <i class="fas fa-lock"></i>
                        <?php if ($isExpired): ?>
                            Phiên đã hết hạn
                        <?php else: ?>
                            Thanh toán <?= number_format($order['total']) ?>đ qua VNPAY
                        <?php endif; ?>
                    </button>
                </form>

                <div class="vnp-info">
                    <i class="fas fa-info-circle"></i>
                    <b>Môi trường Sandbox (test):</b> Khi vào trang VNPAY chọn <b>"Thẻ ATM nội địa - NCB"</b>, nhập:<br>
                    Số thẻ: <code>9704198526191432198</code><br>
                    Tên chủ thẻ: <code>NGUYEN VAN A</code><br>
                    Ngày phát hành: <code>07/15</code> · OTP: <code>123456</code>
                </div>

                <div class="vnp-secure">
                    <i class="fas fa-shield-alt"></i> Giao dịch bảo mật bởi VNPAY · Chữ ký HMAC-SHA512
                </div>

                <div style="text-align:center">
                    <a href="<?= BASE_PATH ?>index.php?act=myOrders" class="pay-cancel">
                        <i class="fas fa-times"></i> Hủy giao dịch
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
(function(){
    // Anchor: thời điểm hết hạn từ server (Unix timestamp ms)
    const expireMs = <?= $expireTs ?> * 1000;
    const cd       = document.getElementById('cd');
    const bar      = document.getElementById('cdBar');
    const btn      = document.getElementById('vnpBtn');
    if (!cd) return;
    const tick = () => {
        const remain = Math.max(0, Math.floor((expireMs - Date.now()) / 1000));
        if (remain <= 0) {
            bar.innerHTML = '<i class="far fa-clock"></i> Phiên thanh toán đã hết hạn — vui lòng đặt đơn mới';
            bar.style.background = '#FEE2E2';
            bar.style.color = '#991B1B';
            if (btn) {
                btn.disabled = true;
                btn.innerHTML = '<i class="fas fa-lock"></i> Phiên đã hết hạn';
            }
            clearInterval(timerId);
            return;
        }
        const m = String(Math.floor(remain / 60)).padStart(2, '0');
        const s = String(remain % 60).padStart(2, '0');
        cd.textContent = m + ':' + s;
        // Khi còn dưới 5 phút, đổi cảnh báo sang đỏ
        if (remain < 5 * 60) {
            bar.style.background = '#FEE2E2';
            bar.style.color = '#991B1B';
        }
    };
    tick();
    const timerId = setInterval(tick, 1000);
})();
</script>

<?php require_once __DIR__ . '/_dark_editorial.php'; ?>
<?php require_once __DIR__ . '/_footer.php'; ?>
