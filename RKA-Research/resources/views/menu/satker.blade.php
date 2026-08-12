<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard | Penelitian RKA-K/L</title>

    <style>
        :root {
            --primary: #0759b7;
            --text-primary: #18365b;
            --text-secondary: #607995;
            --background: #ffffff;
            --border: #dbe5ee;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html,
        body {
            min-height: 100%;
        }

        body {
            min-height: 100vh;
            overflow-x: hidden;
            color: var(--text-primary);
            background: var(--background);
            font-family: Inter, "Segoe UI", Arial, sans-serif;
        }

        button {
            font: inherit;
        }

        .app-shell {
            min-height: 100vh;
        }

        .dashboard-header {
            position: sticky;
            top: 0;
            z-index: 900;
            min-height: var(--header-height, 66px);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 18px;
            padding: 0 25px;
            border-bottom: 1px solid var(--border);
            background: rgba(255, 255, 255, 0.96);
            box-shadow: 0 4px 18px rgba(33, 67, 103, 0.05);
            backdrop-filter: blur(12px);
        }

        .header-left {
            min-width: 0;
            display: flex;
            align-items: center;
            gap: 13px;
        }

        .header-copy {
            min-width: 0;
        }

        .header-eyebrow {
            overflow: hidden;
            color: #879bb1;
            font-size: 7.5px;
            font-weight: 700;
            letter-spacing: 0.8px;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .header-title {
            margin-top: 3px;
            overflow: hidden;
            color: var(--text-primary);
            font-size: 14px;
            font-weight: 800;
            line-height: 1.3;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .header-user {
            flex-shrink: 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .header-user-text {
            text-align: right;
            color: var(--text-secondary);
            font-size: 8.5px;
            line-height: 1.4;
        }

        .header-user-text strong {
            display: block;
            max-width: 200px;
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
            box-shadow: 0 5px 12px rgba(31, 91, 148, 0.16);
            font-size: 10px;
            font-weight: 800;
        }

        .page-container {
            width: 100%;
            min-height: 0;
            flex: 1;
            background: #ffffff;
        }

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

        @media (max-width: 680px) {
            .dashboard-header {
                padding: 0 14px;
            }

            .header-user-text {
                display: none;
            }

            .footer-container {
                padding: 15px 14px;
            }
        }
    </style>
</head>

<body>

    @php
        /*
        |--------------------------------------------------------------------------
        | DATA DASAR TEMPLATE
        |--------------------------------------------------------------------------
        | Controller dapat mengirim $user, $pageTitle, dan $activeMenu.
        */

        $userName = data_get($user ?? null, 'name', session('user_name'));

        $jabatanName = data_get($user ?? null, 'jabatan.jabatan_name', session('jabatan_id'));

        $initials = collect(explode(' ', $userName))
            ->filter()
            ->take(2)
            ->map(fn($word) => strtoupper(substr($word, 0, 1)))
            ->implode('');

    @endphp

    <div class="app-shell">

        @include('partials.sidebar', [
            'activeMenu' => 'Dashboard',
            'sidebarUserName' => $userName,
            'sidebarUserRole' => $jabatanName,
            'sidebarInitials' => $initials,
        ])

        <div class="app-main">

            <header class="dashboard-header">

                <div class="header-left">

                    <button type="button" class="sidebar-toggle" id="sidebarToggle" aria-label="Buka menu navigasi"
                        aria-expanded="false">
                        <i class="bi bi-list"></i>
                    </button>

                    <div class="header-copy">

                        <div class="header-eyebrow">
                            SISTEM INFORMASI PENELITIAN RKA-K/L
                        </div>

                        <div class="header-title">
                            Dashboard
                        </div>

                    </div>

                </div>

                <div class="header-user">

                    <div class="header-user-text">
                        Biro Perencanaan

                        <strong>
                            {{ $userName }}
                        </strong>
                    </div>

                    <div class="header-avatar">
                        {{ $initials ?: 'US' }}
                    </div>

                </div>

            </header>

            {{-- Area konten sengaja dikosongkan sebagai template menu. --}}
            <main class="page-container" style="padding: 30px;">

                <div class="form-wrapper"
                    style="max-width: 600px; margin: 0 auto; background: #ffffff; padding: 30px; border-radius: 12px; border: 1px solid var(--border); box-shadow: 0 4px 18px rgba(33, 67, 103, 0.05);">

                    <h2 style="font-size: 18px; font-weight: 800; color: var(--text-primary); margin-bottom: 24px;">
                        Upload Data Satker
                    </h2>

                    {{-- 1. Menampilkan Pesan Sukses --}}
@if (session('success'))
    <div style="padding: 15px; margin-bottom: 20px; background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; border-radius: 5px;">
        <strong>BERHASIL:</strong> {{ session('success') }}
    </div>
@endif

{{-- 2. Menampilkan Pesan Error dari Try-Catch --}}
@if (session('error'))
    <div style="padding: 15px; margin-bottom: 20px; background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; border-radius: 5px;">
        <strong>GAGAL:</strong> {{ session('error') }}
    </div>
@endif

{{-- 3. Menampilkan Pesan Error Validasi (Misal: salah ekstensi, file terlalu besar) --}}
@if ($errors->any())
    <div style="padding: 15px; margin-bottom: 20px; background-color: #fff3cd; color: #856404; border: 1px solid #ffeeba; border-radius: 5px;">
        <strong>ERROR VALIDASI:</strong>
        <ul style="margin-top: 5px; margin-bottom: 0; padding-left: 20px;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

                    {{-- Pastikan action mengarah ke route yang sudah Anda buat di controller --}}
                    <form action="{{ route('upload.satker.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div style="margin-bottom: 24px;">
                            <label for="file_satker"
                                style="display: block; font-size: 11px; font-weight: 700; color: var(--text-secondary); margin-bottom: 8px; letter-spacing: 0.5px;">
                                FILE EXCEL DATA SATKER
                            </label>

                            {{-- Atribut accept="MIME_types" membatasi agar dialog file hanya menampilkan ekstensi excel --}}
                            <input type="file" id="file_satker" name="file_satker" accept=".xlsx, .xls, .csv"
                                required
                                style="width: 100%; padding: 12px 15px; border: 1px solid var(--border); border-radius: 8px; font-family: inherit; font-size: 13px; color: var(--text-primary); background: #f8fafc; cursor: pointer;">

                            <small
                                style="display: block; margin-top: 8px; font-size: 10px; color: var(--text-secondary);">
                                Format yang didukung: .xlsx, .xls, .csv (Maksimal 50MB).
                            </small>
                        </div>

                        <button type="submit"
                            style="width: 100%; background: var(--primary); color: #ffffff; border: none; padding: 14px 20px; border-radius: 8px; font-size: 13px; font-weight: 700; cursor: pointer; transition: background 0.2s ease;">
                            <i class="bi bi-cloud-arrow-up" style="margin-right: 5px;"></i> Unggah File Excel
                        </button>

                    </form>

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

</body>

</html>
