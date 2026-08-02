<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Buat Kata Sandi Baru | Penelitian RKA-K/L</title>

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    >

    <style>
        :root {
            --blue-900: #07366f;
            --blue-800: #07458f;
            --blue-700: #075fbe;
            --blue-600: #0871d1;
            --blue-100: #eaf4ff;

            --green: #16ad57;
            --red: #e54444;
            --orange: #f39a30;
            --yellow: #e1b62e;

            --text-primary: #123b69;
            --text-secondary: #7189a4;
            --text-muted: #a7b5c5;

            --border: #d3dfeb;
            --background: #f3f7fb;
            --white: #ffffff;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html,
        body {
            width: 100%;
            min-height: 100%;
        }

        body {
            font-family: Inter, "Segoe UI", Arial, sans-serif;
            background: var(--background);
            color: var(--text-primary);
        }

        button,
        input {
            font-family: inherit;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        .page-wrapper {
            display: flex;
            min-height: 100vh;
            overflow: hidden;
        }

        /* =====================================================
           PANEL KIRI
        ===================================================== */

        .left-panel {
            position: relative;
            width: 38.2%;
            min-height: 100vh;
            padding: 31px 26px 27px;
            color: var(--white);
            background:
                linear-gradient(
                    160deg,
                    #07356f 0%,
                    #084990 48%,
                    #0870cb 100%
                );
            overflow: hidden;
        }

        .decoration-circle {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.08);
            pointer-events: none;
        }

        .circle-top {
            width: 210px;
            height: 210px;
            top: -64px;
            right: -61px;
        }

        .circle-right {
            width: 132px;
            height: 132px;
            right: -50px;
            top: 213px;
            background: rgba(255, 255, 255, 0.06);
        }

        .circle-bottom {
            width: 210px;
            height: 210px;
            left: -76px;
            bottom: -80px;
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
            width: 27px;
            height: 27px;
            flex-shrink: 0;
        }

        .brand-logo-shape {
            position: absolute;
            inset: 5px;
            border-radius: 3px;
            transform: rotate(45deg);
            background:
                linear-gradient(
                    135deg,
                    #c6e3ff 0%,
                    #ffffff 45%,
                    #6ca7df 100%
                );
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
        }

        .brand-logo-shape::after {
            content: "";
            position: absolute;
            left: 6px;
            top: 6px;
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #0758ad;
        }

        .brand-text-primary {
            display: block;
            font-size: 9px;
            line-height: 1.3;
            font-weight: 600;
            letter-spacing: 0.65px;
            color: rgba(255, 255, 255, 0.78);
        }

        .brand-text-secondary {
            display: block;
            font-size: 10px;
            line-height: 1.35;
            font-weight: 700;
            color: #ffffff;
        }

        .system-label {
            margin-top: 28px;
            font-size: 8px;
            font-weight: 600;
            letter-spacing: 1px;
            color: rgba(202, 226, 255, 0.71);
        }

        .application-title {
            margin-top: 10px;
            font-size: 19px;
            line-height: 1.15;
            font-weight: 800;
            letter-spacing: -0.4px;
        }

        .title-line {
            width: 32px;
            height: 3px;
            margin-top: 9px;
            border-radius: 20px;
            background: #78baff;
        }

        .recovery-title {
            margin-top: 18px;
            font-size: 11px;
            font-weight: 700;
        }

        .recovery-description {
            width: 238px;
            max-width: 100%;
            margin-top: 7px;
            font-size: 10px;
            line-height: 1.65;
            color: rgba(225, 239, 255, 0.75);
        }

        /* Ilustrasi keamanan */

        .security-illustration {
            position: relative;
            width: 280px;
            height: 164px;
            max-width: 100%;
            margin: 22px auto 10px;
        }

        .illustration-dot {
            position: absolute;
            width: 5px;
            height: 5px;
            border-radius: 50%;
            background: rgba(201, 226, 255, 0.3);
        }

        .dot-one {
            left: 39px;
            top: 13px;
        }

        .dot-two {
            left: 35px;
            bottom: 16px;
        }

        .dot-three {
            right: 28px;
            bottom: 7px;
        }

        .illustration-lock {
            position: absolute;
            left: 74px;
            top: 10px;
            width: 28px;
            height: 25px;
            border: 1.5px solid rgba(221, 239, 255, 0.6);
            border-radius: 6px;
            background: rgba(255, 255, 255, 0.05);
        }

        .illustration-lock::before {
            content: "";
            position: absolute;
            left: 4px;
            top: -11px;
            width: 16px;
            height: 13px;
            border: 1.5px solid rgba(221, 239, 255, 0.6);
            border-bottom: none;
            border-radius: 10px 10px 0 0;
        }

        .illustration-lock::after {
            content: "";
            position: absolute;
            left: 10px;
            top: 9px;
            width: 4px;
            height: 4px;
            border-radius: 50%;
            background: rgba(224, 241, 255, 0.85);
        }

        .illustration-envelope {
            position: absolute;
            left: 48px;
            top: 48px;
            width: 119px;
            height: 80px;
            border: 1.5px solid rgba(213, 233, 255, 0.46);
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.05);
            overflow: hidden;
            box-shadow: 0 10px 20px rgba(0, 30, 80, 0.12);
        }

        .illustration-envelope::before,
        .illustration-envelope::after {
            content: "";
            position: absolute;
            top: 17px;
            width: 80px;
            height: 1.5px;
            background: rgba(213, 233, 255, 0.36);
        }

        .illustration-envelope::before {
            left: -8px;
            transform: rotate(37deg);
        }

        .illustration-envelope::after {
            right: -8px;
            transform: rotate(-37deg);
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

        .mini-code {
            position: absolute;
            left: 139px;
            top: 43px;
            display: flex;
            gap: 4px;
        }

        .mini-code span {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 14px;
            height: 17px;
            border: 1px solid rgba(221, 239, 255, 0.5);
            border-radius: 3px;
            color: rgba(236, 245, 255, 0.85);
            background: rgba(255, 255, 255, 0.08);
            font-size: 8px;
        }

        .illustration-shield {
            position: absolute;
            right: 38px;
            top: 76px;
            width: 45px;
            height: 53px;
            border: 1.7px solid rgba(220, 239, 255, 0.56);
            background: rgba(255, 255, 255, 0.05);
            clip-path:
                polygon(
                    50% 0%,
                    93% 17%,
                    86% 72%,
                    50% 100%,
                    14% 72%,
                    7% 17%
                );
        }

        .illustration-shield::after {
            content: "";
            position: absolute;
            left: 14px;
            top: 20px;
            width: 18px;
            height: 10px;
            border-left: 2px solid #d9efff;
            border-bottom: 2px solid #d9efff;
            transform: rotate(-45deg);
        }

        /* Alur pemulihan */

        .recovery-flow {
            margin-top: auto;
        }

        .flow-title {
            margin-bottom: 11px;
            font-size: 8px;
            font-weight: 600;
            letter-spacing: 0.6px;
            color: rgba(219, 237, 255, 0.7);
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
            font-size: 7px;
            font-weight: 600;
            white-space: nowrap;
            color: #edf7ff;
            background: rgba(255, 255, 255, 0.16);
        }

        .flow-item.active {
            background: rgba(128, 188, 249, 0.5);
        }

        .flow-arrow {
            color: rgba(224, 241, 255, 0.48);
            font-size: 9px;
        }

        .login-flow {
            margin-top: 8px;
        }

        .left-footer {
            margin-top: 19px;
            padding-top: 16px;
            border-top: 1px solid rgba(255, 255, 255, 0.14);
            color: rgba(215, 234, 255, 0.54);
            font-size: 7.5px;
        }

        /* =====================================================
           PANEL KANAN
        ===================================================== */

        .right-panel {
            position: relative;
            width: 61.8%;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 28px 30px;
            background: #f3f7fb;
        }

        .password-card {
            width: 100%;
            max-width: 426px;
            padding: 21px 22px 23px;
            border: 1px solid rgba(215, 225, 236, 0.78);
            border-radius: 15px;
            background: #ffffff;
            box-shadow:
                0 15px 40px rgba(58, 88, 123, 0.11),
                0 2px 7px rgba(58, 88, 123, 0.07);
        }

        .recovery-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 5px 10px;
            border-radius: 20px;
            color: #0762bd;
            background: #edf6ff;
            font-size: 9px;
            font-weight: 650;
        }

        /* Stepper */

        .stepper {
            display: grid;
            grid-template-columns:
                25px 1fr
                25px 1fr
                25px 1fr
                25px;
            align-items: start;
            margin: 18px 0 24px;
        }

        .step {
            position: relative;
            z-index: 2;
            text-align: center;
        }

        .step-circle {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 25px;
            height: 25px;
            margin: 0 auto;
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
            color: #9aaabd;
            background: #e7edf5;
        }

        .step-label {
            position: absolute;
            top: 31px;
            left: 50%;
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
            margin-top: 2px;
            font-size: 17px;
            line-height: 1.3;
            font-weight: 800;
            letter-spacing: -0.2px;
        }

        .card-description {
            max-width: 330px;
            margin-top: 5px;
            margin-bottom: 17px;
            color: var(--text-secondary);
            font-size: 10px;
            line-height: 1.55;
        }

        /* Form */

        .form-group {
            margin-bottom: 12px;
        }

        .form-label {
            display: block;
            margin-bottom: 6px;
            color: #21466f;
            font-size: 10px;
            font-weight: 700;
        }

        .required {
            color: #ec4f5b;
        }

        .password-input-wrapper {
            position: relative;
        }

        .password-input {
            width: 100%;
            height: 39px;
            padding: 0 40px 0 35px;
            border: 1.4px solid var(--border);
            border-radius: 12px;
            outline: none;
            color: #244a72;
            background: #fbfdff;
            font-size: 10px;
            transition:
                border-color 0.2s ease,
                box-shadow 0.2s ease,
                background 0.2s ease;
        }

        .password-input::placeholder {
            color: #aab8c8;
        }

        .password-input:hover {
            border-color: #abc0d7;
        }

        .password-input:focus {
            border-color: #397fc8;
            background: #ffffff;
            box-shadow: 0 0 0 3px rgba(27, 111, 198, 0.08);
        }

        .input-icon {
            position: absolute;
            top: 50%;
            left: 12px;
            transform: translateY(-50%);
            color: #9eb0c2;
            font-size: 12px;
            pointer-events: none;
        }

        .toggle-password {
            position: absolute;
            top: 50%;
            right: 11px;
            transform: translateY(-50%);
            display: flex;
            align-items: center;
            justify-content: center;
            border: none;
            outline: none;
            color: #9eb0c2;
            background: transparent;
            cursor: pointer;
            font-size: 12px;
        }

        .toggle-password:hover {
            color: #075dbd;
        }

        /* Password strength */

        .strength-section {
            margin-top: 8px;
            margin-bottom: 15px;
        }

        .strength-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 6px;
            font-size: 8px;
            font-weight: 650;
        }

        .strength-title {
            color: #45627f;
        }

        .strength-label {
            color: var(--red);
            font-weight: 700;
        }

        .strength-bars {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 4px;
        }

        .strength-bar {
            height: 4px;
            border-radius: 20px;
            background: #e6ebf1;
            transition: background 0.25s ease;
        }

        .criteria-list {
            display: flex;
            flex-direction: column;
            gap: 5px;
            margin-top: 8px;
        }

        .criteria-item {
            display: flex;
            align-items: center;
            gap: 6px;
            color: #a0afbf;
            font-size: 8px;
            transition: color 0.2s ease;
        }

        .criteria-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 11px;
            height: 11px;
            flex-shrink: 0;
            border-radius: 50%;
            color: #a8b5c3;
            background: #e7edf3;
            font-size: 7px;
        }

        .criteria-item.valid {
            color: #38a866;
        }

        .criteria-item.valid .criteria-icon {
            color: #ffffff;
            background: #31b66d;
        }

        .confirm-message {
            display: none;
            margin-top: 5px;
            font-size: 8px;
        }

        .confirm-message.error {
            display: block;
            color: #dc4d4d;
        }

        .confirm-message.success {
            display: block;
            color: #25a55c;
        }

        .submit-button {
            width: 100%;
            height: 42px;
            margin-top: 3px;
            border: none;
            border-radius: 12px;
            color: #ffffff;
            background: #0862c4;
            box-shadow: 0 8px 18px rgba(8, 98, 196, 0.2);
            font-size: 10px;
            font-weight: 700;
            cursor: pointer;
            transition:
                background 0.2s ease,
                transform 0.15s ease,
                box-shadow 0.2s ease;
        }

        .submit-button i {
            margin-right: 7px;
        }

        .submit-button:hover:not(:disabled) {
            background: #0754aa;
            transform: translateY(-1px);
            box-shadow: 0 10px 20px rgba(8, 98, 196, 0.25);
        }

        .submit-button:disabled {
            color: rgba(255, 255, 255, 0.88);
            background: #80afe0;
            box-shadow: none;
            cursor: not-allowed;
        }

        .right-footer {
            margin-top: 15px;
            text-align: center;
            color: #adbaca;
            font-size: 8px;
            line-height: 1.8;
        }

        .right-footer a {
            color: #075dbd;
            font-weight: 600;
        }

        .help-button {
            position: fixed;
            right: 10px;
            bottom: 9px;
            z-index: 10;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 23px;
            height: 23px;
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

        .server-message ul {
            margin: 0;
            padding-left: 16px;
        }

        .server-message-error {
            color: #a52f3f;
            border: 1px solid #f1c1c8;
            background: #fff2f4;
        }

        /* =====================================================
           RESPONSIVE
        ===================================================== */

        @media (max-width: 900px) {
            .page-wrapper {
                display: block;
                overflow: visible;
            }

            .left-panel,
            .right-panel {
                width: 100%;
                min-height: auto;
            }

            .left-panel {
                padding: 28px 23px;
            }

            .left-content {
                min-height: auto;
            }

            .security-illustration,
            .recovery-flow,
            .circle-right {
                display: none;
            }

            .right-panel {
                padding: 32px 16px 50px;
            }

            .password-card {
                max-width: 450px;
            }
        }

        @media (max-width: 480px) {
            .right-panel {
                padding: 25px 12px 45px;
            }

            .password-card {
                padding: 20px 16px 22px;
            }

            .step-label {
                width: 62px;
                font-size: 7px;
            }

            .card-title {
                font-size: 16px;
            }
        }

        @media (max-width: 370px) {
            .password-card {
                padding-left: 13px;
                padding-right: 13px;
            }

            .step-label {
                width: 55px;
                font-size: 6.5px;
            }
        }
    </style>
</head>

<body>

<div class="page-wrapper">

    <!-- PANEL KIRI -->
    <aside class="left-panel">

        <div class="decoration-circle circle-top"></div>
        <div class="decoration-circle circle-right"></div>
        <div class="decoration-circle circle-bottom"></div>

        <div class="left-content">

            <div>
                <div class="brand">
                    <div class="brand-logo">
                        <div class="brand-logo-shape"></div>
                    </div>

                    <div>
                        <span class="brand-text-primary">
                            KEMENTERIAN PERDAGANGAN RI
                        </span>

                        <span class="brand-text-secondary">
                            Biro Perencanaan
                        </span>
                    </div>
                </div>

                <div class="system-label">
                    SISTEM INFORMASI
                </div>

                <h1 class="application-title">
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

            <div class="security-illustration">

                <span class="illustration-dot dot-one"></span>
                <span class="illustration-dot dot-two"></span>
                <span class="illustration-dot dot-three"></span>

                <div class="illustration-lock"></div>

                <div class="mini-code">
                    <span>3</span>
                    <span>8</span>
                    <span>4</span>
                </div>

                <div class="illustration-envelope">
                    <span class="envelope-bottom-left"></span>
                    <span class="envelope-bottom-right"></span>
                </div>

                <div class="illustration-shield"></div>

            </div>

            <div class="recovery-flow">

                <div class="flow-title">
                    ALUR PEMULIHAN
                </div>

                <div class="flow-list">

                    <div class="flow-item">
                        <i class="bi bi-envelope-fill"></i>
                        Masukkan Email
                    </div>

                    <i class="bi bi-chevron-right flow-arrow"></i>

                    <div class="flow-item">
                        <i class="bi bi-key-fill"></i>
                        Verifikasi OTP
                    </div>

                    <i class="bi bi-chevron-right flow-arrow"></i>

                    <div class="flow-item active">
                        <i class="bi bi-lock-fill"></i>
                        Buat Password Baru
                    </div>

                    <i class="bi bi-chevron-right flow-arrow"></i>

                </div>

                <div class="login-flow">
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

    <!-- PANEL KANAN -->
    <main class="right-panel">

        <section class="password-card">

            <div class="recovery-badge">
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

                <div class="step completed">
                    <div class="step-circle">
                        <i class="bi bi-check-lg"></i>
                    </div>
                    <span class="step-label">Verifikasi</span>
                </div>

                <div class="step-line completed"></div>

                <div class="step current">
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
                Buat Kata Sandi Baru
            </h2>

            <p class="card-description">
                Buat kata sandi yang kuat dan tidak pernah digunakan
                sebelumnya untuk menjaga keamanan akun Anda.
            </p>

            <form
                id="newPasswordForm"
                action="{{ route('forgot.password.update') }}"
                method="POST"
                autocomplete="off"
            >
                @csrf
                <input
                    type="hidden"
                    name="reset_token"
                    value="{{ $resetToken }}"
                >

                @if (session('error'))
                    <div class="server-message server-message-error">
                        {{ session('error') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="server-message server-message-error">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="form-group">

                    <label for="password" class="form-label">
                        Kata Sandi Baru
                        <span class="required">*</span>
                    </label>

                    <div class="password-input-wrapper">

                        <i class="bi bi-lock input-icon"></i>

                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="password-input"
                            placeholder="Masukkan kata sandi baru"
                            autocomplete="new-password"
                            minlength="8"
                            required
                        >

                        <button
                            type="button"
                            class="toggle-password"
                            data-target="password"
                            aria-label="Tampilkan kata sandi"
                        >
                            <i class="bi bi-eye"></i>
                        </button>

                    </div>

                    <div class="strength-section">

                        <div class="strength-header">
                            <span class="strength-title">
                                Kekuatan Kata Sandi
                            </span>

                            <span class="strength-label" id="strengthLabel">
                                Sangat Lemah
                            </span>
                        </div>

                        <div class="strength-bars">
                            <span class="strength-bar"></span>
                            <span class="strength-bar"></span>
                            <span class="strength-bar"></span>
                            <span class="strength-bar"></span>
                        </div>

                        <div class="criteria-list">

                            <div class="criteria-item" id="ruleLength">
                                <span class="criteria-icon">
                                    <i class="bi bi-check-lg"></i>
                                </span>
                                Minimal 8 karakter
                            </div>

                            <div class="criteria-item" id="ruleUppercase">
                                <span class="criteria-icon">
                                    <i class="bi bi-check-lg"></i>
                                </span>
                                Mengandung huruf kapital (A–Z)
                            </div>

                            <div class="criteria-item" id="ruleLowercase">
                                <span class="criteria-icon">
                                    <i class="bi bi-check-lg"></i>
                                </span>
                                Mengandung huruf kecil (a–z)
                            </div>

                            <div class="criteria-item" id="ruleNumber">
                                <span class="criteria-icon">
                                    <i class="bi bi-check-lg"></i>
                                </span>
                                Mengandung angka (0–9)
                            </div>

                            <div class="criteria-item" id="ruleSpecial">
                                <span class="criteria-icon">
                                    <i class="bi bi-check-lg"></i>
                                </span>
                                Mengandung karakter khusus (!@#$...)
                            </div>

                        </div>

                    </div>

                </div>

                <div class="form-group">

                    <label for="passwordConfirmation" class="form-label">
                        Konfirmasi Kata Sandi
                        <span class="required">*</span>
                    </label>

                    <div class="password-input-wrapper">

                        <i class="bi bi-lock input-icon"></i>

                        <input
                            type="password"
                            id="passwordConfirmation"
                            name="password_confirmation"
                            class="password-input"
                            placeholder="Ulangi kata sandi baru"
                            autocomplete="new-password"
                            minlength="8"
                            required
                        >

                        <button
                            type="button"
                            class="toggle-password"
                            data-target="passwordConfirmation"
                            aria-label="Tampilkan konfirmasi kata sandi"
                        >
                            <i class="bi bi-eye"></i>
                        </button>

                    </div>

                    <div
                        class="confirm-message"
                        id="confirmationMessage"
                    ></div>

                </div>

                <button
                    type="submit"
                    class="submit-button"
                    id="submitButton"
                    disabled
                >
                    <i class="bi bi-key"></i>
                    Simpan Kata Sandi Baru
                </button>

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

    </main>

</div>

<a href="#" class="help-button" aria-label="Bantuan">
    ?
</a>

<script>
    const passwordInput = document.getElementById("password");
    const confirmationInput =
        document.getElementById("passwordConfirmation");

    const strengthLabel =
        document.getElementById("strengthLabel");

    const strengthBars =
        Array.from(document.querySelectorAll(".strength-bar"));

    const confirmationMessage =
        document.getElementById("confirmationMessage");

    const submitButton =
        document.getElementById("submitButton");

    const rules = {
        length: {
            element: document.getElementById("ruleLength"),
            validate: value => value.length >= 8
        },
        uppercase: {
            element: document.getElementById("ruleUppercase"),
            validate: value => /[A-Z]/.test(value)
        },
        lowercase: {
            element: document.getElementById("ruleLowercase"),
            validate: value => /[a-z]/.test(value)
        },
        number: {
            element: document.getElementById("ruleNumber"),
            validate: value => /[0-9]/.test(value)
        },
        special: {
            element: document.getElementById("ruleSpecial"),
            validate: value =>
                /[!@#$%^&*()_\-+=\[\]{};:'",.<>/?\\|`~]/.test(value)
        }
    };

    const strengthLevels = [
        {
            minimumScore: 0,
            label: "Sangat Lemah",
            color: "#e54444",
            activeBars: 1
        },
        {
            minimumScore: 2,
            label: "Lemah",
            color: "#f07b32",
            activeBars: 1
        },
        {
            minimumScore: 3,
            label: "Sedang",
            color: "#e1b62e",
            activeBars: 2
        },
        {
            minimumScore: 4,
            label: "Kuat",
            color: "#5dbb6e",
            activeBars: 3
        },
        {
            minimumScore: 5,
            label: "Sangat Kuat",
            color: "#17a85a",
            activeBars: 4
        }
    ];

    function evaluatePassword() {
        const password = passwordInput.value;

        let score = 0;

        Object.values(rules).forEach(rule => {
            const isValid = rule.validate(password);

            rule.element.classList.toggle("valid", isValid);

            if (isValid) {
                score++;
            }
        });

        updateStrengthIndicator(score, password.length);
        validateConfirmation();
        updateSubmitButton();
    }

    function updateStrengthIndicator(score, passwordLength) {
        let selectedLevel = strengthLevels[0];

        strengthLevels.forEach(level => {
            if (score >= level.minimumScore) {
                selectedLevel = level;
            }
        });

        if (passwordLength === 0) {
            selectedLevel = strengthLevels[0];
        }

        strengthLabel.textContent = selectedLevel.label;
        strengthLabel.style.color = selectedLevel.color;

        strengthBars.forEach((bar, index) => {
            const shouldActivate =
                passwordLength > 0 &&
                index < selectedLevel.activeBars;

            bar.style.background = shouldActivate
                ? selectedLevel.color
                : "#e6ebf1";
        });
    }

    function validateConfirmation() {
        const password = passwordInput.value;
        const confirmation = confirmationInput.value;

        confirmationMessage.className = "confirm-message";
        confirmationMessage.textContent = "";

        if (confirmation.length === 0) {
            return false;
        }

        if (password !== confirmation) {
            confirmationMessage.classList.add("error");
            confirmationMessage.textContent =
                "Konfirmasi kata sandi belum sama.";

            return false;
        }

        confirmationMessage.classList.add("success");
        confirmationMessage.textContent =
            "Konfirmasi kata sandi sesuai.";

        return true;
    }

    function areAllRulesValid() {
        const password = passwordInput.value;

        return Object.values(rules).every(rule =>
            rule.validate(password)
        );
    }

    function updateSubmitButton() {
        const passwordValid = areAllRulesValid();

        const confirmationValid =
            passwordInput.value !== "" &&
            passwordInput.value === confirmationInput.value;

        submitButton.disabled =
            !(passwordValid && confirmationValid);
    }

    passwordInput.addEventListener(
        "input",
        evaluatePassword
    );

    confirmationInput.addEventListener("input", () => {
        validateConfirmation();
        updateSubmitButton();
    });

    document
        .querySelectorAll(".toggle-password")
        .forEach(button => {

            button.addEventListener("click", function () {
                const targetId =
                    this.getAttribute("data-target");

                const targetInput =
                    document.getElementById(targetId);

                const icon =
                    this.querySelector("i");

                const isPassword =
                    targetInput.type === "password";

                targetInput.type =
                    isPassword ? "text" : "password";

                icon.className =
                    isPassword
                        ? "bi bi-eye-slash"
                        : "bi bi-eye";

                this.setAttribute(
                    "aria-label",
                    isPassword
                        ? "Sembunyikan kata sandi"
                        : "Tampilkan kata sandi"
                );
            });
        });

    document
        .getElementById("newPasswordForm")
        .addEventListener("submit", event => {

            if (submitButton.disabled) {
                event.preventDefault();
                return;
            }

            submitButton.disabled = true;
            submitButton.innerHTML =
                '<i class="bi bi-hourglass-split"></i> Menyimpan...';
        });
</script>

</body>
</html>