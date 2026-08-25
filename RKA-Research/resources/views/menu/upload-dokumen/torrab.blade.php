<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Upload TOR & RAB | Penelitian RKA-K/L</title>

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
            background: rgba(255, 255, 255, .96);
            box-shadow: 0 4px 18px rgba(33, 67, 103, .05);
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
            box-shadow: 0 5px 12px rgba(31, 91, 148, .16);
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
            max-width: 1080px;
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
            box-shadow: 0 8px 25px rgba(38, 68, 103, .07);
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
            padding: 16px;
            border: 1px solid #dce5ee;
            border-radius: 14px;
            background: linear-gradient(180deg, #ffffff 0%, #f9fbfd 100%);
            box-shadow: 0 6px 18px rgba(36, 67, 99, .045);
            transition: border-color .2s ease, box-shadow .2s ease, transform .2s ease;
        }

        .document-box:hover {
            border-color: #c7d9ea;
            box-shadow: 0 10px 24px rgba(36, 67, 99, .07);
            transform: translateY(-1px);
        }

        .document-title-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            margin-bottom: 11px;
        }

        .document-title {
            display: flex;
            align-items: center;
            gap: 7px;
            margin: 0;
            color: #334e6d;
            font-size: 10px;
            font-weight: 800;
        }

        .document-title i {
            color: var(--primary);
            font-size: 13px;
        }

        .format-badge {
            flex-shrink: 0;
            padding: 5px 8px;
            border: 1px solid #d7e6f7;
            border-radius: 999px;
            color: #4775a8;
            background: #f1f7ff;
            font-size: 7px;
            font-weight: 800;
            letter-spacing: .2px;
        }

        .upload-zone {
            min-height: 180px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 18px;
            border: 1.5px dashed #b8c9dc;
            border-radius: 12px;
            background: linear-gradient(180deg, #ffffff 0%, #fbfdff 100%);
            cursor: pointer;
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
            height: 33px;
            padding: 0 16px;
            border: 0;
            border-radius: 8px;
            color: #ffffff;
            background: linear-gradient(135deg, var(--primary), #0b73d1);
            box-shadow: 0 6px 14px rgba(7, 89, 183, .16);
            font-size: 8px;
            font-weight: 800;
            transition: transform .18s ease, box-shadow .18s ease;
        }

        .choose-button:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 17px rgba(7, 89, 183, .21);
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
            box-shadow: 0 0 0 3px rgba(7, 89, 183, .07);
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
            background: #ffffff;
            transition: border-color .2s ease, box-shadow .2s ease, background .2s ease;
        }

        .reference-row:hover {
            border-color: #cbdcec;
            box-shadow: 0 5px 14px rgba(42, 74, 108, .045);
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
            transition: .2s ease;
        }

        .reference-row.is-complete {
            border-color: #cce8d8;
            background: #fcfffd;
        }

        .reference-row.is-complete::before {
            content: "✓";
            border-color: #b9e3cb;
            color: #ffffff;
            background: var(--success);
        }

        .reference-field label {
            display: block;
            margin-bottom: 6px;
            color: #526a84;
            font-size: 7.5px;
            font-weight: 800;
            letter-spacing: .3px;
            text-transform: uppercase;
        }

        .select-wrapper {
            position: relative;
        }

        .select-wrapper select {
            height: 42px;
            padding: 0 42px 0 12px;
            border-color: #cedbe8;
            border-radius: 9px;
            color: #284a6f;
            background: linear-gradient(180deg, #ffffff, #fbfdff);
            appearance: none;
            font-size: 8.5px;
            font-weight: 650;
        }

        .select-wrapper select:hover:not(:disabled) {
            border-color: #9fbddd;
        }

        .select-wrapper select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(7, 89, 183, .08);
        }

        .select-wrapper select:disabled {
            color: #97a8b9;
            border-color: #e0e7ee;
            background: #f5f7f9;
            cursor: not-allowed;
        }

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

        .reference-row .reference-field:last-child .form-control {
            height: 42px;
            border: 1px solid #d7e4f1;
            border-radius: 9px;
            color: #165da5;
            background: #f1f7fd;
            font-family: Consolas, "Courier New", monospace;
            font-size: 8px;
            font-weight: 800;
            text-align: center;
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
            box-shadow: 0 12px 30px rgba(30, 60, 90, .14);
            font-size: 8px;
        }

        .toast.show {
            display: flex;
            animation: toastIn .28s ease both;
        }

        .toast strong {
            display: block;
            margin-bottom: 2px;
            font-size: 9px;
        }

        .toast i {
            margin-top: 1px;
            font-size: 16px;
        }

        .toast.toast-error {
            border-color: #f0c2c8;
            color: #a72d3a;
            background: var(--danger-soft);
        }

        .toast.toast-error i {
            color: var(--danger);
        }

        .year-reference-note {
            margin-top: 5px;
            color: var(--text-muted);
            font-size: 7px;
            line-height: 1.4;
        }

        .is-spinning {
            animation: spin .85s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        @keyframes toastIn {
            from {
                opacity: 0;
                transform: translateY(-8px) translateX(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0) translateX(0);
            }
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
                grid-template-columns: 31px minmax(0, 1fr);
                align-items: center;
            }

            .reference-row .reference-field:last-child {
                grid-column: 2;
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
        $userName = data_get($user ?? null, 'name', session('user_name', 'Dr. Siti Rahayu, M.Si'));

        $jabatanName = data_get($user ?? null, 'jabatan.jabatan_name', session('jabatan_name', 'Perencana Ahli Madya'));

        $initials = collect(explode(' ', $userName))
            ->filter()
            ->take(2)
            ->map(fn($word) => strtoupper(substr($word, 0, 1)))
            ->implode('');
    @endphp

    <div class="app-shell">

        @include('partials.sidebar', [
            'activeMenu' => 'upload-tor-rab',
            'sidebarUserName' => $userName,
            // 'sidebarUserRole' => $jabatanName,
            'sidebarInitials' => $initials,
        ])

        <div class="app-main">

            <!-- HEADER -->

            <header class="dashboard-header">

                <div class="header-left">

                    <button type="button" class="sidebar-toggle" id="sidebarToggle" aria-label="Buka menu navigasi">
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

                            Unggah <strong>minimal satu dokumen</strong>: TOR/KAK saja, RAB saja, atau keduanya.
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
                                        Upload Dokumen TOR / RAB
                                    </h1>

                                    <p class="card-description">
                                        Pilih TOR/KAK, RAB, atau keduanya sesuai dokumen yang tersedia.
                                    </p>
                                </div>

                            </div>

                            <div class="card-date">
                                <i class="bi bi-clock"></i>
                                {{ now()->translatedFormat('d F Y') }}
                            </div>

                        </div>

                        <form id="torRabForm" class="main-form" action="{{ route('upload.torrab.store') }}"
                            method="POST" enctype="multipart/form-data" novalidate>

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

                                    <div class="document-title-row">
                                        <div class="document-title">
                                            <i class="bi bi-file-earmark-pdf"></i>
                                            Upload TOR / KAK
                                        </div>
                                        <span class="format-badge">Opsional · PDF saja</span>
                                    </div>

                                    <div class="upload-zone" id="torDropzone">

                                        <input type="file" id="torFile" name="tor_file" class="file-input"
                                            accept=".pdf,application/pdf">

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

                                            <button type="button" class="choose-button" data-choose="torFile">
                                                Pilih File
                                            </button>

                                            <div class="file-format">
                                                PDF saja
                                            </div>

                                            <div class="selected-file" id="torSelected">
                                                <div class="selected-file-info">

                                                    <i class="bi bi-file-earmark-check"></i>

                                                    <span class="selected-file-name" id="torSelectedName"></span>

                                                </div>

                                                <button type="button" class="remove-file" data-remove="tor">
                                                    <i class="bi bi-x-lg"></i>
                                                </button>
                                            </div>

                                        </div>

                                    </div>

                                    <div class="file-error" id="torFileError">
                                        TOR/KAK hanya dapat menggunakan file PDF.
                                    </div>
                                    @error('tor_file')
                                        <div class="file-error show">{{ $message }}</div>
                                    @enderror

                                    <div class="form-group">

                                        <label class="form-label">
                                            Nama File TOR / KAK
                                            <span
                                                style="font-weight:600;text-transform:none;color:var(--text-muted);">(wajib
                                                jika TOR dipilih)</span>
                                        </label>

                                        <input type="text" id="torName" name="tor_name" class="form-control"
                                            value="{{ old('tor_name') }}"
                                            placeholder="Terisi otomatis saat file TOR/KAK dipilih...">

                                    </div>

                                </div>

                                <!-- RAB -->

                                <div class="document-box">

                                    <div class="document-title-row">
                                        <div class="document-title">
                                            <i class="bi bi-file-earmark-spreadsheet"></i>
                                            Upload RAB
                                        </div>
                                        <span class="format-badge">Opsional · PDF / Excel</span>
                                    </div>

                                    <div class="upload-zone" id="rabDropzone">

                                        <input type="file" id="rabFile" name="rab_file" class="file-input"
                                            accept=".pdf,.xlsx,.xls,application/pdf,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel">

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

                                            <button type="button" class="choose-button" data-choose="rabFile">
                                                Pilih File
                                            </button>

                                            <div class="file-format">
                                                PDF, XLSX, atau XLS
                                            </div>

                                            <div class="selected-file" id="rabSelected">
                                                <div class="selected-file-info">

                                                    <i class="bi bi-file-earmark-check"></i>

                                                    <span class="selected-file-name" id="rabSelectedName"></span>

                                                </div>

                                                <button type="button" class="remove-file" data-remove="rab">
                                                    <i class="bi bi-x-lg"></i>
                                                </button>
                                            </div>

                                        </div>

                                    </div>

                                    <div class="file-error" id="rabFileError">
                                        RAB hanya dapat menggunakan file PDF, XLSX, atau XLS.
                                    </div>
                                    @error('rab_file')
                                        <div class="file-error show">{{ $message }}</div>
                                    @enderror

                                    <div class="form-group">

                                        <label class="form-label">
                                            Nama File RAB
                                            <span
                                                style="font-weight:600;text-transform:none;color:var(--text-muted);">(wajib
                                                jika RAB dipilih)</span>
                                        </label>

                                        <input type="text" id="rabName" name="rab_name" class="form-control"
                                            value="{{ old('rab_name') }}"
                                            placeholder="Terisi otomatis saat file RAB dipilih...">

                                    </div>

                                </div>

                            </div>

                            <div
                                style="margin-top:10px;padding:9px 11px;border:1px solid #dbe6f0;border-radius:9px;background:#f8fbfe;color:#6f8297;font-size:7.8px;line-height:1.5;">
                                <i class="bi bi-info-circle" style="margin-right:5px;color:var(--primary);"></i>
                                Minimal satu file harus dipilih. Anda dapat menyimpan <strong>TOR/KAK saja</strong>,
                                <strong>RAB saja</strong>, atau <strong>TOR/KAK dan RAB sekaligus</strong>.
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

                                <!-- TAHUN ANGGARAN -->

                                <div class="reference-row" data-reference="tahunAnggaran">

                                    <div class="reference-field">
                                        <label>
                                            Tahun Anggaran
                                            <span
                                                style="font-weight:600;text-transform:none;color:var(--text-muted);">(wajib
                                                untuk RAB)</span>
                                        </label>

                                        <div class="select-wrapper">
                                            <select id="tahunAnggaran" name="tahun_anggaran" class="form-control">
                                                <option value="">
                                                    -- Pilih Tahun Anggaran --
                                                </option>

                                                @foreach ($tahunAnggaran ?? [] as $tahun)
                                                    <option value="{{ $tahun }}" @selected((string) old('tahun_anggaran') === (string) $tahun)>
                                                        {{ $tahun }}
                                                    </option>
                                                @endforeach
                                            </select>

                                            <i class="bi bi-chevron-down"></i>
                                        </div>

                                        <div class="year-reference-note">
                                            Tahun anggaran hanya diwajibkan apabila dokumen RAB diunggah.
                                        </div>

                                        @error('tahun_anggaran')
                                            <div class="file-error show">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="reference-field">
                                        <label>Status</label>
                                        <input id="statusTahunAnggaran" class="form-control" readonly
                                            value="Opsional untuk TOR">
                                    </div>

                                </div>

                                <!-- UNIT 1 -->

                                <div class="reference-row">

                                    <div class="reference-field">
                                        <label>Unit Eselon 1</label>

                                        <div class="select-wrapper">

                                            <select id="unit1" name="unit_eselon1_id" class="form-control">
                                                <option value="">
                                                    -- Pilih Unit Eselon 1 --
                                                </option>
                                            </select>

                                            <i class="bi bi-chevron-down"></i>

                                        </div>
                                    </div>

                                    <div class="reference-field">
                                        <label>Kode Unit Eselon 1</label>

                                        <input id="kodeUnit1" name="kode_unit_eselon1" class="form-control" readonly
                                            placeholder="-">
                                    </div>

                                </div>

                                <!-- UNIT 2 -->

                                <div class="reference-row">

                                    <div class="reference-field">
                                        <label>Unit Eselon 2</label>

                                        <div class="select-wrapper">

                                            <select id="unit2" name="unit_eselon2_id" class="form-control"
                                                disabled>
                                                <option value="">
                                                    -- Pilih Unit Eselon 1 terlebih dahulu --
                                                </option>
                                            </select>

                                            <i class="bi bi-chevron-down"></i>

                                        </div>
                                    </div>

                                    <div class="reference-field">
                                        <label>Kode Unit Eselon 2</label>

                                        <input id="kodeUnit2" name="kode_unit_eselon2" class="form-control" readonly
                                            placeholder="-">
                                    </div>

                                </div>

                                <!-- SATKER -->

                                <div class="reference-row">

                                    <div class="reference-field">
                                        <label>Satuan Kerja</label>

                                        <div class="select-wrapper">

                                            <select id="satker" name="satker_id" class="form-control" disabled>
                                                <option value="">
                                                    -- Pilih Unit Eselon 2 terlebih dahulu --
                                                </option>
                                            </select>

                                            <i class="bi bi-chevron-down"></i>

                                        </div>
                                    </div>

                                    <div class="reference-field">
                                        <label>Kode Satker</label>

                                        <input id="kodeSatker" name="kode_satker" class="form-control" readonly
                                            placeholder="-">
                                    </div>

                                </div>

                                <!-- PROGRAM -->

                                <div class="reference-row">

                                    <div class="reference-field">
                                        <label>Program</label>

                                        <div class="select-wrapper">

                                            <select id="program" name="program_id" class="form-control" disabled>
                                                <option value="">
                                                    -- Pilih Satker terlebih dahulu --
                                                </option>
                                            </select>

                                            <i class="bi bi-chevron-down"></i>

                                        </div>
                                    </div>

                                    <div class="reference-field">
                                        <label>Kode Program</label>

                                        <input id="kodeProgram" name="kode_program" class="form-control" readonly
                                            placeholder="-">
                                    </div>

                                </div>

                                <!-- KEGIATAN -->

                                <div class="reference-row">

                                    <div class="reference-field">
                                        <label>Kegiatan</label>

                                        <div class="select-wrapper">

                                            <select id="kegiatan" name="kegiatan_id" class="form-control" disabled>
                                                <option value="">
                                                    -- Pilih Program terlebih dahulu --
                                                </option>
                                            </select>

                                            <i class="bi bi-chevron-down"></i>

                                        </div>
                                    </div>

                                    <div class="reference-field">
                                        <label>Kode Kegiatan</label>

                                        <input id="kodeKegiatan" name="kode_kegiatan" class="form-control" readonly
                                            placeholder="-">
                                    </div>

                                </div>

                                <!-- KRO -->

                                <div class="reference-row">

                                    <div class="reference-field">
                                        <label>
                                            KRO (Klasifikasi Rincian Output)
                                        </label>

                                        <div class="select-wrapper">

                                            <select id="kro" name="kro_id" class="form-control" disabled>
                                                <option value="">
                                                    -- Pilih Kegiatan terlebih dahulu --
                                                </option>
                                            </select>

                                            <i class="bi bi-chevron-down"></i>

                                        </div>
                                    </div>

                                    <div class="reference-field">
                                        <label>Kode KRO</label>

                                        <input id="kodeKro" name="kode_kro" class="form-control" readonly
                                            placeholder="-">
                                    </div>

                                </div>

                                <!-- RO -->

                                <div class="reference-row">

                                    <div class="reference-field">
                                        <label>
                                            RO (Rincian Output)
                                        </label>

                                        <div class="select-wrapper">

                                            <select id="ro" name="ro_id" class="form-control" disabled>
                                                <option value="">
                                                    -- Pilih KRO terlebih dahulu --
                                                </option>
                                            </select>

                                            <i class="bi bi-chevron-down"></i>

                                        </div>
                                    </div>

                                    <div class="reference-field">
                                        <label>Kode RO</label>

                                        <input id="kodeRo" name="kode_ro" class="form-control" readonly
                                            placeholder="-">
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

                                <span class="status-counter" id="statusCounter">
                                    0/7 referensi terpenuhi
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

                                <div class="status-item" data-status="tahunAnggaran">
                                    <span class="status-dot">
                                        <i class="bi bi-check"></i>
                                    </span>
                                    Tahun Anggaran (RAB)
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
                                <div class="progress-bar" id="progressBar"></div>
                            </div>

                            <div class="progress-text" id="progressText">
                                0%
                            </div>

                            <div class="bottom-actions">

                                <div class="action-message" id="actionMessage">
                                    Lengkapi referensi anggaran dan pilih minimal satu dokumen.
                                </div>

                                <button type="submit" class="save-button" id="saveButton" disabled>
                                    <i class="bi bi-upload"></i>
                                    Simpan Dokumen
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

    @if (session('success'))
        <div class="toast show" id="successToast" role="status" aria-live="polite">
            <i class="bi bi-check-circle-fill"></i>
            <div>
                <strong>Data Berhasil Disimpan</strong>
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="toast toast-error show" id="errorToast" role="alert" aria-live="assertive">
            <i class="bi bi-exclamation-circle-fill"></i>
            <div>
                <strong>Dokumen Gagal Diproses</strong>
                {{ session('error') }}
            </div>
        </div>
    @endif

    @include('partials.document-processing-modal')
    <script src="{{ asset('js/document-processing-modal.js') }}"></script>

    <script>
        /*
    |--------------------------------------------------------------------------
    | DATA REFERENSI DARI DATABASE
    |--------------------------------------------------------------------------
    |
    | Cascading UI:
    | Unit I -> Unit II -> Satker -> Program -> Kegiatan -> KRO -> RO
    |
    | Relasi database:
    | Unit I -> Unit II -> Satker
    | Satker -> satker_kegiatan -> Kegiatan
    | Program -> Kegiatan
    | Kegiatan -> kegiatan_kro -> KRO
    | Kegiatan + KRO -> RO
    |
    */

        const unitEselon1Data = @json($unitEselon1 ?? []);
        const unitEselon2Data = @json($unitEselon2 ?? []);
        const satkerData = @json($satker ?? []);
        const programData = @json($program ?? []);
        const kegiatanData = @json($kegiatan ?? []);
        const satkerKegiatanData = @json($satkerKegiatan ?? []);
        const kroData = @json($kro ?? []);
        const roData = @json($ro ?? []);

        /*
        |--------------------------------------------------------------------------
        | OLD INPUT
        |--------------------------------------------------------------------------
        |
        | Digunakan agar pilihan dropdown tetap kembali setelah validation error.
        |
        */

        const oldReference = {
            unit1: @json(old('kode_unit_eselon1')),
            unit2: @json(old('kode_unit_eselon2')),
            satker: @json(old('kode_satker')),
            program: @json(old('kode_program')),
            kegiatan: @json(old('kode_kegiatan')),
            kro: @json(old('kode_kro')),
            ro: @json(old('kode_ro')),
        };

        /*
        |--------------------------------------------------------------------------
        | ELEMENT
        |--------------------------------------------------------------------------
        */

        const torFile = document.getElementById("torFile");
        const rabFile = document.getElementById("rabFile");

        const torName = document.getElementById("torName");
        const rabName = document.getElementById("rabName");

        const tahunAnggaran = document.getElementById("tahunAnggaran");
        const statusTahunAnggaran = document.getElementById("statusTahunAnggaran");

        const unit1 = document.getElementById("unit1");
        const unit2 = document.getElementById("unit2");
        const satker = document.getElementById("satker");
        const program = document.getElementById("program");
        const kegiatan = document.getElementById("kegiatan");
        const kro = document.getElementById("kro");
        const ro = document.getElementById("ro");

        const kodeUnit1 = document.getElementById("kodeUnit1");
        const kodeUnit2 = document.getElementById("kodeUnit2");
        const kodeSatker = document.getElementById("kodeSatker");
        const kodeProgram = document.getElementById("kodeProgram");
        const kodeKegiatan = document.getElementById("kodeKegiatan");
        const kodeKro = document.getElementById("kodeKro");
        const kodeRo = document.getElementById("kodeRo");

        let torSelectedFile = null;
        let rabSelectedFile = null;

        /*
        |--------------------------------------------------------------------------
        | DROPDOWN HELPER
        |--------------------------------------------------------------------------
        */

        const asString = value => value === null || value === undefined ?
            "" :
            String(value).trim();

        function sameCode(left, right) {
            return asString(left) === asString(right);
        }

        function uniqueBy(data, key) {
            const seen = new Set();

            return data.filter(item => {
                const value = asString(item?.[key]);

                if (!value || seen.has(value)) {
                    return false;
                }

                seen.add(value);
                return true;
            });
        }

        function renderOptions({
            select,
            data,
            valueKey,
            labelKey,
            placeholder,
            selectedValue = "",
            emptyText = "-- Data tidak tersedia --"
        }) {
            select.innerHTML = "";

            const optionPlaceholder = document.createElement("option");
            optionPlaceholder.value = "";
            optionPlaceholder.textContent = data.length ? placeholder : emptyText;
            select.appendChild(optionPlaceholder);

            data.forEach(item => {
                const code = asString(item?.[valueKey]);
                const name = asString(item?.[labelKey]);

                if (!code) {
                    return;
                }

                const option = document.createElement("option");
                option.value = code;
                option.textContent = name ? `[${code}] ${name}` : `[${code}]`;
                option.dataset.code = code;
                option.dataset.name = name;

                select.appendChild(option);
            });

            select.disabled = data.length === 0;

            if (selectedValue && data.some(item => sameCode(item?.[valueKey], selectedValue))) {
                select.value = asString(selectedValue);
            } else {
                select.value = "";
            }
        }

        function resetSelect(select, placeholder) {
            select.innerHTML = `<option value="">${placeholder}</option>`;
            select.disabled = true;
        }

        function clearCodesFrom(level) {
            const codeMap = {
                unit1: kodeUnit1,
                unit2: kodeUnit2,
                satker: kodeSatker,
                program: kodeProgram,
                kegiatan: kodeKegiatan,
                kro: kodeKro,
                ro: kodeRo,
            };

            const order = [
                "unit1",
                "unit2",
                "satker",
                "program",
                "kegiatan",
                "kro",
                "ro",
            ];

            const index = order.indexOf(level);

            order.slice(index).forEach(key => {
                if (codeMap[key]) {
                    codeMap[key].value = "";
                }
            });
        }

        function resetChildrenFrom(level) {
            const hierarchy = {
                unit1: [
                    [unit2, "-- Pilih Unit Eselon 1 terlebih dahulu --"],
                    [satker, "-- Pilih Unit Eselon 2 terlebih dahulu --"],
                    [program, "-- Pilih Satker terlebih dahulu --"],
                    [kegiatan, "-- Pilih Program terlebih dahulu --"],
                    [kro, "-- Pilih Kegiatan terlebih dahulu --"],
                    [ro, "-- Pilih KRO terlebih dahulu --"],
                ],
                unit2: [
                    [satker, "-- Pilih Unit Eselon 2 terlebih dahulu --"],
                    [program, "-- Pilih Satker terlebih dahulu --"],
                    [kegiatan, "-- Pilih Program terlebih dahulu --"],
                    [kro, "-- Pilih Kegiatan terlebih dahulu --"],
                    [ro, "-- Pilih KRO terlebih dahulu --"],
                ],
                satker: [
                    [program, "-- Pilih Satker terlebih dahulu --"],
                    [kegiatan, "-- Pilih Program terlebih dahulu --"],
                    [kro, "-- Pilih Kegiatan terlebih dahulu --"],
                    [ro, "-- Pilih KRO terlebih dahulu --"],
                ],
                program: [
                    [kegiatan, "-- Pilih Program terlebih dahulu --"],
                    [kro, "-- Pilih Kegiatan terlebih dahulu --"],
                    [ro, "-- Pilih KRO terlebih dahulu --"],
                ],
                kegiatan: [
                    [kro, "-- Pilih Kegiatan terlebih dahulu --"],
                    [ro, "-- Pilih KRO terlebih dahulu --"],
                ],
                kro: [
                    [ro, "-- Pilih KRO terlebih dahulu --"],
                ],
            };

            (hierarchy[level] || []).forEach(([select, placeholder]) => {
                resetSelect(select, placeholder);
            });

            const codeChildren = {
                unit1: [kodeUnit2, kodeSatker, kodeProgram, kodeKegiatan, kodeKro, kodeRo],
                unit2: [kodeSatker, kodeProgram, kodeKegiatan, kodeKro, kodeRo],
                satker: [kodeProgram, kodeKegiatan, kodeKro, kodeRo],
                program: [kodeKegiatan, kodeKro, kodeRo],
                kegiatan: [kodeKro, kodeRo],
                kro: [kodeRo],
            };

            (codeChildren[level] || []).forEach(input => {
                input.value = "";
            });
        }

        function getKegiatanCodesForSatker(kodeSatkerValue) {
            return new Set(
                satkerKegiatanData
                .filter(item => sameCode(item.kode_satker, kodeSatkerValue))
                .map(item => asString(item.kode_kegiatan))
                .filter(Boolean)
            );
        }

        function populateUnit2(selectedValue = "") {
            const parentCode = asString(unit1.value);

            if (!parentCode) {
                resetSelect(unit2, "-- Pilih Unit Eselon 1 terlebih dahulu --");
                return;
            }

            const filtered = unitEselon2Data.filter(
                item => sameCode(item.kode_unit_eselon1, parentCode)
            );

            renderOptions({
                select: unit2,
                data: filtered,
                valueKey: "kode_unit_eselon2",
                labelKey: "nama_unit_eselon2",
                placeholder: "-- Pilih Unit Eselon 2 --",
                selectedValue,
                emptyText: "-- Unit Eselon 2 tidak tersedia --"
            });
        }

        function populateSatker(selectedValue = "") {
            const parentCode = asString(unit2.value);

            if (!parentCode) {
                resetSelect(satker, "-- Pilih Unit Eselon 2 terlebih dahulu --");
                return;
            }

            const filtered = satkerData.filter(
                item => sameCode(item.kode_unit_eselon2, parentCode)
            );

            renderOptions({
                select: satker,
                data: uniqueBy(filtered, "kode_satker"),
                valueKey: "kode_satker",
                labelKey: "nama_satker",
                placeholder: "-- Pilih Satuan Kerja --",
                selectedValue,
                emptyText: "-- Satuan Kerja tidak tersedia --"
            });
        }

        function populateProgram(selectedValue = "") {
            const selectedSatker = asString(satker.value);

            if (!selectedSatker) {
                resetSelect(program, "-- Pilih Satker terlebih dahulu --");
                return;
            }

            const kodeKegiatanSatker = getKegiatanCodesForSatker(selectedSatker);

            const kodeProgramSatker = new Set(
                kegiatanData
                .filter(item => kodeKegiatanSatker.has(asString(item.kode_kegiatan)))
                .map(item => asString(item.kode_program))
                .filter(Boolean)
            );

            const filtered = programData.filter(
                item => kodeProgramSatker.has(asString(item.kode_program))
            );

            renderOptions({
                select: program,
                data: uniqueBy(filtered, "kode_program"),
                valueKey: "kode_program",
                labelKey: "nama_program",
                placeholder: "-- Pilih Program --",
                selectedValue,
                emptyText: "-- Program untuk Satker ini tidak tersedia --"
            });
        }

        function populateKegiatan(selectedValue = "") {
            const selectedSatker = asString(satker.value);
            const selectedProgram = asString(program.value);

            if (!selectedSatker || !selectedProgram) {
                resetSelect(kegiatan, "-- Pilih Program terlebih dahulu --");
                return;
            }

            const kodeKegiatanSatker = getKegiatanCodesForSatker(selectedSatker);

            const filtered = kegiatanData.filter(item =>
                kodeKegiatanSatker.has(asString(item.kode_kegiatan)) &&
                sameCode(item.kode_program, selectedProgram)
            );

            renderOptions({
                select: kegiatan,
                data: uniqueBy(filtered, "kode_kegiatan"),
                valueKey: "kode_kegiatan",
                labelKey: "nama_kegiatan",
                placeholder: "-- Pilih Kegiatan --",
                selectedValue,
                emptyText: "-- Kegiatan tidak tersedia --"
            });
        }

        function populateKro(selectedValue = "") {
            const selectedKegiatan = asString(kegiatan.value);

            if (!selectedKegiatan) {
                resetSelect(kro, "-- Pilih Kegiatan terlebih dahulu --");
                return;
            }

            const filtered = kroData.filter(
                item => sameCode(item.kode_kegiatan, selectedKegiatan)
            );

            renderOptions({
                select: kro,
                data: filtered,
                valueKey: "kode_kro",
                labelKey: "nama_kro",
                placeholder: "-- Pilih KRO --",
                selectedValue,
                emptyText: "-- KRO untuk Kegiatan ini tidak tersedia --"
            });
        }

        function populateRo(selectedValue = "") {
            const selectedKegiatan = asString(kegiatan.value);
            const selectedKro = asString(kro.value);

            if (!selectedKegiatan || !selectedKro) {
                resetSelect(ro, "-- Pilih KRO terlebih dahulu --");
                return;
            }

            const filtered = roData.filter(item =>
                sameCode(item.kode_kegiatan, selectedKegiatan) &&
                sameCode(item.kode_kro, selectedKro)
            );

            renderOptions({
                select: ro,
                data: filtered,
                valueKey: "kode_ro",
                labelKey: "nama_ro",
                placeholder: "-- Pilih RO --",
                selectedValue,
                emptyText: "-- RO untuk KRO ini tidak tersedia --"
            });
        }

        /*
        |--------------------------------------------------------------------------
        | CASCADING DROPDOWN EVENT
        |--------------------------------------------------------------------------
        */

        unit1.addEventListener("change", () => {
            kodeUnit1.value = asString(unit1.value);
            resetChildrenFrom("unit1");
            populateUnit2();
            updateStatus();
        });

        unit2.addEventListener("change", () => {
            kodeUnit2.value = asString(unit2.value);
            resetChildrenFrom("unit2");
            populateSatker();
            updateStatus();
        });

        satker.addEventListener("change", () => {
            kodeSatker.value = asString(satker.value);
            resetChildrenFrom("satker");
            populateProgram();
            updateStatus();
        });

        program.addEventListener("change", () => {
            kodeProgram.value = asString(program.value);
            resetChildrenFrom("program");
            populateKegiatan();
            updateStatus();
        });

        kegiatan.addEventListener("change", () => {
            kodeKegiatan.value = asString(kegiatan.value);
            resetChildrenFrom("kegiatan");
            populateKro();
            updateStatus();
        });

        kro.addEventListener("change", () => {
            kodeKro.value = asString(kro.value);
            resetChildrenFrom("kro");
            populateRo();
            updateStatus();
        });

        ro.addEventListener("change", () => {
            kodeRo.value = asString(ro.value);
            updateStatus();
        });

        tahunAnggaran.addEventListener("change", updateStatus);

        /*
        |--------------------------------------------------------------------------
        | INITIALIZE DROPDOWN
        |--------------------------------------------------------------------------
        */

        function initializeReferenceDropdowns() {
            renderOptions({
                select: unit1,
                data: unitEselon1Data,
                valueKey: "kode_unit_eselon1",
                labelKey: "nama_unit_eselon1",
                placeholder: "-- Pilih Unit Eselon 1 --",
                selectedValue: oldReference.unit1,
                emptyText: "-- Data Unit Eselon 1 tidak tersedia --"
            });

            kodeUnit1.value = asString(unit1.value);

            if (!unit1.value) {
                resetChildrenFrom("unit1");
                return;
            }

            populateUnit2(oldReference.unit2);
            kodeUnit2.value = asString(unit2.value);

            if (!unit2.value) {
                resetChildrenFrom("unit2");
                return;
            }

            populateSatker(oldReference.satker);
            kodeSatker.value = asString(satker.value);

            if (!satker.value) {
                resetChildrenFrom("satker");
                return;
            }

            populateProgram(oldReference.program);
            kodeProgram.value = asString(program.value);

            if (!program.value) {
                resetChildrenFrom("program");
                return;
            }

            populateKegiatan(oldReference.kegiatan);
            kodeKegiatan.value = asString(kegiatan.value);

            if (!kegiatan.value) {
                resetChildrenFrom("kegiatan");
                return;
            }

            populateKro(oldReference.kro);
            kodeKro.value = asString(kro.value);

            if (!kro.value) {
                resetChildrenFrom("kro");
                return;
            }

            populateRo(oldReference.ro);
            kodeRo.value = asString(ro.value);
        }

        initializeReferenceDropdowns();

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

        const allowedFileTypes = {
            tor: {
                extensions: ["pdf"],
                message: "TOR/KAK hanya dapat menggunakan file PDF."
            },
            rab: {
                extensions: ["pdf", "xlsx", "xls"],
                message: "RAB hanya dapat menggunakan file PDF, XLSX, atau XLS."
            }
        };

        function getFileExtension(filename) {
            return filename.includes(".") ?
                filename.split(".").pop().toLowerCase() :
                "";
        }

        function setFileError(type, message = "") {
            const error = document.getElementById(`${type}FileError`);
            const dropzone = document.getElementById(`${type}Dropzone`);

            error.textContent = message;
            error.classList.toggle("show", !!message);
            dropzone.classList.toggle("has-error", !!message);
        }

        function clearSelectedFile(type) {
            const input = type === "tor" ? torFile : rabFile;
            const nameInput = type === "tor" ? torName : rabName;

            input.value = "";
            nameInput.value = "";
            nameInput.required = false;

            if (type === "tor") {
                torSelectedFile = null;
            } else {
                rabSelectedFile = null;
            }

            document.getElementById(`${type}Selected`).classList.remove("show");
            document.getElementById(`${type}Dropzone`).classList.remove("has-file");
        }

        function handleFile(type, file) {
            if (!file) {
                return false;
            }

            const extension = getFileExtension(file.name);
            const config = allowedFileTypes[type];

            if (!config.extensions.includes(extension)) {
                clearSelectedFile(type);
                setFileError(type, config.message);
                updateStatus();
                return false;
            }

            setFileError(type);

            // Jika salah satu dokumen sudah valid, dokumen lainnya bersifat opsional.
            const otherType = type === "tor" ? "rab" : "tor";
            const otherFile = otherType === "tor" ? torSelectedFile : rabSelectedFile;
            if (!otherFile) {
                setFileError(otherType);
            }

            const selected = document.getElementById(`${type}Selected`);
            const selectedName = document.getElementById(`${type}SelectedName`);
            const dropzone = document.getElementById(`${type}Dropzone`);
            const nameInput = type === "tor" ? torName : rabName;

            if (type === "tor") {
                torSelectedFile = file;
            } else {
                rabSelectedFile = file;
            }

            selected.classList.add("show");
            selectedName.textContent = file.name;
            dropzone.classList.add("has-file");

            nameInput.required = true;

            if (!nameInput.value.trim()) {
                nameInput.value = file.name.replace(/\.[^/.]+$/, "");
            }

            updateStatus();
            return true;
        }

        function assignDroppedFile(input, file) {
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            input.files = dataTransfer.files;
        }

        function setupDropzone(type, dropzone, input) {
            ["dragenter", "dragover"].forEach(eventName => {
                dropzone.addEventListener(eventName, event => {
                    event.preventDefault();
                    event.stopPropagation();
                    dropzone.classList.add("dragover");
                });
            });

            ["dragleave", "drop"].forEach(eventName => {
                dropzone.addEventListener(eventName, event => {
                    event.preventDefault();
                    event.stopPropagation();
                    dropzone.classList.remove("dragover");
                });
            });

            dropzone.addEventListener("drop", event => {
                const file = event.dataTransfer?.files?.[0];

                if (!file) {
                    return;
                }

                assignDroppedFile(input, file);
                handleFile(type, file);
            });

            dropzone.addEventListener("click", event => {
                if (event.target.closest("button")) {
                    return;
                }
                input.click();
            });
        }

        torFile.addEventListener("change", () => {
            handleFile("tor", torFile.files[0]);
        });

        rabFile.addEventListener("change", () => {
            handleFile("rab", rabFile.files[0]);
        });

        setupDropzone(
            "tor",
            document.getElementById("torDropzone"),
            torFile
        );

        setupDropzone(
            "rab",
            document.getElementById("rabDropzone"),
            rabFile
        );

        document
            .querySelectorAll("[data-remove]")
            .forEach(button => {
                button.addEventListener("click", event => {
                    event.stopPropagation();

                    const type =
                        button.dataset.remove;

                    clearSelectedFile(type);
                    setFileError(type);
                    updateStatus();
                });
            });

        /*
        |--------------------------------------------------------------------------
        | STATUS KELENGKAPAN
        |--------------------------------------------------------------------------
        */

        const referenceStatusKeys = [
            "unit1",
            "unit2",
            "satker",
            "program",
            "kegiatan",
            "kro",
            "ro"
        ];

        const documentStatusKeys = [
            "torFile",
            "torName",
            "rabFile",
            "rabName"
        ];

        function getUploadMode() {
            const hasTor = !!torSelectedFile;
            const hasRab = !!rabSelectedFile;

            if (hasTor && hasRab) return "both";
            if (hasTor) return "tor";
            if (hasRab) return "rab";
            return "none";
        }

        function updateDocumentStatusVisual(key, complete, active) {
            const element = document.querySelector(`[data-status="${key}"]`);
            if (!element) return;

            element.classList.toggle("complete", !!complete);
            element.style.opacity = active ? "1" : ".42";
        }

        function updateStatus() {
            const mode = getUploadMode();

            const referenceValues = {
                unit1: !!unit1.value,
                unit2: !!unit2.value,
                satker: !!satker.value,
                program: !!program.value,
                kegiatan: !!kegiatan.value,
                kro: !!kro.value,
                ro: !!ro.value
            };

            const documentValues = {
                torFile: !!torSelectedFile,
                torName: !!torSelectedFile && !!torName.value.trim(),
                rabFile: !!rabSelectedFile,
                rabName: !!rabSelectedFile && !!rabName.value.trim()
            };

            let referenceCompleted = 0;

            referenceStatusKeys.forEach(key => {
                const element = document.querySelector(`[data-status="${key}"]`);
                const complete = referenceValues[key];

                if (element) {
                    element.classList.toggle("complete", complete);
                }

                if (complete) referenceCompleted++;
            });

            const torActive = mode === "tor" || mode === "both";
            const rabActive = mode === "rab" || mode === "both";
            const tahunAnggaranComplete = !!tahunAnggaran.value;

            updateDocumentStatusVisual("torFile", documentValues.torFile, torActive);
            updateDocumentStatusVisual("torName", documentValues.torName, torActive);
            updateDocumentStatusVisual("rabFile", documentValues.rabFile, rabActive);
            updateDocumentStatusVisual("rabName", documentValues.rabName, rabActive);
            updateDocumentStatusVisual(
                "tahunAnggaran",
                tahunAnggaranComplete,
                rabActive
            );

            statusTahunAnggaran.value = rabActive ?
                (tahunAnggaranComplete ? "Siap digunakan" : "Wajib untuk RAB") :
                "Opsional untuk TOR";

            const requiredDocumentKeys = [];
            if (torActive) requiredDocumentKeys.push("torFile", "torName");
            if (rabActive) requiredDocumentKeys.push("rabFile", "rabName");

            const documentCompleted = requiredDocumentKeys.filter(
                key => documentValues[key]
            ).length;

            const tahunRequiredCount = rabActive ? 1 : 0;
            const tahunCompletedCount = rabActive && tahunAnggaranComplete ? 1 : 0;

            const totalRequired =
                referenceStatusKeys.length +
                requiredDocumentKeys.length +
                tahunRequiredCount;

            const completed =
                referenceCompleted +
                documentCompleted +
                tahunCompletedCount;

            const hasDocument = mode !== "none";
            const allReferencesComplete =
                referenceCompleted === referenceStatusKeys.length &&
                (!rabActive || tahunAnggaranComplete);

            const selectedDocumentsComplete =
                hasDocument &&
                documentCompleted === requiredDocumentKeys.length;

            const formReady =
                allReferencesComplete &&
                selectedDocumentsComplete;

            const percent = totalRequired > 0 ?
                Math.round((completed / totalRequired) * 100) :
                0;

            const modeLabel = mode === "both" ?
                "TOR + RAB" :
                mode === "tor" ?
                "TOR" :
                mode === "rab" ?
                "RAB" :
                "Belum ada dokumen";

            document.getElementById("statusCounter").textContent =
                `${completed}/${totalRequired} terpenuhi · ${modeLabel}`;

            document.getElementById("progressBar").style.width = `${percent}%`;
            document.getElementById("progressText").textContent = `${percent}%`;

            document.querySelectorAll(".reference-row").forEach(row => {
                const select = row.querySelector("select");
                const isYearRow = row.dataset.reference === "tahunAnggaran";

                if (isYearRow) {
                    row.classList.toggle(
                        "is-complete",
                        rabActive && !!select?.value
                    );
                    row.style.opacity = rabActive ? "1" : ".72";
                    return;
                }

                row.classList.toggle("is-complete", !!select?.value);
                row.style.opacity = "1";
            });

            const saveButton = document.getElementById("saveButton");
            const actionMessage = document.getElementById("actionMessage");

            saveButton.disabled = !formReady;

            if (mode === "tor") {
                saveButton.innerHTML = `<i class="bi bi-upload"></i> Simpan Dokumen TOR/KAK`;
            } else if (mode === "rab") {
                saveButton.innerHTML = `<i class="bi bi-upload"></i> Simpan Dokumen RAB`;
            } else if (mode === "both") {
                saveButton.innerHTML = `<i class="bi bi-upload"></i> Simpan TOR/KAK & RAB`;
            } else {
                saveButton.innerHTML = `<i class="bi bi-upload"></i> Simpan Dokumen`;
            }

            if (!hasDocument) {
                actionMessage.textContent =
                    "Pilih minimal satu dokumen: TOR/KAK atau RAB.";
                return;
            }

            if (rabActive && !tahunAnggaranComplete) {
                actionMessage.textContent =
                    "Pilih Tahun Anggaran untuk dokumen RAB.";
                return;
            }

            if (referenceCompleted !== referenceStatusKeys.length) {
                const remainingReferences =
                    referenceStatusKeys.length - referenceCompleted;

                actionMessage.textContent =
                    `Lengkapi ${remainingReferences} referensi anggaran yang belum terisi.`;
                return;
            }

            if (torActive && !torName.value.trim()) {
                actionMessage.textContent = "Isi nama dokumen TOR/KAK yang dipilih.";
                return;
            }

            if (rabActive && !rabName.value.trim()) {
                actionMessage.textContent = "Isi nama dokumen RAB yang dipilih.";
                return;
            }

            actionMessage.textContent = mode === "both" ?
                "Referensi dan kedua dokumen telah lengkap. Siap disimpan." :
                `Referensi dan dokumen ${modeLabel} telah lengkap. Siap disimpan.`;
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
        | SUBMIT KE BACKEND
        |--------------------------------------------------------------------------
        */

        function ajaxHttpMessage(status) {
            const messages = {
                400: "Permintaan tidak dapat diproses karena data request tidak valid.",
                401: "Sesi atau autentikasi tidak valid.",
                403: "Anda tidak memiliki izin untuk menjalankan proses ini.",
                413: "Ukuran dokumen terlalu besar untuk diproses.",
                419: "Sesi halaman telah berakhir. Silakan muat ulang halaman lalu coba kembali.",
                422: "Data yang dikirim belum lengkap atau tidak valid.",
                429: "Layanan Gemini sedang menerima terlalu banyak permintaan. Silakan tunggu beberapa saat lalu coba kembali.",
                500: "Terjadi kesalahan internal pada server saat memproses dokumen.",
                502: "Terjadi gangguan saat server menghubungi layanan eksternal.",
                503: "Layanan Gemini atau server sedang tidak tersedia. Silakan coba kembali beberapa saat lagi.",
                504: "Proses layanan eksternal melebihi batas waktu. Silakan coba kembali."
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
                    message: response.ok ? "" : ajaxHttpMessage(response.status)
                };
            }
        }

        async function submitTorRabWithAjax(form) {
            const response = await fetch(form.action, {
                method: "POST",
                body: new FormData(form),
                credentials: "same-origin",
                headers: {
                    "Accept": "application/json",
                    "X-Requested-With": "XMLHttpRequest"
                }
            });

            const payload = await readAjaxPayload(response);

            if (!response.ok || payload.success === false) {
                const error = new Error(
                    payload.message ||
                    ajaxHttpMessage(response.status)
                );

                error.title = payload.title || "Dokumen Gagal Diproses";
                error.status = response.status;
                error.details = validationDetails(payload);

                if (Array.isArray(payload.errors)) {
                    error.details.push(
                        ...payload.errors
                        .map(item => item?.message)
                        .filter(Boolean)
                    );
                }

                throw error;
            }

            return payload;
        }

        document
            .getElementById("torRabForm")
            .addEventListener("submit", async event => {
                event.preventDefault();

                const form = event.currentTarget;
                const saveButton = document.getElementById("saveButton");
                const hasTor = torFile.files.length > 0;
                const hasRab = rabFile.files.length > 0;

                if (!hasTor && !hasRab) {
                    setFileError("tor", "Pilih TOR/KAK atau RAB. Minimal satu dokumen wajib diunggah.");
                    setFileError("rab", "Pilih TOR/KAK atau RAB. Minimal satu dokumen wajib diunggah.");
                    updateStatus();
                    return;
                }

                if (hasTor && !handleFile("tor", torFile.files[0])) {
                    return;
                }

                if (hasRab && !handleFile("rab", rabFile.files[0])) {
                    return;
                }

                if (hasTor && !torName.value.trim()) {
                    torName.focus();
                    updateStatus();
                    return;
                }

                if (hasRab && !rabName.value.trim()) {
                    rabName.focus();
                    updateStatus();
                    return;
                }

                if (hasRab && !tahunAnggaran.value) {
                    tahunAnggaran.focus();
                    updateStatus();
                    return;
                }

                updateStatus();

                if (saveButton.disabled) {
                    return;
                }

                const mode = getUploadMode();
                const loadingTitle = mode === "both" ?
                    "Memproses TOR/KAK dan RAB" :
                    mode === "tor" ?
                    "Memproses Dokumen TOR/KAK" :
                    "Memproses Dokumen RAB";

                const loadingMessage = mode === "both" ?
                    "Dokumen sedang diunggah dan diproses. TOR/KAK akan dianalisis dengan Gemini AI, sedangkan RAB diproses sesuai format file. Mohon tunggu hingga seluruh proses selesai." :
                    mode === "tor" ?
                    "Dokumen TOR/KAK sedang diunggah dan dianalisis dengan Gemini AI. Mohon tunggu hingga proses selesai." :
                    "Dokumen RAB sedang diunggah dan diproses. PDF akan dianalisis dengan Gemini AI, sedangkan Excel diproses oleh parser aplikasi. Mohon tunggu hingga proses selesai.";

                saveButton.disabled = true;
                saveButton.innerHTML = `
                <i class="bi bi-arrow-repeat is-spinning"></i>
                Memproses dokumen...
            `;

                DocumentProcessingModal.showLoading({
                    title: loadingTitle,
                    message: loadingMessage
                });

                try {
                    const payload = await submitTorRabWithAjax(form);

                    DocumentProcessingModal.showSuccess({
                        title: payload.title || "Dokumen Berhasil Diproses",
                        message: payload.message || "Dokumen berhasil diproses dan disimpan.",
                        buttonText: "OKE",
                        onClose: () => {
                            clearSelectedFile("tor");
                            clearSelectedFile("rab");
                            torName.value = "";
                            rabName.value = "";
                            updateStatus();
                        }
                    });
                } catch (error) {
                    DocumentProcessingModal.showError({
                        title: error.title || "Dokumen Gagal Diproses",
                        message: error.message || "Terjadi kesalahan saat memproses dokumen.",
                        details: error.details || [],
                        buttonText: "TUTUP",
                        onClose: () => {
                            updateStatus();
                        }
                    });
                }
            });

        const successToast = document.getElementById("successToast");
        const errorToast = document.getElementById("errorToast");

        if (successToast) {
            setTimeout(() => {
                successToast.classList.remove("show");
            }, 3200);
        }

        if (errorToast) {
            setTimeout(() => {
                errorToast.classList.remove("show");
            }, 6500);
        }

        updateStatus();
    </script>

</body>

</html>
