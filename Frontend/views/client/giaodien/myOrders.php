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

function badgeOrderEd($status) {
    return match ($status) {
        'cho_xac_nhan'    => ['bg'=>'#FEF3C7','color'=>'#92400E','icon'=>'clock'],
        'da_dat_hang'     => ['bg'=>'#DBEAFE','color'=>'#1E40AF','icon'=>'check-circle'],
        'dang_lay_hang'   => ['bg'=>'#E0E7FF','color'=>'#3730A3','icon'=>'box'],
        'dang_van_chuyen' => ['bg'=>'#CFFAFE','color'=>'#155E75','icon'=>'truck'],
        'da_van_chuyen'   => ['bg'=>'#CFFAFE','color'=>'#155E75','icon'=>'truck-loading'],
        'hoan_thanh'      => ['bg'=>'#D1FAE5','color'=>'#065F46','icon'=>'check-double'],
        'da_huy'          => ['bg'=>'#FEE2E2','color'=>'#991B1B','icon'=>'times-circle'],
        default           => ['bg'=>'#F1F5F9','color'=>'#475569','icon'=>'question-circle'],
    };
}

function badgePaymentEd($paymentStatus) {
    return match ($paymentStatus) {
        'paid'           => ['bg'=>'#D1FAE5','color'=>'#065F46','icon'=>'check'],
        'dang_hoan_tien' => ['bg'=>'#FEF3C7','color'=>'#92400E','icon'=>'sync'],
        'da_hoan_tien'   => ['bg'=>'#DBEAFE','color'=>'#1E40AF','icon'=>'undo'],
        default          => ['bg'=>'#FEE2E2','color'=>'#991B1B','icon'=>'exclamation'],
    };
}

// Thống kê
$statCount = ['total'=>count($orders ?? []),'pending'=>0,'shipping'=>0,'done'=>0,'spent'=>0];
foreach ($orders ?? [] as $o) {
    if (in_array($o['status'], ['cho_xac_nhan','da_dat_hang','dang_lay_hang'], true)) $statCount['pending']++;
    if (in_array($o['status'], ['dang_van_chuyen','da_van_chuyen'], true)) $statCount['shipping']++;
    if ($o['status'] === 'hoan_thanh') $statCount['done']++;
    if ($o['payment_status'] === 'paid') $statCount['spent'] += (int)$o['total'];
}

$pageTitle = 'Đơn hàng của tôi';
$activeNav = 'orders';
require_once __DIR__ . '/_header.php';
?>

