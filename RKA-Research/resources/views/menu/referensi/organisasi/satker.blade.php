<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Referensi Satker | Penelitian RKA-K/L</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root {
            --primary: #0759b7;
            --primary-dark: #06498f;
            --primary-soft: #eef5ff;
            --success: #159957;
            --success-soft: #effaf4;
            --danger: #df4052;
            --danger-soft: #fff4f5;
            --text-primary: #18365b;
            --text-secondary: #607995;
            --text-muted: #91a4b9;
            --background: #f3f6fa;
            --border: #dbe5ee;
            --white: #fff;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box
        }

        html,
        body {
            min-height: 100%
        }

        body {
            min-height: 100vh;
            overflow-x: hidden;
            color: var(--text-primary);
            background: var(--background);
            font-family: Inter, "Segoe UI", Arial, sans-serif
        }

        button,
        input,
        select {
            font: inherit
        }

        button,
        select {
            cursor: pointer
        }

        .app-shell {
            min-height: 100vh
        }

        .app-main {
            min-height: 100vh;
            display: flex;
            flex-direction: column
        }

        .dashboard-header {
            position: sticky;
            top: 0;
            z-index: 900;
            min-height: 66px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 18px;
            padding: 0 25px;
            border-bottom: 1px solid var(--border);
            background: rgba(255, 255, 255, .96);
            box-shadow: 0 4px 18px rgba(33, 67, 103, .05);
            backdrop-filter: blur(12px)
        }

        .header-left {
            min-width: 0;
            display: flex;
            align-items: center;
            gap: 13px
        }

        .sidebar-toggle {
            display: none;
            width: 36px;
            height: 36px;
            align-items: center;
            justify-content: center;
            border: 1px solid var(--border);
            border-radius: 9px;
            color: var(--text-primary);
            background: #fff;
            font-size: 18px
        }

        .header-copy {
            min-width: 0
        }

        .header-eyebrow {
            overflow: hidden;
            color: #879bb1;
            font-size: 7.5px;
            font-weight: 700;
            letter-spacing: .8px;
            text-overflow: ellipsis;
            white-space: nowrap
        }

        .header-title {
            margin-top: 3px;
            color: var(--text-primary);
            font-size: 14px;
            font-weight: 800
        }

        .header-user {
            flex-shrink: 0;
            display: flex;
            align-items: center;
            gap: 10px
        }

        .header-user-text {
            text-align: right;
            color: var(--text-secondary);
            font-size: 8.5px;
            line-height: 1.4
        }

        .header-user-text strong {
            display: block;
            max-width: 200px;
            overflow: hidden;
            color: var(--text-primary);
            font-size: 10px;
            text-overflow: ellipsis;
            white-space: nowrap
        }

        .header-avatar {
            width: 37px;
            height: 37px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 3px solid #e4eef8;
            border-radius: 50%;
            color: #fff;
            background: linear-gradient(135deg, #063c7c, #1681d5);
            box-shadow: 0 5px 12px rgba(31, 91, 148, .16);
            font-size: 10px;
            font-weight: 800
        }

        .page-container {
            width: 100%;
            min-height: 0;
            flex: 1;
            padding: 24px;
            background: var(--background)
        }

        .content-wrapper {
            width: 100%;
            max-width: 920px;
            margin: 0 auto
        }

        .info-box {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            margin-bottom: 16px;
            padding: 13px 15px;
            border: 1px solid #cfe1fb;
            border-radius: 10px;
            color: #4b74a3;
            background: #eef6ff;
            font-size: 9px;
            line-height: 1.55
        }

        .info-box i {
            flex-shrink: 0;
            color: var(--primary);
            font-size: 14px
        }

        .info-box strong {
            color: #2e5d94
        }

        .alert {
            margin-bottom: 16px;
            padding: 12px 14px;
            border-radius: 10px;
            font-size: 9px;
            line-height: 1.55
        }

        .alert.success {
            border: 1px solid #bfe4cf;
            color: #28734b;
            background: #effaf4
        }

        .alert.error {
            border: 1px solid #f1c5cb;
            color: #b73545;
            background: #fff4f5
        }

        .main-card {
            border: 1px solid var(--border);
            border-radius: 14px;
            background: var(--white);
            box-shadow: 0 8px 25px rgba(38, 68, 103, .07);
            overflow: hidden
        }

        .card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            padding: 18px 21px;
            border-bottom: 1px solid #e6ecf2
        }

        .card-header-left {
            display: flex;
            align-items: center;
            gap: 11px
        }

        .card-header-icon {
            width: 34px;
            height: 34px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 9px;
            color: var(--primary);
            background: var(--primary-soft)
        }

        .card-title {
            font-size: 13px;
            font-weight: 800
        }

        .card-description {
            margin-top: 3px;
            color: var(--text-muted);
            font-size: 8px
        }

        .card-badge {
            padding: 5px 9px;
            border-radius: 999px;
            color: #56728f;
            background: #f3f6f9;
            font-size: 7.5px;
            font-weight: 700
        }

        .main-form {
            padding: 22px
        }

        .section-title {
            display: flex;
            align-items: center;
            gap: 9px;
            margin-bottom: 14px;
            padding-left: 8px;
            border-left: 3px solid var(--primary)
        }

        .section-title h2 {
            font-size: 11px;
            font-weight: 800
        }

        .section-title span {
            color: var(--text-muted);
            font-size: 8px
        }

        .section-divider {
            height: 1px;
            margin: 22px 0;
            background: #e7edf3
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 15px
        }

        .form-group {
            min-width: 0
        }

        .form-group.full {
            grid-column: 1/-1
        }

        .form-label {
            display: block;
            margin-bottom: 7px;
            color: #324d6e;
            font-size: 9px;
            font-weight: 750
        }

        .required {
            color: var(--danger)
        }

        .form-control {
            width: 100%;
            height: 43px;
            padding: 0 13px;
            border: 1px solid #d3dde8;
            border-radius: 9px;
            outline: none;
            color: var(--text-primary);
            background: #fff;
            font-size: 10px;
            transition: .18s ease
        }

        .form-control:hover {
            border-color: #adc0d4
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(7, 89, 183, .08)
        }

        .form-control:disabled {
            color: #9aaabc;
            background: #f5f7f9;
            cursor: not-allowed
        }

        .form-control.is-invalid {
            border-color: var(--danger);
            box-shadow: 0 0 0 3px rgba(223, 64, 82, .06)
        }

        .field-help {
            margin-top: 6px;
            color: #8da0b4;
            font-size: 8px;
            line-height: 1.5
        }

        .field-error {
            display: none;
            margin-top: 6px;
            color: var(--danger);
            font-size: 8px;
            line-height: 1.45
        }

        .field-error.show {
            display: block
        }

        .status-list {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 20px
        }

        .status-chip {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 5px 10px;
            border: 1px solid #dce5ef;
            border-radius: 20px;
            color: #98a8ba;
            background: #f7f9fb;
            font-size: 8px;
            font-weight: 650
        }

        .status-chip::before {
            content: "";
            width: 5px;
            height: 5px;
            border-radius: 50%;
            background: #c7d0db
        }

        .status-chip.complete {
            color: #268050;
            border-color: #bee6d0;
            background: #f1fbf5
        }

        .status-chip.complete::before {
            background: var(--success)
        }

        .form-actions {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            margin-top: 22px;
            padding-top: 19px;
            border-top: 1px solid #e5ebf2
        }

        .action-message {
            color: #8a9caf;
            font-size: 8.5px;
            line-height: 1.45
        }

        .save-button {
            min-width: 180px;
            height: 42px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 0 20px;
            border: none;
            border-radius: 10px;
            color: #fff;
            background: var(--primary);
            box-shadow: 0 7px 16px rgba(7, 89, 183, .18);
            font-size: 9px;
            font-weight: 750;
            transition: .18s ease
        }

        .save-button:hover:not(:disabled) {
            background: var(--primary-dark);
            transform: translateY(-1px)
        }

        .save-button:disabled {
            color: #9cacbd;
            background: #e3eaf2;
            box-shadow: none;
            cursor: not-allowed;
            transform: none
        }

        .footer {
            margin-top: auto;
            border-top: 1px solid var(--border);
            background: #eef3f8
        }

        .footer-container {
            width: 100%;
            max-width: 920px;
            min-height: 68px;
            display: flex;
            align-items: center;
            margin: 0 auto;
            padding: 15px 26px
        }

        .footer-brand {
            color: #75889b;
            font-size: 7.5px;
            line-height: 1.6
        }

        .footer-brand strong {
            display: block;
            color: #405974;
            font-size: 8.5px
        }

        @media(max-width:760px) {
            .form-grid {
                grid-template-columns: 1fr
            }

            .form-group.full {
                grid-column: auto
            }

            .form-actions {
                align-items: stretch;
                flex-direction: column
            }

            .save-button {
                width: 100%
            }
        }

        @media(max-width:680px) {
            .dashboard-header {
                padding: 0 14px
            }

            .sidebar-toggle {
                display: flex
            }

            .header-user-text {
                display: none
            }

            .page-container {
                padding: 15px
            }

            .main-form,
            .card-header {
                padding: 17px
            }

            .footer-container {
                padding: 15px 14px
            }
        }
    </style>
