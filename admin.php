<?php
session_start();

// 1. Validasi Login Admin
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

require_once 'koneksi.php';

$error_message = '';
$success_message = '';

// 2. PROSES FORM ACTIONS (Ditangani langsung di halaman ini)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // A. TINDAKAN: TOGGLE STATUS RENT / RELEASE
    if (isset($_POST['aksi']) && $_POST['aksi'] === 'toggle_status') {
        $unit_id = (int)$_POST['unit_id'];
        $status_sekarang = $_POST['status_sekarang'];
        
        if ($status_sekarang === 'Tersedia') {
            // Ubah dari Tersedia -> Sedang Digunakan
            $durasi_sewa = isset($_POST['durasi_sewa']) ? (int)$_POST['durasi_sewa'] : 1;
            
            $sql_update = "UPDATE units SET 
                            status = 'Sedang Digunakan', 
                            waktu_sewa_mulai = NOW(), 
                            durasi_sewa = ? 
                           WHERE id = ?";
            $stmt = mysqli_prepare($conn, $sql_update);
            mysqli_stmt_bind_param($stmt, 'ii', $durasi_sewa, $unit_id);
            mysqli_stmt_execute($stmt);
        } else {
            // Ubah dari Sedang Digunakan -> Tersedia (Kosongkan waktu & durasi)
            $sql_update = "UPDATE units SET 
                            status = 'Tersedia', 
                            waktu_sewa_mulai = NULL, 
                            durasi_sewa = NULL 
                           WHERE id = ?";
            $stmt = mysqli_prepare($conn, $sql_update);
            mysqli_stmt_bind_param($stmt, 'i', $unit_id);
            mysqli_stmt_execute($stmt);
        }
        
        header('Location: admin.php');
        exit;
    }

    // B. TINDAKAN: MENDAFTARKAN UNIT BARU
    if (isset($_POST['aksi']) && $_POST['aksi'] === 'tambah_unit') {
        $nama_unit = trim($_POST['nama_unit']);
        $harga_per_jam = (int)$_POST['harga_per_jam'];
        $status_baru = $_POST['status_baru'];
        $tipe = 'PS5';

        if (!empty($nama_unit) && $harga_per_jam > 0) {
            if ($status_baru === 'Sedang Digunakan') {
                $sql_insert = "INSERT INTO units (nama_unit, tipe, harga_per_jam, status, waktu_sewa_mulai, durasi_sewa) VALUES (?, ?, ?, ?, NOW(), 1)";
                $stmt = mysqli_prepare($conn, $sql_insert);
                mysqli_stmt_bind_param($stmt, 'ssis', $nama_unit, $tipe, $harga_per_jam, $status_baru);
            } else {
                $sql_insert = "INSERT INTO units (nama_unit, tipe, harga_per_jam, status, waktu_sewa_mulai, durasi_sewa) VALUES (?, ?, ?, ?, NULL, NULL)";
                $stmt = mysqli_prepare($conn, $sql_insert);
                mysqli_stmt_bind_param($stmt, 'ssis', $nama_unit, $tipe, $harga_per_jam, $status_baru);
            }
            
            if (mysqli_stmt_execute($stmt)) {
                $success_message = "Stasiun unit baru berhasil didaftarkan!";
            } else {
                $error_message = "Gagal menyimpan unit ke database.";
            }
        } else {
            $error_message = "Data form tidak valid. Mohon periksa kembali.";
        }
    }
}

// 3. AMBIL DATA DARI DATABASE
function ambil_unit(mysqli $conn): array
{
    $sql = "SELECT id, nama_unit, tipe, harga_per_jam, status, waktu_sewa_mulai, durasi_sewa FROM units ORDER BY id ASC";
    $result = mysqli_query($conn, $sql);

    $daftar_unit = [];
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $daftar_unit[] = $row;
        }
    }
    return $daftar_unit;
}

$daftar_unit = ambil_unit($conn);
?>

<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Admin Dashboard - Ruang Gaming</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root {
            --neon-cyan: #00ffcc;
            --neon-magenta: #ff00ff;
        }

        .digital {
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
            letter-spacing: 0.1em;
        }

        /* Latar Belakang menggunakan cyberpunk-2077.jpg */
        .bg-cyberpunk-fixed {
            position: fixed;
            inset: 0;
            background-image: url('assets/cyberpunk-2077.jpg'); /* Sesuai dengan file yang diupload */
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            z-index: -2;
        }

        /* Overlay Gelap Khusus agar warna kuning background tidak merusak kontras tulisan */
        .bg-cyberpunk-fixed::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(to bottom, rgba(9, 9, 11, 0.88), rgba(20, 20, 25, 0.94));
            z-index: -1;
        }
    </style>
