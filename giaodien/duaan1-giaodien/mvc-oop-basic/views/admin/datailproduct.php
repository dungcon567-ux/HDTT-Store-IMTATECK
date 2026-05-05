<?php
$pageTitle = 'Chi tiết biến thể';
$pageBadge = 'Catalog';
$pageSubtitle = 'Thông tin tổng quan của một biến thể sản phẩm.';
$activeMenu = 'variant';
$breadcrumb = ['Catalog', 'Biến thể', 'Chi tiết'];
$pageActions = '<a href="?act=CateProduct" class="btn-ghost"><i class="bi bi-arrow-left"></i> Quay lại</a>';

require __DIR__ . '/_layout_header.php';
?>

<div class="surface" style="max-width:900px">
    <table class="data-table">
        <thead>
            <tr>
                <th style="width:80px">Ảnh</th>
                <th>Sản phẩm</th>
                <th>Màu</th>
                <th>Size</th>
                <th>Giá</th>
                <th>Tồn kho</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><img src="uploads/<?= e_admin($item['image'] ?? '') ?>" class="thumb" alt=""></td>
                <td style="font-weight:700"><?= e_admin($item['product_name'] ?? '') ?></td>
                <td><span class="pill violet"><i class="bi bi-circle-fill"></i><?= e_admin($item['color_name'] ?? '') ?></span></td>
                <td><span class="pill info"><?= e_admin($item['size_name'] ?? '') ?></span></td>
                <td class="mono" style="color:#fff;font-weight:700"><?= number_format((int)($item['price'] ?? 0)) ?>₫</td>
                <td>
                    <?php if (($item['stock'] ?? 0) > 0): ?>
                        <span class="pill success"><?= (int)$item['stock'] ?> sản phẩm</span>
                    <?php else: ?>
                        <span class="pill danger">Hết hàng</span>
                    <?php endif; ?>
                </td>
            </tr>
        </tbody>
    </table>
</div>

<?php require __DIR__ . '/_layout_footer.php'; ?>
