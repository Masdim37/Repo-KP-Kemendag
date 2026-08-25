<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Master Data | Penelitian RKA-K/L</title>
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
            --warning: #db9b17;
            --text-primary: #18365b;
            --text-secondary: #607995;
            --text-muted: #91a4b9;
            --background: #f3f6fa;
            --border: #dbe5ee;
            --white: #ffffff;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        html, body { min-height: 100%; }
        body {
            min-height: 100vh;
            overflow-x: hidden;
            color: var(--text-primary);
            background: var(--background);
            font-family: Inter, "Segoe UI", Arial, sans-serif;
        }
        button, input, select { font: inherit; }
        button, select { cursor: pointer; }
        .app-shell { min-height: 100vh; }
        .app-main { min-height: 100vh; display: flex; flex-direction: column; }

        /* HEADER */
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
            background: rgba(255,255,255,.96);
            box-shadow: 0 4px 18px rgba(33,67,103,.05);
            backdrop-filter: blur(12px);
        }
        .header-left { min-width: 0; display: flex; align-items: center; gap: 13px; }
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
            font-size: 18px;
        }
        .header-copy { min-width: 0; }
        .header-eyebrow {
            overflow: hidden;
            color: #879bb1;
            font-size: 7.5px;
            font-weight: 700;
            letter-spacing: .8px;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .header-title { margin-top: 3px; color: var(--text-primary); font-size: 14px; font-weight: 800; }
        .header-user { flex-shrink: 0; display: flex; align-items: center; gap: 10px; }
        .header-user-text { text-align: right; color: var(--text-secondary); font-size: 8.5px; line-height: 1.4; }
        .header-user-text strong {
            display: block;
            max-width: 200px;
            overflow: hidden;
            color: var(--text-primary);
            font-size: 10px;
            text-overflow: ellipsis;
            white-space: nowrap;
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
            box-shadow: 0 5px 12px rgba(31,91,148,.16);
            font-size: 10px;
            font-weight: 800;
        }

        /* PAGE */
        .page-container { width: 100%; min-height: 0; flex: 1; padding: 24px; background: var(--background); }
        .content-wrapper { width: 100%; max-width: 1080px; margin: 0 auto; }
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
            font-size: 8.5px;
            line-height: 1.55;
        }
        .info-box i { flex-shrink: 0; color: var(--primary); font-size: 14px; }
        .info-box strong { display: block; margin-bottom: 2px; color: #2e5d94; font-size: 9.5px; }

        .main-card {
            border: 1px solid var(--border);
            border-radius: 14px;
            background: var(--white);
            box-shadow: 0 8px 25px rgba(38,68,103,.07);
            overflow: hidden;
        }
        .card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            padding: 18px 21px;
            border-bottom: 1px solid #e6ecf2;
        }
        .card-header-left { display: flex; align-items: center; gap: 11px; }
        .card-header-icon {
            width: 34px;
            height: 34px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 9px;
            color: var(--primary);
            background: var(--primary-soft);
            font-size: 14px;
        }
        .card-title { font-size: 13px; font-weight: 800; }
        .card-description { margin-top: 3px; color: var(--text-muted); font-size: 8px; }
        .card-date { color: #93a2b4; font-size: 8px; }
        .main-form { padding: 20px; }

        .section-title {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            margin-bottom: 13px;
            padding-left: 8px;
            border-left: 3px solid var(--primary);
        }
        .section-title h2 { font-size: 11px; font-weight: 800; }
        .section-note {
            padding: 4px 8px;
            border-radius: 12px;
            color: #7c8ea2;
            background: #f3f6f9;
            font-size: 7px;
        }

        /* DOCUMENT CARDS */
        .document-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 16px; }
        .document-box {
            min-width: 0;
            padding: 16px;
            border: 1px solid #dce5ee;
            border-radius: 14px;
            background: linear-gradient(180deg, #fff 0%, #f9fbfd 100%);
            box-shadow: 0 6px 18px rgba(36,67,99,.045);
        }
        .document-title-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            margin-bottom: 11px;
        }
        .document-title { display: flex; align-items: center; gap: 7px; color: #334e6d; font-size: 10px; font-weight: 800; }
        .document-title i { color: var(--primary); font-size: 13px; }
        .format-badge {
            flex-shrink: 0;
            padding: 4px 8px;
            border: 1px solid #d7e5f7;
            border-radius: 12px;
            color: #53779f;
            background: #f2f7fd;
            font-size: 7px;
            font-weight: 800;
        }

        .upload-zone {
            position: relative;
            min-height: 155px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            border: 1.5px dashed #91b8eb;
            border-radius: 11px;
            background: #f8fbff;
            text-align: center;
            cursor: pointer;
            transition: .2s ease;
        }
        .upload-zone:hover, .upload-zone.dragover {
            border-color: var(--primary);
            background: #f0f6ff;
            box-shadow: 0 0 0 4px rgba(7,89,183,.05);
        }
        .upload-zone.has-file { border-color: #87c8a6; background: var(--success-soft); }
        .upload-zone.has-error { border-color: #e9959f; background: var(--danger-soft); }
        .file-input { position: absolute; width: 1px; height: 1px; opacity: 0; pointer-events: none; }
        .upload-icon {
            width: 43px;
            height: 43px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
            border-radius: 11px;
            color: var(--primary);
            background: #e5f0ff;
            font-size: 19px;
        }
        .upload-zone.has-file .upload-icon { color: var(--success); background: #dcf4e7; }
        .upload-title { font-size: 10px; font-weight: 800; }
        .upload-description { margin-top: 4px; color: var(--text-muted); font-size: 7.5px; line-height: 1.5; }
        .choose-file-button {
            min-width: 105px;
            height: 33px;
            margin-top: 10px;
            padding: 0 14px;
            border: 0;
            border-radius: 8px;
            color: #fff;
            background: var(--primary);
            box-shadow: 0 4px 10px rgba(7,89,183,.16);
            font-size: 8px;
            font-weight: 700;
        }
        .choose-file-button:hover { background: var(--primary-dark); }
        .selected-file {
            display: none;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            margin-top: 11px;
            padding: 9px 10px;
            border: 1px solid #bfe4cf;
            border-radius: 8px;
            color: #31754f;
            background: #f1fbf5;
            text-align: left;
        }
        .selected-file.show { display: flex; }
        .selected-file-info { min-width: 0; display: flex; align-items: center; gap: 7px; }
        .selected-file-info i { flex-shrink: 0; color: var(--success); font-size: 14px; }
        .selected-file-copy { min-width: 0; }
        .selected-file-name { overflow: hidden; font-size: 8px; font-weight: 700; text-overflow: ellipsis; white-space: nowrap; }
        .selected-file-size { margin-top: 2px; color: #779186; font-size: 7px; }
        .remove-file-button {
            width: 25px;
            height: 25px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            border: 0;
            border-radius: 6px;
            color: var(--danger);
            background: #fff;
        }
        .file-note { margin-top: 8px; color: #8fa1b7; font-size: 7.5px; line-height: 1.5; }
        .file-note i { margin-right: 3px; color: var(--primary); }
        .field-error { display: none; margin-top: 5px; color: var(--danger); font-size: 7.5px; line-height: 1.45; }
        .field-error.show { display: block; }

        /* FORM */
        .field-grid { display: grid; grid-template-columns: minmax(0, 1.3fr) minmax(0, .7fr); gap: 10px; margin-top: 12px; }
        .form-group { min-width: 0; }
        .form-label { display: block; margin-bottom: 6px; color: #344e6c; font-size: 8px; font-weight: 700; }
        .required { color: var(--danger); }
        .conditional-required { display: none; }
        .form-control {
            width: 100%;
            height: 39px;
            padding: 0 11px;
            border: 1px solid #d4dee8;
            border-radius: 9px;
            outline: none;
            color: var(--text-primary);
            background: #fff;
            font-size: 8.5px;
            transition: .2s ease;
        }
        .form-control:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(7,89,183,.07); }
        .form-control:disabled { color: #91a0b1; background: #f1f4f7; cursor: default; }
        .form-control.is-valid { border-color: #a9d8bd; }
        .form-control.is-invalid { border-color: #ef9aa4; box-shadow: 0 0 0 3px rgba(223,64,82,.04); }
        select.form-control {
            padding-right: 32px;
            appearance: none;
            background-image:
                linear-gradient(45deg, transparent 50%, #8193a7 50%),
                linear-gradient(135deg, #8193a7 50%, transparent 50%);
            background-position: calc(100% - 15px) 17px, calc(100% - 10px) 17px;
            background-size: 5px 5px, 5px 5px;
            background-repeat: no-repeat;
        }

        /* STATUS / ACTION */
        .status-area { margin-top: 18px; padding-top: 15px; border-top: 1px solid #e6ecf2; }
        .status-heading { margin-bottom: 8px; color: #60758d; font-size: 7.5px; font-weight: 800; }
        .status-list { display: flex; flex-wrap: wrap; gap: 6px; }
        .status-chip {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 5px 8px;
            border: 1px solid #dce4ec;
            border-radius: 20px;
            color: #98a8b8;
            background: #f7f9fb;
            font-size: 7px;
            font-weight: 700;
        }
        .status-chip::before { content: ""; width: 5px; height: 5px; border-radius: 50%; background: #c7d0d9; }
        .status-chip.complete { color: #257749; border-color: #bee5cf; background: #f0faf4; }
        .status-chip.complete::before { background: var(--success); }
        .action-row { display: flex; align-items: center; justify-content: space-between; gap: 15px; margin-top: 17px; }
        .action-hint { color: #8b9bae; font-size: 7.5px; line-height: 1.5; }
        .save-button {
            min-width: 190px;
            height: 41px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            padding: 0 18px;
            border: 0;
            border-radius: 9px;
            color: #fff;
            background: var(--primary);
            box-shadow: 0 5px 12px rgba(7,89,183,.18);
            font-size: 8.5px;
            font-weight: 700;
        }
        .save-button:hover:not(:disabled) { background: var(--primary-dark); }
        .save-button:disabled { color: #9baabc; background: #e1e7ee; box-shadow: none; cursor: not-allowed; }

        /* FALLBACK ALERTS */
        .server-alert {
            margin-bottom: 13px;
            padding: 10px 12px;
            border-radius: 9px;
            font-size: 8px;
            line-height: 1.5;
        }
        .server-alert.success { color: #276947; border: 1px solid #b8e2ca; background: #f0fbf5; }
        .server-alert.error { color: #a33845; border: 1px solid #efc2c8; background: #fff5f6; }

        /* FOOTER */
        .footer { margin-top: auto; border-top: 1px solid var(--border); background: #eef3f8; }
        .footer-container { width: 100%; max-width: 1080px; min-height: 68px; display: flex; align-items: center; margin: 0 auto; padding: 15px 24px; }
        .footer-brand { color: #75889b; font-size: 7.5px; line-height: 1.6; }
        .footer-brand strong { display: block; color: #405974; font-size: 8.5px; }

        @media (max-width: 820px) {
            .document-grid { grid-template-columns: 1fr; }
        }
        @media (max-width: 680px) {
            .dashboard-header { padding: 0 14px; }
            .sidebar-toggle { display: flex; }
            .header-user-text { display: none; }
            .page-container { padding: 15px; }
            .main-form, .card-header { padding: 16px; }
            .field-grid { grid-template-columns: 1fr; }
            .action-row { align-items: stretch; flex-direction: column; }
            .save-button { width: 100%; }
            .footer-container { padding: 15px 14px; }
        }
    </style>
</head>
<body>
    @php
        $userName = data_get($user ?? null, 'name', session('user_name', 'User'));
        $jabatanName = data_get($user ?? null, 'jabatan.jabatan_name', session('jabatan_name', 'Pengguna'));
        $initials = collect(explode(' ', $userName))
            ->filter()
            ->take(2)
            ->map(fn($word) => strtoupper(substr($word, 0, 1)))
            ->implode('');
    @endphp

    <div class="app-shell">
        @include('partials.sidebar', [
            'activeMenu' => 'upload-master-data',
            'sidebarUserName' => $userName,
            // 'sidebarUserRole' => $jabatanName,
            'sidebarInitials' => $initials,
        ])

        <div class="app-main">
            <header class="dashboard-header">
                <div class="header-left">
                    <button type="button" class="sidebar-toggle" id="sidebarToggle" aria-label="Buka menu navigasi" aria-expanded="false">
                        <i class="bi bi-list"></i>
                    </button>
                    <div class="header-copy">
                        <div class="header-eyebrow">SISTEM INFORMASI PENELITIAN RKA-K/L</div>
                        <div class="header-title">Upload Master Data</div>
                    </div>
                </div>

                <div class="header-user">
                    <div class="header-user-text">
                        Biro Perencanaan
                        <strong>{{ $userName }}</strong>
                    </div>
                    <div class="header-avatar">{{ $initials ?: 'US' }}</div>
                </div>
            </header>

            <main class="page-container">
                <div class="content-wrapper">
                    <div class="info-box">
                        <i class="bi bi-info-circle-fill"></i>
                        <div>
                            <strong>Master data diproses langsung dari Excel.</strong>
                            RENJA dan RKBMN menggunakan parser manual, tanpa Gemini AI. Pilih Tahun Anggaran untuk masing-masing dokumen karena kedua file dapat berasal dari tahun yang berbeda.
                        </div>
                    </div>

                    @if (session('success'))
                        <div class="server-alert success"><i class="bi bi-check-circle-fill"></i> {{ session('success') }}</div>
                    @endif
                    @if (session('error'))
                        <div class="server-alert error"><i class="bi bi-x-circle-fill"></i> {{ session('error') }}</div>
                    @endif

                    <section class="main-card">
                        <div class="card-header">
                            <div class="card-header-left">
                                <div class="card-header-icon"><i class="bi bi-database-up"></i></div>
                                <div>
                                    <h1 class="card-title">Upload Master Data</h1>
                                    <p class="card-description">Unggah RENJA dalam format Excel/CSV dan RKBMN dalam format Excel.</p>
                                </div>
                            </div>
                            <div class="card-date">{{ now()->translatedFormat('d F Y') }}</div>
                        </div>

                        <form id="masterDataForm" class="main-form" action="{{ route('upload.masterdata.store') }}" method="POST" enctype="multipart/form-data" novalidate>
                            @csrf

                            <div class="section-title">
                                <h2>Dokumen Master Data</h2>
                                <span class="section-note">Minimal 1 dokumen</span>
                            </div>

                            <div class="document-grid">
                                {{-- RENJA --}}
                                <section class="document-box" id="renjaBox">
                                    <div class="document-title-row">
                                        <div class="document-title"><i class="bi bi-file-earmark-spreadsheet-fill"></i> RENJA</div>
                                        <span class="format-badge">XLSX / XLS / CSV</span>
                                    </div>

                                    <div class="upload-zone" id="renjaDropzone" tabindex="0" role="button" aria-label="Pilih file RENJA">
                                        <input type="file" id="renjaFile" name="renja_file" class="file-input" accept=".xlsx,.xls,.csv">
                                        <div>
                                            <div class="upload-icon"><i class="bi bi-file-earmark-excel-fill"></i></div>
                                            <div class="upload-title">Upload File RENJA</div>
                                            <div class="upload-description">Seret file ke area ini atau pilih dari perangkat.</div>
                                            <button type="button" class="choose-file-button" data-file-button="renja">Pilih Excel</button>

                                            <div class="selected-file" id="renjaSelectedFile">
                                                <div class="selected-file-info">
                                                    <i class="bi bi-file-earmark-check-fill"></i>
                                                    <div class="selected-file-copy">
                                                        <div class="selected-file-name" id="renjaSelectedName"></div>
                                                        <div class="selected-file-size" id="renjaSelectedSize"></div>
                                                    </div>
                                                </div>
                                                <button type="button" class="remove-file-button" data-remove-file="renja" aria-label="Hapus file RENJA"><i class="bi bi-x-lg"></i></button>
                                            </div>
                                        </div>
                                    </div>

                                    <p class="file-note"><i class="bi bi-shield-check"></i> Hanya file Excel (.xlsx atau .xls), maksimal 50 MB.</p>
                                    <div class="field-error" id="renjaFileError">File RENJA harus berformat .xlsx, .xls, atau .csv.</div>

                                    <div class="field-grid">
                                        <div class="form-group">
                                            <label class="form-label" for="renjaName">Nama Dokumen RENJA <span class="required conditional-required" id="renjaNameRequired">*</span></label>
                                            <input type="text" id="renjaName" name="renja_name" class="form-control" maxlength="255" autocomplete="off" placeholder="Contoh: RENJA Kementerian Perdagangan 2027" value="{{ old('renja_name') }}">
                                            <div class="field-error" id="renjaNameError">Nama dokumen RENJA wajib diisi.</div>
                                        </div>

                                        <div class="form-group">
                                            <label class="form-label" for="renjaYear">Tahun Anggaran <span class="required conditional-required" id="renjaYearRequired">*</span></label>
                                            <select id="renjaYear" name="renja_tahun_anggaran" class="form-control" disabled>
                                                <option value="">-- Pilih Tahun --</option>
                                                @foreach ($tahunAnggaran as $tahun)
                                                    <option value="{{ $tahun }}" {{ (string) old('renja_tahun_anggaran') === (string) $tahun ? 'selected' : '' }}>{{ $tahun }}</option>
                                                @endforeach
                                            </select>
                                            <div class="field-error" id="renjaYearError">Tahun Anggaran RENJA wajib dipilih.</div>
                                        </div>
                                    </div>
                                </section>

                                {{-- RKBMN --}}
                                <section class="document-box" id="rkbmnBox">
                                    <div class="document-title-row">
                                        <div class="document-title"><i class="bi bi-buildings-fill"></i> RKBMN</div>
                                        <span class="format-badge">XLSX / XLS</span>
                                    </div>

                                    <div class="upload-zone" id="rkbmnDropzone" tabindex="0" role="button" aria-label="Pilih file RKBMN">
                                        <input type="file" id="rkbmnFile" name="rkbmn_file" class="file-input" accept=".xlsx,.xls">
                                        <div>
                                            <div class="upload-icon"><i class="bi bi-file-earmark-excel-fill"></i></div>
                                            <div class="upload-title">Upload File RKBMN</div>
                                            <div class="upload-description">File harus mempunyai sheet Pengadaan dan Pemeliharaan.</div>
                                            <button type="button" class="choose-file-button" data-file-button="rkbmn">Pilih Excel</button>

                                            <div class="selected-file" id="rkbmnSelectedFile">
                                                <div class="selected-file-info">
                                                    <i class="bi bi-file-earmark-check-fill"></i>
                                                    <div class="selected-file-copy">
                                                        <div class="selected-file-name" id="rkbmnSelectedName"></div>
                                                        <div class="selected-file-size" id="rkbmnSelectedSize"></div>
                                                    </div>
                                                </div>
                                                <button type="button" class="remove-file-button" data-remove-file="rkbmn" aria-label="Hapus file RKBMN"><i class="bi bi-x-lg"></i></button>
                                            </div>
                                        </div>
                                    </div>

                                    <p class="file-note"><i class="bi bi-shield-check"></i> Hanya file Excel (.xlsx atau .xls), maksimal 50 MB.</p>
                                    <div class="field-error" id="rkbmnFileError">File RKBMN harus berformat .xlsx atau .xls.</div>

                                    <div class="field-grid">
                                        <div class="form-group">
                                            <label class="form-label" for="rkbmnName">Nama Dokumen RKBMN <span class="required conditional-required" id="rkbmnNameRequired">*</span></label>
                                            <input type="text" id="rkbmnName" name="rkbmn_name" class="form-control" maxlength="255" autocomplete="off" placeholder="Contoh: RKBMN Kementerian Perdagangan 2027" value="{{ old('rkbmn_name') }}">
                                            <div class="field-error" id="rkbmnNameError">Nama dokumen RKBMN wajib diisi.</div>
                                        </div>

                                        <div class="form-group">
                                            <label class="form-label" for="rkbmnYear">Tahun Anggaran <span class="required conditional-required" id="rkbmnYearRequired">*</span></label>
                                            <select id="rkbmnYear" name="rkbmn_tahun_anggaran" class="form-control" disabled>
                                                <option value="">-- Pilih Tahun --</option>
                                                @foreach ($tahunAnggaran as $tahun)
                                                    <option value="{{ $tahun }}" {{ (string) old('rkbmn_tahun_anggaran') === (string) $tahun ? 'selected' : '' }}>{{ $tahun }}</option>
                                                @endforeach
                                            </select>
                                            <div class="field-error" id="rkbmnYearError">Tahun Anggaran RKBMN wajib dipilih.</div>
                                        </div>
                                    </div>
                                </section>
                            </div>

                            <div class="status-area">
                                <div class="status-heading">KELENGKAPAN DOKUMEN</div>
                                <div class="status-list">
                                    <span class="status-chip" id="renjaFileStatus">File RENJA</span>
                                    <span class="status-chip" id="renjaNameStatus">Nama RENJA</span>
                                    <span class="status-chip" id="renjaYearStatus">Tahun RENJA</span>
                                    <span class="status-chip" id="rkbmnFileStatus">File RKBMN</span>
                                    <span class="status-chip" id="rkbmnNameStatus">Nama RKBMN</span>
                                    <span class="status-chip" id="rkbmnYearStatus">Tahun RKBMN</span>
                                </div>
                            </div>

                            <div class="action-row">
                                <div class="action-hint" id="actionHint">Pilih minimal satu dokumen, lalu lengkapi nama dan Tahun Anggarannya.</div>
                                <button type="submit" class="save-button" id="saveMasterButton" disabled>
                                    <i class="bi bi-floppy"></i>
                                    Simpan Master Data
                                </button>
                            </div>
                        </form>
                    </section>
                </div>
            </main>

            <footer class="footer">
                <div class="footer-container">
                    <div class="footer-brand">
                        <strong>Kementerian Perdagangan Republik Indonesia</strong>
                        © 2026 Biro Perencanaan. Seluruh Hak Cipta Dilindungi.
                    </div>
                </div>
            </footer>
        </div>
    </div>

    @include('partials.document-processing-modal')
    <script src="{{ asset('js/document-processing-modal.js') }}"></script>

    <script>
        (() => {
            'use strict';

            const form = document.getElementById('masterDataForm');
            const saveButton = document.getElementById('saveMasterButton');
            const actionHint = document.getElementById('actionHint');

            const uploadState = {
                renja: null,
                rkbmn: null,
            };

            const config = {
                renja: {
                    label: 'RENJA',
                    input: document.getElementById('renjaFile'),
                    dropzone: document.getElementById('renjaDropzone'),
                    selected: document.getElementById('renjaSelectedFile'),
                    selectedName: document.getElementById('renjaSelectedName'),
                    selectedSize: document.getElementById('renjaSelectedSize'),
                    fileError: document.getElementById('renjaFileError'),
                    name: document.getElementById('renjaName'),
                    nameError: document.getElementById('renjaNameError'),
                    nameRequired: document.getElementById('renjaNameRequired'),
                    year: document.getElementById('renjaYear'),
                    yearError: document.getElementById('renjaYearError'),
                    yearRequired: document.getElementById('renjaYearRequired'),
                    fileStatus: document.getElementById('renjaFileStatus'),
                    nameStatus: document.getElementById('renjaNameStatus'),
                    yearStatus: document.getElementById('renjaYearStatus'),
                },
                rkbmn: {
                    label: 'RKBMN',
                    input: document.getElementById('rkbmnFile'),
                    dropzone: document.getElementById('rkbmnDropzone'),
                    selected: document.getElementById('rkbmnSelectedFile'),
                    selectedName: document.getElementById('rkbmnSelectedName'),
                    selectedSize: document.getElementById('rkbmnSelectedSize'),
                    fileError: document.getElementById('rkbmnFileError'),
                    name: document.getElementById('rkbmnName'),
                    nameError: document.getElementById('rkbmnNameError'),
                    nameRequired: document.getElementById('rkbmnNameRequired'),
                    year: document.getElementById('rkbmnYear'),
                    yearError: document.getElementById('rkbmnYearError'),
                    yearRequired: document.getElementById('rkbmnYearRequired'),
                    fileStatus: document.getElementById('rkbmnFileStatus'),
                    nameStatus: document.getElementById('rkbmnNameStatus'),
                    yearStatus: document.getElementById('rkbmnYearStatus'),
                },
            };

            function isAllowedSpreadsheetFile(type, file) {
                if (!file) return false;

                const name = file.name.toLowerCase();

                if (type === 'renja') {
                    return name.endsWith('.xlsx')
                        || name.endsWith('.xls')
                        || name.endsWith('.csv');
                }

                // RKBMN harus tetap workbook karena membutuhkan sheet Pengadaan
                // dan Pemeliharaan.
                return name.endsWith('.xlsx') || name.endsWith('.xls');
            }

            function formatFileSize(bytes) {
                if (bytes < 1024) return `${bytes} Bytes`;
                if (bytes < 1024 * 1024) return `${(bytes / 1024).toFixed(1)} KB`;
                return `${(bytes / (1024 * 1024)).toFixed(1)} MB`;
            }

            function assignFileToInput(input, file) {
                try {
                    const transfer = new DataTransfer();
                    transfer.items.add(file);
                    input.files = transfer.files;
                } catch (error) {
                    console.warn('Browser tidak mendukung assignment file melalui DataTransfer.', error);
                }
            }

            function cleanDocumentName(fileName) {
                return fileName.replace(/\.(xlsx|xls|csv)$/i, '');
            }

            function setSelectedFile(type, file) {
                const item = config[type];
                item.dropzone.classList.remove('has-file', 'has-error');
                item.fileError.classList.remove('show');

                if (!isAllowedSpreadsheetFile(type, file)) {
                    uploadState[type] = null;
                    item.input.value = '';
                    item.selected.classList.remove('show');
                    item.selectedName.textContent = '';
                    item.selectedSize.textContent = '';
                    item.dropzone.classList.add('has-error');
                    item.fileError.classList.add('show');
                    item.year.disabled = true;
                    updateFormState();
                    return;
                }

                uploadState[type] = file;
                assignFileToInput(item.input, file);
                item.selectedName.textContent = file.name;

                const extension = file.name.split('.').pop().toUpperCase();
                item.selectedSize.textContent = `${formatFileSize(file.size)} · ${extension}`;
                item.selected.classList.add('show');
                item.dropzone.classList.add('has-file');
                item.year.disabled = false;

                if (!item.name.value.trim()) {
                    item.name.value = cleanDocumentName(file.name);
                }

                updateFormState();
            }

            function clearSelectedFile(type, clearName = false) {
                const item = config[type];
                uploadState[type] = null;
                item.input.value = '';
                item.selectedName.textContent = '';
                item.selectedSize.textContent = '';
                item.selected.classList.remove('show');
                item.dropzone.classList.remove('has-file', 'has-error');
                item.fileError.classList.remove('show');
                item.year.disabled = true;

                if (clearName) {
                    item.name.value = '';
                }

                updateFormState();
            }

            function setStatus(element, complete) {
                element.classList.toggle('complete', complete);
            }

            function updateFormState(showErrors = false) {
                let atLeastOneFile = false;
                let allSelectedGroupsValid = true;
                const selectedLabels = [];

                Object.entries(config).forEach(([type, item]) => {
                    const selected = uploadState[type] !== null;
                    const nameValid = item.name.value.trim() !== '';
                    const yearValid = item.year.value !== '';

                    if (selected) {
                        atLeastOneFile = true;
                        selectedLabels.push(item.label);
                    }

                    item.nameRequired.style.display = selected ? 'inline' : 'none';
                    item.yearRequired.style.display = selected ? 'inline' : 'none';
                    item.year.disabled = !selected;

                    const groupValid = !selected || (nameValid && yearValid);
                    allSelectedGroupsValid = allSelectedGroupsValid && groupValid;

                    item.name.classList.toggle('is-valid', selected && nameValid);
                    item.name.classList.toggle('is-invalid', showErrors && selected && !nameValid);
                    item.nameError.classList.toggle('show', showErrors && selected && !nameValid);

                    item.year.classList.toggle('is-valid', selected && yearValid);
                    item.year.classList.toggle('is-invalid', showErrors && selected && !yearValid);
                    item.yearError.classList.toggle('show', showErrors && selected && !yearValid);

                    setStatus(item.fileStatus, selected);
                    setStatus(item.nameStatus, selected && nameValid);
                    setStatus(item.yearStatus, selected && yearValid);
                });

                const valid = atLeastOneFile && allSelectedGroupsValid;
                saveButton.disabled = !valid;

                if (!atLeastOneFile) {
                    actionHint.textContent = 'Pilih minimal satu dokumen, lalu lengkapi nama dan Tahun Anggarannya.';
                } else if (!allSelectedGroupsValid) {
                    actionHint.textContent = 'Lengkapi nama dokumen dan Tahun Anggaran untuk file yang dipilih.';
                } else {
                    actionHint.textContent = `${selectedLabels.join(' & ')} siap diproses menggunakan parser Excel.`;
                }

                return valid;
            }

            Object.entries(config).forEach(([type, item]) => {
                item.input.addEventListener('change', () => {
                    if (item.input.files[0]) setSelectedFile(type, item.input.files[0]);
                });

                item.dropzone.addEventListener('click', event => {
                    if (event.target.closest('.remove-file-button') || event.target.closest('.choose-file-button')) return;
                    item.input.click();
                });

                item.dropzone.addEventListener('keydown', event => {
                    if (event.key === 'Enter' || event.key === ' ') {
                        event.preventDefault();
                        item.input.click();
                    }
                });

                item.dropzone.addEventListener('dragover', event => {
                    event.preventDefault();
                    item.dropzone.classList.add('dragover');
                });

                item.dropzone.addEventListener('dragleave', () => item.dropzone.classList.remove('dragover'));

                item.dropzone.addEventListener('drop', event => {
                    event.preventDefault();
                    item.dropzone.classList.remove('dragover');
                    if (event.dataTransfer.files[0]) setSelectedFile(type, event.dataTransfer.files[0]);
                });

                item.name.addEventListener('input', () => updateFormState());
                item.year.addEventListener('change', () => updateFormState());
            });

            document.querySelectorAll('[data-file-button]').forEach(button => {
                button.addEventListener('click', event => {
                    event.stopPropagation();
                    const type = button.dataset.fileButton;
                    config[type]?.input.click();
                });
            });

            document.querySelectorAll('[data-remove-file]').forEach(button => {
                button.addEventListener('click', event => {
                    event.stopPropagation();
                    clearSelectedFile(button.dataset.removeFile);
                });
            });

            function validationDetails(payload) {
                if (!payload || !payload.errors || typeof payload.errors !== 'object') return [];
                return Object.values(payload.errors)
                    .flatMap(value => Array.isArray(value) ? value : [value])
                    .filter(Boolean)
                    .map(String);
            }

            function fallbackHttpMessage(status) {
                const messages = {
                    400: 'Permintaan tidak dapat diproses karena data yang dikirim tidak valid.',
                    401: 'Sesi autentikasi tidak valid. Silakan masuk kembali.',
                    403: 'Anda tidak memiliki izin untuk melakukan proses ini.',
                    413: 'Ukuran file terlalu besar untuk diproses oleh server.',
                    419: 'Sesi halaman telah berakhir. Muat ulang halaman lalu coba kembali.',
                    422: 'Data upload belum lengkap atau tidak valid.',
                    500: 'Terjadi kesalahan internal pada server saat memproses master data.',
                    502: 'Server menerima respons yang tidak valid saat memproses permintaan.',
                    503: 'Layanan server sedang tidak tersedia. Silakan coba kembali beberapa saat lagi.',
                    504: 'Proses pada server melebihi batas waktu. Silakan coba kembali.',
                };

                return messages[status] || `Dokumen gagal diproses (HTTP ${status}).`;
            }

            async function readResponse(response) {
                const raw = await response.text();

                if (!raw) return {};

                try {
                    return JSON.parse(raw);
                } catch (error) {
                    return { rawResponse: raw };
                }
            }

            form.addEventListener('submit', async event => {
                event.preventDefault();

                if (!updateFormState(true)) {
                    return;
                }

                const selectedTypes = Object.keys(config).filter(type => uploadState[type] !== null);
                const selectedLabels = selectedTypes.map(type => config[type].label);
                const loadingLabel = selectedLabels.join(' & ');

                if (!window.DocumentProcessingModal) {
                    alert('Komponen popup pemrosesan tidak ditemukan. Pastikan document-processing-modal.js sudah tersedia.');
                    return;
                }

                DocumentProcessingModal.showLoading({
                    title: 'Memproses Master Data',
                    message: `${loadingLabel} sedang diunggah, dibaca, dan disimpan ke database. Mohon tunggu hingga proses selesai.`,
                });

                saveButton.disabled = true;

                try {
                    const response = await fetch(form.action, {
                        method: 'POST',
                        body: new FormData(form),
                        credentials: 'same-origin',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                    });

                    const payload = await readResponse(response);

                    if (!response.ok || payload.success === false) {
                        const details = validationDetails(payload);
                        let message = payload.message || fallbackHttpMessage(response.status);

                        // Jika server mengirim HTML error page, jangan tampilkan seluruh HTML.
                        if (payload.rawResponse && !payload.message) {
                            message = fallbackHttpMessage(response.status || 500);
                        }

                        DocumentProcessingModal.showError({
                            title: payload.title || 'Master Data Gagal Diproses',
                            message,
                            details,
                            buttonText: 'TUTUP',
                            onClose: () => updateFormState(),
                        });
                        return;
                    }

                    DocumentProcessingModal.showSuccess({
                        title: payload.title || 'Master Data Berhasil Diproses',
                        message: payload.message || 'Master data berhasil diproses dan disimpan ke database.',
                        buttonText: 'OKE',
                        onClose: () => {
                            selectedTypes.forEach(type => clearSelectedFile(type, true));
                            updateFormState();
                        },
                    });
                } catch (error) {
                    console.error('AJAX Upload Master Data Error:', error);

                    DocumentProcessingModal.showError({
                        title: 'Master Data Gagal Diproses',
                        message: 'Koneksi ke server terputus atau respons tidak dapat diterima. Silakan periksa koneksi lalu coba kembali.',
                        details: error?.message ? [error.message] : [],
                        buttonText: 'TUTUP',
                        onClose: () => updateFormState(),
                    });
                }
            });

            updateFormState();
        })();
    </script>
</body>
</html>
