<?php
$variants = $variants ?? [];

$pageTitle = 'Biến thể sản phẩm';
$pageBadge = 'Catalog';
$pageSubtitle = 'Quản lý màu sắc, kích thước, giá và tồn kho của từng biến thể.';
$activeMenu = 'variant';
$breadcrumb = ['Catalog', 'Biến thể'];
$pageActions = '<a href="?act=addCateProduct" class="btn-aurora"><i class="bi bi-plus-lg"></i> Thêm biến thể</a>';

require __DIR__ . '/../_layout_header.php';

$totalStock = 0; $outOfStock = 0;
foreach ($variants as $v) {
    $totalStock += (int)$v['stock'];
    if ((int)$v['stock'] === 0) $outOfStock++;
}
?>

<div class="stat-grid" style="grid-template-columns:repeat(3,1fr);margin-bottom:22px">
    <div class="stat">
        <div class="glow c1"></div>
        <div class="icon-tile g1"><i class="bi bi-stack"></i></div>
        <div class="label">Tổng biến thể</div>
        <div class="value"><?= count($variants) ?></div>
    </div>
    <div class="stat">
        <div class="glow c3"></div>
        <div class="icon-tile g3"><i class="bi bi-boxes"></i></div>
        <div class="label">Tổng tồn kho</div>
        <div class="value"><?= number_format($totalStock) ?></div>
    </div>
    <div class="stat">
        <div class="glow c4"></div>
        <div class="icon-tile g4"><i class="bi bi-exclamation-octagon"></i></div>
        <div class="label">Hết hàng</div>
        <div class="value"><?= $outOfStock ?></div>
    </div>
</div>

<div class="surface">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:18px">
        <div>
            <div style="font-size:11px;color:var(--text-mut);text-transform:uppercase;letter-spacing:1.4px;font-weight:600">Danh sách</div>
            <h3 style="margin:4px 0 0;font-size:18px;font-weight:700">Tất cả biến thể</h3>
        </div>
    </div>

    <?php if (!empty($variants)): ?>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width:50px">ID</th>
                <th style="width:80px">Ảnh</th>
                <th>Sản phẩm</th>
                <th>Phân loại</th>
                <th>Giá</th>
                <th>Tồn kho</th>
                <th style="text-align:right">Thao tác</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($variants as $item): ?>
            <tr>
                <td class="mono" style="color:var(--text-mut)">#<?= (int)$item['id'] ?></td>
                <td><img src="uploads/<?= e_admin($item['image']) ?>" class="thumb" alt=""></td>
                <td style="font-weight:700"><?= e_admin($item['product_name']) ?></td>
                <td>
                    <span class="pill violet"><i class="bi bi-circle-fill"></i><?= e_admin($item['color_name']) ?></span>
                    <span class="pill info" style="margin-left:4px"><?= e_admin($item['size_name']) ?></span>
                </td>
                <td class="mono" style="color:#fff;font-weight:700"><?= number_format($item['price']) ?>₫</td>
                <td>
                    <?php if ((int)$item['stock'] > 0): ?>
                        <span class="pill success"><?= (int)$item['stock'] ?> sản phẩm</span>
                    <?php else: ?>
                        <span class="pill danger">Hết hàng</span>
                    <?php endif; ?>
                </td>
                <td style="text-align:right;white-space:nowrap">
                    <a href="?act=editCateProduct&id=<?= (int)$item['id'] ?>" class="btn-ghost warn"><i class="bi bi-pencil"></i></a>
                    <a href="?act=deleteCateProduct&id=<?= (int)$item['id'] ?>" class="btn-ghost danger" data-confirm="Ẩn biến thể này?"><i class="bi bi-trash"></i></a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?php else: ?>
        <div class="empty-box"><i class="bi bi-stack"></i>Chưa có biến thể nào.</div>
    <?php endif; ?>
</div>

<?php require __DIR__ . '/../_layout_footer.php'; ?>
