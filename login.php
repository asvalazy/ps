<?php
// login.php - Halaman Login Admin Ruang Gaming (Cyberpunk Theme)
session_start();

$login_error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = (string)($_POST['password'] ?? '');

    if ($username === 'admin' && $password === 'password123') {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_username'] = 'admin';
        header('Location: admin.php');
        exit;
    }

    $login_error = 'Username atau password salah.';
}
?>

<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Login Admin - Ruang Gaming</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root {
            --neon-cyan: #00ffcc;
            --neon-magenta: #ff00ff;
        }

        .digital {
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
            letter-spacing: 0.12em;
        }

        /* Fixed Background menggunakan ps background.jpg */
        .bg-neon-fixed {
            position: fixed;
            inset: 0;
            background-image: url('ps background.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            z-index: -2;
        }

        /* Overlay gelap agar form dan teks neon kontras */
        .bg-neon-fixed::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(to bottom, rgba(9, 9, 11, 0.8), rgba(9, 9, 11, 0.9));
            z-index: -1;
        }
    </style>
</head>
<body class="bg-zinc-950 text-zinc-50 min-h-screen flex items-center justify-center p-4 selection:bg-[#ff00ff] selection:text-white antialiased">

    <div class="bg-neon-fixed"></div>

    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-black tracking-wider text-transparent bg-clip-text bg-gradient-to-r from-[#00ffcc] to-[#ff00ff] uppercase digital mb-2">
                [ AUTH_REQUIRED ]
            </h1>
            <p class="text-xs text-zinc-400 tracking-widest uppercase digital">
                RUANG GAMING // CORE_ADMIN_GATEWAY
            </p>
        </div>

        <main class="rounded-2xl border border-zinc-800/80 bg-zinc-900/40 p-8 backdrop-blur-md shadow-[0_0_25px_rgba(0,0,0,0.6)]">
            
            <?php if (!empty($login_error)): ?>
                <div class="mb-6 rounded-xl border border-red-500/30 bg-red-950/40 p-4 text-xs font-bold text-red-400 uppercase tracking-wide digital">
                    !! ERROR: <?php echo htmlspecialchars($login_error); ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="login.php" class="space-y-6">
                <div>
                    <label class="block text-xs font-bold text-zinc-400 mb-2 uppercase tracking-wider digital" for=\"username\">// USERNAME</label>
                    <input
                        id="username"
                        name="username"
                        type="text"
                        autocomplete="username"
                        class="w-full rounded-xl border border-zinc-800 bg-zinc-950/80 px-4 py-3 text-zinc-100 outline-none focus:border-[#00ffcc] focus:shadow-[0_0_15px_rgba(0,255,204,0.25)] transition digital text-sm"
                        required
                    />
                </div>

                <div>
                    <label class="block text-xs font-bold text-zinc-400 mb-2 uppercase tracking-wider digital" for=\"password\">// PASSWORD</label>
                    <input
                        id="password"
                        name="password"
                        type="password"
                        autocomplete="current-password"
                        class="w-full rounded-xl border border-zinc-800 bg-zinc-950/80 px-4 py-3 text-zinc-100 outline-none focus:border-[#00ffcc] focus:shadow-[0_0_15px_rgba(0,255,204,0.25)] transition digital text-sm"
                        required
                    />
                </div>

                <button
                    type="submit"
                    class="w-full inline-flex items-center justify-center rounded-xl bg-[#00ffcc] text-black font-black px-6 py-4 transition-all duration-300 hover:bg-[#ff00ff] hover:text-white shadow-[0_0_20px_rgba(0,255,204,0.35)] hover:shadow-[0_0_20px_rgba(255,0,255,0.4)] uppercase tracking-widest text-sm digital"
                >
                    <span>[ INTENT_LOGIN ]</span>
                </button>
            </form>

            <div class="mt-6 border-t border-zinc-800/60 pt-4 text-[11px] text-zinc-500 digital uppercase tracking-wider text-center">
                Kredensial Debug: <span class="text-zinc-400">admin / password123</span>
            </div>
        </main>
    </div>

</body>
</html>