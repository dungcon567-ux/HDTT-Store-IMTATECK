<?php
$vouchers = $vouchers ?? [];
$errors   = $errors ?? [];

$pageTitle    = 'Mã giảm giá';
$pageBadge    = 'Marketing';
$pageSubtitle = 'Tạo và quản lý các mã giảm giá áp dụng cho đơn hàng.';
$activeMenu   = 'voucher';
$breadcrumb   = ['Marketing', 'Mã giảm giá'];

require __DIR__ . '/../_layout_header.php';

$active = 0; $remain = 0;
foreach ($vouchers as $v) { if ((int)$v['active']) $active++; $remain += (int)$v['quantity']; }
?>

<div class="stat-grid" style="grid-template-columns:repeat(3,1fr);margin-bottom:22px">
    <div class="stat"><div class="icon-tile g1"><i class="bi bi-ticket-perforated"></i></div>
        <div class="label">Tổng mã</div><div class="value"><?= count($vouchers) ?></div></div>
    <div class="stat"><div class="icon-tile g2"><i class="bi bi-check2-circle"></i></div>
        <div class="label">Đang bật</div><div class="value"><?= $active ?></div></div>
    <div class="stat"><div class="icon-tile g3"><i class="bi bi-lightning-charge"></i></div>
        <div class="label">Tổng lượt còn lại</div><div class="value"><?= $remain ?></div></div>
</div>

<div class="surface" style="margin-bottom:22px">
    <div style="font-size:11px;color:var(--text-mut);text-transform:uppercase;letter-spacing:1.4px;font-weight:600">Tạo mới</div>
    <h3 style="margin:4px 0 18px;font-size:18px;font-weight:700"><i class="bi bi-plus-circle" style="color:var(--accent)"></i> Thêm mã giảm giá</h3>

    <?php if (!empty($errors)): ?>
        <div class="alert-soft"><?php foreach ($errors as $e): ?><div><?= e_admin($e) ?></div><?php endforeach; ?></div>
    <?php endif; ?>

    <form method="POST" action="?act=vouchers">
        <?= csrf_field() ?>
        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:14px">
            <div class="field"><label>Mã (VD: SALE20)</label><input name="code" placeholder="SALE20" required style="text-transform:uppercase"></div>
            <div class="field"><label>Loại</label>
                <select name="type"><option value="percent">Giảm theo %</option><option value="fixed">Giảm số tiền (VNĐ)</option></select>
            </div>
            <div class="field"><label>Giá trị (% hoặc VNĐ)</label><input type="number" name="value" min="1" placeholder="10" required></div>
            <div class="field"><label>Đơn tối thiểu (VNĐ)</label><input type="number" name="min_order" min="0" value="0"></div>
            <div class="field"><label>Trần giảm (VNĐ, cho %)</label><input type="number" name="max_discount" min="0" placeholder="Bỏ trống = không giới hạn"></div>
            <div class="field"><label>Số lượt dùng</label><input type="number" name="quantity" min="1" value="100" required></div>
        </div>
        <button class="btn-aurora" type="submit"><i class="bi bi-check2"></i> Tạo mã</button>
    </form>
</div>

<div class="surface">
    <div style="font-size:11px;color:var(--text-mut);text-transform:uppercase;letter-spacing:1.4px;font-weight:600">Danh sách</div>
    <h3 style="margin:4px 0 18px;font-size:18px;font-weight:700">Tất cả mã giảm giá</h3>

    <?php if (!empty($vouchers)): ?>
    <table class="data-table">
        <thead><tr>
            <th style="width:50px">ID</th><th>Mã</th><th>Loại</th><th>Giá trị</th>
            <th>Đơn tối thiểu</th><th>Trần giảm</th><th>Còn lại</th><th>Trạng thái</th>
            <th style="text-align:right">Thao tác</th>
        </tr></thead>
        <tbody>
        <?php foreach ($vouchers as $v): ?>
            <tr>
                <td class="mono" style="color:var(--text-mut)">#<?= (int)$v['id'] ?></td>
                <td><span class="pill violet"><i class="bi bi-ticket"></i> <?= e_admin($v['code']) ?></span></td>
                <td><?= $v['type']==='percent' ? 'Theo %' : 'Số tiền' ?></td>
                <td class="mono" style="font-weight:700;color:var(--accent)">
                    <?= $v['type']==='percent' ? (int)$v['value'].'%' : number_format((int)$v['value']).'đ' ?>
                </td>
                <td class="mono"><?= number_format((int)$v['min_order']) ?>đ</td>
                <td class="mono"><?= $v['max_discount'] !== null ? number_format((int)$v['max_discount']).'đ' : '—' ?></td>
                <td class="mono"><?= (int)$v['quantity'] ?></td>
                <td>
                    <?php if ((int)$v['active']): ?>
                        <span class="pill success">Đang bật</span>
                    <?php else: ?>
                        <span class="pill muted">Đã tắt</span>
                    <?php endif; ?>
                </td>
                <td style="text-align:right;white-space:nowrap">
                    <a href="?act=toggleVoucher&id=<?= (int)$v['id'] ?>" class="btn-ghost info" title="Bật/tắt">
                        <i class="bi bi-<?= (int)$v['active'] ? 'toggle-on' : 'toggle-off' ?>"></i>
                    </a>
                    <a href="?act=deleteVoucher&id=<?= (int)$v['id'] ?>" class="btn-ghost danger" data-confirm="Xoá mã này?"><i class="bi bi-trash"></i></a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?php else: ?>
        <div class="empty-box"><i class="bi bi-ticket-perforated"></i>Chưa có mã giảm giá nào.</div>
    <?php endif; ?>
</div>

<?php require __DIR__ . '/../_layout_footer.php'; ?>
