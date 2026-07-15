<?php
$pageTitle = 'Thanh toán';
$activeNav = 'cart';
require_once __DIR__ . '/_header.php';

$selectedMethod = $_POST['payment_method'] ?? 'cod';
?>

<style>
    .pay-card{
        border:2px solid #e5e7eb;border-radius:14px;
        padding:14px 16px;cursor:pointer;
        display:flex;align-items:center;gap:14px;
        transition:.25s;background:#fff;
        position:relative;
    }
    .pay-card:hover{border-color:#0d6efd;transform:translateY(-1px);box-shadow:0 6px 16px rgba(13,110,253,.08)}
    .pay-card input{position:absolute;opacity:0;pointer-events:none}
    .pay-card .pay-logo{
        width:48px;height:48px;border-radius:10px;
        display:flex;align-items:center;justify-content:center;
        font-weight:800;color:#fff;font-size:18px;flex-shrink:0;
    }
    .pay-logo.cod{background:linear-gradient(135deg,#10b981,#34d399)}
    .pay-logo.momo{background:linear-gradient(135deg,#a50064,#d82d8b)}
    .pay-logo.zalopay{background:linear-gradient(135deg,#0068ff,#00b9ff)}
    .pay-logo.vnpay{background:linear-gradient(135deg,#005baa,#00a4e4)}
    .pay-card .pay-name{font-weight:700;font-size:14.5px;margin-bottom:2px}
    .pay-card .pay-desc{font-size:12px;color:#6c757d}
    .pay-card .pay-check{
        margin-left:auto;width:22px;height:22px;border-radius:50%;
        border:2px solid #d1d5db;flex-shrink:0;
        display:flex;align-items:center;justify-content:center;
    }
    .pay-card input:checked + .pay-logo + .pay-info + .pay-check{
        background:#0d6efd;border-color:#0d6efd;
    }
    .pay-card input:checked + .pay-logo + .pay-info + .pay-check::after{
        content:"";width:7px;height:7px;border-radius:50%;background:#fff;
    }
    .pay-card:has(input:checked){border-color:#0d6efd;background:#f0f7ff}
    .pay-info{flex:1}
    .badge-demo{
        display:inline-block;padding:2px 7px;border-radius:6px;
        background:#fef3c7;color:#92400e;font-size:10px;font-weight:700;margin-left:6px;
    }
</style>


<div class="container py-5">
    <h2 class="mb-4">Xác nhận thanh toán</h2>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="row g-4">
        <div class="col-lg-7">
            <form method="POST" action="<?= BASE_PATH ?>index.php?act=placeOrder">
                        <?= csrf_field() ?>
                <?php foreach ($checkoutItems as $item): ?>
                    <input type="hidden" name="selected_cart[]" value="<?= $item['id'] ?>">
                <?php endforeach; ?>

                <div class="card shadow-sm border-0 rounded-4 mb-3">
                    <div class="card-body p-4">
                        <h4 class="mb-3">Thông tin nhận hàng</h4>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Họ tên người nhận</label>
                            <input type="text" name="receiver_name" class="form-control rounded-pill"
                                   value="<?= htmlspecialchars($_POST['receiver_name'] ?? $user['username'] ?? '') ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Số điện thoại</label>
                            <input type="text" name="receiver_phone" class="form-control rounded-pill"
                                   value="<?= htmlspecialchars($_POST['receiver_phone'] ?? $user['std'] ?? '') ?>" required>
                        </div>

                        <div class="mb-1">
                            <label class="form-label fw-bold mb-2">Địa chỉ nhận hàng</label>
                            <?php
                                $oldAddress = $_POST['receiver_address'] ?? $user['diachi'] ?? '';
                                $required = true;
                                require __DIR__ . '/_address_picker.php';
                            ?>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm border-0 rounded-4 mb-3">
                    <div class="card-body p-4">
                        <h4 class="mb-3">Phương thức thanh toán</h4>

                        <div class="d-flex flex-column gap-2">
                            <label class="pay-card">
                                <input type="radio" name="payment_method" value="cod" <?= $selectedMethod==='cod'?'checked':'' ?>>
                                <span class="pay-logo cod"><i class="fas fa-money-bill-wave"></i></span>
                                <span class="pay-info">
                                    <div class="pay-name">Thanh toán khi nhận hàng (COD)</div>
                                    <div class="pay-desc">Trả tiền mặt cho shipper khi nhận hàng</div>
                                </span>
                                <span class="pay-check"></span>
                            </label>

                            <label class="pay-card">
                                <input type="radio" name="payment_method" value="momo" <?= $selectedMethod==='momo'?'checked':'' ?>>
                                <span class="pay-logo momo">M</span>
                                <span class="pay-info">
                                    <div class="pay-name">Ví MoMo <span class="badge-demo">DEMO</span></div>
                                    <div class="pay-desc">Quét mã QR thanh toán qua ứng dụng MoMo</div>
                                </span>
                                <span class="pay-check"></span>
                            </label>

                            <label class="pay-card">
                                <input type="radio" name="payment_method" value="zalopay" <?= $selectedMethod==='zalopay'?'checked':'' ?>>
                                <span class="pay-logo zalopay">Z</span>
                                <span class="pay-info">
                                    <div class="pay-name">ZaloPay <span class="badge-demo">DEMO</span></div>
                                    <div class="pay-desc">Thanh toán qua ví điện tử ZaloPay</div>
                                </span>
                                <span class="pay-check"></span>
                            </label>

                            <label class="pay-card">
                                <input type="radio" name="payment_method" value="vnpay" <?= $selectedMethod==='vnpay'?'checked':'' ?>>
                                <span class="pay-logo vnpay"><i class="fas fa-credit-card"></i></span>
                                <span class="pay-info">
                                    <div class="pay-name">Thẻ ATM / Visa / Master (VNPay) <span class="badge-demo">DEMO</span></div>
                                    <div class="pay-desc">Thẻ nội địa hoặc quốc tế qua cổng VNPay</div>
                                </span>
                                <span class="pay-check"></span>
                            </label>
                        </div>

                        <div class="mt-3 d-flex gap-2">
                            <button type="submit" class="btn btn-primary rounded-pill px-4 py-2">
                                <i class="fas fa-lock me-2"></i>Xác nhận đặt hàng
                            </button>
                            <a href="<?= BASE_PATH ?>index.php?act=cart" class="btn btn-outline-secondary rounded-pill px-4 py-2">
                                Quay lại giỏ
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div class="col-lg-5">
            <div class="card shadow-sm border-0 rounded-4 sticky-top" style="top:20px">
                <div class="card-body p-4">
                    <h4 class="mb-3">Đơn hàng</h4>

                    <?php foreach ($checkoutItems as $item): ?>
                        <div class="d-flex align-items-center border-bottom py-3">
                            <img loading="lazy" src="<?= BASE_PATH ?>uploads/<?= htmlspecialchars($item['image'] ?? '') ?>" width="60" class="rounded me-3">
                            <div class="flex-grow-1">
                                <div class="fw-bold"><?= htmlspecialchars($item['product_name']) ?></div>
                                <div class="text-muted small">
                                    <?= htmlspecialchars($item['color_name'] ?? '') ?> ·
                                    Size <?= htmlspecialchars($item['size_name'] ?? '') ?> ·
                                    SL <?= (int)$item['quantity'] ?>
                                </div>
                            </div>
                            <div class="fw-bold text-primary">
                                <?= number_format($item['price'] * $item['quantity']) ?>đ
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <div class="pt-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Tạm tính</span>
                            <strong><?= number_format($subTotal) ?>đ</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Phí vận chuyển</span>
                            <strong><?= number_format($shippingFee) ?>đ</strong>
                        </div>
                        <?php if (!empty($discount) && $discount > 0): ?>
                        <div class="d-flex justify-content-between mb-2">
                            <span><i class="fas fa-ticket-alt" style="color:var(--accent)"></i>
                                Mã <?= htmlspecialchars($appliedVoucher['code'] ?? '') ?></span>
                            <strong style="color:#6EE7B7">−<?= number_format($discount) ?>đ</strong>
                        </div>
                        <?php endif; ?>
                        <div class="d-flex justify-content-between border-top pt-3">
                            <span class="fw-bold fs-5">Tổng cộng</span>
                            <strong class="text-danger fs-5"><?= number_format($grandTotal) ?>đ</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Dark Premium overrides for checkout -->
<style>
    .pay-card{background:var(--surface-2) !important;border:2px solid var(--border-2) !important}
    .pay-card:hover{border-color:var(--border-glow) !important;box-shadow:0 6px 16px rgba(216,255,0,.15) !important}
    .pay-card .pay-name{color:var(--text) !important}
    .pay-card .pay-desc{color:var(--text-3) !important}
    .pay-card .pay-check{border:2px solid var(--border-2) !important}
    .pay-card:has(input:checked){border-color:var(--accent) !important;background:var(--grad-soft) !important}
    .pay-card input:checked + .pay-logo + .pay-info + .pay-check{background:var(--accent) !important;border-color:var(--accent) !important}
    .badge-demo{background:rgba(251,191,36,.16) !important;color:#FCD34D !important}
</style>

<?php require_once __DIR__ . '/_footer.php'; ?>
