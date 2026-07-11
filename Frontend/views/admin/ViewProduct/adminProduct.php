<?php
$products = $products ?? [];
$keyword = trim($_GET['keyword'] ?? '');

$pageTitle = 'Sản phẩm';
$pageBadge = 'Catalog';
$pageSubtitle = 'Quản lý các sản phẩm gốc trong cửa hàng. Mỗi sản phẩm có thể có nhiều biến thể.';
$activeMenu = 'product';
$breadcrumb = ['Catalog', 'Sản phẩm'];
$pageActions = '<a href="?act=addProduct" class="btn-aurora"><i class="bi bi-plus-lg"></i> Thêm sản phẩm</a>';

require __DIR__ . '/../_layout_header.php';
?>

<div class="surface">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:18px;flex-wrap:wrap;gap:10px">
        <div>
            <div style="font-size:11px;color:var(--text-mut);text-transform:uppercase;letter-spacing:1.4px;font-weight:600">Danh sách</div>
            <h3 style="margin:4px 0 0;font-size:18px;font-weight:700">Tất cả sản phẩm <span class="pill muted" style="margin-left:8px"><?= count($products) ?></span></h3>
        </div>
        <form method="GET" style="display:flex;gap:8px">
            <input type="hidden" name="act" value="adminProduct">
            <input type="text" name="keyword" placeholder="Tìm tên / mô tả..." value="<?= e_admin($keyword) ?>"
                   style="background:rgba(7,10,22,.55);border:1px solid var(--border-2);color:var(--text);padding:9px 14px;border-radius:11px;font-size:13px;outline:none">
            <button type="submit" class="btn-ghost"><i class="bi bi-search"></i> Tìm</button>
        </form>
    </div>

    <?php if ($keyword !== ''): ?>
        <div class="alert-soft info"><i class="bi bi-search"></i> Kết quả cho: <b><?= e_admin($keyword) ?></b></div>
    <?php endif; ?>

    <?php if (!empty($products)): ?>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width:60px">ID</th>
                <th>Tên sản phẩm</th>
                <th>Danh mục</th>
                <th>Mô tả</th>
                <th style="text-align:right;width:200px">Thao tác</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $item): ?>
            <tr>
                <td class="mono" style="color:var(--text-mut)">#<?= (int)$item['id'] ?></td>
                <td style="font-weight:700;font-size:14px"><?= e_admin($item['name']) ?></td>
                <td>
                    <span class="pill violet">
                        <?= e_admin($item['category_name'] ?? 'Chưa phân loại') ?>
                    </span>
                </td>
                <td style="max-width:340px;color:var(--text-mut);white-space:nowrap;overflow:hidden;text-overflow:ellipsis"
                    title="<?= e_admin($item['description']) ?>">
                    <?= e_admin($item['description']) ?>
                </td>
                <td style="text-align:right;white-space:nowrap">
                    <a href="?act=detailAdmin&id=<?= (int)$item['id'] ?>" class="btn-ghost info" title="Chi tiết"><i class="bi bi-eye"></i></a>
                    <a href="?act=editProduct&id=<?= (int)$item['id'] ?>" class="btn-ghost warn" title="Sửa"><i class="bi bi-pencil"></i></a>
                    <a href="?act=deleteProduct&id=<?= (int)$item['id'] ?>" class="btn-ghost danger" data-confirm="Bạn có chắc chắn muốn xóa sản phẩm này?" title="Xóa"><i class="bi bi-trash"></i></a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php else: ?>
        <div class="empty-box">
            <i class="bi bi-inbox"></i>
            Không tìm thấy sản phẩm phù hợp.
        </div>
    <?php endif; ?>
</div>

<?php require __DIR__ . '/../_layout_footer.php'; ?>
