<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Referensi Program | Penelitian RKA-K/L</title>
    @include('menu.referensi.penganggaran.partials.reference-style')
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
    <div class="app-shell">
        @include('partials.sidebar', [
            'activeMenu' => 'reference-program',
            'sidebarUserName' => $userName,
            'sidebarUserRole' => $jabatanName,
            'sidebarInitials' => $initials,
        ])
        <div class="app-main">
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
                        <div>Program merupakan parent Kegiatan. Kode Program bersifat master dan harus unik.</div>
                    </div>
                    @if (session('success'))
                        <div class="alert success">{{ session('success') }}</div>
                    @endif
                    @if (session('error'))
                        <div class="alert error">{{ session('error') }}</div>
                    @endif
                    <section class="main-card">
                        <div class="card-header">
                            <div class="card-header-left">
                                <div class="card-header-icon"><i class="bi bi-database-add"></i></div>
                                <div>
                                    <h1 class="card-title">Tambah Referensi Program</h1>
                                    <p class="card-description">Masukkan kode dan nomenklatur Program yang telah
                                        ditetapkan.</p>
                                </div>
                            </div><span class="card-badge">REFERENSI PENGANGGARAN</span>
                        </div>
                        <form id="referenceForm" class="main-form"
                            action="{{ route('referensi.penganggaran.program.store') }}" method="POST" novalidate>@csrf
                            <div class="section-title">
                                <div>
                                    <h2>Data Program</h2><span>Kode disimpan sebagai teks dan tidak diubah menjadi
                                        angka.</span>
                                </div>
                            </div>
                            <div class="form-grid">
                                <div class="form-group"><label for="kodeProgram" class="form-label">Kode Program <span
                                            class="required">*</span></label><input type="text" id="kodeProgram"
                                        name="kode_program" class="form-control" maxlength="50"
                                        value="{{ old('kode_program') }}" placeholder="Contoh: WA">
                                    <div class="field-error" id="kodeProgramError"></div>
                                    @error('kode_program')
                                        <div class="field-error show">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group"><label for="namaProgram" class="form-label">Nama Program <span
                                            class="required">*</span></label><input type="text" id="namaProgram"
                                        name="nama_program" class="form-control" maxlength="255"
                                        value="{{ old('nama_program') }}" placeholder="Masukkan nama Program">
                                    <div class="field-error" id="namaProgramError"></div>
                                    @error('nama_program')
                                        <div class="field-error show">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="status-list"><span class="status-chip" id="kodeStatus">Kode Program</span><span
                                    class="status-chip" id="namaStatus">Nama Program</span></div>
                            <div class="form-actions">
                                <div class="action-message" id="actionMessage">Lengkapi kode dan nama Program.</div>
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
    <script src="{{ asset('js/document-processing-modal.js') }}"></script>
    @include('menu.referensi.penganggaran.partials.reference-helpers')
    <script>
        const form = document.getElementById('referenceForm'),
            kode = document.getElementById('kodeProgram'),
            nama = document.getElementById('namaProgram'),
            kodeError = document.getElementById('kodeProgramError'),
            namaError = document.getElementById('namaProgramError'),
            saveButton = document.getElementById('saveButton'),
            actionMessage = document.getElementById('actionMessage');

        function updateState(showErrors = false) {
            const a = kode.value.trim() !== '',
                b = nama.value.trim() !== '';
            document.getElementById('kodeStatus').classList.toggle('complete', a);
            document.getElementById('namaStatus').classList.toggle('complete', b);
            if (showErrors) {
                refSetFieldError(kode, kodeError, a ? '' : 'Kode Program wajib diisi.');
                refSetFieldError(nama, namaError, b ? '' : 'Nama Program wajib diisi.');
            }
            const valid = a && b;
            saveButton.disabled = !valid;
            actionMessage.textContent = valid ? 'Data siap disimpan.' : 'Lengkapi kode dan nama Program.';
            return valid;
        }
        [kode, nama].forEach(el => el.addEventListener('input', () => updateState(false)));
        form.addEventListener('submit', async e => {
            e.preventDefault();
            if (!updateState(true)) return;
            await refSubmitAjax({
                form,
                saveButton,
                loadingMessage: 'Data Program sedang divalidasi dan disimpan ke database.',
                fieldMap: {
                    kode_program: {
                        input: kode,
                        error: kodeError
                    },
                    nama_program: {
                        input: nama,
                        error: namaError
                    }
                },
                successReset: () => {
                    kode.value = '';
                    nama.value = '';
                    refSetFieldError(kode, kodeError, '');
                    refSetFieldError(nama, namaError, '');
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
