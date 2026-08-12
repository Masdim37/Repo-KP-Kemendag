<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Upload TOR & RAB | Penelitian RKA-K/L</title>

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    >

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

        /* =====================================================
           HEADER
        ===================================================== */

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
            letter-spacing: .8px;
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
            box-shadow: 0 5px 12px rgba(31,91,148,.16);
            font-size: 10px;
            font-weight: 800;
        }

        /* =====================================================
           PAGE
        ===================================================== */

        .page-container {
            width: 100%;
            min-height: 0;
            flex: 1;
            padding: 24px;
            background: var(--background);
        }

        .content-wrapper {
            width: 100%;
            max-width: 930px;
            margin: 0 auto;
        }

        /* INFO */

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

        .info-box i {
            flex-shrink: 0;
            color: var(--primary);
            font-size: 14px;
        }

        .info-box strong {
            display: block;
            margin-bottom: 2px;
            color: #2e5d94;
            font-size: 9.5px;
        }

        /* CARD */

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

        .card-header-left {
            display: flex;
            align-items: center;
            gap: 11px;
        }

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

        .card-title {
            font-size: 13px;
            font-weight: 800;
        }

        .card-description {
            margin-top: 3px;
            color: var(--text-muted);
            font-size: 8px;
        }

        .card-date {
            color: #93a2b4;
            font-size: 8px;
        }

        .main-form {
            padding: 20px;
        }

        /* SECTION */

        .section-title {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            margin-bottom: 13px;
            padding-left: 8px;
            border-left: 3px solid var(--primary);
        }

        .section-title h2 {
            font-size: 11px;
            font-weight: 800;
        }

        .section-note {
            padding: 4px 8px;
            border-radius: 12px;
            color: #7c8ea2;
            background: #f3f6f9;
            font-size: 7px;
        }

        .section-divider {
            height: 1px;
            margin: 22px 0;
            background: #e7edf3;
        }

        /* =====================================================
           TOR & RAB UPLOAD
        ===================================================== */

        .document-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 18px;
        }

        .document-box {
            padding: 14px;
            border: 1px solid #dce5ee;
            border-radius: 11px;
            background: #fbfcfe;
        }

        .document-title {
            margin-bottom: 10px;
            color: #334e6d;
            font-size: 10px;
            font-weight: 800;
        }

        .upload-zone {
            min-height: 170px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 18px;
            border: 1.5px dashed #b8c9dc;
            border-radius: 9px;
            background: #ffffff;
            text-align: center;
            transition: .2s ease;
        }

        .upload-zone:hover,
        .upload-zone.dragover {
            border-color: var(--primary);
            background: #f6faff;
        }

        .upload-zone.has-file {
            border-color: var(--success);
            background: var(--success-soft);
        }

        .upload-zone.has-error {
            border-color: var(--danger);
            background: var(--danger-soft);
        }

        .file-input {
            display: none;
        }

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

        .upload-zone.has-file .upload-icon {
            color: var(--success);
            background: #ddf4e7;
        }

        .drop-title {
            color: #3e536d;
            font-size: 9px;
            font-weight: 700;
        }

        .drop-or {
            margin: 5px 0;
            color: #9ba9b8;
            font-size: 7px;
        }

        .choose-button {
            height: 31px;
            padding: 0 15px;
            border: 0;
            border-radius: 7px;
            color: #ffffff;
            background: var(--primary);
            font-size: 8px;
            font-weight: 700;
        }

        .file-format {
            margin-top: 8px;
            color: #9aa9ba;
            font-size: 7px;
        }

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

        .selected-file.show {
            display: flex;
        }

        .selected-file-info {
            min-width: 0;
            display: flex;
            align-items: center;
            gap: 7px;
        }

        .selected-file-name {
            overflow: hidden;
            font-size: 7.5px;
            font-weight: 700;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .remove-file {
            width: 25px;
            height: 25px;
            flex-shrink: 0;
            border: 0;
            border-radius: 6px;
            color: var(--danger);
            background: #ffffff;
        }

        .file-error {
            display: none;
            margin-top: 6px;
            color: var(--danger);
            font-size: 7.5px;
        }

        .file-error.show {
            display: block;
        }

        /* INPUT */

        .form-group {
            margin-top: 12px;
        }

        .form-label {
            display: block;
            margin-bottom: 6px;
            color: #53677e;
            font-size: 8px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .required {
            color: var(--danger);
        }

        .form-control {
            width: 100%;
            height: 38px;
            padding: 0 11px;
            border: 1px solid #d5dee7;
            border-radius: 7px;
            outline: none;
            color: #304b69;
            background: #ffffff;
            font-size: 8.5px;
            transition: .2s ease;
        }

        .form-control::placeholder {
            color: #a7b4c2;
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(7,89,183,.07);
        }

        .form-control[readonly],
        .form-control:disabled {
            color: #8998aa;
            background: #f4f6f8;
        }

        /* =====================================================
           REFERENSI
        ===================================================== */

        .reference-box {
            overflow: hidden;
            border: 1px solid #e0e7ee;
            border-radius: 10px;
        }

        .reference-row {
            display: grid;
            grid-template-columns: minmax(0, 1fr) 135px;
            gap: 12px;
            padding: 10px 12px;
            border-bottom: 1px solid #edf1f5;
        }

        .reference-row:last-child {
            border-bottom: 0;
        }

        .reference-field label {
            display: block;
            margin-bottom: 5px;
            color: #5a6d82;
            font-size: 7px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .select-wrapper {
            position: relative;
        }

        .select-wrapper select {
            padding-right: 30px;
            appearance: none;
        }

        .select-wrapper i {
            position: absolute;
            top: 50%;
            right: 11px;
            transform: translateY(-50%);
            color: #8b9aab;
            font-size: 8px;
            pointer-events: none;
        }

        /* =====================================================
           STATUS
        ===================================================== */

        .status-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 12px;
        }

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
            grid-template-columns: repeat(5, 1fr);
            gap: 9px;
            padding: 13px;
            border: 1px solid #e3e9ef;
            border-radius: 9px;
            background: #fafbfd;
        }

        .status-item {
            display: flex;
            align-items: center;
            gap: 6px;
            color: #9baaba;
            font-size: 7px;
        }

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

        .status-item.complete {
            color: #267849;
        }

        .status-item.complete .status-dot {
            color: #ffffff;
            background: var(--success);
        }

        .progress-track {
            height: 4px;
            margin-top: 12px;
            overflow: hidden;
            border-radius: 10px;
            background: #e9eef3;
        }

        .progress-bar {
            width: 0;
            height: 100%;
            border-radius: 10px;
            background: var(--success);
            transition: width .3s ease;
        }

        .progress-text {
            margin-top: 5px;
            text-align: right;
            color: #77899b;
            font-size: 7px;
        }

        /* ACTION */

        .bottom-actions {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            margin-top: 15px;
        }

        .action-message {
            color: #8798aa;
            font-size: 7.5px;
        }

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
            color: #ffffff;
            background: var(--primary);
            font-size: 8.5px;
            font-weight: 700;
        }

        .save-button:disabled {
            color: #98a7b7;
            background: #e1e7ed;
            cursor: not-allowed;
        }

        /* TOAST */

        .toast {
            position: fixed;
            top: 80px;
            right: 20px;
            z-index: 2000;
            display: none;
            align-items: flex-start;
            gap: 9px;
            width: 330px;
            padding: 12px 14px;
            border: 1px solid #bae4cc;
            border-radius: 9px;
            color: #2d704a;
            background: #effaf4;
            box-shadow: 0 12px 30px rgba(30,60,90,.14);
            font-size: 8px;
        }

        .toast.show {
            display: flex;
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

        @media (max-width: 800px) {
            .document-grid {
                grid-template-columns: 1fr;
            }

            .status-grid {
                grid-template-columns: repeat(2, 1fr);
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
                padding: 14px;
            }

            .main-form {
                padding: 15px;
            }

            .reference-row {
                grid-template-columns: 1fr;
            }

            .status-grid {
                grid-template-columns: 1fr;
            }

            .bottom-actions {
                align-items: stretch;
                flex-direction: column;
            }

            .save-button {
                width: 100%;
            }
        }
    </style>
</head>

<body>

@php
    $userName = data_get(
        $user ?? null,
        'name',
        session('user_name', 'Dr. Siti Rahayu, M.Si')
    );

    $jabatanName = data_get(
        $user ?? null,
        'jabatan.jabatan_name',
        session('jabatan_name', 'Perencana Ahli Madya')
    );

    $initials = collect(explode(' ', $userName))
        ->filter()
        ->take(2)
        ->map(fn ($word) => strtoupper(substr($word, 0, 1)))
        ->implode('');
@endphp

<div class="app-shell">

    @include('partials.sidebar', [
        'activeMenu' => 'upload-tor-rab',
        'sidebarUserName' => $userName,
        'sidebarUserRole' => $jabatanName,
        'sidebarInitials' => $initials,
    ])

    <div class="app-main">

        <!-- HEADER -->

        <header class="dashboard-header">

            <div class="header-left">

                <button
                    type="button"
                    class="sidebar-toggle"
                    id="sidebarToggle"
                    aria-label="Buka menu navigasi"
                >
                    <i class="bi bi-list"></i>
                </button>

                <div class="header-copy">

                    <div class="header-eyebrow">
                        SISTEM INFORMASI PENELITIAN RKA-K/L
                    </div>

                    <div class="header-title">
                        Upload TOR & RAB
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

            <div class="content-wrapper">

                <!-- PANDUAN -->

                <div class="info-box">

                    <i class="bi bi-info-circle"></i>

                    <div>
                        <strong>
                            Panduan Unggah Dokumen
                        </strong>

                        Unggah file TOR/KAK dan RAB sesuai format yang ditentukan.
                        Pastikan seluruh referensi anggaran telah terisi sebelum
                        menyimpan dokumen.
                    </div>

                </div>

                <section class="main-card">

                    <div class="card-header">

                        <div class="card-header-left">

                            <div class="card-header-icon">
                                <i class="bi bi-files"></i>
                            </div>

                            <div>
                                <h1 class="card-title">
                                    Upload Dokumen TOR & RAB
                                </h1>

                                <p class="card-description">
                                    Dokumen pendukung perencanaan dan penganggaran.
                                </p>
                            </div>

                        </div>

                        <div class="card-date">
                            <i class="bi bi-clock"></i>
                            11 Agustus 2026
                        </div>

                    </div>

                    <form
                        id="torRabForm"
                        class="main-form"
                        action="#"
                        method="POST"
                        enctype="multipart/form-data"
                        novalidate
                    >

                        @csrf

                        <!-- ===============================
                             FILE DOKUMEN
                        ================================ -->

                        <div class="section-title">
                            <h2>File Dokumen</h2>
                        </div>

                        <div class="document-grid">

                            <!-- TOR -->

                            <div class="document-box">

                                <div class="document-title">
                                    Upload TOR / KAK
                                </div>

                                <div
                                    class="upload-zone"
                                    id="torDropzone"
                                >

                                    <input
                                        type="file"
                                        id="torFile"
                                        class="file-input"
                                    >

                                    <div>

                                        <div class="upload-icon">
                                            <i class="bi bi-upload"></i>
                                        </div>

                                        <div class="drop-title">
                                            Seret & jatuhkan file di sini
                                        </div>

                                        <div class="drop-or">
                                            atau
                                        </div>

                                        <button
                                            type="button"
                                            class="choose-button"
                                            data-choose="torFile"
                                        >
                                            Pilih File
                                        </button>

                                        <div class="file-format">
                                            PDF, DOCX, DOC
                                        </div>

                                        <div
                                            class="selected-file"
                                            id="torSelected"
                                        >
                                            <div class="selected-file-info">

                                                <i class="bi bi-file-earmark-check"></i>

                                                <span
                                                    class="selected-file-name"
                                                    id="torSelectedName"
                                                ></span>

                                            </div>

                                            <button
                                                type="button"
                                                class="remove-file"
                                                data-remove="tor"
                                            >
                                                <i class="bi bi-x-lg"></i>
                                            </button>
                                        </div>

                                    </div>

                                </div>

                                <div class="file-error" id="torFileError">
                                    Pilih file TOR/KAK terlebih dahulu.
                                </div>

                                <div class="form-group">

                                    <label class="form-label">
                                        Nama File TOR / KAK
                                        <span class="required">*</span>
                                    </label>

                                    <input
                                        type="text"
                                        id="torName"
                                        class="form-control"
                                        placeholder="Masukkan nama file TOR/KAK..."
                                    >

                                </div>

                            </div>

                            <!-- RAB -->

                            <div class="document-box">

                                <div class="document-title">
                                    Upload RAB
                                </div>

                                <div
                                    class="upload-zone"
                                    id="rabDropzone"
                                >

                                    <input
                                        type="file"
                                        id="rabFile"
                                        class="file-input"
                                    >

                                    <div>

                                        <div class="upload-icon">
                                            <i class="bi bi-upload"></i>
                                        </div>

                                        <div class="drop-title">
                                            Seret & jatuhkan file di sini
                                        </div>

                                        <div class="drop-or">
                                            atau
                                        </div>

                                        <button
                                            type="button"
                                            class="choose-button"
                                            data-choose="rabFile"
                                        >
                                            Pilih File
                                        </button>

                                        <div class="file-format">
                                            XLSX, XLS, PDF
                                        </div>

                                        <div
                                            class="selected-file"
                                            id="rabSelected"
                                        >
                                            <div class="selected-file-info">

                                                <i class="bi bi-file-earmark-check"></i>

                                                <span
                                                    class="selected-file-name"
                                                    id="rabSelectedName"
                                                ></span>

                                            </div>

                                            <button
                                                type="button"
                                                class="remove-file"
                                                data-remove="rab"
                                            >
                                                <i class="bi bi-x-lg"></i>
                                            </button>
                                        </div>

                                    </div>

                                </div>

                                <div class="file-error" id="rabFileError">
                                    Pilih file RAB terlebih dahulu.
                                </div>

                                <div class="form-group">

                                    <label class="form-label">
                                        Nama File RAB
                                        <span class="required">*</span>
                                    </label>

                                    <input
                                        type="text"
                                        id="rabName"
                                        class="form-control"
                                        placeholder="Masukkan nama file RAB..."
                                    >

                                </div>

                            </div>

                        </div>

                        <div class="section-divider"></div>

                        <!-- ===============================
                             REFERENSI ANGGARAN
                        ================================ -->

                        <div class="section-title">

                            <h2>
                                Referensi Anggaran
                            </h2>

                            <span class="section-note">
                                Berlaku untuk TOR & RAB
                            </span>

                        </div>

                        <div class="reference-box">

                            <!-- UNIT 1 -->

                            <div class="reference-row">

                                <div class="reference-field">
                                    <label>Unit Eselon 1</label>

                                    <div class="select-wrapper">

                                        <select
                                            id="unit1"
                                            class="form-control"
                                        >
                                            <option value="">
                                                -- Pilih Unit Eselon 1 --
                                            </option>
                                        </select>

                                        <i class="bi bi-chevron-down"></i>

                                    </div>
                                </div>

                                <div class="reference-field">
                                    <label>Kode Unit Eselon 1</label>

                                    <input
                                        id="kodeUnit1"
                                        class="form-control"
                                        readonly
                                        placeholder="-"
                                    >
                                </div>

                            </div>

                            <!-- UNIT 2 -->

                            <div class="reference-row">

                                <div class="reference-field">
                                    <label>Unit Eselon 2</label>

                                    <div class="select-wrapper">

                                        <select
                                            id="unit2"
                                            class="form-control"
                                            disabled
                                        >
                                            <option value="">
                                                -- Pilih Unit Eselon 1 terlebih dahulu --
                                            </option>
                                        </select>

                                        <i class="bi bi-chevron-down"></i>

                                    </div>
                                </div>

                                <div class="reference-field">
                                    <label>Kode Unit Eselon 2</label>

                                    <input
                                        id="kodeUnit2"
                                        class="form-control"
                                        readonly
                                        placeholder="-"
                                    >
                                </div>

                            </div>

                            <!-- SATKER -->

                            <div class="reference-row">

                                <div class="reference-field">
                                    <label>Satuan Kerja</label>

                                    <div class="select-wrapper">

                                        <select
                                            id="satker"
                                            class="form-control"
                                            disabled
                                        >
                                            <option value="">
                                                -- Pilih Unit Eselon 2 terlebih dahulu --
                                            </option>
                                        </select>

                                        <i class="bi bi-chevron-down"></i>

                                    </div>
                                </div>

                                <div class="reference-field">
                                    <label>Kode Satker</label>

                                    <input
                                        id="kodeSatker"
                                        class="form-control"
                                        readonly
                                        placeholder="-"
                                    >
                                </div>

                            </div>

                            <!-- PROGRAM -->

                            <div class="reference-row">

                                <div class="reference-field">
                                    <label>Program</label>

                                    <div class="select-wrapper">

                                        <select
                                            id="program"
                                            class="form-control"
                                            disabled
                                        >
                                            <option value="">
                                                -- Pilih Satker terlebih dahulu --
                                            </option>
                                        </select>

                                        <i class="bi bi-chevron-down"></i>

                                    </div>
                                </div>

                                <div class="reference-field">
                                    <label>Kode Program</label>

                                    <input
                                        id="kodeProgram"
                                        class="form-control"
                                        readonly
                                        placeholder="-"
                                    >
                                </div>

                            </div>

                            <!-- KEGIATAN -->

                            <div class="reference-row">

                                <div class="reference-field">
                                    <label>Kegiatan</label>

                                    <div class="select-wrapper">

                                        <select
                                            id="kegiatan"
                                            class="form-control"
                                            disabled
                                        >
                                            <option value="">
                                                -- Pilih Program terlebih dahulu --
                                            </option>
                                        </select>

                                        <i class="bi bi-chevron-down"></i>

                                    </div>
                                </div>

                                <div class="reference-field">
                                    <label>Kode Kegiatan</label>

                                    <input
                                        id="kodeKegiatan"
                                        class="form-control"
                                        readonly
                                        placeholder="-"
                                    >
                                </div>

                            </div>

                            <!-- KRO -->

                            <div class="reference-row">

                                <div class="reference-field">
                                    <label>
                                        KRO (Klasifikasi Rincian Output)
                                    </label>

                                    <div class="select-wrapper">

                                        <select
                                            id="kro"
                                            class="form-control"
                                            disabled
                                        >
                                            <option value="">
                                                -- Pilih Kegiatan terlebih dahulu --
                                            </option>
                                        </select>

                                        <i class="bi bi-chevron-down"></i>

                                    </div>
                                </div>

                                <div class="reference-field">
                                    <label>Kode KRO</label>

                                    <input
                                        id="kodeKro"
                                        class="form-control"
                                        readonly
                                        placeholder="-"
                                    >
                                </div>

                            </div>

                            <!-- RO -->

                            <div class="reference-row">

                                <div class="reference-field">
                                    <label>
                                        RO (Rincian Output)
                                    </label>

                                    <div class="select-wrapper">

                                        <select
                                            id="ro"
                                            class="form-control"
                                            disabled
                                        >
                                            <option value="">
                                                -- Pilih KRO terlebih dahulu --
                                            </option>
                                        </select>

                                        <i class="bi bi-chevron-down"></i>

                                    </div>
                                </div>

                                <div class="reference-field">
                                    <label>Kode RO</label>

                                    <input
                                        id="kodeRo"
                                        class="form-control"
                                        readonly
                                        placeholder="-"
                                    >
                                </div>

                            </div>

                        </div>

                        <div class="section-divider"></div>

                        <!-- ===============================
                             STATUS
                        ================================ -->

                        <div class="status-header">

                            <div class="section-title" style="margin:0;">
                                <h2>Status Kelengkapan</h2>
                            </div>

                            <span
                                class="status-counter"
                                id="statusCounter"
                            >
                                0/11 terpenuhi
                            </span>

                        </div>

                        <div class="status-grid">

                            <div class="status-item" data-status="torFile">
                                <span class="status-dot">
                                    <i class="bi bi-check"></i>
                                </span>
                                File TOR
                            </div>

                            <div class="status-item" data-status="torName">
                                <span class="status-dot">
                                    <i class="bi bi-check"></i>
                                </span>
                                Nama TOR
                            </div>

                            <div class="status-item" data-status="rabFile">
                                <span class="status-dot">
                                    <i class="bi bi-check"></i>
                                </span>
                                File RAB
                            </div>

                            <div class="status-item" data-status="rabName">
                                <span class="status-dot">
                                    <i class="bi bi-check"></i>
                                </span>
                                Nama RAB
                            </div>

                            <div class="status-item" data-status="unit1">
                                <span class="status-dot">
                                    <i class="bi bi-check"></i>
                                </span>
                                Unit Eselon
                            </div>

                            <div class="status-item" data-status="satker">
                                <span class="status-dot">
                                    <i class="bi bi-check"></i>
                                </span>
                                Satker
                            </div>

                            <div class="status-item" data-status="program">
                                <span class="status-dot">
                                    <i class="bi bi-check"></i>
                                </span>
                                Program
                            </div>

                            <div class="status-item" data-status="kegiatan">
                                <span class="status-dot">
                                    <i class="bi bi-check"></i>
                                </span>
                                Kegiatan
                            </div>

                            <div class="status-item" data-status="kro">
                                <span class="status-dot">
                                    <i class="bi bi-check"></i>
                                </span>
                                KRO
                            </div>

                            <div class="status-item" data-status="ro">
                                <span class="status-dot">
                                    <i class="bi bi-check"></i>
                                </span>
                                RO
                            </div>

                            <div class="status-item" data-status="unit2">
                                <span class="status-dot">
                                    <i class="bi bi-check"></i>
                                </span>
                                Unit Eselon 2
                            </div>

                        </div>

                        <div class="progress-track">
                            <div
                                class="progress-bar"
                                id="progressBar"
                            ></div>
                        </div>

                        <div
                            class="progress-text"
                            id="progressText"
                        >
                            0%
                        </div>

                        <div class="bottom-actions">

                            <div
                                class="action-message"
                                id="actionMessage"
                            >
                                Lengkapi 11 item yang belum terisi untuk
                                mengaktifkan tombol simpan.
                            </div>

                            <button
                                type="submit"
                                class="save-button"
                                id="saveButton"
                                disabled
                            >
                                <i class="bi bi-upload"></i>
                                Simpan Dokumen TOR & RAB
                            </button>

                        </div>

                    </form>

                </section>

            </div>

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

<div class="toast" id="successToast">

    <i class="bi bi-check-circle-fill"></i>

    <div>
        <strong>Dokumen berhasil disimpan.</strong><br>
        TOR/KAK dan RAB berhasil disimulasikan pada frontend.
    </div>

</div>

<script>
    /*
    |--------------------------------------------------------------------------
    | DATA DUMMY FRONTEND
    |--------------------------------------------------------------------------
    */

    const referenceData = [
        {
            id: "01",
            code: "01",
            name: "Sekretariat Jenderal",
            unit2: [
                {
                    id: "01.01",
                    code: "01.01",
                    name: "Biro Perencanaan",
                    satkers: [
                        {
                            id: "635912",
                            code: "635912",
                            name: "Sekretariat Jenderal Kementerian Perdagangan",
                            programs: [
                                {
                                    id: "WA",
                                    code: "WA",
                                    name: "Program Dukungan Manajemen",
                                    kegiatan: [
                                        {
                                            id: "5048",
                                            code: "5048",
                                            name: "Pengelolaan Perencanaan",
                                            kro: [
                                                {
                                                    id: "EBA",
                                                    code: "EBA",
                                                    name: "Layanan Dukungan Manajemen Internal",
                                                    ro: [
                                                        {
                                                            id: "EBA.994",
                                                            code: "EBA.994",
                                                            name: "Layanan Perkantoran"
                                                        },
                                                        {
                                                            id: "EBA.962",
                                                            code: "EBA.962",
                                                            name: "Layanan Umum"
                                                        }
                                                    ]
                                                }
                                            ]
                                        }
                                    ]
                                }
                            ]
                        }
                    ]
                },
                {
                    id: "01.03",
                    code: "01.03",
                    name: "Biro Keuangan",
                    satkers: [
                        {
                            id: "635912",
                            code: "635912",
                            name: "Sekretariat Jenderal Kementerian Perdagangan",
                            programs: [
                                {
                                    id: "WA",
                                    code: "WA",
                                    name: "Program Dukungan Manajemen",
                                    kegiatan: [
                                        {
                                            id: "5048",
                                            code: "5048",
                                            name: "Pengelolaan Keuangan",
                                            kro: [
                                                {
                                                    id: "EBA",
                                                    code: "EBA",
                                                    name: "Layanan Dukungan Manajemen Internal",
                                                    ro: [
                                                        {
                                                            id: "EBA.994",
                                                            code: "EBA.994",
                                                            name: "Layanan Perkantoran"
                                                        }
                                                    ]
                                                }
                                            ]
                                        }
                                    ]
                                }
                            ]
                        }
                    ]
                }
            ]
        },

        {
            id: "02",
            code: "02",
            name: "Direktorat Jenderal Perdagangan Dalam Negeri",
            unit2: [
                {
                    id: "02.01",
                    code: "02.01",
                    name: "Sekretariat Ditjen Perdagangan Dalam Negeri",
                    satkers: [
                        {
                            id: "635920",
                            code: "635920",
                            name: "Ditjen Perdagangan Dalam Negeri",
                            programs: [
                                {
                                    id: "EE",
                                    code: "EE",
                                    name: "Program Perdagangan Dalam Negeri",
                                    kegiatan: [
                                        {
                                            id: "3702",
                                            code: "3702",
                                            name: "Pengembangan Perdagangan Dalam Negeri",
                                            kro: [
                                                {
                                                    id: "QDC",
                                                    code: "QDC",
                                                    name: "Fasilitasi dan Pembinaan Masyarakat",
                                                    ro: [
                                                        {
                                                            id: "QDC.001",
                                                            code: "QDC.001",
                                                            name: "Pelaku Usaha yang Difasilitasi"
                                                        }
                                                    ]
                                                }
                                            ]
                                        }
                                    ]
                                }
                            ]
                        }
                    ]
                }
            ]
        }
    ];

    /*
    |--------------------------------------------------------------------------
    | ELEMENT
    |--------------------------------------------------------------------------
    */

    const torFile = document.getElementById("torFile");
    const rabFile = document.getElementById("rabFile");

    const torName = document.getElementById("torName");
    const rabName = document.getElementById("rabName");

    const unit1 = document.getElementById("unit1");
    const unit2 = document.getElementById("unit2");
    const satker = document.getElementById("satker");
    const program = document.getElementById("program");
    const kegiatan = document.getElementById("kegiatan");
    const kro = document.getElementById("kro");
    const ro = document.getElementById("ro");

    let torSelectedFile = null;
    let rabSelectedFile = null;

    /*
    |--------------------------------------------------------------------------
    | OPTION HELPER
    |--------------------------------------------------------------------------
    */

    function renderOptions(
        select,
        placeholder,
        data
    ) {
        select.innerHTML = `
            <option value="">
                ${placeholder}
            </option>
        `;

        data.forEach(item => {
            const option =
                document.createElement("option");

            option.value = item.id;
            option.textContent = item.name;
            option.dataset.code = item.code;

            select.appendChild(option);
        });
    }

    renderOptions(
        unit1,
        "-- Pilih Unit Eselon 1 --",
        referenceData
    );

    function findItem(data, id) {
        return data?.find(item => item.id === id);
    }

    /*
    |--------------------------------------------------------------------------
    | CASCADING DROPDOWN
    |--------------------------------------------------------------------------
    */

    unit1.addEventListener("change", () => {
        const data =
            findItem(referenceData, unit1.value);

        document.getElementById("kodeUnit1").value =
            data?.code || "";

        renderOptions(
            unit2,
            "-- Pilih Unit Eselon 2 --",
            data?.unit2 || []
        );

        unit2.disabled = !data;

        resetFrom("unit2");

        updateStatus();
    });

    unit2.addEventListener("change", () => {
        const parent =
            findItem(referenceData, unit1.value);

        const data =
            findItem(parent?.unit2, unit2.value);

        document.getElementById("kodeUnit2").value =
            data?.code || "";

        renderOptions(
            satker,
            "-- Pilih Satuan Kerja --",
            data?.satkers || []
        );

        satker.disabled = !data;

        resetFrom("satker");

        updateStatus();
    });

    satker.addEventListener("change", () => {
        const u1 =
            findItem(referenceData, unit1.value);

        const u2 =
            findItem(u1?.unit2, unit2.value);

        const data =
            findItem(u2?.satkers, satker.value);

        document.getElementById("kodeSatker").value =
            data?.code || "";

        renderOptions(
            program,
            "-- Pilih Program --",
            data?.programs || []
        );

        program.disabled = !data;

        resetFrom("program");

        updateStatus();
    });

    program.addEventListener("change", () => {
        const data = getSelectedProgram();

        document.getElementById("kodeProgram").value =
            data?.code || "";

        renderOptions(
            kegiatan,
            "-- Pilih Kegiatan --",
            data?.kegiatan || []
        );

        kegiatan.disabled = !data;

        resetFrom("kegiatan");

        updateStatus();
    });

    kegiatan.addEventListener("change", () => {
        const parent = getSelectedProgram();

        const data =
            findItem(parent?.kegiatan, kegiatan.value);

        document.getElementById("kodeKegiatan").value =
            data?.code || "";

        renderOptions(
            kro,
            "-- Pilih KRO --",
            data?.kro || []
        );

        kro.disabled = !data;

        resetFrom("kro");

        updateStatus();
    });

    kro.addEventListener("change", () => {
        const kegiatanData =
            getSelectedKegiatan();

        const data =
            findItem(kegiatanData?.kro, kro.value);

        document.getElementById("kodeKro").value =
            data?.code || "";

        renderOptions(
            ro,
            "-- Pilih RO --",
            data?.ro || []
        );

        ro.disabled = !data;

        resetFrom("ro");

        updateStatus();
    });

    ro.addEventListener("change", () => {
        const selected =
            ro.options[ro.selectedIndex];

        document.getElementById("kodeRo").value =
            selected?.dataset.code || "";

        updateStatus();
    });

    function getSelectedSatker() {
        const u1 =
            findItem(referenceData, unit1.value);

        const u2 =
            findItem(u1?.unit2, unit2.value);

        return findItem(
            u2?.satkers,
            satker.value
        );
    }

    function getSelectedProgram() {
        const satkerData =
            getSelectedSatker();

        return findItem(
            satkerData?.programs,
            program.value
        );
    }

    function getSelectedKegiatan() {
        const programData =
            getSelectedProgram();

        return findItem(
            programData?.kegiatan,
            kegiatan.value
        );
    }

    function resetFrom(level) {
        const order = [
            "unit2",
            "satker",
            "program",
            "kegiatan",
            "kro",
            "ro"
        ];

        const index = order.indexOf(level);

        order.slice(index + 1).forEach(id => {
            const select =
                document.getElementById(id);

            select.innerHTML = `
                <option value="">
                    -- Pilih data sebelumnya terlebih dahulu --
                </option>
            `;

            select.disabled = true;
        });

        const codeMap = {
            unit2: "kodeUnit2",
            satker: "kodeSatker",
            program: "kodeProgram",
            kegiatan: "kodeKegiatan",
            kro: "kodeKro",
            ro: "kodeRo"
        };

        order.slice(index + 1).forEach(id => {
            document.getElementById(
                codeMap[id]
            ).value = "";
        });
    }

    /*
    |--------------------------------------------------------------------------
    | FILE UPLOAD
    |--------------------------------------------------------------------------
    */

    document
        .querySelectorAll("[data-choose]")
        .forEach(button => {
            button.addEventListener("click", event => {
                event.stopPropagation();

                document
                    .getElementById(
                        button.dataset.choose
                    )
                    .click();
            });
        });

    function handleFile(type, file) {
        if (!file) {
            return;
        }

        if (type === "tor") {
            torSelectedFile = file;

            document
                .getElementById("torSelected")
                .classList.add("show");

            document.getElementById(
                "torSelectedName"
            ).textContent = file.name;

            document
                .getElementById("torDropzone")
                .classList.add("has-file");
        }

        if (type === "rab") {
            rabSelectedFile = file;

            document
                .getElementById("rabSelected")
                .classList.add("show");

            document.getElementById(
                "rabSelectedName"
            ).textContent = file.name;

            document
                .getElementById("rabDropzone")
                .classList.add("has-file");
        }

        updateStatus();
    }

    torFile.addEventListener("change", () => {
        handleFile(
            "tor",
            torFile.files[0]
        );
    });

    rabFile.addEventListener("change", () => {
        handleFile(
            "rab",
            rabFile.files[0]
        );
    });

    document
        .querySelectorAll("[data-remove]")
        .forEach(button => {
            button.addEventListener("click", event => {
                event.stopPropagation();

                const type =
                    button.dataset.remove;

                if (type === "tor") {
                    torSelectedFile = null;
                    torFile.value = "";

                    document
                        .getElementById("torSelected")
                        .classList.remove("show");

                    document
                        .getElementById("torDropzone")
                        .classList.remove("has-file");
                }

                if (type === "rab") {
                    rabSelectedFile = null;
                    rabFile.value = "";

                    document
                        .getElementById("rabSelected")
                        .classList.remove("show");

                    document
                        .getElementById("rabDropzone")
                        .classList.remove("has-file");
                }

                updateStatus();
            });
        });

    /*
    |--------------------------------------------------------------------------
    | STATUS KELENGKAPAN
    |--------------------------------------------------------------------------
    */

    const statusKeys = [
        "torFile",
        "torName",
        "rabFile",
        "rabName",
        "unit1",
        "unit2",
        "satker",
        "program",
        "kegiatan",
        "kro",
        "ro"
    ];

    function updateStatus() {
        const values = {
            torFile: !!torSelectedFile,
            torName: !!torName.value.trim(),
            rabFile: !!rabSelectedFile,
            rabName: !!rabName.value.trim(),

            unit1: !!unit1.value,
            unit2: !!unit2.value,
            satker: !!satker.value,
            program: !!program.value,
            kegiatan: !!kegiatan.value,
            kro: !!kro.value,
            ro: !!ro.value
        };

        let completed = 0;

        statusKeys.forEach(key => {
            const element =
                document.querySelector(
                    `[data-status="${key}"]`
                );

            element.classList.toggle(
                "complete",
                values[key]
            );

            if (values[key]) {
                completed++;
            }
        });

        const total = statusKeys.length;

        const percent =
            Math.round(
                (completed / total) * 100
            );

        document.getElementById(
            "statusCounter"
        ).textContent =
            `${completed}/${total} terpenuhi`;

        document.getElementById(
            "progressBar"
        ).style.width =
            `${percent}%`;

        document.getElementById(
            "progressText"
        ).textContent =
            `${percent}%`;

        const remaining =
            total - completed;

        const saveButton =
            document.getElementById(
                "saveButton"
            );

        saveButton.disabled =
            completed !== total;

        document.getElementById(
            "actionMessage"
        ).textContent =
            remaining === 0
                ? "Seluruh data telah lengkap. Dokumen siap disimpan."
                : `Lengkapi ${remaining} item yang belum terisi untuk mengaktifkan tombol simpan.`;
    }

    torName.addEventListener(
        "input",
        updateStatus
    );

    rabName.addEventListener(
        "input",
        updateStatus
    );

    /*
    |--------------------------------------------------------------------------
    | SUBMIT FRONTEND
    |--------------------------------------------------------------------------
    */

    document
        .getElementById("torRabForm")
        .addEventListener("submit", event => {
            event.preventDefault();

            const saveButton =
                document.getElementById(
                    "saveButton"
                );

            if (saveButton.disabled) {
                return;
            }

            saveButton.disabled = true;

            saveButton.innerHTML = `
                <i class="bi bi-arrow-repeat"></i>
                Menyimpan...
            `;

            setTimeout(() => {
                const toast =
                    document.getElementById(
                        "successToast"
                    );

                toast.classList.add("show");

                saveButton.innerHTML = `
                    <i class="bi bi-check-circle-fill"></i>
                    Berhasil Disimpan
                `;

                setTimeout(() => {
                    toast.classList.remove("show");

                    saveButton.disabled = false;

                    saveButton.innerHTML = `
                        <i class="bi bi-upload"></i>
                        Simpan Dokumen TOR & RAB
                    `;
                }, 2300);

            }, 800);
        });

    updateStatus();
</script>

</body>

</html>