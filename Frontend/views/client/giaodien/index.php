<?php
$categoryMap = [
    1 => ['name' => 'Áo',   'icon' => 'fa-tshirt'],
    2 => ['name' => 'Quần', 'icon' => 'fa-user-tie'],
    3 => ['name' => 'Giày', 'icon' => 'fa-shoe-prints'],
];
$totalVariants = is_array($products ?? null) ? count($products) : 0;
$currentCategory = (int)($currentCategory ?? ($_GET['category'] ?? 0));
$currentCategoryName = $currentCategory > 0 && isset($categoryMap[$currentCategory])
    ? $categoryMap[$currentCategory]['name']
    : '';

$pageTitle = 'Trang chủ';
$activeNav = 'home';
require_once __DIR__ . '/_header.php';
?>
    <!-- ============================================================
         EDITORIAL MAGAZINE THEME (chỉ áp dụng trang chủ)
         Font: Fraunces (heading) + Inter (body)
         Palette: kem ngà · nâu đậm · cam đất · vàng mù tạt
         ============================================================ -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,700;9..144,900&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root{
            --ed-cream:#F5F1EA;
            --ed-cream-2:#FBF6ED;
            --ed-cream-3:#F5E9D8;
            --ed-paper:#FFFEFB;
            --ed-ink:#2A1F14;
            --ed-ink-soft:#5C4A33;
            --ed-rust:#C2410C;
            --ed-mustard:#CA8A04;
            --ed-cocoa:#94715A;
        }
        body{
            background:var(--ed-cream) !important;
            color:var(--ed-ink) !important;
            font-family:'Inter',system-ui,-apple-system,sans-serif !important;
        }
        /* Headings dùng serif Fraunces */
        .hdtt-hero-title,.hdtt-cats-head h2,.top-title,.hdtt-cta h3,
        .pcard-name,.tcard-name,.top-sub,.hdtt-hero-sub{
            font-family:'Fraunces','Times New Roman',serif !important;
        }
        .hdtt-hero-title,.top-title,.hdtt-cta h3,.hdtt-cats-head h2{
            letter-spacing:-.02em !important;font-weight:700 !important;
        }
        .pcard-name,.tcard-name{font-weight:700 !important;letter-spacing:-.01em !important}

        /* HERO ----------------------------------------------------- */
        .hdtt-hero{
            background:
              linear-gradient(120deg, rgba(15,10,5,.92) 0%, rgba(15,10,5,.7) 50%, rgba(15,10,5,.4) 100%),
              url('img/banner.jpg') center/cover no-repeat !important;
        }
        .hdtt-hero::before{
            content:"" !important;position:absolute !important;inset:0 !important;
            background:
              radial-gradient(ellipse 70% 60% at 25% 50%, rgba(0,0,0,.55), transparent 70%),
              radial-gradient(circle at 85% 15%, rgba(202,138,4,.22), transparent 55%) !important;
            pointer-events:none !important;
        }
        .hdtt-hero-inner{position:relative;z-index:2}
        .hdtt-hero-eyebrow{
            background:rgba(0,0,0,.45) !important;
            border:1px solid rgba(252,211,77,.45) !important;
            color:#FCD34D !important;
            font-family:'Inter',sans-serif !important;letter-spacing:3px !important;
            backdrop-filter:blur(10px) !important;
            box-shadow:0 4px 20px rgba(0,0,0,.4) !important;
        }
        .hdtt-hero-eyebrow i{color:var(--ed-mustard) !important}
        .hdtt-hero-title{
            font-style:italic;font-weight:900 !important;
            color:#FFFEFB !important;
            text-shadow:
              0 2px 16px rgba(0,0,0,.7),
              0 0 40px rgba(0,0,0,.5),
              0 1px 2px rgba(0,0,0,.9) !important;
        }
        .hdtt-hero-title span{
            background:linear-gradient(135deg,#FCD34D,#FB923C) !important;
            -webkit-background-clip:text !important;-webkit-text-fill-color:transparent !important;
            font-style:italic;
            filter:drop-shadow(0 2px 12px rgba(251,146,60,.5)) drop-shadow(0 0 20px rgba(0,0,0,.4));
        }
        .hdtt-hero-sub{
            font-style:italic;font-weight:500;
            color:#FFFEFB !important;
            text-shadow:0 2px 10px rgba(0,0,0,.85), 0 1px 2px rgba(0,0,0,.95) !important;
        }
        .hdtt-hero-stats > div{
            text-shadow:0 2px 8px rgba(0,0,0,.7);
        }
        .hdtt-hero-btn{font-family:'Inter',sans-serif !important;letter-spacing:.5px}
        .hdtt-hero-btn.primary{
            background:var(--ed-rust) !important;
            box-shadow:0 12px 30px rgba(194,65,12,.4) !important;
        }
        .hdtt-hero-btn.primary:hover{box-shadow:0 16px 40px rgba(194,65,12,.55) !important}
        .hdtt-hero-stat-num{font-family:'Fraunces',serif !important;color:var(--ed-mustard) !important}

        /* TRUST STRIP --------------------------------------------- */
        .hdtt-trust{background:var(--ed-cream) !important;border-bottom:1px solid rgba(42,31,20,.08) !important}
        .hdtt-trust-icon.c1{background:var(--ed-ink) !important}
        .hdtt-trust-icon.c2{background:var(--ed-rust) !important}
        .hdtt-trust-icon.c3{background:var(--ed-mustard) !important}
        .hdtt-trust-icon.c4{background:var(--ed-cocoa) !important}
        .hdtt-trust-text strong{color:var(--ed-ink) !important;font-weight:700 !important}
        .hdtt-trust-text small{color:var(--ed-ink-soft) !important}

        /* CATEGORIES ---------------------------------------------- */
        .hdtt-cats-head h2{color:var(--ed-ink) !important;font-style:italic}
        .hdtt-cats-head h2 i{color:var(--ed-rust) !important}
        .hdtt-cats-head p{color:var(--ed-ink-soft) !important;font-style:italic}
        .hdtt-cats-head a{color:var(--ed-rust) !important;letter-spacing:1px;text-transform:uppercase;font-size:12px !important}
        .hdtt-cats-head a:hover{color:var(--ed-ink) !important}
        .hdtt-cat{background:var(--ed-paper) !important;border:1px solid rgba(42,31,20,.1) !important;color:var(--ed-ink) !important}
        .hdtt-cat:hover{border-color:var(--ed-rust) !important;color:var(--ed-rust) !important;box-shadow:0 12px 28px rgba(194,65,12,.18) !important}
        .hdtt-cat.active{border-color:var(--ed-rust) !important;background:linear-gradient(135deg,var(--ed-cream-2),var(--ed-paper)) !important;box-shadow:0 8px 22px rgba(194,65,12,.15) !important}
        .hdtt-cat-icon{background:var(--ed-cream-2) !important;color:var(--ed-rust) !important}
        .hdtt-cat:hover .hdtt-cat-icon{background:var(--ed-rust) !important;color:#fff !important}
        .hdtt-cat.all .hdtt-cat-icon{background:#FEF3C7 !important;color:var(--ed-mustard) !important}
        .hdtt-cat.all:hover .hdtt-cat-icon{background:var(--ed-mustard) !important;color:#fff !important}
        .hdtt-cat-name{font-weight:600 !important;letter-spacing:.3px}

        /* PRODUCT TABS NAV ---------------------------------------- */
        .product .nav-pills .bg-light{background:var(--ed-cream-2) !important}
        .product .nav-pills .active{background:var(--ed-ink) !important}
        .product .nav-pills .active .text-dark{color:#fff !important}
        .product .nav-pills .text-dark{color:var(--ed-ink) !important;font-weight:600;letter-spacing:.5px}
        .product h1{font-family:'Fraunces',serif !important;color:var(--ed-ink) !important;font-style:italic;font-weight:700}
        .product h1 .text-primary{color:var(--ed-rust) !important}
        .product .text-muted{color:var(--ed-ink-soft) !important}

        /* PRODUCT CARD -------------------------------------------- */
        .pcard{background:var(--ed-paper) !important;border:1px solid rgba(42,31,20,.1) !important;border-radius:8px !important}
        .pcard:hover{border-color:var(--ed-rust) !important;box-shadow:0 16px 40px rgba(42,31,20,.12) !important}
        .pcard-img{background:linear-gradient(135deg,var(--ed-cream-2),var(--ed-cream)) !important;border-radius:0 !important}
        .pcard-name a{color:var(--ed-ink) !important}
        .pcard-name a:hover{color:var(--ed-rust) !important}
        .pcard-price{color:var(--ed-rust) !important;font-family:'Fraunces',serif !important;font-style:italic;font-size:22px !important}
        .pcard-price small{color:var(--ed-cocoa) !important}
        .pcard-btn{
            background:var(--ed-ink) !important;color:#fff !important;
            border-radius:0 !important;letter-spacing:1px;text-transform:uppercase;
            font-size:11.5px !important;font-weight:600 !important;
            padding:13px 16px !important;
            box-shadow:none !important;
        }
        .pcard-btn:hover{background:var(--ed-rust) !important;transform:translateY(-1px) !important;box-shadow:0 6px 16px rgba(194,65,12,.3) !important}
        .pcard-btn.disabled{background:#D6CCC2 !important;color:#8B7B6A !important}
        .pcard-tag.new{background:var(--ed-mustard) !important}
        .pcard-tag.out{background:var(--ed-ink) !important}
        .pcard-tag{border-radius:0 !important;font-family:'Inter',sans-serif !important}
        .pcard-eye{color:var(--ed-rust) !important}
        .pcard-eye:hover{background:var(--ed-rust) !important;color:#fff !important}
        .pcard-cat{background:rgba(255,254,251,.95) !important;color:var(--ed-ink) !important;border-radius:0 !important}
        .pcard-rating{color:var(--ed-mustard) !important}
        .pcard-rating-num{color:var(--ed-ink) !important}
        .pcard-rating-sold{color:var(--ed-ink-soft) !important}
        .pcard-meta span{background:var(--ed-cream-2) !important;color:var(--ed-ink-soft) !important}
        .pcard-stock.in{color:var(--ed-rust) !important}
        .pcard-stock.out{color:var(--ed-cocoa) !important}

        /* PAGINATION ---------------------------------------------- */
        .hdtt-pager{border-top-color:rgba(42,31,20,.1) !important}
        .hdtt-pager-info{color:var(--ed-ink-soft) !important}
        .hdtt-pager-info b{color:var(--ed-rust) !important}
        .hdtt-pager-list{background:var(--ed-cream-2) !important;box-shadow:inset 0 0 0 1px rgba(42,31,20,.06) !important}
        .hdtt-page{color:var(--ed-ink) !important}
        .hdtt-page:hover{background:var(--ed-paper) !important;color:var(--ed-rust) !important;box-shadow:0 4px 12px rgba(194,65,12,.15) !important}
        .hdtt-page.active{background:var(--ed-ink) !important;color:#fff !important;box-shadow:0 6px 16px rgba(42,31,20,.3) !important}
        .hdtt-page-arrow{background:var(--ed-paper) !important;border-color:rgba(42,31,20,.12) !important}
        .hdtt-page-arrow:hover{background:var(--ed-rust) !important;color:#fff !important;border-color:var(--ed-rust) !important}

        /* TOP SELLERS / FLASH SECTION ----------------------------- */
        .top-section{background:linear-gradient(135deg,var(--ed-cream-2) 0%,var(--ed-cream-3) 100%) !important}
        .top-section::before{background:radial-gradient(circle,rgba(202,138,4,.18),transparent 70%) !important}
        .top-section::after{background:radial-gradient(circle,rgba(194,65,12,.15),transparent 70%) !important}
        .top-eyebrow{
            background:var(--ed-ink) !important;color:var(--ed-cream-2) !important;
            box-shadow:0 8px 20px rgba(42,31,20,.25) !important;
            letter-spacing:2px !important;font-family:'Inter',sans-serif !important;
        }
        .top-title{
            background:linear-gradient(135deg,var(--ed-ink),var(--ed-rust)) !important;
            -webkit-background-clip:text !important;-webkit-text-fill-color:transparent !important;
            font-style:italic;font-weight:900 !important;
        }
        .top-sub{color:var(--ed-ink-soft) !important;font-style:italic;font-weight:500 !important}
        .tcard{background:var(--ed-paper) !important;border:1px solid rgba(42,31,20,.1) !important;border-radius:8px !important}
        .tcard:hover{box-shadow:0 20px 50px rgba(42,31,20,.14) !important}
        .tcard-rank{border-radius:6px !important;font-family:'Fraunces',serif !important;font-style:italic}
        .tcard-rank.r1{background:var(--ed-mustard) !important}
        .tcard-rank.r2{background:var(--ed-cocoa) !important}
        .tcard-rank.r3{background:var(--ed-rust) !important}
        .tcard-rank.r-other{background:var(--ed-ink) !important}
        .tcard-fire{background:var(--ed-rust) !important;box-shadow:0 4px 10px rgba(194,65,12,.3) !important;border-radius:0 !important;letter-spacing:.5px}
        .tcard-img{background:linear-gradient(135deg,var(--ed-cream-2),var(--ed-cream-3)) !important}
        .tcard-name a{color:var(--ed-ink) !important}
        .tcard-name a:hover{color:var(--ed-rust) !important}
        .tcard-price{color:var(--ed-rust) !important;font-family:'Fraunces',serif !important;font-style:italic;font-size:18px !important}
        .tcard-price small{color:var(--ed-cocoa) !important}
        .tcard-rating{color:var(--ed-mustard) !important}
        .tcard-rating .text-mut{color:var(--ed-ink-soft) !important}
        .tcard-link{
            background:var(--ed-cream-2) !important;color:var(--ed-ink) !important;
            border-radius:0 !important;letter-spacing:1px;text-transform:uppercase;
            font-size:11px !important;font-weight:600 !important;
        }
        .tcard-link:hover{background:var(--ed-ink) !important;color:var(--ed-cream-2) !important}

        /* CTA / NEWSLETTER ---------------------------------------- */
        .hdtt-cta{background:linear-gradient(135deg,var(--ed-ink) 0%,#3D2817 100%) !important}
        .hdtt-cta::before{background:radial-gradient(circle,rgba(202,138,4,.3),transparent 70%) !important}
        .hdtt-cta::after{background:radial-gradient(circle,rgba(194,65,12,.3),transparent 70%) !important}
        .hdtt-cta h3{font-style:italic;font-weight:900 !important}
        .hdtt-cta h3 span{
            background:linear-gradient(135deg,#FCD34D,#FB923C) !important;
            -webkit-background-clip:text !important;-webkit-text-fill-color:transparent !important;
            font-style:italic;
        }
        .hdtt-cta p{color:#E5D9C7 !important;font-style:italic}
        .hdtt-cta-form{background:rgba(245,233,216,.1) !important;border-color:rgba(245,233,216,.2) !important}
        .hdtt-cta-form button{background:var(--ed-rust) !important;letter-spacing:1px;text-transform:uppercase;font-size:12px !important}
        .hdtt-cta-form button:hover{background:var(--ed-mustard) !important}

        /* SECTION cải thiện chung */
        .product.py-5{background:var(--ed-cream) !important}
    </style>

    <!-- Hero Start -->
    <style>
        .hdtt-hero{
            position:relative;min-height:480px;
            background:
              linear-gradient(120deg, rgba(13,17,38,.75) 0%, rgba(13,17,38,.35) 55%, rgba(13,17,38,.05) 100%),
              url('img/banner.jpg') center/cover no-repeat;
            display:flex;align-items:center;
            color:#fff;overflow:hidden;
        }
        .hdtt-hero::before{
            content:"";position:absolute;inset:0;
            background:radial-gradient(circle at 80% 20%, rgba(102,16,242,.25), transparent 50%);
            pointer-events:none;
        }
        .hdtt-hero-inner{position:relative;z-index:2;max-width:680px}
        .hdtt-hero-eyebrow{
            display:inline-flex;align-items:center;gap:8px;
            padding:6px 14px;border-radius:999px;
            background:rgba(255,255,255,.14);backdrop-filter:blur(10px);
            border:1px solid rgba(255,255,255,.25);
            font-size:12px;font-weight:600;letter-spacing:2px;text-transform:uppercase;
            margin-bottom:22px;
        }
        .hdtt-hero-eyebrow i{color:#fbbf24}
        .hdtt-hero-title{
            font-size:clamp(34px,5vw,58px);font-weight:800;
            line-height:1.05;letter-spacing:-1.5px;margin:0 0 18px;
        }
        .hdtt-hero-title span{
            background:linear-gradient(135deg,#60a5fa,#a78bfa);
            -webkit-background-clip:text;-webkit-text-fill-color:transparent;
        }
        .hdtt-hero-sub{font-size:16.5px;line-height:1.65;color:#e5e7eb;margin:0 0 28px;max-width:560px}
        .hdtt-hero-cta{display:flex;flex-wrap:wrap;gap:12px}
        .hdtt-hero-btn{
            padding:14px 28px;border-radius:999px;
            font-weight:700;font-size:15px;text-decoration:none;
            display:inline-flex;align-items:center;gap:8px;
            transition:transform .25s, box-shadow .25s;
        }
        .hdtt-hero-btn.primary{
            background:linear-gradient(135deg,#3b82f6,#6366f1);color:#fff;
            box-shadow:0 12px 30px rgba(59,130,246,.4);
        }
        .hdtt-hero-btn.primary:hover{transform:translateY(-2px);box-shadow:0 16px 40px rgba(59,130,246,.55);color:#fff}
        .hdtt-hero-btn.ghost{
            background:rgba(255,255,255,.08);color:#fff;
            border:1px solid rgba(255,255,255,.3);backdrop-filter:blur(8px);
        }
        .hdtt-hero-btn.ghost:hover{background:rgba(255,255,255,.16);transform:translateY(-2px);color:#fff}
        .hdtt-hero-stats{
            display:flex;gap:32px;margin-top:38px;flex-wrap:wrap;
        }
        .hdtt-hero-stat-num{font-size:24px;font-weight:800;color:#fff;line-height:1}
        .hdtt-hero-stat-lbl{font-size:12px;color:#cbd5e1;margin-top:4px;letter-spacing:.5px}
    </style>
    <section class="hdtt-hero">
        <div class="container">
            <div class="hdtt-hero-inner">
                <span class="hdtt-hero-eyebrow"><i class="fas fa-bolt"></i> Bộ sưu tập 2026</span>
                <h1 class="hdtt-hero-title">Mặc đẹp. <span>Sống chất.</span><br>Mỗi ngày.</h1>
                <p class="hdtt-hero-sub">Tủ đồ kể câu chuyện của bạn — chất liệu cao cấp, kiểu dáng hiện đại, không kén người mặc.</p>
                <div class="hdtt-hero-cta">
                    <a class="hdtt-hero-btn primary" href="<?= BASE_PATH ?>index.php?act=giaodien#products">
                        <i class="fas fa-shopping-bag"></i> Mua ngay
                    </a>
                    <a class="hdtt-hero-btn ghost" href="<?= BASE_PATH ?>index.php?act=giaodien#top-sellers">
                        <i class="fas fa-fire"></i> Xem hàng hot
                    </a>
                </div>
                <div class="hdtt-hero-stats">
                    <div><div class="hdtt-hero-stat-num">500+</div><div class="hdtt-hero-stat-lbl">SẢN PHẨM</div></div>
                    <div><div class="hdtt-hero-stat-num">10K+</div><div class="hdtt-hero-stat-lbl">KHÁCH HÀNG</div></div>
                    <div><div class="hdtt-hero-stat-num">4.8★</div><div class="hdtt-hero-stat-lbl">ĐÁNH GIÁ</div></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Trust strip -->
    <style>
        .hdtt-trust{background:#fff;border-bottom:1px solid #eef0f4;padding:18px 0}
        .hdtt-trust-row{
            display:grid;grid-template-columns:repeat(4,1fr);gap:16px;
        }
        @media(max-width:768px){.hdtt-trust-row{grid-template-columns:repeat(2,1fr)}}
        .hdtt-trust-item{
            display:flex;align-items:center;gap:12px;
            padding:8px 4px;
        }
        .hdtt-trust-icon{
            width:44px;height:44px;border-radius:12px;flex-shrink:0;
            display:flex;align-items:center;justify-content:center;
            color:#fff;font-size:17px;
        }
        .hdtt-trust-icon.c1{background:linear-gradient(135deg,#3b82f6,#6366f1)}
        .hdtt-trust-icon.c2{background:linear-gradient(135deg,#10b981,#059669)}
        .hdtt-trust-icon.c3{background:linear-gradient(135deg,#f59e0b,#d97706)}
        .hdtt-trust-icon.c4{background:linear-gradient(135deg,#ef4444,#dc2626)}
        .hdtt-trust-text{line-height:1.25}
        .hdtt-trust-text strong{color:#0f172a;font-size:14px;font-weight:700;display:block}
        .hdtt-trust-text small{color:#64748b;font-size:12px}
    </style>
    <div class="hdtt-trust">
        <div class="container">
            <div class="hdtt-trust-row">
                <div class="hdtt-trust-item">
                    <div class="hdtt-trust-icon c1"><i class="fas fa-shipping-fast"></i></div>
                    <div class="hdtt-trust-text"><strong>Giao hàng nhanh</strong><small>Toàn quốc 1–3 ngày</small></div>
                </div>
                <div class="hdtt-trust-item">
                    <div class="hdtt-trust-icon c2"><i class="fas fa-shield-alt"></i></div>
                    <div class="hdtt-trust-text"><strong>Chính hãng 100%</strong><small>Cam kết chất lượng</small></div>
                </div>
                <div class="hdtt-trust-item">
                    <div class="hdtt-trust-icon c3"><i class="fas fa-undo-alt"></i></div>
                    <div class="hdtt-trust-text"><strong>Đổi trả 7 ngày</strong><small>Miễn phí đổi size</small></div>
                </div>
                <div class="hdtt-trust-item">
                    <div class="hdtt-trust-icon c4"><i class="fas fa-headset"></i></div>
                    <div class="hdtt-trust-text"><strong>Hỗ trợ 24/7</strong><small>Hotline 0866914326</small></div>
                </div>
            </div>
        </div>
    </div>
    <!-- Hero End -->

    <!-- Danh mục nổi bật -->
    <style>
        .hdtt-cats{padding:50px 0 30px}
        .hdtt-cats-head{
            display:flex;align-items:end;justify-content:space-between;
            margin-bottom:28px;flex-wrap:wrap;gap:12px;
        }
        .hdtt-cats-head h2{
            font-size:26px;font-weight:800;margin:0;color:#0f172a;letter-spacing:-.5px;
        }
        .hdtt-cats-head h2 i{color:#3b82f6;margin-right:8px}
        .hdtt-cats-head p{color:#64748b;font-size:14px;margin:4px 0 0}
        .hdtt-cats-head a{
            font-size:14px;font-weight:600;color:#3b82f6;text-decoration:none;
            display:inline-flex;align-items:center;gap:6px;
        }
        .hdtt-cats-head a:hover{color:#1d4ed8}
        .hdtt-cats-grid{
            display:grid;grid-template-columns:repeat(auto-fill,minmax(140px,1fr));gap:16px;
        }
        .hdtt-cat{
            display:flex;flex-direction:column;align-items:center;
            padding:20px 12px;border-radius:16px;
            background:#fff;border:1px solid #eef0f4;
            text-decoration:none;color:#0f172a;
            transition:all .25s ease;
        }
        .hdtt-cat:hover{
            transform:translateY(-4px);
            border-color:#3b82f6;
            box-shadow:0 12px 28px rgba(59,130,246,.15);
            color:#3b82f6;
        }
        .hdtt-cat.active{
            border-color:#3b82f6;background:linear-gradient(135deg,#eff6ff,#fff);
            box-shadow:0 8px 22px rgba(59,130,246,.12);
        }
        .hdtt-cat-icon{
            width:64px;height:64px;border-radius:50%;
            display:flex;align-items:center;justify-content:center;
            background:linear-gradient(135deg,#eff6ff,#e0e7ff);
            color:#3b82f6;font-size:24px;margin-bottom:12px;
            transition:all .25s ease;
        }
        .hdtt-cat:hover .hdtt-cat-icon{
            background:linear-gradient(135deg,#3b82f6,#6366f1);color:#fff;
            transform:scale(1.08);
        }
        .hdtt-cat-name{font-size:14.5px;font-weight:700;margin:0}
        .hdtt-cat.all .hdtt-cat-icon{background:linear-gradient(135deg,#fef3c7,#fde68a);color:#d97706}
        .hdtt-cat.all:hover .hdtt-cat-icon{background:linear-gradient(135deg,#f59e0b,#d97706);color:#fff}
    </style>
    <div class="container hdtt-cats">
        <div class="hdtt-cats-head">
            <div>
                <h2><i class="fas fa-th-large"></i>Mua sắm theo danh mục</h2>
                <p>Chọn nhanh danh mục yêu thích của bạn</p>
            </div>
            <a href="<?= BASE_PATH ?>index.php?act=giaodien#products">
                Xem tất cả <i class="fas fa-arrow-right"></i>
            </a>
        </div>
        <div class="hdtt-cats-grid">
            <a href="<?= BASE_PATH ?>index.php?act=giaodien#products"
               class="hdtt-cat all <?= $currentCategory === 0 ? 'active' : '' ?>">
                <div class="hdtt-cat-icon"><i class="fas fa-grip"></i></div>
                <p class="hdtt-cat-name">Tất cả</p>
            </a>
            <?php foreach ($categoryMap as $catId => $cat): ?>
                <a href="<?= BASE_PATH ?>index.php?act=giaodien&category=<?= $catId ?>#products"
                   class="hdtt-cat <?= $currentCategory === $catId ? 'active' : '' ?>">
                    <div class="hdtt-cat-icon"><i class="fas <?= $cat['icon'] ?>"></i></div>
                    <p class="hdtt-cat-name"><?= htmlspecialchars($cat['name']) ?></p>
                </a>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Our Products Start -->
    <div class="container-fluid product py-5" id="products">
        <div class="container py-5">
            <div class="tab-class">
                <div class="row g-4 align-items-center">
                    <div class="col-lg-6 text-start wow fadeInLeft" data-wow-delay="0.1s">
                        <h1 class="mb-0">
                            <?php if ($currentCategoryName !== ''): ?>
                                Danh mục: <span class="text-primary"><?= htmlspecialchars($currentCategoryName) ?></span>
                            <?php else: ?>
                                Sản phẩm của chúng tôi
                            <?php endif; ?>
                        </h1>
                        <p class="text-muted mb-0">
                            Hiện có <b><?= $totalVariants ?></b> mẫu
                            <?= $currentCategoryName !== '' ? 'thuộc danh mục "' . htmlspecialchars($currentCategoryName) . '"' : 'đang bán' ?>
                            <?php if ($currentCategory > 0): ?>
                                &middot; <a href="<?= BASE_PATH ?>index.php?act=giaodien" class="text-decoration-none">
                                    <i class="fas fa-times-circle"></i> Bỏ lọc
                                </a>
                            <?php endif; ?>
                        </p>
                    </div>
                    <div class="col-lg-6 text-end wow fadeInRight" data-wow-delay="0.1s">
                        <ul class="nav nav-pills d-inline-flex text-center mb-0">
                            <li class="nav-item">
                                <a class="d-flex mx-2 py-2 bg-light rounded-pill active" data-bs-toggle="pill" href="#tab-1">
                                    <span class="text-dark px-3">Tất cả</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="d-flex py-2 mx-2 bg-light rounded-pill" data-bs-toggle="pill" href="#tab-2">
                                    <span class="text-dark px-3">Mới về</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="d-flex mx-2 py-2 bg-light rounded-pill" data-bs-toggle="pill" href="#tab-3">
                                    <span class="text-dark px-3">Còn hàng</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="tab-content mt-5">
                    <!-- Tab 1: Tất cả sản phẩm -->
                    <div id="tab-1" class="tab-pane fade show p-0 active">
                        <?php if (!empty($_GET['keyword'])): ?>
                            <div class="mb-4">
                                <p class="mb-0">
                                    Bạn đang tìm: <b><?= htmlspecialchars($_GET['keyword']) ?></b>
                                </p>
                            </div>
                        <?php endif; ?>

                        <style>
                            .pcard{
                                background:#fff;
                                border:1px solid #ececec;
                                border-radius:16px;
                                overflow:hidden;
                                transition:all .3s ease;
                                height:100%;
                                display:flex;flex-direction:column;
                            }
                            .pcard:hover{
                                transform:translateY(-6px);
                                box-shadow:0 16px 40px rgba(15,23,42,.08);
                                border-color:#cfe2ff;
                            }
                            .pcard-img{
                                position:relative;overflow:hidden;
                                aspect-ratio:1/1;
                                background:linear-gradient(135deg,#f8fafc,#eef2ff);
                            }
                            .pcard-img img{
                                width:100%;height:100%;object-fit:cover;
                                transition:transform .5s ease;
                            }
                            .pcard:hover .pcard-img img{transform:scale(1.05)}
                            .pcard-tag{
                                position:absolute;top:12px;left:12px;
                                padding:4px 11px;border-radius:999px;
                                font-size:11px;font-weight:700;letter-spacing:.4px;
                                text-transform:uppercase;color:#fff;
                                box-shadow:0 4px 10px rgba(0,0,0,.1);
                            }
                            .pcard-tag.new{background:linear-gradient(135deg,#10b981,#059669)}
                            .pcard-tag.out{background:linear-gradient(135deg,#dc3545,#991b1b)}
                            .pcard-eye{
                                position:absolute;top:12px;right:12px;
                                width:36px;height:36px;border-radius:50%;
                                background:rgba(255,255,255,.95);
                                display:flex;align-items:center;justify-content:center;
                                color:#0d6efd;font-size:14px;
                                opacity:0;transform:translateX(8px);
                                transition:all .3s ease;
                                box-shadow:0 4px 10px rgba(0,0,0,.1);
                            }
                            .pcard:hover .pcard-eye{opacity:1;transform:translateX(0)}
                            .pcard-eye:hover{background:#0d6efd;color:#fff}
                            .pcard-cat{
                                position:absolute;bottom:12px;left:12px;
                                padding:3px 10px;border-radius:6px;
                                background:rgba(255,255,255,.92);backdrop-filter:blur(4px);
                                font-size:10.5px;font-weight:700;letter-spacing:.5px;
                                color:#1e293b;text-transform:uppercase;
                            }
                            .pcard-body{
                                padding:18px 18px 16px;
                                flex:1;display:flex;flex-direction:column;
                            }
                            .pcard-name{
                                font-size:15.5px;font-weight:700;
                                color:#1e293b;margin:0 0 8px;
                                display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;
                                overflow:hidden;line-height:1.35;min-height:42px;
                            }
                            .pcard-name a{color:inherit;text-decoration:none}
                            .pcard-name a:hover{color:#0d6efd}
                            .pcard-rating{
                                display:flex;align-items:center;gap:2px;
                                color:#fbbf24;font-size:12px;margin-bottom:8px;
                            }
                            .pcard-rating-num{
                                color:#475569;font-weight:700;font-size:12px;margin-left:4px;
                            }
                            .pcard-rating-sold{
                                color:#64748b;font-size:12px;font-weight:500;margin-left:6px;
                            }
                            .pcard-meta{
                                display:flex;flex-wrap:wrap;gap:6px;
                                margin-bottom:10px;
                            }
                            .pcard-meta span{
                                font-size:11px;color:#64748b;
                                padding:2px 8px;border-radius:6px;
                                background:#f1f5f9;font-weight:600;
                            }
                            .pcard-price{
                                font-size:18px;font-weight:800;
                                color:#0d6efd;margin-bottom:4px;
                            }
                            .pcard-price small{font-size:12px;color:#94a3b8;font-weight:500}
                            .pcard-stock{font-size:12px;margin-bottom:14px}
                            .pcard-stock.in{color:#059669}
                            .pcard-stock.out{color:#dc3545}
                            .pcard-btn{
                                margin-top:auto;
                                background:linear-gradient(135deg,#0d6efd,#6610f2);
                                color:#fff;
                                padding:10px 16px;border-radius:10px;
                                font-size:13px;font-weight:600;
                                text-align:center;text-decoration:none;
                                display:flex;align-items:center;justify-content:center;gap:6px;
                                transition:all .25s ease;
                                box-shadow:0 4px 12px rgba(13,110,253,.25);
                            }
                            .pcard-btn:hover{
                                color:#fff;
                                transform:translateY(-1px);
                                box-shadow:0 8px 20px rgba(13,110,253,.4);
                            }
                            .pcard-btn.disabled{
                                background:#e5e7eb;color:#94a3b8;
                                box-shadow:none;cursor:not-allowed;pointer-events:none;
                            }
                            .cat-name-1{color:#0ea5e9}
                            .cat-name-2{color:#22c55e}
                            .cat-name-3{color:#a855f7}
                        </style>

                        <div class="row g-4">
                            <?php if (!empty($products)): ?>
                                <?php
                                    $catNames = [1 => 'Áo', 2 => 'Quần', 3 => 'Giày'];
                                ?>
                                <?php foreach ($products as $item):
                                    $hasStock = (int)$item['total_stock'] > 0;
                                    $catId = (int)($item['category_id'] ?? 0);
                                    $catLabel = $catNames[$catId] ?? '';
                                ?>
                                    <div class="col-sm-6 col-lg-4 col-xl-3">
                                        <div class="pcard wow fadeInUp" data-wow-delay="0.1s">
                                            <div class="pcard-img">
                                                <img loading="lazy" src="<?= BASE_PATH ?>uploads/<?= htmlspecialchars($item['image']) ?>"
                                                     alt="<?= htmlspecialchars($item['product_name']) ?>"
                                                     loading="lazy">

                                                <?php if (!$hasStock): ?>
                                                    <span class="pcard-tag out">Hết hàng</span>
                                                <?php else: ?>
                                                    <span class="pcard-tag new">Mới</span>
                                                <?php endif; ?>

                                                <a href="<?= BASE_PATH ?>index.php?act=detail&id=<?= (int)$item['product_id'] ?>"
                                                   class="pcard-eye" title="Xem nhanh">
                                                    <i class="fas fa-eye"></i>
                                                </a>

                                                <?php if ($catLabel): ?>
                                                    <span class="pcard-cat cat-name-<?= $catId ?>"><?= htmlspecialchars($catLabel) ?></span>
                                                <?php endif; ?>
                                            </div>

                                            <div class="pcard-body">
                                                <h5 class="pcard-name">
                                                    <a href="<?= BASE_PATH ?>index.php?act=detail&id=<?= (int)$item['product_id'] ?>">
                                                        <?= htmlspecialchars($item['product_name']) ?>
                                                    </a>
                                                </h5>

                                                <div class="pcard-rating">
                                                    <i class="fas fa-star"></i><i class="fas fa-star"></i>
                                                    <i class="fas fa-star"></i><i class="fas fa-star"></i>
                                                    <i class="fas fa-star-half-alt"></i>
                                                    <span class="pcard-rating-num">4.5</span>
                                                    <span class="pcard-rating-sold">· Đã bán <?= max(10, (int)$item['variant_count'] * 17) ?></span>
                                                </div>
                                                <div class="pcard-meta">
                                                    <span title="Số màu"><i class="fas fa-palette me-1"></i><?= count($item['colors']) ?> màu</span>
                                                    <span title="Số size"><i class="fas fa-ruler me-1"></i><?= count($item['sizes']) ?> size</span>
                                                </div>

                                                <div class="pcard-price">
                                                    <?php if ($item['min_price'] === $item['max_price']): ?>
                                                        <?= number_format($item['min_price']) ?>đ
                                                    <?php else: ?>
                                                        <?= number_format($item['min_price']) ?>đ
                                                        <small>– <?= number_format($item['max_price']) ?>đ</small>
                                                    <?php endif; ?>
                                                </div>

                                                <div class="pcard-stock <?= $hasStock ? 'in' : 'out' ?>">
                                                    <?php if ($hasStock): ?>
                                                        <i class="fas fa-check-circle"></i> Còn <?= (int)$item['total_stock'] ?> sản phẩm
                                                    <?php else: ?>
                                                        <i class="fas fa-times-circle"></i> Tạm hết hàng
                                                    <?php endif; ?>
                                                </div>

                                                <a href="<?= BASE_PATH ?>index.php?act=detail&id=<?= (int)$item['product_id'] ?>"
                                                   class="pcard-btn <?= !$hasStock ? 'disabled' : '' ?>">
                                                    <i class="fas fa-shopping-bag"></i> Xem chi tiết
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="col-12">
                                    <div class="alert alert-warning text-center py-5">
                                        <i class="fas fa-search fa-2x mb-3 d-block text-muted"></i>
                                        <h5 class="mb-0">Không tìm thấy sản phẩm phù hợp</h5>
                                        <small class="text-muted">Thử từ khóa khác hoặc xem các danh mục khác</small>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>

                        <?php if (($totalPages ?? 1) > 1):
                            $qs = function($p) use ($keyword, $currentCategory) {
                                $params = ['act' => 'giaodien', 'page' => $p];
                                if ($keyword !== '') $params['keyword'] = $keyword;
                                if ($currentCategory > 0) $params['category'] = $currentCategory;
                                return BASE_PATH . 'index.php?' . http_build_query($params) . '#products';
                            };
                            $start = max(1, $page - 2);
                            $end   = min($totalPages, $page + 2);
                        ?>
                        <style>
                            .hdtt-pager{
                                display:flex;justify-content:space-between;align-items:center;
                                flex-wrap:wrap;gap:18px;margin-top:50px;padding-top:30px;
                                border-top:1px solid #ececec;
                            }
                            .hdtt-pager-info{color:#6c757d;font-size:14px}
                            .hdtt-pager-info b{color:#0d6efd}
                            .hdtt-pager-list{
                                display:inline-flex;align-items:center;gap:6px;
                                padding:6px;border-radius:999px;
                                background:#f4f6fb;
                                box-shadow:inset 0 0 0 1px rgba(0,0,0,.04);
                            }
                            .hdtt-page{
                                min-width:40px;height:40px;
                                display:inline-flex;align-items:center;justify-content:center;
                                border-radius:999px;
                                font-weight:600;font-size:14px;color:#475569;
                                text-decoration:none;
                                transition:all .25s ease;
                                padding:0 12px;
                            }
                            .hdtt-page:hover{background:#fff;color:#0d6efd;box-shadow:0 4px 12px rgba(13,110,253,.15)}
                            .hdtt-page.active{
                                background:linear-gradient(135deg,#0d6efd,#6610f2);
                                color:#fff;
                                box-shadow:0 6px 16px rgba(102,16,242,.35);
                                transform:translateY(-1px);
                            }
                            .hdtt-page.active:hover{color:#fff}
                            .hdtt-page.disabled{opacity:.35;pointer-events:none}
                            .hdtt-page.dots{color:#94a3b8;cursor:default;background:transparent}
                            .hdtt-page-arrow{
                                width:42px;height:42px;
                                background:#fff;border:1px solid #e5e7eb;
                            }
                            .hdtt-page-arrow:hover{background:#0d6efd;color:#fff;border-color:#0d6efd}
                        </style>
                        <nav aria-label="Phân trang" class="hdtt-pager">
                            <div class="hdtt-pager-info">
                                <i class="fas fa-cube me-2 text-primary"></i>
                                Hiển thị <b><?= ($page-1)*$perPage + 1 ?></b>–<b><?= min($page*$perPage, $totalItems) ?></b>
                                trên <b><?= $totalItems ?></b> sản phẩm
                            </div>
                            <div class="hdtt-pager-list">
                                <a href="<?= $page > 1 ? $qs($page-1) : '#' ?>"
                                   class="hdtt-page hdtt-page-arrow <?= $page <= 1 ? 'disabled' : '' ?>"
                                   title="Trang trước">
                                    <i class="fas fa-chevron-left"></i>
                                </a>

                                <?php if ($start > 1): ?>
                                    <a href="<?= $qs(1) ?>" class="hdtt-page">1</a>
                                    <?php if ($start > 2): ?>
                                        <span class="hdtt-page dots">…</span>
                                    <?php endif; ?>
                                <?php endif; ?>

                                <?php for ($i = $start; $i <= $end; $i++): ?>
                                    <a href="<?= $qs($i) ?>"
                                       class="hdtt-page <?= $i === $page ? 'active' : '' ?>">
                                        <?= $i ?>
                                    </a>
                                <?php endfor; ?>

                                <?php if ($end < $totalPages): ?>
                                    <?php if ($end < $totalPages - 1): ?>
                                        <span class="hdtt-page dots">…</span>
                                    <?php endif; ?>
                                    <a href="<?= $qs($totalPages) ?>" class="hdtt-page"><?= $totalPages ?></a>
                                <?php endif; ?>

                                <a href="<?= $page < $totalPages ? $qs($page+1) : '#' ?>"
                                   class="hdtt-page hdtt-page-arrow <?= $page >= $totalPages ? 'disabled' : '' ?>"
                                   title="Trang sau">
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                            </div>
                        </nav>
                        <?php endif; ?>
                    </div>

                    <?php
                        $renderCard = function($item, $catNames) {
                            $hasStock = (int)$item['total_stock'] > 0;
                            $catId = (int)($item['category_id'] ?? 0);
                            $catLabel = $catNames[$catId] ?? '';
                            ob_start();
                            ?>
                            <div class="col-sm-6 col-lg-4 col-xl-3">
                                <div class="pcard wow fadeInUp" data-wow-delay="0.1s">
                                    <div class="pcard-img">
                                        <img loading="lazy" src="<?= BASE_PATH ?>uploads/<?= htmlspecialchars($item['image']) ?>"
                                             alt="<?= htmlspecialchars($item['product_name']) ?>" loading="lazy">
                                        <?php if (!$hasStock): ?>
                                            <span class="pcard-tag out">Hết hàng</span>
                                        <?php else: ?>
                                            <span class="pcard-tag new">Mới</span>
                                        <?php endif; ?>
                                        <a href="<?= BASE_PATH ?>index.php?act=detail&id=<?= (int)$item['product_id'] ?>"
                                           class="pcard-eye"><i class="fas fa-eye"></i></a>
                                        <?php if ($catLabel): ?>
                                            <span class="pcard-cat cat-name-<?= $catId ?>"><?= htmlspecialchars($catLabel) ?></span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="pcard-body">
                                        <h5 class="pcard-name">
                                            <a href="<?= BASE_PATH ?>index.php?act=detail&id=<?= (int)$item['product_id'] ?>">
                                                <?= htmlspecialchars($item['product_name']) ?>
                                            </a>
                                        </h5>
                                        <div class="pcard-rating">
                                            <i class="fas fa-star"></i><i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i><i class="fas fa-star"></i>
                                            <i class="fas fa-star-half-alt"></i>
                                            <span class="pcard-rating-num">4.5</span>
                                            <span class="pcard-rating-sold">· Đã bán <?= max(10, (int)$item['variant_count'] * 17) ?></span>
                                        </div>
                                        <div class="pcard-meta">
                                            <span><i class="fas fa-palette me-1"></i><?= count($item['colors']) ?> màu</span>
                                            <span><i class="fas fa-ruler me-1"></i><?= count($item['sizes']) ?> size</span>
                                        </div>
                                        <div class="pcard-price">
                                            <?php if ($item['min_price'] === $item['max_price']): ?>
                                                <?= number_format($item['min_price']) ?>đ
                                            <?php else: ?>
                                                <?= number_format($item['min_price']) ?>đ
                                                <small>– <?= number_format($item['max_price']) ?>đ</small>
                                            <?php endif; ?>
                                        </div>
                                        <div class="pcard-stock <?= $hasStock ? 'in' : 'out' ?>">
                                            <?php if ($hasStock): ?>
                                                <i class="fas fa-check-circle"></i> Còn <?= (int)$item['total_stock'] ?> sản phẩm
                                            <?php else: ?>
                                                <i class="fas fa-times-circle"></i> Tạm hết hàng
                                            <?php endif; ?>
                                        </div>
                                        <a href="<?= BASE_PATH ?>index.php?act=detail&id=<?= (int)$item['product_id'] ?>"
                                           class="pcard-btn <?= !$hasStock ? 'disabled' : '' ?>">
                                            <i class="fas fa-shopping-bag"></i> Xem chi tiết
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <?php
                            return ob_get_clean();
                        };
                    ?>

                    <!-- Tab 2: Mới về -->
                    <div id="tab-2" class="tab-pane fade p-0">
                        <div class="row g-4">
                            <?php if (!empty($products)): ?>
                                <?php foreach (array_slice($products, 0, 8) as $item): ?>
                                    <?= $renderCard($item, $catNames ?? [1=>'Áo',2=>'Quần',3=>'Giày']) ?>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="col-12">
                                    <div class="alert alert-warning text-center">Chưa có sản phẩm mới.</div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Tab 3: Còn hàng -->
                    <div id="tab-3" class="tab-pane fade p-0">
                        <div class="row g-4">
                            <?php
                            $inStock = !empty($products)
                                ? array_filter($products, fn($p) => (int)$p['total_stock'] > 0)
                                : [];
                            ?>
                            <?php if (!empty($inStock)): ?>
                                <?php foreach ($inStock as $item): ?>
                                    <?= $renderCard($item, $catNames ?? [1=>'Áo',2=>'Quần',3=>'Giày']) ?>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="col-12">
                                    <div class="alert alert-warning text-center">
                                        Hiện chưa có sản phẩm nào còn hàng.
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Our Products End -->

    <!-- Top sản phẩm bán chạy -->
    <?php if (!empty($topSellers)): ?>
    <style>
        .top-section{
            background:linear-gradient(135deg,#fff5f0 0%,#ffe9dc 50%,#ffd9c2 100%);
            padding:60px 0;
            position:relative;
            overflow:hidden;
        }
        .top-section::before{
            content:"";position:absolute;left:-120px;top:-120px;
            width:340px;height:340px;border-radius:50%;
            background:radial-gradient(circle,rgba(239,68,68,.12),transparent 70%);
        }
        .top-section::after{
            content:"";position:absolute;right:-120px;bottom:-120px;
            width:340px;height:340px;border-radius:50%;
            background:radial-gradient(circle,rgba(245,158,11,.14),transparent 70%);
        }
        .top-head{
            text-align:center;
            max-width:640px;margin:0 auto 40px;
            position:relative;
        }
        .top-eyebrow{
            display:inline-flex;align-items:center;gap:6px;
            background:linear-gradient(135deg,#dc2626,#ea580c);
            color:#fff;
            padding:7px 16px;border-radius:999px;
            font-size:12px;font-weight:800;letter-spacing:1.5px;
            text-transform:uppercase;margin-bottom:16px;
            box-shadow:0 8px 20px rgba(220,38,38,.3);
        }
        .top-title{
            font-size:38px;font-weight:800;letter-spacing:-1px;
            background:linear-gradient(135deg,#b91c1c,#ea580c);
            -webkit-background-clip:text;-webkit-text-fill-color:transparent;
            margin:0 0 10px;
        }
        .top-sub{color:#7c2d12;font-size:15px;margin:0;opacity:.85}

        .top-grid{
            display:grid;
            grid-template-columns:repeat(4,1fr);
            gap:18px;
        }
        @media(max-width:992px){.top-grid{grid-template-columns:repeat(2,1fr)}}
        @media(max-width:576px){.top-grid{grid-template-columns:1fr}}

        .tcard{
            background:#fff;
            border:1px solid #ececec;
            border-radius:18px;
            overflow:hidden;
            transition:all .3s ease;
            position:relative;
            display:flex;flex-direction:column;
        }
        .tcard:hover{
            transform:translateY(-8px);
            box-shadow:0 20px 50px rgba(15,23,42,.1);
            border-color:transparent;
        }
        .tcard-rank{
            position:absolute;top:14px;left:14px;
            width:38px;height:38px;border-radius:12px;
            display:flex;align-items:center;justify-content:center;
            font-weight:800;font-size:15px;color:#fff;
            box-shadow:0 6px 16px rgba(0,0,0,.15);
            z-index:2;
        }
        .tcard-rank.r1{background:linear-gradient(135deg,#fbbf24,#d97706)}
        .tcard-rank.r2{background:linear-gradient(135deg,#a3a3a3,#737373)}
        .tcard-rank.r3{background:linear-gradient(135deg,#fb923c,#c2410c)}
        .tcard-rank.r-other{background:linear-gradient(135deg,#0d6efd,#6610f2)}

        .tcard-fire{
            position:absolute;top:14px;right:14px;
            background:linear-gradient(135deg,#dc2626,#991b1b);
            color:#fff;
            padding:5px 11px;border-radius:999px;
            font-size:11px;font-weight:700;
            display:flex;align-items:center;gap:5px;
            z-index:2;
            box-shadow:0 4px 10px rgba(220,38,38,.3);
        }
        .tcard-img{
            aspect-ratio:1/1;
            background:linear-gradient(135deg,#f1f5f9,#e2e8f0);
            overflow:hidden;
        }
        .tcard-img img{
            width:100%;height:100%;object-fit:cover;
            transition:transform .5s ease;
        }
        .tcard:hover .tcard-img img{transform:scale(1.08)}
        .tcard-body{padding:16px;flex:1;display:flex;flex-direction:column}
        .tcard-name{
            font-size:14.5px;font-weight:700;
            color:#1e293b;margin:0 0 8px;
            line-height:1.4;
            display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;
            overflow:hidden;min-height:40px;
        }
        .tcard-name a{color:inherit;text-decoration:none}
        .tcard-name a:hover{color:#0d6efd}
        .tcard-stats{
            display:flex;align-items:center;justify-content:space-between;
            margin-bottom:10px;
        }
        .tcard-sold{
            font-size:11px;color:#dc2626;font-weight:700;
            background:#fef2f2;padding:3px 9px;border-radius:6px;
            display:inline-flex;align-items:center;gap:4px;
        }
        .tcard-rating{font-size:12px;color:#fbbf24}
        .tcard-rating .text-mut{color:#94a3b8}
        .tcard-price{
            font-size:16px;font-weight:800;color:#0d6efd;
            margin-bottom:12px;
        }
        .tcard-price small{color:#94a3b8;font-size:11px;font-weight:500}
        .tcard-link{
            margin-top:auto;
            display:flex;align-items:center;justify-content:center;gap:6px;
            padding:9px;border-radius:10px;
            background:#f1f5f9;color:#475569;
            font-size:12.5px;font-weight:600;
            text-decoration:none;
            transition:all .25s;
        }
        .tcard-link:hover{
            background:linear-gradient(135deg,#0d6efd,#6610f2);
            color:#fff;
        }
    </style>

    <section class="top-section" id="top-sellers">
        <div class="container">
            <div class="top-head">
                <span class="top-eyebrow"><i class="fas fa-fire"></i> Flash Sale · Bán chạy nhất</span>
                <h2 class="top-title">Sản phẩm HOT tháng này</h2>
                <p class="top-sub">Những món được khách hàng săn đón nhiều nhất — đừng bỏ lỡ!</p>
            </div>

            <div class="top-grid">
                <?php $rank = 0; ?>
                <?php foreach ($topSellers as $p): $rank++; ?>
                    <?php
                        $rankClass = match (true) {
                            $rank === 1 => 'r1',
                            $rank === 2 => 'r2',
                            $rank === 3 => 'r3',
                            default     => 'r-other',
                        };
                        $rankLabel = match (true) {
                            $rank === 1 => '🥇',
                            $rank === 2 => '🥈',
                            $rank === 3 => '🥉',
                            default     => '#' . $rank,
                        };
                        $sold = (int)($p['sold_qty'] ?? 0);
                    ?>
                    <article class="tcard">
                        <div class="tcard-rank <?= $rankClass ?>"><?= $rankLabel ?></div>
                        <?php if ($sold > 0): ?>
                            <div class="tcard-fire"><i class="fas fa-fire"></i> Đã bán <?= $sold ?></div>
                        <?php endif; ?>

                        <a href="<?= BASE_PATH ?>index.php?act=detail&id=<?= (int)$p['product_id'] ?>" class="tcard-img d-block">
                            <img loading="lazy" src="<?= BASE_PATH ?>uploads/<?= htmlspecialchars($p['image']) ?>"
                                 alt="<?= htmlspecialchars($p['product_name']) ?>" loading="lazy">
                        </a>

                        <div class="tcard-body">
                            <h6 class="tcard-name">
                                <a href="<?= BASE_PATH ?>index.php?act=detail&id=<?= (int)$p['product_id'] ?>">
                                    <?= htmlspecialchars($p['product_name']) ?>
                                </a>
                            </h6>

                            <div class="tcard-stats">
                                <span class="tcard-rating">
                                    <i class="fas fa-star"></i><i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i><i class="fas fa-star"></i>
                                    <i class="fas fa-star-half-alt"></i>
                                    <span class="text-mut" style="font-weight:600;margin-left:4px">4.5</span>
                                </span>
                            </div>

                            <div class="tcard-price">
                                <?php if ((int)$p['min_price'] === (int)$p['max_price']): ?>
                                    <?= number_format((int)$p['min_price']) ?>đ
                                <?php else: ?>
                                    <?= number_format((int)$p['min_price']) ?>đ
                                    <small>– <?= number_format((int)$p['max_price']) ?>đ</small>
                                <?php endif; ?>
                            </div>

                            <a href="<?= BASE_PATH ?>index.php?act=detail&id=<?= (int)$p['product_id'] ?>"
                               class="tcard-link">
                                <i class="fas fa-shopping-bag"></i> Xem chi tiết
                            </a>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Newsletter / CTA cuối trang -->
    <style>
        .hdtt-cta{
            position:relative;overflow:hidden;
            background:linear-gradient(135deg,#0f172a 0%,#1e293b 50%,#312e81 100%);
            padding:60px 0;color:#fff;
        }
        .hdtt-cta::before{
            content:"";position:absolute;left:-80px;top:-80px;
            width:280px;height:280px;border-radius:50%;
            background:radial-gradient(circle,rgba(99,102,241,.3),transparent 70%);
        }
        .hdtt-cta::after{
            content:"";position:absolute;right:-80px;bottom:-80px;
            width:280px;height:280px;border-radius:50%;
            background:radial-gradient(circle,rgba(59,130,246,.3),transparent 70%);
        }
        .hdtt-cta-inner{position:relative;z-index:2;text-align:center;max-width:680px;margin:0 auto}
        .hdtt-cta h3{font-size:30px;font-weight:800;margin:0 0 10px;letter-spacing:-.5px}
        .hdtt-cta h3 span{
            background:linear-gradient(135deg,#60a5fa,#a78bfa);
            -webkit-background-clip:text;-webkit-text-fill-color:transparent;
        }
        .hdtt-cta p{color:#cbd5e1;font-size:15px;margin:0 0 24px}
        .hdtt-cta-form{
            display:flex;gap:10px;max-width:480px;margin:0 auto;
            background:rgba(255,255,255,.08);padding:6px;border-radius:999px;
            border:1px solid rgba(255,255,255,.15);backdrop-filter:blur(8px);
        }
        .hdtt-cta-form input{
            flex:1;background:transparent;border:0;color:#fff;
            padding:10px 18px;font-size:14px;outline:none;
        }
        .hdtt-cta-form input::placeholder{color:#94a3b8}
        .hdtt-cta-form button{
            background:linear-gradient(135deg,#3b82f6,#6366f1);
            color:#fff;border:0;border-radius:999px;
            padding:10px 22px;font-weight:700;font-size:14px;
            cursor:pointer;transition:transform .2s;
        }
        .hdtt-cta-form button:hover{transform:translateY(-1px)}
    </style>
    <section class="hdtt-cta">
        <div class="container">
            <div class="hdtt-cta-inner">
                <h3>Đăng ký nhận <span>ưu đãi mới nhất</span></h3>
                <p>Nhận thông báo sản phẩm mới, mã giảm giá độc quyền và xu hướng thời trang mỗi tuần.</p>
                <form class="hdtt-cta-form" onsubmit="event.preventDefault(); this.querySelector('input').value=''; alert('Cảm ơn bạn đã đăng ký!');">
                    <input type="email" placeholder="Nhập email của bạn..." required>
                    <button type="submit"><i class="fas fa-paper-plane me-2"></i>Đăng ký</button>
                </form>
            </div>
        </div>
    </section>

<!-- ============================================================
     STREETWEAR BRUTALIST OVERRIDES (loaded last → wins cascade)
     ============================================================ -->
<style>
    body{background:#000 !important;color:var(--text) !important}

    /* ---------- HERO ---------- */
    .hdtt-hero{
        background:
          linear-gradient(90deg, #000 0%, rgba(0,0,0,.72) 48%, rgba(0,0,0,.35) 100%),
          url('img/banner.jpg') center/cover no-repeat !important;
        position:relative;overflow:hidden;border-bottom:2px solid var(--border-2);
    }
    .hdtt-hero::before{
        content:"" !important;position:absolute !important;inset:0 !important;pointer-events:none;
        background:linear-gradient(0deg,rgba(216,255,0,.06),transparent 40%) !important;
    }
    .hdtt-hero-inner{position:relative;z-index:2}
    .hdtt-hero-eyebrow{
        background:var(--accent) !important;border:0 !important;border-radius:0 !important;
        color:#000 !important;backdrop-filter:none !important;
        font-family:'Space Mono',monospace !important;letter-spacing:.16em !important;font-weight:700 !important;
    }
    .hdtt-hero-eyebrow i{color:#000 !important}
    .hdtt-hero-title{
        color:#fff !important;font-family:'Anton',sans-serif !important;font-weight:400 !important;
        text-transform:uppercase !important;letter-spacing:0 !important;line-height:.9 !important;
        font-size:clamp(52px,8vw,104px) !important;
        text-shadow:0 0 1px rgba(0,0,0,.4);
    }
    .hdtt-hero-title span{
        background:none !important;color:var(--accent) !important;-webkit-text-fill-color:var(--accent) !important;
        filter:none !important;-webkit-text-stroke:0;
    }
    .hdtt-hero-sub{color:var(--text-2) !important;font-family:'Space Mono',monospace !important;max-width:520px}
    .hdtt-hero-btn{border-radius:0 !important;text-transform:uppercase;font-family:'Archivo';font-weight:900;letter-spacing:.05em}
    .hdtt-hero-btn.primary{background:var(--accent) !important;box-shadow:none !important;border:2px solid var(--accent) !important;color:#000 !important}
    .hdtt-hero-btn.primary:hover{transform:translate(-3px,-3px);box-shadow:6px 6px 0 #000 !important;color:#000 !important}
    .hdtt-hero-btn.ghost{background:transparent !important;border:2px solid #fff !important;color:#fff !important;backdrop-filter:none}
    .hdtt-hero-btn.ghost:hover{background:#fff !important;color:#000 !important;transform:translate(-3px,-3px);box-shadow:6px 6px 0 var(--accent) !important}
    .hdtt-hero-stat-num{color:var(--accent) !important;font-family:'Anton',sans-serif !important;-webkit-text-fill-color:var(--accent);background:none}
    .hdtt-hero-stat-lbl{color:var(--text-3) !important;font-family:'Space Mono',monospace !important;letter-spacing:.1em}

    /* ---------- TRUST STRIP ---------- */
    .hdtt-trust{background:#000 !important;border-bottom:2px solid var(--border-2) !important;border-top:2px solid var(--border-2)}
    .hdtt-trust-icon{border-radius:0 !important}
    .hdtt-trust-icon.c1,.hdtt-trust-icon.c2,.hdtt-trust-icon.c3,.hdtt-trust-icon.c4{background:var(--accent) !important;color:#000 !important}
    .hdtt-trust-text strong{color:var(--text) !important;text-transform:uppercase;font-family:'Archivo';font-weight:800}
    .hdtt-trust-text small{color:var(--text-3) !important;font-family:'Space Mono',monospace}

    /* ---------- CATEGORIES ---------- */
    .hdtt-cats-head h2{color:var(--text) !important;font-family:'Anton',sans-serif !important;text-transform:uppercase}
    .hdtt-cats-head h2 i{color:var(--accent) !important}
    .hdtt-cats-head p{color:var(--text-3) !important;font-family:'Space Mono',monospace}
    .hdtt-cats-head a{color:var(--accent) !important;font-family:'Space Mono',monospace;text-transform:uppercase}
    .hdtt-cat{background:#0A0A0A !important;border:2px solid var(--border) !important;border-radius:0 !important;color:var(--text) !important;backdrop-filter:none;transition:transform .15s,border-color .15s,box-shadow .15s !important}
    .hdtt-cat:hover{border-color:var(--accent) !important;color:var(--accent) !important;box-shadow:6px 6px 0 var(--accent) !important;transform:translate(-4px,-4px)}
    .hdtt-cat.active{border-color:var(--accent) !important;background:#0A0A0A !important}
    .hdtt-cat-icon{background:#000 !important;border:2px solid var(--border-2);border-radius:0 !important;color:var(--accent) !important}
    .hdtt-cat:hover .hdtt-cat-icon{background:var(--accent) !important;color:#000 !important;border-color:var(--accent)}
    .hdtt-cat.all .hdtt-cat-icon{background:#000 !important;color:var(--accent) !important}
    .hdtt-cat.all:hover .hdtt-cat-icon{background:var(--accent) !important;color:#000 !important}
    .hdtt-cat-name{color:inherit !important;text-transform:uppercase;font-family:'Archivo';font-weight:800}

    /* ---------- PRODUCT SECTION ---------- */
    .container-fluid.product{background:transparent !important}
    .product h1{color:var(--text) !important;font-family:'Anton',sans-serif !important;text-transform:uppercase}
    .product h1 .text-primary{background:none;color:var(--accent) !important;-webkit-text-fill-color:var(--accent)}
    .product .text-muted{color:var(--text-3) !important;font-family:'Space Mono',monospace}
    .product .nav-pills .bg-light{background:#0A0A0A !important;border:2px solid var(--border-2)}
    .product .nav-pills .active,.product .nav-pills .active .bg-light{background:var(--accent) !important;border-color:var(--accent)}
    .product .nav-pills .text-dark{color:var(--text) !important;text-transform:uppercase;font-family:'Archivo';font-weight:800}
    .product .nav-pills .active .text-dark{color:#000 !important}

    /* ---------- PRODUCT CARD (.pcard) ---------- */
    .pcard{
        background:#0A0A0A !important;border:2px solid var(--border) !important;border-radius:0 !important;backdrop-filter:none;
        transition:transform .15s, border-color .15s, box-shadow .15s !important;
    }
    .pcard:hover{transform:translate(-5px,-5px) !important;border-color:var(--accent) !important;box-shadow:9px 9px 0 var(--accent) !important}
    .pcard-img{background:var(--bg-3) !important;border-bottom:2px solid var(--border)}
    .pcard-img img{filter:grayscale(.2);transition:filter .3s,transform .5s}
    .pcard:hover .pcard-img img{filter:grayscale(0)}
    .pcard-name,.pcard-name a{color:var(--text) !important;font-family:'Archivo',sans-serif !important;font-weight:800;text-transform:uppercase}
    .pcard-name a:hover{color:var(--accent) !important}
    .pcard-price{color:var(--accent) !important;background:none;-webkit-text-fill-color:var(--accent);font-family:'Space Mono',monospace !important;font-weight:700}
    .pcard-cat{color:var(--text-3) !important;font-family:'Space Mono',monospace;text-transform:uppercase}
    .pcard-tag,.pcard-tag.new{background:var(--accent) !important;color:#000 !important;border:0 !important;border-radius:0 !important;font-family:'Space Mono',monospace;text-transform:uppercase}
    .pcard-tag.out{background:var(--danger) !important;color:#fff !important}
    .pcard-eye{background:#000 !important;color:var(--accent) !important;border:2px solid var(--border-2) !important;border-radius:0 !important;backdrop-filter:none}
    .pcard-eye:hover{background:var(--accent) !important;color:#000 !important}
    .pcard-rating-num{color:var(--text) !important;font-family:'Space Mono',monospace}
    .pcard-rating-sold,.pcard-meta,.pcard-stock{color:var(--text-3) !important;font-family:'Space Mono',monospace}
    .pcard-btn{background:var(--accent) !important;color:#000 !important;border:2px solid var(--accent) !important;border-radius:0 !important;text-transform:uppercase;font-family:'Archivo';font-weight:900}
    .pcard-btn:hover{transform:translate(-2px,-2px);box-shadow:4px 4px 0 #000}

    /* ---------- PAGINATION ---------- */
    .hdtt-pager-info{color:var(--text-3) !important;font-family:'Space Mono',monospace}
    .hdtt-page{background:transparent !important;border:2px solid var(--border-2) !important;color:var(--text-2) !important;border-radius:0 !important;font-family:'Space Mono',monospace}
    .hdtt-page:hover{background:#0A0A0A !important;color:var(--accent) !important;border-color:var(--accent) !important}
    .hdtt-page.active{background:var(--accent) !important;color:#000 !important;border:2px solid var(--accent) !important}
    .hdtt-page-arrow{background:transparent !important;border:2px solid var(--border-2) !important;color:var(--text-2) !important;border-radius:0 !important}
    .hdtt-page-arrow:hover{background:var(--accent) !important;color:#000 !important;border-color:var(--accent)}

    /* ---------- TOP SELLERS (.tcard) ---------- */
    .top-title{color:var(--text) !important;font-family:'Anton',sans-serif !important;text-transform:uppercase}
    .top-sub{color:var(--text-3) !important;font-family:'Space Mono',monospace !important}
    .tcard{background:#0A0A0A !important;border:2px solid var(--border) !important;border-radius:0 !important;backdrop-filter:none;transition:transform .15s, border-color .15s, box-shadow .15s !important}
    .tcard:hover{transform:translate(-4px,-4px) !important;border-color:var(--accent) !important;box-shadow:8px 8px 0 var(--accent) !important}
    .tcard-name,.tcard-name a{color:var(--text) !important;font-family:'Archivo',sans-serif !important;font-weight:800;text-transform:uppercase}
    .tcard-name a:hover{color:var(--accent) !important}
    .tcard-sold,.tcard-stats{color:var(--text-3) !important;font-family:'Space Mono',monospace}
    .tcard-price{color:var(--accent) !important;background:none;-webkit-text-fill-color:var(--accent);font-family:'Space Mono',monospace !important}
    .tcard-link{background:var(--accent) !important;color:#000 !important;border:0 !important;border-radius:0 !important;text-transform:uppercase;font-family:'Archivo';font-weight:900}

    /* ---------- CTA (newsletter) ---------- */
    .hdtt-cta{background:var(--accent) !important;border:2px solid #000 !important;border-radius:0 !important;box-shadow:10px 10px 0 #0A0A0A}
    .hdtt-cta::before,.hdtt-cta::after{display:none !important}
    .hdtt-cta h3{color:#000 !important;font-family:'Anton',sans-serif !important;text-transform:uppercase}
    .hdtt-cta h3 span{background:none !important;color:#000 !important;-webkit-text-fill-color:#000 !important;text-decoration:underline;text-decoration-thickness:4px}
    .hdtt-cta p{color:#1a1a00 !important;font-family:'Space Mono',monospace;font-weight:700}
    .hdtt-cta-form{background:#000 !important;border:2px solid #000 !important;border-radius:0 !important}
    .hdtt-cta-form input{color:#fff !important;font-family:'Space Mono',monospace}
    .hdtt-cta-form input::placeholder{color:#666 !important}
    .hdtt-cta-form button{background:var(--accent) !important;color:#000 !important;border-radius:0 !important;text-transform:uppercase;font-family:'Archivo';font-weight:900}
</style>

<?php require_once __DIR__ . '/_footer.php'; ?>
