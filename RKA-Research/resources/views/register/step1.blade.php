<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Registrasi Akun | Penelitian RKA-K/L</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --primary-900: #102d83;
            --primary-800: #173fa4;
            --primary-700: #2056d4;
            --primary-600: #2468f2;
            --primary-100: #eaf1ff;

            --success: #1db660;
            --danger: #ef4355;
            --warning: #f1942f;

            --text-primary: #172f57;
            --text-secondary: #71829d;
            --text-muted: #a8b4c5;

            --border: #d5deea;
            --background: #f3f7fc;
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
            font-family: Inter, "Segoe UI", Arial, sans-serif;
            color: var(--text-primary);
            background: var(--background);
        }

        button,
        input {
            font: inherit;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        .register-page {
            display: flex;
            min-height: 100vh;
        }

        /* =========================
           PANEL KIRI
        ========================= */

        .left-panel {
            position: relative;
            width: 44.5%;
            min-height: 100vh;
            padding: 28px 20px 18px;
            color: #ffffff;
            background:
                radial-gradient(circle at 45% 26%, rgba(255, 255, 255, .08), transparent 20%),
                linear-gradient(155deg, #102d83 0%, #173fa4 50%, #2468f2 100%);
            overflow: hidden;
        }

        .left-panel::before {
            content: "";
            position: absolute;
            width: 330px;
            height: 330px;
            left: -130px;
            bottom: -170px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .035);
        }

        .left-content {
            position: relative;
            z-index: 2;
            display: flex;
            flex-direction: column;
            min-height: calc(100vh - 46px);
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .brand-logo {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 29px;
            height: 29px;
            border: 1px solid rgba(255, 255, 255, .32);
            border-radius: 50%;
            background: rgba(255, 255, 255, .12);
            font-size: 13px;
        }

        .brand-government {
            font-size: 8px;
            color: rgba(255, 255, 255, .78);
        }

        .brand-unit {
            margin-top: 2px;
            font-size: 9px;
            font-weight: 700;
        }

        .portal-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            width: fit-content;
            margin-top: 24px;
            padding: 5px 9px;
            border: 1px solid rgba(255, 255, 255, .18);
            border-radius: 20px;
            background: rgba(255, 255, 255, .10);
            font-size: 7px;
        }

        .portal-badge span {
            width: 5px;
            height: 5px;
            border-radius: 50%;
            background: #3fe47d;
        }

        .app-title {
            margin-top: 15px;
            font-size: 17px;
            line-height: 1.22;
            font-weight: 800;
        }

        .app-description {
            max-width: 430px;
            margin-top: 7px;
            color: rgba(255, 255, 255, .78);
            font-size: 8px;
            line-height: 1.6;
        }

        .illustration {
            width: 200px;
            margin: 25px auto 22px;
            opacity: .85;
        }

        .feature-list {
            display: flex;
            flex-wrap: wrap;
            gap: 7px;
            margin-bottom: 17px;
        }

        .feature-chip {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 5px 10px;
            border: 1px solid rgba(255, 255, 255, .14);
            border-radius: 20px;
            background: rgba(255, 255, 255, .10);
            color: rgba(255, 255, 255, .88);
            font-size: 7px;
        }

        .flow-title {
            margin-bottom: 10px;
            color: rgba(255, 255, 255, .65);
            font-size: 7px;
            font-weight: 700;
            letter-spacing: .8px;
        }

        .flow-list {
            display: flex;
            flex-direction: column;
            gap: 7px;
        }

        .flow-item {
            display: flex;
            align-items: center;
            gap: 10px;
            min-height: 43px;
            padding: 8px 10px;
            border: 1px solid rgba(255, 255, 255, .08);
            border-radius: 9px;
            background: rgba(255, 255, 255, .06);
        }

        .flow-item.active {
            border-color: rgba(255, 255, 255, .35);
            background: rgba(255, 255, 255, .18);
            box-shadow: 0 5px 15px rgba(0, 0, 0, .08);
        }

        .flow-number {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 20px;
            height: 20px;
            flex-shrink: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, .12);
            color: rgba(255, 255, 255, .65);
            font-size: 8px;
            font-weight: 700;
        }

        .flow-item.active .flow-number {
            color: var(--primary-700);
            background: #ffffff;
        }

        .flow-name {
            font-size: 8px;
            font-weight: 700;
        }

        .flow-description {
            margin-top: 2px;
            color: rgba(255, 255, 255, .58);
            font-size: 7px;
        }

        .left-footer {
            margin-top: auto;
            padding-top: 17px;
            border-top: 1px solid rgba(255, 255, 255, .15);
            text-align: center;
            color: rgba(255, 255, 255, .48);
            font-size: 6.5px;
            line-height: 1.6;
        }

        /* =========================
           PANEL KANAN
        ========================= */

        .right-panel {
            width: 55.5%;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 25px 24px;
            background: #f3f7fc;
        }

        .stepper {
            width: 100%;
            max-width: 385px;
            display: grid;
            grid-template-columns: 25px 1fr 25px 1fr 25px;
            align-items: start;
            margin-bottom: 16px;
        }

        .step {
            position: relative;
            text-align: center;
        }

        .step-circle {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 25px;
            height: 25px;
            margin: auto;
            border-radius: 50%;
            background: #ffffff;
            border: 1px solid #d7dfeb;
            color: #b0bccb;
            font-size: 8px;
            font-weight: 700;
        }

        .step.active .step-circle {
            color: #ffffff;
            border-color: var(--primary-600);
            background: var(--primary-600);
            box-shadow: 0 0 0 5px rgba(36, 104, 242, .08);
        }

        .step-label {
            position: absolute;
            top: 31px;
            left: 50%;
            width: 70px;
            transform: translateX(-50%);
            color: #a2afc0;
            font-size: 7px;
            line-height: 1.3;
        }

        .step.active .step-label {
            color: var(--primary-600);
            font-weight: 700;
        }

        .step-line {
            height: 1px;
            margin-top: 12px;
            background: #dce4ee;
        }

        .register-card {
            width: 100%;
            max-width: 430px;
            margin-top: 20px;
            padding: 23px 22px 20px;
            border: 1px solid rgba(216, 225, 235, .75);
            border-radius: 15px;
            background: #ffffff;
            box-shadow:
                0 16px 45px rgba(46, 72, 105, .10),
                0 3px 8px rgba(46, 72, 105, .05);
        }

        .card-title {
            font-size: 15px;
            font-weight: 800;
        }

        .card-description {
            margin-top: 4px;
            margin-bottom: 17px;
            color: var(--text-secondary);
            font-size: 8px;
            line-height: 1.55;
        }

        .form-group {
            margin-bottom: 10px;
        }

        .form-label {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 5px;
            color: #27476f;
            font-size: 8px;
            font-weight: 700;
        }

        .required {
            color: var(--danger);
        }

        .character-count {
            color: #a8b5c5;
            font-size: 7px;
            font-weight: 400;
        }

        .input-wrapper {
            position: relative;
        }

        .input-icon {
            position: absolute;
            top: 50%;
            left: 12px;
            transform: translateY(-50%);
            color: #a4b2c3;
            font-size: 11px;
            pointer-events: none;
        }

        .form-input {
            width: 100%;
            height: 36px;
            padding: 0 35px;
            border: 1.3px solid var(--border);
            border-radius: 10px;
            outline: none;
            color: #294b71;
            background: #ffffff;
            font-size: 9px;
            transition: .2s ease;
        }

        .form-input::placeholder {
            color: #aab6c6;
        }

        .form-input:hover {
            border-color: #aabdd3;
        }

        .form-input:focus {
            border-color: var(--primary-600);
            box-shadow: 0 0 0 3px rgba(36, 104, 242, .08);
        }

        .form-input.is-valid {
            border-color: var(--success);
        }

        .form-input.is-invalid {
            border-color: var(--danger);
            padding-right: 58px;
        }

        .field-status {
            position: absolute;
            top: 50%;
            right: 35px;
            transform: translateY(-50%);
            display: none;
            font-size: 11px;
        }

        .form-input.is-valid~.field-status.success {
            display: block;
            color: var(--success);
        }

        .form-input.is-invalid~.field-status.error {
            display: block;
            color: var(--danger);
        }

        .toggle-password {
            position: absolute;
            top: 50%;
            right: 11px;
            transform: translateY(-50%);
            border: 0;
            color: #9daabb;
            background: transparent;
            cursor: pointer;
            font-size: 11px;
        }

        .helper-text,
        .error-text {
            margin-top: 4px;
            font-size: 7px;
            line-height: 1.45;
        }

        .helper-text {
            color: #a2afbf;
        }

        .error-text {
            display: none;
            color: var(--danger);
        }

        .error-text.show {
            display: block;
        }

        /* Password strength */

        .strength-section {
            margin-top: 7px;
        }

        .strength-bars {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 4px;
        }

        .strength-bar {
            height: 3px;
            border-radius: 10px;
            background: #e5eaf0;
        }

        .strength-header {
            display: flex;
            justify-content: space-between;
            margin-top: 4px;
            font-size: 7px;
        }

        .strength-label {
            color: var(--danger);
            font-weight: 700;
        }

        .rules-box {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 5px 12px;
            margin-top: 8px;
            padding: 9px 10px;
            border-radius: 9px;
            background: #f6f8fb;
        }

        .rule {
            display: flex;
            align-items: center;
            gap: 5px;
            color: #a6b2c1;
            font-size: 7px;
        }

        .rule-icon {
            width: 8px;
            height: 8px;
            flex-shrink: 0;
            border-radius: 50%;
            background: #dfe5ec;
        }

        .rule.valid {
            color: var(--success);
        }

        .rule.valid .rule-icon {
            background: var(--success);
        }

        .password-summary {
            display: none;
            margin-top: 6px;
            color: var(--danger);
            font-size: 7px;
        }

        .password-summary.show {
            display: block;
        }

        .next-button {
            width: 100%;
            height: 39px;
            margin-top: 5px;
            border: none;
            border-radius: 9px;
            color: #ffffff;
            background: var(--primary-600);
            box-shadow: 0 7px 16px rgba(36, 104, 242, .23);
            font-size: 9px;
            font-weight: 700;
            cursor: pointer;
            transition: .2s ease;
        }

        .next-button:hover:not(:disabled) {
            background: #1857d7;
            transform: translateY(-1px);
        }

        .next-button:disabled {
            background: #98b5ef;
            cursor: not-allowed;
            box-shadow: none;
        }

        .login-link {
            margin-top: 9px;
            text-align: center;
            color: #8f9daf;
            font-size: 7px;
        }

        .login-link a {
            color: var(--primary-600);
            font-weight: 700;
        }

        .help-button {
            position: fixed;
            right: 11px;
            bottom: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            color: #ffffff;
            background: #24292f;
            box-shadow: 0 2px 7px rgba(0, 0, 0, .25);
            font-size: 12px;
        }

        @media (max-width: 900px) {
            .register-page {
                display: block;
            }

            .left-panel,
            .right-panel {
                width: 100%;
                min-height: auto;
            }

            .left-panel {
                padding: 25px 20px;
            }

            .left-content {
                min-height: auto;
            }

            .illustration,
            .feature-list,
            .flow-list,
            .flow-title,
            .left-footer {
                display: none;
            }

            .right-panel {
                padding: 32px 15px 50px;
            }

            .register-card {
                max-width: 470px;
            }
        }

        @media (max-width: 480px) {
            .register-card {
                padding: 20px 16px;
            }

            .rules-box {
                grid-template-columns: 1fr;
            }

            .step-label {
                width: 60px;
                font-size: 6.5px;
            }
        }
    </style>
