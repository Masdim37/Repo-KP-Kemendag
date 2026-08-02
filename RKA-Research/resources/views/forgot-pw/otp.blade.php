<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Verifikasi Kode OTP | Penelitian RKA-K/L</title>

    <!-- Bootstrap Icons -->
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    >

    <style>
        :root {
            --primary: #0759b7;
            --primary-dark: #063c7c;
            --primary-light: #7fb0df;
            --blue-soft: #edf5ff;
            --green: #16b65b;
            --orange: #ff8b22;
            --text-dark: #18365b;
            --text-normal: #55708f;
            --text-muted: #a6b4c7;
            --border: #d4deeb;
            --background: #f3f7fc;
            --white: #ffffff;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html,
        body {
            width: 100%;
            min-height: 100%;
        }

        body {
            font-family:
                Inter,
                "Segoe UI",
                Arial,
                sans-serif;
            color: var(--text-dark);
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

        .otp-page {
            display: flex;
            min-height: 100vh;
            overflow: hidden;
        }

        /* =====================================================
           PANEL KIRI
        ===================================================== */

        .left-panel {
            position: relative;
            width: 37.7%;
            min-height: 100vh;
            padding: 32px 21px 26px;
            color: #ffffff;
            background:
                linear-gradient(
                    155deg,
                    #06356c 0%,
                    #064996 48%,
                    #0872cf 100%
                );
            overflow: hidden;
        }

        .left-panel::before,
        .left-panel::after {
            content: "";
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.08);
            pointer-events: none;
        }

        .left-panel::before {
            width: 220px;
            height: 220px;
            top: -72px;
            right: -72px;
        }

        .left-panel::after {
            width: 205px;
            height: 205px;
            bottom: -86px;
            left: -72px;
        }

        .circle-decoration {
            position: absolute;
            width: 125px;
            height: 125px;
            top: 215px;
            right: -54px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.065);
        }

        .left-content {
            position: relative;
            z-index: 2;
            display: flex;
            flex-direction: column;
            min-height: calc(100vh - 58px);
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 11px;
        }

        .brand-logo {
            position: relative;
            width: 28px;
            height: 28px;
            flex: 0 0 auto;
        }

        .brand-logo .diamond {
            position: absolute;
            inset: 5px;
            transform: rotate(45deg);
            border-radius: 3px;
            background:
                linear-gradient(
                    135deg,
                    #bfe0ff 0%,
                    #ffffff 48%,
                    #6ca8e4 100%
                );
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.12);
        }

        .brand-logo .diamond::after {
            content: "";
            position: absolute;
            width: 6px;
            height: 6px;
            left: 6px;
            top: 6px;
            border-radius: 50%;
            background: #1265ba;
        }

        .brand-text span {
            display: block;
            line-height: 1.3;
        }

        .brand-government {
            font-size: 9px;
            font-weight: 600;
            letter-spacing: 0.7px;
            color: rgba(255, 255, 255, 0.78);
        }

        .brand-unit {
            font-size: 10px;
            font-weight: 700;
            color: #ffffff;
        }

        .system-label {
            margin-top: 29px;
            font-size: 8px;
            font-weight: 600;
            letter-spacing: 1px;
            color: rgba(192, 221, 255, 0.72);
        }

        .app-title {
            margin-top: 11px;
            font-size: clamp(18px, 2vw, 22px);
            font-weight: 750;
            line-height: 1.1;
            letter-spacing: -0.5px;
        }

        .title-line {
            width: 33px;
            height: 3px;
            margin-top: 9px;
            border-radius: 10px;
            background: #72b6ff;
        }

        .recovery-title {
            margin-top: 20px;
            font-size: 11px;
            font-weight: 700;
        }

        .recovery-description {
            width: 235px;
            max-width: 90%;
            margin-top: 8px;
            color: rgba(225, 239, 255, 0.72);
            font-size: 10px;
            line-height: 1.65;
        }

        /* Ilustrasi */

        .security-illustration {
            position: relative;
            width: 280px;
            height: 160px;
            max-width: 100%;
            margin: 22px auto 10px;
        }

        .illustration-dot {
            position: absolute;
            width: 5px;
            height: 5px;
            border-radius: 50%;
            background: rgba(196, 224, 255, 0.28);
        }

        .dot-1 {
            left: 37px;
            top: 12px;
        }

        .dot-2 {
            left: 34px;
            bottom: 14px;
        }

        .dot-3 {
            right: 29px;
            bottom: 7px;
        }

        .envelope {
            position: absolute;
            left: 47px;
            top: 47px;
            width: 118px;
            height: 80px;
            border: 1.5px solid rgba(213, 233, 255, 0.48);
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.06);
            box-shadow: 0 8px 18px rgba(0, 25, 67, 0.14);
            overflow: hidden;
        }

        .envelope::before,
        .envelope::after {
            content: "";
            position: absolute;
            top: 17px;
            width: 80px;
            height: 1.5px;
            background: rgba(213, 233, 255, 0.38);
        }

        .envelope::before {
            left: -8px;
            transform: rotate(37deg);
            transform-origin: left center;
        }

        .envelope::after {
            right: -8px;
            transform: rotate(-37deg);
            transform-origin: right center;
        }

        .envelope-bottom-left,
        .envelope-bottom-right {
            position: absolute;
            bottom: 13px;
            width: 73px;
            height: 1.5px;
            background: rgba(213, 233, 255, 0.22);
        }

        .envelope-bottom-left {
            left: -9px;
            transform: rotate(-34deg);
        }

        .envelope-bottom-right {
            right: -9px;
            transform: rotate(34deg);
        }

        .lock-icon {
            position: absolute;
            left: 71px;
            top: 10px;
            width: 28px;
            height: 25px;
            border: 1.5px solid rgba(221, 239, 255, 0.63);
            border-radius: 6px;
            background: rgba(255, 255, 255, 0.06);
        }

        .lock-icon::before {
            content: "";
            position: absolute;
            width: 16px;
            height: 13px;
            left: 4px;
            top: -11px;
            border: 1.5px solid rgba(221, 239, 255, 0.63);
            border-bottom: 0;
            border-radius: 10px 10px 0 0;
        }

        .lock-icon::after {
            content: "";
            position: absolute;
            width: 4px;
            height: 4px;
            left: 10px;
            top: 9px;
            border-radius: 50%;
            background: rgba(224, 241, 255, 0.8);
        }

        .otp-mini {
            position: absolute;
            left: 137px;
            top: 43px;
            display: flex;
            gap: 4px;
        }

        .otp-mini span {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 14px;
            height: 17px;
            border: 1px solid rgba(221, 239, 255, 0.5);
            border-radius: 3px;
            color: rgba(230, 243, 255, 0.78);
            background: rgba(255, 255, 255, 0.08);
            font-size: 8px;
        }

        .shield {
            position: absolute;
            right: 40px;
            top: 75px;
            width: 45px;
            height: 53px;
            border: 1.7px solid rgba(220, 239, 255, 0.58);
            background: rgba(255, 255, 255, 0.05);
            clip-path: polygon(
                50% 0%,
                93% 17%,
                86% 72%,
                50% 100%,
                14% 72%,
                7% 17%
            );
        }

        .shield-check {
            position: absolute;
            left: 14px;
            top: 20px;
            width: 18px;
            height: 10px;
            border-left: 2px solid #d9efff;
            border-bottom: 2px solid #d9efff;
            transform: rotate(-45deg);
        }

        .flow-section {
            margin-top: auto;
        }

        .flow-title {
            margin-bottom: 12px;
            color: rgba(219, 237, 255, 0.68);
            font-size: 8px;
            font-weight: 600;
            letter-spacing: 0.6px;
        }

        .flow-list {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 6px;
        }

        .flow-item {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            min-height: 20px;
            padding: 4px 9px;
            border-radius: 14px;
            color: #edf7ff;
            background: rgba(255, 255, 255, 0.16);
            font-size: 7px;
            font-weight: 600;
            white-space: nowrap;
        }

        .flow-item.active {
            background: rgba(128, 188, 249, 0.55);
        }

        .flow-arrow {
            color: rgba(224, 241, 255, 0.5);
            font-size: 9px;
        }

        .flow-login {
            margin-top: 8px;
        }

        .left-footer {
            margin-top: 20px;
            padding-top: 17px;
            border-top: 1px solid rgba(255, 255, 255, 0.14);
            color: rgba(215, 234, 255, 0.54);
            font-size: 7.5px;
        }

        /* =====================================================
           PANEL KANAN
        ===================================================== */

        .right-panel {
            position: relative;
            width: 62.3%;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 50px 30px;
            background: #f4f8fc;
        }

        .otp-card {
            width: 100%;
            max-width: 440px;
            padding: 28px 31px 31px;
            border: 1px solid rgba(216, 225, 236, 0.72);
            border-radius: 15px;
            background: #ffffff;
            box-shadow:
                0 15px 40px rgba(58, 88, 123, 0.12),
                0 2px 7px rgba(58, 88, 123, 0.08);
        }

        .card-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 5px 10px;
            border-radius: 20px;
            color: #0a63be;
            background: #eef6ff;
            font-size: 9px;
            font-weight: 650;
        }

        /* Stepper */

        .stepper {
            display: grid;
            grid-template-columns:
                24px 1fr
                24px 1fr
                24px 1fr
                24px;
            align-items: start;
            margin: 20px 0 27px;
        }

        .step {
            position: relative;
            z-index: 2;
            text-align: center;
        }

        .step-circle {
            width: 25px;
            height: 25px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            font-size: 10px;
            font-weight: 700;
        }

        .step.completed .step-circle {
            color: #ffffff;
            background: var(--green);
        }

        .step.current .step-circle {
            color: #ffffff;
            background: #075dbd;
            box-shadow: 0 0 0 5px rgba(7, 93, 189, 0.1);
        }

        .step.pending .step-circle {
            color: #94a5ba;
            background: #e7edf5;
        }

        .step-label {
            position: absolute;
            left: 50%;
            top: 31px;
            width: 74px;
            transform: translateX(-50%);
            font-size: 8px;
            font-weight: 550;
            white-space: nowrap;
        }

        .step.completed .step-label {
            color: var(--green);
        }

        .step.current .step-label {
            color: #075dbd;
            font-weight: 700;
        }

        .step.pending .step-label {
            color: #9baabd;
        }

        .step-line {
            height: 1px;
            margin-top: 12px;
            background: #d9e2ed;
        }

        .step-line.completed {
            background: var(--green);
        }

        .card-title {
            margin-top: 3px;
            font-size: 20px;
            line-height: 1.25;
            font-weight: 750;
            letter-spacing: -0.35px;
        }

        .card-description {
            margin-top: 7px;
            color: var(--text-normal);
            font-size: 11px;
            line-height: 1.6;
        }

        .masked-email {
            color: #075dbd;
            font-weight: 700;
        }

        .duration {
            color: var(--text-dark);
            font-weight: 700;
        }

        .otp-form {
            margin-top: 22px;
        }

        .form-label {
            display: block;
            margin-bottom: 10px;
            color: var(--text-dark);
            font-size: 11px;
            font-weight: 700;
        }

        .required {
            color: #e44d5e;
        }

        .otp-input-wrapper {
            display: flex;
            justify-content: space-between;
            gap: 10px;
        }

        .otp-input {
            width: 48px;
            height: 55px;
            border: 1.5px solid #cbd7e6;
            border-radius: 12px;
            outline: none;
            color: #164873;
            background: #fbfdff;
            text-align: center;
            font-size: 21px;
            font-weight: 700;
            caret-color: #075dbd;
            transition:
                border-color 0.2s ease,
                box-shadow 0.2s ease,
                background 0.2s ease;
        }

        .otp-input:hover {
            border-color: #9cb8d7;
        }

        .otp-input:focus {
            border-color: #247aca;
            background: #ffffff;
            box-shadow: 0 0 0 4px rgba(36, 122, 202, 0.1);
        }

        .otp-input.filled {
            border-color: #5e9ed7;
            background: #f5faff;
        }

        .resend-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-top: 20px;
            color: #a0afc2;
            font-size: 9px;
        }

        .resend-action {
            color: #8b9bb0;
            white-space: nowrap;
        }

        .countdown {
            color: var(--orange);
            font-weight: 700;
        }

        .resend-button {
            display: none;
            border: 0;
            padding: 0;
            color: #075dbd;
            background: transparent;
            font-size: 9px;
            font-weight: 700;
            cursor: pointer;
        }

        .verify-button {
            width: 100%;
            height: 45px;
            margin-top: 18px;
            border: 0;
            border-radius: 12px;
            color: #ffffff;
            background: #7daede;
            font-size: 11px;
            font-weight: 700;
            cursor: pointer;
            transition:
                transform 0.15s ease,
                background 0.2s ease,
                box-shadow 0.2s ease;
        }

        .verify-button i {
            margin-right: 7px;
        }

        .verify-button:not(:disabled):hover {
            background: #176dbd;
            box-shadow: 0 8px 18px rgba(23, 109, 189, 0.22);
            transform: translateY(-1px);
        }

        .verify-button:not(:disabled):active {
            transform: translateY(0);
        }

        .verify-button:disabled {
            cursor: not-allowed;
            opacity: 0.8;
        }

        .change-email {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 6px;
            margin-top: 17px;
            color: #587594;
            font-size: 10px;
            transition: color 0.2s ease;
        }

        .change-email:hover {
            color: #075dbd;
        }

        .right-footer {
            margin-top: 22px;
            text-align: center;
            color: #adbac9;
            font-size: 8px;
            line-height: 1.75;
        }

        .right-footer a {
            color: #075dbd;
            font-weight: 600;
        }

        .help-button {
            position: fixed;
            right: 11px;
            bottom: 10px;
            z-index: 10;
            width: 23px;
            height: 23px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            color: #ffffff;
            background: #24292f;
            box-shadow: 0 2px 7px rgba(0, 0, 0, 0.27);
            font-size: 12px;
        }

        .server-message {
            margin: 0 0 14px;
            padding: 10px 12px;
            border-radius: 10px;
            font-size: 9px;
            line-height: 1.5;
        }

        .server-message-error {
            color: #a52f3f;
            border: 1px solid #f1c1c8;
            background: #fff2f4;
        }

        .server-message-success {
            color: #217346;
            border: 1px solid #b9e4c6;
            background: #eefaf2;
        }

        /* =====================================================
           RESPONSIVE
        ===================================================== */

        @media (max-width: 1050px) {
            .left-panel {
                width: 40%;
            }

            .right-panel {
                width: 60%;
            }

            .otp-card {
                max-width: 420px;
            }

            .otp-input {
                width: 45px;
                height: 53px;
            }
        }

        @media (max-width: 850px) {
            .otp-page {
                display: block;
                overflow: visible;
            }

            .left-panel,
            .right-panel {
                width: 100%;
                min-height: auto;
            }

            .left-panel {
                padding: 28px 24px;
            }

            .left-content {
                min-height: auto;
            }

            .security-illustration,
            .flow-section,
            .left-footer,
            .circle-decoration {
                display: none;
            }

            .recovery-description {
                width: 100%;
                max-width: 580px;
            }

            .right-panel {
                padding: 35px 18px 55px;
            }

            .otp-card {
                max-width: 460px;
            }
        }

        @media (max-width: 520px) {
            .left-panel {
                padding: 23px 20px;
            }

            .system-label {
                margin-top: 22px;
            }

            .app-title {
                font-size: 20px;
            }

            .right-panel {
                padding: 26px 13px 48px;
            }

            .otp-card {
                padding: 24px 20px 27px;
                border-radius: 13px;
            }

            .otp-input-wrapper {
                gap: 7px;
            }

            .otp-input {
                width: calc((100% - 35px) / 6);
                min-width: 38px;
                height: 51px;
                border-radius: 10px;
            }

            .step-label {
                width: 63px;
                font-size: 7px;
            }

            .card-title {
                font-size: 19px;
            }

            .resend-row {
                align-items: flex-start;
            }
        }

        @media (max-width: 390px) {
            .otp-card {
                padding-left: 15px;
                padding-right: 15px;
            }

            .otp-input-wrapper {
                gap: 5px;
            }

            .otp-input {
                width: calc((100% - 25px) / 6);
                min-width: 34px;
                height: 48px;
            }

            .step-label {
                font-size: 6.5px;
            }
        }
    </style>
