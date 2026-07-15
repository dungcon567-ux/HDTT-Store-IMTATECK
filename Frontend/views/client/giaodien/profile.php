<?php
$pageTitle = 'Thông tin cá nhân';
$activeNav = 'profile';
require_once __DIR__ . '/_header.php';

$avatarUrl = !empty($user['avatar']) && file_exists(__DIR__ . '/../../../../uploads/' . $user['avatar'])
    ? BASE_PATH . 'uploads/' . htmlspecialchars($user['avatar'])
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
                        <?= csrf_field() ?>
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
                    <a href="<?= BASE_PATH ?>index.php?act=myOrders" class="d-block text-decoration-none mb-2 p-2 rounded" style="color:#475569;transition:.2s" onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='transparent'">
                        <i class="fas fa-box-open me-2 text-primary"></i> Đơn hàng của tôi
                    </a>
                    <a href="<?= BASE_PATH ?>index.php?act=cart" class="d-block text-decoration-none mb-2 p-2 rounded" style="color:#475569" onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='transparent'">
                        <i class="fas fa-shopping-cart me-2 text-primary"></i> Giỏ hàng
                    </a>
                    <a href="<?= BASE_PATH ?>index.php?act=logout" class="d-block text-decoration-none p-2 rounded" style="color:#dc3545" onmouseover="this.style.background='#fee2e2'" onmouseout="this.style.background='transparent'">
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
                    <li class="nav-item"><a class="nav-link" data-bs-toggle="pill" href="#tab-addr"><i class="fas fa-map-marker-alt me-1"></i> Sổ địa chỉ</a></li>
                </ul>

                <div class="tab-content">
                    <div id="tab-info" class="tab-pane fade show active">
                        <div class="pro-card">
                            <h4>Thông tin cá nhân</h4>
                            <p class="sub">Cập nhật thông tin cá nhân và liên hệ của bạn</p>

                            <form method="POST" enctype="multipart/form-data">
                        <?= csrf_field() ?>
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
                        <?= csrf_field() ?>
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

                    <div id="tab-addr" class="tab-pane fade">
                        <div class="pro-card" id="addresses">
                            <h4>Sổ địa chỉ</h4>
                            <p class="sub">Lưu địa chỉ để đặt hàng nhanh hơn</p>

                            <?php if (!empty($addresses)): ?>
                                <div class="addr-list">
                                    <?php foreach ($addresses as $a): ?>
                                        <div class="addr-item <?= (int)$a['is_default'] ? 'is-default' : '' ?>">
                                            <div class="addr-item-body">
                                                <div class="addr-name">
                                                    <?= htmlspecialchars($a['receiver_name']) ?>
                                                    <span class="addr-phone"><?= htmlspecialchars($a['receiver_phone']) ?></span>
                                                    <?php if ((int)$a['is_default']): ?><span class="addr-badge">Mặc định</span><?php endif; ?>
                                                </div>
                                                <div class="addr-text"><?= htmlspecialchars($a['address']) ?></div>
                                            </div>
                                            <div class="addr-actions">
                                                <?php if (!(int)$a['is_default']): ?>
                                                    <a href="<?= BASE_PATH ?>index.php?act=setDefaultAddress&id=<?= (int)$a['id'] ?>" class="addr-btn">Đặt mặc định</a>
                                                <?php endif; ?>
                                                <a href="<?= BASE_PATH ?>index.php?act=deleteAddress&id=<?= (int)$a['id'] ?>" class="addr-btn danger"
                                                   onclick="return confirm('Xoá địa chỉ này?')"><i class="fas fa-trash"></i></a>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <p class="sub" style="opacity:.7">Bạn chưa lưu địa chỉ nào.</p>
                            <?php endif; ?>

                            <h4 style="font-size:15px;margin-top:24px">Thêm địa chỉ mới</h4>
                            <form method="POST" action="<?= BASE_PATH ?>index.php?act=addAddress">
                                <?= csrf_field() ?>
                                <div class="form-row mb-3">
                                    <div><label class="form-label-strong">Họ tên người nhận</label>
                                        <input type="text" name="receiver_name" class="form-control-pro" required></div>
                                    <div><label class="form-label-strong">Số điện thoại</label>
                                        <input type="text" name="receiver_phone" class="form-control-pro" pattern="[0-9]{9,11}" required></div>
                                </div>
                                <div class="mb-3"><label class="form-label-strong">Địa chỉ</label>
                                    <input type="text" name="address" class="form-control-pro" placeholder="Số nhà, đường, phường/xã, quận/huyện, tỉnh/thành" required></div>
                                <label style="display:flex;align-items:center;gap:8px;color:var(--text-2);font-size:13px;margin-bottom:14px">
                                    <input type="checkbox" name="is_default" value="1"> Đặt làm địa chỉ mặc định
                                </label>
                                <button type="submit" class="btn-grad"><i class="fas fa-plus me-2"></i>Thêm địa chỉ</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Dark Premium overrides for profile -->
