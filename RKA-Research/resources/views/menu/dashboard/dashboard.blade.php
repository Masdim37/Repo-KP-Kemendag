<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard | Penelitian RKA-K/L</title>

    <style>
        :root {
            --primary: #0759b7;
            --primary-soft: #edf5ff;
            --primary-border: #cfe2f8;

            --text-primary: #18365b;
            --text-secondary: #607995;
            --text-muted: #879bb1;

            --background: #f5f8fc;
            --surface: #ffffff;
            --border: #dbe5ee;

            --success: #147348;
            --success-soft: #e9f8ef;

            --warning: #9a6a11;
            --warning-soft: #fff7df;

            --danger: #b42f40;
            --danger-soft: #fff0f2;

            --shadow: 0 10px 28px rgba(27, 70, 112, 0.07);
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
            min-height: 100vh;
            overflow-x: hidden;
            color: var(--text-primary);
            background: var(--background);
            font-family: Inter, "Segoe UI", Arial, sans-serif;
        }

        a,
        button {
            color: inherit;
            font: inherit;
        }

        a {
            text-decoration: none;
        }

        .app-shell {
            min-height: 100vh;
        }

        /* ================================================================
         * HEADER
         * ================================================================ */
        .dashboard-header {
            position: sticky;
            top: 0;
            z-index: 900;

            min-height: var(--header-height, 66px);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 18px;

            padding: 0 25px;
            border-bottom: 1px solid var(--border);

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

        .header-copy {
            min-width: 0;
        }

        .header-eyebrow {
            overflow: hidden;
            color: var(--text-muted);
            font-size: 7.5px;
            font-weight: 750;
            letter-spacing: 0.8px;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .header-title {
            margin-top: 3px;
            overflow: hidden;
            color: var(--text-primary);
            font-size: 14px;
            font-weight: 850;
            line-height: 1.3;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .header-user {
            flex-shrink: 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .header-user-text {
            text-align: right;
            color: var(--text-secondary);
            font-size: 8.5px;
            line-height: 1.4;
        }

        .header-user-text strong {
            display: block;
            max-width: 210px;
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
            background: linear-gradient(135deg, #063c7c, #1681d5);
            box-shadow: 0 5px 12px rgba(31, 91, 148, 0.16);

            font-size: 10px;
            font-weight: 850;
        }

        /* ================================================================
         * PAGE
         * ================================================================ */
        .page-container {
            width: 100%;
            min-height: 0;
            flex: 1;
            padding: 22px 25px 30px;
            background: var(--background);
        }

        .dashboard-content {
            width: 100%;
            max-width: 1440px;
            margin: 0 auto;
        }

        .dashboard-intro {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 17px;
        }

        .dashboard-intro h1 {
            color: var(--text-primary);
            font-size: 18px;
            font-weight: 900;
            line-height: 1.3;
        }

        .dashboard-intro p {
            max-width: 760px;
            margin-top: 5px;
            color: var(--text-secondary);
            font-size: 9px;
            line-height: 1.65;
        }

        .dashboard-meta {
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 7px;
        }

        .dashboard-updated {
            color: #8295aa;
            font-size: 8px;
            line-height: 1.5;
            text-align: right;
        }

        .dashboard-action {
            min-height: 33px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 7px;

            padding: 0 11px;
            border: 1px solid var(--primary-border);
            border-radius: 9px;

            color: var(--primary);
            background: #ffffff;

            font-size: 8px;
            font-weight: 800;
            transition:
                background .2s ease,
                border-color .2s ease,
                transform .2s ease;
        }

        .dashboard-action:hover {
            border-color: #a9caee;
            background: var(--primary-soft);
            transform: translateY(-1px);
        }

        /* ================================================================
         * SECTIONS / CARDS
         * ================================================================ */
        .section-block {
            margin-top: 18px;
        }

        .section-heading {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            margin-bottom: 10px;
        }

        .section-heading-left {
            min-width: 0;
        }

        .section-eyebrow {
            color: #8a9db1;
            font-size: 7px;
            font-weight: 850;
            letter-spacing: 0.8px;
            text-transform: uppercase;
        }

        .section-title {
            margin-top: 2px;
            color: var(--text-primary);
            font-size: 12px;
            font-weight: 900;
        }

        .section-note {
            color: var(--text-secondary);
            font-size: 8px;
            text-align: right;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 12px;
        }

        .stats-grid.reference {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }

        .stats-grid.budget {
            grid-template-columns: repeat(7, minmax(0, 1fr));
        }

        .stat-card {
            min-width: 0;
            min-height: 98px;
            display: flex;
            align-items: center;
            gap: 12px;

            padding: 15px;
            border: 1px solid var(--border);
            border-radius: 16px;

            background: var(--surface);
            box-shadow: var(--shadow);
        }

        .stat-card.compact {
            min-height: 88px;
            padding: 13px;
        }

        .stat-icon {
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

            font-size: 16px;
        }

        .stat-card.draft .stat-icon {
            color: var(--warning);
            border-color: #f0dfab;
            background: var(--warning-soft);
        }

        .stat-card.final .stat-icon {
            color: var(--success);
            border-color: #cde8d7;
            background: var(--success-soft);
        }

        .stat-copy {
            min-width: 0;
        }

        .stat-label {
            color: var(--text-secondary);
            font-size: 7.5px;
            font-weight: 800;
            line-height: 1.35;
        }

        .stat-value {
            margin-top: 4px;
            color: var(--text-primary);
            font-size: 23px;
            font-weight: 900;
            line-height: 1;
            font-variant-numeric: tabular-nums;
        }

        .stat-card.compact .stat-value {
            font-size: 20px;
        }

        .stat-helper {
            margin-top: 5px;
            overflow: hidden;
            color: #8a9db1;
            font-size: 7px;
            line-height: 1.35;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        /* ================================================================
         * ACTIVITY
         * ================================================================ */
        .activity-card {
            overflow: hidden;
            border: 1px solid var(--border);
            border-radius: 16px;
            background: var(--surface);
            box-shadow: var(--shadow);
        }

        .activity-head {
            display: grid;
            grid-template-columns: 155px 190px minmax(0, 1fr) 145px;
            gap: 14px;

            padding: 10px 15px;
            border-bottom: 1px solid var(--border);

            color: #71879f;
            background: #edf4fb;

            font-size: 7px;
            font-weight: 850;
            letter-spacing: .35px;
            text-transform: uppercase;
        }

        .activity-row {
            display: grid;
            grid-template-columns: 155px 190px minmax(0, 1fr) 145px;
            gap: 14px;
            align-items: start;

            padding: 12px 15px;
            border-bottom: 1px solid #e9eff5;
        }

        .activity-row:last-child {
            border-bottom: 0;
        }

        .activity-time {
            color: #71879f;
            font-size: 8px;
            line-height: 1.5;
            font-variant-numeric: tabular-nums;
        }

        .activity-action {
            min-width: 0;
        }

        .activity-badge {
            display: inline-flex;
            align-items: center;
            max-width: 100%;
            overflow: hidden;

            padding: 4px 7px;
            border-radius: 999px;

            color: #205f99;
            background: #edf5ff;

            font-size: 7px;
            font-weight: 850;
            line-height: 1;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .activity-badge.success {
            color: var(--success);
            background: var(--success-soft);
        }

        .activity-badge.warning {
            color: var(--warning);
            background: var(--warning-soft);
        }

        .activity-badge.danger {
            color: var(--danger);
            background: var(--danger-soft);
        }

        .activity-description {
            min-width: 0;
            color: var(--text-primary);
            font-size: 8.5px;
            line-height: 1.55;
        }

        .activity-description strong {
            display: block;
            margin-bottom: 2px;
            overflow: hidden;
            color: var(--text-primary);
            font-size: 8.5px;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .activity-description a:hover strong {
            color: var(--primary);
        }

        .activity-user {
            color: #71879f;
            font-size: 8px;
            line-height: 1.5;
            text-align: right;
            word-break: break-word;
        }

        .activity-empty {
            padding: 34px 20px;
            color: #8699ad;
            font-size: 9px;
            line-height: 1.6;
            text-align: center;
        }

        .activity-empty i {
            display: block;
            margin-bottom: 8px;
            color: #9db0c3;
            font-size: 22px;
        }

        /* ================================================================
         * FOOTER
         * ================================================================ */
        .footer {
            margin-top: auto;
            border-top: 1px solid var(--border);
            background: #eef3f8;
        }

        .footer-container {
            width: 100%;
            max-width: 1440px;
            min-height: 68px;

            display: flex;
            align-items: center;

            margin: 0 auto;
            padding: 15px 26px;
        }

        .footer-brand {
            color: #75889b;
            font-size: 7.5px;
            line-height: 1.6;
        }

        .footer-brand strong {
            display: block;
            color: #405974;
            font-size: 8.5px;
        }

        /* ================================================================
         * RESPONSIVE
         * ================================================================ */
        @media (max-width: 1180px) {
            .stats-grid.budget {
                grid-template-columns: repeat(4, minmax(0, 1fr));
            }

            .activity-head,
            .activity-row {
                grid-template-columns: 130px 165px minmax(0, 1fr);
            }

            .activity-head > :last-child,
            .activity-user {
                display: none;
            }
        }

        @media (max-width: 900px) {
            .stats-grid,
            .stats-grid.reference {
                grid-template-columns: 1fr;
            }

            .stats-grid.budget {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .dashboard-intro {
                align-items: flex-start;
                flex-direction: column;
            }

            .dashboard-meta {
                align-items: flex-start;
            }

            .dashboard-updated {
                text-align: left;
            }

            .activity-head {
                display: none;
            }

            .activity-row {
                grid-template-columns: 1fr;
                gap: 7px;
            }

            .activity-user {
                display: block;
                text-align: left;
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
                padding: 16px 14px 24px;
            }

            .stats-grid.budget {
                grid-template-columns: 1fr;
            }

            .section-heading {
                align-items: flex-start;
                flex-direction: column;
            }

            .section-note {
                text-align: left;
            }

            .footer-container {
                padding: 15px 14px;
            }
        }
    </style>
</head>

<body>
    @php
        /*
        |--------------------------------------------------------------------------
        | PRESENTATION HELPERS
        |--------------------------------------------------------------------------
        |
        | Semua query database sudah dilakukan oleh DashboardController.
        |
        */

        $userName = data_get($user, 'name', session('user_name', 'Pengguna Sistem'));

        $initials = collect(explode(' ', (string) $userName))
            ->filter()
            ->take(2)
            ->map(fn($word) => strtoupper(substr($word, 0, 1)))
            ->implode('');

        $penelitianStats = data_get($dashboardStats, 'penelitian', []);
        $referenceStats = data_get($dashboardStats, 'referensi', []);
        $budgetStats = data_get($dashboardStats, 'penganggaran', []);

        $activityLogs = collect($activityLogs ?? []);

        $number = fn($value) => number_format((int) ($value ?? 0), 0, ',', '.');

        $actionLabel = function ($action) {
            $action = trim((string) $action);

            return $action !== ''
                ? str_replace('_', ' ', $action)
                : 'AKTIVITAS';
        };

        $actionClass = function ($action) {
            $action = strtoupper((string) $action);

            if (str_contains($action, 'GAGAL')) {
                return 'danger';
            }

            if (
                str_contains($action, 'FINAL')
                || str_contains($action, 'BERHASIL')
                || str_contains($action, 'DICETAK')
            ) {
                return 'success';
            }

            if (
                str_contains($action, 'INVALID')
                || str_contains($action, 'DIUBAH')
            ) {
                return 'warning';
            }

            return '';
        };

        try {
            $generatedLabel = $generatedAt
                ? \Illuminate\Support\Carbon::parse($generatedAt)->format('d/m/Y H:i')
                : '-';
        } catch (\Throwable $e) {
            $generatedLabel = '-';
        }
    @endphp

    <div class="app-shell">

        {{-- Sidebar saat ini mengambil identitas user langsung dari Laravel Auth. --}}
        @include('partials.sidebar', [
            'activeMenu' => 'Dashboard',
        ])

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

                    <div class="header-copy">
                        <div class="header-eyebrow">
                            SISTEM INFORMASI PENELITIAN RKA-K/L
                        </div>

                        <div class="header-title">
                            Dashboard
                        </div>
                    </div>
                </div>

                <div class="header-user">
                    <div class="header-user-text">
                        Biro Perencanaan
                        <strong>{{ $userName }}</strong>
                    </div>

                    <div class="header-avatar">
                        {{ $initials ?: 'US' }}
                    </div>
                </div>
            </header>

            <main class="page-container">
                <div class="dashboard-content">

                    <div class="dashboard-intro">
                        <div>
                            <h1>Ringkasan Sistem Penelitian RKA-K/L</h1>

                            <p>
                                Menampilkan posisi workspace penelitian,
                                ketersediaan referensi organisasi, referensi
                                penganggaran, dan aktivitas penelitian terbaru.
                            </p>
                        </div>

                        <div class="dashboard-meta">
                            <div class="dashboard-updated">
                                Kondisi database per {{ $generatedLabel }}.
                            </div>

                            @if (\Illuminate\Support\Facades\Route::has('penelitian.index'))
                                <a
                                    href="{{ route('penelitian.index') }}"
                                    class="dashboard-action"
                                >
                                    <i class="bi bi-clipboard2-data"></i>
                                    Buka Workspace Penelitian
                                </a>
                            @endif
                        </div>
                    </div>

                    {{-- ======================================================
                         1. STATUS PENELITIAN
                    ======================================================= --}}
                    <section class="section-block">
                        <div class="section-heading">
                            <div class="section-heading-left">
                                <div class="section-eyebrow">Penelitian</div>
                                <div class="section-title">
                                    Status Workspace Penelitian
                                </div>
                            </div>

                            <div class="section-note">
                                DRAFT masih dapat diedit; FINAL sudah dikunci.
                            </div>
                        </div>

                        <div class="stats-grid">
                            <article class="stat-card">
                                <div class="stat-icon">
                                    <i class="bi bi-clipboard-data"></i>
                                </div>

                                <div class="stat-copy">
                                    <div class="stat-label">
                                        Total Penelitian
                                    </div>

                                    <div class="stat-value">
                                        {{ $number(data_get($penelitianStats, 'total')) }}
                                    </div>

                                    <div class="stat-helper">
                                        Seluruh workspace penelitian
                                    </div>
                                </div>
                            </article>

                            <article class="stat-card draft">
                                <div class="stat-icon">
                                    <i class="bi bi-pencil-square"></i>
                                </div>

                                <div class="stat-copy">
                                    <div class="stat-label">
                                        Status DRAFT
                                    </div>

                                    <div class="stat-value">
                                        {{ $number(data_get($penelitianStats, 'draft')) }}
                                    </div>

                                    <div class="stat-helper">
                                        Masih dapat diperbarui dan dijalankan ulang
                                    </div>
                                </div>
                            </article>

                            <article class="stat-card final">
                                <div class="stat-icon">
                                    <i class="bi bi-patch-check"></i>
                                </div>

                                <div class="stat-copy">
                                    <div class="stat-label">
                                        Status FINAL
                                    </div>

                                    <div class="stat-value">
                                        {{ $number(data_get($penelitianStats, 'final')) }}
                                    </div>

                                    <div class="stat-helper">
                                        Penelitian telah difinalisasi
                                    </div>
                                </div>
                            </article>
                        </div>
                    </section>

                    {{-- ======================================================
                         2. MASTER ORGANISASI
                    ======================================================= --}}
                    <section class="section-block">
                        <div class="section-heading">
                            <div class="section-heading-left">
                                <div class="section-eyebrow">Master Data</div>
                                <div class="section-title">
                                    Data Referensi Organisasi
                                </div>
                            </div>

                            <div class="section-note">
                                Jumlah record pada master referensi organisasi.
                            </div>
                        </div>

                        <div class="stats-grid reference">
                            <article class="stat-card compact">
                                <div class="stat-icon">
                                    <i class="bi bi-diagram-3"></i>
                                </div>

                                <div class="stat-copy">
                                    <div class="stat-label">
                                        Unit Eselon I
                                    </div>

                                    <div class="stat-value">
                                        {{ $number(data_get($referenceStats, 'unit_eselon_1')) }}
                                    </div>
                                </div>
                            </article>

                            <article class="stat-card compact">
                                <div class="stat-icon">
                                    <i class="bi bi-diagram-2"></i>
                                </div>

                                <div class="stat-copy">
                                    <div class="stat-label">
                                        Unit Eselon II
                                    </div>

                                    <div class="stat-value">
                                        {{ $number(data_get($referenceStats, 'unit_eselon_2')) }}
                                    </div>
                                </div>
                            </article>

                            <article class="stat-card compact">
                                <div class="stat-icon">
                                    <i class="bi bi-building"></i>
                                </div>

                                <div class="stat-copy">
                                    <div class="stat-label">
                                        Satuan Kerja
                                    </div>

                                    <div class="stat-value">
                                        {{ $number(data_get($referenceStats, 'satker')) }}
                                    </div>
                                </div>
                            </article>
                        </div>
                    </section>

                    {{-- ======================================================
                         3. REFERENSI PENGANGGARAN
                    ======================================================= --}}
                    <section class="section-block">
                        <div class="section-heading">
                            <div class="section-heading-left">
                                <div class="section-eyebrow">Master Data</div>
                                <div class="section-title">
                                    Data Referensi Penganggaran
                                </div>
                            </div>

                            <div class="section-note">
                                Jumlah record pada master referensi penganggaran.
                            </div>
                        </div>

                        <div class="stats-grid budget">
                            @foreach ([
                                ['key' => 'program', 'label' => 'Program', 'icon' => 'bi-folder'],
                                ['key' => 'kegiatan', 'label' => 'Kegiatan', 'icon' => 'bi-list-task'],
                                ['key' => 'kro', 'label' => 'KRO', 'icon' => 'bi-boxes'],
                                ['key' => 'ro', 'label' => 'RO', 'icon' => 'bi-box'],
                                ['key' => 'komponen', 'label' => 'Komponen', 'icon' => 'bi-layers'],
                                ['key' => 'subkomponen', 'label' => 'Subkomponen', 'icon' => 'bi-layers-half'],
                                ['key' => 'akun', 'label' => 'Akun', 'icon' => 'bi-calculator'],
                            ] as $item)
                                <article class="stat-card compact">
                                    <div class="stat-icon">
                                        <i class="bi {{ $item['icon'] }}"></i>
                                    </div>

                                    <div class="stat-copy">
                                        <div class="stat-label">
                                            {{ $item['label'] }}
                                        </div>

                                        <div class="stat-value">
                                            {{ $number(data_get($budgetStats, $item['key'])) }}
                                        </div>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    </section>

                    {{-- ======================================================
                         4. AKTIVITAS TERBARU
                    ======================================================= --}}
                    <section class="section-block">
                        <div class="section-heading">
                            <div class="section-heading-left">
                                <div class="section-eyebrow">Audit Trail</div>
                                <div class="section-title">
                                    Aktivitas Penelitian Terbaru
                                </div>
                            </div>

                            <div class="section-note">
                                Maksimal 10 aktivitas terbaru dari penelitian_log.
                            </div>
                        </div>

                        <div class="activity-card">
                            @if ($activityLogs->isEmpty())
                                <div class="activity-empty">
                                    <i class="bi bi-clock-history"></i>
                                    Belum terdapat log aktivitas penelitian.
                                </div>
                            @else
                                <div class="activity-head">
                                    <div>Waktu</div>
                                    <div>Aktivitas</div>
                                    <div>Penelitian / Keterangan</div>
                                    <div>Pengguna</div>
                                </div>

                                @foreach ($activityLogs as $log)
                                    @php
                                        try {
                                            $activityTime = $log->created_at
                                                ? \Illuminate\Support\Carbon::parse($log->created_at)->format('d/m/Y H:i')
                                                : '-';
                                        } catch (\Throwable $e) {
                                            $activityTime = (string) ($log->created_at ?: '-');
                                        }
                                    @endphp

                                    <div class="activity-row">
                                        <div class="activity-time">
                                            {{ $activityTime }}
                                        </div>

                                        <div class="activity-action">
                                            <span class="activity-badge {{ $actionClass($log->action ?? '') }}">
                                                {{ $actionLabel($log->action ?? '') }}
                                            </span>
                                        </div>

                                        <div class="activity-description">
                                            @if (
                                                !empty($log->penelitianID)
                                                && \Illuminate\Support\Facades\Route::has('penelitian.edit')
                                            )
                                                <a href="{{ route('penelitian.edit', $log->penelitianID) }}">
                                                    <strong>
                                                        {{ $log->nama_penelitian ?: 'Penelitian' }}
                                                    </strong>
                                                </a>
                                            @else
                                                <strong>
                                                    {{ $log->nama_penelitian ?: 'Penelitian' }}
                                                </strong>
                                            @endif

                                            {{ $log->description ?: '-' }}
                                        </div>

                                        <div class="activity-user">
                                            {{ $log->user_name ?: '-' }}
                                        </div>
                                    </div>
                                @endforeach
                            @endif
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
                </div>
            </footer>
        </div>
    </div>
</body>

</html>
