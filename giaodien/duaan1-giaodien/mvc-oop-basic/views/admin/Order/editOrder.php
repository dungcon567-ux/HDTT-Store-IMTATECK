<?php
$errors = $errors ?? [];

$pageTitle = 'Sửa đơn hàng #' . (int)$order['id'];
$pageBadge = 'Vận hành';
$pageSubtitle = 'Chỉnh sửa thông tin người nhận, phí ship, phương thức và trạng thái thanh toán.';
$activeMenu = 'order';
$breadcrumb = ['Vận hành', 'Đơn hàng', '#' . (int)$order['id'], 'Sửa'];
$pageActions = '<a href="index.php?act=donhang" class="btn-ghost"><i class="bi bi-arrow-left"></i> Quay lại</a>';

require __DIR__ . '/../_layout_header.php';

$isCompleted = $order['status'] === 'hoan_thanh';
$isCancelled = $order['status'] === 'da_huy';
$isLocked    = $isCompleted || $isCancelled;
$isPaid      = $order['payment_status'] === 'paid';
$isOnlinePaidPlaced = $order['payment_method'] !== 'cod'
                   && $order['payment_status'] === 'paid'
                   && $order['status'] === 'da_dat_hang';
$isCod = $order['payment_method'] === 'cod';
$payLocked = $isPaid || $isOnlinePaidPlaced || $isCod;

$receiverEditable = in_array($order['status'], ['cho_xac_nhan', 'da_dat_hang', 'dang_lay_hang'], true);
?>