</head>

<body>
    @php
        $userName = data_get($user ?? null, 'name', session('user_name', 'Pengguna Sistem'));
        $jabatanName = data_get($user ?? null, 'jabatan.jabatan_name', session('jabatan_name', 'Pengguna Sistem'));
        $initials = collect(explode(' ', $userName))
            ->filter()
            ->take(2)
            ->map(fn($word) => strtoupper(substr($word, 0, 1)))
            ->implode('');
    @endphp
    <div class="app-shell">
        @include('partials.sidebar', [
            'activeMenu' => 'reference-satker',
            'sidebarUserName' => $userName,
            // 'sidebarUserRole' => $jabatanName,
            'sidebarInitials' => $initials,
        ])
        <div class="app-main">
            <header class="dashboard-header">
                <div class="header-left">
                    <button type="button" class="sidebar-toggle" id="sidebarToggle" aria-label="Buka menu navigasi"><i
                            class="bi bi-list"></i></button>
                    <div class="header-copy">
                        <div class="header-eyebrow">SISTEM INFORMASI PENELITIAN RKA-K/L</div>
                        <div class="header-title">Tambah Data Referensi</div>
                    </div>
                </div>
                <div class="header-user">
                    <div class="header-user-text">Biro Perencanaan<strong>{{ $userName }}</strong></div>
                    <div class="header-avatar">{{ $initials ?: 'US' }}</div>
                </div>
            </header>
            <main class="page-container">
                <div class="content-wrapper">
                    {{-- <div class="info-box"><i class="bi bi-info-circle-fill"></i>
                        <div>Pilih <strong>Unit Eselon I</strong> lalu <strong>Unit Eselon II</strong>. Sistem hanya
                            menyimpan kode Unit Eselon II pada tabel Satker karena hubungan ke Unit Eselon I sudah
                            diperoleh melalui Unit Eselon II.</div>
                    </div> --}}
                    @if (session('success'))
                        <div class="alert success">{{ session('success') }}</div>
                    @endif
                    @if (session('error'))
                        <div class="alert error">{{ session('error') }}</div>
                    @endif
                    <section class="main-card">
                        <div class="card-header">
                            <div class="card-header-left">
                                <div class="card-header-icon"><i class="bi bi-database-add"></i></div>
                                <div>
                                    <h1 class="card-title">Tambah Referensi Satker</h1>
                                    <p class="card-description">Tambahkan Satker baru sesuai hierarki Unit Eselon I dan
                                        Unit Eselon II.</p>
                                </div>
                            </div>
                            <span class="card-badge">REFERENSI ORGANISASI</span>
                        </div>
                        <form id="referenceForm" class="main-form"
                            action="{{ route('referensi.organisasi.satker.store') }}" method="POST" novalidate>
                            @csrf
                            <div class="section-title">
                                <div>
                                    <h2>Referensi Induk</h2><span>Pilih Unit Eselon I lalu Unit Eselon II yang menjadi
                                        induk Satker baru.</span>
                                </div>
                            </div>
                            <div class="form-grid">
                                <div class="form-group"><label for="unit1" class="form-label">Unit Eselon I <span
                                            class="required">*</span></label><select id="unit1"
                                        name="kode_unit_eselon1" class="form-control">
                                        <option value="">-- Pilih Unit Eselon I --</option>
                                        @foreach ($unitEselon1 as $item)
                                            <option value="{{ $item->kode_unit_eselon1 }}"
                                                {{ old('kode_unit_eselon1') == $item->kode_unit_eselon1 ? 'selected' : '' }}>
                                                [{{ $item->kode_unit_eselon1 }}] {{ $item->nama_unit_eselon1 }}</option>
                                        @endforeach
                                    </select>
                                    <div class="field-error" id="unit1Error"></div>
                                    @error('kode_unit_eselon1')
                                        <div class="field-error show">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group"><label for="unit2" class="form-label">Unit Eselon II <span
                                            class="required">*</span></label><select id="unit2"
                                        name="kode_unit_eselon2" class="form-control" disabled>
                                        <option value="">-- Pilih Unit Eselon I terlebih dahulu --</option>
                                    </select>
                                    <div class="field-error" id="unit2Error"></div>
                                    @error('kode_unit_eselon2')
                                        <div class="field-error show">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="section-divider"></div>
                            <div class="section-title">
                                <div>
                                    <h2>Data Referensi Baru</h2><span>Masukkan kode dan nama Satker baru.</span>
                                </div>
                            </div>
                            <div class="form-grid">
                                <div class="form-group"><label for="kodeSatker" class="form-label">Kode Satker <span
                                            class="required">*</span></label><input type="text" id="kodeSatker"
                                        name="kode_satker" class="form-control" maxlength="50" autocomplete="off"
                                        value="{{ old('kode_satker') }}" placeholder="Contoh: 412512">
                                    <div class="field-help">Kode Satker disimpan sebagai teks agar format kode tetap
                                        utuh.</div>
                                    <div class="field-error" id="kodeSatkerError"></div>
                                    @error('kode_satker')
                                        <div class="field-error show">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group"><label for="namaSatker" class="form-label">Nama Satker <span
                                            class="required">*</span></label><input type="text" id="namaSatker"
                                        name="nama_satker" class="form-control" maxlength="255" autocomplete="off"
                                        value="{{ old('nama_satker') }}" placeholder="Masukkan nama Satker Baru">
                                    <div class="field-help">Gunakan nomenklatur resmi satuan kerja.</div>
                                    <div class="field-error" id="namaSatkerError"></div>
                                    @error('nama_satker')
                                        <div class="field-error show">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="status-list"><span class="status-chip" id="unit1Status">Unit Eselon
                                    I</span><span class="status-chip" id="unit2Status">Unit Eselon II</span><span
                                    class="status-chip" id="kodeStatus">Kode Satker</span><span class="status-chip"
                                    id="namaStatus">Nama Satker</span></div>
                            <div class="form-actions">
                                <div class="action-message" id="actionMessage">Pilih hierarki organisasi dan lengkapi
                                    data Satker.</div><button type="submit" class="save-button" id="saveButton"
                                    disabled><i class="bi bi-floppy"></i>Simpan Referensi</button>
                            </div>
                        </form>
                    </section>
                </div>
            </main>
            <footer class="footer">
                <div class="footer-container">
                    <div class="footer-brand"><strong>Kementerian Perdagangan Republik Indonesia</strong>© 2026 Biro
                        Perencanaan. Seluruh Hak Cipta Dilindungi.</div>
                </div>
            </footer>
        </div>
    </div>
    @include('partials.document-processing-modal')
    <script src="{{ asset('js/document-processing-modal.js') }}"></script>
    <script>
        function modalErrorMessage(payload, fallback) {
            if (payload && payload.errors) {
                const messages = Object.values(payload.errors).flat().filter(Boolean);
                if (messages.length) return messages.join('\n');
            }
            return payload?.message || fallback;
        }

        async function readResponse(response) {
            const text = await response.text();
            if (!text) return {};
            try {
                return JSON.parse(text);
            } catch (_) {
                return {
                    message: response.ok ? text : 'Server mengembalikan respons yang tidak dapat dibaca.'
                };
            }
        }

        function setFieldError(input, errorEl, message = '') {
            const show = !!message;
            input?.classList.toggle('is-invalid', show);
            if (errorEl) {
                errorEl.textContent = message;
                errorEl.classList.toggle('show', show);
            }
        }

        const allUnit2 = @json($unitEselon2 ?? []),
            oldUnit2 = @json(old('kode_unit_eselon2'));
        const form = document.getElementById('referenceForm'),
            unit1 = document.getElementById('unit1'),
            unit2 = document.getElementById('unit2'),
            kode = document.getElementById('kodeSatker'),
            nama = document.getElementById('namaSatker');
        const unit1Error = document.getElementById('unit1Error'),
            unit2Error = document.getElementById('unit2Error'),
            kodeError = document.getElementById('kodeSatkerError'),
            namaError = document.getElementById('namaSatkerError');
        const unit1Status = document.getElementById('unit1Status'),
            unit2Status = document.getElementById('unit2Status'),
            kodeStatus = document.getElementById('kodeStatus'),
            namaStatus = document.getElementById('namaStatus'),
            saveButton = document.getElementById('saveButton'),
            actionMessage = document.getElementById('actionMessage');
        const asString = v => v === null || v === undefined ? '' : String(v);
        const sameCode = (a, b) => asString(a) === asString(b);

        function populateUnit2(selected = '') {
            const parent = asString(unit1.value);
            unit2.innerHTML = '';
            if (!parent) {
                unit2.innerHTML = '<option value="">-- Pilih Unit Eselon I terlebih dahulu --</option>';
                unit2.disabled = true;
                return
            }
            const filtered = allUnit2.filter(item => sameCode(item.kode_unit_eselon1, parent));
            unit2.innerHTML = '<option value="">-- Pilih Unit Eselon II --</option>';
            filtered.forEach(item => {
                const o = document.createElement('option');
                o.value = asString(item.kode_unit_eselon2);
                o.textContent = `[${o.value}] ${item.nama_unit_eselon2 ?? ''}`;
                unit2.appendChild(o)
            });
            unit2.disabled = filtered.length === 0;
            if (selected && filtered.some(item => sameCode(item.kode_unit_eselon2, selected))) unit2.value = asString(
                selected)
        }

        function updateState(showErrors = false) {
            const a = unit1.value !== '',
                b = unit2.value !== '',
                k = kode.value.trim() !== '',
                n = nama.value.trim() !== '';
            unit1Status.classList.toggle('complete', a);
            unit2Status.classList.toggle('complete', b);
            kodeStatus.classList.toggle('complete', k);
            namaStatus.classList.toggle('complete', n);
            if (showErrors) {
                setFieldError(unit1, unit1Error, a ? '' : 'Unit Eselon I wajib dipilih.');
                setFieldError(unit2, unit2Error, b ? '' : 'Unit Eselon II wajib dipilih.');
                setFieldError(kode, kodeError, k ? '' : 'Kode Satker wajib diisi.');
                setFieldError(nama, namaError, n ? '' : 'Nama Satker wajib diisi.')
            } else {
                if (a) setFieldError(unit1, unit1Error, '');
                if (b) setFieldError(unit2, unit2Error, '');
                if (k) setFieldError(kode, kodeError, '');
                if (n) setFieldError(nama, namaError, '')
            }
            const valid = a && b && k && n;
            saveButton.disabled = !valid;
            actionMessage.textContent = valid ? 'Data siap disimpan.' :
                'Pilih hierarki organisasi dan lengkapi data Satker.';
            return valid
        }
        unit1.addEventListener('change', () => {
            populateUnit2('');
            setFieldError(unit2, unit2Error, '');
            updateState(false)
        });
        unit2.addEventListener('change', () => updateState(false));
        kode.addEventListener('input', () => updateState(false));
        nama.addEventListener('input', () => updateState(false));
        form.addEventListener('submit', async event => {
            event.preventDefault();
            if (!updateState(true)) return;
            saveButton.disabled = true;
            DocumentProcessingModal.showLoading({
                title: 'Menyimpan Data Referensi',
                message: 'Data Satker sedang divalidasi dan disimpan ke database.'
            });
            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    body: new FormData(form),
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                const payload = await readResponse(response);
                if (!response.ok || payload.success === false) {
                    if (payload.errors?.kode_unit_eselon1) setFieldError(unit1, unit1Error, payload.errors
                        .kode_unit_eselon1[0]);
                    if (payload.errors?.kode_unit_eselon2) setFieldError(unit2, unit2Error, payload.errors
                        .kode_unit_eselon2[0]);
                    if (payload.errors?.kode_satker) setFieldError(kode, kodeError, payload.errors.kode_satker[
                        0]);
                    if (payload.errors?.nama_satker) setFieldError(nama, namaError, payload.errors.nama_satker[
                        0]);
                    DocumentProcessingModal.showError({
                        title: payload.title || 'Data Referensi Gagal Disimpan',
                        message: modalErrorMessage(payload,
                            'Terjadi kesalahan saat menyimpan data referensi.'),
                        buttonText: 'TUTUP'
                    });
                    return
                }
                DocumentProcessingModal.showSuccess({
                    title: payload.title || 'Data Referensi Berhasil Ditambahkan',
                    message: payload.message || 'Satker berhasil ditambahkan.',
                    buttonText: 'OKE',
                    onClose: () => {
                        kode.value = '';
                        nama.value = '';
                        setFieldError(kode, kodeError, '');
                        setFieldError(nama, namaError, '');
                        updateState(false);
                        kode.focus()
                    }
                })
            } catch (error) {
                DocumentProcessingModal.showError({
                    title: 'Data Referensi Gagal Disimpan',
                    message: 'Tidak dapat terhubung ke server. Silakan coba kembali.',
                    details: error?.message || '',
                    buttonText: 'TUTUP'
                })
            } finally {
                saveButton.disabled = false;
                updateState(false)
            }
        });
        populateUnit2(oldUnit2);
        updateState(false);
    </script>
</body>

</html>
