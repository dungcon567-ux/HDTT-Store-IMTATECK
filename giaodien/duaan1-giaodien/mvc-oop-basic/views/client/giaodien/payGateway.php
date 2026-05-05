<?php
$gates = [
    'momo'    => ['name' => 'MoMo',    'color' => '#a50064', 'color2' => '#d82d8b', 'logo' => 'M', 'icon' => null,           'tagline' => 'Quét QR bằng app MoMo'],
    'zalopay' => ['name' => 'ZaloPay', 'color' => '#0068ff', 'color2' => '#00b9ff', 'logo' => 'Z', 'icon' => null,           'tagline' => 'Quét QR bằng app ZaloPay hoặc ngân hàng'],
    'vnpay'   => ['name' => 'VNPay',   'color' => '#005baa', 'color2' => '#00a4e4', 'logo' => null,'icon' => 'fa-credit-card','tagline' => 'Quét QR NAPAS 247 bằng app ngân hàng'],
];
$g = $gates[$order['payment_method']] ?? $gates['momo'];

// Thông tin tài khoản nhận
$accountName    = 'DO VIET DUNG';
$accountNoFull  = 'PSP2604910500000353';
$accountNoMask  = '*******353';
$transferNote   = 'HDTT' . $order['id'];
$amount         = (int)$order['total'];

// Chọn nguồn QR theo cổng thanh toán
$method = $order['payment_method'];

$qrStaticPath = __DIR__ . '/../../../uploads/qr_zalopay.png';
$hasZaloFile = file_exists($qrStaticPath);

