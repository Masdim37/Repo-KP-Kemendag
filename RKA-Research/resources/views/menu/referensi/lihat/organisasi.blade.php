<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Referensi Organisasi | Penelitian RKA-K/L</title>

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    >

    <style>
        :root {
            --primary: #0759b7;
            --primary-dark: #06498f;
            --primary-soft: #eef5ff;
            --primary-border: #cfe1fb;

            --success: #159957;

            --text-primary: #18365b;
            --text-secondary: #607995;
            --text-muted: #8da0b4;

            --background: #f3f6fa;
            --surface: #ffffff;
            --surface-soft: #f8fafc;
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
            max-width: 1180px;
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
            max-width: 700px;
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

        /* STATS */

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 11px;
            margin-bottom: 15px;
        }

        .stat-card {
            display: flex;
            align-items: center;
            gap: 11px;
            padding: 14px 15px;
            border: 1px solid var(--border);
            border-radius: 12px;
            background: var(--surface);
            box-shadow: 0 5px 15px rgba(38, 68, 103, .04);
        }

        .stat-icon {
            width: 35px;
            height: 35px;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 9px;
            color: var(--primary);
            background: var(--primary-soft);
            font-size: 14px;
        }

        .stat-label {
            color: var(--text-muted);
            font-size: 7px;
            font-weight: 800;
            letter-spacing: .25px;
            text-transform: uppercase;
        }

        .stat-value {
            margin-top: 3px;
            color: var(--text-primary);
            font-size: 16px;
            font-weight: 900;
        }

        /* CARD */

        .main-card {
            overflow: hidden;
            border: 1px solid var(--border);
            border-radius: 14px;
            background: var(--surface);
            box-shadow: 0 8px 25px rgba(38, 68, 103, .065);
        }

        .tabs {
            display: flex;
            gap: 5px;
            padding: 13px 15px 0;
            border-bottom: 1px solid #e7edf3;
            background: #fbfdff;
        }

        .tab-link {
            position: relative;
            display: inline-flex;
            align-items: center;
            gap: 7px;
            min-height: 40px;
            padding: 0 13px;
            border-radius: 9px 9px 0 0;
            color: #72869c;
            text-decoration: none;
            font-size: 8px;
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
            right: 10px;
            bottom: -1px;
            left: 10px;
            height: 2px;
            border-radius: 10px;
            background: var(--primary);
        }

        .tab-count {
            min-width: 22px;
            height: 20px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0 6px;
            border-radius: 999px;
            color: #5c7590;
            background: #edf2f7;
            font-size: 6.8px;
        }

        .tab-link.active .tab-count {
            color: #ffffff;
            background: var(--primary);
        }

        /* FILTER */

        .filter-panel {
            padding: 14px 16px;
            border-bottom: 1px solid #edf1f5;
            background: #ffffff;
        }

        .filter-grid {
            display: grid;
            grid-template-columns: minmax(230px, 1.5fr) minmax(180px, 1fr) minmax(180px, 1fr) 105px auto;
            gap: 9px;
            align-items: end;
        }

        .filter-grid.unit1 {
            grid-template-columns: minmax(260px, 1fr) 105px auto;
        }

        .filter-grid.unit2 {
            grid-template-columns: minmax(240px, 1.4fr) minmax(210px, 1fr) 105px auto;
        }

        .filter-group label {
            display: block;
            margin-bottom: 5px;
            color: #53677e;
            font-size: 7px;
            font-weight: 800;
            letter-spacing: .2px;
            text-transform: uppercase;
        }

        .form-control {
            width: 100%;
            height: 38px;
            padding: 0 10px;
            border: 1px solid #d5dee7;
            border-radius: 8px;
            outline: none;
            color: #304b69;
            background: #ffffff;
            font-size: 8.3px;
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(7, 89, 183, .07);
        }

        .filter-actions {
            display: flex;
            gap: 7px;
        }

        .btn-filter,
        .btn-reset {
            min-width: 84px;
            height: 38px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 0 12px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 7.8px;
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
            padding: 11px 16px;
            color: #7c8fa3;
            font-size: 7.8px;
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
            min-width: 760px;
            border-collapse: collapse;
        }

        .reference-table th {
            padding: 10px 12px;
            border-top: 1px solid #e7edf3;
            border-bottom: 1px solid #dce5ee;
            color: #61778e;
            background: #f6f9fc;
            font-size: 7px;
            font-weight: 900;
            letter-spacing: .25px;
            text-align: left;
            text-transform: uppercase;
            white-space: nowrap;
        }

        .reference-table td {
            padding: 11px 12px;
            border-bottom: 1px solid #edf1f5;
            color: #415b77;
            font-size: 8.1px;
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

        .parent-name {
            display: block;
            margin-top: 2px;
            color: #8396aa;
            font-size: 7px;
        }

        .count-pill {
            min-width: 31px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 4px 7px;
            border-radius: 999px;
            color: #316b9d;
            background: #edf6ff;
            font-size: 7px;
            font-weight: 850;
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
            padding: 13px 16px;
            border-top: 1px solid #e7edf3;
        }

        .pagination-info {
            color: #7c8fa3;
            font-size: 7.6px;
        }

        .pagination {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .page-link,
        .page-current,
        .page-disabled {
            min-width: 30px;
            height: 30px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0 8px;
            border-radius: 7px;
            font-size: 7.6px;
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
            max-width: 1180px;
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

        @media (max-width: 950px) {
            .filter-grid,
            .filter-grid.unit1,
            .filter-grid.unit2 {
                grid-template-columns: 1fr 1fr;
            }

            .filter-actions {
                grid-column: 1 / -1;
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

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .tabs {
                overflow-x: auto;
            }

            .tab-link {
                flex-shrink: 0;
            }

            .filter-grid,
            .filter-grid.unit1,
            .filter-grid.unit2 {
                grid-template-columns: 1fr;
            }

            .filter-actions {
                grid-column: auto;
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

    $tabDefinitions = [
        'unit1' => [
            'label' => 'Unit Eselon I',
            'icon' => 'bi-diagram-3',
            'count' => $stats['unit1'] ?? 0,
        ],
        'unit2' => [
            'label' => 'Unit Eselon II',
            'icon' => 'bi-diagram-2',
            'count' => $stats['unit2'] ?? 0,
        ],
        'satker' => [
            'label' => 'Satker',
            'icon' => 'bi-building',
            'count' => $stats['satker'] ?? 0,
        ],
    ];

    $currentTitle = $tabDefinitions[$jenis]['label'] ?? 'Unit Eselon I';
@endphp

<div class="app-shell">
    @include('partials.sidebar', [
        'activeMenu' => 'view-reference-organization',
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
                        <h1>Data Referensi Organisasi</h1>
                        <p>
                            Lihat dan telusuri struktur Unit Eselon I, Unit Eselon II,
                            dan Satuan Kerja dari data referensi organisasi yang telah
                            tersimpan pada database.
                        </p>
                    </div>

                    <div class="read-badge">
                        <i class="bi bi-eye"></i>
                        READ ONLY
                    </div>
                </div>

                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="bi bi-diagram-3"></i>
                        </div>
                        <div>
                            <div class="stat-label">Unit Eselon I</div>
                            <div class="stat-value">
                                {{ number_format($stats['unit1'] ?? 0, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="bi bi-diagram-2"></i>
                        </div>
                        <div>
                            <div class="stat-label">Unit Eselon II</div>
                            <div class="stat-value">
                                {{ number_format($stats['unit2'] ?? 0, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="bi bi-building"></i>
                        </div>
                        <div>
                            <div class="stat-label">Satuan Kerja</div>
                            <div class="stat-value">
                                {{ number_format($stats['satker'] ?? 0, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>
                </div>

                <section class="main-card">

                    <nav class="tabs" aria-label="Jenis data referensi organisasi">
                        @foreach ($tabDefinitions as $tabKey => $tab)
                            <a
                                href="{{ route('referensi.lihat.organisasi', ['jenis' => $tabKey]) }}"
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
                        action="{{ route('referensi.lihat.organisasi') }}"
                        class="filter-panel"
                    >
                        <input type="hidden" name="jenis" value="{{ $jenis }}">

                        <div class="filter-grid {{ $jenis }}">
                            <div class="filter-group">
                                <label for="q">Pencarian</label>
                                <input
                                    type="text"
                                    id="q"
                                    name="q"
                                    class="form-control"
                                    value="{{ $filters['q'] ?? '' }}"
                                    placeholder="
                                        @if ($jenis === 'unit1')
                                            Cari kode atau nama Unit Eselon I...
                                        @elseif ($jenis === 'unit2')
                                            Cari kode/nama Unit Eselon II atau parent...
                                        @else
                                            Cari kode/nama Satker atau unit organisasi...
                                        @endif
                                    "
                                >
                            </div>

                            @if (in_array($jenis, ['unit2', 'satker'], true))
                                <div class="filter-group">
                                    <label for="unit1Filter">Unit Eselon I</label>
                                    <select
                                        id="unit1Filter"
                                        name="kode_unit_eselon1"
                                        class="form-control"
                                    >
                                        <option value="">-- Semua Unit Eselon I --</option>

                                        @foreach ($unit1Options as $item)
                                            <option
                                                value="{{ $item->kode_unit_eselon1 }}"
                                                @selected(
                                                    ($filters['kode_unit_eselon1'] ?? '') ===
                                                    $item->kode_unit_eselon1
                                                )
                                            >
                                                [{{ $item->kode_unit_eselon1 }}]
                                                {{ $item->nama_unit_eselon1 }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif

                            @if ($jenis === 'satker')
                                <div class="filter-group">
                                    <label for="unit2Filter">Unit Eselon II</label>
                                    <select
                                        id="unit2Filter"
                                        name="kode_unit_eselon2"
                                        class="form-control"
                                    >
                                        <option value="">-- Semua Unit Eselon II --</option>
                                    </select>
                                </div>
                            @endif

                            <div class="filter-group">
                                <label for="perPage">Per Halaman</label>
                                <select
                                    id="perPage"
                                    name="per_page"
                                    class="form-control"
                                >
                                    @foreach ([10, 20, 50, 100] as $size)
                                        <option
                                            value="{{ $size }}"
                                            @selected(($filters['per_page'] ?? 20) == $size)
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
                                    href="{{ route('referensi.lihat.organisasi', ['jenis' => $jenis]) }}"
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

                        @if ($jenis === 'unit1')
                            <table class="reference-table">
                                <thead>
                                    <tr>
                                        <th style="width:55px;">No.</th>
                                        <th style="width:145px;">Kode Unit I</th>
                                        <th>Nama Unit Eselon I</th>
                                        <th style="width:125px;">Unit II</th>
                                        <th style="width:110px;">Satker</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($records as $record)
                                        <tr>
                                            <td>
                                                {{ $records->firstItem() + $loop->index }}
                                            </td>
                                            <td class="code-cell">
                                                {{ $record->kode_unit_eselon1 }}
                                            </td>
                                            <td class="name-cell">
                                                {{ $record->nama_unit_eselon1 }}
                                            </td>
                                            <td>
                                                <span class="count-pill">
                                                    {{ $record->jumlah_unit_eselon2 }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="count-pill">
                                                    {{ $record->jumlah_satker }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="empty-state">
                                                <i class="bi bi-database-x"></i>
                                                Data Unit Eselon I tidak ditemukan.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>

                        @elseif ($jenis === 'unit2')
                            <table class="reference-table">
                                <thead>
                                    <tr>
                                        <th style="width:55px;">No.</th>
                                        <th style="width:145px;">Kode Unit II</th>
                                        <th>Nama Unit Eselon II</th>
                                        <th style="width:135px;">Kode Unit I</th>
                                        <th>Unit Eselon I</th>
                                        <th style="width:100px;">Satker</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($records as $record)
                                        <tr>
                                            <td>
                                                {{ $records->firstItem() + $loop->index }}
                                            </td>
                                            <td class="code-cell">
                                                {{ $record->kode_unit_eselon2 }}
                                            </td>
                                            <td class="name-cell">
                                                {{ $record->nama_unit_eselon2 }}
                                            </td>
                                            <td class="code-cell">
                                                {{ $record->kode_unit_eselon1 }}
                                            </td>
                                            <td>
                                                {{ $record->nama_unit_eselon1 }}
                                            </td>
                                            <td>
                                                <span class="count-pill">
                                                    {{ $record->jumlah_satker }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="empty-state">
                                                <i class="bi bi-database-x"></i>
                                                Data Unit Eselon II tidak ditemukan.
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
                                        <th style="width:115px;">Kode Satker</th>
                                        <th>Nama Satker</th>
                                        <th style="width:130px;">Kode Unit II</th>
                                        <th>Unit Eselon II</th>
                                        <th style="width:120px;">Kode Unit I</th>
                                        <th>Unit Eselon I</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($records as $record)
                                        <tr>
                                            <td>
                                                {{ $records->firstItem() + $loop->index }}
                                            </td>
                                            <td class="code-cell">
                                                {{ $record->kode_satker }}
                                            </td>
                                            <td class="name-cell">
                                                {{ $record->nama_satker }}
                                            </td>
                                            <td class="code-cell">
                                                {{ $record->kode_unit_eselon2 }}
                                            </td>
                                            <td>
                                                {{ $record->nama_unit_eselon2 }}
                                            </td>
                                            <td class="code-cell">
                                                {{ $record->kode_unit_eselon1 }}
                                            </td>
                                            <td>
                                                {{ $record->nama_unit_eselon1 }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="empty-state">
                                                <i class="bi bi-database-x"></i>
                                                Data Satker tidak ditemukan.
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
                                    <a
                                        class="page-link"
                                        href="{{ $records->url(1) }}"
                                    >
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

@if ($jenis === 'satker')
<script>
    const unit2Data = @json($unit2Options ?? []);
    const selectedUnit2 = @json($filters['kode_unit_eselon2'] ?? '');

    const unit1Filter = document.getElementById('unit1Filter');
    const unit2Filter = document.getElementById('unit2Filter');

    function normalizeCode(value) {
        return value === null || value === undefined
            ? ''
            : String(value).trim();
    }

    function populateUnit2Filter(preferredValue = '') {
        const kodeUnit1 = normalizeCode(unit1Filter?.value);
        const preferred = normalizeCode(preferredValue);

        const filtered = unit2Data.filter(item => {
            return !kodeUnit1 ||
                normalizeCode(item.kode_unit_eselon1) === kodeUnit1;
        });

        unit2Filter.innerHTML =
            '<option value="">-- Semua Unit Eselon II --</option>';

        filtered.forEach(item => {
            const option = document.createElement('option');
            option.value = normalizeCode(item.kode_unit_eselon2);
            option.textContent =
                `[${normalizeCode(item.kode_unit_eselon2)}] ` +
                normalizeCode(item.nama_unit_eselon2);

            unit2Filter.appendChild(option);
        });

        if (
            preferred &&
            filtered.some(item =>
                normalizeCode(item.kode_unit_eselon2) === preferred
            )
        ) {
            unit2Filter.value = preferred;
        }
    }

    unit1Filter?.addEventListener('change', () => {
        populateUnit2Filter('');
    });

    populateUnit2Filter(selectedUnit2);
</script>
@endif

</body>
</html>
