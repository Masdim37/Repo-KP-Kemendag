<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="color-scheme" content="light">
    <title>Login | Sistem Informasi Penelitian RKA-K/L</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        :root {
            --primary: #0759b7;
            --primary-dark: #06498f;
            --primary-deep: #063c7c;
            --primary-bright: #0878d4;
            --primary-soft: #edf5ff;
            --primary-border: #cfe2f8;

            --text-primary: #18365b;
            --text-secondary: #607995;
            --text-muted: #879bb1;

            --background: #f5f8fc;
            --surface: #ffffff;
            --border: #dbe5ee;

            --success: #147348;
            --success-soft: #e9f8ef;
            --danger: #b42f40;
            --danger-soft: #fff0f2;

            --shadow: 0 10px 28px rgba(27, 70, 112, 0.07);
            --shadow-strong: 0 22px 60px rgba(20, 60, 102, 0.12);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
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

        a,
        button,
        input {
            font: inherit;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        button,
        input {
            outline: none;
        }

        .login-shell {
            min-height: 100vh;
            display: grid;
            grid-template-columns: minmax(560px, 1.1fr) minmax(440px, 0.9fr);
        }

        /* ================================================================
         * BRAND / BUSINESS PANEL
         * ================================================================ */
        .brand-panel {
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            padding: 42px 52px 36px;
            color: #ffffff;
            background: linear-gradient(155deg, #06356c 0%, #064996 48%, #0872cf 100%);
        }

        .brand-panel::before,
        .brand-panel::after {
            content: "";
            position: absolute;
            border-radius: 50%;
            pointer-events: none;
        }

        .brand-panel::before {
            width: 520px;
            height: 520px;
            top: -245px;
            right: -205px;
            border: 1px solid rgba(255, 255, 255, 0.09);
            box-shadow:
                0 0 0 65px rgba(255, 255, 255, 0.025),
                0 0 0 130px rgba(255, 255, 255, 0.018);
        }

        .brand-panel::after {
            width: 360px;
            height: 360px;
            bottom: -225px;
            left: -165px;
            background: rgba(255, 255, 255, 0.035);
        }

        .brand-top,
        .brand-content,
        .brand-footer {
            position: relative;
            z-index: 1;
        }

        .brand-top {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .brand-logo-wrap {
            width: 58px;
            height: 58px;
            flex: 0 0 auto;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 6px;
            border: 1px solid rgba(255, 255, 255, 0.22);
            border-radius: 15px;
            background: rgba(255, 255, 255, 0.96);
            box-shadow: 0 10px 28px rgba(3, 32, 68, 0.18);
        }

        .brand-logo {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }

        .brand-ministry {
            min-width: 0;
        }

        .brand-ministry small {
            display: block;
            margin-bottom: 4px;
            color: rgba(232, 243, 255, 0.74);
            font-size: 8px;
            font-weight: 800;
            letter-spacing: 1.25px;
            text-transform: uppercase;
        }

        .brand-ministry strong {
            display: block;
            font-size: 13px;
            font-weight: 850;
            line-height: 1.35;
        }

        .brand-content {
            width: 100%;
            max-width: 760px;
            margin: auto 0;
            padding: 52px 0 44px;
        }

        .system-chip {
            width: fit-content;
            display: inline-flex;
            align-items: center;
            gap: 7px;
            min-height: 28px;
            padding: 0 11px;
            border: 1px solid rgba(255, 255, 255, 0.18);
            border-radius: 999px;
            color: #e7f2ff;
            background: rgba(255, 255, 255, 0.09);
            backdrop-filter: blur(8px);
            font-size: 8px;
            font-weight: 850;
            letter-spacing: 0.85px;
            text-transform: uppercase;
        }

        .system-chip i {
            font-size: 10px;
        }

        .brand-title {
            max-width: 690px;
            margin-top: 18px;
            color: #ffffff;
            font-size: clamp(34px, 4vw, 52px);
            font-weight: 900;
            line-height: 1.08;
            letter-spacing: -1.6px;
        }

        .brand-title span {
            color: #a9d4ff;
        }

        .brand-description {
            max-width: 670px;
            margin-top: 17px;
            color: rgba(230, 241, 255, 0.78);
            font-size: 11px;
            line-height: 1.8;
        }

        .business-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 11px;
            margin-top: 28px;
        }

        .business-card {
            min-width: 0;
            min-height: 95px;
            display: flex;
            align-items: flex-start;
            gap: 11px;
            padding: 14px;
            border: 1px solid rgba(255, 255, 255, 0.13);
            border-radius: 14px;
            background: rgba(255, 255, 255, 0.075);
            backdrop-filter: blur(8px);
        }

        .business-icon {
            width: 36px;
            height: 36px;
            flex: 0 0 auto;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid rgba(255, 255, 255, 0.18);
            border-radius: 11px;
            color: #ffffff;
            background: rgba(255, 255, 255, 0.105);
            font-size: 14px;
        }

        .business-copy {
            min-width: 0;
        }

        .business-copy strong {
            display: block;
            color: #ffffff;
            font-size: 10px;
            font-weight: 850;
            line-height: 1.4;
        }

        .business-copy p {
            margin-top: 4px;
            color: rgba(226, 239, 255, 0.7);
            font-size: 8px;
            line-height: 1.55;
        }

        .workflow {
            margin-top: 24px;
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.13);
        }

        .workflow-label {
            color: rgba(229, 241, 255, 0.63);
            font-size: 7.5px;
            font-weight: 850;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .workflow-track {
            position: relative;
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 8px;
            margin-top: 14px;
        }

        .workflow-track::before {
            content: "";
            position: absolute;
            top: 15px;
            left: 12.5%;
            right: 12.5%;
            height: 1px;
            background: rgba(255, 255, 255, 0.16);
        }

        .workflow-step {
            position: relative;
            z-index: 1;
            min-width: 0;
            text-align: center;
        }

        .workflow-number {
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
            border: 1px solid rgba(255, 255, 255, 0.21);
            border-radius: 50%;
            color: #ddecff;
            background: #0754a8;
            box-shadow: 0 0 0 5px rgba(7, 78, 159, 0.45);
            font-size: 8px;
            font-weight: 900;
        }

        .workflow-step:first-child .workflow-number {
            color: #0759b7;
            background: #ffffff;
        }

        .workflow-step strong {
            display: block;
            margin-top: 8px;
            color: #ffffff;
            font-size: 8px;
            font-weight: 800;
            line-height: 1.35;
        }

        .workflow-step span {
            display: block;
            margin-top: 3px;
            color: rgba(220, 235, 252, 0.56);
            font-size: 7px;
            line-height: 1.4;
        }

        .brand-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 18px;
            padding-top: 18px;
            border-top: 1px solid rgba(255, 255, 255, 0.11);
            color: rgba(220, 235, 252, 0.58);
            font-size: 7.5px;
            line-height: 1.5;
        }

        .brand-footer strong {
            color: rgba(239, 247, 255, 0.86);
        }

        /* ================================================================
         * LOGIN PANEL
         * ================================================================ */
        .auth-panel {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background:
                radial-gradient(circle at 100% 0%, rgba(7, 89, 183, 0.05), transparent 34%),
                var(--background);
        }

        .auth-header {
            min-height: 66px;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 8px;
            padding: 0 34px;
            border-bottom: 1px solid var(--border);
            color: var(--text-secondary);
            background: rgba(255, 255, 255, 0.86);
            backdrop-filter: blur(12px);
            font-size: 8px;
            font-weight: 700;
        }

        .auth-header i {
            color: var(--primary);
            font-size: 10px;
        }

        .auth-content {
            width: 100%;
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 42px 34px;
        }

        .login-card {
            width: 100%;
            max-width: 440px;
            overflow: hidden;
            border: 1px solid var(--border);
            border-radius: 16px;
            background: var(--surface);
            box-shadow: var(--shadow-strong);
        }

        .login-card-head {
            padding: 27px 28px 20px;
            border-bottom: 1px solid #e8eef4;
        }

        .login-card-heading {
            display: flex;
            align-items: flex-start;
            gap: 12px;
        }

        .login-icon {
            width: 42px;
            height: 42px;
            flex: 0 0 auto;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid var(--primary-border);
            border-radius: 13px;
            color: var(--primary);
            background: var(--primary-soft);
            font-size: 16px;
        }

        .login-heading-copy {
            min-width: 0;
            padding-top: 1px;
        }

        .login-eyebrow {
            color: var(--text-muted);
            font-size: 7.5px;
            font-weight: 850;
            letter-spacing: 0.8px;
            text-transform: uppercase;
        }

        .login-title {
            margin-top: 3px;
            color: var(--text-primary);
            font-size: 18px;
            font-weight: 900;
            line-height: 1.3;
        }

        .login-description {
            margin-top: 10px;
            color: var(--text-secondary);
            font-size: 9px;
            line-height: 1.7;
        }

        .login-card-body {
            padding: 23px 28px 27px;
        }

        .flash-alert {
            display: flex;
            align-items: flex-start;
            gap: 9px;
            margin-bottom: 17px;
            padding: 10px 12px;
            border: 1px solid #efc4cb;
            border-radius: 9px;
            color: var(--danger);
            background: var(--danger-soft);
            font-size: 9px;
            font-weight: 650;
            line-height: 1.55;
        }

        .flash-alert i {
            flex: 0 0 auto;
            margin-top: 1px;
            font-size: 12px;
        }

        .form-group + .form-group {
            margin-top: 16px;
        }

        .form-label-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 7px;
        }

        .form-label {
            color: #334b68;
            font-size: 9px;
            font-weight: 800;
            letter-spacing: 0.18px;
        }

        .form-required {
            color: var(--text-muted);
            font-size: 7px;
        }

        .input-shell {
            position: relative;
        }

        .input-icon {
            position: absolute;
            top: 50%;
            left: 13px;
            transform: translateY(-50%);
            color: #8a9db1;
            font-size: 12px;
            pointer-events: none;
        }

        .form-control {
            width: 100%;
            height: 42px;
            padding: 0 42px 0 36px;
            border: 1px solid #d5dee7;
            border-radius: 9px;
            color: #233b58;
            background: #ffffff;
            font-size: 10px;
            transition:
                border-color .2s ease,
                box-shadow .2s ease,
                background .2s ease;
        }

        .form-control::placeholder {
            color: #9aaabd;
        }

        .form-control:hover {
            border-color: #becdda;
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(7, 89, 183, 0.09);
        }

        .form-control.is-invalid {
            border-color: #d75a68;
            background: #fffdfd;
        }

        .form-control.is-invalid:focus {
            box-shadow: 0 0 0 3px rgba(180, 47, 64, 0.08);
        }

        .toggle-password {
            position: absolute;
            top: 50%;
            right: 8px;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            transform: translateY(-50%);
            border: 0;
            border-radius: 7px;
            color: #8295aa;
            background: transparent;
            cursor: pointer;
            transition: .2s ease;
        }

        .toggle-password:hover,
        .toggle-password:focus-visible {
            color: var(--primary);
            background: var(--primary-soft);
        }

        .invalid-feedback {
            display: flex;
            align-items: flex-start;
            gap: 5px;
            margin-top: 6px;
            color: var(--danger);
            font-size: 8px;
            line-height: 1.45;
        }

        .invalid-feedback::before {
            content: "\F33A";
            font-family: "bootstrap-icons";
            flex: 0 0 auto;
            margin-top: 1px;
        }

        .login-options {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            margin-top: 12px;
        }

        .text-link {
            color: var(--primary);
            font-size: 8.5px;
            font-weight: 800;
            transition: color .2s ease;
        }

        .text-link:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }

        .btn-login {
            width: 100%;
            min-height: 42px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-top: 20px;
            padding: 0 16px;
            border: 0;
            border-radius: 9px;
            color: #ffffff;
            background: linear-gradient(135deg, var(--primary), var(--primary-bright));
            box-shadow: 0 8px 18px rgba(7, 89, 183, 0.17);
            font-size: 10px;
            font-weight: 850;
            cursor: pointer;
            transition:
                transform .2s ease,
                box-shadow .2s ease,
                filter .2s ease;
        }

        .btn-login:hover {
            transform: translateY(-1px);
            box-shadow: 0 11px 22px rgba(7, 89, 183, 0.21);
            filter: saturate(1.05);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .register-section {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
            margin-top: 17px;
            color: var(--text-secondary);
            font-size: 8.5px;
        }

        .register-section a {
            color: var(--primary);
            font-weight: 850;
        }

        .register-section a:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }

        .access-note {
            display: flex;
            align-items: flex-start;
            gap: 9px;
            margin-top: 20px;
            padding: 11px 12px;
            border: 1px solid var(--primary-border);
            border-radius: 9px;
            color: #4f6d8d;
            background: var(--primary-soft);
            font-size: 8px;
            line-height: 1.55;
        }

        .access-note i {
            flex: 0 0 auto;
            margin-top: 1px;
            color: var(--primary);
            font-size: 12px;
        }

        .access-note strong {
            color: var(--text-primary);
        }

        .auth-footer {
            min-height: 58px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 12px 24px;
            border-top: 1px solid var(--border);
            color: #8a9db1;
            background: #f1f5f9;
            font-size: 7.5px;
            text-align: center;
            line-height: 1.5;
        }

        /* ================================================================
         * RESPONSIVE
         * ================================================================ */
        @media (max-width: 1180px) {
            .login-shell {
                grid-template-columns: minmax(500px, 1fr) minmax(410px, 0.84fr);
            }

            .brand-panel {
                padding-left: 38px;
                padding-right: 38px;
            }
        }

        @media (max-width: 1024px) {
            .login-shell {
                display: block;
            }

            .brand-panel {
                display: none;
            }

            .auth-panel {
                min-height: 100vh;
            }

            .auth-header {
                justify-content: space-between;
            }

            .auth-header::before {
                content: "Penelitian RKA-K/L";
                color: var(--text-primary);
                font-size: 9px;
                font-weight: 850;
            }

            .login-card {
                max-width: 460px;
            }
        }

        @media (max-width: 680px) {
            .auth-header {
                min-height: 58px;
                padding: 0 17px;
            }

            .auth-header span {
                display: none;
            }

            .auth-content {
                align-items: flex-start;
                padding: 24px 14px 30px;
            }

            .login-card {
                border-radius: 14px;
            }

            .login-card-head {
                padding: 23px 20px 18px;
            }

            .login-card-body {
                padding: 20px;
            }

            .auth-footer {
                padding-left: 18px;
                padding-right: 18px;
            }
        }

        @media (max-width: 420px) {
            .login-card-heading {
                gap: 10px;
            }

            .login-icon {
                width: 38px;
                height: 38px;
                border-radius: 11px;
                font-size: 14px;
            }

            .login-title {
                font-size: 16px;
            }
        }
    </style>
