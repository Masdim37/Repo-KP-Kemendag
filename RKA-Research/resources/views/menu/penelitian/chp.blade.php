<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>CHP - {{ $snapshot['penelitian']->nama_satker }}</title>

    <style>
        :root {
            --line: #25394d;
            --head: #d9e7f4;
            --ink: #172b3f;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            color: var(--ink);
            background: #edf2f7;
            font-family: Arial, Helvetica, sans-serif;
        }

        .screen-toolbar {
            position: sticky;
            top: 0;
            z-index: 20;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            padding: 12px 18px;
            border-bottom: 1px solid #d8e0e8;
            background: rgba(255,255,255,.97);
        }

        .toolbar-left {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .toolbar-title {
            font-size: 14px;
            font-weight: 800;
        }

        .toolbar-subtitle {
            margin-top: 2px;
            color: #6a8197;
            font-size: 11px;
        }

        .toolbar-actions {
            display: flex;
            gap: 8px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 36px;
            padding: 0 13px;
            border: 1px solid #c9d5df;
            border-radius: 8px;
            color: #284b69;
            background: #fff;
            font-size: 11px;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
        }

        .btn-primary {
            border-color: #165a9b;
            color: #fff;
            background: #165a9b;
        }

        .paper-wrap { padding: 18px; }

        .paper {
            width: 210mm;
            min-height: 297mm;
            margin: 0 auto;
            padding: 8mm;
            background: #fff;
            box-shadow: 0 8px 35px rgba(35,55,75,.12);
        }

        .chp-title {
            margin-bottom: 5px;
            text-align: center;
            font-size: 8pt;
            line-height: 1.35;
            font-weight: 700;
        }

        .chp-title .main { font-size: 9pt; }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .meta-table,
        .section-table { margin-top: 3px; }

        th,
        td {
            border: .35pt solid var(--line);
            padding: 2px 3px;
            font-size: 5.8pt;
            line-height: 1.32;
            vertical-align: top;
            overflow-wrap: anywhere;
        }

        th {
            background: var(--head);
            text-align: center;
            font-weight: 700;
        }

        .meta-label {
            width: 22%;
            font-weight: 700;
            background: #fbfcfd;
        }

        .section-title {
            margin-top: 5px;
            padding: 2px 4px;
            border: .35pt solid var(--line);
            border-bottom: 0;
            font-size: 6.2pt;
            font-weight: 700;
            background: #fff;
        }

        .no {
            width: 4%;
            text-align: center;
        }

        .money {
            white-space: nowrap;
            text-align: right;
            font-variant-numeric: tabular-nums;
        }

        .status {
            text-align: center;
            font-weight: 700;
        }

        .child { padding-left: 12px; }
        .preline { white-space: pre-line; }

        .final-info {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            margin-top: 5px;
            font-size: 5.5pt;
            color: #526577;
        }

        .signature-table { margin-top: 7px; }

        .signature-table td {
            width: 50%;
            padding: 5px 8px 7px;
            border: .35pt solid var(--line);
            vertical-align: top;
        }

        .signature-heading {
            margin-bottom: 5px;
            font-size: 6.2pt;
            font-weight: 700;
        }

        .signature-slot {
            display: grid;
            grid-template-columns: 16px 1fr;
            column-gap: 5px;
            min-height: 24mm;
            margin-bottom: 2mm;
        }

        .signature-number {
            padding-top: 1px;
            font-size: 5.8pt;
        }

        .signature-content {
            position: relative;
            min-height: 23mm;
        }

        .signature-space { height: 15mm; }

        .signature-name {
            min-height: 10px;
            padding-bottom: 2px;
            border-bottom: .35pt dotted #333;
            font-size: 6pt;
        }

        .signature-empty { color: transparent; }

        tr { break-inside: avoid; }
        thead { display: table-header-group; }

        @page {
            size: A4 portrait;
            margin: 8mm;
        }

        @media print {
            body { background: #fff; }
            .screen-toolbar { display: none !important; }
            .paper-wrap { padding: 0; }

            .paper {
                width: auto;
                min-height: 0;
                margin: 0;
                padding: 0;
                box-shadow: none;
            }

            .section-title { break-after: avoid; }
            .signature-table { break-inside: avoid; }
        }

        @media (max-width: 900px) {
            .paper-wrap { overflow-x: auto; }
        }
    </style>
</head>
<body>
@php
    $p = $snapshot['penelitian'];

    $rupiah = function ($value) {
        $value = (float) ($value ?? 0);
        $sign = $value < 0 ? '-' : '';

        return $sign . 'Rp' . number_format(abs($value), 0, ',', '.');
    };

    $volume = function ($value) {
        $number = (float) ($value ?? 0);

        if (abs($number - round($number)) < 0.000001) {
            return number_format($number, 0, ',', '.');
        }

        return rtrim(
            rtrim(number_format($number, 2, ',', '.'), '0'),
            ','
        );
    };
@endphp

<div class="screen-toolbar">
    <div class="toolbar-left">
        <a href="{{ route('penelitian.index') }}" class="btn">← Daftar Penelitian</a>
        <div>
            <div class="toolbar-title">CHP FINAL - {{ $p->nama_satker }}</div>
            <div class="toolbar-subtitle">
                Finalisasi {{ \Carbon\Carbon::parse($p->finalized_at)->translatedFormat('d F Y H:i') }}
                · oleh {{ $p->finalizer_name ?? $p->finalized_by }}
            </div>
        </div>
    </div>

    <div class="toolbar-actions">
        @if (!$printMode)
            <a href="{{ route('penelitian.chp.print', $p->penelitianID) }}" class="btn">Mode Cetak</a>
        @endif
        <button type="button" id="printButton" class="btn btn-primary">Cetak / Simpan PDF</button>
    </div>
</div>

<div class="paper-wrap">
<article class="paper">
    <div class="chp-title">
        <div class="main">CATATAN HASIL PENELITIAN (CHP)</div>
        <div>KERTAS KERJA RENCANA KERJA DAN ANGGARAN KEMENTERIAN/LEMBAGA (RKA-K/L)</div>
        <div>PAGU ANGGARAN TAHUN ANGGARAN {{ $p->tahun_anggaran }}</div>
        <div>KEMENTERIAN PERDAGANGAN</div>
    </div>

    <table class="meta-table">
        <tr><td class="meta-label">UNIT ESELON I</td><td>{{ $p->kode_unit_eselon1 }} | {{ $p->nama_unit_eselon1 }}</td></tr>
        <tr><td class="meta-label">UNIT ESELON II</td><td>{{ $p->kode_unit_eselon2 }} | {{ $p->nama_unit_eselon2 }}</td></tr>
        <tr><td class="meta-label">SATKER</td><td>{{ $p->kode_satker }} | {{ $p->nama_satker }}</td></tr>
        <tr>
            <td class="meta-label">PROGRAM - KEGIATAN</td>
            <td>
                @forelse ($snapshot['programKegiatan'] as $pk)
                    <div>{{ $pk->kode_program }} | {{ $pk->nama_program }} → {{ $pk->kode_kegiatan }} | {{ $pk->nama_kegiatan }}</div>
                @empty
                    -
                @endforelse
            </td>
        </tr>
        <tr><td class="meta-label">TANGGAL PENELITIAN</td><td>{{ \Carbon\Carbon::parse($p->tanggal_penelitian)->translatedFormat('d F Y') }}</td></tr>
        <tr><td class="meta-label">TEMPAT</td><td>{{ $p->tempat }}</td></tr>
        <tr><td class="meta-label">TOTAL ANGGARAN UNIT KERJA ESELON I/SATKER</td><td>{{ $rupiah($p->total_anggaran) }}</td></tr>
    </table>

    <div class="section-title">A &nbsp; Konsistensi Pencantuman Sasaran Kinerja dalam RKA-K/L dengan Sasaran Kinerja dalam Renja K/L dan RKP</div>
    <table class="section-table">
        <thead><tr><th class="no">NO</th><th style="width:35%">KELUARAN (OUTPUT)/KOMPONEN & IKK</th><th style="width:18%">STATUS</th><th>PENJELASAN</th></tr></thead>
        <tbody>
            @foreach ($snapshot['A'] as $row)
                <tr><td class="no">{{ $loop->iteration }}</td><td>{{ $row->uraian }}</td><td class="status">{{ str_replace('_', ' ', $row->status_efektif) }}</td><td class="preline">{{ $row->penjelasan_efektif }}</td></tr>
            @endforeach
        </tbody>
    </table>

    <div class="section-title">B &nbsp; Kesesuaian Total Pagu dalam RENJA-K/L dengan RKA-K/L</div>
    <table class="section-table">
        <thead><tr><th class="no">NO</th><th style="width:35%">URAIAN</th><th style="width:18%">STATUS</th><th>PENJELASAN</th></tr></thead>
        <tbody>
            @foreach ($snapshot['B'] as $row)
                @php
                    $bNo = match ((string) $row->kode_baris) {
                        'B1' => '1',
                        'B2' => '2',
                        'B3' => '3',
                        default => '',
                    };
                @endphp
                <tr><td class="no">{{ $bNo }}</td><td class="{{ (int) $row->level_baris > 0 ? 'child' : '' }}">{{ $row->uraian }}</td><td class="status">{{ str_replace('_', ' ', $row->status_efektif) }}</td><td class="preline">{{ $row->penjelasan_efektif }}</td></tr>
            @endforeach
        </tbody>
    </table>

    <div class="section-title">C &nbsp; Kesesuaian sumber dana dalam RKA-K/L dengan sumber dana yang ditetapkan dalam Pagu Anggaran K/L</div>
    <table class="section-table">
        <thead><tr><th class="no">NO</th><th style="width:31%">RINCIAN</th><th style="width:15%">PAGU RENJA</th><th style="width:15%">PAGU RKA-K/L</th><th style="width:15%">SELISIH</th><th>CATATAN</th></tr></thead>
        <tbody>
            @foreach ($snapshot['C'] as $row)
                <tr><td class="no">{{ (int) $row->level_baris === 0 ? preg_replace('/\D+/', '', $row->kode_baris) : '' }}</td><td class="{{ (int) $row->level_baris > 0 ? 'child' : '' }}">{{ $row->uraian }}</td><td class="money">{{ $rupiah($row->pagu_renja_efektif) }}</td><td class="money">{{ $rupiah($row->pagu_rka_efektif) }}</td><td class="money">{{ $rupiah($row->selisih_efektif) }}</td><td class="preline">{{ $row->penjelasan_efektif }}</td></tr>
            @endforeach
        </tbody>
    </table>

    <div class="section-title">D &nbsp; Kepatuhan dan Ketepatan dalam penandaan (Budget Tagging) sesuai dengan kategori pada semua Keluaran yang dihasilkan</div>
    <table class="section-table">
        <thead><tr><th class="no">NO</th><th style="width:31%">URAIAN</th><th style="width:15%">PAGU RENJA</th><th style="width:15%">PAGU RKA</th><th style="width:15%">SELISIH</th><th>PENJELASAN</th></tr></thead>
        <tbody>
            @foreach ($snapshot['D'] as $row)
                <tr><td class="no">{{ $loop->iteration }}</td><td>{{ $row->uraian }}</td><td class="money">{{ $rupiah($row->pagu_renja_efektif) }}</td><td class="money">{{ $rupiah($row->pagu_rka_efektif) }}</td><td class="money">{{ $rupiah($row->selisih_efektif) }}</td><td class="preline">{{ $row->penjelasan_efektif }}</td></tr>
            @endforeach
        </tbody>
    </table>

    <div class="section-title">D.1 &nbsp; Indentifikasi KRO Belanja Bidang Teknologi Informasi dan Komunikasi</div>
    <table class="section-table">
        <thead><tr><th class="no">NO</th><th style="width:31%">URAIAN</th><th style="width:15%">PAGU RENJA</th><th style="width:15%">PAGU RKA</th><th style="width:15%">SELISIH</th><th>PENJELASAN</th></tr></thead>
        <tbody>
            @foreach ($snapshot['D1'] as $row)
                <tr><td class="no">{{ (int) $row->level_baris === 0 ? '1' : '' }}</td><td class="{{ (int) $row->level_baris > 0 ? 'child' : '' }}">{{ $row->uraian }}</td><td class="money">{{ $rupiah($row->pagu_renja_efektif) }}</td><td class="money">{{ $rupiah($row->pagu_rka_efektif) }}</td><td class="money">{{ $rupiah($row->selisih_efektif) }}</td><td class="preline">{{ $row->penjelasan_efektif }}</td></tr>
            @endforeach
        </tbody>
    </table>

    <div class="section-title">D.2 &nbsp; Indentifikasi Aset Bidang Teknologi Informasi dan Komunikasi</div>
    <table class="section-table">
        <thead>
            <tr><th rowspan="2" class="no">NO</th><th rowspan="2" style="width:25%">URAIAN</th><th rowspan="2" style="width:12%">PEMELIHARAAN RKBMN (Unit)</th><th colspan="2">ALOKASI PEMELIHARAAN</th><th colspan="2">ALOKASI PENGADAAN</th><th rowspan="2" style="width:20%">PENJELASAN</th></tr>
            <tr><th>Vol</th><th>Pagu</th><th>Vol</th><th>Pagu</th></tr>
        </thead>
        <tbody>
            @foreach ($snapshot['D2'] as $row)
                @php
                    $groupNo = '';
                    if ((int) $row->level_baris === 0) {
                        $groupNo = $row->kelompok === 'PERANGKAT_PENGOLAH_DATA' ? '1' : '2';
                    }
                @endphp
                <tr><td class="no">{{ $groupNo }}</td><td class="{{ (int) $row->level_baris > 0 ? 'child' : '' }}">{{ $row->uraian }}</td><td class="money">{{ $volume($row->rkbmn_pemeliharaan_unit_efektif) }}</td><td class="money">{{ $volume($row->alokasi_pemeliharaan_vol_efektif) }}</td><td class="money">{{ $rupiah($row->alokasi_pemeliharaan_pagu_efektif) }}</td><td class="money">{{ $volume($row->alokasi_pengadaan_vol_efektif) }}</td><td class="money">{{ $rupiah($row->alokasi_pengadaan_pagu_efektif) }}</td><td class="preline">{{ $row->penjelasan_efektif }}</td></tr>
            @endforeach
        </tbody>
    </table>

    <div class="section-title">E &nbsp; Kelengkapan dokumen pendukung RKA-K/L antara lain: RKA Satker, Kerangka Acuan Kerja, Rincian Anggaran Biaya, dan Dokumen Pendukung terkait lainnya</div>
    <table class="section-table">
        <thead><tr><th class="no">NO</th><th style="width:35%">URAIAN</th><th style="width:18%">STATUS</th><th>PENJELASAN</th></tr></thead>
        <tbody>
            @foreach ($snapshot['E'] as $row)
                <tr><td class="no">{{ $loop->iteration }}</td><td>{{ $row->uraian }}</td><td class="status">{{ str_replace('_', ' ', $row->status_efektif) }}</td><td class="preline">{{ $row->penjelasan_efektif }}</td></tr>
            @endforeach
        </tbody>
    </table>

    <div class="section-title">F &nbsp; CATATAN LAIN-LAIN</div>
    <table class="section-table">
        <thead><tr><th class="no">NO</th><th>URAIAN</th></tr></thead>
        <tbody>
            @forelse ($snapshot['F'] as $row)
                <tr><td class="no">{{ $loop->iteration }}</td><td class="preline">{{ $row->catatan_efektif }}</td></tr>
            @empty
                <tr><td class="no">1</td><td>-</td></tr>
            @endforelse
        </tbody>
    </table>

    <table class="signature-table">
        <tr>
            <td>
                <div class="signature-heading">Peneliti RKA/K-L</div>
                @for ($i = 1; $i <= 3; $i++)
                    <div class="signature-slot">
                        <div class="signature-number">{{ $i }}</div>
                        <div class="signature-content">
                            <div class="signature-space"></div>
                            <div class="signature-name {{ blank($snapshot['peneliti'][$i] ?? null) ? 'signature-empty' : '' }}">
                                {{ $snapshot['peneliti'][$i] ?? '........................................................' }}
                            </div>
                        </div>
                    </div>
                @endfor
            </td>
            <td>
                <div class="signature-heading">Perwakilan Unit yang diteliti</div>
                @for ($i = 1; $i <= 3; $i++)
                    <div class="signature-slot">
                        <div class="signature-number">{{ $i }}</div>
                        <div class="signature-content">
                            <div class="signature-space"></div>
                            <div class="signature-name {{ blank($snapshot['perwakilan'][$i] ?? null) ? 'signature-empty' : '' }}">
                                {{ $snapshot['perwakilan'][$i] ?? '........................................................' }}
                            </div>
                        </div>
                    </div>
                @endfor
            </td>
        </tr>
    </table>

    <div class="final-info">
        <div>Status: <strong>FINAL</strong> · Finalisasi: {{ \Carbon\Carbon::parse($p->finalized_at)->translatedFormat('d F Y H:i') }}</div>
        <div>Finalizer: {{ $p->finalizer_name ?? $p->finalized_by }}</div>
    </div>
</article>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const printButton = document.getElementById('printButton');
    const endpoint = @json(route('penelitian.chp.print-log', $p->penelitianID));
    const csrf = document.querySelector('meta[name="csrf-token"]')?.content;

    if (!printButton) {
        return;
    }

    printButton.addEventListener('click', async () => {
        printButton.disabled = true;

        try {
            await fetch(endpoint, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrf,
                },
                credentials: 'same-origin',
                body: JSON.stringify({}),
            });
        } catch (error) {
            // Logging tidak boleh menghalangi proses cetak.
        } finally {
            printButton.disabled = false;
            window.print();
        }
    });
});
</script>
</body>
</html>
