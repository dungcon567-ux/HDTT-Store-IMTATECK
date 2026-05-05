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

// Gom giá min-max nếu các biến thể chênh giá
$minPrice = PHP_INT_MAX; $maxPrice = 0;
foreach ($variants as $v) {
    $p = (int)$v['price'];
    if ($p < $minPrice) $minPrice = $p;
    if ($p > $maxPrice) $maxPrice = $p;
}

// Wishlist state
$isFavorited = false;
if (isset($_SESSION['user_id'])) {
    require_once __DIR__ . '/../../../models/Product.php';
    $_pmw = new Product();
    $isFavorited = $_pmw->isInWishlist($_SESSION['user_id'], (int)$firstVariant['product_id']);
}
?>

<style>
    .pdetail-card{
        border:1px solid #ececec;
        border-radius:18px;
        background:#fff;
        box-shadow:0 6px 24px rgba(31,41,59,.05);
    }
    .pdetail-img-wrap{
        background:linear-gradient(135deg,#f8fafc,#eef2ff);
        border-radius:18px;
        padding:18px;
        position:relative;
        overflow:hidden;
    }
    .pdetail-img-wrap::before{
        content:"";
        position:absolute;inset:0;
        background:radial-gradient(400px 300px at 80% 0%, rgba(124,92,255,.08), transparent 60%);
        pointer-events:none;
    }
    .pdetail-img{
        width:100%;
        max-height:520px;
        object-fit:cover;
        border-radius:14px;
        transition:transform .4s ease;
    }
    .pdetail-img:hover{transform:scale(1.02)}

    .badge-cat{
        display:inline-flex;align-items:center;gap:6px;
        padding:5px 12px;border-radius:999px;
        font-size:11.5px;font-weight:600;
        color:var(--bs-primary);
        background:rgba(13,110,253,.08);
        text-transform:uppercase;letter-spacing:1px;
    }
    .price-now{
        font-size:36px;font-weight:800;letter-spacing:-.5px;
        background:linear-gradient(135deg,#0d6efd,#6610f2);
        -webkit-background-clip:text;-webkit-text-fill-color:transparent;
    }
    .price-range{font-size:14px;color:#6c757d;margin-left:8px}

    .meta-grid{display:grid;grid-template-columns:1fr 1fr;gap:10px;margin:18px 0}
    .meta-item{
        display:flex;align-items:center;gap:10px;
        padding:10px 14px;border-radius:12px;
        background:#f8f9fa;border:1px solid #ececec;
    }
    .meta-item i{color:var(--bs-primary);font-size:18px}
    .meta-item small{color:#6c757d;font-size:11px;text-transform:uppercase;letter-spacing:.6px;display:block}
    .meta-item span{font-weight:600;font-size:13.5px}

    .form-label-strong{font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:#374151}

    .qty-box{display:inline-flex;align-items:center;border:1px solid #ddd;border-radius:12px;overflow:hidden}
    .qty-btn{width:42px;height:42px;background:#f8f9fa;border:0;font-size:18px;color:#374151;cursor:pointer;transition:.2s}
    .qty-btn:hover{background:#0d6efd;color:#fff}
    .qty-input{width:60px;text-align:center;border:0;height:42px;font-weight:700;outline:none}

    .stock-pill{
        display:inline-flex;align-items:center;gap:8px;
        padding:7px 14px;border-radius:999px;font-size:12.5px;font-weight:600;
    }
    .stock-pill.ok{background:rgba(25,135,84,.1);color:#198754}
    .stock-pill.no{background:rgba(220,53,69,.1);color:#dc3545}

    .trust-row{
        display:grid;grid-template-columns:repeat(4,1fr);gap:10px;
        margin-top:22px;padding-top:18px;border-top:1px solid #ececec;
    }
    .trust{text-align:center}
    .trust i{font-size:20px;color:var(--bs-primary)}
    .trust div{font-size:11px;color:#6c757d;margin-top:4px;font-weight:500}

    .desc-block{
        margin-top:30px;padding:24px;border-radius:18px;
        background:#fff;border:1px solid #ececec;
    }
    .desc-block h5{font-weight:700;border-left:4px solid #0d6efd;padding-left:10px;margin-bottom:14px}
    .desc-block p{color:#475569;line-height:1.8;margin:0}

    @media(max-width:768px){
        .meta-grid,.trust-row{grid-template-columns:1fr 1fr}
        .price-now{font-size:28px}
    }
</style>

<div class="container py-5">
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="/Duan1/giaodien/duaan1-giaodien/mvc-oop-basic/index.php?act=giaodien" class="text-decoration-none">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="/Duan1/giaodien/duaan1-giaodien/mvc-oop-basic/index.php?act=giaodien" class="text-decoration-none">Sản phẩm</a></li>
            <li class="breadcrumb-item active text-truncate" style="max-width:300px"><?= htmlspecialchars($firstVariant['product_name']) ?></li>
        </ol>
    </nav>

    <div class="row g-4 align-items-start">
        <div class="col-lg-6">
            <div class="pdetail-img-wrap">
                <img src="/Duan1/giaodien/duaan1-giaodien/mvc-oop-basic/uploads/<?= htmlspecialchars($firstVariant['image']) ?>"
                     class="pdetail-img"
                     alt="<?= htmlspecialchars($firstVariant['product_name']) ?>">
            </div>
        </div>

        <div class="col-lg-6">
            <div class="pdetail-card p-4 p-lg-5">
                <span class="badge-cat"><i class="fas fa-tag"></i> HDTT Store</span>
                <h2 class="mt-3 mb-2 fw-bold"><?= htmlspecialchars($firstVariant['product_name']) ?></h2>

                <div class="d-flex align-items-center mb-3">
                    <div class="text-warning me-2" style="font-size:14px">
                        <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                        <i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i>
                    </div>
                    <small class="text-muted">4.5 · <?= count($variants) ?> phiên bản</small>
                </div>

                <div class="mb-2">
                    <span class="price-now"><?= number_format($minPrice) ?>đ</span>
                    <?php if ($maxPrice > $minPrice): ?>
                        <span class="price-range">– <?= number_format($maxPrice) ?>đ</span>
                    <?php endif; ?>
                </div>

                <?php if ($hasStock): ?>
                    <div class="stock-pill ok mb-3">
                        <i class="fas fa-check-circle"></i> Còn hàng (<?= $totalStock ?> sản phẩm)
                    </div>
                <?php else: ?>
                    <div class="stock-pill no mb-3">
                        <i class="fas fa-times-circle"></i> Tạm hết hàng
                    </div>
                <?php endif; ?>

                <div class="meta-grid">
                    <div class="meta-item">
                        <i class="fas fa-palette"></i>
                        <div>
                            <small>Màu</small>
                            <span><?= count($colors) ?> lựa chọn</span>
                        </div>
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-ruler-combined"></i>
                        <div>
                            <small>Size</small>
                            <span><?= count($sizes) ?> kích cỡ</span>
                        </div>
                    </div>
                </div>

                <form method="POST" action="/Duan1/giaodien/duaan1-giaodien/mvc-oop-basic/index.php?act=addToCart">
                    <input type="hidden" name="product_id" value="<?= (int)$firstVariant['product_id'] ?>">

                    <div class="mb-3">
                        <label class="form-label-strong d-block mb-2">Màu sắc</label>
                        <select name="color_id" class="form-select rounded-pill" required>
                            <option value="">-- Chọn màu --</option>
                            <?php foreach ($colors as $colorId => $colorName): ?>
                                <option value="<?= $colorId ?>"><?= htmlspecialchars($colorName) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label-strong d-block mb-2">Kích thước</label>
                        <select name="size_id" class="form-select rounded-pill" required>
                            <option value="">-- Chọn size --</option>
                            <?php foreach ($sizes as $sizeId => $sizeName): ?>
                                <option value="<?= $sizeId ?>"><?= htmlspecialchars($sizeName) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="form-label-strong d-block mb-2">Số lượng</label>
                        <div class="qty-box">
                            <button type="button" class="qty-btn" onclick="this.nextElementSibling.stepDown()">−</button>
                            <input type="number" name="quantity" class="qty-input" value="1" min="1" required>
                            <button type="button" class="qty-btn" onclick="this.previousElementSibling.stepUp()">+</button>
                        </div>
                    </div>

                    <div class="d-flex flex-wrap gap-2">
                        <?php if ($hasStock): ?>
                            <button type="submit" class="btn btn-primary rounded-pill px-4 py-2">
                                <i class="fas fa-shopping-cart me-2"></i>Thêm vào giỏ
                            </button>
                            <button type="submit" class="btn btn-warning rounded-pill px-4 py-2 text-white">
                                <i class="fas fa-bolt me-2"></i>Mua ngay
                            </button>
                            <a href="/Duan1/giaodien/duaan1-giaodien/mvc-oop-basic/index.php?act=<?= isset($_SESSION['user_id']) ? 'toggleWishlist' : 'loginUser' ?>&id=<?= (int)$firstVariant['product_id'] ?>&back=detail"
                               class="btn rounded-pill px-3 py-2 <?= $isFavorited ? 'btn-danger' : 'btn-outline-danger' ?>"
                               title="<?= $isFavorited ? 'Bỏ khỏi yêu thích' : 'Thêm vào yêu thích' ?>">
                                <i class="<?= $isFavorited ? 'fas' : 'far' ?> fa-heart"></i>
                            </a>
                        <?php else: ?>
                            <button type="button" class="btn btn-danger rounded-pill px-4 py-2" disabled>
                                <i class="fas fa-ban me-2"></i>Hết hàng
                            </button>
                        <?php endif; ?>
                    </div>
                </form>

                <div class="trust-row">
                    <div class="trust"><i class="fas fa-truck"></i><div>Free ship 30k</div></div>
                    <div class="trust"><i class="fas fa-undo"></i><div>Đổi trả 7 ngày</div></div>
                    <div class="trust"><i class="fas fa-shield-alt"></i><div>Hàng chính hãng</div></div>
                    <div class="trust"><i class="fas fa-headset"></i><div>Hỗ trợ 24/7</div></div>
                </div>
            </div>
        </div>
    </div>

    <?php if (!empty($firstVariant['description'])): ?>
    <div class="desc-block">
        <h5><i class="fas fa-align-left me-2"></i>Mô tả sản phẩm</h5>
        <p><?= nl2br(htmlspecialchars($firstVariant['description'])) ?></p>
    </div>
    <?php endif; ?>

    <!-- Bình luận -->
    <div id="comments" class="desc-block mt-4">
        <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
            <h5 class="mb-0"><i class="fas fa-comments me-2"></i>Bình luận & đánh giá <span class="badge bg-primary ms-1"><?= count($comments) ?></span></h5>
        </div>

        <?php if (isset($_SESSION['user_id'])): ?>
            <form method="POST" action="/Duan1/giaodien/duaan1-giaodien/mvc-oop-basic/index.php?act=addComment" class="comment-form">
                <input type="hidden" name="product_id" value="<?= (int)$firstVariant['product_id'] ?>">

                <div class="comment-author mb-3">
                    <div class="cmt-avatar">
                        <?= strtoupper(mb_substr($_SESSION['user'] ?? '?', 0, 1)) ?>
                    </div>
                    <div>
                        <div class="fw-bold"><?= htmlspecialchars($_SESSION['user'] ?? '') ?></div>
                        <small class="text-muted">Đang bình luận với tài khoản của bạn</small>
                    </div>
                </div>

                <div class="mb-2">
                    <small class="d-block mb-1 fw-bold text-muted">Đánh giá của bạn</small>
                    <div class="rating-input">
                        <?php for ($i = 5; $i >= 1; $i--): ?>
                            <input type="radio" name="rating" id="star<?= $i ?>" value="<?= $i ?>" <?= $i === 5 ? 'checked' : '' ?>>
                            <label for="star<?= $i ?>" title="<?= $i ?> sao"><i class="fas fa-star"></i></label>
                        <?php endfor; ?>
                    </div>
                </div>

                <textarea name="content" class="form-control rounded-3 mb-3" rows="3"
                          placeholder="Chia sẻ cảm nhận của bạn về sản phẩm..." required maxlength="500"></textarea>

                <button type="submit" class="btn btn-primary rounded-pill px-4">
                    <i class="fas fa-paper-plane me-2"></i>Gửi bình luận
                </button>
            </form>
        <?php else: ?>
            <div class="login-cta">
                <i class="fas fa-lock fa-2x mb-2 d-block text-primary"></i>
                <h6 class="mb-1">Vui lòng đăng nhập để bình luận</h6>
                <p class="text-muted small mb-3">Chia sẻ trải nghiệm của bạn với cộng đồng HDTT</p>
                <a href="/Duan1/giaodien/duaan1-giaodien/mvc-oop-basic/index.php?act=loginUser" class="btn btn-primary rounded-pill px-4">
                    <i class="fas fa-sign-in-alt me-2"></i>Đăng nhập ngay
                </a>
            </div>
        <?php endif; ?>

        <hr class="my-4">

        <?php if (!empty($comments)): ?>
            <div class="comments-list">
                <?php foreach ($comments as $cmt): ?>
                    <div class="comment-item">
                        <div class="cmt-avatar"><?= strtoupper(mb_substr($cmt['username'] ?? '?', 0, 1)) ?></div>
                        <div class="comment-body">
                            <div class="comment-head">
                                <div>
                                    <strong><?= htmlspecialchars($cmt['username'] ?? 'Khách') ?></strong>
                                    <span class="comment-stars">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <i class="fas fa-star <?= $i <= (int)$cmt['rating'] ? 'star-on' : 'star-off' ?>"></i>
                                        <?php endfor; ?>
                                    </span>
                                </div>
                                <small class="text-muted">
                                    <i class="far fa-clock me-1"></i><?= date('d/m/Y H:i', strtotime($cmt['created_at'])) ?>
                                </small>
                            </div>
                            <p class="comment-text"><?= nl2br(htmlspecialchars($cmt['content'] ?? '')) ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="text-center text-muted py-4">
                <i class="far fa-comment-dots fa-2x mb-2 d-block"></i>
                Chưa có bình luận nào — hãy là người đầu tiên!
            </div>
        <?php endif; ?>
    </div>

    <!-- Sản phẩm cùng loại -->
    <?php if (!empty($relatedProducts)): ?>
    <div class="related-section">
        <div class="related-head">
            <h3>Có thể bạn cũng thích</h3>
            <p class="text-muted">Sản phẩm cùng danh mục</p>
        </div>

        <div class="row g-3">
            <?php foreach ($relatedProducts as $rp): $rpStock = (int)($rp['total_stock'] ?? 0); ?>
                <div class="col-6 col-md-3">
                    <a href="/Duan1/giaodien/duaan1-giaodien/mvc-oop-basic/index.php?act=detail&id=<?= (int)$rp['product_id'] ?>"
                       class="related-card text-decoration-none">
                        <div class="related-img">
                            <img src="/Duan1/giaodien/duaan1-giaodien/mvc-oop-basic/uploads/<?= htmlspecialchars($rp['image']) ?>"
                                 alt="<?= htmlspecialchars($rp['product_name']) ?>" loading="lazy">
                        </div>
                        <div class="related-body">
                            <div class="related-name"><?= htmlspecialchars($rp['product_name']) ?></div>
                            <div class="related-price">
                                <?php if ((int)$rp['min_price'] === (int)$rp['max_price']): ?>
                                    <?= number_format((int)$rp['min_price']) ?>đ
                                <?php else: ?>
                                    <?= number_format((int)$rp['min_price']) ?>đ
                                <?php endif; ?>
                            </div>
                            <?php if ($rpStock > 0): ?>
                                <small class="text-success"><i class="fas fa-check-circle me-1"></i>Còn hàng</small>
                            <?php else: ?>
                                <small class="text-danger">Hết hàng</small>
                            <?php endif; ?>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>
</div>

<style>
    /* Comment styles */
    .comment-form{background:#f8fafc;padding:18px;border-radius:14px;border:1px solid #ececec}
    .comment-author{display:flex;align-items:center;gap:12px}
    .cmt-avatar{
        width:40px;height:40px;border-radius:50%;flex-shrink:0;
        background:linear-gradient(135deg,#0d6efd,#6610f2);
        color:#fff;font-weight:700;font-size:16px;
        display:flex;align-items:center;justify-content:center;
    }
    .login-cta{
        text-align:center;padding:30px 20px;
        background:linear-gradient(135deg,#eff6ff,#f0f9ff);
        border:1px dashed #93c5fd;border-radius:14px;
    }

    /* 5-star input (CSS-only) */
    .rating-input{display:inline-flex;flex-direction:row-reverse;font-size:22px}
    .rating-input input{display:none}
    .rating-input label{color:#cbd5e1;cursor:pointer;padding:0 2px;transition:color .15s}
    .rating-input input:checked ~ label,
    .rating-input label:hover,
    .rating-input label:hover ~ label{color:#fbbf24}

    /* Comment list */
    .comments-list{display:flex;flex-direction:column;gap:14px}
    .comment-item{display:flex;gap:12px;padding:14px;border-radius:12px;background:#fff;border:1px solid #ececec}
    .comment-item:hover{border-color:#cfe2ff;box-shadow:0 4px 12px rgba(13,110,253,.05)}
    .comment-body{flex:1;min-width:0}
    .comment-head{display:flex;justify-content:space-between;align-items:center;margin-bottom:6px;flex-wrap:wrap;gap:6px}
    .comment-stars{margin-left:8px;font-size:11px}
    .star-on{color:#fbbf24}
    .star-off{color:#e2e8f0}
    .comment-text{margin:0;color:#475569;font-size:14px;line-height:1.6;word-wrap:break-word}

    /* Related products */
    .related-section{margin-top:50px;padding-top:30px;border-top:1px solid #ececec}
    .related-head{text-align:center;margin-bottom:24px}
    .related-head h3{font-size:24px;font-weight:800;letter-spacing:-.5px;margin:0 0 4px}
    .related-card{
        display:block;background:#fff;border:1px solid #ececec;
        border-radius:14px;overflow:hidden;
        transition:all .3s;height:100%;
    }
    .related-card:hover{
        transform:translateY(-4px);
        box-shadow:0 10px 24px rgba(15,23,42,.08);
        border-color:#cfe2ff;
    }
    .related-img{aspect-ratio:1/1;overflow:hidden;background:#f8fafc}
    .related-img img{width:100%;height:100%;object-fit:cover;transition:transform .4s}
    .related-card:hover .related-img img{transform:scale(1.06)}
    .related-body{padding:12px}
    .related-name{
        font-size:13.5px;font-weight:600;color:#1e293b;margin-bottom:4px;
        display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;
        overflow:hidden;line-height:1.35;min-height:36px;
    }
    .related-price{font-size:15px;font-weight:800;color:#0d6efd;margin-bottom:2px}
</style>

<?php require_once __DIR__ . '/_footer.php'; ?>
