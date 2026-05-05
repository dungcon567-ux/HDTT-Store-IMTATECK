<?php
$pageTitle = 'Thông tin cá nhân';
$activeNav = 'profile';
require_once __DIR__ . '/_header.php';

$avatarUrl = !empty($user['avatar']) && file_exists(__DIR__ . '/../../../uploads/' . $user['avatar'])
    ? '/Duan1/giaodien/duaan1-giaodien/mvc-oop-basic/uploads/' . htmlspecialchars($user['avatar'])
    : null;
$initial = strtoupper(mb_substr($user['username'], 0, 1));
?>

<style>
    .profile-wrap{
        background:linear-gradient(180deg,#f8fafc 0%,#fff 30%);
        padding:40px 0 60px;
        position:relative;
    }
    .profile-cover{
        height:200px;border-radius:24px;
        background:linear-gradient(135deg,#0d6efd 0%,#6610f2 50%,#d946ef 100%);
        position:relative;overflow:hidden;
        margin-bottom:80px;
    }
    .profile-cover::before{
        content:"";position:absolute;inset:0;
        background:
            radial-gradient(circle at 80% 20%, rgba(255,255,255,.18), transparent 40%),
            radial-gradient(circle at 20% 80%, rgba(255,255,255,.15), transparent 40%);
    }
    .profile-cover::after{
        content:"";position:absolute;inset:0;
        background-image:linear-gradient(rgba(255,255,255,.05) 1px,transparent 1px),
                         linear-gradient(90deg,rgba(255,255,255,.05) 1px,transparent 1px);
        background-size:40px 40px;
    }
    .profile-cover h1{
        color:#fff;position:absolute;left:40px;top:40px;
        font-size:32px;font-weight:800;letter-spacing:-.5px;margin:0;
        text-shadow:0 4px 14px rgba(0,0,0,.15);
    }
    .profile-cover p{
        color:rgba(255,255,255,.9);position:absolute;left:40px;top:80px;
        font-size:14px;margin:0;
    }

    .avatar-card{
        background:#fff;border-radius:20px;
        box-shadow:0 10px 40px rgba(15,23,42,.08);
        padding:0 28px 28px;text-align:center;
        position:relative;margin-top:-150px;
    }
    .avatar-wrap{
        width:140px;height:140px;
        margin:-70px auto 16px;
        border-radius:50%;
        background:linear-gradient(135deg,#0d6efd,#6610f2);
        padding:5px;
        box-shadow:0 12px 30px rgba(102,16,242,.3);
        position:relative;
    }
    .avatar-img{
        width:100%;height:100%;border-radius:50%;
        object-fit:cover;
        background:#fff;
        display:flex;align-items:center;justify-content:center;
        font-size:48px;font-weight:800;color:#0d6efd;
    }
    .avatar-edit{
        position:absolute;bottom:5px;right:5px;
        width:36px;height:36px;border-radius:50%;
        background:#0d6efd;color:#fff;
        display:flex;align-items:center;justify-content:center;
        cursor:pointer;font-size:14px;
        border:3px solid #fff;
        box-shadow:0 4px 10px rgba(13,110,253,.4);
        transition:all .25s;
    }
    .avatar-edit:hover{background:#6610f2;transform:scale(1.1)}
    .avatar-edit input{display:none}

    .profile-name{font-size:22px;font-weight:800;margin:0 0 4px}
    .profile-email{color:#64748b;font-size:13px;margin:0 0 12px}
    .profile-role{
        display:inline-flex;align-items:center;gap:6px;
        padding:5px 14px;border-radius:999px;
        background:linear-gradient(135deg,#fef3c7,#fde68a);
        color:#92400e;font-size:11.5px;font-weight:700;letter-spacing:.5px;
        text-transform:uppercase;
    }
    .profile-role.admin{
        background:linear-gradient(135deg,#dbeafe,#bfdbfe);
        color:#1e40af;
    }

    .stats-row{
        display:grid;grid-template-columns:repeat(3,1fr);
        gap:8px;margin-top:20px;padding-top:20px;
        border-top:1px solid #ececec;
    }
    .stat{padding:8px 4px}
    .stat .v{font-size:18px;font-weight:800;color:#0d6efd}
    .stat .l{font-size:10.5px;color:#94a3b8;text-transform:uppercase;letter-spacing:.6px;font-weight:600;margin-top:2px}

    .nav-pills-pro{
        display:flex;gap:6px;
        background:#f1f5f9;padding:6px;border-radius:14px;
        margin-bottom:24px;
    }
    .nav-pills-pro .nav-link{
        flex:1;padding:10px 16px;border-radius:10px;
        font-size:13.5px;font-weight:600;color:#64748b;
        text-align:center;transition:all .25s;
        background:transparent;border:0;
    }
    .nav-pills-pro .nav-link.active{
        background:#fff;color:#0d6efd;
        box-shadow:0 4px 14px rgba(13,110,253,.12);
    }

    .pro-card{
        background:#fff;border-radius:18px;
        box-shadow:0 6px 24px rgba(15,23,42,.05);
        padding:30px;
    }
    .pro-card h4{
        font-size:18px;font-weight:700;margin:0 0 6px;
    }
    .pro-card .sub{color:#64748b;font-size:13px;margin:0 0 22px}

    .form-row{display:grid;grid-template-columns:1fr 1fr;gap:16px}
    @media(max-width:768px){.form-row{grid-template-columns:1fr}}
    .form-label-strong{font-size:12px;font-weight:700;color:#334155;text-transform:uppercase;letter-spacing:.6px;margin-bottom:6px;display:block}
    .form-control-pro{
        width:100%;padding:11px 14px;
        border:1.5px solid #e5e7eb;border-radius:11px;
        font-size:14px;background:#fff;
        transition:all .2s;outline:none;
    }
    .form-control-pro:focus{
        border-color:#0d6efd;box-shadow:0 0 0 3px rgba(13,110,253,.12)
    }

    .btn-grad{
        background:linear-gradient(135deg,#0d6efd,#6610f2);
        color:#fff;border:0;
        padding:11px 24px;border-radius:11px;
        font-size:13.5px;font-weight:700;
        cursor:pointer;
        box-shadow:0 6px 16px rgba(102,16,242,.25);
        transition:all .25s;
    }
    .btn-grad:hover{transform:translateY(-1px);box-shadow:0 10px 22px rgba(102,16,242,.4);color:#fff}
    .btn-soft{
        background:#f1f5f9;color:#475569;border:0;
        padding:11px 22px;border-radius:11px;
        font-size:13.5px;font-weight:600;cursor:pointer;
    }

    .alert-pro{
        padding:13px 16px;border-radius:12px;
        margin-bottom:20px;font-size:13.5px;
        display:flex;align-items:center;gap:10px;
    }
    .alert-pro.success{background:#d1fae5;color:#065f46;border:1px solid #6ee7b7}
    .alert-pro.error{background:#fee2e2;color:#991b1b;border:1px solid #fca5a5}
</style>

<div class="profile-wrap">
    <div class="container">
        <div class="profile-cover">
            <h1>👋 Xin chào, <?= htmlspecialchars($user['username']) ?></h1>
            <p>Quản lý thông tin cá nhân và bảo mật tài khoản của bạn</p>
        </div>

        <div class="row g-4">
            <div class="col-lg-4">
                <div class="avatar-card">
                    <form method="POST" enctype="multipart/form-data" id="avatarForm">
                        <input type="hidden" name="action" value="update_info">
                        <input type="hidden" name="username" value="<?= htmlspecialchars($user['username']) ?>">
                        <input type="hidden" name="email"    value="<?= htmlspecialchars($user['email']) ?>">
                        <input type="hidden" name="std"      value="<?= htmlspecialchars($user['std']) ?>">
                        <input type="hidden" name="diachi"   value="<?= htmlspecialchars($user['diachi']) ?>">

                        <div class="avatar-wrap">
                            <?php if ($avatarUrl): ?>
                                <img src="<?= $avatarUrl ?>" class="avatar-img" alt="Avatar">
                            <?php else: ?>
                                <div class="avatar-img"><?= $initial ?></div>
                            <?php endif; ?>

                            <label class="avatar-edit" title="Đổi ảnh đại diện">
                                <i class="fas fa-camera"></i>
                                <input type="file" name="avatar" accept="image/*" onchange="document.getElementById('avatarForm').submit()">
                            </label>
                        </div>
                    </form>

                    <h3 class="profile-name"><?= htmlspecialchars($user['username']) ?></h3>
                    <p class="profile-email"><?= htmlspecialchars($user['email']) ?></p>
                    <span class="profile-role <?= $user['role'] === 'admin' ? 'admin' : '' ?>">
                        <i class="fas fa-<?= $user['role'] === 'admin' ? 'shield-alt' : 'user' ?>"></i>
                        <?= $user['role'] === 'admin' ? 'Quản trị viên' : 'Khách hàng' ?>
                    </span>

                    <div class="stats-row">
                        <div class="stat">
                            <div class="v"><?= $totalOrders ?></div>
                            <div class="l">Đơn hàng</div>
                        </div>
                        <div class="stat">
                            <div class="v"><?= $completedOrders ?></div>
                            <div class="l">Hoàn thành</div>
                        </div>
                        <div class="stat">
                            <div class="v" style="font-size:14px"><?= number_format($totalSpent / 1000, 0) ?>K</div>
                            <div class="l">Đã chi</div>
                        </div>
                    </div>
                </div>

                <div class="pro-card mt-4" style="padding:20px">
                    <h4 style="margin:0 0 12px;font-size:15px"><i class="fas fa-link text-primary me-2"></i>Liên kết nhanh</h4>
                    <a href="/Duan1/giaodien/duaan1-giaodien/mvc-oop-basic/index.php?act=myOrders" class="d-block text-decoration-none mb-2 p-2 rounded" style="color:#475569;transition:.2s" onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='transparent'">
                        <i class="fas fa-box-open me-2 text-primary"></i> Đơn hàng của tôi
                    </a>
                    <a href="/Duan1/giaodien/duaan1-giaodien/mvc-oop-basic/index.php?act=cart" class="d-block text-decoration-none mb-2 p-2 rounded" style="color:#475569" onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='transparent'">
                        <i class="fas fa-shopping-cart me-2 text-primary"></i> Giỏ hàng
                    </a>
                    <a href="/Duan1/giaodien/duaan1-giaodien/mvc-oop-basic/index.php?act=logout" class="d-block text-decoration-none p-2 rounded" style="color:#dc3545" onmouseover="this.style.background='#fee2e2'" onmouseout="this.style.background='transparent'">
                        <i class="fas fa-sign-out-alt me-2"></i> Đăng xuất
                    </a>
                </div>
            </div>

            <div class="col-lg-8">
                <?php if ($success): ?>
                    <div class="alert-pro success"><i class="fas fa-check-circle"></i> <?= htmlspecialchars($success) ?></div>
                <?php endif; ?>
                <?php if (!empty($errors)): ?>
                    <div class="alert-pro error">
                        <i class="fas fa-exclamation-triangle"></i>
                        <div><?php foreach ($errors as $err): ?><div><?= htmlspecialchars($err) ?></div><?php endforeach; ?></div>
                    </div>
                <?php endif; ?>

                <ul class="nav nav-pills-pro" role="tablist">
                    <li class="nav-item"><a class="nav-link active" data-bs-toggle="pill" href="#tab-info"><i class="fas fa-id-card me-1"></i> Thông tin</a></li>
                    <li class="nav-item"><a class="nav-link" data-bs-toggle="pill" href="#tab-pass"><i class="fas fa-lock me-1"></i> Đổi mật khẩu</a></li>
                </ul>

                <div class="tab-content">
                    <div id="tab-info" class="tab-pane fade show active">
                        <div class="pro-card">
                            <h4>Thông tin cá nhân</h4>
                            <p class="sub">Cập nhật thông tin cá nhân và liên hệ của bạn</p>

                            <form method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="action" value="update_info">

                                <div class="form-row mb-3">
                                    <div>
                                        <label class="form-label-strong">Tên đăng nhập</label>
                                        <input type="text" name="username" class="form-control-pro" value="<?= htmlspecialchars($user['username']) ?>" required>
                                    </div>
                                    <div>
                                        <label class="form-label-strong">Email</label>
                                        <input type="email" name="email" class="form-control-pro" value="<?= htmlspecialchars($user['email']) ?>" required>
                                    </div>
                                </div>

                                <div class="form-row mb-3">
                                    <div>
                                        <label class="form-label-strong">Số điện thoại</label>
                                        <input type="text" name="std" class="form-control-pro" value="<?= htmlspecialchars($user['std']) ?>" required>
                                    </div>
                                    <div>
                                        <label class="form-label-strong">Vai trò</label>
                                        <input type="text" class="form-control-pro" value="<?= $user['role'] === 'admin' ? 'Quản trị viên' : 'Khách hàng' ?>" disabled style="background:#f8fafc;cursor:not-allowed">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label-strong">Địa chỉ</label>
                                    <input type="text" name="diachi" class="form-control-pro" value="<?= htmlspecialchars($user['diachi']) ?>" required>
                                </div>

                                <div class="d-flex gap-2 mt-4">
                                    <button type="submit" class="btn-grad"><i class="fas fa-save me-2"></i>Lưu thay đổi</button>
                                    <button type="reset" class="btn-soft">Hoàn tác</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div id="tab-pass" class="tab-pane fade">
                        <div class="pro-card">
                            <h4>Đổi mật khẩu</h4>
                            <p class="sub">Để bảo mật tài khoản, hãy chọn mật khẩu mạnh và không chia sẻ</p>

                            <form method="POST">
                                <input type="hidden" name="action" value="change_password">

                                <div class="mb-3">
                                    <label class="form-label-strong">Mật khẩu hiện tại</label>
                                    <input type="password" name="old_password" class="form-control-pro" required>
                                </div>

                                <div class="form-row mb-3">
                                    <div>
                                        <label class="form-label-strong">Mật khẩu mới</label>
                                        <input type="password" name="new_password" class="form-control-pro" minlength="6" required>
                                        <small style="color:#94a3b8;font-size:11.5px">Tối thiểu 6 ký tự</small>
                                    </div>
                                    <div>
                                        <label class="form-label-strong">Xác nhận mật khẩu</label>
                                        <input type="password" name="confirm_password" class="form-control-pro" minlength="6" required>
                                    </div>
                                </div>

                                <button type="submit" class="btn-grad"><i class="fas fa-key me-2"></i>Cập nhật mật khẩu</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/_footer.php'; ?>
