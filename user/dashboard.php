<?php 
// /user/dashboard.php
include '../layouts/header.php'; 
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'user') {
    header("Location: ../auth/login.php"); exit();
}

$user_id = $_SESSION['user_id'];
// Ambil data statistik untuk user
$stmt_aktif = $koneksi->prepare("SELECT COUNT(id_pesanan) AS total FROM tb_pesanan WHERE id_user = ? AND status != 'Selesai'");
$stmt_aktif->bind_param("i", $user_id);
$stmt_aktif->execute();
$pesanan_aktif = $stmt_aktif->get_result()->fetch_assoc()['total'];

$stmt_selesai = $koneksi->prepare("SELECT COUNT(id_pesanan) AS total FROM tb_pesanan WHERE id_user = ? AND status = 'Selesai'");
$stmt_selesai->bind_param("i", $user_id);
$stmt_selesai->execute();
$pesanan_selesai = $stmt_selesai->get_result()->fetch_assoc()['total'];
?>

<div class="container">
    <div class="p-5 mb-4 bg-light rounded-3 shadow-sm mt-5">
      <div class="container-fluid py-5">
        <h1 class="display-5 fw-bold">Selamat Datang, <?= htmlspecialchars($_SESSION['nama']) ?>!</h1>
        <p class="col-md-8 fs-4">Kelola penitipan hewan kesayangan Anda dengan mudah melalui dashboard ini.</p>
        <a href="form_pemesanan.php" class="btn btn-primary btn-lg" type="button">Pesan Penitipan Baru</a>
      </div>
    </div>

    <div class="row align-items-md-stretch mt-3">
      <div class="col-md-6 mb-4">
        <div class="h-100 p-5 text-black bg-warning rounded-3 shadow">
          <h2><i class="bi bi-clock-history"></i> Status Pesanan</h2>
          <p>Anda memiliki <strong><?= $pesanan_aktif ?> pesanan</strong> yang sedang menunggu atau dalam proses penitipan.</p>
          <a href="status_pesanan.php" class="btn btn-light" type="button">Lihat Detail</a>
        </div>
      </div>
      <div class="col-md-6 mb-4">
        <div class="h-100 p-5 bg-white border rounded-3 shadow">
          <h2><i class="bi bi-check2-circle"></i> Riwayat Pesanan</h2>
          <p>Lihat kembali semua riwayat penitipan hewan Anda yang telah selesai. Total <strong><?= $pesanan_selesai ?> pesanan</strong>.</p>
          <a href="riwayat_pesanan.php" class="btn btn-secondary" type="button">Lihat Riwayat</a>
        </div>
      </div>
    </div>
</div>

<?php include '../layouts/footer.php'; ?>