</head>

<body>

<div class="otp-page">

    <!-- =====================================================
         PANEL KIRI
    ====================================================== -->
    <aside class="left-panel">

        <div class="circle-decoration"></div>

        <div class="left-content">

            <div>
                <div class="brand">
                    <div class="brand-logo" aria-hidden="true">
                        <div class="diamond"></div>
                    </div>

                    <div class="brand-text">
                        <span class="brand-government">
                            KEMENTERIAN PERDAGANGAN RI
                        </span>
                        <span class="brand-unit">
                            Biro Perencanaan
                        </span>
                    </div>
                </div>

                <div class="system-label">
                    SISTEM INFORMASI
                </div>

                <h1 class="app-title">
                    Penelitian RKA-K/L
                </h1>

                <div class="title-line"></div>

                <h2 class="recovery-title">
                    Pemulihan Akses Akun
                </h2>

                <p class="recovery-description">
                    Ikuti langkah-langkah berikut untuk mengatur ulang kata
                    sandi akun Anda dengan aman melalui verifikasi email.
                </p>
            </div>

            <div class="security-illustration" aria-hidden="true">
                <span class="illustration-dot dot-1"></span>
                <span class="illustration-dot dot-2"></span>
                <span class="illustration-dot dot-3"></span>

                <div class="lock-icon"></div>

                <div class="otp-mini">
                    <span>2</span>
                    <span>9</span>
                    <span>1</span>
                </div>

                <div class="envelope">
                    <span class="envelope-bottom-left"></span>
                    <span class="envelope-bottom-right"></span>
                </div>

                <div class="shield">
                    <div class="shield-check"></div>
                </div>
            </div>

            <div class="flow-section">
                <div class="flow-title">
                    ALUR PEMULIHAN
                </div>

                <div class="flow-list">
                    <div class="flow-item">
                        <i class="bi bi-envelope-fill"></i>
                        Masukkan Email
                    </div>

                    <i class="bi bi-chevron-right flow-arrow"></i>

                    <div class="flow-item active">
                        <i class="bi bi-key-fill"></i>
                        Verifikasi OTP
                    </div>

                    <i class="bi bi-chevron-right flow-arrow"></i>

                    <div class="flow-item">
                        <i class="bi bi-lock-fill"></i>
                        Buat Password Baru
                    </div>

                    <i class="bi bi-chevron-right flow-arrow"></i>
                </div>

                <div class="flow-login">
                    <div class="flow-item">
                        <i class="bi bi-check-circle-fill"></i>
                        Login
                    </div>
                </div>

                <div class="left-footer">
                    © 2025 Kementerian Perdagangan Republik Indonesia.
                    Hak Cipta Dilindungi.
                </div>
            </div>

        </div>
    </aside>

    <!-- =====================================================
         PANEL KANAN
    ====================================================== -->
    <main class="right-panel">

        <section class="otp-card">

            <div class="card-badge">
                <i class="bi bi-shield-lock"></i>
                Pemulihan Akses Akun
            </div>

            <div class="stepper">

                <div class="step completed">
                    <div class="step-circle">
                        <i class="bi bi-check-lg"></i>
                    </div>
                    <span class="step-label">Email</span>
                </div>

                <div class="step-line completed"></div>

                <div class="step current">
                    <div class="step-circle">
                        <i class="bi bi-key-fill"></i>
                    </div>
                    <span class="step-label">Verifikasi</span>
                </div>

                <div class="step-line"></div>

                <div class="step pending">
                    <div class="step-circle">
                        <i class="bi bi-lock-fill"></i>
                    </div>
                    <span class="step-label">Password Baru</span>
                </div>

                <div class="step-line"></div>

                <div class="step pending">
                    <div class="step-circle">
                        <i class="bi bi-check-circle"></i>
                    </div>
                    <span class="step-label">Selesai</span>
                </div>

            </div>

            <h2 class="card-title">
                Verifikasi Kode OTP
            </h2>

            <p class="card-description">
                Kode OTP 6 digit telah dikirim ke
                <span class="masked-email">{{ $maskedEmail }}</span>.
                Kode berlaku selama
                <span class="duration">{{ $otpExpiresIn ?? 5 }} menit</span>.
            </p>

            <form
                class="otp-form"
                id="otpForm"
                action="{{ route('forgot.password.verify') }}"
                method="POST"
            >
                @csrf

                @if (session('error'))
                    <div class="server-message server-message-error">
                        {{ session('error') }}
                    </div>
                @endif

                @if (session('success'))
                    <div class="server-message server-message-success">
                        {{ session('success') }}
                    </div>
                @endif

                @error('otp')
                    <div class="server-message server-message-error">
                        {{ $message }}
                    </div>
                @enderror

                <label class="form-label">
                    Kode OTP <span class="required">*</span>
                </label>

                <div class="otp-input-wrapper" id="otpInputs">

                    <input
                        class="otp-input"
                        type="text"
                        inputmode="numeric"
                        autocomplete="one-time-code"
                        maxlength="1"
                        aria-label="Digit OTP 1"
                    >

                    <input
                        class="otp-input"
                        type="text"
                        inputmode="numeric"
                        maxlength="1"
                        aria-label="Digit OTP 2"
                    >

                    <input
                        class="otp-input"
                        type="text"
                        inputmode="numeric"
                        maxlength="1"
                        aria-label="Digit OTP 3"
                    >

                    <input
                        class="otp-input"
                        type="text"
                        inputmode="numeric"
                        maxlength="1"
                        aria-label="Digit OTP 4"
                    >

                    <input
                        class="otp-input"
                        type="text"
                        inputmode="numeric"
                        maxlength="1"
                        aria-label="Digit OTP 5"
                    >

                    <input
                        class="otp-input"
                        type="text"
                        inputmode="numeric"
                        maxlength="1"
                        aria-label="Digit OTP 6"
                    >

                </div>

                <input type="hidden" name="otp" id="otpValue" value="{{ old('otp') }}">

                <div class="resend-row">
                    <span>Tidak menerima kode OTP?</span>

                    <span class="resend-action" id="countdownWrapper">
                        Kirim ulang dalam
                        <span class="countdown" id="countdown">00:57</span>
                    </span>

                    <button
                        type="button"
                        class="resend-button"
                        id="resendButton"
                    >
                        Kirim Ulang OTP
                    </button>
                </div>

                <button
                    type="submit"
                    class="verify-button"
                    id="verifyButton"
                    disabled
                >
                    <i class="bi bi-shield-check"></i>
                    Verifikasi OTP
                </button>

                <a href="{{ route('forgot.password') }}" class="change-email">
                    <i class="bi bi-arrow-left"></i>
                    Ubah Alamat Email
                </a>

            </form>

            <form
                id="resendOtpForm"
                action="{{ route('forgot.password.resend') }}"
                method="POST"
                hidden
            >
                @csrf
            </form>

        </section>

        <footer class="right-footer">
            <div>
                Butuh bantuan?
                <a href="#">Hubungi Administrator Sistem</a>
            </div>

            <div>
                © 2025 Biro Perencanaan — Kementerian Perdagangan
                Republik Indonesia
            </div>
        </footer>

        <a href="#" class="help-button" aria-label="Bantuan">
            ?
        </a>

    </main>

