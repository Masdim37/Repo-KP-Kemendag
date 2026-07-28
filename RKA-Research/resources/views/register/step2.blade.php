<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Informasi Jabatan | Penelitian RKA-K/L</title>

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

            --success: #13b85c;
            --success-soft: #f1fff6;
            --success-border: #39d77f;

            --danger: #ef4355;

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
        input,
        select {
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

        /* ==================================================
           PANEL KIRI
        ================================================== */

        .left-panel {
            position: relative;
            width: 44.5%;
            min-height: 100vh;
            padding: 28px 20px 18px;
            color: #ffffff;
            background:
                radial-gradient(
                    circle at 45% 26%,
                    rgba(255, 255, 255, 0.08),
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
            background: rgba(255, 255, 255, 0.035);
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
            border: 1px solid rgba(255, 255, 255, 0.32);
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.12);
            font-size: 13px;
        }

        .brand-government {
            font-size: 8px;
            color: rgba(255, 255, 255, 0.78);
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
            border: 1px solid rgba(255, 255, 255, 0.18);
            border-radius: 20px;
            background: rgba(255, 255, 255, 0.10);
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
            color: rgba(255, 255, 255, 0.78);
            font-size: 8px;
            line-height: 1.6;
        }

        .illustration {
            width: 200px;
            margin: 25px auto 22px;
            opacity: 0.85;
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
            border: 1px solid rgba(255, 255, 255, 0.14);
            border-radius: 20px;
            background: rgba(255, 255, 255, 0.10);
            color: rgba(255, 255, 255, 0.88);
            font-size: 7px;
        }

        .flow-title {
            margin-bottom: 10px;
            color: rgba(255, 255, 255, 0.65);
            font-size: 7px;
            font-weight: 700;
            letter-spacing: 0.8px;
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
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 9px;
            background: rgba(255, 255, 255, 0.06);
        }

        .flow-item.active {
            border-color: rgba(255, 255, 255, 0.35);
            background: rgba(255, 255, 255, 0.18);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        }

        .flow-item.completed {
            background: rgba(255, 255, 255, 0.10);
        }

        .flow-number {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 20px;
            height: 20px;
            flex-shrink: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.12);
            color: rgba(255, 255, 255, 0.65);
            font-size: 8px;
            font-weight: 700;
        }

        .flow-item.active .flow-number {
            color: var(--primary-700);
            background: #ffffff;
        }

        .flow-item.completed .flow-number {
            color: #ffffff;
            background: var(--success);
        }

        .flow-name {
            font-size: 8px;
            font-weight: 700;
        }

        .flow-description {
            margin-top: 2px;
            color: rgba(255, 255, 255, 0.58);
            font-size: 7px;
        }

        .left-footer {
            margin-top: auto;
            padding-top: 17px;
            border-top: 1px solid rgba(255, 255, 255, 0.15);
            text-align: center;
            color: rgba(255, 255, 255, 0.48);
            font-size: 6.5px;
            line-height: 1.6;
        }

        /* ==================================================
           PANEL KANAN
        ================================================== */

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

        .step.active .step-circle {
            color: #ffffff;
            border-color: var(--primary-600);
            background: var(--primary-600);
            box-shadow: 0 0 0 5px rgba(36, 104, 242, 0.08);
        }

        .step.completed .step-circle {
            color: #ffffff;
            border-color: var(--success);
            background: var(--success);
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

        .step.completed .step-label {
            color: var(--success);
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

        /* Card */

        .register-card {
            width: 100%;
            max-width: 430px;
            margin-top: 20px;
            padding: 23px 22px 20px;
            border: 1px solid rgba(216, 225, 235, 0.75);
            border-radius: 15px;
            background: #ffffff;
            box-shadow:
                0 16px 45px rgba(46, 72, 105, 0.10),
                0 3px 8px rgba(46, 72, 105, 0.05);
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

        /* Form */

        .form-group {
            margin-bottom: 11px;
        }

        .form-label {
            display: block;
            margin-bottom: 6px;
            color: #27476f;
            font-size: 8px;
            font-weight: 700;
        }

        .required {
            color: var(--danger);
        }

        .select-wrapper {
            position: relative;
        }

        .select-icon {
            position: absolute;
            top: 50%;
            left: 12px;
            z-index: 2;
            transform: translateY(-50%);
            color: #8fa3ba;
            font-size: 11px;
            pointer-events: none;
        }

        .jabatan-select {
            width: 100%;
            height: 39px;
            padding: 0 38px 0 34px;
            border: 1.4px solid var(--border);
            border-radius: 10px;
            outline: none;
            color: #294b71;
            background: #ffffff;
            font-size: 9px;
            cursor: pointer;
            appearance: none;
            transition: 0.2s ease;
        }

        .jabatan-select:hover {
            border-color: #9db4cf;
        }

        .jabatan-select:focus,
        .jabatan-select.has-value {
            border-color: var(--success-border);
            box-shadow: 0 0 0 3px rgba(19, 184, 92, 0.07);
        }

        .select-arrow {
            position: absolute;
            top: 50%;
            right: 12px;
            transform: translateY(-50%);
            color: #8fa3ba;
            font-size: 10px;
            pointer-events: none;
        }

        .field-error {
            display: none;
            margin-top: 5px;
            color: var(--danger);
            font-size: 7px;
        }

        .field-error.show {
            display: block;
        }

        /* Detail jabatan */

        .detail-card {
            margin-top: 9px;
            padding: 12px;
            border: 1px solid #c8d9f7;
            border-radius: 10px;
            background: #f8fbff;
        }

        .detail-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            margin-bottom: 10px;
        }

        .detail-title {
            display: flex;
            align-items: center;
            gap: 5px;
            color: var(--primary-600);
            font-size: 8px;
            font-weight: 700;
        }

        .position-badge {
            display: none;
            padding: 3px 6px;
            border: 1px solid #bcd3ff;
            border-radius: 12px;
            color: var(--primary-600);
            background: #edf4ff;
            font-size: 6px;
            font-weight: 700;
        }

        .position-badge.show {
            display: inline-flex;
        }

        .readonly-group {
            margin-bottom: 9px;
        }

        .readonly-group:last-child {
            margin-bottom: 0;
        }

        .readonly-label {
            display: block;
            margin-bottom: 4px;
            color: #6f829a;
            font-size: 7px;
        }

        .readonly-wrapper {
            position: relative;
        }

        .readonly-icon {
            position: absolute;
            top: 50%;
            left: 11px;
            transform: translateY(-50%);
            color: #a4b2c3;
            font-size: 10px;
        }

        .readonly-input {
            width: 100%;
            height: 34px;
            padding: 0 30px;
            border: 1px solid #d9e2ec;
            border-radius: 9px;
            outline: none;
            color: #526d89;
            background: #f5f8fb;
            font-size: 8px;
            cursor: default;
        }

        .readonly-status {
            position: absolute;
            top: 50%;
            right: 11px;
            transform: translateY(-50%);
            width: 5px;
            height: 5px;
            border-radius: 50%;
            background: var(--primary-600);
        }

        .detail-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 9px;
        }

        /* Checkbox */

        .confirmation-box {
            position: relative;
            margin-top: 11px;
            padding: 12px 12px 12px 38px;
            border: 1px solid var(--border);
            border-radius: 10px;
            background: #ffffff;
            transition: 0.2s ease;
        }

        .confirmation-box.checked {
            border-color: var(--success-border);
            background: var(--success-soft);
        }

        .confirmation-checkbox {
            position: absolute;
            width: 1px;
            height: 1px;
            opacity: 0;
        }

        .custom-checkbox {
            position: absolute;
            top: 12px;
            left: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 16px;
            height: 16px;
            border: 1.5px solid #b6c3d2;
            border-radius: 5px;
            color: transparent;
            background: #ffffff;
            cursor: pointer;
            font-size: 10px;
            transition: 0.2s ease;
        }

        .confirmation-checkbox:focus + .custom-checkbox {
            box-shadow: 0 0 0 3px rgba(36, 104, 242, 0.12);
        }

        .confirmation-checkbox:checked + .custom-checkbox {
            color: #ffffff;
            border-color: var(--primary-600);
            background: var(--primary-600);
        }

        .confirmation-title {
            display: block;
            color: #25476d;
            font-size: 8px;
            font-weight: 700;
            cursor: pointer;
        }

        .confirmation-text {
            display: block;
            margin-top: 4px;
            color: #71849a;
            font-size: 7px;
            line-height: 1.55;
            cursor: pointer;
        }

        .confirmation-error {
            display: none;
            margin-top: 5px;
            color: var(--danger);
            font-size: 7px;
        }

        .confirmation-error.show {
            display: block;
        }

        /* Buttons */

        .form-actions {
            display: grid;
            grid-template-columns: 95px 1fr;
            gap: 10px;
            margin-top: 14px;
        }

        .back-button,
        .submit-button {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 39px;
            border-radius: 9px;
            font-size: 8px;
            font-weight: 700;
            transition: 0.2s ease;
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

        .submit-button {
            border: none;
            color: #ffffff;
            background: var(--primary-600);
            box-shadow: 0 7px 16px rgba(36, 104, 242, 0.23);
            cursor: pointer;
        }

        .submit-button i {
            margin-right: 6px;
        }

        .submit-button:hover:not(:disabled) {
            background: #1857d7;
            transform: translateY(-1px);
        }

        .submit-button:disabled {
            color: rgba(255, 255, 255, 0.8);
            background: #9db7ef;
            box-shadow: none;
            cursor: not-allowed;
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
            box-shadow: 0 2px 7px rgba(0, 0, 0, 0.25);
            font-size: 12px;
        }

        /* ==================================================
           RESPONSIVE
        ================================================== */

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

            .detail-grid {
                grid-template-columns: 1fr;
            }

            .form-actions {
                grid-template-columns: 1fr;
            }

            .submit-button {
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
    </style>
</head>

<body>

<div class="register-page">

    <!-- ==================================================
         PANEL KIRI
    ================================================== -->

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

                <div class="flow-item active">
                    <div class="flow-number">2</div>

                    <div>
                        <div class="flow-name">
                            Informasi Jabatan
                        </div>

                        <div class="flow-description">
                            Jabatan dan posisi di instansi
                        </div>
                    </div>
                </div>

                <div class="flow-item">
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

    <!-- ==================================================
         PANEL KANAN
    ================================================== -->

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

            <div class="step active">
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

            <h2 class="card-title">
                Informasi Jabatan
            </h2>

            <p class="card-description">
                Pilih jabatan sesuai data kepegawaian resmi Anda.
            </p>

            <form
                id="registerStepTwo"
                action="{{ route('register.step2') }}"
                method="POST"
                novalidate
            >

                @csrf

                <!-- JABATAN ID -->

                <div class="form-group">

                    <label for="jabatanID" class="form-label">
                        Jabatan
                        <span class="required">*</span>
                    </label>

                    <div class="select-wrapper">

                        <i class="bi bi-briefcase select-icon"></i>

                        <select
                            id="jabatanID"
                            name="jabatanID"
                            class="jabatan-select
                                @error('jabatanID') is-invalid @enderror"
                            required
                        >
                            <option value="">
                                Pilih nama jabatan
                            </option>

                            @isset($jabatans)

                                @forelse($jabatans as $jabatan)

                                    <option
                                        value="{{ $jabatan->jabatanID }}"
                                        data-name="{{ $jabatan->jabatan_name }}"
                                        data-type="{{ $jabatan->jabatan_type }}"
                                        data-level="{{ $jabatan->jabatan_level }}"
                                        data-eselon="{{ $jabatan->eselon }}"
                                        {{ old('jabatanID') == $jabatan->jabatanID ? 'selected' : '' }}
                                    >
                                        {{ $jabatan->jabatan_name }}
                                    </option>

                                @empty

                                    <option value="" disabled>
                                        Data jabatan belum tersedia
                                    </option>

                                @endforelse

                            @else

                                <option value="" disabled>
                                    Data jabatan belum dimuat
                                </option>

                            @endisset

                        </select>

                        <i class="bi bi-chevron-down select-arrow"></i>

                    </div>

                    <div
                        class="field-error"
                        id="jabatanError"
                    >
                        Silakan pilih jabatan.
                    </div>

                    @error('jabatanID')
                        <div class="field-error show">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

                <!-- DETAIL JABATAN -->

                <div class="detail-card">

                    <div class="detail-header">

                        <div class="detail-title">
                            <i class="bi bi-info-circle-fill"></i>
                            Detail Jabatan Terpilih
                        </div>

                        <span
                            class="position-badge"
                            id="positionBadge"
                        >
                            Jabatan
                        </span>

                    </div>

                    <div class="readonly-group">

                        <label
                            for="jabatanNameDisplay"
                            class="readonly-label"
                        >
                            Nama Jabatan
                        </label>

                        <div class="readonly-wrapper">

                            <i class="bi bi-briefcase readonly-icon"></i>

                            <input
                                type="text"
                                id="jabatanNameDisplay"
                                class="readonly-input"
                                placeholder="Belum ada jabatan dipilih"
                                readonly
                            >

                            <span class="readonly-status"></span>

                        </div>

                    </div>

                    <div class="detail-grid">

                        <div class="readonly-group">

                            <label
                                for="jabatanTypeDisplay"
                                class="readonly-label"
                            >
                                Tipe Jabatan
                            </label>

                            <div class="readonly-wrapper">

                                <i class="bi bi-diagram-3 readonly-icon"></i>

                                <input
                                    type="text"
                                    id="jabatanTypeDisplay"
                                    class="readonly-input"
                                    placeholder="-"
                                    readonly
                                >

                                <span class="readonly-status"></span>

                            </div>

                        </div>

                        <div class="readonly-group">

                            <label
                                for="jabatanLevelDisplay"
                                class="readonly-label"
                            >
                                Level Jabatan
                            </label>

                            <div class="readonly-wrapper">

                                <i class="bi bi-award readonly-icon"></i>

                                <input
                                    type="text"
                                    id="jabatanLevelDisplay"
                                    class="readonly-input"
                                    placeholder="-"
                                    readonly
                                >

                                <span class="readonly-status"></span>

                            </div>

                        </div>

                    </div>

                    <div class="readonly-group">

                        <label
                            for="eselonDisplay"
                            class="readonly-label"
                        >
                            Eselon
                        </label>

                        <div class="readonly-wrapper">

                            <i class="bi bi-building readonly-icon"></i>

                            <input
                                type="text"
                                id="eselonDisplay"
                                class="readonly-input"
                                placeholder="-"
                                readonly
                            >

                            <span class="readonly-status"></span>

                        </div>

                    </div>

                </div>

                <!-- PERNYATAAN DATA -->

                <div
                    class="confirmation-box"
                    id="confirmationBox"
                >

                    <input
                        type="checkbox"
                        id="data_confirmation"
                        name="data_confirmation"
                        value="1"
                        class="confirmation-checkbox"
                        {{ old('data_confirmation') ? 'checked' : '' }}
                        required
                    >

                    <label
                        for="data_confirmation"
                        class="custom-checkbox"
                    >
                        <i class="bi bi-check-lg"></i>
                    </label>

                    <label
                        for="data_confirmation"
                        class="confirmation-title"
                    >
                        Konfirmasi Kebenaran Data
                        <span class="required">*</span>
                    </label>

                    <label
                        for="data_confirmation"
                        class="confirmation-text"
                    >
                        Saya menyatakan bahwa seluruh data yang saya masukkan
                        adalah benar dan sesuai dengan data kepegawaian resmi
                        Kementerian Perdagangan RI.
                    </label>

                </div>

                <div
                    class="confirmation-error"
                    id="confirmationError"
                >
                    Silakan menyetujui pernyataan kebenaran data.
                </div>

                @error('data_confirmation')
                    <div class="confirmation-error show">
                        {{ $message }}
                    </div>
                @enderror

                <!-- ACTION -->

                <div class="form-actions">

                    <a
                        href="{{ route('register.step1') }}"
                        class="back-button"
                    >
                        <i class="bi bi-arrow-left"></i>
                        &nbsp;Kembali
                    </a>

                    <button
                        type="submit"
                        class="submit-button"
                        id="submitButton"
                        disabled
                    >
                        <i class="bi bi-send-fill"></i>
                        Daftar dan Kirim OTP
                    </button>

                </div>

            </form>

        </section>

    </main>

</div>

<a href="#" class="help-button" aria-label="Bantuan">
    ?
</a>

<script>
    const jabatanSelect =
        document.getElementById("jabatanID");

    const confirmationCheckbox =
        document.getElementById("data_confirmation");

    const confirmationBox =
        document.getElementById("confirmationBox");

    const submitButton =
        document.getElementById("submitButton");

    const jabatanError =
        document.getElementById("jabatanError");

    const confirmationError =
        document.getElementById("confirmationError");

    const jabatanNameDisplay =
        document.getElementById("jabatanNameDisplay");

    const jabatanTypeDisplay =
        document.getElementById("jabatanTypeDisplay");

    const jabatanLevelDisplay =
        document.getElementById("jabatanLevelDisplay");

    const eselonDisplay =
        document.getElementById("eselonDisplay");

    const positionBadge =
        document.getElementById("positionBadge");

    function formatDatabaseValue(value) {
        if (!value) {
            return "-";
        }

        return value
            .replaceAll("_", " ")
            .toLowerCase()
            .replace(/\b\w/g, character =>
                character.toUpperCase()
            );
    }

    function updatePositionDetails() {
        const selectedOption =
            jabatanSelect.options[jabatanSelect.selectedIndex];

        if (!jabatanSelect.value) {
            jabatanNameDisplay.value = "";
            jabatanTypeDisplay.value = "";
            jabatanLevelDisplay.value = "";
            eselonDisplay.value = "";

            positionBadge.textContent = "Jabatan";
            positionBadge.classList.remove("show");
            jabatanSelect.classList.remove("has-value");

            return;
        }

        const name =
            selectedOption.dataset.name || "";

        const type =
            selectedOption.dataset.type || "";

        const level =
            selectedOption.dataset.level || "";

        const eselon =
            selectedOption.dataset.eselon || "";

        jabatanNameDisplay.value =
            name;

        jabatanTypeDisplay.value =
            formatDatabaseValue(type);

        jabatanLevelDisplay.value =
            formatDatabaseValue(level);

        eselonDisplay.value =
            formatDatabaseValue(eselon);

        positionBadge.textContent =
            formatDatabaseValue(type);

        positionBadge.classList.add("show");
        jabatanSelect.classList.add("has-value");

        jabatanError.classList.remove("show");
    }

    function validateForm(showErrors = false) {
        const jabatanValid =
            jabatanSelect.value !== "";

        const confirmationValid =
            confirmationCheckbox.checked;

        if (showErrors) {
            jabatanError.classList.toggle(
                "show",
                !jabatanValid
            );

            confirmationError.classList.toggle(
                "show",
                !confirmationValid
            );
        } else {
            if (jabatanValid) {
                jabatanError.classList.remove("show");
            }

            if (confirmationValid) {
                confirmationError.classList.remove("show");
            }
        }

        confirmationBox.classList.toggle(
            "checked",
            confirmationValid
        );

        submitButton.disabled =
            !(jabatanValid && confirmationValid);

        return jabatanValid && confirmationValid;
    }

    jabatanSelect.addEventListener("change", function () {
        updatePositionDetails();
        validateForm();
    });

    confirmationCheckbox.addEventListener(
        "change",
        function () {
            validateForm();
        }
    );

    document
        .getElementById("registerStepTwo")
        .addEventListener("submit", function (event) {
            event.preventDefault();

            if (!validateForm(true)) {
                return;
            }

            submitButton.disabled = true;

            submitButton.innerHTML = `
                <i class="bi bi-arrow-repeat"></i>
                Mendaftarkan dan Mengirim OTP...
            `;

            /*
             * FRONTEND SAJA
             *
             * Data yang akan dikirim:
             * - jabatanID
             * - data_confirmation
             *
             * Detail jabatan tidak dikirim karena dibaca
             * dari tabel jabatan berdasarkan jabatanID.
             */

            setTimeout(() => {
                alert(
                    "Frontend saja: data jabatan valid dan OTP siap dikirim."
                );

                submitButton.disabled = false;

                submitButton.innerHTML = `
                    <i class="bi bi-send-fill"></i>
                    Daftar dan Kirim OTP
                `;
            }, 800);
        });

    updatePositionDetails();
    validateForm();
</script>

</body>
</html>