<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Workspace Penelitian | Penelitian RKA-K/L</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --primary: #0759b7;
            --primary-dark: #06498f;
            --primary-soft: #eef5ff;
            --success: #159957;
            --success-soft: #effaf4;
            --warning: #db9b17;
            --warning-soft: #fff8e8;
            --text-primary: #18365b;
            --text-secondary: #607995;
            --text-muted: #91a4b9;
            --background: #f3f6fa;
            --border: #dbe5ee;
            --white: #ffffff;
            --danger: #df4052;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        html, body { min-height: 100%; }
        body {
            min-height: 100vh;
            overflow-x: hidden;
            color: var(--text-primary);
            background: var(--background);
            font-family: Inter, "Segoe UI", Arial, sans-serif;
        }
        button, input, select { font: inherit; }
        button, select { cursor: pointer; }
        a { color: inherit; text-decoration: none; }
        .app-shell { min-height: 100vh; }
        .app-main { min-height: 100vh; display: flex; flex-direction: column; }

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
            background: rgba(255,255,255,.96);
            box-shadow: 0 4px 18px rgba(33,67,103,.05);
            backdrop-filter: blur(12px);
        }
        .header-left { min-width: 0; display: flex; align-items: center; gap: 13px; }
        .header-copy { min-width: 0; }
        .header-eyebrow {
            color: #879bb1;
            font-size: 7.5px;
            font-weight: 700;
            letter-spacing: .8px;
            white-space: nowrap;
        }
        .header-title { margin-top: 3px; font-size: 14px; font-weight: 800; }
        .header-user { display: flex; align-items: center; gap: 10px; }
        .header-user-text { text-align: right; color: var(--text-secondary); font-size: 8.5px; line-height: 1.4; }
        .header-user-text strong { display: block; color: var(--text-primary); font-size: 10px; }
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
            font-weight: 800;
        }

        .page-container { flex: 1; width: 100%; padding: 24px; }
        .content-wrapper { width: 100%; max-width: 1180px; margin: 0 auto; }

        .page-heading {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 18px;
            margin-bottom: 18px;
        }
        .page-heading h1 { font-size: 20px; line-height: 1.25; }
        .page-heading p { margin-top: 5px; color: var(--text-secondary); font-size: 9px; line-height: 1.55; }
        .btn-primary {
            min-height: 39px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            padding: 0 14px;
            border: 0;
            border-radius: 10px;
            color: #fff;
            background: linear-gradient(135deg, #0759b7, #0878d4);
            box-shadow: 0 7px 16px rgba(7,89,183,.18);
            font-size: 9px;
            font-weight: 800;
        }
        .btn-primary:hover { filter: brightness(.97); }

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 12px;
            margin-bottom: 16px;
        }
        .summary-card {
            display: flex;
            align-items: center;
            gap: 11px;
            padding: 14px 16px;
            border: 1px solid var(--border);
            border-radius: 12px;
            background: #fff;
            box-shadow: 0 5px 17px rgba(38,68,103,.045);
        }
        .summary-icon {
            width: 34px;
            height: 34px;
            flex: 0 0 auto;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 9px;
            color: var(--primary);
            background: var(--primary-soft);
            font-size: 14px;
        }
        .summary-card:nth-child(2) .summary-icon { color: #a06a00; background: var(--warning-soft); }
        .summary-card:nth-child(3) .summary-icon { color: var(--success); background: var(--success-soft); }
        .summary-label { color: var(--text-muted); font-size: 7.5px; font-weight: 700; text-transform: uppercase; letter-spacing: .35px; }
        .summary-value { margin-top: 2px; font-size: 17px; font-weight: 850; }

        .alert {
            display: flex;
            align-items: flex-start;
            gap: 9px;
            margin-bottom: 14px;
            padding: 11px 13px;
            border-radius: 10px;
            font-size: 8.5px;
            line-height: 1.5;
        }
        .alert-success { border: 1px solid #bee9d1; color: #28744d; background: #f1fbf5; }
        .alert-error { border: 1px solid #f0c9cf; color: #9a3442; background: #fff4f5; }

        .main-card {
            border: 1px solid var(--border);
            border-radius: 14px;
            background: #fff;
            box-shadow: 0 8px 25px rgba(38,68,103,.06);
            overflow: hidden;
        }
        .filter-bar {
            display: grid;
            grid-template-columns: minmax(230px, 1.4fr) minmax(160px, .6fr) minmax(220px, 1fr) auto;
            gap: 10px;
            padding: 15px;
            border-bottom: 1px solid #e7edf3;
            background: #fbfcfe;
        }
        .field { position: relative; }
        .field i {
            position: absolute;
            left: 11px;
            top: 50%;
            transform: translateY(-50%);
            color: #8ba0b6;
            font-size: 12px;
            pointer-events: none;
        }
        .control {
            width: 100%;
            min-height: 38px;
            padding: 8px 10px;
            border: 1px solid #d7e1ea;
            border-radius: 9px;
            color: #2a4564;
            background: #fff;
            outline: none;
            font-size: 8.5px;
        }
        .field.with-icon .control { padding-left: 31px; }
        .control:focus { border-color: #8cb9e8; box-shadow: 0 0 0 3px rgba(7,89,183,.07); }
        .btn-filter {
            min-height: 38px;
            padding: 0 14px;
            border: 1px solid #c9d7e5;
            border-radius: 9px;
            color: #355878;
            background: #fff;
            font-size: 8.5px;
            font-weight: 800;
        }

        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; }
        thead th {
            padding: 11px 12px;
            border-bottom: 1px solid #dfe7ef;
            color: #6f849a;
            background: #f8fafc;
            font-size: 7.5px;
            font-weight: 800;
            text-align: left;
            text-transform: uppercase;
            letter-spacing: .25px;
            white-space: nowrap;
        }
        tbody td {
            padding: 13px 12px;
            border-bottom: 1px solid #edf1f5;
            color: #425f7c;
            font-size: 8.5px;
            vertical-align: middle;
        }
        tbody tr:last-child td { border-bottom: 0; }
        tbody tr:hover { background: #fbfdff; }
        .research-name { max-width: 310px; color: #203f61; font-size: 9px; font-weight: 800; line-height: 1.4; }
        .small-muted { margin-top: 3px; color: #94a5b7; font-size: 7.5px; }
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 5px 8px;
            border-radius: 999px;
            font-size: 7.5px;
            font-weight: 850;
        }
        .status-draft { color: #946400; background: #fff5d8; border: 1px solid #f3df9f; }
        .status-final { color: #1a7a4c; background: #edf9f2; border: 1px solid #c2e8d3; }
        .action-stack {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            gap: 5px;
        }

        .action-link.print-link {
            color: #2c6949;
        }

        .action-link {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 6px 9px;
            border: 1px solid #cfe0f2;
            border-radius: 8px;
            color: #24639d;
            background: #f5f9fd;
            font-size: 7.5px;
            font-weight: 800;
        }
        .action-disabled { color: #98a7b6; background: #f5f6f7; border-color: #e2e6ea; cursor: not-allowed; }
        .money { white-space: nowrap; font-variant-numeric: tabular-nums; }

        .empty-state {
            padding: 50px 20px;
            text-align: center;
            color: var(--text-secondary);
        }
        .empty-state i { display: block; margin-bottom: 9px; color: #a7bacd; font-size: 28px; }
        .empty-state strong { display: block; color: #385878; font-size: 10px; }
        .empty-state span { display: block; margin-top: 5px; font-size: 8px; }

        .pagination-bar {
            min-height: 50px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            padding: 10px 14px;
            border-top: 1px solid #e8edf2;
            color: #7e91a5;
            background: #fbfcfd;
            font-size: 8px;
        }
        .pagination-actions { display: flex; align-items: center; gap: 6px; }
        .page-btn {
            min-width: 31px;
            min-height: 31px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #d4dee8;
            border-radius: 8px;
            color: #43627f;
            background: #fff;
        }
        .page-btn.disabled { opacity: .45; pointer-events: none; }
        .page-number { padding: 0 7px; color: #516b84; font-weight: 700; }

        @media (max-width: 900px) {
            .filter-bar { grid-template-columns: 1fr 1fr; }
            .summary-grid { grid-template-columns: 1fr; }
        }
        @media (max-width: 680px) {
            .page-container { padding: 16px 12px; }
            .dashboard-header { padding: 0 14px; }
            .header-user-text { display: none; }
            .page-heading { align-items: stretch; flex-direction: column; }
            .filter-bar { grid-template-columns: 1fr; }
            .btn-primary { width: 100%; }
        }
    </style>
</head>
<body>
@php
    $authUser = auth()->user();
    $userName = $authUser?->name ?? session('user_name') ?? 'Pengguna Sistem';
    $initials = collect(explode(' ', $userName))
        ->filter()->take(2)
        ->map(fn($word) => strtoupper(substr($word, 0, 1)))
        ->implode('');
@endphp

<div class="app-shell">
    @include('partials.sidebar', ['activeMenu' => 'penelitian'])

    <div class="app-main">
        <header class="dashboard-header">
            <div class="header-left">
                <button type="button" class="sidebar-toggle" id="sidebarToggle" aria-label="Buka menu navigasi" aria-expanded="false">
                    <i class="bi bi-list"></i>
                </button>
                <div class="header-copy">
                    <div class="header-eyebrow">SISTEM INFORMASI PENELITIAN RKA-K/L</div>
                    <div class="header-title">Workspace Penelitian</div>
                </div>
            </div>
            <div class="header-user">
                <div class="header-user-text">Biro Perencanaan<strong>{{ $userName }}</strong></div>
                <div class="header-avatar">{{ $initials ?: 'US' }}</div>
            </div>
        </header>

        <main class="page-container">
            <div class="content-wrapper">
                <div class="page-heading">
                    <div>
                        <h1>Daftar Penelitian RKA-K/L</h1>
                        <p>Kelola penelitian DRAFT dan CHP FINAL per Satker. CHP FINAL dapat dibuka dalam mode read-only serta dicetak/disimpan sebagai PDF.</p>
                    </div>
                    <a href="{{ route('penelitian.create') }}" class="btn-primary">
                        <i class="bi bi-plus-lg"></i>
                        Penelitian Baru
                    </a>
                </div>

                @if (session('success'))
                    <div class="alert alert-success"><i class="bi bi-check-circle-fill"></i><span>{{ session('success') }}</span></div>
                @endif
                @if (session('error'))
                    <div class="alert alert-error"><i class="bi bi-exclamation-circle-fill"></i><span>{{ session('error') }}</span></div>
                @endif

                <div class="summary-grid">
                    <div class="summary-card">
                        <div class="summary-icon"><i class="bi bi-clipboard-data"></i></div>
                        <div><div class="summary-label">Total Penelitian</div><div class="summary-value">{{ number_format($summary['total']) }}</div></div>
                    </div>
                    <div class="summary-card">
                        <div class="summary-icon"><i class="bi bi-pencil-square"></i></div>
                        <div><div class="summary-label">Draft</div><div class="summary-value">{{ number_format($summary['draft']) }}</div></div>
                    </div>
                    <div class="summary-card">
                        <div class="summary-icon"><i class="bi bi-lock-fill"></i></div>
                        <div><div class="summary-label">Final</div><div class="summary-value">{{ number_format($summary['final']) }}</div></div>
                    </div>
                </div>

                <section class="main-card">
                    <form method="GET" action="{{ route('penelitian.index') }}" class="filter-bar">
                        <div class="field with-icon">
                            <i class="bi bi-search"></i>
                            <input type="text" class="control" name="q" value="{{ $search }}" placeholder="Cari nama penelitian, Satker, atau pembuat...">
                        </div>
                        <div class="field">
                            <select class="control" name="status">
                                <option value="">Semua Status</option>
                                <option value="DRAFT" @selected($status === 'DRAFT')>DRAFT</option>
                                <option value="FINAL" @selected($status === 'FINAL')>FINAL</option>
                            </select>
                        </div>
                        <div class="field">
                            <select class="control" name="kode_satker">
                                <option value="">Semua Satker</option>
                                @foreach ($satkerOptions as $satkerItem)
                                    <option value="{{ $satkerItem->kode_satker }}" @selected($kodeSatker === $satkerItem->kode_satker)>
                                        {{ $satkerItem->nama_satker }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn-filter"><i class="bi bi-funnel"></i> Terapkan</button>
                    </form>

                    @if ($penelitian->count())
                        <div class="table-wrap">
                            <table>
                                <thead>
                                <tr>
                                    <th>Nama Penelitian</th>
                                    <th>Satker</th>
                                    <th>TA</th>
                                    <th>Tanggal Penelitian</th>
                                    <th>Total Anggaran</th>
                                    <th>Status</th>
                                    <th>Pembuat</th>
                                    <th>Aksi</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($penelitian as $item)
                                    <tr>
                                        <td>
                                            <div class="research-name">{{ $item->nama_penelitian }}</div>
                                            <div class="small-muted">ID #{{ $item->penelitianID }} · Diperbarui {{ \Carbon\Carbon::parse($item->updated_at)->format('d/m/Y H:i') }}</div>
                                        </td>
                                        <td>
                                            <div>{{ $item->nama_satker }}</div>
                                            <div class="small-muted">{{ $item->kode_satker }}</div>
                                        </td>
                                        <td>{{ $item->tahun_anggaran }}</td>
                                        <td>{{ \Carbon\Carbon::parse($item->tanggal_penelitian)->translatedFormat('d M Y H:i') }}</td>
                                        <td class="money">Rp{{ number_format((float) $item->total_anggaran, 0, ',', '.') }}</td>
                                        <td>
                                            <span class="status-badge {{ $item->status === 'FINAL' ? 'status-final' : 'status-draft' }}">
                                                <i class="bi {{ $item->status === 'FINAL' ? 'bi-lock-fill' : 'bi-pencil-fill' }}"></i>
                                                {{ $item->status }}
                                            </span>
                                        </td>
                                        <td>
                                            <div>{{ $item->creator_name ?? $item->created_by }}</div>
                                            <div class="small-muted">{{ $item->created_by }}</div>
                                        </td>
                                        <td>
                                            @if ($item->status === 'DRAFT')
                                                <a href="{{ route('penelitian.edit', $item->penelitianID) }}" class="action-link">
                                                    <i class="bi bi-folder2-open"></i> Buka DRAFT
                                                </a>
                                            @else
                                                <div class="action-stack">
                                                    <a href="{{ route('penelitian.chp', $item->penelitianID) }}" class="action-link">
                                                        <i class="bi bi-file-earmark-text"></i> Lihat CHP
                                                    </a>
                                                    <a href="{{ route('penelitian.chp.print', $item->penelitianID) }}" class="action-link print-link">
                                                        <i class="bi bi-printer"></i> Cetak / PDF
                                                    </a>
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="pagination-bar">
                            <div>Menampilkan {{ $penelitian->firstItem() }}–{{ $penelitian->lastItem() }} dari {{ $penelitian->total() }} penelitian</div>
                            <div class="pagination-actions">
                                <a href="{{ $penelitian->previousPageUrl() ?: '#' }}" class="page-btn {{ $penelitian->onFirstPage() ? 'disabled' : '' }}" aria-label="Halaman sebelumnya">
                                    <i class="bi bi-chevron-left"></i>
                                </a>
                                <span class="page-number">{{ $penelitian->currentPage() }} / {{ $penelitian->lastPage() }}</span>
                                <a href="{{ $penelitian->nextPageUrl() ?: '#' }}" class="page-btn {{ !$penelitian->hasMorePages() ? 'disabled' : '' }}" aria-label="Halaman berikutnya">
                                    <i class="bi bi-chevron-right"></i>
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="empty-state">
                            <i class="bi bi-clipboard2-x"></i>
                            <strong>Belum ada penelitian yang sesuai filter.</strong>
                            <span>Buat workspace baru atau ubah filter pencarian.</span>
                        </div>
                    @endif
                </section>
            </div>
        </main>
    </div>
</div>
</body>
</html>
