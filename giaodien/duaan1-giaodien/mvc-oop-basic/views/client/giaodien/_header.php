<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<?php
$pageTitle = $pageTitle ?? 'HDTT Store';
$categoryMap = $categoryMap ?? [
    1 => ['name' => 'Áo',   'icon' => 'fa-tshirt'],
    2 => ['name' => 'Quần', 'icon' => 'fa-user-tie'],
    3 => ['name' => 'Giày', 'icon' => 'fa-shoe-prints'],
];

// Lấy avatar user 1 lần / phiên đăng nhập, cache vào session
if (isset($_SESSION['user_id']) && !array_key_exists('user_avatar', $_SESSION)) {
    try {
        $pdo = new PDO("mysql:host=localhost;port=3306;dbname=php-oop-basic", 'root', '');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $pdo->prepare("SELECT avatar FROM users WHERE id = ? LIMIT 1");
        $stmt->execute([(int)$_SESSION['user_id']]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $_SESSION['user_avatar'] = $row['avatar'] ?? '';
    } catch (Throwable $e) {
        $_SESSION['user_avatar'] = '';
    }
}
$__avatarFile = $_SESSION['user_avatar'] ?? '';
$__avatarUrl  = $__avatarFile
    ? '/Duan1/giaodien/duaan1-giaodien/mvc-oop-basic/uploads/' . rawurlencode($__avatarFile)
    : '';
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <title><?= htmlspecialchars($pageTitle) ?> - HDTT Store</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <base href="/Duan1/giaodien/duaan1-giaodien/mvc-oop-basic/views/client/giaodien/">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">

    <style>
        /* ====== USER DROPDOWN (topbar) ====== */
        .hdtt-user-toggle{
            display:inline-flex;align-items:center;gap:10px;
            padding:4px 14px 4px 4px;border-radius:999px;
            background:#fff;border:1px solid #ececec;
            text-decoration:none;color:#1e293b;
            transition:all .2s ease;cursor:pointer;
        }
        .hdtt-user-toggle:hover{
            border-color:#0d6efd;box-shadow:0 6px 16px rgba(13,110,253,.12);
            color:#0d6efd;
        }
        .hdtt-user-toggle::after{display:none !important}
        .hdtt-user-avatar{
            width:34px;height:34px;border-radius:50%;
            background:linear-gradient(135deg,#3b82f6,#6366f1);
            color:#fff;display:flex;align-items:center;justify-content:center;
            font-weight:700;font-size:14px;
            overflow:hidden;flex-shrink:0;
            border:2px solid #fff;box-shadow:0 2px 6px rgba(0,0,0,.1);
        }
        .hdtt-user-avatar img{width:100%;height:100%;object-fit:cover;display:block}
        .hdtt-user-name{
            font-size:13px;font-weight:600;line-height:1.2;
            max-width:120px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;
        }
        .hdtt-user-name small{color:#94a3b8;font-size:10.5px;font-weight:500;display:block}
        .hdtt-user-chev{font-size:10px;color:#94a3b8;transition:transform .2s}
        .hdtt-user-toggle[aria-expanded="true"] .hdtt-user-chev{transform:rotate(180deg);color:#0d6efd}

        .hdtt-user-menu{
            min-width:280px;padding:0;border:0;
            border-radius:14px;overflow:hidden;
            box-shadow:0 20px 50px rgba(15,23,42,.15), 0 4px 12px rgba(15,23,42,.08);
            margin-top:8px !important;
        }
        .hdtt-user-menu-head{
            padding:18px 20px;
            background:linear-gradient(135deg,#3b82f6,#6366f1);
            color:#fff;display:flex;align-items:center;gap:12px;
        }
        .hdtt-user-menu-avatar{
            width:48px;height:48px;border-radius:50%;
            background:rgba(255,255,255,.2);overflow:hidden;
            display:flex;align-items:center;justify-content:center;
            border:2px solid rgba(255,255,255,.6);font-weight:700;font-size:18px;
            flex-shrink:0;
        }
        .hdtt-user-menu-avatar img{width:100%;height:100%;object-fit:cover}
        .hdtt-user-menu-info{line-height:1.3;min-width:0;flex:1}
        .hdtt-user-menu-info strong{display:block;font-size:15px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
        .hdtt-user-menu-info small{display:block;color:rgba(255,255,255,.85);font-size:11.5px;letter-spacing:.4px;text-transform:uppercase;margin-top:2px}
        .hdtt-user-menu-body{padding:8px 0}
        .hdtt-user-menu .dropdown-item{
            display:flex;align-items:center;gap:12px;
            padding:10px 18px;font-size:14px;color:#334155;
            transition:all .15s ease;
        }
        .hdtt-user-menu .dropdown-item i{
            width:30px;height:30px;border-radius:8px;
            background:#f1f5f9;color:#475569;
            display:inline-flex;align-items:center;justify-content:center;
            font-size:13px;transition:all .15s;
        }
        .hdtt-user-menu .dropdown-item:hover{background:#f8fafc;color:#0d6efd}
        .hdtt-user-menu .dropdown-item:hover i{background:#dbeafe;color:#0d6efd}
        .hdtt-user-menu .dropdown-item.text-danger:hover{background:#fef2f2;color:#dc2626}
        .hdtt-user-menu .dropdown-item.text-danger:hover i{background:#fee2e2;color:#dc2626}
        .hdtt-user-menu .dropdown-divider{margin:4px 0;border-color:#eef0f4}
        .hdtt-user-menu .dropdown-item .badge{margin-left:auto;font-size:10px;padding:3px 8px}

        /* ====== AUTH (chưa đăng nhập) ====== */
        .hdtt-auth-btns{display:inline-flex;gap:8px;align-items:center}
        .hdtt-auth-btn{
            padding:6px 16px;border-radius:999px;font-size:13px;font-weight:600;
            text-decoration:none;transition:all .2s;
            display:inline-flex;align-items:center;gap:6px;
        }
        .hdtt-auth-btn.login{color:#0d6efd;border:1px solid #cbd5e1;background:#fff}
        .hdtt-auth-btn.login:hover{border-color:#0d6efd;background:#eff6ff}
        .hdtt-auth-btn.register{
            background:linear-gradient(135deg,#3b82f6,#6366f1);color:#fff;
            box-shadow:0 4px 12px rgba(59,130,246,.25);
        }
        .hdtt-auth-btn.register:hover{transform:translateY(-1px);box-shadow:0 6px 18px rgba(59,130,246,.4);color:#fff}

        /* ====== SEARCH BAR · Category select ====== */
        .hdtt-search{
            display:flex;align-items:stretch;
            background:#fff;border-radius:999px;
            box-shadow:0 4px 14px rgba(15,23,42,.06);
            border:1px solid #e5e7eb;position:relative;
            transition:box-shadow .2s, border-color .2s;
        }
        .hdtt-search:focus-within{border-color:#3b82f6;box-shadow:0 6px 20px rgba(59,130,246,.18)}
        .hdtt-search input.hdtt-search-input{
            flex:1;border:0;outline:0;background:transparent;
            padding:12px 22px;font-size:14px;color:#0f172a;min-width:0;
            border-top-left-radius:999px;border-bottom-left-radius:999px;
        }
        .hdtt-search input.hdtt-search-input::placeholder{color:#94a3b8}
        .hdtt-search-cat-wrap{
            position:relative;display:flex;align-items:center;
            border-left:1px solid #e5e7eb;background:#f8fafc;
            padding:0 16px;min-width:170px;cursor:pointer;
        }
        .hdtt-search-cat-wrap:hover{background:#eef2ff}
        .hdtt-search-cat-wrap i.cat-leading{color:#0d6efd;margin-right:8px;font-size:13px}
        .hdtt-search-cat-wrap i.cat-chev{color:#94a3b8;margin-left:auto;font-size:11px;transition:transform .2s}
        .hdtt-search-cat-wrap:has(select:focus) i.cat-chev{transform:rotate(180deg);color:#0d6efd}
        .hdtt-search-cat{
            appearance:none;-webkit-appearance:none;-moz-appearance:none;
            border:0;outline:0;background:transparent;
            padding:12px 0;padding-right:6px;
            font-size:13.5px;font-weight:600;color:#0f172a;
            cursor:pointer;flex:1;
            background-image:none !important;
        }
        .hdtt-search-btn{
            background:linear-gradient(135deg,#3b82f6,#6366f1);
            color:#fff;border:0;padding:0 28px;font-weight:600;
            display:inline-flex;align-items:center;gap:8px;
            transition:filter .15s;font-size:14px;
            border-top-right-radius:999px;border-bottom-right-radius:999px;
        }
        .hdtt-search-btn:hover{filter:brightness(1.1)}

        /* ====== CATEGORY CUSTOM DROPDOWN (thay <select> native) ====== */
        .hdtt-cat-dd{position:relative;min-width:180px;display:flex;align-items:stretch;cursor:pointer}
        .hdtt-cat-dd-trigger{
            display:flex;align-items:center;width:100%;
            padding:0 16px;border-left:1px solid #e5e7eb;
            background:#f8fafc;font-size:13.5px;font-weight:600;color:#0f172a;
            transition:background .15s;user-select:none;
        }
        .hdtt-cat-dd-trigger:hover{background:#eef2ff}
        .hdtt-cat-dd-trigger .cat-leading{color:#0d6efd;margin-right:8px;font-size:13px}
        .hdtt-cat-dd-trigger .cat-label{flex:1;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
        .hdtt-cat-dd-trigger .cat-chev{color:#94a3b8;margin-left:auto;font-size:11px;transition:transform .25s}
        .hdtt-cat-dd.open .hdtt-cat-dd-trigger{background:#eef2ff;color:#0d6efd}
        .hdtt-cat-dd.open .hdtt-cat-dd-trigger .cat-chev{transform:rotate(180deg);color:#0d6efd}

        .hdtt-cat-dd-panel{
            position:absolute;top:calc(100% + 10px);left:0;right:0;
            background:#fff;border-radius:14px;
            box-shadow:0 20px 50px rgba(15,23,42,.15), 0 4px 12px rgba(15,23,42,.06);
            border:1px solid #eef0f4;
            padding:8px;z-index:1000;
            opacity:0;visibility:hidden;transform:translateY(-6px);
            transition:opacity .18s ease, transform .18s ease, visibility .18s;
            min-width:220px;
        }
        .hdtt-cat-dd.open .hdtt-cat-dd-panel{opacity:1;visibility:visible;transform:translateY(0)}
        .hdtt-cat-dd-opt{
            display:flex;align-items:center;gap:10px;
            padding:10px 12px;border-radius:10px;
            font-size:13.5px;font-weight:500;color:#334155;
            cursor:pointer;transition:all .15s;
        }
        .hdtt-cat-dd-opt:hover{background:#f1f5f9;color:#0d6efd}
        .hdtt-cat-dd-opt.active{background:linear-gradient(135deg,#eff6ff,#dbeafe);color:#0d6efd;font-weight:700}
        .hdtt-cat-dd-opt i.opt-icon{
            width:30px;height:30px;border-radius:8px;
            background:#f1f5f9;color:#475569;
            display:inline-flex;align-items:center;justify-content:center;
            font-size:13px;flex-shrink:0;transition:all .15s;
        }
        .hdtt-cat-dd-opt:hover i.opt-icon{background:#dbeafe;color:#0d6efd}
        .hdtt-cat-dd-opt.active i.opt-icon{background:#0d6efd;color:#fff}
        .hdtt-cat-dd-opt .opt-check{margin-left:auto;color:#0d6efd;font-size:13px;display:none}
        .hdtt-cat-dd-opt.active .opt-check{display:inline-flex}

        /* ====== "DANH MỤC SẢN PHẨM" navbar panel ====== */
        .hdtt-allcat-toggle{
            background:transparent;border:0;width:100%;
            padding:14px 18px;text-align:left;color:#fff;
            display:flex;align-items:center;gap:12px;
            font-size:15px;font-weight:700;letter-spacing:.4px;
            transition:background .15s;cursor:pointer;
        }
        .hdtt-allcat-toggle:hover{background:rgba(255,255,255,.1)}
        .hdtt-allcat-toggle i.bars{
            width:34px;height:34px;border-radius:10px;
            background:rgba(255,255,255,.15);color:#fff;
            display:inline-flex;align-items:center;justify-content:center;font-size:14px;
        }
        .hdtt-allcat-toggle i.chev{margin-left:auto;font-size:11px;transition:transform .25s;opacity:.7}
        .hdtt-allcat-toggle[aria-expanded="true"] i.chev{transform:rotate(180deg);opacity:1}

        .hdtt-allcat-panel{
            background:#fff;border-radius:14px;
            box-shadow:0 20px 50px rgba(15,23,42,.18), 0 4px 12px rgba(15,23,42,.08);
            margin-top:6px;padding:10px;
            min-width:260px;border:1px solid #eef0f4;
        }
        .hdtt-allcat-item{
            display:flex;align-items:center;gap:12px;
            padding:11px 14px;border-radius:10px;
            color:#334155;text-decoration:none;
            font-size:14px;font-weight:600;
            transition:all .18s ease;
        }
        .hdtt-allcat-item:hover{
            background:linear-gradient(135deg,#eff6ff,#fff);
            color:#0d6efd;transform:translateX(4px);
        }
        .hdtt-allcat-item i.icon{
            width:36px;height:36px;border-radius:10px;
            background:#f1f5f9;color:#475569;
            display:inline-flex;align-items:center;justify-content:center;
            font-size:15px;transition:all .18s;
        }
        .hdtt-allcat-item:hover i.icon{
            background:linear-gradient(135deg,#3b82f6,#6366f1);color:#fff;
            transform:scale(1.06);
        }
        .hdtt-allcat-item.all i.icon{background:linear-gradient(135deg,#fef3c7,#fde68a);color:#d97706}
        .hdtt-allcat-item.all:hover i.icon{background:linear-gradient(135deg,#f59e0b,#d97706);color:#fff}
        .hdtt-allcat-item .arrow{margin-left:auto;font-size:11px;color:#cbd5e1;opacity:0;transition:all .18s}
        .hdtt-allcat-item:hover .arrow{opacity:1;color:#0d6efd;transform:translateX(2px)}
    </style>
</head>

<body>

    <!-- Topbar Start -->
    <div class="container-fluid px-5 d-none border-bottom d-lg-block">
        <div class="row gx-0 align-items-center">
            <div class="col-lg-4 text-center text-lg-start mb-lg-0">
                <div class="d-inline-flex align-items-center" style="height: 45px;">
                    <a href="#" class="text-muted me-2"> Trợ giúp</a><small> / </small>
                    <a href="#" class="text-muted mx-2"> Hỗ trợ</a><small> / </small>
                    <a href="#" class="text-muted ms-2"> Liên hệ</a>
                </div>
            </div>

            <div class="col-lg-4 text-center d-flex align-items-center justify-content-center">
                <small class="text-dark">Hotline:</small>
                <span class="text-muted ms-2">0866914326</span>
            </div>

            <div class="col-lg-4 text-center text-lg-end">
                <div class="d-inline-flex align-items-center" style="height: 45px;">
                    <?php if (!isset($_SESSION['user'])): ?>
                        <div class="hdtt-auth-btns">
                            <a href="/Duan1/giaodien/duaan1-giaodien/mvc-oop-basic/index.php?act=loginUser" class="hdtt-auth-btn login">
                                <i class="fas fa-sign-in-alt"></i> Đăng nhập
                            </a>
                            <a href="/Duan1/giaodien/duaan1-giaodien/mvc-oop-basic/index.php?act=registerUser" class="hdtt-auth-btn register">
                                <i class="fas fa-user-plus"></i> Đăng ký
                            </a>
                        </div>
                    <?php else: ?>
                        <?php $__userInitial = mb_strtoupper(mb_substr($_SESSION['user'], 0, 1)); ?>
                        <div class="dropdown">
                            <a href="#" class="hdtt-user-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="hdtt-user-avatar">
                                    <?php if ($__avatarUrl): ?>
                                        <img src="<?= htmlspecialchars($__avatarUrl) ?>" alt="avatar"
                                             onerror="this.parentNode.innerHTML='<?= htmlspecialchars($__userInitial) ?>'">
                                    <?php else: ?>
                                        <?= htmlspecialchars($__userInitial) ?>
                                    <?php endif; ?>
                                </span>
                                <span class="hdtt-user-name text-start">
                                    <?= htmlspecialchars($_SESSION['user']) ?>
                                    <small>Xem tài khoản</small>
                                </span>
                                <i class="fas fa-chevron-down hdtt-user-chev"></i>
                            </a>

                            <div class="dropdown-menu dropdown-menu-end hdtt-user-menu">
                                <div class="hdtt-user-menu-head">
                                    <div class="hdtt-user-menu-avatar">
                                        <?php if ($__avatarUrl): ?>
                                            <img src="<?= htmlspecialchars($__avatarUrl) ?>" alt="avatar"
                                                 onerror="this.parentNode.innerHTML='<?= htmlspecialchars($__userInitial) ?>'">
                                        <?php else: ?>
                                            <?= htmlspecialchars($__userInitial) ?>
                                        <?php endif; ?>
                                    </div>
                                    <div class="hdtt-user-menu-info">
                                        <strong><?= htmlspecialchars($_SESSION['user']) ?></strong>
                                        <small><i class="fas fa-circle" style="font-size:7px;color:#86efac"></i> Đã đăng nhập</small>
                                    </div>
                                </div>
                                <div class="hdtt-user-menu-body">
                                    <a href="/Duan1/giaodien/duaan1-giaodien/mvc-oop-basic/index.php?act=profile" class="dropdown-item">
                                        <i class="fas fa-user-circle"></i> Thông tin cá nhân
                                    </a>
                                    <a href="/Duan1/giaodien/duaan1-giaodien/mvc-oop-basic/index.php?act=myOrders" class="dropdown-item">
                                        <i class="fas fa-box"></i> Đơn hàng của tôi
                                    </a>
                                    <a href="/Duan1/giaodien/duaan1-giaodien/mvc-oop-basic/index.php?act=wishlist" class="dropdown-item">
                                        <i class="fas fa-heart"></i> Sản phẩm yêu thích
                                    </a>
                                    <a href="/Duan1/giaodien/duaan1-giaodien/mvc-oop-basic/index.php?act=cart" class="dropdown-item">
                                        <i class="fas fa-shopping-cart"></i> Giỏ hàng
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <a href="/Duan1/giaodien/duaan1-giaodien/mvc-oop-basic/index.php?act=logout" class="dropdown-item text-danger">
                                        <i class="fas fa-sign-out-alt"></i> Đăng xuất
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <!-- Topbar End -->

    <div class="container-fluid px-5 py-4 d-none d-lg-block">
        <div class="row gx-0 align-items-center text-center">
            <div class="col-md-4 col-lg-3 text-center text-lg-start">
                <div class="d-inline-flex align-items-center">
                    <a href="/Duan1/giaodien/duaan1-giaodien/mvc-oop-basic/index.php?act=giaodien" class="navbar-brand p-0">
                        <h1 class="display-5 text-primary m-0"><i
                                class="fas fa-shopping-bag text-secondary me-2"></i>HDTT Store</h1>
                    </a>
                </div>
            </div>

            <div class="col-md-4 col-lg-6 text-center">
                <div class="position-relative ps-4">
                    <form method="GET" action="/Duan1/giaodien/duaan1-giaodien/mvc-oop-basic/index.php">
                        <input type="hidden" name="act" value="giaodien">

                        <?php
                            $__currentCat = (int)($_GET['category'] ?? 0);
                            $__currentCatName = $__currentCat > 0 && isset($categoryMap[$__currentCat])
                                ? $categoryMap[$__currentCat]['name']
                                : 'Tất cả danh mục';
                        ?>
                        <div class="hdtt-search">
                            <input class="hdtt-search-input"
                                type="text"
                                name="keyword"
                                placeholder="Bạn đang tìm gì hôm nay?"
                                value="<?= htmlspecialchars($_GET['keyword'] ?? '') ?>">

                            <div class="hdtt-cat-dd" id="hdttCatDd">
                                <input type="hidden" name="category" id="hdttCatVal" value="<?= $__currentCat > 0 ? $__currentCat : '' ?>">
                                <div class="hdtt-cat-dd-trigger">
                                    <i class="fas fa-th-large cat-leading"></i>
                                    <span class="cat-label" id="hdttCatLabel"><?= htmlspecialchars($__currentCatName) ?></span>
                                    <i class="fas fa-chevron-down cat-chev"></i>
                                </div>
                                <div class="hdtt-cat-dd-panel">
                                    <div class="hdtt-cat-dd-opt <?= $__currentCat === 0 ? 'active' : '' ?>" data-val="" data-label="Tất cả danh mục">
                                        <i class="fas fa-th opt-icon"></i>
                                        <span>Tất cả danh mục</span>
                                        <i class="fas fa-check opt-check"></i>
                                    </div>
                                    <?php foreach ($categoryMap as $catId => $cat): ?>
                                        <div class="hdtt-cat-dd-opt <?= $__currentCat === $catId ? 'active' : '' ?>"
                                             data-val="<?= $catId ?>" data-label="<?= htmlspecialchars($cat['name']) ?>">
                                            <i class="fas <?= $cat['icon'] ?> opt-icon"></i>
                                            <span><?= htmlspecialchars($cat['name']) ?></span>
                                            <i class="fas fa-check opt-check"></i>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <button type="submit" class="hdtt-search-btn">
                                <i class="fas fa-search"></i> Tìm
                            </button>
                        </div>
                        <script>
                        (function(){
                            const dd = document.getElementById('hdttCatDd');
                            if (!dd) return;
                            const trigger = dd.querySelector('.hdtt-cat-dd-trigger');
                            const label = document.getElementById('hdttCatLabel');
                            const valInput = document.getElementById('hdttCatVal');
                            trigger.addEventListener('click', function(e){
                                e.stopPropagation();
                                dd.classList.toggle('open');
                            });
                            dd.querySelectorAll('.hdtt-cat-dd-opt').forEach(opt => {
                                opt.addEventListener('click', function(e){
                                    e.stopPropagation();
                                    dd.querySelectorAll('.hdtt-cat-dd-opt').forEach(o => o.classList.remove('active'));
                                    this.classList.add('active');
                                    label.textContent = this.dataset.label;
                                    valInput.value = this.dataset.val;
                                    dd.classList.remove('open');
                                });
                            });
                            document.addEventListener('click', function(e){
                                if (!dd.contains(e.target)) dd.classList.remove('open');
                            });
                        })();
                        </script>
                    </form>
                </div>
            </div>

            <div class="col-md-4 col-lg-3 text-center text-lg-end">
                <div class="d-inline-flex align-items-center">
                    <a href="/Duan1/giaodien/duaan1-giaodien/mvc-oop-basic/index.php?act=myOrders" class="text-muted d-flex align-items-center justify-content-center me-3" title="Đơn hàng">
                        <span class="rounded-circle btn-md-square border"><i class="fas fa-box"></i></span>
                    </a>
                    <a href="/Duan1/giaodien/duaan1-giaodien/mvc-oop-basic/index.php?act=wishlist" class="text-muted d-flex align-items-center justify-content-center me-3" title="Sản phẩm yêu thích">
                        <span class="rounded-circle btn-md-square border"><i class="fas fa-heart"></i></span>
                    </a>
                    <a href="/Duan1/giaodien/duaan1-giaodien/mvc-oop-basic/index.php?act=cart" class="text-muted d-flex align-items-center justify-content-center">
                        <span class="rounded-circle btn-md-square border">
                            <i class="fas fa-shopping-cart"></i>
                        </span>
                        <span class="text-dark ms-2">Giỏ hàng</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Navbar Start -->
    <div class="container-fluid nav-bar p-0">
        <div class="row gx-0 bg-primary px-5 align-items-center">
            <div class="col-lg-3 d-none d-lg-block">
                <nav class="navbar navbar-light position-relative" style="width: 280px;">
                    <button class="hdtt-allcat-toggle" type="button"
                        data-bs-toggle="collapse" data-bs-target="#allCat" aria-expanded="false">
                        <i class="fas fa-bars bars"></i>
                        <span>Danh mục sản phẩm</span>
                        <i class="fas fa-chevron-down chev"></i>
                    </button>
                    <div class="collapse navbar-collapse" id="allCat">
                        <div class="hdtt-allcat-panel">
                            <a href="/Duan1/giaodien/duaan1-giaodien/mvc-oop-basic/index.php?act=giaodien"
                               class="hdtt-allcat-item all">
                                <i class="fas fa-th icon"></i>
                                <span>Tất cả sản phẩm</span>
                                <i class="fas fa-arrow-right arrow"></i>
                            </a>
                            <?php foreach ($categoryMap as $catId => $cat): ?>
                                <a href="/Duan1/giaodien/duaan1-giaodien/mvc-oop-basic/index.php?act=giaodien&category=<?= $catId ?>"
                                   class="hdtt-allcat-item">
                                    <i class="fas <?= $cat['icon'] ?> icon"></i>
                                    <span><?= htmlspecialchars($cat['name']) ?></span>
                                    <i class="fas fa-arrow-right arrow"></i>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </nav>
            </div>

            <div class="col-12 col-lg-9">
                <nav class="navbar navbar-expand-lg navbar-light bg-primary ">
                    <a href="/Duan1/giaodien/duaan1-giaodien/mvc-oop-basic/index.php?act=giaodien" class="navbar-brand d-block d-lg-none">
                        <h1 class="display-5 text-secondary m-0"><i
                                class="fas fa-shopping-bag text-white me-2"></i>HDTT Store</h1>
                    </a>

                    <button class="navbar-toggler ms-auto" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarCollapse">
                        <span class="fa fa-bars fa-1x"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarCollapse">
                        <div class="navbar-nav ms-auto py-0">
                            <a href="/Duan1/giaodien/duaan1-giaodien/mvc-oop-basic/index.php?act=giaodien" class="nav-item nav-link <?= ($activeNav ?? '') === 'home' ? 'active' : '' ?>">Trang chủ</a>
                            <a href="/Duan1/giaodien/duaan1-giaodien/mvc-oop-basic/index.php?act=giaodien" class="nav-item nav-link <?= ($activeNav ?? '') === 'shop' ? 'active' : '' ?>">Sản phẩm</a>
                            <?php if (isset($_SESSION['user_id'])): ?>
                                <a href="/Duan1/giaodien/duaan1-giaodien/mvc-oop-basic/index.php?act=myOrders" class="nav-item nav-link <?= ($activeNav ?? '') === 'orders' ? 'active' : '' ?>">Đơn hàng</a>
                                <a href="/Duan1/giaodien/duaan1-giaodien/mvc-oop-basic/index.php?act=cart" class="nav-item nav-link <?= ($activeNav ?? '') === 'cart' ? 'active' : '' ?>">Giỏ hàng</a>
                            <?php else: ?>
                                <a href="/Duan1/giaodien/duaan1-giaodien/mvc-oop-basic/index.php?act=loginUser" class="nav-item nav-link">Đăng nhập</a>
                                <a href="/Duan1/giaodien/duaan1-giaodien/mvc-oop-basic/index.php?act=registerUser" class="nav-item nav-link">Đăng ký</a>
                            <?php endif; ?>
                        </div>

                        <a href="tel:0866914326" class="btn btn-secondary rounded-pill py-2 px-4 px-lg-3 mb-3 mb-md-3 mb-lg-0"><i
                                class="fa fa-mobile-alt me-2"></i> 0866914326</a>
                    </div>
                </nav>
            </div>
        </div>
    </div>
    <!-- Navbar End -->
