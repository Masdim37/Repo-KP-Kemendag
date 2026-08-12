<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Upload RKA | Penelitian RKA-K/L</title>

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    >

    <style>
        :root {
            --primary: #0759b7;
            --primary-light: #eef5ff;
            --primary-dark: #06498f;
            --success: #159957;
            --danger: #df4052;
            --text-primary: #18365b;
            --text-secondary: #607995;
            --text-muted: #91a4b9;
            --background: #f3f6fa;
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
            color: var(--text-primary);
            font-size: 14px;
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
            max-width: 780px;
            margin: 0 auto;
            border: 1px solid var(--border);
            border-radius: 14px;
            background: var(--white);
            box-shadow: 0 8px 25px rgba(38, 68, 103, 0.07);
            overflow: hidden;
        }

        .card-header {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 18px 21px;
            border-bottom: 1px solid #e6ecf2;
        }

        .card-header-icon {
            width: 35px;
            height: 35px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            border-radius: 10px;
            color: var(--primary);
            background: var(--primary-light);
            font-size: 15px;
        }

        .card-title {
            font-size: 13px;
            font-weight: 800;
        }

        .card-description {
            margin-top: 3px;
            color: var(--text-muted);
            font-size: 8.5px;
        }

        .upload-form {
            padding: 22px;
        }

        /* UPLOAD */

        .upload-zone {
            position: relative;
            min-height: 205px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 25px;
            border: 2px dashed #6aa6ff;
            border-radius: 12px;
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
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 13px;
            border-radius: 50%;
            color: #1967d2;
            background: #e1edff;
            font-size: 20px;
        }

        .upload-zone.has-file .upload-icon {
            color: var(--success);
            background: #def5e8;
        }

        .upload-title {
            font-size: 12px;
            font-weight: 800;
        }

        .upload-description {
            margin-top: 5px;
            color: var(--text-muted);
            font-size: 8.5px;
        }

        .choose-file-button {
            min-width: 105px;
            height: 36px;
            margin-top: 13px;
            padding: 0 17px;
            border: 0;
            border-radius: 9px;
            color: #ffffff;
            background: var(--primary);
            box-shadow: 0 5px 12px rgba(7, 89, 183, 0.2);
            font-size: 9px;
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
            margin-top: 14px;
            padding: 10px 12px;
            border: 1px solid #b9e4cc;
            border-radius: 9px;
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
            font-size: 16px;
        }

        .selected-file-name {
            overflow: hidden;
            font-size: 9px;
            font-weight: 700;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .selected-file-size {
            margin-top: 2px;
            color: #6c8b79;
            font-size: 7.5px;
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
            font-size: 8px;
            line-height: 1.5;
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
            font-size: 8px;
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
            gap: 15px 18px;
        }

        .form-group.full-width {
            grid-column: 1 / -1;
        }

        .form-label {
            display: block;
            margin-bottom: 7px;
            color: #344e6c;
            font-size: 9px;
            font-weight: 700;
        }

        .required {
            color: var(--danger);
        }

        .form-control {
            width: 100%;
            height: 42px;
            padding: 0 12px;
            border: 1px solid #d4dee8;
            border-radius: 9px;
            outline: none;
            color: var(--text-primary);
            background: #ffffff;
            font-size: 9px;
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
            font-size: 7.5px;
            font-weight: 600;
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
            min-width: 180px;
            height: 41px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 0 20px;
            border: 0;
            border-radius: 9px;
            color: #ffffff;
            background: var(--primary);
            box-shadow: 0 6px 14px rgba(7, 89, 183, 0.2);
            font-size: 9px;
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
            font-size: 15px;
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
                min-height: 185px;
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
    $userName = data_get(
        $user ?? null,
        'name',
        session('user_name', 'M Dhimas Hafizh')
    );

    $jabatanName = data_get(
        $user ?? null,
        'jabatan.jabatan_name',
        session('jabatan_name', 'Perencana Ahli Muda')
    );

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
        'sidebarUserRole' => $jabatanName,
        'sidebarInitials' => $initials,
    ])

    <div class="app-main">

        <header class="dashboard-header">

            <div class="header-left">

                <button
                    type="button"
                    class="sidebar-toggle"
                    id="sidebarToggle"
                    aria-label="Buka menu navigasi"
                    aria-expanded="false"
                >
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
                        <i class="bi bi-file-earmark-spreadsheet"></i>
                    </div>

                    <div>
                        <h1 class="card-title">
                            Upload Dokumen RKA
                        </h1>

                        <p class="card-description">
                            Unggah dokumen RKA/Kertas Kerja Satker dalam format Excel.
                        </p>
                    </div>

                </div>

                <form
                    id="uploadRkaForm"
                    class="upload-form"
                    action="#"
                    method="POST"
                    enctype="multipart/form-data"
                    novalidate
                >
                    @csrf

                    <div
                        class="upload-zone"
                        id="rkaDropzone"
                        tabindex="0"
                        role="button"
                    >

                        <input
                            type="file"
                            id="rkaFile"
                            name="rka_file"
                            class="file-input"
                            accept=".xlsx,.xls"
                        >

                        <div>

                            <div class="upload-icon">
                                <i class="bi bi-file-earmark-spreadsheet"></i>
                            </div>

                            <h2 class="upload-title">
                                Upload RKA / Kertas Kerja Satker
                            </h2>

                            <p class="upload-description">
                                Seret dan lepaskan file di sini, atau
                            </p>

                            <button
                                type="button"
                                class="choose-file-button"
                                id="chooseFileButton"
                            >
                                Pilih File
                            </button>

                            <div
                                class="selected-file"
                                id="selectedFile"
                            >

                                <div class="selected-file-info">

                                    <i class="bi bi-file-earmark-excel-fill"></i>

                                    <div>
                                        <div
                                            class="selected-file-name"
                                            id="selectedFileName"
                                        ></div>

                                        <div
                                            class="selected-file-size"
                                            id="selectedFileSize"
                                        ></div>
                                    </div>

                                </div>

                                <button
                                    type="button"
                                    class="remove-file-button"
                                    id="removeFileButton"
                                    aria-label="Hapus file"
                                >
                                    <i class="bi bi-x-lg"></i>
                                </button>

                            </div>

                        </div>

                    </div>

                    <p class="file-note">
                        File hanya dapat menggunakan format Excel
                        (.xlsx atau .xls).
                    </p>

                    <div
                        class="error-message"
                        id="fileError"
                    >
                        File harus menggunakan format Excel (.xlsx atau .xls).
                    </div>

                    <div class="form-divider"></div>

                    <div class="form-grid">

                        <div class="form-group full-width">

                            <label
                                for="rkaName"
                                class="form-label"
                            >
                                Nama File RKA
                                <span class="required">*</span>
                            </label>

                            <input
                                type="text"
                                id="rkaName"
                                name="rka_name"
                                class="form-control"
                                placeholder="Masukkan nama file RKA"
                                maxlength="150"
                                autocomplete="off"
                            >

                            <div
                                class="error-message"
                                id="rkaNameError"
                            >
                                Nama file RKA wajib diisi.
                            </div>

                        </div>

                        <div class="form-group">

                            <label
                                for="unitEselon1"
                                class="form-label"
                            >
                                Unit Eselon 1
                                <span class="required">*</span>
                            </label>

                            <div class="select-wrapper">

                                <select
                                    id="unitEselon1"
                                    name="unit_eselon_1"
                                    class="form-control"
                                >
                                    <option value="">
                                        -- Pilih Unit Eselon 1 --
                                    </option>
                                </select>

                                <i class="bi bi-chevron-down"></i>

                            </div>

                        </div>

                        <div class="form-group">

                            <label
                                for="kodeUnitEselon1"
                                class="form-label"
                            >
                                Kode Unit Eselon 1
                            </label>

                            <input
                                type="text"
                                id="kodeUnitEselon1"
                                class="form-control"
                                placeholder="Otomatis terisi"
                                readonly
                            >

                        </div>

                        <div class="form-group">

                            <label
                                for="unitEselon2"
                                class="form-label"
                            >
                                Unit Eselon 2
                                <span class="required">*</span>
                            </label>

                            <div class="select-wrapper">

                                <select
                                    id="unitEselon2"
                                    name="unit_eselon_2"
                                    class="form-control"
                                    disabled
                                >
                                    <option value="">
                                        -- Pilih Unit Eselon 2 --
                                    </option>
                                </select>

                                <i class="bi bi-chevron-down"></i>

                            </div>

                        </div>

                        <div class="form-group">

                            <label
                                for="kodeUnitEselon2"
                                class="form-label"
                            >
                                Kode Unit Eselon 2
                            </label>

                            <input
                                type="text"
                                id="kodeUnitEselon2"
                                class="form-control"
                                placeholder="Otomatis terisi"
                                readonly
                            >

                        </div>

                        <div class="form-group">

                            <label
                                for="satuanKerja"
                                class="form-label"
                            >
                                Satuan Kerja
                                <span class="required">*</span>
                            </label>

                            <div class="select-wrapper">

                                <select
                                    id="satuanKerja"
                                    name="satker"
                                    class="form-control"
                                    disabled
                                >
                                    <option value="">
                                        -- Pilih Satuan Kerja --
                                    </option>
                                </select>

                                <i class="bi bi-chevron-down"></i>

                            </div>

                        </div>

                        <div class="form-group">

                            <label
                                for="kodeSatker"
                                class="form-label"
                            >
                                Kode Satker
                            </label>

                            <input
                                type="text"
                                id="kodeSatker"
                                class="form-control"
                                placeholder="Otomatis terisi"
                                readonly
                            >

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

                        <button
                            type="submit"
                            class="save-button"
                            id="saveButton"
                            disabled
                        >
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
    const unitData = [
        {
            id: "setjen",
            name: "Sekretariat Jenderal",
            code: "090.01",
            children: [
                {
                    id: "biro-perencanaan",
                    name: "Biro Perencanaan",
                    code: "090.01.01",
                    satkers: [
                        {
                            id: "satker-biro-perencanaan",
                            name: "Biro Perencanaan",
                            code: "635912"
                        }
                    ]
                },
                {
                    id: "biro-keuangan",
                    name: "Biro Keuangan",
                    code: "090.01.02",
                    satkers: [
                        {
                            id: "satker-biro-keuangan",
                            name: "Biro Keuangan",
                            code: "635913"
                        }
                    ]
                }
            ]
        },
        {
            id: "ditjen-pdn",
            name: "Direktorat Jenderal Perdagangan Dalam Negeri",
            code: "090.02",
            children: [
                {
                    id: "setditjen-pdn",
                    name: "Sekretariat Ditjen PDN",
                    code: "090.02.01",
                    satkers: [
                        {
                            id: "satker-ditjen-pdn",
                            name: "Direktorat Jenderal Perdagangan Dalam Negeri",
                            code: "635920"
                        }
                    ]
                }
            ]
        },
        {
            id: "ditjen-pen",
            name: "Direktorat Jenderal Pengembangan Ekspor Nasional",
            code: "090.03",
            children: [
                {
                    id: "setditjen-pen",
                    name: "Sekretariat Ditjen PEN",
                    code: "090.03.01",
                    satkers: [
                        {
                            id: "satker-ditjen-pen",
                            name: "Direktorat Jenderal Pengembangan Ekspor Nasional",
                            code: "635930"
                        }
                    ]
                }
            ]
        }
    ];

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
    const unitEselon1 = document.getElementById("unitEselon1");
    const unitEselon2 = document.getElementById("unitEselon2");
    const satuanKerja = document.getElementById("satuanKerja");

    const kodeUnitEselon1 =
        document.getElementById("kodeUnitEselon1");

    const kodeUnitEselon2 =
        document.getElementById("kodeUnitEselon2");

    const kodeSatker =
        document.getElementById("kodeSatker");

    const saveButton =
        document.getElementById("saveButton");

    let selectedRkaFile = null;

    function renderOptions(select, placeholder, items) {
        select.innerHTML = `
            <option value="">${placeholder}</option>
        `;

        items.forEach(item => {
            const option = document.createElement("option");

            option.value = item.id;
            option.textContent = item.name;
            option.dataset.code = item.code;

            select.appendChild(option);
        });
    }

    renderOptions(
        unitEselon1,
        "-- Pilih Unit Eselon 1 --",
        unitData
    );

    function isExcelFile(file) {
        if (!file) {
            return false;
        }

        const name = file.name.toLowerCase();

        return (
            name.endsWith(".xlsx") ||
            name.endsWith(".xls")
        );
    }

    function formatFileSize(bytes) {
        if (bytes < 1024) {
            return `${bytes} Bytes`;
        }

        if (bytes < 1024 * 1024) {
            return `${(bytes / 1024).toFixed(1)} KB`;
        }

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
        selectedFileSize.textContent = formatFileSize(file.size);

        selectedFile.classList.add("show");
        dropzone.classList.add("has-file");

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
        if (event.target.closest("#removeFileButton")) {
            return;
        }

        fileInput.click();
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

        assignFile(event.dataTransfer.files[0]);
    });

    unitEselon1.addEventListener("change", () => {
        const selectedUnit = unitData.find(
            item => item.id === unitEselon1.value
        );

        kodeUnitEselon1.value =
            selectedUnit?.code || "";

        kodeUnitEselon2.value = "";
        kodeSatker.value = "";

        renderOptions(
            unitEselon2,
            "-- Pilih Unit Eselon 2 --",
            selectedUnit?.children || []
        );

        unitEselon2.disabled = !selectedUnit;

        renderOptions(
            satuanKerja,
            "-- Pilih Satuan Kerja --",
            []
        );

        satuanKerja.disabled = true;

        updateFormState();
    });

    unitEselon2.addEventListener("change", () => {
        const selectedUnit1 = unitData.find(
            item => item.id === unitEselon1.value
        );

        const selectedUnit2 = selectedUnit1?.children.find(
            item => item.id === unitEselon2.value
        );

        kodeUnitEselon2.value =
            selectedUnit2?.code || "";

        kodeSatker.value = "";

        renderOptions(
            satuanKerja,
            "-- Pilih Satuan Kerja --",
            selectedUnit2?.satkers || []
        );

        satuanKerja.disabled = !selectedUnit2;

        updateFormState();
    });

    satuanKerja.addEventListener("change", () => {
        const selectedUnit1 = unitData.find(
            item => item.id === unitEselon1.value
        );

        const selectedUnit2 = selectedUnit1?.children.find(
            item => item.id === unitEselon2.value
        );

        const selectedSatker = selectedUnit2?.satkers.find(
            item => item.id === satuanKerja.value
        );

        kodeSatker.value =
            selectedSatker?.code || "";

        updateFormState();
    });

    function setStatus(id, complete) {
        document
            .getElementById(id)
            .classList.toggle("complete", complete);
    }

    function updateFormState(showErrors = false) {
        const fileValid = selectedRkaFile !== null;
        const nameValid = rkaName.value.trim() !== "";
        const eselon1Valid = unitEselon1.value !== "";
        const eselon2Valid = unitEselon2.value !== "";
        const satkerValid = satuanKerja.value !== "";

        rkaName.classList.toggle(
            "is-valid",
            nameValid
        );

        rkaName.classList.toggle(
            "is-invalid",
            showErrors && !nameValid
        );

        document
            .getElementById("rkaNameError")
            .classList.toggle(
                "show",
                showErrors && !nameValid
            );

        setStatus("fileStatus", fileValid);
        setStatus("nameStatus", nameValid);
        setStatus("eselon1Status", eselon1Valid);
        setStatus("eselon2Status", eselon2Valid);
        setStatus("satkerStatus", satkerValid);

        const valid =
            fileValid &&
            nameValid &&
            eselon1Valid &&
            eselon2Valid &&
            satkerValid;

        saveButton.disabled = !valid;

        return valid;
    }

    rkaName.addEventListener("input", () => {
        updateFormState();
    });

    form.addEventListener("submit", event => {
        event.preventDefault();

        if (!updateFormState(true)) {
            return;
        }

        saveButton.disabled = true;

        saveButton.innerHTML = `
            <i class="bi bi-arrow-repeat"></i>
            Menyimpan...
        `;

        setTimeout(() => {
            saveButton.innerHTML = `
                <i class="bi bi-check-circle-fill"></i>
                Berhasil Disimpan
            `;

            const toast =
                document.getElementById("successToast");

            toast.classList.add("show");

            setTimeout(() => {
                toast.classList.remove("show");

                saveButton.disabled = false;

                saveButton.innerHTML = `
                    <i class="bi bi-floppy"></i>
                    Simpan Dokumen RKA
                `;
            }, 2300);
        }, 800);
    });

    updateFormState();
</script>

</body>

</html>