<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="color-scheme" content="light">
    <title>Informasi Jabatan | Sistem Informasi Penelitian RKA-K/L</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root {
            --primary: #0759b7;
            --primary-dark: #06498f;
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
        input,
        select {
            font: inherit
        }

        a {
            text-decoration: none;
            color: inherit
        }

        button,
        input,
        select {
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

        .info-panel {
            margin-top: 25px;
            padding: 16px;
            border: 1px solid rgba(255, 255, 255, .13);
            border-radius: 15px;
            background: rgba(255, 255, 255, .075)
        }

        .info-title {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 9px;
            font-weight: 850;
            letter-spacing: .7px;
            text-transform: uppercase;
            color: #dcecff
        }

        .info-row {
            display: flex;
            gap: 10px;
            margin-top: 12px
        }

        .info-icon {
            width: 30px;
            height: 30px;
            flex: 0 0 auto;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 9px;
            background: rgba(255, 255, 255, .11);
            font-size: 13px
        }

        .info-row strong {
            display: block;
            font-size: 10px
        }

        .info-row span {
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
            flex-direction: column;
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
            font-weight: 900
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
            font-size: 10px
        }

        .form-group {
            margin-bottom: 14px
        }

        .form-label {
            display: block;
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

        .select-wrapper {
            position: relative
        }

        .select-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            z-index: 2;
            color: #8da0b5;
            font-size: 13px;
            pointer-events: none
        }

        .jabatan-select {
            width: 100%;
            height: 40px;
            padding: 0 38px 0 36px;
            border: 1px solid #d5dee7;
            border-radius: 9px;
            color: #274766;
            background: #fff;
            font-size: 11px;
            appearance: none;
            cursor: pointer;
            transition: .18s
        }

        .jabatan-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(7, 89, 183, .09)
        }

        .jabatan-select.has-value {
            border-color: #8cb7e6
        }

        .select-arrow {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #8599ae;
            font-size: 11px;
            pointer-events: none
        }

        .field-error,
        .confirmation-error {
            display: none;
            margin-top: 5px;
            color: var(--danger);
            font-size: 8px
        }

        .field-error.show,
        .confirmation-error.show {
            display: block
        }

        .detail-card {
            margin-top: 10px;
            padding: 14px;
            border: 1px solid #d4e4f6;
            border-radius: 12px;
            background: #f8fbff
        }

        .detail-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            margin-bottom: 12px
        }

        .detail-title {
            display: flex;
            align-items: center;
            gap: 7px;
            color: var(--primary);
            font-size: 9px;
            font-weight: 850;
            text-transform: uppercase;
            letter-spacing: .3px
        }

        .position-badge {
            display: none;
            padding: 4px 8px;
            border: 1px solid #c6ddf6;
            border-radius: 999px;
            color: var(--primary);
            background: #edf5ff;
            font-size: 7px;
            font-weight: 800
        }

        .position-badge.show {
            display: inline-flex
        }

        .readonly-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px
        }

        .readonly-group {
            margin-bottom: 10px
        }

        .readonly-label {
            display: block;
            margin-bottom: 5px;
            color: #72869d;
            font-size: 8px
        }

        .readonly-wrapper {
            position: relative
        }

        .readonly-icon {
            position: absolute;
            left: 11px;
            top: 50%;
            transform: translateY(-50%);
            color: #9badc0;
            font-size: 11px
        }

        .readonly-input {
            width: 100%;
            height: 36px;
            padding: 0 30px;
            border: 1px solid #dce5ee;
            border-radius: 8px;
            color: #536d88;
            background: #f4f7fa;
            font-size: 9px
        }

        .readonly-status {
            position: absolute;
            right: 11px;
            top: 50%;
            transform: translateY(-50%);
            width: 5px;
            height: 5px;
            border-radius: 50%;
            background: #9eb1c4
        }

        .readonly-input:not(:placeholder-shown)+.readonly-status {
            background: var(--primary)
        }

        .confirmation-box {
            position: relative;
            margin-top: 14px;
            padding: 13px 13px 13px 41px;
            border: 1px solid var(--border);
            border-radius: 11px;
            background: #fff;
            transition: .18s
        }

        .confirmation-box.checked {
            border-color: #b8e3cb;
            background: var(--success-soft)
        }

        .confirmation-checkbox {
            position: absolute;
            width: 1px;
            height: 1px;
            opacity: 0
        }

        .custom-checkbox {
            position: absolute;
            left: 13px;
            top: 14px;
            width: 17px;
            height: 17px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1.5px solid #afbdcc;
            border-radius: 5px;
            color: transparent;
            background: #fff;
            cursor: pointer;
            font-size: 10px
        }

        .confirmation-checkbox:checked+.custom-checkbox {
            border-color: var(--primary);
            color: #fff;
            background: var(--primary)
        }

        .confirmation-title {
            display: block;
            color: #355575;
            font-size: 9px;
            font-weight: 850;
            cursor: pointer
        }

        .confirmation-text {
            display: block;
            margin-top: 4px;
            color: #71869d;
            font-size: 8px;
            line-height: 1.55;
            cursor: pointer
        }

        .form-actions {
            display: grid;
            grid-template-columns: 120px 1fr;
            gap: 10px;
            margin-top: 17px
        }

        .back-button,
        .submit-button {
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

        .back-button:hover {
            color: var(--primary);
            border-color: #a8c4e4;
            background: #f8fbff
        }

        .submit-button {
            border: 0;
            color: #fff;
            background: linear-gradient(135deg, var(--primary), var(--primary-bright));
            box-shadow: 0 8px 18px rgba(7, 89, 183, .19);
            cursor: pointer
        }

        .submit-button:disabled {
            background: #a9c4e5;
            box-shadow: none;
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
                padding: 22px 17px
            }

            .readonly-grid {
                grid-template-columns: 1fr
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
    <div class="auth-shell">
        <aside class="brand-panel">
            <div class="brand-top">
                <div class="brand-logo-wrap"><img src="{{ asset('images/logo-kemendag.png') }}"
                        alt="Logo Kementerian Perdagangan" class="brand-logo"></div>
                <div class="brand-ministry"><small>Kementerian Perdagangan RI</small><strong>Biro Perencanaan</strong>
                </div>
            </div>
            <div class="brand-content">
                <div class="system-chip"><i class="bi bi-person-badge"></i> Registrasi Pengguna</div>
                <h1 class="brand-title">Lengkapi informasi <span>jabatan pengguna</span></h1>
                <p class="brand-description">Pilih jabatan sesuai data kepegawaian. Informasi ini disimpan sebagai
                    bagian dari profil pribadi dan data kepegawaian pengguna pada sistem.</p>
                <div class="info-panel">
                    <div class="info-title"><i class="bi bi-info-circle"></i> Mengapa informasi jabatan diperlukan?
                    </div>
                    <div class="info-row">
                        <div class="info-icon"><i class="bi bi-person-vcard"></i></div>
                        <div><strong>Kelengkapan profil</strong><span>Jabatan melengkapi informasi pribadi dan data
                                kepegawaian pengguna pada sistem.</span></div>
                    </div>
                    <div class="info-row">
                        <div class="info-icon"><i class="bi bi-card-checklist"></i></div>
                        <div><strong>Informasi kepegawaian</strong><span>Nama, tipe, level, dan eselon jabatan dicatat
                                sebagai informasi profil pengguna.</span></div>
                    </div>
                </div>
                <div class="flow">
                    <div class="flow-step completed">
                        <div class="flow-no"><i class="bi bi-check-lg"></i></div><strong>Informasi
                            Akun</strong><small>Identitas telah dilengkapi.</small>
                    </div>
                    <div class="flow-step active">
                        <div class="flow-no">2</div><strong>Jabatan</strong><small>Lengkapi data jabatan Anda.</small>
                    </div>
                    <div class="flow-step">
                        <div class="flow-no">3</div><strong>Verifikasi OTP</strong><small>Konfirmasi email dan
                            aktivasi.</small>
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
                    <div class="step active">
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
                    <div class="card-kicker"><i class="bi bi-briefcase"></i> Tahap 2 dari 3</div>
                    <h2 class="card-title">Informasi Jabatan</h2>
                    <p class="card-description">Pilih jabatan sesuai data kepegawaian resmi. Detail jabatan akan
                        ditampilkan otomatis untuk membantu memastikan pilihan Anda benar.</p>
                    @if (session('error'))
                        <div class="server-message"><i class="bi bi-exclamation-triangle-fill"></i>
                            {{ session('error') }}</div>
                    @endif
                    <form id="registerStepTwo" action="{{ route('register.step2') }}" method="POST" novalidate>@csrf
                        <div class="form-group"><label for="jabatanID" class="form-label">Jabatan <span
                                    class="required">*</span></label>
                            <div class="select-wrapper"><i class="bi bi-briefcase select-icon"></i><select
                                    id="jabatanID" name="jabatanID"
                                    class="jabatan-select @error('jabatanID') is-invalid @enderror" required>
                                    <option value="">Pilih nama jabatan</option>
                                    @isset($jabatans)@forelse($jabatans as $jabatan)
                                        <option value="{{ $jabatan->jabatanID }}"
                                            data-name="{{ $jabatan->jabatan_name }}"
                                            data-type="{{ $jabatan->jabatan_type }}"
                                            data-level="{{ $jabatan->jabatan_level }}"
                                            data-eselon="{{ $jabatan->eselon }}"
                                            {{ old('jabatanID') == $jabatan->jabatanID ? 'selected' : '' }}>
                                            {{ $jabatan->jabatan_name }}</option>@empty<option value="" disabled>
                                                Data jabatan belum tersedia</option>
                                    @endforelse @else<option value="" disabled>Data jabatan
                                        belum dimuat</option>@endisset
                                </select>
                                <i class="bi bi-chevron-down select-arrow"></i>
                            </div>
                            <div class="field-error" id="jabatanError">Silakan pilih jabatan.</div>
                            @error('jabatanID')
                                <div class="field-error show">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="detail-card">
                            <div class="detail-header">
                                <div class="detail-title"><i class="bi bi-info-circle-fill"></i> Detail Jabatan Terpilih
                                </div><span class="position-badge" id="positionBadge">Jabatan</span>
                            </div>
                            <div class="readonly-group"><label for="jabatanNameDisplay" class="readonly-label">Nama
                                    Jabatan</label>
                                <div class="readonly-wrapper"><i class="bi bi-briefcase readonly-icon"></i><input
                                        type="text" id="jabatanNameDisplay" class="readonly-input"
                                        placeholder="Belum ada jabatan dipilih" readonly><span
                                        class="readonly-status"></span></div>
                            </div>
                            <div class="readonly-grid">
                                <div class="readonly-group"><label for="jabatanTypeDisplay"
                                        class="readonly-label">Tipe Jabatan</label>
                                    <div class="readonly-wrapper"><i class="bi bi-diagram-3 readonly-icon"></i><input
                                            type="text" id="jabatanTypeDisplay" class="readonly-input"
                                            placeholder="-" readonly><span class="readonly-status"></span></div>
                                </div>
                                <div class="readonly-group"><label for="jabatanLevelDisplay"
                                        class="readonly-label">Level Jabatan</label>
                                    <div class="readonly-wrapper"><i class="bi bi-award readonly-icon"></i><input
                                            type="text" id="jabatanLevelDisplay" class="readonly-input"
                                            placeholder="-" readonly><span class="readonly-status"></span></div>
                                </div>
                            </div>
                            <div class="readonly-group" style="margin-bottom:0"><label for="eselonDisplay"
                                    class="readonly-label">Eselon</label>
                                <div class="readonly-wrapper"><i class="bi bi-building readonly-icon"></i><input
                                        type="text" id="eselonDisplay" class="readonly-input" placeholder="-"
                                        readonly><span class="readonly-status"></span></div>
                            </div>
                        </div>
                        <div class="confirmation-box" id="confirmationBox"><input type="checkbox"
                                id="data_confirmation" name="data_confirmation" value="1"
                                class="confirmation-checkbox" {{ old('data_confirmation') ? 'checked' : '' }}
                                required><label for="data_confirmation" class="custom-checkbox"><i
                                    class="bi bi-check-lg"></i></label><label for="data_confirmation"
                                class="confirmation-title">Konfirmasi Kebenaran Data <span
                                    class="required">*</span></label><label for="data_confirmation"
                                class="confirmation-text">Saya menyatakan bahwa data jabatan yang dipilih benar dan
                                sesuai dengan data kepegawaian resmi Kementerian Perdagangan RI.</label></div>
                        <div class="confirmation-error" id="confirmationError">Silakan menyetujui pernyataan kebenaran
                            data.</div>
                        @error('data_confirmation')
                            <div class="confirmation-error show">{{ $message }}</div>
                        @enderror
                        <div class="form-actions"><a href="{{ route('register') }}" class="back-button"><i
                                    class="bi bi-arrow-left"></i> Kembali</a><button type="submit"
                                class="submit-button" id="submitButton" disabled><i class="bi bi-send-fill"></i>
                                Daftar dan Kirim OTP</button></div>
                    </form>
                </section>
            </div>
        </main>
    </div>
    <script>
        const jabatanSelect = document.getElementById('jabatanID'),
            confirmationCheckbox = document.getElementById('data_confirmation'),
            confirmationBox = document.getElementById('confirmationBox'),
            submitButton = document.getElementById('submitButton'),
            jabatanError = document.getElementById('jabatanError'),
            confirmationError = document.getElementById('confirmationError'),
            jabatanNameDisplay = document.getElementById('jabatanNameDisplay'),
            jabatanTypeDisplay = document.getElementById('jabatanTypeDisplay'),
            jabatanLevelDisplay = document.getElementById('jabatanLevelDisplay'),
            eselonDisplay = document.getElementById('eselonDisplay'),
            positionBadge = document.getElementById('positionBadge');

        function formatDatabaseValue(value) {
            if (!value) return '-';
            return value.replaceAll('_', ' ').toLowerCase().replace(/\b\w/g, c => c.toUpperCase())
        }

        function updatePositionDetails() {
            const selected = jabatanSelect.options[jabatanSelect.selectedIndex];
            if (!jabatanSelect.value) {
                jabatanNameDisplay.value = '';
                jabatanTypeDisplay.value = '';
                jabatanLevelDisplay.value = '';
                eselonDisplay.value = '';
                positionBadge.textContent = 'Jabatan';
                positionBadge.classList.remove('show');
                jabatanSelect.classList.remove('has-value');
                return
            }
            const name = selected.dataset.name || '',
                type = selected.dataset.type || '',
                level = selected.dataset.level || '',
                eselon = selected.dataset.eselon || '';
            jabatanNameDisplay.value = name;
            jabatanTypeDisplay.value = formatDatabaseValue(type);
            jabatanLevelDisplay.value = formatDatabaseValue(level);
            eselonDisplay.value = formatDatabaseValue(eselon);
            positionBadge.textContent = formatDatabaseValue(type);
            positionBadge.classList.add('show');
            jabatanSelect.classList.add('has-value');
            jabatanError.classList.remove('show')
        }

        function validateForm(showErrors = false) {
            const jabatanValid = jabatanSelect.value !== '',
                confirmationValid = confirmationCheckbox.checked;
            if (showErrors) {
                jabatanError.classList.toggle('show', !jabatanValid);
                confirmationError.classList.toggle('show', !confirmationValid)
            } else {
                if (jabatanValid) jabatanError.classList.remove('show');
                if (confirmationValid) confirmationError.classList.remove('show')
            }
            confirmationBox.classList.toggle('checked', confirmationValid);
            submitButton.disabled = !(jabatanValid && confirmationValid);
            return jabatanValid && confirmationValid
        }
        jabatanSelect.addEventListener('change', () => {
            updatePositionDetails();
            validateForm()
        });
        confirmationCheckbox.addEventListener('change', () => validateForm());
        document.getElementById('registerStepTwo').addEventListener('submit', event => {
            if (!validateForm(true)) {
                event.preventDefault();
                return
            }
            submitButton.disabled = true;
            submitButton.innerHTML = '<i class="bi bi-arrow-repeat"></i> Mendaftarkan dan Mengirim OTP...'
        });
        updatePositionDetails();
        validateForm();
    </script>
</body>

</html>
