<?php
$products = $products ?? [];
$keyword = trim($_GET['keyword'] ?? '');

$pageTitle = 'Dashboard';
$pageBadge = 'Tổng quan';
$pageSubtitle = 'Theo dõi nhanh hiệu suất cửa hàng và các sản phẩm mới nhất.';
$activeMenu = 'dashboard';
$breadcrumb = ['Tổng quan', 'Dashboard'];
$pageActions = '<a href="?act=addProduct" class="btn-aurora"><i class="bi bi-plus-lg"></i> Thêm sản phẩm</a>';

require __DIR__ . '/_layout_header.php';

require_once __DIR__ . '/../../models/Product.php';
require_once __DIR__ . '/../../models/User.php';

try {
    $_pm = new Product();
    $_um = new User();
    $totalProducts = count($_pm->getAllProducts());
    $totalVariants = count($_pm->getAllVariants());
    $totalCustomers = count($_um->getAll());
    $stats = $_pm->getThongKeDoanhThu();
    $totalOrders = (int)($stats['total_orders'] ?? 0);
    $revenue = (int)($stats['revenue'] ?? 0);
    $unpaid = (int)($stats['unpaid_orders'] ?? 0);
    $completed = (int)($stats['completed_orders'] ?? 0);
    $allOrders = method_exists($_pm, 'getAllOrders') ? $_pm->getAllOrders() : [];
} catch (Throwable $e) {
    $totalProducts = count($products);
    $totalVariants = $totalCustomers = $totalOrders = $revenue = $unpaid = $completed = 0;
    $allOrders = [];
}

function fmtVnd($n){
    if($n>=1000000000) return number_format($n/1000000000,1).'B';
    if($n>=1000000) return number_format($n/1000000,1).'M';
    if($n>=1000) return number_format($n/1000,0).'K';
    return number_format($n);
}
?>

<div class="stat-grid">
    <div class="stat">
        <div class="glow c1"></div>
        <div class="icon-tile g1"><i class="bi bi-box-seam"></i></div>
        <div class="label">Sản phẩm</div>
        <div class="value"><?= $totalProducts ?></div>
        <div class="delta"><i class="bi bi-stack"></i> <?= $totalVariants ?> biến thể</div>
    </div>
    <div class="stat">
        <div class="glow c2"></div>
        <div class="icon-tile g2"><i class="bi bi-bag-check"></i></div>
        <div class="label">Đơn hàng</div>
        <div class="value"><?= $totalOrders ?></div>
        <div class="delta"><i class="bi bi-check2-circle"></i> <?= $completed ?> hoàn thành</div>
    </div>
    <div class="stat">
        <div class="glow c3"></div>
        <div class="icon-tile g3"><i class="bi bi-people"></i></div>
        <div class="label">Khách hàng</div>
        <div class="value"><?= $totalCustomers ?></div>
        <div class="delta"><i class="bi bi-person-plus"></i> Người dùng đăng ký</div>
    </div>
    <div class="stat">
        <div class="glow c4"></div>
        <div class="icon-tile g4"><i class="bi bi-cash-stack"></i></div>
        <div class="label">Doanh thu</div>
        <div class="value mono"><?= fmtVnd($revenue) ?>₫</div>
        <div class="delta" style="color:var(--warn)"><i class="bi bi-hourglass-split"></i> <?= $unpaid ?> chưa thanh toán</div>
    </div>
</div>

