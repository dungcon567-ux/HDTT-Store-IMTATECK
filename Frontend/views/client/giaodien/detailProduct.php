<?php
$pageTitle = $firstVariant['product_name'] ?? 'Chi tiết sản phẩm';
$activeNav = 'shop';
require_once __DIR__ . '/_header.php';

$hasStock = false;
$totalStock = 0;
foreach ($variants as $v) {
    $totalStock += (int)$v['stock'];
    if ((int)$v['stock'] > 0) $hasStock = true;
}

$minPrice = PHP_INT_MAX; $maxPrice = 0;
foreach ($variants as $v) {
    $p = (int)$v['price'];
    if ($p < $minPrice) $minPrice = $p;
    if ($p > $maxPrice) $maxPrice = $p;
}

$isFavorited = false;
if (isset($_SESSION['user_id'])) {
    require_once __DIR__ . '/../../../../Backend/models/Product.php';
    $_pmw = new Product();
    $isFavorited = $_pmw->isInWishlist($_SESSION['user_id'], (int)$firstVariant['product_id']);
}
?>

<link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,700;9..144,900&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
    :root{
        --ed-cream:#F5F1EA;--ed-cream-2:#FBF6ED;--ed-cream-3:#F5E9D8;
        --ed-paper:#FFFEFB;--ed-ink:#2A1F14;--ed-ink-soft:#5C4A33;
        --ed-rust:#C2410C;--ed-mustard:#CA8A04;--ed-cocoa:#94715A;
    }
    body{background:var(--ed-cream)!important;color:var(--ed-ink)!important;font-family:'Inter',system-ui,sans-serif!important}

    .pd-wrap{max-width:1200px;margin:0 auto;padding:30px 20px 60px}
    .pd-crumbs{display:flex;align-items:center;gap:8px;font-size:12.5px;color:var(--ed-ink-soft);margin-bottom:24px;flex-wrap:wrap}
    .pd-crumbs a{color:var(--ed-ink-soft);text-decoration:none;font-weight:500;transition:color .2s}
    .pd-crumbs a:hover{color:var(--ed-rust)}
    .pd-crumbs .sep{color:var(--ed-cocoa)}
    .pd-crumbs .current{color:var(--ed-ink);font-weight:700;letter-spacing:.2px}

    .pd-main{display:grid;grid-template-columns:1.1fr 1fr;gap:40px;align-items:start}
    @media(max-width:900px){.pd-main{grid-template-columns:1fr;gap:24px}}

    .pd-img-stage{
        position:relative;background:linear-gradient(135deg,var(--ed-cream-2),var(--ed-cream-3));
        border-radius:8px;padding:28px;border:1px solid rgba(42,31,20,.1);
        overflow:hidden;
    }
    .pd-img-stage::before{
        content:"";position:absolute;width:280px;height:280px;border-radius:50%;
        background:radial-gradient(circle,rgba(202,138,4,.18),transparent 70%);
        top:-80px;right:-80px;pointer-events:none;
    }
    .pd-img{
        position:relative;z-index:2;width:100%;aspect-ratio:1/1;
        object-fit:cover;border-radius:6px;
        transition:transform .5s ease;
    }
    .pd-img:hover{transform:scale(1.03)}
    .pd-img-tag{
        position:absolute;top:18px;left:18px;z-index:3;
        display:inline-flex;align-items:center;gap:6px;
        padding:6px 14px;border-radius:4px;
        background:var(--ed-ink);color:#fff;
        font-size:11px;font-weight:700;letter-spacing:1.5px;text-transform:uppercase;
    }

    /* Right column */
    .pd-info{padding:0}
    .pd-eyebrow{
        display:inline-flex;align-items:center;gap:8px;
        padding:5px 12px;border-radius:999px;
        background:var(--ed-cream-2);border:1px solid rgba(42,31,20,.1);
        color:var(--ed-rust);font-size:11px;font-weight:700;letter-spacing:1.5px;text-transform:uppercase;
        margin-bottom:14px;
    }
    .pd-name{
        font-family:'Fraunces',serif;font-style:italic;font-weight:700;
        font-size:38px;line-height:1.15;color:var(--ed-ink);
        letter-spacing:-1px;margin:0 0 14px;
    }
    .pd-rating{
        display:flex;align-items:center;gap:10px;margin-bottom:18px;
        padding-bottom:18px;border-bottom:1px solid rgba(42,31,20,.1);
    }
    .pd-rating-stars{color:var(--ed-mustard);font-size:14px;letter-spacing:1px}
    .pd-rating-num{font-weight:700;color:var(--ed-ink);font-size:14px}
    .pd-rating-meta{color:var(--ed-cocoa);font-size:12.5px}

    .pd-price-row{margin-bottom:16px}
    .pd-price{
        font-family:'Fraunces',serif;font-style:italic;font-weight:900;
        font-size:42px;color:var(--ed-rust);letter-spacing:-1.5px;line-height:1;
    }
    .pd-price-range{
        font-family:'Fraunces',serif;font-style:italic;
        font-size:18px;color:var(--ed-cocoa);margin-left:10px;font-weight:500;
    }

    .pd-stock{
        display:inline-flex;align-items:center;gap:8px;
        padding:7px 14px;border-radius:999px;
        font-size:12.5px;font-weight:700;letter-spacing:.5px;
        margin-bottom:24px;
    }
    .pd-stock.ok{background:#D1FAE5;color:#065F46}
    .pd-stock.no{background:#FEE2E2;color:#991B1B}

    .pd-meta{display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:24px}
    .pd-meta-item{
        display:flex;align-items:center;gap:12px;padding:12px 14px;
        background:var(--ed-cream-2);border:1px solid rgba(42,31,20,.08);border-radius:6px;
    }
    .pd-meta-item i{
        width:36px;height:36px;border-radius:8px;
        background:var(--ed-paper);color:var(--ed-rust);
        display:flex;align-items:center;justify-content:center;font-size:14px;flex-shrink:0;
    }
    .pd-meta-item small{display:block;font-size:10.5px;color:var(--ed-cocoa);font-weight:600;letter-spacing:.5px;text-transform:uppercase;margin-bottom:2px}
    .pd-meta-item span{font-weight:700;color:var(--ed-ink);font-size:13px}

    .pd-field{margin-bottom:18px}
    .pd-field-label{
        display:block;font-size:11.5px;font-weight:700;color:var(--ed-ink);
        letter-spacing:1px;text-transform:uppercase;margin-bottom:8px;
    }
    .pd-select{
        width:100%;padding:13px 16px;
        background:var(--ed-paper);border:1.5px solid rgba(42,31,20,.15);
        border-radius:6px;font-size:14px;font-weight:600;color:var(--ed-ink);
        font-family:inherit;cursor:pointer;outline:none;transition:all .2s;
    }
    .pd-select:focus{border-color:var(--ed-rust);box-shadow:0 0 0 3px rgba(194,65,12,.12)}

    .pd-qty-box{
        display:inline-flex;align-items:center;
        background:var(--ed-cream-2);border:1.5px solid rgba(42,31,20,.15);
        border-radius:6px;overflow:hidden;
    }
    .pd-qty-btn{
        width:44px;height:44px;background:transparent;border:0;
        font-size:20px;color:var(--ed-ink);cursor:pointer;
        font-weight:700;transition:all .2s;font-family:inherit;
    }
    .pd-qty-btn:hover{background:var(--ed-ink);color:#fff}
    .pd-qty-input{
        width:60px;height:44px;text-align:center;border:0;
        background:transparent;font-weight:800;font-size:15px;
        color:var(--ed-ink);outline:none;font-family:inherit;
    }

    .pd-actions{display:flex;flex-wrap:wrap;gap:10px;margin-top:8px}
    .pd-btn{
        flex:1;min-width:140px;padding:14px 22px;border-radius:6px;
        font-size:12.5px;font-weight:700;letter-spacing:1.5px;text-transform:uppercase;
        text-decoration:none;cursor:pointer;border:0;font-family:inherit;
        display:inline-flex;align-items:center;justify-content:center;gap:8px;
        transition:all .2s;
    }
    .pd-btn.cart{background:var(--ed-ink);color:#fff}
    .pd-btn.cart:hover{background:var(--ed-rust);transform:translateY(-1px);box-shadow:0 8px 20px rgba(194,65,12,.3);color:#fff}
    .pd-btn.buy{background:var(--ed-rust);color:#fff}
    .pd-btn.buy:hover{background:var(--ed-ink);transform:translateY(-1px);color:#fff}
    .pd-btn.disabled{background:#E5E5E5;color:#A1A1A1;cursor:not-allowed}
    .pd-btn-wish{
        flex:0 0 auto;width:50px;height:50px;border-radius:6px;
        background:var(--ed-cream-2);border:1.5px solid rgba(42,31,20,.15);
        color:var(--ed-ink-soft);display:inline-flex;align-items:center;justify-content:center;
        font-size:18px;text-decoration:none;transition:all .2s;
    }
    .pd-btn-wish:hover{border-color:#dc2626;color:#dc2626;background:#fff}
    .pd-btn-wish.on{background:#dc2626;border-color:#dc2626;color:#fff}

    .pd-trust{
        display:grid;grid-template-columns:repeat(4,1fr);gap:10px;
        margin-top:24px;padding-top:24px;border-top:1px solid rgba(42,31,20,.1);
    }
    .pd-trust-item{text-align:center}
    .pd-trust-item i{
        width:42px;height:42px;border-radius:50%;
        background:var(--ed-cream-2);color:var(--ed-rust);
        display:inline-flex;align-items:center;justify-content:center;font-size:14px;margin-bottom:6px;
    }
    .pd-trust-item div{font-size:10.5px;color:var(--ed-ink-soft);font-weight:600;letter-spacing:.3px}

    /* Description / Comments / Related */
    .pd-block{
        margin-top:36px;background:var(--ed-paper);border:1px solid rgba(42,31,20,.1);
        border-radius:8px;padding:28px;
    }
    .pd-block-title{
        font-family:'Fraunces',serif;font-style:italic;font-weight:700;font-size:22px;
        color:var(--ed-ink);margin:0 0 18px;display:flex;align-items:center;gap:10px;
    }
    .pd-block-title i{color:var(--ed-rust);font-size:18px}
    .pd-block-title .pd-count{
        font-family:'Inter',sans-serif;font-style:normal;font-weight:700;
        background:var(--ed-rust);color:#fff;font-size:11px;padding:3px 10px;border-radius:999px;
    }
    .pd-desc{color:var(--ed-ink-soft);line-height:1.8;font-size:14.5px;margin:0}

    /* Comment form */
    .pd-cmt-form{background:var(--ed-cream-2);padding:20px;border-radius:8px;border:1px dashed rgba(42,31,20,.15);margin-bottom:22px}
    .pd-cmt-author{display:flex;align-items:center;gap:12px;margin-bottom:14px}
    .pd-cmt-avatar{
        width:44px;height:44px;border-radius:50%;flex-shrink:0;
        background:var(--ed-rust);color:#fff;
        font-weight:800;font-size:17px;
        display:flex;align-items:center;justify-content:center;
    }
    .pd-cmt-author strong{font-family:'Fraunces',serif;font-weight:700;color:var(--ed-ink);display:block;font-size:15px}
    .pd-cmt-author small{color:var(--ed-cocoa);font-size:12px}

    .pd-rating-input{display:inline-flex;flex-direction:row-reverse;font-size:24px;margin:6px 0 14px}
    .pd-rating-input input{display:none}
    .pd-rating-input label{color:#D6CCC2;cursor:pointer;padding:0 3px;transition:color .15s}
    .pd-rating-input input:checked ~ label,
    .pd-rating-input label:hover,
    .pd-rating-input label:hover ~ label{color:var(--ed-mustard)}

    .pd-cmt-textarea{
        width:100%;padding:12px 14px;background:var(--ed-paper);
        border:1.5px solid rgba(42,31,20,.15);border-radius:6px;
        font-family:inherit;font-size:14px;color:var(--ed-ink);
        outline:none;resize:vertical;min-height:90px;transition:all .2s;
    }
    .pd-cmt-textarea:focus{border-color:var(--ed-rust);box-shadow:0 0 0 3px rgba(194,65,12,.12)}

    .pd-cmt-submit{
        margin-top:12px;padding:11px 22px;border-radius:6px;border:0;
        background:var(--ed-ink);color:#fff;
        font-size:12px;font-weight:700;letter-spacing:1px;text-transform:uppercase;
        cursor:pointer;transition:all .2s;font-family:inherit;
        display:inline-flex;align-items:center;gap:8px;
    }
    .pd-cmt-submit:hover{background:var(--ed-rust)}

    .pd-login-cta{
        text-align:center;padding:36px 24px;border-radius:8px;
        background:linear-gradient(135deg,var(--ed-cream-2),var(--ed-cream-3));
        border:1px dashed rgba(194,65,12,.3);margin-bottom:22px;
    }
    .pd-login-cta i.lock{font-size:32px;color:var(--ed-rust);margin-bottom:10px;display:block}
    .pd-login-cta h6{font-family:'Fraunces',serif;font-style:italic;font-weight:700;color:var(--ed-ink);margin:0 0 4px;font-size:18px}
    .pd-login-cta p{color:var(--ed-ink-soft);margin:0 0 16px;font-size:13.5px}
    .pd-login-cta a{
        display:inline-flex;align-items:center;gap:8px;
        padding:11px 22px;border-radius:6px;
        background:var(--ed-rust);color:#fff;text-decoration:none;
        font-size:12px;font-weight:700;letter-spacing:1px;text-transform:uppercase;
        transition:all .2s;
    }
    .pd-login-cta a:hover{background:var(--ed-ink);color:#fff}

    .pd-cmt-list{display:flex;flex-direction:column;gap:14px}
    .pd-cmt-item{
        display:flex;gap:14px;padding:18px;border-radius:8px;
        background:var(--ed-cream-2);border:1px solid rgba(42,31,20,.06);
    }
    .pd-cmt-body{flex:1;min-width:0}
    .pd-cmt-head{display:flex;justify-content:space-between;align-items:center;margin-bottom:6px;flex-wrap:wrap;gap:6px}
    .pd-cmt-head strong{font-family:'Fraunces',serif;font-weight:700;color:var(--ed-ink);font-size:15px}
    .pd-cmt-stars{margin-left:8px;font-size:12px;color:var(--ed-mustard);letter-spacing:1px}
    .pd-cmt-stars .off{color:#D6CCC2}
    .pd-cmt-time{color:var(--ed-cocoa);font-size:12px}
    .pd-cmt-text{margin:0;color:var(--ed-ink-soft);font-size:14px;line-height:1.65;word-wrap:break-word}
    .pd-cmt-empty{text-align:center;color:var(--ed-cocoa);padding:30px}
    .pd-cmt-empty i{font-size:36px;color:var(--ed-cocoa);margin-bottom:8px;display:block}

    /* Related */
    .pd-rel-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:16px}
    @media(max-width:768px){.pd-rel-grid{grid-template-columns:repeat(2,1fr)}}
    .pd-rel-card{
        display:block;background:var(--ed-paper);border:1px solid rgba(42,31,20,.1);
        border-radius:8px;overflow:hidden;text-decoration:none;color:inherit;
        transition:all .25s;
    }
    .pd-rel-card:hover{transform:translateY(-4px);border-color:var(--ed-rust);box-shadow:0 12px 28px rgba(42,31,20,.1);color:inherit}
    .pd-rel-img{aspect-ratio:1/1;background:linear-gradient(135deg,var(--ed-cream-2),var(--ed-cream-3));overflow:hidden}
    .pd-rel-img img{width:100%;height:100%;object-fit:cover;transition:transform .4s}
    .pd-rel-card:hover .pd-rel-img img{transform:scale(1.06)}
    .pd-rel-body{padding:14px}
    .pd-rel-name{
        font-family:'Fraunces',serif;font-weight:700;font-size:14px;color:var(--ed-ink);
        margin-bottom:6px;line-height:1.35;min-height:38px;
        display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;
    }
    .pd-rel-price{
        font-family:'Fraunces',serif;font-style:italic;font-weight:800;font-size:16px;
        color:var(--ed-rust);
    }
    .pd-rel-stock{font-size:11px;font-weight:600;margin-top:4px}
    .pd-rel-stock.ok{color:#059669}
    .pd-rel-stock.no{color:#dc2626}
</style>

<div class="pd-wrap">
    <nav class="pd-crumbs">
        <a href="<?= BASE_PATH ?>index.php?act=giaodien"><i class="fas fa-home"></i> Trang chủ</a>
        <span class="sep">/</span>
        <a href="<?= BASE_PATH ?>index.php?act=giaodien">Sản phẩm</a>
        <span class="sep">/</span>
        <span class="current"><?= htmlspecialchars($firstVariant['product_name']) ?></span>
    </nav>

    <div class="pd-main">
        <!-- LEFT: image -->
        <div class="pd-img-stage">
            <span class="pd-img-tag"><i class="fas fa-tag"></i> HDTT Store</span>
            <img loading="lazy" src="<?= BASE_PATH ?>uploads/<?= htmlspecialchars($firstVariant['image']) ?>"
                 class="pd-img"
                 alt="<?= htmlspecialchars($firstVariant['product_name']) ?>">
        </div>

        <!-- RIGHT: info + form -->
        <div class="pd-info">
            <span class="pd-eyebrow"><i class="fas fa-fire"></i> Sản phẩm nổi bật</span>
            <h1 class="pd-name"><?= htmlspecialchars($firstVariant['product_name']) ?></h1>

            <div class="pd-rating">
                <div class="pd-rating-stars">
                    <i class="fas fa-star"></i><i class="fas fa-star"></i>
                    <i class="fas fa-star"></i><i class="fas fa-star"></i>
                    <i class="fas fa-star-half-alt"></i>
                </div>
                <span class="pd-rating-num">4.5</span>
                <span class="pd-rating-meta">· <?= count($variants) ?> phiên bản · <?= count($comments) ?> đánh giá</span>
            </div>

            <div class="pd-price-row">
                <span class="pd-price"><?= number_format($minPrice) ?>đ</span>
                <?php if ($maxPrice > $minPrice): ?>
                    <span class="pd-price-range">– <?= number_format($maxPrice) ?>đ</span>
                <?php endif; ?>
            </div>

            <?php if ($hasStock): ?>
                <div class="pd-stock ok"><i class="fas fa-check-circle"></i> Còn hàng (<?= $totalStock ?> sản phẩm)</div>
            <?php else: ?>
                <div class="pd-stock no"><i class="fas fa-times-circle"></i> Tạm hết hàng</div>
            <?php endif; ?>

            <div class="pd-meta">
                <div class="pd-meta-item">
                    <i class="fas fa-palette"></i>
                    <div><small>Màu sắc</small><span><?= count($colors) ?> lựa chọn</span></div>
                </div>
                <div class="pd-meta-item">
                    <i class="fas fa-ruler-combined"></i>
                    <div><small>Kích thước</small><span><?= count($sizes) ?> kích cỡ</span></div>
                </div>
            </div>

            <form method="POST" action="<?= BASE_PATH ?>index.php?act=addToCart">
                        <?= csrf_field() ?>
                <input type="hidden" name="product_id" value="<?= (int)$firstVariant['product_id'] ?>">

                <div class="pd-field">
                    <label class="pd-field-label">Màu sắc</label>
                    <select name="color_id" class="pd-select" required>
                        <option value="">— Chọn màu —</option>
                        <?php foreach ($colors as $colorId => $colorName): ?>
                            <option value="<?= $colorId ?>"><?= htmlspecialchars($colorName) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="pd-field">
                    <label class="pd-field-label">Kích thước</label>
                    <select name="size_id" class="pd-select" required>
                        <option value="">— Chọn size —</option>
                        <?php foreach ($sizes as $sizeId => $sizeName): ?>
                            <option value="<?= $sizeId ?>"><?= htmlspecialchars($sizeName) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="pd-field">
                    <label class="pd-field-label">Số lượng</label>
                    <div class="pd-qty-box">
                        <button type="button" class="pd-qty-btn" onclick="this.nextElementSibling.stepDown()">−</button>
                        <input type="number" name="quantity" class="pd-qty-input" value="1" min="1" required>
                        <button type="button" class="pd-qty-btn" onclick="this.previousElementSibling.stepUp()">+</button>
                    </div>
                </div>

                <div class="pd-actions">
                    <?php if ($hasStock): ?>
                        <button type="submit" class="pd-btn cart"><i class="fas fa-shopping-cart"></i> Thêm vào giỏ</button>
                        <button type="submit" class="pd-btn buy"><i class="fas fa-bolt"></i> Mua ngay</button>
                        <a href="<?= BASE_PATH ?>index.php?act=<?= isset($_SESSION['user_id']) ? 'toggleWishlist' : 'loginUser' ?>&id=<?= (int)$firstVariant['product_id'] ?>&back=detail"
                           class="pd-btn-wish <?= $isFavorited ? 'on' : '' ?>"
                           title="<?= $isFavorited ? 'Bỏ khỏi yêu thích' : 'Thêm vào yêu thích' ?>">
                            <i class="<?= $isFavorited ? 'fas' : 'far' ?> fa-heart"></i>
                        </a>
                    <?php else: ?>
                        <button type="button" class="pd-btn disabled" disabled><i class="fas fa-ban"></i> Hết hàng</button>
                    <?php endif; ?>
                </div>
            </form>

            <div class="pd-trust">
                <div class="pd-trust-item"><i class="fas fa-shipping-fast"></i><div>Giao toàn quốc</div></div>
                <div class="pd-trust-item"><i class="fas fa-undo-alt"></i><div>Đổi trả 7 ngày</div></div>
                <div class="pd-trust-item"><i class="fas fa-shield-alt"></i><div>Chính hãng 100%</div></div>
                <div class="pd-trust-item"><i class="fas fa-headset"></i><div>Hỗ trợ 24/7</div></div>
            </div>
        </div>
    </div>

    <!-- Description -->
    <?php if (!empty($firstVariant['description'])): ?>
    <div class="pd-block">
        <h3 class="pd-block-title"><i class="fas fa-align-left"></i> Mô tả sản phẩm</h3>
        <p class="pd-desc"><?= nl2br(htmlspecialchars($firstVariant['description'])) ?></p>
    </div>
    <?php endif; ?>

    <!-- Comments -->
    <div id="comments" class="pd-block">
        <h3 class="pd-block-title">
            <i class="fas fa-comments"></i> Bình luận & Đánh giá
            <span class="pd-count"><?= count($comments) ?></span>
        </h3>

        <?php if (isset($_SESSION['user_id'])): ?>
            <form method="POST" action="<?= BASE_PATH ?>index.php?act=addComment" class="pd-cmt-form">
                        <?= csrf_field() ?>
                <input type="hidden" name="product_id" value="<?= (int)$firstVariant['product_id'] ?>">

                <div class="pd-cmt-author">
                    <div class="pd-cmt-avatar"><?= strtoupper(mb_substr($_SESSION['user'] ?? '?', 0, 1)) ?></div>
                    <div>
                        <strong><?= htmlspecialchars($_SESSION['user'] ?? '') ?></strong>
                        <small>Đang bình luận với tài khoản của bạn</small>
                    </div>
                </div>

                <label class="pd-field-label">Đánh giá của bạn</label>
                <div class="pd-rating-input">
                    <?php for ($i = 5; $i >= 1; $i--): ?>
                        <input type="radio" name="rating" id="star<?= $i ?>" value="<?= $i ?>" <?= $i === 5 ? 'checked' : '' ?>>
                        <label for="star<?= $i ?>" title="<?= $i ?> sao"><i class="fas fa-star"></i></label>
                    <?php endfor; ?>
                </div>

                <textarea name="content" class="pd-cmt-textarea" placeholder="Chia sẻ cảm nhận của bạn về sản phẩm..." required maxlength="500"></textarea>

                <button type="submit" class="pd-cmt-submit"><i class="fas fa-paper-plane"></i> Gửi bình luận</button>
            </form>
        <?php else: ?>
            <div class="pd-login-cta">
                <i class="fas fa-lock lock"></i>
                <h6>Vui lòng đăng nhập để bình luận</h6>
                <p>Chia sẻ trải nghiệm của bạn với cộng đồng HDTT</p>
                <a href="<?= BASE_PATH ?>index.php?act=loginUser"><i class="fas fa-sign-in-alt"></i> Đăng nhập ngay</a>
            </div>
        <?php endif; ?>

        <?php if (!empty($comments)): ?>
            <div class="pd-cmt-list">
                <?php foreach ($comments as $cmt): ?>
                    <div class="pd-cmt-item">
                        <div class="pd-cmt-avatar"><?= strtoupper(mb_substr($cmt['username'] ?? '?', 0, 1)) ?></div>
                        <div class="pd-cmt-body">
                            <div class="pd-cmt-head">
                                <div>
                                    <strong><?= htmlspecialchars($cmt['username'] ?? 'Khách') ?></strong>
                                    <span class="pd-cmt-stars">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <i class="fas fa-star <?= $i <= (int)$cmt['rating'] ? '' : 'off' ?>"></i>
                                        <?php endfor; ?>
                                    </span>
                                </div>
                                <span class="pd-cmt-time"><i class="far fa-clock"></i> <?= date('d/m/Y H:i', strtotime($cmt['created_at'])) ?></span>
                            </div>
                            <p class="pd-cmt-text"><?= nl2br(htmlspecialchars($cmt['content'] ?? '')) ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="pd-cmt-empty">
                <i class="far fa-comment-dots"></i>
                Chưa có bình luận nào — hãy là người đầu tiên!
            </div>
        <?php endif; ?>
    </div>

    <!-- Related products -->
    <?php if (!empty($relatedProducts)): ?>
    <div class="pd-block">
        <h3 class="pd-block-title"><i class="fas fa-th-large"></i> Có thể bạn cũng thích</h3>
        <div class="pd-rel-grid">
            <?php foreach ($relatedProducts as $rp): $rpStock = (int)($rp['total_stock'] ?? 0); ?>
                <a href="<?= BASE_PATH ?>index.php?act=detail&id=<?= (int)$rp['product_id'] ?>" class="pd-rel-card">
                    <div class="pd-rel-img">
                        <img loading="lazy" src="<?= BASE_PATH ?>uploads/<?= htmlspecialchars($rp['image']) ?>"
                             alt="<?= htmlspecialchars($rp['product_name']) ?>" loading="lazy">
                    </div>
                    <div class="pd-rel-body">
                        <div class="pd-rel-name"><?= htmlspecialchars($rp['product_name']) ?></div>
                        <div class="pd-rel-price"><?= number_format((int)$rp['min_price']) ?>đ</div>
                        <?php if ($rpStock > 0): ?>
                            <div class="pd-rel-stock ok"><i class="fas fa-check-circle"></i> Còn hàng</div>
                        <?php else: ?>
                            <div class="pd-rel-stock no"><i class="fas fa-times-circle"></i> Hết hàng</div>
                        <?php endif; ?>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>
</div>

<!-- Dark Premium overrides for product detail -->
<style>
    body{background:var(--bg) !important;color:var(--text) !important}
    .pd-crumbs,.pd-crumbs a{color:var(--text-3) !important}
    .pd-crumbs a:hover{color:var(--accent-2) !important}
    .pd-crumbs .sep{color:var(--muted) !important}
    .pd-crumbs .current{color:var(--text) !important}

    .pd-img-stage{background:linear-gradient(135deg,var(--bg-2),var(--bg-3)) !important;border:1px solid var(--border) !important}
    .pd-img-stage::before{background:radial-gradient(circle,rgba(216,255,0,.28),transparent 70%) !important}
    .pd-img-tag{background:var(--grad) !important;color:#fff !important}

    .pd-eyebrow{background:var(--grad-soft) !important;border:1px solid var(--border-glow) !important;color:#D8FF00 !important}
    .pd-name{color:var(--text) !important;font-family:'Archivo',sans-serif !important;font-style:normal !important}
    .pd-rating{border-bottom:1px solid var(--border) !important}
    .pd-rating-stars,.pd-cmt-stars{color:#FCD34D !important}
    .pd-rating-num{color:var(--text) !important}
    .pd-rating-meta{color:var(--text-3) !important}
    .pd-price{color:transparent !important;background:var(--grad);-webkit-background-clip:text;background-clip:text;font-family:'Archivo',sans-serif !important;font-style:normal !important}
    .pd-price-range{color:var(--text-3) !important;font-family:'Archivo',sans-serif !important;font-style:normal !important}

    .pd-stock.ok{background:rgba(52,211,153,.15) !important;color:#6EE7B7 !important}
    .pd-stock.no{background:rgba(251,113,133,.15) !important;color:#FDA4AF !important}

    .pd-meta-item{background:var(--surface-2) !important;border:1px solid var(--border) !important}
    .pd-meta-item i{background:var(--bg-3) !important;color:var(--accent-3) !important}
    .pd-meta-item small{color:var(--text-3) !important}
    .pd-meta-item span{color:var(--text) !important}

    .pd-field-label{color:var(--text-2) !important}
    .pd-select{background:var(--surface-2) !important;border:1.5px solid var(--border-2) !important;color:var(--text) !important}
    .pd-select:focus{border-color:var(--accent) !important;box-shadow:0 0 0 3px rgba(216,255,0,.2) !important}
    .pd-select option{background:var(--bg-2);color:var(--text)}
    .pd-qty-box{background:var(--surface-2) !important;border:1.5px solid var(--border-2) !important}
    .pd-qty-btn{color:var(--text) !important}
    .pd-qty-btn:hover{background:var(--accent) !important;color:#fff !important}
    .pd-qty-input{color:var(--text) !important}

    .pd-btn.cart{background:var(--surface-3) !important;color:#fff !important;border:1px solid var(--border-2) !important}
    .pd-btn.cart:hover{background:var(--surface-3) !important;border-color:var(--border-glow) !important;box-shadow:0 8px 22px rgba(216,255,0,.3) !important}
    .pd-btn.buy{background:var(--grad) !important;color:#fff !important}
    .pd-btn.buy:hover{filter:brightness(1.1);color:#fff !important;box-shadow:0 10px 26px rgba(216,255,0,.5) !important}
    .pd-btn.disabled{background:var(--surface) !important;color:var(--muted) !important}
    .pd-btn-wish{background:var(--surface-2) !important;border:1.5px solid var(--border-2) !important;color:var(--text-2) !important}
    .pd-btn-wish:hover{border-color:var(--danger) !important;color:var(--danger) !important;background:var(--surface-3) !important}
    .pd-btn-wish.on{background:var(--danger) !important;border-color:var(--danger) !important;color:#fff !important}

    .pd-trust{border-top:1px solid var(--border) !important}
    .pd-trust-item i{background:var(--surface-2) !important;color:var(--accent-3) !important}
    .pd-trust-item div{color:var(--text-3) !important}

    .pd-block{background:var(--surface) !important;border:1px solid var(--border) !important;backdrop-filter:blur(12px)}
    .pd-block-title{color:var(--text) !important;font-family:'Archivo',sans-serif !important;font-style:normal !important}
    .pd-block-title i{color:var(--accent) !important}
    .pd-block-title .pd-count{background:var(--grad) !important;color:#fff !important}
    .pd-desc{color:var(--text-2) !important}

    .pd-cmt-form{background:var(--surface-2) !important;border:1px dashed var(--border-2) !important}
    .pd-cmt-avatar{background:var(--grad) !important;color:#fff !important}
    .pd-cmt-author strong{color:var(--text) !important;font-family:'Archivo',sans-serif !important}
    .pd-cmt-author small{color:var(--text-3) !important}
    .pd-rating-input label{color:var(--border-2) !important}
    .pd-rating-input input:checked ~ label,.pd-rating-input label:hover,.pd-rating-input label:hover ~ label{color:#FCD34D !important}
    .pd-cmt-textarea{background:var(--surface-2) !important;border:1.5px solid var(--border-2) !important;color:var(--text) !important}
    .pd-cmt-textarea:focus{border-color:var(--accent) !important;box-shadow:0 0 0 3px rgba(216,255,0,.2) !important}
    .pd-cmt-submit{background:var(--grad) !important;color:#fff !important}
    .pd-cmt-submit:hover{filter:brightness(1.1)}

    .pd-login-cta{background:var(--grad-soft) !important;border:1px dashed var(--border-glow) !important}
    .pd-login-cta i.lock{color:var(--accent) !important}
    .pd-login-cta h6{color:var(--text) !important;font-family:'Archivo',sans-serif !important;font-style:normal !important}
    .pd-login-cta p{color:var(--text-2) !important}
    .pd-login-cta a{background:var(--grad) !important;color:#fff !important}

    .pd-cmt-item{background:var(--surface-2) !important;border:1px solid var(--border) !important}
    .pd-cmt-head strong{color:var(--text) !important;font-family:'Archivo',sans-serif !important}
    .pd-cmt-stars .off{color:var(--border-2) !important}
    .pd-cmt-time{color:var(--text-3) !important}
    .pd-cmt-text{color:var(--text-2) !important}
    .pd-cmt-empty,.pd-cmt-empty i{color:var(--text-3) !important}

    .pd-rel-card{background:var(--surface) !important;border:1px solid var(--border) !important;color:inherit !important}
    .pd-rel-card:hover{border-color:var(--border-glow) !important;box-shadow:0 16px 36px rgba(0,0,0,.5) !important}
    .pd-rel-img{background:var(--bg-3) !important}
    .pd-rel-name{color:var(--text) !important;font-family:'Archivo',sans-serif !important}
    .pd-rel-price{color:transparent !important;background:var(--grad);-webkit-background-clip:text;background-clip:text;font-family:'Archivo',sans-serif !important;font-style:normal !important}
    .pd-rel-stock.ok{color:#6EE7B7 !important}
    .pd-rel-stock.no{color:#FDA4AF !important}
</style>

<?php require_once __DIR__ . '/_footer.php'; ?>
