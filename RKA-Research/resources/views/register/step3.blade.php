<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Verifikasi OTP | Penelitian RKA-K/L</title>

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    >

    <style>
        :root {
            --primary-900: #102d83;
            --primary-800: #173fa4;
            --primary-700: #2056d4;
            --primary-600: #2468f2;
            --primary-500: #4381f5;
            --primary-100: #eaf1ff;

            --success: #13b85c;
            --success-soft: #effbf4;

            --danger: #ef4355;
            --danger-soft: #fff2f4;

            --warning: #f3a51b;
            --warning-soft: #fff9e9;

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

        /* =========================================
           PANEL KIRI
        ========================================= */

        .left-panel {
            position: relative;
            width: 44.5%;
            min-height: 100vh;
            padding: 28px 20px 18px;
            color: #ffffff;
            background:
                radial-gradient(
                    circle at 45% 26%,
                    rgba(255,255,255,.08),
                    transparent 20%
                ),
                linear-gradient(
                    155deg,
                    #102d83 0%,
                    #173fa4 50%,
                    #2468f2 100%
                );
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
            background: rgba(255,255,255,.035);
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
            border: 1px solid rgba(255,255,255,.32);
            border-radius: 50%;
            background: rgba(255,255,255,.12);
            font-size: 13px;
        }

        .brand-government {
            font-size: 8px;
            color: rgba(255,255,255,.78);
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
            border: 1px solid rgba(255,255,255,.18);
            border-radius: 20px;
            background: rgba(255,255,255,.10);
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
            color: rgba(255,255,255,.78);
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
            border: 1px solid rgba(255,255,255,.14);
            border-radius: 20px;
            background: rgba(255,255,255,.10);
            color: rgba(255,255,255,.88);
            font-size: 7px;
        }

        .flow-title {
            margin-bottom: 10px;
            color: rgba(255,255,255,.65);
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
            border: 1px solid rgba(255,255,255,.08);
            border-radius: 9px;
            background: rgba(255,255,255,.06);
        }

        .flow-item.active {
            border-color: rgba(255,255,255,.35);
            background: rgba(255,255,255,.18);
        }

        .flow-item.completed {
            background: rgba(255,255,255,.10);
        }

        .flow-number {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 20px;
            height: 20px;
            flex-shrink: 0;
            border-radius: 50%;
            background: rgba(255,255,255,.12);
            color: rgba(255,255,255,.65);
            font-size: 8px;
            font-weight: 700;
        }

        .flow-item.completed .flow-number {
            color: #ffffff;
            background: var(--success);
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
            color: rgba(255,255,255,.58);
            font-size: 7px;
        }

        .left-footer {
            margin-top: auto;
            padding-top: 17px;
            border-top: 1px solid rgba(255,255,255,.15);
            text-align: center;
            color: rgba(255,255,255,.48);
            font-size: 6.5px;
            line-height: 1.6;
        }

        /* =========================================
           PANEL KANAN
        ========================================= */

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

        /* Stepper */

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
            border: 1px solid #d7dfeb;
            border-radius: 50%;
            color: #b0bccb;
            background: #ffffff;
            font-size: 8px;
            font-weight: 700;
        }

        .step.completed .step-circle {
            color: #ffffff;
            border-color: var(--success);
            background: var(--success);
        }

        .step.active .step-circle {
            color: #ffffff;
            border-color: var(--primary-600);
            background: var(--primary-600);
            box-shadow: 0 0 0 5px rgba(36,104,242,.08);
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

        .step.completed .step-label {
            color: var(--success);
            font-weight: 700;
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

        .step-line.completed {
            background: var(--success);
        }

        /* Card OTP */

        .otp-card {
            width: 100%;
            max-width: 430px;
            margin-top: 20px;
            padding: 27px 24px 22px;
            border: 1px solid rgba(216,225,235,.75);
            border-radius: 15px;
            background: #ffffff;
            box-shadow:
                0 16px 45px rgba(46,72,105,.10),
                0 3px 8px rgba(46,72,105,.05);
        }

        .otp-icon-wrapper {
            display: flex;
            justify-content: center;
            margin-bottom: 13px;
        }

        .otp-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 46px;
            height: 46px;
            border-radius: 12px;
            color: var(--primary-600);
            background: var(--primary-100);
            font-size: 22px;
        }

        .card-title {
            text-align: center;
            font-size: 15px;
            font-weight: 800;
        }

        .card-description {
            margin-top: 7px;
            text-align: center;
            color: var(--text-secondary);
            font-size: 8px;
            line-height: 1.6;
        }

        .masked-email {
            color: var(--primary-600);
            font-weight: 700;
        }

        .countdown-box {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
            width: fit-content;
            margin: 12px auto 15px;
            padding: 5px 10px;
            border: 1px solid #e0e7ef;
            border-radius: 20px;
            color: #8090a5;
            background: #fafcff;
            font-size: 7px;
        }

        .countdown {
            color: var(--primary-600);
            font-weight: 700;
        }

        /* OTP input */

        .otp-inputs {
            display: flex;
            justify-content: center;
            gap: 9px;
        }

        .otp-input {
            width: 43px;
            height: 48px;
            border: 1.4px solid var(--border);
            border-radius: 10px;
            outline: none;
            color: var(--text-primary);
            background: #ffffff;
            text-align: center;
            font-size: 18px;
            font-weight: 700;
            caret-color: var(--primary-600);
            transition: .2s ease;
        }

        .otp-input:hover {
            border-color: #9db4cf;
        }

        .otp-input:focus {
            border-color: var(--primary-600);
            box-shadow: 0 0 0 3px rgba(36,104,242,.09);
        }

        .otp-input.filled {
            border-color: var(--primary-500);
            background: #f8fbff;
        }

        .otp-input.error {
            border-color: var(--danger);
            background: var(--danger-soft);
        }

        .otp-input.success {
            border-color: var(--success);
            background: var(--success-soft);
        }

        .otp-message {
            display: none;
            align-items: center;
            justify-content: center;
            gap: 5px;
            margin-top: 8px;
            font-size: 7px;
        }

        .otp-message.show {
            display: flex;
        }

        .otp-message.error {
            color: var(--danger);
        }

        .otp-message.success {
            color: var(--success);
        }

        /* Resend OTP */

        .resend-wrapper {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
            margin-top: 13px;
            color: #95a3b5;
            font-size: 7px;
        }

        .resend-button {
            border: 0;
            padding: 0;
            color: var(--primary-600);
            background: transparent;
            font-size: 7px;
            font-weight: 700;
            cursor: pointer;
        }

        .resend-button:disabled {
            color: #aeb9c7;
            cursor: not-allowed;
        }

        .spam-information {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
            margin-top: 13px;
            color: #9aa8b9;
            font-size: 7px;
        }

        /* Action */

        .form-actions {
            display: grid;
            grid-template-columns: 94px 1fr;
            gap: 10px;
            margin-top: 17px;
        }

        .back-button,
        .verify-button {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 39px;
            border-radius: 9px;
            font-size: 8px;
            font-weight: 700;
            transition: .2s ease;
        }

        .back-button {
            border: 1px solid #d4dee9;
            color: #536d89;
            background: #ffffff;
        }

        .back-button:hover {
            color: var(--primary-600);
            border-color: #9db9e2;
            background: #f7faff;
        }

        .verify-button {
            border: none;
            color: #ffffff;
            background: var(--primary-600);
            box-shadow: 0 7px 16px rgba(36,104,242,.23);
            cursor: pointer;
        }

        .verify-button i {
            margin-right: 6px;
        }

        .verify-button:hover:not(:disabled) {
            background: #1857d7;
            transform: translateY(-1px);
        }

        .verify-button:disabled {
            color: rgba(255,255,255,.85);
            background: #8cc0f6;
            box-shadow: none;
            cursor: not-allowed;
        }

        /* Demo */

        .demo-notice {
            display: flex;
            align-items: flex-start;
            gap: 7px;
            margin-top: 12px;
            padding: 8px 10px;
            border: 1px solid #f4c95d;
            border-radius: 9px;
            color: #b47b09;
            background: var(--warning-soft);
            font-size: 7px;
            line-height: 1.5;
        }

        .demo-code {
            padding: 1px 4px;
            border-radius: 4px;
            color: #9d6600;
            background: #fff0bd;
            font-family: monospace;
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
            box-shadow: 0 2px 7px rgba(0,0,0,.25);
            font-size: 12px;
        }

        /* =========================================
           RESPONSIVE
        ========================================= */

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

            .otp-card {
                max-width: 470px;
            }
        }

        @media (max-width: 480px) {
            .otp-card {
                padding: 24px 15px 20px;
            }

            .otp-inputs {
                gap: 6px;
            }

            .otp-input {
                width: calc((100% - 30px) / 6);
                min-width: 38px;
                height: 46px;
            }

            .form-actions {
                grid-template-columns: 1fr;
            }

            .verify-button {
                grid-row: 1;
            }

            .back-button {
                grid-row: 2;
            }

            .step-label {
                width: 60px;
                font-size: 6.5px;
            }
        }

        @media (max-width: 370px) {
            .otp-input {
                min-width: 34px;
                height: 43px;
                font-size: 16px;
            }
        }
    </style>
