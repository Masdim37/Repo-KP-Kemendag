<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Referensi Kegiatan | Penelitian RKA-K/L</title>@include('menu.referensi.penganggaran.partials.reference-style')
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
        $oldSatkers = old('kode_satker', []);
    @endphp
    <div class="app-shell">@include('partials.sidebar', [
        'activeMenu' => 'reference-kegiatan',
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
                        <div>Kegiatan berada di bawah Program dan relasinya dengan Satker disimpan pada
                            <strong>satker_kegiatan</strong>. Minimal satu Satker Pelaksana wajib dipilih.</div>
                    </div>
                    @if (session('success'))
                        <div class="alert success">{{ session('success') }}</div>
                        @endif @if (session('error'))
                            <div class="alert error">{{ session('error') }}</div>
                        @endif
                        <section class="main-card">
                            <div class="card-header">
                                <div class="card-header-left">
                                    <div class="card-header-icon"><i class="bi bi-diagram-3"></i></div>
                                    <div>
                                        <h1 class="card-title">Tambah Referensi Kegiatan</h1>
                                        <p class="card-description">Tambahkan Kegiatan sekaligus pemetaan Satker
                                            Pelaksana.</p>
                                    </div>
                                </div><span class="card-badge">REFERENSI PENGANGGARAN</span>
                            </div>
                            <form id="referenceForm" class="main-form"
                                action="{{ route('referensi.penganggaran.kegiatan.store') }}" method="POST" novalidate>
                                @csrf
                                <div class="section-title">
                                    <div>
                                        <h2>Data Kegiatan</h2><span>Pilih Program, masukkan kode/nama Kegiatan, lalu
                                            tentukan Satker Pelaksana.</span>
                                    </div>
                                </div>
                                <div class="form-grid">
                                    <div class="form-group"><label class="form-label" for="program">Program <span
                                                class="required">*</span></label><select id="program"
                                            name="kode_program" class="form-control">
                                            <option value="">-- Pilih Program --</option>
                                            @foreach ($program as $item)
                                                <option value="{{ $item->kode_program }}" @selected(old('kode_program') == $item->kode_program)>
                                                    [{{ $item->kode_program }}] {{ $item->nama_program }}</option>
                                            @endforeach
                                        </select>
                                        <div class="field-error" id="programError"></div>
                                        @error('kode_program')
                                            <div class="field-error show">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group"><label class="form-label" for="kodeKegiatan">Kode Kegiatan
                                            <span class="required">*</span></label><input id="kodeKegiatan"
                                            name="kode_kegiatan" class="form-control" maxlength="50"
                                            value="{{ old('kode_kegiatan') }}" placeholder="Contoh: 3702">
                                        <div class="field-error" id="kodeKegiatanError"></div>
                                        @error('kode_kegiatan')
                                            <div class="field-error show">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group full"><label class="form-label" for="namaKegiatan">Nama
                                            Kegiatan <span class="required">*</span></label><input id="namaKegiatan"
                                            name="nama_kegiatan" class="form-control" maxlength="255"
                                            value="{{ old('nama_kegiatan') }}" placeholder="Masukkan nama Kegiatan">
                                        <div class="field-error" id="namaKegiatanError"></div>
                                        @error('nama_kegiatan')
                                            <div class="field-error show">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group full"><label class="form-label" for="satkers">Satker
                                            Pelaksana <span class="required">*</span></label><select id="satkers"
                                            name="kode_satker[]" class="form-control multi-select" multiple
                                            size="9">
                                            @foreach ($satker as $item)
                                                <option value="{{ $item->kode_satker }}" @selected(in_array($item->kode_satker, $oldSatkers, true))>
                                                    [{{ $item->kode_satker }}] {{ $item->nama_satker }} —
                                                    {{ $item->kode_unit_eselon2 }} {{ $item->nama_unit_eselon2 }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="field-help">Gunakan Ctrl/Cmd + klik untuk memilih lebih dari satu
                                            Satker.</div>
                                        <div class="field-error" id="satkersError"></div>
                                        @error('kode_satker')
                                            <div class="field-error show">{{ $message }}</div>
                                            @enderror @error('kode_satker.*')
                                            <div class="field-error show">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="status-list"><span class="status-chip"
                                        id="programStatus">Program</span><span class="status-chip" id="kodeStatus">Kode
                                        Kegiatan</span><span class="status-chip" id="namaStatus">Nama
                                        Kegiatan</span><span class="status-chip" id="satkerStatus">Satker
                                        Pelaksana</span></div>
                                <div class="form-actions">
                                    <div class="action-message" id="actionMessage">Lengkapi seluruh data Kegiatan.
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
    <script src="{{ asset('js/document-processing-modal.js') }}"></script>@include('menu.referensi.penganggaran.partials.reference-helpers')
    <script>
        const form = document.getElementById('referenceForm'),
            program = document.getElementById('program'),
            kode = document.getElementById('kodeKegiatan'),
            nama = document.getElementById('namaKegiatan'),
            satkers = document.getElementById('satkers'),
            saveButton = document.getElementById('saveButton'),
            actionMessage = document.getElementById('actionMessage');
        const errors = {
            program: document.getElementById('programError'),
            kode: document.getElementById('kodeKegiatanError'),
            nama: document.getElementById('namaKegiatanError'),
            satkers: document.getElementById('satkersError')
        };

        function selectedSatkers() {
            return Array.from(satkers.selectedOptions).map(o => o.value).filter(Boolean);
        }

        function updateState(showErrors = false) {
            const c = {
                program: !!program.value,
                kode: kode.value.trim() !== '',
                nama: nama.value.trim() !== '',
                satkers: selectedSatkers().length > 0
            };
            document.getElementById('programStatus').classList.toggle('complete', c.program);
            document.getElementById('kodeStatus').classList.toggle('complete', c.kode);
            document.getElementById('namaStatus').classList.toggle('complete', c.nama);
            document.getElementById('satkerStatus').classList.toggle('complete', c.satkers);
            if (showErrors) {
                refSetFieldError(program, errors.program, c.program ? '' : 'Program wajib dipilih.');
                refSetFieldError(kode, errors.kode, c.kode ? '' : 'Kode Kegiatan wajib diisi.');
                refSetFieldError(nama, errors.nama, c.nama ? '' : 'Nama Kegiatan wajib diisi.');
                refSetFieldError(satkers, errors.satkers, c.satkers ? '' : 'Minimal satu Satker Pelaksana wajib dipilih.');
            }
            const valid = Object.values(c).every(Boolean);
            saveButton.disabled = !valid;
            actionMessage.textContent = valid ? 'Data siap disimpan.' : 'Lengkapi seluruh data Kegiatan.';
            return valid;
        }
        program.addEventListener('change', () => updateState(false));
        satkers.addEventListener('change', () => updateState(false));
        [kode, nama].forEach(el => el.addEventListener('input', () => updateState(false)));
        form.addEventListener('submit', async e => {
            e.preventDefault();
            if (!updateState(true)) return;
            await refSubmitAjax({
                form,
                saveButton,
                loadingMessage: 'Data Kegiatan dan pemetaan Satker sedang disimpan.',
                fieldMap: {
                    kode_program: {
                        input: program,
                        error: errors.program
                    },
                    kode_kegiatan: {
                        input: kode,
                        error: errors.kode
                    },
                    nama_kegiatan: {
                        input: nama,
                        error: errors.nama
                    },
                    kode_satker: {
                        input: satkers,
                        error: errors.satkers
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
