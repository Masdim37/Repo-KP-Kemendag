<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<style>
    :root {
        --primary: #0759b7;
        --primary-dark: #06498f;
        --primary-soft: #eef5ff;
        --success: #159957;
        --danger: #df4052;
        --warning: #db9b17;
        --text-primary: #18365b;
        --text-secondary: #607995;
        --text-muted: #91a4b9;
        --background: #f3f6fa;
        --border: #dbe5ee;
        --white: #fff
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

    .app-shell {
        min-height: 100vh
    }

    .app-main {
        min-height: 100vh;
        display: flex;
        flex-direction: column
    }

    .dashboard-header {
        position: sticky;
        top: 0;
        z-index: 900;
        min-height: 66px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 18px;
        padding: 0 25px;
        border-bottom: 1px solid var(--border);
        background: rgba(255, 255, 255, .96);
        box-shadow: 0 4px 18px rgba(33, 67, 103, .05);
        backdrop-filter: blur(12px)
    }

    .header-left {
        min-width: 0;
        display: flex;
        align-items: center;
        gap: 13px
    }

    .sidebar-toggle {
        display: none;
        width: 36px;
        height: 36px;
        align-items: center;
        justify-content: center;
        border: 1px solid var(--border);
        border-radius: 9px;
        color: var(--text-primary);
        background: #fff;
        font-size: 18px
    }

    .header-eyebrow {
        color: #879bb1;
        font-size: 7.5px;
        font-weight: 700;
        letter-spacing: .8px
    }

    .header-title {
        margin-top: 3px;
        font-size: 14px;
        font-weight: 800
    }

    .header-user {
        flex-shrink: 0;
        display: flex;
        align-items: center;
        gap: 10px
    }

    .header-user-text {
        text-align: right;
        color: var(--text-secondary);
        font-size: 8.5px;
        line-height: 1.4
    }

    .header-user-text strong {
        display: block;
        max-width: 210px;
        overflow: hidden;
        color: var(--text-primary);
        font-size: 10px;
        text-overflow: ellipsis;
        white-space: nowrap
    }

    .header-avatar {
        width: 37px;
        height: 37px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 3px solid #e4eef8;
        border-radius: 50%;
        color: #fff;
        background: linear-gradient(135deg, #063c7c, #1681d5);
        font-size: 10px;
        font-weight: 800
    }

    .page-container {
        width: 100%;
        flex: 1;
        padding: 24px
    }

    .content-wrapper {
        width: 100%;
        max-width: 1040px;
        margin: 0 auto
    }

    .info-box {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        margin-bottom: 16px;
        padding: 13px 15px;
        border: 1px solid #cfe1fb;
        border-radius: 10px;
        color: #4b74a3;
        background: #eef6ff;
        font-size: 8.5px;
        line-height: 1.55
    }

    .info-box i {
        flex-shrink: 0;
        color: var(--primary);
        font-size: 14px
    }

    .main-card {
        overflow: hidden;
        border: 1px solid var(--border);
        border-radius: 14px;
        background: #fff;
        box-shadow: 0 8px 25px rgba(38, 68, 103, .07)
    }

    .card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        padding: 18px 21px;
        border-bottom: 1px solid #e6ecf2
    }

    .card-header-left {
        display: flex;
        align-items: center;
        gap: 11px
    }

    .card-header-icon {
        width: 34px;
        height: 34px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 9px;
        color: var(--primary);
        background: var(--primary-soft)
    }

    .card-title {
        font-size: 13px;
        font-weight: 800
    }

    .card-description {
        margin-top: 3px;
        color: var(--text-muted);
        font-size: 8px;
        line-height: 1.5
    }

    .card-badge {
        padding: 5px 9px;
        border: 1px solid #d7e6f7;
        border-radius: 999px;
        color: #4775a8;
        background: #f1f7ff;
        font-size: 7px;
        font-weight: 800;
        letter-spacing: .2px
    }

    .main-form {
        padding: 20px
    }

    .section-title {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 10px;
        margin-bottom: 14px;
        padding-left: 8px;
        border-left: 3px solid var(--primary)
    }

    .section-title h2 {
        font-size: 11px;
        font-weight: 800
    }

    .section-title span {
        display: block;
        margin-top: 3px;
        color: var(--text-muted);
        font-size: 7.5px;
        line-height: 1.45
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 14px
    }

    .form-group.full {
        grid-column: 1/-1
    }

    .form-label {
        display: block;
        margin-bottom: 6px;
        color: #53677e;
        font-size: 8px;
        font-weight: 800;
        text-transform: uppercase
    }

    .required {
        color: var(--danger)
    }

    .form-control {
        width: 100%;
        min-height: 40px;
        padding: 0 11px;
        border: 1px solid #d5dee7;
        border-radius: 8px;
        outline: none;
        color: #304b69;
        background: #fff;
        font-size: 8.7px;
        transition: .2s ease
    }

    .form-control:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(7, 89, 183, .07)
    }

    select.form-control {
        cursor: pointer
    }

    select.form-control:disabled,
    .form-control:disabled {
        color: #8998aa;
        background: #f4f6f8;
        cursor: not-allowed
    }

    textarea.form-control {
        min-height: 100px;
        padding: 10px 11px;
        resize: vertical;
        line-height: 1.5
    }

    .form-control.is-invalid {
        border-color: var(--danger);
        box-shadow: 0 0 0 3px rgba(223, 64, 82, .07)
    }

    .field-help {
        margin-top: 5px;
        color: var(--text-muted);
        font-size: 7.2px;
        line-height: 1.45
    }

    .field-error {
        display: none;
        margin-top: 5px;
        color: var(--danger);
        font-size: 7.5px;
        line-height: 1.45
    }

    .field-error.show {
        display: block
    }

    .status-list {
        display: flex;
        flex-wrap: wrap;
        gap: 7px;
        margin-top: 18px
    }

    .status-chip {
        padding: 6px 9px;
        border: 1px solid #e1e8ef;
        border-radius: 999px;
        color: #8b9caf;
        background: #f8fafc;
        font-size: 7.3px;
        font-weight: 700
    }

    .status-chip.complete {
        border-color: #bfe2ce;
        color: #247648;
        background: #effaf4
    }

    .form-actions {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        margin-top: 18px;
        padding-top: 16px;
        border-top: 1px solid #e7edf3
    }

    .action-message {
        color: #8798aa;
        font-size: 7.5px;
        line-height: 1.45
    }

    .save-button {
        min-width: 190px;
        height: 39px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        padding: 0 18px;
        border: 0;
        border-radius: 8px;
        color: #fff;
        background: var(--primary);
        font-size: 8.5px;
        font-weight: 800;
        cursor: pointer
    }

    .save-button:disabled {
        color: #98a7b7;
        background: #e1e7ed;
        cursor: not-allowed
    }

    .subsection {
        grid-column: 1/-1;
        margin-top: 2px;
        padding: 13px;
        border: 1px solid #e0e8f0;
        border-radius: 10px;
        background: #f9fbfd
    }

    .subsection-title {
        margin-bottom: 10px;
        color: #47627e;
        font-size: 8px;
        font-weight: 800;
        text-transform: uppercase
    }

    .multi-select {
        min-height: 190px;
        padding: 6px
    }

    .multi-select option {
        padding: 6px 7px;
        border-radius: 5px
    }

    .mode-note {
        margin-top: 7px;
        padding: 8px 10px;
        border: 1px solid #e1e8ef;
        border-radius: 8px;
        color: #6f8297;
        background: #f8fbfe;
        font-size: 7.5px;
        line-height: 1.5
    }

    .hidden-section {
        display: none !important
    }

    .alert {
        margin-bottom: 12px;
        padding: 10px 12px;
        border-radius: 8px;
        font-size: 8px
    }

    .alert.success {
        border: 1px solid #bfe2ce;
        color: #247648;
        background: #effaf4
    }

    .alert.error {
        border: 1px solid #efc3c8;
        color: #a72d3a;
        background: #fff4f5
    }

    .footer {
        margin-top: auto;
        border-top: 1px solid var(--border);
        background: #eef3f8
    }

    .footer-container {
        width: 100%;
        max-width: 1040px;
        min-height: 68px;
        display: flex;
        align-items: center;
        margin: 0 auto;
        padding: 15px 26px
    }

    .footer-brand {
        color: #75889b;
        font-size: 7.5px;
        line-height: 1.6
    }

    .footer-brand strong {
        display: block;
        color: #405974;
        font-size: 8.5px
    }

    @media(max-width:760px) {
        .form-grid {
            grid-template-columns: 1fr
        }

        .form-group.full,
        .subsection {
            grid-column: auto
        }

        .form-actions {
            align-items: stretch;
            flex-direction: column
        }

        .save-button {
            width: 100%
        }
    }

    @media(max-width:680px) {
        .dashboard-header {
            padding: 0 14px
        }

        .sidebar-toggle {
            display: flex
        }

        .header-user-text {
            display: none
        }

        .page-container {
            padding: 15px
        }

        .main-form,
        .card-header {
            padding: 17px
        }

        .footer-container {
            padding: 15px 14px
        }
    }
</style>
