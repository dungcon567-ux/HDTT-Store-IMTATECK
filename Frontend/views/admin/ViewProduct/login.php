<?php
$error = $error ?? '';
$errors = $errors ?? [];
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng nhập · HDTT Console</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        :root{
            --bg-base:#070a16;
            --surface:#111738;
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
            margin:0;
            font-family:'Manrope',system-ui,sans-serif;
            background:var(--bg-base);
            color:var(--text);
            min-height:100vh;
            display:grid;
            grid-template-columns:1fr 1fr;
            position:relative;
            overflow:hidden;
        }
        body::before{
            content:"";position:fixed;inset:0;
            background:
                radial-gradient(800px 500px at 8% -10%, rgba(124,92,255,.30), transparent 60%),
                radial-gradient(700px 500px at 100% 0%, rgba(34,211,238,.22), transparent 60%);
            z-index:-2;
        }

        .left{
            display:flex;flex-direction:column;justify-content:space-between;
            padding:40px 50px;
            border-right:1px solid var(--border);
            background:linear-gradient(180deg,rgba(17,23,56,.5),rgba(11,16,40,.5));
            backdrop-filter:blur(20px);
        }
        .brand{display:flex;align-items:center;gap:14px}
        .brand-mark{
            width:46px;height:46px;border-radius:14px;background:var(--grad);
            display:flex;align-items:center;justify-content:center;color:#fff;
            font-size:22px;font-weight:800;
            box-shadow:0 12px 30px rgba(124,92,255,.45);
        }
        .brand-name{font-weight:800;font-size:18px}
        .brand-sub{font-size:11px;color:var(--text-mut);letter-spacing:1.4px;text-transform:uppercase}

        .hero{max-width:480px}
        .hero h1{
            font-size:48px;line-height:1.1;font-weight:800;letter-spacing:-1.2px;margin:0 0 16px;
            background:linear-gradient(180deg,#fff,#a3aed5);
            -webkit-background-clip:text;-webkit-text-fill-color:transparent;
        }
        .hero p{color:var(--text-mut);font-size:15px;line-height:1.7}

        .features{display:flex;flex-direction:column;gap:14px;margin-top:30px}
        .feat{display:flex;align-items:center;gap:14px;color:var(--text-mut);font-size:13.5px}
        .feat i{
            width:36px;height:36px;border-radius:10px;
            display:flex;align-items:center;justify-content:center;
            background:rgba(124,92,255,.15);color:#a78bfa;
        }

        .foot{color:var(--text-mut);font-size:12px}

        .right{
            display:flex;align-items:center;justify-content:center;
            padding:40px;
        }
        .auth-card{
            width:100%;max-width:420px;
            background:linear-gradient(180deg,rgba(28,38,87,.65),rgba(17,23,56,.65));
            border:1px solid var(--border);
            border-radius:22px;
            padding:38px;
            backdrop-filter:blur(20px);
            position:relative;
        }
        .auth-card::before{
            content:"";position:absolute;inset:0;border-radius:22px;padding:1px;
            background:linear-gradient(135deg,rgba(124,92,255,.4),transparent 40%,rgba(34,211,238,.3));
            -webkit-mask:linear-gradient(#000 0 0) content-box, linear-gradient(#000 0 0);
            -webkit-mask-composite:xor;mask-composite:exclude;
            pointer-events:none;
        }
        .auth-title{font-size:26px;font-weight:800;letter-spacing:-.4px;margin:0 0 6px}
        .auth-sub{color:var(--text-mut);font-size:13.5px;margin:0 0 26px}

        .field{display:flex;flex-direction:column;gap:6px;margin-bottom:14px}
        .field label{font-size:11.5px;color:var(--text-mut);font-weight:600;text-transform:uppercase;letter-spacing:1px}
        .field input{
            background:rgba(7,10,22,.55);
            border:1px solid var(--border-2);
            color:var(--text);
            padding:12px 14px;border-radius:12px;
            font-size:14px;font-family:inherit;
            outline:none;transition:.2s;
        }
        .field input:focus{border-color:var(--accent);box-shadow:0 0 0 3px rgba(124,92,255,.18)}

        .btn-aurora{
            display:inline-flex;align-items:center;justify-content:center;gap:8px;
            width:100%;padding:13px 16px;border-radius:12px;
            font-weight:700;font-size:14px;
            background:var(--grad);color:#fff;border:0;
            box-shadow:0 12px 26px rgba(124,92,255,.4);
            cursor:pointer;transition:.2s;
        }
        .btn-aurora:hover{transform:translateY(-1px);box-shadow:0 16px 32px rgba(124,92,255,.55)}

        .alert-soft{
            padding:11px 14px;border-radius:11px;
            background:rgba(251,113,133,.1);
            border:1px solid rgba(251,113,133,.3);
            color:#fda4af;font-size:13px;margin-bottom:14px;
        }

        .auth-footer{margin-top:18px;text-align:center;color:var(--text-mut);font-size:13px}
        .auth-footer a{color:var(--accent-2);text-decoration:none;font-weight:600}
        .auth-footer a:hover{text-decoration:underline}

        @media (max-width:900px){
            body{grid-template-columns:1fr}
            .left{display:none}
        }
    </style>
</head>
<body>

<div class="left">
    <div class="brand">
        <div class="brand-mark">H</div>
        <div>
            <div class="brand-name">HDTT Console</div>
            <div class="brand-sub">Admin v2</div>
        </div>
    </div>

    <div class="hero">
        <h1>Vận hành cửa hàng,<br>thông minh hơn.</h1>
        <p>Console quản trị thế hệ mới — quản lý sản phẩm, biến thể, đơn hàng và khách hàng trong một giao diện duy nhất.</p>

        <div class="features">
            <div class="feat"><i class="bi bi-box-seam"></i> Catalog đa cấp với biến thể không giới hạn</div>
            <div class="feat"><i class="bi bi-graph-up-arrow"></i> Báo cáo doanh thu theo thời gian thực</div>
            <div class="feat"><i class="bi bi-shield-check"></i> Phân quyền & xác thực bảo mật</div>
        </div>
    </div>

    <div class="foot">© <?= date('Y') ?> HDTT Store. All rights reserved.</div>
</div>

<div class="right">
    <form method="POST" class="auth-card">
        <h2 class="auth-title">Chào mừng trở lại 👋</h2>
        <p class="auth-sub">Đăng nhập vào tài khoản admin của bạn để tiếp tục.</p>

        <?php if (!empty($error)): ?>
            <div class="alert-soft"><i class="bi bi-exclamation-triangle"></i> <?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <?php if (!empty($errors)): ?>
            <div class="alert-soft">
                <?php foreach ($errors as $e): ?>
                    <div><?= htmlspecialchars($e) ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <div class="field">
            <label>Tên đăng nhập</label>
            <input name="username" placeholder="admin" required autofocus>
        </div>
        <div class="field">
            <label>Mật khẩu</label>
            <input type="password" name="password" placeholder="••••••••" required>
        </div>

        <button class="btn-aurora"><i class="bi bi-shield-lock"></i> Đăng nhập</button>

        <div class="auth-footer">
            Chưa có tài khoản? <a href="?act=register">Tạo tài khoản mới</a>
        </div>
    </form>
</div>

</body>
</html>