<div class="surface" style="max-width:980px">
    <div style="display:flex;align-items:center;gap:14px;margin-bottom:22px">
        <div style="width:44px;height:44px;border-radius:12px;background:var(--grad-2);display:flex;align-items:center;justify-content:center;color:#fff;font-size:20px">
            <i class="bi bi-pencil-square"></i>
        </div>
        <div>
            <div style="font-size:11px;color:var(--text-mut);text-transform:uppercase;letter-spacing:1.4px;font-weight:600">Chỉnh sửa</div>
            <h3 style="margin:2px 0 0;font-size:18px;font-weight:700">Đơn hàng <span class="mono" style="color:var(--text-mut)">#<?= (int)$order['id'] ?></span></h3>
        </div>
        <div style="margin-left:auto;display:flex;gap:8px">
            <span class="pill muted" style="font-size:11px"><i class="bi bi-cash"></i> Tổng <?= number_format($order['total']) ?>₫</span>
            <a href="index.php?act=detailOrder&id=<?= (int)$order['id'] ?>" class="btn-ghost info"><i class="bi bi-eye"></i> Xem chi tiết</a>
        </div>
    </div>

    <?php if (!empty($errors)): ?>
        <div class="alert-soft">
            <ul style="margin:0;padding-left:18px">
                <?php foreach ($errors as $err): ?><li><?= e_admin($err) ?></li><?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="POST">
        <div style="font-size:11px;color:var(--text-mut);text-transform:uppercase;letter-spacing:1.4px;font-weight:600;margin-bottom:12px;padding-bottom:6px;border-bottom:1px solid var(--border);display:flex;align-items:center;gap:8px">
            <i class="bi bi-person-vcard"></i> Thông tin người nhận
            <?php if (!$receiverEditable): ?>
                <span class="pill warn" style="font-size:10px;letter-spacing:.4px"><i class="bi bi-lock-fill"></i> Khoá</span>
            <?php endif; ?>
        </div>

        <?php if (!$receiverEditable): ?>
            <div class="alert-soft info" style="margin-bottom:14px;font-size:13px">
                <i class="bi bi-info-circle"></i> Đơn đã chuyển sang trạng thái <b><?= htmlspecialchars(match($order['status']){
                    'dang_van_chuyen'=>'Đang vận chuyển',
                    'da_van_chuyen'=>'Đã vận chuyển',
                    'hoan_thanh'=>'Hoàn thành',
                    'da_huy'=>'Đã hủy',
                    default=>$order['status'],
                }) ?></b> — không thể sửa thông tin người nhận nữa.
            </div>
        <?php endif; ?>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px">
            <div class="field">
                <label>Họ tên người nhận</label>
                <input type="text" name="receiver_name" value="<?= e_admin($order['receiver_name']) ?>" <?= $receiverEditable ? 'required' : 'readonly disabled' ?>>
            </div>
            <div class="field">
                <label>Số điện thoại</label>
                <input type="text" name="receiver_phone" value="<?= e_admin($order['receiver_phone']) ?>" <?= $receiverEditable ? 'required' : 'readonly disabled' ?>>
            </div>
        </div>

        <div class="field">
            <label>Địa chỉ nhận hàng</label>
            <input type="text" name="receiver_address" value="<?= e_admin($order['receiver_address']) ?>" <?= $receiverEditable ? 'required' : 'readonly disabled' ?>>
        </div>

        <?php if (!$receiverEditable): ?>
            <input type="hidden" name="receiver_name"    value="<?= e_admin($order['receiver_name']) ?>">
            <input type="hidden" name="receiver_phone"   value="<?= e_admin($order['receiver_phone']) ?>">
            <input type="hidden" name="receiver_address" value="<?= e_admin($order['receiver_address']) ?>">
        <?php endif; ?>

        <div style="font-size:11px;color:var(--text-mut);text-transform:uppercase;letter-spacing:1.4px;font-weight:600;margin:18px 0 12px;padding-bottom:6px;border-bottom:1px solid var(--border)">
            <i class="bi bi-cash-coin"></i> Thanh toán & vận chuyển
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:14px">
            <div class="field">
                <label>Phí vận chuyển (₫)</label>
                <input type="number" step="1000" name="shipping_fee" value="<?= (int)$order['shipping_fee'] ?>" required>
            </div>
            <div class="field">
                <label>Phương thức</label>
                <select name="payment_method">
                    <option value="cod" <?= $order['payment_method']==='cod'?'selected':'' ?>>COD - Thanh toán khi nhận hàng</option>
                </select>
            </div>
            <div class="field">
                <label>Trạng thái thanh toán <?= $payLocked ? '<span class="pill success" style="margin-left:6px;font-size:10px"><i class="bi bi-lock-fill"></i> Khoá</span>' : '' ?></label>
                <?php if ($isCod): ?>
                    <select disabled>
                        <option><?= htmlspecialchars(match($order['payment_status']){'paid'=>'Đã thanh toán','dang_hoan_tien'=>'Đang hoàn tiền','da_hoan_tien'=>'Đã hoàn tiền',default=>'Chưa thanh toán'}) ?></option>
                    </select>
                    <input type="hidden" name="payment_status" value="<?= htmlspecialchars($order['payment_status']) ?>">
                    <small style="color:var(--text-mut);font-size:11.5px">Đơn COD — trạng thái thanh toán <b>tự động đổi sang "Đã thanh toán"</b> khi đơn được chuyển sang "Hoàn thành".</small>
                <?php elseif ($isOnlinePaidPlaced): ?>
                    <select disabled>
                        <option>Đã thanh toán</option>
                    </select>
                    <input type="hidden" name="payment_status" value="paid">
                    <small style="color:var(--text-mut);font-size:11.5px">Đơn online đã thanh toán + đang ở trạng thái "Đã đặt hàng" — không thể đổi trạng thái thanh toán.</small>
                <?php elseif ($isPaid): ?>
                    <select disabled>
                        <option>Đã thanh toán</option>
                    </select>
                    <input type="hidden" name="payment_status" value="paid">
                    <small style="color:var(--text-mut);font-size:11.5px">Đơn đã thanh toán không thể đổi về chưa thanh toán.</small>
                <?php else: ?>
                    <select name="payment_status">
                        <option value="unpaid"         <?= $order['payment_status']==='unpaid'?'selected':'' ?>>Chưa thanh toán</option>
                        <option value="paid"           <?= $order['payment_status']==='paid'?'selected':'' ?>>Đã thanh toán</option>
                        <option value="dang_hoan_tien" <?= $order['payment_status']==='dang_hoan_tien'?'selected':'' ?>>Đang hoàn tiền</option>
                        <option value="da_hoan_tien"   <?= $order['payment_status']==='da_hoan_tien'?'selected':'' ?>>Đã hoàn tiền</option>
                    </select>
                <?php endif; ?>
            </div>
        </div>

        <div style="font-size:11px;color:var(--text-mut);text-transform:uppercase;letter-spacing:1.4px;font-weight:600;margin:18px 0 12px;padding-bottom:6px;border-bottom:1px solid var(--border)">
            <i class="bi bi-flag"></i> Trạng thái đơn hàng
        </div>

        <div class="field">
            <label>Trạng thái xử lý <?= $isLocked ? '<span class="pill ' . ($isCancelled ? 'danger' : 'success') . '" style="margin-left:6px;font-size:10px"><i class="bi bi-lock-fill"></i> Khoá</span>' : '' ?></label>
            <?php if ($isCompleted): ?>
                <select disabled>
                    <option>Hoàn thành</option>
                </select>
                <input type="hidden" name="status" value="hoan_thanh">
                <small style="color:var(--text-mut);font-size:11.5px">Đơn đã hoàn thành — không thể chuyển về trạng thái khác.</small>
            <?php elseif ($isCancelled): ?>
                <select disabled>
                    <option>Đã hủy</option>
                </select>
                <input type="hidden" name="status" value="da_huy">
                <small style="color:var(--text-mut);font-size:11.5px">Đơn đã bị hủy — không thể chuyển sang trạng thái khác.</small>
            <?php else: ?>
                <select name="status" id="orderStatus">
                    <option value="cho_xac_nhan"    <?= $order['status']==='cho_xac_nhan'?'selected':'' ?>>Chờ xác nhận</option>
                    <option value="da_dat_hang"     <?= $order['status']==='da_dat_hang'?'selected':'' ?>>Đã đặt hàng</option>
                    <option value="dang_lay_hang"   <?= $order['status']==='dang_lay_hang'?'selected':'' ?>>Đang lấy hàng</option>
                    <option value="dang_van_chuyen" <?= $order['status']==='dang_van_chuyen'?'selected':'' ?>>Đang vận chuyển</option>
                    <option value="da_van_chuyen"   <?= $order['status']==='da_van_chuyen'?'selected':'' ?>>Đã vận chuyển</option>
                    <option value="hoan_thanh"      <?= $order['status']==='hoan_thanh'?'selected':'' ?>>Hoàn thành</option>
                    <option value="da_huy"          <?= $order['status']==='da_huy'?'selected':'' ?>>Đã hủy</option>
                </select>
                <small style="color:var(--text-mut);font-size:11.5px">Khi chọn <b>Hoàn thành</b>, hệ thống sẽ tự đặt thanh toán = Đã thanh toán và không thể đổi lại.</small>
            <?php endif; ?>
        </div>

        <div style="display:flex;gap:10px;margin-top:16px">
            <button type="submit" class="btn-aurora"><i class="bi bi-check2-circle"></i> Lưu thay đổi</button>
            <a href="index.php?act=donhang" class="btn-ghost">Hủy</a>
        </div>
    </form>
</div>

<?php require __DIR__ . '/../_layout_footer.php'; ?>
