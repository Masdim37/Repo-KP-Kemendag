<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $mode === 'edit' ? 'Edit DRAFT Penelitian' : 'Penelitian Baru' }} | Penelitian RKA-K/L</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --primary: #0759b7;
            --primary-dark: #06498f;
            --primary-soft: #eef5ff;
            --success: #159957;
            --success-soft: #effaf4;
            --danger: #df4052;
            --danger-soft: #fff4f5;
            --warning: #db9b17;
            --warning-soft: #fff8e8;
            --text-primary: #18365b;
            --text-secondary: #607995;
            --text-muted: #91a4b9;
            --background: #f3f6fa;
            --border: #dbe5ee;
            --white: #ffffff;
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
        .header-eyebrow { color: #879bb1; font-size: 7.5px; font-weight: 700; letter-spacing: .8px; }
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
        .content-wrapper { width: 100%; max-width: 1120px; margin: 0 auto; }
        .breadcrumb {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-bottom: 12px;
            color: #68819a;
            font-size: 8px;
            font-weight: 700;
        }
        .breadcrumb:hover { color: var(--primary); }

        .page-heading {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 15px;
            margin-bottom: 16px;
        }
        .page-heading h1 { font-size: 19px; line-height: 1.3; }
        .page-heading p { margin-top: 5px; color: var(--text-secondary); font-size: 8.5px; line-height: 1.55; }
        .status-chip {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 10px;
            border: 1px solid #f0d991;
            border-radius: 999px;
            color: #946400;
            background: #fff6dd;
            font-size: 7.5px;
            font-weight: 850;
        }

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
        .alert-warning { border: 1px solid #f0dda2; color: #8b6516; background: #fff9e9; }
        .validation-list { margin-top: 4px; padding-left: 16px; }

        .main-card {
            margin-bottom: 16px;
            border: 1px solid var(--border);
            border-radius: 14px;
            background: #fff;
            box-shadow: 0 8px 25px rgba(38,68,103,.055);
            overflow: hidden;
        }
        .card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 15px 18px;
            border-bottom: 1px solid #e6ecf2;
            background: #fcfdff;
        }
        .card-header-left { display: flex; align-items: center; gap: 10px; }
        .card-icon {
            width: 33px;
            height: 33px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 9px;
            color: var(--primary);
            background: var(--primary-soft);
            font-size: 13px;
        }
        .card-title { font-size: 11px; font-weight: 850; }
        .card-subtitle { margin-top: 3px; color: var(--text-muted); font-size: 7.5px; line-height: 1.45; }
        .card-body { padding: 18px; }

        .form-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 14px; }
        .form-grid.three { grid-template-columns: repeat(3, minmax(0, 1fr)); }
        .form-group.full { grid-column: 1 / -1; }
        label {
            display: flex;
            align-items: center;
            gap: 5px;
            margin-bottom: 6px;
            color: #49647f;
            font-size: 8px;
            font-weight: 800;
        }
        .required { color: var(--danger); }
        .form-control {
            width: 100%;
            min-height: 40px;
            padding: 9px 11px;
            border: 1px solid #d7e1ea;
            border-radius: 9px;
            color: #294967;
            background: #fff;
            outline: none;
            font-size: 8.7px;
            transition: .15s ease;
        }
        .form-control:focus { border-color: #86b6e7; box-shadow: 0 0 0 3px rgba(7,89,183,.07); }
        .form-control:disabled { color: #8092a5; background: #f3f5f7; cursor: not-allowed; }
        .field-help { margin-top: 5px; color: #91a2b4; font-size: 7.2px; line-height: 1.45; }

        .document-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 12px; }
        .document-card {
            min-width: 0;
            padding: 14px;
            border: 1px solid #dce5ee;
            border-radius: 12px;
            background: linear-gradient(180deg, #fff 0%, #fbfdff 100%);
        }
        .document-card.required-card { border-color: #bed5ed; box-shadow: inset 0 0 0 1px rgba(7,89,183,.025); }
        .document-heading { display: flex; align-items: center; justify-content: space-between; gap: 8px; margin-bottom: 9px; }
        .document-name { display: flex; align-items: center; gap: 6px; color: #365675; font-size: 9px; font-weight: 850; }
        .document-name i { color: var(--primary); }
        .optional-badge, .required-badge {
            padding: 4px 7px;
            border-radius: 999px;
            font-size: 6.5px;
            font-weight: 850;
        }
        .optional-badge { color: #70859a; background: #f1f4f7; }
        .required-badge { color: #356b9d; background: #edf5fd; }
        .document-state { min-height: 16px; margin-top: 6px; color: #8a9bad; font-size: 7px; line-height: 1.4; }
        .loading { color: #547ca7; }
        .empty { color: #a06a00; }

        .multi-list {
            min-height: 90px;
            max-height: 220px;
            overflow-y: auto;
            border: 1px solid #d7e1ea;
            border-radius: 9px;
            background: #fff;
        }
        .multi-placeholder { padding: 27px 12px; color: #96a6b6; font-size: 7.5px; text-align: center; }
        .multi-item {
            display: flex;
            align-items: flex-start;
            gap: 8px;
            padding: 9px 10px;
            border-bottom: 1px solid #eef2f5;
        }
        .multi-item:last-child { border-bottom: 0; }
        .multi-item:hover { background: #f9fbfd; }
        .multi-item input { margin-top: 2px; }
        .multi-copy { min-width: 0; }
        .multi-title { color: #36536f; font-size: 7.8px; font-weight: 750; line-height: 1.35; }
        .multi-meta { margin-top: 2px; color: #9aabba; font-size: 6.8px; }

        .snapshot-box {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 8px;
            margin-top: 14px;
            padding: 12px;
            border: 1px solid #dce8f4;
            border-radius: 10px;
            background: #f7fbff;
        }
        .snapshot-label { color: #8a9db0; font-size: 6.8px; font-weight: 700; text-transform: uppercase; }
        .snapshot-value { margin-top: 3px; color: #315777; font-size: 8.2px; font-weight: 800; line-height: 1.4; }

        .party-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 18px; }
        .party-column { padding: 14px; border: 1px solid #e0e7ee; border-radius: 11px; background: #fbfcfd; }
        .party-title { margin-bottom: 10px; color: #3f5e7c; font-size: 9px; font-weight: 850; }
        .party-field { margin-top: 9px; }
        .party-field:first-of-type { margin-top: 0; }
        .readonly-person {
            min-height: 40px;
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 9px 11px;
            border: 1px solid #dbe4ec;
            border-radius: 9px;
            color: #526d87;
            background: #f2f5f8;
            font-size: 8.5px;
            font-weight: 750;
        }

        .form-actions {
            position: sticky;
            bottom: 0;
            z-index: 20;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-top: 16px;
            padding: 12px 14px;
            border: 1px solid #dbe4ed;
            border-radius: 12px;
            background: rgba(255,255,255,.96);
            box-shadow: 0 -5px 18px rgba(30,65,100,.07);
            backdrop-filter: blur(10px);
        }
        .action-note { color: #7e91a4; font-size: 7.5px; line-height: 1.45; }
        .button-group { display: flex; align-items: center; gap: 8px; }
        .btn-secondary, .btn-primary {
            min-height: 39px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            padding: 0 14px;
            border-radius: 9px;
            font-size: 8.3px;
            font-weight: 850;
        }
        .btn-secondary { border: 1px solid #cedae5; color: #4d6984; background: #fff; }
        .btn-primary { border: 0; color: #fff; background: linear-gradient(135deg, #0759b7, #0878d4); box-shadow: 0 7px 16px rgba(7,89,183,.17); }
        .btn-primary:disabled { opacity: .58; cursor: wait; }

        .research-header-actions {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-shrink: 0;
        }
        .btn-research {
            min-height: 36px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            padding: 0 14px;
            border: 1px solid #b9d5fb;
            border-radius: 9px;
            color: #0759b7;
            background: #eef5ff;
            font-size: 9px;
            font-weight: 800;
            cursor: pointer;
        }
        .btn-research:hover { background: #e1eeff; }
        .btn-research:disabled { opacity: .58; cursor: wait; }
        .research-note {
            margin-bottom: 14px;
            padding: 11px 13px;
            border: 1px solid #dbe8f7;
            border-radius: 10px;
            color: #607995;
            background: #f8fbff;
            font-size: 9px;
            line-height: 1.55;
        }
        .research-table-wrap {
            width: 100%;
            overflow-x: auto;
            border: 1px solid #dfe8f1;
            border-radius: 11px;
        }
        .research-table {
            width: 100%;
            min-width: 900px;
            border-collapse: collapse;
            background: #fff;
        }
        .research-table th,
        .research-table td {
            padding: 11px 12px;
            border-right: 1px solid #e4ebf2;
            border-bottom: 1px solid #e4ebf2;
            vertical-align: top;
        }
        .research-table th:last-child,
        .research-table td:last-child { border-right: 0; }
        .research-table tr:last-child td { border-bottom: 0; }
        .research-table th {
            color: #17365d;
            background: #dce8f5;
            font-size: 9px;
            font-weight: 800;
            text-align: center;
        }
        .research-no {
            width: 50px;
            color: #355a7d;
            font-size: 10px;
            font-weight: 800;
            text-align: center;
        }
        .research-uraian {
            min-width: 280px;
            color: #18365b;
            font-size: 9.5px;
            font-weight: 700;
            line-height: 1.5;
        }
        .research-status-cell { min-width: 190px; }
        .research-explanation-cell { min-width: 360px; }
        .research-explanation {
            min-height: 92px;
            resize: vertical;
            line-height: 1.5;
        }
        .system-result {
            margin-top: 6px;
            color: #7b8fa5;
            font-size: 8px;
            line-height: 1.45;
        }
        .system-result strong { color: #506b86; }
        .system-status {
            display: inline-flex;
            align-items: center;
            margin-left: 4px;
            padding: 2px 6px;
            border-radius: 999px;
            font-size: 7.5px;
            font-weight: 800;
        }
        .system-status.sesuai { color: #147348; background: #e8f7ef; }
        .system-status.tidak-sesuai { color: #b42f40; background: #fff0f2; }
        .system-status.perlu-konfirmasi { color: #9b6a12; background: #fff7df; }

        .system-status.lengkap { color: #147348; background: #e8f7ef; }
        .system-status.belum-lengkap { color: #b42f40; background: #fff0f2; }
        .research-empty {
            padding: 24px 18px;
            border: 1px dashed #cbd9e8;
            border-radius: 11px;
            color: #71869d;
            background: #fbfdff;
            font-size: 10px;
            line-height: 1.6;
            text-align: center;
        }

        .research-uraian.is-child {
            padding-left: 28px;
            font-weight: 750;
            position: relative;
        }
        .research-uraian.is-child::before {
            content: '↳';
            position: absolute;
            left: 12px;
            color: #8aa1b8;
            font-weight: 800;
        }
        .research-parent-row td { background: #fbfdff; }


        .research-currency {
            text-align: right;
            white-space: nowrap;
            font-variant-numeric: tabular-nums;
            font-weight: 700;
            color: #294866;
        }

        .research-c-note {
            white-space: pre-line;
            line-height: 1.55;
            color: #405f7d;
            font-size: 9px;
        }

        .research-c-negative {
            color: #b42335;
        }


        .research-c-editable {
            min-width: 145px;
        }

        .research-c-amount-input {
            width: 100%;
            min-width: 135px;
            text-align: right;
            font-variant-numeric: tabular-nums;
            font-weight: 700;
        }

        .research-c-system {
            margin-top: 5px;
            font-size: 8px;
            line-height: 1.35;
            color: #73869a;
        }

        .research-c-override-active {
            color: #9b5c00;
            font-weight: 800;
        }

        .research-c-note-input {
            width: 100%;
            min-height: 92px;
            resize: vertical;
            white-space: pre-wrap;
            line-height: 1.5;
            font-size: 9px;
        }

        .research-c-difference {
            text-align: right;
            white-space: nowrap;
            font-variant-numeric: tabular-nums;
            font-weight: 800;
            color: #294866;
        }

        .research-c-difference.is-negative {
            color: #b42335;
        }


        .research-d2-number {
            text-align: right;
            white-space: nowrap;
            font-variant-numeric: tabular-nums;
            font-weight: 700;
            color: #294866;
        }

        .research-d2-group-header {
            text-align: center;
            line-height: 1.25;
        }


        .research-f-source {
            display: inline-flex;
            align-items: center;
            padding: 3px 7px;
            border-radius: 999px;
            font-size: 8px;
            font-weight: 800;
            letter-spacing: .02em;
            margin-bottom: 6px;
            background: #eef5ff;
            color: #245a91;
        }

        .research-f-source.user {
            background: #eef8f1;
            color: #287048;
        }

        .research-f-row-hidden {
            opacity: .58;
        }

        .research-f-actions {
            display: flex;
            gap: 8px;
            align-items: center;
            flex-wrap: wrap;
            margin-top: 8px;
        }

        .research-f-delete-label {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 9px;
            color: #8d3540;
            cursor: pointer;
        }

        .research-f-add {
            margin-top: 12px;
        }

        .research-f-remove {
            border: 0;
            background: transparent;
            color: #b42f40;
            font-size: 9px;
            font-weight: 800;
            cursor: pointer;
            padding: 4px 0;
        }


        .finalization-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 8px;
            margin-top: 12px;
        }

        .finalization-check {
            display: flex;
            align-items: flex-start;
            gap: 8px;
            padding: 9px 10px;
            border: 1px solid #e1e8f0;
            border-radius: 9px;
            background: #fbfdff;
            font-size: 8px;
            line-height: 1.45;
        }

        .finalization-check.is-ready {
            border-color: #c9ead6;
            background: #f4fbf7;
            color: #2b704d;
        }

        .finalization-check.is-missing {
            border-color: #f1d2d7;
            background: #fff7f8;
            color: #963d49;
        }

        .finalization-warning {
            margin-top: 12px;
            padding: 11px 12px;
            border: 1px solid #ecd6a8;
            border-radius: 9px;
            color: #7c5a19;
            background: #fffaf0;
            font-size: 8.5px;
            line-height: 1.55;
        }

        .btn-finalize {
            min-height: 38px;
            padding: 0 14px;
            border: 1px solid #164f88;
            border-radius: 9px;
            color: #fff;
            background: #123f6e;
            font-size: 8.5px;
            font-weight: 850;
            cursor: pointer;
        }

        .btn-finalize:disabled {
            opacity: .45;
            cursor: not-allowed;
        }

        @media (max-width: 850px) {
            .finalization-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 900px) {
            .form-grid.three { grid-template-columns: 1fr; }
            .document-grid, .party-grid { grid-template-columns: 1fr; }
            .snapshot-box { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        }
        @media (max-width: 680px) {
            .page-container { padding: 16px 12px 90px; }
            .dashboard-header { padding: 0 14px; }
            .header-user-text { display: none; }
            .page-heading { flex-direction: column; }
            .form-grid { grid-template-columns: 1fr; }
            .snapshot-box { grid-template-columns: 1fr; }
            .form-actions { align-items: stretch; flex-direction: column; }
            .button-group { width: 100%; }
            .button-group > * { flex: 1; }
        }
    </style>
</head>
<body>
@php
    $authUser = auth()->user();
    $userName = $authUser?->name ?? session('user_name') ?? 'Pengguna Sistem';
    $initials = collect(explode(' ', $userName))->filter()->take(2)
        ->map(fn($word) => strtoupper(substr($word, 0, 1)))->implode('');

    $partyMap = collect($parties)->keyBy(fn($item) => $item->jenis_pihak . '_' . $item->urutan);

    $valueE1 = old('kode_unit_eselon1', $penelitian->kode_unit_eselon1 ?? '');
    $valueE2 = old('kode_unit_eselon2', $penelitian->kode_unit_eselon2 ?? '');
    $valueSatker = old('kode_satker', $penelitian->kode_satker ?? '');

    $selectedRenja = old('renja_document_id', $selectedDocuments['RENJA'] ?? '');
    $selectedRkbmn = old('rkbmn_document_id', $selectedDocuments['RKBMN'] ?? '');
    $selectedJumlahPegawai = old('jumlah_pegawai_document_id', $selectedDocuments['JUMLAH_PEGAWAI'] ?? '');
    $selectedRka = old('rka_document_id', $selectedDocuments['RKA'] ?? '');
    $selectedTor = old('tor_document_ids', $selectedDocuments['TOR'] ?? []);
    $selectedRab = old('rab_document_ids', $selectedDocuments['RAB'] ?? []);

    // Baseline selalu berasal dari data DATABASE, bukan old input. Ini penting
    // agar warning invalidasi tetap benar setelah validation error.
    $baselineSelection = [
        'RENJA' => $selectedDocuments['RENJA'] ?? null,
        'RKBMN' => $selectedDocuments['RKBMN'] ?? null,
        'JUMLAH_PEGAWAI' => $selectedDocuments['JUMLAH_PEGAWAI'] ?? null,
        'RKA' => $selectedDocuments['RKA'] ?? null,
        'TOR' => array_values((array) ($selectedDocuments['TOR'] ?? [])),
        'RAB' => array_values((array) ($selectedDocuments['RAB'] ?? [])),
    ];
    $baselineContext = [
        'kode_unit_eselon1' => $penelitian->kode_unit_eselon1 ?? null,
        'kode_unit_eselon2' => $penelitian->kode_unit_eselon2 ?? null,
        'kode_satker' => $penelitian->kode_satker ?? null,
    ];

    $peneliti1 = $partyMap->get('PENELITI_1')?->nama_snapshot ?? $userName;
    $peneliti2 = old('peneliti_2', $partyMap->get('PENELITI_2')?->nama_snapshot ?? '');
    $peneliti3 = old('peneliti_3', $partyMap->get('PENELITI_3')?->nama_snapshot ?? '');
    $perwakilan1 = old('perwakilan_1', $partyMap->get('PERWAKILAN_1')?->nama_snapshot ?? '');
    $perwakilan2 = old('perwakilan_2', $partyMap->get('PERWAKILAN_2')?->nama_snapshot ?? '');
    $perwakilan3 = old('perwakilan_3', $partyMap->get('PERWAKILAN_3')?->nama_snapshot ?? '');

    $hasilAMap = collect($hasilBagianA ?? [])->keyBy('kode_baris');
    $hasilARows = [
        'A1' => 'Klasifikasi Rincian Output/ Rincian Output / Komponen',
        'A2' => 'Sasaran Program',
        'A3' => 'Indikator Kinerja Program (IKP)',
        'A4' => 'Sasaran Kegiatan',
        'A5' => 'Indikator Kinerja Kegiatan (IKK)',
    ];


    $hasilBMap = collect($hasilBagianB ?? [])->keyBy('kode_baris');
    $hasilBRows = [
        ['kode' => 'B1', 'no' => '1', 'uraian' => 'Total Pagu RKA dengan RENJA', 'level' => 0],
        ['kode' => 'B2', 'no' => '2', 'uraian' => 'Pagu Operasional', 'level' => 0],
        ['kode' => 'B2.1', 'no' => '', 'uraian' => 'Belanja Pegawai', 'level' => 1],
        ['kode' => 'B2.2', 'no' => '', 'uraian' => 'Belanja Barang', 'level' => 1],
        ['kode' => 'B3', 'no' => '3', 'uraian' => 'Pagu PN', 'level' => 0],
    ];


    $hasilC = collect($hasilBagianC ?? []);
    $hasilD = collect($hasilBagianD ?? []);
    $hasilD1 = collect($hasilBagianD1 ?? []);
    $hasilD2 = collect($hasilBagianD2 ?? []);


    $hasilF = collect($hasilBagianF ?? []);
    $finalizationReadiness = $finalizationReadiness ?? [
        'all_ready' => false,
        'checks' => [],
    ];
    $hasilFSystem = $hasilF->filter(
        fn ($row) => in_array(
            $row->sumber_catatan ?? null,
            ['SYSTEM_RULE', 'SYSTEM_AI'],
            true
        )
    )->values();
    $hasilFUser = $hasilF->filter(
        fn ($row) => ($row->sumber_catatan ?? null) === 'USER'
    )->values();

    $hasilEMap = collect($hasilBagianE ?? [])->keyBy('kode_baris');
    $hasilERows = [
        'E1' => 'Surat Pengantar',
        'E2' => 'Surat Tugas',
        'E3' => 'RKA Satker',
        'E4' => 'TOR dan RAB',
        'E5' => 'Data Dukung Lainnya',
    ];

    $formatVolumeD2 = function ($value) {
        $number = (float) ($value ?? 0);

        if (abs($number - round($number)) < 0.000001) {
            return number_format($number, 0, ',', '.');
        }

        return rtrim(rtrim(number_format($number, 2, ',', '.'), '0'), ',');
    };

    $formatRupiahC = function ($value) {
        $number = (int) round((float) ($value ?? 0));

        if ($number < 0) {
            return '-Rp' . number_format(abs($number), 0, ',', '.');
        }

        return 'Rp' . number_format($number, 0, ',', '.');
    };

    $cRowNumber = function ($kodeBaris) {
        return match ((string) $kodeBaris) {
            'C1' => '1',
            'C2' => '2',
            'C3' => '3',
            'C4' => '4',
            'C5' => '5',
            default => '',
        };
    };
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
                    <div class="header-title">{{ $mode === 'edit' ? 'Edit Workspace DRAFT' : 'Tambah Penelitian' }}</div>
                </div>
            </div>
            <div class="header-user">
                <div class="header-user-text">Biro Perencanaan<strong>{{ $userName }}</strong></div>
                <div class="header-avatar">{{ $initials ?: 'US' }}</div>
            </div>
        </header>

        <main class="page-container">
            <div class="content-wrapper">
                <a href="{{ route('penelitian.index') }}" class="breadcrumb"><i class="bi bi-arrow-left"></i> Kembali ke Daftar Penelitian</a>

                <div class="page-heading">
                    <div>
                        <h1>{{ $mode === 'edit' ? $penelitian->nama_penelitian : 'Buat Workspace Penelitian Baru' }}</h1>
                        <p>Workspace disimpan sebagai DRAFT dan dapat diedit kembali.</p>
                    </div>
                    <span class="status-chip"><i class="bi bi-pencil-fill"></i> DRAFT</span>
                </div>

                @if (session('success'))
                    <div class="alert alert-success"><i class="bi bi-check-circle-fill"></i><span>{{ session('success') }}</span></div>
                @endif
                @if (session('error'))
                    <div class="alert alert-error"><i class="bi bi-exclamation-circle-fill"></i><span>{{ session('error') }}</span></div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-error">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                        <div>
                            <strong>Periksa kembali input berikut:</strong>
                            <ul class="validation-list">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif
                @if ($mode === 'edit' && $hasResearchResults)
                    <div class="alert alert-warning">
                        <i class="bi bi-shield-exclamation"></i>
                        <span>Workspace ini sudah mempunyai hasil penelitian sistem. Jika dokumen sumber diubah, hasil sistem akan di-invalidasi/dihapus dan penelitian wajib dijalankan kembali. Catatan manual user tetap dipertahankan.</span>
                    </div>
                @endif

                <form id="workspaceForm" method="POST" action="{{ $mode === 'edit' ? route('penelitian.update', $penelitian->penelitianID) : route('penelitian.store') }}">
                    @csrf
                    @if ($mode === 'edit')
                        @method('PUT')
                    @endif

                    <section class="main-card">
                        <div class="card-header">
                            <div class="card-header-left">
                                <div class="card-icon"><i class="bi bi-clipboard2-data"></i></div>
                                <div>
                                    <div class="card-title">Identitas Workspace Penelitian</div>
                                    <div class="card-subtitle">Isi nama penelitian dan identitas unit/satker yang ingin diteliti.</div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="form-grid">
                                <div class="form-group full">
                                    <label for="nama_penelitian">Nama Penelitian <span class="required">*</span></label>
                                    <input id="nama_penelitian" type="text" name="nama_penelitian" class="form-control" maxlength="255" required
                                           value="{{ old('nama_penelitian', $penelitian->nama_penelitian ?? '') }}"
                                           placeholder="Contoh: Penelitian RKA Balai Kalibrasi TA 2027">
                                    {{-- <div class="field-help">Nama ini digunakan sebagai identitas workspace dan activity log, bukan sebagai judul yang mencetak format CHP.</div> --}}
                                </div>
                            </div>

                            <div class="form-grid three" style="margin-top:14px;">
                                <div class="form-group">
                                    <label for="kode_unit_eselon1">Unit Eselon I <span class="required">*</span></label>
                                    <select id="kode_unit_eselon1" name="kode_unit_eselon1" class="form-control" required>
                                        <option value="">Pilih Unit Eselon I</option>
                                        @foreach ($unitEselon1 as $item)
                                            <option value="{{ $item->kode_unit_eselon1 }}" @selected($valueE1 === $item->kode_unit_eselon1)>
                                                {{ $item->kode_unit_eselon1 }} - {{ $item->nama_unit_eselon1 }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="kode_unit_eselon2">Unit Eselon II <span class="required">*</span></label>
                                    <select id="kode_unit_eselon2" name="kode_unit_eselon2" class="form-control" required disabled>
                                        <option value="">Pilih Unit Eselon II</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="kode_satker">Satker <span class="required">*</span></label>
                                    <select id="kode_satker" name="kode_satker" class="form-control" required disabled>
                                        <option value="">Pilih Satker</option>
                                    </select>
                                </div>
                            </div>

                            @if ($mode === 'edit')
                                <div class="snapshot-box">
                                    <div><div class="snapshot-label">Tanggal Penelitian</div><div class="snapshot-value">{{ \Carbon\Carbon::parse($penelitian->tanggal_penelitian)->translatedFormat('d F Y H:i') }}</div></div>
                                    <div><div class="snapshot-label">Tahun Anggaran</div><div class="snapshot-value">{{ $penelitian->tahun_anggaran }}</div></div>
                                    <div><div class="snapshot-label">Tempat</div><div class="snapshot-value">{{ $penelitian->tempat }}</div></div>
                                    <div><div class="snapshot-label">Total Anggaran</div><div class="snapshot-value">Rp{{ number_format((float) $penelitian->total_anggaran, 0, ',', '.') }}</div></div>
                                </div>
                            @endif
                        </div>
                    </section>

                    <section class="main-card">
                        <div class="card-header">
                            <div class="card-header-left">
                                <div class="card-icon"><i class="bi bi-folder-check"></i></div>
                                <div>
                                    <div class="card-title">Dokumen Sumber Penelitian</div>
                                    <div class="card-subtitle">RKA/TOR/RAB difilter sesuai Satker. RENJA, RKBMN, dan Data Jumlah Pegawai dipilih sebagai versi master/acuan. TOR dan RAB dapat dipilih lebih dari satu.</div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="document-grid">
                                <div class="document-card">
                                    <div class="document-heading"><div class="document-name"><i class="bi bi-file-earmark-spreadsheet"></i> RENJA</div><span class="required-badge">WAJIB</span></div>
                                    <select id="renja_document_id" name="renja_document_id" class="form-control document-single" data-role="RENJA" required disabled>
                                        <option value="">Pilih Satker terlebih dahulu</option>
                                    </select>
                                    <div class="document-state" id="state-RENJA"></div>
                                </div>

                                <div class="document-card">
                                    <div class="document-heading"><div class="document-name"><i class="bi bi-buildings"></i> RKBMN</div><span class="optional-badge">OPSIONAL DRAFT</span></div>
                                    <select id="rkbmn_document_id" name="rkbmn_document_id" class="form-control document-single" data-role="RKBMN" disabled>
                                        <option value="">Pilih Satker terlebih dahulu</option>
                                    </select>
                                    <div class="document-state" id="state-RKBMN"></div>
                                </div>

                                <div class="document-card">
                                    <div class="document-heading"><div class="document-name"><i class="bi bi-people"></i> Data Jumlah Pegawai</div><span class="optional-badge">OPSIONAL DRAFT</span></div>
                                    <select id="jumlah_pegawai_document_id" name="jumlah_pegawai_document_id" class="form-control document-single" data-role="JUMLAH_PEGAWAI" disabled>
                                        <option value="">Pilih Satker terlebih dahulu</option>
                                    </select>
                                    <div class="document-state" id="state-JUMLAH_PEGAWAI"></div>
                                </div>

                                <div class="document-card required-card">
                                    <div class="document-heading"><div class="document-name"><i class="bi bi-file-earmark-text"></i> RKA Satker</div><span class="required-badge">WAJIB</span></div>
                                    <select id="rka_document_id" name="rka_document_id" class="form-control document-single" data-role="RKA" required disabled>
                                        <option value="">Pilih Satker terlebih dahulu</option>
                                    </select>
                                    <div class="document-state" id="state-RKA">RKA menentukan Tahun Anggaran, total pagu, dan Program-Kegiatan header CHP.</div>
                                </div>

                                <div class="document-card">
                                    <div class="document-heading"><div class="document-name"><i class="bi bi-files"></i> TOR</div><span class="optional-badge">MULTIPLE</span></div>
                                    <div id="torList" class="multi-list"><div class="multi-placeholder">Pilih Satker terlebih dahulu.</div></div>
                                    <div class="document-state" id="state-TOR"></div>
                                </div>

                                <div class="document-card">
                                    <div class="document-heading"><div class="document-name"><i class="bi bi-files-alt"></i> RAB</div><span class="optional-badge">MULTIPLE</span></div>
                                    <div id="rabList" class="multi-list"><div class="multi-placeholder">Pilih Satker terlebih dahulu.</div></div>
                                    <div class="document-state" id="state-RAB"></div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section class="main-card">
                        <div class="card-header">
                            <div class="card-header-left">
                                <div class="card-icon"><i class="bi bi-person-vcard"></i></div>
                                <div>
                                    <div class="card-title">Peneliti dan Perwakilan Unit</div>
                                    <div class="card-subtitle">Maksimal tiga Peneliti RKA-K/L dan tiga Perwakilan Unit yang diteliti.</div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="party-grid">
                                <div class="party-column">
                                    <div class="party-title">Peneliti RKA-K/L</div>
                                    <div class="party-field">
                                        <label>Peneliti 1</label>
                                        <div class="readonly-person"><i class="bi bi-person-check-fill"></i> {{ $peneliti1 }}</div>
                                    </div>
                                    <div class="party-field"><label for="peneliti_2">Peneliti 2</label><input id="peneliti_2" name="peneliti_2" class="form-control" maxlength="255" value="{{ $peneliti2 }}" placeholder="Opsional"></div>
                                    <div class="party-field"><label for="peneliti_3">Peneliti 3</label><input id="peneliti_3" name="peneliti_3" class="form-control" maxlength="255" value="{{ $peneliti3 }}" placeholder="Opsional"></div>
                                </div>
                                <div class="party-column">
                                    <div class="party-title">Perwakilan Unit yang Diteliti</div>
                                    <div class="party-field"><label for="perwakilan_1">Perwakilan 1</label><input id="perwakilan_1" name="perwakilan_1" class="form-control" maxlength="255" value="{{ $perwakilan1 }}" required placeholder="Wajib Diisi"></div>
                                    <div class="party-field"><label for="perwakilan_2">Perwakilan 2</label><input id="perwakilan_2" name="perwakilan_2" class="form-control" maxlength="255" value="{{ $perwakilan2 }}" placeholder="Opsional"></div>
                                    <div class="party-field"><label for="perwakilan_3">Perwakilan 3</label><input id="perwakilan_3" name="perwakilan_3" class="form-control" maxlength="255" value="{{ $perwakilan3 }}" placeholder="Opsional"></div>
                                </div>
                            </div>
                            {{-- <div class="research-note" style="margin-top:14px;">
                                <strong>Tanda tangan CHP:</strong> sistem tidak menyimpan tanda tangan digital. Pada tampilan FINAL/cetak tersedia kolom tanda tangan kosong di atas nama Peneliti RKA-K/L dan Perwakilan Unit yang Diteliti sesuai FORMAT CHP.
                            </div> --}}
                        </div>
                    </section>

                    @if ($mode === 'edit')
                        <section class="main-card" id="bagianASection">
                            <div class="card-header">
                                <div class="card-header-left">
                                    <div class="card-icon"><i class="bi bi-clipboard2-check"></i></div>
                                    <div>
                                        <div class="card-title">A Konsistensi Pencantuman Sasaran Kinerja dalam RKA-K/L dengan Sasaran Kinerja dalam Renja K/L dan RKP</div>
                                        {{-- <div class="card-subtitle">A.1, A.2, dan A.4 diperiksa secara deterministic. A.3 dan A.5 default PERLU_KONFIRMASI karena sumber pembanding belum tersedia.</div> --}}
                                    </div>
                                </div>
                                <div class="research-header-actions">
                                    <button type="button" class="btn-research" id="runPartAButton">
                                        <i class="bi bi-play-circle-fill"></i>
                                        {{ $hasilAMap->isEmpty() ? 'Jalankan Bagian A' : 'Jalankan Ulang Bagian A' }}
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                {{-- <div class="research-note">
                                    Sasaran Program dan Sasaran Kegiatan dibandingkan RENJA ↔ TOR secara exact text setelah normalisasi teknis trim, line break, dan spasi berulang. Tidak menggunakan Gemini. STATUS dan PENJELASAN dapat diedit selama DRAFT; hasil sistem tetap tersimpan terpisah.
                                </div> --}}
                                <div class="research-note">
                                    Tampilan default setelah menjalankan pemeriksaan bagian A merupakan hasil temuan sistem. <strong>Namun user tetap dapat melakukan perubahan secara manual.</strong>
                                </div>

                                @if ($hasilAMap->isEmpty())
                                    <div class="research-empty">
                                        Belum ada hasil penelitian Bagian A. Klik <strong>Jalankan Bagian A</strong> untuk menjalankan pemeriksaan.
                                    </div>
                                @else
                                    <div class="research-table-wrap">
                                        <table class="research-table">
                                            <thead>
                                                <tr>
                                                    <th style="width:50px;">NO</th>
                                                    <th>KELUARAN (OUTPUT)/KOMPONEN &amp; INDIKATOR KINERJA KEGIATAN (IKK)</th>
                                                    <th style="width:190px;">STATUS</th>
                                                    <th>PENJELASAN</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($hasilARows as $kodeBaris => $uraian)
                                                    @php
                                                        $hasil = $hasilAMap->get($kodeBaris);
                                                        $effectiveStatus = old("hasil_a.{$kodeBaris}.status", $hasil?->status_efektif ?? $hasil?->status_sistem ?? 'PERLU_KONFIRMASI');
                                                        $effectiveExplanation = old("hasil_a.{$kodeBaris}.penjelasan", $hasil?->penjelasan_efektif ?? $hasil?->penjelasan_sistem ?? '');
                                                        $systemStatusClass = strtolower(str_replace('_', '-', (string) ($hasil?->status_sistem ?? 'PERLU_KONFIRMASI')));
                                                    @endphp
                                                    <tr>
                                                        <td class="research-no">{{ $loop->iteration }}</td>
                                                        <td class="research-uraian">{{ $uraian }}</td>
                                                        <td class="research-status-cell">
                                                            <select name="hasil_a[{{ $kodeBaris }}][status]" class="form-control">
                                                                <option value="SESUAI" @selected($effectiveStatus === 'SESUAI')>SESUAI</option>
                                                                <option value="TIDAK_SESUAI" @selected($effectiveStatus === 'TIDAK_SESUAI')>TIDAK SESUAI</option>
                                                                <option value="PERLU_KONFIRMASI" @selected($effectiveStatus === 'PERLU_KONFIRMASI')>PERLU KONFIRMASI</option>
                                                            </select>
                                                            <div class="system-result">
                                                                Hasil sistem:
                                                                <span class="system-status {{ $systemStatusClass }}">{{ str_replace('_', ' ', $hasil?->status_sistem ?? '-') }}</span>
                                                            </div>
                                                        </td>
                                                        <td class="research-explanation-cell">
                                                            <textarea name="hasil_a[{{ $kodeBaris }}][penjelasan]" class="form-control research-explanation" maxlength="65000">{{ $effectiveExplanation }}</textarea>
                                                            @if ($hasil?->penjelasan_user !== null || $hasil?->status_user !== null)
                                                                <div class="system-result"><strong>Override user aktif.</strong> Hasil sistem awal tetap tersimpan untuk traceability.</div>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                            </div>
                        </section>
                    @endif

                    @if ($mode === 'edit')
                        <section class="main-card" id="bagianBSection">
                            <div class="card-header">
                                <div class="card-header-left">
                                    <div class="card-icon"><i class="bi bi-cash-stack"></i></div>
                                    <div>
                                        <div class="card-title">B Kesesuaian Total Pagu dalam RENJA-K/L dengan RKA-K/L</div>
                                        {{-- <div class="card-subtitle">B.1 membandingkan total pagu. B.2 memeriksa Belanja Pegawai (akun prefix 51) dan Belanja Barang (akun prefix 52) pada level Komponen. B.3 menggunakan default status MVP.</div> --}}
                                    </div>
                                </div>
                                <div class="research-header-actions">
                                    <button type="button" class="btn-research" id="runPartBButton">
                                        <i class="bi bi-play-circle-fill"></i>
                                        {{ $hasilBMap->isEmpty() ? 'Jalankan Bagian B' : 'Jalankan Ulang Bagian B' }}
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                {{-- <div class="research-note">
                                    Seluruh perhitungan Bagian B dilakukan secara deterministic tanpa Gemini. Pagu RENJA TA aktif menggunakan alokasi_komponen_0 × 1.000 (RENJA disimpan dalam ribu rupiah); alokasi_komponen_1 s.d. alokasi_komponen_3 tidak dijumlahkan. Pada Pagu Operasional, akun 51/52 hanya dibandingkan langsung jika Komponen RKA tidak bercampur dengan kelompok akun lain. Komponen campuran ditandai PERLU KONFIRMASI. PENJELASAN mengikuti format ringkasan dan bullet per temuan agar mudah dibaca.
                                </div> --}}
                                <div class="research-note">
                                    Tampilan default setelah menjalankan pemeriksaan bagian B merupakan hasil temuan sistem. <strong>Namun user tetap dapat melakukan perubahan secara manual.</strong>
                                </div>

                                @if ($hasilBMap->isEmpty())
                                    <div class="research-empty">
                                        Belum ada hasil penelitian Bagian B. Klik <strong>Jalankan Bagian B</strong> untuk menjalankan pemeriksaan.
                                    </div>
                                @else
                                    <div class="research-table-wrap">
                                        <table class="research-table">
                                            <thead>
                                                <tr>
                                                    <th style="width:50px;">NO</th>
                                                    <th>URAIAN</th>
                                                    <th style="width:190px;">STATUS</th>
                                                    <th>PENJELASAN</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($hasilBRows as $rowConfig)
                                                    @php
                                                        $kodeBaris = $rowConfig['kode'];
                                                        $hasil = $hasilBMap->get($kodeBaris);
                                                        $effectiveStatus = old("hasil_b.{$kodeBaris}.status", $hasil?->status_efektif ?? $hasil?->status_sistem ?? 'PERLU_KONFIRMASI');
                                                        $effectiveExplanation = old("hasil_b.{$kodeBaris}.penjelasan", $hasil?->penjelasan_efektif ?? $hasil?->penjelasan_sistem ?? '');
                                                        $systemStatusClass = strtolower(str_replace('_', '-', (string) ($hasil?->status_sistem ?? 'PERLU_KONFIRMASI')));
                                                        $isChild = (int) $rowConfig['level'] > 0;
                                                    @endphp
                                                    <tr class="{{ $kodeBaris === 'B2' ? 'research-parent-row' : '' }}">
                                                        <td class="research-no">{{ $rowConfig['no'] }}</td>
                                                        <td class="research-uraian {{ $isChild ? 'is-child' : '' }}">{{ $rowConfig['uraian'] }}</td>
                                                        <td class="research-status-cell">
                                                            <select name="hasil_b[{{ $kodeBaris }}][status]" class="form-control">
                                                                <option value="SESUAI" @selected($effectiveStatus === 'SESUAI')>SESUAI</option>
                                                                <option value="TIDAK_SESUAI" @selected($effectiveStatus === 'TIDAK_SESUAI')>TIDAK SESUAI</option>
                                                                <option value="PERLU_KONFIRMASI" @selected($effectiveStatus === 'PERLU_KONFIRMASI')>PERLU KONFIRMASI</option>
                                                            </select>
                                                            <div class="system-result">
                                                                Hasil sistem:
                                                                <span class="system-status {{ $systemStatusClass }}">{{ str_replace('_', ' ', $hasil?->status_sistem ?? '-') }}</span>
                                                            </div>
                                                        </td>
                                                        <td class="research-explanation-cell">
                                                            <textarea name="hasil_b[{{ $kodeBaris }}][penjelasan]" class="form-control research-explanation" maxlength="65000">{{ $effectiveExplanation }}</textarea>
                                                            @if ($hasil?->penjelasan_user !== null || $hasil?->status_user !== null)
                                                                <div class="system-result"><strong>Override user aktif.</strong> Hasil sistem awal tetap tersimpan untuk traceability.</div>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                            </div>
                        </section>
                    @endif


                    @if ($mode === 'edit')
                        <section class="main-card" id="bagianCSection">
                            <div class="card-header">
                                <div class="card-header-left">
                                    <div class="card-icon"><i class="bi bi-table"></i></div>
                                    <div>
                                        <div class="card-title">C Kesesuaian sumber dana dalam RKA-K/L dengan sumber dana yang ditetapkan dalam Pagu Anggaran K/L</div>
                                        {{-- <div class="card-subtitle">C.1 dan C.2 membandingkan nilai RENJA TA aktif dengan RKA. C.3-C.5 menampilkan RENJA Rp0 karena data RENJA tidak tersedia sampai level Akun.</div> --}}
                                    </div>
                                </div>
                                <div class="research-header-actions">
                                    <button type="button" class="btn-research" id="runPartCButton">
                                        <i class="bi bi-play-circle-fill"></i>
                                        {{ $hasilC->isEmpty() ? 'Jalankan Bagian C' : 'Jalankan Ulang Bagian C' }}
                                    </button>
                                </div>
                            </div>

                            <div class="card-body">
                                {{-- <div class="research-note">
                                    Bagian C dihitung secara deterministic tanpa Gemini. RENJA TA aktif menggunakan <strong>alokasi_komponen_0 × 1.000</strong>; alokasi_komponen_1 s.d. alokasi_komponen_3 tidak dijumlahkan. C.3 memakai mapping kode akun eksplisit untuk PNS/PPPK/Honorer dengan parent seluruh akun 51. C.4 menggunakan daftar struktur operasional configurable (saat ini <strong>EBA → 994 → 002</strong>). Jika struktur tersebut ditemukan pada RKA Satker, akun 52 pada struktur itu menjadi Operasional dan akun 52 lainnya menjadi Non Operasional. Jika tidak ditemukan, parent tetap seluruh akun 52 tetapi child Operasional/Non Operasional tidak ditebak dan diberi catatan perlu konfirmasi. C.5 menggunakan seluruh akun prefix 53 sebagai Belanja Modal. Selama DRAFT, <strong>PAGU RENJA, PAGU RKA, dan CATATAN dapat diubah user</strong>; hasil sistem tetap tersimpan terpisah. <strong>SELISIH tidak dapat diedit dan selalu dihitung otomatis dari PAGU RKA efektif - PAGU RENJA efektif</strong>.
                                </div> --}}
                                <div class="research-note">
                                    Tampilan default setelah menjalankan pemeriksaan bagian C merupakan hasil temuan sistem. <strong>Namun user tetap dapat melakukan perubahan secara manual.</strong>
                                </div>
                            

                                @if ($hasilC->isEmpty())
                                    <div class="research-empty">
                                        Belum ada hasil penelitian Bagian C. Klik <strong>Jalankan Bagian C</strong> untuk menjalankan pemeriksaan.
                                    </div>
                                @else
                                    <div class="research-table-wrap">
                                        <table class="research-table">
                                            <thead>
                                                <tr>
                                                    <th style="width:48px;">NO</th>
                                                    <th>RINCIAN</th>
                                                    <th style="width:155px;">PAGU RENJA (Rp.)</th>
                                                    <th style="width:155px;">PAGU RKA-K/L (Rp.)</th>
                                                    <th style="width:155px;">SELISIH (RP.)</th>
                                                    <th>CATATAN</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($hasilC as $hasil)
                                                    @php
                                                        $kodeBarisC = (string) $hasil->kode_baris;
                                                        $isChild = (int) ($hasil->level_baris ?? 0) > 0;
                                                        $isParent = !$isChild && in_array($kodeBarisC, ['C1', 'C2', 'C3', 'C4'], true);

                                                        $renjaValue = (int) old(
                                                            "hasil_c.{$kodeBarisC}.pagu_renja",
                                                            $hasil->pagu_renja_efektif ?? $hasil->pagu_renja_sistem ?? 0
                                                        );

                                                        $rkaValue = (int) old(
                                                            "hasil_c.{$kodeBarisC}.pagu_rka",
                                                            $hasil->pagu_rka_efektif ?? $hasil->pagu_rka_sistem ?? 0
                                                        );

                                                        $catatanValue = old(
                                                            "hasil_c.{$kodeBarisC}.catatan",
                                                            $hasil->penjelasan_efektif ?? $hasil->penjelasan_sistem ?? ''
                                                        );

                                                        $selisihValue = $rkaValue - $renjaValue;

                                                        $renjaOverride = $hasil->pagu_renja_user !== null;
                                                        $rkaOverride = $hasil->pagu_rka_user !== null;
                                                        $catatanOverride = $hasil->penjelasan_user !== null;
                                                        $hasCOverride = $renjaOverride || $rkaOverride || $catatanOverride;
                                                    @endphp
                                                    <tr class="{{ $isParent ? 'research-parent-row' : '' }}" data-c-row="{{ $kodeBarisC }}">
                                                        <td class="research-no">{{ $cRowNumber($kodeBarisC) }}</td>
                                                        <td class="research-uraian {{ $isChild ? 'is-child' : '' }}">
                                                            {{ $hasil->uraian }}
                                                            @if ($hasCOverride)
                                                                <div class="research-c-system research-c-override-active">Override user aktif.</div>
                                                            @endif
                                                        </td>

                                                        <td class="research-c-editable">
                                                            <input
                                                                type="number"
                                                                min="0"
                                                                step="1"
                                                                inputmode="numeric"
                                                                class="form-control research-c-amount-input"
                                                                name="hasil_c[{{ $kodeBarisC }}][pagu_renja]"
                                                                value="{{ $renjaValue }}"
                                                                data-c-renja
                                                                aria-label="Pagu RENJA {{ $hasil->uraian }}"
                                                            >
                                                            <div class="research-c-system">
                                                                Hasil sistem: {{ $formatRupiahC((int) ($hasil->pagu_renja_sistem ?? 0)) }}
                                                                @if ($renjaOverride)
                                                                    · <span class="research-c-override-active">diubah user</span>
                                                                @endif
                                                            </div>
                                                        </td>

                                                        <td class="research-c-editable">
                                                            <input
                                                                type="number"
                                                                min="0"
                                                                step="1"
                                                                inputmode="numeric"
                                                                class="form-control research-c-amount-input"
                                                                name="hasil_c[{{ $kodeBarisC }}][pagu_rka]"
                                                                value="{{ $rkaValue }}"
                                                                data-c-rka
                                                                aria-label="Pagu RKA {{ $hasil->uraian }}"
                                                            >
                                                            <div class="research-c-system">
                                                                Hasil sistem: {{ $formatRupiahC((int) ($hasil->pagu_rka_sistem ?? 0)) }}
                                                                @if ($rkaOverride)
                                                                    · <span class="research-c-override-active">diubah user</span>
                                                                @endif
                                                            </div>
                                                        </td>

                                                        <td
                                                            class="research-c-difference {{ $selisihValue < 0 ? 'is-negative' : '' }}"
                                                            data-c-selisih
                                                            data-c-selisih-value="{{ $selisihValue }}"
                                                        >
                                                            {{ $formatRupiahC($selisihValue) }}
                                                            <div class="research-c-system">Otomatis · tidak dapat diedit</div>
                                                        </td>

                                                        <td class="research-explanation-cell">
                                                            <textarea
                                                                name="hasil_c[{{ $kodeBarisC }}][catatan]"
                                                                class="form-control research-c-note-input"
                                                                maxlength="65000"
                                                            >{{ $catatanValue }}</textarea>
                                                            <div class="research-c-system">
                                                                CATATAN sistem tetap tersimpan untuk traceability.
                                                                @if ($catatanOverride)
                                                                    <span class="research-c-override-active">Override user aktif.</span>
                                                                @endif
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                            </div>
                        </section>
                    @endif


                    @if ($mode === 'edit')
                        <section class="main-card" id="bagianDSection">
                            <div class="card-header">
                                <div class="card-header-left">
                                    <div class="card-icon"><i class="bi bi-tags"></i></div>
                                    <div>
                                        <div class="card-title">D Kepatuhan dan Ketepatan dalam penandaan (Budget Tagging) sesuai dengan kategori pada semua Keluaran yang dihasilkan</div>
                                        {{-- <div class="card-subtitle">Tujuh kategori mengikuti FORMAT CHP. Baseline MVP tidak melakukan automatic tagging; hasil sistem awal adalah Rp0 dan peneliti dapat melakukan override selama DRAFT.</div> --}}
                                    </div>
                                </div>
                                <div class="research-header-actions">
                                    <button type="button" class="btn-research" id="runPartDButton">
                                        <i class="bi bi-play-circle-fill"></i>
                                        {{ $hasilD->isEmpty() ? 'Jalankan Bagian D' : 'Jalankan Ulang Bagian D' }}
                                    </button>
                                </div>
                            </div>

                            <div class="card-body">
                                {{-- <div class="research-note">
                                    Bagian D saat ini menggunakan baseline deterministic tanpa Gemini. Sistem membentuk 7 kategori Budget Tagging sesuai FORMAT CHP dengan <strong>PAGU RENJA = Rp0</strong>, <strong>PAGU RKA = Rp0</strong>, dan <strong>PENJELASAN kosong</strong> sebagai hasil sistem awal. Selama DRAFT, user dapat mengubah PAGU RENJA, PAGU RKA, dan PENJELASAN. <strong>SELISIH tidak dapat diedit dan selalu dihitung otomatis dari PAGU RKA efektif - PAGU RENJA efektif</strong>.
                                </div> --}}
                                <div class="research-note">
                                    Tampilan default setelah menjalankan pemeriksaan bagian D merupakan hasil temuan sistem. <strong>Namun user tetap dapat melakukan perubahan secara manual.</strong>
                                </div>

                                @if ($hasilD->isEmpty())
                                    <div class="research-empty">
                                        Belum ada hasil penelitian Bagian D. Klik <strong>Jalankan Bagian D</strong> untuk menjalankan pemeriksaan.
                                    </div>
                                @else
                                    <div class="research-table-wrap">
                                        <table class="research-table">
                                            <thead>
                                                <tr>
                                                    <th style="width:48px;">NO</th>
                                                    <th>URAIAN</th>
                                                    <th style="width:155px;">PAGU RENJA<br>(Rp.)</th>
                                                    <th style="width:155px;">PAGU RKA<br>(Rp.)</th>
                                                    <th style="width:155px;">SELISIH<br>(RP.)</th>
                                                    <th>PENJELASAN</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($hasilD as $hasil)
                                                    @php
                                                        $kodeBarisD = (string) $hasil->kode_baris;

                                                        $renjaValueD = (int) old(
                                                            "hasil_d.{$kodeBarisD}.pagu_renja",
                                                            $hasil->pagu_renja_efektif ?? $hasil->pagu_renja_sistem ?? 0
                                                        );

                                                        $rkaValueD = (int) old(
                                                            "hasil_d.{$kodeBarisD}.pagu_rka",
                                                            $hasil->pagu_rka_efektif ?? $hasil->pagu_rka_sistem ?? 0
                                                        );

                                                        $penjelasanValueD = old(
                                                            "hasil_d.{$kodeBarisD}.penjelasan",
                                                            $hasil->penjelasan_efektif ?? $hasil->penjelasan_sistem ?? ''
                                                        );

                                                        $selisihValueD = $rkaValueD - $renjaValueD;

                                                        $renjaOverrideD = $hasil->pagu_renja_user !== null;
                                                        $rkaOverrideD = $hasil->pagu_rka_user !== null;
                                                        $penjelasanOverrideD = $hasil->penjelasan_user !== null;
                                                        $hasDOverride = $renjaOverrideD || $rkaOverrideD || $penjelasanOverrideD;
                                                    @endphp

                                                    <tr data-d-row="{{ $kodeBarisD }}">
                                                        <td class="research-no">{{ $hasil->urutan }}</td>
                                                        <td class="research-uraian">
                                                            {{ $hasil->uraian }}
                                                            @if ($hasDOverride)
                                                                <div class="research-c-system research-c-override-active">Override user aktif.</div>
                                                            @endif
                                                        </td>

                                                        <td class="research-c-editable">
                                                            <input
                                                                type="number"
                                                                min="0"
                                                                step="1"
                                                                inputmode="numeric"
                                                                class="form-control research-c-amount-input"
                                                                name="hasil_d[{{ $kodeBarisD }}][pagu_renja]"
                                                                value="{{ $renjaValueD }}"
                                                                data-d-renja
                                                                aria-label="Pagu RENJA {{ $hasil->uraian }}"
                                                            >
                                                            <div class="research-c-system">
                                                                Hasil sistem: {{ $formatRupiahC((int) ($hasil->pagu_renja_sistem ?? 0)) }}
                                                                @if ($renjaOverrideD)
                                                                    · <span class="research-c-override-active">diubah user</span>
                                                                @endif
                                                            </div>
                                                        </td>

                                                        <td class="research-c-editable">
                                                            <input
                                                                type="number"
                                                                min="0"
                                                                step="1"
                                                                inputmode="numeric"
                                                                class="form-control research-c-amount-input"
                                                                name="hasil_d[{{ $kodeBarisD }}][pagu_rka]"
                                                                value="{{ $rkaValueD }}"
                                                                data-d-rka
                                                                aria-label="Pagu RKA {{ $hasil->uraian }}"
                                                            >
                                                            <div class="research-c-system">
                                                                Hasil sistem: {{ $formatRupiahC((int) ($hasil->pagu_rka_sistem ?? 0)) }}
                                                                @if ($rkaOverrideD)
                                                                    · <span class="research-c-override-active">diubah user</span>
                                                                @endif
                                                            </div>
                                                        </td>

                                                        <td
                                                            class="research-c-difference {{ $selisihValueD < 0 ? 'is-negative' : '' }}"
                                                            data-d-selisih
                                                            data-d-selisih-value="{{ $selisihValueD }}"
                                                        >
                                                            {{ $formatRupiahC($selisihValueD) }}
                                                            <div class="research-c-system">Otomatis · tidak dapat diedit</div>
                                                        </td>

                                                        <td class="research-explanation-cell">
                                                            <textarea
                                                                name="hasil_d[{{ $kodeBarisD }}][penjelasan]"
                                                                class="form-control research-c-note-input"
                                                                maxlength="65000"
                                                            >{{ $penjelasanValueD }}</textarea>
                                                            <div class="research-c-system">
                                                                Hasil sistem: {{ blank($hasil->penjelasan_sistem ?? null) ? 'kosong' : 'tersedia' }}.
                                                                @if ($penjelasanOverrideD)
                                                                    <span class="research-c-override-active">Override user aktif.</span>
                                                                @endif
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                            </div>
                        </section>
                    @endif


                    @if ($mode === 'edit')
                        <section class="main-card" id="bagianD1Section">
                            <div class="card-header">
                                <div class="card-header-left">
                                    <div class="card-icon"><i class="bi bi-pc-display-horizontal"></i></div>
                                    <div>
                                        <div class="card-title">D.1 Indentifikasi KRO Belanja Bidang Teknologi Informasi dan Komunikasi</div>
                                        <div class="card-subtitle">Mengidentifikasi Belanja TIK berdasarkan pasangan kode KRO yang telah ditentukan.</div>
                                    </div>
                                </div>
                                <div class="research-header-actions">
                                    <button type="button" class="btn-research" id="runPartD1Button">
                                        <i class="bi bi-play-circle-fill"></i>
                                        {{ $hasilD1->isEmpty() ? 'Jalankan Bagian D.1' : 'Jalankan Ulang Bagian D.1' }}
                                    </button>
                                </div>
                            </div>

                            <div class="card-body">
                                {{-- <div class="research-note">
                                    D.1 dihitung secara deterministic tanpa Gemini. Mapping KRO: <strong>ABO/PBO</strong> Rekomendasi Kebijakan; <strong>CAN/RAN</strong> Pengadaan Sarana; <strong>CCL/RCL</strong> Pemeliharaan Sarana; <strong>CBT/RBT</strong> Pengadaan Prasarana; <strong>CDS/RDS</strong> Pemeliharaan Prasarana; <strong>FAB/UAB</strong> Sistem Informasi Pemerintah; dan <strong>BMA/QMA</strong> Data dan Informasi Publik. RENJA TA aktif menggunakan <strong>alokasi_komponen_0 × 1.000</strong>. Angka bersifat read-only; hanya PENJELASAN yang dapat diedit user selama DRAFT.
                                </div> --}}
                                <div class="research-note">
                                    Tampilan default setelah menjalankan pemeriksaan bagian D.1 merupakan hasil temuan sistem. <strong>Namun user tetap dapat melakukan perubahan secara manual.</strong>
                                </div>

                                @if ($hasilD1->isEmpty())
                                    <div class="research-empty">
                                        Belum ada hasil penelitian Bagian D.1. Klik <strong>Jalankan Bagian D.1</strong> untuk menjalankan pemeriksaan.
                                    </div>
                                @else
                                    <div class="research-table-wrap">
                                        <table class="research-table">
                                            <thead>
                                                <tr>
                                                    <th style="width:48px;">NO</th>
                                                    <th>URAIAN</th>
                                                    <th style="width:155px;">PAGU RENJA<br>(Rp.)</th>
                                                    <th style="width:155px;">PAGU RKA<br>(Rp.)</th>
                                                    <th style="width:155px;">SELISIH<br>(RP.)</th>
                                                    <th>PENJELASAN</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($hasilD1 as $hasil)
                                                    @php
                                                        $kodeBarisD1 = (string) $hasil->kode_baris;
                                                        $isChildD1 = (int) ($hasil->level_baris ?? 0) > 0;
                                                        $renjaD1 = (int) ($hasil->pagu_renja_sistem ?? 0);
                                                        $rkaD1 = (int) ($hasil->pagu_rka_sistem ?? 0);
                                                        $selisihD1 = $rkaD1 - $renjaD1;
                                                        $penjelasanD1 = old(
                                                            "hasil_d1.{$kodeBarisD1}.penjelasan",
                                                            $hasil->penjelasan_efektif ?? $hasil->penjelasan_sistem ?? ''
                                                        );
                                                        $overrideD1 = $hasil->penjelasan_user !== null;
                                                    @endphp

                                                    <tr class="{{ !$isChildD1 ? 'research-parent-row' : '' }}">
                                                        <td class="research-no">
                                                            {{ $kodeBarisD1 === 'D1.TOTAL' ? '1' : '' }}
                                                        </td>
                                                        <td class="research-uraian {{ $isChildD1 ? 'is-child' : '' }}">
                                                            {{ $hasil->uraian }}
                                                            @if ($overrideD1)
                                                                <div class="research-c-system research-c-override-active">Override PENJELASAN user aktif.</div>
                                                            @endif
                                                        </td>
                                                        <td class="research-currency">{{ $formatRupiahC($renjaD1) }}</td>
                                                        <td class="research-currency">{{ $formatRupiahC($rkaD1) }}</td>
                                                        <td class="research-currency {{ $selisihD1 < 0 ? 'research-c-negative' : '' }}">
                                                            {{ $formatRupiahC($selisihD1) }}
                                                        </td>
                                                        <td class="research-explanation-cell">
                                                            <textarea
                                                                name="hasil_d1[{{ $kodeBarisD1 }}][penjelasan]"
                                                                class="form-control research-c-note-input"
                                                                maxlength="65000"
                                                            >{{ $penjelasanD1 }}</textarea>
                                                            <div class="research-c-system">
                                                                Angka hasil sistem bersifat read-only.
                                                                @if ($overrideD1)
                                                                    <span class="research-c-override-active">PENJELASAN diubah user.</span>
                                                                @endif
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                            </div>
                        </section>
                    @endif


                    @if ($mode === 'edit')
                        <section class="main-card" id="bagianD2Section">
                            <div class="card-header">
                                <div class="card-header-left">
                                    <div class="card-icon"><i class="bi bi-cpu"></i></div>
                                    <div>
                                        <div class="card-title">D.2 Indentifikasi Aset Bidang Teknologi Informasi dan Komunikasi</div>
                                        {{-- <div class="card-subtitle">Menghubungkan RKBMN Pemeliharaan dengan alokasi pemeliharaan dan pengadaan aset TIK pada RKA.</div> --}}
                                    </div>
                                </div>
                                <div class="research-header-actions">
                                    <button type="button" class="btn-research" id="runPartD2Button">
                                        <i class="bi bi-play-circle-fill"></i>
                                        {{ $hasilD2->isEmpty() ? 'Jalankan Bagian D.2' : 'Jalankan Ulang Bagian D.2' }}
                                    </button>
                                </div>
                            </div>

                            <div class="card-body">
                                {{-- <div class="research-note">
                                    Gemini hanya digunakan untuk <strong>klasifikasi semantik</strong> nomenklatur aset RKBMN dan detail RKA ke kategori D.2 serta membedakan pemeliharaan/pengadaan. Gemini <strong>tidak menerima tugas menghitung volume atau pagu</strong>. Seluruh angka dihitung deterministic dari database. Jika Gemini gagal, engine tetap berjalan menggunakan fallback keyword. Kolom angka read-only; hanya PENJELASAN dapat diedit selama DRAFT.
                                </div> --}}
                                <div class="research-note">
                                    Tampilan default setelah menjalankan pemeriksaan bagian D.2 merupakan hasil temuan sistem. <strong>Namun user tetap dapat melakukan perubahan secara manual.</strong>
                                </div>

                                @if ($hasilD2->isEmpty())
                                    <div class="research-empty">
                                        Belum ada hasil penelitian Bagian D.2. Klik <strong>Jalankan Bagian D.2</strong> untuk menjalankan pemeriksaan.
                                    </div>
                                @else
                                    <div class="research-table-wrap">
                                        <table class="research-table">
                                            <thead>
                                                <tr>
                                                    <th rowspan="2" style="width:46px;">NO</th>
                                                    <th rowspan="2">URAIAN</th>
                                                    <th rowspan="2" style="width:135px;" class="research-d2-group-header">PEMELIHARAAN<br>RKBMN (Unit)</th>
                                                    <th colspan="2" class="research-d2-group-header">ALOKASI PEMELIHARAAN</th>
                                                    <th colspan="2" class="research-d2-group-header">ALOKASI PENGADAAN</th>
                                                    <th rowspan="2">PENJELASAN</th>
                                                </tr>
                                                <tr>
                                                    <th style="width:90px;">Vol</th>
                                                    <th style="width:145px;">Pagu (Rp.)</th>
                                                    <th style="width:90px;">Vol</th>
                                                    <th style="width:145px;">Pagu (Rp.)</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($hasilD2 as $hasil)
                                                    @php
                                                        $kodeD2 = (string) $hasil->kode_baris;
                                                        $isChildD2 = (int) ($hasil->level_baris ?? 0) > 0;
                                                        $kelompokD2 = (string) ($hasil->kelompok ?? '');
                                                        $groupNumberD2 = !$isChildD2
                                                            ? (in_array($kelompokD2, ['PPD', 'PERANGKAT_PENGOLAH_DATA'], true) ? '1' : '2')
                                                            : '';
                                                        $penjelasanD2 = old(
                                                            "hasil_d2.{$kodeD2}.penjelasan",
                                                            $hasil->penjelasan_efektif ?? $hasil->penjelasan_sistem ?? ''
                                                        );
                                                        $overrideD2 = $hasil->penjelasan_user !== null;
                                                    @endphp

                                                    <tr class="{{ !$isChildD2 ? 'research-parent-row' : '' }}">
                                                        <td class="research-no">{{ $groupNumberD2 }}</td>
                                                        <td class="research-uraian {{ $isChildD2 ? 'is-child' : '' }}">
                                                            {{ $hasil->uraian }}
                                                            @if ((bool) ($hasil->is_dynamic ?? false))
                                                                <div class="research-c-system">Kategori dinamis hasil identifikasi aset.</div>
                                                            @endif
                                                            @if ($overrideD2)
                                                                <div class="research-c-system research-c-override-active">Override PENJELASAN user aktif.</div>
                                                            @endif
                                                        </td>
                                                        <td class="research-d2-number">
                                                            {{ $formatVolumeD2($hasil->rkbmn_pemeliharaan_unit ?? 0) }}
                                                        </td>
                                                        <td class="research-d2-number">
                                                            {{ $formatVolumeD2($hasil->alokasi_pemeliharaan_vol ?? 0) }}
                                                        </td>
                                                        <td class="research-currency">
                                                            {{ $formatRupiahC((int) round((float) ($hasil->alokasi_pemeliharaan_pagu ?? 0))) }}
                                                        </td>
                                                        <td class="research-d2-number">
                                                            {{ $formatVolumeD2($hasil->alokasi_pengadaan_vol ?? 0) }}
                                                        </td>
                                                        <td class="research-currency">
                                                            {{ $formatRupiahC((int) round((float) ($hasil->alokasi_pengadaan_pagu ?? 0))) }}
                                                        </td>
                                                        <td class="research-explanation-cell">
                                                            <textarea
                                                                name="hasil_d2[{{ $kodeD2 }}][penjelasan]"
                                                                class="form-control research-c-note-input"
                                                                maxlength="65000"
                                                            >{{ $penjelasanD2 }}</textarea>
                                                            <div class="research-c-system">
                                                                Angka hasil sistem bersifat read-only.
                                                                @if (!blank($hasil->classification_source ?? null))
                                                                    Klasifikasi: {{ $hasil->classification_source }}.
                                                                @endif
                                                                @if ($overrideD2)
                                                                    <span class="research-c-override-active">PENJELASAN diubah user.</span>
                                                                @endif
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                            </div>
                        </section>
                    @endif


                    @if ($mode === 'edit')
                        <section class="main-card" id="bagianESection">
                            <div class="card-header">
                                <div class="card-header-left">
                                    <div class="card-icon"><i class="bi bi-folder2-open"></i></div>
                                    <div>
                                        <div class="card-title">E Kelengkapan dokumen pendukung RKA-K/L antara lain: RKA Satker, Kerangka Acuan Kerja, Rincian Anggaran Biaya, dan Dokumen Pendukung terkait lainnya</div>
                                        <div class="card-subtitle">Menilai kelengkapan dokumen berdasarkan dokumen yang dipilih pada workspace penelitian.</div>
                                    </div>
                                </div>
                                <div class="research-header-actions">
                                    <button type="button" class="btn-research" id="runPartEButton">
                                        <i class="bi bi-play-circle-fill"></i>
                                        {{ $hasilEMap->isEmpty() ? 'Jalankan Bagian E' : 'Jalankan Ulang Bagian E' }}
                                    </button>
                                </div>
                            </div>

                            <div class="card-body">
                                {{-- <div class="research-note">
                                    Pemeriksaan Bagian E dilakukan secara deterministic tanpa Gemini. <strong>Surat Pengantar</strong> dan <strong>Surat Tugas</strong> default <strong>PERLU KONFIRMASI</strong> karena belum tersedia pada menu upload. RKA Satker, TOR/RAB, RKBMN, dan Data Jumlah Pegawai diperiksa dari dokumen yang tersimpan pada workspace. STATUS dan PENJELASAN dapat diedit selama DRAFT; hasil sistem tetap tersimpan terpisah.
                                </div> --}}
                                <div class="research-note">
                                    Tampilan default setelah menjalankan pemeriksaan bagian E merupakan hasil temuan sistem. <strong>Namun user tetap dapat melakukan perubahan secara manual.</strong>
                                </div>

                                @if ($hasilEMap->isEmpty())
                                    <div class="research-empty">
                                        Belum ada hasil penelitian Bagian E. Klik <strong>Jalankan Bagian E</strong> untuk menjalankan pemeriksaan.
                                    </div>
                                @else
                                    <div class="research-table-wrap">
                                        <table class="research-table">
                                            <thead>
                                                <tr>
                                                    <th style="width:50px;">NO</th>
                                                    <th>URAIAN</th>
                                                    <th style="width:190px;">STATUS</th>
                                                    <th>PENJELASAN</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($hasilERows as $kodeBaris => $uraian)
                                                    @php
                                                        $hasil = $hasilEMap->get($kodeBaris);
                                                        $effectiveStatus = old(
                                                            "hasil_e.{$kodeBaris}.status",
                                                            $hasil?->status_efektif
                                                                ?? $hasil?->status_sistem
                                                                ?? 'PERLU_KONFIRMASI'
                                                        );
                                                        $effectiveExplanation = old(
                                                            "hasil_e.{$kodeBaris}.penjelasan",
                                                            $hasil?->penjelasan_efektif
                                                                ?? $hasil?->penjelasan_sistem
                                                                ?? ''
                                                        );
                                                        $systemStatusClass = strtolower(
                                                            str_replace(
                                                                '_',
                                                                '-',
                                                                (string) ($hasil?->status_sistem ?? 'PERLU_KONFIRMASI')
                                                            )
                                                        );
                                                    @endphp

                                                    <tr>
                                                        <td class="research-no">{{ $loop->iteration }}</td>
                                                        <td class="research-uraian">{{ $uraian }}</td>
                                                        <td class="research-status-cell">
                                                            <select
                                                                name="hasil_e[{{ $kodeBaris }}][status]"
                                                                class="form-control"
                                                            >
                                                                <option value="LENGKAP" @selected($effectiveStatus === 'LENGKAP')>LENGKAP</option>
                                                                <option value="BELUM_LENGKAP" @selected($effectiveStatus === 'BELUM_LENGKAP')>BELUM LENGKAP</option>
                                                                <option value="PERLU_KONFIRMASI" @selected($effectiveStatus === 'PERLU_KONFIRMASI')>PERLU KONFIRMASI</option>
                                                            </select>

                                                            <div class="system-result">
                                                                Hasil sistem:
                                                                <span class="system-status {{ $systemStatusClass }}">
                                                                    {{ str_replace('_', ' ', $hasil?->status_sistem ?? '-') }}
                                                                </span>
                                                            </div>
                                                        </td>

                                                        <td class="research-explanation-cell">
                                                            <textarea
                                                                name="hasil_e[{{ $kodeBaris }}][penjelasan]"
                                                                class="form-control research-explanation"
                                                                maxlength="65000"
                                                            >{{ $effectiveExplanation }}</textarea>

                                                            @if ($hasil?->penjelasan_user !== null || $hasil?->status_user !== null)
                                                                <div class="system-result">
                                                                    <strong>Override user aktif.</strong>
                                                                    Hasil sistem awal tetap tersimpan untuk traceability.
                                                                </div>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                            </div>
                        </section>
                    @endif


                    @if ($mode === 'edit')
                        <section class="main-card" id="bagianFSection">
                            <div class="card-header">
                                <div class="card-header-left">
                                    <div class="card-icon"><i class="bi bi-journal-text"></i></div>
                                    <div>
                                        <div class="card-title">F CATATAN LAIN-LAIN</div>
                                        <div class="card-subtitle">Catatan sistem merangkum hasil A-E dan warning tambahan; peneliti dapat menambah, mengedit, atau menghapus catatan selama DRAFT.</div>
                                    </div>
                                </div>
                                <div class="research-header-actions">
                                    <button type="button" class="btn-research" id="runPartFButton">
                                        <i class="bi bi-play-circle-fill"></i>
                                        {{ $hasilFSystem->isEmpty() ? 'Jalankan Bagian F' : 'Jalankan Ulang Bagian F' }}
                                    </button>
                                </div>
                            </div>

                            <div class="card-body">
                                {{-- <div class="research-note">
                                    Bagian F bersifat dinamis. Sistem merangkum hasil A-E, memvalidasi kebutuhan gaji/honor terhadap Data Jumlah Pegawai, dan melakukan double-check setiap RAB terhadap scope RKA yang relevan. RKA tetap authoritative; perbedaan RKA-RAB hanya menjadi catatan F dan tidak mengubah hasil A-E. Gemini, jika tersedia, hanya merapikan redaksi structured findings dan tidak boleh membuat fakta atau angka baru.
                                </div> --}}
                                <div class="research-note">
                                    Tampilan default setelah menjalankan pemeriksaan bagian F merupakan hasil temuan sistem. <strong>Namun user tetap dapat melakukan perubahan secara manual.</strong>
                                </div>

                                <div class="research-table-wrap">
                                    <table class="research-table" id="partFTable">
                                        <thead>
                                            <tr>
                                                <th style="width:55px;">NO</th>
                                                <th>URAIAN</th>
                                            </tr>
                                        </thead>
                                        <tbody id="partFSystemBody">
                                            @foreach ($hasilFSystem as $catatan)
                                                @php
                                                    $effectiveFText = old(
                                                        "hasil_f_system.{$catatan->catatanID}.catatan",
                                                        $catatan->catatan_efektif ?? $catatan->catatan_sistem ?? ''
                                                    );
                                                    $deletedF = (int) ($catatan->dihapus_user ?? 0) === 1;
                                                    $sourceLabelF = ($catatan->sumber_catatan ?? '') === 'SYSTEM_AI'
                                                        ? 'SYSTEM · AI NARRATIVE'
                                                        : 'SYSTEM · RULE';
                                                @endphp

                                                <tr class="{{ $deletedF ? 'research-f-row-hidden' : '' }}" data-f-system-row>
                                                    <td class="research-no">{{ $loop->iteration }}</td>
                                                    <td class="research-explanation-cell">
                                                        <div class="research-f-source">{{ $sourceLabelF }}</div>

                                                        <textarea
                                                            name="hasil_f_system[{{ $catatan->catatanID }}][catatan]"
                                                            class="form-control research-c-note-input"
                                                            maxlength="65000"
                                                        >{{ $effectiveFText }}</textarea>

                                                        <div class="research-f-actions">
                                                            <label class="research-f-delete-label">
                                                                <input
                                                                    type="checkbox"
                                                                    name="hasil_f_system[{{ $catatan->catatanID }}][dihapus]"
                                                                    value="1"
                                                                    @checked($deletedF)
                                                                    data-f-hide-checkbox
                                                                >
                                                                Sembunyikan catatan sistem dari hasil efektif
                                                            </label>

                                                            @if ($catatan->catatan_user !== null)
                                                                <span class="research-c-system research-c-override-active">
                                                                    Redaksi diubah user.
                                                                </span>
                                                            @endif
                                                        </div>

                                                        <div class="research-c-system">
                                                            Catatan sistem asli tetap tersimpan untuk traceability.
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>

                                        <tbody id="partFUserBody">
                                            @foreach ($hasilFUser as $catatan)
                                                <tr data-f-user-row>
                                                    <td class="research-no" data-f-user-number>
                                                        {{ $hasilFSystem->count() + $loop->iteration }}
                                                    </td>
                                                    <td class="research-explanation-cell">
                                                        <div class="research-f-source user">CATATAN USER</div>

                                                        <input
                                                            type="hidden"
                                                            name="hasil_f_user[existing_{{ $catatan->catatanID }}][catatan_id]"
                                                            value="{{ $catatan->catatanID }}"
                                                        >

                                                        <textarea
                                                            name="hasil_f_user[existing_{{ $catatan->catatanID }}][catatan]"
                                                            class="form-control research-c-note-input"
                                                            maxlength="65000"
                                                            placeholder="Tulis catatan tambahan..."
                                                        >{{ old("hasil_f_user.existing_{$catatan->catatanID}.catatan", $catatan->catatan_user ?? '') }}</textarea>

                                                        <button
                                                            type="button"
                                                            class="research-f-remove"
                                                            data-f-remove
                                                        >
                                                            <i class="bi bi-trash3"></i>
                                                            Hapus catatan
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                @if ($hasilFSystem->isEmpty() && $hasilFUser->isEmpty())
                                    <div class="research-empty" id="partFEmpty">
                                        Belum ada Catatan Lain-Lain. Klik <strong>Jalankan Bagian F</strong> untuk menghasilkan catatan sistem, atau tambahkan catatan secara manual.
                                    </div>
                                @endif

                                <div class="research-f-add">
                                    <button type="button" class="btn-secondary" id="addPartFUserNote">
                                        <i class="bi bi-plus-circle"></i>
                                        Tambah Catatan Manual
                                    </button>
                                </div>
                            </div>
                        </section>
                    @endif

                    @if ($mode === 'edit')
                        <section class="main-card" id="finalisasiChpSection">
                            <div class="card-header">
                                <div class="card-header-left">
                                    <div class="card-icon"><i class="bi bi-lock-fill"></i></div>
                                    <div>
                                        <div class="card-title">Finalisasi CHP</div>
                                        <div class="card-subtitle">Finalisasi mengunci seluruh workspace dan menjadikan CHP tidak dapat diedit kembali.</div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-body">
                                <div class="finalization-grid">
                                    @foreach ($finalizationReadiness['checks'] ?? [] as $check)
                                        <div class="finalization-check {{ $check['ready'] ? 'is-ready' : 'is-missing' }}">
                                            <i class="bi {{ $check['ready'] ? 'bi-check-circle-fill' : 'bi-exclamation-circle-fill' }}"></i>
                                            <div>
                                                <strong>{{ $check['label'] }}</strong><br>
                                                {{ $check['detail'] }}
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="finalization-warning">
                                    <strong>Setelah FINAL:</strong> dokumen sumber, hasil A-F, catatan, Peneliti, dan Perwakilan tidak dapat diubah; research engine tidak dapat dijalankan ulang. Tampilan FINAL dan hasil cetak selalu menggunakan data yang sudah tersimpan saat finalisasi. Kolom tanda tangan pada cetakan tetap kosong untuk tanda tangan fisik.
                                </div>
                            </div>
                        </section>
                    @endif

                    <div class="form-actions">
                        <div class="action-note">
                            @if ($mode === 'edit')
                                Tanggal Penelitian tidak berubah saat DRAFT disimpan ulang. Perubahan sumber akan dicatat pada activity log.
                            @else
                                Workspace pertama kali disimpan dengan status DRAFT dan tanggal penelitian diambil saat penyimpanan berhasil.
                            @endif
                        </div>
                        <div class="button-group">
                            <a href="{{ route('penelitian.index') }}" class="btn-secondary"><i class="bi bi-x-lg"></i> Batal</a>

                            {{-- 
                                workspace_action WAJIB dikirim lewat hidden input.
                                Submit button akan di-disable oleh JavaScript setelah event submit;
                                disabled submit button tidak ikut dalam form payload browser.
                            --}}
                            <input
                                type="hidden"
                                id="workspaceActionInput"
                                name="workspace_action"
                                value="save"
                            >

                            <button
                                id="saveButton"
                                type="submit"
                                value="save"
                                class="btn-primary"
                            >
                                <i class="bi bi-floppy"></i> Simpan DRAFT
                            </button>

                            @if ($mode === 'edit')
                                <button
                                    id="finalizeButton"
                                    type="submit"
                                    value="finalize"
                                    class="btn-finalize"
                                    @disabled(!($finalizationReadiness['all_ready'] ?? false))
                                    title="{{ ($finalizationReadiness['all_ready'] ?? false) ? 'Simpan perubahan terakhir lalu finalisasi CHP' : 'Jalankan/lengkapi seluruh Bagian A-F terlebih dahulu' }}"
                                >
                                    <i class="bi bi-lock-fill"></i> Finalisasi CHP
                                </button>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
        </main>
    </div>
</div>

@include('partials.document-processing-modal')
<script src="{{ asset('js/document-processing-modal.js') }}"></script>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const unit2Data = @json($unitEselon2);
    const satkerData = @json($satker);
    const documentEndpoint = @json(route('penelitian.documents'));
    const runPartAEndpoint = @json($mode === 'edit' ? route('penelitian.run-a', $penelitian->penelitianID) : null);
    const runPartBEndpoint = @json($mode === 'edit' ? route('penelitian.run-b', $penelitian->penelitianID) : null);
    const runPartCEndpoint = @json($mode === 'edit' ? route('penelitian.run-c', $penelitian->penelitianID) : null);
    const runPartDEndpoint = @json($mode === 'edit' ? route('penelitian.run-d', $penelitian->penelitianID) : null);
    const runPartD1Endpoint = @json($mode === 'edit' ? route('penelitian.run-d1', $penelitian->penelitianID) : null);
    const runPartD2Endpoint = @json($mode === 'edit' ? route('penelitian.run-d2', $penelitian->penelitianID) : null);
    const runPartEEndpoint = @json($mode === 'edit' ? route('penelitian.run-e', $penelitian->penelitianID) : null);
    const runPartFEndpoint = @json($mode === 'edit' ? route('penelitian.run-f', $penelitian->penelitianID) : null);
    const csrfToken = @json(csrf_token());
    const finalizeButton = document.getElementById('finalizeButton');
    const workspaceActionInput = document.getElementById('workspaceActionInput');

    const initialE1 = @json($valueE1);
    const initialE2 = @json($valueE2);
    const initialSatker = @json($valueSatker);
    const initialSelection = @json($baselineSelection);
    const initialContext = @json($baselineContext);
    const displaySelection = {
        RENJA: @json($selectedRenja ?: null),
        RKBMN: @json($selectedRkbmn ?: null),
        JUMLAH_PEGAWAI: @json($selectedJumlahPegawai ?: null),
        RKA: @json($selectedRka ?: null),
        TOR: @json(array_values((array) $selectedTor)),
        RAB: @json(array_values((array) $selectedRab)),
    };

    const isEdit = @json($mode === 'edit');
    const hasResearchResults = @json((bool) $hasResearchResults);

    const e1Select = document.getElementById('kode_unit_eselon1');
    const e2Select = document.getElementById('kode_unit_eselon2');
    const satkerSelect = document.getElementById('kode_satker');
    const form = document.getElementById('workspaceForm');
    const saveButton = document.getElementById('saveButton');

    const singleControls = {
        RENJA: document.getElementById('renja_document_id'),
        RKBMN: document.getElementById('rkbmn_document_id'),
        JUMLAH_PEGAWAI: document.getElementById('jumlah_pegawai_document_id'),
        RKA: document.getElementById('rka_document_id'),
    };

    let requestToken = 0;

    function escapeHtml(value) {
        return String(value ?? '')
            .replaceAll('&', '&amp;')
            .replaceAll('<', '&lt;')
            .replaceAll('>', '&gt;')
            .replaceAll('"', '&quot;')
            .replaceAll("'", '&#039;');
    }

    function formatDate(value) {
        if (!value) return '';
        const parsed = new Date(value);
        if (Number.isNaN(parsed.getTime())) return String(value);
        return new Intl.DateTimeFormat('id-ID', { dateStyle: 'medium', timeStyle: 'short' }).format(parsed);
    }

    function optionMeta(item) {
        const meta = item.meta || {};
        const parts = [];
        if (meta.tahun_anggaran) parts.push(`TA ${meta.tahun_anggaran}`);
        if (meta.tanggal_data) parts.push(`Data ${meta.tanggal_data}`);
        if (meta.scope) parts.push(meta.scope);
        if (item.created_at) parts.push(formatDate(item.created_at));
        return parts.filter(Boolean).join(' · ');
    }

    function setState(role, text, cssClass = '') {
        const el = document.getElementById(`state-${role}`);
        if (!el) return;
        el.textContent = text || '';
        el.className = `document-state ${cssClass}`.trim();
    }

    function resetDocuments(message = 'Pilih Satker terlebih dahulu.') {
        Object.entries(singleControls).forEach(([role, select]) => {
            select.innerHTML = `<option value="">${escapeHtml(message)}</option>`;
            select.disabled = true;
            setState(role, '');
        });
        document.getElementById('torList').innerHTML = `<div class="multi-placeholder">${escapeHtml(message)}</div>`;
        document.getElementById('rabList').innerHTML = `<div class="multi-placeholder">${escapeHtml(message)}</div>`;
        setState('TOR', '');
        setState('RAB', '');
    }

    function populateUnit2(selected = '') {
        const e1 = e1Select.value;
        const rows = unit2Data.filter(item => String(item.kode_unit_eselon1) === String(e1));
        e2Select.innerHTML = '<option value="">Pilih Unit Eselon II</option>';
        rows.forEach(item => {
            const option = document.createElement('option');
            option.value = item.kode_unit_eselon2;
            option.textContent = `${item.kode_unit_eselon2} - ${item.nama_unit_eselon2}`;
            option.selected = String(selected) === String(item.kode_unit_eselon2);
            e2Select.appendChild(option);
        });
        e2Select.disabled = !e1;
    }

    function populateSatker(selected = '') {
        const e2 = e2Select.value;
        const rows = satkerData.filter(item => String(item.kode_unit_eselon2) === String(e2));
        satkerSelect.innerHTML = '<option value="">Pilih Satker</option>';
        rows.forEach(item => {
            const option = document.createElement('option');
            option.value = item.kode_satker;
            option.textContent = `${item.kode_satker} - ${item.nama_satker}`;
            option.selected = String(selected) === String(item.kode_satker);
            satkerSelect.appendChild(option);
        });
        satkerSelect.disabled = !e2;
    }

    function populateSingle(role, items, selected) {
        const select = singleControls[role];
        select.innerHTML = '<option value="">-- Tidak dipilih --</option>';

        (items || []).forEach(item => {
            const option = document.createElement('option');
            option.value = item.documentID;
            const meta = optionMeta(item);
            option.textContent = `${item.document_name}${meta ? ` — ${meta}` : ''}`;
            option.selected = String(selected || '') === String(item.documentID);
            select.appendChild(option);
        });

        select.disabled = false;
        const count = (items || []).length;
        setState(role, count ? `${count} dokumen tersedia.` : 'Tidak ada dokumen yang sesuai dengan Satker.', count ? '' : 'empty');
    }

    function populateMulti(role, items, selectedValues) {
        const target = document.getElementById(role === 'TOR' ? 'torList' : 'rabList');
        const inputName = role === 'TOR' ? 'tor_document_ids[]' : 'rab_document_ids[]';
        const selectedSet = new Set((selectedValues || []).map(String));

        if (!items || items.length === 0) {
            target.innerHTML = '<div class="multi-placeholder">Tidak ada dokumen yang sesuai dengan Satker.</div>';
            setState(role, '0 dokumen tersedia.', 'empty');
            return;
        }

        target.innerHTML = items.map(item => {
            const checked = selectedSet.has(String(item.documentID)) ? 'checked' : '';
            const meta = optionMeta(item);
            return `
                <label class="multi-item">
                    <input type="checkbox" name="${inputName}" value="${escapeHtml(item.documentID)}" ${checked}>
                    <span class="multi-copy">
                        <span class="multi-title">${escapeHtml(item.document_name)}</span>
                        <span class="multi-meta">${escapeHtml(meta || item.documentID)}</span>
                    </span>
                </label>`;
        }).join('');
        setState(role, `${items.length} dokumen tersedia.`);
    }

    async function loadDocumentOptions(useInitialSelection = false) {
        if (!e1Select.value || !e2Select.value || !satkerSelect.value) {
            resetDocuments();
            return;
        }

        const token = ++requestToken;
        resetDocuments('Memuat dokumen...');
        ['RENJA','RKBMN','JUMLAH_PEGAWAI','RKA','TOR','RAB'].forEach(role => setState(role, 'Memuat...', 'loading'));

        const params = new URLSearchParams({
            kode_unit_eselon1: e1Select.value,
            kode_unit_eselon2: e2Select.value,
            kode_satker: satkerSelect.value,
        });

        try {
            const response = await fetch(`${documentEndpoint}?${params.toString()}`, {
                headers: { 'Accept': 'application/json' },
                credentials: 'same-origin',
            });
            const payload = await response.json();

            if (token !== requestToken) return;
            if (!response.ok || !payload.success) {
                throw new Error(payload.message || 'Gagal memuat dokumen.');
            }

            const selected = useInitialSelection ? displaySelection : currentSelection();
            populateSingle('RENJA', payload.documents.RENJA, selected.RENJA);
            populateSingle('RKBMN', payload.documents.RKBMN, selected.RKBMN);
            populateSingle('JUMLAH_PEGAWAI', payload.documents.JUMLAH_PEGAWAI, selected.JUMLAH_PEGAWAI);
            populateSingle('RKA', payload.documents.RKA, selected.RKA);
            populateMulti('TOR', payload.documents.TOR, selected.TOR);
            populateMulti('RAB', payload.documents.RAB, selected.RAB);
        } catch (error) {
            if (token !== requestToken) return;
            resetDocuments('Dokumen gagal dimuat');
            ['RENJA','RKBMN','JUMLAH_PEGAWAI','RKA','TOR','RAB'].forEach(role => setState(role, error.message, 'empty'));
        }
    }

    function currentSelection() {
        return {
            RENJA: singleControls.RENJA.value || null,
            RKBMN: singleControls.RKBMN.value || null,
            JUMLAH_PEGAWAI: singleControls.JUMLAH_PEGAWAI.value || null,
            RKA: singleControls.RKA.value || null,
            TOR: Array.from(document.querySelectorAll('input[name="tor_document_ids[]"]:checked')).map(el => el.value).sort(),
            RAB: Array.from(document.querySelectorAll('input[name="rab_document_ids[]"]:checked')).map(el => el.value).sort(),
        };
    }

    function signature(selection) {
        const normalized = {
            RENJA: selection.RENJA || null,
            RKBMN: selection.RKBMN || null,
            JUMLAH_PEGAWAI: selection.JUMLAH_PEGAWAI || null,
            RKA: selection.RKA || null,
            TOR: [...(selection.TOR || [])].map(String).sort(),
            RAB: [...(selection.RAB || [])].map(String).sort(),
        };
        return JSON.stringify(normalized);
    }

    function currentContext() {
        return {
            kode_unit_eselon1: e1Select.value || null,
            kode_unit_eselon2: e2Select.value || null,
            kode_satker: satkerSelect.value || null,
        };
    }

    e1Select.addEventListener('change', () => {
        populateUnit2('');
        populateSatker('');
        resetDocuments();
    });

    e2Select.addEventListener('change', () => {
        populateSatker('');
        resetDocuments();
    });

    satkerSelect.addEventListener('change', () => loadDocumentOptions(false));

    const runPartAButton = document.getElementById('runPartAButton');

    if (runPartAButton && runPartAEndpoint) {
        runPartAButton.addEventListener('click', async () => {
            const sourceOrContextChanged = signature(currentSelection()) !== signature(initialSelection)
                || JSON.stringify(currentContext()) !== JSON.stringify(initialContext);

            if (sourceOrContextChanged) {
                window.alert('Dokumen sumber atau Satker pada form telah berubah tetapi belum disimpan. Simpan DRAFT terlebih dahulu sebelum menjalankan penelitian Bagian A.');
                return;
            }

            runPartAButton.disabled = true;

            if (window.DocumentProcessingModal) {
                window.DocumentProcessingModal.showLoading({
                    title: 'Menjalankan Penelitian Bagian A',
                    message: 'Sistem sedang membandingkan RENJA, RKA, dan TOR sesuai business rule Bagian A. Mohon tunggu hingga proses selesai.'
                });
            }

            try {
                const response = await fetch(runPartAEndpoint, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    credentials: 'same-origin',
                    body: JSON.stringify({}),
                });

                let payload = null;
                try {
                    payload = await response.json();
                } catch (parseError) {
                    throw new Error('Server tidak mengembalikan response JSON yang valid.');
                }

                if (!response.ok || !payload.success) {
                    throw new Error(payload.message || 'Penelitian Bagian A gagal dijalankan.');
                }

                const statusRows = payload.result?.statuses || {};
                const detail = Object.entries(statusRows)
                    .map(([code, status]) => `${code}: ${String(status).replaceAll('_', ' ')}`)
                    .join('\n');

                if (window.DocumentProcessingModal) {
                    window.DocumentProcessingModal.showSuccess({
                        title: payload.title || 'Bagian A Berhasil Diproses',
                        message: payload.message || 'Hasil Bagian A berhasil disimpan.',
                        details: detail,
                        buttonText: 'LIHAT HASIL',
                        onClose: () => window.location.reload(),
                    });
                } else {
                    window.alert(payload.message || 'Bagian A berhasil diproses.');
                    window.location.reload();
                }
            } catch (error) {
                runPartAButton.disabled = false;

                if (window.DocumentProcessingModal) {
                    window.DocumentProcessingModal.showError({
                        title: 'Bagian A Gagal Diproses',
                        message: error.message || 'Terjadi kesalahan saat menjalankan penelitian Bagian A.',
                        buttonText: 'TUTUP',
                    });
                } else {
                    window.alert(error.message || 'Bagian A gagal diproses.');
                }
            }
        });
    }

    const runPartBButton = document.getElementById('runPartBButton');

    if (runPartBButton && runPartBEndpoint) {
        runPartBButton.addEventListener('click', async () => {
            const sourceOrContextChanged = signature(currentSelection()) !== signature(initialSelection)
                || JSON.stringify(currentContext()) !== JSON.stringify(initialContext);

            if (sourceOrContextChanged) {
                window.alert('Dokumen sumber atau Satker pada form telah berubah tetapi belum disimpan. Simpan DRAFT terlebih dahulu sebelum menjalankan penelitian Bagian B.');
                return;
            }

            runPartBButton.disabled = true;

            if (window.DocumentProcessingModal) {
                window.DocumentProcessingModal.showLoading({
                    title: 'Menjalankan Penelitian Bagian B',
                    message: 'Sistem sedang membandingkan pagu RENJA dan RKA serta memeriksa Belanja Pegawai/Belanja Barang pada level Komponen. Mohon tunggu hingga proses selesai.'
                });
            }

            try {
                const response = await fetch(runPartBEndpoint, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    credentials: 'same-origin',
                    body: JSON.stringify({}),
                });

                let payload = null;
                try {
                    payload = await response.json();
                } catch (parseError) {
                    throw new Error('Server tidak mengembalikan response JSON yang valid.');
                }

                if (!response.ok || !payload.success) {
                    throw new Error(payload.message || 'Penelitian Bagian B gagal dijalankan.');
                }

                const statusRows = payload.result?.statuses || {};
                const detail = Object.entries(statusRows)
                    .map(([code, status]) => `${code}: ${String(status).replaceAll('_', ' ')}`)
                    .join('\n');

                if (window.DocumentProcessingModal) {
                    window.DocumentProcessingModal.showSuccess({
                        title: payload.title || 'Bagian B Berhasil Diproses',
                        message: payload.message || 'Hasil Bagian B berhasil disimpan.',
                        details: detail,
                        buttonText: 'LIHAT HASIL',
                        onClose: () => window.location.reload(),
                    });
                } else {
                    window.alert(payload.message || 'Bagian B berhasil diproses.');
                    window.location.reload();
                }
            } catch (error) {
                runPartBButton.disabled = false;

                if (window.DocumentProcessingModal) {
                    window.DocumentProcessingModal.showError({
                        title: 'Bagian B Gagal Diproses',
                        message: error.message || 'Terjadi kesalahan saat menjalankan penelitian Bagian B.',
                        buttonText: 'TUTUP',
                    });
                } else {
                    window.alert(error.message || 'Bagian B gagal diproses.');
                }
            }
        });
    }


    const runPartCButton = document.getElementById('runPartCButton');

    if (runPartCButton && runPartCEndpoint) {
        runPartCButton.addEventListener('click', async () => {
            const sourceOrContextChanged = signature(currentSelection()) !== signature(initialSelection)
                || JSON.stringify(currentContext()) !== JSON.stringify(initialContext);

            if (sourceOrContextChanged) {
                window.alert('Dokumen sumber atau Satker pada form telah berubah tetapi belum disimpan. Simpan DRAFT terlebih dahulu sebelum menjalankan penelitian Bagian C.');
                return;
            }

            runPartCButton.disabled = true;

            if (window.DocumentProcessingModal) {
                window.DocumentProcessingModal.showLoading({
                    title: 'Menjalankan Penelitian Bagian C',
                    message: 'Sistem sedang menghitung rincian kegiatan, sumber dana, dan klasifikasi belanja berdasarkan RENJA TA aktif dan RKA. Mohon tunggu hingga proses selesai.'
                });
            }

            try {
                const response = await fetch(runPartCEndpoint, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    credentials: 'same-origin',
                    body: JSON.stringify({}),
                });

                let payload = null;
                try {
                    payload = await response.json();
                } catch (parseError) {
                    throw new Error('Server tidak mengembalikan response JSON yang valid.');
                }

                if (!response.ok || !payload.success) {
                    throw new Error(payload.message || 'Penelitian Bagian C gagal dijalankan.');
                }

                const result = payload.result || {};
                const detail = [
                    `Jumlah baris: ${result.jumlah_baris ?? 0}`,
                    `Total RENJA: ${formatCurrencyForModal(result.total_renja ?? 0)}`,
                    `Total RKA: ${formatCurrencyForModal(result.total_rka ?? 0)}`,
                    `Selisih RKA - RENJA: ${formatCurrencyForModal(result.selisih ?? 0)}`,
                ].join('\n');

                if (window.DocumentProcessingModal) {
                    window.DocumentProcessingModal.showSuccess({
                        title: payload.title || 'Bagian C Berhasil Diproses',
                        message: payload.message || 'Hasil Bagian C berhasil disimpan.',
                        details: detail,
                        buttonText: 'LIHAT HASIL',
                        onClose: () => window.location.reload(),
                    });
                } else {
                    window.alert(payload.message || 'Bagian C berhasil diproses.');
                    window.location.reload();
                }
            } catch (error) {
                runPartCButton.disabled = false;

                if (window.DocumentProcessingModal) {
                    window.DocumentProcessingModal.showError({
                        title: 'Bagian C Gagal Diproses',
                        message: error.message || 'Terjadi kesalahan saat menjalankan penelitian Bagian C.',
                        buttonText: 'TUTUP',
                    });
                } else {
                    window.alert(error.message || 'Bagian C gagal diproses.');
                }
            }
        });
    }


    const runPartDButton = document.getElementById('runPartDButton');

    if (runPartDButton && runPartDEndpoint) {
        runPartDButton.addEventListener('click', async () => {
            const sourceOrContextChanged = signature(currentSelection()) !== signature(initialSelection)
                || JSON.stringify(currentContext()) !== JSON.stringify(initialContext);

            if (sourceOrContextChanged) {
                window.alert('Dokumen sumber atau Satker pada form telah berubah tetapi belum disimpan. Simpan DRAFT terlebih dahulu sebelum menjalankan penelitian Bagian D.');
                return;
            }

            runPartDButton.disabled = true;

            if (window.DocumentProcessingModal) {
                window.DocumentProcessingModal.showLoading({
                    title: 'Menjalankan Penelitian Bagian D',
                    message: 'Sistem sedang menyiapkan tujuh kategori Budget Tagging sesuai FORMAT CHP. Mohon tunggu hingga proses selesai.'
                });
            }

            try {
                const response = await fetch(runPartDEndpoint, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    credentials: 'same-origin',
                    body: JSON.stringify({}),
                });

                let payload = null;
                try {
                    payload = await response.json();
                } catch (parseError) {
                    throw new Error('Server tidak mengembalikan response JSON yang valid.');
                }

                if (!response.ok || !payload.success) {
                    throw new Error(payload.message || 'Penelitian Bagian D gagal dijalankan.');
                }

                const result = payload.result || {};
                const detail = [
                    `Jumlah kategori: ${result.jumlah_baris ?? 0}`,
                    'Automatic tagging: tidak digunakan pada baseline MVP',
                    'Nilai sistem awal: PAGU RENJA Rp0, PAGU RKA Rp0',
                ].join('\n');

                if (window.DocumentProcessingModal) {
                    window.DocumentProcessingModal.showSuccess({
                        title: payload.title || 'Bagian D Berhasil Diproses',
                        message: payload.message || 'Hasil Bagian D berhasil disimpan.',
                        details: detail,
                        buttonText: 'LIHAT HASIL',
                        onClose: () => window.location.reload(),
                    });
                } else {
                    window.alert(payload.message || 'Bagian D berhasil diproses.');
                    window.location.reload();
                }
            } catch (error) {
                runPartDButton.disabled = false;

                if (window.DocumentProcessingModal) {
                    window.DocumentProcessingModal.showError({
                        title: 'Bagian D Gagal Diproses',
                        message: error.message || 'Terjadi kesalahan saat menjalankan penelitian Bagian D.',
                        buttonText: 'TUTUP',
                    });
                } else {
                    window.alert(error.message || 'Bagian D gagal diproses.');
                }
            }
        });
    }


    const runPartD1Button = document.getElementById('runPartD1Button');

    if (runPartD1Button && runPartD1Endpoint) {
        runPartD1Button.addEventListener('click', async () => {
            const sourceOrContextChanged = signature(currentSelection()) !== signature(initialSelection)
                || JSON.stringify(currentContext()) !== JSON.stringify(initialContext);

            if (sourceOrContextChanged) {
                window.alert('Dokumen sumber atau Satker pada form telah berubah tetapi belum disimpan. Simpan DRAFT terlebih dahulu sebelum menjalankan penelitian Bagian D.1.');
                return;
            }

            runPartD1Button.disabled = true;

            if (window.DocumentProcessingModal) {
                window.DocumentProcessingModal.showLoading({
                    title: 'Menjalankan Penelitian Bagian D.1',
                    message: 'Sistem sedang menghitung Belanja TIK berdasarkan mapping KRO pada RENJA dan RKA. Mohon tunggu hingga proses selesai.'
                });
            }

            try {
                const response = await fetch(runPartD1Endpoint, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    credentials: 'same-origin',
                    body: JSON.stringify({}),
                });

                let payload = null;

                try {
                    payload = await response.json();
                } catch (parseError) {
                    throw new Error('Server tidak mengembalikan response JSON yang valid.');
                }

                if (!response.ok || !payload.success) {
                    throw new Error(payload.message || 'Penelitian Bagian D.1 gagal dijalankan.');
                }

                const result = payload.result || {};

                const detail = [
                    `Jumlah baris: ${result.jumlah_baris ?? 0}`,
                    `Pagu RENJA Belanja TIK: ${formatCurrencyForModal(result.pagu_renja ?? 0)}`,
                    `Pagu RKA Belanja TIK: ${formatCurrencyForModal(result.pagu_rka ?? 0)}`,
                    `Selisih RKA - RENJA: ${formatCurrencyForModal(result.selisih ?? 0)}`,
                ].join('\n');

                if (window.DocumentProcessingModal) {
                    window.DocumentProcessingModal.showSuccess({
                        title: payload.title || 'Bagian D.1 Berhasil Diproses',
                        message: payload.message || 'Hasil D.1 berhasil disimpan.',
                        details: detail,
                        buttonText: 'LIHAT HASIL',
                        onClose: () => window.location.reload(),
                    });
                } else {
                    window.alert(payload.message || 'Bagian D.1 berhasil diproses.');
                    window.location.reload();
                }
            } catch (error) {
                runPartD1Button.disabled = false;

                if (window.DocumentProcessingModal) {
                    window.DocumentProcessingModal.showError({
                        title: 'Bagian D.1 Gagal Diproses',
                        message: error.message || 'Terjadi kesalahan saat menjalankan penelitian Bagian D.1.',
                        buttonText: 'TUTUP',
                    });
                } else {
                    window.alert(error.message || 'Bagian D.1 gagal diproses.');
                }
            }
        });
    }


    const runPartD2Button = document.getElementById('runPartD2Button');

    if (runPartD2Button && runPartD2Endpoint) {
        runPartD2Button.addEventListener('click', async () => {
            const sourceOrContextChanged = signature(currentSelection()) !== signature(initialSelection)
                || JSON.stringify(currentContext()) !== JSON.stringify(initialContext);

            if (sourceOrContextChanged) {
                window.alert('Dokumen sumber atau Satker pada form telah berubah tetapi belum disimpan. Simpan DRAFT terlebih dahulu sebelum menjalankan penelitian Bagian D.2.');
                return;
            }

            runPartD2Button.disabled = true;

            if (window.DocumentProcessingModal) {
                window.DocumentProcessingModal.showLoading({
                    title: 'Menjalankan Penelitian Bagian D.2',
                    message: 'Sistem sedang mengidentifikasi aset TIK, mengklasifikasikan nomenklatur RKBMN/RKA, lalu menghitung volume dan pagu secara deterministic. Mohon tunggu hingga proses selesai.'
                });
            }

            try {
                const response = await fetch(runPartD2Endpoint, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    credentials: 'same-origin',
                    body: JSON.stringify({}),
                });

                let payload = null;

                try {
                    payload = await response.json();
                } catch (parseError) {
                    throw new Error('Server tidak mengembalikan response JSON yang valid.');
                }

                if (!response.ok || !payload.success) {
                    throw new Error(payload.message || 'Penelitian Bagian D.2 gagal dijalankan.');
                }

                const result = payload.result || {};

                const detail = [
                    `Jumlah baris: ${result.jumlah_baris ?? 0}`,
                    `Kategori dinamis: ${result.jumlah_dynamic ?? 0}`,
                    `Pagu pemeliharaan RKA: ${formatCurrencyForModal(result.alokasi_pemeliharaan_pagu ?? 0)}`,
                    `Pagu pengadaan RKA: ${formatCurrencyForModal(result.alokasi_pengadaan_pagu ?? 0)}`,
                    `Gemini digunakan: ${result.gemini_digunakan ? 'ya' : 'tidak'}`,
                    `Fallback digunakan: ${result.fallback_digunakan ? 'ya' : 'tidak'}`,
                ].join('\n');

                if (window.DocumentProcessingModal) {
                    window.DocumentProcessingModal.showSuccess({
                        title: payload.title || 'Bagian D.2 Berhasil Diproses',
                        message: payload.message || 'Hasil D.2 berhasil disimpan.',
                        details: detail,
                        buttonText: 'LIHAT HASIL',
                        onClose: () => window.location.reload(),
                    });
                } else {
                    window.alert(payload.message || 'Bagian D.2 berhasil diproses.');
                    window.location.reload();
                }
            } catch (error) {
                runPartD2Button.disabled = false;

                if (window.DocumentProcessingModal) {
                    window.DocumentProcessingModal.showError({
                        title: 'Bagian D.2 Gagal Diproses',
                        message: error.message || 'Terjadi kesalahan saat menjalankan penelitian Bagian D.2.',
                        buttonText: 'TUTUP',
                    });
                } else {
                    window.alert(error.message || 'Bagian D.2 gagal diproses.');
                }
            }
        });
    }


    const runPartEButton = document.getElementById('runPartEButton');

    if (runPartEButton && runPartEEndpoint) {
        runPartEButton.addEventListener('click', async () => {
            const sourceOrContextChanged = signature(currentSelection()) !== signature(initialSelection)
                || JSON.stringify(currentContext()) !== JSON.stringify(initialContext);

            if (sourceOrContextChanged) {
                window.alert('Dokumen sumber atau Satker pada form telah berubah tetapi belum disimpan. Simpan DRAFT terlebih dahulu sebelum menjalankan penelitian Bagian E.');
                return;
            }

            runPartEButton.disabled = true;

            if (window.DocumentProcessingModal) {
                window.DocumentProcessingModal.showLoading({
                    title: 'Menjalankan Penelitian Bagian E',
                    message: 'Sistem sedang memeriksa kelengkapan dokumen pendukung yang tersimpan pada workspace penelitian.'
                });
            }

            try {
                const response = await fetch(runPartEEndpoint, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    credentials: 'same-origin',
                    body: JSON.stringify({}),
                });

                let payload = null;

                try {
                    payload = await response.json();
                } catch (parseError) {
                    throw new Error('Server tidak mengembalikan response JSON yang valid.');
                }

                if (!response.ok || !payload.success) {
                    throw new Error(payload.message || 'Penelitian Bagian E gagal dijalankan.');
                }

                const result = payload.result || {};
                const summary = result.status_summary || {};

                const detail = [
                    `Jumlah baris: ${result.jumlah_baris ?? 0}`,
                    `LENGKAP: ${summary.LENGKAP ?? 0}`,
                    `BELUM LENGKAP: ${summary.BELUM_LENGKAP ?? 0}`,
                    `PERLU KONFIRMASI: ${summary.PERLU_KONFIRMASI ?? 0}`,
                ].join('\n');

                if (window.DocumentProcessingModal) {
                    window.DocumentProcessingModal.showSuccess({
                        title: payload.title || 'Bagian E Berhasil Diproses',
                        message: payload.message || 'Hasil Bagian E berhasil disimpan.',
                        details: detail,
                        buttonText: 'LIHAT HASIL',
                        onClose: () => window.location.reload(),
                    });
                } else {
                    window.alert(payload.message || 'Bagian E berhasil diproses.');
                    window.location.reload();
                }
            } catch (error) {
                runPartEButton.disabled = false;

                if (window.DocumentProcessingModal) {
                    window.DocumentProcessingModal.showError({
                        title: 'Bagian E Gagal Diproses',
                        message: error.message || 'Terjadi kesalahan saat menjalankan penelitian Bagian E.',
                        buttonText: 'TUTUP',
                    });
                } else {
                    window.alert(error.message || 'Bagian E gagal diproses.');
                }
            }
        });
    }


    const runPartFButton = document.getElementById('runPartFButton');

    if (runPartFButton && runPartFEndpoint) {
        runPartFButton.addEventListener('click', async () => {
            const sourceOrContextChanged = signature(currentSelection()) !== signature(initialSelection)
                || JSON.stringify(currentContext()) !== JSON.stringify(initialContext);

            if (sourceOrContextChanged) {
                window.alert('Dokumen sumber atau Satker pada form telah berubah tetapi belum disimpan. Simpan DRAFT terlebih dahulu sebelum menjalankan penelitian Bagian F.');
                return;
            }

            runPartFButton.disabled = true;

            if (window.DocumentProcessingModal) {
                window.DocumentProcessingModal.showLoading({
                    title: 'Menjalankan Penelitian Bagian F',
                    message: 'Sistem sedang merangkum hasil A-E, memvalidasi Data Jumlah Pegawai, dan melakukan double-check RKA terhadap RAB.'
                });
            }

            try {
                const response = await fetch(runPartFEndpoint, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    credentials: 'same-origin',
                    body: JSON.stringify({}),
                });

                let payload = null;

                try {
                    payload = await response.json();
                } catch (parseError) {
                    throw new Error('Server tidak mengembalikan response JSON yang valid.');
                }

                if (!response.ok || !payload.success) {
                    throw new Error(payload.message || 'Penelitian Bagian F gagal dijalankan.');
                }

                const result = payload.result || {};

                const detail = [
                    `Catatan sistem: ${result.jumlah_catatan_sistem ?? 0}`,
                    `Temuan validasi pegawai: ${result.jumlah_temuan_pegawai ?? 0}`,
                    `Temuan double-check RKA-RAB: ${result.jumlah_temuan_rka_rab ?? 0}`,
                    `Gemini narrative: ${result.gemini_digunakan ? 'digunakan' : 'fallback deterministic'}`,
                ];

                if (result.gemini_warning) {
                    detail.push(`Catatan Gemini: ${result.gemini_warning}`);
                }

                if (window.DocumentProcessingModal) {
                    window.DocumentProcessingModal.showSuccess({
                        title: payload.title || 'Bagian F Berhasil Diproses',
                        message: payload.message || 'Catatan Lain-Lain berhasil disimpan.',
                        details: detail.join('\n'),
                        buttonText: 'LIHAT HASIL',
                        onClose: () => window.location.reload(),
                    });
                } else {
                    window.alert(payload.message || 'Bagian F berhasil diproses.');
                    window.location.reload();
                }
            } catch (error) {
                runPartFButton.disabled = false;

                if (window.DocumentProcessingModal) {
                    window.DocumentProcessingModal.showError({
                        title: 'Bagian F Gagal Diproses',
                        message: error.message || 'Terjadi kesalahan saat menjalankan penelitian Bagian F.',
                        buttonText: 'TUTUP',
                    });
                } else {
                    window.alert(error.message || 'Bagian F gagal diproses.');
                }
            }
        });
    }

    function formatCurrencyForModal(value) {
        const numeric = Number(value || 0);
        const absolute = Math.abs(Math.round(numeric)).toLocaleString('id-ID');
        return `${numeric < 0 ? '-' : ''}Rp${absolute}`;
    }

    function readCAmount(input) {
        if (!input) return 0;

        const value = String(input.value ?? '').trim();

        if (value === '') {
            return 0;
        }

        const parsed = Number(value);
        return Number.isFinite(parsed) ? Math.round(parsed) : 0;
    }

    function refreshCDifference(row) {
        const renjaInput = row.querySelector('[data-c-renja]');
        const rkaInput = row.querySelector('[data-c-rka]');
        const differenceCell = row.querySelector('[data-c-selisih]');

        if (!renjaInput || !rkaInput || !differenceCell) {
            return;
        }

        const difference = readCAmount(rkaInput) - readCAmount(renjaInput);

        differenceCell.dataset.cSelisihValue = String(difference);
        differenceCell.classList.toggle('is-negative', difference < 0);

        // Preserve helper text while refreshing the visible nominal.
        differenceCell.innerHTML = `${formatCurrencyForModal(difference)}<div class="research-c-system">Otomatis · tidak dapat diedit</div>`;
    }

    document.querySelectorAll('[data-c-row]').forEach((row) => {
        row.querySelectorAll('[data-c-renja], [data-c-rka]').forEach((input) => {
            input.addEventListener('input', () => refreshCDifference(row));
            input.addEventListener('change', () => refreshCDifference(row));
        });

        refreshCDifference(row);
    });


    function readDAmount(input) {
        if (!input) return 0;

        const value = String(input.value ?? '').trim();

        if (value === '') {
            return 0;
        }

        const parsed = Number(value);
        return Number.isFinite(parsed) ? Math.round(parsed) : 0;
    }

    function refreshDDifference(row) {
        const renjaInput = row.querySelector('[data-d-renja]');
        const rkaInput = row.querySelector('[data-d-rka]');
        const differenceCell = row.querySelector('[data-d-selisih]');

        if (!renjaInput || !rkaInput || !differenceCell) {
            return;
        }

        const difference = readDAmount(rkaInput) - readDAmount(renjaInput);

        differenceCell.dataset.dSelisihValue = String(difference);
        differenceCell.classList.toggle('is-negative', difference < 0);
        differenceCell.innerHTML = `${formatCurrencyForModal(difference)}<div class="research-c-system">Otomatis · tidak dapat diedit</div>`;
    }

    document.querySelectorAll('[data-d-row]').forEach((row) => {
        row.querySelectorAll('[data-d-renja], [data-d-rka]').forEach((input) => {
            input.addEventListener('input', () => refreshDDifference(row));
            input.addEventListener('change', () => refreshDDifference(row));
        });

        refreshDDifference(row);
    });


    const partFUserBody = document.getElementById('partFUserBody');
    const addPartFUserNoteButton = document.getElementById('addPartFUserNote');
    let partFNewCounter = 0;

    function refreshPartFNumbers() {
        const systemRows = document.querySelectorAll('#partFSystemBody [data-f-system-row]');
        const userRows = document.querySelectorAll('#partFUserBody [data-f-user-row]');

        systemRows.forEach((row, index) => {
            const no = row.querySelector('.research-no');
            if (no) no.textContent = String(index + 1);
        });

        userRows.forEach((row, index) => {
            const no = row.querySelector('[data-f-user-number]');
            if (no) no.textContent = String(systemRows.length + index + 1);
        });

        const empty = document.getElementById('partFEmpty');
        if (empty && (systemRows.length > 0 || userRows.length > 0)) {
            empty.style.display = 'none';
        }
    }

    function bindPartFRemoveButton(button) {
        button.addEventListener('click', () => {
            const row = button.closest('[data-f-user-row]');
            if (row) {
                row.remove();
                refreshPartFNumbers();
            }
        });
    }

    document.querySelectorAll('[data-f-remove]').forEach(bindPartFRemoveButton);

    document.querySelectorAll('[data-f-hide-checkbox]').forEach((checkbox) => {
        checkbox.addEventListener('change', () => {
            const row = checkbox.closest('[data-f-system-row]');
            if (row) {
                row.classList.toggle(
                    'research-f-row-hidden',
                    checkbox.checked
                );
            }
        });
    });

    if (addPartFUserNoteButton && partFUserBody) {
        addPartFUserNoteButton.addEventListener('click', () => {
            partFNewCounter++;
            const key = `new_${Date.now()}_${partFNewCounter}`;

            const row = document.createElement('tr');
            row.setAttribute('data-f-user-row', '');

            row.innerHTML = `
                <td class="research-no" data-f-user-number></td>
                <td class="research-explanation-cell">
                    <div class="research-f-source user">CATATAN USER</div>
                    <textarea
                        name="hasil_f_user[${key}][catatan]"
                        class="form-control research-c-note-input"
                        maxlength="65000"
                        placeholder="Tulis catatan tambahan..."
                    ></textarea>
                    <button
                        type="button"
                        class="research-f-remove"
                        data-f-remove
                    >
                        <i class="bi bi-trash3"></i>
                        Hapus catatan
                    </button>
                </td>
            `;

            partFUserBody.appendChild(row);

            const removeButton = row.querySelector('[data-f-remove]');
            if (removeButton) {
                bindPartFRemoveButton(removeButton);
            }

            refreshPartFNumbers();

            const textarea = row.querySelector('textarea');
            if (textarea) textarea.focus();
        });
    }

    refreshPartFNumbers();

    form.addEventListener('submit', (event) => {
        const sourceOrContextChanged = signature(currentSelection()) !== signature(initialSelection)
            || JSON.stringify(currentContext()) !== JSON.stringify(initialContext);

        const submitter = event.submitter;
        const action = submitter?.value || 'save';
        const finalizeRequested = action === 'finalize';

        // Simpan intent submit ke hidden input SEBELUM tombol submit di-disable.
        // Dengan demikian backend tetap menerima workspace_action=finalize.
        if (workspaceActionInput) {
            workspaceActionInput.value = action;
        }

        if (finalizeRequested && sourceOrContextChanged) {
            event.preventDefault();
            window.alert(
                'Finalisasi tidak dapat dilakukan bersamaan dengan perubahan dokumen sumber/Satker. Simpan DRAFT terlebih dahulu, jalankan ulang Bagian A-F yang terinvalidasi, kemudian finalisasi.'
            );
            return;
        }

        if (isEdit && hasResearchResults && sourceOrContextChanged) {
            const proceed = window.confirm(
                'Dokumen sumber atau ruang lingkup Satker penelitian berubah. Seluruh hasil penelitian yang dihasilkan sistem akan di-invalidasi/dihapus dan penelitian wajib dijalankan kembali. Catatan manual user tetap dipertahankan. Lanjutkan menyimpan DRAFT?'
            );
            if (!proceed) {
                event.preventDefault();
                return;
            }
        }

        if (finalizeRequested) {
            const proceed = window.confirm(
                'Finalisasi CHP akan menyimpan seluruh perubahan pada form lalu mengunci penelitian menjadi FINAL. Setelah FINAL, dokumen sumber, hasil penelitian, catatan, Peneliti, dan Perwakilan tidak dapat diedit atau dijalankan ulang. Lanjutkan finalisasi?'
            );

            if (!proceed) {
                event.preventDefault();
                return;
            }
        }

        if (!event.defaultPrevented) {
            saveButton.disabled = true;

            if (finalizeButton) {
                finalizeButton.disabled = true;
            }

            if (finalizeRequested && finalizeButton) {
                finalizeButton.innerHTML = '<i class="bi bi-hourglass-split"></i> Memfinalisasi...';
            } else {
                saveButton.innerHTML = '<i class="bi bi-hourglass-split"></i> Menyimpan...';
            }
        }
    });

    populateUnit2(initialE2);
    populateSatker(initialSatker);

    if (initialSatker) {
        loadDocumentOptions(true);
    } else {
        resetDocuments();
    }
});
</script>
</body>
</html>
