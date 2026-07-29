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
            --primary-100: #eaf1ff;

            --success: #149653;
            --success-soft: #eefbf4;
            --danger: #d9364a;
            --danger-soft: #fff1f3;
            --warning: #b7791f;
            --warning-soft: #fff9e8;

            --text-primary: #173255;
            --text-secondary: #687d98;
            --text-muted: #91a2b8;
            --border: #d7e1ed;
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

        button,
        a {
            -webkit-tap-highlight-color: transparent;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        .register-page {
            display: flex;
            min-height: 100vh;
        }

        /* Panel kiri */
        .left-panel {
            position: relative;
            width: 43%;
            min-height: 100vh;
            padding: 34px 34px 24px;
            color: #ffffff;
            background:
                radial-gradient(circle at 42% 24%, rgba(255,255,255,.10), transparent 22%),
                linear-gradient(155deg, #102d83 0%, #173fa4 50%, #2468f2 100%);
            overflow: hidden;
        }

        .left-panel::before,
        .left-panel::after {
            content: "";
            position: absolute;
            border-radius: 50%;
            background: rgba(255,255,255,.04);
        }

        .left-panel::before {
            width: 390px;
            height: 390px;
            left: -190px;
            bottom: -210px;
        }

        .left-panel::after {
            width: 240px;
            height: 240px;
            right: -120px;
            top: -110px;
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
            gap: 12px;
        }

        .brand-logo {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 38px;
            height: 38px;
            border: 1px solid rgba(255,255,255,.32);
            border-radius: 50%;
            background: rgba(255,255,255,.12);
            font-size: 17px;
        }

        .brand-government {
            color: rgba(255,255,255,.78);
            font-size: 11px;
        }

        .brand-unit {
            margin-top: 3px;
            font-size: 13px;
            font-weight: 700;
        }

        .portal-badge {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            width: fit-content;
            margin-top: 38px;
            padding: 7px 12px;
            border: 1px solid rgba(255,255,255,.18);
            border-radius: 999px;
            background: rgba(255,255,255,.10);
            font-size: 11px;
        }

        .portal-badge span {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: #48e586;
            box-shadow: 0 0 0 4px rgba(72,229,134,.12);
        }

        .app-title {
            margin-top: 22px;
            font-size: clamp(26px, 2.4vw, 38px);
            line-height: 1.17;
            font-weight: 800;
            letter-spacing: -.5px;
        }

        .app-description {
            max-width: 500px;
            margin-top: 12px;
            color: rgba(255,255,255,.76);
            font-size: 13px;
            line-height: 1.7;
        }

        .security-illustration {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 180px;
            height: 180px;
            margin: 38px auto 32px;
            border: 1px solid rgba(255,255,255,.11);
            border-radius: 50%;
            background: rgba(255,255,255,.06);
        }

        .security-illustration i {
            font-size: 72px;
            color: rgba(255,255,255,.88);
            filter: drop-shadow(0 10px 20px rgba(0,0,0,.12));
        }

        .flow-title {
            margin-bottom: 12px;
            color: rgba(255,255,255,.58);
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 1.2px;
        }

        .flow-list {
            display: flex;
            flex-direction: column;
            gap: 9px;
        }

        .flow-item {
            display: flex;
            align-items: center;
            gap: 12px;
            min-height: 58px;
            padding: 10px 13px;
            border: 1px solid rgba(255,255,255,.08);
            border-radius: 12px;
            background: rgba(255,255,255,.055);
        }

        .flow-item.completed {
            background: rgba(255,255,255,.085);
        }

        .flow-item.active {
            border-color: rgba(255,255,255,.33);
            background: rgba(255,255,255,.17);
            box-shadow: 0 9px 24px rgba(0,0,0,.08);
        }

        .flow-number {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 28px;
            height: 28px;
            flex-shrink: 0;
            border-radius: 50%;
            color: rgba(255,255,255,.72);
            background: rgba(255,255,255,.13);
            font-size: 11px;
            font-weight: 700;
        }

        .flow-item.completed .flow-number {
            color: #ffffff;
            background: #18a85e;
        }

        .flow-item.active .flow-number {
            color: var(--primary-700);
            background: #ffffff;
        }

        .flow-name {
            font-size: 12px;
            font-weight: 700;
        }

        .flow-description {
            margin-top: 3px;
            color: rgba(255,255,255,.58);
            font-size: 10px;
            line-height: 1.4;
        }

        .left-footer {
            margin-top: auto;
            padding-top: 22px;
            border-top: 1px solid rgba(255,255,255,.14);
            text-align: center;
            color: rgba(255,255,255,.47);
            font-size: 10px;
            line-height: 1.6;
        }

        /* Panel kanan */
        .right-panel {
            width: 57%;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 40px 28px;
        }

        .stepper {
            width: 100%;
            max-width: 470px;
            display: grid;
            grid-template-columns: 34px 1fr 34px 1fr 34px;
            align-items: start;
            margin-bottom: 28px;
        }

        .step {
            position: relative;
            text-align: center;
        }

        .step-circle {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 34px;
            height: 34px;
            margin: auto;
            border: 1px solid #d6e0ec;
            border-radius: 50%;
            color: #a6b3c3;
            background: #ffffff;
            font-size: 11px;
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
            box-shadow: 0 0 0 6px rgba(36,104,242,.09);
        }

        .step-label {
            position: absolute;
            top: 42px;
            left: 50%;
            width: 105px;
            transform: translateX(-50%);
            color: #98a8ba;
            font-size: 10px;
            line-height: 1.35;
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
            height: 2px;
            margin-top: 16px;
            background: #dce5ef;
        }

        .step-line.completed {
            background: var(--success);
        }

        .otp-card {
            width: 100%;
            max-width: 510px;
            margin-top: 30px;
            padding: 32px;
            border: 1px solid rgba(213,224,236,.9);
            border-radius: 20px;
            background: #ffffff;
            box-shadow:
                0 22px 55px rgba(47,73,105,.11),
                0 4px 12px rgba(47,73,105,.05);
        }

        .otp-icon-wrapper {
            display: flex;
            justify-content: center;
            margin-bottom: 15px;
        }

        .otp-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 58px;
            height: 58px;
            border-radius: 16px;
            color: var(--primary-600);
            background: var(--primary-100);
            font-size: 28px;
        }

        .card-title {
            text-align: center;
            font-size: 22px;
            font-weight: 800;
            letter-spacing: -.2px;
        }

        .card-description {
            max-width: 390px;
            margin: 8px auto 0;
            text-align: center;
            color: var(--text-secondary);
            font-size: 13px;
            line-height: 1.65;
        }

        .masked-email {
            color: var(--primary-600);
            font-weight: 700;
            word-break: break-word;
        }

        .alert {
            display: flex;
            align-items: flex-start;
            gap: 9px;
            margin: 18px 0 0;
            padding: 11px 13px;
            border-radius: 11px;
            font-size: 12px;
            line-height: 1.55;
        }

        .alert i {
            margin-top: 1px;
            flex-shrink: 0;
        }

        .alert-success {
            color: #126a3e;
            border: 1px solid #b9e7ce;
            background: var(--success-soft);
        }

        .alert-error {
            color: #a52637;
            border: 1px solid #f0b9c1;
            background: var(--danger-soft);
        }

        .countdown-box {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            width: fit-content;
            margin: 18px auto 22px;
            padding: 7px 12px;
            border: 1px solid #dfe7f0;
            border-radius: 999px;
            color: #71839a;
            background: #f9fbfe;
            font-size: 11px;
        }

        .countdown {
            min-width: 31px;
            color: var(--primary-600);
            font-weight: 800;
        }

        .otp-inputs {
            display: flex;
            justify-content: center;
            gap: 10px;
        }

        .otp-input {
            width: 52px;
            height: 58px;
            border: 1.5px solid var(--border);
            border-radius: 12px;
            outline: none;
            color: var(--text-primary);
            background: #ffffff;
            text-align: center;
            font-size: 22px;
            font-weight: 800;
            caret-color: var(--primary-600);
            transition: border-color .18s ease, box-shadow .18s ease, background .18s ease;
        }

        .otp-input:hover {
            border-color: #aebed1;
        }

        .otp-input:focus {
            border-color: var(--primary-600);
            box-shadow: 0 0 0 4px rgba(36,104,242,.09);
        }

        .otp-input.filled {
            border-color: #6b9cf6;
            background: #f7faff;
        }

        .otp-input.error {
            border-color: var(--danger);
            background: var(--danger-soft);
        }

        .otp-message {
            display: none;
            align-items: center;
            justify-content: center;
            gap: 6px;
            margin-top: 10px;
            font-size: 11px;
            line-height: 1.5;
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

        .spam-information {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            margin-top: 15px;
            color: var(--text-muted);
            font-size: 11px;
        }

        .resend-wrapper {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            margin-top: 15px;
            color: #8798ad;
            font-size: 11px;
        }

        .resend-button {
            border: 0;
            padding: 2px;
            color: var(--primary-600);
            background: transparent;
            font-size: 11px;
            font-weight: 800;
            cursor: pointer;
        }

        .resend-button:hover:not(:disabled) {
            text-decoration: underline;
        }

        .resend-button:disabled {
            color: #aeb9c7;
            cursor: not-allowed;
        }

        .form-actions {
            display: grid;
            grid-template-columns: 118px 1fr;
            gap: 11px;
            margin-top: 22px;
        }

        .back-button,
        .verify-button {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 44px;
            border-radius: 11px;
            font-size: 12px;
            font-weight: 700;
            transition: .18s ease;
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
            border: 0;
            color: #ffffff;
            background: var(--primary-600);
            box-shadow: 0 8px 18px rgba(36,104,242,.22);
            cursor: pointer;
        }

        .verify-button:hover:not(:disabled) {
            background: #1959dc;
            transform: translateY(-1px);
        }

        .verify-button:disabled {
            background: #9abff6;
            box-shadow: none;
            cursor: not-allowed;
        }

        @media (max-width: 960px) {
            .register-page {
                display: block;
            }

            .left-panel,
            .right-panel {
                width: 100%;
                min-height: auto;
            }

            .left-panel {
                padding: 25px 22px;
            }

            .left-content {
                min-height: auto;
            }

            .security-illustration,
            .flow-list,
            .flow-title,
            .left-footer {
                display: none;
            }

            .portal-badge {
                margin-top: 22px;
            }

            .app-title {
                font-size: 27px;
            }

            .right-panel {
                min-height: calc(100vh - 260px);
                padding: 42px 16px 55px;
            }
        }

        @media (max-width: 560px) {
            .otp-card {
                padding: 27px 17px 22px;
                border-radius: 16px;
            }

            .otp-inputs {
                gap: 6px;
            }

            .otp-input {
                width: calc((100% - 30px) / 6);
                min-width: 39px;
                height: 52px;
                font-size: 19px;
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
                width: 78px;
                font-size: 9px;
            }
        }

        @media (max-width: 390px) {
            .otp-input {
                min-width: 34px;
                height: 48px;
                font-size: 17px;
            }
        }
    </style>
</head>

<body>
@php
    $emailValue = $email ?? ($user->email ?? session('register_email', ''));
    $maskedEmail = $emailValue;

    if ($emailValue && str_contains($emailValue, '@')) {
        [$localPart, $domainPart] = explode('@', $emailValue, 2);

        $visiblePrefix = mb_substr($localPart, 0, min(2, mb_strlen($localPart)));
        $maskLength = max(mb_strlen($localPart) - mb_strlen($visiblePrefix), 3);

        $maskedEmail = $visiblePrefix . str_repeat('*', $maskLength) . '@' . $domainPart;
    }
@endphp

<div class="register-page">
    <aside class="left-panel">
        <div class="left-content">
            <div class="brand">
                <div class="brand-logo">
                    <i class="bi bi-star-fill"></i>
                </div>

                <div>
                    <div class="brand-government">Kementerian Perdagangan RI</div>
                    <div class="brand-unit">Biro Perencanaan</div>
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
                Verifikasi alamat email untuk mengaktifkan akun dan menjaga keamanan
                akses ke sistem penelitian RKA-K/L.
            </p>

            <div class="security-illustration" aria-hidden="true">
                <i class="bi bi-shield-lock-fill"></i>
            </div>

            <div class="flow-title">ALUR PENDAFTARAN</div>

            <div class="flow-list">
                <div class="flow-item completed">
                    <div class="flow-number">
                        <i class="bi bi-check-lg"></i>
                    </div>

                    <div>
                        <div class="flow-name">Informasi Akun</div>
                        <div class="flow-description">Data diri dan kredensial login</div>
                    </div>
                </div>

                <div class="flow-item completed">
                    <div class="flow-number">
                        <i class="bi bi-check-lg"></i>
                    </div>

                    <div>
                        <div class="flow-name">Informasi Jabatan</div>
                        <div class="flow-description">Jabatan dan posisi di instansi</div>
                    </div>
                </div>

                <div class="flow-item active">
                    <div class="flow-number">3</div>

                    <div>
                        <div class="flow-name">Verifikasi OTP</div>
                        <div class="flow-description">Konfirmasi melalui kode OTP email</div>
                    </div>
                </div>
            </div>

            <div class="left-footer">
                © {{ date('Y') }} Biro Perencanaan — Kementerian Perdagangan RI<br>
                Seluruh data dilindungi berdasarkan regulasi yang berlaku.
            </div>
        </div>
    </aside>

    <main class="right-panel">
        <div class="stepper" aria-label="Tahapan registrasi">
            <div class="step completed">
                <div class="step-circle">
                    <i class="bi bi-check-lg"></i>
                </div>
                <div class="step-label">Informasi<br>Akun</div>
            </div>

            <div class="step-line completed"></div>

            <div class="step completed">
                <div class="step-circle">
                    <i class="bi bi-check-lg"></i>
                </div>
                <div class="step-label">Informasi<br>Jabatan</div>
            </div>

            <div class="step-line completed"></div>

            <div class="step active">
                <div class="step-circle">3</div>
                <div class="step-label">Verifikasi<br>OTP</div>
            </div>
        </div>

        <section class="otp-card">
            <div class="otp-icon-wrapper">
                <div class="otp-icon">
                    <i class="bi bi-envelope-check-fill"></i>
                </div>
            </div>

            <h2 class="card-title">Verifikasi Email Anda</h2>

            <p class="card-description">
                Masukkan kode OTP 6 digit yang telah dikirim ke
                <span class="masked-email">{{ $maskedEmail ?: 'alamat email Anda' }}</span>.
                Kode berlaku selama 10 menit.
            </p>

            @if (session('success'))
                <div class="alert alert-success" role="alert">
                    <i class="bi bi-check-circle-fill"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-error" role="alert">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <div class="countdown-box" id="countdownBox">
                <i class="bi bi-clock"></i>
                <span id="countdownText">Kirim ulang tersedia dalam</span>
                <span class="countdown" id="countdown">01:00</span>
            </div>

            <form
                id="verifyOtpForm"
                action="{{ route('register.step3') }}"
                method="POST"
                novalidate
            >
                @csrf

                <div class="otp-inputs" id="otpInputs">
                    @for ($index = 0; $index < 6; $index++)
                        <input
                            type="text"
                            class="otp-input"
                            inputmode="numeric"
                            maxlength="1"
                            autocomplete="{{ $index === 0 ? 'one-time-code' : 'off' }}"
                            aria-label="Digit OTP ke-{{ $index + 1 }}"
                        >
                    @endfor
                </div>

                <input
                    type="hidden"
                    id="otp_code"
                    name="otp_code"
                    value="{{ old('otp_code') }}"
                >

                <div class="otp-message error" id="otpError">
                    <i class="bi bi-exclamation-circle-fill"></i>
                    Masukkan kode OTP 6 digit.
                </div>

                <div class="otp-message success" id="otpSuccess">
                    <i class="bi bi-check-circle-fill"></i>
                    Format kode OTP sudah lengkap.
                </div>

                @error('otp_code')
                    <div class="otp-message error show">
                        <i class="bi bi-exclamation-circle-fill"></i>
                        {{ $message }}
                    </div>
                @enderror

                <div class="spam-information">
                    <i class="bi bi-info-circle"></i>
                    Periksa folder Spam atau Junk jika email belum terlihat.
                </div>

                <div class="form-actions">
                    <a
                        href="{{ route('register.step2') }}"
                        class="back-button"
                    >
                        <i class="bi bi-arrow-left"></i>&nbsp;
                        Kembali
                    </a>

                    <button
                        type="submit"
                        class="verify-button"
                        id="verifyButton"
                        disabled
                    >
                        <i class="bi bi-shield-check"></i>&nbsp;
                        Verifikasi dan Aktifkan Akun
                    </button>
                </div>
            </form>

            <form
                id="resendOtpForm"
                method="POST"
                action="{{ route('register.resend_otp') }}"
            >
                @csrf

                <div class="resend-wrapper">
                    <span>Belum menerima kode?</span>

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
        </section>
    </main>
</div>

<script>
    const otpInputs = Array.from(document.querySelectorAll(".otp-input"));
    const hiddenOtpInput = document.getElementById("otp_code");
    const verifyButton = document.getElementById("verifyButton");
    const otpError = document.getElementById("otpError");
    const otpSuccess = document.getElementById("otpSuccess");
    const verifyOtpForm = document.getElementById("verifyOtpForm");
    const resendOtpForm = document.getElementById("resendOtpForm");
    const resendButton = document.getElementById("resendButton");
    const countdownElement = document.getElementById("countdown");
    const countdownText = document.getElementById("countdownText");

    let remainingSeconds = 60;
    let countdownInterval = null;

    function collectOtp() {
        return otpInputs.map(input => input.value).join("");
    }

    function updateOtpValue() {
        const otpCode = collectOtp();

        hiddenOtpInput.value = otpCode;

        otpInputs.forEach(input => {
            input.classList.toggle("filled", input.value !== "");
            input.classList.remove("error");
        });

        const isComplete = /^\d{6}$/.test(otpCode);

        verifyButton.disabled = !isComplete;
        otpError.classList.remove("show");
        otpSuccess.classList.toggle("show", isComplete);
    }

    otpInputs.forEach((input, index) => {
        input.addEventListener("input", event => {
            event.target.value = event.target.value
                .replace(/\D/g, "")
                .slice(0, 1);

            if (event.target.value && index < otpInputs.length - 1) {
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

            if (event.key === "ArrowLeft" && index > 0) {
                event.preventDefault();
                otpInputs[index - 1].focus();
            }

            if (
                event.key === "ArrowRight" &&
                index < otpInputs.length - 1
            ) {
                event.preventDefault();
                otpInputs[index + 1].focus();
            }
        });

        input.addEventListener("focus", () => input.select());
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

            const focusIndex = Math.min(
                Math.max(pastedValue.length - 1, 0),
                otpInputs.length - 1
            );

            otpInputs[focusIndex].focus();
            updateOtpValue();
        });

    verifyOtpForm.addEventListener("submit", event => {
        const otpCode = collectOtp();

        hiddenOtpInput.value = otpCode;

        if (!/^\d{6}$/.test(otpCode)) {
            event.preventDefault();

            otpError.classList.add("show");
            otpSuccess.classList.remove("show");

            otpInputs.forEach(input => input.classList.add("error"));

            return;
        }

        // Form valid: jangan memakai preventDefault().
        verifyButton.disabled = true;
        verifyButton.innerHTML = `
            <i class="bi bi-arrow-repeat"></i>&nbsp;
            Memverifikasi...
        `;
    });

    resendOtpForm.addEventListener("submit", event => {
        if (resendButton.disabled) {
            event.preventDefault();
            return;
        }

        // Form valid: biarkan POST menuju controller.
        resendButton.disabled = true;
        resendButton.innerHTML = `
            <i class="bi bi-arrow-repeat"></i>
            Mengirim...
        `;
    });

    function formatCountdown(seconds) {
        const minutes = Math.floor(seconds / 60)
            .toString()
            .padStart(2, "0");

        const remainder = (seconds % 60)
            .toString()
            .padStart(2, "0");

        return `${minutes}:${remainder}`;
    }

    function startCountdown() {
        clearInterval(countdownInterval);

        resendButton.disabled = true;
        countdownElement.textContent = formatCountdown(remainingSeconds);

        countdownInterval = setInterval(() => {
            remainingSeconds -= 1;
            countdownElement.textContent = formatCountdown(
                Math.max(remainingSeconds, 0)
            );

            if (remainingSeconds <= 0) {
                clearInterval(countdownInterval);
                resendButton.disabled = false;
                countdownText.textContent = "Anda dapat";
                countdownElement.textContent = "";
            }
        }, 1000);
    }

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

    if (otpInputs[0] && !hiddenOtpInput.value) {
        otpInputs[0].focus();
    }
</script>
</body>
</html>