<?php
if (session_status() === PHP_SESSION_NONE) session_start();

$pageTitle  = $pageTitle  ?? 'Console';
$pageBadge  = $pageBadge  ?? '';
$activeMenu = $activeMenu ?? '';
$breadcrumb = $breadcrumb ?? [];
$pageActions = $pageActions ?? '';

if (!function_exists('e_admin')) {
    function e_admin($v) { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title><?= e_admin($pageTitle) ?> · HDTT Console</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        :root{
            --bg-base:#070a16;
            --bg-grain:#0b1024;
            --surface:#111738;
            --surface-2:#161e44;
            --surface-elev:#1c2657;
            --border:rgba(148,163,255,.10);
            --border-2:rgba(148,163,255,.18);
            --text:#e6e9f5;
            --text-mut:#8a92b3;
            --text-dim:#5b6388;
            --accent:#7c5cff;     /* violet */
            --accent-2:#22d3ee;   /* cyan */
            --accent-3:#34d399;   /* mint */
            --warn:#fbbf24;
            --danger:#fb7185;
            --pink:#f472b6;
            --grad: linear-gradient(135deg,#7c5cff 0%, #22d3ee 100%);
            --grad-2: linear-gradient(135deg,#f472b6 0%, #fbbf24 100%);
            --grad-3: linear-gradient(135deg,#34d399 0%, #22d3ee 100%);
            --grad-4: linear-gradient(135deg,#fb7185 0%, #7c5cff 100%);
        }
        *{box-sizing:border-box}
        html,body{height:100%}
        body{
            margin:0;
            font-family:'Manrope',system-ui,-apple-system,Segoe UI,sans-serif;
            background:var(--bg-base);
            color:var(--text);
            font-feature-settings:"ss01","cv11";
            min-height:100vh;
            position:relative;
            overflow-x:hidden;
        }
        body::before{
            content:"";
            position:fixed;inset:0;
            background:
                radial-gradient(800px 500px at 8% -10%, rgba(124,92,255,.22), transparent 60%),
                radial-gradient(700px 500px at 100% 0%, rgba(34,211,238,.16), transparent 60%),
                radial-gradient(600px 400px at 50% 110%, rgba(244,114,182,.10), transparent 60%);
            z-index:-2;
        }
        body::after{
            content:"";
            position:fixed;inset:0;
            background-image:
                linear-gradient(rgba(255,255,255,.025) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,.025) 1px, transparent 1px);
            background-size:48px 48px;
            mask-image:radial-gradient(ellipse at center, #000 30%, transparent 70%);
            z-index:-1;
            opacity:.6;
        }

        a{color:inherit;text-decoration:none}
        ::selection{background:var(--accent);color:#fff}

        /* Layout */
        .console-shell{display:grid;grid-template-columns:264px 1fr;min-height:100vh}

        /* Sidebar */
        .nav-rail{
            position:sticky;top:0;
            height:100vh;
            background:linear-gradient(180deg, rgba(20,26,68,.85), rgba(11,16,40,.85));
            border-right:1px solid var(--border);
            backdrop-filter:blur(20px);
            -webkit-backdrop-filter:blur(20px);
            padding:22px 16px;
            display:flex;flex-direction:column;
        }
        .brand{
            display:flex;align-items:center;gap:12px;
            padding:6px 10px 18px;
            border-bottom:1px solid var(--border);
            margin-bottom:14px;
        }
        .brand-mark{
            width:38px;height:38px;border-radius:11px;
            background:var(--grad);
            display:flex;align-items:center;justify-content:center;
            color:#fff;font-weight:800;font-size:17px;
            box-shadow:0 8px 22px rgba(124,92,255,.45);
        }
        .brand-name{font-weight:800;font-size:16px;letter-spacing:.2px}
        .brand-sub{font-size:11px;color:var(--text-mut);letter-spacing:1.4px;text-transform:uppercase}

        .nav-section{font-size:11px;letter-spacing:1.6px;text-transform:uppercase;color:var(--text-dim);padding:14px 12px 6px;font-weight:600}

        .nav-item{
            display:flex;align-items:center;gap:12px;
            padding:10px 12px;border-radius:10px;
            color:var(--text-mut);font-weight:500;font-size:14px;
            position:relative;
            transition:.2s ease;
        }
        .nav-item i{font-size:17px;width:20px;text-align:center}
        .nav-item:hover{color:var(--text);background:rgba(148,163,255,.06)}
        .nav-item.active{
            color:#fff;
            background:linear-gradient(135deg, rgba(124,92,255,.22), rgba(34,211,238,.10));
            border:1px solid rgba(124,92,255,.35);
            box-shadow:inset 0 0 0 1px rgba(255,255,255,.04);
        }
        .nav-item.active::before{
            content:"";position:absolute;left:-16px;top:8px;bottom:8px;width:3px;
            background:var(--grad);border-radius:0 4px 4px 0;
        }
        .nav-item .pill{
            margin-left:auto;font-size:10px;
            background:rgba(255,255,255,.08);
            border:1px solid var(--border-2);
            padding:2px 7px;border-radius:999px;color:var(--text-mut);
        }

        .nav-foot{margin-top:auto;padding-top:14px;border-top:1px solid var(--border)}
        .user-chip{
            display:flex;align-items:center;gap:10px;
            padding:10px;border-radius:12px;
            background:rgba(255,255,255,.03);
            border:1px solid var(--border);
        }
        .avatar{
            width:36px;height:36px;border-radius:10px;
            background:var(--grad-2);
            display:flex;align-items:center;justify-content:center;
            color:#fff;font-weight:700;
        }

        /* Main */
        .main-col{padding:22px 28px 60px;min-width:0}
        .topbar{
            display:flex;align-items:center;gap:18px;
            padding:14px 18px;border-radius:18px;
            background:rgba(17,23,56,.55);
            border:1px solid var(--border);
            backdrop-filter:blur(22px);
            margin-bottom:22px;
        }
        .crumb{display:flex;align-items:center;gap:8px;color:var(--text-mut);font-size:13px;font-weight:500}
        .crumb .sep{opacity:.5}
        .crumb .now{color:var(--text)}

        .topbar .search{
            margin-left:auto;
            position:relative;
            flex:0 1 360px;
        }
        .topbar .search input{
            width:100%;
            background:rgba(7,10,22,.55);
            border:1px solid var(--border-2);
            color:var(--text);
            padding:9px 14px 9px 40px;
            border-radius:12px;
            font-size:13.5px;
            outline:none;
        }
        .topbar .search input:focus{border-color:var(--accent);box-shadow:0 0 0 3px rgba(124,92,255,.18)}
        .topbar .search i{position:absolute;left:14px;top:50%;transform:translateY(-50%);color:var(--text-mut);font-size:16px}

        .icon-btn{
            width:38px;height:38px;border-radius:11px;
            border:1px solid var(--border-2);
            background:rgba(7,10,22,.55);
            color:var(--text-mut);
            display:inline-flex;align-items:center;justify-content:center;
            transition:.2s;
        }
        .icon-btn:hover{color:var(--text);border-color:rgba(124,92,255,.5);transform:translateY(-1px)}

        /* Page header */
        .page-head{
            display:flex;align-items:flex-end;justify-content:space-between;
            margin-bottom:22px;flex-wrap:wrap;gap:14px;
        }
        .page-title{
            font-size:30px;font-weight:800;letter-spacing:-.4px;
            background:linear-gradient(180deg,#fff 0%,#a3aed5 100%);
            -webkit-background-clip:text;-webkit-text-fill-color:transparent;
            margin:0;
        }
        .page-sub{color:var(--text-mut);font-size:14px;margin-top:6px}
        .page-badge{
            display:inline-flex;align-items:center;gap:6px;
            font-size:11px;font-weight:600;letter-spacing:.4px;text-transform:uppercase;
            color:var(--accent-2);
            padding:5px 11px;border-radius:999px;
            background:rgba(34,211,238,.08);
            border:1px solid rgba(34,211,238,.25);
            margin-bottom:8px;
        }

        /* Glass surface */
        .surface{
            background:linear-gradient(180deg, rgba(28,38,87,.55), rgba(17,23,56,.55));
            border:1px solid var(--border);
            border-radius:20px;
            backdrop-filter:blur(20px);
            -webkit-backdrop-filter:blur(20px);
            padding:22px;
            position:relative;
        }
        .surface::before{
            content:"";
            position:absolute;inset:0;
            border-radius:20px;
            padding:1px;
            background:linear-gradient(135deg, rgba(124,92,255,.25), transparent 40%, rgba(34,211,238,.18));
            -webkit-mask:linear-gradient(#000 0 0) content-box, linear-gradient(#000 0 0);
            -webkit-mask-composite:xor;mask-composite:exclude;
            pointer-events:none;
            opacity:.7;
        }

        /* Stat tiles */
        .stat-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:18px;margin-bottom:24px}
        @media (max-width:1100px){.stat-grid{grid-template-columns:repeat(2,1fr)}}
        .stat{
            position:relative;overflow:hidden;
            background:linear-gradient(180deg, rgba(28,38,87,.7), rgba(17,23,56,.6));
            border:1px solid var(--border);
            border-radius:18px;padding:20px;
            transition:.25s;
        }
        .stat:hover{transform:translateY(-3px);border-color:var(--border-2)}
        .stat .label{font-size:12px;color:var(--text-mut);text-transform:uppercase;letter-spacing:1.4px;font-weight:600}
        .stat .value{font-size:30px;font-weight:800;margin-top:8px;letter-spacing:-.5px}
        .stat .delta{font-size:12px;color:var(--accent-3);margin-top:6px;font-weight:600}
        .stat .icon-tile{
            position:absolute;top:18px;right:18px;
            width:42px;height:42px;border-radius:12px;
            display:flex;align-items:center;justify-content:center;
            font-size:20px;color:#fff;
        }
        .stat .icon-tile.g1{background:var(--grad)}
        .stat .icon-tile.g2{background:var(--grad-2)}
        .stat .icon-tile.g3{background:var(--grad-3)}
        .stat .icon-tile.g4{background:var(--grad-4)}
        .stat .glow{
            position:absolute;width:200px;height:200px;
            border-radius:50%;filter:blur(60px);opacity:.18;
            bottom:-80px;right:-50px;pointer-events:none;
        }
        .stat .glow.c1{background:#7c5cff}
        .stat .glow.c2{background:#22d3ee}
        .stat .glow.c3{background:#34d399}
        .stat .glow.c4{background:#f472b6}

        /* Tables */
        .data-table{
            width:100%;border-collapse:separate;border-spacing:0 6px;
            font-size:13.5px;
        }
        .data-table thead th{
            text-align:left;padding:10px 14px;
            font-weight:600;color:var(--text-mut);
            font-size:11px;text-transform:uppercase;letter-spacing:1.2px;
            border-bottom:1px solid var(--border);
        }
        .data-table tbody tr{
            background:rgba(255,255,255,.02);
            transition:.2s;
        }
        .data-table tbody td{
            padding:13px 14px;
            border-top:1px solid var(--border);
            border-bottom:1px solid var(--border);
        }
        .data-table tbody td:first-child{border-left:1px solid var(--border);border-radius:10px 0 0 10px}
        .data-table tbody td:last-child{border-right:1px solid var(--border);border-radius:0 10px 10px 0}
        .data-table tbody tr:hover{background:rgba(124,92,255,.07)}

        /* Buttons */
        .btn-aurora{
            display:inline-flex;align-items:center;gap:8px;
            padding:9px 16px;border-radius:11px;
            font-weight:600;font-size:13px;letter-spacing:.2px;
            background:var(--grad);color:#fff;border:0;
            box-shadow:0 8px 18px rgba(124,92,255,.35);
            transition:.2s;cursor:pointer;
        }
        .btn-aurora:hover{transform:translateY(-1px);box-shadow:0 12px 22px rgba(124,92,255,.5);color:#fff}
        .btn-ghost{
            display:inline-flex;align-items:center;gap:7px;
            padding:8px 13px;border-radius:10px;
            background:rgba(255,255,255,.04);
            border:1px solid var(--border-2);color:var(--text);
            font-size:12.5px;font-weight:600;
            transition:.2s;cursor:pointer;
        }
        .btn-ghost:hover{background:rgba(124,92,255,.12);border-color:rgba(124,92,255,.5);color:#fff}
        .btn-ghost.danger:hover{background:rgba(251,113,133,.12);border-color:rgba(251,113,133,.5);color:#fff}
        .btn-ghost.warn:hover{background:rgba(251,191,36,.12);border-color:rgba(251,191,36,.5);color:#fff}
        .btn-ghost.info:hover{background:rgba(34,211,238,.12);border-color:rgba(34,211,238,.5);color:#fff}

        /* Pills / status */
        .pill{
            display:inline-flex;align-items:center;gap:6px;
            padding:4px 11px;border-radius:999px;
            font-size:11.5px;font-weight:600;letter-spacing:.2px;
            border:1px solid transparent;
        }
        .pill::before{content:"";width:6px;height:6px;border-radius:50%;background:currentColor}
        .pill.warn{color:#fbbf24;background:rgba(251,191,36,.12);border-color:rgba(251,191,36,.3)}
        .pill.info{color:#22d3ee;background:rgba(34,211,238,.12);border-color:rgba(34,211,238,.3)}
        .pill.success{color:#34d399;background:rgba(52,211,153,.12);border-color:rgba(52,211,153,.3)}
        .pill.danger{color:#fb7185;background:rgba(251,113,133,.12);border-color:rgba(251,113,133,.3)}
        .pill.violet{color:#a78bfa;background:rgba(167,139,250,.12);border-color:rgba(167,139,250,.3)}
        .pill.muted{color:var(--text-mut);background:rgba(148,163,255,.06);border-color:var(--border-2)}

        /* Forms */
        .field{display:flex;flex-direction:column;gap:6px;margin-bottom:14px}
        .field label{font-size:12px;color:var(--text-mut);font-weight:600;text-transform:uppercase;letter-spacing:1px}
        .field input,.field textarea,.field select{
            background:rgba(7,10,22,.55);
            border:1px solid var(--border-2);
            color:var(--text);
            padding:11px 14px;border-radius:11px;
            font-size:14px;font-family:inherit;
            outline:none;
            transition:.2s;
        }
        .field input:focus,.field textarea:focus,.field select:focus{
            border-color:var(--accent);box-shadow:0 0 0 3px rgba(124,92,255,.18)
        }
        .field textarea{resize:vertical;min-height:110px}

        .alert-soft{
            padding:13px 16px;border-radius:12px;
            background:rgba(251,113,133,.08);
            border:1px solid rgba(251,113,133,.3);
            color:#fda4af;font-size:13.5px;
            margin-bottom:16px;
        }
        .alert-soft.info{background:rgba(34,211,238,.08);border-color:rgba(34,211,238,.3);color:#67e8f9}
        .alert-soft.success{background:rgba(52,211,153,.08);border-color:rgba(52,211,153,.3);color:#86efac}

        /* Misc */
        .thumb{width:62px;height:62px;border-radius:12px;object-fit:cover;border:1px solid var(--border-2)}
        .mono{font-family:'JetBrains Mono',monospace}
        .empty-box{
            text-align:center;padding:60px 20px;color:var(--text-mut);
        }
        .empty-box i{font-size:48px;opacity:.4;display:block;margin-bottom:14px}

        /* Form selects/inputs in tables */
        .data-table select, .data-table input{
            background:rgba(7,10,22,.5);
            border:1px solid var(--border-2);
            color:var(--text);
            padding:6px 10px;border-radius:8px;
            font-size:12.5px;font-family:inherit;
            outline:none;
        }

        /* Bar viz */
        .bar-row{display:grid;grid-template-columns:120px 1fr 110px;gap:14px;align-items:center;margin-bottom:10px}
        .bar-row .bar{height:10px;border-radius:999px;background:rgba(255,255,255,.05);position:relative;overflow:hidden}
        .bar-row .bar > span{position:absolute;left:0;top:0;bottom:0;background:var(--grad);border-radius:999px}
        .bar-row .lbl{color:var(--text-mut);font-size:13px}
        .bar-row .val{text-align:right;color:#fff;font-weight:600;font-size:13px}

        @media (max-width:900px){
            .console-shell{grid-template-columns:1fr}
            .nav-rail{position:fixed;left:-280px;top:0;width:264px;z-index:50;transition:.3s}
            .nav-rail.open{left:0}
            .stat-grid{grid-template-columns:1fr 1fr}
        }
    </style>
</head>
<body>

<div class="console-shell">
    <aside class="nav-rail">
        <div class="brand">
            <div class="brand-mark">H</div>
            <div>
                <div class="brand-name">HDTT Console</div>
                <div class="brand-sub">Admin v2</div>
            </div>
        </div>

        <div class="nav-section">Tổng quan</div>
        <a href="?act=admin" class="nav-item <?= $activeMenu==='dashboard'?'active':'' ?>">
            <i class="bi bi-grid-1x2"></i> Dashboard
        </a>
        <a href="?act=thongke" class="nav-item <?= $activeMenu==='thongke'?'active':'' ?>">
            <i class="bi bi-graph-up-arrow"></i> Thống kê
        </a>

        <div class="nav-section">Catalog</div>
        <a href="?act=adminProduct" class="nav-item <?= $activeMenu==='product'?'active':'' ?>">
            <i class="bi bi-box-seam"></i> Sản phẩm
        </a>
        <a href="?act=CateProduct" class="nav-item <?= $activeMenu==='variant'?'active':'' ?>">
            <i class="bi bi-stack"></i> Biến thể
        </a>

        <div class="nav-section">Vận hành</div>
        <a href="?act=donhang" class="nav-item <?= $activeMenu==='order'?'active':'' ?>">
            <i class="bi bi-bag-check"></i> Đơn hàng
        </a>
        <a href="?act=users" class="nav-item <?= $activeMenu==='user'?'active':'' ?>">
            <i class="bi bi-people"></i> Khách hàng
        </a>

        <div class="nav-foot">
            <?php if (isset($_SESSION['user'])): ?>
                <div class="user-chip">
                    <div class="avatar"><?= strtoupper(mb_substr($_SESSION['user'],0,1)) ?></div>
                    <div style="flex:1;min-width:0">
                        <div style="font-weight:700;font-size:13.5px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis"><?= e_admin($_SESSION['user']) ?></div>
                        <div style="font-size:11px;color:var(--text-mut)">Quản trị viên</div>
                    </div>
                    <a href="?act=logout" class="icon-btn" title="Đăng xuất"><i class="bi bi-box-arrow-right"></i></a>
                </div>
            <?php else: ?>
                <a href="?act=login" class="btn-aurora" style="width:100%;justify-content:center">
                    <i class="bi bi-shield-lock"></i> Đăng nhập
                </a>
            <?php endif; ?>
        </div>
    </aside>

    <main class="main-col">
        <div class="topbar">
            <div class="crumb">
                <i class="bi bi-house" style="color:var(--text-dim)"></i>
                <span class="sep">/</span>
                <?php foreach ($breadcrumb as $i => $c): ?>
                    <?php if ($i > 0): ?><span class="sep">/</span><?php endif; ?>
                    <span class="<?= $i === count($breadcrumb)-1 ? 'now' : '' ?>"><?= e_admin($c) ?></span>
                <?php endforeach; ?>
            </div>

            <div class="search">
                <i class="bi bi-search"></i>
                <form method="GET" action="index.php">
                    <input type="hidden" name="act" value="adminProduct">
                    <input type="text" name="keyword" placeholder="Tìm sản phẩm, đơn hàng, khách..."
                           value="<?= e_admin($_GET['keyword'] ?? '') ?>">
                </form>
            </div>

            <a href="#" class="icon-btn" title="Thông báo"><i class="bi bi-bell"></i></a>
            <a href="?act=giaodien" class="icon-btn" title="Xem trang khách"><i class="bi bi-box-arrow-up-right"></i></a>
        </div>

        <div class="page-head">
            <div>
                <?php if ($pageBadge): ?>
                    <span class="page-badge"><i class="bi bi-stars"></i> <?= e_admin($pageBadge) ?></span>
                <?php endif; ?>
                <h1 class="page-title"><?= e_admin($pageTitle) ?></h1>
                <?php if (!empty($pageSubtitle ?? '')): ?>
                    <p class="page-sub"><?= e_admin($pageSubtitle) ?></p>
                <?php endif; ?>
            </div>
            <div><?= $pageActions ?></div>
        </div>
