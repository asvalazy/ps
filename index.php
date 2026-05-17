<?php
// index.php - Landing Page (Cyberpunk Futuristik) dengan Background PS
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Ruang Gaming - Ultimate Gaming Hub</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="description" content="Sleman Sector V.2 - The Ultimate Gaming Hub. Cek ketersediaan PS5 secara real-time." />

    <style>
        :root {
            --neon-cyan: #00ffcc;
            --neon-magenta: #ff00ff;
        }

        .digital {
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
            letter-spacing: 0.14em;
        }

        /* Fixed Background menggunakan ps background.jpg */
        .bg-neon-fixed {
            position: fixed;
            inset: 0;
            background-image: url('assets/ps background.jpg'); /* Menggunakan gambar kolase karakter game */
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            z-index: -2;
        }

        /* Overlay gelap agar elemen UI dan teks neon tetap menyala & kontras */
        .bg-neon-fixed::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(to bottom, rgba(9, 9, 11, 0.75), rgba(9, 9, 11, 0.85));
            z-index: -1;
        }

        /* Efek Cyberpunk Glitch untuk Judul (Sama persis dengan status_unit.php) */
        .cyber-glitch {
            position: relative;
            animation: glitch-anim 4s infinite linear alternate-reverse;
        }
        .cyber-glitch::before, .cyber-glitch::after {
            content: attr(data-text);
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            background: transparent;
        }
        .cyber-glitch::before {
            text-shadow: -2px 0 var(--neon-cyan);
            clip: rect(44px, 450px, 56px, 0);
            animation: glitch-anim-2 5s infinite linear alternate-reverse;
        }
        .cyber-glitch::after {
            text-shadow: 2px 0 var(--neon-magenta);
            clip: rect(12px, 450px, 85px, 0);
            animation: glitch-anim-3 3s infinite linear alternate-reverse;
        }

        @keyframes glitch-anim {
            0% { transform: skew(0deg); }
            2% { transform: skew(3deg); }
            4% { transform: skew(-2deg); }
            100% { transform: skew(0deg); }
        }
        @keyframes glitch-anim-2 {
            0% { clip: rect(10px, 9999px, 40px, 0); }
            10% { clip: rect(85px, 9999px, 5px, 0); }
            20% { clip: rect(30px, 9999px, 105px, 0); }
            100% { clip: rect(12px, 9999px, 85px, 0); }
        }
        @keyframes glitch-anim-3 {
            0% { clip: rect(70px, 9999px, 105px, 0); }
            15% { clip: rect(12px, 9999px, 45px, 0); }
            30% { clip: rect(90px, 9999px, 130px, 0); }
            100% { clip: rect(40px, 9999px, 20px, 0); }
        }
    </style>
</head>
<body class="bg-zinc-950 text-zinc-50 min-h-screen selection:bg-[#ff00ff] selection:text-white antialiased flex flex-col justify-between">

    <div class="bg-neon-fixed"></div>

    <div class="relative max-w-4xl mx-auto px-4 py-16 flex flex-col items-center justify-center flex-grow text-center">
        
        <header class="mb-10">
            <span class="text-xs text-[#00ffcc] tracking-[0.3em] font-bold uppercase digital block mb-3 animate-pulse">
                // SYSTEM_READY_TO_CONNECT
            </span>
            
            <h1 class="text-5xl sm:text-6xl font-black tracking-wider text-transparent bg-clip-text bg-gradient-to-r from-[#00ffcc] to-[#ff00ff] uppercase digital cyber-glitch mb-2" data-text="RUANG GAMING">
                RUANG GAMING
            </h1>
            
            <p class="text-xs sm:text-sm text-zinc-400 tracking-widest uppercase digital">
                Sleman Sector V.2 // Ultimate Gaming Hub
            </p>
        </header>

        <main class="w-full max-w-md rounded-2xl border border-zinc-800/80 bg-zinc-900/40 p-8 backdrop-blur-md shadow-[0_0_25px_rgba(0,0,0,0.6)]">
            <p class="text-xs text-zinc-300 mb-8 leading-relaxed digital uppercase text-center tracking-wide">
                Selamat datang di portal interface utama. Silakan inisialisasi stasiun pelacakan untuk memantau unit persewaan secara real-time.
            </p>

            <div class="flex flex-col gap-4">
                <a
                    href="status_unit.php"
                    class="w-full inline-flex items-center justify-center rounded-xl bg-[#00ffcc] text-black font-black px-6 py-4 transition-all duration-300 hover:bg-[#ff00ff] hover:text-white shadow-[0_0_20px_rgba(0,255,204,0.35)] hover:shadow-[0_0_20px_rgba(255,0,255,0.4)] uppercase tracking-widest text-sm digital"
                >
                    <span>[ INITIALIZE_STATION ]</span>
                </a>

                <div class="text-[10px] text-zinc-500 my-1 uppercase tracking-widest digital">
                    OR
                </div>

                <a
                    href="login.php"
                    class="w-full inline-flex items-center justify-center rounded-xl border border-zinc-800/90 bg-zinc-950/60 px-6 py-3.5 text-zinc-400 font-bold transition-all duration-300 hover:border-[#ff00ff]/50 hover:text-white uppercase tracking-widest text-xs digital"
                >
                    <span>[ ADMIN_PORTAL ]</span>
                </a>
            </div>

            <div class="mt-6 text-[11px] text-zinc-500 digital uppercase tracking-wider">
                Tip: Status durasi unit diperbarui secara otomatis menggunakan jam real-time.
            </div>
        </main>

    </div>

    <footer class="w-full max-w-6xl mx-auto px-4 py-6 border-t border-zinc-800/70 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-zinc-500 digital relative">
        <div>RUANG GAMING © 2026 // ALL RIGHTS RESERVED.</div>
        <div class="flex gap-4">
            <span class="text-zinc-600">SECTOR_GATE: 127.0.0.1</span>
        </div>
    </footer>

</body>
</html>