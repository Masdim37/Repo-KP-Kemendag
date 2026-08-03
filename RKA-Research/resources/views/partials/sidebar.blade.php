@php
    /*
    |--------------------------------------------------------------------------
    | KONFIGURASI SIDEBAR
    |--------------------------------------------------------------------------
    |
    | Variabel dapat dikirim melalui @include. Jika tidak dikirim, partial
    | menggunakan data session/default agar tetap aman digunakan.
    |
    */

    $sidebarActiveMenu = $activeMenu ?? 'account';

    $sidebarUserName = $sidebarUserName ?? ($userName ?? session('user_name', 'Pengguna Sistem'));

    $sidebarUserRole = $sidebarUserRole ?? ($jabatanName ?? 'Pengguna Sistem');

    $sidebarInitials =
        $sidebarInitials ??
        ($initials ??
            collect(explode(' ', $sidebarUserName))
                ->filter()
                ->take(2)
                ->map(fn($word) => strtoupper(substr($word, 0, 1)))
                ->implode(''));

    $sidebarMenus = $sidebarMenus ?? [
        [
            'key' => 'Dashboard',
            'label' => 'Dashboard',
            'icon' => 'bi-grid-1x2-fill',
            'url' => Route::has('dashboard') ? route('dashboard') : url('/Dashboard'),
        ],
        [
            'key' => 'menu2',
            'label' => 'Menu 2',
            'icon' => 'bi-folder2-open',
            'url' => '#',
        ],
        [
            'key' => 'menu3',
            'label' => 'Menu 3',
            'icon' => 'bi-bar-chart-line-fill',
            'url' => '#',
        ],
        [
            'key' => 'Account',
            'label' => 'Account',
            'icon' => 'bi-person-circle',
            'url' => Route::has('account.show') ? route('account.show') : url('/Account'),
        ],
    ];
@endphp

@once
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
@endonce

