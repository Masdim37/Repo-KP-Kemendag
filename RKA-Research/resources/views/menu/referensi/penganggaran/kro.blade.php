<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Referensi KRO | Penelitian RKA-K/L</title>@include('menu.referensi.penganggaran.partials.reference-style')
</head>

<body>
    @php
        $userName = data_get($user ?? null, 'name', session('user_name', 'Pengguna Sistem'));
        $jabatanName = data_get($user ?? null, 'jabatan.jabatan_name', session('jabatan_name', 'Pengguna Sistem'));
        $initials = collect(explode(' ', $userName))->filter()->take(2)->map(fn($word) => strtoupper(substr($word, 0, 1)))->implode('');
    @endphp
    <div class="app-shell">@include('partials.sidebar', [
        'activeMenu' => 'reference-kro',
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
                    {{-- <div class="info-box"><i class="bi bi-info-circle-fill"></i>
                        <div>KRO merupakan master global. Relasi Kegiatan–KRO disimpan pada
                            <strong>kegiatan_kro</strong>. Jika kode KRO sudah ada, gunakan nomenklatur master yang
                            sama.</div>
                    </div> --}}
                    @if (session('success'))
                        <div class="alert success">{{ session('success') }}</div>
                        @endif @if (session('error'))
                            <div class="alert error">{{ session('error') }}</div>
                        @endif
                        <section class="main-card">
                            <div class="card-header">
                                <div class="card-header-left">
                                    <div class="card-header-icon"><i class="bi bi-diagram-2"></i></div>
                                    <div>
                                        <h1 class="card-title">Tambah Referensi KRO</h1>
                                        <p class="card-description">Tambahkan KRO baru atau petakan KRO master yang
                                            sudah ada ke Kegiatan.</p>
                                    </div>
                                </div><span class="card-badge">REFERENSI PENGANGGARAN</span>
                            </div>
                            <form id="referenceForm" class="main-form"
                                action="{{ route('referensi.penganggaran.kro.store') }}" method="POST" novalidate>@csrf
                                <div class="section-title">
                                    <div>
                                        <h2>Data KRO</h2><span>Pilih Program lalu Kegiatan tempat KRO Baru 
                                            digunakan.</span>
                                    </div>
                                </div>
                                <div class="form-grid">
                                    <div class="form-group"><label class="form-label" for="program">Program <span
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
                                    <div class="form-group"><label class="form-label" for="kegiatan">Kegiatan <span
                                                class="required">*</span></label><select id="kegiatan"
                                            name="kode_kegiatan" class="form-control" disabled>
                                            <option value="">-- Pilih Program terlebih dahulu --</option>
                                        </select>
                                        <div class="field-error" id="kegiatanError"></div>
                                    </div>
                                    <div class="form-group"><label class="form-label" for="kodeKro">Kode KRO <span
                                                class="required">*</span></label><input id="kodeKro" name="kode_kro"
                                            class="form-control" maxlength="50" value="{{ old('kode_kro') }}"
                                            placeholder="Contoh: EBA">
                                        <div class="field-error" id="kodeKroError"></div>
                                        @error('kode_kro')
                                            <div class="field-error show">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group"><label class="form-label" for="namaKro">Nama KRO <span
                                                class="required">*</span></label><input id="namaKro" name="nama_kro"
                                            class="form-control" maxlength="255" value="{{ old('nama_kro') }}"
                                            placeholder="Masukkan nama KRO Baru">
                                        <div class="field-error" id="namaKroError"></div>
                                        @error('nama_kro')
                                            <div class="field-error show">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="status-list"><span class="status-chip" id="parentStatus">Program &
                                        Kegiatan</span><span class="status-chip" id="kodeStatus">Kode KRO</span><span
                                        class="status-chip" id="namaStatus">Nama KRO</span></div>
                                <div class="form-actions">
                                    <div class="action-message" id="actionMessage">Lengkapi parent dan data KRO.</div>
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
        const kegiatanData = @json($kegiatan);
        const oldRef = {
            program: @json(old('kode_program')),
            kegiatan: @json(old('kode_kegiatan'))
        };
        const form = document.getElementById('referenceForm'),
            program = document.getElementById('program'),
            kegiatan = document.getElementById('kegiatan'),
            kode = document.getElementById('kodeKro'),
            nama = document.getElementById('namaKro'),
            saveButton = document.getElementById('saveButton'),
            actionMessage = document.getElementById('actionMessage');
        const errors = {
            program: document.getElementById('programError'),
            kegiatan: document.getElementById('kegiatanError'),
            kode: document.getElementById('kodeKroError'),
            nama: document.getElementById('namaKroError')
        };

        function updateState(show = false) {
            const c = {
                parent: !!program.value && !!kegiatan.value,
                kode: kode.value.trim() !== '',
                nama: nama.value.trim() !== ''
            };
            document.getElementById('parentStatus').classList.toggle('complete', c.parent);
            document.getElementById('kodeStatus').classList.toggle('complete', c.kode);
            document.getElementById('namaStatus').classList.toggle('complete', c.nama);
            if (show) {
                refSetFieldError(program, errors.program, program.value ? '' : 'Program wajib dipilih.');
                refSetFieldError(kegiatan, errors.kegiatan, kegiatan.value ? '' : 'Kegiatan wajib dipilih.');
                refSetFieldError(kode, errors.kode, c.kode ? '' : 'Kode KRO wajib diisi.');
                refSetFieldError(nama, errors.nama, c.nama ? '' : 'Nama KRO wajib diisi.');
            }
            const valid = Object.values(c).every(Boolean);
            saveButton.disabled = !valid;
            actionMessage.textContent = valid ? 'Data siap disimpan.' : 'Lengkapi parent dan data KRO.';
            return valid;
        }
        program.value = oldRef.program || '';
        refPopulateKegiatan({
            programEl: program,
            kegiatanEl: kegiatan,
            kegiatanData,
            selectedValue: oldRef.kegiatan
        });
        program.addEventListener('change', () => {
            refPopulateKegiatan({
                programEl: program,
                kegiatanEl: kegiatan,
                kegiatanData
            });
            updateState(false);
        });
        kegiatan.addEventListener('change', () => updateState(false));
        [kode, nama].forEach(el => el.addEventListener('input', () => updateState(false)));
        form.addEventListener('submit', async e => {
            e.preventDefault();
            if (!updateState(true)) return;
            await refSubmitAjax({
                form,
                saveButton,
                loadingMessage: 'KRO sedang divalidasi, dibuat atau dipetakan ke Kegiatan.',
                fieldMap: {
                    kode_program: {
                        input: program,
                        error: errors.program
                    },
                    kode_kegiatan: {
                        input: kegiatan,
                        error: errors.kegiatan
                    },
                    kode_kro: {
                        input: kode,
                        error: errors.kode
                    },
                    nama_kro: {
                        input: nama,
                        error: errors.nama
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
