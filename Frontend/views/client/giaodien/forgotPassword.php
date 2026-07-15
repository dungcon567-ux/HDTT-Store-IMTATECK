<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <title>Quên mật khẩu - HDTT Store</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <base href="<?= BASE_PATH ?>Frontend/views/client/giaodien/">
    <link rel="icon" href="data:image/svg+xml,%3Csvg xmlns=%27http://www.w3.org/2000/svg%27 viewBox=%270 0 32 32%27%3E%3Crect width=%2732%27 height=%2732%27 fill=%27%23000%27/%3E%3Ctext x=%2716%27 y=%2723%27 font-family=%27Arial Black,sans-serif%27 font-size=%2720%27 font-weight=%27900%27 fill=%27%23D8FF00%27 text-anchor=%27middle%27%3EH%3C/text%3E%3C/svg%3E">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" />
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link href="css/theme.css" rel="stylesheet">
    <style>
        .fp-wrap{max-width:520px;margin:70px auto;padding:0 18px}
        .fp-card{background:var(--surface);border:2px solid var(--border-2);padding:34px 30px}
        .fp-card h2{font-size:34px;margin:0 0 6px}
        .fp-card .sub{color:var(--text-3);font-family:'Space Mono',monospace;font-size:13px;margin-bottom:22px}
        .fp-link-box{word-break:break-all;background:#000;border:2px dashed var(--border-glow);
            padding:12px;font-family:'Space Mono',monospace;font-size:12px;color:var(--accent);margin-top:12px}
        .fp-back{display:inline-flex;align-items:center;gap:8px;color:var(--text-2);
            font-family:'Space Mono',monospace;text-transform:uppercase;font-size:12px;margin-bottom:16px}
        .fp-back:hover{color:var(--accent)}
    </style>
</head>
<body>
    <div class="fp-wrap">
        <a href="<?= BASE_PATH ?>index.php?act=loginUser" class="fp-back"><i class="fas fa-arrow-left"></i> Quay lại đăng nhập</a>
        <div class="fp-card">
            <h2>Quên mật khẩu</h2>
            <div class="sub">Nhập email để nhận liên kết đặt lại mật khẩu</div>

            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <?php foreach ($errors as $e): ?><div><?= htmlspecialchars($e) ?></div><?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($done)): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle me-1"></i>
                    Nếu email tồn tại trong hệ thống, chúng tôi đã gửi liên kết đặt lại (hết hạn sau 1 giờ).
                </div>
                <?php if (!empty($resetLink)): ?>
                    <div class="sub" style="margin:14px 0 0">Chế độ demo (localhost) — mở liên kết dưới đây để đặt lại:</div>
                    <div class="fp-link-box"><a href="<?= htmlspecialchars($resetLink) ?>" style="color:var(--accent)"><?= htmlspecialchars($resetLink) ?></a></div>
                <?php endif; ?>
            <?php else: ?>
                <form method="POST" action="<?= BASE_PATH ?>index.php?act=forgotPassword" autocomplete="on">
                    <?= csrf_field() ?>
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control mb-3" placeholder="ban@email.com" required>
                    <button type="submit" class="btn btn-primary w-100 py-2">
                        <i class="fas fa-paper-plane me-2"></i>Gửi liên kết đặt lại
                    </button>
                </form>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/theme.js"></script>
</body>
</html>