</div>

<script>
    const otpInputs = Array.from(
        document.querySelectorAll(".otp-input")
    );

    const otpValue = document.getElementById("otpValue");
    const otpForm = document.getElementById("otpForm");
    const verifyButton = document.getElementById("verifyButton");

    const countdownElement = document.getElementById("countdown");
    const countdownWrapper = document.getElementById("countdownWrapper");
    const resendButton = document.getElementById("resendButton");

    function updateOtpValue() {
        const code = otpInputs.map(input => input.value).join("");

        otpValue.value = code;
        verifyButton.disabled = code.length !== otpInputs.length;

        otpInputs.forEach(input => {
            input.classList.toggle("filled", input.value !== "");
        });
    }

    otpInputs.forEach((input, index) => {

        input.addEventListener("input", event => {
            event.target.value = event.target.value.replace(/\D/g, "");

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
                !input.value &&
                index > 0
            ) {
                otpInputs[index - 1].focus();
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

            const pastedCode = event.clipboardData
                .getData("text")
                .replace(/\D/g, "")
                .slice(0, otpInputs.length);

            pastedCode.split("").forEach((digit, index) => {
                otpInputs[index].value = digit;
            });

            const focusIndex = Math.min(
                pastedCode.length,
                otpInputs.length - 1
            );

            otpInputs[focusIndex].focus();
            updateOtpValue();
        });

    otpForm.addEventListener("submit", event => {
        if (otpValue.value.length !== otpInputs.length) {
            event.preventDefault();
            otpInputs[0].focus();
            return;
        }

        verifyButton.disabled = true;
        verifyButton.innerHTML =
            '<i class="bi bi-hourglass-split"></i> Memverifikasi...';
    });

    let remainingSeconds = 57;
    let countdownInterval;

    function formatTime(seconds) {
        const minutes = Math.floor(seconds / 60)
            .toString()
            .padStart(2, "0");

        const secs = (seconds % 60)
            .toString()
            .padStart(2, "0");

        return `${minutes}:${secs}`;
    }

    function startCountdown() {
        clearInterval(countdownInterval);

        countdownWrapper.style.display = "inline";
        resendButton.style.display = "none";

        countdownElement.textContent = formatTime(remainingSeconds);

        countdownInterval = setInterval(() => {
            remainingSeconds--;

            countdownElement.textContent =
                formatTime(Math.max(remainingSeconds, 0));

            if (remainingSeconds <= 0) {
                clearInterval(countdownInterval);

                countdownWrapper.style.display = "none";
                resendButton.style.display = "inline-block";
            }
        }, 1000);
    }

    resendButton.addEventListener("click", () => {
        resendButton.disabled = true;
        resendButton.textContent = "Mengirim...";

        document
            .getElementById("resendOtpForm")
            .submit();
    });

    startCountdown();
</script>

</body>
</html>