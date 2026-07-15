<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <title>404 - Không tìm thấy trang | HDTT Store</title>
    <meta name="robots" content="noindex">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <base href="<?= BASE_PATH ?>Frontend/views/client/giaodien/">
    <link rel="icon" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32'%3E%3Crect width='32' height='32' fill='%23000'/%3E%3Ctext x='16' y='23' font-family='Arial Black,sans-serif' font-size='20' font-weight='900' fill='%23D8FF00' text-anchor='middle'%3EH%3C/text%3E%3C/svg%3E">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" />
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link href="css/theme.css" rel="stylesheet">
    <style>
        .e404{min-height:100vh;display:flex;align-items:center;justify-content:center;text-align:center;padding:24px}
        .e404-num{font-family:'Anton',sans-serif;font-size:clamp(120px,26vw,320px);line-height:.85;color:var(--accent);
            text-shadow:8px 8px 0 #0A0A0A;letter-spacing:-.02em}
        .e404 h2{font-size:clamp(24px,4vw,40px);margin:6px 0 10px}
        .e404 p{color:var(--text-3);font-family:'Space Mono',monospace;margin-bottom:26px}
        .e404-actions{display:flex;gap:12px;justify-content:center;flex-wrap:wrap}
    </style>
</head>
<body>
    <div class="e404">
        <div>
            <div class="e404-num">404</div>
            <h2>Trang không tồn tại</h2>
            <p>// Đường dẫn bạn tìm đã bị xoá, đổi tên hoặc chưa từng tồn tại.</p>
            <div class="e404-actions">
                <a href="<?= BASE_PATH ?>index.php?act=giaodien" class="btn btn-primary py-2 px-4">
                    <i class="fas fa-home me-2"></i>Về trang chủ
                </a>
                <a href="<?= BASE_PATH ?>index.php?act=giaodien#products" class="btn btn-secondary py-2 px-4">
                    <i class="fas fa-bag-shopping me-2"></i>Xem sản phẩm
                </a>
            </div>
        </div>
    </div>
    <script src="js/theme.js"></script>
</body>
</html>
