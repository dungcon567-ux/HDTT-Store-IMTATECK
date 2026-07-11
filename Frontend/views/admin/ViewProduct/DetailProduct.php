<?php
$pageTitle = 'Chi tiết sản phẩm';
$pageBadge = 'Catalog';
$pageSubtitle = 'Xem nhanh thông tin chi tiết một biến thể của sản phẩm.';
$activeMenu = 'product';
$breadcrumb = ['Catalog', 'Sản phẩm', '#' . (int)($item['id'] ?? 0)];
$pageActions = '<a href="?act=adminProduct" class="btn-ghost"><i class="bi bi-arrow-left"></i> Quay lại</a>';

require __DIR__ . '/../_layout_header.php';
?>

<div style="display:grid;grid-template-columns:380px 1fr;gap:18px;max-width:1100px">
    <div class="surface" style="display:flex;align-items:center;justify-content:center;padding:18px">
        <img src="uploads/<?= e_admin($item['image'] ?? '') ?>"
             onerror="this.src='https://placehold.co/400x400/0a0e1a/8a92b3?text=No+Image';"
             style="width:100%;border-radius:14px;border:1px solid var(--border-2);object-fit:cover;aspect-ratio:1/1">
    </div>

    <div class="surface">
        <div style="font-size:11px;color:var(--text-mut);text-transform:uppercase;letter-spacing:1.4px;font-weight:600">Sản phẩm</div>
        <h2 style="margin:6px 0 12px;font-size:28px;font-weight:800;letter-spacing:-.4px"><?= e_admin($item['product_name'] ?? '') ?></h2>

        <div style="margin-bottom:18px">
            <span class="mono" style="font-size:32px;font-weight:800;background:var(--grad);-webkit-background-clip:text;-webkit-text-fill-color:transparent">
                <?= isset($item['price']) ? number_format($item['price']) . '₫' : 'Chưa có giá' ?>
            </span>
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:18px">
            <div style="padding:14px;border:1px solid var(--border);border-radius:14px;background:rgba(255,255,255,.02)">
                <div style="font-size:11px;color:var(--text-mut);text-transform:uppercase;letter-spacing:1.4px;font-weight:600;margin-bottom:6px">Màu sắc</div>
                <span class="pill violet"><i class="bi bi-circle-fill"></i><?= e_admin($item['color_name'] ?? 'Chưa có') ?></span>
            </div>
            <div style="padding:14px;border:1px solid var(--border);border-radius:14px;background:rgba(255,255,255,.02)">
                <div style="font-size:11px;color:var(--text-mut);text-transform:uppercase;letter-spacing:1.4px;font-weight:600;margin-bottom:6px">Kích thước</div>
                <span class="pill info"><?= e_admin($item['size_name'] ?? 'Chưa có') ?></span>
            </div>
            <div style="padding:14px;border:1px solid var(--border);border-radius:14px;background:rgba(255,255,255,.02);grid-column:1/-1">
                <div style="font-size:11px;color:var(--text-mut);text-transform:uppercase;letter-spacing:1.4px;font-weight:600;margin-bottom:6px">Tồn kho</div>
                <?php if (isset($item['stock']) && $item['stock'] > 0): ?>
                    <span class="pill success"><i class="bi bi-check2"></i> Còn <?= (int)$item['stock'] ?> sản phẩm</span>
                <?php elseif (isset($item['stock'])): ?>
                    <span class="pill danger">Hết hàng</span>
                <?php else: ?>
                    <span class="pill muted">Chưa cập nhật</span>
                <?php endif; ?>
            </div>
        </div>

        <?php if (!empty($item['description'])): ?>
        <div style="padding:14px;border:1px solid var(--border);border-radius:14px;background:rgba(255,255,255,.02);margin-bottom:18px">
            <div style="font-size:11px;color:var(--text-mut);text-transform:uppercase;letter-spacing:1.4px;font-weight:600;margin-bottom:8px">Mô tả</div>
            <p style="margin:0;color:var(--text-mut);font-size:13.5px;line-height:1.7"><?= e_admin($item['description']) ?></p>
        </div>
        <?php endif; ?>

        <div style="display:flex;gap:10px">
            <a href="?act=editProduct&id=<?= (int)($item['id'] ?? 0) ?>" class="btn-aurora"><i class="bi bi-pencil"></i> Chỉnh sửa</a>
            <a href="?act=CateProduct" class="btn-ghost"><i class="bi bi-stack"></i> Quản lý biến thể</a>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../_layout_footer.php'; ?>
