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
            --primary-light: #edf5ff;
            --primary-dark: #06498f;
            --success: #159957;
            --danger: #df4052;
            --text-primary: #18365b;
            --text-secondary: #607995;
            --text-muted: #91a4b9;
            --background: #f4f7fb;
            --border: #dbe5ee;
            --white: #ffffff;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html,
        body {
            min-height: 100%;
        }

        body {
            min-height: 100vh;
            overflow-x: hidden;
            color: var(--text-primary);
            background: var(--background);
            font-family: Inter, "Segoe UI", Arial, sans-serif;
        }

        button,
        input,
        select {
            font: inherit;
        }

        button,
        select {
            cursor: pointer;
        }

        .app-shell {
            min-height: 100vh;
        }

        .app-main {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

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
            background: rgba(255, 255, 255, 0.96);
            box-shadow: 0 4px 18px rgba(33, 67, 103, 0.05);
            backdrop-filter: blur(12px);
        }

        .header-left {
            min-width: 0;
            display: flex;
            align-items: center;
            gap: 13px;
        }

        .sidebar-toggle {
            display: none;
            width: 36px;
            height: 36px;
            align-items: center;
            justify-content: center;
            border: 1px solid var(--border);
            border-radius: 10px;
            color: var(--text-primary);
            background: #ffffff;
            font-size: 18px;
        }

        .header-copy {
            min-width: 0;
        }

        .header-eyebrow {
            overflow: hidden;
            color: #879bb1;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 0.9px;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .header-title {
            margin-top: 3px;
            color: var(--text-primary);
            font-size: 17px;
            font-weight: 800;
        }

        .header-user {
            flex-shrink: 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .header-user-text {
            text-align: right;
            color: var(--text-secondary);
            font-size: 11px;
            line-height: 1.4;
        }

        .header-user-text strong {
            display: block;
            max-width: 200px;
            overflow: hidden;
            color: var(--text-primary);
            font-size: 12px;
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
            color: #ffffff;
            background: linear-gradient(135deg, #063c7c, #1681d5);
            box-shadow: 0 5px 12px rgba(31, 91, 148, 0.16);
            font-size: 10px;
            font-weight: 800;
        }

        /* CONTENT */

        .page-container {
            width: 100%;
            min-height: 0;
            flex: 1;
            padding: 26px;
            background: var(--background);
        }

        .upload-card {
            width: 100%;
            max-width: 860px;
            margin: 0 auto;
            border: 1px solid var(--border);
            border-radius: 16px;
            background: var(--white);
            box-shadow: 0 14px 38px rgba(38, 68, 103, 0.08);
            overflow: hidden;
        }

        .card-header {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 22px 24px;
            border-bottom: 1px solid #e6ecf2;
        }

        .card-header-icon {
            width: 42px;
            height: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            border-radius: 10px;
            color: var(--primary);
            background: var(--primary-light);
            font-size: 19px;
        }

        .card-title {
            font-size: 18px;
            font-weight: 800;
        }

        .card-description {
            margin-top: 3px;
            color: var(--text-muted);
            font-size: 11px;
            line-height: 1.5;
        }

        .upload-form {
            padding: 24px;
        }

        /* UPLOAD */

        .upload-zone {
            position: relative;
            min-height: 220px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 30px;
            border: 2px dashed #8ebcff;
            border-radius: 14px;
            background: #f8fbff;
            text-align: center;
            cursor: pointer;
            transition: 0.2s ease;
        }

        .upload-zone:hover,
        .upload-zone.dragover {
            border-color: var(--primary);
            background: #f0f6ff;
            box-shadow: 0 0 0 4px rgba(7, 89, 183, 0.06);
        }

        .upload-zone.has-file {
            border-color: var(--success);
            background: #f2fbf6;
        }

        .upload-zone.has-error {
            border-color: var(--danger);
            background: #fff5f6;
        }

        .file-input {
            position: absolute;
            width: 1px;
            height: 1px;
            opacity: 0;
            pointer-events: none;
        }

        .upload-icon {
            width: 58px;
            height: 58px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            border-radius: 50%;
            color: #1967d2;
            background: #e1edff;
            font-size: 25px;
        }

        .upload-zone.has-file .upload-icon {
            color: var(--success);
            background: #def5e8;
        }

        .upload-title {
            font-size: 15px;
            font-weight: 800;
        }

        .upload-description {
            margin-top: 5px;
            color: var(--text-muted);
            font-size: 11px;
            line-height: 1.5;
        }

        .choose-file-button {
            min-width: 124px;
            height: 40px;
            margin-top: 13px;
            padding: 0 17px;
            border: 0;
            border-radius: 10px;
            color: #ffffff;
            background: var(--primary);
            box-shadow: 0 5px 12px rgba(7, 89, 183, 0.2);
            font-size: 11px;
            font-weight: 700;
        }

        .choose-file-button:hover {
            background: var(--primary-dark);
        }

        .selected-file {
            display: none;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-top: 16px;
            padding: 12px 14px;
            border: 1px solid #b9e4cc;
            border-radius: 10px;
            color: #297347;
            background: #effaf4;
            text-align: left;
        }

        .selected-file.show {
            display: flex;
        }

        .selected-file-info {
            min-width: 0;
            display: flex;
            align-items: center;
            gap: 9px;
        }

        .selected-file-info i {
            flex-shrink: 0;
            color: var(--success);
            font-size: 18px;
        }

        .selected-file-name {
            overflow: hidden;
            font-size: 11px;
            font-weight: 700;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .selected-file-size {
            margin-top: 2px;
            color: #6c8b79;
            font-size: 10px;
        }

        .remove-file-button {
            width: 28px;
            height: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            border: 0;
            border-radius: 7px;
            color: var(--danger);
            background: #ffffff;
        }

        .file-note {
            display: flex;
            align-items: flex-start;
            gap: 4px;
            margin-top: 10px;
            color: #8ca0b7;
            font-size: 10px;
            line-height: 1.55;
        }

        .file-note::before {
            content: "*";
            color: var(--danger);
            font-weight: 800;
        }

        .error-message {
            display: none;
            margin-top: 6px;
            color: var(--danger);
            font-size: 10px;
            font-weight: 600;
        }

        .error-message.show {
            display: block;
        }

        /* FORM */

        .form-divider {
            height: 1px;
            margin: 18px 0;
            background: #e6ecf2;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 18px 20px;
        }

        .form-group.full-width {
            grid-column: 1 / -1;
        }

        .form-label {
            display: block;
            margin-bottom: 7px;
            color: #344e6c;
            font-size: 11px;
            font-weight: 700;
        }

        .required {
            color: var(--danger);
        }

        .form-control {
            width: 100%;
            height: 44px;
            padding: 0 12px;
            border: 1px solid #d4dee8;
            border-radius: 10px;
            outline: none;
            color: var(--text-primary);
            background: #ffffff;
            font-size: 11px;
            transition: 0.2s ease;
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(7, 89, 183, 0.08);
        }

        .form-control:disabled,
        .form-control[readonly] {
            color: #8292a5;
            background: #f3f5f7;
            cursor: default;
        }

        .form-control.is-valid {
            border-color: var(--success);
        }

        .form-control.is-invalid {
            border-color: var(--danger);
        }

        .select-wrapper {
            position: relative;
        }

        .select-wrapper select {
            padding-right: 35px;
            appearance: none;
        }

        .select-wrapper i {
            position: absolute;
            top: 50%;
            right: 12px;
            transform: translateY(-50%);
            color: #8292a5;
            font-size: 9px;
            pointer-events: none;
        }

        .upload-zone:focus-visible {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(7, 89, 183, 0.10);
        }

        .upload-zone.has-error .upload-icon {
            color: var(--danger);
            background: #ffe7ea;
        }

        .selected-file-info i {
            color: var(--success);
        }

        select.form-control {
            padding-right: 36px;
            appearance: none;
            background-image:
                linear-gradient(45deg, transparent 50%, #8193a7 50%),
                linear-gradient(135deg, #8193a7 50%, transparent 50%);
            background-position:
                calc(100% - 17px) 19px,
                calc(100% - 12px) 19px;
            background-size: 5px 5px, 5px 5px;
            background-repeat: no-repeat;
        }

        select.form-control:disabled {
            opacity: 1;
            color: #8a9aac;
            background-color: #f2f5f8;
        }

        .form-control.is-valid {
            border-color: #a9d8bd;
            box-shadow: 0 0 0 3px rgba(21, 153, 87, 0.05);
        }

        .form-control.is-invalid {
            border-color: #ef9aa4;
            box-shadow: 0 0 0 3px rgba(223, 64, 82, 0.05);
        }

        /* STATUS */

        .status-list {
            display: flex;
            flex-wrap: wrap;
            gap: 7px;
            margin-top: 19px;
            padding-top: 16px;
            border-top: 1px solid #e6ecf2;
        }

        .status-chip {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 5px 9px;
            border: 1px solid #dce4ec;
            border-radius: 20px;
            color: #98a8b8;
            background: #f7f9fb;
            font-size: 9px;
            font-weight: 700;
        }

        .status-chip::before {
            content: "";
            width: 5px;
            height: 5px;
            border-radius: 50%;
            background: #c7d0d9;
        }

        .status-chip.complete {
            color: #257749;
            border-color: #bee5cf;
            background: #f0faf4;
        }

        .status-chip.complete::before {
            background: var(--success);
        }

        /* ACTION */

        .form-actions {
            display: flex;
            justify-content: flex-end;
            margin-top: 18px;
        }

        .save-button {
            min-width: 190px;
            height: 44px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 0 20px;
            border: 0;
            border-radius: 10px;
            color: #ffffff;
            background: var(--primary);
            box-shadow: 0 6px 14px rgba(7, 89, 183, 0.2);
            font-size: 11px;
            font-weight: 700;
        }

        .save-button:hover:not(:disabled) {
            background: var(--primary-dark);
        }

        .save-button:disabled {
            color: #9baabc;
            background: #e1e7ee;
            box-shadow: none;
            cursor: not-allowed;
        }

        /* TOAST */

        .toast-message {
            position: fixed;
            top: 82px;
            right: 22px;
            z-index: 2000;
            width: min(350px, calc(100% - 30px));
            display: none;
            align-items: flex-start;
            gap: 10px;
            padding: 13px 15px;
            border: 1px solid #b8e2ca;
            border-radius: 10px;
            color: #276947;
            background: #f0fbf5;
            box-shadow: 0 13px 35px rgba(31, 62, 90, 0.15);
            font-size: 9px;
            line-height: 1.5;
        }

        .toast-message.show {
            display: flex;
        }

        .toast-message i {
            color: var(--success);
            font-size: 19px;
        }

        /* FOOTER */

        .footer {
            margin-top: auto;
            border-top: 1px solid var(--border);
            background: #eef3f8;
        }

        .footer-container {
            width: 100%;
            max-width: 1180px;
            min-height: 68px;
            display: flex;
            align-items: center;
            margin: 0 auto;
            padding: 15px 26px;
        }

        .footer-brand {
            color: #75889b;
            font-size: 7.5px;
            line-height: 1.6;
        }

        .footer-brand strong {
            display: block;
            color: #405974;
            font-size: 8.5px;
        }

        .help-button {
            position: fixed;
            right: 13px;
            bottom: 13px;
            width: 27px;
            height: 27px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 0;
            border-radius: 50%;
            color: #ffffff;
            background: #22272e;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.22);
            font-size: 12px;
        }

        @media (max-width: 720px) {
            .dashboard-header {
                padding: 0 14px;
            }

            .sidebar-toggle {
                display: flex;
            }

            .header-user-text {
                display: none;
            }

            .page-container {
                padding: 15px;
            }

            .upload-form,
            .card-header {
                padding: 17px;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }

            .form-group.full-width {
                grid-column: auto;
            }

            .upload-zone {
                min-height: 190px;
                padding: 24px 18px;
            }

            .selected-file {
                align-items: flex-start;
            }

            .selected-file-name {
                max-width: 220px;
            }

            .save-button {
                width: 100%;
            }

            .footer-container {
                padding: 15px 14px;
            }
        }
    </style>
</head>

<body>

    @php
        $userName = data_get($user ?? null, 'name', session('user_name', 'M Dhimas Hafizh'));

        $jabatanName = data_get($user ?? null, 'jabatan.jabatan_name', session('jabatan_name', 'Perencana Ahli Muda'));

        $initials = collect(explode(' ', $userName))
            ->filter()
            ->take(2)
            ->map(fn($word) => strtoupper(substr($word, 0, 1)))
            ->implode('');
    @endphp

    <div class="app-shell">

        @include('partials.sidebar', [
            'activeMenu' => 'upload-rka',
            'sidebarUserName' => $userName,
            'sidebarUserRole' => $jabatanName,
            'sidebarInitials' => $initials,
        ])

        <div class="app-main">

            <header class="dashboard-header">

                <div class="header-left">

                    <button type="button" class="sidebar-toggle" id="sidebarToggle" aria-label="Buka menu navigasi"
                        aria-expanded="false">
                        <i class="bi bi-list"></i>
                    </button>

                    <div class="header-copy">
                        <div class="header-eyebrow">
                            SISTEM INFORMASI PENELITIAN RKA-K/L
                        </div>

                        <div class="header-title">
                            Upload RKA
                        </div>
                    </div>

                </div>

                <div class="header-user">

                    <div class="header-user-text">
                        Biro Perencanaan

                        <strong>
                            {{ $userName }}
                        </strong>
                    </div>

                    <div class="header-avatar">
                        {{ $initials ?: 'US' }}
                    </div>

                </div>

            </header>

            <main class="page-container">

                <section class="upload-card">

                    <div class="card-header">

                        <div class="card-header-icon">
                            <i class="bi bi-file-earmark-excel-fill"></i>
                        </div>

                        <div>
                            <h1 class="card-title">
                                Upload Dokumen RKA
                            </h1>

                            <p class="card-description">
                                Unggah dokumen RKA/Kertas Kerja Satker dalam format Excel (.xlsx atau .xls).
                            </p>
                        </div>

                    </div>

                    <form id="uploadRkaForm" class="upload-form" action="{{ route('upload.rka.store') }}" method="POST"
                        enctype="multipart/form-data" novalidate>
                        @csrf

                        <div class="upload-zone" id="rkaDropzone" tabindex="0" role="button"
                            aria-label="Pilih atau jatuhkan file Excel RKA">

                            <input type="file" id="rkaFile" name="rka_file" class="file-input"
                                accept=".xlsx,.xls" aria-describedby="fileNote fileError">

                            <div>

                                <div class="upload-icon">
                                    <i class="bi bi-file-earmark-excel-fill"></i>
                                </div>

                                <h2 class="upload-title">
                                    Upload RKA / Kertas Kerja Satker
                                </h2>

                                <p class="upload-description">
                                    Seret dan lepaskan file di sini, atau
                                </p>

                                <button type="button" class="choose-file-button" id="chooseFileButton">
                                    Pilih Excel
                                </button>

                                <div class="selected-file" id="selectedFile">

                                    <div class="selected-file-info">

                                        <i class="bi bi-file-earmark-excel-fill"></i>

                                        <div>
                                            <div class="selected-file-name" id="selectedFileName"></div>

                                            <div class="selected-file-size" id="selectedFileSize"></div>
                                        </div>

                                    </div>

                                    <button type="button" class="remove-file-button" id="removeFileButton"
                                        aria-label="Hapus file">
                                        <i class="bi bi-x-lg"></i>
                                    </button>

                                </div>

                            </div>

                        </div>

                        <p class="file-note" id="fileNote">
                            File yang dapat diunggah hanya berformat Excel (.xlsx atau .xls).
                        </p>

                        <div class="error-message" id="fileError">
                            File harus berformat Excel (.xlsx atau .xls).
                        </div>

                        <div class="form-divider"></div>

                        <div class="form-grid">

                            <div class="form-group full-width">

                                <label for="rkaName" class="form-label">
                                    Nama File RKA
                                    <span class="required">*</span>
                                </label>

                                <input type="text" id="rkaName" name="rka_name" class="form-control"
                                    placeholder="Masukkan nama file RKA" maxlength="150" autocomplete="off">

                                <div class="error-message" id="rkaNameError">
                                    Nama file RKA wajib diisi.
                                </div>

                            </div>

                            <div class="form-group">
                                <label for="eselon1" class="form-label">
                                    Unit Eselon 1 <span class="required">*</span>
                                </label>
                                <select id="eselon1" name="kode_unit_eselon1" class="form-control">
                                    <option value="">-- Pilih Unit Eselon 1 --</option>
                                    @foreach ($unitEselon1 as $e1)
                                        <option value="{{ $e1->kode_unit_eselon1 }}"
                                            {{ old('kode_unit_eselon1') == $e1->kode_unit_eselon1 ? 'selected' : '' }}>
                                            {{ $e1->kode_unit_eselon1 }} - {{ $e1->nama_unit_eselon1 }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="error-message" id="eselon1Error">
                                    Unit Eselon 1 wajib dipilih.
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="eselon2" class="form-label">
                                    Unit Eselon 2 <span class="required">*</span>
                                </label>
                                <select id="eselon2" name="kode_unit_eselon2" class="form-control" disabled>
                                    <option value="">-- Pilih Unit Eselon 1 Terlebih Dahulu --</option>
                                </select>
                                <div class="error-message" id="eselon2Error">
                                    Unit Eselon 2 wajib dipilih.
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="satker" class="form-label">
                                    Satker <span class="required">*</span>
                                </label>
                                <select id="satker" name="kode_satker" class="form-control" disabled>
                                    <option value="">-- Pilih Unit Eselon 2 Terlebih Dahulu --</option>
                                </select>
                                <div class="error-message" id="satkerError">
                                    Satker wajib dipilih.
                                </div>
                            </div>

                        </div>

                        <div class="status-list">

                            <span class="status-chip" id="fileStatus">
                                File RKA
                            </span>

                            <span class="status-chip" id="nameStatus">
                                Nama File RKA
                            </span>

                            <span class="status-chip" id="eselon1Status">
                                Unit Eselon 1
                            </span>

                            <span class="status-chip" id="eselon2Status">
                                Unit Eselon 2
                            </span>

                            <span class="status-chip" id="satkerStatus">
                                Satuan Kerja
                            </span>

                        </div>

                        <div class="form-actions">

                            <button type="submit" class="save-button" id="saveButton" disabled>
                                <i class="bi bi-floppy"></i>
                                Simpan Dokumen RKA
                            </button>

                        </div>

                    </form>

                </section>

            </main>

            <footer class="footer">

                <div class="footer-container">

                    <div class="footer-brand">

                        <strong>
                            Kementerian Perdagangan Republik Indonesia
                        </strong>

                        © 2026 Biro Perencanaan. Seluruh Hak Cipta Dilindungi.

                    </div>

                </div>

            </footer>

        </div>

    </div>

    <div class="toast-message" id="successToast">

        <i class="bi bi-check-circle-fill"></i>

        <div>
            <strong>Dokumen RKA berhasil disimpan.</strong><br>
            Penyimpanan masih berupa simulasi frontend.
        </div>

    </div>

    <button type="button" class="help-button">
        ?
    </button>

    <script>
        // ==========================================
        // 1. INISIALISASI DATA & ELEMEN DOM
        // ==========================================

        // Konversi data PHP dari Controller ke JSON
        const allEselon2 = @json($unitEselon2);
        const allSatker = @json($satker);

        const form = document.getElementById("uploadRkaForm");
        const fileInput = document.getElementById("rkaFile");
        const dropzone = document.getElementById("rkaDropzone");
        const chooseFileButton = document.getElementById("chooseFileButton");
        const removeFileButton = document.getElementById("removeFileButton");
        const selectedFile = document.getElementById("selectedFile");
        const selectedFileName = document.getElementById("selectedFileName");
        const selectedFileSize = document.getElementById("selectedFileSize");
        const fileError = document.getElementById("fileError");

        const rkaName = document.getElementById("rkaName");
        const saveButton = document.getElementById("saveButton");

        // Dropdown Elemen
        const eselon1Select = document.getElementById("eselon1");
        const eselon2Select = document.getElementById("eselon2");
        const satkerSelect = document.getElementById("satker");

        let selectedRkaFile = null;

        // ==========================================
        // 2. LOGIKA CASCADING DROPDOWN (DATABASE)
        // ==========================================

        eselon1Select.addEventListener("change", function() {
            const selectedEselon1 = this.value;

            // Reset Dropdown Eselon 2 & Satker
            eselon2Select.innerHTML = '<option value="">-- Pilih Unit Eselon 2 --</option>';
            satkerSelect.innerHTML = '<option value="">-- Pilih Unit Eselon 2 Terlebih Dahulu --</option>';
            satkerSelect.disabled = true;

            if (selectedEselon1) {
                const filteredEselon2 = allEselon2.filter(e2 => e2.kode_unit_eselon1 === selectedEselon1);
                filteredEselon2.forEach(e2 => {
                    const option = document.createElement("option");
                    option.value = e2.kode_unit_eselon2;
                    option.textContent = `${e2.kode_unit_eselon2} - ${e2.nama_unit_eselon2}`;
                    eselon2Select.appendChild(option);
                });
                eselon2Select.disabled = false;
            } else {
                eselon2Select.disabled = true;
                eselon2Select.innerHTML = '<option value="">-- Pilih Unit Eselon 1 Terlebih Dahulu --</option>';
            }
            updateFormState();
        });

        eselon2Select.addEventListener("change", function() {
            const selectedEselon2 = this.value;

            // Reset Dropdown Satker
            satkerSelect.innerHTML = '<option value="">-- Pilih Satker --</option>';

            if (selectedEselon2) {
                const filteredSatker = allSatker.filter(s => s.kode_unit_eselon2 === selectedEselon2);
                filteredSatker.forEach(s => {
                    const option = document.createElement("option");
                    option.value = s.kode_satker;
                    option.textContent = `${s.kode_satker} - ${s.nama_satker}`;
                    satkerSelect.appendChild(option);
                });
                satkerSelect.disabled = false;
            } else {
                satkerSelect.disabled = true;
                satkerSelect.innerHTML = '<option value="">-- Pilih Unit Eselon 2 Terlebih Dahulu --</option>';
            }
            updateFormState();
        });

        satkerSelect.addEventListener("change", function() {
            updateFormState();
        });


        // ==========================================
        // 3. LOGIKA FILE UPLOAD (KHUSUS EXCEL)
        // ==========================================

        function isExcelFile(file) {
            if (!file) return false;
            const name = file.name.toLowerCase();
            return name.endsWith(".xlsx") || name.endsWith(".xls");
        }

        function formatFileSize(bytes) {
            if (bytes < 1024) return `${bytes} Bytes`;
            if (bytes < 1024 * 1024) return `${(bytes / 1024).toFixed(1)} KB`;
            return `${(bytes / (1024 * 1024)).toFixed(1)} MB`;
        }

        function assignFile(file) {
            dropzone.classList.remove("has-error", "has-file");
            fileError.classList.remove("show");

            if (!isExcelFile(file)) {
                selectedRkaFile = null;
                fileInput.value = "";
                selectedFile.classList.remove("show");
                dropzone.classList.add("has-error");
                fileError.classList.add("show");
                fileError.textContent = "File harus berformat Excel (.xlsx atau .xls).";
                updateFormState();
                return;
            }

            selectedRkaFile = file;
            try {
                const transfer = new DataTransfer();
                transfer.items.add(file);
                fileInput.files = transfer.files;
            } catch (error) {
                console.warn(error);
            }

            selectedFileName.textContent = file.name;
            selectedFileSize.textContent = `${formatFileSize(file.size)} · Excel`;
            selectedFile.classList.add("show");
            dropzone.classList.add("has-file");

            // Isi otomatis nama RKA dari nama file bila kolom masih kosong.
            if (!rkaName.value.trim()) {
                rkaName.value = file.name.replace(/\.(xlsx|xls)$/i, "");
            }

            updateFormState();
        }

        function clearFile() {
            selectedRkaFile = null;
            fileInput.value = "";
            selectedFileName.textContent = "";
            selectedFileSize.textContent = "";
            selectedFile.classList.remove("show");
            dropzone.classList.remove("has-file", "has-error");
            fileError.classList.remove("show");
            updateFormState();
        }

        chooseFileButton.addEventListener("click", event => {
            event.stopPropagation();
            fileInput.click();
        });

        dropzone.addEventListener("click", event => {
            if (event.target.closest("#removeFileButton") || event.target.closest("#chooseFileButton")) return;
            fileInput.click();
        });

        dropzone.addEventListener("keydown", event => {
            if (event.key === "Enter" || event.key === " ") {
                event.preventDefault();
                fileInput.click();
            }
        });

        fileInput.addEventListener("change", () => {
            assignFile(fileInput.files[0]);
        });

        removeFileButton.addEventListener("click", event => {
            event.stopPropagation();
            clearFile();
        });

        dropzone.addEventListener("dragover", event => {
            event.preventDefault();
            dropzone.classList.add("dragover");
        });

        dropzone.addEventListener("dragleave", () => {
            dropzone.classList.remove("dragover");
        });

        dropzone.addEventListener("drop", event => {
            event.preventDefault();
            dropzone.classList.remove("dragover");

            if (event.dataTransfer.files.length !== 1) {
                clearFile();
                dropzone.classList.add("has-error");
                fileError.textContent = "Unggah satu file Excel saja.";
                fileError.classList.add("show");
                return;
            }

            assignFile(event.dataTransfer.files[0]);
        });


        // ==========================================
        // 4. VALIDASI & SUBMIT FORM
        // ==========================================

        function setStatus(id, complete) {
            const el = document.getElementById(id);
            if (el) el.classList.toggle("complete", complete);
        }

        function setFieldValidation(field, errorId, valid, showErrors = false) {
            field.classList.toggle("is-valid", valid);
            field.classList.toggle("is-invalid", showErrors && !valid);

            const errorElement = document.getElementById(errorId);
            if (errorElement) {
                errorElement.classList.toggle("show", showErrors && !valid);
            }
        }

        function updateFormState(showErrors = false) {
            const fileValid = selectedRkaFile !== null && isExcelFile(selectedRkaFile);
            const nameValid = rkaName.value.trim() !== "";
            const eselon1Valid = eselon1Select.value !== "";
            const eselon2Valid = eselon2Select.value !== "";
            const satkerValid = satkerSelect.value !== "";

            setFieldValidation(rkaName, "rkaNameError", nameValid, showErrors);
            setFieldValidation(eselon1Select, "eselon1Error", eselon1Valid, showErrors);
            setFieldValidation(eselon2Select, "eselon2Error", eselon2Valid, showErrors);
            setFieldValidation(satkerSelect, "satkerError", satkerValid, showErrors);

            if (showErrors && !fileValid) {
                dropzone.classList.add("has-error");
                fileError.textContent = "File Excel wajib dipilih.";
                fileError.classList.add("show");
            }

            setStatus("fileStatus", fileValid);
            setStatus("nameStatus", nameValid);
            setStatus("eselon1Status", eselon1Valid);
            setStatus("eselon2Status", eselon2Valid);
            setStatus("satkerStatus", satkerValid);

            const valid = fileValid && nameValid && eselon1Valid && eselon2Valid && satkerValid;
            saveButton.disabled = !valid;

            return valid;
        }

        rkaName.addEventListener("input", () => {
            updateFormState();
        });

        // Jalankan satu kali di awal untuk memastikan state tombol tersinkron
        updateFormState();

        form.addEventListener("submit", event => {
            // Hapus preventDefault jika Anda ingin form langsung tersubmit ke Laravel Controller
            // Jika Anda memakai ajax, biarkan preventDefault dan atur ajax-nya di bawah
            if (!updateFormState(true)) {
                event.preventDefault(); // Cegah submit jika belum valid
                return;
            }

            saveButton.disabled = true;
            saveButton.innerHTML = `<i class="bi bi-arrow-repeat"></i> Menyimpan...`;

            // Proses submit akan diteruskan ke Laravel
        });
    </script>

</body>

</html>
