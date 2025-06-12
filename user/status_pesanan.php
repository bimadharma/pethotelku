<?php
// /user/status_pesanan.php
include '../layouts/header.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'user') {
    header("Location: ../auth/login.php");
    exit();
}
$user_id = $_SESSION['user_id'];
$stmt = $koneksi->prepare("SELECT p.*, l.nama_layanan, py.status_bayar FROM tb_pesanan p 
                           JOIN tb_layanan l ON p.id_layanan = l.id_layanan
                           LEFT JOIN tb_pembayaran py ON p.id_pesanan = py.id_pesanan
                           WHERE p.id_user = ? AND p.status != 'Selesai' ORDER BY p.id_pesanan DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<h2 class="mb-4 text-white">Status Pesanan Aktif</h2>

<!-- status berhasil upload bukti -->
<?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const modal = new bootstrap.Modal(document.getElementById('successModal'));
        modal.show();
    });
</script>

<!-- Modal success -->
<div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title" id="successModalLabel"><i class="bi bi-check-circle-fill me-2"></i>Berhasil!</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>
      <div class="modal-body">
        Bukti pembayaran berhasil diunggah. Kami akan segera memproses pesanan Anda.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" data-bs-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>
<?php endif; ?>



<?php if ($result->num_rows > 0): ?>
    <?php while ($row = $result->fetch_assoc()):
        $badge_class = '';
        switch ($row['status']) {
            case 'Menunggu':
                $badge_class = 'bg-warning text-dark';
                break;
            case 'Diproses':
                $badge_class = 'bg-info text-dark';
                break;
        }
    ?>
        <div class="card shadow-sm mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <strong>Pesanan #<?= $row['id_pesanan'] ?></strong>
                <span class="badge <?= $badge_class ?>"><?= htmlspecialchars($row['status']) ?></span>
            </div>
            <div class="card-body">
                <h5 class="card-title mb-3"><?= htmlspecialchars($row['nama_layanan']) ?></h5>

                <div class="row mb-2">
                    <div class="col-md-6">
                        <p class="mb-1"><strong><i class="bi bi-wallet2"></i> Total Harga:</strong><br>Rp <?= number_format($row['total_harga']) ?></p>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-1"><strong><i class="bi bi-calendar-check"></i> Jadwal:</strong><br><?= date('d M Y', strtotime($row['tgl_masuk'])) ?> s/d <?= date('d M Y', strtotime($row['tgl_keluar'])) ?></p>
                    </div>
                </div>

                <div class="mb-3">
                    <p class="mb-1"><strong><i class="bi bi-paw"></i> Hewan:</strong><br><?= htmlspecialchars($row['nama_hewan']) ?> (<?= htmlspecialchars($row['jenis_hewan']) ?>)</p>
                </div>

                <?php if ($row['status'] == 'Menunggu' && $row['status_bayar'] != 'Belum_Dikonfirmasi'): ?>
                    <form action="upload_bukti.php" method="POST" enctype="multipart/form-data" class="mb-3">
                        <input type="hidden" name="id_pesanan" value="<?= $row['id_pesanan'] ?>">
                        <div class="mb-2">
                            <label for="bukti_bayar" class="form-label"><strong>Upload Bukti Pembayaran</strong></label>
                            <input type="file" name="bukti_bayar" id="bukti_bayar" class="form-control" accept="image/*" required>
                            <small class="form-text text-muted">Hanya gambar (JPG, JPEG, PNG) maksimal 2MB.</small>
                        </div>
                        <button type="submit" class="btn btn-success"><i class="bi bi-upload"></i> Upload</button>
                    </form>
                    <div class="alert alert-warning p-2">
                        <small>
                            <strong>Instruksi Pembayaran:</strong><br>
                            Silakan transfer ke <strong>Bank Indonesia</strong><br>
                            No. Rekening: <strong>12345</strong><br>
                            Setelah itu, unggah bukti pembayaran agar pesanan diproses.
                        </small>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    <?php endwhile; ?>
<?php else: ?>
    <div class="alert alert-info">Anda tidak memiliki pesanan yang sedang aktif. <a href="form_pemesanan.php">Buat pesanan baru?</a></div>
<?php endif; ?>

<script>
document.getElementById('bukti_bayar').addEventListener('change', function(event) {
    const file = event.target.files[0];
    if (file) {
        const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
        const maxSize = 2 * 1024 * 1024; // 2MB

        if (!allowedTypes.includes(file.type)) {
            alert('Hanya file gambar (JPG/PNG) yang diperbolehkan.');
            event.target.value = ''; // reset input
        } else if (file.size > maxSize) {
            alert('Ukuran file maksimal 2MB.');
            event.target.value = ''; // reset input
        }
    }
});
</script>

<?php include '../layouts/footer.php'; ?>