</head>

<body>

<div class="register-page">

    <!-- PANEL KIRI -->
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

            <svg
                class="illustration"
                viewBox="0 0 260 150"
                fill="none"
                xmlns="http://www.w3.org/2000/svg"
            >
                <circle
                    cx="120"
                    cy="75"
                    r="60"
                    fill="rgba(255,255,255,.04)"
                />

                <rect
                    x="54"
                    y="28"
                    width="68"
                    height="91"
                    rx="7"
                    fill="rgba(255,255,255,.15)"
                    stroke="rgba(255,255,255,.25)"
                />

                <rect
                    x="64"
                    y="38"
                    width="46"
                    height="5"
                    rx="2"
                    fill="rgba(255,255,255,.55)"
                />

                <rect
                    x="64"
                    y="49"
                    width="36"
                    height="3"
                    rx="1.5"
                    fill="rgba(255,255,255,.28)"
                />

                <rect
                    x="66"
                    y="86"
                    width="8"
                    height="22"
                    rx="2"
                    fill="rgba(255,255,255,.38)"
                />

                <rect
                    x="79"
                    y="77"
                    width="8"
                    height="31"
                    rx="2"
                    fill="rgba(255,255,255,.52)"
                />

                <rect
                    x="92"
                    y="67"
                    width="8"
                    height="41"
                    rx="2"
                    fill="rgba(255,255,255,.68)"
                />

                <circle
                    cx="163"
                    cy="54"
                    r="21"
                    stroke="#f9d248"
                    stroke-width="2"
                />

                <path
                    d="M149 61L157 52L164 57L176 43"
                    stroke="#f9d248"
                    stroke-width="2.5"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                />

                <circle
                    cx="195"
                    cy="91"
                    r="8"
                    stroke="rgba(255,255,255,.4)"
                />

                <circle
                    cx="217"
                    cy="105"
                    r="8"
                    stroke="rgba(255,255,255,.4)"
                />

                <path
                    d="M179 70L190 84M202 96L210 101"
                    stroke="rgba(255,255,255,.35)"
                    stroke-width="2"
                />
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

                <div class="flow-item completed">
                    <div class="flow-number">
                        <i class="bi bi-check-lg"></i>
                    </div>

                    <div>
                        <div class="flow-name">
                            Informasi Akun
                        </div>

                        <div class="flow-description">
                            Data diri dan kredensial login
                        </div>
                    </div>
                </div>

                <div class="flow-item completed">
                    <div class="flow-number">
                        <i class="bi bi-check-lg"></i>
                    </div>

                    <div>
                        <div class="flow-name">
                            Informasi Jabatan
                        </div>

                        <div class="flow-description">
                            Jabatan dan posisi di instansi
                        </div>
                    </div>
                </div>

                <div class="flow-item active">
                    <div class="flow-number">3</div>

                    <div>
                        <div class="flow-name">
                            Verifikasi OTP
                        </div>

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

    <!-- PANEL KANAN -->
    <main class="right-panel">

        <div class="stepper">

            <div class="step completed">
                <div class="step-circle">
                    <i class="bi bi-check-lg"></i>
                </div>

                <div class="step-label">
                    Informasi<br>Akun
                </div>
            </div>

            <div class="step-line completed"></div>

            <div class="step completed">
                <div class="step-circle">
                    <i class="bi bi-check-lg"></i>
                </div>

                <div class="step-label">
                    Informasi<br>Jabatan
                </div>
            </div>

            <div class="step-line completed"></div>

            <div class="step active">
                <div class="step-circle">3</div>

                <div class="step-label">
                    Verifikasi<br>OTP
                </div>
            </div>

        </div>

        <section class="otp-card">

            <div class="otp-icon-wrapper">
                <div class="otp-icon">
                    <i class="bi bi-shield-check"></i>
                </div>
            </div>

            <h2 class="card-title">
                Verifikasi OTP
            </h2>

            <p class="card-description">
                Kode OTP 6 digit dikirim ke
                <span class="masked-email">
                    {{ $maskedEmail ?? session('masked_email', 'ad***@gmail.com') }}
                </span>
            </p>

            <div class="countdown-box">
                <i class="bi bi-clock"></i>

                Kirim ulang dalam

                <span class="countdown" id="countdown">
                    01:57
                </span>
            </div>

            <!-- FORM VERIFIKASI OTP -->
            <form
                id="verifyOtpForm"
                action="{{ route('register.step3') }}"
                method="POST"
                novalidate
            >

                @csrf

                <div class="otp-inputs" id="otpInputs">

                    <input
                        type="text"
                        class="otp-input"
                        inputmode="numeric"
                        maxlength="1"
                        autocomplete="one-time-code"
                        aria-label="Digit OTP pertama"
                    >

                    <input
                        type="text"
                        class="otp-input"
                        inputmode="numeric"
                        maxlength="1"
                        aria-label="Digit OTP kedua"
                    >

                    <input
                        type="text"
                        class="otp-input"
                        inputmode="numeric"
                        maxlength="1"
                        aria-label="Digit OTP ketiga"
                    >

                    <input
                        type="text"
                        class="otp-input"
                        inputmode="numeric"
                        maxlength="1"
                        aria-label="Digit OTP keempat"
                    >

                    <input
                        type="text"
                        class="otp-input"
                        inputmode="numeric"
                        maxlength="1"
                        aria-label="Digit OTP kelima"
                    >

                    <input
                        type="text"
                        class="otp-input"
                        inputmode="numeric"
                        maxlength="1"
                        aria-label="Digit OTP keenam"
                    >

                </div>

                <!-- Field yang dikirim ke backend -->
                <input
                    type="hidden"
                    id="otp_code"
                    name="otp_code"
                    value="{{ old('otp_code') }}"
                >

                <div
                    class="otp-message error"
                    id="otpError"
                >
                    <i class="bi bi-exclamation-circle-fill"></i>
                    Masukkan kode OTP 6 digit.
                </div>

                <div
                    class="otp-message success"
                    id="otpSuccess"
                >
                    <i class="bi bi-check-circle-fill"></i>
                    Format kode OTP valid.
                </div>

                @error('otp_code')
                    <div class="otp-message error show">
                        <i class="bi bi-exclamation-circle-fill"></i>
                        {{ $message }}
                    </div>
                @enderror

                <div class="spam-information">
                    <i class="bi bi-envelope"></i>
                    Periksa folder Spam jika tidak menerima email
                </div>

                <div class="form-actions">

                    <a
                        href="{{ url('/register/informasi-jabatan') }}"
                        class="back-button"
                    >
                        <i class="bi bi-arrow-left"></i>
                        &nbsp;Kembali
                    </a>

                    <button
                        type="submit"
                        class="verify-button"
                        id="verifyButton"
                        disabled
                    >
                        <i class="bi bi-shield-check"></i>
                        Verifikasi OTP
                    </button>

                </div>

            </form>

            <!-- FORM RESEND HARUS TERPISAH -->
            <form
                id="resendOtpForm"
                method="POST"
                action="{{ url('/register/resend-otp') }}"
            >
                @csrf

                <div class="resend-wrapper">
                    <span>Tidak menerima kode?</span>

                    <button
                        type="submit"
                        class="resend-button"
                        id="resendButton"
                        disabled
                    >
                        Kirim Ulang OTP
                    </button>
                </div>
            </form>

            <!-- Hapus bagian ini saat backend sudah aktif -->
            <div class="demo-notice">
                <i class="bi bi-info-circle-fill"></i>

                <div>
                    <strong>Mode Demo:</strong>
                    gunakan kode
                    <span class="demo-code">123456</span>
                    untuk simulasi verifikasi berhasil.
                </div>
            </div>

        </section>

    </main>

