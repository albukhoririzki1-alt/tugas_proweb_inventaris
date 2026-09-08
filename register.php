<?php

/**
 * register.php — Halaman Registrasi Pengguna Baru
 * Desain konsisten dengan login.php (dark theme, same fonts & colors)
 */
session_start();

// Redirect jika sudah login
if (!empty($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar — Inventaris Sekolah</title>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:ital,wght@0,300;0,400;0,500;1,300&display=swap" rel="stylesheet">
    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        :root {
            --bg: #0d0f1a;
            --surface: #13162a;
            --surface2: #1a1f38;
            --border: rgba(79, 138, 255, 0.12);
            --accent: #4f8aff;
            --accent2: #a78bfa;
            --text: #e8eaf6;
            --text-muted: #8b92b3;
            --danger: #f87171;
            --success: #34d399;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow-x: hidden;
            position: relative;
            padding: 24px;
        }

        /* Background orbs */
        body::before {
            content: '';
            position: fixed;
            top: -200px;
            left: -200px;
            width: 600px;
            height: 600px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(79, 138, 255, 0.15) 0%, transparent 70%);
            pointer-events: none;
        }

        body::after {
            content: '';
            position: fixed;
            bottom: -200px;
            right: -200px;
            width: 600px;
            height: 600px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(167, 139, 250, 0.12) 0%, transparent 70%);
            pointer-events: none;
        }

        .register-wrapper {
            width: 100%;
            max-width: 480px;
            z-index: 1;
            animation: fadeUp 0.5s ease;
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(24px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .register-logo {
            text-align: center;
            margin-bottom: 36px;
        }

        .register-logo .icon {
            font-size: 52px;
            display: block;
            margin-bottom: 12px;
            filter: drop-shadow(0 0 20px rgba(79, 138, 255, 0.4));
        }

        .register-logo h1 {
            font-family: 'Syne', sans-serif;
            font-size: 26px;
            font-weight: 800;
            background: linear-gradient(135deg, #4f8aff, #a78bfa);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            letter-spacing: -0.5px;
        }

        .register-logo p {
            font-size: 13px;
            color: var(--text-muted);
            margin-top: 4px;
        }

        .register-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 36px;
            box-shadow: 0 24px 80px rgba(0, 0, 0, 0.4), inset 0 1px 0 rgba(255, 255, 255, 0.04);
            backdrop-filter: blur(20px);
        }

        .form-group {
            margin-bottom: 18px;
        }

        label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--text-muted);
            margin-bottom: 8px;
        }

        .input-wrap {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 16px;
            pointer-events: none;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            background: var(--surface2);
            border: 1.5px solid var(--border);
            border-radius: 10px;
            padding: 12px 14px 12px 42px;
            font-family: 'DM Sans', sans-serif;
            font-size: 14px;
            color: var(--text);
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        input:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(79, 138, 255, 0.12);
        }

        input::placeholder {
            color: var(--text-muted);
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px;
        }

        .error-box {
            display: flex;
            align-items: center;
            gap: 8px;
            background: rgba(248, 113, 113, 0.1);
            border: 1px solid rgba(248, 113, 113, 0.25);
            border-radius: 10px;
            padding: 12px 14px;
            font-size: 13px;
            color: var(--danger);
            margin-bottom: 18px;
            animation: shake 0.4s ease;
        }

        .success-box {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            background: rgba(52, 211, 153, 0.1);
            border: 1px solid rgba(52, 211, 153, 0.25);
            border-radius: 10px;
            padding: 16px;
            font-size: 13px;
            color: var(--success);
            margin-bottom: 18px;
            animation: fadeUp 0.4s ease;
            line-height: 1.5;
        }

        @keyframes shake {
            0%,
            100% {
                transform: translateX(0);
            }

            25% {
                transform: translateX(-6px);
            }

            75% {
                transform: translateX(6px);
            }
        }

        .btn-register {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #4f8aff, #7c5cfc);
            border: none;
            border-radius: 10px;
            color: #fff;
            font-family: 'Syne', sans-serif;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            transition: transform 0.15s, box-shadow 0.15s, opacity 0.15s;
            letter-spacing: 0.3px;
            box-shadow: 0 6px 20px rgba(79, 138, 255, 0.35);
        }

        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 28px rgba(79, 138, 255, 0.45);
        }

        .btn-register:active {
            transform: translateY(0);
            opacity: 0.9;
        }

        .btn-register:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .login-link {
            text-align: center;
            margin-top: 20px;
            font-size: 13px;
            color: var(--text-muted);
        }

        .login-link a {
            color: var(--accent);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.2s;
        }

        .login-link a:hover {
            color: var(--accent2);
        }

        /* Loading spinner */
        .spinner {
            display: inline-block;
            width: 16px;
            height: 16px;
            border: 2px solid rgba(255,255,255,0.3);
            border-top-color: #fff;
            border-radius: 50%;
            animation: spin 0.6s linear infinite;
            vertical-align: middle;
            margin-right: 8px;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        @media (max-width: 520px) {
            .form-row {
                grid-template-columns: 1fr;
            }

            .register-card {
                padding: 24px;
            }
        }
    </style>
</head>

<body>

    <div class="register-wrapper">
        <!-- Logo -->
        <div class="register-logo">
            <span class="icon">📦</span>
            <h1>INVENTARIS</h1>
            <p>Buat Akun Baru — Sistem Inventaris Sekolah</p>
        </div>

        <!-- Card -->
        <div class="register-card">

            <!-- Messages -->
            <div id="error-msg" style="display:none"></div>
            <div id="success-msg" style="display:none"></div>

            <!-- Form -->
            <form id="register-form" autocomplete="off">
                <div class="form-group">
                    <label for="reg-nama">Nama Lengkap</label>
                    <div class="input-wrap">
                        <span class="input-icon">👤</span>
                        <input type="text" id="reg-nama" name="nama"
                            placeholder="Masukkan nama lengkap"
                            required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="reg-username">Username</label>
                        <div class="input-wrap">
                            <span class="input-icon">🆔</span>
                            <input type="text" id="reg-username" name="username"
                                placeholder="Pilih username"
                                required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reg-email">Email</label>
                        <div class="input-wrap">
                            <span class="input-icon">📧</span>
                            <input type="email" id="reg-email" name="email"
                                placeholder="contoh@email.com"
                                required>
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="reg-password">Password</label>
                        <div class="input-wrap">
                            <span class="input-icon">🔒</span>
                            <input type="password" id="reg-password" name="password"
                                placeholder="Minimal 6 karakter"
                                required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reg-confirm">Konfirmasi Password</label>
                        <div class="input-wrap">
                            <span class="input-icon">🔒</span>
                            <input type="password" id="reg-confirm" name="confirm_password"
                                placeholder="Ulangi password"
                                required>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn-register" id="btn-submit">Daftar Akun →</button>
            </form>

            <!-- Link ke login -->
            <div class="login-link">
                Sudah punya akun? <a href="login.php">Masuk</a>
            </div>
        </div>
    </div>

    <script>
        const form = document.getElementById('register-form');
        const btnSubmit = document.getElementById('btn-submit');
        const errorEl = document.getElementById('error-msg');
        const successEl = document.getElementById('success-msg');

        function showError(msg) {
            errorEl.innerHTML = `<div class="error-box">❌ ${msg}</div>`;
            errorEl.style.display = 'block';
            successEl.style.display = 'none';
        }

        function showSuccess(msg) {
            successEl.innerHTML = `<div class="success-box">✅ <div>${msg}</div></div>`;
            successEl.style.display = 'block';
            errorEl.style.display = 'none';
        }

        form.addEventListener('submit', async function (e) {
            e.preventDefault();
            errorEl.style.display = 'none';
            successEl.style.display = 'none';

            const nama     = document.getElementById('reg-nama').value.trim();
            const username = document.getElementById('reg-username').value.trim();
            const email    = document.getElementById('reg-email').value.trim();
            const password = document.getElementById('reg-password').value;
            const confirm  = document.getElementById('reg-confirm').value;

            // Client-side validation
            if (!nama || !username || !email || !password || !confirm) {
                showError('Semua kolom wajib diisi');
                return;
            }

            if (password.length < 6) {
                showError('Password minimal 6 karakter');
                return;
            }

            if (password !== confirm) {
                showError('Password dan Konfirmasi Password tidak cocok');
                return;
            }

            // Submit
            btnSubmit.disabled = true;
            btnSubmit.innerHTML = '<span class="spinner"></span>Mendaftar...';

            try {
                const res = await fetch('api/register.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ nama, username, email, password, confirm_password: confirm })
                });

                const data = await res.json();

                if (!res.ok) {
                    showError(data.error || 'Terjadi kesalahan saat mendaftar');
                    btnSubmit.disabled = false;
                    btnSubmit.textContent = 'Daftar Akun →';
                    return;
                }

                showSuccess(data.message);
                form.reset();
                btnSubmit.disabled = true;
                btnSubmit.textContent = '✅ Pendaftaran Terkirim';

                // Redirect ke login setelah 3 detik
                setTimeout(() => {
                    window.location.href = 'login.php';
                }, 4000);

            } catch (err) {
                showError('Gagal terhubung ke server. Coba lagi nanti.');
                btnSubmit.disabled = false;
                btnSubmit.textContent = 'Daftar Akun →';
            }
        });
    </script>

</body>

</html>
