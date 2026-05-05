<?php
$pageTitle = 'Sản phẩm yêu thích';
$activeNav = 'wishlist';
require_once __DIR__ . '/_header.php';

$catNames = [1 => 'Áo', 2 => 'Quần', 3 => 'Giày'];
?>

<style>
    .wl-hero{
        background:linear-gradient(135deg,#fff1f2 0%,#fce7f3 50%,#fdf4ff 100%);
        border-radius:24px;
        padding:38px 40px;margin:30px 0;
        position:relative;overflow:hidden;
    }
    .wl-hero::before{
        content:"";position:absolute;right:30px;top:50%;transform:translateY(-50%);
        font-size:160px;opacity:.12;
    }
    .wl-hero::after{content:"\f004";font-family:"Font Awesome 5 Free";font-weight:900;
        position:absolute;right:50px;top:50%;transform:translateY(-50%);
        font-size:140px;color:#ec4899;opacity:.15;
    }
    .wl-hero h1{font-size:34px;font-weight:800;letter-spacing:-.6px;margin:0 0 8px;color:#831843}
    .wl-hero p{color:#9d174d;margin:0;font-size:14.5px}
    .wl-count{
        display:inline-flex;align-items:center;gap:6px;
        background:#fff;color:#be185d;
        padding:5px 13px;border-radius:999px;
        font-size:13px;font-weight:700;
        box-shadow:0 4px 12px rgba(190,24,93,.12);margin-top:14px;
    }

    .wl-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:18px}
    @media(max-width:992px){.wl-grid{grid-template-columns:repeat(2,1fr)}}
    @media(max-width:576px){.wl-grid{grid-template-columns:1fr}}

    .wl-card{
        background:#fff;border:1px solid #ececec;border-radius:18px;
        overflow:hidden;transition:all .3s;display:flex;flex-direction:column;
        position:relative;
    }
    .wl-card:hover{transform:translateY(-6px);box-shadow:0 20px 50px rgba(15,23,42,.08);border-color:#fbcfe8}
    .wl-img{aspect-ratio:1/1;overflow:hidden;background:#f8fafc;position:relative}
    .wl-img img{width:100%;height:100%;object-fit:cover;transition:transform .5s}
    .wl-card:hover .wl-img img{transform:scale(1.06)}
    .wl-cat{
        position:absolute;top:12px;left:12px;
        padding:4px 10px;border-radius:6px;
        background:rgba(255,255,255,.95);
        font-size:10.5px;font-weight:700;letter-spacing:.5px;text-transform:uppercase;
        color:#1e293b;
    }
    .wl-remove{
        position:absolute;top:12px;right:12px;
        width:36px;height:36px;border-radius:50%;
        background:#fff;color:#ec4899;
        display:flex;align-items:center;justify-content:center;
        text-decoration:none;font-size:14px;
        box-shadow:0 4px 12px rgba(0,0,0,.1);
        transition:all .25s;
    }
    .wl-remove:hover{background:#ec4899;color:#fff;transform:scale(1.08)}
    .wl-body{padding:16px;flex:1;display:flex;flex-direction:column}
    .wl-name{
        font-size:15px;font-weight:700;color:#1e293b;
        margin:0 0 8px;line-height:1.4;
        display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;
        overflow:hidden;min-height:42px;
    }
    .wl-name a{color:inherit;text-decoration:none}
    .wl-name a:hover{color:#0d6efd}
    .wl-price{font-size:17px;font-weight:800;color:#0d6efd;margin-bottom:6px}
    .wl-price small{color:#94a3b8;font-size:11px;font-weight:500}
    .wl-stock{font-size:12px;margin-bottom:12px}
    .wl-stock.in{color:#059669}
    .wl-stock.out{color:#dc3545}
    .wl-actions{margin-top:auto;display:flex;gap:6px}
    .wl-btn{
        flex:1;padding:9px;border-radius:10px;
        text-align:center;font-size:12.5px;font-weight:600;
        text-decoration:none;display:flex;align-items:center;justify-content:center;gap:5px;
        transition:all .25s;
    }
    .wl-btn.primary{
        background:linear-gradient(135deg,#0d6efd,#6610f2);color:#fff;
        box-shadow:0 4px 10px rgba(13,110,253,.2);
    }
    .wl-btn.primary:hover{transform:translateY(-1px);box-shadow:0 8px 16px rgba(13,110,253,.35);color:#fff}

    .wl-empty{
        background:#fff;border-radius:24px;
        padding:80px 30px;text-align:center;
        box-shadow:0 6px 24px rgba(15,23,42,.04);
        border:1px solid #ececec;
    }
    .wl-empty i{font-size:80px;color:#fbcfe8;margin-bottom:20px}
    .wl-empty h3{font-weight:800;letter-spacing:-.4px;margin:0 0 8px}
    .wl-empty p{color:#64748b;margin:0 0 20px}
</style>

<div class="container">
    <div class="wl-hero">
        <h1><i class="fas fa-heart text-danger me-2"></i>Sản phẩm yêu thích</h1>
        <p>Danh sách những sản phẩm bạn quan tâm — mua sắm khi sẵn sàng nhé!</p>
        <span class="wl-count"><i class="fas fa-bookmark"></i> <?= count($items) ?> sản phẩm</span>
    </div>

    <?php if (!empty($items)): ?>
        <div class="wl-grid mb-5">
            <?php foreach ($items as $p):
                $stock = (int)($p['total_stock'] ?? 0);
                $catId = (int)($p['category_id'] ?? 0);
                $catLabel = $catNames[$catId] ?? '';
            ?>
                <article class="wl-card">
                    <div class="wl-img">
                        <?php if ($catLabel): ?>
                            <span class="wl-cat"><?= htmlspecialchars($catLabel) ?></span>
                        <?php endif; ?>
                        <a href="/Duan1/giaodien/duaan1-giaodien/mvc-oop-basic/index.php?act=toggleWishlist&id=<?= (int)$p['product_id'] ?>&back=wishlist"
                           class="wl-remove" title="Bỏ khỏi yêu thích"
                           onclick="return confirm('Bỏ sản phẩm này khỏi yêu thích?');">
                            <i class="fas fa-heart"></i>
                        </a>
                        <a href="/Duan1/giaodien/duaan1-giaodien/mvc-oop-basic/index.php?act=detail&id=<?= (int)$p['product_id'] ?>">
                            <img src="/Duan1/giaodien/duaan1-giaodien/mvc-oop-basic/uploads/<?= htmlspecialchars($p['image']) ?>"
                                 alt="<?= htmlspecialchars($p['product_name']) ?>" loading="lazy">
                        </a>
                    </div>
                    <div class="wl-body">
                        <h6 class="wl-name">
                            <a href="/Duan1/giaodien/duaan1-giaodien/mvc-oop-basic/index.php?act=detail&id=<?= (int)$p['product_id'] ?>">
                                <?= htmlspecialchars($p['product_name']) ?>
                            </a>
                        </h6>
                        <div class="wl-price">
                            <?php if ((int)$p['min_price'] === (int)$p['max_price']): ?>
                                <?= number_format((int)$p['min_price']) ?>đ
                            <?php else: ?>
                                <?= number_format((int)$p['min_price']) ?>đ
                                <small>– <?= number_format((int)$p['max_price']) ?>đ</small>
                            <?php endif; ?>
                        </div>
                        <div class="wl-stock <?= $stock > 0 ? 'in' : 'out' ?>">
                            <?php if ($stock > 0): ?>
                                <i class="fas fa-check-circle"></i> Còn <?= $stock ?> sản phẩm
                            <?php else: ?>
                                <i class="fas fa-times-circle"></i> Tạm hết hàng
                            <?php endif; ?>
                        </div>
                        <div class="wl-actions">
                            <a href="/Duan1/giaodien/duaan1-giaodien/mvc-oop-basic/index.php?act=detail&id=<?= (int)$p['product_id'] ?>"
                               class="wl-btn primary">
                                <i class="fas fa-shopping-bag"></i> Xem & mua
                            </a>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="wl-empty mb-5">
            <i class="far fa-heart"></i>
            <h3>Chưa có sản phẩm yêu thích</h3>
            <p>Bấm vào biểu tượng ❤️ trên sản phẩm bạn thích để lưu lại đây.</p>
            <a href="/Duan1/giaodien/duaan1-giaodien/mvc-oop-basic/index.php?act=giaodien" class="btn btn-primary rounded-pill px-4">
                <i class="fas fa-shopping-bag me-2"></i>Khám phá sản phẩm
            </a>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/_footer.php'; ?>
