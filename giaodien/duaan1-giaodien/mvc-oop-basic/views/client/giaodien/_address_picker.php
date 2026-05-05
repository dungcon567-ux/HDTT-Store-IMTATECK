<?php
/**
 * Reusable address picker component (Vietnam-style 4-level address).
 * Variables expected:
 *   $oldAddress (string, optional) - existing address to show as hint
 *   $required (bool, optional, default true)
 */
$oldAddress = $oldAddress ?? '';
$required = $required ?? true;
$pickerId = 'addr_' . uniqid();
?>

<style>
    .addr-picker .form-label-strong{
        font-size:12px;font-weight:700;color:#334155;
        text-transform:uppercase;letter-spacing:.6px;
        margin-bottom:6px;display:block;
    }
    .addr-picker .form-row{display:grid;grid-template-columns:1fr 1fr 1fr;gap:14px;margin-bottom:14px}
    @media(max-width:768px){.addr-picker .form-row{grid-template-columns:1fr}}
    .addr-picker select, .addr-picker input, .addr-picker textarea{
        width:100%;padding:11px 14px;
        border:1.5px solid #e5e7eb;border-radius:11px;
        font-size:14px;background:#fff;outline:none;
        transition:all .2s;font-family:inherit;
    }
    .addr-picker select:focus, .addr-picker input:focus, .addr-picker textarea:focus{
        border-color:#0d6efd;box-shadow:0 0 0 3px rgba(13,110,253,.12);
    }
    .addr-picker select:disabled{background:#f8fafc;cursor:not-allowed;color:#94a3b8}
    .addr-old-hint{
        font-size:12px;color:#64748b;margin-top:6px;
        background:#f8fafc;border:1px dashed #cbd5e1;
        padding:8px 12px;border-radius:8px;
    }
    .addr-old-hint i{color:#0d6efd}
    .addr-preview{
        margin-top:12px;padding:10px 14px;
        background:linear-gradient(135deg,#eff6ff,#dbeafe);
        border:1px solid #93c5fd;border-radius:10px;
        font-size:13.5px;color:#1e40af;
        display:none;
    }
    .addr-preview.show{display:block}
    .addr-preview b{color:#1e3a8a}
</style>

<div class="addr-picker" id="<?= $pickerId ?>">
    <div class="form-row">
        <div>
            <label class="form-label-strong">Tỉnh / Thành phố <?= $required ? '<span style="color:#dc3545">*</span>' : '' ?></label>
            <select class="addr-province" <?= $required ? 'required' : '' ?>>
                <option value="">-- Đang tải... --</option>
            </select>
        </div>
        <div>
            <label class="form-label-strong">Quận / Huyện <?= $required ? '<span style="color:#dc3545">*</span>' : '' ?></label>
            <select class="addr-district" disabled <?= $required ? 'required' : '' ?>>
                <option value="">-- Chọn Tỉnh / TP trước --</option>
            </select>
        </div>
        <div>
            <label class="form-label-strong">Phường / Xã <?= $required ? '<span style="color:#dc3545">*</span>' : '' ?></label>
            <select class="addr-ward" disabled <?= $required ? 'required' : '' ?>>
                <option value="">-- Chọn Quận / Huyện trước --</option>
            </select>
        </div>
    </div>

    <div class="mb-2">
        <label class="form-label-strong">Số nhà, tên đường <?= $required ? '<span style="color:#dc3545">*</span>' : '' ?></label>
        <input type="text" class="addr-street" placeholder="VD: 123 Nguyễn Trãi" <?= $required ? 'required' : '' ?>>
    </div>

    <!-- Hidden field gửi về server: địa chỉ đầy đủ ghép từ 4 trường trên -->
    <input type="hidden" name="receiver_address" class="addr-final" value="<?= htmlspecialchars($oldAddress) ?>">

    <div class="addr-preview">
        <i class="fas fa-map-marker-alt me-2"></i>
        <b>Giao đến:</b> <span class="addr-preview-text"></span>
    </div>

    <?php if (!empty($oldAddress)): ?>
        <div class="addr-old-hint">
            <i class="fas fa-info-circle me-1"></i>
            <b>Địa chỉ hiện tại:</b> <?= htmlspecialchars($oldAddress) ?>
            <br><small class="text-muted">Để giữ nguyên, không cần điền lại bên trên. Chọn địa chỉ mới sẽ ghi đè.</small>
        </div>
    <?php endif; ?>
</div>

<script>
(function() {
    const root = document.getElementById('<?= $pickerId ?>');
    if (!root) return;

    const $province = root.querySelector('.addr-province');
    const $district = root.querySelector('.addr-district');
    const $ward     = root.querySelector('.addr-ward');
    const $street   = root.querySelector('.addr-street');
    const $final    = root.querySelector('.addr-final');
    const $preview  = root.querySelector('.addr-preview');
    const $previewT = root.querySelector('.addr-preview-text');

    const API = 'https://provinces.open-api.vn/api/';
    const oldVal = <?= json_encode($oldAddress) ?>;

    function setOptions($sel, items, placeholder) {
        $sel.innerHTML = '<option value="">' + placeholder + '</option>'
            + items.map(it => `<option value="${it.code}" data-name="${it.name}">${it.name}</option>`).join('');
    }

    function recalc() {
        const pName = $province.options[$province.selectedIndex]?.dataset.name || '';
        const dName = $district.options[$district.selectedIndex]?.dataset.name || '';
        const wName = $ward.options[$ward.selectedIndex]?.dataset.name || '';
        const street = $street.value.trim();

        const parts = [street, wName, dName, pName].filter(Boolean);

        if (parts.length === 4) {
            $final.value = parts.join(', ');
            $previewT.textContent = $final.value;
            $preview.classList.add('show');
        } else if (parts.length > 0) {
            $previewT.textContent = parts.join(', ');
            $preview.classList.add('show');
            // Nếu chưa đủ — không ghi đè giá trị cũ (nếu có)
            if (!oldVal) $final.value = parts.join(', ');
        } else {
            $preview.classList.remove('show');
        }
    }

    // Load provinces
    fetch(API + 'p/')
        .then(r => r.json())
        .then(data => {
            setOptions($province, data, '-- Chọn Tỉnh / Thành phố --');
        })
        .catch(() => {
            $province.innerHTML = '<option value="">Không tải được dữ liệu</option>';
        });

    $province.addEventListener('change', () => {
        const code = $province.value;
        if (!code) {
            $district.innerHTML = '<option value="">-- Chọn Tỉnh / TP trước --</option>';
            $district.disabled = true;
            $ward.innerHTML = '<option value="">-- Chọn Quận / Huyện trước --</option>';
            $ward.disabled = true;
            recalc();
            return;
        }
        $district.disabled = true;
        $district.innerHTML = '<option value="">-- Đang tải... --</option>';
        $ward.innerHTML = '<option value="">-- Chọn Quận / Huyện trước --</option>';
        $ward.disabled = true;

        fetch(API + 'p/' + code + '?depth=2')
            .then(r => r.json())
            .then(data => {
                setOptions($district, data.districts || [], '-- Chọn Quận / Huyện --');
                $district.disabled = false;
                recalc();
            });
    });

    $district.addEventListener('change', () => {
        const code = $district.value;
        if (!code) {
            $ward.innerHTML = '<option value="">-- Chọn Quận / Huyện trước --</option>';
            $ward.disabled = true;
            recalc();
            return;
        }
        $ward.disabled = true;
        $ward.innerHTML = '<option value="">-- Đang tải... --</option>';

        fetch(API + 'd/' + code + '?depth=2')
            .then(r => r.json())
            .then(data => {
                setOptions($ward, data.wards || [], '-- Chọn Phường / Xã --');
                $ward.disabled = false;
                recalc();
            });
    });

    $ward.addEventListener('change', recalc);
    $street.addEventListener('input', recalc);
})();
</script>
