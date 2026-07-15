<?php
$subTotal = 0;
foreach ($orderDetails as $it) { $subTotal += (int)$it['price'] * (int)$it['quantity']; }
$discount = (int)($order['discount'] ?? 0);
$methodNames = ['cod'=>'Thanh toán khi nhận hàng (COD)','momo'=>'MoMo','zalopay'=>'ZaloPay','vnpay'=>'VNPay'];
$statusNames = ['cho_xac_nhan'=>'Chờ xác nhận','da_dat_hang'=>'Đã đặt hàng','dang_lay_hang'=>'Đang lấy hàng','dang_van_chuyen'=>'Đang vận chuyển','da_van_chuyen'=>'Đã vận chuyển','hoan_thanh'=>'Hoàn thành','da_huy'=>'Đã huỷ'];
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <title>Hoá đơn #<?= (int)$order['id'] ?> - HDTT Store</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <base href="<?= BASE_PATH ?>Frontend/views/client/giaodien/">
    <style>
        *{box-sizing:border-box;margin:0;padding:0}
        body{font-family:'Segoe UI',Arial,sans-serif;background:#e9e9ee;color:#111;padding:24px}
        .inv{max-width:820px;margin:0 auto;background:#fff;padding:44px 48px;border:1px solid #ddd}
        .inv-top{display:flex;justify-content:space-between;align-items:flex-start;border-bottom:3px solid #111;padding-bottom:22px;margin-bottom:26px}
        .inv-brand{font-size:30px;font-weight:900;letter-spacing:-.5px}
        .inv-brand span{color:#111}
        .inv-brand small{display:block;font-size:12px;font-weight:600;color:#666;letter-spacing:2px;margin-top:2px}
        .inv-meta{text-align:right;font-size:13px;color:#444}
        .inv-meta .num{font-size:22px;font-weight:800;color:#111}
        .inv-cols{display:flex;justify-content:space-between;gap:30px;margin-bottom:26px}
        .inv-cols h4{font-size:11px;text-transform:uppercase;letter-spacing:1.5px;color:#888;margin-bottom:8px}
        .inv-cols p{font-size:14px;line-height:1.6}
        table{width:100%;border-collapse:collapse;margin-bottom:20px}
        thead th{background:#111;color:#fff;text-align:left;padding:11px 12px;font-size:12px;text-transform:uppercase;letter-spacing:.5px}
        thead th.r,tbody td.r{text-align:right}
        tbody td{padding:12px;border-bottom:1px solid #eee;font-size:13.5px}
        .totals{margin-left:auto;width:320px}
        .totals .row{display:flex;justify-content:space-between;padding:7px 0;font-size:14px}
        .totals .grand{border-top:2px solid #111;margin-top:6px;padding-top:12px;font-size:19px;font-weight:800}
        .badge{display:inline-block;padding:3px 10px;border:1px solid #111;font-size:12px;font-weight:700;border-radius:2px}
        .inv-foot{margin-top:34px;padding-top:18px;border-top:1px solid #eee;text-align:center;color:#888;font-size:12.5px;line-height:1.7}
        .toolbar{max-width:820px;margin:0 auto 16px;display:flex;gap:10px;justify-content:flex-end}
        .btn{padding:10px 20px;border:0;border-radius:4px;font-weight:700;font-size:14px;cursor:pointer;text-decoration:none;display:inline-flex;align-items:center;gap:8px}
        .btn.print{background:#111;color:#fff}
        .btn.back{background:#fff;color:#111;border:1px solid #ccc}
        @media print{
            body{background:#fff;padding:0}
            .inv{border:0;max-width:100%;padding:20px}
            .toolbar{display:none}
        }
    </style>
</head>
<body>
    <div class="toolbar">
        <a href="<?= BASE_PATH ?>index.php?act=orderDetail&id=<?= (int)$order['id'] ?>" class="btn back">← Quay lại đơn hàng</a>
        <button class="btn print" onclick="window.print()">🖨️ In hoá đơn</button>
    </div>

    <div class="inv">
        <div class="inv-top">
            <div>
                <div class="inv-brand">HDTT <span>STORE</span><small>STREETWEAR</small></div>
            </div>
            <div class="inv-meta">
                <div class="num">HOÁ ĐƠN #<?= (int)$order['id'] ?></div>
                <div>Ngày: <?= !empty($order['created_at']) ? date('d/m/Y H:i', strtotime($order['created_at'])) : date('d/m/Y') ?></div>
                <div style="margin-top:6px"><span class="badge"><?= htmlspecialchars($statusNames[$order['status']] ?? $order['status']) ?></span></div>
            </div>
        </div>

        <div class="inv-cols">
            <div>
                <h4>Người nhận</h4>
                <p>
                    <b><?= htmlspecialchars($order['receiver_name']) ?></b><br>
                    <?= htmlspecialchars($order['receiver_phone']) ?><br>
                    <?= htmlspecialchars($order['receiver_address']) ?>
                </p>
            </div>
            <div style="text-align:right">
                <h4>Thanh toán</h4>
                <p>
                    <?= htmlspecialchars($methodNames[$order['payment_method']] ?? $order['payment_method']) ?><br>
                    Trạng thái: <b><?= ($order['payment_status'] ?? '') === 'paid' ? 'Đã thanh toán' : 'Chưa thanh toán' ?></b>
                    <?php if (!empty($order['voucher_code'])): ?><br>Mã giảm giá: <b><?= htmlspecialchars($order['voucher_code']) ?></b><?php endif; ?>
                </p>
            </div>
        </div>

        <table>
            <thead><tr>
                <th>Sản phẩm</th><th>Phân loại</th><th class="r">Đơn giá</th><th class="r">SL</th><th class="r">Thành tiền</th>
            </tr></thead>
            <tbody>
            <?php foreach ($orderDetails as $it): ?>
                <tr>
                    <td><?= htmlspecialchars($it['product_name']) ?></td>
                    <td><?= htmlspecialchars(($it['color_name'] ?? '') . ' / ' . ($it['size_name'] ?? '')) ?></td>
                    <td class="r"><?= number_format((int)$it['price']) ?>đ</td>
                    <td class="r"><?= (int)$it['quantity'] ?></td>
                    <td class="r"><?= number_format((int)$it['price'] * (int)$it['quantity']) ?>đ</td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

        <div class="totals">
            <div class="row"><span>Tạm tính</span><span><?= number_format($subTotal) ?>đ</span></div>
            <div class="row"><span>Phí vận chuyển</span><span><?= number_format((int)$order['shipping_fee']) ?>đ</span></div>
            <?php if ($discount > 0): ?>
                <div class="row"><span>Giảm giá<?= !empty($order['voucher_code']) ? ' ('.htmlspecialchars($order['voucher_code']).')' : '' ?></span><span>−<?= number_format($discount) ?>đ</span></div>
            <?php endif; ?>
            <div class="row grand"><span>Tổng cộng</span><span><?= number_format((int)$order['total']) ?>đ</span></div>
        </div>

        <div class="inv-foot">
            Cảm ơn bạn đã mua sắm tại HDTT Store!<br>
            Hotline: 0866914326 · hdttstore@gmail.com · Hà Nội, Việt Nam
        </div>
    </div>
</body>
</html>
