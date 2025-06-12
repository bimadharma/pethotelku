<?php
include '../layouts/header.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'user') {
    header("Location: ../auth/login.php");
    exit();
}

// Proses form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_user = $_SESSION['user_id'];
    $id_layanan = $_POST['id_layanan'];
    $nama_hewan = $_POST['nama_hewan'];
    $jenis_hewan = strtolower(trim($_POST['jenis_hewan']));
    $tgl_masuk = $_POST['tgl_masuk'];
    $tgl_keluar = $_POST['tgl_keluar'];
    $fasilitas_tambahan = isset($_POST['fasilitas']) ? $_POST['fasilitas'] : [];
    $status = 'Menunggu';

    // Validasi jenis hewan
    if (!in_array($jenis_hewan, ['kucing', 'anjing'])) {
        $_SESSION['flash_message'] = ['type' => 'danger', 'text' => 'Jenis hewan hanya boleh kucing atau anjing.'];
    } elseif (strtotime($tgl_keluar) < strtotime($tgl_masuk)) {
        $_SESSION['flash_message'] = ['type' => 'danger', 'text' => 'Tanggal keluar tidak boleh sebelum tanggal masuk.'];
    } else {
        // Hitung total hari
        $lama_titip = (strtotime($tgl_keluar) - strtotime($tgl_masuk)) / (60 * 60 * 24);
        $lama_titip = max(1, $lama_titip); // minimal 1 hari

        // Ambil harga layanan
        $query_layanan = mysqli_query($koneksi, "SELECT harga FROM tb_layanan WHERE id_layanan = $id_layanan");
        $data_layanan = mysqli_fetch_assoc($query_layanan);
        $harga_layanan = $data_layanan['harga'];

        $total_harga = $harga_layanan * $lama_titip;

        // Tambah harga fasilitas tambahan
        if (!empty($fasilitas_tambahan)) {
            $id_fasilitas_implode = implode(',', array_map('intval', $fasilitas_tambahan));
            $query_fasilitas = mysqli_query($koneksi, "SELECT harga_tambahan FROM tb_fasilitas WHERE id_fasilitas IN ($id_fasilitas_implode)");
            while ($fasilitas = mysqli_fetch_assoc($query_fasilitas)) {
                $total_harga += $fasilitas['harga_tambahan'];
            }
        }

        // Insert ke tb_pesanan
        $stmt = $koneksi->prepare("INSERT INTO tb_pesanan (id_user, id_layanan, nama_hewan, jenis_hewan, tgl_masuk, tgl_keluar, total_harga, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iissssds", $id_user, $id_layanan, $nama_hewan, $jenis_hewan, $tgl_masuk, $tgl_keluar, $total_harga, $status);

        if ($stmt->execute()) {
            $id_pesanan_baru = $stmt->insert_id;

            // Insert fasilitas tambahan jika ada
            if (!empty($fasilitas_tambahan)) {
                $stmt_fasilitas = $koneksi->prepare("INSERT INTO tb_pesanan_fasilitas (id_pesanan, id_fasilitas) VALUES (?, ?)");
                foreach ($fasilitas_tambahan as $id_fasilitas) {
                    $stmt_fasilitas->bind_param("ii", $id_pesanan_baru, $id_fasilitas);
                    $stmt_fasilitas->execute();
                }
                $stmt_fasilitas->close();
            }

            $_SESSION['flash_message'] = ['type' => 'success', 'text' => 'Pesanan berhasil dibuat! Menunggu konfirmasi dari admin.'];
            header("Location: status_pesanan.php");
            exit();
        } else {
            $_SESSION['flash_message'] = ['type' => 'danger', 'text' => 'Gagal membuat pesanan. Silakan coba lagi.'];
        }
        $stmt->close();
    }
}
?>

<h2 class="mb-4 text-white">Formulir Pemesanan Layanan</h2>

<?php
if (isset($_SESSION['flash_message'])) {
    $message = $_SESSION['flash_message'];
    unset($_SESSION['flash_message']);
    echo "<div class='alert alert-{$message['type']}'>{$message['text']}</div>";
}
?>

