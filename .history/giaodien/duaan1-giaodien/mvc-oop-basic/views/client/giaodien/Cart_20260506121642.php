<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>

<?php
function hienThiTrangThaiDonHang($status) {
    return match ($status) {
        'cho_xac_nhan'   => 'Chờ xác nhận',
        'da_dat_hang'    => 'Đã đặt hàng',
        'dang_lay_hang'  => 'Đang lấy hàng',
        'dang_van_chuyen'=> 'Đang vận chuyển',
        'da_van_chuyen'  => 'Đã vận chuyển',
        'hoan_thanh'     => 'Hoàn thành',
        'da_huy'         => 'Đã hủy',
        default => 'Không xác định',
    };
}

function hienThiTrangThaiThanhToan($paymentStatus) {
    return match ($paymentStatus) {
        'unpaid'         => 'Chưa thanh toán',
        'paid'           => 'Đã thanh toán',
        'dang_hoan_tien' => 'Đang hoàn tiền',
        'da_hoan_tien'   => 'Đã hoàn tiền',
        default          => 'Không xác định',
    };
}

$pageTitle = 'Giỏ hàng';
$activeNav = 'cart';
require_once __DIR__ . '/_header.php';
?>

<link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,700;9..144,900&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
    :root{
        --ed-cream:#F5F1EA;--ed-cream-2:#FBF6ED;--ed-cream-3:#F5E9D8;
        --ed-paper:#FFFEFB;--ed-ink:#2A1F14;--ed-ink-soft:#5C4A33;
        --ed-rust:#C2410C;--ed-mustard:#CA8A04;--ed-cocoa:#94715A;
    }
    body{background:var(--ed-cream)!important;color:var(--ed-ink)!important;font-family:'Inter',system-ui,sans-serif!important}

    .cart-wrap{max-width:1200px;margin:0 auto;padding:50px 20px}
    .cart-head{margin-bottom:32px;text-align:center}
    .cart-eyebrow{
        display:inline-flex;align-items:center;gap:8px;
        padding:6px 14px;border-radius:999px;
        background:var(--ed-ink);color:var(--ed-cream-2);
        font-size:11.5px;font-weight:700;letter-spacing:2px;text-transform:uppercase;
        margin-bottom:14px;
    }
    .cart-title{
        font-family:'Fraunces',serif;font-style:italic;font-weight:900;
        font-size:42px;letter-spacing:-1px;color:var(--ed-ink);margin:0;
    }
    .cart-title span{color:var(--ed-rust)}
    .cart-sub{font-family:'Fraunces',serif;font-style:italic;color:var(--ed-ink-soft);margin-top:6px;font-size:15px}

    .cart-grid{display:grid;grid-template-columns:1.6fr 1fr;gap:24px;margin-top:30px}
    @media(max-width:900px){.cart-grid{grid-template-columns:1fr}}

    .cart-section{
        background:var(--ed-paper);border:1px solid rgba(42,31,20,.1);
        border-radius:8px;padding:0;overflow:hidden;
    }
    .cart-section-head{
        padding:18px 24px;border-bottom:1px solid rgba(42,31,20,.08);
        background:var(--ed-cream-2);
        display:flex;align-items:center;justify-content:space-between;
    }
    .cart-section-head h3{
        font-family:'Fraunces',serif;font-style:italic;font-weight:700;
        font-size:20px;margin:0;color:var(--ed-ink);
        display:flex;align-items:center;gap:10px;
    }
    .cart-section-head h3 i{color:var(--ed-rust)}
    .cart-checkall{display:inline-flex;align-items:center;gap:8px;font-size:13px;font-weight:600;color:var(--ed-ink-soft);cursor:pointer}
    .cart-checkall input{accent-color:var(--ed-rust);width:16px;height:16px}

    .cart-items{display:flex;flex-direction:column}
    .cart-item{
        display:grid;grid-template-columns:24px 100px 1fr auto;gap:16px;
        padding:20px 24px;border-bottom:1px solid rgba(42,31,20,.06);
        align-items:center;
    }
    .cart-item:last-child{border-bottom:0}
    .cart-item-check{display:flex;align-items:center}
    .cart-item-check input{accent-color:var(--ed-rust);width:18px;height:18px;cursor:pointer}
    .cart-item-img{
        aspect-ratio:1/1;background:linear-gradient(135deg,var(--ed-cream-2),var(--ed-cream-3));
        border-radius:6px;overflow:hidden;
    }
    .cart-item-img img{width:100%;height:100%;object-fit:cover}
    .cart-item-info{min-width:0}
    .cart-item-name{
        font-family:'Fraunces',serif;font-weight:700;font-size:17px;
        color:var(--ed-ink);margin:0 0 6px;line-height:1.3;
        display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;
    }
    .cart-item-attrs{display:flex;flex-wrap:wrap;gap:6px;margin-bottom:10px}
    .cart-item-attr{
        display:inline-flex;align-items:center;gap:5px;
        padding:3px 10px;border-radius:4px;
        background:var(--ed-cream-2);color:var(--ed-ink-soft);
        font-size:11.5px;font-weight:600;
    }
    .cart-item-price{
        font-family:'Fraunces',serif;font-style:italic;font-weight:800;
        color:var(--ed-rust);font-size:18px;
    }
    .cart-item-price small{color:var(--ed-cocoa);font-size:12px;font-weight:500;font-style:normal}

    .cart-item-actions{display:flex;flex-direction:column;align-items:flex-end;gap:10px;min-width:200px}
    .cart-qty-form{display:flex;align-items:center;gap:8px}
    .cart-qty-input{
        width:62px;padding:7px 8px;text-align:center;
        border:1px solid rgba(42,31,20,.15);border-radius:6px;
        font-weight:700;color:var(--ed-ink);outline:none;
        font-family:inherit;background:var(--ed-cream-2);
    }
    .cart-qty-input:focus{border-color:var(--ed-rust);background:#fff}
    .cart-qty-update{
        background:var(--ed-ink);color:#fff;border:0;
        padding:8px 14px;border-radius:6px;
        font-size:11.5px;font-weight:700;letter-spacing:.5px;
        cursor:pointer;text-transform:uppercase;
        transition:background .2s;
    }
    .cart-qty-update:hover{background:var(--ed-rust)}
    .cart-qty-stock{font-size:11.5px;color:var(--ed-ink-soft);font-weight:500}
    .cart-qty-stock b{color:var(--ed-rust)}
    .cart-item-subtotal{
        font-family:'Fraunces',serif;font-weight:800;font-size:16px;color:var(--ed-ink);
    }
    .cart-item-del{
        color:var(--ed-cocoa);font-size:13px;font-weight:600;
        text-decoration:none;display:inline-flex;align-items:center;gap:5px;
        transition:color .2s;
    }
    .cart-item-del:hover{color:#dc2626}

    @media(max-width:640px){
        .cart-item{grid-template-columns:24px 80px 1fr;gap:10px;padding:16px}
        .cart-item-actions{grid-column:1/-1;flex-direction:row;justify-content:space-between;align-items:center;min-width:0;flex-wrap:wrap}
    }

    /* Summary */
    .cart-summary{position:sticky;top:24px}
    .summary-row{
        display:flex;justify-content:space-between;align-items:center;
        padding:12px 0;border-bottom:1px dashed rgba(42,31,20,.1);
        font-size:14px;color:var(--ed-ink-soft);
    }
    .summary-row:last-child{border-bottom:0}
    .summary-row b{color:var(--ed-ink);font-weight:700}
    .summary-total{
        margin-top:8px;padding-top:18px;border-top:2px solid var(--ed-ink);
        display:flex;justify-content:space-between;align-items:baseline;
    }
    .summary-total-label{font-family:'Fraunces',serif;font-style:italic;font-size:18px;color:var(--ed-ink);font-weight:700}
    .summary-total-num{
        font-family:'Fraunces',serif;font-weight:900;font-size:30px;
        color:var(--ed-rust);letter-spacing:-1px;
    }

    .summary-checkout{
        width:100%;padding:14px;margin-top:18px;border:0;
        background:var(--ed-ink);color:#fff;border-radius:6px;
        font-size:13px;font-weight:700;letter-spacing:1.5px;text-transform:uppercase;
        cursor:pointer;transition:all .2s;font-family:inherit;
        display:inline-flex;align-items:center;justify-content:center;gap:8px;
    }
    .summary-checkout:hover{background:var(--ed-rust);transform:translateY(-1px);box-shadow:0 8px 20px rgba(194,65,12,.3)}

    .summary-promo{
        margin-top:14px;padding:14px;border-radius:8px;
        background:var(--ed-cream-2);border:1px dashed rgba(42,31,20,.15);
        font-size:12px;color:var(--ed-ink-soft);text-align:center;
    }
    .summary-promo i{color:var(--ed-mustard);margin-right:5px}

    .empty-cart{
        background:var(--ed-paper);border:1px dashed rgba(42,31,20,.2);
        border-radius:8px;padding:60px 30px;text-align:center;
    }
    .empty-cart i{font-size:42px;color:var(--ed-cocoa);margin-bottom:14px}
    .empty-cart h4{font-family:'Fraunces',serif;font-style:italic;font-weight:700;color:var(--ed-ink);margin:0 0 6px}
    .empty-cart p{color:var(--ed-ink-soft);margin:0 0 20px}

    .cart-btn-link{
        display:inline-flex;align-items:center;gap:8px;
        padding:11px 22px;border-radius:6px;
        font-size:12.5px;font-weight:700;letter-spacing:1px;text-transform:uppercase;
        text-decoration:none;transition:all .2s;
    }
    .cart-btn-link.outline{
        background:transparent;color:var(--ed-ink);
        border:1.5px solid var(--ed-ink);
    }
    .cart-btn-link.outline:hover{background:var(--ed-ink);color:#fff}
    .cart-btn-link.solid{background:var(--ed-rust);color:#fff;border:1.5px solid var(--ed-rust)}
    .cart-btn-link.solid:hover{background:var(--ed-ink);border-color:var(--ed-ink);color:#fff}

    .cart-foot-actions{
        display:flex;justify-content:space-between;align-items:center;
        margin-top:24px;flex-wrap:wrap;gap:12px;
    }
</style>

<div class="cart-wrap">
    <div class="cart-head">
        <span class="cart-eyebrow"><i class="fas fa-shopping-bag"></i> Giỏ hàng của bạn</span>
        <h1 class="cart-title">Sẵn sàng <span>thanh toán?</span></h1>
        <p class="cart-sub">Kiểm tra giỏ hàng và hoàn tất đơn hàng của bạn</p>
    </div>

    <?php if (!empty($cartItems)): ?>
        <div class="cart-grid">
            <!-- Items -->
            <div class="cart-section">
                <div class="cart-section-head">
                    <h3><i class="fas fa-box-open"></i> <?= count($cartItems) ?> sản phẩm</h3>
                    <label class="cart-checkall">
                        <input type="checkbox" id="checkAll"> Chọn tất cả
                    </label>
                </div>

                <div class="cart-items">
                    <?php $total = 0; ?>
                    <?php foreach ($cartItems as $item): ?>
                        <?php $subtotal = $item['price'] * $item['quantity']; $total += $subtotal; ?>
                        <div class="cart-item">
                            <div class="cart-item-check">
                                <input type="checkbox" form="checkoutForm" name="selected_cart[]" value="<?= $item['id'] ?>" class="item-check">
                            </div>
                            <div class="cart-item-img">
                                <img src="/Duan1/giaodien/duaan1-giaodien/mvc-oop-basic/uploads/<?= htmlspecialchars($item['image']) ?>"
                                     alt="<?= htmlspecialchars($item['product_name']) ?>">
                            </div>
                            <div class="cart-item-info">
                                <h4 class="cart-item-name"><?= htmlspecialchars($item['product_name']) ?></h4>
                                <div class="cart-item-attrs">
                                    <span class="cart-item-attr"><i class="fas fa-palette"></i> <?= htmlspecialchars($item['color_name']) ?></span>
                                    <span class="cart-item-attr"><i class="fas fa-ruler"></i> Size <?= htmlspecialchars($item['size_name']) ?></span>
                                </div>
                                <div class="cart-item-price"><?= number_format($item['price']) ?>đ <small>/sp</small></div>
                            </div>
                            <div class="cart-item-actions">
                                <form method="POST" action="/Duan1/giaodien/duaan1-giaodien/mvc-oop-basic/index.php?act=updateCart" class="cart-qty-form">
                                    <input type="hidden" name="cart_id" value="<?= $item['id'] ?>">
                                    <input type="number" name="quantity" value="<?= $item['quantity'] ?>"
                                           min="1" max="<?= (int)$item['stock'] ?>" class="cart-qty-input">
                                    <button type="submit" class="cart-qty-update">Cập nhật</button>
                                </form>
                                <small class="cart-qty-stock">Còn lại: <b><?= max(0, (int)$item['stock'] - (int)$item['quantity']) ?></b>/<?= (int)$item['stock'] ?></small>
                                <div class="cart-item-subtotal"><?= number_format($subtotal) ?>đ</div>
                                <a href="/Duan1/giaodien/duaan1-giaodien/mvc-oop-basic/index.php?act=deleteCart&id=<?= $item['id'] ?>"
                                   class="cart-item-del"
                                   onclick="return confirm('Xóa sản phẩm này khỏi giỏ hàng?')">
                                    <i class="fas fa-trash-alt"></i> Xóa
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Summary -->
            <div class="cart-summary">
                <div class="cart-section">
                    <div class="cart-section-head">
                        <h3><i class="fas fa-receipt"></i> Tóm tắt đơn</h3>
                    </div>
                    <div style="padding:18px 24px">
                        <div class="summary-row"><span>Tạm tính (<?= count($cartItems) ?> sản phẩm)</span><b><?= number_format($total) ?>đ</b></div>
                        <div class="summary-row"><span>Phí vận chuyển</span><b style="color:var(--ed-mustard)">Tính khi thanh toán</b></div>
                        <div class="summary-row"><span>Voucher</span><b style="color:var(--ed-cocoa)">Chưa áp dụng</b></div>
                        <div class="summary-total">
                            <span class="summary-total-label">Tổng cộng</span>
                            <span class="summary-total-num"><?= number_format($total) ?>đ</span>
                        </div>

                        <form method="POST" action="/Duan1/giaodien/duaan1-giaodien/mvc-oop-basic/index.php?act=checkout" id="checkoutForm">
                            <button type="submit" class="summary-checkout">
                                <i class="fas fa-credit-card"></i> Tiến hành thanh toán
                            </button>
                        </form>

                        <div class="summary-promo">
                            <i class="fas fa-shield-alt"></i> Thanh toán an toàn · Đổi trả 7 ngày · Freeship đơn từ 500k
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="cart-foot-actions">
            <a href="/Duan1/giaodien/duaan1-giaodien/mvc-oop-basic/index.php?act=giaodien" class="cart-btn-link outline">
                <i class="fas fa-arrow-left"></i> Tiếp tục mua hàng
            </a>
            <a href="/Duan1/giaodien/duaan1-giaodien/mvc-oop-basic/index.php?act=myOrders" class="cart-btn-link solid">
                <i class="fas fa-box"></i> Lịch sử đơn hàng
            </a>
        </div>

    <?php else: ?>
        <div class="empty-cart">
            <i class="fas fa-shopping-cart"></i>
            <h4>Giỏ hàng đang trống</h4>
            <p>Khám phá các sản phẩm để thêm vào giỏ hàng nhé!</p>
            <a href="/Duan1/giaodien/duaan1-giaodien/mvc-oop-basic/index.php?act=giaodien" class="cart-btn-link solid">
                <i class="fas fa-shopping-bag"></i> Mua sắm ngay
            </a>
        </div>
    <?php endif; ?>
</div>

<script>
document.getElementById('checkAll')?.addEventListener('change', function () {
    document.querySelectorAll('.item-check').forEach(item => { item.checked = this.checked; });
});
</script>

<?php require_once __DIR__ . '/_footer.php'; ?>
