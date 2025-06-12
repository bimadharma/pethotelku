<?php 
// /user/layanan.php
include '../layouts/header.php'; 
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'user') {
    header("Location: ../auth/login.php"); exit();
}
?>

<div class="container">
    <div class="text-center mb-5">
        <h1 class="fw-bold">Pilih Layanan Terbaik</h1>
        <p class="lead text-muted">Kami menyediakan berbagai paket layanan untuk memenuhi semua kebutuhan hewan kesayangan Anda.</p>
    </div>

    <div class="row">
        <?php
        $query_layanan = mysqli_query($koneksi, "SELECT * FROM tb_layanan");
        if(mysqli_num_rows($query_layanan) > 0):
            while ($layanan = mysqli_fetch_assoc($query_layanan)):
        ?>
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card h-100 shadow-sm border-0 text-center">
                <div class="card-header bg-primary text-white">
                    <h4 class="my-0 fw-normal"><?= htmlspecialchars($layanan['nama_layanan']) ?></h4>
                </div>
                <div class="card-body d-flex flex-column">
                    <h1 class="card-title pricing-card-title">Rp<?= number_format($layanan['harga']) ?><small class="text-muted fw-light">/hari</small></h1>
                    <p class="mt-3 mb-4"><?= htmlspecialchars($layanan['deskripsi']) ?></p>
                    <a href="form_pemesanan.php?layanan=<?= $layanan['id_layanan'] ?>" class="btn btn-lg btn-primary mt-auto">Pesan Layanan Ini</a>
                </div>
            </div>
        </div>
        <?php endwhile; else: ?>
            <p class="text-center">Layanan belum tersedia.</p>
        <?php endif; ?>
    </div>
</div>

<?php include '../layouts/footer.php'; ?>