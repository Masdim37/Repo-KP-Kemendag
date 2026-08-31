<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="color-scheme" content="light">
    <title>Registrasi Akun | Sistem Informasi Penelitian RKA-K/L</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
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
            --surface: #fff;
            --border: #dbe5ee;
            --success: #159957;
            --success-soft: #effaf4;
            --warning: #db9b17;
            --danger: #c83446;
            --danger-soft: #fff1f3;
            --shadow: 0 10px 28px rgba(27, 70, 112, .07);
            --shadow-strong: 0 22px 60px rgba(20, 60, 102, .12);
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
            overflow-x: hidden;
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
            font-weight: 850;
            line-height: 1.35
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

        .business-card {
            margin-top: 25px;
            padding: 16px;
            border: 1px solid rgba(255, 255, 255, .13);
            border-radius: 15px;
            background: rgba(255, 255, 255, .075);
            backdrop-filter: blur(7px)
        }

        .business-card-title {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 9px;
            font-weight: 850;
            letter-spacing: .7px;
            text-transform: uppercase;
            color: #dcecff
        }

        .business-list {
            display: grid;
            gap: 9px;
            margin-top: 12px
        }

        .business-item {
            display: flex;
            align-items: flex-start;
            gap: 10px
        }

        .business-icon {
            width: 29px;
            height: 29px;
            flex: 0 0 auto;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 9px;
            background: rgba(255, 255, 255, .11);
            color: #d8ecff;
            font-size: 13px
        }

        .business-item strong {
            display: block;
            font-size: 10px
        }

        .business-item span {
            display: block;
            margin-top: 2px;
            color: rgba(226, 241, 255, .66);
            font-size: 9px;
            line-height: 1.45
        }

        .flow {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 7px;
            margin-top: 18px
        }

        .flow-step {
            padding: 10px;
            border: 1px solid rgba(255, 255, 255, .10);
            border-radius: 11px;
            background: rgba(255, 255, 255, .055)
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
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 34px 34px;
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

        .step.active .step-label {
            color: var(--primary);
            font-weight: 800
        }

        .step-line {
            height: 1px;
            margin-top: 15px;
            background: #dbe5ee
        }

        .card {
            margin-top: 37px;
            padding: 25px 27px 24px;
            border: 1px solid var(--border);
            border-radius: 16px;
            background: #fff;
            box-shadow: var(--shadow-strong)
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
            margin-top: 12px;
            font-size: 21px;
            font-weight: 900;
            letter-spacing: -.3px
        }

        .card-description {
            margin-top: 6px;
            margin-bottom: 19px;
            color: var(--text-secondary);
            font-size: 11px;
            line-height: 1.6
        }

        .server-message {
            margin-bottom: 15px;
            padding: 11px 12px;
            border: 1px solid #f0b7bf;
            border-radius: 10px;
            color: #a82c3c;
            background: var(--danger-soft);
            font-size: 10px;
            line-height: 1.5
        }

        .server-message ul {
            margin: 6px 0 0 17px
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px 14px
        }

        .form-group {
            min-width: 0
        }

        .span-2 {
            grid-column: 1/-1
        }

        .form-label {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 6px;
            color: #355575;
            font-size: 9px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .35px
        }

        .required {
            color: var(--danger)
        }

        .input-wrapper {
            position: relative
        }

        .input-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #8da0b5;
            font-size: 13px;
            pointer-events: none
        }

        .form-input {
            width: 100%;
            height: 40px;
            padding: 0 37px;
            border: 1px solid #d5dee7;
            border-radius: 9px;
            color: #274766;
            background: #fff;
            font-size: 11px;
            transition: .18s
        }

        .form-input::placeholder {
            color: #a0aec0
        }

        .form-input:hover {
            border-color: #aebed0
        }

        .form-input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(7, 89, 183, .09)
        }

        .form-input.is-valid {
            border-color: #57b77f
        }

        .form-input.is-invalid {
            border-color: #df5968
        }

        .field-status {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            display: none;
            font-size: 12px
        }

        .form-input.is-valid~.field-status.success {
            display: block;
            color: var(--success)
        }

        .form-input.is-invalid~.field-status.error {
            display: block;
            color: var(--danger)
        }

        .password-field .form-input {
            padding-right: 68px
        }

        .password-field .field-status {
            right: 40px
        }

        .toggle-password {
            position: absolute;
            right: 11px;
            top: 50%;
            transform: translateY(-50%);
            border: 0;
            background: transparent;
            color: #7f92a8;
            cursor: pointer;
            font-size: 14px
        }

        .toggle-password:hover {
            color: var(--primary)
        }

        .error-text {
            display: none;
            margin-top: 4px;
            color: var(--danger);
            font-size: 8px;
            line-height: 1.4
        }

        .error-text.show {
            display: block
        }

        .helper-text {
            margin-top: 4px;
            color: #91a1b4;
            font-size: 8px
        }

        .strength-section {
            margin-top: 8px;
            padding: 10px 11px;
            border-radius: 10px;
            background: #f8fafc
        }

        .strength-bars {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 4px
        }

        .strength-bar {
            height: 3px;
            border-radius: 99px;
            background: #e5eaf0
        }

        .strength-header {
            display: flex;
            justify-content: space-between;
            margin-top: 6px;
            color: #8294aa;
            font-size: 8px
        }

        .strength-label {
            font-weight: 800
        }

        .rules-box {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 5px 12px;
            margin-top: 8px
        }

        .rule {
            display: flex;
            align-items: center;
            gap: 5px;
            color: #99a8ba;
            font-size: 7.5px
        }

        .rule-icon {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: #dce3ea
        }

        .rule.valid {
            color: var(--success)
        }

        .rule.valid .rule-icon {
            background: var(--success)
        }

        .password-summary {
            display: none;
            margin-top: 6px;
            color: var(--danger);
            font-size: 8px
        }

        .password-summary.show {
            display: block
        }

        .action-row {
            margin-top: 17px
        }

        .primary-button {
            width: 100%;
            min-height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            border: 0;
            border-radius: 9px;
            color: #fff;
            background: linear-gradient(135deg, var(--primary), var(--primary-bright));
            box-shadow: 0 8px 18px rgba(7, 89, 183, .19);
            font-size: 10px;
            font-weight: 850;
            cursor: pointer;
            transition: .18s
        }

        .primary-button:hover:not(:disabled) {
            transform: translateY(-1px);
            box-shadow: 0 10px 22px rgba(7, 89, 183, .23)
        }

        .primary-button:disabled {
            background: #a9c4e5;
            box-shadow: none;
            cursor: not-allowed
        }

        .login-link {
            margin-top: 13px;
            text-align: center;
            color: #7f91a6;
            font-size: 9px
        }

        .login-link a {
            color: var(--primary);
            font-weight: 850
        }

        .login-link a:hover {
            text-decoration: underline
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
                padding: 22px 17px
            }

            .form-grid {
                grid-template-columns: 1fr
            }

            .span-2 {
                grid-column: auto
            }

            .rules-box {
                grid-template-columns: 1fr
            }

            .step-label {
                width: 76px;
                font-size: 7px
            }

            .card-title {
                font-size: 19px
            }
        }
    </style>
