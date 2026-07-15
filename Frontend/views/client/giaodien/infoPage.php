<?php require_once __DIR__ . '/_header.php'; ?>

<style>
    .info-wrap{max-width:1000px;margin:0 auto;padding:44px 20px 70px}
    .info-hero{border-bottom:2px solid var(--border-2);padding-bottom:22px;margin-bottom:34px}
    .info-eyebrow{display:inline-block;background:var(--accent);color:#000;font-family:'Space Mono',monospace;
        font-weight:700;font-size:11px;letter-spacing:.14em;text-transform:uppercase;padding:5px 12px;margin-bottom:14px}
    .info-hero h1{font-family:'Anton',sans-serif;font-size:clamp(38px,7vw,64px);text-transform:uppercase;margin:0;color:var(--text)}
    .info-hero p{font-family:'Space Mono',monospace;color:var(--text-3);margin:10px 0 0}
    .info-block{background:var(--surface);border:2px solid var(--border);padding:26px 28px;margin-bottom:18px}
    .info-block h3{font-family:'Archivo';font-weight:800;text-transform:uppercase;font-size:18px;margin:0 0 12px;color:var(--text);letter-spacing:.02em}
    .info-block p,.info-block li{color:var(--text-2);line-height:1.75;font-size:15px}
    .info-block ul{margin:0;padding-left:20px}
    .info-grid{display:grid;grid-template-columns:1.1fr 1fr;gap:18px}
    @media(max-width:780px){.info-grid{grid-template-columns:1fr}}
    .info-contact-item{display:flex;gap:14px;align-items:flex-start;padding:14px 0;border-bottom:1px solid var(--border)}
    .info-contact-item:last-child{border-bottom:0}
    .info-contact-item i{width:44px;height:44px;flex-shrink:0;background:var(--accent);color:#000;display:flex;align-items:center;justify-content:center;font-size:18px}
    .info-contact-item .lbl{font-family:'Space Mono',monospace;font-size:11px;text-transform:uppercase;letter-spacing:.08em;color:var(--text-3)}
    .info-contact-item .val{font-weight:700;color:var(--text);margin-top:2px}
    .faq-q{background:var(--surface);border:2px solid var(--border);margin-bottom:10px}
    .faq-q summary{cursor:pointer;padding:16px 20px;font-family:'Archivo';font-weight:700;color:var(--text);list-style:none;display:flex;justify-content:space-between;align-items:center}
    .faq-q summary::-webkit-details-marker{display:none}
    .faq-q summary::after{content:"+";color:var(--accent);font-size:22px;font-family:'Anton'}
    .faq-q[open] summary::after{content:"–"}
    .faq-q .faq-a{padding:0 20px 18px;color:var(--text-2);line-height:1.7}
    .info-num{font-family:'Anton';color:var(--accent);font-size:22px;margin-right:10px}
</style>

<div class="info-wrap" >
    <?php
        $subs = [
            'contact'=>'// Chúng tôi luôn sẵn sàng hỗ trợ bạn 24/7',
            'about'=>'// Câu chuyện thương hiệu streetwear HDTT',
            'policy'=>'// Vận chuyển · Đổi trả · Bảo hành',
            'faq'=>'// Những thắc mắc thường gặp nhất',
            'terms'=>'// Quy định sử dụng dịch vụ',
        ];
    ?>
    <div class="info-hero">
        <span class="info-eyebrow"><i class="fas fa-hashtag"></i> HDTT STORE</span>
        <h1><?= htmlspecialchars($pageTitle) ?></h1>
        <p><?= $subs[$activePage] ?? '' ?></p>
    </div>

    <?php if ($activePage === 'contact'): ?>
        <div class="info-grid">
            <div class="info-block">
                <h3><i class="fas fa-headset" style="color:var(--accent)"></i> Thông tin liên hệ</h3>
                <div class="info-contact-item"><i class="fas fa-map-marker-alt"></i><div><div class="lbl">Địa chỉ</div><div class="val">Hà Nội, Việt Nam</div></div></div>
                <div class="info-contact-item"><i class="fas fa-phone"></i><div><div class="lbl">Hotline</div><div class="val">0866914326</div></div></div>
                <div class="info-contact-item"><i class="fas fa-envelope"></i><div><div class="lbl">Email</div><div class="val">hdttstore@gmail.com</div></div></div>
                <div class="info-contact-item"><i class="fas fa-clock"></i><div><div class="lbl">Giờ làm việc</div><div class="val">8:00 – 22:00 (T2 – CN)</div></div></div>
            </div>
            <div class="info-block">
                <h3><i class="fas fa-paper-plane" style="color:var(--accent)"></i> Gửi tin nhắn</h3>
                <?php if (!empty($contactSent)): ?>
                    <div class="alert alert-success"><i class="fas fa-check-circle me-1"></i> Cảm ơn! Chúng tôi đã nhận được tin nhắn và sẽ phản hồi sớm.</div>
                <?php endif; ?>
                <form method="POST" action="<?= BASE_PATH ?>index.php?act=contactSend">
                    <?= csrf_field() ?>
                    <input type="text" name="name" class="form-control mb-2" placeholder="Họ tên" required>
                    <input type="email" name="email" class="form-control mb-2" placeholder="Email" required>
                    <textarea name="message" class="form-control mb-3" rows="4" placeholder="Nội dung..." required></textarea>
                    <button class="btn btn-primary w-100 py-2"><i class="fas fa-paper-plane me-2"></i>Gửi liên hệ</button>
                </form>
            </div>
        </div>

    <?php elseif ($activePage === 'about'): ?>
        <div class="info-block">
            <h3>Chúng tôi là ai</h3>
            <p>HDTT Store là thương hiệu thời trang streetwear dành cho người trẻ dám thể hiện cá tính. Từ áo, quần đến giày — mỗi sản phẩm được tuyển chọn kỹ về chất liệu và kiểu dáng, hướng tới phong cách "mặc đẹp, sống chất, mỗi ngày".</p>
        </div>
        <div class="info-grid">
            <div class="info-block"><h3><span class="info-num">01</span>Sứ mệnh</h3><p>Mang tới trang phục chính hãng, chất lượng cao với mức giá hợp lý cho giới trẻ Việt.</p></div>
            <div class="info-block"><h3><span class="info-num">02</span>Giá trị</h3><p>Chính hãng 100%, minh bạch, dịch vụ tận tâm và đổi trả dễ dàng.</p></div>
            <div class="info-block"><h3><span class="info-num">03</span>Cam kết</h3><p>Giao nhanh 1–3 ngày toàn quốc, freeship đơn từ 500k, đổi size miễn phí 7 ngày.</p></div>
            <div class="info-block"><h3><span class="info-num">04</span>Cộng đồng</h3><p>Hơn 10.000 khách hàng tin tưởng, đánh giá trung bình 4.8/5 sao.</p></div>
        </div>

    <?php elseif ($activePage === 'policy'): ?>
        <div class="info-block"><h3><i class="fas fa-truck" style="color:var(--accent)"></i> Chính sách vận chuyển</h3>
            <ul><li>Giao hàng toàn quốc trong 1–3 ngày làm việc.</li><li>Miễn phí vận chuyển cho đơn từ 500.000đ.</li><li>Phí ship tiêu chuẩn: 30.000đ.</li></ul></div>
        <div class="info-block"><h3><i class="fas fa-undo" style="color:var(--accent)"></i> Chính sách đổi trả</h3>
            <ul><li>Đổi trả miễn phí trong 7 ngày kể từ khi nhận hàng.</li><li>Sản phẩm còn nguyên tem, nhãn, chưa qua sử dụng.</li><li>Miễn phí đổi size khác nếu còn hàng.</li></ul></div>
        <div class="info-block"><h3><i class="fas fa-shield-alt" style="color:var(--accent)"></i> Chính sách bảo hành</h3>
            <ul><li>Cam kết chính hãng 100%.</li><li>Hỗ trợ kiểm tra và xử lý lỗi từ nhà sản xuất.</li></ul></div>

    <?php elseif ($activePage === 'faq'): ?>
        <?php
            $faqs = [
                ['Làm sao để đặt hàng?','Chọn sản phẩm → chọn màu/size → Thêm vào giỏ → vào Giỏ hàng → Tiến hành thanh toán và điền thông tin nhận hàng.'],
                ['Có những hình thức thanh toán nào?','COD (khi nhận hàng), VNPay, MoMo, ZaloPay (demo). Bạn có thể chọn khi thanh toán.'],
                ['Bao lâu thì nhận được hàng?','Đơn hàng được giao trong 1–3 ngày làm việc trên toàn quốc.'],
                ['Có được đổi size không?','Được. Bạn được đổi size miễn phí trong 7 ngày nếu sản phẩm còn nguyên tem nhãn.'],
                ['Dùng mã giảm giá thế nào?','Vào Giỏ hàng, nhập mã (VD: GIAM10, SALE50K, FREESHIP) vào ô "Nhập mã giảm giá" rồi bấm Áp dụng.'],
                ['Quên mật khẩu thì làm sao?','Vào trang Đăng nhập → bấm "Quên mật khẩu?" → nhập email để nhận liên kết đặt lại.'],
            ];
            foreach ($faqs as $f): ?>
            <details class="faq-q"><summary><?= htmlspecialchars($f[0]) ?></summary><div class="faq-a"><?= htmlspecialchars($f[1]) ?></div></details>
        <?php endforeach; ?>

    <?php else: /* terms */ ?>
        <div class="info-block"><h3><span class="info-num">01</span>Chấp nhận điều khoản</h3><p>Khi sử dụng website HDTT Store, bạn đồng ý tuân thủ các điều khoản và điều kiện được nêu tại đây.</p></div>
        <div class="info-block"><h3><span class="info-num">02</span>Tài khoản</h3><p>Bạn chịu trách nhiệm bảo mật thông tin đăng nhập và mọi hoạt động dưới tài khoản của mình.</p></div>
        <div class="info-block"><h3><span class="info-num">03</span>Đơn hàng & giá</h3><p>Giá sản phẩm có thể thay đổi. Chúng tôi có quyền từ chối/huỷ đơn trong trường hợp bất khả kháng hoặc sai sót hệ thống.</p></div>
        <div class="info-block"><h3><span class="info-num">04</span>Sở hữu trí tuệ</h3><p>Toàn bộ nội dung, hình ảnh, thương hiệu thuộc sở hữu của HDTT Store, không được sao chép khi chưa cho phép.</p></div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/_footer.php'; ?>
