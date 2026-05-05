<?php
$thongke = $thongke ?? ['total_orders'=>0,'completed_orders'=>0,'unpaid_orders'=>0,'revenue'=>0,'daily_revenue'=>[]];

$pageTitle = 'Thống kê doanh thu';
$pageBadge = 'Báo cáo';
$pageSubtitle = 'Phân tích doanh thu theo ngày, đơn hàng và tình trạng thanh toán.';
$activeMenu = 'thongke';
$breadcrumb = ['Tổng quan', 'Thống kê'];

require __DIR__ . '/../_layout_header.php';

$dailyMax = 0;
foreach (($thongke['daily_revenue'] ?? []) as $r) {
    if ((int)$r['daily_revenue'] > $dailyMax) $dailyMax = (int)$r['daily_revenue'];
}
?>

<div class="stat-grid">
    <div class="stat">
        <div class="glow c1"></div>
        <div class="icon-tile g1"><i class="bi bi-receipt"></i></div>
        <div class="label">Tổng đơn hàng</div>
        <div class="value"><?= (int)$thongke['total_orders'] ?></div>
    </div>
    <div class="stat">
        <div class="glow c3"></div>
        <div class="icon-tile g3"><i class="bi bi-check2-circle"></i></div>
        <div class="label">Đơn hoàn thành</div>
        <div class="value"><?= (int)$thongke['completed_orders'] ?></div>
    </div>
    <div class="stat">
        <div class="glow c2"></div>
        <div class="icon-tile g2"><i class="bi bi-hourglass-split"></i></div>
        <div class="label">Chưa thanh toán</div>
        <div class="value"><?= (int)$thongke['unpaid_orders'] ?></div>
    </div>
    <div class="stat">
        <div class="glow c4"></div>
        <div class="icon-tile g4"><i class="bi bi-cash-stack"></i></div>
        <div class="label">Doanh thu</div>
        <div class="value mono"><?= number_format((int)$thongke['revenue']) ?>₫</div>
    </div>
</div>

<div class="surface">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:22px">
        <div>
            <div style="font-size:11px;color:var(--text-mut);text-transform:uppercase;letter-spacing:1.4px;font-weight:600">Phân tích</div>
            <h3 style="margin:4px 0 0;font-size:18px;font-weight:700">Doanh thu theo ngày</h3>
        </div>
        <span class="pill info"><i class="bi bi-graph-up-arrow"></i> Cao nhất: <?= number_format($dailyMax) ?>₫</span>
    </div>

    <?php if (!empty($thongke['daily_revenue'])): ?>
        <?php foreach ($thongke['daily_revenue'] as $row): ?>
            <?php $pct = $dailyMax > 0 ? ((int)$row['daily_revenue'] / $dailyMax) * 100 : 0; ?>
            <div class="bar-row">
                <div class="lbl mono"><?= e_admin($row['order_date']) ?></div>
                <div class="bar"><span style="width:<?= $pct ?>%"></span></div>
                <div class="val mono"><?= number_format((int)$row['daily_revenue']) ?>₫</div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="empty-box"><i class="bi bi-bar-chart"></i>Chưa có dữ liệu doanh thu.</div>
    <?php endif; ?>
</div>

<?php require __DIR__ . '/../_layout_footer.php'; ?>