</head>

<body>
    <div class="auth-shell">
        <aside class="brand-panel">
            <div class="brand-top">
                <div class="brand-logo-wrap"><img src="{{ asset('images/logo-kemendag.png') }}"
                        alt="Logo Kementerian Perdagangan" class="brand-logo"></div>
                <div class="brand-ministry"><small>Kementerian Perdagangan RI</small><strong>Biro Perencanaan</strong>
                </div>
            </div>
            <div class="brand-content">
                <div class="system-chip"><i class="bi bi-person-plus"></i> Registrasi Pengguna</div>
                <h1 class="brand-title">Registrasi Pengguna <span>Sistem Penelitian RKA-K/L</span></h1>
                <p class="brand-description">Registrasi digunakan untuk membentuk identitas pengguna, melengkapi
                    informasi pribadi dan kepegawaian, serta memverifikasi email sebelum akun dapat digunakan.</p>
                <div class="business-card">
                    <div class="business-card-title"><i class="bi bi-diagram-3"></i> Tahapan registrasi</div>
                    <div class="flow">
                        <div class="flow-step active">
                            <div class="flow-no">1</div><strong>Informasi Akun</strong><small>Identitas dan kredensial
                                login.</small>
                        </div>
                        <div class="flow-step">
                            <div class="flow-no">2</div><strong>Jabatan</strong><small>Informasi jabatan
                                pengguna.</small>
                        </div>
                        <div class="flow-step">
                            <div class="flow-no">3</div><strong>Verifikasi OTP</strong><small>Konfirmasi email dan
                                aktivasi.</small>
                        </div>
                    </div>
                </div>
                <div class="business-list" style="margin-top:18px">
                    <div class="business-item">
                        <div class="business-icon"><i class="bi bi-shield-check"></i></div>
                        <div><strong>Data terverifikasi</strong><span>NIP dan email menjadi bagian dari identitas
                                pengguna aplikasi.</span></div>
                    </div>
                    <div class="business-item">
                        <div class="business-icon"><i class="bi bi-person-vcard"></i></div>
                        <div><strong>Profil kepegawaian</strong><span>Informasi jabatan dilengkapi sebagai bagian dari
                                data pribadi dan profil pengguna pada sistem.</span></div>
                    </div>
                </div>
            </div>
            <div class="brand-footer">© {{ date('Y') }} Biro Perencanaan — Kementerian Perdagangan Republik
                Indonesia</div>
        </aside>

        <main class="form-panel">
            <div class="form-wrap">
                <div class="stepper" aria-label="Tahapan registrasi">
                    <div class="step active">
                        <div class="step-circle">1</div>
                        <div class="step-label">Informasi<br>Akun</div>
                    </div>
                    <div class="step-line"></div>
                    <div class="step">
                        <div class="step-circle">2</div>
                        <div class="step-label">Informasi<br>Jabatan</div>
                    </div>
                    <div class="step-line"></div>
                    <div class="step">
                        <div class="step-circle">3</div>
                        <div class="step-label">Verifikasi<br>OTP</div>
                    </div>
                </div>

                <section class="card">
                    <div class="card-kicker"><i class="bi bi-person-vcard"></i> Tahap 1 dari 3</div>
                    <h2 class="card-title">Informasi Akun</h2>
                    <p class="card-description">Lengkapi identitas pengguna dan buat kredensial yang akan digunakan
                        untuk masuk ke aplikasi.</p>

                    @if (session('error'))
                        <div class="server-message"><i class="bi bi-exclamation-triangle-fill"></i>
                            {{ session('error') }}</div>
                    @endif
                    @if ($errors->any())
                        <div class="server-message"><strong>Data belum dapat diproses:</strong>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form id="registerStepOne" action="{{ route('register.step1') }}" method="POST" novalidate>
                        @csrf
                        <div class="form-grid">
                            <div class="form-group span-2">
                                <label class="form-label" for="name"><span>Nama Lengkap <span
                                            class="required">*</span></span></label>
                                <div class="input-wrapper"><i class="bi bi-person input-icon"></i><input type="text"
                                        id="name" name="name" value="{{ old('name') }}"
                                        class="form-input @error('name') is-invalid @enderror"
                                        placeholder="Masukkan nama lengkap" maxlength="255" autocomplete="name"
                                        required><i class="bi bi-check-circle-fill field-status success"></i><i
                                        class="bi bi-exclamation-circle-fill field-status error"></i></div>
                                <div class="error-text" id="nameError">Nama lengkap minimal 3 karakter.</div>
                                @error('name')
                                    <div class="error-text show">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="nip"><span>NIP <span
                                            class="required">*</span></span></label>
                                <div class="input-wrapper"><i class="bi bi-hash input-icon"></i><input type="text"
                                        id="nip" name="nip" value="{{ old('nip') }}"
                                        class="form-input @error('nip') is-invalid @enderror" placeholder="18 digit NIP"
                                        maxlength="18" inputmode="numeric" autocomplete="off" required><i
                                        class="bi bi-check-circle-fill field-status success"></i><i
                                        class="bi bi-exclamation-circle-fill field-status error"></i></div>
                                <div class="error-text" id="nipError">NIP wajib terdiri dari 18 digit angka.</div>
                                @error('nip')
                                    <div class="error-text show">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="email"><span>Email Instansi <span
                                            class="required">*</span></span></label>
                                <div class="input-wrapper"><i class="bi bi-envelope input-icon"></i><input
                                        type="email" id="email" name="email" value="{{ old('email') }}"
                                        class="form-input @error('email') is-invalid @enderror"
                                        placeholder="nama@kemendag.go.id" maxlength="150" autocomplete="email"
                                        required><i class="bi bi-check-circle-fill field-status success"></i><i
                                        class="bi bi-exclamation-circle-fill field-status error"></i></div>
                                <div class="helper-text">Kode OTP registrasi akan dikirim ke email ini.</div>
                                <div class="error-text" id="emailError">Masukkan alamat email yang valid.</div>
                                @error('email')
                                    <div class="error-text show">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group span-2">
                                <label class="form-label" for="username"><span>Username <span
                                            class="required">*</span></span></label>
                                <div class="input-wrapper"><i class="bi bi-at input-icon"></i><input type="text"
                                        id="username" name="username" value="{{ old('username') }}"
                                        class="form-input @error('username') is-invalid @enderror"
                                        placeholder="Buat username" maxlength="100" autocomplete="username"
                                        required><i class="bi bi-check-circle-fill field-status success"></i><i
                                        class="bi bi-exclamation-circle-fill field-status error"></i></div>
                                <div class="helper-text">Minimal 4 karakter; gunakan huruf, angka, strip, atau
                                    underscore.</div>
                                <div class="error-text" id="usernameError">Username hanya boleh berisi huruf, angka,
                                    strip, dan underscore.</div>
                                @error('username')
                                    <div class="error-text show">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group span-2">
                                <label class="form-label" for="password"><span>Kata Sandi <span
                                            class="required">*</span></span></label>
                                <div class="input-wrapper password-field"><i class="bi bi-lock input-icon"></i><input
                                        type="password" id="password" name="password"
                                        class="form-input @error('password') is-invalid @enderror"
                                        placeholder="Buat kata sandi" maxlength="255" autocomplete="new-password"
                                        required><i class="bi bi-check-circle-fill field-status success"></i><i
                                        class="bi bi-exclamation-circle-fill field-status error"></i><button
                                        type="button" class="toggle-password" data-target="password"
                                        aria-label="Tampilkan kata sandi"><i class="bi bi-eye"></i></button></div>
                                <div class="strength-section">
                                    <div class="strength-bars"><span class="strength-bar"></span><span
                                            class="strength-bar"></span><span class="strength-bar"></span><span
                                            class="strength-bar"></span></div>
                                    <div class="strength-header"><span id="rulesCounter">0/5 kriteria
                                            terpenuhi</span><span class="strength-label" id="strengthLabel">Sangat
                                            Lemah</span></div>
                                    <div class="rules-box">
                                        <div class="rule" id="ruleLength"><span class="rule-icon"></span>Minimal 8
                                            karakter</div>
                                        <div class="rule" id="ruleUppercase"><span class="rule-icon"></span>Huruf
                                            kapital (A–Z)</div>
                                        <div class="rule" id="ruleLowercase"><span class="rule-icon"></span>Huruf
                                            kecil (a–z)</div>
                                        <div class="rule" id="ruleNumber"><span class="rule-icon"></span>Angka
                                            (0–9)
                                        </div>
                                        <div class="rule" id="ruleSpecial"><span class="rule-icon"></span>Karakter
                                            khusus (!@#$...)</div>
                                    </div>
                                </div>
                                <div class="password-summary" id="passwordError">Penuhi seluruh kriteria kata sandi.
                                </div>
                                @error('password')
                                    <div class="error-text show">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group span-2">
                                <label class="form-label" for="password_confirmation"><span>Konfirmasi Kata Sandi
                                        <span class="required">*</span></span></label>
                                <div class="input-wrapper password-field"><i
                                        class="bi bi-lock-check input-icon"></i><input type="password"
                                        id="password_confirmation" name="password_confirmation" class="form-input"
                                        placeholder="Ulangi kata sandi" maxlength="255" autocomplete="new-password"
                                        required><i class="bi bi-check-circle-fill field-status success"></i><i
                                        class="bi bi-exclamation-circle-fill field-status error"></i><button
                                        type="button" class="toggle-password" data-target="password_confirmation"
                                        aria-label="Tampilkan kata sandi"><i class="bi bi-eye"></i></button></div>
                                <div class="error-text" id="confirmationError">Konfirmasi kata sandi harus sama.</div>
                            </div>
                        </div>
                        <div class="action-row"><button type="submit" class="primary-button" id="nextButton"
                                disabled><span>Lanjut ke Informasi Jabatan</span><i
                                    class="bi bi-arrow-right"></i></button></div>
                    </form>
                    <div class="login-link">Sudah memiliki akun? <a href="{{ route('login') }}">Login</a>
                    </div>
                </section>
            </div>
        </main>
    </div>
    <script>
        const form = document.getElementById('registerStepOne');
        const fields = {
            name: document.getElementById('name'),
            nip: document.getElementById('nip'),
            email: document.getElementById('email'),
            username: document.getElementById('username'),
            password: document.getElementById('password'),
            confirmation: document.getElementById('password_confirmation')
        };
        const nextButton = document.getElementById('nextButton');
        const strengthBars = Array.from(document.querySelectorAll('.strength-bar'));
        const strengthLabel = document.getElementById('strengthLabel');
        const rulesCounter = document.getElementById('rulesCounter');
        const passwordRules = [{
            element: document.getElementById('ruleLength'),
            validate: v => v.length >= 8
        }, {
            element: document.getElementById('ruleUppercase'),
            validate: v => /[A-Z]/.test(v)
        }, {
            element: document.getElementById('ruleLowercase'),
            validate: v => /[a-z]/.test(v)
        }, {
            element: document.getElementById('ruleNumber'),
            validate: v => /[0-9]/.test(v)
        }, {
            element: document.getElementById('ruleSpecial'),
            validate: v => /[!@#$%^&*()_\-+=\[\]{};:'",.<>/?\\|`~]/.test(v)
    }];

    function setValidation(input, errorElement, isValid, showError = true) {
        input.classList.remove('is-valid', 'is-invalid');
        if (!input.value) {
            errorElement.classList.remove('show');
            return;
        }
        if (isValid) {
            input.classList.add('is-valid');
            errorElement.classList.remove('show')
        } else {
            input.classList.add('is-invalid');
            if (showError) errorElement.classList.add('show')
        }
    }

    function validateName() {
        const valid = fields.name.value.trim().length >= 3;
        setValidation(fields.name, document.getElementById('nameError'), valid);
        return valid
    }

    function validateNip() {
        fields.nip.value = fields.nip.value.replace(/\D/g, '').slice(0, 18);
        const valid = /^\d{18}$/.test(fields.nip.value);
        setValidation(fields.nip, document.getElementById('nipError'), valid);
        return valid
    }

    function validateEmail() {
        const valid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(fields.email.value.trim());
        setValidation(fields.email, document.getElementById('emailError'), valid);
        return valid
    }

    function validateUsername() {
        fields.username.value = fields.username.value.replace(/\s/g, '');
        const valid = /^[A-Za-z0-9_-]{4,100}$/.test(fields.username.value);
        setValidation(fields.username, document.getElementById('usernameError'), valid);
        return valid
    }

    function validatePassword() {
        const value = fields.password.value;
        let score = 0;
        passwordRules.forEach(rule => {
            const valid = rule.validate(value);
            rule.element.classList.toggle('valid', valid);
            if (valid) score++
        });
        rulesCounter.textContent = `${score}/5 kriteria terpenuhi`;
            let label = 'Sangat Lemah',
                color = '#c83446',
                activeBars = value ? 1 : 0;
            if (score === 2) {
                label = 'Lemah';
                color = '#d77b28';
                activeBars = 1
            } else if (score === 3) {
                label = 'Sedang';
                color = '#db9b17';
                activeBars = 2
            } else if (score === 4) {
                label = 'Kuat';
                color = '#4ca86d';
                activeBars = 3
            } else if (score === 5) {
                label = 'Sangat Kuat';
                color = '#159957';
                activeBars = 4
            }
            strengthLabel.textContent = label;
            strengthLabel.style.color = color;
            strengthBars.forEach((bar, index) => bar.style.background = index < activeBars ? color : '#e5eaf0');
            const valid = score === 5;
            document.getElementById('passwordError').classList.toggle('show', value.length > 0 && !valid);
            return valid
        }

        function validateConfirmation() {
            const valid = fields.confirmation.value.length > 0 && fields.confirmation.value === fields.password.value;
            setValidation(fields.confirmation, document.getElementById('confirmationError'), valid);
            return valid
        }

        function validateForm() {
            const valid = validateName() && validateNip() && validateEmail() && validateUsername() && validatePassword() &&
                validateConfirmation();
            nextButton.disabled = !valid;
            return valid
        }
        Object.values(fields).forEach(field => field.addEventListener('input', validateForm));
        document.querySelectorAll('.toggle-password').forEach(button => button.addEventListener('click', function() {
            const target = document.getElementById(this.dataset.target);
            const icon = this.querySelector('i');
            const hidden = target.type === 'password';
            target.type = hidden ? 'text' : 'password';
            icon.className = hidden ? 'bi bi-eye-slash' : 'bi bi-eye';
            this.setAttribute('aria-label', hidden ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi')
        }));
        form.addEventListener('submit', event => {
            if (!validateForm()) {
                event.preventDefault();
                return;
            }
            nextButton.disabled = true;
            nextButton.innerHTML = '<i class="bi bi-arrow-repeat"></i> Memproses...'
        });
        validateForm();
    </script>
</body>

</html>
