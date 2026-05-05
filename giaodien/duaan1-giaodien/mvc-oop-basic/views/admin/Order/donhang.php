<?php if (session_status() === PHP_SESSION_NONE) session_start();

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
function pillClass($s){
    return match($s){
        'cho_xac_nhan'=>'warn',
        'da_dat_hang'=>'violet',
        'dang_lay_hang','dang_van_chuyen','da_van_chuyen'=>'info',
        'hoan_thanh'=>'success',
        'da_huy'=>'danger',
        default=>'muted',
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

$orders = $orders ?? [];

$pageTitle = 'Đơn hàng';
$pageBadge = 'Vận hành';
$pageSubtitle = 'Theo dõi và cập nhật trạng thái đơn hàng theo thời gian thực.';
$activeMenu = 'order';
$breadcrumb = ['Vận hành', 'Đơn hàng'];

require __DIR__ . '/../_layout_header.php';

$flash = $_SESSION['order_flash'] ?? null;
unset($_SESSION['order_flash']);

$cntPending = 0; $cntDone = 0; $cntPaid = 0; $totalRev = 0;
foreach ($orders as $o) {
    if ($o['status']==='cho_xac_nhan') $cntPending++;
    if ($o['status']==='hoan_thanh') $cntDone++;
    if ($o['payment_status']==='paid') $cntPaid++;
    $totalRev += (int)$o['total'];
}
?>

<?php if ($flash): ?>
    <div class="alert-soft <?= $flash['type'] === 'success' ? 'success' : '' ?>" id="orderFlash" style="display:flex;align-items:center;gap:10px">
        <i class="bi <?= $flash['type'] === 'success' ? 'bi-check-circle-fill' : 'bi-exclamation-triangle-fill' ?>" style="font-size:18px"></i>
        <span style="flex:1"><?= e_admin($flash['msg']) ?></span>
        <button type="button" onclick="document.getElementById('orderFlash').remove()" style="background:none;border:0;color:inherit;font-size:18px;cursor:pointer;opacity:.6"><i class="bi bi-x-lg"></i></button>
    </div>
    <script>setTimeout(()=>{const el=document.getElementById('orderFlash');if(el)el.style.transition='opacity .4s';if(el)el.style.opacity=0;setTimeout(()=>el?.remove(),500)},5000);</script>
<?php endif; ?>

<div class="stat-grid">
    <div class="stat">
        <div class="glow c1"></div>
        <div class="icon-tile g1"><i class="bi bi-receipt"></i></div>
        <div class="label">Tổng đơn</div>
        <div class="value"><?= count($orders) ?></div>
    </div>
    <div class="stat">
        <div class="glow c2"></div>
        <div class="icon-tile g2"><i class="bi bi-hourglass-split"></i></div>
        <div class="label">Chờ xác nhận</div>
        <div class="value"><?= $cntPending ?></div>
    </div>
    <div class="stat">
        <div class="glow c3"></div>
        <div class="icon-tile g3"><i class="bi bi-check2-circle"></i></div>
        <div class="label">Hoàn thành</div>
        <div class="value"><?= $cntDone ?></div>
    </div>
    <div class="stat">
        <div class="glow c4"></div>
        <div class="icon-tile g4"><i class="bi bi-cash-coin"></i></div>
        <div class="label">Tổng giá trị</div>
        <div class="value mono"><?= number_format($totalRev) ?>₫</div>
    </div>
</div>

<div class="surface">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:18px">
        <div>
            <div style="font-size:11px;color:var(--text-mut);text-transform:uppercase;letter-spacing:1.4px;font-weight:600">Danh sách</div>
            <h3 style="margin:4px 0 0;font-size:18px;font-weight:700">Tất cả đơn hàng</h3>
        </div>
    </div>

    <?php if (!empty($orders)): ?>
    <div style="overflow-x:auto">
    <table class="data-table" style="min-width:1200px">
        <thead>
            <tr>
                <th>Mã</th>
                <th>Khách</th>
                <th>Người nhận</th>
                <th>Liên hệ</th>
                <th>Tổng</th>
                <th>PTTT</th>
                <th>Trạng thái</th>
                <th>Thanh toán</th>
                <th>Cập nhật</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($orders as $o): ?>
            <tr>
                <td class="mono" style="font-weight:700;color:var(--accent-2)">#<?= (int)$o['id'] ?></td>
                <td><?= e_admin($o['username'] ?? '-') ?></td>
                <td style="font-weight:600"><?= e_admin($o['receiver_name']) ?></td>
                <td style="font-size:12px;color:var(--text-mut)">
                    <div class="mono"><?= e_admin($o['receiver_phone']) ?></div>
                    <div style="white-space:nowrap;max-width:160px;overflow:hidden;text-overflow:ellipsis"><?= e_admin($o['receiver_address']) ?></div>
                </td>
                <td>
                    <div class="mono" style="font-weight:700;color:#fff"><?= number_format($o['total']) ?>₫</div>
                    <div style="font-size:11px;color:var(--text-mut)">+ ship <?= number_format($o['shipping_fee']) ?>₫</div>
                </td>
                <td>
                    <span class="pill muted"><?= $o['payment_method']==='cod' ? 'COD' : e_admin($o['payment_method']) ?></span>
                </td>
                <td>
                    <span class="pill <?= pillClass($o['status']) ?>"><?= hienThiTrangThaiDonHang($o['status']) ?></span>
                </td>
                <td style="min-width:230px">
                    <?php
                        $payPill = match($o['payment_status']) {
                            'paid'           => 'success',
                            'dang_hoan_tien' => 'warn',
                            'da_hoan_tien'   => 'info',
                            default          => 'warn',
                        };
                        $payLocked = $o['payment_method'] !== 'cod'
                                  && $o['payment_status'] === 'paid'
                                  && $o['status'] === 'da_dat_hang';
                        $isCod = $o['payment_method'] === 'cod';
                    ?>
                    <div style="display:flex;flex-direction:column;gap:6px">
                        <span class="pill <?= $payPill ?>" style="align-self:flex-start"><?= hienThiTrangThaiThanhToan($o['payment_status']) ?></span>
                        <?php if ($payLocked): ?>
                            <span class="pill success" style="align-self:flex-start;font-size:10.5px" title="Online đã thanh toán + đã đặt hàng — đã khoá">
                                <i class="bi bi-lock-fill"></i> Đã khoá
                            </span>
                        <?php elseif ($isCod): ?>
                            <span class="pill muted" style="align-self:flex-start;font-size:10.5px" title="COD — tự cập nhật khi đơn hoàn thành">
                                <i class="bi bi-lock-fill"></i> Tự động khi hoàn thành
                            </span>
                        <?php else: ?>
                            <form method="POST" action="index.php?act=updatePaymentStatus" style="display:flex;gap:6px">
                                <input type="hidden" name="order_id" value="<?= (int)$o['id'] ?>">
                                <select name="payment_status">
                                    <option value="unpaid"         <?= $o['payment_status']==='unpaid'?'selected':'' ?>>Chưa thanh toán</option>
                                    <option value="paid"           <?= $o['payment_status']==='paid'?'selected':'' ?>>Đã thanh toán</option>
                                    <option value="dang_hoan_tien" <?= $o['payment_status']==='dang_hoan_tien'?'selected':'' ?>>Đang hoàn tiền</option>
                                    <option value="da_hoan_tien"   <?= $o['payment_status']==='da_hoan_tien'?'selected':'' ?>>Đã hoàn tiền</option>
                                </select>
                                <button class="btn-ghost" title="Cập nhật thanh toán"><i class="bi bi-arrow-repeat"></i></button>
                            </form>
                        <?php endif; ?>
                    </div>
                </td>
                <td style="min-width:230px">
                    <?php if ($o['status'] === 'hoan_thanh'): ?>
                        <span class="pill success" title="Đơn đã hoàn thành — đã khoá">
                            <i class="bi bi-lock-fill"></i> Đã khoá
                        </span>
                    <?php elseif ($o['status'] === 'da_huy'): ?>
                        <span class="pill danger" title="Đơn đã hủy — đã khoá">
                            <i class="bi bi-lock-fill"></i> Đã khoá
                        </span>
                    <?php else: ?>
                        <form method="POST" action="index.php?act=updateOrderStatus" style="display:flex;gap:6px">
                            <input type="hidden" name="order_id" value="<?= (int)$o['id'] ?>">
                            <select name="status">
                                <option value="cho_xac_nhan" <?= $o['status']==='cho_xac_nhan'?'selected':'' ?>>Chờ xác nhận</option>
                                <option value="da_dat_hang" <?= $o['status']==='da_dat_hang'?'selected':'' ?>>Đã đặt hàng</option>
                                <option value="dang_lay_hang" <?= $o['status']==='dang_lay_hang'?'selected':'' ?>>Đang lấy hàng</option>
                                <option value="dang_van_chuyen" <?= $o['status']==='dang_van_chuyen'?'selected':'' ?>>Đang vận chuyển</option>
                                <option value="da_van_chuyen" <?= $o['status']==='da_van_chuyen'?'selected':'' ?>>Đã vận chuyển</option>
                                <option value="hoan_thanh">Hoàn thành</option>
                                <option value="da_huy" <?= $o['status']==='da_huy'?'selected':'' ?>>Đã hủy</option>
                            </select>
                            <button class="btn-ghost" title="Cập nhật"><i class="bi bi-arrow-repeat"></i></button>
                        </form>
                    <?php endif; ?>
                </td>
                <td style="white-space:nowrap">
                    <a href="index.php?act=detailOrder&id=<?= (int)$o['id'] ?>" class="btn-ghost info" title="Xem chi tiết đơn">
                        <i class="bi bi-eye"></i> Chi tiết
                    </a>
                    <a href="index.php?act=editOrder&id=<?= (int)$o['id'] ?>" class="btn-ghost warn" title="Sửa đơn"><i class="bi bi-pencil"></i></a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    </div>
    <?php else: ?>
        <div class="empty-box"><i class="bi bi-bag"></i>Chưa có đơn hàng nào.</div>
    <?php endif; ?>
</div>

<?php require __DIR__ . '/../_layout_footer.php'; ?>