<link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,700;9..144,900&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
    :root{
        --ed-cream:#F5F1EA;--ed-cream-2:#FBF6ED;--ed-cream-3:#F5E9D8;
        --ed-paper:#FFFEFB;--ed-ink:#2A1F14;--ed-ink-soft:#5C4A33;
        --ed-rust:#C2410C;--ed-mustard:#CA8A04;--ed-cocoa:#94715A;
    }
    body{background:var(--ed-cream)!important;color:var(--ed-ink)!important;font-family:'Inter',system-ui,sans-serif!important}

    .ord-wrap{max-width:1200px;margin:0 auto;padding:50px 20px}
    .ord-head{margin-bottom:32px;text-align:center}
    .ord-eyebrow{
        display:inline-flex;align-items:center;gap:8px;
        padding:6px 14px;border-radius:999px;
        background:var(--ed-ink);color:var(--ed-cream-2);
        font-size:11.5px;font-weight:700;letter-spacing:2px;text-transform:uppercase;margin-bottom:14px;
    }
    .ord-title{font-family:'Fraunces',serif;font-style:italic;font-weight:900;font-size:42px;letter-spacing:-1px;color:var(--ed-ink);margin:0}
    .ord-title span{color:var(--ed-rust)}
    .ord-sub{font-family:'Fraunces',serif;font-style:italic;color:var(--ed-ink-soft);margin-top:6px;font-size:15px}

    .ord-stats{display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin:30px 0}
    @media(max-width:768px){.ord-stats{grid-template-columns:repeat(2,1fr)}}
    .ord-stat{
        background:var(--ed-paper);border:1px solid rgba(42,31,20,.1);
        border-radius:8px;padding:18px 20px;display:flex;align-items:center;gap:14px;
    }
    .ord-stat-icon{
        width:48px;height:48px;border-radius:10px;flex-shrink:0;
        display:flex;align-items:center;justify-content:center;font-size:18px;color:#fff;
    }
    .ord-stat-icon.c1{background:var(--ed-ink)}
    .ord-stat-icon.c2{background:var(--ed-mustard)}
    .ord-stat-icon.c3{background:var(--ed-rust)}
    .ord-stat-icon.c4{background:var(--ed-cocoa)}
    .ord-stat-num{font-family:'Fraunces',serif;font-style:italic;font-weight:900;font-size:24px;color:var(--ed-ink);line-height:1}
    .ord-stat-lbl{font-size:11.5px;color:var(--ed-ink-soft);font-weight:600;letter-spacing:.5px;text-transform:uppercase;margin-top:4px}

    .ord-toolbar{
        display:flex;justify-content:space-between;align-items:center;
        margin-bottom:18px;flex-wrap:wrap;gap:12px;
    }
    .ord-toolbar h3{
        font-family:'Fraunces',serif;font-style:italic;font-weight:700;
        font-size:22px;color:var(--ed-ink);margin:0;
    }

    .ord-list{display:flex;flex-direction:column;gap:14px}
    .ord-card{
        background:var(--ed-paper);border:1px solid rgba(42,31,20,.1);
        border-radius:8px;overflow:hidden;transition:all .2s;
    }
    .ord-card:hover{border-color:var(--ed-rust);box-shadow:0 12px 28px rgba(42,31,20,.08)}

    .ord-card-head{
        display:flex;justify-content:space-between;align-items:center;
        padding:14px 22px;background:var(--ed-cream-2);
        border-bottom:1px solid rgba(42,31,20,.06);flex-wrap:wrap;gap:10px;
    }
    .ord-card-id{
        font-family:'Fraunces',serif;font-style:italic;font-weight:800;
        font-size:18px;color:var(--ed-ink);
    }
    .ord-card-id i{color:var(--ed-rust);margin-right:4px}
    .ord-card-badges{display:flex;gap:8px;flex-wrap:wrap}
    .ord-badge{
        display:inline-flex;align-items:center;gap:6px;
        padding:5px 12px;border-radius:999px;
        font-size:11.5px;font-weight:700;letter-spacing:.4px;
    }

    .ord-card-body{
        display:grid;grid-template-columns:1fr 1fr;gap:20px;padding:18px 22px;
    }
    @media(max-width:640px){.ord-card-body{grid-template-columns:1fr}}
    .ord-info-block{}
    .ord-info-label{
        font-size:10.5px;font-weight:700;letter-spacing:1px;text-transform:uppercase;
        color:var(--ed-cocoa);margin-bottom:4px;
    }
    .ord-info-value{font-size:14px;color:var(--ed-ink);font-weight:500}
    .ord-info-value i{color:var(--ed-rust);margin-right:6px;font-size:12px}
    .ord-info-value.price{
        font-family:'Fraunces',serif;font-style:italic;font-weight:800;
        font-size:22px;color:var(--ed-rust);
    }
    .ord-info-value.price small{font-size:12px;color:var(--ed-cocoa);font-weight:500;font-style:normal}
    .ord-payment-method{
        display:inline-flex;align-items:center;gap:6px;
        padding:4px 10px;border-radius:6px;
        background:var(--ed-cream-2);color:var(--ed-ink);
        font-size:12px;font-weight:700;letter-spacing:.5px;text-transform:uppercase;
    }

    .ord-card-foot{
        padding:14px 22px;border-top:1px dashed rgba(42,31,20,.1);
        display:flex;flex-wrap:wrap;gap:8px;justify-content:flex-end;
    }
    .ord-action{
        display:inline-flex;align-items:center;gap:6px;
        padding:8px 16px;border-radius:6px;
        font-size:12px;font-weight:700;letter-spacing:.5px;text-transform:uppercase;
        text-decoration:none;cursor:pointer;border:0;font-family:inherit;
        transition:all .2s;
    }
    .ord-action.primary{background:var(--ed-ink);color:#fff}
    .ord-action.primary:hover{background:var(--ed-rust);color:#fff}
    .ord-action.success{background:#10b981;color:#fff}
    .ord-action.success:hover{background:#059669;color:#fff}
    .ord-action.warning{background:var(--ed-mustard);color:#fff}
    .ord-action.warning:hover{background:#a16207;color:#fff}
    .ord-action.danger{background:transparent;color:#dc2626;border:1.5px solid #fca5a5}
    .ord-action.danger:hover{background:#dc2626;color:#fff;border-color:#dc2626}
    .ord-action.muted{background:var(--ed-cream-2);color:var(--ed-cocoa);cursor:not-allowed}

    .ord-empty{
        background:var(--ed-paper);border:1px dashed rgba(42,31,20,.2);
        border-radius:8px;padding:60px 30px;text-align:center;
    }
    .ord-empty i{font-size:48px;color:var(--ed-cocoa);margin-bottom:14px}
    .ord-empty h4{font-family:'Fraunces',serif;font-style:italic;font-weight:700;color:var(--ed-ink);margin:0 0 6px}
    .ord-empty p{color:var(--ed-ink-soft);margin:0 0 20px}

    .ord-back-btn{
        display:inline-flex;align-items:center;gap:8px;
        padding:11px 22px;border-radius:6px;
        background:transparent;color:var(--ed-ink);border:1.5px solid var(--ed-ink);
        font-size:12.5px;font-weight:700;letter-spacing:1px;text-transform:uppercase;
        text-decoration:none;transition:all .2s;
    }
    .ord-back-btn:hover{background:var(--ed-ink);color:#fff}
</style>

<div class="ord-wrap">
    <div class="ord-head">
        <span class="ord-eyebrow"><i class="fas fa-receipt"></i> Lịch sử mua sắm</span>
        <h1 class="ord-title">Đơn hàng <span>của tôi</span></h1>
        <p class="ord-sub">Theo dõi và quản lý các đơn hàng đã đặt</p>
    </div>

    <?php if (!empty($orders)): ?>
        <!-- Stats -->
        <div class="ord-stats">
            <div class="ord-stat">
                <div class="ord-stat-icon c1"><i class="fas fa-box"></i></div>
                <div><div class="ord-stat-num"><?= $statCount['total'] ?></div><div class="ord-stat-lbl">Tổng đơn</div></div>
            </div>
            <div class="ord-stat">
                <div class="ord-stat-icon c2"><i class="fas fa-clock"></i></div>
                <div><div class="ord-stat-num"><?= $statCount['pending'] ?></div><div class="ord-stat-lbl">Đang xử lý</div></div>
            </div>
            <div class="ord-stat">
                <div class="ord-stat-icon c3"><i class="fas fa-truck"></i></div>
                <div><div class="ord-stat-num"><?= $statCount['shipping'] ?></div><div class="ord-stat-lbl">Đang giao</div></div>
            </div>
            <div class="ord-stat">
                <div class="ord-stat-icon c4"><i class="fas fa-coins"></i></div>
                <div><div class="ord-stat-num" style="font-size:18px"><?= number_format($statCount['spent']) ?>đ</div><div class="ord-stat-lbl">Đã chi tiêu</div></div>
            </div>
        </div>

        <div class="ord-toolbar">
            <h3>Danh sách đơn hàng (<?= $statCount['total'] ?>)</h3>
            <a href="/Duan1/index.php?act=giaodien" class="ord-back-btn">
                <i class="fas fa-arrow-left"></i> Tiếp tục mua hàng
            </a>
        </div>

        <div class="ord-list">
            <?php foreach ($orders as $order):
                $bo = badgeOrderEd($order['status']);
                $bp = badgePaymentEd($order['payment_status']);
                $editableInfo  = ['cho_xac_nhan', 'da_dat_hang', 'dang_lay_hang'];
                $cancelable    = ['cho_xac_nhan', 'da_dat_hang', 'dang_lay_hang'];
                $cantCancelYet = ['dang_van_chuyen', 'da_van_chuyen'];
                $reorderable   = ['da_huy', 'hoan_thanh'];
            ?>
                <div class="ord-card">
                    <div class="ord-card-head">
                        <div class="ord-card-id"><i class="fas fa-hashtag"></i>Đơn #<?= $order['id'] ?></div>
                        <div class="ord-card-badges">
                            <span class="ord-badge" style="background:<?= $bo['bg'] ?>;color:<?= $bo['color'] ?>">
                                <i class="fas fa-<?= $bo['icon'] ?>"></i> <?= hienThiTrangThaiDonHang($order['status']) ?>
                            </span>
                            <span class="ord-badge" style="background:<?= $bp['bg'] ?>;color:<?= $bp['color'] ?>">
                                <i class="fas fa-<?= $bp['icon'] ?>"></i> <?= hienThiTrangThaiThanhToan($order['payment_status']) ?>
                            </span>
                        </div>
                    </div>

                    <div class="ord-card-body">
                        <div class="ord-info-block">
                            <div class="ord-info-label">Người nhận</div>
                            <div class="ord-info-value"><i class="fas fa-user"></i><?= htmlspecialchars($order['receiver_name']) ?></div>
                            <div class="ord-info-value" style="margin-top:6px"><i class="fas fa-phone"></i><?= htmlspecialchars($order['receiver_phone']) ?></div>
                            <div class="ord-info-value" style="margin-top:6px"><i class="fas fa-map-marker-alt"></i><?= htmlspecialchars($order['receiver_address']) ?></div>
                        </div>
                        <div class="ord-info-block" style="text-align:right">
                            <div class="ord-info-label">Tổng tiền</div>
                            <div class="ord-info-value price"><?= number_format($order['total']) ?>đ <small>(ship <?= number_format($order['shipping_fee']) ?>đ)</small></div>
                            <div style="margin-top:10px">
                                <span class="ord-payment-method">
                                    <i class="fas fa-<?= $order['payment_method']==='cod' ? 'money-bill-wave' : 'credit-card' ?>"></i>
                                    <?= ($order['payment_method'] === 'cod') ? 'COD' : htmlspecialchars(strtoupper($order['payment_method'])) ?>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="ord-card-foot">
                        <a href="/Duan1/index.php?act=orderDetail&id=<?= $order['id'] ?>" class="ord-action primary">
                            <i class="fas fa-eye"></i> Chi tiết
                        </a>
                        <?php if ($order['status'] === 'cho_xac_nhan'): ?>
                            <a href="/Duan1/index.php?act=confirmOrder&id=<?= $order['id'] ?>" class="ord-action success">
                                <i class="fas fa-check-circle"></i> Hoàn tất
                            </a>
                        <?php endif; ?>
                        <?php if (in_array($order['status'], $editableInfo, true)): ?>
                            <a href="/Duan1/index.php?act=editReceiverInfo&id=<?= $order['id'] ?>" class="ord-action warning">
                                <i class="fas fa-user-edit"></i> Sửa địa chỉ
                            </a>
                        <?php endif; ?>
                        <?php if (in_array($order['status'], $cancelable, true)): ?>
                            <a href="/Duan1/index.php?act=cancelOrder&id=<?= $order['id'] ?>"
                               class="ord-action danger"
                               onclick="return confirm('Bạn có chắc muốn hủy đơn hàng #<?= (int)$order['id'] ?>? <?= $order['payment_method']!=='cod' && $order['payment_status']==='paid' ? 'Hệ thống sẽ xử lý hoàn tiền.' : 'Hành động này không thể hoàn tác.' ?>');">
                                <i class="fas fa-times"></i> Hủy đơn
                            </a>
                        <?php elseif (in_array($order['status'], $cantCancelYet, true)): ?>
                            <button type="button" class="ord-action muted" disabled title="Đơn đang vận chuyển, không thể hủy">
                                <i class="fas fa-ban"></i> Không thể hủy
                            </button>
                        <?php endif; ?>
                        <?php if (in_array($order['status'], $reorderable, true)): ?>
                            <a href="/Duan1/index.php?act=reorder&id=<?= $order['id'] ?>"
                               class="ord-action warning"
                               onclick="return confirm('Thêm các sản phẩm trong đơn này vào giỏ hàng?');">
                                <i class="fas fa-redo"></i> Mua lại
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="ord-empty">
            <i class="fas fa-box-open"></i>
            <h4>Bạn chưa có đơn hàng nào</h4>
            <p>Hãy bắt đầu hành trình mua sắm tại HDTT Store</p>
            <a href="/Duan1/index.php?act=giaodien" class="ord-back-btn" style="background:var(--ed-rust);color:#fff;border-color:var(--ed-rust)">
                <i class="fas fa-shopping-bag"></i> Mua sắm ngay
            </a>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/_footer.php'; ?>
