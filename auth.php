<?php
// ============================================================
//  auth.php — Login & Register StomaCare (Dengan Animasi Slide)
// ============================================================
require_once __DIR__ . '/config/session.php';
require_once __DIR__ . '/config/app.php';

if (isLoggedIn()) {
    header('Location: ' . url('index.php'));
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login / Register – StomaCare</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'stoma-dark':  '#1a3c30',
                        'stoma-green': '#458b68',
                        'stoma-light': '#a8d5be',
                        'stoma-pale':  '#e8f5ee',
                    }
                }
            }
        }
    </script>
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #e8f5ee 0%, #c8e6d4 100%);
            padding: 20px;
        }

        /* ── WRAPPER UTAMA ── */
        .auth-wrapper {
            position: relative;
            width: 100%;
            max-width: 820px;
            min-height: 500px;
            background: #fff;
            border-radius: 28px;
            box-shadow: 0 20px 60px rgba(26,60,48,.15);
            overflow: hidden;
        }

        /* ── PANEL FORM (kiri awal) ── */
        .form-side {
            position: absolute;
            top: 0;
            left: 0;
            width: 50%;
            height: 100%;
            background: #fff;
            transition: transform 0.6s cubic-bezier(0.77, 0, 0.18, 1);
            z-index: 1;
        }

        /* ── PANEL HIJAU (kanan awal) ── */
        .green-side {
            position: absolute;
            top: 0;
            left: 50%;
            width: 50%;
            height: 100%;
            background: #458b68;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            transition: transform 0.6s cubic-bezier(0.77, 0, 0.18, 1);
            z-index: 2; /* Di atas panel form saat bergeser */
        }

        /* ── STATE: Register Mode (Animasi Slide) ── */
        .auth-wrapper.reg-mode .form-side {
            transform: translateX(100%);
        }
        .auth-wrapper.reg-mode .green-side {
            transform: translateX(-100%);
        }

        /* ── KONTROL OPACITY FORM (Fade in/out) ── */
        .form-login,
        .form-register {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            padding: 48px 44px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            transition: opacity 0.4s ease, visibility 0.4s ease;
        }

        .form-login { opacity: 1; visibility: visible; }
        .form-register { opacity: 0; visibility: hidden; }

        .auth-wrapper.reg-mode .form-login { opacity: 0; visibility: hidden; }
        .auth-wrapper.reg-mode .form-register { opacity: 1; visibility: visible; }

        /* ── KONTROL OPACITY TEKS PANEL HIJAU ── */
        #panelLogin, #panelRegister {
            position: absolute;
            width: 100%;
            padding: 0 36px;
            transition: opacity 0.4s ease, visibility 0.4s ease;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        #panelLogin { opacity: 1; visibility: visible; }
        #panelRegister { opacity: 0; visibility: hidden; }

        .auth-wrapper.reg-mode #panelLogin { opacity: 0; visibility: hidden; }
        .auth-wrapper.reg-mode #panelRegister { opacity: 1; visibility: visible; }

        /* ── INPUT STYLE ── */
        .inp-auth {
            width: 100%;
            background: #f4f8f6;
            border: 1.5px solid #e0ede7;
            border-radius: 12px;
            padding: 12px 44px 12px 16px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 14px;
            color: #1a3c30;
            outline: none;
            transition: border .2s, box-shadow .2s;
        }
        .inp-auth:focus {
            border-color: #458b68;
            box-shadow: 0 0 0 3px rgba(69,139,104,.12);
        }
        .inp-wrap {
            position: relative;
            margin-bottom: 14px;
        }
        .inp-wrap i {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            font-size: 15px;
            cursor: pointer;
        }

        /* ── TOMBOL ── */
        .btn-auth {
            width: 100%;
            background: #458b68;
            color: #fff;
            border: none;
            border-radius: 12px;
            padding: 13px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            transition: background .2s, transform .15s;
            margin-top: 6px;
        }
        .btn-auth:hover  { background: #1a3c30; transform: translateY(-1px); }
        .btn-auth:active { transform: translateY(0); }

        .btn-outline {
            background: transparent;
            border: 2px solid #fff;
            color: #fff;
            border-radius: 50px;
            padding: 10px 32px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            transition: background .2s;
            margin-top: 16px;
        }
        .btn-outline:hover { background: rgba(255,255,255,.15); }

        /* ── LABEL ── */
        .form-title {
            font-size: 28px;
            font-weight: 800;
            color: #1a3c30;
            margin-bottom: 4px;
        }
        .form-sub {
            font-size: 13px;
            color: #9ca3af;
            margin-bottom: 28px;
        }

        /* ── ALERT PHP ── */
        .php-alert {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 999;
            padding: 12px 20px;
            border-radius: 12px;
            font-size: 13px;
            font-weight: 600;
            box-shadow: 0 4px 20px rgba(0,0,0,.1);
            transition: opacity .5s;
            white-space: nowrap;
        }
        .php-alert.err  { background:#fee2e2; color:#991b1b; border:1px solid #fecaca; }
        .php-alert.ok   { background:#dcfce7; color:#166534; border:1px solid #bbf7d0; }

        /* ── RESPONSIVE ── */
        @media (max-width: 600px) {
            .auth-wrapper { display: flex; flex-direction: column; min-height: unset; }
            .form-side    { position: relative; width: 100%; height: auto; transform: none !important; }
            .green-side   { position: relative; left: 0; width: 100%; height: auto; transform: none !important; min-height: 240px; }

            .auth-wrapper.reg-mode .form-side  { order: 2; }
            .auth-wrapper.reg-mode .green-side { order: 1; }

            .form-login, .form-register { position: relative; padding: 36px 28px; height: auto; }
            .form-register { display: none; }
            .auth-wrapper.reg-mode .form-login { display: none; }
            .auth-wrapper.reg-mode .form-register { display: flex; opacity: 1; visibility: visible; }

            #panelLogin, #panelRegister { position: relative; padding: 28px 24px; }
            #panelRegister { display: none; }
            .auth-wrapper.reg-mode #panelLogin { display: none; }
            .auth-wrapper.reg-mode #panelRegister { display: flex; opacity: 1; visibility: visible; }
        }
    </style>
</head>
<body>

<?php if (!empty($_GET['error'])): ?>
<div class="php-alert err" id="phpAlert">
    <i class="fas fa-exclamation-circle" style="margin-right:6px;"></i>
    <?= htmlspecialchars($_GET['error']) ?>
</div>
<?php elseif (!empty($_GET['success'])): ?>
<div class="php-alert ok" id="phpAlert">
    <i class="fas fa-check-circle" style="margin-right:6px;"></i>
    <?= htmlspecialchars($_GET['success']) ?>
</div>
<?php endif; ?>

<div class="auth-wrapper" id="authWrapper">

    <div class="form-side">

        <div class="form-login">
            <h1 class="form-title">Login</h1>
            <p class="form-sub">Masuk ke akun StomaCare kamu</p>

            <form action="<?= url('api/login.php') ?>" method="POST">
                <div class="inp-wrap">
                    <input class="inp-auth" type="text" name="username" placeholder="Username" required>
                    <i class="fas fa-user"></i>
                </div>
                <div class="inp-wrap">
                    <input class="inp-auth" type="password" name="password" id="loginPass" placeholder="Password" required>
                    <i class="fas fa-eye" onclick="togglePass('loginPass', this)"></i>
                </div>
               <div style="text-align:right;margin:-6px 0 14px;">
                   <a href="<?= url('forgot-password.php') ?>" style="font-size:12px;color:#9ca3af;text-decoration:none;transition:color .2s;" onmouseover="this.style.color='#458b68'" onmouseout="this.style.color='#9ca3af'">Lupa Password?</a>
               </div>
                <button type="submit" class="btn-auth">
                    <i class="fas fa-sign-in-alt" style="margin-right:8px;"></i>Login
                </button>
            </form>
        </div>

        <div class="form-register">
            <h1 class="form-title">Daftar</h1>
            <p class="form-sub">Buat akun StomaCare gratis</p>

            <form action="<?= url('api/register.php') ?>" method="POST">
                <div class="inp-wrap">
                    <input class="inp-auth" type="text" name="username" placeholder="Username" required>
                    <i class="fas fa-user"></i>
                </div>
                <div class="inp-wrap">
                    <input class="inp-auth" type="email" name="email" placeholder="E-mail" required>
                    <i class="fas fa-envelope"></i>
                </div>
                <div class="inp-wrap">
                    <input class="inp-auth" type="password" name="password" id="regPass" placeholder="Password (min. 6 karakter)" required>
                    <i class="fas fa-eye" onclick="togglePass('regPass', this)"></i>
                </div>
                <button type="submit" class="btn-auth">
                    <i class="fas fa-user-plus" style="margin-right:8px;"></i>Daftar Sekarang
                </button>
            </form>
        </div>

    </div><div class="green-side">

        <div id="panelLogin">
            <div style="font-size:48px;margin-bottom:12px;">👋</div>
            <h2 style="color:#fff;font-size:22px;font-weight:800;margin-bottom:8px;">Halo, Selamat Datang!</h2>
            <p style="color:rgba(255,255,255,.75);font-size:13px;line-height:1.6;margin-bottom:4px;">
                Belum punya akun?<br>Daftar sekarang dan mulai pantau kesehatan lambungmu.
            </p>
            <button class="btn-outline" id="toRegisterBtn">Daftar Sekarang</button>
        </div>

        <div id="panelRegister">
            <div style="font-size:48px;margin-bottom:12px;">✅</div>
            <h2 style="color:#fff;font-size:22px;font-weight:800;margin-bottom:8px;">Sudah Punya Akun?</h2>
            <p style="color:rgba(255,255,255,.75);font-size:13px;line-height:1.6;margin-bottom:4px;">
                Masuk dan lanjutkan memantau<br>kesehatan lambungmu.
            </p>
            <button class="btn-outline" id="toLoginBtn">Login Sekarang</button>
        </div>

    </div></div><script>
const wrapper      = document.getElementById('authWrapper');
const toRegBtn     = document.getElementById('toRegisterBtn');
const toLoginBtn   = document.getElementById('toLoginBtn');

function goRegister() {
    wrapper.classList.add('reg-mode');
}

function goLogin() {
    wrapper.classList.remove('reg-mode');
}

toRegBtn.addEventListener('click', goRegister);
toLoginBtn.addEventListener('click', goLogin);

// Toggle show/hide password
function togglePass(id, icon) {
    const inp = document.getElementById(id);
    if (!inp) return;
    inp.type = inp.type === 'password' ? 'text' : 'password';
    icon.classList.toggle('fa-eye');
    icon.classList.toggle('fa-eye-slash');
}

// Auto-hide alert PHP
const phpAlert = document.getElementById('phpAlert');
if (phpAlert) setTimeout(() => phpAlert.style.opacity = '0', 3500);

// Langsung buka mode register jika URL param ?mode=register
if (new URLSearchParams(location.search).get('mode') === 'register') {
    goRegister();
}
</script>

</body>
</html>