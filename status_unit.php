<?php
// status_unit.php - Status Unit PS5 (berdasarkan data MySQL)

require_once 'koneksi.php';

// Ambil data unit dari MySQL (Sudah termasuk kolom waktu sewa)
$sql = "SELECT id, nama_unit, tipe, harga_per_jam, status, waktu_sewa_mulai, durasi_sewa FROM units ORDER BY id ASC";
$result = mysqli_query($conn, $sql);

$daftar_unit = [];
while ($row = mysqli_fetch_assoc($result)) {
    $daftar_unit[] = $row;
}

$jumlah_tersedia = count(array_filter(
    $daftar_unit,
    fn ($u) => ($u['status'] ?? '') === 'Tersedia'
));

function format_rupiah($angka): string
{
    return 'Rp' . number_format((float)$angka, 0, ',', '.');
}
?>

<!doctype html>
<html lang=\"id\">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Ruang Gaming - Cek Ketersediaan PS5</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="description" content="Cek ketersediaan unit PS5 di Ruang Gaming Jogja secara real-time." />

    <style>
        :root {
            --neon-cyan: #00ffcc;
            --neon-magenta: #ff00ff;
        }

        .digital {
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
            letter-spacing: 0.14em;
        }

        /* Global fixed background */
        .bg-neon-fixed {
            position: fixed;
            inset: 0;
            background-image: url('assets/neon.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            z-index: -2;
        }

        .bg-neon-fixed::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(to bottom, rgba(9,9,11,0.85), rgba(9,9,11,0.95));
            z-index: -1;
        }

        /* Efek Cyberpunk Glitch untuk Judul */
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
<body class="bg-zinc-950 text-zinc-50 min-h-screen selection:bg-[#ff00ff] selection:text-white antialiased">

    <div class="bg-neon-fixed"></div>

    <div class="relative max-w-6xl mx-auto px-4 py-8 sm:py-12 flex flex-col min-h-screen">
        
        <header class="flex flex-col sm:flex-row items-center justify-between gap-4 border-b border-zinc-800/80 pb-6 mb-8">
            <div class="text-center sm:text-left">
                <h1 class="text-3xl sm:text-4xl font-black tracking-wider text-transparent bg-clip-text bg-gradient-to-r from-[#00ffcc] to-[#ff00ff] uppercase digital cyber-glitch" data-text="RUANG GAMING">
                    RUANG GAMING
                </h1>
                <p class="text-xs text-zinc-400 mt-1 tracking-widest uppercase digital">
                    Sleman Sector V.2 // Live Station Status
                </p>
            </div>
            
            <div class="flex items-center gap-3 bg-zinc-900/80 border border-zinc-800 rounded-2xl px-5 py-3 backdrop-blur-md">
                <span class="relative flex h-3 w-3">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#00ffcc] opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-[#00ffcc]"></span>
                </span>
                <p class="text-sm font-bold uppercase tracking-wider digital">
                    <span class="text-[#00ffcc]"><?php echo $jumlah_tersedia; ?></span> Units Available
                </p>
            </div>
        </header>

        <main class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
            <?php foreach ($daftar_unit as $unit): ?>
                <?php
                $is_tersedia = ($unit['status'] ?? '') === 'Tersedia';
                
                // 1. Setup Pendaran Border Bercahaya Cyberpunk
                $card_glow_class = $is_tersedia 
                    ? 'border-[#00ffcc]/30 shadow-[0_0_15px_rgba(0,255,204,0.15)]' 
                    : 'border-[#ff00ff]/20 shadow-[0_0_15px_rgba(255,0,255,0.1)]';

                $badge_border = $is_tersedia ? 'border-[#00ffcc]/30' : 'border-[#ff00ff]/30';
                $badge_bg     = $is_tersedia ? 'bg-[#00ffcc]/10' : 'bg-[#ff00ff]/10';
                $badge_text   = $is_tersedia ? 'text-[#00ffcc]' : 'text-[#ff00ff]';
                $dot_bg       = $is_tersedia ? 'bg-[#00ffcc]' : 'bg-[#ff00ff]';
                $status_label = $is_tersedia ? 'READY_TO_BOOT' : 'STATION_OCCUPIED';
                ?>
                
                <article class="relative rounded-2xl border bg-zinc-900/40 p-5 backdrop-blur-md transition-all duration-300 hover:scale-[1.02] <?php echo $card_glow_class; ?>">
                    <div class="flex items-start justify-between gap-4 border-b border-zinc-800/60 pb-4">
                        <div>
                            <span class="text-[10px] uppercase tracking-widest text-zinc-500 font-bold digital">
                                [ <?php echo htmlspecialchars($unit['tipe'] ?? 'PS5'); ?> ]
                            </span>
                            <h2 class="text-xl font-black text-zinc-100 tracking-wide uppercase mt-0.5">
                                <?php echo htmlspecialchars($unit['nama_unit'] ?? 'STATION'); ?>
                            </h2>
                        </div>
                        <div class="inline-flex items-center gap-2 rounded-full px-3 py-1 border <?php echo $badge_border; ?> <?php echo $badge_bg; ?>">
                            <span class="w-2.5 h-2.5 rounded-full <?php echo $dot_bg; ?>"></span>
                            <span class="digital font-semibold text-xs <?php echo $badge_text; ?>">
                                <?php echo $status_label; ?>
                            </span>
                        </div>
                    </div>

                    <div class="mt-4">
                        <p class="text-xs text-zinc-400 digital uppercase tracking-wider">Rate / Hour</p>
                        <p class="text-2xl font-extrabold text-zinc-100 tracking-wide mt-0.5 digital">
                            <?php echo format_rupiah($unit['harga_per_jam'] ?? 0); ?>
                        </p>
                    </div>

                    <div class="mt-5">
                        <?php if ($is_tersedia): ?>
                            <button 
                                onclick="openBookingModal('<?php echo htmlspecialchars($unit['nama_unit']); ?>')"
                                class="w-full inline-flex items-center justify-center rounded-xl bg-[#00ffcc] text-black font-black px-4 py-3 transition-all duration-300 hover:bg-[#ff00ff] hover:text-white shadow-[0_0_15px_rgba(0,255,204,0.4)] hover:shadow-[0_0_15px_rgba(255,0,255,0.4)] uppercase tracking-widest text-sm digital"
                            >
                                <span>[ BOOK_STATION ]</span>
                            </button>
                        <?php else: ?>
                            <div class="w-full inline-flex items-center justify-center rounded-xl bg-[#ff00ff] text-white font-black px-4 py-3 shadow-[0_0_15px_rgba(255,0,255,0.3)] uppercase tracking-widest text-sm digital animate-pulse cursor-not-allowed select-none">
                                <span>[ PLEASE_WAIT ]</span>
                            </div>

                            <?php if (!empty($unit['waktu_sewa_mulai']) && !empty($unit['durasi_sewa'])): ?>
                                <p class="text-xs text-center font-bold tracking-wider text-[#ff00ff] mt-2.5 digital countdown-timer" 
                                   data-start="<?php echo date('Y-m-d H:i:s', strtotime($unit['waktu_sewa_mulai'])); ?>" 
                                   data-duration="<?php echo $unit['durasi_sewa']; ?>">
                                    INITIALIZING_CLOCK...
                                </p>
                            <?php endif; ?>
                        <?php endif; ?>

                        <p class="text-[10px] text-zinc-500 mt-2.5 text-center tracking-wider uppercase digital">(DIRECT_COMMUNICATION_LINE)</p>
                    </div>
                </article>
            <?php endforeach; ?>
        </main>

        <footer class="relative mt-auto border-t border-zinc-800/70 pt-6 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-zinc-500 digital">
            <div>RUANG GAMING © 2026 // ALL RIGHTS RESERVED.</div>
            <div class="flex gap-4">
                <a href="login.php" class="hover:text-[#00ffcc] transition">[ ADMIN_PORTAL ]</a>
            </div>
        </footer>
    </div>

    <div id="bookingModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/85 backdrop-blur-sm p-4">
        <div class="w-full max-w-md rounded-2xl border border-[#00ffcc]/40 bg-zinc-950 p-6 shadow-[0_0_30px_rgba(0,255,204,0.25)]">
            <div class="flex justify-between items-center border-b border-zinc-800 pb-3 mb-4">
                <h3 class="text-md font-black tracking-wider text-[#00ffcc] uppercase digital">[ INITIALIZE_BOOKING ]</h3>
                <button onclick="closeBookingModal()" class="text-zinc-500 hover:text-white font-bold text-xs digital transition">[ X ]</button>
            </div>
            
            <form id="bookingForm" onsubmit="processBooking(event)">
                <input type="hidden" id="modalUnitName">
                
                <div class="mb-4">
                    <label class="block text-[10px] uppercase text-zinc-400 font-bold mb-1.5 digital tracking-widest">Selected Station</label>
                    <input type="text" id="modalUnitDisplay" class="w-full bg-zinc-900 border border-zinc-800 rounded-xl px-4 py-2.5 text-[#00ffcc] font-bold tracking-wider digital select-none" readonly>
                </div>

                <div class="mb-4">
                    <label for="userName" class="block text-[10px] uppercase text-zinc-400 font-bold mb-1.5 digital tracking-widest">Operator Name (Gamer ID)</label>
                    <input type="text" id="userName" required placeholder="Masukkan nama kamu" class="w-full bg-zinc-900 border border-zinc-700 focus:border-[#00ffcc] outline-none rounded-xl px-4 py-2.5 text-white tracking-wide transition-all placeholder:text-zinc-600">
                </div>

                <div class="mb-5">
                    <label for="playDuration" class="block text-[10px] uppercase text-zinc-400 font-bold mb-1.5 digital tracking-widest">Duration (Hours)</label>
                    <select id="playDuration" class="w-full bg-zinc-900 border border-zinc-700 focus:border-[#00ffcc] outline-none rounded-xl px-4 py-2.5 text-white tracking-wide transition-all cursor-pointer">
                        <option value="1">1 Jam</option>
                        <option value="2">2 Jam</option>
                        <option value="3">3 Jam</option>
                        <option value="4">4 Jam</option>
                        <option value="5">5 Jam</option>
                    </select>
                </div>

                <button type="submit" class="w-full rounded-xl bg-[#00ffcc] text-black font-black py-3.5 uppercase tracking-widest text-xs digital hover:bg-[#ff00ff] hover:text-white transition-all duration-300 shadow-[0_0_15px_rgba(0,255,204,0.3)]">
                    LAUNCH_TO_WHATSAPP_
                </button>
            </form>
        </div>
    </div>

    <script>
        // 1. ENGINE COUNTDOWN TIMER
        function pad2(number) {
            return (number < 10 ? '0' : '') + number;
        }

        function formatCountdown(totalSeconds) {
            const seconds = Math.max(0, Math.floor(totalSeconds));
            const h = Math.floor(seconds / 3600);
            const m = Math.floor((seconds % 3600) / 60);
            const s = seconds % 60;
            return `AVAILABLE IN: [ ${pad2(h)}h ${pad2(m)}m ${pad2(s)}s ]`;
        }

        function updateAllTimers() {
            const timers = document.querySelectorAll('.countdown-timer');
            timers.forEach(function (el) {
                const startStr = el.getAttribute('data-start');
                const durationHours = parseInt(el.getAttribute('data-duration') || '0', 10);
                
                if (!startStr || !durationHours || Number.isNaN(durationHours)) return;

                // Konversi format datetime MySQL agar kompatibel silang dengan browser safari/chrome
                const startMs = new Date(startStr.replace(' ', 'T')).getTime();
                const endMs = startMs + (durationHours * 3600 * 1000);
                const nowMs = Date.now();

                const remainingSeconds = (endMs - nowMs) / 1000;

                if (remainingSeconds <= 0) {
                    el.textContent = '[ SESSION_OVER_PENDING_RELEASE ]';
                    el.classList.add('animate-pulse');
                } else {
                    el.textContent = formatCountdown(remainingSeconds);
                }
            });
        }

        // Jalankan timer saat halaman dimuat & setiap 1 detik bergulir
        document.addEventListener('DOMContentLoaded', updateAllTimers);
        setInterval(updateAllTimers, 1000);


        // 2. ENGINE MODAL BOOKING FORM
        function openBookingModal(unitName) {
            document.getElementById('modalUnitName').value = unitName;
            document.getElementById('modalUnitDisplay').value = unitName.toUpperCase();
            document.getElementById('bookingModal').classList.remove('hidden');
        }

        function closeBookingModal() {
            document.getElementById('bookingModal').classList.add('hidden');
            document.getElementById('bookingForm').reset();
        }

        function processBooking(event) {
            event.preventDefault();
            
            const unitName = document.getElementById('modalUnitName').value;
            const name = document.getElementById('userName').value;
            const duration = document.getElementById('playDuration').value;
            
            // Masukkan Nomor HP Admin Toko Kamu di Sini (Ganti 628xxxxxxxx)
            const adminPhone = "6281234567890"; 
            
            // Format String Pesan Sesuai Aturan Proyek
            const textMessage = `Halo Admin, saya ${name} ingin booking ${unitName} selama ${duration} jam.`;
            
            const encodedMessage = encodeURIComponent(textMessage);
            const waUrl = `https://api.whatsapp.com/send?phone=${adminPhone}&text=${encodedMessage}`;
            
            // Arahkan ke tab WhatsApp baru
            window.open(waUrl, '_blank');
            
            closeBookingModal();
        }
    </script>
</body>
</html>