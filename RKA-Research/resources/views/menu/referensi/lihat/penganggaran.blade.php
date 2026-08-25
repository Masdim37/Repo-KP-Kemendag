<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Referensi Penganggaran | Penelitian RKA-K/L</title>

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    >

    <style>
        :root {
            --primary: #0759b7;
            --primary-dark: #06498f;
            --primary-soft: #eef5ff;

            --success: #159957;

            --text-primary: #18365b;
            --text-secondary: #607995;
            --text-muted: #8da0b4;

            --background: #f3f6fa;
            --surface: #ffffff;
            --border: #dbe5ee;
        }

        * {
            box-sizing: border-box;
        }

        html,
        body {
            min-height: 100%;
        }

        body {
            min-height: 100vh;
            margin: 0;
            overflow-x: hidden;
            color: var(--text-primary);
            background: var(--background);
            font-family: Inter, "Segoe UI", Arial, sans-serif;
        }

        button,
        input,
        select {
            font: inherit;
        }

        .app-shell {
            min-height: 100vh;
        }

        .app-main {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* HEADER */

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
            backdrop-filter: blur(12px);
        }

        .header-left {
            min-width: 0;
            display: flex;
            align-items: center;
            gap: 13px;
        }

        .sidebar-toggle {
            display: none;
            width: 36px;
            height: 36px;
            align-items: center;
            justify-content: center;
            border: 1px solid var(--border);
            border-radius: 9px;
            color: var(--primary);
            background: #ffffff;
            font-size: 18px;
            cursor: pointer;
        }

        .header-eyebrow {
            overflow: hidden;
            color: #879bb1;
            font-size: 7.5px;
            font-weight: 700;
            letter-spacing: .8px;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .header-title {
            margin-top: 3px;
            color: var(--text-primary);
            font-size: 14px;
            font-weight: 800;
        }

        .header-user {
            flex-shrink: 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .header-user-text {
            color: var(--text-secondary);
            font-size: 8.5px;
            line-height: 1.4;
            text-align: right;
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
            box-shadow: 0 5px 12px rgba(31, 91, 148, .16);
            font-size: 10px;
            font-weight: 800;
        }

        /* PAGE */

        .page-container {
            width: 100%;
            min-height: 0;
            flex: 1;
            padding: 24px;
        }

        .content-wrapper {
            width: 100%;
            max-width: 1240px;
            margin: 0 auto;
        }

        .page-heading {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 18px;
            margin-bottom: 16px;
        }

        .page-heading h1 {
            margin: 0;
            color: var(--text-primary);
            font-size: 18px;
            font-weight: 850;
        }

        .page-heading p {
            max-width: 760px;
            margin: 5px 0 0;
            color: var(--text-muted);
            font-size: 8.5px;
            line-height: 1.55;
        }

        .read-badge {
            flex-shrink: 0;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 10px;
            border: 1px solid #cde1f5;
            border-radius: 999px;
            color: #3f6e9d;
            background: #eef6ff;
            font-size: 7.5px;
            font-weight: 800;
        }

        /* SUMMARY */

        .summary-strip {
            display: grid;
            grid-template-columns: repeat(7, minmax(0, 1fr));
            gap: 8px;
            margin-bottom: 15px;
        }

        .summary-item {
            min-width: 0;
            padding: 11px 12px;
            border: 1px solid var(--border);
            border-radius: 10px;
            background: var(--surface);
            box-shadow: 0 4px 13px rgba(38, 68, 103, .035);
        }

        .summary-label {
            overflow: hidden;
            color: var(--text-muted);
            font-size: 6.8px;
            font-weight: 850;
            letter-spacing: .2px;
            text-overflow: ellipsis;
            text-transform: uppercase;
            white-space: nowrap;
        }

        .summary-value {
            margin-top: 3px;
            color: var(--text-primary);
            font-size: 14px;
            font-weight: 900;
        }

        /* CARD + TABS */

        .main-card {
            overflow: hidden;
            border: 1px solid var(--border);
            border-radius: 14px;
            background: var(--surface);
            box-shadow: 0 8px 25px rgba(38, 68, 103, .065);
        }

        .tabs {
            display: flex;
            gap: 4px;
            overflow-x: auto;
            padding: 12px 13px 0;
            border-bottom: 1px solid #e7edf3;
            background: #fbfdff;
        }

        .tab-link {
            position: relative;
            flex-shrink: 0;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            min-height: 39px;
            padding: 0 10px;
            border-radius: 8px 8px 0 0;
            color: #72869c;
            text-decoration: none;
            font-size: 7.6px;
            font-weight: 800;
            transition: .18s ease;
        }

        .tab-link:hover {
            color: var(--primary);
            background: #f3f8fd;
        }

        .tab-link.active {
            color: var(--primary);
            background: #ffffff;
        }

        .tab-link.active::after {
            content: "";
            position: absolute;
            right: 8px;
            bottom: -1px;
            left: 8px;
            height: 2px;
            border-radius: 10px;
            background: var(--primary);
        }

        .tab-count {
            min-width: 20px;
            height: 19px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0 5px;
            border-radius: 999px;
            color: #5c7590;
            background: #edf2f7;
            font-size: 6.4px;
        }

        .tab-link.active .tab-count {
            color: #ffffff;
            background: var(--primary);
        }

        /* FILTER */

        .filter-panel {
            padding: 13px 15px;
            border-bottom: 1px solid #edf1f5;
            background: #ffffff;
        }

        .filter-grid {
            display: grid;
            grid-template-columns: repeat(12, 1fr);
            gap: 8px;
            align-items: end;
        }

        .filter-group {
            grid-column: span 2;
            min-width: 0;
        }

        .filter-group.search {
            grid-column: span 3;
        }

        .filter-group.compact {
            grid-column: span 1;
        }

        .filter-actions {
            grid-column: span 2;
            display: flex;
            gap: 6px;
        }

        .filter-group label {
            display: block;
            margin-bottom: 5px;
            overflow: hidden;
            color: #53677e;
            font-size: 6.8px;
            font-weight: 800;
            letter-spacing: .18px;
            text-overflow: ellipsis;
            text-transform: uppercase;
            white-space: nowrap;
        }

        .form-control {
            width: 100%;
            height: 37px;
            padding: 0 9px;
            border: 1px solid #d5dee7;
            border-radius: 8px;
            outline: none;
            color: #304b69;
            background: #ffffff;
            font-size: 7.9px;
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(7, 89, 183, .07);
        }

        .form-control:disabled {
            color: #99a8b8;
            background: #f5f7f9;
            cursor: not-allowed;
        }

        .btn-filter,
        .btn-reset {
            min-width: 78px;
            height: 37px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
            padding: 0 10px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 7.4px;
            font-weight: 800;
            white-space: nowrap;
        }

        .btn-filter {
            border: 0;
            color: #ffffff;
            background: var(--primary);
            cursor: pointer;
        }

        .btn-reset {
            border: 1px solid #d5dee7;
            color: #607995;
            background: #ffffff;
        }

        /* TABLE */

        .table-toolbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 10px 15px;
            color: #7c8fa3;
            font-size: 7.6px;
            background: #fafcfe;
        }

        .table-toolbar strong {
            color: #365f88;
        }

        .table-title {
            color: #385b81;
            font-size: 8px;
            font-weight: 800;
        }

        .table-wrap {
            width: 100%;
            overflow-x: auto;
        }

        .reference-table {
            width: 100%;
            min-width: 820px;
            border-collapse: collapse;
        }

        .reference-table.wide {
            min-width: 1180px;
        }

        .reference-table.extra-wide {
            min-width: 1450px;
        }

        .reference-table th {
            padding: 9px 10px;
            border-top: 1px solid #e7edf3;
            border-bottom: 1px solid #dce5ee;
            color: #61778e;
            background: #f6f9fc;
            font-size: 6.7px;
            font-weight: 900;
            letter-spacing: .2px;
            text-align: left;
            text-transform: uppercase;
            white-space: nowrap;
        }

        .reference-table td {
            padding: 10px;
            border-bottom: 1px solid #edf1f5;
            color: #415b77;
            font-size: 7.8px;
            line-height: 1.45;
            vertical-align: top;
        }

        .reference-table tbody tr:hover {
            background: #fbfdff;
        }

        .code-cell {
            color: #125fa8 !important;
            font-family: Consolas, "Courier New", monospace;
            font-weight: 800;
            white-space: nowrap;
        }

        .name-cell {
            color: #294d73;
            font-weight: 750;
        }

        .subtext {
            display: block;
            margin-top: 2px;
            color: #8598ab;
            font-size: 6.8px;
            font-weight: 500;
        }

        .count-pill,
        .type-pill {
            min-width: 30px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 4px 7px;
            border-radius: 999px;
            font-size: 6.8px;
            font-weight: 850;
        }

        .count-pill {
            color: #316b9d;
            background: #edf6ff;
        }

        .type-pill.u {
            color: #15633e;
            background: #eaf8f1;
        }

        .type-pill.p {
            color: #8a6514;
            background: #fff6db;
        }

        .empty-state {
            padding: 34px 20px !important;
            color: #8a9caf !important;
            text-align: center;
        }

        .empty-state i {
            display: block;
            margin-bottom: 8px;
            color: #b7c4d1;
            font-size: 24px;
        }

        /* PAGINATION */

        .pagination-wrap {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 13px 15px;
            border-top: 1px solid #e7edf3;
        }

        .pagination-info {
            color: #7c8fa3;
            font-size: 7.5px;
        }

        .pagination {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .page-link,
        .page-current,
        .page-disabled {
            min-width: 29px;
            height: 29px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0 7px;
            border-radius: 7px;
            font-size: 7.4px;
            font-weight: 800;
            text-decoration: none;
        }

        .page-link {
            border: 1px solid #dbe5ee;
            color: #456985;
            background: #ffffff;
        }

        .page-current {
            border: 1px solid var(--primary);
            color: #ffffff;
            background: var(--primary);
        }

        .page-disabled {
            border: 1px solid #e5ebf1;
            color: #b1bdc9;
            background: #f7f9fb;
        }

        /* FOOTER */

        .footer {
            margin-top: auto;
            border-top: 1px solid var(--border);
            background: #eef3f8;
        }

        .footer-container {
            width: 100%;
            max-width: 1240px;
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

        @media (max-width: 1050px) {
            .summary-strip {
                grid-template-columns: repeat(4, 1fr);
            }

            .filter-group,
            .filter-group.search,
            .filter-group.compact {
                grid-column: span 4;
            }

            .filter-actions {
                grid-column: span 4;
            }
        }

        @media (max-width: 680px) {
            .dashboard-header {
                padding: 0 14px;
            }

            .sidebar-toggle {
                display: flex;
            }

            .header-user-text {
                display: none;
            }

            .page-container {
                padding: 14px;
            }

            .page-heading {
                align-items: flex-start;
                flex-direction: column;
            }

            .summary-strip {
                grid-template-columns: repeat(2, 1fr);
            }

            .filter-grid {
                display: block;
            }

            .filter-group {
                margin-bottom: 8px;
            }

            .filter-actions {
                margin-top: 8px;
            }

            .filter-actions > * {
                flex: 1;
            }

            .pagination-wrap {
                align-items: flex-start;
                flex-direction: column;
            }
        }
    </style>
</head>

<body>
@php
    $userName = data_get(
        $user ?? null,
        'name',
        session('user_name', 'Pengguna Sistem')
    );

    $jabatanName = data_get(
        $user ?? null,
        'jabatan.jabatan_name',
        session('jabatan_name', 'Pengguna Sistem')
    );

    $initials = collect(explode(' ', $userName))
        ->filter()
        ->take(2)
        ->map(fn ($word) => strtoupper(substr($word, 0, 1)))
        ->implode('');

    $tabs = [
        'program' => [
            'label' => 'Program',
            'icon' => 'bi-folder2-open',
            'count' => $stats['program'] ?? 0,
        ],
        'kegiatan' => [
            'label' => 'Kegiatan',
            'icon' => 'bi-list-task',
            'count' => $stats['kegiatan'] ?? 0,
        ],
        'kro' => [
            'label' => 'KRO',
            'icon' => 'bi-boxes',
            'count' => $stats['kro'] ?? 0,
        ],
        'ro' => [
            'label' => 'RO',
            'icon' => 'bi-box',
            'count' => $stats['ro'] ?? 0,
        ],
        'komponen' => [
            'label' => 'Komponen',
            'icon' => 'bi-diagram-3',
            'count' => $stats['komponen'] ?? 0,
        ],
        'subkomponen' => [
            'label' => 'Subkomponen',
            'icon' => 'bi-diagram-2',
            'count' => $stats['subkomponen'] ?? 0,
        ],
        'akun' => [
            'label' => 'Akun',
            'icon' => 'bi-journal-text',
            'count' => $stats['akun'] ?? 0,
        ],
    ];

    $currentTitle = $tabs[$jenis]['label'] ?? 'Program';

    $showProgramFilter = in_array(
        $jenis,
        ['kegiatan', 'kro', 'ro', 'komponen', 'subkomponen'],
        true
    );

    $showKegiatanFilter = in_array(
        $jenis,
        ['kro', 'ro', 'komponen', 'subkomponen'],
        true
    );

    $showKroFilter = in_array(
        $jenis,
        ['ro', 'komponen', 'subkomponen'],
        true
    );

    $showRoFilter = in_array(
        $jenis,
        ['komponen', 'subkomponen'],
        true
    );
@endphp

<div class="app-shell">
    @include('partials.sidebar', [
        'activeMenu' => 'view-reference-budget',
        'sidebarUserName' => $userName,
        'sidebarUserRole' => $jabatanName,
        'sidebarInitials' => $initials,
    ])

    <div class="app-main">

        <header class="dashboard-header">
            <div class="header-left">
                <button
                    type="button"
                    class="sidebar-toggle"
                    id="sidebarToggle"
                    aria-label="Buka menu navigasi"
                >
                    <i class="bi bi-list"></i>
                </button>

                <div>
                    <div class="header-eyebrow">
                        SISTEM INFORMASI PENELITIAN RKA-K/L
                    </div>
                    <div class="header-title">
                        Lihat Data Referensi
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
            <div class="content-wrapper">

                <div class="page-heading">
                    <div>
                        <h1>Data Referensi Penganggaran</h1>
                        <p>
                            Lihat dan telusuri hierarki Program, Kegiatan, KRO,
                            RO, Komponen, Subkomponen, serta master Akun yang
                            digunakan dalam referensi penganggaran.
                        </p>
                    </div>

                    <div class="read-badge">
                        <i class="bi bi-eye"></i>
                        READ ONLY
                    </div>
                </div>

                <div class="summary-strip">
                    @foreach ($tabs as $tabKey => $tab)
                        <div class="summary-item">
                            <div class="summary-label">
                                {{ $tab['label'] }}
                            </div>
                            <div class="summary-value">
                                {{ number_format($tab['count'], 0, ',', '.') }}
                            </div>
                        </div>
                    @endforeach
                </div>

                <section class="main-card">

                    <nav class="tabs" aria-label="Jenis data referensi penganggaran">
                        @foreach ($tabs as $tabKey => $tab)
                            <a
                                href="{{ route('referensi.lihat.penganggaran', ['jenis' => $tabKey]) }}"
                                class="tab-link {{ $jenis === $tabKey ? 'active' : '' }}"
                                @if ($jenis === $tabKey) aria-current="page" @endif
                            >
                                <i class="bi {{ $tab['icon'] }}"></i>
                                <span>{{ $tab['label'] }}</span>
                                <span class="tab-count">
                                    {{ number_format($tab['count'], 0, ',', '.') }}
                                </span>
                            </a>
                        @endforeach
                    </nav>

                    <form
                        method="GET"
                        action="{{ route('referensi.lihat.penganggaran') }}"
                        class="filter-panel"
                    >
                        <input type="hidden" name="jenis" value="{{ $jenis }}">

                        <div class="filter-grid">

                            <div class="filter-group search">
                                <label for="q">Pencarian</label>
                                <input
                                    type="text"
                                    id="q"
                                    name="q"
                                    class="form-control"
                                    value="{{ $filters['q'] ?? '' }}"
                                    placeholder="Cari kode atau nama {{ $currentTitle }}..."
                                >
                            </div>

                            @if ($jenis === 'subkomponen')
                                <div class="filter-group">
                                    <label for="satkerFilter">Satker</label>
                                    <select
                                        id="satkerFilter"
                                        name="kode_satker"
                                        class="form-control"
                                    >
                                        <option value="">-- Semua Satker --</option>

                                        @foreach ($satkerOptions as $item)
                                            <option
                                                value="{{ $item->kode_satker }}"
                                                @selected(
                                                    ($filters['kode_satker'] ?? '') ===
                                                    $item->kode_satker
                                                )
                                            >
                                                [{{ $item->kode_satker }}]
                                                {{ $item->nama_satker }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif

                            @if ($showProgramFilter)
                                <div class="filter-group">
                                    <label for="programFilter">Program</label>
                                    <select
                                        id="programFilter"
                                        name="kode_program"
                                        class="form-control"
                                    >
                                        <option value="">-- Semua Program --</option>

                                        @foreach ($programOptions as $item)
                                            <option
                                                value="{{ $item->kode_program }}"
                                                @selected(
                                                    ($filters['kode_program'] ?? '') ===
                                                    $item->kode_program
                                                )
                                            >
                                                [{{ $item->kode_program }}]
                                                {{ $item->nama_program }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif

                            @if ($showKegiatanFilter)
                                <div class="filter-group">
                                    <label for="kegiatanFilter">Kegiatan</label>
                                    <select
                                        id="kegiatanFilter"
                                        name="kode_kegiatan"
                                        class="form-control"
                                    >
                                        <option value="">-- Semua Kegiatan --</option>
                                    </select>
                                </div>
                            @endif

                            @if ($showKroFilter)
                                <div class="filter-group">
                                    <label for="kroFilter">KRO</label>
                                    <select
                                        id="kroFilter"
                                        name="kode_kro"
                                        class="form-control"
                                    >
                                        <option value="">-- Semua KRO --</option>
                                    </select>
                                </div>
                            @endif

                            @if ($showRoFilter)
                                <div class="filter-group">
                                    <label for="roFilter">RO</label>
                                    <select
                                        id="roFilter"
                                        name="kode_ro"
                                        class="form-control"
                                    >
                                        <option value="">-- Semua RO --</option>
                                    </select>
                                </div>
                            @endif

                            @if ($jenis === 'subkomponen')
                                <div class="filter-group">
                                    <label for="komponenFilter">Komponen</label>
                                    <select
                                        id="komponenFilter"
                                        name="kode_komponen"
                                        class="form-control"
                                    >
                                        <option value="">-- Semua Komponen --</option>
                                    </select>
                                </div>
                            @endif

                            @if ($jenis === 'komponen')
                                <div class="filter-group compact">
                                    <label for="jenisKomponenFilter">Jenis</label>
                                    <select
                                        id="jenisKomponenFilter"
                                        name="jenis_komponen"
                                        class="form-control"
                                    >
                                        <option value="">Semua</option>
                                        <option
                                            value="U"
                                            @selected(($filters['jenis_komponen'] ?? '') === 'U')
                                        >
                                            U
                                        </option>
                                        <option
                                            value="P"
                                            @selected(($filters['jenis_komponen'] ?? '') === 'P')
                                        >
                                            P
                                        </option>
                                    </select>
                                </div>
                            @endif

                            <div class="filter-group compact">
                                <label for="perPage">Per Hal.</label>
                                <select
                                    id="perPage"
                                    name="per_page"
                                    class="form-control"
                                >
                                    @foreach ([10, 20, 50, 100] as $size)
                                        <option
                                            value="{{ $size }}"
                                            @selected(
                                                ($filters['per_page'] ?? 20) == $size
                                            )
                                        >
                                            {{ $size }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="filter-actions">
                                <button type="submit" class="btn-filter">
                                    <i class="bi bi-search"></i>
                                    Tampilkan
                                </button>

                                <a
                                    href="{{ route('referensi.lihat.penganggaran', ['jenis' => $jenis]) }}"
                                    class="btn-reset"
                                >
                                    <i class="bi bi-arrow-counterclockwise"></i>
                                    Reset
                                </a>
                            </div>

                        </div>
                    </form>

                    <div class="table-toolbar">
                        <div class="table-title">
                            Daftar {{ $currentTitle }}
                        </div>

                        <div>
                            Ditemukan
                            <strong>
                                {{ number_format($records->total(), 0, ',', '.') }}
                            </strong>
                            data
                        </div>
                    </div>

                    <div class="table-wrap">

                        @if ($jenis === 'program')
                            <table class="reference-table">
                                <thead>
                                    <tr>
                                        <th style="width:55px;">No.</th>
                                        <th style="width:130px;">Kode Program</th>
                                        <th>Nama Program</th>
                                        <th style="width:110px;">Kegiatan</th>
                                        <th style="width:100px;">Satker</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($records as $record)
                                        <tr>
                                            <td>{{ $records->firstItem() + $loop->index }}</td>
                                            <td class="code-cell">{{ $record->kode_program }}</td>
                                            <td class="name-cell">{{ $record->nama_program }}</td>
                                            <td><span class="count-pill">{{ $record->jumlah_kegiatan }}</span></td>
                                            <td><span class="count-pill">{{ $record->jumlah_satker }}</span></td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="empty-state">
                                                <i class="bi bi-database-x"></i>
                                                Data Program tidak ditemukan.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>

                        @elseif ($jenis === 'kegiatan')
                            <table class="reference-table">
                                <thead>
                                    <tr>
                                        <th style="width:55px;">No.</th>
                                        <th style="width:120px;">Kode Kegiatan</th>
                                        <th>Nama Kegiatan</th>
                                        <th style="width:110px;">Program</th>
                                        <th>Nama Program</th>
                                        <th style="width:90px;">Satker</th>
                                        <th style="width:80px;">KRO</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($records as $record)
                                        <tr>
                                            <td>{{ $records->firstItem() + $loop->index }}</td>
                                            <td class="code-cell">{{ $record->kode_kegiatan }}</td>
                                            <td class="name-cell">{{ $record->nama_kegiatan }}</td>
                                            <td class="code-cell">{{ $record->kode_program }}</td>
                                            <td>{{ $record->nama_program }}</td>
                                            <td><span class="count-pill">{{ $record->jumlah_satker }}</span></td>
                                            <td><span class="count-pill">{{ $record->jumlah_kro }}</span></td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="empty-state">
                                                <i class="bi bi-database-x"></i>
                                                Data Kegiatan tidak ditemukan.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>

                        @elseif ($jenis === 'kro')
                            <table class="reference-table">
                                <thead>
                                    <tr>
                                        <th style="width:55px;">No.</th>
                                        <th style="width:110px;">Kode KRO</th>
                                        <th>Nama KRO</th>
                                        <th style="width:110px;">Kegiatan</th>
                                        <th style="width:90px;">RO</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($records as $record)
                                        <tr>
                                            <td>{{ $records->firstItem() + $loop->index }}</td>
                                            <td class="code-cell">{{ $record->kode_kro }}</td>
                                            <td class="name-cell">{{ $record->nama_kro }}</td>
                                            <td><span class="count-pill">{{ $record->jumlah_kegiatan }}</span></td>
                                            <td><span class="count-pill">{{ $record->jumlah_ro }}</span></td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="empty-state">
                                                <i class="bi bi-database-x"></i>
                                                Data KRO tidak ditemukan.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>

                        @elseif ($jenis === 'ro')
                            <table class="reference-table wide">
                                <thead>
                                    <tr>
                                        <th style="width:50px;">No.</th>
                                        <th style="width:100px;">Kode RO</th>
                                        <th>Nama RO</th>
                                        <th style="width:110px;">Kegiatan</th>
                                        <th>Nama Kegiatan</th>
                                        <th style="width:90px;">KRO</th>
                                        <th>Nama KRO</th>
                                        <th style="width:90px;">Program</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($records as $record)
                                        <tr>
                                            <td>{{ $records->firstItem() + $loop->index }}</td>
                                            <td class="code-cell">{{ $record->kode_ro }}</td>
                                            <td class="name-cell">{{ $record->nama_ro }}</td>
                                            <td class="code-cell">{{ $record->kode_kegiatan }}</td>
                                            <td>{{ $record->nama_kegiatan }}</td>
                                            <td class="code-cell">{{ $record->kode_kro }}</td>
                                            <td>{{ $record->nama_kro }}</td>
                                            <td class="code-cell">{{ $record->kode_program }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="empty-state">
                                                <i class="bi bi-database-x"></i>
                                                Data RO tidak ditemukan.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>

                        @elseif ($jenis === 'komponen')
                            <table class="reference-table extra-wide">
                                <thead>
                                    <tr>
                                        <th style="width:50px;">No.</th>
                                        <th style="width:105px;">Komponen</th>
                                        <th>Nama Komponen</th>
                                        <th style="width:70px;">Jenis</th>
                                        <th style="width:95px;">RO</th>
                                        <th>Nama RO</th>
                                        <th style="width:90px;">KRO</th>
                                        <th style="width:105px;">Kegiatan</th>
                                        <th>Nama Kegiatan</th>
                                        <th style="width:90px;">Program</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($records as $record)
                                        <tr>
                                            <td>{{ $records->firstItem() + $loop->index }}</td>
                                            <td class="code-cell">{{ $record->kode_komponen }}</td>
                                            <td class="name-cell">{{ $record->nama_komponen }}</td>
                                            <td>
                                                <span class="type-pill {{ strtolower($record->jenis_komponen) }}">
                                                    {{ $record->jenis_komponen }}
                                                </span>
                                            </td>
                                            <td class="code-cell">{{ $record->kode_ro }}</td>
                                            <td>{{ $record->nama_ro }}</td>
                                            <td class="code-cell">{{ $record->kode_kro }}</td>
                                            <td class="code-cell">{{ $record->kode_kegiatan }}</td>
                                            <td>{{ $record->nama_kegiatan }}</td>
                                            <td class="code-cell">{{ $record->kode_program }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="10" class="empty-state">
                                                <i class="bi bi-database-x"></i>
                                                Data Komponen tidak ditemukan.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>

                        @elseif ($jenis === 'subkomponen')
                            <table class="reference-table extra-wide">
                                <thead>
                                    <tr>
                                        <th style="width:50px;">No.</th>
                                        <th style="width:100px;">Satker</th>
                                        <th>Nama Satker</th>
                                        <th style="width:90px;">Subkomp.</th>
                                        <th>Nama Subkomponen</th>
                                        <th style="width:90px;">Komponen</th>
                                        <th style="width:85px;">RO</th>
                                        <th style="width:80px;">KRO</th>
                                        <th style="width:100px;">Kegiatan</th>
                                        <th style="width:80px;">Akun</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($records as $record)
                                        <tr>
                                            <td>{{ $records->firstItem() + $loop->index }}</td>
                                            <td class="code-cell">{{ $record->kode_satker }}</td>
                                            <td>{{ $record->nama_satker }}</td>
                                            <td class="code-cell">{{ $record->kode_subkomponen }}</td>
                                            <td class="name-cell">
                                                {{ $record->nama_subkomponen }}
                                                @if (!empty($record->deskripsi))
                                                    <span class="subtext">
                                                        {{ \Illuminate\Support\Str::limit($record->deskripsi, 90) }}
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="code-cell">
                                                {{ $record->kode_komponen }}
                                                <span class="subtext">{{ $record->nama_komponen }}</span>
                                            </td>
                                            <td class="code-cell">
                                                {{ $record->kode_ro }}
                                                <span class="subtext">{{ $record->nama_ro }}</span>
                                            </td>
                                            <td class="code-cell">{{ $record->kode_kro }}</td>
                                            <td class="code-cell">
                                                {{ $record->kode_kegiatan }}
                                                <span class="subtext">{{ $record->nama_kegiatan }}</span>
                                            </td>
                                            <td>
                                                <span class="count-pill">{{ $record->jumlah_akun }}</span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="10" class="empty-state">
                                                <i class="bi bi-database-x"></i>
                                                Data Subkomponen tidak ditemukan.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>

                        @else
                            <table class="reference-table">
                                <thead>
                                    <tr>
                                        <th style="width:55px;">No.</th>
                                        <th style="width:115px;">Kode Akun</th>
                                        <th>Nama Akun</th>
                                        <th style="width:150px;">Mapping Komponen</th>
                                        <th style="width:175px;">Mapping Subkomponen</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($records as $record)
                                        <tr>
                                            <td>{{ $records->firstItem() + $loop->index }}</td>
                                            <td class="code-cell">{{ $record->kode_akun }}</td>
                                            <td class="name-cell">{{ $record->nama_akun }}</td>
                                            <td><span class="count-pill">{{ $record->jumlah_komponen }}</span></td>
                                            <td><span class="count-pill">{{ $record->jumlah_subkomponen }}</span></td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="empty-state">
                                                <i class="bi bi-database-x"></i>
                                                Data Akun tidak ditemukan.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        @endif

                    </div>

                    @if ($records->hasPages())
                        <div class="pagination-wrap">
                            <div class="pagination-info">
                                Menampilkan
                                <strong>{{ $records->firstItem() }}</strong>
                                -
                                <strong>{{ $records->lastItem() }}</strong>
                                dari
                                <strong>{{ $records->total() }}</strong>
                                data.
                            </div>

                            <nav class="pagination" aria-label="Navigasi halaman">
                                @if ($records->onFirstPage())
                                    <span class="page-disabled">
                                        <i class="bi bi-chevron-left"></i>
                                    </span>
                                @else
                                    <a
                                        class="page-link"
                                        href="{{ $records->previousPageUrl() }}"
                                    >
                                        <i class="bi bi-chevron-left"></i>
                                    </a>
                                @endif

                                @php
                                    $startPage = max(
                                        1,
                                        $records->currentPage() - 2
                                    );

                                    $endPage = min(
                                        $records->lastPage(),
                                        $records->currentPage() + 2
                                    );
                                @endphp

                                @if ($startPage > 1)
                                    <a class="page-link" href="{{ $records->url(1) }}">
                                        1
                                    </a>

                                    @if ($startPage > 2)
                                        <span class="page-disabled">…</span>
                                    @endif
                                @endif

                                @for (
                                    $page = $startPage;
                                    $page <= $endPage;
                                    $page++
                                )
                                    @if ($page === $records->currentPage())
                                        <span class="page-current">
                                            {{ $page }}
                                        </span>
                                    @else
                                        <a
                                            class="page-link"
                                            href="{{ $records->url($page) }}"
                                        >
                                            {{ $page }}
                                        </a>
                                    @endif
                                @endfor

                                @if ($endPage < $records->lastPage())
                                    @if ($endPage < $records->lastPage() - 1)
                                        <span class="page-disabled">…</span>
                                    @endif

                                    <a
                                        class="page-link"
                                        href="{{ $records->url($records->lastPage()) }}"
                                    >
                                        {{ $records->lastPage() }}
                                    </a>
                                @endif

                                @if ($records->hasMorePages())
                                    <a
                                        class="page-link"
                                        href="{{ $records->nextPageUrl() }}"
                                    >
                                        <i class="bi bi-chevron-right"></i>
                                    </a>
                                @else
                                    <span class="page-disabled">
                                        <i class="bi bi-chevron-right"></i>
                                    </span>
                                @endif
                            </nav>
                        </div>
                    @endif

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

<script>
    /*
    |--------------------------------------------------------------------------
    | CASCADING FILTER PENUH
    |--------------------------------------------------------------------------
    |
    | Setiap child filter selalu menghormati seluruh parent yang sudah dipilih.
    |
    | Contoh tab Komponen:
    | Program EE
    |   -> Kegiatan hanya Program EE
    |   -> KRO hanya KRO dari Kegiatan Program EE
    |   -> RO hanya RO dalam Program EE
    |
    | Jika Kegiatan kemudian dipilih, ruang lingkup KRO/RO/Komponen
    | dipersempit lagi ke Kegiatan tersebut, dan seterusnya.
    |
    | Khusus Subkomponen, Satker juga menjadi parent pembatas melalui
    | satker_kegiatan.
    |
    */

    const programData = @json($programOptions ?? []);
    const kegiatanData = @json($kegiatanOptions ?? []);
    const kroData = @json($kroOptions ?? []);
    const roData = @json($roOptions ?? []);
    const komponenData = @json($komponenOptions ?? []);
    const satkerKegiatanData = @json($satkerKegiatanOptions ?? []);

    const oldFilter = {
        program: @json($filters['kode_program'] ?? ''),
        kegiatan: @json($filters['kode_kegiatan'] ?? ''),
        kro: @json($filters['kode_kro'] ?? ''),
        ro: @json($filters['kode_ro'] ?? ''),
        komponen: @json($filters['kode_komponen'] ?? ''),
        satker: @json($filters['kode_satker'] ?? ''),
    };

    const programFilter = document.getElementById('programFilter');
    const kegiatanFilter = document.getElementById('kegiatanFilter');
    const kroFilter = document.getElementById('kroFilter');
    const roFilter = document.getElementById('roFilter');
    const komponenFilter = document.getElementById('komponenFilter');
    const satkerFilter = document.getElementById('satkerFilter');

    function code(value) {
        return value === null || value === undefined
            ? ''
            : String(value).trim();
    }

    function uniqueBy(items, key) {
        const seen = new Set();

        return items.filter(item => {
            const value = code(item?.[key]);

            if (!value || seen.has(value)) {
                return false;
            }

            seen.add(value);
            return true;
        });
    }

    function renderSelect(
        element,
        items,
        valueKey,
        labelKey,
        placeholder,
        selectedValue = ''
    ) {
        if (!element) {
            return;
        }

        element.innerHTML = '';

        const first = document.createElement('option');
        first.value = '';
        first.textContent = placeholder;
        element.appendChild(first);

        items.forEach(item => {
            const value = code(item?.[valueKey]);
            const label = code(item?.[labelKey]);

            if (!value) {
                return;
            }

            const option = document.createElement('option');
            option.value = value;
            option.textContent = label
                ? `[${value}] ${label}`
                : `[${value}]`;

            element.appendChild(option);
        });

        element.disabled = items.length === 0;

        if (
            selectedValue &&
            items.some(item =>
                code(item?.[valueKey]) === code(selectedValue)
            )
        ) {
            element.value = code(selectedValue);
        } else {
            element.value = '';
        }
    }

    function kegiatanCodesForSatker(kodeSatker) {
        const satker = code(kodeSatker);

        if (!satker) {
            return null;
        }

        return new Set(
            satkerKegiatanData
                .filter(item =>
                    code(item.kode_satker) === satker
                )
                .map(item => code(item.kode_kegiatan))
                .filter(Boolean)
        );
    }

    /*
     * Menghasilkan seluruh Kegiatan yang masih valid berdasarkan
     * parent yang saat ini dipilih:
     *
     * - Satker, jika ada
     * - Program, jika ada
     * - Kegiatan langsung, jika sudah dipilih
     */
    function allowedKegiatanCodes() {
        const selectedProgram = code(programFilter?.value);
        const selectedKegiatan = code(kegiatanFilter?.value);
        const selectedSatker = code(satkerFilter?.value);

        if (selectedKegiatan) {
            return new Set([selectedKegiatan]);
        }

        const satkerCodes = kegiatanCodesForSatker(selectedSatker);

        return new Set(
            kegiatanData
                .filter(item => {
                    const kegiatanCode = code(item.kode_kegiatan);

                    const programOk =
                        !selectedProgram ||
                        code(item.kode_program) === selectedProgram;

                    const satkerOk =
                        !satkerCodes ||
                        satkerCodes.has(kegiatanCode);

                    return kegiatanCode && programOk && satkerOk;
                })
                .map(item => code(item.kode_kegiatan))
                .filter(Boolean)
        );
    }

    /*
     * Program ikut dibatasi oleh Satker pada tab Subkomponen.
     * Jika tidak ada filter Satker, seluruh Program tetap tersedia.
     */
    function populateProgram(selectedValue = '') {
        if (!programFilter) {
            return;
        }

        const selectedSatker = code(satkerFilter?.value);

        let filtered = programData;

        if (selectedSatker) {
            const satkerCodes = kegiatanCodesForSatker(selectedSatker);

            const allowedProgramCodes = new Set(
                kegiatanData
                    .filter(item =>
                        satkerCodes?.has(code(item.kode_kegiatan))
                    )
                    .map(item => code(item.kode_program))
                    .filter(Boolean)
            );

            filtered = programData.filter(item =>
                allowedProgramCodes.has(code(item.kode_program))
            );
        }

        renderSelect(
            programFilter,
            filtered,
            'kode_program',
            'nama_program',
            '-- Semua Program --',
            selectedValue
        );
    }

    /*
     * Kegiatan dibatasi oleh:
     * Satker (jika ada) + Program (jika ada).
     */
    function populateKegiatan(selectedValue = '') {
        if (!kegiatanFilter) {
            return;
        }

        const selectedProgram = code(programFilter?.value);
        const selectedSatker = code(satkerFilter?.value);
        const satkerCodes = kegiatanCodesForSatker(selectedSatker);

        const filtered = kegiatanData.filter(item => {
            const kegiatanCode = code(item.kode_kegiatan);

            const programOk =
                !selectedProgram ||
                code(item.kode_program) === selectedProgram;

            const satkerOk =
                !satkerCodes ||
                satkerCodes.has(kegiatanCode);

            return kegiatanCode && programOk && satkerOk;
        });

        renderSelect(
            kegiatanFilter,
            filtered,
            'kode_kegiatan',
            'nama_kegiatan',
            '-- Semua Kegiatan --',
            selectedValue
        );
    }

    /*
     * KRO tidak hanya melihat Kegiatan yang dipilih.
     *
     * Jika Kegiatan masih "Semua", KRO tetap dibatasi ke seluruh
     * Kegiatan yang valid pada Program/Satker yang sedang dipilih.
     */
    function populateKro(selectedValue = '') {
        if (!kroFilter) {
            return;
        }

        const allowedKegiatan = allowedKegiatanCodes();

        const filtered = uniqueBy(
            kroData.filter(item =>
                allowedKegiatan.has(code(item.kode_kegiatan))
            ),
            'kode_kro'
        );

        renderSelect(
            kroFilter,
            filtered,
            'kode_kro',
            'nama_kro',
            '-- Semua KRO --',
            selectedValue
        );
    }

    /*
     * RO dibatasi oleh:
     * Satker -> Program -> Kegiatan -> KRO
     *
     * Parent yang belum dipilih berarti "semua child yang masih
     * valid di dalam parent yang sudah dipilih", bukan semua RO global.
     */
    function populateRo(selectedValue = '') {
        if (!roFilter) {
            return;
        }

        const allowedKegiatan = allowedKegiatanCodes();
        const selectedKro = code(kroFilter?.value);

        const filtered = uniqueBy(
            roData.filter(item => {
                const kegiatanOk =
                    allowedKegiatan.has(code(item.kode_kegiatan));

                const kroOk =
                    !selectedKro ||
                    code(item.kode_kro) === selectedKro;

                return kegiatanOk && kroOk;
            }),
            'kode_ro'
        );

        renderSelect(
            roFilter,
            filtered,
            'kode_ro',
            'nama_ro',
            '-- Semua RO --',
            selectedValue
        );
    }

    /*
     * Komponen dibatasi oleh seluruh parent:
     * Satker -> Program -> Kegiatan -> KRO -> RO
     */
    function populateKomponen(selectedValue = '') {
        if (!komponenFilter) {
            return;
        }

        const allowedKegiatan = allowedKegiatanCodes();
        const selectedKro = code(kroFilter?.value);
        const selectedRo = code(roFilter?.value);

        const filtered = uniqueBy(
            komponenData.filter(item => {
                const kegiatanOk =
                    allowedKegiatan.has(code(item.kode_kegiatan));

                const kroOk =
                    !selectedKro ||
                    code(item.kode_kro) === selectedKro;

                const roOk =
                    !selectedRo ||
                    code(item.kode_ro) === selectedRo;

                return kegiatanOk && kroOk && roOk;
            }),
            'kode_komponen'
        );

        renderSelect(
            komponenFilter,
            filtered,
            'kode_komponen',
            'nama_komponen',
            '-- Semua Komponen --',
            selectedValue
        );
    }

    /*
     * PROGRAM BERUBAH
     * Semua child langsung dihitung ulang dalam ruang lingkup Program baru.
     */
    programFilter?.addEventListener('change', () => {
        populateKegiatan('');
        populateKro('');
        populateRo('');
        populateKomponen('');
    });

    /*
     * SATKER BERUBAH (khusus Subkomponen)
     *
     * Program juga child dari konteks Satker untuk kebutuhan filter,
     * karena hanya Program yang mempunyai Kegiatan pada Satker tersebut
     * yang relevan.
     */
    satkerFilter?.addEventListener('change', () => {
        populateProgram('');
        populateKegiatan('');
        populateKro('');
        populateRo('');
        populateKomponen('');
    });

    /*
     * KEGIATAN BERUBAH
     */
    kegiatanFilter?.addEventListener('change', () => {
        populateKro('');
        populateRo('');
        populateKomponen('');
    });

    /*
     * KRO BERUBAH
     */
    kroFilter?.addEventListener('change', () => {
        populateRo('');
        populateKomponen('');
    });

    /*
     * RO BERUBAH
     */
    roFilter?.addEventListener('change', () => {
        populateKomponen('');
    });

    /*
     * RESTORE FILTER GET
     *
     * Restore dilakukan dari parent ke child.
     * Nilai hanya dikembalikan apabila masih valid terhadap parent.
     */
    populateProgram(oldFilter.program);
    populateKegiatan(oldFilter.kegiatan);
    populateKro(oldFilter.kro);
    populateRo(oldFilter.ro);
    populateKomponen(oldFilter.komponen);
</script>

</body>
</html>
