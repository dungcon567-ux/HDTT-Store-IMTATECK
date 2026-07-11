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

$pageTitle = 'Đơn #' . (int)$order['id'];
$pageBadge = 'Vận hành';
$pageSubtitle = 'Chi tiết đầy đủ về đơn hàng và các sản phẩm trong đơn.';
$activeMenu = 'order';
$breadcrumb = ['Vận hành', 'Đơn hàng', '#' . (int)$order['id']];
$pageActions = '<a href="index.php?act=donhang" class="btn-ghost"><i class="bi bi-arrow-left"></i> Quay lại</a>';

require __DIR__ . '/../_layout_header.php';

$subTotal = 0;
foreach ($orderDetails as $item) $subTotal += $item['price'] * $item['quantity'];
?>

<div style="display:grid;grid-template-columns:380px 1fr;gap:18px">
    <div>
        <div class="surface" style="margin-bottom:16px">
            <div style="font-size:11px;color:var(--text-mut);text-transform:uppercase;letter-spacing:1.4px;font-weight:600">Người đặt hàng</div>
            <div style="display:flex;align-items:center;gap:12px;margin-top:10px;padding-bottom:14px;border-bottom:1px solid var(--border)">
                <div class="avatar" style="width:42px;height:42px;border-radius:12px;font-size:17px"><?= strtoupper(mb_substr($order['username'] ?? '?', 0, 1)) ?></div>
                <div style="flex:1;min-width:0">
                    <div style="font-weight:700;font-size:15px"><?= e_admin($order['username'] ?? 'Khách vãng lai') ?></div>
                    <div style="font-size:11.5px;color:var(--text-mut)" class="mono">User #<?= (int)$order['user_id'] ?><?= !empty($order['user_email']) ? ' · ' . e_admin($order['user_email']) : '' ?></div>
                </div>
            </div>

            <div style="font-size:11px;color:var(--text-mut);text-transform:uppercase;letter-spacing:1.4px;font-weight:600;margin-top:14px">Người nhận</div>
            <h3 style="margin:4px 0 14px;font-size:17px;font-weight:700"><?= e_admin($order['receiver_name']) ?></h3>
            <div style="display:flex;flex-direction:column;gap:8px;font-size:13.5px">
                <div><i class="bi bi-telephone" style="color:var(--accent-2);margin-right:8px"></i><span class="mono"><?= e_admin($order['receiver_phone']) ?></span></div>
                <div><i class="bi bi-geo-alt" style="color:var(--accent-2);margin-right:8px"></i><?= e_admin($order['receiver_address']) ?></div>
            </div>
        </div>

        <div class="surface" style="margin-bottom:16px">
            <div style="font-size:11px;color:var(--text-mut);text-transform:uppercase;letter-spacing:1.4px;font-weight:600">Trạng thái</div>
            <div style="display:flex;flex-direction:column;gap:10px;margin-top:14px">
                <div style="display:flex;justify-content:space-between;align-items:center">
                    <span style="color:var(--text-mut);font-size:13px">Đơn hàng</span>
                    <span class="pill <?= pillClass($order['status']) ?>"><?= hienThiTrangThaiDonHang($order['status']) ?></span>
                </div>
                <div style="display:flex;justify-content:space-between;align-items:center">
                    <span style="color:var(--text-mut);font-size:13px">Thanh toán</span>
                    <span class="pill <?= match($order['payment_status']){'paid'=>'success','dang_hoan_tien'=>'warn','da_hoan_tien'=>'info',default=>'warn'} ?>"><?= hienThiTrangThaiThanhToan($order['payment_status']) ?></span>
                </div>
                <div style="display:flex;justify-content:space-between;align-items:center">
                    <span style="color:var(--text-mut);font-size:13px">Phương thức</span>
                    <span class="pill muted"><?= $order['payment_method']==='cod' ? 'COD' : e_admin($order['payment_method']) ?></span>
                </div>
            </div>
        </div>

        <div class="surface">
            <div style="font-size:11px;color:var(--text-mut);text-transform:uppercase;letter-spacing:1.4px;font-weight:600">Tổng kết</div>
            <div style="display:flex;flex-direction:column;gap:8px;margin-top:14px;font-size:14px">
                <div style="display:flex;justify-content:space-between">
                    <span style="color:var(--text-mut)">Tạm tính</span>
                    <span class="mono"><?= number_format($subTotal) ?>₫</span>
                </div>
                <div style="display:flex;justify-content:space-between">
                    <span style="color:var(--text-mut)">Phí vận chuyển</span>
                    <span class="mono"><?= number_format($order['shipping_fee']) ?>₫</span>
                </div>
                <div style="height:1px;background:var(--border);margin:6px 0"></div>
                <div style="display:flex;justify-content:space-between;align-items:center">
                    <span style="font-weight:700">Tổng cộng</span>
                    <span class="mono" style="font-size:22px;font-weight:800;background:var(--grad);-webkit-background-clip:text;-webkit-text-fill-color:transparent"><?= number_format($order['total']) ?>₫</span>
                </div>
            </div>
        </div>
    </div>

    <div class="surface">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:18px">
            <div>
                <div style="font-size:11px;color:var(--text-mut);text-transform:uppercase;letter-spacing:1.4px;font-weight:600">Sản phẩm</div>
                <h3 style="margin:4px 0 0;font-size:18px;font-weight:700">Trong đơn hàng <span class="pill muted" style="margin-left:8px"><?= count($orderDetails) ?></span></h3>
            </div>
        </div>

        <?php if (!empty($orderDetails)): ?>
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width:80px">Ảnh</th>
                        <th>Sản phẩm</th>
                        <th>Phân loại</th>
                        <th>Đơn giá</th>
                        <th>SL</th>
                        <th style="text-align:right">Thành tiền</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($orderDetails as $item): ?>
                    <tr>
                        <td><img src="uploads/<?= e_admin($item['image']) ?>" class="thumb" alt=""></td>
                        <td style="font-weight:700"><?= e_admin($item['product_name']) ?></td>
                        <td>
                            <span class="pill violet"><i class="bi bi-circle-fill"></i><?= e_admin($item['color_name']) ?></span>
                            <span class="pill info" style="margin-left:4px"><?= e_admin($item['size_name']) ?></span>
                        </td>
                        <td class="mono"><?= number_format($item['price']) ?>₫</td>
                        <td><span class="pill muted">x<?= (int)$item['quantity'] ?></span></td>
                        <td style="text-align:right;font-weight:700;color:var(--accent-2)" class="mono"><?= number_format($item['price'] * $item['quantity']) ?>₫</td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="empty-box"><i class="bi bi-bag"></i>Đơn hàng chưa có chi tiết sản phẩm.</div>
        <?php endif; ?>
    </div>
</div>

<?php require __DIR__ . '/../_layout_footer.php'; ?>
