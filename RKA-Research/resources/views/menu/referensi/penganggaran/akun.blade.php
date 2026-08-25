<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Referensi Akun | Penelitian RKA-K/L</title>@include('menu.referensi.penganggaran.partials.reference-style')
</head>

<body>
    @php
        $userName = data_get($user ?? null, 'name', session('user_name', 'Pengguna Sistem'));
        $jabatanName = data_get($user ?? null, 'jabatan.jabatan_name', session('jabatan_name', 'Pengguna Sistem'));
        $initials = collect(explode(' ', $userName))
            ->filter()
            ->take(2)
            ->map(fn($word) => strtoupper(substr($word, 0, 1)))
            ->implode('');
    @endphp
    <div class="app-shell">@include('partials.sidebar', [
        'activeMenu' => 'reference-akun',
        'sidebarUserName' => $userName,
        // 'sidebarUserRole' => $jabatanName,
        'sidebarInitials' => $initials,
    ])<div class="app-main">
            <header class="dashboard-header">
                <div class="header-left"><button type="button" class="sidebar-toggle" id="sidebarToggle"><i
                            class="bi bi-list"></i></button>
                    <div>
                        <div class="header-eyebrow">SISTEM INFORMASI PENELITIAN RKA-K/L</div>
                        <div class="header-title">Tambah Data Referensi</div>
                    </div>
                </div>
                <div class="header-user">
                    <div class="header-user-text">Biro Perencanaan<strong>{{ $userName }}</strong></div>
                    <div class="header-avatar">{{ $initials ?: 'US' }}</div>
                </div>
            </header>
            <main class="page-container">
                <div class="content-wrapper">
                    <div class="info-box"><i class="bi bi-info-circle-fill"></i>
                        <div>Setiap Akun yang disimpan akan otomatis dibuat atau digunakan kembali pada master
                            <strong>akun</strong>, lalu dipetakan ke Komponen (<strong>komponen_akun</strong>) atau ke
                            Subkomponen spesifik Satker (<strong>subkomponen_akun</strong>).
                        </div>
                    </div>
                    @if (session('success'))
                        <div class="alert success">{{ session('success') }}</div>
                        @endif @if (session('error'))
                            <div class="alert error">{{ session('error') }}</div>
                        @endif
                        <section class="main-card">
                            <div class="card-header">
                                <div class="card-header-left">
                                    <div class="card-header-icon"><i class="bi bi-journal-code"></i></div>
                                    <div>
                                        <h1 class="card-title">Tambah Referensi Akun</h1>
                                        <p class="card-description">Tambahkan Akun sekaligus tentukan penempatannya pada
                                            struktur anggaran.</p>
                                    </div>
                                </div><span class="card-badge">REFERENSI PENGANGGARAN</span>
                            </div>
                            <form id="referenceForm" class="main-form"
                                action="{{ route('referensi.penganggaran.akun.store') }}" method="POST" novalidate>
                                @csrf
                                <div class="section-title">
                                    <div>
                                        <h2>Master Akun</h2><span>Kode Akun dan nama harus mengikuti nomenklatur
                                            resmi.</span>
                                    </div>
                                </div>
                                <div class="form-grid">
                                    <div class="form-group"><label class="form-label">Kode Akun <span
                                                class="required">*</span></label><input id="kodeAkun" name="kode_akun"
                                            class="form-control" maxlength="10" value="{{ old('kode_akun') }}"
                                            placeholder="Contoh: 521111">
                                        <div class="field-error" id="kodeAkunError"></div>
                                    </div>
                                    <div class="form-group"><label class="form-label">Nama Akun <span
                                                class="required">*</span></label><input id="namaAkun" name="nama_akun"
                                            class="form-control" maxlength="255" value="{{ old('nama_akun') }}"
                                            placeholder="Masukkan nama Akun">
                                        <div class="field-error" id="namaAkunError"></div>
                                    </div>
                                    <div class="form-group full"><label class="form-label">Jenis Penempatan <span
                                                class="required">*</span></label><select id="mode"
                                            name="mode_penempatan" class="form-control">
                                            <option value="KOMPONEN" @selected(old('mode_penempatan', 'KOMPONEN') === 'KOMPONEN')>KOMPONEN — Digunakan
                                                langsung pada Komponen tanpa Subkomponen</option>
                                            <option value="SUBKOMPONEN" @selected(old('mode_penempatan') === 'SUBKOMPONEN')>SUBKOMPONEN —
                                                Digunakan pada Subkomponen spesifik Satker</option>
                                        </select>
                                        <div class="mode-note" id="modeNote"></div>
                                    </div>
                                    <div class="subsection hidden-section" id="organizationSection">
                                        <div class="subsection-title">Organisasi dan Satker — wajib untuk penempatan
                                            Subkomponen</div>
                                        <div class="form-grid">
                                            <div class="form-group"><label class="form-label">Unit Eselon I <span
                                                        class="required">*</span></label><select id="unit1"
                                                    name="kode_unit_eselon1" class="form-control">
                                                    <option value="">-- Pilih Unit Eselon I --</option>
                                                    @foreach ($unitEselon1 as $item)
                                                        <option value="{{ $item->kode_unit_eselon1 }}">
                                                            [{{ $item->kode_unit_eselon1 }}]
                                                            {{ $item->nama_unit_eselon1 }}</option>
                                                    @endforeach
                                                </select>
                                                <div class="field-error" id="unit1Error"></div>
                                            </div>
                                            <div class="form-group"><label class="form-label">Unit Eselon II <span
                                                        class="required">*</span></label><select id="unit2"
                                                    name="kode_unit_eselon2" class="form-control" disabled>
                                                    <option value="">-- Pilih Unit Eselon I terlebih dahulu --
                                                    </option>
                                                </select>
                                                <div class="field-error" id="unit2Error"></div>
                                            </div>
                                            <div class="form-group full"><label class="form-label">Satker <span
                                                        class="required">*</span></label><select id="satker"
                                                    name="kode_satker" class="form-control" disabled>
                                                    <option value="">-- Pilih Unit Eselon II terlebih dahulu --
                                                    </option>
                                                </select>
                                                <div class="field-error" id="satkerError"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="subsection" id="budgetSection">
                                        <div class="subsection-title">Hierarki Penempatan Akun</div>
                                        <div class="form-grid">
                                            <div class="form-group"><label class="form-label">Program <span
                                                        class="required">*</span></label><select id="program"
                                                    name="kode_program" class="form-control" disabled>
                                                    <option value="">-- Pilih Program --</option>
                                                </select>
                                                <div class="field-error" id="programError"></div>
                                            </div>
                                            <div class="form-group"><label class="form-label">Kegiatan <span
                                                        class="required">*</span></label><select id="kegiatan"
                                                    name="kode_kegiatan" class="form-control" disabled>
                                                    <option value="">-- Pilih Program terlebih dahulu --</option>
                                                </select>
                                                <div class="field-error" id="kegiatanError"></div>
                                            </div>
                                            <div class="form-group"><label class="form-label">KRO <span
                                                        class="required">*</span></label><select id="kro"
                                                    name="kode_kro" class="form-control" disabled>
                                                    <option value="">-- Pilih Kegiatan terlebih dahulu --
                                                    </option>
                                                </select>
                                                <div class="field-error" id="kroError"></div>
                                            </div>
                                            <div class="form-group"><label class="form-label">RO <span
                                                        class="required">*</span></label><select id="ro"
                                                    name="kode_ro" class="form-control" disabled>
                                                    <option value="">-- Pilih KRO terlebih dahulu --</option>
                                                </select>
                                                <div class="field-error" id="roError"></div>
                                            </div>
                                            <div class="form-group full"><label class="form-label">Komponen <span
                                                        class="required">*</span></label><select id="komponen"
                                                    name="kode_komponen" class="form-control" disabled>
                                                    <option value="">-- Pilih RO terlebih dahulu --</option>
                                                </select>
                                                <div class="field-error" id="komponenError"></div>
                                            </div>
                                            <div class="form-group full hidden-section" id="subkomponenGroup"><label
                                                    class="form-label">Subkomponen <span
                                                        class="required">*</span></label><select id="subkomponen"
                                                    name="kode_subkomponen" class="form-control" disabled>
                                                    <option value="">-- Pilih Komponen terlebih dahulu --
                                                    </option>
                                                </select>
                                                <div class="field-error" id="subkomponenError"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="status-list"><span class="status-chip" id="masterStatus">Master Akun
                                        Otomatis</span><span class="status-chip"
                                        id="placementStatus">Penempatan</span></div>
                                <div class="form-actions">
                                    <div class="action-message" id="actionMessage">Lengkapi kode dan nama Akun.</div>
                                    <button type="submit" class="save-button" id="saveButton" disabled><i
                                            class="bi bi-floppy"></i>Simpan Referensi</button>
                                </div>
                            </form>
                        </section>
                </div>
            </main>
            <footer class="footer">
                <div class="footer-container">
                    <div class="footer-brand"><strong>Kementerian Perdagangan Republik Indonesia</strong>© 2026 Biro
                        Perencanaan. Seluruh Hak Cipta Dilindungi.</div>
                </div>
            </footer>
        </div>
    </div>
    @include('partials.document-processing-modal')
    <script src="{{ asset('js/document-processing-modal.js') }}"></script>@include('menu.referensi.penganggaran.partials.reference-helpers')@include('menu.referensi.penganggaran.partials.cascade-helpers')
    <script>
        const unitEselon2Data = @json($unitEselon2),
            satkerData = @json($satker),
            satkerKegiatanData = @json($satkerKegiatan),
            programData = @json($program),
            kegiatanData = @json($kegiatan),
            kroData = @json($kro),
            roData = @json($ro),
            komponenData = @json($komponen),
            subkomponenData = @json($subkomponen);
        const oldRef = {
            mode: @json(old('mode_penempatan', 'KOMPONEN')),
            unit1: @json(old('kode_unit_eselon1')),
            unit2: @json(old('kode_unit_eselon2')),
            satker: @json(old('kode_satker')),
            program: @json(old('kode_program')),
            kegiatan: @json(old('kode_kegiatan')),
            kro: @json(old('kode_kro')),
            ro: @json(old('kode_ro')),
            komponen: @json(old('kode_komponen')),
            subkomponen: @json(old('kode_subkomponen'))
        };
        const form = document.getElementById('referenceForm'),
            mode = document.getElementById('mode'),
            kode = document.getElementById('kodeAkun'),
            nama = document.getElementById('namaAkun'),
            orgSection = document.getElementById('organizationSection'),
            budgetSection = document.getElementById('budgetSection'),
            subGroup = document.getElementById('subkomponenGroup'),
            unit1 = document.getElementById('unit1'),
            unit2 = document.getElementById('unit2'),
            satker = document.getElementById('satker'),
            program = document.getElementById('program'),
            kegiatan = document.getElementById('kegiatan'),
            kro = document.getElementById('kro'),
            ro = document.getElementById('ro'),
            komponen = document.getElementById('komponen'),
            subkomponen = document.getElementById('subkomponen'),
            saveButton = document.getElementById('saveButton'),
            actionMessage = document.getElementById('actionMessage'),
            modeNote = document.getElementById('modeNote');
        const err = {
            kode: document.getElementById('kodeAkunError'),
            nama: document.getElementById('namaAkunError'),
            unit1: document.getElementById('unit1Error'),
            unit2: document.getElementById('unit2Error'),
            satker: document.getElementById('satkerError'),
            program: document.getElementById('programError'),
            kegiatan: document.getElementById('kegiatanError'),
            kro: document.getElementById('kroError'),
            ro: document.getElementById('roError'),
            komponen: document.getElementById('komponenError'),
            subkomponen: document.getElementById('subkomponenError')
        };

        function resetBudget() {
            const firstPlaceholder = mode.value === 'SUBKOMPONEN' ? '-- Pilih Satker terlebih dahulu --' :
                '-- Pilih Program --';
            [program, kegiatan, kro, ro, komponen, subkomponen].forEach((el, i) => refResetSelect(el, [firstPlaceholder,
                '-- Pilih Program terlebih dahulu --', '-- Pilih Kegiatan terlebih dahulu --',
                '-- Pilih KRO terlebih dahulu --', '-- Pilih RO terlebih dahulu --',
                '-- Pilih Komponen terlebih dahulu --'
            ][i]));
        }

        function populateGlobalProgram(selected = '') {
            refRenderOptions({
                select: program,
                data: programData,
                valueKey: 'kode_program',
                labelKey: 'nama_program',
                placeholder: '-- Pilih Program --',
                selectedValue: selected
            });
        }

        function populateSatkerProgram(selected = '') {
            refPopulateProgramForSatker({
                satkerEl: satker,
                programEl: program,
                satkerKegiatanData,
                kegiatanData,
                programData,
                selectedValue: selected
            });
        }

        function populateKegiatanForMode(selected = '') {
            const allowed = mode.value === 'SUBKOMPONEN' ? refGetKegiatanCodesForSatker(satker.value, satkerKegiatanData) :
                null;
            refPopulateKegiatan({
                programEl: program,
                kegiatanEl: kegiatan,
                kegiatanData,
                selectedValue: selected,
                allowedKegiatanCodes: allowed
            });
        }

        function populateSubkomponen(selected = '') {
            const filtered = subkomponenData.filter(item => refSame(item.kode_satker, satker.value) && refSame(item
                .kode_kegiatan, kegiatan.value) && refSame(item.kode_kro, kro.value) && refSame(item.kode_ro, ro
                .value) && refSame(item.kode_komponen, komponen.value));
            refRenderOptions({
                select: subkomponen,
                data: refUniqueBy(filtered, 'kode_subkomponen'),
                valueKey: 'kode_subkomponen',
                labelKey: 'nama_subkomponen',
                placeholder: '-- Pilih Subkomponen --',
                selectedValue: selected,
                emptyText: '-- Subkomponen tidak tersedia --'
            });
        }

        function applyMode(restore = false) {
            const m = mode.value;
            orgSection.classList.toggle('hidden-section', m !== 'SUBKOMPONEN');
            budgetSection.classList.remove('hidden-section');
            subGroup.classList.toggle('hidden-section', m !== 'SUBKOMPONEN');
            modeNote.textContent = m === 'KOMPONEN' ?
                'Akun otomatis dibuat/digunakan pada master akun, lalu dipetakan ke komponen_akun untuk penggunaan langsung pada Komponen tanpa Subkomponen.' :
                'Akun otomatis dibuat/digunakan pada master akun, lalu dipetakan ke subkomponen_akun pada Subkomponen spesifik Satker.';
            if (!restore) {
                unit1.value = '';
                refResetSelect(unit2, '-- Pilih Unit Eselon I terlebih dahulu --');
                refResetSelect(satker, '-- Pilih Unit Eselon II terlebih dahulu --');
                resetBudget();
                if (m === 'KOMPONEN') populateGlobalProgram();
            }
            updateState(false);
        }

        function updateState(show = false) {
            const master = kode.value.trim() !== '' && nama.value.trim() !== '';
            let placement = false;
            if (mode.value === 'KOMPONEN') placement = !!program.value && !!kegiatan.value && !!kro.value && !!ro.value && !
                !komponen.value;
            if (mode.value === 'SUBKOMPONEN') placement = !!unit1.value && !!unit2.value && !!satker.value && !!program
                .value && !!kegiatan.value && !!kro.value && !!ro.value && !!komponen.value && !!subkomponen.value;
            document.getElementById('masterStatus').classList.toggle('complete', master);
            document.getElementById('placementStatus').classList.toggle('complete', placement);
            if (show) {
                refSetFieldError(kode, err.kode, kode.value.trim() ? '' : 'Kode Akun wajib diisi.');
                refSetFieldError(nama, err.nama, nama.value.trim() ? '' : 'Nama Akun wajib diisi.');
                [
                    ['program', 'Program wajib dipilih.'],
                    ['kegiatan', 'Kegiatan wajib dipilih.'],
                    ['kro', 'KRO wajib dipilih.'],
                    ['ro', 'RO wajib dipilih.'],
                    ['komponen', 'Komponen wajib dipilih.']
                ].forEach(([k, m]) => refSetFieldError(eval(k), err[k], eval(k).value ? '' : m));
                if (mode.value === 'SUBKOMPONEN') {
                    [
                        ['unit1', 'Unit Eselon I wajib dipilih.'],
                        ['unit2', 'Unit Eselon II wajib dipilih.'],
                        ['satker', 'Satker wajib dipilih.'],
                        ['subkomponen', 'Subkomponen wajib dipilih.']
                    ].forEach(([k, m]) => refSetFieldError(eval(k), err[k], eval(k).value ? '' : m));
                }
            }
            const valid = master && placement;
            saveButton.disabled = !valid;
            actionMessage.textContent = valid ? 'Data siap disimpan.' :
                'Lengkapi data Akun dan seluruh hierarki penempatan.';
            return valid;
        }
        mode.value = oldRef.mode || 'KOMPONEN';
        applyMode(true);
        if (mode.value === 'KOMPONEN') {
            populateGlobalProgram(oldRef.program);
            refPopulateKegiatan({
                programEl: program,
                kegiatanEl: kegiatan,
                kegiatanData,
                selectedValue: oldRef.kegiatan
            });
            refPopulateKro({
                kegiatanEl: kegiatan,
                kroEl: kro,
                kroData,
                selectedValue: oldRef.kro
            });
            refPopulateRo({
                kegiatanEl: kegiatan,
                kroEl: kro,
                roEl: ro,
                roData,
                selectedValue: oldRef.ro
            });
            refPopulateKomponen({
                kegiatanEl: kegiatan,
                kroEl: kro,
                roEl: ro,
                komponenEl: komponen,
                komponenData,
                selectedValue: oldRef.komponen
            });
        }
        if (mode.value === 'SUBKOMPONEN') {
            unit1.value = oldRef.unit1 || '';
            refPopulateUnit2({
                unit1El: unit1,
                unit2El: unit2,
                unitEselon2Data,
                selectedValue: oldRef.unit2
            });
            refPopulateSatker({
                unit2El: unit2,
                satkerEl: satker,
                satkerData,
                selectedValue: oldRef.satker
            });
            populateSatkerProgram(oldRef.program);
            populateKegiatanForMode(oldRef.kegiatan);
            refPopulateKro({
                kegiatanEl: kegiatan,
                kroEl: kro,
                kroData,
                selectedValue: oldRef.kro
            });
            refPopulateRo({
                kegiatanEl: kegiatan,
                kroEl: kro,
                roEl: ro,
                roData,
                selectedValue: oldRef.ro
            });
            refPopulateKomponen({
                kegiatanEl: kegiatan,
                kroEl: kro,
                roEl: ro,
                komponenEl: komponen,
                komponenData,
                selectedValue: oldRef.komponen
            });
            populateSubkomponen(oldRef.subkomponen);
        }
        mode.addEventListener('change', () => applyMode(false));
        unit1.addEventListener('change', () => {
            refPopulateUnit2({
                unit1El: unit1,
                unit2El: unit2,
                unitEselon2Data
            });
            refResetSelect(satker, '-- Pilih Unit Eselon II terlebih dahulu --');
            resetBudget();
            updateState(false);
        });
        unit2.addEventListener('change', () => {
            refPopulateSatker({
                unit2El: unit2,
                satkerEl: satker,
                satkerData
            });
            resetBudget();
            updateState(false);
        });
        satker.addEventListener('change', () => {
            resetBudget();
            populateSatkerProgram();
            updateState(false);
        });
        program.addEventListener('change', () => {
            refResetSelect(kegiatan, '-- Pilih Program terlebih dahulu --');
            refResetSelect(kro, '-- Pilih Kegiatan terlebih dahulu --');
            refResetSelect(ro, '-- Pilih KRO terlebih dahulu --');
            refResetSelect(komponen, '-- Pilih RO terlebih dahulu --');
            refResetSelect(subkomponen, '-- Pilih Komponen terlebih dahulu --');
            populateKegiatanForMode();
            updateState(false);
        });
        kegiatan.addEventListener('change', () => {
            refPopulateKro({
                kegiatanEl: kegiatan,
                kroEl: kro,
                kroData
            });
            refResetSelect(ro, '-- Pilih KRO terlebih dahulu --');
            refResetSelect(komponen, '-- Pilih RO terlebih dahulu --');
            refResetSelect(subkomponen, '-- Pilih Komponen terlebih dahulu --');
            updateState(false);
        });
        kro.addEventListener('change', () => {
            refPopulateRo({
                kegiatanEl: kegiatan,
                kroEl: kro,
                roEl: ro,
                roData
            });
            refResetSelect(komponen, '-- Pilih RO terlebih dahulu --');
            refResetSelect(subkomponen, '-- Pilih Komponen terlebih dahulu --');
            updateState(false);
        });
        ro.addEventListener('change', () => {
            refPopulateKomponen({
                kegiatanEl: kegiatan,
                kroEl: kro,
                roEl: ro,
                komponenEl: komponen,
                komponenData
            });
            refResetSelect(subkomponen, '-- Pilih Komponen terlebih dahulu --');
            updateState(false);
        });
        komponen.addEventListener('change', () => {
            if (mode.value === 'SUBKOMPONEN') populateSubkomponen();
            updateState(false);
        });
        subkomponen.addEventListener('change', () => updateState(false));
        [kode, nama].forEach(el => el.addEventListener('input', () => updateState(false)));
        form.addEventListener('submit', async e => {
            e.preventDefault();
            if (!updateState(true)) return;
            await refSubmitAjax({
                form,
                saveButton,
                loadingMessage: 'Master Akun dan penempatannya sedang divalidasi dan disimpan.',
                fieldMap: {
                    kode_akun: {
                        input: kode,
                        error: err.kode
                    },
                    nama_akun: {
                        input: nama,
                        error: err.nama
                    },
                    kode_unit_eselon1: {
                        input: unit1,
                        error: err.unit1
                    },
                    kode_unit_eselon2: {
                        input: unit2,
                        error: err.unit2
                    },
                    kode_satker: {
                        input: satker,
                        error: err.satker
                    },
                    kode_program: {
                        input: program,
                        error: err.program
                    },
                    kode_kegiatan: {
                        input: kegiatan,
                        error: err.kegiatan
                    },
                    kode_kro: {
                        input: kro,
                        error: err.kro
                    },
                    kode_ro: {
                        input: ro,
                        error: err.ro
                    },
                    kode_komponen: {
                        input: komponen,
                        error: err.komponen
                    },
                    kode_subkomponen: {
                        input: subkomponen,
                        error: err.subkomponen
                    }
                },
                successReset: () => {
                    kode.value = '';
                    nama.value = '';
                    updateState(false);
                    kode.focus();
                }
            });
            updateState(false);
        });
        updateState(false);
    </script>
</body>

</html>
