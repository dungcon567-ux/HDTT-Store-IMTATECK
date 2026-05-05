<?php
$users = $users ?? [];

$pageTitle = 'Khách hàng';
$pageBadge = 'Vận hành';
$pageSubtitle = 'Tất cả tài khoản người dùng đã đăng ký trên cửa hàng.';
$activeMenu = 'user';
$breadcrumb = ['Vận hành', 'Khách hàng'];

require __DIR__ . '/../_layout_header.php';

$adminCount = 0; $userCount = 0;
foreach ($users as $u) { $u['role'] === 'admin' ? $adminCount++ : $userCount++; }
?>

<div class="stat-grid" style="grid-template-columns:repeat(3,1fr);margin-bottom:22px">
    <div class="stat">
        <div class="glow c1"></div>
        <div class="icon-tile g1"><i class="bi bi-people"></i></div>
        <div class="label">Tổng người dùng</div>
        <div class="value"><?= count($users) ?></div>
    </div>
    <div class="stat">
        <div class="glow c2"></div>
        <div class="icon-tile g2"><i class="bi bi-shield-check"></i></div>
        <div class="label">Quản trị viên</div>
        <div class="value"><?= $adminCount ?></div>
    </div>
    <div class="stat">
        <div class="glow c3"></div>
        <div class="icon-tile g3"><i class="bi bi-person-badge"></i></div>
        <div class="label">Khách hàng</div>
        <div class="value"><?= $userCount ?></div>
    </div>
</div>

<div class="surface">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:18px">
        <div>
            <div style="font-size:11px;color:var(--text-mut);text-transform:uppercase;letter-spacing:1.4px;font-weight:600">Danh sách</div>
            <h3 style="margin:4px 0 0;font-size:18px;font-weight:700">Tài khoản người dùng</h3>
        </div>
    </div>

    <?php if (!empty($users)): ?>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width:50px">ID</th>
                <th>Người dùng</th>
                <th>Email</th>
                <th>SĐT</th>
                <th>Địa chỉ</th>
                <th>Vai trò</th>
                <th style="text-align:right">Thao tác</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($users as $u): ?>
            <tr>
                <td class="mono" style="color:var(--text-mut)">#<?= (int)$u['id'] ?></td>
                <td>
                    <div style="display:flex;align-items:center;gap:11px">
                        <div class="avatar"><?= strtoupper(mb_substr($u['username'],0,1)) ?></div>
                        <div style="font-weight:700"><?= e_admin($u['username']) ?></div>
                    </div>
                </td>
                <td style="color:var(--text-mut)" class="mono"><?= e_admin($u['email']) ?></td>
                <td class="mono"><?= e_admin($u['std']) ?></td>
                <td style="color:var(--text-mut)"><?= e_admin($u['diachi']) ?></td>
                <td>
                    <?php if ($u['role'] === 'admin'): ?>
                        <span class="pill violet"><i class="bi bi-shield-fill-check"></i> Admin</span>
                    <?php else: ?>
                        <span class="pill muted">User</span>
                    <?php endif; ?>
                </td>
                <td style="text-align:right;white-space:nowrap">
                    <a href="?act=editUser&id=<?= (int)$u['id'] ?>" class="btn-ghost warn"><i class="bi bi-pencil"></i></a>
                    <a href="?act=deleteUser&id=<?= (int)$u['id'] ?>" class="btn-ghost danger" data-confirm="Xóa user này?"><i class="bi bi-trash"></i></a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?php else: ?>
        <div class="empty-box"><i class="bi bi-people"></i>Chưa có người dùng.</div>
    <?php endif; ?>
</div>

<?php require __DIR__ . '/../_layout_footer.php'; ?>