<div style="display:grid;grid-template-columns:2fr 1fr;gap:18px;margin-bottom:18px">
    <div class="surface">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:18px">
            <div>
                <div style="font-size:11px;color:var(--text-mut);text-transform:uppercase;letter-spacing:1.4px;font-weight:600">Catalog</div>
                <h3 style="margin:4px 0 0;font-size:18px;font-weight:700">Sản phẩm gần đây</h3>
            </div>
            <a href="?act=adminProduct" class="btn-ghost"><i class="bi bi-arrow-up-right"></i> Tất cả</a>
        </div>

        <?php if ($keyword !== ''): ?>
            <div class="alert-soft info"><i class="bi bi-search"></i> Đang tìm: <b><?= e_admin($keyword) ?></b></div>
        <?php endif; ?>

        <?php if (!empty($products)): ?>
        <table class="data-table">
            <thead><tr>
                <th>ID</th><th>Sản phẩm</th><th>Danh mục</th><th>Mô tả</th><th style="text-align:right">Thao tác</th>
            </tr></thead>
            <tbody>
                <?php foreach (array_slice($products, 0, 8) as $item): ?>
                <tr>
                    <td class="mono" style="color:var(--text-mut)">#<?= (int)($item['id'] ?? 0) ?></td>
                    <td style="font-weight:600"><?= e_admin($item['name'] ?? $item['product_name'] ?? '') ?></td>
                    <td><span class="pill violet"><?= e_admin($item['category_name'] ?? 'Chưa phân loại') ?></span></td>
                    <td style="max-width:280px;color:var(--text-mut);white-space:nowrap;overflow:hidden;text-overflow:ellipsis"
                        title="<?= e_admin($item['description'] ?? '') ?>"><?= e_admin($item['description'] ?? '') ?></td>
                    <td style="text-align:right;white-space:nowrap">
                        <a href="?act=detailAdmin&id=<?= (int)($item['id'] ?? 0) ?>" class="btn-ghost info"><i class="bi bi-eye"></i></a>
                        <a href="?act=editProduct&id=<?= (int)($item['id'] ?? 0) ?>" class="btn-ghost warn"><i class="bi bi-pencil"></i></a>
                        <a href="?act=deleteProduct&id=<?= (int)($item['id'] ?? 0) ?>" class="btn-ghost danger" data-confirm="Xóa sản phẩm này?"><i class="bi bi-trash"></i></a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php else: ?>
            <div class="empty-box"><i class="bi bi-inbox"></i>Chưa có sản phẩm nào.</div>
        <?php endif; ?>
    </div>

    <div class="surface">
        <div style="font-size:11px;color:var(--text-mut);text-transform:uppercase;letter-spacing:1.4px;font-weight:600">Hoạt động</div>
        <h3 style="margin:4px 0 18px;font-size:18px;font-weight:700">Đơn hàng mới</h3>

        <?php if (!empty($allOrders)): ?>
            <?php foreach (array_slice($allOrders, 0, 6) as $od): ?>
                <div style="display:flex;align-items:center;gap:12px;padding:11px 0;border-bottom:1px solid var(--border)">
                    <div style="width:38px;height:38px;border-radius:11px;background:var(--grad);display:flex;align-items:center;justify-content:center;font-weight:700;font-size:12px;color:#fff">#<?= (int)$od['id'] ?></div>
                    <div style="flex:1;min-width:0">
                        <div style="font-weight:600;font-size:13.5px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis"><?= e_admin($od['receiver_name'] ?: ($od['username'] ?? 'Khách')) ?></div>
                        <div style="font-size:11.5px;color:var(--text-mut)" class="mono"><?= number_format((int)$od['total']) ?>₫</div>
                    </div>
                    <span class="pill <?= match($od['status']){
                        'cho_xac_nhan'=>'warn','dang_lay_hang','dang_van_chuyen','da_van_chuyen'=>'info',
                        'hoan_thanh'=>'success','da_huy'=>'danger',default=>'muted'} ?>" style="font-size:10.5px">
                        <?= match($od['status']){
                            'cho_xac_nhan'=>'Chờ','dang_lay_hang'=>'Lấy','dang_van_chuyen'=>'Vận chuyển',
                            'da_van_chuyen'=>'Đã giao','hoan_thanh'=>'Done','da_huy'=>'Hủy',default=>'?'} ?>
                    </span>
                </div>
            <?php endforeach; ?>
            <a href="?act=donhang" class="btn-ghost" style="margin-top:14px;width:100%;justify-content:center"><i class="bi bi-list-ul"></i> Xem tất cả đơn hàng</a>
        <?php else: ?>
            <div class="empty-box" style="padding:30px 10px"><i class="bi bi-bag"></i>Chưa có đơn nào</div>
        <?php endif; ?>
    </div>
</div>

<?php require __DIR__ . '/_layout_footer.php'; ?>
