<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <title>Đăng nhập - HDTT Store</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <base href="/Duan1/giaodien/duaan1-giaodien/mvc-oop-basic/views/client/giaodien/">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>

<body>

    <div id="spinner"
        class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>

    <div class="container-fluid px-5 d-none border-bottom d-lg-block">
        <div class="row gx-0 align-items-center">
            <div class="col-lg-4 text-center text-lg-start mb-lg-0">
                <div class="d-inline-flex align-items-center" style="height: 45px;">
                    <a href="index.php?act=giaodien" class="text-muted me-2">Trang chủ</a>
                    <small> / </small>
                    <a href="#" class="text-muted mx-2">Hỗ trợ</a>
                    <small> / </small>
                    <a href="#" class="text-muted ms-2">Liên hệ</a>
                </div>
            </div>
            <div class="col-lg-4 text-center d-flex align-items-center justify-content-center">
                <small class="text-dark">Số điện thoại:</small>
                <a href="#" class="text-muted ms-1">0967807956</a>
            </div>
            <div class="col-lg-4 text-center text-lg-end">
                <div class="d-inline-flex align-items-center" style="height: 45px;">
                    <a href="index.php?act=registerUser" class="text-muted ms-2">
                        <small><i class="fa fa-user-plus me-2"></i>Đăng ký</small>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid px-5 py-4 d-none d-lg-block">
        <div class="row gx-0 align-items-center text-center">
            <div class="col-md-4 col-lg-3 text-center text-lg-start">
                <div class="d-inline-flex align-items-center">
                    <a href="index.php?act=giaodien" class="navbar-brand p-0">
                        <h1 class="display-5 text-primary m-0">
                            <i class="fas fa-shopping-bag text-secondary me-2"></i>HDTT Store
                        </h1>
                    </a>
                </div>
            </div>
            <div class="col-md-4 col-lg-6 text-center">
                <div class="position-relative ps-4">
                    <div class="d-flex border rounded-pill">
                        <input class="form-control border-0 rounded-pill w-100 py-3" type="text" placeholder="Tìm kiếm...">
                        <button type="button" class="btn btn-primary rounded-pill py-3 px-5" style="border: 0;">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-lg-3 text-center text-lg-end">
                <div class="d-inline-flex align-items-center">
                    <a href="#" class="text-muted d-flex align-items-center justify-content-center me-3">
                        <span class="rounded-circle btn-md-square border"><i class="fas fa-heart"></i></span>
                    </a>
                    <a href="#" class="text-muted d-flex align-items-center justify-content-center">
                        <span class="rounded-circle btn-md-square border"><i class="fas fa-shopping-cart"></i></span>
                        <span class="text-dark ms-2">0.vnd</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid nav-bar p-0">
        <div class="row gx-0 bg-primary px-5 align-items-center">
            <div class="col-12">
                <nav class="navbar navbar-expand-lg navbar-light bg-primary">
                    <div class="collapse navbar-collapse show">
                        <div class="navbar-nav ms-auto py-0">
                            <a href="/Duan1/giaodien/duaan1-giaodien/mvc-oop-basic/index.php?act=giaodien" class="nav-item nav-link">Trang chủ</a>
                            <a href="#" class="nav-item nav-link active">Đăng nhập</a>
                            <a href="/Duan1/giaodien/duaan1-giaodien/mvc-oop-basic/index.php?act=registerUser" class="nav-item nav-link">Đăng ký</a>
                        </div>
                        <a href="#" class="btn btn-secondary rounded-pill py-2 px-4 px-lg-3">
                            <i class="fa fa-mobile-alt me-2"></i>0967807956
                        </a>
                    </div>
                </nav>
            </div>
        </div>
    </div>

    <div class="container-fluid page-header py-5">
        <h1 class="text-center text-white display-6 wow fadeInUp" data-wow-delay="0.1s">Đăng nhập</h1>
        <ol class="breadcrumb justify-content-center mb-0 wow fadeInUp" data-wow-delay="0.3s">
            <li class="breadcrumb-item"><a href="/Duan1/giaodien/duaan1-giaodien/mvc-oop-basic/index.php?act=giaodien">Trang chủ</a></li>
            <li class="breadcrumb-item active text-white">Đăng nhập</li>
        </ol>
    </div>

    <div class="container-fluid contact py-5">
        <div class="container py-5">
            <div class="p-5 bg-light rounded">
                <div class="row g-4 justify-content-center">
                    <div class="col-12">
                        <div class="text-center mx-auto wow fadeInUp" data-wow-delay="0.1s" style="max-width: 900px;">
                            <h4 class="text-primary border-bottom border-primary border-2 d-inline-block pb-2">Tài khoản</h4>
                            <p class="mb-2 fs-5 text-dark">Đăng nhập để tiếp tục</p>
                        </div>
                    </div>

                    <div class="col-lg-7">
                        <?php if (!empty($errors)): ?>
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    <?php foreach ($errors as $item): ?>
                                        <li><?= htmlspecialchars($item) ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($error)): ?>
                            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                        <?php endif; ?>

                        <form method="POST" action="/Duan1/giaodien/duaan1-giaodien/mvc-oop-basic/index.php?act=loginUser">
                            <div class="row g-4 wow fadeInUp" data-wow-delay="0.1s">
                                <div class="col-12">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="username" name="username" placeholder="Tên đăng nhập">
                                        <label for="username">Tên đăng nhập</label>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-floating">
                                        <input type="password" class="form-control" id="password" name="password" placeholder="Mật khẩu">
                                        <label for="password">Mật khẩu</label>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <button class="btn btn-primary w-100 py-3">
                                        <i class="fas fa-sign-in-alt me-2"></i>Đăng nhập
                                    </button>
                                </div>

                                <div class="col-12 text-center">
                                    <p class="mb-0">
                                        Chưa có tài khoản?
                                        <a href="/Duan1/giaodien/duaan1-giaodien/mvc-oop-basic/index.php?act=registerUser" class="text-primary">Đăng ký ngay</a>
                                    </p>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="col-lg-5 wow fadeInUp" data-wow-delay="0.2s">
                        <div class="h-100 rounded d-flex align-items-center justify-content-center bg-white p-4">
                            <div class="text-center">
                                <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center mb-4 mx-auto"
                                    style="width: 90px; height: 90px;">
                                    <i class="fas fa-user-lock fa-2x text-primary"></i>
                                </div>
                                <h4 class="mb-3">HDTT Store</h4>
                                <p class="mb-2">Đăng nhập bằng tài khoản của bạn.</p>
                                <p class="mb-0 text-muted">Admin sẽ được chuyển tới trang quản trị, người dùng thường sẽ quay về giao diện mua hàng.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
     </div>

    <a href="#" class="btn btn-primary btn-lg-square back-to-top"><i class="fa fa-arrow-up"></i></a>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/wow/wow.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="js/main.js"></script>
</body>
</html>