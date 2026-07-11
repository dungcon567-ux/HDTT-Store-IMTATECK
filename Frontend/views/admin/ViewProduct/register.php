<?php
$message = $message ?? '';
$errors = $errors ?? [];
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng ký · HDTT Console</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        :root{
            --bg-base:#070a16;
            --border:rgba(148,163,255,.12);
            --border-2:rgba(148,163,255,.22);
            --text:#e6e9f5;
            --text-mut:#8a92b3;
            --accent:#7c5cff;
            --accent-2:#22d3ee;
            --grad: linear-gradient(135deg,#7c5cff 0%, #22d3ee 100%);
        }
        *{box-sizing:border-box}
        body{
            margin:0;font-family:'Manrope',system-ui,sans-serif;
            background:var(--bg-base);color:var(--text);
            min-height:100vh;display:flex;align-items:center;justify-content:center;
            padding:30px 20px;position:relative;
        }
        body::before{
            content:"";position:fixed;inset:0;
            background:
                radial-gradient(700px 500px at 20% 0%, rgba(124,92,255,.30), transparent 60%),
                radial-gradient(700px 500px at 80% 100%, rgba(34,211,238,.22), transparent 60%);
            z-index:-1;
        }

        .register-card{
            width:100%;max-width:480px;
            background:linear-gradient(180deg,rgba(28,38,87,.65),rgba(17,23,56,.65));
            border:1px solid var(--border);
            border-radius:22px;padding:38px;
            backdrop-filter:blur(20px);
            position:relative;
        }
        .register-card::before{
            content:"";position:absolute;inset:0;border-radius:22px;padding:1px;
            background:linear-gradient(135deg,rgba(124,92,255,.4),transparent 40%,rgba(34,211,238,.3));
            -webkit-mask:linear-gradient(#000 0 0) content-box, linear-gradient(#000 0 0);
            -webkit-mask-composite:xor;mask-composite:exclude;
            pointer-events:none;
        }
        .brand{display:flex;align-items:center;gap:12px;margin-bottom:22px}
        .brand-mark{
            width:42px;height:42px;border-radius:12px;background:var(--grad);
            display:flex;align-items:center;justify-content:center;color:#fff;
            font-size:20px;font-weight:800;
            box-shadow:0 10px 26px rgba(124,92,255,.45);
        }
        .auth-title{font-size:24px;font-weight:800;letter-spacing:-.4px;margin:0}
        .auth-sub{color:var(--text-mut);font-size:13px;margin:2px 0 24px}

        .field{display:flex;flex-direction:column;gap:6px;margin-bottom:13px;position:relative}
        .field label{font-size:11px;color:var(--text-mut);font-weight:600;text-transform:uppercase;letter-spacing:1px}
        .field .ipt{
            background:rgba(7,10,22,.55);
            border:1px solid var(--border-2);
            color:var(--text);
            padding:11px 14px 11px 40px;border-radius:11px;
            font-size:14px;font-family:inherit;outline:none;transition:.2s;
        }
        .field .ipt:focus{border-color:var(--accent);box-shadow:0 0 0 3px rgba(124,92,255,.18)}
        .field i.lead{position:absolute;left:14px;bottom:13px;color:var(--text-mut);font-size:15px}

        .btn-aurora{
            display:inline-flex;align-items:center;justify-content:center;gap:8px;
            width:100%;padding:13px 16px;border-radius:12px;
            font-weight:700;font-size:14px;
            background:var(--grad);color:#fff;border:0;
            box-shadow:0 12px 26px rgba(124,92,255,.4);
            cursor:pointer;transition:.2s;margin-top:6px;
        }
        .btn-aurora:hover{transform:translateY(-1px);box-shadow:0 16px 32px rgba(124,92,255,.55)}

        .alert-soft{
            padding:11px 14px;border-radius:11px;
            background:rgba(52,211,153,.1);
            border:1px solid rgba(52,211,153,.3);
            color:#86efac;font-size:13px;margin-bottom:14px;
        }
        .alert-soft.danger{background:rgba(251,113,133,.1);border-color:rgba(251,113,133,.3);color:#fda4af}

        .auth-footer{margin-top:18px;text-align:center;color:var(--text-mut);font-size:13px}
        .auth-footer a{color:var(--accent-2);text-decoration:none;font-weight:600}
        .auth-footer a:hover{text-decoration:underline}
    </style>
</head>
<body>

<form method="POST" class="register-card">
    <div class="brand">
        <div class="brand-mark">H</div>
        <div>
            <h2 class="auth-title">Tạo tài khoản mới</h2>
            <p class="auth-sub">Bắt đầu hành trình quản lý cửa hàng cùng HDTT.</p>
        </div>
    </div>

    <?php if (!empty($message)): ?>
        <div class="alert-soft"><i class="bi bi-check-circle"></i> <?= htmlspecialchars($message) ?></div>
    <?php endif; ?>
    <?php if (!empty($errors)): ?>
        <div class="alert-soft danger">
            <?php foreach ($errors as $e): ?><div><?= htmlspecialchars($e) ?></div><?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div class="field">
        <label>Tài khoản</label>
        <i class="bi bi-person lead"></i>
        <input name="username" class="ipt" placeholder="username" required>
    </div>

    <div class="field">
        <label>Email</label>
        <i class="bi bi-envelope lead"></i>
        <input name="email" type="email" class="ipt" placeholder="you@example.com" required>
    </div>

    <div class="field">
        <label>Số điện thoại</label>
        <i class="bi bi-telephone lead"></i>
        <input name="std" class="ipt" placeholder="0900000000" required>
    </div>

    <div class="field">
        <label>Địa chỉ</label>
        <i class="bi bi-geo-alt lead"></i>
        <input name="diachi" class="ipt" placeholder="Hà Nội, Việt Nam" required>
    </div>

    <div class="field">
        <label>Mật khẩu</label>
        <i class="bi bi-lock lead"></i>
        <input type="password" name="password" class="ipt" placeholder="••••••••" required>
    </div>

    <button class="btn-aurora"><i class="bi bi-person-plus"></i> Tạo tài khoản</button>

    <div class="auth-footer">
        Đã có tài khoản? <a href="?act=login">Đăng nhập</a>
    </div>
</form>

</body>
</html>
