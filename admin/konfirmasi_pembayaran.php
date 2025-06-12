<?php
include '../layouts/admin_header.php';

// Proses konfirmasi
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['aksi'])) {
    $id_pembayaran = $_POST['id_pembayaran'];
    $id_pesanan = $_POST['id_pesanan'];
    $aksi = $_POST['aksi']; // sesuai / tidak_sesuai

    $koneksi->begin_transaction();
    try {
        if ($aksi == 'sesuai') {
            $stmt1 = $koneksi->prepare("UPDATE tb_pembayaran SET status_bayar = 'Terkonfirmasi' WHERE id_pembayaran = ?");
            $stmt1->bind_param("i", $id_pembayaran);
            $stmt1->execute();

            $stmt2 = $koneksi->prepare("UPDATE tb_pesanan SET status = 'Selesai' WHERE id_pesanan = ?");
            $stmt2->bind_param("i", $id_pesanan);
            $stmt2->execute();
        } elseif ($aksi == 'tidak_sesuai') {
            $stmt1 = $koneksi->prepare("DELETE FROM tb_pembayaran WHERE id_pembayaran = ?");
            $stmt1->bind_param("i", $id_pembayaran);
            $stmt1->execute();
        }

        $koneksi->commit();
    } catch (mysqli_sql_exception $exception) {
        $koneksi->rollback();
        throw $exception;
    }

    echo "<script>window.location='konfirmasi_pembayaran.php';</script>";
}

$result = $koneksi->query("SELECT pb.*, p.id_pesanan, p.total_harga, u.nama as nama_user FROM tb_pembayaran pb 
                           JOIN tb_pesanan p ON pb.id_pesanan = p.id_pesanan
                           JOIN tb_users u ON p.id_user = u.id_user
                           WHERE pb.status_bayar = 'Belum_Dikonfirmasi' 
                           ORDER BY pb.tgl_bayar ASC");

?>

<h1 class="h3 mb-4 text-white">Konfirmasi Pembayaran</h1>

<div class="card shadow mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID Bayar</th>
                        <th>User</th>
                        <th>Tgl Bayar</th>
                        <th>Total Harga</th>
                        <th>Bukti Bayar</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= $row['id_pembayaran'] ?></td>
                                <td><?= htmlspecialchars($row['nama_user']) ?></td>
                                <td><?= date('d M Y', strtotime($row['tgl_bayar'])) ?></td>
                                <td>Rp <?= number_format($row['total_harga'], 0, ',', '.') ?></td>
                                <td>
                                    <?php if (!empty($row['bukti_bayar'])): ?>
                                        <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#modalBukti<?= $row['id_pembayaran'] ?>">
                                            Lihat Bukti
                                        </button>

                                        <div class="modal fade" id="modalBukti<?= $row['id_pembayaran'] ?>" tabindex="-1" aria-labelledby="modalLabel<?= $row['id_pembayaran'] ?>" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Bukti Pembayaran</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body text-center">
                                                        <img src="<?= $base_url ?>/assets/bukti/<?= htmlspecialchars($row['bukti_bayar']) ?>" class="img-fluid">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-muted">Tidak ada bukti</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <!-- Tombol Konfirmasi Sesuai -->
                                    <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modalSesuai<?= $row['id_pembayaran'] ?>">Konfirmasi Sesuai</button>
                                    <!-- Modal Konfirmasi Sesuai -->
                                    <div class="modal fade" id="modalSesuai<?= $row['id_pembayaran'] ?>" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <form method="POST">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Konfirmasi Pembayaran</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Apakah Anda yakin ingin mengonfirmasi pembayaran ini sebagai <strong>sesuai</strong>?
                                                        <input type="hidden" name="id_pembayaran" value="<?= $row['id_pembayaran'] ?>">
                                                        <input type="hidden" name="id_pesanan" value="<?= $row['id_pesanan'] ?>">
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="submit" name="aksi" value="sesuai" class="btn btn-success">Ya, Sesuai</button>
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Tombol Tidak Sesuai -->
                                    <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modalTidak<?= $row['id_pembayaran'] ?>">Tidak Sesuai</button>
                                    <!-- Modal Konfirmasi Tidak Sesuai -->
                                    <div class="modal fade" id="modalTidak<?= $row['id_pembayaran'] ?>" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <form method="POST">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Konfirmasi Pembayaran</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Apakah Anda yakin <strong>menolak</strong> dan menghapus pembayaran ini?
                                                        <input type="hidden" name="id_pembayaran" value="<?= $row['id_pembayaran'] ?>">
                                                        <input type="hidden" name="id_pesanan" value="<?= $row['id_pesanan'] ?>">
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="submit" name="aksi" value="tidak_sesuai" class="btn btn-danger">Ya, Hapus</button>
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center">Tidak ada pembayaran yang perlu dikonfirmasi.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../layouts/admin_footer.php'; ?>