</head>
<body class="bg-zinc-950 text-zinc-50 min-h-screen selection:bg-[#ff00ff] selection:text-white antialiased flex flex-col justify-between">

    <div class="bg-cyberpunk-fixed"></div>

    <div class="w-full max-w-6xl mx-auto px-4 py-12 flex-grow">
        
        <header class="flex flex-col sm:flex-row items-center justify-between border-b border-zinc-800/80 pb-6 mb-8 gap-4">
            <div>
                <span class="text-xs text-[#00ffcc] tracking-[0.2em] font-bold uppercase digital block mb-1">
                    // CONTROL_PANEL_SECURE
                </span>
                <h1 class="text-3xl font-black tracking-wider uppercase digital text-transparent bg-clip-text bg-gradient-to-r from-[#00ffcc] to-[#ff00ff]">
                    ADMIN CONTROL
                </h1>
            </div>
            
            <a href="logout.php" class="inline-flex items-center justify-center rounded-xl border border-[#ff00ff] text-[#ff00ff] font-bold px-5 py-2.5 transition duration-300 hover:bg-[#ff00ff] hover:text-white shadow-[0_0_15px_rgba(255,0,255,0.2)] uppercase tracking-wider text-xs digital">
                <span>[ LOGOUT_SESSION ]</span>
            </a>
        </header>

        <?php if (!empty($success_message)): ?>
            <div class="mb-6 rounded-xl border border-[#00ffcc]/30 bg-[#00ffcc]/10 p-4 text-xs font-bold text-[#00ffcc] uppercase digital tracking-wide">
                &gt;&gt; SUCCESS: <?php echo htmlspecialchars($success_message); ?>
            </div>
        <?php endif; ?>
        <?php if (!empty($error_message)): ?>
            <div class="mb-6 rounded-xl border border-red-500/30 bg-red-950/40 p-4 text-xs font-bold text-red-400 uppercase digital tracking-wide">
                !! ERROR: <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>

        <main class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <section class="lg:col-span-2 space-y-4">
                <h2 class="text-sm font-bold text-zinc-400 uppercase tracking-widest digital mb-3">// LIVE_STATION_MONITOR</h2>
                
                <div class="overflow-x-auto rounded-2xl border border-zinc-800/80 bg-zinc-900/40 backdrop-blur-md shadow-[0_0_20px_rgba(0,0,0,0.5)]">
                    <table class="w-full text-left border-collapse text-sm digital">
                        <thead>
                            <tr class="border-b border-zinc-800 bg-zinc-950/50 text-xs text-zinc-400 uppercase tracking-wider">
                                <th class="p-4 font-bold">STATION</th>
                                <th class="p-4 font-bold">RATE / HR</th>
                                <th class="p-4 font-bold">STATUS</th>
                                <th class="p-4 font-bold">INFO WAKTU</th>
                                <th class="p-4 font-bold text-center">ACTION</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-800/60">
                            <?php foreach ($daftar_unit as $unit): 
                                $is_tersedia = ($unit['status'] ?? '') === 'Tersedia';
                                $status_color = $is_tersedia ? 'text-[#00ffcc]' : 'text-[#ff00ff]';
                            ?>
                                <tr class="hover:bg-zinc-900/40 transition">
                                    <td class="p-4">
                                        <div class="text-[10px] text-zinc-500 font-bold">[<?php echo htmlspecialchars($unit['tipe']); ?>]</div>
                                        <div class="font-extrabold text-zinc-200 text-base"><?php echo htmlspecialchars($unit['nama_unit']); ?></div>
                                    </td>
                                    <td class="p-4 text-zinc-300">Rp<?php echo number_format($unit['harga_per_jam'], 0, ',', '.'); ?></td>
                                    <td class="p-4 font-black <?php echo $status_color; ?> uppercase">
                                        [ <?php echo htmlspecialchars($unit['status']); ?> ]
                                    </td>
                                    <td class="p-4 text-xs text-zinc-400">
                                        <?php if (!$is_tersedia && !empty($unit['waktu_sewa_mulai'])): ?>
                                            Mulai: <span class="text-zinc-200"><?php echo date('H:i', strtotime($unit['waktu_sewa_mulai'])); ?></span><br>
                                            Durasi: <span class="text-[#ff00ff]"><?php echo $unit['durasi_sewa']; ?> Jam</span>
                                        <?php else: ?>
                                            <span class="text-zinc-600">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="p-4 text-center">
                                        <form method="POST" action="admin.php" class="flex items-center justify-center gap-2">
                                            <input type="hidden" name="aksi" value="toggle_status">
                                            <input type="hidden" name="unit_id" value="<?php echo $unit['id']; ?>">
                                            <input type="hidden" name="status_sekarang" value="<?php echo htmlspecialchars($unit['status']); ?>">
                                            
                                            <?php if ($is_tersedia): ?>
                                                <select name="durasi_sewa" class="bg-zinc-950 border border-zinc-700 text-white rounded px-2 py-1 text-xs outline-none cursor-pointer focus:border-[#00ffcc]">
                                                    <option value="1">1 Jam</option>
                                                    <option value="2">2 Jam</option>
                                                    <option value="3">3 Jam</option>
                                                    <option value="4">4 Jam</option>
                                                    <option value="5">5 Jam</option>
                                                </select>
                                                <button type="submit" class="px-3 py-1 text-xs rounded border border-[#00ffcc] text-[#00ffcc] bg-zinc-950/80 hover:bg-[#00ffcc] hover:text-black font-bold uppercase transition">
                                                    RENT
                                                </button>
                                            <?php else: ?>
                                                <button type="submit" class="px-3 py-1 text-xs rounded border border-[#ff00ff] text-[#ff00ff] bg-zinc-950/80 hover:bg-[#ff00ff] hover:text-white font-bold uppercase transition">
                                                    RELEASE
                                                </button>
                                            <?php endif; ?>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </section>

            <section class="space-y-4">
                <h2 class="text-sm font-bold text-zinc-400 uppercase tracking-widest digital mb-3">// INITIALIZE_STATION_FORM</h2>
                
                <div class="rounded-2xl border border-zinc-800/80 bg-zinc-900/40 p-6 backdrop-blur-md shadow-[0_0_20px_rgba(0,0,0,0.5)]">
                    <form method="POST" action="admin.php" class="space-y-5 text-xs">
                        <input type="hidden" name="aksi" value="tambah_unit">
                        <div>
                            <label class="block font-bold text-zinc-400 mb-2 uppercase tracking-wider digital" for="nama_unit">// STATION_NAME</label>
                            <input
                                id="nama_unit"
                                name="nama_unit"
                                type="text"
                                placeholder="E.g., Station 07"
                                class="w-full rounded-xl border border-zinc-800 bg-zinc-950/80 px-4 py-3 text-zinc-100 outline-none focus:border-[#00ffcc] transition digital"
                                required
                            />
                        </div>

                        <div>
                            <label class="block font-bold text-zinc-400 mb-2 uppercase tracking-wider digital" for="harga_per_jam">// RATE_PER_HOUR (RP)</label>
                            <input
                                id="harga_per_jam"
                                name="harga_per_jam"
                                type="number"
                                placeholder="10000"
                                class="w-full rounded-xl border border-zinc-800 bg-zinc-950/80 px-4 py-3 text-zinc-100 outline-none focus:border-[#00ffcc] transition digital"
                                required
                            />
                        </div>

                        <div>
                            <label class="block font-bold text-zinc-400 mb-2 uppercase tracking-wider digital" for="status_baru">// BASE_STATUS</label>
                            <select
                                id="status_baru"
                                name="status_baru"
                                class="w-full rounded-xl border border-zinc-800 bg-zinc-950/80 px-4 py-3 text-zinc-100 outline-none focus:border-[#00ffcc] transition digital cursor-pointer"
                                required
                            >
                                <option value="Tersedia">Tersedia</option>
                                <option value="Sedang Digunakan">Sedang Digunakan</option>
                            </select>
                        </div>

                        <button
                            type="submit"
                            class="w-full inline-flex items-center justify-center rounded-xl bg-[#00ffcc] text-black font-black px-4 py-3.5 transition-all duration-300 hover:bg-[#ff00ff] hover:text-white shadow-[0_0_15px_rgba(0,255,204,0.3)] uppercase tracking-widest text-xs digital"
                        >
                            + REGISTER_STATION
                        </button>
                    </form>
                </div>
            </section>
        </main>
    </div>

    <footer class="w-full max-w-6xl mx-auto px-4 py-6 border-t border-zinc-800/70 text-center text-xs text-zinc-600 digital">
        SYSTEM_PANEL // SECURITY_ENFORCED_LOGS
    </footer>

</body>
</html>