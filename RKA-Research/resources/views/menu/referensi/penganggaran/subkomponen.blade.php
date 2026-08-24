<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Referensi Subkomponen | Penelitian RKA-K/L</title>@include('menu.referensi.penganggaran.partials.reference-style')
</head>

<body>
    @php
        $userName = data_get($user ?? null, 'name', session('user_name', 'Pengguna Sistem'));
        $jabatanName = data_get($user ?? null, 'jabatan.jabatan_name', session('jabatan_name', 'Pengguna Sistem'));
        $initials = collect(explode(' ', $userName))->filter()->take(2)->map(fn($word) => strtoupper(substr($word, 0, 1)))->implode('');
    @endphp
    <div class="app-shell">@include('partials.sidebar', [
        'activeMenu' => 'reference-subkomponen',
        'sidebarUserName' => $userName,
        'sidebarUserRole' => $jabatanName,
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
                        <div>Subkomponen bersifat <strong>spesifik per Satker</strong>. Identitasnya mengikuti Satker +
                            Kegiatan + KRO + RO + Komponen + kode Subkomponen.</div>
                    </div>
                    @if (session('success'))
                        <div class="alert success">{{ session('success') }}</div>
                        @endif @if (session('error'))
                            <div class="alert error">{{ session('error') }}</div>
                        @endif
                        <section class="main-card">
                            <div class="card-header">
                                <div class="card-header-left">
                                    <div class="card-header-icon"><i class="bi bi-diagram-3-fill"></i></div>
                                    <div>
                                        <h1 class="card-title">Tambah Referensi Subkomponen</h1>
                                        <p class="card-description">Pilih Satker dan hierarki anggaran sampai Komponen,
                                            lalu tambahkan Subkomponen.</p>
                                    </div>
                                </div><span class="card-badge">REFERENSI PENGANGGARAN</span>
                            </div>
                            <form id="referenceForm" class="main-form"
                                action="{{ route('referensi.penganggaran.subkomponen.store') }}" method="POST"
                                novalidate>@csrf
                                <div class="section-title">
                                    <div>
                                        <h2>Satker dan Hierarki Anggaran</h2><span>Unit I → Unit II → Satker → Program →
                                            Kegiatan → KRO → RO → Komponen.</span>
                                    </div>
                                </div>
                                <div class="form-grid">
                                    <div class="form-group"><label class="form-label">Unit Eselon I <span
                                                class="required">*</span></label><select id="unit1"
                                            name="kode_unit_eselon1" class="form-control">
                                            <option value="">-- Pilih Unit Eselon I --</option>
                                            @foreach ($unitEselon1 as $item)
                                                <option value="{{ $item->kode_unit_eselon1 }}">
                                                    [{{ $item->kode_unit_eselon1 }}] {{ $item->nama_unit_eselon1 }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="field-error" id="unit1Error"></div>
                                    </div>
                                    <div class="form-group"><label class="form-label">Unit Eselon II <span
                                                class="required">*</span></label><select id="unit2"
                                            name="kode_unit_eselon2" class="form-control" disabled>
                                            <option value="">-- Pilih Unit Eselon I terlebih dahulu --</option>
                                        </select>
                                        <div class="field-error" id="unit2Error"></div>
                                    </div>
                                    <div class="form-group"><label class="form-label">Satker <span
                                                class="required">*</span></label><select id="satker"
                                            name="kode_satker" class="form-control" disabled>
                                            <option value="">-- Pilih Unit Eselon II terlebih dahulu --</option>
                                        </select>
                                        <div class="field-error" id="satkerError"></div>
                                    </div>
                                    <div class="form-group"><label class="form-label">Program <span
                                                class="required">*</span></label><select id="program"
                                            name="kode_program" class="form-control" disabled>
                                            <option value="">-- Pilih Satker terlebih dahulu --</option>
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
                                                class="required">*</span></label><select id="kro" name="kode_kro"
                                            class="form-control" disabled>
                                            <option value="">-- Pilih Kegiatan terlebih dahulu --</option>
                                        </select>
                                        <div class="field-error" id="kroError"></div>
                                    </div>
                                    <div class="form-group"><label class="form-label">RO <span
                                                class="required">*</span></label><select id="ro" name="kode_ro"
                                            class="form-control" disabled>
                                            <option value="">-- Pilih KRO terlebih dahulu --</option>
                                        </select>
                                        <div class="field-error" id="roError"></div>
                                    </div>
                                    <div class="form-group"><label class="form-label">Komponen <span
                                                class="required">*</span></label><select id="komponen"
                                            name="kode_komponen" class="form-control" disabled>
                                            <option value="">-- Pilih RO terlebih dahulu --</option>
                                        </select>
                                        <div class="field-error" id="komponenError"></div>
                                    </div>
                                    <div class="form-group"><label class="form-label">Kode Subkomponen <span
                                                class="required">*</span></label><input id="kodeSubkomponen"
                                            name="kode_subkomponen" class="form-control" maxlength="10"
                                            value="{{ old('kode_subkomponen') }}" placeholder="Contoh: A">
                                        <div class="field-error" id="kodeSubkomponenError"></div>
                                    </div>
                                    <div class="form-group"><label class="form-label">Nama Subkomponen <span
                                                class="required">*</span></label><input id="namaSubkomponen"
                                            name="nama_subkomponen" class="form-control" maxlength="255"
                                            value="{{ old('nama_subkomponen') }}"
                                            placeholder="Masukkan nama Subkomponen">
                                        <div class="field-error" id="namaSubkomponenError"></div>
                                    </div>
                                    <div class="form-group full"><label class="form-label">Deskripsi</label>
                                        <textarea id="deskripsi" name="deskripsi" class="form-control"
                                            placeholder="Deskripsi atau catatan penggunaan Subkomponen (opsional)">{{ old('deskripsi') }}</textarea>
                                    </div>
                                </div>
                                <div class="status-list"><span class="status-chip" id="orgStatus">Organisasi &
                                        Satker</span><span class="status-chip" id="parentStatus">Hierarki
                                        Komponen</span><span class="status-chip" id="kodeStatus">Kode
                                        Subkomponen</span><span class="status-chip" id="namaStatus">Nama
                                        Subkomponen</span></div>
                                <div class="form-actions">
                                    <div class="action-message" id="actionMessage">Lengkapi Satker, parent Komponen,
                                        dan data Subkomponen.</div><button type="submit" class="save-button"
                                        id="saveButton" disabled><i class="bi bi-floppy"></i>Simpan Referensi</button>
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
            komponenData = @json($komponen);
        const oldRef = {
            unit1: @json(old('kode_unit_eselon1')),
            unit2: @json(old('kode_unit_eselon2')),
            satker: @json(old('kode_satker')),
            program: @json(old('kode_program')),
            kegiatan: @json(old('kode_kegiatan')),
            kro: @json(old('kode_kro')),
            ro: @json(old('kode_ro')),
            komponen: @json(old('kode_komponen'))
        };
        const form = document.getElementById('referenceForm'),
            unit1 = document.getElementById('unit1'),
            unit2 = document.getElementById('unit2'),
            satker = document.getElementById('satker'),
            program = document.getElementById('program'),
            kegiatan = document.getElementById('kegiatan'),
            kro = document.getElementById('kro'),
            ro = document.getElementById('ro'),
            komponen = document.getElementById('komponen'),
            kode = document.getElementById('kodeSubkomponen'),
            nama = document.getElementById('namaSubkomponen'),
            saveButton = document.getElementById('saveButton'),
            actionMessage = document.getElementById('actionMessage');
        const err = {
            unit1: document.getElementById('unit1Error'),
            unit2: document.getElementById('unit2Error'),
            satker: document.getElementById('satkerError'),
            program: document.getElementById('programError'),
            kegiatan: document.getElementById('kegiatanError'),
            kro: document.getElementById('kroError'),
            ro: document.getElementById('roError'),
            komponen: document.getElementById('komponenError'),
            kode: document.getElementById('kodeSubkomponenError'),
            nama: document.getElementById('namaSubkomponenError')
        };

        function resetBudgetFrom(level) {
            const map = {
                satker: [
                    [program, '-- Pilih Satker terlebih dahulu --'],
                    [kegiatan, '-- Pilih Program terlebih dahulu --'],
                    [kro, '-- Pilih Kegiatan terlebih dahulu --'],
                    [ro, '-- Pilih KRO terlebih dahulu --'],
                    [komponen, '-- Pilih RO terlebih dahulu --']
                ],
                program: [
                    [kegiatan, '-- Pilih Program terlebih dahulu --'],
                    [kro, '-- Pilih Kegiatan terlebih dahulu --'],
                    [ro, '-- Pilih KRO terlebih dahulu --'],
                    [komponen, '-- Pilih RO terlebih dahulu --']
                ],
                kegiatan: [
                    [kro, '-- Pilih Kegiatan terlebih dahulu --'],
                    [ro, '-- Pilih KRO terlebih dahulu --'],
                    [komponen, '-- Pilih RO terlebih dahulu --']
                ],
                kro: [
                    [ro, '-- Pilih KRO terlebih dahulu --'],
                    [komponen, '-- Pilih RO terlebih dahulu --']
                ],
                ro: [
                    [komponen, '-- Pilih RO terlebih dahulu --']
                ]
            };
            (map[level] || []).forEach(([el, p]) => refResetSelect(el, p));
        }

        function updateState(show = false) {
            const c = {
                org: !!unit1.value && !!unit2.value && !!satker.value,
                parent: !!program.value && !!kegiatan.value && !!kro.value && !!ro.value && !!komponen.value,
                kode: kode.value.trim() !== '',
                nama: nama.value.trim() !== ''
            };
            document.getElementById('orgStatus').classList.toggle('complete', c.org);
            document.getElementById('parentStatus').classList.toggle('complete', c.parent);
            document.getElementById('kodeStatus').classList.toggle('complete', c.kode);
            document.getElementById('namaStatus').classList.toggle('complete', c.nama);
            if (show) {
                [
                    ['unit1', 'Unit Eselon I wajib dipilih.'],
                    ['unit2', 'Unit Eselon II wajib dipilih.'],
                    ['satker', 'Satker wajib dipilih.'],
                    ['program', 'Program wajib dipilih.'],
                    ['kegiatan', 'Kegiatan wajib dipilih.'],
                    ['kro', 'KRO wajib dipilih.'],
                    ['ro', 'RO wajib dipilih.'],
                    ['komponen', 'Komponen wajib dipilih.']
                ].forEach(([k, m]) => refSetFieldError(eval(k), err[k], eval(k).value ? '' : m));
                refSetFieldError(kode, err.kode, c.kode ? '' : 'Kode Subkomponen wajib diisi.');
                refSetFieldError(nama, err.nama, c.nama ? '' : 'Nama Subkomponen wajib diisi.');
            }
            const valid = Object.values(c).every(Boolean);
            saveButton.disabled = !valid;
            actionMessage.textContent = valid ? 'Data siap disimpan.' :
                'Lengkapi Satker, parent Komponen, dan data Subkomponen.';
            return valid;
        }

        function populateProgram(selected = '') {
            refPopulateProgramForSatker({
                satkerEl: satker,
                programEl: program,
                satkerKegiatanData,
                kegiatanData,
                programData,
                selectedValue: selected
            });
        }

        function populateKegiatan(selected = '') {
            const allowed = refGetKegiatanCodesForSatker(satker.value, satkerKegiatanData);
            refPopulateKegiatan({
                programEl: program,
                kegiatanEl: kegiatan,
                kegiatanData,
                selectedValue: selected,
                allowedKegiatanCodes: allowed
            });
        }
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
        populateProgram(oldRef.program);
        populateKegiatan(oldRef.kegiatan);
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
        unit1.addEventListener('change', () => {
            refPopulateUnit2({
                unit1El: unit1,
                unit2El: unit2,
                unitEselon2Data
            });
            refResetSelect(satker, '-- Pilih Unit Eselon II terlebih dahulu --');
            resetBudgetFrom('satker');
            updateState(false);
        });
        unit2.addEventListener('change', () => {
            refPopulateSatker({
                unit2El: unit2,
                satkerEl: satker,
                satkerData
            });
            resetBudgetFrom('satker');
            updateState(false);
        });
        satker.addEventListener('change', () => {
            resetBudgetFrom('satker');
            populateProgram();
            updateState(false);
        });
        program.addEventListener('change', () => {
            resetBudgetFrom('program');
            populateKegiatan();
            updateState(false);
        });
        kegiatan.addEventListener('change', () => {
            resetBudgetFrom('kegiatan');
            refPopulateKro({
                kegiatanEl: kegiatan,
                kroEl: kro,
                kroData
            });
            updateState(false);
        });
        kro.addEventListener('change', () => {
            resetBudgetFrom('kro');
            refPopulateRo({
                kegiatanEl: kegiatan,
                kroEl: kro,
                roEl: ro,
                roData
            });
            updateState(false);
        });
        ro.addEventListener('change', () => {
            resetBudgetFrom('ro');
            refPopulateKomponen({
                kegiatanEl: kegiatan,
                kroEl: kro,
                roEl: ro,
                komponenEl: komponen,
                komponenData
            });
            updateState(false);
        });
        komponen.addEventListener('change', () => updateState(false));
        [kode, nama].forEach(el => el.addEventListener('input', () => updateState(false)));
        form.addEventListener('submit', async e => {
            e.preventDefault();
            if (!updateState(true)) return;
            await refSubmitAjax({
                form,
                saveButton,
                loadingMessage: 'Data Subkomponen sedang divalidasi terhadap Satker dan hierarki anggaran.',
                fieldMap: {
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
                        input: kode,
                        error: err.kode
                    },
                    nama_subkomponen: {
                        input: nama,
                        error: err.nama
                    }
                },
                successReset: () => {
                    kode.value = '';
                    nama.value = '';
                    document.getElementById('deskripsi').value = '';
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