@once
    <style>
        :root {
            --sidebar-start: #06356c;
            --sidebar-middle: #064996;
            --sidebar-end: #0872cf;
            --sidebar-width: 250px;
            --header-height: 66px;
        }

        .sidebar {
            position: fixed;
            inset: 0 auto 0 0;
            z-index: 1100;
            width: var(--sidebar-width);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            color: #ffffff;
            background:
                radial-gradient(circle at 105% 18%,
                    rgba(255, 255, 255, 0.10) 0,
                    rgba(255, 255, 255, 0.10) 92px,
                    transparent 93px),
                radial-gradient(circle at -18% 88%,
                    rgba(255, 255, 255, 0.08) 0,
                    rgba(255, 255, 255, 0.08) 120px,
                    transparent 121px),
                linear-gradient(160deg,
                    var(--sidebar-start) 0%,
                    var(--sidebar-middle) 48%,
                    var(--sidebar-end) 100%);
            box-shadow: 12px 0 34px rgba(16, 52, 93, 0.13);
            overflow: hidden;
            transition: transform 0.25s ease;
        }

        .sidebar-header {
            min-height: var(--header-height);
            display: flex;
            align-items: center;
            gap: 11px;
            padding: 15px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.13);
        }

        .sidebar-brand-logo {
            position: relative;
            width: 31px;
            height: 31px;
            flex: 0 0 auto;
        }

        .sidebar-brand-logo::before {
            content: "";
            position: absolute;
            inset: 6px;
            transform: rotate(45deg);
            border-radius: 4px;
            background:
                linear-gradient(135deg,
                    #bfe0ff 0%,
                    #ffffff 48%,
                    #6ca8e4 100%);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.14);
        }

        .sidebar-brand-logo::after {
            content: "";
            position: absolute;
            width: 6px;
            height: 6px;
            left: 13px;
            top: 13px;
            border-radius: 50%;
            background: #1265ba;
        }

        .sidebar-brand-text {
            min-width: 0;
        }

        .sidebar-brand-government {
            display: block;
            color: rgba(255, 255, 255, 0.70);
            font-size: 8px;
            font-weight: 600;
            letter-spacing: 0.65px;
            line-height: 1.35;
        }

        .sidebar-brand-name {
            display: block;
            margin-top: 2px;
            color: #ffffff;
            font-size: 11px;
            font-weight: 750;
            line-height: 1.35;
        }

        .sidebar-body {
            flex: 1;
            display: flex;
            flex-direction: column;
            padding: 21px 14px 15px;
        }

        .sidebar-system-label {
            padding: 0 10px;
            color: rgba(202, 226, 255, 0.62);
            font-size: 7.5px;
            font-weight: 700;
            letter-spacing: 1px;
        }

        .sidebar-system-title {
            margin-top: 7px;
            padding: 0 10px 20px;
            color: #ffffff;
            font-size: 15px;
            font-weight: 800;
            line-height: 1.3;
        }

        .sidebar-nav-label {
            margin: 2px 10px 9px;
            color: rgba(219, 237, 255, 0.55);
            font-size: 7.5px;
            font-weight: 700;
            letter-spacing: 0.8px;
        }

        .sidebar-nav {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .sidebar-link {
            position: relative;
            min-height: 43px;
            display: flex;
            align-items: center;
            gap: 11px;
            padding: 0 13px;
            border: 1px solid transparent;
            border-radius: 11px;
            color: rgba(238, 247, 255, 0.80);
            text-decoration: none;
            font-size: 10px;
            font-weight: 650;
            transition:
                background 0.2s ease,
                border-color 0.2s ease,
                color 0.2s ease,
                transform 0.2s ease;
        }

        .sidebar-link i {
            width: 18px;
            text-align: center;
            font-size: 15px;
        }

        .sidebar-link:hover {
            color: #ffffff;
            background: rgba(255, 255, 255, 0.10);
            transform: translateX(2px);
        }

        .sidebar-link.active {
            color: #ffffff;
            border-color: rgba(255, 255, 255, 0.20);
            background: rgba(128, 188, 249, 0.30);
            box-shadow: 0 8px 20px rgba(0, 28, 70, 0.13);
        }

        .sidebar-link.active::before {
            content: "";
            position: absolute;
            left: -14px;
            top: 8px;
            bottom: 8px;
            width: 3px;
            border-radius: 0 5px 5px 0;
            background: #91caff;
        }

        .sidebar-link-badge {
            margin-left: auto;
            padding: 3px 7px;
            border-radius: 20px;
            color: #e9f6ff;
            background: rgba(255, 255, 255, 0.15);
            font-size: 7px;
            font-weight: 700;
        }

        .sidebar-footer {
            margin-top: auto;
            padding-top: 17px;
            border-top: 1px solid rgba(255, 255, 255, 0.13);
        }

        .sidebar-user {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 10px 12px;
        }

        .sidebar-avatar {
            width: 34px;
            height: 34px;
            flex: 0 0 auto;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid rgba(255, 255, 255, 0.24);
            border-radius: 50%;
            color: #ffffff;
            background: rgba(255, 255, 255, 0.15);
            font-size: 9px;
            font-weight: 800;
        }

        .sidebar-user-copy {
            min-width: 0;
        }

        .sidebar-user-name {
            display: block;
            overflow: hidden;
            color: #ffffff;
            font-size: 9px;
            font-weight: 700;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .sidebar-user-role {
            display: block;
            margin-top: 2px;
            overflow: hidden;
            color: rgba(224, 240, 255, 0.62);
            font-size: 7.5px;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .sidebar-logout-form {
            margin: 0;
        }

        .sidebar-logout {
            width: 100%;
            min-height: 37px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            border: 1px solid rgba(255, 255, 255, 0.16);
            border-radius: 10px;
            color: rgba(239, 247, 255, 0.88);
            background: rgba(255, 255, 255, 0.08);
            font-size: 9px;
            font-weight: 700;
            cursor: pointer;
        }

        .sidebar-logout:hover {
            color: #ffffff;
            background: rgba(255, 255, 255, 0.14);
        }

        .sidebar-overlay {
            position: fixed;
            inset: 0;
            z-index: 1050;
            display: none;
            background: rgba(7, 31, 60, 0.50);
            backdrop-filter: blur(2px);
        }

        .app-main {
            min-height: 100vh;
            margin-left: var(--sidebar-width);
            display: flex;
            flex-direction: column;
            transition: margin-left 0.25s ease;
        }

        .sidebar-toggle {
            width: 36px;
            height: 36px;
            display: none;
            align-items: center;
            justify-content: center;
            border: 1px solid #d6e0eb;
            border-radius: 9px;
            color: var(--primary, #0759b7);
            background: #ffffff;
            font-size: 17px;
            cursor: pointer;
        }

        @media (max-width: 1024px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.open {
                transform: translateX(0);
            }

            .sidebar-overlay.show {
                display: block;
            }

            .app-main {
                margin-left: 0;
            }

            .sidebar-toggle {
                display: flex;
            }

            body.sidebar-open {
                overflow: hidden;
            }
        }

        @media (max-width: 480px) {
            .sidebar {
                width: min(84vw, 276px);
            }
        }
    </style>
@endonce

<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <div class="sidebar-brand-logo" aria-hidden="true"></div>

        <div class="sidebar-brand-text">
            <span class="sidebar-brand-government">
                KEMENTERIAN PERDAGANGAN RI
            </span>
            <span class="sidebar-brand-name">
                Biro Perencanaan
            </span>
        </div>
    </div>

    <div class="sidebar-body">
        <div class="sidebar-system-label">
            SISTEM INFORMASI
        </div>

        <div class="sidebar-system-title">
            Penelitian RKA-K/L
        </div>

        <div class="sidebar-nav-label">
            MENU UTAMA
        </div>

        <nav class="sidebar-nav" aria-label="Navigasi utama">
            @foreach ($sidebarMenus as $menu)
                @php
                    $menuIsActive = $sidebarActiveMenu === $menu['key'];
                @endphp

                <a href="{{ $menu['url'] ?? '#' }}" class="sidebar-link {{ $menuIsActive ? 'active' : '' }}"
                    @if ($menuIsActive) aria-current="page" @endif>
                    <i class="bi {{ $menu['icon'] }}"></i>
                    <span>{{ $menu['label'] }}</span>

                    {{-- @if ($menuIsActive)
                        <span class="sidebar-link-badge">Aktif</span>
                    @endif --}}
                </a>
            @endforeach
        </nav>

        <div class="sidebar-footer">
            <div class="sidebar-user">
                <div class="sidebar-avatar">
                    {{ $sidebarInitials ?: 'US' }}
                </div>

                <div class="sidebar-user-copy">
                    <span class="sidebar-user-name">
                        {{ $sidebarUserName }}
                    </span>
                    <span class="sidebar-user-role">
                        {{ $sidebarUserRole }}
                    </span>
                </div>
            </div>

            <form action="{{ route('logout') }}" method="POST" class="sidebar-logout-form">
                @csrf

                <button type="submit" class="sidebar-logout">
                    <i class="bi bi-box-arrow-right"></i>
                    Logout
                </button>
            </form>
        </div>
    </div>
</aside>

<div class="sidebar-overlay" id="sidebarOverlay"></div>

@once
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const sidebar = document.getElementById("sidebar");
            const sidebarOverlay =
                document.getElementById("sidebarOverlay");
            const sidebarToggle =
                document.getElementById("sidebarToggle");

            if (!sidebar || !sidebarOverlay) {
                return;
            }

            function setSidebarState(isOpen) {
                sidebar.classList.toggle("open", isOpen);
                sidebarOverlay.classList.toggle("show", isOpen);
                document.body.classList.toggle(
                    "sidebar-open",
                    isOpen
                );

                if (sidebarToggle) {
                    sidebarToggle.setAttribute(
                        "aria-expanded",
                        String(isOpen)
                    );
                }
            }

            if (sidebarToggle) {
                sidebarToggle.addEventListener("click", () => {
                    setSidebarState(
                        !sidebar.classList.contains("open")
                    );
                });
            }

            sidebarOverlay.addEventListener("click", () => {
                setSidebarState(false);
            });

            sidebar
                .querySelectorAll(".sidebar-link")
                .forEach(link => {
                    link.addEventListener("click", () => {
                        if (window.innerWidth <= 1024) {
                            setSidebarState(false);
                        }
                    });
                });

            document.addEventListener("keydown", event => {
                if (event.key === "Escape") {
                    setSidebarState(false);
                }
            });

            window.addEventListener("resize", () => {
                if (window.innerWidth > 1024) {
                    setSidebarState(false);
                }
            });
        });
    </script>
@endonce