<style>
    .profile-wrap{background:transparent !important}
    .profile-cover{background:#0A0A0A !important;border:2px solid var(--border-2) !important;border-radius:0 !important}
    .profile-cover::before,.profile-cover::after{opacity:.4}
    .profile-cover h1{font-family:'Anton',sans-serif !important;text-transform:uppercase;color:#fff !important}
    .profile-cover p{color:var(--text-2) !important;font-family:'Space Mono',monospace}
    .avatar-wrap{background:var(--accent) !important;border-radius:0 !important}
    .avatar-img{background:#000 !important;color:var(--accent) !important;border-radius:0 !important;font-family:'Anton',sans-serif}
    .avatar-edit{background:var(--accent) !important;color:#000 !important;border-radius:0 !important;border:2px solid #000 !important}
    .profile-name{font-family:'Anton',sans-serif !important;text-transform:uppercase}
    .profile-role{background:var(--accent) !important;color:#000 !important;border-radius:0 !important;font-family:'Space Mono',monospace}
    .profile-role.admin{background:var(--accent) !important;color:#000 !important}
    .avatar-card{border-radius:0 !important}
    .avatar-card,.pro-card{background:var(--surface) !important;border:1px solid var(--border) !important;box-shadow:var(--sh) !important;backdrop-filter:blur(12px)}
    .avatar-img{background:var(--bg-2) !important}
    .profile-name{color:var(--text) !important;font-family:'Archivo',sans-serif !important}
    .profile-email{color:var(--text-3) !important}
    .stats-row{border-top:1px solid var(--border) !important}
    .stat .v{color:transparent !important;background:var(--grad);-webkit-background-clip:text;background-clip:text;font-family:'Archivo',sans-serif !important}
    .stat .l{color:var(--text-3) !important}
    .nav-pills-pro{background:var(--surface-2) !important}
    .nav-pills-pro .nav-link{color:var(--text-2) !important}
    .nav-pills-pro .nav-link.active{background:var(--bg-2) !important;color:#fff !important;box-shadow:0 4px 14px rgba(216,255,0,.25) !important}
    .pro-card h4{color:var(--text) !important;font-family:'Archivo',sans-serif !important}
    .pro-card .sub{color:var(--text-3) !important}
    /* Sổ địa chỉ */
    .addr-list{display:flex;flex-direction:column;gap:10px;margin-bottom:8px}
    .addr-item{display:flex;justify-content:space-between;align-items:flex-start;gap:14px;
        background:var(--surface-2);border:2px solid var(--border-2);padding:14px 16px}
    .addr-item.is-default{border-color:var(--border-glow)}
    .addr-name{font-family:'Archivo';font-weight:800;color:var(--text);display:flex;align-items:center;gap:10px;flex-wrap:wrap}
    .addr-phone{font-family:'Space Mono',monospace;font-weight:400;color:var(--text-3);font-size:13px}
    .addr-badge{background:var(--accent);color:#000;font-family:'Space Mono',monospace;font-size:10.5px;font-weight:700;
        text-transform:uppercase;padding:2px 8px;letter-spacing:.04em}
    .addr-text{color:var(--text-2);font-size:14px;margin-top:4px}
    .addr-actions{display:flex;gap:8px;flex-shrink:0}
    .addr-btn{font-family:'Space Mono',monospace;font-size:12px;text-transform:uppercase;color:var(--text-2);
        border:2px solid var(--border-2);padding:6px 12px;white-space:nowrap;transition:all .12s}
    .addr-btn:hover{border-color:var(--accent);color:var(--accent)}
    .addr-btn.danger:hover{border-color:var(--danger);color:var(--danger)}
    .form-label-strong{color:var(--text-2) !important}
    .form-control-pro{background:var(--surface-2) !important;border:1.5px solid var(--border-2) !important;color:var(--text) !important}
    .form-control-pro:focus{border-color:var(--accent) !important;box-shadow:0 0 0 3px rgba(216,255,0,.2) !important}
    .btn-grad{background:var(--grad) !important;box-shadow:0 8px 22px rgba(216,255,0,.4) !important}
    .btn-soft{background:var(--surface-2) !important;color:var(--text) !important}
    .alert-pro.success{background:rgba(52,211,153,.12) !important;color:#A7F3D0 !important;border:1px solid rgba(52,211,153,.3) !important}
    .alert-pro.error{background:rgba(251,113,133,.12) !important;color:#FECDD3 !important;border:1px solid rgba(251,113,133,.3) !important}
</style>

<?php require_once __DIR__ . '/_footer.php'; ?>