<div class="card shadow-sm">
    <div class="card-body">
        <form action="form_pemesanan.php" method="POST" id="formPesanan">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="id_layanan" class="form-label">Pilih Layanan</label>
                    <select class="form-select" id="id_layanan" name="id_layanan" required>
                        <option value="">-- Pilih Paket Layanan --</option>
                        <?php
                        $layanan_result = mysqli_query($koneksi, "SELECT * FROM tb_layanan");
                        while ($layanan = mysqli_fetch_assoc($layanan_result)): ?>
                            <option value="<?= $layanan['id_layanan'] ?>" data-harga="<?= $layanan['harga'] ?>">
                                <?= htmlspecialchars($layanan['nama_layanan']) ?> (Rp <?= number_format($layanan['harga']) ?>/hari)
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
            </div>

            <h5 class="mt-4">Detail Hewan</h5>
            <hr>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="nama_hewan" class="form-label">Nama Hewan</label>
                    <input type="text" class="form-control" id="nama_hewan" name="nama_hewan" placeholder="nama hewan kamu" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="jenis_hewan" class="form-label">Jenis Hewan</label>
                    <select class="form-select" id="jenis_hewan" name="jenis_hewan" required>
                        <option value="">-- Pilih Jenis Hewan --</option>
                        <option value="kucing">Kucing</option>
                        <option value="anjing">Anjing</option>
                    </select>
                </div>
            </div>

            <h5 class="mt-4">Jadwal Penitipan</h5>
            <hr>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="tgl_masuk" class="form-label">Tanggal Masuk</label>
                    <input type="date" class="form-control" id="tgl_masuk" name="tgl_masuk" required min="<?= date('Y-m-d') ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="tgl_keluar" class="form-label">Tanggal Keluar</label>
                    <input type="date" class="form-control" id="tgl_keluar" name="tgl_keluar" required min="<?= date('Y-m-d') ?>">
                </div>
            </div>

            <h5 class="mt-4">Fasilitas Tambahan</h5>
            <hr>
            <div class="row">
                <?php
                $fasilitas_result = mysqli_query($koneksi, "SELECT * FROM tb_fasilitas");
                if (mysqli_num_rows($fasilitas_result) > 0):
                    while ($fasilitas = mysqli_fetch_assoc($fasilitas_result)):
                ?>
                        <div class="col-md-4">
                            <div class="form-check">
                                <input class="form-check-input fasilitas-check" type="checkbox" name="fasilitas[]" value="<?= $fasilitas['id_fasilitas'] ?>" data-harga="<?= $fasilitas['harga_tambahan'] ?>" id="fasilitas-<?= $fasilitas['id_fasilitas'] ?>">
                                <label class="form-check-label" for="fasilitas-<?= $fasilitas['id_fasilitas'] ?>">
                                    <?= htmlspecialchars($fasilitas['nama_fasilitas']) ?> (+Rp <?= number_format($fasilitas['harga_tambahan']) ?>)
                                    <small class="d-block text-muted"><?= htmlspecialchars($fasilitas['keterangan']) ?></small>
                                </label>
                            </div>
                        </div>
                    <?php endwhile;
                else: ?>
                    <p>Tidak ada fasilitas tambahan yang tersedia.</p>
                <?php endif; ?>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-4">
                <button type="submit" class="btn btn-primary">Kirim Pesanan</button>
                <h5>Total: <span id="totalHarga">Rp 0</span></h5>
            </div>
        </form>
    </div>
</div>


<script>
    function hitungTotalHarga() {
        const layananSelect = document.getElementById('id_layanan');
        const tglMasuk = document.getElementById('tgl_masuk').value;
        const tglKeluar = document.getElementById('tgl_keluar').value;

        const layananHarga = layananSelect.options[layananSelect.selectedIndex]?.dataset.harga || 0;

        let total = 0;

        if (tglMasuk && tglKeluar) {
            const start = new Date(tglMasuk);
            const end = new Date(tglKeluar);
            const diff = Math.ceil((end - start) / (1000 * 60 * 60 * 24));
            const hari = Math.max(1, diff);

            total += (layananHarga * hari);
        }

        const fasilitasChecks = document.querySelectorAll('.fasilitas-check:checked');
        fasilitasChecks.forEach(check => {
            total += parseInt(check.dataset.harga || 0);
        });

        document.getElementById('totalHarga').innerText = 'Rp ' + total.toLocaleString('id-ID');
    }

    document.getElementById('id_layanan').addEventListener('change', hitungTotalHarga);
    document.getElementById('tgl_masuk').addEventListener('change', hitungTotalHarga);
    document.getElementById('tgl_keluar').addEventListener('change', hitungTotalHarga);
    document.querySelectorAll('.fasilitas-check').forEach(item => {
        item.addEventListener('change', hitungTotalHarga);
    });
</script>


<?php include '../layouts/footer.php'; ?>