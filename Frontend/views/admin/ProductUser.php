<?php
$variants = $variants ?? [];

$pageTitle = 'Sản phẩm khách xem';
$pageBadge = 'Catalog';
$pageSubtitle = 'Danh sách biến thể đang hiển thị tới khách hàng.';
$activeMenu = 'variant';
$breadcrumb = ['Catalog', 'Sản phẩm khách'];
$pageActions = '<a href="?act=admin" class="btn-ghost"><i class="bi bi-arrow-left"></i> Dashboard</a>';

require __DIR__ . '/_layout_header.php';
?>

<div class="surface">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:18px">
        <div>
            <div style="font-size:11px;color:var(--text-mut);text-transform:uppercase;letter-spacing:1.4px;font-weight:600">Hiển thị</div>
            <h3 style="margin:4px 0 0;font-size:18px;font-weight:700">Sản phẩm phía khách <span class="pill muted" style="margin-left:8px"><?= count($variants) ?></span></h3>
        </div>
    </div>

    <?php if (!empty($variants)): ?>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width:60px">ID</th>
                <th>Sản phẩm</th>
                <th>Phân loại</th>
                <th>Giá</th>
                <th>Tồn kho</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($variants as $item): ?>
            <tr>
                <td class="mono" style="color:var(--text-mut)">#<?= (int)($item['id'] ?? 0) ?></td>
                <td style="font-weight:700"><?= e_admin($item['product_name'] ?? '') ?></td>
                <td>
                    <span class="pill violet"><i class="bi bi-circle-fill"></i><?= e_admin($item['color_name'] ?? '') ?></span>
                    <span class="pill info" style="margin-left:4px"><?= e_admin($item['size_name'] ?? '') ?></span>
                </td>
                <td class="mono" style="color:#fff;font-weight:700"><?= number_format((int)($item['price'] ?? 0)) ?>₫</td>
                <td>
                    <?php $stock = (int)($item['stock'] ?? $item['quantity'] ?? 0); ?>
                    <?php if ($stock > 0): ?>
                        <span class="pill success"><?= $stock ?> sản phẩm</span>
                    <?php else: ?>
                        <span class="pill danger">Hết hàng</span>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?php else: ?>
        <div class="empty-box"><i class="bi bi-bag"></i>Chưa có dữ liệu.</div>
    <?php endif; ?>
</div>

<?php require __DIR__ . '/_layout_footer.php'; ?>
