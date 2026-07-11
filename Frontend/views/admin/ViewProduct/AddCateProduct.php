<?php
$products = $products ?? [];
$colors = $colors ?? [];
$sizes = $sizes ?? [];
$errors = $errors ?? [];

$pageTitle = 'Thêm biến thể';
$pageBadge = 'Catalog';
$pageSubtitle = 'Tạo biến thể mới với màu, kích thước, giá và tồn kho cụ thể.';
$activeMenu = 'variant';
$breadcrumb = ['Catalog', 'Biến thể', 'Thêm mới'];
$pageActions = '<a href="?act=CateProduct" class="btn-ghost"><i class="bi bi-arrow-left"></i> Quay lại</a>';

require __DIR__ . '/../_layout_header.php';
?>

<div class="surface" style="max-width:980px">
    <div style="display:flex;align-items:center;gap:14px;margin-bottom:22px">
        <div style="width:44px;height:44px;border-radius:12px;background:var(--grad);display:flex;align-items:center;justify-content:center;color:#fff;font-size:20px">
            <i class="bi bi-stack"></i>
        </div>
        <div>
            <div style="font-size:11px;color:var(--text-mut);text-transform:uppercase;letter-spacing:1.4px;font-weight:600">Form</div>
            <h3 style="margin:2px 0 0;font-size:18px;font-weight:700">Thông tin biến thể</h3>
        </div>
    </div>

    <?php if (!empty($errors)): ?>
        <div class="alert-soft">
            <ul style="margin:0;padding-left:18px">
                <?php foreach ($errors as $err): ?><li><?= e_admin($err) ?></li><?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <div style="font-size:11px;color:var(--text-mut);text-transform:uppercase;letter-spacing:1.4px;font-weight:600;margin-bottom:12px;padding-bottom:6px;border-bottom:1px solid var(--border)">
            <i class="bi bi-card-list"></i> Thông tin cơ bản
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:14px">
            <div class="field">
                <label>Sản phẩm</label>
                <select name="product_id" required>
                    <option value="">-- Chọn sản phẩm --</option>
                    <?php foreach ($products as $p): ?>
                        <option value="<?= (int)$p['id'] ?>"><?= e_admin($p['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="field">
                <label>Màu sắc</label>
                <select name="color_id" required>
                    <option value="">-- Chọn màu --</option>
                    <?php foreach ($colors as $c): ?>
                        <option value="<?= (int)$c['id'] ?>"><?= e_admin($c['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="field">
                <label>Kích thước</label>
                <select name="size_id" required>
                    <option value="">-- Chọn size --</option>
                    <optgroup label="Quần áo">
                        <?php foreach ($sizes as $s): if (!is_numeric($s['name'])): ?>
                            <option value="<?= (int)$s['id'] ?>"><?= e_admin($s['name']) ?></option>
                        <?php endif; endforeach; ?>
                    </optgroup>
                    <optgroup label="Giày">
                        <?php foreach ($sizes as $s): if (is_numeric($s['name'])): ?>
                            <option value="<?= (int)$s['id'] ?>"><?= e_admin($s['name']) ?></option>
                        <?php endif; endforeach; ?>
                    </optgroup>
                </select>
            </div>
        </div>

        <div style="font-size:11px;color:var(--text-mut);text-transform:uppercase;letter-spacing:1.4px;font-weight:600;margin:18px 0 12px;padding-bottom:6px;border-bottom:1px solid var(--border)">
            <i class="bi bi-image"></i> Hình ảnh & giá
        </div>

        <div class="field">
            <label>Ảnh sản phẩm</label>
            <input type="file" name="image" accept="image/*" required>
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px">
            <div class="field">
                <label>Giá bán (₫)</label>
                <input type="number" name="price" placeholder="100000" required>
            </div>
            <div class="field">
                <label>Tồn kho</label>
                <input type="number" name="stock" placeholder="20" required>
            </div>
        </div>

        <div style="display:flex;gap:10px;margin-top:14px">
            <button type="submit" class="btn-aurora"><i class="bi bi-plus-circle"></i> Tạo biến thể</button>
            <a href="?act=CateProduct" class="btn-ghost">Hủy</a>
        </div>
    </form>
</div>

<?php require __DIR__ . '/../_layout_footer.php'; ?>
