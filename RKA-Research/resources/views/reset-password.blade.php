<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Baru | Penelitian RKA-K/L</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root{
            --primary-dark:#08346d;
            --primary:#0b5ec2;
            --primary-soft:#eaf3ff;
            --primary-text:#0d4da1;
            --bg:#f2f5f9;
            --white:#ffffff;
            --text:#183b66;
            --text-soft:#6f89a5;
            --muted:#b4c2d2;
            --border:#d7e1ec;
            --green:#1fb55c;
            --shadow:0 12px 30px rgba(40, 70, 110, 0.12);
        }

        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
            font-family: "Segoe UI", Inter, Arial, sans-serif;
        }

        body{
            background:var(--bg);
            color:var(--text);
        }

        a{
            text-decoration:none;
        }

        .page{
            min-height:100vh;
            display:flex;
        }

        /* LEFT PANEL */
        .left-panel{
            width:38%;
            min-height:100vh;
            position:relative;
            overflow:hidden;
            background:linear-gradient(180deg, #08356f 0%, #0b4fa4 55%, #0d61c2 100%);
            color:#fff;
            padding:30px 24px 28px;
        }

        .left-panel::before,
        .left-panel::after{
            content:"";
            position:absolute;
            border-radius:50%;
            background:rgba(255,255,255,0.08);
        }

        .left-panel::before{
            width:210px;
            height:210px;
            top:-62px;
            right:-58px;
        }

        .left-panel::after{
            width:190px;
            height:190px;
            bottom:-72px;
            left:-62px;
        }

        .circle-mid{
            position:absolute;
            width:130px;
            height:130px;
            border-radius:50%;
            right:-45px;
            top:215px;
            background:rgba(255,255,255,0.06);
        }

        .left-content{
            position:relative;
            z-index:2;
            min-height:calc(100vh - 58px);
            display:flex;
            flex-direction:column;
        }

        .brand{
            display:flex;
            align-items:center;
            gap:12px;
        }

        .brand-logo{
            width:28px;
            height:28px;
            position:relative;
            flex:0 0 auto;
        }

        .brand-logo .diamond{
            position:absolute;
            inset:5px;
            transform:rotate(45deg);
            border-radius:3px;
            background:linear-gradient(135deg,#c9e3ff 0%, #ffffff 45%, #76aee4 100%);
        }

        .brand-logo .diamond::after{
            content:"";
            position:absolute;
            width:6px;
            height:6px;
            background:#0d59b0;
            border-radius:50%;
            left:6px;
            top:6px;
        }

        .brand-text .gov{
            font-size:9px;
            letter-spacing:.7px;
            font-weight:600;
            color:rgba(255,255,255,.8);
            line-height:1.3;
        }

        .brand-text .unit{
            font-size:10px;
            font-weight:700;
            line-height:1.3;
        }

        .system-label{
            margin-top:30px;
            font-size:8px;
            letter-spacing:1px;
            font-weight:600;
            color:rgba(210,230,255,.75);
        }

        .app-title{
            margin-top:10px;
            font-size:18px;
            font-weight:800;
            line-height:1.2;
        }

        .title-line{
            width:32px;
            height:3px;
            border-radius:999px;
            background:#72b6ff;
            margin-top:10px;
        }

        .section-title{
            margin-top:18px;
            font-size:11px;
            font-weight:700;
        }

        .section-desc{
            width:235px;
            max-width:100%;
            margin-top:8px;
            font-size:10px;
            line-height:1.7;
            color:rgba(230,241,255,.8);
        }

        /* Illustration */
        .illustration{
            position:relative;
            width:280px;
            height:160px;
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

        .dot.one{ left:38px; top:14px; }
        .dot.two{ left:34px; bottom:15px; }
        .dot.three{ right:29px; bottom:8px; }

        .lock{
            position:absolute;
            left:72px;
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
            width:4px;
            height:4px;
            left:10px;
            top:9px;
            border-radius:50%;
            background:rgba(224,241,255,.85);
        }

        .envelope{
            position:absolute;
            left:48px;
            top:47px;
            width:118px;
            height:80px;
            border:1.5px solid rgba(213,233,255,.45);
            border-radius:8px;
            background:rgba(255,255,255,.05);
            overflow:hidden;
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

        .mini-otp{
            position:absolute;
            left:137px;
            top:43px;
            display:flex;
            gap:4px;
        }

        .mini-otp span{
            width:14px;
            height:17px;
            border:1px solid rgba(221,239,255,.5);
            border-radius:3px;
            display:flex;
            align-items:center;
            justify-content:center;
            font-size:8px;
            background:rgba(255,255,255,.08);
            color:rgba(236,245,255,.85);
        }

        .shield{
            position:absolute;
            right:40px;
            top:75px;
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
            color:rgba(219,237,255,.75);
            font-size:8px;
            font-weight:600;
            letter-spacing:.6px;
            margin-bottom:10px;
        }

        .flow-list{
            display:flex;
            flex-wrap:wrap;
            gap:6px;
            align-items:center;
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
            color:#edf7ff;
            background:rgba(255,255,255,.16);
            white-space:nowrap;
        }

        .flow-item.active{
            background:rgba(128,188,249,.52);
        }

        .arrow{
            color:rgba(224,241,255,.48);
            font-size:9px;
        }

        .flow-login{
            margin-top:8px;
        }

        .copyright{
            margin-top:20px;
            padding-top:17px;
            border-top:1px solid rgba(255,255,255,.15);
            color:rgba(215,234,255,.54);
            font-size:7.5px;
        }

        /* RIGHT PANEL */
        .right-panel{
            width:62%;
            min-height:100vh;
            display:flex;
            flex-direction:column;
            align-items:center;
            justify-content:center;
            padding:45px 20px;
            background:#f4f7fb;
        }

        .card{
            width:100%;
            max-width:425px;
            background:#fff;
            border:1px solid rgba(216,225,236,.75);
            border-radius:15px;
            box-shadow:var(--shadow);
            padding:20px 22px 22px;
        }

        .badge{
            display:inline-flex;
            align-items:center;
            gap:5px;
            padding:5px 10px;
            border-radius:20px;
            font-size:9px;
            font-weight:650;
            color:#0a63be;
            background:#eef6ff;
        }

        .stepper{
            display:grid;
            grid-template-columns:24px 1fr 24px 1fr 24px 1fr 24px;
            align-items:start;
            margin:18px 0 22px;
        }

        .step{
            position:relative;
            text-align:center;
        }

        .step-circle{
            width:25px;
            height:25px;
            border-radius:50%;
            margin:0 auto;
            display:flex;
            align-items:center;
            justify-content:center;
            font-size:10px;
            font-weight:700;
        }

        .step.completed .step-circle{
            background:var(--green);
            color:#fff;
        }

        .step.current .step-circle{
            background:#075dbd;
            color:#fff;
            box-shadow:0 0 0 5px rgba(7,93,189,.1);
        }

        .step.pending .step-circle{
            background:#e7edf5;
            color:#9aaabd;
        }

        .step-label{
            position:absolute;
            left:50%;
            top:31px;
            transform:translateX(-50%);
            width:70px;
            font-size:8px;
            white-space:nowrap;
            font-weight:550;
        }

        .step.completed .step-label{ color:var(--green); }
        .step.current .step-label{ color:#075dbd; font-weight:700; }
        .step.pending .step-label{ color:#9baabd; }

        .step-line{
            height:1px;
            margin-top:12px;
            background:#d9e2ed;
        }

        .step-line.completed{
            background:var(--green);
        }

        .card h2{
            font-size:17px;
            font-weight:800;
            line-height:1.3;
            margin-bottom:6px;
            color:#163b67;
        }

        .card .desc{
            font-size:11px;
            line-height:1.55;
            color:#7089a5;
            margin-bottom:16px;
            max-width:310px;
        }

        .form-group{
            margin-bottom:12px;
        }

        .form-label{
            display:block;
            font-size:11px;
            font-weight:700;
            color:#234870;
            margin-bottom:7px;
        }

        .required{
            color:#ef5361;
        }

        .password-field{
            position:relative;
        }

        .password-field input{
            width:100%;
            height:40px;
            border:1.4px solid var(--border);
            border-radius:12px;
            outline:none;
            padding:0 40px 0 30px;
            font-size:11px;
            color:#294c73;
            background:#fbfdff;
            transition:.2s ease;
        }

        .password-field input:focus{
            border-color:#6ea3d8;
            box-shadow:0 0 0 4px rgba(11,94,194,.08);
            background:#fff;
        }

        .field-icon-left,
        .toggle-password{
            position:absolute;
            top:50%;
            transform:translateY(-50%);
            color:#a7b6c7;
            font-size:12px;
        }

        .field-icon-left{
            left:11px;
        }

        .toggle-password{
            right:12px;
            cursor:pointer;
            background:none;
            border:none;
            padding:0;
        }

        .toggle-password:hover{
            color:#0b5ec2;
        }

        .password-hint{
            margin-top:4px;
            font-size:9px;
            color:#9aadbf;
        }

        .submit-btn{
            width:100%;
            height:42px;
            margin-top:4px;
            border:none;
            border-radius:12px;
            background:#0a5cbc;
            color:#fff;
            font-size:11px;
            font-weight:700;
            cursor:pointer;
            box-shadow:0 8px 18px rgba(10,92,188,.18);
            transition:.2s ease;
        }

        .submit-btn:hover{
            background:#084e9f;
            transform:translateY(-1px);
        }

        .submit-btn:disabled{
            background:#7daede;
            cursor:not-allowed;
            transform:none;
            box-shadow:none;
        }

        .submit-btn i{
            margin-right:7px;
        }

        .footer-right{
            margin-top:16px;
            text-align:center;
            font-size:8px;
            color:#b2bfce;
            line-height:1.8;
        }

        .footer-right a{
            color:#0a63be;
            font-weight:600;
        }

        .help-btn{
            position:fixed;
            right:10px;
            bottom:10px;
            width:24px;
            height:24px;
            border-radius:50%;
            background:#24292f;
            color:#fff;
            display:flex;
            align-items:center;
            justify-content:center;
            font-size:13px;
            box-shadow:0 2px 7px rgba(0,0,0,.25);
        }

        @media(max-width:900px){
            .page{
                display:block;
            }

            .left-panel,
            .right-panel{
                width:100%;
                min-height:auto;
            }

            .left-panel{
                padding:28px 22px;
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
                padding:28px 14px 45px;
            }
        }

        @media(max-width:480px){
            .card{
                padding:18px 16px 20px;
            }

            .step-label{
                width:60px;
                font-size:7px;
            }

            .card h2{
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
                        <div class="diamond"></div>
                    </div>
                    <div class="brand-text">
                        <div class="gov">KEMENTERIAN PERDAGANGAN RI</div>
                        <div class="unit">Biro Perencanaan</div>
                    </div>
                </div>

                <div class="system-label">SISTEM INFORMASI</div>
                <div class="app-title">Penelitian RKA-K/L</div>
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

                <div class="mini-otp">
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

                    <i class="bi bi-chevron-right arrow"></i>

                    <div class="flow-item">
                        <i class="bi bi-key-fill"></i>
                        Verifikasi OTP
                    </div>

                    <i class="bi bi-chevron-right arrow"></i>

                    <div class="flow-item active">
                        <i class="bi bi-lock-fill"></i>
                        Buat Password Baru
                    </div>

                    <i class="bi bi-chevron-right arrow"></i>
                </div>

                <div class="flow-login">
                    <div class="flow-item">
                        <i class="bi bi-check-circle-fill"></i>
                        Login
                    </div>
                </div>

                <div class="copyright">
                    © 2025 Kementerian Perdagangan Republik Indonesia.
                    Hak Cipta Dilindungi.
                </div>
            </div>
        </div>
    </aside>

    <!-- RIGHT -->
    <main class="right-panel">
        <section class="card">

            <div class="badge">
                <i class="bi bi-shield-lock"></i>
                Pemulihan Akses Akun
            </div>

            <div class="stepper">
                <div class="step completed">
                    <div class="step-circle"><i class="bi bi-check-lg"></i></div>
                    <span class="step-label">Email</span>
                </div>
                <div class="step-line completed"></div>

                <div class="step completed">
                    <div class="step-circle"><i class="bi bi-check-lg"></i></div>
                    <span class="step-label">Verifikasi</span>
                </div>
                <div class="step-line completed"></div>

                <div class="step current">
                    <div class="step-circle"><i class="bi bi-lock-fill"></i></div>
                    <span class="step-label">Password Baru</span>
                </div>
                <div class="step-line"></div>

                <div class="step pending">
                    <div class="step-circle"><i class="bi bi-check-circle"></i></div>
                    <span class="step-label">Selesai</span>
                </div>
            </div>

            <h2>Buat Kata Sandi Baru</h2>
            <p class="desc">
                Buat kata sandi yang kuat dan tidak pernah digunakan sebelumnya
                untuk menjaga keamanan akun Anda.
            </p>

            <form id="newPasswordForm">
                <div class="form-group">
                    <label class="form-label">
                        Kata Sandi Baru <span class="required">*</span>
                    </label>
                    <div class="password-field">
                        <i class="bi bi-lock field-icon-left"></i>
                        <input type="password" id="password" placeholder="Masukkan kata sandi baru">
                        <button type="button" class="toggle-password" data-target="password">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        Konfirmasi Kata Sandi <span class="required">*</span>
                    </label>
                    <div class="password-field">
                        <i class="bi bi-lock field-icon-left"></i>
                        <input type="password" id="confirmPassword" placeholder="Ulangi kata sandi baru">
                        <button type="button" class="toggle-password" data-target="confirmPassword">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                    <div class="password-hint" id="passwordHint">
                        Gunakan minimal 8 karakter.
                    </div>
                </div>

                <button type="submit" class="submit-btn" id="submitBtn" disabled>
                    <i class="bi bi-key"></i>
                    Simpan Kata Sandi Baru
                </button>
            </form>

        </section>

        <div class="footer-right">
            <div>Butuh bantuan? <a href="#">Hubungi Administrator Sistem</a></div>
            <div>© 2025 Biro Perencanaan — Kementerian Perdagangan Republik Indonesia</div>
        </div>
    </main>

</div>

<a href="#" class="help-btn">?</a>

<script>
    const password = document.getElementById('password');
    const confirmPassword = document.getElementById('confirmPassword');
    const submitBtn = document.getElementById('submitBtn');
    const passwordHint = document.getElementById('passwordHint');

    function validateForm() {
        const pass = password.value.trim();
        const confirm = confirmPassword.value.trim();

        if (pass.length === 0 && confirm.length === 0) {
            passwordHint.textContent = 'Gunakan minimal 8 karakter.';
            passwordHint.style.color = '#9aadbf';
            submitBtn.disabled = true;
            return;
        }

        if (pass.length < 8) {
            passwordHint.textContent = 'Kata sandi minimal 8 karakter.';
            passwordHint.style.color = '#e67e22';
            submitBtn.disabled = true;
            return;
        }

        if (confirm.length > 0 && pass !== confirm) {
            passwordHint.textContent = 'Konfirmasi kata sandi belum sama.';
            passwordHint.style.color = '#e74c3c';
            submitBtn.disabled = true;
            return;
        }

        if (pass.length >= 8 && pass === confirm) {
            passwordHint.textContent = 'Kata sandi siap disimpan.';
            passwordHint.style.color = '#1fb55c';
            submitBtn.disabled = false;
            return;
        }

        passwordHint.textContent = 'Lengkapi kedua kolom kata sandi.';
        passwordHint.style.color = '#9aadbf';
        submitBtn.disabled = true;
    }

    [password, confirmPassword].forEach(input => {
        input.addEventListener('input', validateForm);
    });

    document.querySelectorAll('.toggle-password').forEach(button => {
        button.addEventListener('click', function () {
            const targetId = this.getAttribute('data-target');
            const targetInput = document.getElementById(targetId);
            const icon = this.querySelector('i');

            if (targetInput.type === 'password') {
                targetInput.type = 'text';
                icon.className = 'bi bi-eye-slash';
            } else {
                targetInput.type = 'password';
                icon.className = 'bi bi-eye';
            }
        });
    });

    document.getElementById('newPasswordForm').addEventListener('submit', function(e){
        e.preventDefault();
        if (!submitBtn.disabled) {
            alert('Frontend only: password baru berhasil disubmit.');
        }
    });
</script>

</body>
</html>