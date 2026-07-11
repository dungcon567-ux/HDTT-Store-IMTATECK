<?php
$product = $product ?? [];
$categories = $categories ?? [];
$errors = $errors ?? [];

$pageTitle = 'Cập nhật sản phẩm';
$pageBadge = 'Catalog';
$pageSubtitle = 'Chỉnh sửa thông tin chi tiết của sản phẩm gốc.';
$activeMenu = 'product';
$breadcrumb = ['Catalog', 'Sản phẩm', '#' . (int)($product['id'] ?? 0)];
$pageActions = '<a href="?act=adminProduct" class="btn-ghost"><i class="bi bi-arrow-left"></i> Quay lại</a>';

require __DIR__ . '/../_layout_header.php';
?>

<div class="surface" style="max-width:880px">
    <div style="display:flex;align-items:center;gap:14px;margin-bottom:22px">
        <div style="width:44px;height:44px;border-radius:12px;background:var(--grad-2);display:flex;align-items:center;justify-content:center;color:#fff;font-size:20px">
            <i class="bi bi-pencil-square"></i>
        </div>
        <div>
            <div style="font-size:11px;color:var(--text-mut);text-transform:uppercase;letter-spacing:1.4px;font-weight:600">Chỉnh sửa</div>
            <h3 style="margin:2px 0 0;font-size:18px;font-weight:700">Sản phẩm <span class="mono" style="color:var(--text-mut)">#<?= (int)($product['id'] ?? 0) ?></span></h3>
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
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
            <div class="field">
                <label>Tên sản phẩm</label>
                <input type="text" name="name" value="<?= e_admin($product['name'] ?? '') ?>">
            </div>
            <div class="field">
                <label>Danh mục</label>
                <select name="category_id">
                    <option value="">-- Chọn danh mục --</option>
                    <?php foreach ($categories as $cate): ?>
                        <option value="<?= (int)$cate['id'] ?>"
                            <?= (isset($product['category_id']) && (int)$product['category_id'] === (int)$cate['id']) ? 'selected' : '' ?>>
                            <?= e_admin($cate['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="field">
            <label>Mô tả</label>
            <textarea name="description" rows="6"><?= e_admin($product['description'] ?? '') ?></textarea>
        </div>

        <div style="display:flex;gap:10px;margin-top:8px">
            <button type="submit" class="btn-aurora"><i class="bi bi-check2-circle"></i> Lưu thay đổi</button>
            <a href="?act=adminProduct" class="btn-ghost">Hủy</a>
        </div>
    </form>
</div>

<?php require __DIR__ . '/../_layout_footer.php'; ?>
