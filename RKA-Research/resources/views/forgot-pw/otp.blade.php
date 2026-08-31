<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="color-scheme" content="light">
    <title>Verifikasi Kode OTP | Sistem Informasi Penelitian RKA-K/L</title>
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
            --success: #159957;
            --success-soft: #effaf4;
            --success-border: #c7ead7;
            --warning: #b7770b;
            --warning-soft: #fff8e8;
            --warning-border: #f0ddb0;
            --danger: #b42f40;
            --danger-soft: #fff0f2;
            --danger-border: #efc4cb;
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
            color: inherit;
            text-decoration: none
        }

        button,
        input {
            outline: none
        }

        .recovery-shell {
            min-height: 100vh;
            display: grid;
            grid-template-columns: minmax(520px, 1.05fr) minmax(440px, .95fr)
        }

        .brand-panel {
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            padding: 42px 52px 36px;
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
            width: 520px;
            height: 520px;
            top: -245px;
            right: -205px;
            border: 1px solid rgba(255, 255, 255, .09);
            box-shadow: 0 0 0 65px rgba(255, 255, 255, .025), 0 0 0 130px rgba(255, 255, 255, .018)
        }

        .brand-panel:after {
            width: 360px;
            height: 360px;
            bottom: -225px;
            left: -165px;
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
            gap: 14px
        }

        .brand-logo-wrap {
            width: 58px;
            height: 58px;
            flex: 0 0 auto;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 6px;
            border: 1px solid rgba(255, 255, 255, .22);
            border-radius: 15px;
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
            margin-bottom: 4px;
            color: rgba(232, 243, 255, .74);
            font-size: 8px;
            font-weight: 800;
            letter-spacing: 1.25px;
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
            max-width: 720px;
            margin: auto 0;
            padding: 50px 0 42px
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
            backdrop-filter: blur(8px);
            font-size: 8px;
            font-weight: 850;
            letter-spacing: .85px;
            text-transform: uppercase
        }

        .brand-title {
            max-width: 650px;
            margin-top: 18px;
            color: #fff;
            font-size: clamp(30px, 3.4vw, 46px);
            font-weight: 900;
            line-height: 1.08;
            letter-spacing: -1.3px
        }

        .brand-title span {
            color: #a9d4ff
        }

        .brand-description {
            max-width: 650px;
            margin-top: 17px;
            color: rgba(230, 241, 255, .78);
            font-size: 10.5px;
            line-height: 1.8
        }

        .recovery-info {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 11px;
            margin-top: 27px
        }

        .info-card {
            display: flex;
            align-items: flex-start;
            gap: 11px;
            min-height: 88px;
            padding: 14px;
            border: 1px solid rgba(255, 255, 255, .13);
            border-radius: 14px;
            background: rgba(255, 255, 255, .075);
            backdrop-filter: blur(8px)
        }

        .info-icon {
            width: 36px;
            height: 36px;
            flex: 0 0 auto;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid rgba(255, 255, 255, .18);
            border-radius: 11px;
            background: rgba(255, 255, 255, .105);
            font-size: 14px
        }

        .info-copy strong {
            display: block;
            font-size: 9.5px;
            font-weight: 850;
            line-height: 1.4
        }

        .info-copy p {
            margin-top: 4px;
            color: rgba(226, 239, 255, .7);
            font-size: 7.8px;
            line-height: 1.55
        }

        .workflow {
            margin-top: 24px;
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, .13)
        }

        .workflow-label {
            color: rgba(229, 241, 255, .63);
            font-size: 7.5px;
            font-weight: 850;
            letter-spacing: 1px;
            text-transform: uppercase
        }

        .workflow-track {
            position: relative;
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 8px;
            margin-top: 14px
        }

        .workflow-track:before {
            content: "";
            position: absolute;
            top: 15px;
            left: 12.5%;
            right: 12.5%;
            height: 1px;
            background: rgba(255, 255, 255, .16)
        }

        .workflow-step {
            position: relative;
            z-index: 1;
            min-width: 0;
            text-align: center
        }

        .workflow-number {
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
            border: 1px solid rgba(255, 255, 255, .21);
            border-radius: 50%;
            color: #ddecff;
            background: #0754a8;
            box-shadow: 0 0 0 5px rgba(7, 78, 159, .45);
            font-size: 8px;
            font-weight: 900
        }

        .workflow-step.is-active .workflow-number {
            color: #0759b7;
            background: #fff
        }

        .workflow-step.is-done .workflow-number {
            color: #fff;
            background: #159957
        }

        .workflow-step strong {
            display: block;
            margin-top: 8px;
            color: #fff;
            font-size: 8px;
            font-weight: 800;
            line-height: 1.35
        }

        .workflow-step span {
            display: block;
            margin-top: 3px;
            color: rgba(220, 235, 252, .56);
            font-size: 7px;
            line-height: 1.4
        }

        .brand-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 18px;
            padding-top: 18px;
            border-top: 1px solid rgba(255, 255, 255, .11);
            color: rgba(220, 235, 252, .58);
            font-size: 7.5px;
            line-height: 1.5
        }

        .brand-footer strong {
            color: rgba(239, 247, 255, .86)
        }

        .auth-panel {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background: radial-gradient(circle at 100% 0%, rgba(7, 89, 183, .05), transparent 34%), var(--background)
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
            background: rgba(255, 255, 255, .86);
            backdrop-filter: blur(12px);
            font-size: 8px;
            font-weight: 700
        }

        .auth-header i {
            color: var(--primary);
            font-size: 10px
        }

        .auth-content {
            width: 100%;
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 34px
        }

        .recovery-card {
            width: 100%;
            max-width: 460px;
            overflow: hidden;
            border: 1px solid var(--border);
            border-radius: 16px;
            background: var(--surface);
            box-shadow: var(--shadow-strong)
        }

        .card-head {
            padding: 24px 27px 18px;
            border-bottom: 1px solid #e8eef4
        }

        .card-heading {
            display: flex;
            align-items: flex-start;
            gap: 12px
        }

        .card-icon {
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
            font-size: 16px
        }

        .card-eyebrow {
            color: var(--text-muted);
            font-size: 7.5px;
            font-weight: 850;
            letter-spacing: .8px;
            text-transform: uppercase
        }

        .card-title {
            margin-top: 3px;
            color: var(--text-primary);
            font-size: 18px;
            font-weight: 900;
            line-height: 1.3
        }

        .card-description {
            margin-top: 10px;
            color: var(--text-secondary);
            font-size: 9px;
            line-height: 1.7
        }

        .card-body {
            padding: 21px 27px 27px
        }

        .stepper {
            display: grid;
            grid-template-columns: 28px 1fr 28px 1fr 28px 1fr 28px;
            align-items: start;
            margin-bottom: 28px;
            text-align: center
        }

        .step {
            position: relative;
            z-index: 2
        }

        .step-circle {
            width: 28px;
            height: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
            border: 1px solid #dbe5ee;
            border-radius: 50%;
            color: #8da0b4;
            background: #f8fafc;
            font-size: 10px;
            font-weight: 850
        }

        .step-label {
            position: absolute;
            left: 50%;
            top: 34px;
            width: 76px;
            transform: translateX(-50%);
            color: #98a8b9;
            font-size: 7.2px;
            font-weight: 700;
            white-space: nowrap
        }

        .step.current .step-circle {
            border-color: var(--primary);
            color: #fff;
            background: var(--primary);
            box-shadow: 0 0 0 4px rgba(7, 89, 183, .09)
        }

        .step.current .step-label {
            color: var(--primary);
            font-weight: 850
        }

        .step.completed .step-circle {
            border-color: var(--success);
            color: #fff;
            background: var(--success)
        }

        .step.completed .step-label {
            color: var(--success)
        }

        .step-line {
            height: 1px;
            margin-top: 14px;
            background: #dfe7ef
        }

        .step-line.completed {
            background: #8ed0ad
        }

        .server-message {
            display: flex;
            align-items: flex-start;
            gap: 8px;
            margin-bottom: 15px;
            padding: 10px 11px;
            border: 1px solid;
            border-radius: 9px;
            font-size: 8.5px;
            font-weight: 650;
            line-height: 1.55
        }

        .server-message i {
            flex: 0 0 auto;
            margin-top: 1px;
            font-size: 11px
        }

        .server-message-error {
            border-color: var(--danger-border);
            color: var(--danger);
            background: var(--danger-soft)
        }

        .server-message-success {
            border-color: var(--success-border);
            color: #147348;
            background: var(--success-soft)
        }

        .server-message-info {
            border-color: var(--primary-border);
            color: #476d93;
            background: var(--primary-soft)
        }

        .form-group+.form-group {
            margin-top: 16px
        }

        .form-label-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 7px
        }

        .form-label {
            color: #334b68;
            font-size: 9px;
            font-weight: 800;
            letter-spacing: .18px
        }

        .form-required {
            color: var(--text-muted);
            font-size: 7px
        }

        .input-shell {
            position: relative
        }

        .input-icon {
            position: absolute;
            top: 50%;
            left: 13px;
            transform: translateY(-50%);
            color: #8a9db1;
            font-size: 12px;
            pointer-events: none
        }

        .form-control {
            width: 100%;
            height: 42px;
            padding: 0 42px 0 36px;
            border: 1px solid #d5dee7;
            border-radius: 9px;
            color: #233b58;
            background: #fff;
            font-size: 10px;
            transition: border-color .2s, box-shadow .2s, background .2s
        }

        .form-control::placeholder {
            color: #9aaabd
        }

        .form-control:hover {
            border-color: #becdda
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(7, 89, 183, .09)
        }

        .form-control.is-valid {
            border-color: #55aa7d
        }

        .form-control.is-invalid {
            border-color: #d75a68;
            background: #fffdfd
        }

        .input-status {
            position: absolute;
            top: 50%;
            right: 13px;
            display: none;
            transform: translateY(-50%);
            color: var(--success);
            font-size: 12px
        }

        .field-hint {
            margin-top: 6px;
            color: var(--text-muted);
            font-size: 7.7px;
            line-height: 1.5
        }

        .field-error {
            display: none;
            margin-top: 6px;
            color: var(--danger);
            font-size: 8px;
            line-height: 1.45
        }

        .primary-button {
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
            color: #fff;
            background: linear-gradient(135deg, var(--primary), var(--primary-bright));
            box-shadow: 0 8px 18px rgba(7, 89, 183, .17);
            font-size: 10px;
            font-weight: 850;
            cursor: pointer;
            transition: transform .2s, box-shadow .2s, filter .2s
        }

        .primary-button:hover:not(:disabled) {
            transform: translateY(-1px);
            box-shadow: 0 11px 22px rgba(7, 89, 183, .21)
        }

        .primary-button:disabled {
            cursor: not-allowed;
            opacity: .55;
            box-shadow: none
        }

        .secondary-link {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            margin-top: 15px;
            color: var(--primary);
            font-size: 8.5px;
            font-weight: 800
        }

        .secondary-link:hover {
            text-decoration: underline;
            color: var(--primary-dark)
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
            line-height: 1.5
        }

        .security-note {
            display: flex;
            align-items: flex-start;
            gap: 9px;
            margin-top: 17px;
            padding: 10px 11px;
            border: 1px solid var(--primary-border);
            border-radius: 9px;
            color: #4f6d8d;
            background: var(--primary-soft);
            font-size: 8px;
            line-height: 1.55
        }

        .security-note i {
            flex: 0 0 auto;
            margin-top: 1px;
            color: var(--primary);
            font-size: 12px
        }

        .security-note strong {
            color: var(--text-primary)
        }

        .otp-grid {
            display: grid;
            grid-template-columns: repeat(6, 1fr);
            gap: 8px
        }

        .otp-input {
            width: 100%;
            height: 48px;
            border: 1px solid #d5dee7;
            border-radius: 9px;
            color: var(--text-primary);
            background: #fff;
            text-align: center;
            font-size: 16px;
            font-weight: 850;
            transition: .2s
        }

        .otp-input:hover {
            border-color: #becdda
        }

        .otp-input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(7, 89, 183, .09)
        }

        .otp-input.filled {
            border-color: #84afd9;
            background: #f8fbff
        }

        .resend-row {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: center;
            gap: 4px;
            margin-top: 13px;
            color: var(--text-secondary);
            font-size: 8px;
            text-align: center
        }

        .resend-action {
            color: var(--text-muted)
        }

        .countdown {
            color: var(--primary);
            font-weight: 850
        }

        .resend-button {
            display: none;
            padding: 0;
            border: 0;
            color: var(--primary);
            background: transparent;
            font-size: 8px;
            font-weight: 850;
            cursor: pointer
        }

        .resend-button:hover {
            text-decoration: underline
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
            cursor: pointer
        }

        .toggle-password:hover,
        .toggle-password:focus-visible {
            color: var(--primary);
            background: var(--primary-soft)
        }

        .strength-section {
            margin-top: 10px;
            padding: 11px 12px;
            border: 1px solid #e2e9f0;
            border-radius: 10px;
            background: #fafcff
        }

        .strength-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px
        }

        .strength-title {
            color: #5f7590;
            font-size: 7.8px;
            font-weight: 800
        }

        .strength-label {
            font-size: 7.8px;
            font-weight: 850
        }

        .strength-bars {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 5px;
            margin-top: 7px
        }

        .strength-bar {
            height: 3px;
            border-radius: 99px;
            background: #e6ebf1
        }

        .criteria-list {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 6px 10px;
            margin-top: 10px
        }

        .criteria-item {
            display: flex;
            align-items: flex-start;
            gap: 5px;
            color: #8b9caf;
            font-size: 7.3px;
            line-height: 1.45
        }

        .criteria-icon {
            width: 13px;
            height: 13px;
            flex: 0 0 auto;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: 1px;
            border-radius: 50%;
            color: #a4b2c1;
            background: #edf1f5;
            font-size: 7px
        }

        .criteria-item.valid {
            color: #3e7f5e
        }

        .criteria-item.valid .criteria-icon {
            color: #fff;
            background: var(--success)
        }

        .confirm-message {
            min-height: 16px;
            margin-top: 6px;
            font-size: 7.8px;
            line-height: 1.45
        }

        .confirm-message.error {
            color: var(--danger)
        }

        .confirm-message.success {
            color: #147348
        }

        .success-hero {
            text-align: center
        }

        .success-icon {
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            border: 1px solid var(--success-border);
            border-radius: 50%;
            color: var(--success);
            background: var(--success-soft);
            box-shadow: 0 0 0 8px rgba(21, 153, 87, .055);
            font-size: 26px
        }

        .success-title {
            color: var(--text-primary);
            font-size: 18px;
            font-weight: 900;
            line-height: 1.35
        }

        .success-description {
            max-width: 360px;
            margin: 9px auto 0;
            color: var(--text-secondary);
            font-size: 9px;
            line-height: 1.7
        }

        .tips-box {
            margin-top: 19px;
            padding: 12px 13px;
            border: 1px solid var(--success-border);
            border-radius: 10px;
            background: var(--success-soft);
            text-align: left
        }

        .tips-title {
            display: flex;
            align-items: center;
            gap: 7px;
            color: #28734c;
            font-size: 8.5px;
            font-weight: 850
        }

        .tips-list {
            margin: 8px 0 0 17px;
            color: #547463;
            font-size: 7.7px;
            line-height: 1.75
        }

        @media(max-width:1180px) {
            .recovery-shell {
                grid-template-columns: minmax(480px, 1fr) minmax(410px, .88fr)
            }

            .brand-panel {
                padding-left: 38px;
                padding-right: 38px
            }
        }

        @media(max-width:1024px) {
            .recovery-shell {
                display: block
            }

            .brand-panel {
                display: none
            }

            .auth-panel {
                min-height: 100vh
            }

            .auth-header {
                justify-content: space-between
            }

            .auth-header:before {
                content: "Penelitian RKA-K/L";
                color: var(--text-primary);
                font-size: 9px;
                font-weight: 850
            }

            .recovery-card {
                max-width: 480px
            }
        }

        @media(max-width:680px) {
            .auth-header {
                min-height: 58px;
                padding: 0 17px
            }

            .auth-header span {
                display: none
            }

            .auth-content {
                align-items: flex-start;
                padding: 24px 14px 30px
            }

            .recovery-card {
                border-radius: 14px
            }

            .card-head {
                padding: 22px 20px 18px
            }

            .card-body {
                padding: 20px
            }

            .criteria-list {
                grid-template-columns: 1fr
            }

            .auth-footer {
                padding-left: 18px;
                padding-right: 18px
            }
        }

        @media(max-width:420px) {
            .stepper {
                grid-template-columns: 26px 1fr 26px 1fr 26px 1fr 26px
            }

            .step-circle {
                width: 26px;
                height: 26px
            }

            .step-label {
                top: 32px;
                font-size: 6.7px;
                width: 68px
            }

            .otp-grid {
                gap: 5px
            }

            .otp-input {
                height: 44px;
                font-size: 14px
            }
        }
    </style>
