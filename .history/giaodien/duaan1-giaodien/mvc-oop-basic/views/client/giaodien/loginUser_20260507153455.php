<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <title>Đăng nhập - HDTT Store</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <base href="/Duan1/giaodien/duaan1-giaodien/mvc-oop-basic/views/client/giaodien/">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>

<body>

    <div id="spinner"
        class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>

    <div class="container-fluid px-5 d-none border-bottom d-lg-block">
        <div class="row gx-0 align-items-center">
            <div class="col-lg-4 text-center text-lg-start mb-lg-0">
                <div class="d-inline-flex align-items-center" style="height: 45px;">
                    <a href="index.php?act=giaodien" class="text-muted me-2">Trang chủ</a>
                    <small> / </small>
                    <a href="#" class="text-muted mx-2">Hỗ trợ</a>
                    <small> / </small>
                    <a href="#" class="text-muted ms-2">Liên hệ</a>
                </div>
            </div>
            <div class="col-lg-4 text-center d-flex align-items-center justify-content-center">
                <small class="text-dark">Số điện thoại:</small>
                <a href="#" class="text-muted ms-1">0866914326</a>
            </div>
            <div class="col-lg-4 text-center text-lg-end">
                <div class="d-inline-flex align-items-center" style="height: 45px;">
                    <a href="index.php?act=registerUser" class="text-muted ms-2">
                        <small><i class="fa fa-user-plus me-2"></i>Đăng ký</small>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid px-5 py-4 d-none d-lg-block">
        <div class="row gx-0 align-items-center text-center">
            <div class="col-md-4 col-lg-3 text-center text-lg-start">
                <div class="d-inline-flex align-items-center">
                    <a href="index.php?act=giaodien" class="navbar-brand p-0">
                        <h1 class="display-5 text-primary m-0">
                            <i class="fas fa-shopping-bag text-secondary me-2"></i>HDTT Store
                        </h1>
                    </a>
                </div>
            </div>
            <div class="col-md-4 col-lg-6 text-center">
                <div class="position-relative ps-4">
                    <div class="d-flex border rounded-pill">
                        <input class="form-control border-0 rounded-pill w-100 py-3" type="text" placeholder="Tìm kiếm...">
                        <button type="button" class="btn btn-primary rounded-pill py-3 px-5" style="border: 0;">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-lg-3 text-center text-lg-end">
                <div class="d-inline-flex align-items-center">
                    <a href="#" class="text-muted d-flex align-items-center justify-content-center me-3">
                        <span class="rounded-circle btn-md-square border"><i class="fas fa-heart"></i></span>
                    </a>
                    <a href="#" class="text-muted d-flex align-items-center justify-content-center">
                        <span class="rounded-circle btn-md-square border"><i class="fas fa-shopping-cart"></i></span>
                        <span class="text-dark ms-2">0.vnd</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid nav-bar p-0">
        <div class="row gx-0 bg-primary px-5 align-items-center">
            <div class="col-12">
                <nav class="navbar navbar-expand-lg navbar-light bg-primary">
                    <div class="collapse navbar-collapse show">
                        <div class="navbar-nav ms-auto py-0">
                            <a href="/Duan1/giaodien/duaan1-giaodien/mvc-oop-basic/index.php?act=giaodien" class="nav-item nav-link">Trang chủ</a>
                            <a href="#" class="nav-item nav-link active">Đăng nhập</a>
                            <a href="/Duan1/giaodien/duaan1-giaodien/mvc-oop-basic/index.php?act=registerUser" class="nav-item nav-link">Đăng ký</a>
                        </div>
                        <a href="#" class="btn btn-secondary rounded-pill py-2 px-4 px-lg-3">
                            <i class="fa fa-mobile-alt me-2"></i>0866914326
                        </a>
                    </div>
                </nav>
            </div>
        </div>
    </div>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        .auth-stage{
            position:relative;min-height:calc(100vh - 60px);
            background:
              radial-gradient(circle at 15% 20%, #FFE9D5 0%, transparent 45%),
              radial-gradient(circle at 85% 75%, #FFE0B8 0%, transparent 50%),
              linear-gradient(135deg, #FBF6ED 0%, #F5E9D8 50%, #F0DCC0 100%);
            overflow:hidden;padding:60px 16px;
            display:flex;align-items:center;justify-content:center;
            font-family:'Plus Jakarta Sans',system-ui,sans-serif;
        }
        .auth-stage::before,.auth-stage::after{
            content:"";position:absolute;border-radius:50%;
            filter:blur(70px);pointer-events:none;
            animation:authFloat 14s ease-in-out infinite;
        }
        .auth-stage::before{
            width:520px;height:520px;left:-180px;top:-160px;
            background:radial-gradient(circle,rgba(202,138,4,.32) 0%,rgba(202,138,4,0) 70%);
        }
        .auth-stage::after{
            width:480px;height:480px;right:-160px;bottom:-160px;
            background:radial-gradient(circle,rgba(194,65,12,.28) 0%,rgba(194,65,12,0) 70%);
            animation-delay:-7s;
        }
        @keyframes authFloat{
            0%,100%{transform:translate(0,0) scale(1)}
            50%{transform:translate(30px,-25px) scale(1.08)}
        }
        .auth-blob{
            position:absolute;width:360px;height:360px;border-radius:50%;
            background:radial-gradient(circle,rgba(251,146,60,.35) 0%,rgba(251,146,60,0) 70%);
            filter:blur(40px);pointer-events:none;
            top:35%;left:50%;transform:translate(-50%,-50%);
            animation:authPulse 6s ease-in-out infinite;
        }
        @keyframes authPulse{
            0%,100%{opacity:.45;transform:translate(-50%,-50%) scale(1)}
            50%{opacity:.7;transform:translate(-50%,-50%) scale(1.15)}
        }
        .auth-grid-bg{
            position:absolute;inset:0;
            background-image:
                linear-gradient(rgba(42,31,20,.05) 1px,transparent 1px),
                linear-gradient(90deg,rgba(42,31,20,.05) 1px,transparent 1px);
            background-size:50px 50px;mask-image:radial-gradient(ellipse at center,black 30%,transparent 80%);
            -webkit-mask-image:radial-gradient(ellipse at center,black 30%,transparent 80%);
        }

        .auth-card{
            position:relative;z-index:2;
            width:100%;max-width:980px;
            background:#FFFEFB;
            border:1px solid rgba(42,31,20,.08);
            border-radius:24px;overflow:hidden;
            box-shadow:0 30px 70px rgba(42,31,20,.15), 0 8px 24px rgba(42,31,20,.06);
            display:grid;grid-template-columns:1fr 1.1fr;
            animation:authPop .6s cubic-bezier(.16,1,.3,1);
        }
        @keyframes authPop{from{opacity:0;transform:translateY(20px) scale(.97)}to{opacity:1;transform:none}}
        @media(max-width:840px){.auth-card{grid-template-columns:1fr}}

        .auth-side{
            position:relative;padding:48px 40px;
            background:linear-gradient(140deg,#4338ca 0%,#6d28d9 50%,#3b82f6 100%);
            color:#fff;overflow:hidden;
            display:flex;flex-direction:column;justify-content:space-between;
            min-height:540px;
        }
        .auth-side::before{
            content:"";position:absolute;width:300px;height:300px;border-radius:50%;
            background:rgba(255,255,255,.08);top:-100px;right:-80px;filter:blur(40px);
        }
        .auth-side::after{
            content:"";position:absolute;width:240px;height:240px;border-radius:50%;
            background:rgba(252,211,77,.25);bottom:-80px;left:-60px;filter:blur(40px);
        }
        .auth-side-inner{position:relative;z-index:2}
        .auth-brand{
            display:inline-flex;align-items:center;gap:10px;
            padding:8px 16px;border-radius:999px;
            background:rgba(255,255,255,.18);backdrop-filter:blur(8px);
            font-size:13px;font-weight:700;letter-spacing:1.5px;
            margin-bottom:32px;
        }
        .auth-side h2{
            font-size:36px;font-weight:800;line-height:1.15;margin:0 0 14px;
            letter-spacing:-1px;
        }
        .auth-side h2 span{
            background:linear-gradient(135deg,#fcd34d,#fb923c);
            -webkit-background-clip:text;-webkit-text-fill-color:transparent;
        }
        .auth-side p{font-size:14.5px;color:rgba(255,255,255,.85);line-height:1.65;margin:0 0 28px}
        .auth-side ul{list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:14px;position:relative;z-index:2}
        .auth-side ul li{
            display:flex;align-items:center;gap:12px;
            font-size:13.5px;color:rgba(255,255,255,.92);
        }
        .auth-side ul li i{
            width:32px;height:32px;border-radius:10px;
            background:rgba(255,255,255,.2);
            display:inline-flex;align-items:center;justify-content:center;
            font-size:12px;flex-shrink:0;
        }

        .auth-form-side{padding:48px 44px;background:#fff;color:#0f172a}
        .auth-head{margin-bottom:28px}
        .auth-head h3{font-size:28px;font-weight:800;margin:0 0 6px;letter-spacing:-.5px;color:#0f172a}
        .auth-head p{color:#64748b;font-size:14px;margin:0}

        .auth-alert{
            display:flex;gap:10px;padding:12px 14px;border-radius:12px;
            background:#fef2f2;border:1px solid #fecaca;color:#991b1b;
            font-size:13.5px;margin-bottom:18px;
            animation:shake .35s;
        }
        .auth-alert i{flex-shrink:0;color:#dc2626;font-size:16px;margin-top:1px}
        .auth-alert ul{margin:0;padding-left:16px}
        @keyframes shake{
            0%,100%{transform:translateX(0)}
            25%{transform:translateX(-5px)}
            75%{transform:translateX(5px)}
        }

        .auth-field{position:relative;margin-bottom:16px}
        .auth-field-label{
            display:block;font-size:12.5px;font-weight:600;color:#475569;
            margin-bottom:6px;letter-spacing:.3px;
        }
        .auth-input-wrap{position:relative}
        .auth-input-wrap > i.fld-icon{
            position:absolute;left:16px;top:50%;transform:translateY(-50%);
            color:#94a3b8;font-size:14px;transition:color .2s;pointer-events:none;
        }
        .auth-input{
            width:100%;padding:14px 16px 14px 44px;
            background:#f8fafc;border:1.5px solid #e5e7eb;border-radius:12px;
            font-size:14.5px;color:#0f172a;font-weight:500;
            font-family:inherit;
            transition:all .2s;outline:none;
        }
        .auth-input::placeholder{color:#94a3b8;font-weight:400}
        .auth-input:focus{
            background:#fff;border-color:#6366f1;
            box-shadow:0 0 0 4px rgba(99,102,241,.12);
        }
        .auth-input:focus + i.fld-icon,
        .auth-input-wrap:focus-within > i.fld-icon{color:#6366f1}
        .auth-toggle-pwd{
            position:absolute;right:14px;top:50%;transform:translateY(-50%);
            background:transparent;border:0;padding:6px;cursor:pointer;
            color:#94a3b8;transition:color .2s;
        }
        .auth-toggle-pwd:hover{color:#6366f1}

        .auth-row-extra{
            display:flex;align-items:center;justify-content:space-between;
            margin:6px 0 22px;font-size:13px;
        }
        .auth-row-extra label{display:flex;align-items:center;gap:6px;color:#475569;cursor:pointer;font-weight:500}
        .auth-row-extra label input{accent-color:#6366f1}
        .auth-row-extra a{color:#6366f1;text-decoration:none;font-weight:600}
        .auth-row-extra a:hover{color:#4338ca;text-decoration:underline}

        .auth-submit{
            width:100%;padding:14px;border:0;border-radius:12px;
            background:linear-gradient(135deg,#6366f1 0%,#8b5cf6 50%,#3b82f6 100%);
            background-size:200% 200%;
            color:#fff;font-size:15px;font-weight:700;letter-spacing:.4px;
            cursor:pointer;position:relative;overflow:hidden;
            box-shadow:0 12px 28px rgba(99,102,241,.4);
            transition:transform .2s, box-shadow .2s, background-position .4s;
            font-family:inherit;
            display:inline-flex;align-items:center;justify-content:center;gap:8px;
        }
        .auth-submit:hover{
            transform:translateY(-2px);
            box-shadow:0 16px 36px rgba(99,102,241,.55);
            background-position:100% 0;
        }
        .auth-submit::after{
            content:"";position:absolute;top:0;left:-100%;width:100%;height:100%;
            background:linear-gradient(120deg,transparent,rgba(255,255,255,.35),transparent);
            transition:left .6s ease;
        }
        .auth-submit:hover::after{left:100%}

        .auth-divider{
            display:flex;align-items:center;gap:14px;margin:22px 0 18px;
            color:#cbd5e1;font-size:12px;font-weight:600;letter-spacing:1px;
        }
        .auth-divider::before,.auth-divider::after{
            content:"";flex:1;height:1px;background:#e5e7eb;
        }

        .auth-foot{text-align:center;font-size:14px;color:#64748b;margin-top:6px}
        .auth-foot a{color:#6366f1;font-weight:700;text-decoration:none}
        .auth-foot a:hover{text-decoration:underline}

        .auth-back{
            position:absolute;top:24px;left:24px;z-index:5;
            display:inline-flex;align-items:center;gap:8px;
            padding:8px 14px;border-radius:999px;
            background:rgba(255,254,251,.85);backdrop-filter:blur(8px);
            border:1px solid rgba(42,31,20,.1);
            color:#2A1F14;text-decoration:none;font-size:13px;font-weight:600;
            box-shadow:0 4px 12px rgba(42,31,20,.06);
            transition:all .2s;
        }
        .auth-back:hover{background:#FFFEFB;color:#C2410C;transform:translateX(-2px);box-shadow:0 6px 16px rgba(194,65,12,.15)}
    </style>

    <section class="auth-stage">
        <div class="auth-grid-bg"></div>
        <div class="auth-blob"></div>
        <a href="/Duan1/giaodien/duaan1-giaodien/mvc-oop-basic/index.php?act=giaodien" class="auth-back">
            <i class="fas fa-arrow-left"></i> Trang chủ
        </a>

        <div class="auth-card">
            <!-- LEFT: branding -->
            <aside class="auth-side">
                <div class="auth-side-inner">
                    <span class="auth-brand"><i class="fas fa-shopping-bag"></i> HDTT STORE</span>
                    <h2>Chào mừng <span>trở lại!</span></h2>
                    <p>Đăng nhập để tiếp tục mua sắm những bộ trang phục mới nhất với ưu đãi dành riêng cho bạn.</p>
                </div>
                <ul>
                    <li><i class="fas fa-bolt"></i> Đặt hàng nhanh — không cần nhập lại địa chỉ</li>
                    <li><i class="fas fa-gift"></i> Tích điểm nhận voucher giảm giá</li>
                    <li><i class="fas fa-heart"></i> Lưu sản phẩm yêu thích, theo dõi đơn hàng</li>
                </ul>
            </aside>

            <!-- RIGHT: form -->
            <div class="auth-form-side">
                <div class="auth-head">
                    <h3>Đăng nhập</h3>
                    <p>Nhập thông tin tài khoản của bạn để tiếp tục</p>
                </div>

                <?php if (!empty($errors)): ?>
                    <div class="auth-alert">
                        <i class="fas fa-exclamation-circle"></i>
                        <ul><?php foreach ($errors as $item): ?><li><?= htmlspecialchars($item) ?></li><?php endforeach; ?></ul>
                    </div>
                <?php endif; ?>
                <?php if (!empty($error)): ?>
                    <div class="auth-alert">
                        <i class="fas fa-exclamation-circle"></i>
                        <span><?= htmlspecialchars($error) ?></span>
                    </div>
                <?php endif; ?>

                <form method="POST" action="/Duan1/giaodien/duaan1-giaodien/mvc-oop-basic/index.php?act=loginUser" autocomplete="on">
                    <div class="auth-field">
                        <label class="auth-field-label" for="email">EMAIL</label>
                        <div class="auth-input-wrap">
                            <input class="auth-input" type="email" id="email" name="email"
                                   placeholder="ban@email.com" autocomplete="email" required>
                            <i class="fas fa-envelope fld-icon"></i>
                        </div>
                    </div>

                    <div class="auth-field">
                        <label class="auth-field-label" for="password">MẬT KHẨU</label>
                        <div class="auth-input-wrap">
                            <input class="auth-input" type="password" id="password" name="password"
                                   placeholder="••••••••" autocomplete="current-password" required style="padding-right:46px">
                            <i class="fas fa-lock fld-icon"></i>
                            <button type="button" class="auth-toggle-pwd" onclick="(function(b){var i=b.parentNode.querySelector('input');var ic=b.querySelector('i');var s=i.type==='password';i.type=s?'text':'password';ic.className=s?'fas fa-eye-slash':'fas fa-eye';})(this)">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="auth-row-extra">
                        <label><input type="checkbox" name="remember"> Ghi nhớ đăng nhập</label>
                        <a href="#">Quên mật khẩu?</a>
                    </div>

                    <button type="submit" class="auth-submit">
                        <i class="fas fa-sign-in-alt"></i> Đăng nhập
                    </button>

                    <div class="auth-divider">HOẶC</div>

                    <p class="auth-foot">
                        Chưa có tài khoản?
                        <a href="/Duan1/giaodien/duaan1-giaodien/mvc-oop-basic/index.php?act=registerUser">Đăng ký ngay</a>
                    </p>
                </form>
            </div>
        </div>
    </section>

    <a href="#" class="btn btn-primary btn-lg-square back-to-top"><i class="fa fa-arrow-up"></i></a>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/wow/wow.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="js/main.js"></script>
</body>
</html>