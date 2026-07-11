<?php
$errors = $errors ?? [];

$pageTitle = 'Sửa người dùng';
$pageBadge = 'Vận hành';
$pageSubtitle = 'Cập nhật thông tin tài khoản của khách hàng.';
$activeMenu = 'user';
$breadcrumb = ['Vận hành', 'Khách hàng', '#' . (int)$user['id']];
$pageActions = '<a href="?act=users" class="btn-ghost"><i class="bi bi-arrow-left"></i> Quay lại</a>';

require __DIR__ . '/../_layout_header.php';
?>

<div class="surface" style="max-width:680px">
    <div style="display:flex;align-items:center;gap:14px;margin-bottom:22px">
        <div class="avatar" style="width:50px;height:50px;font-size:20px;border-radius:14px"><?= strtoupper(mb_substr($user['username'],0,1)) ?></div>
        <div>
            <div style="font-size:11px;color:var(--text-mut);text-transform:uppercase;letter-spacing:1.4px;font-weight:600">Tài khoản</div>
            <h3 style="margin:2px 0 0;font-size:18px;font-weight:700"><?= e_admin($user['username']) ?> <span class="mono" style="color:var(--text-mut);font-weight:500">#<?= (int)$user['id'] ?></span></h3>
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
        <div class="field">
            <label>Tên đăng nhập</label>
            <input type="text" name="username" value="<?= e_admin($user['username']) ?>" required>
        </div>
        <div class="field">
            <label>Email</label>
            <input type="email" name="email" value="<?= e_admin($user['email']) ?>" required>
        </div>
        <div style="display:grid;grid-template-columns:1fr 2fr;gap:14px">
            <div class="field">
                <label>Số điện thoại</label>
                <input type="text" name="std" value="<?= e_admin($user['std']) ?>" required>
            </div>
            <div class="field">
                <label>Địa chỉ</label>
                <input type="text" name="diachi" value="<?= e_admin($user['diachi']) ?>" required>
            </div>
        </div>

        <?php $isSelf = (int)$user['id'] === (int)($_SESSION['user_id'] ?? 0); ?>
        <div class="field">
            <label>Vai trò <?= $isSelf ? '<span class="pill muted" style="margin-left:6px;font-size:10px"><i class="bi bi-lock-fill"></i> Khoá</span>' : '' ?></label>
            <?php if ($isSelf): ?>
                <select disabled>
                    <option>Quản trị viên</option>
                </select>
                <input type="hidden" name="role" value="admin">
                <small style="color:var(--text-mut);font-size:11.5px">Bạn không thể tự hạ vai trò của chính mình.</small>
            <?php else: ?>
                <select name="role">
                    <option value="user"  <?= $user['role']==='user'  ? 'selected' : '' ?>>Khách hàng (user)</option>
                    <option value="admin" <?= $user['role']==='admin' ? 'selected' : '' ?>>Quản trị viên (admin)</option>
                </select>
                <small style="color:var(--text-mut);font-size:11.5px">Cấp quyền truy cập trang quản trị cho người dùng này.</small>
            <?php endif; ?>
        </div>

        <div style="display:flex;gap:10px;margin-top:8px">
            <button type="submit" class="btn-aurora"><i class="bi bi-check2-circle"></i> Cập nhật</button>
            <a href="?act=users" class="btn-ghost">Hủy</a>
        </div>
    </form>
</div>

<?php require __DIR__ . '/../_layout_footer.php'; ?>
