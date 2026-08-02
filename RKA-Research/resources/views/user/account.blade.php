<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Akun Pengguna | Penelitian RKA-K/L</title>

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    >

    <style>

* {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
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

        /* HEADER */

        .top-header {
            height: 67px;
            display: flex;
            align-items: center;
            border-bottom: 3px solid var(--primary);
            background: var(--white);
        }

        .header-container {
            width: 100%;
            max-width: 1200px;
            margin: auto;
            padding: 0 22px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 15px;
            font-weight: 800;
        }

        .brand-icon {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            color: var(--primary);
            background: var(--primary-soft);
        }

        .header-user {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .header-user-text {
            text-align: right;
            font-size: 10px;
            line-height: 1.4;
        }

        .header-user-text strong {
            display: block;
            font-size: 11px;
        }

        .header-avatar {
            width: 38px;
            height: 38px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            color: #ffffff;
            background: linear-gradient(135deg, #173f6b, #3d8bd1);
            font-size: 12px;
            font-weight: 800;
        }

        /* CONTENT */

        .page-container {
            width: 100%;
            max-width: 1200px;
            min-height: calc(100vh - 145px);
            margin: auto;
            padding: 28px 22px 48px;
        }

        .page-title {
            font-size: 25px;
            font-weight: 800;
        }

        .page-description {
            margin-top: 4px;
            color: var(--text-secondary);
            font-size: 12px;
            line-height: 1.5;
        }

        .account-layout {
            display: grid;
            grid-template-columns: 285px 1fr;
            gap: 20px;
            margin-top: 24px;
        }

        .card {
            border: 1px solid var(--border);
            border-radius: 12px;
            background: var(--white);
            box-shadow: 0 3px 12px rgba(26, 52, 84, 0.06);
        }

        /* PROFILE */

        .profile-card {
            height: fit-content;
            padding: 24px 19px 18px;
            text-align: center;
        }

        .avatar-wrapper {
            position: relative;
            width: fit-content;
            margin: auto;
        }

        .profile-avatar {
            width: 96px;
            height: 96px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 4px solid #e0e7ef;
            border-radius: 50%;
            color: #ffffff;
            background: linear-gradient(135deg, #173f6b, #3d8bd1);
            font-size: 27px;
            font-weight: 800;
        }

        .online-indicator {
            position: absolute;
            right: 5px;
            bottom: 7px;
            width: 17px;
            height: 17px;
            border: 3px solid #ffffff;
            border-radius: 50%;
            background: #19b75d;
        }

        .profile-name {
            margin-top: 17px;
            font-size: 15px;
            line-height: 1.45;
            font-weight: 800;
        }

        .profile-position {
            margin-top: 4px;
            color: var(--text-secondary);
            font-size: 11px;
        }

        .status-badge {
            width: fit-content;
            display: flex;
            align-items: center;
            gap: 5px;
            margin: 10px auto 0;
            padding: 4px 9px;
            border-radius: 20px;
            color: #0a65bd;
            background: #ddecff;
            font-size: 9px;
            font-weight: 700;
        }

        .profile-divider {
            height: 1px;
            margin: 22px 0 15px;
            background: #e4e9ef;
        }

        .profile-meta {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .meta-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            font-size: 10px;
        }

        .meta-label {
            color: var(--text-secondary);
        }

        .meta-value {
            text-align: right;
            font-weight: 700;
        }

        /* INFORMATION */

        .right-content {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .information-card {
            padding: 20px;
        }

        .section-heading {
            display: flex;
            align-items: center;
            gap: 8px;
            padding-bottom: 12px;
            border-bottom: 1px solid #e4e9ef;
            font-size: 13px;
            font-weight: 800;
        }

        .section-heading i {
            color: var(--primary);
            font-size: 15px;
        }

        .information-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 15px 20px;
            margin-top: 18px;
        }

        .information-item.full-width {
            grid-column: 1 / -1;
        }

        .information-label {
            display: block;
            margin-bottom: 6px;
            color: #506078;
            font-size: 9px;
            font-weight: 700;
        }

        .information-box {
            min-height: 42px;
            display: flex;
            align-items: center;
            gap: 9px;
            padding: 9px 12px;
            border: 1px solid #d7dee7;
            border-radius: 8px;
            color: #233a58;
            background: #f3f5f7;
            font-size: 11px;
            word-break: break-word;
        }

        .information-box i {
            flex-shrink: 0;
            color: #8290a2;
        }

        /* ACTION */

        .action-card {
            padding: 18px;
        }

        .action-buttons {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .button {
            min-height: 43px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 0 23px;
            border: 1px solid transparent;
            border-radius: 8px;
            font-size: 11px;
            font-weight: 700;
            cursor: pointer;
            transition: 0.2s ease;
        }

        .button:disabled {
            opacity: 0.65;
            cursor: not-allowed;
        }

        .button-primary {
            color: #ffffff;
            background: var(--primary);
        }

        .button-primary:hover:not(:disabled) {
            background: var(--primary-dark);
        }

        .button-danger {
            color: var(--danger);
            border-color: #ef9da6;
            background: #ffffff;
        }

        .button-danger:hover {
            background: var(--danger-soft);
        }

        .button-secondary {
            color: #42566f;
            border-color: #bcc7d3;
            background: #ffffff;
        }

        .button-secondary:hover {
            background: #f3f6f9;
        }

        .button-logout {
            margin-left: auto;
        }

        /* FOOTER */

        .footer {
            border-top: 1px solid #d9e0e8;
            background: #eef1f4;
        }

        .footer-container {
            width: 100%;
            max-width: 1200px;
            min-height: 78px;
            margin: auto;
            padding: 18px 22px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
        }

        .footer-brand {
            font-size: 9px;
            line-height: 1.6;
        }

        .footer-brand strong {
            display: block;
            font-size: 10px;
        }

        .footer-links {
            display: flex;
            gap: 22px;
            color: #52637a;
            font-size: 9px;
        }

        /* MODAL */

        .modal-overlay {
            position: fixed;
            inset: 0;
            z-index: 1000;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 20px;
            background: rgba(8, 26, 48, 0.55);
        }

        .modal-overlay.show {
            display: flex;
        }

        .modal-box {
            width: 100%;
            max-width: 470px;
            padding: 23px;
            border-radius: 13px;
            background: #ffffff;
            box-shadow: 0 22px 70px rgba(10, 30, 55, 0.25);
        }

        .edit-account-modal {
            width: 100%;
            max-width: 650px;
            max-height: calc(100vh - 40px);
            overflow-y: auto;
            border-radius: 13px;
            background: #ffffff;
            box-shadow: 0 22px 70px rgba(10, 30, 55, 0.25);
        }

        .modal-header,
        .edit-modal-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 15px;
        }

        .edit-modal-header {
            align-items: center;
            padding: 18px 22px;
            border-bottom: 1px solid #dfe5ec;
        }

        .modal-title {
            font-size: 17px;
            font-weight: 800;
        }

        .modal-description {
            margin-top: 6px;
            color: var(--text-secondary);
            font-size: 11px;
            line-height: 1.6;
        }

        .modal-close {
            width: 31px;
            height: 31px;
            flex-shrink: 0;
            border: 0;
            border-radius: 50%;
            color: #66768a;
            background: #f0f3f6;
            cursor: pointer;
        }

        .modal-close:hover {
            background: #e3e9ef;
        }

        /* EDIT FORM */

        .edit-modal-body {
            padding: 22px;
        }

        .edit-form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 17px 20px;
        }

        .edit-form-group.full-width {
            grid-column: 1 / -1;
        }

        .edit-form-group label {
            display: block;
            margin-bottom: 7px;
            color: #334b68;
            font-size: 10px;
            font-weight: 700;
        }

        .edit-form-group label span {
            color: #8a98a9;
            font-weight: 400;
        }

        .edit-form-control {
            width: 100%;
            height: 41px;
            padding: 0 12px;
            border: 1px solid #cbd5e0;
            border-radius: 7px;
            outline: none;
            color: #233b58;
            background: #ffffff;
            font-size: 11px;
            transition: 0.2s;
        }

        .edit-form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(18, 100, 189, 0.09);
        }

        .edit-form-control.readonly {
            color: #69788b;
            background: #f1f3f5;
            cursor: default;
        }

        .edit-select-wrapper {
            position: relative;
        }

        .edit-select {
            padding-right: 38px;
            appearance: none;
            cursor: pointer;
        }

        .edit-select-wrapper > i {
            position: absolute;
            top: 50%;
            right: 13px;
            transform: translateY(-50%);
            color: #75859a;
            font-size: 11px;
            pointer-events: none;
        }

        .edit-helper {
            display: block;
            margin-top: 6px;
            color: #6f8299;
            font-size: 8px;
            line-height: 1.5;
        }

        .edit-modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            padding: 16px 22px;
            border-top: 1px solid #dfe5ec;
        }

        .warning-icon {
            width: 54px;
            height: 54px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 14px;
            border-radius: 50%;
            color: var(--danger);
            background: var(--danger-soft);
            font-size: 24px;
        }

        .modal-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 22px;
        }

        /* RESPONSIVE */

        @media (max-width: 850px) {
            .account-layout {
                grid-template-columns: 1fr;
            }

            .information-grid {
                grid-template-columns: 1fr;
            }

            .information-item.full-width {
                grid-column: auto;
            }
        }

        @media (max-width: 600px) {
            .header-user-text {
                display: none;
            }

            .page-container {
                padding: 24px 14px 35px;
            }

            .page-title {
                font-size: 21px;
            }

            .information-card,
            .action-card {
                padding: 15px;
            }

            .action-buttons {
                flex-direction: column;
            }

            .button,
            .button-logout {
                width: 100%;
                margin-left: 0;
            }

            .edit-form-grid {
                grid-template-columns: 1fr;
            }

            .edit-form-group.full-width {
                grid-column: auto;
            }

            .edit-modal-footer,
            .modal-actions {
                flex-direction: column-reverse;
            }

            .footer-container {
                align-items: flex-start;
                flex-direction: column;
            }

            .footer-links {
                flex-wrap: wrap;
                gap: 12px;
            }
        }
    


        /* =====================================================
           DASHBOARD SHELL + SIDEBAR
        ===================================================== */

        :root {
            --primary: #0759b7;
            --primary-dark: #063c7c;
            --primary-light: #7fb0df;
            --primary-soft: #edf5ff;
            --sidebar-start: #06356c;
            --sidebar-middle: #064996;
            --sidebar-end: #0872cf;
            --danger: #dc3545;
            --danger-soft: #fff1f2;
            --success: #16b65b;
            --success-soft: #eaf9f1;
            --text-primary: #18365b;
            --text-secondary: #607995;
            --text-muted: #9aabbd;
            --border: #d8e2ed;
            --background: #f3f7fc;
            --white: #ffffff;
            --sidebar-width: 250px;
            --header-height: 66px;
        }

        body {
            overflow-x: hidden;
            color: var(--text-primary);
            background: var(--background);
        }

        .app-shell {
            min-height: 100vh;
        }

        .sidebar {
            position: fixed;
            inset: 0 auto 0 0;
            z-index: 1100;
            width: var(--sidebar-width);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            color: #ffffff;
            background:
                radial-gradient(
                    circle at 105% 18%,
                    rgba(255, 255, 255, 0.10) 0,
                    rgba(255, 255, 255, 0.10) 92px,
                    transparent 93px
                ),
                radial-gradient(
                    circle at -18% 88%,
                    rgba(255, 255, 255, 0.08) 0,
                    rgba(255, 255, 255, 0.08) 120px,
                    transparent 121px
                ),
                linear-gradient(
                    160deg,
                    var(--sidebar-start) 0%,
                    var(--sidebar-middle) 48%,
                    var(--sidebar-end) 100%
                );
            box-shadow: 12px 0 34px rgba(16, 52, 93, 0.13);
            overflow: hidden;
            transition: transform 0.25s ease;
        }

        .sidebar-header {
            min-height: var(--header-height);
            display: flex;
            align-items: center;
            gap: 11px;
            padding: 15px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.13);
        }

        .sidebar-brand-logo {
            position: relative;
            width: 31px;
            height: 31px;
            flex: 0 0 auto;
        }

        .sidebar-brand-logo::before {
            content: "";
            position: absolute;
            inset: 6px;
            transform: rotate(45deg);
            border-radius: 4px;
            background:
                linear-gradient(
                    135deg,
                    #bfe0ff 0%,
                    #ffffff 48%,
                    #6ca8e4 100%
                );
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.14);
        }

        .sidebar-brand-logo::after {
            content: "";
            position: absolute;
            width: 6px;
            height: 6px;
            left: 13px;
            top: 13px;
            border-radius: 50%;
            background: #1265ba;
        }

        .sidebar-brand-text {
            min-width: 0;
        }

        .sidebar-brand-government {
            display: block;
            color: rgba(255, 255, 255, 0.70);
            font-size: 8px;
            font-weight: 600;
            letter-spacing: 0.65px;
            line-height: 1.35;
        }

        .sidebar-brand-name {
            display: block;
            margin-top: 2px;
            color: #ffffff;
            font-size: 11px;
            font-weight: 750;
            line-height: 1.35;
        }

        .sidebar-body {
            flex: 1;
            display: flex;
            flex-direction: column;
            padding: 21px 14px 15px;
        }

        .sidebar-system-label {
            padding: 0 10px;
            color: rgba(202, 226, 255, 0.62);
            font-size: 7.5px;
            font-weight: 700;
            letter-spacing: 1px;
        }

        .sidebar-system-title {
            margin-top: 7px;
            padding: 0 10px 20px;
            color: #ffffff;
            font-size: 15px;
            font-weight: 800;
            line-height: 1.3;
        }

        .sidebar-nav-label {
            margin: 2px 10px 9px;
            color: rgba(219, 237, 255, 0.55);
            font-size: 7.5px;
            font-weight: 700;
            letter-spacing: 0.8px;
        }

        .sidebar-nav {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .sidebar-link {
            position: relative;
            min-height: 43px;
            display: flex;
            align-items: center;
            gap: 11px;
            padding: 0 13px;
            border: 1px solid transparent;
            border-radius: 11px;
            color: rgba(238, 247, 255, 0.80);
            font-size: 10px;
            font-weight: 650;
            transition:
                background 0.2s ease,
                border-color 0.2s ease,
                color 0.2s ease,
                transform 0.2s ease;
        }

        .sidebar-link i {
            width: 18px;
            text-align: center;
            font-size: 15px;
        }

        .sidebar-link:hover {
            color: #ffffff;
            background: rgba(255, 255, 255, 0.10);
            transform: translateX(2px);
        }

        .sidebar-link.active {
            color: #ffffff;
            border-color: rgba(255, 255, 255, 0.20);
            background: rgba(128, 188, 249, 0.30);
            box-shadow: 0 8px 20px rgba(0, 28, 70, 0.13);
        }

        .sidebar-link.active::before {
            content: "";
            position: absolute;
            left: -14px;
            top: 8px;
            bottom: 8px;
            width: 3px;
            border-radius: 0 5px 5px 0;
            background: #91caff;
        }

        .sidebar-link-badge {
            margin-left: auto;
            padding: 3px 7px;
            border-radius: 20px;
            color: #e9f6ff;
            background: rgba(255, 255, 255, 0.15);
            font-size: 7px;
            font-weight: 700;
        }

        .sidebar-footer {
            margin-top: auto;
            padding-top: 17px;
            border-top: 1px solid rgba(255, 255, 255, 0.13);
        }

        .sidebar-user {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 10px 12px;
        }

        .sidebar-avatar {
            width: 34px;
            height: 34px;
            flex: 0 0 auto;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid rgba(255, 255, 255, 0.24);
            border-radius: 50%;
            color: #ffffff;
            background: rgba(255, 255, 255, 0.15);
            font-size: 9px;
            font-weight: 800;
        }

        .sidebar-user-copy {
            min-width: 0;
        }

        .sidebar-user-name {
            display: block;
            overflow: hidden;
            color: #ffffff;
            font-size: 9px;
            font-weight: 700;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .sidebar-user-role {
            display: block;
            margin-top: 2px;
            overflow: hidden;
            color: rgba(224, 240, 255, 0.62);
            font-size: 7.5px;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .sidebar-logout {
            width: 100%;
            min-height: 37px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            border: 1px solid rgba(255, 255, 255, 0.16);
            border-radius: 10px;
            color: rgba(239, 247, 255, 0.88);
            background: rgba(255, 255, 255, 0.08);
            font-size: 9px;
            font-weight: 700;
            cursor: pointer;
        }

        .sidebar-logout:hover {
            color: #ffffff;
            background: rgba(255, 255, 255, 0.14);
        }

        .sidebar-overlay {
            position: fixed;
            inset: 0;
            z-index: 1050;
            display: none;
            background: rgba(7, 31, 60, 0.50);
            backdrop-filter: blur(2px);
        }

        .app-main {
            min-height: 100vh;
            margin-left: var(--sidebar-width);
            display: flex;
            flex-direction: column;
            transition: margin-left 0.25s ease;
        }

        .dashboard-header {
            position: sticky;
            top: 0;
            z-index: 900;
            min-height: var(--header-height);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 18px;
            padding: 0 25px;
            border-bottom: 1px solid #dae4ef;
            background: rgba(255, 255, 255, 0.96);
            box-shadow: 0 4px 18px rgba(33, 67, 103, 0.05);
            backdrop-filter: blur(12px);
        }

        .header-left {
            min-width: 0;
            display: flex;
            align-items: center;
            gap: 13px;
        }

        .sidebar-toggle {
            width: 36px;
            height: 36px;
            display: none;
            align-items: center;
            justify-content: center;
            border: 1px solid #d6e0eb;
            border-radius: 9px;
            color: var(--primary);
            background: #ffffff;
            font-size: 17px;
            cursor: pointer;
        }

        .header-eyebrow {
            color: #879bb1;
            font-size: 7.5px;
            font-weight: 700;
            letter-spacing: 0.8px;
        }

        .header-title {
            margin-top: 3px;
            font-size: 14px;
            font-weight: 800;
            line-height: 1.3;
        }

        .header-user {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .header-user-text {
            text-align: right;
            color: #6f849a;
            font-size: 8.5px;
            line-height: 1.4;
        }

        .header-user-text strong {
            display: block;
            max-width: 200px;
            overflow: hidden;
            color: var(--text-primary);
            font-size: 10px;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .header-avatar {
            width: 37px;
            height: 37px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 3px solid #e4eef8;
            border-radius: 50%;
            color: #ffffff;
            background:
                linear-gradient(135deg, #063c7c, #1681d5);
            font-size: 10px;
            font-weight: 800;
            box-shadow: 0 5px 12px rgba(31, 91, 148, 0.16);
        }

        .page-container {
            width: 100%;
            max-width: 1180px;
            min-height: auto;
            flex: 1;
            margin: 0 auto;
            padding: 28px 26px 42px;
        }

        .page-heading-row {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 20px;
        }

        .page-title {
            color: var(--text-primary);
            font-size: 22px;
            font-weight: 800;
            letter-spacing: -0.45px;
        }

        .page-description {
            max-width: 680px;
            margin-top: 6px;
            color: var(--text-secondary);
            font-size: 10px;
            line-height: 1.65;
        }

        .page-chip {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-top: 2px;
            padding: 7px 11px;
            border: 1px solid #d6e7f8;
            border-radius: 20px;
            color: var(--primary);
            background: var(--primary-soft);
            font-size: 8px;
            font-weight: 700;
            white-space: nowrap;
        }

        .account-alert {
            display: flex;
            align-items: flex-start;
            gap: 9px;
            margin-top: 16px;
            padding: 11px 13px;
            border: 1px solid;
            border-radius: 11px;
            font-size: 9px;
            line-height: 1.55;
        }

        .account-alert i {
            flex: 0 0 auto;
            margin-top: 1px;
            font-size: 13px;
        }

        .account-alert ul {
            margin: 5px 0 0;
            padding-left: 16px;
        }

        .account-alert-success {
            color: #217346;
            border-color: #b9e4c6;
            background: #eefaf2;
        }

        .account-alert-error {
            color: #a52f3f;
            border-color: #f1c1c8;
            background: #fff2f4;
        }

        /* =====================================================
           PENYESUAIAN KARTU AKUN
        ===================================================== */

        .account-layout {
            grid-template-columns: 265px minmax(0, 1fr);
            gap: 18px;
            margin-top: 21px;
        }

        .card {
            border-color: #d8e3ee;
            border-radius: 15px;
            box-shadow:
                0 12px 30px rgba(45, 78, 113, 0.07),
                0 2px 6px rgba(45, 78, 113, 0.04);
        }

        .profile-card {
            position: relative;
            padding: 25px 19px 18px;
            overflow: hidden;
        }

        .profile-card::before {
            content: "";
            position: absolute;
            inset: 0 0 auto;
            height: 74px;
            background:
                linear-gradient(
                    135deg,
                    rgba(7, 89, 183, 0.13),
                    rgba(127, 176, 223, 0.05)
                );
        }

        .avatar-wrapper,
        .profile-name,
        .profile-position,
        .status-badge,
        .profile-divider,
        .profile-meta {
            position: relative;
            z-index: 1;
        }

        .profile-avatar {
            width: 91px;
            height: 91px;
            border: 5px solid #ffffff;
            background:
                linear-gradient(135deg, #063c7c, #1681d5);
            box-shadow: 0 8px 18px rgba(24, 83, 139, 0.20);
            font-size: 24px;
        }

        .online-indicator {
            right: 4px;
            bottom: 6px;
            width: 16px;
            height: 16px;
        }

        .profile-name {
            margin-top: 15px;
            color: var(--text-primary);
            font-size: 13px;
        }

        .profile-position {
            color: var(--text-secondary);
            font-size: 9px;
            line-height: 1.5;
        }

        .status-badge {
            color: #08754a;
            background: #e8f8f0;
        }

        .profile-divider {
            margin: 19px 0 14px;
            background: #e4ebf3;
        }

        .meta-row {
            font-size: 8.5px;
        }

        .information-card {
            padding: 20px 21px 22px;
        }

        .section-heading {
            padding-bottom: 13px;
            border-bottom-color: #e2eaf2;
            color: var(--text-primary);
            font-size: 11px;
        }

        .section-heading i {
            color: var(--primary);
            font-size: 14px;
        }

        .information-grid {
            gap: 14px 17px;
            margin-top: 17px;
        }

        .information-label {
            margin-bottom: 6px;
            color: #60748b;
            font-size: 8px;
            letter-spacing: 0.15px;
        }

        .information-box {
            min-height: 43px;
            padding: 9px 12px;
            border-color: #d6e1ec;
            border-radius: 10px;
            color: #263f5d;
            background: #f8fbfe;
            font-size: 9.5px;
        }

        .information-box i {
            color: #7894af;
            font-size: 12px;
        }

        .action-card {
            padding: 15px 17px;
        }

        .action-buttons {
            gap: 9px;
        }

        .button {
            min-height: 40px;
            gap: 7px;
            padding: 0 18px;
            border-radius: 10px;
            font-size: 9px;
        }

        .button-primary {
            background: var(--primary);
            box-shadow: 0 7px 15px rgba(7, 89, 183, 0.15);
        }

        .button-primary:hover:not(:disabled) {
            background: var(--primary-dark);
            transform: translateY(-1px);
        }

        .button-danger {
            border-color: #efb0b7;
        }

        .button-secondary {
            border-color: #ccd7e2;
        }

        /* =====================================================
           FOOTER
        ===================================================== */

        .footer {
            margin-top: auto;
            border-top: 1px solid #dbe5ee;
            background: #eef3f8;
        }

        .footer-container {
            max-width: 1180px;
            min-height: 68px;
            padding: 15px 26px;
        }

        .footer-brand {
            color: #75889b;
            font-size: 7.5px;
        }

        .footer-brand strong {
            color: #405974;
            font-size: 8.5px;
        }

        .footer-links {
            color: #60758b;
            font-size: 8px;
        }

        /* =====================================================
           MODAL SELARAS DENGAN TEMA
        ===================================================== */

        .modal-overlay {
            background: rgba(7, 31, 60, 0.55);
            backdrop-filter: blur(3px);
        }

        .modal-box,
        .edit-account-modal {
            border: 1px solid rgba(216, 227, 238, 0.85);
            border-radius: 15px;
            box-shadow: 0 24px 75px rgba(10, 38, 70, 0.24);
        }

        .edit-modal-header {
            background: #fbfdff;
        }

        .modal-title {
            color: var(--text-primary);
            font-size: 15px;
        }

        .modal-description {
            font-size: 9.5px;
        }

        .edit-form-group label {
            font-size: 9px;
        }

        .edit-form-control {
            height: 42px;
            border-radius: 9px;
            font-size: 9.5px;
        }

        .edit-form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(7, 89, 183, 0.09);
        }

        .edit-helper {
            font-size: 7.5px;
        }

        /* =====================================================
           RESPONSIVE SIDEBAR
        ===================================================== */

        @media (max-width: 1024px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.open {
                transform: translateX(0);
            }

            .sidebar-overlay.show {
                display: block;
            }

            .app-main {
                margin-left: 0;
            }

            .sidebar-toggle {
                display: flex;
            }
        }

        @media (max-width: 850px) {
            .account-layout {
                grid-template-columns: 1fr;
            }

            .profile-card {
                display: grid;
                grid-template-columns: auto minmax(0, 1fr);
                align-items: center;
                column-gap: 18px;
                text-align: left;
            }

            .avatar-wrapper {
                grid-row: 1 / span 4;
                margin: 0;
            }

            .profile-name {
                margin-top: 0;
            }

            .status-badge {
                margin-left: 0;
                margin-right: 0;
            }

            .profile-divider,
            .profile-meta {
                grid-column: 1 / -1;
            }
        }

        @media (max-width: 680px) {
            .dashboard-header {
                padding: 0 14px;
            }

            .header-user-text {
                display: none;
            }

            .page-container {
                padding: 23px 14px 34px;
            }

            .page-heading-row {
                display: block;
            }

            .page-chip {
                margin-top: 12px;
            }

            .page-title {
                font-size: 19px;
            }

            .information-grid {
                grid-template-columns: 1fr;
            }

            .information-item.full-width {
                grid-column: auto;
            }

            .action-buttons {
                flex-direction: column;
            }

            .button,
            .button-logout {
                width: 100%;
                margin-left: 0;
            }

            .footer-container {
                align-items: flex-start;
                flex-direction: column;
            }
        }

        @media (max-width: 480px) {
            .sidebar {
                width: min(84vw, 276px);
            }

            .profile-card {
                display: block;
                text-align: center;
            }

            .avatar-wrapper {
                margin: auto;
            }

            .profile-name {
                margin-top: 15px;
            }

            .status-badge {
                margin-left: auto;
                margin-right: auto;
            }

            .edit-form-grid {
                grid-template-columns: 1fr;
            }

            .edit-form-group.full-width {
                grid-column: auto;
            }

            .edit-modal-footer,
            .modal-actions {
                flex-direction: column-reverse;
            }
        }

    </style>
</head>

<body>

@php
    /*
    |--------------------------------------------------------------------------
    | DATA CONTOH FRONTEND
    |--------------------------------------------------------------------------
    | Ketika controller sudah mengirim $user dan $jabatans,
    | data contoh berikut otomatis diganti oleh data database.
    */

    $userName = data_get(
        $user ?? null,
        'name',
        'Dr. H. Ahmad Fauzi, S.E., M.Si.'
    );

    $userNip = data_get(
        $user ?? null,
        'nip',
        '197508122000031001'
    );

    $userEmail = data_get(
        $user ?? null,
        'email',
        'ahmad.fauzi@kemendag.go.id'
    );

    $username = data_get(
        $user ?? null,
        'username',
        'fauzi_perencanaan'
    );

    $userStatus = data_get(
        $user ?? null,
        'status',
        'active'
    );

    $jabatanID = data_get(
        $user ?? null,
        'jabatanID',
        'jbt00001'
    );

    $jabatanName = data_get(
        $user ?? null,
        'jabatan.jabatan_name',
        'Kepala Biro Perencanaan'
    );

    $jabatanType = data_get(
        $user ?? null,
        'jabatan.jabatan_type',
        'ADMINISTRASI'
    );

    $jabatanLevel = data_get(
        $user ?? null,
        'jabatan.jabatan_level',
        'ADMINISTRATOR'
    );

    $jabatanEselon = data_get(
        $user ?? null,
        'jabatan.eselon',
        'II'
    );

    $registeredDate = data_get(
        $user ?? null,
        'registered_date',
        '15 Jan 2020'
    );

    $lastLogin = data_get(
        $user ?? null,
        'last_login_display',
        'Hari ini, 08:45 WIB'
    );

    $initials = collect(explode(' ', $userName))
        ->filter()
        ->take(2)
        ->map(fn ($word) => strtoupper(substr($word, 0, 1)))
        ->implode('');
@endphp

<div class="app-shell">

    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="sidebar-brand-logo" aria-hidden="true"></div>

            <div class="sidebar-brand-text">
                <span class="sidebar-brand-government">
                    KEMENTERIAN PERDAGANGAN RI
                </span>
                <span class="sidebar-brand-name">
                    Biro Perencanaan
                </span>
            </div>
        </div>

        <div class="sidebar-body">
            <div class="sidebar-system-label">
                SISTEM INFORMASI
            </div>

            <div class="sidebar-system-title">
                Penelitian RKA-K/L
            </div>

            <div class="sidebar-nav-label">
                MENU UTAMA
            </div>

            <nav class="sidebar-nav" aria-label="Navigasi utama">
                <a href="#" class="sidebar-link">
                    <i class="bi bi-grid-1x2-fill"></i>
                    <span>Menu 1</span>
                </a>

                <a href="#" class="sidebar-link">
                    <i class="bi bi-folder2-open"></i>
                    <span>Menu 2</span>
                </a>

                <a href="#" class="sidebar-link">
                    <i class="bi bi-bar-chart-line-fill"></i>
                    <span>Menu 3</span>
                </a>

                <a
                    href="#"
                    class="sidebar-link active"
                    aria-current="page"
                >
                    <i class="bi bi-person-circle"></i>
                    <span>Account</span>
                    <span class="sidebar-link-badge">Aktif</span>
                </a>
            </nav>

            <div class="sidebar-footer">
                <div class="sidebar-user">
                    <div class="sidebar-avatar">
                        {{ $initials ?: 'US' }}
                    </div>

                    <div class="sidebar-user-copy">
                        <span class="sidebar-user-name">
                            {{ $userName }}
                        </span>
                        <span class="sidebar-user-role">
                            {{ $jabatanName }}
                        </span>
                    </div>
                </div>

                <button
                    type="button"
                    class="sidebar-logout"
                    data-modal-open="logoutModal"
                >
                    <i class="bi bi-box-arrow-right"></i>
                    Logout
                </button>
            </div>
        </div>
    </aside>

    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <div class="app-main">

        <header class="dashboard-header">
            <div class="header-left">
                <button
                    type="button"
                    class="sidebar-toggle"
                    id="sidebarToggle"
                    aria-label="Buka menu navigasi"
                    aria-expanded="false"
                >
                    <i class="bi bi-list"></i>
                </button>

                <div>
                    <div class="header-eyebrow">
                        SISTEM INFORMASI PENELITIAN RKA-K/L
                    </div>
                    <div class="header-title">
                        Pengaturan Akun
                    </div>
                </div>
            </div>

            <div class="header-user">
                <div class="header-user-text">
                    Biro Perencanaan
                    <strong id="headerUserName">
                        {{ $userName }}
                    </strong>
                </div>

                <div class="header-avatar" id="headerAvatar">
                    {{ $initials ?: 'US' }}
                </div>
            </div>
        </header>

        <main class="page-container">
<div class="page-heading-row">
        <div>
            <h1 class="page-title">
                Akun Pengguna
            </h1>

            <p class="page-description">
                Kelola informasi identitas, jabatan, dan kredensial akses Anda
                dalam Sistem Informasi Penelitian RKA-K/L.
            </p>
        </div>

        <div class="page-chip">
            <i class="bi bi-shield-check"></i>
            Profil dan keamanan akun
        </div>
    </div>

    @if (session('success'))
        <div class="account-alert account-alert-success">
            <i class="bi bi-check-circle-fill"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if (session('error'))
        <div class="account-alert account-alert-error">
            <i class="bi bi-exclamation-circle-fill"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    @if ($errors->any())
        <div class="account-alert account-alert-error">
            <i class="bi bi-exclamation-circle-fill"></i>
            <div>
                <strong>Periksa kembali data berikut:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <div class="account-layout">

        <!-- PROFIL -->

        <aside class="card profile-card">

            <div class="avatar-wrapper">
                <div class="profile-avatar" id="profileAvatar">
                    {{ $initials ?: 'US' }}
                </div>

                <span class="online-indicator"></span>
            </div>

            <h2 class="profile-name" id="profileName">
                {{ $userName }}
            </h2>

            <p class="profile-position" id="profilePosition">
                {{ $jabatanName }}
            </p>

            <div class="status-badge">
                <i class="bi bi-check-circle-fill"></i>

                Status:
                {{ $userStatus === 'active' ? 'Aktif' : ucfirst($userStatus) }}
            </div>

            <div class="profile-divider"></div>

            <div class="profile-meta">

                <div class="meta-row">
                    <span class="meta-label">
                        Terdaftar Sejak
                    </span>

                    <span class="meta-value">
                        {{ $registeredDate }}
                    </span>
                </div>

                <div class="meta-row">
                    <span class="meta-label">
                        Terakhir Login
                    </span>

                    <span class="meta-value">
                        {{ $lastLogin }}
                    </span>
                </div>

            </div>

        </aside>

        <!-- INFORMASI -->

        <section class="right-content">

            <div class="card information-card">

                <div class="section-heading">
                    <i class="bi bi-person-vcard"></i>
                    Informasi Personal & Kepegawaian
                </div>

                <div class="information-grid">

                    <div class="information-item">
                        <span class="information-label">
                            Nama Lengkap
                        </span>

                        <div class="information-box">
                            <i class="bi bi-person"></i>
                            <span id="displayName">
                                {{ $userName }}
                            </span>
                        </div>
                    </div>

                    <div class="information-item">
                        <span class="information-label">
                            NIP
                        </span>

                        <div class="information-box">
                            <i class="bi bi-credit-card-2-front"></i>
                            <span>{{ $userNip }}</span>
                        </div>
                    </div>

                    <div class="information-item">
                        <span class="information-label">
                            Alamat Email Kerja
                        </span>

                        <div class="information-box">
                            <i class="bi bi-envelope"></i>
                            <span id="displayEmail">
                                {{ $userEmail }}
                            </span>
                        </div>
                    </div>

                    <div class="information-item">
                        <span class="information-label">
                            Username Sistem
                        </span>

                        <div class="information-box">
                            <i class="bi bi-at"></i>
                            <span id="displayUsername">
                                {{ $username }}
                            </span>
                        </div>
                    </div>

                    <div class="information-item full-width">
                        <span class="information-label">
                            Nama Jabatan
                        </span>

                        <div class="information-box">
                            <i class="bi bi-briefcase"></i>
                            <span id="displayJabatanName">
                                {{ $jabatanName }}
                            </span>
                        </div>
                    </div>

                    <div class="information-item">
                        <span class="information-label">
                            Tipe Jabatan
                        </span>

                        <div class="information-box">
                            <i class="bi bi-diagram-3"></i>
                            <span id="displayJabatanType">
                                {{ str_replace('_', ' ', $jabatanType) }}
                            </span>
                        </div>
                    </div>

                    <div class="information-item">
                        <span class="information-label">
                            Level Jabatan
                        </span>

                        <div class="information-box">
                            <i class="bi bi-award"></i>
                            <span id="displayJabatanLevel">
                                {{ str_replace('_', ' ', $jabatanLevel) }}
                            </span>
                        </div>
                    </div>

                    <div class="information-item full-width">
                        <span class="information-label">
                            Eselon
                        </span>

                        <div class="information-box">
                            <i class="bi bi-building"></i>
                            <span id="displayEselon">
                                {{ str_replace('_', ' ', $jabatanEselon) }}
                            </span>
                        </div>
                    </div>

                </div>

            </div>

            <!-- ACTION -->

            <div class="card action-card">

                <div class="action-buttons">

                    <button
                        type="button"
                        class="button button-primary"
                        data-modal-open="editAccountModal"
                    >
                        <i class="bi bi-pencil-fill"></i>
                        Edit Akun
                    </button>

                    <button
                        type="button"
                        class="button button-danger"
                        data-modal-open="deleteAccountModal"
                    >
                        <i class="bi bi-trash3"></i>
                        Hapus Akun
                    </button>

                    <button
                        type="button"
                        class="button button-secondary button-logout"
                        data-modal-open="logoutModal"
                    >
                        <i class="bi bi-box-arrow-right"></i>
                        Logout
                    </button>

                </div>

            </div>

        </section>

    </div>
        </main>

<footer class="footer">

    <div class="footer-container">

        <div class="footer-brand">
            <strong>
                Kementerian Perdagangan Republik Indonesia
            </strong>

            © 2026 Biro Perencanaan. Seluruh Hak Cipta Dilindungi.
        </div>

        <div class="footer-links">
            <a href="#">Kebijakan Privasi</a>
            <a href="#">Bantuan</a>
            <a href="#">Kontak Kami</a>
        </div>

    </div>

</footer>

    </div>
</div>

<!-- ======================================================
     MODAL EDIT AKUN
====================================================== -->

<div class="modal-overlay" id="editAccountModal">

    <div class="edit-account-modal">

        <div class="edit-modal-header">

            <h3 class="modal-title">
                Edit Profil Pengguna
            </h3>

            <button
                type="button"
                class="modal-close"
                data-modal-close
                aria-label="Tutup"
            >
                <i class="bi bi-x-lg"></i>
            </button>

        </div>

        <form
            id="editAccountForm"
            action="{{ route('account.update') }}"
            method="POST"
            novalidate
        >
            @csrf
            @method('PUT')

            <div class="edit-modal-body">

                <div class="edit-form-grid">

                    <!-- NAMA -->

                    <div class="edit-form-group">
                        <label for="editName">
                            Nama Lengkap
                        </label>

                        <input
                            type="text"
                            id="editName"
                            name="name"
                            class="edit-form-control"
                            value="{{ old('name', $userName) }}"
                            required
                        >
                    </div>

                    <!-- NIP -->

                    <div class="edit-form-group">
                        <label for="editNip">
                            NIP
                            <span>(Readonly)</span>
                        </label>

                        <input
                            type="text"
                            id="editNip"
                            class="edit-form-control readonly"
                            value="{{ $userNip }}"
                            readonly
                        >
                    </div>

                    <!-- EMAIL -->

                    <div class="edit-form-group">
                        <label for="editEmail">
                            Alamat Email Kerja
                        </label>

                        <input
                            type="email"
                            id="editEmail"
                            name="email"
                            class="edit-form-control"
                            value="{{ old('email', $userEmail) }}"
                            required
                        >
                    </div>

                    <!-- USERNAME -->

                    <div class="edit-form-group">
                        <label for="editUsername">
                            Username Sistem
                        </label>

                        <input
                            type="text"
                            id="editUsername"
                            name="username"
                            class="edit-form-control"
                            value="{{ old('username', $username) }}"
                            required
                        >
                    </div>

                    <!-- JABATAN -->

                    <div class="edit-form-group full-width">
                        <label for="editJabatanID">
                            Nama Jabatan
                        </label>

                        <div class="edit-select-wrapper">

                            <select
                                id="editJabatanID"
                                name="jabatanID"
                                class="edit-form-control edit-select"
                                required
                            >
                                <option value="">
                                    Pilih nama jabatan
                                </option>

                                @isset($jabatans)

                                    @foreach($jabatans as $jabatan)

                                        <option
                                            value="{{ $jabatan->jabatanID }}"
                                            data-name="{{ $jabatan->jabatan_name }}"
                                            data-type="{{ $jabatan->jabatan_type }}"
                                            data-level="{{ $jabatan->jabatan_level }}"
                                            data-eselon="{{ $jabatan->eselon }}"
                                            {{ old('jabatanID', $jabatanID) == $jabatan->jabatanID ? 'selected' : '' }}
                                        >
                                            {{ $jabatan->jabatan_name }}
                                        </option>

                                    @endforeach

                                @else

                                    <!-- Data dummy frontend -->

                                    <option
                                        value="jbt00001"
                                        data-name="Kepala Biro Perencanaan"
                                        data-type="ADMINISTRASI"
                                        data-level="ADMINISTRATOR"
                                        data-eselon="II"
                                        selected
                                    >
                                        Kepala Biro Perencanaan
                                    </option>

                                    <option
                                        value="jbt00002"
                                        data-name="Perencana Ahli Madya"
                                        data-type="FUNGSIONAL"
                                        data-level="AHLI_MADYA"
                                        data-eselon="NON_ESELON"
                                    >
                                        Perencana Ahli Madya
                                    </option>

                                    <option
                                        value="jbt00003"
                                        data-name="Perencana Ahli Muda"
                                        data-type="FUNGSIONAL"
                                        data-level="AHLI_MUDA"
                                        data-eselon="NON_ESELON"
                                    >
                                        Perencana Ahli Muda
                                    </option>

                                    <option
                                        value="jbt00004"
                                        data-name="Perencana Ahli Pertama"
                                        data-type="FUNGSIONAL"
                                        data-level="AHLI_PERTAMA"
                                        data-eselon="NON_ESELON"
                                    >
                                        Perencana Ahli Pertama
                                    </option>

                                    <option
                                        value="jbt00005"
                                        data-name="Analis Perencanaan"
                                        data-type="PELAKSANA"
                                        data-level="PELAKSANA"
                                        data-eselon="NON_ESELON"
                                    >
                                        Analis Perencanaan
                                    </option>

                                @endisset

                            </select>

                            <i class="bi bi-chevron-down"></i>

                        </div>

                        <small class="edit-helper">
                            Tipe, level, dan eselon akan muncul otomatis
                            berdasarkan jabatan yang dipilih.
                        </small>
                    </div>

                    <!-- TIPE -->

                    <div class="edit-form-group">
                        <label for="editJabatanType">
                            Tipe Jabatan
                        </label>

                        <input
                            type="text"
                            id="editJabatanType"
                            class="edit-form-control readonly"
                            readonly
                        >
                    </div>

                    <!-- LEVEL -->

                    <div class="edit-form-group">
                        <label for="editJabatanLevel">
                            Level Jabatan
                        </label>

                        <input
                            type="text"
                            id="editJabatanLevel"
                            class="edit-form-control readonly"
                            readonly
                        >
                    </div>

                    <!-- ESELON -->

                    <div class="edit-form-group full-width">
                        <label for="editEselon">
                            Eselon
                        </label>

                        <input
                            type="text"
                            id="editEselon"
                            class="edit-form-control readonly"
                            readonly
                        >
                    </div>

                </div>

            </div>

            <div class="edit-modal-footer">

                <button
                    type="button"
                    class="button button-secondary"
                    data-modal-close
                >
                    Batal
                </button>

                <button
                    type="submit"
                    class="button button-primary"
                    id="saveAccountButton"
                >
                    <i class="bi bi-check-lg"></i>
                    Simpan Perubahan
                </button>

            </div>

        </form>

    </div>

</div>

<!-- ======================================================
     MODAL HAPUS AKUN
====================================================== -->

<div class="modal-overlay" id="deleteAccountModal">

    <div class="modal-box">

        <div class="warning-icon">
            <i class="bi bi-exclamation-triangle"></i>
        </div>

        <h3 class="modal-title">
            Hapus Akun?
        </h3>

        <p class="modal-description">
            Tindakan ini akan menghapus atau menonaktifkan akun Anda.
            Data yang telah dihapus tidak dapat dikembalikan.
        </p>

        <form
            action="{{ route('account.destroy') }}"
            method="POST"
            class="modal-actions"
            id="deleteAccountForm"
        >
            @csrf
            @method('DELETE')

            <button
                type="button"
                class="button button-secondary"
                data-modal-close
            >
                Batal
            </button>

            <button
                type="submit"
                class="button button-danger"
                id="confirmDeleteButton"
            >
                <i class="bi bi-trash3"></i>
                Ya, Hapus Akun
            </button>
        </form>

    </div>

</div>

<!-- ======================================================
     MODAL LOGOUT
====================================================== -->

<div class="modal-overlay" id="logoutModal">

    <div class="modal-box">

        <div class="warning-icon">
            <i class="bi bi-box-arrow-right"></i>
        </div>

        <h3 class="modal-title">
            Keluar dari Sistem?
        </h3>

        <p class="modal-description">
            Anda harus masuk kembali untuk mengakses Sistem Informasi
            Penelitian RKA-K/L.
        </p>

        <form
            action="{{ route('logout') }}"
            method="POST"
            class="modal-actions"
            id="logoutAccountForm"
        >
            @csrf

            <button
                type="button"
                class="button button-secondary"
                data-modal-close
            >
                Batal
            </button>

            <button
                type="submit"
                class="button button-primary"
                id="confirmLogoutButton"
            >
                <i class="bi bi-box-arrow-right"></i>
                Ya, Logout
            </button>
        </form>

    </div>

</div>

<script>
/*
    |--------------------------------------------------------------------------
    | MODAL
    |--------------------------------------------------------------------------
    */

    const modalOpenButtons =
        document.querySelectorAll("[data-modal-open]");

    const modalCloseButtons =
        document.querySelectorAll("[data-modal-close]");

    function openModal(modalId) {
        const modal = document.getElementById(modalId);

        if (!modal) {
            return;
        }

        modal.classList.add("show");
        document.body.style.overflow = "hidden";
    }

    function closeModal(modal) {
        if (!modal) {
            return;
        }

        modal.classList.remove("show");
        document.body.style.overflow = "";
    }

    modalOpenButtons.forEach(button => {
        button.addEventListener("click", () => {
            openModal(button.dataset.modalOpen);
        });
    });

    modalCloseButtons.forEach(button => {
        button.addEventListener("click", () => {
            closeModal(
                button.closest(".modal-overlay")
            );
        });
    });

    document
        .querySelectorAll(".modal-overlay")
        .forEach(modal => {
            modal.addEventListener("click", event => {
                if (event.target === modal) {
                    closeModal(modal);
                }
            });
        });

    document.addEventListener("keydown", event => {
        if (event.key !== "Escape") {
            return;
        }

        closeModal(
            document.querySelector(".modal-overlay.show")
        );
    });

    /*
    |--------------------------------------------------------------------------
    | DETAIL JABATAN OTOMATIS
    |--------------------------------------------------------------------------
    */

    const editJabatanSelect =
        document.getElementById("editJabatanID");

    const editJabatanType =
        document.getElementById("editJabatanType");

    const editJabatanLevel =
        document.getElementById("editJabatanLevel");

    const editEselon =
        document.getElementById("editEselon");

    function formatJabatanValue(value) {
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

    function updateEditJabatanDetail() {
        const selectedOption =
            editJabatanSelect.options[
                editJabatanSelect.selectedIndex
            ];

        if (!editJabatanSelect.value) {
            editJabatanType.value = "";
            editJabatanLevel.value = "";
            editEselon.value = "";

            return;
        }

        editJabatanType.value =
            formatJabatanValue(
                selectedOption.dataset.type
            );

        editJabatanLevel.value =
            formatJabatanValue(
                selectedOption.dataset.level
            );

        editEselon.value =
            formatJabatanValue(
                selectedOption.dataset.eselon
            );
    }

    editJabatanSelect.addEventListener(
        "change",
        updateEditJabatanDetail
    );

    /*
    |--------------------------------------------------------------------------
    | SUBMIT FORM KE BACKEND
    |--------------------------------------------------------------------------
    */

    const editAccountForm =
        document.getElementById("editAccountForm");

    const saveAccountButton =
        document.getElementById("saveAccountButton");

    editAccountForm.addEventListener("submit", event => {
        const editName =
            document.getElementById("editName");

        const editEmail =
            document.getElementById("editEmail");

        const editUsername =
            document.getElementById("editUsername");

        if (
            !editName.value.trim() ||
            !editEmail.value.trim() ||
            !editUsername.value.trim() ||
            !editJabatanSelect.value
        ) {
            event.preventDefault();
            alert("Lengkapi seluruh data yang wajib diisi.");
            return;
        }

        saveAccountButton.disabled = true;
        saveAccountButton.innerHTML = `
            <i class="bi bi-arrow-repeat"></i>
            Menyimpan...
        `;
    });

    const deleteAccountForm =
        document.getElementById("deleteAccountForm");

    const confirmDeleteButton =
        document.getElementById("confirmDeleteButton");

    deleteAccountForm.addEventListener("submit", () => {
        confirmDeleteButton.disabled = true;
        confirmDeleteButton.innerHTML = `
            <i class="bi bi-arrow-repeat"></i>
            Menghapus...
        `;
    });

    const logoutAccountForm =
        document.getElementById("logoutAccountForm");

    const confirmLogoutButton =
        document.getElementById("confirmLogoutButton");

    logoutAccountForm.addEventListener("submit", () => {
        confirmLogoutButton.disabled = true;
        confirmLogoutButton.innerHTML = `
            <i class="bi bi-arrow-repeat"></i>
            Keluar...
        `;
    });

    updateEditJabatanDetail();


    /*
    |--------------------------------------------------------------------------
    | SIDEBAR RESPONSIVE
    |--------------------------------------------------------------------------
    */

    const sidebar = document.getElementById("sidebar");
    const sidebarOverlay = document.getElementById("sidebarOverlay");
    const sidebarToggle = document.getElementById("sidebarToggle");

    function setSidebarState(isOpen) {
        sidebar.classList.toggle("open", isOpen);
        sidebarOverlay.classList.toggle("show", isOpen);
        sidebarToggle.setAttribute("aria-expanded", String(isOpen));
        document.body.classList.toggle("sidebar-open", isOpen);
    }

    sidebarToggle.addEventListener("click", () => {
        setSidebarState(!sidebar.classList.contains("open"));
    });

    sidebarOverlay.addEventListener("click", () => {
        setSidebarState(false);
    });

    document
        .querySelectorAll(".sidebar-link")
        .forEach(link => {
            link.addEventListener("click", () => {
                if (window.innerWidth <= 1024) {
                    setSidebarState(false);
                }
            });
        });

    window.addEventListener("resize", () => {
        if (window.innerWidth > 1024) {
            setSidebarState(false);
        }
    });

    @if ($errors->any())
        openModal("editAccountModal");
    @endif

</script>

</body>
</html>