</head>

<body>
    <main class="login-shell">
        {{-- ============================================================
             LEFT: BRAND + BUSINESS PROCESS
             ============================================================ --}}
        <section class="brand-panel" aria-label="Informasi Sistem Penelitian RKA-K/L">
            <div class="brand-top">
                <div class="brand-logo-wrap">
                    <img src="{{ asset('images/logo-kemendag.png') }}" alt="Logo Kementerian Perdagangan" class="brand-logo">
                </div>

                <div class="brand-ministry">
                    <small>Kementerian Perdagangan Republik Indonesia</small>
                    <strong>Sistem Informasi Penelitian RKA-K/L</strong>
                </div>
            </div>

            <div class="brand-content">
                <div class="system-chip">
                    <i class="bi bi-shield-check"></i>
                    Sistem Informasi Perencanaan dan Penganggaran
                </div>

                <h1 class="brand-title">
                    Penelitian <span>RKA-K/L</span><br>
                    dalam satu ruang kerja terintegrasi.
                </h1>

                <p class="brand-description">
                    Mendukung proses penelitian dokumen perencanaan dan penganggaran melalui pengelolaan dokumen sumber,
                    pemeriksaan terstruktur, pencatatan hasil penelitian, serta finalisasi Catatan Hasil Penelitian (CHP).
                </p>

                <div class="business-grid" aria-label="Ruang lingkup sistem">
                    <article class="business-card">
                        <div class="business-icon">
                            <i class="bi bi-file-earmark-bar-graph"></i>
                        </div>
                        <div class="business-copy">
                            <strong>Dokumen Perencanaan & Anggaran</strong>
                            <p>RENJA dan RKA-K/L menjadi sumber utama dalam penyusunan workspace penelitian.</p>
                        </div>
                    </article>

                    <article class="business-card">
                        <div class="business-icon">
                            <i class="bi bi-folder2-open"></i>
                        </div>
                        <div class="business-copy">
                            <strong>Dokumen Pendukung</strong>
                            <p>TOR/KAK, RAB, RKBMN, serta Data Jumlah Pegawai dikelola sebagai data pembanding penelitian.</p>
                        </div>
                    </article>

                    <article class="business-card">
                        <div class="business-icon">
                            <i class="bi bi-clipboard2-check"></i>
                        </div>
                        <div class="business-copy">
                            <strong>Penelitian Terstruktur</strong>
                            <p>Pemeriksaan klasifikasi, pagu, belanja, tagging, kelengkapan dokumen, dan catatan penelitian.</p>
                        </div>
                    </article>

                    <article class="business-card">
                        <div class="business-icon">
                            <i class="bi bi-file-earmark-check"></i>
                        </div>
                        <div class="business-copy">
                            <strong>Catatan Hasil Penelitian</strong>
                            <p>Hasil penelitian disimpan dalam status DRAFT dan dapat difinalisasi menjadi CHP setelah lengkap.</p>
                        </div>
                    </article>
                </div>

                <div class="workflow">
                    <div class="workflow-label">Alur Utama Penelitian</div>

                    <div class="workflow-track">
                        <div class="workflow-step">
                            <div class="workflow-number">1</div>
                            <strong>Dokumen</strong>
                            <span>Upload sumber data</span>
                        </div>

                        <div class="workflow-step">
                            <div class="workflow-number">2</div>
                            <strong>Workspace</strong>
                            <span>Susun penelitian</span>
                        </div>

                        <div class="workflow-step">
                            <div class="workflow-number">3</div>
                            <strong>Penelitian A–F</strong>
                            <span>Periksa & validasi</span>
                        </div>

                        <div class="workflow-step">
                            <div class="workflow-number">4</div>
                            <strong>CHP</strong>
                            <span>Finalisasi hasil</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- <div class="brand-footer">
                <span><strong>RKA-K/L Research Workspace</strong><br>Pengelolaan penelitian berbasis dokumen dan data.</span>
                <span>Internal • Kementerian Perdagangan RI</span>
            </div> --}}
        </section>

        {{-- ============================================================
             RIGHT: AUTHENTICATION
             ============================================================ --}}
        <section class="auth-panel">
            {{-- <header class="auth-header">
                <i class="bi bi-lock-fill"></i>
                <span>Akses pengguna terdaftar</span>
            </header> --}}

            <div class="auth-content">
                <section class="login-card" aria-labelledby="loginTitle">
                    <div class="login-card-head">
                        <div class="login-card-heading">
                            <div class="login-icon" aria-hidden="true">
                                <i class="bi bi-person-lock"></i>
                            </div>

                            <div class="login-heading-copy">
                                <div class="login-eyebrow">Autentikasi Pengguna</div>
                                <h2 class="login-title" id="loginTitle">Login</h2>
                            </div>
                        </div>

                        <p class="login-description">
                            Gunakan username dan password akun yang telah terdaftar untuk mengakses dashboard dan workspace
                            penelitian RKA-K/L.
                        </p>
                    </div>

                    <div class="login-card-body">
                        @if (session('error'))
                            <div class="flash-alert" role="alert">
                                <i class="bi bi-exclamation-circle-fill"></i>
                                <span>{{ session('error') }}</span>
                            </div>
                        @endif

                        <form action="{{ route('login.process') }}" method="POST" novalidate>
                            @csrf

                            <div class="form-group">
                                <div class="form-label-row">
                                    <label for="username" class="form-label">Username</label>
                                    <span class="form-required">Wajib diisi</span>
                                </div>

                                <div class="input-shell">
                                    <i class="bi bi-person input-icon" aria-hidden="true"></i>
                                    <input
                                        type="text"
                                        id="username"
                                        name="username"
                                        class="form-control @error('username') is-invalid @enderror"
                                        value="{{ old('username') }}"
                                        placeholder="Masukkan username"
                                        autocomplete="username"
                                        required
                                        autofocus
                                    >
                                </div>

                                @error('username')
                                    <div class="invalid-feedback">
                                        <span>{{ $message }}</span>
                                    </div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <div class="form-label-row">
                                    <label for="password" class="form-label">Password</label>
                                    <span class="form-required">Wajib diisi</span>
                                </div>

                                <div class="input-shell">
                                    <i class="bi bi-key input-icon" aria-hidden="true"></i>
                                    <input
                                        type="password"
                                        id="password"
                                        name="password"
                                        class="form-control @error('password') is-invalid @enderror"
                                        placeholder="Masukkan password"
                                        autocomplete="current-password"
                                        required
                                    >

                                    <button
                                        type="button"
                                        class="toggle-password"
                                        id="togglePassword"
                                        aria-label="Tampilkan password"
                                        title="Tampilkan password"
                                    >
                                        <i class="bi bi-eye" id="passwordIcon" aria-hidden="true"></i>
                                    </button>
                                </div>

                                @error('password')
                                    <div class="invalid-feedback">
                                        <span>{{ $message }}</span>
                                    </div>
                                @enderror
                            </div>

                            <div class="login-options">
                                <a href="{{ route('forgot.password') }}" class="text-link">
                                    Lupa Password?
                                </a>
                            </div>

                            <button type="submit" class="btn-login">
                                <i class="bi bi-box-arrow-in-right" aria-hidden="true"></i>
                                <span>Login</span>
                            </button>
                        </form>

                        <div class="register-section">
                            <span>Belum memiliki akun?</span>
                            <a href="{{ route('register') }}">Register</a>
                        </div>

                        {{-- <div class="access-note">
                            <i class="bi bi-info-circle-fill" aria-hidden="true"></i>
                            <div>
                                <strong>Akses sistem menggunakan akun yang telah diverifikasi.</strong><br>
                                Registrasi akun menggunakan verifikasi OTP sebelum pengguna dapat mengakses sistem.
                            </div>
                        </div> --}}
                    </div>
                </section>
            </div>

            <footer class="auth-footer">
                Sistem Informasi Penelitian RKA-K/L • Kementerian Perdagangan Republik Indonesia
            </footer>
        </section>
    </main>

    <script>
        (() => {
            const passwordInput = document.getElementById('password');
            const togglePasswordButton = document.getElementById('togglePassword');
            const passwordIcon = document.getElementById('passwordIcon');

            if (!passwordInput || !togglePasswordButton || !passwordIcon) {
                return;
            }

            togglePasswordButton.addEventListener('click', () => {
                const passwordIsHidden = passwordInput.type === 'password';

                passwordInput.type = passwordIsHidden ? 'text' : 'password';
                passwordIcon.classList.toggle('bi-eye', !passwordIsHidden);
                passwordIcon.classList.toggle('bi-eye-slash', passwordIsHidden);

                const buttonLabel = passwordIsHidden
                    ? 'Sembunyikan password'
                    : 'Tampilkan password';

                togglePasswordButton.setAttribute('aria-label', buttonLabel);
                togglePasswordButton.setAttribute('title', buttonLabel);
                passwordInput.focus();
            });
        })();
    </script>
</body>

</html>
