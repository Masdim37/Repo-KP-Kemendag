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

        $jabatanName = data_get($user ?? null, 'jabatan.jabatan_name');

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
            <main class="page-container"></main>

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
