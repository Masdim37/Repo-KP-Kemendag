<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload RKA | Penelitian RKA-K/L</title>
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

        /* HEADER - disamakan dengan halaman Upload TOR/RAB */
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
        .info-box strong { color: #2e5d94; }

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
        .section-divider { height: 1px; margin: 22px 0; background: #e7edf3; }

        /* UPLOAD */
        .document-box {
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
            padding: 5px 8px;
            border: 1px solid #d7e6f7;
            border-radius: 999px;
            color: #4775a8;
            background: #f1f7ff;
            font-size: 7px;
            font-weight: 800;
        }
        .upload-zone {
            min-height: 165px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 18px;
            border: 1.5px dashed #b8c9dc;
            border-radius: 12px;
            background: linear-gradient(180deg, #fff 0%, #fbfdff 100%);
            cursor: pointer;
            text-align: center;
            transition: .2s ease;
        }
        .upload-zone:hover, .upload-zone.dragover { border-color: var(--primary); background: #f6faff; }
        .upload-zone.has-file { border-color: var(--success); background: var(--success-soft); }
        .upload-zone.has-error { border-color: var(--danger); background: var(--danger-soft); }
        .file-input { display: none; }
        .upload-icon {
            width: 43px;
            height: 43px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
            border-radius: 11px;
            color: var(--primary);
            background: #eef5ff;
            font-size: 18px;
        }
        .upload-zone.has-file .upload-icon { color: var(--success); background: #ddf4e7; }
        .drop-title { color: #3e536d; font-size: 9px; font-weight: 700; }
        .drop-or { margin: 5px 0; color: #9ba9b8; font-size: 7px; }
        .choose-button {
            height: 33px;
            padding: 0 16px;
            border: 0;
            border-radius: 8px;
            color: #fff;
            background: linear-gradient(135deg, var(--primary), #0b73d1);
            box-shadow: 0 6px 14px rgba(7,89,183,.16);
            font-size: 8px;
            font-weight: 800;
        }
        .file-format { margin-top: 8px; color: #9aa9ba; font-size: 7px; }
        .selected-file {
            display: none;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            margin-top: 10px;
            padding: 8px 9px;
            border: 1px solid #b9e3cb;
            border-radius: 7px;
            color: #31774e;
            background: #effaf4;
            text-align: left;
        }
        .selected-file.show { display: flex; }
        .selected-file-info { min-width: 0; display: flex; align-items: center; gap: 7px; }
        .selected-file-copy { min-width: 0; }
        .selected-file-name {
            overflow: hidden;
            font-size: 7.5px;
            font-weight: 700;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .selected-file-meta { margin-top: 2px; color: #6e8b79; font-size: 7px; }
        .remove-file {
            width: 25px;
            height: 25px;
            flex-shrink: 0;
            border: 0;
            border-radius: 6px;
            color: var(--danger);
            background: #fff;
        }
        .file-error, .field-error { display: none; margin-top: 6px; color: var(--danger); font-size: 7.5px; }
        .file-error.show, .field-error.show { display: block; }

        .form-group { margin-top: 12px; }
        .form-label {
            display: block;
            margin-bottom: 6px;
            color: #53677e;
            font-size: 8px;
            font-weight: 700;
            text-transform: uppercase;
        }
        .required { color: var(--danger); }
        .form-control {
            width: 100%;
            height: 42px;
            padding: 0 11px;
            border: 1px solid #d5dee7;
            border-radius: 9px;
            outline: none;
            color: #304b69;
            background: #fff;
            font-size: 8.5px;
            transition: .2s ease;
        }
        .form-control:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(7,89,183,.07); }
        .form-control[readonly], .form-control:disabled { color: #8998aa; background: #f4f6f8; }
        .form-control.is-invalid { border-color: #ef9aa4; }

        /* REFERENSI seperti TOR/RAB */
        .reference-box {
            counter-reset: reference-step;
            display: grid;
            gap: 9px;
            padding: 10px;
            border: 1px solid #dbe6f0;
            border-radius: 13px;
            background: #f7fafd;
        }
        .reference-row {
            counter-increment: reference-step;
            display: grid;
            grid-template-columns: 31px minmax(0, 1fr) 150px;
            align-items: end;
            gap: 11px;
            padding: 11px;
            border: 1px solid #e4ebf2;
            border-radius: 10px;
            background: #fff;
        }
        .reference-row::before {
            content: counter(reference-step);
            width: 27px;
            height: 27px;
            align-self: center;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #dce7f3;
            border-radius: 8px;
            color: #6e86a0;
            background: #f3f7fb;
            font-size: 8px;
            font-weight: 900;
        }
        .reference-row.is-complete { border-color: #cce8d8; background: #fcfffd; }
        .reference-row.is-complete::before { content: "✓"; border-color: #b9e3cb; color: #fff; background: var(--success); }
        .reference-field label {
            display: block;
            margin-bottom: 6px;
            color: #526a84;
            font-size: 7.5px;
            font-weight: 800;
            letter-spacing: .3px;
            text-transform: uppercase;
        }
        .select-wrapper { position: relative; }
        .select-wrapper select { padding-right: 42px; appearance: none; }
        .select-wrapper i {
            position: absolute;
            top: 50%;
            right: 7px;
            width: 28px;
            height: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
            transform: translateY(-50%);
            border-left: 1px solid #e3eaf1;
            color: #67809b;
            font-size: 9px;
            pointer-events: none;
        }
        .reference-code {
            border: 1px solid #d7e4f1 !important;
            color: #165da5 !important;
            background: #f1f7fd !important;
            font-family: Consolas, "Courier New", monospace;
            font-size: 8px !important;
            font-weight: 800;
            text-align: center;
        }

        /* STATUS */
        .status-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px; }
        .status-counter {
            padding: 4px 9px;
            border-radius: 12px;
            color: #ad790d;
            background: #fff4d7;
            font-size: 7px;
            font-weight: 700;
        }
        .status-grid {
            display: grid;
            grid-template-columns: repeat(6, 1fr);
            gap: 9px;
            padding: 13px;
            border: 1px solid #e3e9ef;
            border-radius: 9px;
            background: #fafbfd;
        }
        .status-item { display: flex; align-items: center; gap: 6px; color: #9baaba; font-size: 7px; }
        .status-dot {
            width: 15px;
            height: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            border-radius: 50%;
            color: #a7b5c3;
            background: #e7edf2;
            font-size: 7px;
        }
        .status-item.complete { color: #267849; }
        .status-item.complete .status-dot { color: #fff; background: var(--success); }
        .progress-track { height: 4px; margin-top: 12px; overflow: hidden; border-radius: 10px; background: #e9eef3; }
        .progress-bar { width: 0; height: 100%; border-radius: 10px; background: var(--success); transition: width .3s ease; }
        .progress-text { margin-top: 5px; text-align: right; color: #77899b; font-size: 7px; }

        .bottom-actions {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            margin-top: 15px;
        }
        .action-message { color: #8798aa; font-size: 7.5px; }
        .save-button {
            min-width: 205px;
            height: 39px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            padding: 0 18px;
            border: 0;
            border-radius: 8px;
            color: #fff;
            background: var(--primary);
            font-size: 8.5px;
            font-weight: 700;
        }
        .save-button:disabled { color: #98a7b7; background: #e1e7ed; cursor: not-allowed; }
        .is-spinning { animation: spin .85s linear infinite; }
        @keyframes spin { to { transform: rotate(360deg); } }

        /* TOAST */
        .toast {
            position: fixed;
            top: 80px;
            right: 20px;
            z-index: 2000;
            display: none;
            align-items: flex-start;
            gap: 9px;
            width: 350px;
            padding: 12px 14px;
            border-radius: 9px;
            box-shadow: 0 12px 30px rgba(30,60,90,.14);
            font-size: 8px;
        }
        .toast.show { display: flex; }
        .toast.success { border: 1px solid #bae4cc; color: #2d704a; background: #effaf4; }
        .toast.error { border: 1px solid #f1c3c9; color: #a63c4a; background: #fff4f5; }
        .toast strong { display: block; margin-bottom: 2px; font-size: 9px; }
        .toast i { margin-top: 1px; font-size: 16px; }

        .footer { margin-top: auto; border-top: 1px solid var(--border); background: #eef3f8; }
        .footer-container { width: 100%; max-width: 1180px; min-height: 68px; display: flex; align-items: center; margin: 0 auto; padding: 15px 26px; }
        .footer-brand { color: #75889b; font-size: 7.5px; line-height: 1.6; }
        .footer-brand strong { display: block; color: #405974; font-size: 8.5px; }

        @media (max-width: 900px) {
            .status-grid { grid-template-columns: repeat(3, 1fr); }
        }
        @media (max-width: 680px) {
            .dashboard-header { padding: 0 14px; }
            .sidebar-toggle { display: flex; }
            .header-user-text { display: none; }
            .page-container { padding: 14px; }
            .main-form { padding: 15px; }
            .reference-row { grid-template-columns: 31px minmax(0, 1fr); align-items: center; }
            .reference-row .reference-field:last-child { grid-column: 2; }
            .status-grid { grid-template-columns: repeat(2, 1fr); }
            .bottom-actions { align-items: stretch; flex-direction: column; }
            .save-button { width: 100%; }
        }
    </style>
</head>
<body>
@php
    $userName = data_get($user ?? null, 'name', session('user_name', 'Pengguna'));
    $jabatanName = data_get($user ?? null, 'jabatan.jabatan_name', session('jabatan_name', 'Perencana'));
    $initials = collect(explode(' ', $userName))
        ->filter()
        ->take(2)
        ->map(fn ($word) => strtoupper(substr($word, 0, 1)))
        ->implode('');
@endphp

<div class="app-shell">
    @include('partials.sidebar', [
        'activeMenu' => 'upload-rka',
        'sidebarUserName' => $userName,
        // 'sidebarUserRole' => $jabatanName,
        'sidebarInitials' => $initials,
    ])

    <div class="app-main">
        <header class="dashboard-header">
            <div class="header-left">
                <button type="button" class="sidebar-toggle" id="sidebarToggle" aria-label="Buka menu navigasi">
                    <i class="bi bi-list"></i>
                </button>
                <div class="header-copy">
                    <div class="header-eyebrow">SISTEM INFORMASI PENELITIAN RKA-K/L</div>
                    <div class="header-title">Upload RKA</div>
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
                    <i class="bi bi-info-circle"></i>
                    <div>
                        <strong>Panduan Unggah RKA</strong><br>
                        Unggah RKA/Kertas Kerja Satker dalam format PDF, XLSX, atau XLS.
                    </div>
                </div>

                <section class="main-card">
                    <div class="card-header">
                        <div class="card-header-left">
                            <div class="card-header-icon"><i class="bi bi-file-earmark-bar-graph"></i></div>
                            <div>
                                <h1 class="card-title">Upload Dokumen RKA</h1>
                                <p class="card-description">Unggah file Rincian Kertas Kerja Satker sebagai RKA.</p>
                            </div>
                        </div>
                        {{-- <div class="card-date"><i class="bi bi-clock"></i> {{ now()->translatedFormat('d F Y') }}</div> --}}
                    </div>

                    <form id="uploadRkaForm" class="main-form" action="{{ route('upload.rka.store') }}" method="POST" enctype="multipart/form-data" novalidate>
                        @csrf

                        {{-- <div class="section-title">
                            <h2>File Dokumen</h2>
                            <span class="section-note">PDF · XLSX · XLS</span>
                        </div> --}}

                        <div class="document-box">
                            <div class="document-title-row">
                                <div class="document-title">
                                    <i class="bi bi-file-earmark-arrow-up"></i>
                                    Upload RKA / Kertas Kerja Satker
                                </div>
                                <span class="format-badge">PDF / Excel</span>
                            </div>

                            <div class="upload-zone" id="rkaDropzone">
                                <input
                                    type="file"
                                    id="rkaFile"
                                    name="rka_file"
                                    class="file-input"
                                    accept=".pdf,.xlsx,.xls,application/pdf,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel"
                                >

                                <div>
                                    <div class="upload-icon"><i class="bi bi-upload"></i></div>
                                    <div class="drop-title">Seret & jatuhkan file RKA di sini</div>
                                    <div class="drop-or">atau</div>
                                    <button type="button" class="choose-button" id="chooseFileButton">Pilih File</button>
                                    {{-- <div class="file-format">PDF diproses AI · Excel diproses parser manual</div> --}}

                                    <div class="selected-file" id="selectedFile">
                                        <div class="selected-file-info">
                                            <i class="bi bi-file-earmark-check"></i>
                                            <div class="selected-file-copy">
                                                <div class="selected-file-name" id="selectedFileName"></div>
                                                <div class="selected-file-meta" id="selectedFileMeta"></div>
                                            </div>
                                        </div>
                                        <button type="button" class="remove-file" id="removeFileButton" aria-label="Hapus file">
                                            <i class="bi bi-x-lg"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="file-error" id="fileError">RKA hanya dapat menggunakan PDF, XLSX, atau XLS.</div>
                            @error('rka_file')
                                <div class="file-error show">{{ $message }}</div>
                            @enderror

                            <div class="form-group">
                                <label class="form-label" for="rkaName">Nama File RKA <span class="required">*</span></label>
                                <input
                                    type="text"
                                    id="rkaName"
                                    name="rka_name"
                                    class="form-control"
                                    value="{{ old('rka_name') }}"
                                    maxlength="255"
                                    placeholder="Terisi otomatis saat file dipilih..."
                                >
                                <div class="field-error" id="rkaNameError">Nama file RKA wajib diisi.</div>
                                @error('rka_name')
                                    <div class="field-error show">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="section-divider"></div>

                        <div class="section-title">
                            <h2>Referensi Dokumen</h2>
                            <span class="section-note">Tahun & Organisasi</span>
                        </div>

                        <div class="reference-box">
                            <!-- TAHUN -->
                            <div class="reference-row" id="rowTahun">
                                <div class="reference-field">
                                    <label>Tahun Anggaran</label>
                                    <div class="select-wrapper">
                                        <select id="tahunAnggaran" name="tahun_anggaran" class="form-control">
                                            <option value="">-- Pilih Tahun Anggaran --</option>
                                            @foreach ($tahunAnggaran as $tahun)
                                                <option value="{{ $tahun }}" {{ (string) old('tahun_anggaran') === (string) $tahun ? 'selected' : '' }}>
                                                    {{ $tahun }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <i class="bi bi-chevron-down"></i>
                                    </div>
                                    <div class="field-error" id="tahunError">Tahun Anggaran wajib dipilih.</div>
                                    @error('tahun_anggaran')
                                        <div class="field-error show">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="reference-field">
                                    <label>TA</label>
                                    <input id="kodeTahun" class="form-control reference-code" readonly placeholder="-">
                                </div>
                            </div>

                            <!-- ESELON I -->
                            <div class="reference-row" id="rowEselon1">
                                <div class="reference-field">
                                    <label>Unit Eselon 1</label>
                                    <div class="select-wrapper">
                                        <select id="eselon1" name="kode_unit_eselon1" class="form-control">
                                            <option value="">-- Pilih Unit Eselon 1 --</option>
                                            @foreach ($unitEselon1 as $e1)
                                                <option value="{{ $e1->kode_unit_eselon1 }}" {{ old('kode_unit_eselon1') == $e1->kode_unit_eselon1 ? 'selected' : '' }}>
                                                    [{{ $e1->kode_unit_eselon1 }}] {{ $e1->nama_unit_eselon1 }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <i class="bi bi-chevron-down"></i>
                                    </div>
                                    <div class="field-error" id="eselon1Error">Unit Eselon 1 wajib dipilih.</div>
                                    @error('kode_unit_eselon1')
                                        <div class="field-error show">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="reference-field">
                                    <label>Kode Unit Eselon 1</label>
                                    <input id="kodeEselon1" class="form-control reference-code" readonly placeholder="-">
                                </div>
                            </div>

                            <!-- ESELON II -->
                            <div class="reference-row" id="rowEselon2">
                                <div class="reference-field">
                                    <label>Unit Eselon 2</label>
                                    <div class="select-wrapper">
                                        <select id="eselon2" name="kode_unit_eselon2" class="form-control" disabled>
                                            <option value="">-- Pilih Unit Eselon 1 terlebih dahulu --</option>
                                        </select>
                                        <i class="bi bi-chevron-down"></i>
                                    </div>
                                    <div class="field-error" id="eselon2Error">Unit Eselon 2 wajib dipilih.</div>
                                    @error('kode_unit_eselon2')
                                        <div class="field-error show">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="reference-field">
                                    <label>Kode Unit Eselon 2</label>
                                    <input id="kodeEselon2" class="form-control reference-code" readonly placeholder="-">
                                </div>
                            </div>

                            <!-- SATKER -->
                            <div class="reference-row" id="rowSatker">
                                <div class="reference-field">
                                    <label>Satuan Kerja</label>
                                    <div class="select-wrapper">
                                        <select id="satker" name="kode_satker" class="form-control" disabled>
                                            <option value="">-- Pilih Unit Eselon 2 terlebih dahulu --</option>
                                        </select>
                                        <i class="bi bi-chevron-down"></i>
                                    </div>
                                    <div class="field-error" id="satkerError">Satuan Kerja wajib dipilih.</div>
                                    @error('kode_satker')
                                        <div class="field-error show">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="reference-field">
                                    <label>Kode Satker</label>
                                    <input id="kodeSatker" class="form-control reference-code" readonly placeholder="-">
                                </div>
                            </div>
                        </div>

                        <div class="section-divider"></div>

                        <div class="status-header">
                            <div class="section-title" style="margin:0;"><h2>Status Kelengkapan</h2></div>
                            <span class="status-counter" id="statusCounter">0/6 terpenuhi</span>
                        </div>

                        <div class="status-grid">
                            @foreach ([
                                'file' => 'File RKA',
                                'name' => 'Nama RKA',
                                'tahun' => 'Tahun',
                                'eselon1' => 'Unit Eselon 1',
                                'eselon2' => 'Unit Eselon 2',
                                'satker' => 'Satker'
                            ] as $key => $label)
                                <div class="status-item" data-status="{{ $key }}">
                                    <span class="status-dot"><i class="bi bi-check"></i></span>
                                    {{ $label }}
                                </div>
                            @endforeach
                        </div>

                        <div class="progress-track"><div class="progress-bar" id="progressBar"></div></div>
                        <div class="progress-text" id="progressText">0%</div>

                        <div class="bottom-actions">
                            <div class="action-message" id="actionMessage">Lengkapi file dan referensi dokumen.</div>
                            <button type="submit" class="save-button" id="saveButton" disabled>
                                <i class="bi bi-upload"></i>
                                Simpan Dokumen RKA
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

@if (session('success'))
<div class="toast success show" id="successToast" role="status">
    <i class="bi bi-check-circle-fill"></i>
    <div><strong>Data Berhasil Disimpan</strong>{{ session('success') }}</div>
</div>
@endif

@if (session('error'))
<div class="toast error show" id="errorToast" role="alert">
    <i class="bi bi-exclamation-circle-fill"></i>
    <div><strong>RKA Gagal Diproses</strong>{{ session('error') }}</div>
</div>
@endif

@include('partials.document-processing-modal')
<script src="{{ asset('js/document-processing-modal.js') }}"></script>

<script>
    const allEselon2 = @json($unitEselon2 ?? []);
    const allSatker = @json($satker ?? []);

    const oldReference = {
        tahun: @json(old('tahun_anggaran')),
        eselon1: @json(old('kode_unit_eselon1')),
        eselon2: @json(old('kode_unit_eselon2')),
        satker: @json(old('kode_satker')),
    };

    const form = document.getElementById('uploadRkaForm');
    const fileInput = document.getElementById('rkaFile');
    const dropzone = document.getElementById('rkaDropzone');
    const chooseFileButton = document.getElementById('chooseFileButton');
    const removeFileButton = document.getElementById('removeFileButton');
    const selectedFile = document.getElementById('selectedFile');
    const selectedFileName = document.getElementById('selectedFileName');
    const selectedFileMeta = document.getElementById('selectedFileMeta');
    const fileError = document.getElementById('fileError');
    const rkaName = document.getElementById('rkaName');
    const tahun = document.getElementById('tahunAnggaran');
    const eselon1 = document.getElementById('eselon1');
    const eselon2 = document.getElementById('eselon2');
    const satker = document.getElementById('satker');
    const kodeTahun = document.getElementById('kodeTahun');
    const kodeEselon1 = document.getElementById('kodeEselon1');
    const kodeEselon2 = document.getElementById('kodeEselon2');
    const kodeSatker = document.getElementById('kodeSatker');
    const saveButton = document.getElementById('saveButton');
    const actionMessage = document.getElementById('actionMessage');

    let selectedRkaFile = null;

    const asString = value => value === null || value === undefined ? '' : String(value).trim();
    const sameCode = (a, b) => asString(a) === asString(b);

    function fileExtension(name) {
        return name.includes('.') ? name.split('.').pop().toLowerCase() : '';
    }

    function isAllowedFile(file) {
        return !!file && ['pdf', 'xlsx', 'xls'].includes(fileExtension(file.name));
    }

    function formatFileSize(bytes) {
        if (bytes < 1024) return `${bytes} Bytes`;
        if (bytes < 1024 * 1024) return `${(bytes / 1024).toFixed(1)} KB`;
        return `${(bytes / (1024 * 1024)).toFixed(1)} MB`;
    }

    function setFileError(message = '') {
        fileError.textContent = message;
        fileError.classList.toggle('show', !!message);
        dropzone.classList.toggle('has-error', !!message);
    }

    function assignFile(file) {
        if (!file || !isAllowedFile(file)) {
            clearFile();
            setFileError('RKA hanya dapat menggunakan file PDF, XLSX, atau XLS.');
            updateStatus();
            return false;
        }

        if (file.size > 50 * 1024 * 1024) {
            clearFile();
            setFileError('Ukuran file RKA maksimal 50 MB.');
            updateStatus();
            return false;
        }

        setFileError();
        selectedRkaFile = file;

        try {
            const transfer = new DataTransfer();
            transfer.items.add(file);
            fileInput.files = transfer.files;
        } catch (error) {
            console.warn(error);
        }

        const ext = fileExtension(file.name);
        const typeLabel = ext === 'pdf' ? 'PDF · Gemini AI' : 'Excel · Parser manual';

        selectedFileName.textContent = file.name;
        selectedFileMeta.textContent = `${formatFileSize(file.size)} · ${typeLabel}`;
        selectedFile.classList.add('show');
        dropzone.classList.add('has-file');

        if (!rkaName.value.trim()) {
            rkaName.value = file.name.replace(/\.(pdf|xlsx|xls)$/i, '');
        }

        updateStatus();
        return true;
    }

    function clearFile() {
        selectedRkaFile = null;
        fileInput.value = '';
        selectedFileName.textContent = '';
        selectedFileMeta.textContent = '';
        selectedFile.classList.remove('show');
        dropzone.classList.remove('has-file', 'has-error');
        fileError.classList.remove('show');
    }

    chooseFileButton.addEventListener('click', event => {
        event.stopPropagation();
        fileInput.click();
    });

    dropzone.addEventListener('click', event => {
        if (event.target.closest('button')) return;
        fileInput.click();
    });

    fileInput.addEventListener('change', () => assignFile(fileInput.files[0]));

    removeFileButton.addEventListener('click', event => {
        event.stopPropagation();
        clearFile();
        updateStatus();
    });

    ['dragenter', 'dragover'].forEach(eventName => {
        dropzone.addEventListener(eventName, event => {
            event.preventDefault();
            event.stopPropagation();
            dropzone.classList.add('dragover');
        });
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropzone.addEventListener(eventName, event => {
            event.preventDefault();
            event.stopPropagation();
            dropzone.classList.remove('dragover');
        });
    });

    dropzone.addEventListener('drop', event => {
        const file = event.dataTransfer?.files?.[0];
        if (file) assignFile(file);
    });

    function renderOptions(select, data, valueKey, labelKey, placeholder, selectedValue = '') {
        select.innerHTML = `<option value="">${data.length ? placeholder : '-- Data tidak tersedia --'}</option>`;

        data.forEach(item => {
            const code = asString(item?.[valueKey]);
            if (!code) return;

            const name = asString(item?.[labelKey]);
            const option = document.createElement('option');
            option.value = code;
            option.textContent = name ? `[${code}] ${name}` : `[${code}]`;
            select.appendChild(option);
        });

        select.disabled = data.length === 0;

        if (selectedValue && data.some(item => sameCode(item?.[valueKey], selectedValue))) {
            select.value = asString(selectedValue);
        }
    }

    function populateEselon2(selectedValue = '') {
        const code = asString(eselon1.value);
        if (!code) {
            eselon2.innerHTML = '<option value="">-- Pilih Unit Eselon 1 terlebih dahulu --</option>';
            eselon2.disabled = true;
            return;
        }

        const filtered = allEselon2.filter(item => sameCode(item.kode_unit_eselon1, code));
        renderOptions(eselon2, filtered, 'kode_unit_eselon2', 'nama_unit_eselon2', '-- Pilih Unit Eselon 2 --', selectedValue);
    }

    function populateSatker(selectedValue = '') {
        const code = asString(eselon2.value);
        if (!code) {
            satker.innerHTML = '<option value="">-- Pilih Unit Eselon 2 terlebih dahulu --</option>';
            satker.disabled = true;
            return;
        }

        const filtered = allSatker.filter(item => sameCode(item.kode_unit_eselon2, code));
        renderOptions(satker, filtered, 'kode_satker', 'nama_satker', '-- Pilih Satuan Kerja --', selectedValue);
    }

    tahun.addEventListener('change', () => {
        kodeTahun.value = tahun.value;
        updateStatus();
    });

    eselon1.addEventListener('change', () => {
        kodeEselon1.value = eselon1.value;
        eselon2.value = '';
        kodeEselon2.value = '';
        satker.value = '';
        kodeSatker.value = '';
        populateEselon2();
        satker.innerHTML = '<option value="">-- Pilih Unit Eselon 2 terlebih dahulu --</option>';
        satker.disabled = true;
        updateStatus();
    });

    eselon2.addEventListener('change', () => {
        kodeEselon2.value = eselon2.value;
        satker.value = '';
        kodeSatker.value = '';
        populateSatker();
        updateStatus();
    });

    satker.addEventListener('change', () => {
        kodeSatker.value = satker.value;
        updateStatus();
    });

    function initializeReferences() {
        kodeTahun.value = asString(tahun.value);
        kodeEselon1.value = asString(eselon1.value);

        if (eselon1.value) {
            populateEselon2(oldReference.eselon2);
            kodeEselon2.value = asString(eselon2.value);

            if (eselon2.value) {
                populateSatker(oldReference.satker);
                kodeSatker.value = asString(satker.value);
            }
        }
    }

    function toggleError(field, errorId, isValid, showErrors) {
        field.classList.toggle('is-invalid', showErrors && !isValid);
        const error = document.getElementById(errorId);
        if (error) error.classList.toggle('show', showErrors && !isValid);
    }

    function updateStatus(showErrors = false) {
        const values = {
            file: !!selectedRkaFile && isAllowedFile(selectedRkaFile),
            name: !!rkaName.value.trim(),
            tahun: !!tahun.value,
            eselon1: !!eselon1.value,
            eselon2: !!eselon2.value,
            satker: !!satker.value,
        };

        Object.entries(values).forEach(([key, complete]) => {
            document.querySelector(`[data-status="${key}"]`)?.classList.toggle('complete', complete);
        });

        document.getElementById('rowTahun').classList.toggle('is-complete', values.tahun);
        document.getElementById('rowEselon1').classList.toggle('is-complete', values.eselon1);
        document.getElementById('rowEselon2').classList.toggle('is-complete', values.eselon2);
        document.getElementById('rowSatker').classList.toggle('is-complete', values.satker);

        toggleError(rkaName, 'rkaNameError', values.name, showErrors);
        toggleError(tahun, 'tahunError', values.tahun, showErrors);
        toggleError(eselon1, 'eselon1Error', values.eselon1, showErrors);
        toggleError(eselon2, 'eselon2Error', values.eselon2, showErrors);
        toggleError(satker, 'satkerError', values.satker, showErrors);

        if (showErrors && !values.file) {
            setFileError('File RKA wajib dipilih dalam format PDF, XLSX, atau XLS.');
        }

        const completed = Object.values(values).filter(Boolean).length;
        const total = Object.keys(values).length;
        const ready = completed === total;
        const percent = Math.round((completed / total) * 100);

        document.getElementById('statusCounter').textContent = `${completed}/${total} terpenuhi`;
        document.getElementById('progressBar').style.width = `${percent}%`;
        document.getElementById('progressText').textContent = `${percent}%`;
        saveButton.disabled = !ready;

        if (!values.file) {
            actionMessage.textContent = 'Pilih dokumen RKA PDF atau Excel.';
        } else if (!ready) {
            actionMessage.textContent = `Lengkapi ${total - completed} data yang belum terisi.`;
        } else {
            const typeLabel = fileExtension(selectedRkaFile.name) === 'pdf' ? 'PDF untuk diproses AI' : 'Excel untuk diproses parser';
            actionMessage.textContent = `Dokumen ${typeLabel} dan seluruh referensi telah lengkap.`;
        }

        return ready;
    }

    rkaName.addEventListener('input', () => updateStatus());

    initializeReferences();
    updateStatus();

    function ajaxHttpMessage(status) {
        const messages = {
            400: 'Permintaan tidak dapat diproses karena data request tidak valid.',
            401: 'Sesi atau autentikasi tidak valid.',
            403: 'Anda tidak memiliki izin untuk menjalankan proses ini.',
            413: 'Ukuran dokumen terlalu besar untuk diproses.',
            419: 'Sesi halaman telah berakhir. Silakan muat ulang halaman lalu coba kembali.',
            422: 'Data yang dikirim belum lengkap atau tidak valid.',
            429: 'Layanan Gemini sedang menerima terlalu banyak permintaan. Silakan tunggu beberapa saat lalu coba kembali.',
            500: 'Terjadi kesalahan internal pada server saat memproses dokumen RKA.',
            502: 'Terjadi gangguan saat server menghubungi layanan eksternal.',
            503: 'Layanan Gemini atau server sedang tidak tersedia. Silakan coba kembali beberapa saat lagi.',
            504: 'Proses layanan eksternal melebihi batas waktu. Silakan coba kembali.'
        };

        return messages[status] || `Proses gagal dengan HTTP ${status}.`;
    }

    function validationDetails(payload) {
        if (!payload?.errors || Array.isArray(payload.errors)) {
            return [];
        }

        return Object.values(payload.errors)
            .flatMap(messages => Array.isArray(messages) ? messages : [messages])
            .filter(Boolean);
    }

    async function readAjaxPayload(response) {
        const raw = await response.text();

        if (!raw) {
            return {};
        }

        try {
            return JSON.parse(raw);
        } catch (error) {
            return {
                success: response.ok,
                message: response.ok ? '' : ajaxHttpMessage(response.status)
            };
        }
    }

    async function submitRkaWithAjax() {
        const response = await fetch(form.action, {
            method: 'POST',
            body: new FormData(form),
            credentials: 'same-origin',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        const payload = await readAjaxPayload(response);

        if (!response.ok || payload.success === false) {
            const error = new Error(
                payload.message
                || ajaxHttpMessage(response.status)
            );

            error.title = payload.title || 'RKA Gagal Diproses';
            error.status = response.status;
            error.details = validationDetails(payload);
            throw error;
        }

        return payload;
    }

    form.addEventListener('submit', async event => {
        event.preventDefault();

        if (!updateStatus(true)) {
            return;
        }

        if (!assignFile(fileInput.files[0])) {
            return;
        }

        const extension = fileExtension(selectedRkaFile.name);
        const isPdf = extension === 'pdf';

        saveButton.disabled = true;
        saveButton.innerHTML = '<i class="bi bi-arrow-repeat is-spinning"></i> Memproses RKA...';

        DocumentProcessingModal.showLoading({
            title: 'Memproses Dokumen RKA',
            message: isPdf
                ? 'Dokumen RKA PDF sedang diunggah, dibaca, dan disimpan ke database. Mohon tunggu hingga proses selesai.'
                : 'Dokumen RKA Excel sedang diunggah, dibaca, dan disimpan ke database. Mohon tunggu hingga proses selesai.'
        });

        try {
            const payload = await submitRkaWithAjax();

            DocumentProcessingModal.showSuccess({
                title: payload.title || 'Dokumen RKA Berhasil Diproses',
                message: payload.message || 'Dokumen RKA berhasil diproses dan disimpan.',
                buttonText: 'OKE',
                onClose: () => {
                    clearFile();
                    rkaName.value = '';
                    saveButton.innerHTML = '<i class="bi bi-upload"></i> Simpan Dokumen RKA';
                    updateStatus();
                }
            });
        } catch (error) {
            DocumentProcessingModal.showError({
                title: error.title || 'RKA Gagal Diproses',
                message: error.message || 'Terjadi kesalahan saat memproses dokumen RKA.',
                details: error.details || [],
                buttonText: 'TUTUP',
                onClose: () => {
                    saveButton.innerHTML = '<i class="bi bi-upload"></i> Simpan Dokumen RKA';
                    updateStatus();
                }
            });
        }
    });

    ['successToast', 'errorToast'].forEach(id => {
        const toast = document.getElementById(id);
        if (toast) setTimeout(() => toast.classList.remove('show'), 4500);
    });
</script>
</body>
</html>