if ($method === 'zalopay' && $hasZaloFile) {
    $qrImage = '/Duan1/giaodien/duaan1-giaodien/mvc-oop-basic/uploads/qr_zalopay.png';
} else {
    // Fallback / mặc định: VietQR động (NAPAS 247 - app nào cũng quét được)
    $qrImage = "https://img.vietqr.io/image/MOMO-{$accountNoFull}-compact2.png"
             . '?amount=' . $amount
             . '&addInfo=' . urlencode($transferNote)
             . '&accountName=' . urlencode($accountName);
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Cổng thanh toán <?= htmlspecialchars($g['name']) ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css">
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        *{box-sizing:border-box}
        body{
            margin:0;font-family:'Manrope',sans-serif;
            min-height:100vh;
            background:linear-gradient(135deg,<?= $g['color'] ?>,<?= $g['color2'] ?>);
            display:flex;align-items:center;justify-content:center;
            padding:30px 20px;
        }
        .gateway{
            width:100%;max-width:480px;
            background:#fff;border-radius:24px;
            box-shadow:0 20px 60px rgba(0,0,0,.3);
            overflow:hidden;
            animation:slideUp .5s ease;
        }
        @keyframes slideUp{from{opacity:0;transform:translateY(30px)}to{opacity:1;transform:translateY(0)}}
        .gateway-head{
            background:linear-gradient(135deg,<?= $g['color'] ?>,<?= $g['color2'] ?>);
            color:#fff;padding:22px;text-align:center;
        }
        .logo-box{
            width:60px;height:60px;border-radius:14px;
            background:rgba(255,255,255,.18);
            display:inline-flex;align-items:center;justify-content:center;
            font-size:26px;font-weight:800;margin-bottom:8px;
            backdrop-filter:blur(10px);
        }
        .gateway-head h2{margin:6px 0 4px;font-size:20px;font-weight:800}
        .gateway-head p{margin:0;opacity:.92;font-size:12.5px}
        .countdown{
            display:inline-flex;align-items:center;gap:6px;
            margin-top:10px;background:rgba(255,255,255,.2);
            padding:5px 12px;border-radius:999px;font-size:12.5px;font-weight:600;
        }
        .gateway-body{padding:22px}
        .qr-real{
            margin:0 auto;width:240px;height:240px;
            border:3px solid #fff;border-radius:18px;
            box-shadow:0 6px 20px rgba(0,0,0,.08);
            background:#fff;padding:8px;display:flex;align-items:center;justify-content:center;
        }
        .qr-real img{width:100%;height:100%;object-fit:contain;border-radius:8px}
        .qr-fallback{
            width:100%;height:100%;
            background:repeating-linear-gradient(45deg,#fff,#fff 8px,#f3f4f6 8px,#f3f4f6 16px);
            display:flex;flex-direction:column;align-items:center;justify-content:center;
            border-radius:8px;color:#94a3b8;font-size:11px;text-align:center;padding:10px;
        }
        .qr-fallback i{font-size:50px;color:<?= $g['color'] ?>;opacity:.6;margin-bottom:6px}

        .partners{
            display:flex;align-items:center;justify-content:center;gap:14px;
            margin:14px 0 6px;opacity:.85;
        }
        .partners img{height:18px}
        .partners .sep{width:1px;height:14px;background:#e5e7eb}

        .acct-info{
            background:linear-gradient(135deg,#fff7f9,#ffe4ec);
            border:1px solid #fbcfe8;
            border-radius:14px;padding:14px;margin-top:8px;
        }
        .acct-info .name{font-weight:800;font-size:15px;color:#831843;margin-bottom:2px}
        .acct-info .stk{font-size:13px;color:#9d174d;font-family:monospace;letter-spacing:1px}

        .order-summary{
            margin-top:14px;background:#f8fafc;border-radius:14px;padding:14px;
        }
        .row-line{display:flex;justify-content:space-between;font-size:13px;margin-bottom:5px;color:#475569}
        .row-line:last-child{margin-bottom:0}
        .row-line strong{color:#0f172a}
        .row-note{
            background:#fef3c7;border:1px dashed #f59e0b;border-radius:10px;
            padding:10px;margin-top:10px;
            font-size:12.5px;color:#92400e;
        }
        .row-note .copy-row{
            display:flex;align-items:center;gap:8px;margin-top:6px;
            background:#fff;border-radius:8px;padding:7px 10px;font-family:monospace;font-weight:700;color:#0f172a;
        }
        .copy-btn{
            margin-left:auto;background:<?= $g['color'] ?>;color:#fff;border:0;
            padding:4px 10px;border-radius:6px;font-size:11px;font-weight:700;cursor:pointer;
        }
        .total-line{
            margin-top:8px;padding-top:8px;border-top:1px solid #e5e7eb;
            font-size:17px;font-weight:800;
        }
        .total-line strong{color:<?= $g['color'] ?>}

        .btn-pay{
            width:100%;margin-top:14px;
            background:linear-gradient(135deg,<?= $g['color'] ?>,<?= $g['color2'] ?>);
            color:#fff;border:0;
            padding:13px;border-radius:12px;
            font-size:14.5px;font-weight:700;cursor:pointer;
            box-shadow:0 10px 24px rgba(0,0,0,.15);
            transition:.2s;
        }
        .btn-pay:hover{transform:translateY(-2px);box-shadow:0 14px 30px rgba(0,0,0,.22)}
        .btn-cancel{
            width:100%;margin-top:8px;
            background:transparent;color:#64748b;border:1px solid #e5e7eb;
            padding:10px;border-radius:12px;font-size:12.5px;font-weight:600;cursor:pointer;
            transition:.2s;
        }
        .btn-cancel:hover{background:#f1f5f9;color:#0f172a}
        .secure-line{margin-top:12px;text-align:center;color:#94a3b8;font-size:11px}
        .secure-line i{color:<?= $g['color'] ?>}
    </style>
</head>
<body>

<div class="gateway">
    <div class="gateway-head">
        <div class="logo-box">
            <?php if (!empty($g['icon'])): ?>
                <i class="fas <?= $g['icon'] ?>"></i>
            <?php else: ?>
                <?= htmlspecialchars($g['logo']) ?>
            <?php endif; ?>
        </div>
        <h2>Cổng thanh toán <?= htmlspecialchars($g['name']) ?></h2>
        <p><?= htmlspecialchars($g['tagline']) ?></p>
        <div class="countdown">
            <i class="far fa-clock"></i> <span id="cd">15:00</span>
        </div>
    </div>

    <div class="gateway-body">
        <div class="qr-real">
            <img src="<?= $qrImage ?>"
                 alt="QR thanh toán"
                 onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">
            <div class="qr-fallback" style="display:none">
                <i class="fas fa-qrcode"></i>
                <span>Không tải được QR<br>Vui lòng kiểm tra mạng</span>
            </div>
        </div>

        <div class="partners">
            <span style="font-weight:800;color:#a50064">momo</span>
            <span class="sep"></span>
            <span style="font-weight:800;color:#dc2626">VietQR</span>
            <span class="sep"></span>
            <span style="font-weight:800;color:#0068ff">napas 24/7</span>
        </div>

        <div class="acct-info">
            <div class="name">ĐỖ VIẾT DŨNG</div>
            <div class="stk">STK MoMo: <?= htmlspecialchars($accountNoMask) ?></div>
        </div>

        <div class="order-summary">
            <div class="row-line"><span>Đơn hàng</span><strong>#<?= (int)$order['id'] ?></strong></div>
            <div class="row-line"><span>Người nhận</span><strong><?= htmlspecialchars($order['receiver_name']) ?></strong></div>
            <div class="row-line"><span>Phương thức</span><strong><?= htmlspecialchars($g['name']) ?></strong></div>
            <div class="row-line total-line"><span>Số tiền</span><strong><?= number_format($order['total']) ?>đ</strong></div>

            <div class="row-note">
                <div><i class="fas fa-pen me-1"></i> <b>Nội dung chuyển khoản:</b></div>
                <div class="copy-row">
                    <span id="note"><?= htmlspecialchars($transferNote) ?></span>
                    <button type="button" class="copy-btn" onclick="copyNote()">
                        <i class="far fa-copy"></i> Copy
                    </button>
                </div>
                <div style="margin-top:6px;font-size:11px">Vui lòng ghi đúng nội dung này để hệ thống đối soát.</div>
            </div>
        </div>

        <form method="POST" action="/Duan1/giaodien/duaan1-giaodien/mvc-oop-basic/index.php?act=payConfirm">
            <input type="hidden" name="order_id" value="<?= (int)$order['id'] ?>">
            <input type="hidden" name="action" value="success">
            <button type="submit" class="btn-pay" onclick="return confirm('Bạn đã chuyển khoản thành công?')">
                <i class="fas fa-check-circle me-2"></i> Tôi đã chuyển khoản
            </button>
        </form>

        <form method="POST" action="/Duan1/giaodien/duaan1-giaodien/mvc-oop-basic/index.php?act=payConfirm">
            <input type="hidden" name="order_id" value="<?= (int)$order['id'] ?>">
            <input type="hidden" name="action" value="cancel">
            <button type="submit" class="btn-cancel">
                <i class="fas fa-times me-1"></i> Hủy giao dịch
            </button>
        </form>

        <div class="secure-line">
            <i class="fas fa-shield-alt"></i> Bảo mật bởi NAPAS 247 · Quét bằng MoMo / Banking app
        </div>
    </div>
</div>

<script>
function copyNote() {
    const t = document.getElementById('note').innerText;
    navigator.clipboard.writeText(t);
    event.target.innerHTML = '<i class="fas fa-check"></i> Đã copy';
    setTimeout(() => event.target.innerHTML = '<i class="far fa-copy"></i> Copy', 1500);
}
let secs = 15*60;
const cd = document.getElementById('cd');
setInterval(() => {
    if (secs <= 0) { cd.textContent = 'Hết hạn'; return; }
    secs--;
    const m = String(Math.floor(secs/60)).padStart(2,'0');
    const s = String(secs%60).padStart(2,'0');
    cd.textContent = `${m}:${s}`;
}, 1000);
</script>
</body>
</html>
