<?php
// /user/riwayat_pesanan.php
include '../layouts/header.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'user') {
    header("Location: ../auth/login.php");
    exit();
}
$user_id = $_SESSION['user_id'];
$stmt = $koneksi->prepare("SELECT p.*, l.nama_layanan FROM tb_pesanan p 
                           JOIN tb_layanan l ON p.id_layanan = l.id_layanan
                           WHERE p.id_user = ? AND p.status = 'Selesai' ORDER BY p.id_pesanan DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<h2 class="mb-4 text-white">Riwayat Pesanan Anda</h2>

<div class="card shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID Pesanan</th>
                        <th>Layanan</th>
                        <th>Nama Hewan</th>
                        <th>Jadwal</th>
                        <th>Total Harga</th>
                        <th>Struk</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td>#<?= $row['id_pesanan'] ?></td>
                                <td><?= htmlspecialchars($row['nama_layanan']) ?></td>
                                <td><?= htmlspecialchars($row['nama_hewan']) ?></td>
                                <td><?= date('d M Y', strtotime($row['tgl_masuk'])) ?> - <?= date('d M Y', strtotime($row['tgl_keluar'])) ?></td>
                                <td>Rp<?= number_format($row['total_harga'], 0, ',', '.') ?></td>
                                <td>
                                    <a href="struk_pesanan.php?id=<?= $row['id_pesanan'] ?>" class="btn btn-sm btn-outline-primary">
                                        Lihat Struk
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center">Anda belum memiliki riwayat pesanan.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../layouts/footer.php'; ?>