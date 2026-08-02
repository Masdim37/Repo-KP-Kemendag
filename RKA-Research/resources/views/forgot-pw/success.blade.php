<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Berhasil Diperbarui | Penelitian RKA-K/L</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root{
            --blue-dark:#08346d;
            --blue:#0a5cbc;
            --blue-light:#0e6cd8;
            --bg:#f3f7fb;
            --white:#ffffff;
            --text:#173d69;
            --text-soft:#6f87a1;
            --text-muted:#b0bcc9;
            --border:#d6e0ea;
            --green:#1eb35b;
            --green-soft:#eefaf2;
            --green-border:#b9e4c6;
            --shadow:0 15px 35px rgba(40, 70, 110, 0.12);
        }

        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
            font-family:Inter,"Segoe UI",Arial,sans-serif;
        }

        body{
            background:var(--bg);
            color:var(--text);
        }

        a{
            text-decoration:none;
            color:inherit;
        }

        .page{
            min-height:100vh;
            display:flex;
            overflow:hidden;
        }

        /* =========================
           LEFT PANEL
        ========================= */
        .left-panel{
            width:38%;
            min-height:100vh;
            position:relative;
            overflow:hidden;
            background:linear-gradient(160deg,#07356f 0%,#08488f 48%,#0870cb 100%);
            color:#fff;
            padding:31px 24px 26px;
        }

        .left-panel::before,
        .left-panel::after,
        .circle-mid{
            content:"";
            position:absolute;
            border-radius:50%;
            background:rgba(255,255,255,.08);
        }

        .left-panel::before{
            width:210px;
            height:210px;
            top:-65px;
            right:-60px;
        }

        .left-panel::after{
            width:210px;
            height:210px;
            left:-75px;
            bottom:-80px;
        }

        .circle-mid{
            width:130px;
            height:130px;
            right:-48px;
            top:215px;
            background:rgba(255,255,255,.06);
        }

        .left-content{
            position:relative;
            z-index:2;
            min-height:calc(100vh - 57px);
            display:flex;
            flex-direction:column;
        }

        .brand{
            display:flex;
            align-items:center;
            gap:11px;
        }

        .brand-logo{
            width:28px;
            height:28px;
            position:relative;
            flex:0 0 auto;
        }

        .brand-logo-shape{
            position:absolute;
            inset:5px;
            border-radius:3px;
            transform:rotate(45deg);
            background:linear-gradient(135deg,#c7e3ff 0%,#ffffff 45%,#6fa9e0 100%);
            box-shadow:0 4px 10px rgba(0,0,0,.14);
        }

        .brand-logo-shape::after{
            content:"";
            position:absolute;
            width:6px;
            height:6px;
            border-radius:50%;
            background:#0758ad;
            left:6px;
            top:6px;
        }

        .brand-top{
            font-size:9px;
            font-weight:600;
            letter-spacing:.65px;
            line-height:1.3;
            color:rgba(255,255,255,.78);
            display:block;
        }

        .brand-bottom{
            font-size:10px;
            font-weight:700;
            line-height:1.35;
            display:block;
        }

        .system-label{
            margin-top:29px;
            font-size:8px;
            font-weight:600;
            letter-spacing:1px;
            color:rgba(202,226,255,.72);
        }

        .app-title{
            margin-top:10px;
            font-size:19px;
            line-height:1.15;
            font-weight:800;
            letter-spacing:-.3px;
        }

        .title-line{
            width:32px;
            height:3px;
            border-radius:20px;
            background:#76b9ff;
            margin-top:9px;
        }

        .section-title{
            margin-top:18px;
            font-size:11px;
            font-weight:700;
        }

        .section-desc{
            width:235px;
            max-width:100%;
            margin-top:7px;
            font-size:10px;
            line-height:1.7;
            color:rgba(226,239,255,.78);
        }

        /* Illustration */
        .illustration{
            position:relative;
            width:280px;
            height:165px;
            max-width:100%;
            margin:22px auto 10px;
        }

        .dot{
            position:absolute;
            width:5px;
            height:5px;
            border-radius:50%;
            background:rgba(201,226,255,.3);
        }

        .dot.one{left:39px;top:13px;}
        .dot.two{left:35px;bottom:16px;}
        .dot.three{right:28px;bottom:7px;}

        .lock{
            position:absolute;
            left:74px;
            top:10px;
            width:28px;
            height:25px;
            border:1.5px solid rgba(221,239,255,.6);
            border-radius:6px;
            background:rgba(255,255,255,.05);
        }

        .lock::before{
            content:"";
            position:absolute;
            left:4px;
            top:-11px;
            width:16px;
            height:13px;
            border:1.5px solid rgba(221,239,255,.6);
            border-bottom:none;
            border-radius:10px 10px 0 0;
        }

        .lock::after{
            content:"";
            position:absolute;
            left:10px;
            top:9px;
            width:4px;
            height:4px;
            border-radius:50%;
            background:rgba(224,241,255,.85);
        }

        .mini-code{
            position:absolute;
            left:139px;
            top:43px;
            display:flex;
            gap:4px;
        }

        .mini-code span{
            width:14px;
            height:17px;
            border:1px solid rgba(221,239,255,.5);
            border-radius:3px;
            display:flex;
            align-items:center;
            justify-content:center;
            color:rgba(236,245,255,.85);
            background:rgba(255,255,255,.08);
            font-size:8px;
        }

        .envelope{
            position:absolute;
            left:48px;
            top:48px;
            width:119px;
            height:80px;
            border:1.5px solid rgba(213,233,255,.46);
            border-radius:8px;
            background:rgba(255,255,255,.05);
            overflow:hidden;
            box-shadow:0 10px 20px rgba(0,30,80,.12);
        }

        .envelope::before,
        .envelope::after{
            content:"";
            position:absolute;
            top:17px;
            width:80px;
            height:1.5px;
            background:rgba(213,233,255,.36);
        }

        .envelope::before{
            left:-8px;
            transform:rotate(37deg);
        }

        .envelope::after{
            right:-8px;
            transform:rotate(-37deg);
        }

        .envelope .bl,
        .envelope .br{
            position:absolute;
            bottom:13px;
            width:73px;
            height:1.5px;
            background:rgba(213,233,255,.22);
        }

        .envelope .bl{
            left:-9px;
            transform:rotate(-34deg);
        }

        .envelope .br{
            right:-9px;
            transform:rotate(34deg);
        }

        .shield{
            position:absolute;
            right:38px;
            top:76px;
            width:45px;
            height:53px;
            border:1.7px solid rgba(220,239,255,.56);
            background:rgba(255,255,255,.05);
            clip-path:polygon(50% 0%,93% 17%,86% 72%,50% 100%,14% 72%,7% 17%);
        }

        .shield::after{
            content:"";
            position:absolute;
            left:14px;
            top:20px;
            width:18px;
            height:10px;
            border-left:2px solid #d9efff;
            border-bottom:2px solid #d9efff;
            transform:rotate(-45deg);
        }

        .flow-wrap{
            margin-top:auto;
        }

        .flow-title{
            margin-bottom:11px;
            font-size:8px;
            font-weight:600;
            letter-spacing:.6px;
            color:rgba(219,237,255,.7);
        }

        .flow-list{
            display:flex;
            flex-wrap:wrap;
            align-items:center;
            gap:6px;
        }

        .flow-item{
            display:inline-flex;
            align-items:center;
            gap:5px;
            min-height:20px;
            padding:4px 9px;
            border-radius:14px;
            font-size:7px;
            font-weight:600;
            white-space:nowrap;
            color:#edf7ff;
            background:rgba(255,255,255,.16);
        }

        .flow-item.active{
            background:rgba(128,188,249,.52);
        }

        .flow-arrow{
            color:rgba(224,241,255,.48);
            font-size:9px;
        }

        .flow-login{
            margin-top:8px;
        }

        .left-footer{
            margin-top:19px;
            padding-top:16px;
            border-top:1px solid rgba(255,255,255,.14);
            color:rgba(215,234,255,.54);
            font-size:7.5px;
        }



        /* =========================
           STEPPER
        ========================= */
        .stepper{
            display:grid;
            grid-template-columns:
                24px 1fr
                24px 1fr
                24px 1fr
                24px;
            align-items:start;
            margin:0 9px 31px;
            text-align:center;
        }

        .step{
            position:relative;
            z-index:2;
            text-align:center;
        }

        .step-circle{
            width:25px;
            height:25px;
            margin:0 auto;
            display:flex;
            align-items:center;
            justify-content:center;
            border-radius:50%;
            font-size:10px;
            font-weight:700;
        }

        .step.completed .step-circle{
            color:#fff;
            background:var(--green);
        }

        .step.current .step-circle{
            color:#fff;
            background:var(--blue);
            box-shadow:0 0 0 5px rgba(10,92,188,.10);
        }

        .step-label{
            position:absolute;
            left:50%;
            top:31px;
            width:74px;
            transform:translateX(-50%);
            font-size:8px;
            font-weight:550;
            white-space:nowrap;
        }

        .step.completed .step-label{
            color:var(--green);
        }

        .step.current .step-label{
            color:var(--blue);
            font-weight:700;
        }

        .step-line{
            height:1px;
            margin-top:12px;
            background:var(--green);
        }

        /* =========================
           RIGHT PANEL
        ========================= */
        .right-panel{
            width:62%;
            min-height:100vh;
            display:flex;
            flex-direction:column;
            align-items:center;
            justify-content:center;
            padding:30px 20px;
            background:#f4f7fb;
        }

        .success-card{
            width:100%;
            max-width:440px;
            background:#fff;
            border:1px solid rgba(214,223,233,.8);
            border-radius:15px;
            box-shadow:var(--shadow);
            padding:28px 31px 31px;
            text-align:center;
        }

        .success-icon-wrap{
            display:flex;
            justify-content:center;
            margin-bottom:16px;
        }

        .success-icon-outer{
            width:54px;
            height:54px;
            border-radius:50%;
            background:rgba(30,179,91,.12);
            display:flex;
            align-items:center;
            justify-content:center;
        }

        .success-icon-inner{
            width:34px;
            height:34px;
            border-radius:50%;
            background:rgba(30,179,91,.18);
            display:flex;
            align-items:center;
            justify-content:center;
            color:var(--green);
            font-size:18px;
            font-weight:700;
        }

        .success-title{
            font-size:17px;
            font-weight:800;
            line-height:1.3;
            color:#173d69;
            margin-bottom:8px;
        }

        .success-desc{
            max-width:285px;
            margin:0 auto 18px;
            font-size:10px;
            line-height:1.7;
            color:var(--text-soft);
        }

        .tips-box{
            text-align:left;
            background:var(--green-soft);
            border:1px solid var(--green-border);
            border-radius:12px;
            padding:12px 14px;
            margin-bottom:18px;
        }

        .tips-title{
            display:flex;
            align-items:center;
            gap:7px;
            color:#249153;
            font-size:10px;
            font-weight:700;
            margin-bottom:7px;
        }

        .tips-list{
            list-style:none;
            display:flex;
            flex-direction:column;
            gap:4px;
            color:#75a985;
            font-size:9px;
            line-height:1.5;
        }

        .tips-list li::before{
            content:"• ";
        }

        .login-btn{
            width:100%;
            height:42px;
            display:flex;
            align-items:center;
            justify-content:center;
            border:none;
            border-radius:12px;
            background:var(--blue);
            color:#fff;
            font-size:10px;
            font-weight:700;
            cursor:pointer;
            box-shadow:0 8px 18px rgba(10,92,188,.2);
            transition:.2s ease;
        }

        .login-btn i{
            margin-right:7px;
        }

        .login-btn:hover{
            background:#084f9f;
            transform:translateY(-1px);
        }

        .right-footer{
            margin-top:15px;
            text-align:center;
            color:var(--text-muted);
            font-size:8px;
            line-height:1.8;
        }

        .right-footer a{
            color:#0a63be;
            font-weight:600;
        }

        .help-btn{
            position:fixed;
            right:10px;
            bottom:10px;
            width:23px;
            height:23px;
            border-radius:50%;
            background:#24292f;
            color:#fff;
            display:flex;
            align-items:center;
            justify-content:center;
            font-size:12px;
            box-shadow:0 2px 7px rgba(0,0,0,.27);
        }

        /* =========================
           RESPONSIVE
        ========================= */
        @media(max-width:900px){
            .page{
                display:block;
                overflow:visible;
            }

            .left-panel,
            .right-panel{
                width:100%;
                min-height:auto;
            }

            .left-panel{
                padding:28px 23px;
            }

            .left-content{
                min-height:auto;
            }

            .illustration,
            .flow-wrap,
            .circle-mid{
                display:none;
            }

            .right-panel{
                padding:32px 15px 45px;
            }

            .success-card{
                max-width:460px;
            }
        }

        @media(max-width:480px){
            .success-card{
                padding:24px 16px 24px;
            }

            .stepper{
                margin-left:0;
                margin-right:0;
                margin-bottom:29px;
            }

            .step-label{
                width:63px;
                font-size:7px;
            }

            .success-title{
                font-size:16px;
            }
        }
    </style>
</head>
<body>

<div class="page">

    <!-- LEFT -->
    <aside class="left-panel">
        <div class="circle-mid"></div>

        <div class="left-content">
            <div>
                <div class="brand">
                    <div class="brand-logo">
                        <div class="brand-logo-shape"></div>
                    </div>
                    <div>
                        <span class="brand-top">KEMENTERIAN PERDAGANGAN RI</span>
                        <span class="brand-bottom">Biro Perencanaan</span>
                    </div>
                </div>

                <div class="system-label">SISTEM INFORMASI</div>
                <h1 class="app-title">Penelitian RKA-K/L</h1>
                <div class="title-line"></div>

                <div class="section-title">Pemulihan Akses Akun</div>
                <div class="section-desc">
                    Ikuti langkah-langkah berikut untuk mengatur ulang kata sandi akun Anda
                    dengan aman melalui verifikasi email.
                </div>
            </div>

            <div class="illustration">
                <span class="dot one"></span>
                <span class="dot two"></span>
                <span class="dot three"></span>

                <div class="lock"></div>

                <div class="mini-code">
                    <span>3</span>
                    <span>8</span>
                    <span>4</span>
                </div>

                <div class="envelope">
                    <span class="bl"></span>
                    <span class="br"></span>
                </div>

                <div class="shield"></div>
            </div>

            <div class="flow-wrap">
                <div class="flow-title">ALUR PEMULIHAN</div>

                <div class="flow-list">
                    <div class="flow-item">
                        <i class="bi bi-envelope-fill"></i>
                        Masukkan Email
                    </div>

                    <i class="bi bi-chevron-right flow-arrow"></i>

                    <div class="flow-item">
                        <i class="bi bi-key-fill"></i>
                        Verifikasi OTP
                    </div>

                    <i class="bi bi-chevron-right flow-arrow"></i>

                    <div class="flow-item active">
                        <i class="bi bi-lock-fill"></i>
                        Buat Password Baru
                    </div>
                </div>

                <div class="flow-login">
                    <div class="flow-item">
                        <i class="bi bi-check-circle-fill"></i>
                        Login
                    </div>
                </div>

                <div class="left-footer">
                    © 2025 Kementerian Perdagangan Republik Indonesia. Hak Cipta Dilindungi.
                </div>
            </div>
        </div>
    </aside>

    <!-- RIGHT -->
    <main class="right-panel">
        <section class="success-card">


            <div class="stepper">

                <div class="step completed">
                    <div class="step-circle">
                        <i class="bi bi-check-lg"></i>
                    </div>
                    <span class="step-label">Email</span>
                </div>

                <div class="step-line"></div>

                <div class="step completed">
                    <div class="step-circle">
                        <i class="bi bi-check-lg"></i>
                    </div>
                    <span class="step-label">Verifikasi</span>
                </div>

                <div class="step-line"></div>

                <div class="step completed">
                    <div class="step-circle">
                        <i class="bi bi-check-lg"></i>
                    </div>
                    <span class="step-label">Password Baru</span>
                </div>

                <div class="step-line"></div>

                <div class="step current">
                    <div class="step-circle">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                    <span class="step-label">Selesai</span>
                </div>

            </div>

            <div class="success-icon-wrap">
                <div class="success-icon-outer">
                    <div class="success-icon-inner">
                        <i class="bi bi-check-circle"></i>
                    </div>
                </div>
            </div>

            <h2 class="success-title">Kata Sandi Berhasil Diperbarui!</h2>

            <p class="success-desc">
                Kata sandi akun Anda telah berhasil diperbarui.
                Silakan login menggunakan kata sandi baru Anda
                untuk mengakses sistem.
            </p>

            <div class="tips-box">
                <div class="tips-title">
                    <i class="bi bi-shield-check"></i>
                    Tips Keamanan Akun
                </div>

                <ul class="tips-list">
                    <li>Jangan bagikan kata sandi kepada siapapun</li>
                    <li>Ganti kata sandi secara berkala setiap 3 bulan</li>
                    <li>Gunakan kata sandi yang berbeda untuk setiap akun</li>
                </ul>
            </div>

            <a href="{{ route('login') }}" class="login-btn">
                <i class="bi bi-arrow-left"></i>
                Kembali ke Halaman Login
            </a>

        </section>

        <div class="right-footer">
            <div>
                Butuh bantuan?
                <a href="#">Hubungi Administrator Sistem</a>
            </div>
            <div>
                © 2025 Biro Perencanaan — Kementerian Perdagangan Republik Indonesia
            </div>
        </div>
    </main>

</div>

<a href="#" class="help-btn">?</a>

</body>
</html>