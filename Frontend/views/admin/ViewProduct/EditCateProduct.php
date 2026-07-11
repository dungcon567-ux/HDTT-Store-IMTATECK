<?php
$products = $products ?? [];
$colors = $colors ?? [];
$sizes = $sizes ?? [];
$errors = $errors ?? [];

$pageTitle = 'Sửa biến thể';
$pageBadge = 'Catalog';
$pageSubtitle = 'Cập nhật màu, kích thước, giá và tồn kho cho biến thể.';
$activeMenu = 'variant';
$breadcrumb = ['Catalog', 'Biến thể', '#' . (int)$variant['id']];
$pageActions = '<a href="?act=CateProduct" class="btn-ghost"><i class="bi bi-arrow-left"></i> Quay lại</a>';

require __DIR__ . '/../_layout_header.php';
?>

<div class="surface" style="max-width:980px">
    <div style="display:flex;align-items:center;gap:14px;margin-bottom:22px">
        <div style="width:44px;height:44px;border-radius:12px;background:var(--grad-2);display:flex;align-items:center;justify-content:center;color:#fff;font-size:20px">
            <i class="bi bi-pencil-square"></i>
        </div>
        <div>
            <div style="font-size:11px;color:var(--text-mut);text-transform:uppercase;letter-spacing:1.4px;font-weight:600">Chỉnh sửa</div>
            <h3 style="margin:2px 0 0;font-size:18px;font-weight:700">Biến thể <span class="mono" style="color:var(--text-mut)">#<?= (int)$variant['id'] ?></span></h3>
        </div>
    </div>

    <?php if (!empty($errors)): ?>
        <div class="alert-soft">
            <ul style="margin:0;padding-left:18px">
                <?php foreach ($errors as $err): ?><li><?= e_admin($err) ?></li><?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php
        $currentProductName = '';
        foreach ($products as $p) {
            if ((int)$p['id'] === (int)$variant['product_id']) {
                $currentProductName = $p['name'];
                break;
            }
        }
    ?>
    <form method="POST" enctype="multipart/form-data">
        <div class="field">
            <label>Tên sản phẩm <span class="mono" style="color:var(--text-mut);font-weight:500;font-size:11px;margin-left:4px">#<?= (int)$variant['product_id'] ?></span></label>
            <input type="text" name="product_name" value="<?= e_admin($currentProductName) ?>" required>
            <small style="color:var(--text-mut);font-size:11.5px">Sửa tên ở đây sẽ cập nhật tên cho tất cả biến thể của sản phẩm này.</small>
            <input type="hidden" name="product_id" value="<?= (int)$variant['product_id'] ?>">
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px">
            <div class="field">
                <label>Màu sắc</label>
                <select name="color_id" required>
                    <?php foreach ($colors as $c): ?>
                        <option value="<?= (int)$c['id'] ?>" <?= ($variant['color_id'] == $c['id']) ? 'selected' : '' ?>><?= e_admin($c['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="field">
                <label>Kích thước</label>
                <select name="size_id" required>
                    <optgroup label="Quần áo">
                        <?php foreach ($sizes as $s): if (!is_numeric($s['name'])): ?>
                            <option value="<?= (int)$s['id'] ?>" <?= ($variant['size_id'] == $s['id']) ? 'selected' : '' ?>><?= e_admin($s['name']) ?></option>
                        <?php endif; endforeach; ?>
                    </optgroup>
                    <optgroup label="Giày">
                        <?php foreach ($sizes as $s): if (is_numeric($s['name'])): ?>
                            <option value="<?= (int)$s['id'] ?>" <?= ($variant['size_id'] == $s['id']) ? 'selected' : '' ?>><?= e_admin($s['name']) ?></option>
                        <?php endif; endforeach; ?>
                    </optgroup>
                </select>
            </div>
        </div>

        <div style="font-size:11px;color:var(--text-mut);text-transform:uppercase;letter-spacing:1.4px;font-weight:600;margin:18px 0 12px;padding-bottom:6px;border-bottom:1px solid var(--border)">
            <i class="bi bi-image"></i> Hình ảnh & giá
        </div>

        <div style="display:grid;grid-template-columns:160px 1fr;gap:18px;align-items:start;margin-bottom:14px">
            <div>
                <label style="font-size:12px;color:var(--text-mut);font-weight:600;text-transform:uppercase;letter-spacing:1px;display:block;margin-bottom:6px">Ảnh hiện tại</label>
                <img src="uploads/<?= e_admin($variant['image']) ?>" style="width:100%;border-radius:14px;border:1px solid var(--border-2)" alt="">
            </div>
            <div class="field">
                <label>Chọn ảnh mới (để trống nếu giữ nguyên)</label>
                <input type="file" name="image" accept="image/*">
                <input type="hidden" name="old_image" value="<?= e_admin($variant['image']) ?>">
            </div>
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px">
            <div class="field">
                <label>Giá bán (₫)</label>
                <input type="number" name="price" value="<?= (int)$variant['price'] ?>" required>
            </div>
            <div class="field">
                <label>Tồn kho</label>
                <input type="number" name="stock" value="<?= (int)$variant['stock'] ?>" required>
            </div>
        </div>

        <div style="display:flex;gap:10px;margin-top:14px">
            <button type="submit" class="btn-aurora"><i class="bi bi-check2-circle"></i> Lưu thay đổi</button>
            <a href="?act=CateProduct" class="btn-ghost">Hủy</a>
        </div>
    </form>
</div>

<?php require __DIR__ . '/../_layout_footer.php'; ?>