</div>

<a href="#" class="help-button" aria-label="Bantuan">
    ?
</a>

<script>
    const otpInputs = Array.from(
        document.querySelectorAll(".otp-input")
    );

    const hiddenOtpInput =
        document.getElementById("otp_code");

    const verifyButton =
        document.getElementById("verifyButton");

    const otpError =
        document.getElementById("otpError");

    const otpSuccess =
        document.getElementById("otpSuccess");

    const verifyOtpForm =
        document.getElementById("verifyOtpForm");

    const resendOtpForm =
        document.getElementById("resendOtpForm");

    const resendButton =
        document.getElementById("resendButton");

    const countdownElement =
        document.getElementById("countdown");

    let remainingSeconds = 117;
    let countdownInterval;

    function updateOtpValue() {
        const otpCode = otpInputs
            .map(input => input.value)
            .join("");

        hiddenOtpInput.value = otpCode;

        otpInputs.forEach(input => {
            input.classList.toggle(
                "filled",
                input.value !== ""
            );

            input.classList.remove("error", "success");
        });

        const isComplete =
            /^\d{6}$/.test(otpCode);

        verifyButton.disabled = !isComplete;

        otpError.classList.remove("show");
        otpSuccess.classList.toggle("show", isComplete);
    }

    otpInputs.forEach((input, index) => {

        input.addEventListener("input", event => {
            event.target.value = event.target.value
                .replace(/\D/g, "")
                .slice(0, 1);

            if (
                event.target.value &&
                index < otpInputs.length - 1
            ) {
                otpInputs[index + 1].focus();
            }

            updateOtpValue();
        });

        input.addEventListener("keydown", event => {

            if (
                event.key === "Backspace" &&
                input.value === "" &&
                index > 0
            ) {
                otpInputs[index - 1].focus();
                otpInputs[index - 1].value = "";
                updateOtpValue();
            }

            if (
                event.key === "ArrowLeft" &&
                index > 0
            ) {
                otpInputs[index - 1].focus();
            }

            if (
                event.key === "ArrowRight" &&
                index < otpInputs.length - 1
            ) {
                otpInputs[index + 1].focus();
            }
        });

        input.addEventListener("focus", () => {
            input.select();
        });
    });

    document
        .getElementById("otpInputs")
        .addEventListener("paste", event => {

            event.preventDefault();

            const pastedValue = event.clipboardData
                .getData("text")
                .replace(/\D/g, "")
                .slice(0, 6);

            otpInputs.forEach((input, index) => {
                input.value = pastedValue[index] || "";
            });

            const lastFilledIndex =
                Math.min(
                    pastedValue.length,
                    otpInputs.length
                ) - 1;

            if (lastFilledIndex >= 0) {
                otpInputs[lastFilledIndex].focus();
            }

            updateOtpValue();
        });

    function formatCountdown(seconds) {
        const minutes = Math.floor(seconds / 60)
            .toString()
            .padStart(2, "0");

        const remaining = (seconds % 60)
            .toString()
            .padStart(2, "0");

        return `${minutes}:${remaining}`;
    }

    function startCountdown() {
        clearInterval(countdownInterval);

        resendButton.disabled = true;

        countdownElement.textContent =
            formatCountdown(remainingSeconds);

        countdownInterval = setInterval(() => {
            remainingSeconds--;

            countdownElement.textContent =
                formatCountdown(
                    Math.max(remainingSeconds, 0)
                );

            if (remainingSeconds <= 0) {
                clearInterval(countdownInterval);

                resendButton.disabled = false;
                countdownElement.textContent = "00:00";
            }
        }, 1000);
    }

    verifyOtpForm.addEventListener(
        "submit",
        function (event) {

            /*
             * FRONTEND SAJA
             * Hapus event.preventDefault() setelah backend aktif.
             */
            event.preventDefault();

            const otpCode =
                hiddenOtpInput.value;

            if (!/^\d{6}$/.test(otpCode)) {
                otpError.classList.add("show");
                otpSuccess.classList.remove("show");

                otpInputs.forEach(input => {
                    input.classList.add("error");
                });

                return;
            }

            verifyButton.disabled = true;

            verifyButton.innerHTML = `
                <i class="bi bi-arrow-repeat"></i>
                Memverifikasi OTP...
            `;

            setTimeout(() => {
                if (otpCode === "123456") {
                    otpInputs.forEach(input => {
                        input.classList.add("success");
                    });

                    otpError.classList.remove("show");
                    otpSuccess.classList.add("show");
                    otpSuccess.innerHTML = `
                        <i class="bi bi-check-circle-fill"></i>
                        Kode OTP berhasil diverifikasi.
                    `;

                    verifyButton.innerHTML = `
                        <i class="bi bi-check-circle-fill"></i>
                        Verifikasi Berhasil
                    `;
                } else {
                    otpInputs.forEach(input => {
                        input.classList.add("error");
                    });

                    otpSuccess.classList.remove("show");
                    otpError.classList.add("show");

                    otpError.innerHTML = `
                        <i class="bi bi-exclamation-circle-fill"></i>
                        Kode OTP yang Anda masukkan tidak valid.
                    `;

                    verifyButton.disabled = false;

                    verifyButton.innerHTML = `
                        <i class="bi bi-shield-check"></i>
                        Verifikasi OTP
                    `;
                }
            }, 800);
        }
    );

    resendOtpForm.addEventListener(
        "submit",
        function (event) {

            /*
             * FRONTEND SAJA
             * Hapus event.preventDefault() setelah backend aktif.
             */
            event.preventDefault();

            if (resendButton.disabled) {
                return;
            }

            resendButton.disabled = true;
            resendButton.textContent =
                "Mengirim ulang...";

            setTimeout(() => {
                remainingSeconds = 117;
                startCountdown();

                resendButton.textContent =
                    "Kirim Ulang OTP";

                otpInputs.forEach(input => {
                    input.value = "";
                    input.classList.remove(
                        "filled",
                        "error",
                        "success"
                    );
                });

                hiddenOtpInput.value = "";
                verifyButton.disabled = true;

                otpError.classList.remove("show");
                otpSuccess.classList.remove("show");

                otpInputs[0].focus();

                alert(
                    "Frontend saja: kode OTP baru berhasil dikirim."
                );
            }, 800);
        }
    );

    function populateOldOtp() {
        const oldOtp = hiddenOtpInput.value
            .replace(/\D/g, "")
            .slice(0, 6);

        oldOtp.split("").forEach((digit, index) => {
            otpInputs[index].value = digit;
        });

        updateOtpValue();
    }

    populateOldOtp();
    startCountdown();

    if (otpInputs[0]) {
        otpInputs[0].focus();
    }
</script>

</body>
</html>