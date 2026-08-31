<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="color-scheme" content="light">
    <title>Verifikasi OTP | Sistem Informasi Penelitian RKA-K/L</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root {
            --primary: #0759b7;
            --primary-bright: #0878d4;
            --primary-soft: #edf5ff;
            --text-primary: #18365b;
            --text-secondary: #607995;
            --text-muted: #879bb1;
            --background: #f5f8fc;
            --border: #dbe5ee;
            --success: #159957;
            --success-soft: #effaf4;
            --danger: #c83446;
            --danger-soft: #fff1f3;
            --shadow-strong: 0 22px 60px rgba(20, 60, 102, .12)
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0
        }

        html,
        body {
            min-height: 100%
        }

        body {
            min-height: 100vh;
            color: var(--text-primary);
            background: var(--background);
            font-family: Inter, "Segoe UI", Arial, sans-serif
        }

        a,
        button,
        input {
            font: inherit
        }

        a {
            text-decoration: none;
            color: inherit
        }

        button,
        input {
            outline: none
        }

        .auth-shell {
            min-height: 100vh;
            display: grid;
            grid-template-columns: minmax(500px, .95fr) minmax(560px, 1.05fr)
        }

        .brand-panel {
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            padding: 38px 46px 34px;
            color: #fff;
            background: linear-gradient(155deg, #06356c 0%, #064996 48%, #0872cf 100%)
        }

        .brand-panel:before,
        .brand-panel:after {
            content: "";
            position: absolute;
            border-radius: 50%;
            pointer-events: none
        }

        .brand-panel:before {
            width: 500px;
            height: 500px;
            top: -245px;
            right: -210px;
            border: 1px solid rgba(255, 255, 255, .09);
            box-shadow: 0 0 0 60px rgba(255, 255, 255, .025), 0 0 0 120px rgba(255, 255, 255, .018)
        }

        .brand-panel:after {
            width: 340px;
            height: 340px;
            bottom: -215px;
            left: -160px;
            background: rgba(255, 255, 255, .035)
        }

        .brand-top,
        .brand-content,
        .brand-footer {
            position: relative;
            z-index: 1
        }

        .brand-top {
            display: flex;
            align-items: center;
            gap: 13px
        }

        .brand-logo-wrap {
            width: 54px;
            height: 54px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 6px;
            border: 1px solid rgba(255, 255, 255, .22);
            border-radius: 14px;
            background: rgba(255, 255, 255, .96);
            box-shadow: 0 10px 28px rgba(3, 32, 68, .18)
        }

        .brand-logo {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain
        }

        .brand-ministry small {
            display: block;
            margin-bottom: 3px;
            color: rgba(232, 243, 255, .74);
            font-size: 8px;
            font-weight: 800;
            letter-spacing: 1.2px;
            text-transform: uppercase
        }

        .brand-ministry strong {
            display: block;
            font-size: 13px;
            font-weight: 850
        }

        .brand-content {
            width: 100%;
            max-width: 650px;
            margin: auto 0;
            padding: 38px 0
        }

        .system-chip {
            width: fit-content;
            display: inline-flex;
            align-items: center;
            gap: 7px;
            min-height: 28px;
            padding: 0 11px;
            border: 1px solid rgba(255, 255, 255, .18);
            border-radius: 999px;
            color: #e7f2ff;
            background: rgba(255, 255, 255, .09);
            font-size: 8px;
            font-weight: 850;
            letter-spacing: .85px;
            text-transform: uppercase
        }

        .brand-title {
            max-width: 610px;
            margin-top: 18px;
            font-size: clamp(31px, 3.5vw, 44px);
            font-weight: 900;
            line-height: 1.08;
            letter-spacing: -1.2px
        }

        .brand-title span {
            color: #a9d4ff
        }

        .brand-description {
            max-width: 610px;
            margin-top: 15px;
            color: rgba(230, 241, 255, .78);
            font-size: 12px;
            line-height: 1.7
        }

        .security-panel {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-top: 26px;
            padding: 16px;
            border: 1px solid rgba(255, 255, 255, .13);
            border-radius: 15px;
            background: rgba(255, 255, 255, .075)
        }

        .security-icon {
            width: 50px;
            height: 50px;
            flex: 0 0 auto;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 14px;
            color: #e8f4ff;
            background: rgba(255, 255, 255, .11);
            font-size: 23px
        }

        .security-panel strong {
            display: block;
            font-size: 11px
        }

        .security-panel span {
            display: block;
            margin-top: 4px;
            color: rgba(226, 241, 255, .66);
            font-size: 9px;
            line-height: 1.5
        }

        .flow {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 7px;
            margin-top: 20px
        }

        .flow-step {
            padding: 10px;
            border: 1px solid rgba(255, 255, 255, .10);
            border-radius: 11px;
            background: rgba(255, 255, 255, .055)
        }

        .flow-step.completed {
            background: rgba(21, 153, 87, .18)
        }

        .flow-step.active {
            border-color: rgba(255, 255, 255, .28);
            background: rgba(255, 255, 255, .14)
        }

        .flow-no {
            width: 22px;
            height: 22px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 7px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .13);
            font-size: 8px;
            font-weight: 900
        }

        .flow-step.completed .flow-no {
            background: var(--success);
            color: #fff
        }

        .flow-step.active .flow-no {
            color: var(--primary);
            background: #fff
        }

        .flow-step strong {
            font-size: 8px
        }

        .flow-step small {
            display: block;
            margin-top: 2px;
            color: rgba(226, 241, 255, .6);
            font-size: 7px;
            line-height: 1.4
        }

        .brand-footer {
            padding-top: 17px;
            border-top: 1px solid rgba(255, 255, 255, .13);
            color: rgba(218, 235, 255, .55);
            font-size: 8px
        }

        .form-panel {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 34px;
            background: var(--background)
        }

        .form-wrap {
            width: 100%;
            max-width: 570px
        }

        .stepper {
            display: grid;
            grid-template-columns: 32px 1fr 32px 1fr 32px;
            align-items: start;
            max-width: 440px;
            margin: 0 auto 26px
        }

        .step {
            text-align: center;
            position: relative
        }

        .step-circle {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: auto;
            border: 1px solid var(--border);
            border-radius: 50%;
            color: #9baabd;
            background: #fff;
            font-size: 10px;
            font-weight: 850
        }

        .step.completed .step-circle {
            color: #fff;
            border-color: var(--success);
            background: var(--success)
        }

        .step.active .step-circle {
            color: #fff;
            border-color: var(--primary);
            background: var(--primary);
            box-shadow: 0 0 0 5px rgba(7, 89, 183, .09)
        }

        .step-label {
            position: absolute;
            top: 39px;
            left: 50%;
            width: 100px;
            transform: translateX(-50%);
            font-size: 8px;
            color: #91a4b9;
            line-height: 1.35
        }

        .step.completed .step-label {
            color: var(--success);
            font-weight: 800
        }

        .step.active .step-label {
            color: var(--primary);
            font-weight: 800
        }

        .step-line {
            height: 1px;
            margin-top: 15px;
            background: #dbe5ee
        }

        .step-line.completed {
            background: var(--success)
        }

        .card {
            margin-top: 37px;
            padding: 27px;
            border: 1px solid var(--border);
            border-radius: 16px;
            background: #fff;
            box-shadow: var(--shadow-strong);
            text-align: center
        }

        .otp-icon {
            width: 56px;
            height: 56px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 13px;
            border-radius: 15px;
            color: var(--primary);
            background: var(--primary-soft);
            font-size: 25px
        }

        .card-kicker {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            min-height: 25px;
            padding: 0 9px;
            border-radius: 999px;
            color: var(--primary);
            background: var(--primary-soft);
            font-size: 8px;
            font-weight: 850;
            letter-spacing: .7px;
            text-transform: uppercase
        }

        .card-title {
            margin-top: 11px;
            font-size: 21px;
            font-weight: 900
        }

        .card-description {
            max-width: 430px;
            margin: 7px auto 0;
            color: var(--text-secondary);
            font-size: 11px;
            line-height: 1.65
        }

        .masked-email {
            color: var(--primary);
            font-weight: 850
        }

        .alert {
            display: flex;
            align-items: flex-start;
            gap: 8px;
            margin-top: 15px;
            padding: 10px 11px;
            border-radius: 10px;
            text-align: left;
            font-size: 9px;
            line-height: 1.5
        }

        .alert-success {
            color: #176b43;
            border: 1px solid #bde4cc;
            background: var(--success-soft)
        }

        .alert-error {
            color: #a82c3c;
            border: 1px solid #f0b7bf;
            background: var(--danger-soft)
        }

        .countdown-box {
            width: fit-content;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            margin: 17px auto 19px;
            padding: 7px 11px;
            border: 1px solid #dce6f0;
            border-radius: 999px;
            color: #73869b;
            background: #f8fafc;
            font-size: 9px
        }

        .countdown {
            color: var(--primary);
            font-weight: 850
        }

        .otp-inputs {
            display: flex;
            justify-content: center;
            gap: 8px
        }

        .otp-input {
            width: 49px;
            height: 55px;
            border: 1.5px solid #d5dee7;
            border-radius: 11px;
            color: var(--text-primary);
            background: #fff;
            text-align: center;
            font-size: 20px;
            font-weight: 900;
            caret-color: var(--primary);
            transition: .18s
        }

        .otp-input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(7, 89, 183, .09)
        }

        .otp-input.filled {
            border-color: #72a7df;
            background: #f8fbff
        }

        .otp-input.error {
            border-color: var(--danger);
            background: var(--danger-soft)
        }

        .otp-message {
            display: none;
            align-items: center;
            justify-content: center;
            gap: 6px;
            margin-top: 9px;
            font-size: 8px
        }

        .otp-message.show {
            display: flex
        }

        .otp-message.error {
            color: var(--danger)
        }

        .otp-message.success {
            color: var(--success)
        }

        .spam-information {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 6px;
            margin-top: 13px;
            color: var(--text-muted);
            font-size: 8px
        }

        .form-actions {
            display: grid;
            grid-template-columns: 120px 1fr;
            gap: 10px;
            margin-top: 18px
        }

        .back-button,
        .verify-button {
            min-height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            border-radius: 9px;
            font-size: 10px;
            font-weight: 850
        }

        .back-button {
            border: 1px solid #d5dee7;
            color: #56708b;
            background: #fff
        }

        .verify-button {
            border: 0;
            color: #fff;
            background: linear-gradient(135deg, var(--primary), var(--primary-bright));
            box-shadow: 0 8px 18px rgba(7, 89, 183, .19);
            cursor: pointer
        }

        .verify-button:disabled {
            background: #a9c4e5;
            box-shadow: none;
            cursor: not-allowed
        }

        .resend-wrapper {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
            margin-top: 13px;
            color: #8293a7;
            font-size: 8px
        }

        .resend-button {
            border: 0;
            background: transparent;
            color: var(--primary);
            font-size: 8px;
            font-weight: 850;
            cursor: pointer
        }

        .resend-button:disabled {
            color: #a8b5c4;
            cursor: not-allowed
        }

        @media(max-width:1024px) {
            .auth-shell {
                display: block
            }

            .brand-panel {
                display: none
            }

            .form-panel {
                min-height: 100vh;
                padding: 30px 18px
            }

            .form-wrap {
                max-width: 620px
            }
        }

        @media(max-width:620px) {
            .form-panel {
                padding: 24px 14px
            }

            .card {
                padding: 23px 15px
            }

            .otp-inputs {
                gap: 5px
            }

            .otp-input {
                width: calc((100% - 25px)/6);
                max-width: 49px;
                height: 50px;
                font-size: 18px
            }

            .form-actions {
                grid-template-columns: 1fr
            }

            .step-label {
                width: 76px;
                font-size: 7px
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
    <div class="auth-shell">
        <aside class="brand-panel">
            <div class="brand-top">
                <div class="brand-logo-wrap"><img src="{{ asset('images/logo-kemendag.png') }}"
                        alt="Logo Kementerian Perdagangan" class="brand-logo"></div>
                <div class="brand-ministry"><small>Kementerian Perdagangan RI</small><strong>Biro Perencanaan</strong>
                </div>
            </div>
            <div class="brand-content">
                <div class="system-chip"><i class="bi bi-shield-lock"></i> Verifikasi Registrasi</div>
                <h1 class="brand-title">Satu langkah lagi untuk <span>mengaktifkan akun</span></h1>
                <p class="brand-description">Kode OTP dikirim ke email yang didaftarkan untuk memastikan alamat email
                    benar-benar berada dalam kendali pengguna sebelum akun digunakan.</p>
                <div class="security-panel">
                    <div class="security-icon"><i class="bi bi-envelope-check"></i></div>
                    <div><strong>Verifikasi berbasis email</strong><span>Masukkan kode 6 digit yang diterima. Jangan
                            membagikan kode OTP kepada pihak lain.</span></div>
                </div>
                <div class="flow">
                    <div class="flow-step completed">
                        <div class="flow-no"><i class="bi bi-check-lg"></i></div><strong>Informasi
                            Akun</strong><small>Identitas selesai.</small>
                    </div>
                    <div class="flow-step completed">
                        <div class="flow-no"><i class="bi bi-check-lg"></i></div>
                        <strong>Jabatan</strong><small>Informasi jabatan telah dilengkapi.</small>
                    </div>
                    <div class="flow-step active">
                        <div class="flow-no">3</div><strong>Verifikasi OTP</strong><small>Konfirmasi email Anda.</small>
                    </div>
                </div>
            </div>
            <div class="brand-footer">© {{ date('Y') }} Biro Perencanaan — Kementerian Perdagangan Republik
                Indonesia</div>
        </aside>
        <main class="form-panel">
            <div class="form-wrap">
                <div class="stepper">
                    <div class="step completed">
                        <div class="step-circle"><i class="bi bi-check-lg"></i></div>
                        <div class="step-label">Informasi<br>Akun</div>
                    </div>
                    <div class="step-line completed"></div>
                    <div class="step completed">
                        <div class="step-circle"><i class="bi bi-check-lg"></i></div>
                        <div class="step-label">Informasi<br>Jabatan</div>
                    </div>
                    <div class="step-line completed"></div>
                    <div class="step active">
                        <div class="step-circle">3</div>
                        <div class="step-label">Verifikasi<br>OTP</div>
                    </div>
                </div>
                <section class="card">
                    <div class="otp-icon"><i class="bi bi-shield-check"></i></div>
                    <div class="card-kicker">Tahap 3 dari 3</div>
                    <h2 class="card-title">Verifikasi Kode OTP</h2>
                    <p class="card-description">Kode OTP 6 digit telah dikirim ke <span
                            class="masked-email">{{ $maskedEmail ?: 'alamat email Anda' }}</span>. Kode berlaku selama
                        10 menit.</p>
                    @if (session('success'))
                        <div class="alert alert-success"><i
                                class="bi bi-check-circle-fill"></i><span>{{ session('success') }}</span></div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-error"><i
                                class="bi bi-exclamation-triangle-fill"></i><span>{{ session('error') }}</span></div>
                    @endif
                    <div class="countdown-box"><i class="bi bi-clock"></i><span id="countdownText">Kirim ulang tersedia
                            dalam</span><span class="countdown" id="countdown">01:00</span></div>
                    <form id="verifyOtpForm" action="{{ route('register.step3') }}" method="POST" novalidate>@csrf
                        <div class="otp-inputs" id="otpInputs">
                            @for ($index = 0; $index < 6; $index++)
                                <input type="text" class="otp-input" inputmode="numeric" maxlength="1"
                                    autocomplete="{{ $index === 0 ? 'one-time-code' : 'off' }}"
                                    aria-label="Digit OTP ke-{{ $index + 1 }}">
                            @endfor
                        </div>
                        <input type="hidden" id="otp_code" name="otp_code" value="{{ old('otp_code') }}">
                        <div class="otp-message error" id="otpError"><i
                                class="bi bi-exclamation-circle-fill"></i>Masukkan kode OTP 6 digit.</div>
                        <div class="otp-message success" id="otpSuccess"><i class="bi bi-check-circle-fill"></i>Format
                            kode OTP sudah lengkap.</div>
                        @error('otp_code')
                            <div class="otp-message error show"><i
                                    class="bi bi-exclamation-circle-fill"></i>{{ $message }}</div>
                        @enderror
                        <div class="spam-information"><i class="bi bi-info-circle"></i>Periksa folder Spam atau Junk
                            jika email belum terlihat.</div>
                        <div class="form-actions"><a href="{{ route('register.step2') }}" class="back-button"><i
                                    class="bi bi-arrow-left"></i> Kembali</a><button type="submit"
                                class="verify-button" id="verifyButton" disabled><i class="bi bi-shield-check"></i>
                                Verifikasi dan Aktifkan Akun</button></div>
                    </form>
                    <form id="resendOtpForm" method="POST" action="{{ route('register.resend_otp') }}">@csrf<div
                            class="resend-wrapper"><span>Belum menerima kode?</span><button type="submit"
                                class="resend-button" id="resendButton" disabled>Kirim Ulang OTP</button></div>
                    </form>
                </section>
            </div>
        </main>
    </div>
    <script>
        const otpInputs = Array.from(document.querySelectorAll('.otp-input')),
            hiddenOtpInput = document.getElementById('otp_code'),
            verifyButton = document.getElementById('verifyButton'),
            otpError = document.getElementById('otpError'),
            otpSuccess = document.getElementById('otpSuccess'),
            verifyOtpForm = document.getElementById('verifyOtpForm'),
            resendOtpForm = document.getElementById('resendOtpForm'),
            resendButton = document.getElementById('resendButton'),
            countdownElement = document.getElementById('countdown'),
            countdownText = document.getElementById('countdownText');
        let remainingSeconds = 60,
            countdownInterval = null;

        function collectOtp() {
            return otpInputs.map(input => input.value).join('')
        }

        function updateOtpValue() {
            const otpCode = collectOtp();
            hiddenOtpInput.value = otpCode;
            otpInputs.forEach(input => {
                input.classList.toggle('filled', input.value !== '');
                input.classList.remove('error')
            });
            const complete = /^\d{6}$/.test(otpCode);
            verifyButton.disabled = !complete;
            otpError.classList.remove('show');
            otpSuccess.classList.toggle('show', complete)
        }
        otpInputs.forEach((input, index) => {
            input.addEventListener('input', event => {
                event.target.value = event.target.value.replace(/\D/g, '').slice(0, 1);
                if (event.target.value && index < otpInputs.length - 1) otpInputs[index + 1].focus();
                updateOtpValue()
            });
            input.addEventListener('keydown', event => {
                if (event.key === 'Backspace' && input.value === '' && index > 0) {
                    otpInputs[index - 1].focus();
                    otpInputs[index - 1].value = '';
                    updateOtpValue()
                }
                if (event.key === 'ArrowLeft' && index > 0) {
                    event.preventDefault();
                    otpInputs[index - 1].focus()
                }
                if (event.key === 'ArrowRight' && index < otpInputs.length - 1) {
                    event.preventDefault();
                    otpInputs[index + 1].focus()
                }
            });
            input.addEventListener('focus', () => input.select())
        });
        document.getElementById('otpInputs').addEventListener('paste', event => {
            event.preventDefault();
            const pasted = event.clipboardData.getData('text').replace(/\D/g, '').slice(0, 6);
            otpInputs.forEach((input, index) => input.value = pasted[index] || '');
            otpInputs[Math.min(Math.max(pasted.length - 1, 0), otpInputs.length - 1)].focus();
            updateOtpValue()
        });
        verifyOtpForm.addEventListener('submit', event => {
            const code = collectOtp();
            hiddenOtpInput.value = code;
            if (!/^\d{6}$/.test(code)) {
                event.preventDefault();
                otpError.classList.add('show');
                otpSuccess.classList.remove('show');
                otpInputs.forEach(input => input.classList.add('error'));
                return
            }
            verifyButton.disabled = true;
            verifyButton.innerHTML = '<i class="bi bi-arrow-repeat"></i> Memverifikasi...'
        });
        resendOtpForm.addEventListener('submit', event => {
            if (resendButton.disabled) {
                event.preventDefault();
                return
            }
            resendButton.disabled = true;
            resendButton.innerHTML = '<i class="bi bi-arrow-repeat"></i> Mengirim...'
        });

        function formatCountdown(seconds) {
            return `${Math.floor(seconds/60).toString().padStart(2,'0')}:${(seconds%60).toString().padStart(2,'0')}`
        }

        function startCountdown() {
            clearInterval(countdownInterval);
            resendButton.disabled = true;
            countdownElement.textContent = formatCountdown(remainingSeconds);
            countdownInterval = setInterval(() => {
                remainingSeconds--;
                countdownElement.textContent = formatCountdown(Math.max(remainingSeconds, 0));
                if (remainingSeconds <= 0) {
                    clearInterval(countdownInterval);
                    resendButton.disabled = false;
                    countdownText.textContent = 'Anda dapat';
                    countdownElement.textContent = ''
                }
            }, 1000)
        }

        function populateOldOtp() {
            const oldOtp = hiddenOtpInput.value.replace(/\D/g, '').slice(0, 6);
            oldOtp.split('').forEach((digit, index) => otpInputs[index].value = digit);
            updateOtpValue()
        }
        populateOldOtp();
        startCountdown();
        if (otpInputs[0] && !hiddenOtpInput.value) otpInputs[0].focus();
    </script>
</body>

</html>
