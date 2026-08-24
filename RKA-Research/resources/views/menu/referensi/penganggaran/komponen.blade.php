<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Referensi Komponen | Penelitian RKA-K/L</title>@include('menu.referensi.penganggaran.partials.reference-style')
</head>

<body>
    @php
        $userName = data_get($user ?? null, 'name', session('user_name', 'Pengguna Sistem'));
        $jabatanName = data_get($user ?? null, 'jabatan.jabatan_name', session('jabatan_name', 'Pengguna Sistem'));
        $initials = collect(explode(' ', $userName))->filter()->take(2)->map(fn($word) => strtoupper(substr($word, 0, 1)))->implode('');
    @endphp
    <div class="app-shell">@include('partials.sidebar', [
        'activeMenu' => 'reference-komponen',
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
                        <div>Komponen berada di bawah RO. <strong>jenis_komponen</strong> disimpan dengan kode
                            <strong>U</strong> (Utama) atau <strong>P</strong> (Pendukung).</div>
                    </div>
                    @if (session('success'))
                        <div class="alert success">{{ session('success') }}</div>
                        @endif @if (session('error'))
                            <div class="alert error">{{ session('error') }}</div>
                        @endif
                        <section class="main-card">
                            <div class="card-header">
                                <div class="card-header-left">
                                    <div class="card-header-icon"><i class="bi bi-boxes"></i></div>
                                    <div>
                                        <h1 class="card-title">Tambah Referensi Komponen</h1>
                                        <p class="card-description">Pilih RO, lalu tambahkan kode, nama, dan jenis
                                            Komponen.</p>
                                    </div>
                                </div><span class="card-badge">REFERENSI PENGANGGARAN</span>
                            </div>
                            <form id="referenceForm" class="main-form"
                                action="{{ route('referensi.penganggaran.komponen.store') }}" method="POST" novalidate>
                                @csrf<div class="section-title">
                                    <div>
                                        <h2>Hierarki Komponen</h2><span>Program → Kegiatan → KRO → RO → Komponen.</span>
                                    </div>
                                </div>
                                <div class="form-grid">
                                    <div class="form-group"><label class="form-label">Program <span
                                                class="required">*</span></label><select id="program"
                                            name="kode_program" class="form-control">
                                            <option value="">-- Pilih Program --</option>
                                            @foreach ($program as $item)
                                                <option value="{{ $item->kode_program }}">[{{ $item->kode_program }}]
                                                    {{ $item->nama_program }}</option>
                                            @endforeach
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
                                    <div class="form-group"><label class="form-label">Kode Komponen <span
                                                class="required">*</span></label><input id="kodeKomponen"
                                            name="kode_komponen" class="form-control" maxlength="50"
                                            value="{{ old('kode_komponen') }}" placeholder="Contoh: 051">
                                        <div class="field-error" id="kodeKomponenError"></div>
                                    </div>
                                    <div class="form-group"><label class="form-label">Jenis Komponen <span
                                                class="required">*</span></label><select id="jenisKomponen"
                                            name="jenis_komponen" class="form-control">
                                            <option value="">-- Pilih Jenis --</option>
                                            <option value="U" @selected(old('jenis_komponen') === 'U')>U — Utama</option>
                                            <option value="P" @selected(old('jenis_komponen') === 'P')>P — Pendukung</option>
                                        </select>
                                        <div class="field-error" id="jenisKomponenError"></div>
                                    </div>
                                    <div class="form-group full"><label class="form-label">Nama Komponen <span
                                                class="required">*</span></label><input id="namaKomponen"
                                            name="nama_komponen" class="form-control" maxlength="255"
                                            value="{{ old('nama_komponen') }}" placeholder="Masukkan nama Komponen">
                                        <div class="field-error" id="namaKomponenError"></div>
                                    </div>
                                </div>
                                <div class="status-list"><span class="status-chip" id="parentStatus">Parent
                                        RO</span><span class="status-chip" id="kodeStatus">Kode Komponen</span><span
                                        class="status-chip" id="jenisStatus">Jenis U/P</span><span
                                        class="status-chip" id="namaStatus">Nama Komponen</span></div>
                                <div class="form-actions">
                                    <div class="action-message" id="actionMessage">Lengkapi seluruh data Komponen.
                                    </div><button type="submit" class="save-button" id="saveButton" disabled><i
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
        const kegiatanData = @json($kegiatan),
            kroData = @json($kro),
            roData = @json($ro),
            oldRef = {
                program: @json(old('kode_program')),
                kegiatan: @json(old('kode_kegiatan')),
                kro: @json(old('kode_kro')),
                ro: @json(old('kode_ro'))
            };
        const form = document.getElementById('referenceForm'),
            program = document.getElementById('program'),
            kegiatan = document.getElementById('kegiatan'),
            kro = document.getElementById('kro'),
            ro = document.getElementById('ro'),
            kode = document.getElementById('kodeKomponen'),
            nama = document.getElementById('namaKomponen'),
            jenis = document.getElementById('jenisKomponen'),
            saveButton = document.getElementById('saveButton'),
            actionMessage = document.getElementById('actionMessage');
        const err = {
            program: document.getElementById('programError'),
            kegiatan: document.getElementById('kegiatanError'),
            kro: document.getElementById('kroError'),
            ro: document.getElementById('roError'),
            kode: document.getElementById('kodeKomponenError'),
            nama: document.getElementById('namaKomponenError'),
            jenis: document.getElementById('jenisKomponenError')
        };

        function updateState(show = false) {
            const c = {
                parent: !!program.value && !!kegiatan.value && !!kro.value && !!ro.value,
                kode: kode.value.trim() !== '',
                nama: nama.value.trim() !== '',
                jenis: ['U', 'P'].includes(jenis.value)
            };
            ['parent', 'kode', 'nama', 'jenis'].forEach(k => document.getElementById(k + 'Status').classList.toggle(
                'complete', c[k]));
            if (show) {
                refSetFieldError(program, err.program, program.value ? '' : 'Program wajib dipilih.');
                refSetFieldError(kegiatan, err.kegiatan, kegiatan.value ? '' : 'Kegiatan wajib dipilih.');
                refSetFieldError(kro, err.kro, kro.value ? '' : 'KRO wajib dipilih.');
                refSetFieldError(ro, err.ro, ro.value ? '' : 'RO wajib dipilih.');
                refSetFieldError(kode, err.kode, c.kode ? '' : 'Kode Komponen wajib diisi.');
                refSetFieldError(nama, err.nama, c.nama ? '' : 'Nama Komponen wajib diisi.');
                refSetFieldError(jenis, err.jenis, c.jenis ? '' : 'Jenis Komponen U/P wajib dipilih.');
            }
            const valid = Object.values(c).every(Boolean);
            saveButton.disabled = !valid;
            actionMessage.textContent = valid ? 'Data siap disimpan.' : 'Lengkapi seluruh data Komponen.';
            return valid;
        }
        program.value = oldRef.program || '';
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
        program.addEventListener('change', () => {
            refPopulateKegiatan({
                programEl: program,
                kegiatanEl: kegiatan,
                kegiatanData
            });
            refResetSelect(kro, '-- Pilih Kegiatan terlebih dahulu --');
            refResetSelect(ro, '-- Pilih KRO terlebih dahulu --');
            updateState(false);
        });
        kegiatan.addEventListener('change', () => {
            refPopulateKro({
                kegiatanEl: kegiatan,
                kroEl: kro,
                kroData
            });
            refResetSelect(ro, '-- Pilih KRO terlebih dahulu --');
            updateState(false);
        });
        kro.addEventListener('change', () => {
            refPopulateRo({
                kegiatanEl: kegiatan,
                kroEl: kro,
                roEl: ro,
                roData
            });
            updateState(false);
        });
        ro.addEventListener('change', () => updateState(false));
        jenis.addEventListener('change', () => updateState(false));
        [kode, nama].forEach(el => el.addEventListener('input', () => updateState(false)));
        form.addEventListener('submit', async e => {
            e.preventDefault();
            if (!updateState(true)) return;
            await refSubmitAjax({
                form,
                saveButton,
                loadingMessage: 'Data Komponen sedang divalidasi dan disimpan.',
                fieldMap: {
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
                        input: kode,
                        error: err.kode
                    },
                    nama_komponen: {
                        input: nama,
                        error: err.nama
                    },
                    jenis_komponen: {
                        input: jenis,
                        error: err.jenis
                    }
                },
                successReset: () => {
                    kode.value = '';
                    nama.value = '';
                    jenis.value = '';
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
