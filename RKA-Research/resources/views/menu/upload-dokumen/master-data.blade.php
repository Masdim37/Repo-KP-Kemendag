<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Upload Master Data | Penelitian RKA-K/L</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --primary: #1b63f0;
            --primary-dark: #0d4fd5;
            --primary-soft: #eef5ff;
            --sidebar-blue: #234493;

            --success: #16a765;
            --success-soft: #effaf5;

            --danger: #df3d4e;
            --danger-soft: #fff1f3;

            --text-primary: #18365b;
            --text-secondary: #607995;
            --text-muted: #9aabc0;

            --background: #f2f6fb;
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
        input {
            font: inherit;
        }

        button {
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

        /* ==================================================
           HEADER
        ================================================== */

        .dashboard-header {
            position: sticky;
            top: 0;
            z-index: 900;
            min-height: var(--header-height, 66px);
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
            border-radius: 9px;
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
            font-size: 7.5px;
            font-weight: 700;
            letter-spacing: 0.8px;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .header-title {
            margin-top: 3px;
            overflow: hidden;
            color: var(--text-primary);
            font-size: 14px;
            font-weight: 800;
            line-height: 1.3;
            text-overflow: ellipsis;
            white-space: nowrap;
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
            font-size: 8.5px;
            line-height: 1.4;
        }

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
            color: #ffffff;
            background: linear-gradient(135deg, #063c7c, #1681d5);
            box-shadow: 0 5px 12px rgba(31, 91, 148, 0.16);
            font-size: 10px;
            font-weight: 800;
        }

        /* ==================================================
           KONTEN
        ================================================== */

        .page-container {
            width: 100%;
            min-height: 0;
            flex: 1;
            padding: 24px;
            background: var(--background);
        }

        .upload-master-card {
            width: 100%;
            max-width: 1280px;
            margin: 0 auto;
            border: 1px solid var(--border);
            border-radius: 15px;
            background: var(--white);
            box-shadow: 0 7px 24px rgba(39, 69, 105, 0.06);
            overflow: hidden;
        }

        .master-card-header {
            display: flex;
            align-items: center;
            gap: 13px;
            padding: 20px 23px;
            border-bottom: 1px solid #e5ebf2;
        }

        .master-header-icon {
            width: 35px;
            height: 35px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            border-radius: 10px;
            color: var(--primary);
            background: var(--primary-soft);
            font-size: 15px;
        }

        .master-card-title {
            color: var(--text-primary);
            font-size: 14px;
            font-weight: 800;
        }

        .master-card-description {
            margin-top: 3px;
            color: #92a3b7;
            font-size: 9px;
        }

        .master-form {
            padding: 23px;
        }

        .upload-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 30px;
        }

        .upload-column {
            min-width: 0;
        }

        /* ==================================================
           DROPZONE
        ================================================== */

        .upload-zone {
            position: relative;
            min-height: 205px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            border: 2px dashed #65a1ff;
            border-radius: 13px;
            background: #f8fbff;
            text-align: center;
            cursor: pointer;
            transition: 0.2s ease;
        }

        .upload-zone:hover,
        .upload-zone.dragover {
            border-color: var(--primary);
            background: #f0f6ff;
            box-shadow: 0 0 0 4px rgba(27, 99, 240, 0.06);
        }

        .upload-zone.has-file {
            border-color: var(--success);
            background: var(--success-soft);
        }

        .upload-zone.has-error {
            border-color: var(--danger);
            background: var(--danger-soft);
        }

        .upload-zone-content {
            width: 100%;
        }

        .upload-icon {
            width: 49px;
            height: 49px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 14px;
            border-radius: 13px;
            color: var(--primary);
            background: #e6f0ff;
            font-size: 20px;
        }

        .upload-zone.has-file .upload-icon {
            color: var(--success);
            background: #dff5e9;
        }

        .upload-title {
            color: var(--text-primary);
            font-size: 12px;
            font-weight: 800;
        }

        .upload-description {
            margin-top: 5px;
            color: #91a4bb;
            font-size: 9px;
            line-height: 1.5;
        }

        .choose-file-button {
            min-width: 100px;
            height: 35px;
            margin-top: 13px;
            padding: 0 17px;
            border: none;
            border-radius: 9px;
            color: #ffffff;
            background: var(--primary);
            box-shadow: 0 5px 12px rgba(27, 99, 240, 0.2);
            font-size: 9px;
            font-weight: 700;
        }

        .choose-file-button:hover {
            background: var(--primary-dark);
        }

        .file-input {
            position: absolute;
            width: 1px;
            height: 1px;
            overflow: hidden;
            opacity: 0;
            pointer-events: none;
        }

        .selected-file {
            display: none;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-top: 13px;
            padding: 10px 12px;
            border: 1px solid #bfe4cf;
            border-radius: 9px;
            color: #31754f;
            background: #f1fbf5;
            text-align: left;
        }

        .selected-file.show {
            display: flex;
        }

        .selected-file-info {
            min-width: 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .selected-file-info i {
            flex-shrink: 0;
            color: var(--success);
            font-size: 15px;
        }

        .selected-file-name {
            overflow: hidden;
            font-size: 9px;
            font-weight: 700;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .remove-file-button {
            width: 27px;
            height: 27px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            border: none;
            border-radius: 7px;
            color: var(--danger);
            background: #ffffff;
        }

        .file-note {
            display: flex;
            align-items: flex-start;
            gap: 5px;
            margin-top: 10px;
            color: #8fa1b7;
            font-size: 8px;
            line-height: 1.5;
        }

        .file-note::before {
            content: "*";
            color: var(--primary);
            font-weight: 800;
        }

        .file-error {
            display: none;
            margin-top: 7px;
            color: var(--danger);
            font-size: 8px;
        }

        .file-error.show {
            display: block;
        }

        /* ==================================================
           INPUT NAMA FILE
        ================================================== */

        .form-group {
            margin-top: 17px;
        }

        .form-label {
            display: block;
            margin-bottom: 7px;
            color: #324d6e;
            font-size: 9px;
            font-weight: 700;
        }

        .required {
            color: var(--danger);
        }

        .form-input {
            width: 100%;
            height: 42px;
            padding: 0 13px;
            border: 1px solid #d3dde8;
            border-radius: 9px;
            outline: none;
            color: var(--text-primary);
            background: #ffffff;
            font-size: 10px;
            transition: 0.2s ease;
        }

        .form-input::placeholder {
            color: #a3b2c4;
        }

        .form-input:hover {
            border-color: #adc0d4;
        }

        .form-input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(27, 99, 240, 0.08);
        }

        .form-input.is-valid {
            border-color: var(--success);
        }

        .form-input.is-invalid {
            border-color: var(--danger);
        }

        .input-error {
            display: none;
            margin-top: 6px;
            color: var(--danger);
            font-size: 8px;
        }

        .input-error.show {
            display: block;
        }

        /* ==================================================
           STATUS DAN SIMPAN
        ================================================== */

        .form-status-row {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 20px;
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
            font-weight: 600;
        }

        .status-chip::before {
            content: "";
            width: 5px;
            height: 5px;
            border-radius: 50%;
            background: #c7d0db;
        }

        .status-chip.complete {
            color: #268050;
            border-color: #bee6d0;
            background: #f1fbf5;
        }

        .status-chip.complete::before {
            background: var(--success);
        }

        .form-actions {
            display: flex;
            justify-content: flex-end;
            margin-top: 22px;
            padding-top: 19px;
            border-top: 1px solid #e5ebf2;
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
            color: #ffffff;
            background: var(--primary);
            box-shadow: 0 7px 16px rgba(27, 99, 240, 0.2);
            font-size: 9px;
            font-weight: 700;
            transition: 0.2s ease;
        }

        .save-button:hover:not(:disabled) {
            background: var(--primary-dark);
            transform: translateY(-1px);
        }

        .save-button:disabled {
            color: #9cacbd;
            background: #e3eaf2;
            box-shadow: none;
            cursor: not-allowed;
        }

        /* Loading Spinner */
        @keyframes spin {
            100% { transform: rotate(360deg); }
        }
        .bi-arrow-repeat.spin {
            animation: spin 1s linear infinite;
        }

        /* ==================================================
           NOTIFIKASI
        ================================================== */

        .toast {
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
            font-size: 10px;
            line-height: 1.5;
        }

        .toast.toast-error {
            border-color: #f5c6cb;
            color: #721c24;
            background: #f8d7da;
        }

        .toast.show {
            display: flex;
            animation: toastSlide 0.25s ease;
        }

        .toast i {
            margin-top: 1px;
            color: var(--success);
            font-size: 15px;
        }
        
        .toast.toast-error i {
            color: var(--danger);
        }

        @keyframes toastSlide {
            from {
                opacity: 0;
                transform: translateY(-8px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* ==================================================
           FOOTER
        ================================================== */

        .footer {
            margin-top: auto;
            border-top: 1px solid var(--border);
            background: #eef3f8;
        }

        .footer-container {
            width: 100%;
            max-width: 1280px;
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

        /* ==================================================
           RESPONSIVE
        ================================================== */

        @media (max-width: 900px) {
            .upload-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 680px) {
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

            .master-form,
            .master-card-header {
                padding: 17px;
            }

            .upload-zone {
                min-height: 185px;
            }

            .form-actions {
                justify-content: stretch;
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
        /*
        |--------------------------------------------------------------------------
        | DATA DASAR TEMPLATE
        |--------------------------------------------------------------------------
        | Controller dapat mengirim $user.
        */

        $userName = data_get($user ?? null, 'name', session('user_name', 'Ahmad Rizaldi'));

        $jabatanName = data_get($user ?? null, 'jabatan.jabatan_name', session('jabatan_name', 'Perencana Ahli Muda'));

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
                            Dashboard
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

                <section class="upload-master-card">

                    <div class="master-card-header">

                        <div class="master-header-icon">
                            <i class="bi bi-upload"></i>
                        </div>

                        <div>
                            <h1 class="master-card-title">
                                Upload Master Data
                            </h1>

                            <p class="master-card-description">
                                Unggah file Renja dan/atau RKBMN dalam format Excel/CSV.
                            </p>
                        </div>

                    </div>

                    <form id="masterDataForm" class="master-form" action="{{ route('upload.masterdata.store') }}" method="POST"
                        enctype="multipart/form-data" novalidate>
                        @csrf

                        <div class="upload-grid">

                            <div class="upload-column">

                                <div class="upload-zone" id="renjaDropzone" data-input="renjaFile" tabindex="0"
                                    role="button" aria-label="Pilih file Renja">

                                    <input type="file" id="renja_file" name="renja_file" class="file-input"
                                        accept=".xlsx,.xls,.csv">

                                    <div class="upload-zone-content">

                                        <div class="upload-icon">
                                            <i class="bi bi-file-earmark-spreadsheet"></i>
                                        </div>

                                        <h2 class="upload-title">
                                            Upload Renja
                                        </h2>

                                        <p class="upload-description">
                                            Seret dan lepaskan file di sini,
                                            atau
                                        </p>

                                        <button type="button" class="choose-file-button" data-file-button="renja_file">
                                            Pilih File
                                        </button>

                                        <div class="selected-file" id="renjaSelectedFile">

                                            <div class="selected-file-info">
                                                <i class="bi bi-file-earmark-excel-fill"></i>

                                                <span class="selected-file-name" id="renjaSelectedName"></span>
                                            </div>

                                            <button type="button" class="remove-file-button" data-remove-file="renja"
                                                aria-label="Hapus file Renja">
                                                <i class="bi bi-x-lg"></i>
                                            </button>

                                        </div>

                                    </div>

                                </div>

                                <p class="file-note">
                                    File hanya dapat menggunakan format Excel atau csv
                                    (.xlsx|.xls|.csv).
                                </p>

                                <div class="file-error" id="renjaFileError">
                                    File Renja harus berformat Excel atau csv
                                    (.xlsx|.xls|.csv).
                                </div>
                                @error('renja_file')
                                    <div class="input-error show">{{ $message }}</div>
                                @enderror

                                <div class="form-group">

                                    <label for="renjaName" class="form-label">
                                        Nama File Renja
                                        <span class="required" id="renjaNameAst">*</span>
                                    </label>

                                    <input type="text" id="renjaName" name="renja_name" class="form-input"
                                        placeholder="Masukkan nama file Renja" maxlength="150" autocomplete="off" value="{{ old('renja_name') }}">

                                    <div class="input-error" id="renjaNameError">
                                        Nama file Renja wajib diisi jika file diunggah.
                                    </div>
                                    @error('renja_name')
                                        <div class="input-error show">{{ $message }}</div>
                                    @enderror

                                </div>

                            </div>

                            <div class="upload-column">

                                <div class="upload-zone" id="rkbmnDropzone" data-input="rkbmnFile" tabindex="0"
                                    role="button" aria-label="Pilih file RKBMN">

                                    <input type="file" id="rkbmn_file" name="rkbmn_file" class="file-input"
                                        accept=".xlsx,.xls,.csv">

                                    <div class="upload-zone-content">

                                        <div class="upload-icon">
                                            <i class="bi bi-file-earmark-spreadsheet"></i>
                                        </div>

                                        <h2 class="upload-title">
                                            Upload RKBMN
                                        </h2>

                                        <p class="upload-description">
                                            Seret dan lepaskan file di sini,
                                            atau
                                        </p>

                                        <button type="button" class="choose-file-button"
                                            data-file-button="rkbmn_file">
                                            Pilih File
                                        </button>

                                        <div class="selected-file" id="rkbmnSelectedFile">

                                            <div class="selected-file-info">
                                                <i class="bi bi-file-earmark-excel-fill"></i>

                                                <span class="selected-file-name" id="rkbmnSelectedName"></span>
                                            </div>

                                            <button type="button" class="remove-file-button"
                                                data-remove-file="rkbmn" aria-label="Hapus file RKBMN">
                                                <i class="bi bi-x-lg"></i>
                                            </button>

                                        </div>

                                    </div>

                                </div>

                                <p class="file-note">
                                    File hanya dapat menggunakan format Excel atau csv
                                    (.xlsx|.xls|.csv).
                                </p>

                                <div class="file-error" id="rkbmnFileError">
                                    File RKBMN harus berformat Excel atau csv
                                    (.xlsx|.xls|.csv).
                                </div>
                                @error('rkbmn_file')
                                    <div class="input-error show">{{ $message }}</div>
                                @enderror

                                <div class="form-group">

                                    <label for="rkbmnName" class="form-label">
                                        Nama File RKBMN
                                        <span class="required" id="rkbmnNameAst">*</span>
                                    </label>

                                    <input type="text" id="rkbmnName" name="rkbmn_name" class="form-input"
                                        placeholder="Masukkan nama file RKBMN" maxlength="150" autocomplete="off" value="{{ old('rkbmn_name') }}">

                                    <div class="input-error" id="rkbmnNameError">
                                        Nama file RKBMN wajib diisi jika file diunggah.
                                    </div>
                                    @error('rkbmn_name')
                                        <div class="input-error show">{{ $message }}</div>
                                    @enderror

                                </div>

                            </div>

                        </div>

                        <div class="form-status-row">

                            <span class="status-chip" id="renjaFileStatus">
                                File Renja
                            </span>

                            <span class="status-chip" id="renjaNameStatus">
                                Nama File Renja
                            </span>

                            <span class="status-chip" id="rkbmnFileStatus">
                                File RKBMN
                            </span>

                            <span class="status-chip" id="rkbmnNameStatus">
                                Nama File RKBMN
                            </span>

                        </div>

                        <div class="form-actions">

                            <button type="submit" class="save-button" id="saveMasterButton" disabled>
                                <i class="bi bi-floppy"></i>
                                Simpan Master Data
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
                        © 2026 Biro Perencanaan.
                        Seluruh Hak Cipta Dilindungi.
                    </div>
                </div>
            </footer>

        </div>
    </div>

    <div class="toast" id="successToast">
        <i class="bi bi-check-circle-fill"></i>
        <div id="toastMessage">
            <strong>Berhasil!</strong><br>
            Master data berhasil disimpan.
        </div>
    </div>

    <div class="toast toast-error" id="errorToast">
        <i class="bi bi-exclamation-triangle-fill"></i>
        <div id="errorToastMessage">
            <strong>Gagal!</strong><br>
            Terjadi kesalahan saat memproses data.
        </div>
    </div>

    @if(session('success'))
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const toast = document.getElementById("successToast");
            document.getElementById("toastMessage").innerHTML = "<strong>Berhasil!</strong><br>{{ session('success') }}";
            toast.classList.add("show");
            setTimeout(() => toast.classList.remove("show"), 4000);
        });
    </script>
    @endif

    @if(session('error'))
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const toast = document.getElementById("errorToast");
            document.getElementById("errorToastMessage").innerHTML = "<strong>Gagal!</strong><br>{{ session('error') }}";
            toast.classList.add("show");
            setTimeout(() => toast.classList.remove("show"), 4000);
        });
    </script>
    @endif

    <script>
        const masterDataForm = document.getElementById("masterDataForm");
        const saveMasterButton = document.getElementById("saveMasterButton");

        const renjaName = document.getElementById("renjaName");
        const rkbmnName = document.getElementById("rkbmnName");

        // Menyimpan status file apa saja yang sudah di-upload user
        const uploadState = {
            renja: null,
            rkbmn: null
        };

        const uploadConfig = {
            renja: {
                input: document.getElementById("renja_file"), // FIX: Sesuaikan dengan ID di HTML
                dropzone: document.getElementById("renjaDropzone"),
                selectedBox: document.getElementById("renjaSelectedFile"),
                selectedName: document.getElementById("renjaSelectedName"),
                error: document.getElementById("renjaFileError"),
                status: document.getElementById("renjaFileStatus"),
                nameAsterisk: document.getElementById("renjaNameAst")
            },

            rkbmn: {
                input: document.getElementById("rkbmn_file"), // FIX: Sesuaikan dengan ID di HTML
                dropzone: document.getElementById("rkbmnDropzone"),
                selectedBox: document.getElementById("rkbmnSelectedFile"),
                selectedName: document.getElementById("rkbmnSelectedName"),
                error: document.getElementById("rkbmnFileError"),
                status: document.getElementById("rkbmnFileStatus"),
                nameAsterisk: document.getElementById("rkbmnNameAst")
            }
        };

        // Fungsi Cek Ekstensi
        function isValidExtension(file, allowedExtensions) {
            if (!file) return false;
            const ext = file.name.split('.').pop().toLowerCase();
            return allowedExtensions.includes(ext);
        }

        function assignFileToInput(input, file) {
            try {
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);
                input.files = dataTransfer.files;
            } catch (error) {
                console.warn("Browser tidak mendukung pengisian input file otomatis.", error);
            }
        }

        function setSelectedFile(type, file) {
            const config = uploadConfig[type];

            config.dropzone.classList.remove("has-file", "has-error");
            config.error.classList.remove("show");

            // Memastikan kedua input mendukung excel dan csv (Sesuai validasi backend)
            if (!isValidExtension(file, ['xlsx', 'xls', 'csv'])) {
                uploadState[type] = null;
                config.input.value = "";
                config.selectedBox.classList.remove("show");
                config.selectedName.textContent = "";
                config.dropzone.classList.add("has-error");
                config.error.classList.add("show");

                updateFormState();
                return;
            }

            uploadState[type] = file;
            assignFileToInput(config.input, file);

            config.selectedName.textContent = file.name;
            config.selectedBox.classList.add("show");
            config.dropzone.classList.add("has-file");

            updateFormState();
        }

        function removeSelectedFile(type) {
            const config = uploadConfig[type];

            uploadState[type] = null;
            config.input.value = "";
            config.selectedName.textContent = "";
            config.selectedBox.classList.remove("show");

            config.dropzone.classList.remove("has-file", "has-error");
            config.error.classList.remove("show");

            updateFormState();
        }

        // Setup Event Listener Dropzone
        Object.entries(uploadConfig).forEach(([type, config]) => {
            config.input.addEventListener("change", function() {
                if(this.files[0]) {
                    setSelectedFile(type, this.files[0]);
                }
            });

            config.dropzone.addEventListener("click", event => {
                if (event.target.closest(".remove-file-button")) return;
                config.input.click();
            });

            config.dropzone.addEventListener("keydown", event => {
                if (event.key === "Enter" || event.key === " ") {
                    event.preventDefault();
                    config.input.click();
                }
            });

            config.dropzone.addEventListener("dragover", event => {
                event.preventDefault();
                config.dropzone.classList.add("dragover");
            });

            config.dropzone.addEventListener("dragleave", () => {
                config.dropzone.classList.remove("dragover");
            });

            config.dropzone.addEventListener("drop", event => {
                event.preventDefault();
                config.dropzone.classList.remove("dragover");

                if (event.dataTransfer.files[0]) {
                    setSelectedFile(type, event.dataTransfer.files[0]);
                }
            });
        });

        // Event handler button file manual (Fix dataset call)
        document.querySelectorAll("[data-file-button]").forEach(button => {
            button.addEventListener("click", event => {
                event.stopPropagation();
                const input = document.getElementById(button.dataset.fileButton);
                if(input) input.click();
            });
        });

        // Event handler remove button
        document.querySelectorAll("[data-remove-file]").forEach(button => {
            button.addEventListener("click", event => {
                event.stopPropagation();
                removeSelectedFile(button.dataset.removeFile);
            });
        });

        function updateStatusChip(element, complete) {
            element.classList.toggle("complete", complete);
        }

        // =========================================================
        // LOGIKA UTAMA (Form Dinamis untuk 1 atau 2 file)
        // =========================================================
        function updateFormState(showErrors = false) {
            const renjaFileValid = uploadState.renja !== null;
            const rkbmnFileValid = uploadState.rkbmn !== null;

            const renjaNameHasValue = renjaName.value.trim().length > 0;
            const rkbmnNameHasValue = rkbmnName.value.trim().length > 0;

            // Logic: Tanda bintang (Asterisk) di label nama file hanya muncul jika file di-upload
            uploadConfig.renja.nameAsterisk.style.display = renjaFileValid ? "inline" : "none";
            uploadConfig.rkbmn.nameAsterisk.style.display = rkbmnFileValid ? "inline" : "none";

            // Validasi: Nama hanya diwajibkan jika file tsb diupload
            const renjaNameValid = !renjaFileValid || renjaNameHasValue;
            const rkbmnNameValid = !rkbmnFileValid || rkbmnNameHasValue;

            // Toggle CSS Error untuk Input Name jika file ada tapi namanya kosong
            renjaName.classList.toggle("is-invalid", showErrors && renjaFileValid && !renjaNameHasValue);
            document.getElementById("renjaNameError").classList.toggle("show", showErrors && renjaFileValid && !renjaNameHasValue);
            
            rkbmnName.classList.toggle("is-invalid", showErrors && rkbmnFileValid && !rkbmnNameHasValue);
            document.getElementById("rkbmnNameError").classList.toggle("show", showErrors && rkbmnFileValid && !rkbmnNameHasValue);

            // Update Status Chips UI
            updateStatusChip(document.getElementById("renjaFileStatus"), renjaFileValid);
            // Chip nama hijau jika tidak diupload (karena gak wajib) ATAU jika diupload & ada isinya
            updateStatusChip(document.getElementById("renjaNameStatus"), renjaNameValid); 
            
            updateStatusChip(document.getElementById("rkbmnFileStatus"), rkbmnFileValid);
            updateStatusChip(document.getElementById("rkbmnNameStatus"), rkbmnNameValid);

            // FORM VALID JIKA:
            // 1. Minimal ada 1 file yang diupload (renjaFileValid atau rkbmnFileValid)
            // 2. Dan nama file terkait sudah terisi dengan benar (renjaNameValid & rkbmnNameValid)
            const atLeastOneFile = renjaFileValid || rkbmnFileValid;
            const valid = atLeastOneFile && renjaNameValid && rkbmnNameValid;

            saveMasterButton.disabled = !valid;

            return valid;
        }

        // Trigger validasi tiap kali user ngetik nama
        renjaName.addEventListener("input", () => updateFormState());
        rkbmnName.addEventListener("input", () => updateFormState());

        // Event Submit
        masterDataForm.addEventListener("submit", function(event) {
            // Jika ada data yang invalid, stop submit
            if (!updateFormState(true)) {
                event.preventDefault(); 
                return;
            }

            // Jika valid, BROWSER AKAN MENSUBMIT FORM ASLI (tanpa preventDefault)
            // Ubah tampilan tombol jadi "Menyimpan" agar user tidak klik 2 kali
            saveMasterButton.disabled = true;
            saveMasterButton.innerHTML = `<i class="bi bi-arrow-repeat spin"></i> Menyimpan...`;
        });

        // Inisialisasi pengecekan pertama kali halaman di-load
        updateFormState();
    </script>

</body>

</html>

{{-- HAHAHAHA --}}


{{-- <!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard | Penelitian RKA-K/L</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --primary: #1b63f0;
            --primary-dark: #0d4fd5;
            --primary-soft: #eef5ff;
            --sidebar-blue: #234493;

            --success: #16a765;
            --success-soft: #effaf5;

            --danger: #df3d4e;
            --danger-soft: #fff1f3;

            --text-primary: #18365b;
            --text-secondary: #607995;
            --text-muted: #9aabc0;

            --background: #f2f6fb;
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
        input {
            font: inherit;
        }

        button {
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

        /* ==================================================
           HEADER
        ================================================== */

        .dashboard-header {
            position: sticky;
            top: 0;
            z-index: 900;
            min-height: var(--header-height, 66px);
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
            border-radius: 9px;
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
            font-size: 7.5px;
            font-weight: 700;
            letter-spacing: 0.8px;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .header-title {
            margin-top: 3px;
            overflow: hidden;
            color: var(--text-primary);
            font-size: 14px;
            font-weight: 800;
            line-height: 1.3;
            text-overflow: ellipsis;
            white-space: nowrap;
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
            font-size: 8.5px;
            line-height: 1.4;
        }

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
            color: #ffffff;
            background: linear-gradient(135deg, #063c7c, #1681d5);
            box-shadow: 0 5px 12px rgba(31, 91, 148, 0.16);
            font-size: 10px;
            font-weight: 800;
        }

        /* ==================================================
           KONTEN
        ================================================== */

        .page-container {
            width: 100%;
            min-height: 0;
            flex: 1;
            padding: 24px;
            background: var(--background);
        }

        .upload-master-card {
            width: 100%;
            max-width: 1280px;
            margin: 0 auto;
            border: 1px solid var(--border);
            border-radius: 15px;
            background: var(--white);
            box-shadow: 0 7px 24px rgba(39, 69, 105, 0.06);
            overflow: hidden;
        }

        .master-card-header {
            display: flex;
            align-items: center;
            gap: 13px;
            padding: 20px 23px;
            border-bottom: 1px solid #e5ebf2;
        }

        .master-header-icon {
            width: 35px;
            height: 35px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            border-radius: 10px;
            color: var(--primary);
            background: var(--primary-soft);
            font-size: 15px;
        }

        .master-card-title {
            color: var(--text-primary);
            font-size: 14px;
            font-weight: 800;
        }

        .master-card-description {
            margin-top: 3px;
            color: #92a3b7;
            font-size: 9px;
        }

        .master-form {
            padding: 23px;
        }

        .upload-grid {
            display: grid;
            /* Mengubah grid menjadi 1 kolom agar pas dan rapi setelah RKBMN dihapus */
            grid-template-columns: 1fr;
            max-width: 600px;
            gap: 30px;
        }

        .upload-column {
            min-width: 0;
        }

        /* ==================================================
           DROPZONE
        ================================================== */

        .upload-zone {
            position: relative;
            min-height: 205px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            border: 2px dashed #65a1ff;
            border-radius: 13px;
            background: #f8fbff;
            text-align: center;
            cursor: pointer;
            transition: 0.2s ease;
        }

        .upload-zone:hover,
        .upload-zone.dragover {
            border-color: var(--primary);
            background: #f0f6ff;
            box-shadow: 0 0 0 4px rgba(27, 99, 240, 0.06);
        }

        .upload-zone.has-file {
            border-color: var(--success);
            background: var(--success-soft);
        }

        .upload-zone.has-error {
            border-color: var(--danger);
            background: var(--danger-soft);
        }

        .upload-zone-content {
            width: 100%;
        }

        .upload-icon {
            width: 49px;
            height: 49px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 14px;
            border-radius: 13px;
            color: var(--primary);
            background: #e6f0ff;
            font-size: 20px;
        }

        .upload-zone.has-file .upload-icon {
            color: var(--success);
            background: #dff5e9;
        }

        .upload-title {
            color: var(--text-primary);
            font-size: 12px;
            font-weight: 800;
        }

        .upload-description {
            margin-top: 5px;
            color: #91a4bb;
            font-size: 9px;
            line-height: 1.5;
        }

        .choose-file-button {
            min-width: 100px;
            height: 35px;
            margin-top: 13px;
            padding: 0 17px;
            border: none;
            border-radius: 9px;
            color: #ffffff;
            background: var(--primary);
            box-shadow: 0 5px 12px rgba(27, 99, 240, 0.2);
            font-size: 9px;
            font-weight: 700;
        }

        .choose-file-button:hover {
            background: var(--primary-dark);
        }

        .file-input {
            position: absolute;
            width: 1px;
            height: 1px;
            overflow: hidden;
            opacity: 0;
            pointer-events: none;
        }

        .selected-file {
            display: none;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-top: 13px;
            padding: 10px 12px;
            border: 1px solid #bfe4cf;
            border-radius: 9px;
            color: #31754f;
            background: #f1fbf5;
            text-align: left;
        }

        .selected-file.show {
            display: flex;
        }

        .selected-file-info {
            min-width: 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .selected-file-info i {
            flex-shrink: 0;
            color: var(--success);
            font-size: 15px;
        }

        .selected-file-name {
            overflow: hidden;
            font-size: 9px;
            font-weight: 700;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .remove-file-button {
            width: 27px;
            height: 27px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            border: none;
            border-radius: 7px;
            color: var(--danger);
            background: #ffffff;
        }

        .file-note {
            display: flex;
            align-items: flex-start;
            gap: 5px;
            margin-top: 10px;
            color: #8fa1b7;
            font-size: 8px;
            line-height: 1.5;
        }

        .file-note::before {
            content: "*";
            color: var(--primary);
            font-weight: 800;
        }

        .file-error {
            display: none;
            margin-top: 7px;
            color: var(--danger);
            font-size: 8px;
        }

        .file-error.show {
            display: block;
        }

        /* ==================================================
           INPUT NAMA FILE
        ================================================== */

        .form-group {
            margin-top: 17px;
        }

        .form-label {
            display: block;
            margin-bottom: 7px;
            color: #324d6e;
            font-size: 9px;
            font-weight: 700;
        }

        .required {
            color: var(--danger);
        }

        .form-input {
            width: 100%;
            height: 42px;
            padding: 0 13px;
            border: 1px solid #d3dde8;
            border-radius: 9px;
            outline: none;
            color: var(--text-primary);
            background: #ffffff;
            font-size: 10px;
            transition: 0.2s ease;
        }

        .form-input::placeholder {
            color: #a3b2c4;
        }

        .form-input:hover {
            border-color: #adc0d4;
        }

        .form-input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(27, 99, 240, 0.08);
        }

        .form-input.is-valid {
            border-color: var(--success);
        }

        .form-input.is-invalid {
            border-color: var(--danger);
        }

        .input-error {
            display: none;
            margin-top: 6px;
            color: var(--danger);
            font-size: 8px;
        }

        .input-error.show {
            display: block;
        }

        /* ==================================================
           STATUS DAN SIMPAN
        ================================================== */

        .form-status-row {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 20px;
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
            font-weight: 600;
        }

        .status-chip::before {
            content: "";
            width: 5px;
            height: 5px;
            border-radius: 50%;
            background: #c7d0db;
        }

        .status-chip.complete {
            color: #268050;
            border-color: #bee6d0;
            background: #f1fbf5;
        }

        .status-chip.complete::before {
            background: var(--success);
        }

        .form-actions {
            display: flex;
            justify-content: flex-end;
            margin-top: 22px;
            padding-top: 19px;
            border-top: 1px solid #e5ebf2;
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
            color: #ffffff;
            background: var(--primary);
            box-shadow: 0 7px 16px rgba(27, 99, 240, 0.2);
            font-size: 9px;
            font-weight: 700;
            transition: 0.2s ease;
        }

        .save-button:hover:not(:disabled) {
            background: var(--primary-dark);
            transform: translateY(-1px);
        }

        .save-button:disabled {
            color: #9cacbd;
            background: #e3eaf2;
            box-shadow: none;
            cursor: not-allowed;
        }

        /* ==================================================
           NOTIFIKASI
        ================================================== */

        .toast {
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
            font-size: 10px;
            line-height: 1.5;
        }

        .toast.show {
            display: flex;
            animation: toastSlide 0.25s ease;
        }

        .toast i {
            margin-top: 1px;
            color: var(--success);
            font-size: 15px;
        }

        @keyframes toastSlide {
            from {
                opacity: 0;
                transform: translateY(-8px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* ==================================================
           FOOTER
        ================================================== */

        .footer {
            margin-top: auto;
            border-top: 1px solid var(--border);
            background: #eef3f8;
        }

        .footer-container {
            width: 100%;
            max-width: 1280px;
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

        /* ==================================================
           RESPONSIVE
        ================================================== */

        @media (max-width: 900px) {
            .upload-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 680px) {
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

            .master-form,
            .master-card-header {
                padding: 17px;
            }

            .upload-zone {
                min-height: 185px;
            }

            .form-actions {
                justify-content: stretch;
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
        /*
        |--------------------------------------------------------------------------
        | DATA DASAR TEMPLATE
        |--------------------------------------------------------------------------
        */

        $userName = data_get($user ?? null, 'name', session('user_name', 'Ahmad Rizaldi'));

        $jabatanName = data_get($user ?? null, 'jabatan.jabatan_name', session('jabatan_name', 'Perencana Ahli Muda'));

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
            'sidebarUserRole' => $jabatanName,
            'sidebarInitials' => $initials,
        ])

        <div class="app-main">

            <!-- HEADER -->

            <header class="dashboard-header">
                <!-- Struktur header tetap sama -->
                <div class="header-left">
                    <button type="button" class="sidebar-toggle" id="sidebarToggle" aria-label="Buka menu navigasi">
                        <i class="bi bi-list"></i>
                    </button>
                    <div class="header-copy">
                        <div class="header-eyebrow">
                            SISTEM INFORMASI PENELITIAN RKA-K/L
                        </div>
                        <div class="header-title">
                            Dashboard
                        </div>
                    </div>
                </div>

                <div class="header-user">
                    <div class="header-user-text">
                        Biro Perencanaan
                        <strong>{{ $userName }}</strong>
                    </div>
                    <div class="header-avatar">
                        {{ $initials ?: 'US' }}
                    </div>
                </div>
            </header>

            <!-- CONTENT -->

            <main class="page-container">

                <!-- TAMPILAN PESAN ERROR/SUKSES DARI CONTROLLER -->
                @if (session('success'))
                    <div
                        style="background: #effaf5; color: #16a765; padding: 15px; border: 1px solid #bfe4cf; border-radius: 10px; margin-bottom: 20px;">
                        <i class="bi bi-check-circle-fill"></i> <strong>Berhasil!</strong> {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div
                        style="background: #fff1f3; color: #df3d4e; padding: 15px; border: 1px solid #f2c7ce; border-radius: 10px; margin-bottom: 20px;">
                        <i class="bi bi-x-circle-fill"></i> <strong>Gagal!</strong> {{ session('error') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div
                        style="background: #fff1f3; color: #df3d4e; padding: 15px; border: 1px solid #f2c7ce; border-radius: 10px; margin-bottom: 20px;">
                        <i class="bi bi-exclamation-triangle-fill"></i> <strong>Validasi Gagal!</strong>
                        <ul style="margin-top: 5px; margin-left: 20px; font-size: 12px;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <!-- END TAMPILAN PESAN -->

                <section class="upload-master-card">

                    <div class="master-card-header">
                        <div class="master-header-icon">
                            <i class="bi bi-upload"></i>
                        </div>
                        <div>
                            <h1 class="master-card-title">
                                Upload Master Data
                            </h1>
                            <p class="master-card-description">
                                Unggah file Renja dalam format Excel atau CSV.
                            </p>
                        </div>
                    </div>

                    <form id="masterDataForm" class="master-form" action="{{ route('upload.masterdata.storeRenja') }}"
                        method="POST" enctype="multipart/form-data" novalidate>
                        @csrf

                        <div class="upload-grid">
                            <!-- ==========================================
                                 UPLOAD RENJA (Single Column)
                            =========================================== -->
                            <div class="upload-column">
                                <div class="upload-zone" id="renjaDropzone" data-input="renjaFile" tabindex="0"
                                    role="button" aria-label="Pilih file Renja">
                                    <input type="file" id="renjaFile" name="renja_file" class="file-input"
                                        accept=".xlsx,.xls,.csv">

                                    <div class="upload-zone-content">
                                        <div class="upload-icon">
                                            <i class="bi bi-file-earmark-spreadsheet"></i>
                                        </div>
                                        <h2 class="upload-title">
                                            Upload Renja
                                        </h2>
                                        <p class="upload-description">
                                            Seret dan lepaskan file di sini, atau
                                        </p>
                                        <button type="button" class="choose-file-button" data-file-button="renjaFile">
                                            Pilih File
                                        </button>
                                        <div class="selected-file" id="renjaSelectedFile">
                                            <div class="selected-file-info">
                                                <i class="bi bi-file-earmark-excel-fill"></i>
                                                <span class="selected-file-name" id="renjaSelectedName"></span>
                                            </div>
                                            <button type="button" class="remove-file-button" data-remove-file="renja"
                                                aria-label="Hapus file Renja">
                                                <i class="bi bi-x-lg"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <p class="file-note">
                                    File hanya dapat menggunakan format Excel atau csv (.xlsx|.xls|.csv).
                                </p>

                                <div class="file-error" id="renjaFileError">
                                    File Renja harus berformat Excel atau csv (.xlsx|.xls|.csv).
                                </div>

                                <div class="form-group">
                                    <label for="renjaName" class="form-label">
                                        Nama File Renja <span class="required">*</span>
                                    </label>
                                    <input type="text" id="renjaName" name="renja_name" class="form-input"
                                        placeholder="Masukkan nama file Renja" maxlength="150" autocomplete="off">

                                    <div class="input-error" id="renjaNameError">
                                        Nama file Renja wajib diisi.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- STATUS -->
                        <div class="form-status-row">
                            <span class="status-chip" id="renjaFileStatus">
                                File Renja
                            </span>
                            <span class="status-chip" id="renjaNameStatus">
                                Nama File Renja
                            </span>
                        </div>

                        <!-- BUTTON -->
                        <div class="form-actions">
                            <button type="submit" class="save-button" id="saveMasterButton" disabled>
                                <i class="bi bi-floppy"></i>
                                Simpan Master Data
                            </button>
                        </div>
                    </form>

                </section>
            </main>

            <!-- FOOTER -->
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

    <!-- NOTIFIKASI -->
    {{-- <div class="toast" id="successToast">
        <i class="bi bi-check-circle-fill"></i>
        <div>
            <strong>Master data berhasil disimpan.</strong><br>
            File Renja telah berhasil diproses secara frontend.
        </div>
    </div> --}}
    {{-- @if (session('success'))
        <div
            style="background-color: #effaf5; color: #16a765; padding: 15px; border: 1px solid #16a765; border-radius: 8px; margin-bottom: 20px;">
            <strong>Berhasil!</strong> {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div
            style="background-color: #fff1f3; color: #df3d4e; padding: 15px; border: 1px solid #df3d4e; border-radius: 8px; margin-bottom: 20px;">
            <strong>Gagal!</strong> {{ session('error') }}
        </div>
    @endif

    <script>
        const masterDataForm = document.getElementById("masterDataForm");
        const saveMasterButton = document.getElementById("saveMasterButton");
        const renjaName = document.getElementById("renjaName");

        const uploadState = {
            renja: null
        };

        const uploadConfig = {
            renja: {
                input: document.getElementById("renjaFile"),
                dropzone: document.getElementById("renjaDropzone"),
                selectedBox: document.getElementById("renjaSelectedFile"),
                selectedName: document.getElementById("renjaSelectedName"),
                error: document.getElementById("renjaFileError"),
                status: document.getElementById("renjaFileStatus")
            }
        };

        // Menambahkan .csv untuk kecocokan ekstensi pada script pengecekan
        function isExcelFile(file) {
            if (!file) return false;
            const fileName = file.name.toLowerCase();
            return fileName.endsWith(".xlsx") || fileName.endsWith(".xls") || fileName.endsWith(".csv");
        }

        function assignFileToInput(input, file) {
            try {
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);
                input.files = dataTransfer.files;
            } catch (error) {
                console.warn("Browser tidak mendukung pengisian input file otomatis.", error);
            }
        }

        function setSelectedFile(type, file) {
            const config = uploadConfig[type];

            config.dropzone.classList.remove("has-file", "has-error");
            config.error.classList.remove("show");

            if (!isExcelFile(file)) {
                uploadState[type] = null;
                config.input.value = "";
                config.selectedBox.classList.remove("show");
                config.selectedName.textContent = "";
                config.dropzone.classList.add("has-error");
                config.error.classList.add("show");

                updateFormState();
                return;
            }

            uploadState[type] = file;
            assignFileToInput(config.input, file);

            config.selectedName.textContent = file.name;
            config.selectedBox.classList.add("show");
            config.dropzone.classList.add("has-file");

            updateFormState();
        }

        function removeSelectedFile(type) {
            const config = uploadConfig[type];

            uploadState[type] = null;
            config.input.value = "";
            config.selectedName.textContent = "";
            config.selectedBox.classList.remove("show");
            config.dropzone.classList.remove("has-file", "has-error");
            config.error.classList.remove("show");

            updateFormState();
        }

        Object.entries(uploadConfig).forEach(([type, config]) => {
            config.input.addEventListener("change", function() {
                setSelectedFile(type, this.files[0]);
            });

            config.dropzone.addEventListener("click", event => {
                if (event.target.closest(".remove-file-button")) return;
                config.input.click();
            });

            config.dropzone.addEventListener("keydown", event => {
                if (event.key === "Enter" || event.key === " ") {
                    event.preventDefault();
                    config.input.click();
                }
            });

            config.dropzone.addEventListener("dragover", event => {
                event.preventDefault();
                config.dropzone.classList.add("dragover");
            });

            config.dropzone.addEventListener("dragleave", () => {
                config.dropzone.classList.remove("dragover");
            });

            config.dropzone.addEventListener("drop", event => {
                event.preventDefault();
                config.dropzone.classList.remove("dragover");
                const file = event.dataTransfer.files[0];
                setSelectedFile(type, file);
            });
        });

        document.querySelectorAll("[data-file-button]").forEach(button => {
            button.addEventListener("click", event => {
                event.stopPropagation();
                document.getElementById(button.dataset.fileButton).click();
            });
        });

        document.querySelectorAll("[data-remove-file]").forEach(button => {
            button.addEventListener("click", event => {
                event.stopPropagation();
                removeSelectedFile(button.dataset.removeFile);
            });
        });

        function validateTextInput(input, errorElement, showError = false) {
            const valid = input.value.trim().length > 0;
            input.classList.toggle("is-valid", valid);
            input.classList.toggle("is-invalid", showError && !valid);
            errorElement.classList.toggle("show", showError && !valid);
            return valid;
        }

        function updateStatusChip(element, complete) {
            element.classList.toggle("complete", complete);
        }

        function updateFormState(showErrors = false) {
            const renjaNameValid = validateTextInput(
                renjaName,
                document.getElementById("renjaNameError"),
                showErrors
            );

            const renjaFileValid = uploadState.renja !== null;

            updateStatusChip(document.getElementById("renjaFileStatus"), renjaFileValid);
            updateStatusChip(document.getElementById("renjaNameStatus"), renjaNameValid);

            const valid = renjaFileValid && renjaNameValid;
            saveMasterButton.disabled = !valid;

            return valid;
        }

        renjaName.addEventListener("input", () => updateFormState());

        masterDataForm.addEventListener("submit", function(event) {
            // 1. Tahan submit sebentar untuk mengecek apakah input sudah diisi semua
            event.preventDefault();

            // 2. Jika validasi frontend (JS) gagal, hentikan proses
            if (!updateFormState(true)) {
                return;
            }

            // 3. Jika data sudah lengkap, ubah tombol jadi mode loading
            saveMasterButton.disabled = true;
            saveMasterButton.innerHTML = `<i class="bi bi-arrow-repeat"></i> Menyimpan ke Database...`;

            // 4. KIRIM DATA KE LARAVEL (Ini bagian yang paling penting!)
            this.submit();
        });

        updateFormState();
    </script>
</body> --}}

{{-- </html> --}}
