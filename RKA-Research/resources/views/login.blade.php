<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Penelitian RKA-K/L</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Segoe UI", sans-serif;
        }

        body {
            background: #edf2f7;
            overflow-x: hidden;
        }

        .container-login {
            display: flex;
            min-height: 100vh;
        }

        /* LEFT */
        .left {
            width: 60%;
            background: linear-gradient(180deg, #294f82, #18385f);
            color: white;
            padding: 55px 70px;
        }

        .logo {
            width: 170px;
            margin-bottom: 35px;
        }

        .badge-system {
            display: inline-block;
            background: rgba(255, 255, 255, .15);
            padding: 8px 18px;
            border-radius: 30px;
            font-size: 12px;
            margin-bottom: 20px;
        }

        .title {
            font-size: 60px;
            font-weight: bold;
            line-height: 1.1;
        }

        .title span {
            color: #8fb7ff;
        }

        .desc {
            width: 75%;
            color: #d7e4ff;
            margin: 25px 0 40px;
        }

        .menu-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 18px;
        }

        .menu-card {
            background: rgba(255, 255, 255, .08);
            border-radius: 12px;
            padding: 18px;
            transition: .3s;
        }

        .menu-card:hover {
            background: rgba(255, 255, 255, .15);
        }

        .menu-card h5 {
            font-size: 17px;
            margin-bottom: 6px;
        }

        .menu-card p {
            font-size: 13px;
            color: #d5e3ff;
            margin: 0;
        }

        hr {
            margin: 50px 0 30px;
            opacity: .2;
        }

        .alur {
            display: flex;
            justify-content: space-between;
            text-align: center;
            margin-top: 20px;
        }

        .circle {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .2);
            display: flex;
            justify-content: center;
            align-items: center;
            margin: auto;
            font-weight: bold;
        }

        .active {
            background: #2b93ff;
        }

        .alur small {
            display: block;
            margin-top: 10px;
            font-size: 12px;
        }

        .bottom {
            display: flex;
            justify-content: space-between;
            margin-top: 60px;
        }

        .bottom h3 {
            font-weight: bold;
        }

        /* RIGHT */

        .right {
            width: 40%;
            display: flex;
            justify-content: center;
            align-items: center;
            background: #eef3fa;
        }

        .login-box {
            width: 400px;
            background: white;
            padding: 40px;
            border-radius: 18px;
            box-shadow: 0 10px 35px rgba(0, 0, 0, .08);
        }

        .login-logo {
            width: 90px;
            display: block;
            margin: auto;
        }

        .login-box h2 {
            text-align: center;
            margin-top: 20px;
            font-weight: bold;
        }

        .login-box p {
            text-align: center;
            color: gray;
            font-size: 14px;
            margin-bottom: 30px;
        }

        .form-control {
            height: 48px;
            border-radius: 10px;
        }

        .btn-login {
            background: #0d6efd;
            color: white;
            height: 48px;
            border-radius: 10px;
            border: none;
            transition: .3s;
        }

        .btn-login:hover {
            background: #0056d6;
        }

        .info {
            margin-top: 25px;
            background: #f2f8ff;
            border-left: 4px solid #0d6efd;
            padding: 15px;
            font-size: 13px;
            border-radius: 8px;
        }

        a {
            text-decoration: none;
            font-size: 13px;
        }

        @media(max-width:992px) {

            .left {
                display: none;
            }

            .right {
                width: 100%;
            }

            .login-box {
                width: 90%;
            }

        }

        .register-section {
            margin-top: 18px;
            text-align: center;
            color: #6c757d;
            font-size: 13px;
        }

        .register-section a {
            color: #0d6efd;
            font-weight: 700;
            text-decoration: none;
            margin-left: 4px;
        }

        .register-section a:hover {
            color: #0056d6;
            text-decoration: underline;
        }
    </style>

</head>

<body>

    <div class="container-login">

        <!-- LEFT -->

        <div class="left">

            <img src="logo.png" class="logo">

            <div class="badge-system">
                SISTEM INFORMASI
            </div>

            <div class="title">
                Penelitian <br>
                <span>RKA-K/L</span>
            </div>

            <div class="desc">
                Perancangan, Validasi, dan Evaluasi Perencanaan serta Penganggaran dalam satu sistem penelitian berbasis
                data.
            </div>

            <div class="menu-grid">

                <div class="menu-card">
                    <h5>RKP/Renja</h5>
                    <p>Rencana Kerja Pemerintah</p>
                </div>

                <div class="menu-card">
                    <h5>TOR/KAK</h5>
                    <p>Kerangka Acuan Kerja</p>
                </div>

                <div class="menu-card">
                    <h5>RAB</h5>
                    <p>Rencana Anggaran Biaya</p>
                </div>

                <div class="menu-card">
                    <h5>RKA-K/L</h5>
                    <p>Rencana Kerja & Anggaran</p>
                </div>

            </div>

            <hr>

            <small>ALUR SISTEM</small>

            <div class="alur">

                <div>
                    <div class="circle active">1</div>
                    <small>Upload</small>
                </div>

                <div>
                    <div class="circle">2</div>
                    <small>Validasi</small>
                </div>

                <div>
                    <div class="circle">3</div>
                    <small>Skor</small>
                </div>

                <div>
                    <div class="circle">4</div>
                    <small>Rekomendasi</small>
                </div>

            </div>

            <div class="bottom">

                <div>
                    <h3>4</h3>
                    <small>Jenis Dokumen</small>
                </div>

                <div>
                    <h3>100%</h3>
                    <small>Terintegrasi</small>
                </div>

                <div>
                    <h3>APBN</h3>
                    <small>Berbasis Data</small>
                </div>

            </div>

        </div>

        <!-- RIGHT -->

        <div class="right">

            <div class="login-box">

                <img src="logo.png" class="login-logo">

                <h2>Masuk ke Sistem</h2>

                <p>Gunakan akun yang telah terdaftar untuk mengakses dashboard penelitian RKA-K/L.</p>

                <form>

                    <div class="mb-3">
                        <label class="mb-2">Username</label>
                        <input type="text" class="form-control" placeholder="Masukkan username">
                    </div>

                    <div class="mb-3">
                        <label class="mb-2">Password</label>
                        <input type="password" class="form-control" placeholder="Masukkan password">
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-4">

                        {{-- <div class="form-check">
                            <input class="form-check-input" type="checkbox">
                            <label class="form-check-label">
                                Ingat Saya
                            </label>
                        </div> --}}

                        <a href="#">Lupa Password?</a>

                    </div>

                    <button class="btn btn-login w-100">
                        Masuk
                    </button>

                </form>

                <div class="register-section">
                    Belum punya akun?
                    <a href="{{ url('/register') }}">
                        Register
                    </a>
                </div>

                {{-- <div class="info">
                    <strong>✔ Akses sistem hanya untuk pengguna yang berwenang.</strong><br>
                    Semua aktivitas diawasi sesuai kebijakan keamanan TI Kementerian Perdagangan.
                </div> --}}

            </div>

        </div>

    </div>

</body>

</html>
