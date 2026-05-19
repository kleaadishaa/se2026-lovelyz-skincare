<?php 
require_once 'includes/config_session.inc.php';
require_once 'includes/signup_view.inc.php';
require_once 'includes/login_view.inc.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Login — Lovelyz Skincare</title>

    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

        :root {
            --cream: #F1EBE7;
            --bark:  #3d2b1f;
            --dark:  #1a1a1a;
            --gold:  #c9b99a;
            --muted: #888;
            --bg:    #faf8f6;
        }

        body {
            font-family: Georgia, 'Times New Roman', serif;
            background: var(--bg);
            min-height: 100vh;
            display: flex;
            overflow: hidden;
        }

        .left-panel {
            position: relative;
            width: 52%;
            min-height: 100vh;
            flex-shrink: 0;
            overflow: hidden;
        }
        .left-panel::before {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(160deg, rgba(26,26,26,0.55) 0%, rgba(61,43,31,0.35) 50%, rgba(26,26,26,0.70) 100%);
            z-index: 1;
        }
        .left-panel::after {
            content: "";
            position: absolute;
            inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)' opacity='0.04'/%3E%3C/svg%3E");
            z-index: 2;
            pointer-events: none;
        }
        .panel-bg {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            transform: scale(1.04);
            animation: slowZoom 18s ease-in-out infinite alternate;
        }
        @keyframes slowZoom {
            from { transform: scale(1.04); }
            to   { transform: scale(1.10); }
        }
        .panel-brand {
            position: absolute;
            top: 3rem;
            left: 3.5rem;
            z-index: 10;
            animation: fadeUp 0.9s ease both;
        }
        .panel-brand a {
            color: var(--cream);
            text-decoration: none;
            font-size: 1.4rem;
            font-weight: 400;
            letter-spacing: 2px;
        }
        .panel-line {
            position: absolute;
            top: 3rem; right: 0;
            width: 1px;
            height: calc(100% - 6rem);
            background: linear-gradient(to bottom, transparent, rgba(241,235,231,0.25) 30%, rgba(241,235,231,0.25) 70%, transparent);
            z-index: 10;
        }
        .panel-quote {
            position: absolute;
            bottom: 3.5rem;
            left: 3.5rem;
            right: 3.5rem;
            z-index: 10;
            animation: fadeUp 1s 0.3s ease both;
        }
        .panel-quote blockquote {
            color: var(--cream);
            font-size: 2rem;
            font-weight: 300;
            line-height: 1.3;
        }
        .panel-quote cite {
            display: block;
            margin-top: 1rem;
            font-size: 0.75rem;
            letter-spacing: 4px;
            text-transform: uppercase;
            color: var(--gold);
            font-style: normal;
        }
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .right-panel {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 2rem;
            position: relative;
            overflow-y: auto;
        }

        .form-wrap {
            width: 100%;
            max-width: 400px;
            animation: fadeUp 0.9s 0.15s ease both;
        }

        .tab-switcher {
            display: flex;
            margin-bottom: 2.8rem;
            border-bottom: 1px solid #e0d8d0;
        }
        .tab-btn {
            flex: 1;
            background: none;
            border: none;
            padding: 0.9rem 0;
            font-family: Georgia, serif;
            font-size: 0.75rem;
            letter-spacing: 3px;
            text-transform: uppercase;
            color: var(--muted);
            cursor: pointer;
            position: relative;
            transition: color 0.3s;
        }
        .tab-btn::after {
            content: "";
            position: absolute;
            bottom: -1px; left: 0;
            width: 100%; height: 2px;
            background: var(--bark);
            transform: scaleX(0);
            transition: transform 0.35s ease;
        }
        .tab-btn.active { color: var(--bark); }
        .tab-btn.active::after { transform: scaleX(1); }

        .form-panel { display: none; }
        .form-panel.active { display: block; }

        .form-heading { margin-bottom: 2.2rem; }
        .form-heading h1 {
            font-size: 2.4rem;
            font-weight: 300;
            color: var(--dark);
            line-height: 1.1;
        }
        .form-heading p {
            margin-top: 0.6rem;
            font-size: 0.9rem;
            color: var(--muted);
        }

        .field {
            margin-bottom: 1.5rem;
            position: relative;
        }
        .field label {
            display: block;
            font-size: 0.7rem;
            letter-spacing: 3px;
            text-transform: uppercase;
            color: var(--muted);
            margin-bottom: 0.5rem;
        }
        .field input {
            width: 100%;
            border: none;
            border-bottom: 1px solid #d0c8be;
            background: transparent;
            padding: 0.7rem 2.2rem 0.7rem 0;
            font-family: Georgia, serif;
            font-size: 1rem;
            color: var(--dark);
            outline: none;
            transition: border-color 0.3s;
        }
        .field input:focus { border-bottom-color: var(--bark); }
        .field input::placeholder { color: #ccc; font-style: italic; }
        .field .field-icon {
            position: absolute;
            right: 0; bottom: 0.75rem;
            color: #ccc;
            font-size: 0.85rem;
            pointer-events: none;
        }
        .field::after {
            content: "";
            position: absolute;
            bottom: 0; left: 0;
            width: 0; height: 1px;
            background: var(--bark);
            transition: width 0.4s ease;
        }
        .field:focus-within::after { width: 100%; }

        .submit-btn {
            display: block;
            width: 100%;
            background-color: transparent;
            border: 2px solid var(--dark);
            color: var(--dark);
            cursor: pointer;
            font-family: Georgia, serif;
            font-weight: 700;
            letter-spacing: 0.05em;
            outline: none;
            overflow: hidden;
            padding: 1.25em 2em;
            position: relative;
            text-align: center;
            transition: all 0.3s ease-in-out;
            font-size: 13px;
            margin-top: 1.8rem;
        }
        .submit-btn::before {
            content: " ";
            width: 1.5625rem; height: 2px;
            background: var(--dark);
            top: 50%; left: 1.5em;
            position: absolute;
            transform: translateY(-50%);
            transition: background 0.3s linear, width 0.3s linear;
        }
        .submit-btn .btn-text {
            font-size: 1.125em;
            line-height: 1.33333em;
            padding-left: 2em;
            display: block;
            text-align: left;
            transition: all 0.3s ease-in-out;
            text-transform: uppercase;
        }
        .submit-btn .top-key {
            height: 2px; width: 1.5625rem;
            top: -2px; left: 0.625rem;
            position: absolute; background: var(--bg);
            transition: width 0.5s ease-out, left 0.3s ease-out;
        }
        .submit-btn .bottom-key-1 {
            height: 2px; width: 1.5625rem;
            right: 1.875rem; bottom: -2px;
            position: absolute; background: var(--bg);
            transition: width 0.5s ease-out, right 0.3s ease-out;
        }
        .submit-btn .bottom-key-2 {
            height: 2px; width: 0.625rem;
            right: 0.625rem; bottom: -2px;
            position: absolute; background: var(--bg);
            transition: width 0.5s ease-out, right 0.3s ease-out;
        }
        .submit-btn:hover { color: #fff; background: var(--dark); }
        .submit-btn:hover::before { width: 0.9375rem; background: #fff; }
        .submit-btn:hover .btn-text { color: #fff; padding-left: 1.5em; }
        .submit-btn:hover .top-key { left: -2px; width: 0; }
        .submit-btn:hover .bottom-key-1,
        .submit-btn:hover .bottom-key-2 { right: 0; width: 0; }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            margin-top: 2rem;
            font-size: 0.75rem;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: var(--muted);
            text-decoration: none;
            transition: color 0.3s, gap 0.3s;
        }
        .back-link:hover { color: var(--bark); gap: 0.8rem; }
        .back-link i { font-size: 0.7rem; }

        @media screen and (max-width: 767px) {
            body { flex-direction: column; overflow: auto; }
            .left-panel { width: 100%; min-height: 38vh; flex-shrink: 0; }
            .panel-quote blockquote { font-size: 1.4rem; }
            .panel-line { display: none; }
            .right-panel { padding: 3rem 2rem 4rem; overflow-y: visible; }
            .right-panel::before, .right-panel::after { display: none; }
            .form-heading h1 { font-size: 1.9rem; }
        }
        @media screen and (min-width: 768px) and (max-width: 1024px) {
            .left-panel { width: 45%; }
            .right-panel { padding: 3rem 2.5rem; }
        }
    </style>
</head>
<body>

<div class="left-panel">
    <img class="panel-bg" src="assets/images/pexels-lascot-studio-841927-1759409.jpg" alt="">
    <div class="panel-brand">
        <a href="index.html">Lovelyz Skincare</a>
    </div>
    <div class="panel-line"></div>
    <div class="panel-quote">
        <blockquote>
            Healthy skin is<br>
            not a luxury —<br>
            it is self-respect.
        </blockquote>
        <cite>The Lovelyz Philosophy</cite>
    </div>
</div>

<div class="right-panel">
    <div class="form-wrap">

        <div class="tab-switcher">
            <button class="tab-btn active" onclick="switchTab('login', this)">Sign In</button>
            <button class="tab-btn" onclick="switchTab('register', this)">Register</button>
        </div>

        <div class="form-panel active" id="panel-login">
            <div class="form-heading">
                <h1>Welcome<br>back.</h1>
                <p>Log in to your Lovelyz account.</p>
            </div>
            <form action="includes/login.inc.php" method="POST">
                <div class="field">
                    <label for="login-email">Email</label>
                    <input type="email" id="login-email" name="email" placeholder="you@example.com" required>
                </div>
                <div class="field">
                    <label for="login-pass">Password</label>
                    <input type="password" id="login-pass" name="pwd" placeholder="••••••••" autocomplete="current-password" required>
                    <i class="fa-solid fa-lock field-icon"></i>
                </div>
                <button type="submit" class="submit-btn">
                    <span class="top-key"></span>
                    <span class="btn-text">Sign In</span>
                    <span class="bottom-key-1"></span>
                    <span class="bottom-key-2"></span>
                </button>
            </form>
        </div>

        <div class="form-panel" id="panel-register">
            <div class="form-heading">
                <h1>Create your<br>account.</h1>
                <p>Join Lovelyz and start your skin journey.</p>
            </div>
            <form action="includes/signup.inc.php" method="POST">
                <div class="field">
                    <label for="reg-name">Username</label>
                    <input type="text" id="reg-name" name="username" placeholder="username" autocomplete="name" required>
                    <i class="fa-regular fa-user field-icon"></i>
                </div>
                <div class="field">
                    <label for="reg-email">Email Address</label>
                    <input type="email" id="reg-email" name="email" placeholder="you@example.com" autocomplete="email" required>
                    <i class="fa-regular fa-envelope field-icon"></i>
                </div>
                <div class="field">
                    <label for="reg-pass">Password</label>
                    <input type="password" id="reg-pass" name="pwd" placeholder="Min. 8 characters" autocomplete="new-password" required>
                    <i class="fa-solid fa-lock field-icon"></i>
                </div>
                <div class="field">
                    <label for="reg-confirm">Confirm Password</label>
                    <input type="password" id="reg-confirm" name="confirm_password" placeholder="Repeat password" autocomplete="new-password" required>
                    <i class="fa-solid fa-lock field-icon"></i>
                </div>
                <button type="submit" class="submit-btn">
                    <span class="top-key"></span>
                    <span class="btn-text">Create Account</span>
                    <span class="bottom-key-1"></span>
                    <span class="bottom-key-2"></span>
                </button>
            </form>
        </div>

    </div>
</div>

<script>
    function switchTab(name, btn) {
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        document.querySelectorAll('.form-panel').forEach(p => p.classList.remove('active'));
        btn.classList.add('active');
        document.getElementById('panel-' + name).classList.add('active');
    }

    document.querySelector('#panel-login form').addEventListener('submit', async function(e) {
        e.preventDefault();

        const email = document.getElementById('login-email').value.trim();
        const password = document.getElementById('login-pass').value.trim();

        try {
            const res = await fetch('includes/auth/login.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ email, password })
            });

            const data = await res.json();

            if (data.success) {
                localStorage.setItem('token', data.token);
                window.location.href = data.role === 'admin' ? 'admin.html' : 'index.html';
            } else {
                alert(data.message || 'Login failed.');
            }
        } catch (err) {
            alert('Something went wrong. Please try again.');
        }
    });
</script>
</body>
</html>