</head>

<body>
    <main class="recovery-shell">

        <section class="brand-panel" aria-label="Informasi pemulihan akun">
            <div class="brand-top">
                <div class="brand-logo-wrap">
                    <img src="{{ asset('images/logo-kemendag.png') }}" alt="Logo Kementerian Perdagangan"
                        class="brand-logo">
                </div>
                <div class="brand-ministry">
                    <small>Kementerian Perdagangan Republik Indonesia</small>
                    <strong>Sistem Informasi Penelitian RKA-K/L</strong>
                </div>
            </div>
            <div class="brand-content">
                <div class="system-chip"><i class="bi bi-shield-lock"></i> Pemulihan Akses Akun</div>
                <h1 class="brand-title">Verifikasi identitas sebelum <span>mengubah kata sandi.</span></h1>
                <p class="brand-description">Kode OTP digunakan sebagai lapisan verifikasi agar perubahan kredensial
                    hanya dilakukan oleh pengguna yang memiliki akses ke email terdaftar.</p>
                <div class="recovery-info">
                    <article class="info-card">
                        <div class="info-icon"><i class="bi bi-envelope-check"></i></div>
                        <div class="info-copy"><strong>Verifikasi melalui email</strong>
                            <p>Kode OTP dikirim ke alamat email yang telah terdaftar pada akun pengguna.</p>
                        </div>
                    </article>
                    <article class="info-card">
                        <div class="info-icon"><i class="bi bi-person-check"></i></div>
                        <div class="info-copy"><strong>Validasi identitas akun</strong>
                            <p>OTP memastikan proses perubahan password dilakukan oleh pemilik akses yang sah.</p>
                        </div>
                    </article>
                    <article class="info-card">
                        <div class="info-icon"><i class="bi bi-key"></i></div>
                        <div class="info-copy"><strong>Password baru</strong>
                            <p>Pengguna menetapkan password baru setelah verifikasi kode otp berhasil dilakukan.</p>
                        </div>
                    </article>
                    <article class="info-card">
                        <div class="info-icon"><i class="bi bi-box-arrow-in-right"></i></div>
                        <div class="info-copy"><strong>Akses kembali sistem</strong>
                            <p>Setelah berhasil, gunakan password baru tersebut untuk masuk ke workspace penelitian RKA-K/L.
                            </p>
                        </div>
                    </article>
                </div>
                <div class="workflow">
                    <div class="workflow-label">Alur Pemulihan Akun</div>
                    <div class="workflow-track">
                        <div class="workflow-step is-done">
                            <div class="workflow-number"><i class="bi bi-check-lg"></i></div>
                            <strong>Email</strong><span>Identifikasi akun</span>
                        </div>
                        <div class="workflow-step is-active">
                            <div class="workflow-number">2</div><strong>OTP</strong><span>Verifikasi kode</span>
                        </div>
                        <div class="workflow-step ">
                            <div class="workflow-number">3</div><strong>Password</strong><span>Buat kata sandi</span>
                        </div>
                        <div class="workflow-step ">
                            <div class="workflow-number">4</div><strong>Selesai</strong><span>Kembali login</span>
                        </div>
                    </div>
                </div>
            </div>
            {{-- <div class="brand-footer">
                <span><strong>RKA-K/L Research Workspace</strong><br>Pemulihan akses pengguna terdaftar.</span>
                <span>Internal • Kementerian Perdagangan RI</span>
            </div> --}}
        </section>

        <section class="auth-panel">
            {{-- <header class="auth-header"><i class="bi bi-lock-fill"></i><span>Proses pemulihan akun pengguna</span></header> --}}
            <div class="auth-content">
                <section class="recovery-card" aria-labelledby="recoveryTitle">
                    <div class="card-head">
                        <div class="card-heading">
                            <div class="card-icon" aria-hidden="true"><i class="bi bi-shield-lock"></i></div>
                            <div>
                                <div class="card-eyebrow">Langkah 2 dari 4</div>
                                <h2 class="card-title" id="recoveryTitle">Verifikasi Kode OTP</h2>
                            </div>
                        </div>
                        <p class="card-description">Masukkan enam digit kode verifikasi yang dikirim ke email akun Anda.
                            Setelah valid, Anda dapat membuat kata sandi baru.</p>
                    </div>
                    <div class="card-body">

                        <div class="stepper">
                            <div class="step completed">
                                <div class="step-circle"><i class="bi bi-check-lg"></i></div><span
                                    class="step-label">Email</span>
                            </div>
                            <div class="step-line completed"></div>
                            <div class="step current">
                                <div class="step-circle"><i class="bi bi-key-fill"></i></div><span
                                    class="step-label">Verifikasi</span>
                            </div>
                            <div class="step-line "></div>
                            <div class="step ">
                                <div class="step-circle"><i class="bi bi-lock-fill"></i></div><span
                                    class="step-label">Password Baru</span>
                            </div>
                            <div class="step-line "></div>
                            <div class="step ">
                                <div class="step-circle"><i class="bi bi-check-circle-fill"></i></div><span
                                    class="step-label">Selesai</span>
                            </div>
                        </div>
                        @if (session('error'))
                            <div class="server-message server-message-error" role="alert"><i
                                    class="bi bi-exclamation-circle-fill"></i><span>{{ session('error') }}</span></div>
                        @endif
                        @if (session('success'))
                            <div class="server-message server-message-success" role="status"><i
                                    class="bi bi-check-circle-fill"></i><span>{{ session('success') }}</span></div>
                        @endif
                        @error('otp')
                            <div class="server-message server-message-error" role="alert"><i
                                    class="bi bi-exclamation-circle-fill"></i><span>{{ $message }}</span></div>
                        @enderror

                        <div class="security-note" style="margin-top:0;margin-bottom:16px">
                            <i class="bi bi-envelope-check"></i>
                            <span>Kode OTP 6 digit telah dikirim ke <strong>{{ $maskedEmail }}</strong> dan berlaku
                                selama <strong>{{ $otpExpiresIn ?? 5 }} menit</strong>.</span>
                        </div>

                        <form id="otpForm" action="{{ route('forgot.password.verify') }}" method="POST" novalidate>
                            @csrf
                            <div class="form-group">
                                <div class="form-label-row"><label class="form-label">Kode OTP</label><span
                                        class="form-required">6 digit</span></div>
                                <div class="otp-grid" id="otpInputs">
                                    <input class="otp-input" type="text" inputmode="numeric"
                                        autocomplete="one-time-code" maxlength="1" aria-label="Digit OTP 1">
                                    <input class="otp-input" type="text" inputmode="numeric" maxlength="1"
                                        aria-label="Digit OTP 2">
                                    <input class="otp-input" type="text" inputmode="numeric" maxlength="1"
                                        aria-label="Digit OTP 3">
                                    <input class="otp-input" type="text" inputmode="numeric" maxlength="1"
                                        aria-label="Digit OTP 4">
                                    <input class="otp-input" type="text" inputmode="numeric" maxlength="1"
                                        aria-label="Digit OTP 5">
                                    <input class="otp-input" type="text" inputmode="numeric" maxlength="1"
                                        aria-label="Digit OTP 6">
                                </div>
                                <input type="hidden" name="otp" id="otpValue" value="{{ old('otp') }}">
                                <div class="resend-row">
                                    <span>Tidak menerima kode?</span>
                                    <span class="resend-action" id="countdownWrapper">Kirim ulang dalam <span
                                            class="countdown" id="countdown">00:57</span></span>
                                    <button type="button" class="resend-button" id="resendButton">Kirim Ulang
                                        OTP</button>
                                </div>
                            </div>
                            <button type="submit" class="primary-button" id="verifyButton" disabled><i
                                    class="bi bi-shield-check"></i>Verifikasi OTP</button>
                            <a href="{{ route('forgot.password') }}" class="secondary-link"><i
                                    class="bi bi-arrow-left"></i>Ubah Alamat Email</a>
                        </form>

                        <form id="resendOtpForm" action="{{ route('forgot.password.resend') }}" method="POST"
                            hidden>@csrf</form>

                    </div>
                </section>
            </div>
            <footer class="auth-footer">© {{ date('Y') }} Biro Perencanaan — Kementerian Perdagangan Republik
                Indonesia</footer>
        </section>
    </main>

    <script>
        const otpInputs = Array.from(document.querySelectorAll(".otp-input"));
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
            otpInputs.forEach(input => input.classList.toggle("filled", input.value !== ""));
        }

        const oldOtp = (otpValue.value || "").replace(/\D/g, "").slice(0, otpInputs.length);
        oldOtp.split("").forEach((digit, index) => otpInputs[index].value = digit);
        updateOtpValue();

        otpInputs.forEach((input, index) => {
            input.addEventListener("input", event => {
                event.target.value = event.target.value.replace(/\D/g, "");
                if (event.target.value && index < otpInputs.length - 1) otpInputs[index + 1].focus();
                updateOtpValue();
            });
            input.addEventListener("keydown", event => {
                if (event.key === "Backspace" && !input.value && index > 0) otpInputs[index - 1].focus();
                if (event.key === "ArrowLeft" && index > 0) otpInputs[index - 1].focus();
                if (event.key === "ArrowRight" && index < otpInputs.length - 1) otpInputs[index + 1]
            .focus();
            });
            input.addEventListener("focus", () => input.select());
        });

        document.getElementById("otpInputs").addEventListener("paste", event => {
            event.preventDefault();
            const pastedCode = event.clipboardData.getData("text").replace(/\D/g, "").slice(0, otpInputs.length);
            pastedCode.split("").forEach((digit, index) => otpInputs[index].value = digit);
            const focusIndex = Math.min(Math.max(pastedCode.length - 1, 0), otpInputs.length - 1);
            otpInputs[focusIndex].focus();
            updateOtpValue();
        });

        otpForm.addEventListener("submit", event => {
            updateOtpValue();
            if (otpValue.value.length !== otpInputs.length) {
                event.preventDefault();
                otpInputs[0].focus();
                return;
            }
            verifyButton.disabled = true;
            verifyButton.innerHTML = '<i class="bi bi-hourglass-split"></i> Memverifikasi...';
        });

        let remainingSeconds = 57;
        let countdownInterval;

        function formatTime(seconds) {
            const minutes = Math.floor(seconds / 60).toString().padStart(2, "0");
            const secs = (seconds % 60).toString().padStart(2, "0");
            return `${minutes}:${secs}`;
        }

        function startCountdown() {
            clearInterval(countdownInterval);
            countdownWrapper.style.display = "inline";
            resendButton.style.display = "none";
            countdownElement.textContent = formatTime(remainingSeconds);
            countdownInterval = setInterval(() => {
                remainingSeconds--;
                countdownElement.textContent = formatTime(Math.max(remainingSeconds, 0));
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
            document.getElementById("resendOtpForm").submit();
        });
        startCountdown();
    </script>

</body>

</html>