</head>

<body>

    <div class="register-page">

        <aside class="left-panel">
            <div class="left-content">

                <div class="brand">
                    <div class="brand-logo">
                        <i class="bi bi-star-fill"></i>
                    </div>

                    <div>
                        <div class="brand-government">
                            Kementerian Perdagangan RI
                        </div>
                        <div class="brand-unit">
                            Biro Perencanaan
                        </div>
                    </div>
                </div>

                <div class="portal-badge">
                    <span></span>
                    Portal Pendaftaran Akun
                </div>

                <h1 class="app-title">
                    Sistem Informasi<br>
                    Penelitian RKA-K/L
                </h1>

                <p class="app-description">
                    Kelola usulan penelitian anggaran secara mudah, aman,
                    dan terkoordinasi.
                </p>

                <svg class="illustration" viewBox="0 0 260 150" fill="none" xmlns="http://www.w3.org/2000/svg">

                    <circle cx="120" cy="75" r="60" fill="rgba(255,255,255,.04)" />

                    <rect x="54" y="28" width="68" height="91" rx="7" fill="rgba(255,255,255,.15)"
                        stroke="rgba(255,255,255,.25)" />

                    <rect x="64" y="38" width="46" height="5" rx="2" fill="rgba(255,255,255,.55)" />

                    <rect x="64" y="49" width="36" height="3" rx="1.5" fill="rgba(255,255,255,.28)" />

                    <rect x="66" y="86" width="8" height="22" rx="2" fill="rgba(255,255,255,.38)" />

                    <rect x="79" y="77" width="8" height="31" rx="2" fill="rgba(255,255,255,.52)" />

                    <rect x="92" y="67" width="8" height="41" rx="2" fill="rgba(255,255,255,.68)" />

                    <circle cx="163" cy="54" r="21" stroke="#f9d248" stroke-width="2" />

                    <path d="M149 61L157 52L164 57L176 43" stroke="#f9d248" stroke-width="2.5" stroke-linecap="round"
                        stroke-linejoin="round" />

                    <circle cx="195" cy="91" r="8" stroke="rgba(255,255,255,.4)" />

                    <circle cx="217" cy="105" r="8" stroke="rgba(255,255,255,.4)" />

                    <path d="M179 70L190 84M202 96L210 101" stroke="rgba(255,255,255,.35)" stroke-width="2" />
                </svg>

                <div class="feature-list">
                    <div class="feature-chip">
                        <i class="bi bi-shield-check"></i>
                        Data terenkripsi & aman
                    </div>

                    <div class="feature-chip">
                        <i class="bi bi-speedometer2"></i>
                        Dashboard riset terintegrasi
                    </div>

                    <div class="feature-chip">
                        <i class="bi bi-briefcase"></i>
                        Manajemen RKA-K/L terpusat
                    </div>
                </div>

                <div class="flow-title">
                    ALUR PENDAFTARAN
                </div>

                <div class="flow-list">
                    <div class="flow-item active">
                        <div class="flow-number">1</div>
                        <div>
                            <div class="flow-name">Informasi Akun</div>
                            <div class="flow-description">
                                Data diri dan kredensial login
                            </div>
                        </div>
                    </div>

                    <div class="flow-item">
                        <div class="flow-number">2</div>
                        <div>
                            <div class="flow-name">Informasi Jabatan</div>
                            <div class="flow-description">
                                Jabatan dan posisi di instansi
                            </div>
                        </div>
                    </div>

                    <div class="flow-item">
                        <div class="flow-number">3</div>
                        <div>
                            <div class="flow-name">Verifikasi OTP</div>
                            <div class="flow-description">
                                Konfirmasi melalui kode OTP
                            </div>
                        </div>
                    </div>
                </div>

                <div class="left-footer">
                    © 2025 Biro Perencanaan — Kementerian Perdagangan RI<br>
                    Seluruh data dilindungi berdasarkan regulasi yang berlaku.
                </div>

            </div>
        </aside>

        <main class="right-panel">

            <div class="stepper">
                <div class="step active">
                    <div class="step-circle">1</div>
                    <div class="step-label">
                        Informasi<br>Akun
                    </div>
                </div>

                <div class="step-line"></div>

                <div class="step">
                    <div class="step-circle">2</div>
                    <div class="step-label">
                        Informasi<br>Jabatan
                    </div>
                </div>

                <div class="step-line"></div>

                <div class="step">
                    <div class="step-circle">3</div>
                    <div class="step-label">
                        Verifikasi<br>OTP
                    </div>
                </div>
            </div>

            <section class="register-card">

                <h2 class="card-title">Informasi Akun</h2>

                <p class="card-description">
                    Lengkapi data diri dan buat kredensial login Anda.
                </p>

                @if (session('error'))
                    <div
                        style="
            margin-bottom: 12px;
            padding: 10px 12px;
            border: 1px solid #f3b8bf;
            border-radius: 8px;
            color: #b42332;
            background: #fff1f3;
            font-size: 12px;
        ">
                        {{ session('error') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div
                        style="
            margin-bottom: 12px;
            padding: 10px 12px;
            border: 1px solid #f3b8bf;
            border-radius: 8px;
            color: #b42332;
            background: #fff1f3;
            font-size: 12px;
        ">
                        <strong>Data belum dapat diproses:</strong>

                        <ul style="margin: 7px 0 0 17px;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form id="registerStepOne" action="{{ route('register.step1') }}" method="POST" novalidate>

                    @csrf

                    <!-- NAME -->
                    <div class="form-group">
                        <label for="name" class="form-label">
                            <span>
                                Nama Lengkap
                                <span class="required">*</span>
                            </span>
                        </label>

                        <div class="input-wrapper">
                            <i class="bi bi-person input-icon"></i>

                            <input type="text" id="name" name="name" value="{{ old('name') }}"
                                class="form-input @error('name') is-invalid @enderror"
                                placeholder="Masukkan nama lengkap" maxlength="255" autocomplete="name" required>

                            <i class="bi bi-check-circle-fill field-status success"></i>
                            <i class="bi bi-exclamation-circle-fill field-status error"></i>
                        </div>

                        <div class="error-text" id="nameError">
                            Nama lengkap wajib diisi.
                        </div>

                        @error('name')
                            <div class="error-text show">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- NIP -->
                    <div class="form-group">
                        <label for="nip" class="form-label">
                            <span>
                                NIP
                                <span class="required">*</span>
                            </span>

                            {{-- <span class="character-count" id="nipCounter">
                                0/18
                            </span> --}}
                        </label>

                        <div class="input-wrapper">
                            <i class="bi bi-hash input-icon"></i>

                            <input type="text" id="nip" name="nip" value="{{ old('nip') }}"
                                class="form-input @error('nip') is-invalid @enderror"
                                placeholder="Masukkan 18 Digit NIP" maxlength="18" inputmode="numeric"
                                autocomplete="off" required>

                            <i class="bi bi-check-circle-fill field-status success"></i>
                            <i class="bi bi-exclamation-circle-fill field-status error"></i>
                        </div>

                        {{-- <div class="helper-text">
                            NIP harus terdiri dari 18 digit angka.
                        </div> --}}

                        <div class="error-text" id="nipError">
                            NIP wajib terdiri dari 18 digit angka.
                        </div>

                        @error('nip')
                            <div class="error-text show">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- EMAIL -->
                    <div class="form-group">
                        <label for="email" class="form-label">
                            <span>
                                Email Instansi
                                <span class="required">*</span>
                            </span>
                        </label>

                        <div class="input-wrapper">
                            <i class="bi bi-envelope input-icon"></i>

                            <input type="email" id="email" name="email" value="{{ old('email') }}"
                                class="form-input @error('email') is-invalid @enderror" placeholder="Masukkan email"
                                maxlength="150" autocomplete="email" required>

                            <i class="bi bi-check-circle-fill field-status success"></i>
                            <i class="bi bi-exclamation-circle-fill field-status error"></i>
                        </div>

                        {{-- <div class="helper-text">
                        Kode OTP akan dikirim ke alamat email ini.
                    </div> --}}

                        <div class="error-text" id="emailError">
                            Masukkan alamat email yang valid.
                        </div>

                        @error('email')
                            <div class="error-text show">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- USERNAME -->
                    <div class="form-group">
                        <label for="username" class="form-label">
                            <span>
                                Username
                                <span class="required">*</span>
                            </span>
                        </label>

                        <div class="input-wrapper">
                            <i class="bi bi-at input-icon"></i>

                            <input type="text" id="username" name="username" value="{{ old('username') }}"
                                class="form-input @error('username') is-invalid @enderror"
                                placeholder="Masukkan Username" maxlength="100" autocomplete="username" required>

                            <i class="bi bi-check-circle-fill field-status success"></i>
                            <i class="bi bi-exclamation-circle-fill field-status error"></i>
                        </div>

                        {{-- <div class="helper-text">
                        Gunakan huruf, angka, titik, atau garis bawah.
                    </div> --}}

                        <div class="error-text" id="usernameError">
                            Username hanya boleh berisi huruf, angka, strip, dan underscore
                        </div>

                        @error('username')
                            <div class="error-text show">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- PASSWORD -->
                    <div class="form-group">
                        <label for="password" class="form-label">
                            <span>
                                Kata Sandi
                                <span class="required">*</span>
                            </span>
                        </label>

                        <div class="input-wrapper">
                            <i class="bi bi-lock input-icon"></i>

                            <input type="password" id="password" name="password"
                                class="form-input @error('password') is-invalid @enderror"
                                placeholder="Masukkan Password" maxlength="255" autocomplete="new-password" required>

                            <button type="button" class="toggle-password" data-target="password"
                                aria-label="Tampilkan kata sandi">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>

                        <div class="strength-section">
                            <div class="strength-bars">
                                <span class="strength-bar"></span>
                                <span class="strength-bar"></span>
                                <span class="strength-bar"></span>
                                <span class="strength-bar"></span>
                            </div>

                            <div class="strength-header">
                                <span class="strength-label" id="strengthLabel">
                                    Sangat Lemah
                                </span>

                                <span id="rulesCounter">
                                    0/5 kriteria terpenuhi
                                </span>
                            </div>

                            <div class="rules-box">
                                <div class="rule" id="ruleLength">
                                    <span class="rule-icon"></span>
                                    Minimal 8 karakter
                                </div>

                                <div class="rule" id="ruleUppercase">
                                    <span class="rule-icon"></span>
                                    Huruf kapital
                                </div>

                                <div class="rule" id="ruleLowercase">
                                    <span class="rule-icon"></span>
                                    Huruf kecil
                                </div>

                                <div class="rule" id="ruleNumber">
                                    <span class="rule-icon"></span>
                                    Angka 0-9
                                </div>

                                <div class="rule" id="ruleSpecial">
                                    <span class="rule-icon"></span>
                                    Karakter khusus
                                </div>
                            </div>

                            <div class="password-summary" id="passwordError">
                                Kata sandi belum memenuhi seluruh persyaratan.
                            </div>
                        </div>

                        @error('password')
                            <div class="error-text show">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- PASSWORD CONFIRMATION -->
                    <div class="form-group">
                        <label for="password_confirmation" class="form-label">
                            <span>
                                Konfirmasi Kata Sandi
                                <span class="required">*</span>
                            </span>
                        </label>

                        <div class="input-wrapper">
                            <i class="bi bi-lock input-icon"></i>

                            <input type="password" id="password_confirmation" name="password_confirmation"
                                class="form-input" placeholder="Ulangi kata sandi" maxlength="255"
                                autocomplete="new-password" required>

                            <span class="field-status success">
                                <i class="bi bi-check-circle-fill"></i>
                            </span>

                            <span class="field-status error">
                                <i class="bi bi-exclamation-circle-fill"></i>
                            </span>

                            <button type="button" class="toggle-password" data-target="password_confirmation"
                                aria-label="Tampilkan konfirmasi kata sandi">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>

                        <div class="error-text" id="confirmationError">
                            Konfirmasi kata sandi tidak sama.
                        </div>
                    </div>

                    <button type="submit" class="next-button" id="nextButton" disabled>
                        Selanjutnya
                        <i class="bi bi-arrow-right"></i>
                    </button>

                    <div class="login-link">
                        Sudah memiliki akun?
                        <a href="{{ route('login') }}">Masuk</a>
                    </div>

                </form>

            </section>

        </main>

    </div>

    {{-- <a href="#" class="help-button" aria-label="Bantuan">
    ?
</a> --}}



    <script>
        const form = document.getElementById("registerStepOne");

        const fields = {
            name: document.getElementById("name"),
            nip: document.getElementById("nip"),
            email: document.getElementById("email"),
            username: document.getElementById("username"),
            password: document.getElementById("password"),
            confirmation: document.getElementById("password_confirmation")
        };

        const nextButton = document.getElementById("nextButton");
        // const nipCounter = document.getElementById("nipCounter");

        const strengthBars = Array.from(
            document.querySelectorAll(".strength-bar")
        );

        const strengthLabel = document.getElementById("strengthLabel");
        const rulesCounter = document.getElementById("rulesCounter");

        const passwordRules = [{
                element: document.getElementById("ruleLength"),
                validate: value => value.length >= 8
            },
            {
                element: document.getElementById("ruleUppercase"),
                validate: value => /[A-Z]/.test(value)
            },
            {
                element: document.getElementById("ruleLowercase"),
                validate: value => /[a-z]/.test(value)
            },
            {
                element: document.getElementById("ruleNumber"),
                validate: value => /[0-9]/.test(value)
            },
            {
                element: document.getElementById("ruleSpecial"),
                validate: value =>
                    /[!@#$%^&*()_\-+=\[\]{};:'",.<>/?\\|`~]/.test(value)
        }
    ];

    function setValidation(input, errorElement, isValid, showError = true) {
        input.classList.remove("is-valid", "is-invalid");

        if (!input.value) {
            errorElement.classList.remove("show");
            return;
        }

        if (isValid) {
            input.classList.add("is-valid");
            errorElement.classList.remove("show");
        } else {
            input.classList.add("is-invalid");

            if (showError) {
                errorElement.classList.add("show");
            }
        }
    }

    function validateName() {
        const valid = fields.name.value.trim().length >= 3;

        setValidation(
            fields.name,
            document.getElementById("nameError"),
            valid
        );

        return valid;
    }

    // function validateNip() {
    //     fields.nip.value = fields.nip.value.replace(/\D/g, "").slice(0, 18);
    //     nipCounter.textContent = `${fields.nip.value.length}/18`;

    //     const valid = /^\d{18}$/.test(fields.nip.value);

    //     setValidation(
    //         fields.nip,
    //         document.getElementById("nipError"),
    //         valid
    //     );

    //     return valid;
    // }

    function validateNip() {
        fields.nip.value = fields.nip.value
            .replace(/\D/g, "")
            .slice(0, 18);

        const valid = /^\d{18}$/.test(fields.nip.value);

        setValidation(
            fields.nip,
            document.getElementById("nipError"),
            valid
        );

        return valid;
    }

    function validateEmail() {
        const valid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(
            fields.email.value.trim()
        );

        setValidation(
            fields.email,
            document.getElementById("emailError"),
            valid
        );

        return valid;
    }

    function validateUsername() {
        fields.username.value = fields.username.value.replace(/\s/g, "");

        const valid = /^[A-Za-z0-9_-]{4,100}$/.test(
            fields.username.value
        );

        setValidation(
            fields.username,
            document.getElementById("usernameError"),
            valid
        );

        return valid;
    }

    function validatePassword() {
        const value = fields.password.value;

        let score = 0;

        passwordRules.forEach(rule => {
            const valid = rule.validate(value);

            rule.element.classList.toggle("valid", valid);

            if (valid) {
                score++;
            }
        });

        rulesCounter.textContent = `${score}/5 kriteria terpenuhi`;

            let label = "Sangat Lemah";
            let color = "#ef4355";
            let activeBars = value ? 1 : 0;

            if (score === 2) {
                label = "Lemah";
                color = "#f07535";
                activeBars = 1;
            } else if (score === 3) {
                label = "Sedang";
                color = "#e7b72f";
                activeBars = 2;
            } else if (score === 4) {
                label = "Kuat";
                color = "#57b96b";
                activeBars = 3;
            } else if (score === 5) {
                label = "Sangat Kuat";
                color = "#1db660";
                activeBars = 4;
            }

            strengthLabel.textContent = label;
            strengthLabel.style.color = color;

            strengthBars.forEach((bar, index) => {
                bar.style.background =
                    index < activeBars ? color : "#e5eaf0";
            });

            const valid = score === 5;

            document
                .getElementById("passwordError")
                .classList.toggle("show", value.length > 0 && !valid);

            return valid;
        }

        function validateConfirmation() {
            const valid =
                fields.confirmation.value.length > 0 &&
                fields.confirmation.value === fields.password.value;

            setValidation(
                fields.confirmation,
                document.getElementById("confirmationError"),
                valid
            );

            return valid;
        }

        function validateForm() {
            const valid =
                validateName() &&
                validateNip() &&
                validateEmail() &&
                validateUsername() &&
                validatePassword() &&
                validateConfirmation();

            nextButton.disabled = !valid;

            return valid;
        }

        fields.name.addEventListener("input", validateForm);
        fields.nip.addEventListener("input", validateForm);
        fields.email.addEventListener("input", validateForm);
        fields.username.addEventListener("input", validateForm);
        fields.password.addEventListener("input", validateForm);
        fields.confirmation.addEventListener("input", validateForm);

        document.querySelectorAll(".toggle-password").forEach(button => {
            button.addEventListener("click", function() {
                const target = document.getElementById(
                    this.dataset.target
                );

                const icon = this.querySelector("i");
                const hidden = target.type === "password";

                target.type = hidden ? "text" : "password";
                icon.className = hidden ?
                    "bi bi-eye-slash" :
                    "bi bi-eye";

                this.setAttribute(
                    "aria-label",
                    hidden ?
                    "Sembunyikan kata sandi" :
                    "Tampilkan kata sandi"
                );
            });
        });

        // form.addEventListener("submit", function (event) {
        //     event.preventDefault();

        //     if (!validateForm()) {
        //         return;
        //     }

        //     /*
        //      * FRONTEND SAJA
        //      *
        //      * Untuk menghubungkan backend:
        //      * 1. Isi action form dengan route register Step 1.
        //      * 2. Hapus event.preventDefault().
        //      * 3. Submit data melalui controller Laravel.
        //      */

        //     alert("Data informasi akun valid. Lanjut ke Informasi Jabatan.");
        // });

        validateNip();
    </script>

</body>

</html>
