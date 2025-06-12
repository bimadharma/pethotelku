<?php 
// /admin/data_fasilitas.php
include '../layouts/admin_header.php';

// Proses Form (Tambah, Edit)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['tambah_fasilitas'])) {
        $nama = $_POST['nama_fasilitas'];
        $ket = $_POST['keterangan'];
        $harga = $_POST['harga_tambahan'];
        $stmt = $koneksi->prepare("INSERT INTO tb_fasilitas (nama_fasilitas, keterangan, harga_tambahan) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $nama, $ket, $harga);
        $stmt->execute();
    } elseif (isset($_POST['edit_fasilitas'])) {
        $id = $_POST['id_fasilitas'];
        $nama = $_POST['nama_fasilitas'];
        $ket = $_POST['keterangan'];
        $harga = $_POST['harga_tambahan'];
        $stmt = $koneksi->prepare("UPDATE tb_fasilitas SET nama_fasilitas=?, keterangan=?, harga_tambahan=? WHERE id_fasilitas=?");
        $stmt->bind_param("ssii", $nama, $ket, $harga, $id);
        $stmt->execute();
    }
    // Redirect untuk menghindari re-submit form
    echo "<script>window.location='data_fasilitas.php';</script>";
}

// Proses Hapus
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $stmt = $koneksi->prepare("DELETE FROM tb_fasilitas WHERE id_fasilitas = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    echo "<script>window.location='data_fasilitas.php';</script>";
}
?>

<h1 class="h3 mb-4 text-white">Data Fasilitas Tambahan</h1>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahModal">
            <i class="bi bi-plus-lg"></i> Tambah Fasilitas
        </button>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nama Fasilitas</th>
                        <th>Keterangan</th>
                        <th>Harga Tambahan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $result = $koneksi->query("SELECT * FROM tb_fasilitas ORDER BY id_fasilitas DESC");
                    while($row = $result->fetch_assoc()):
                    ?>
                    <tr>
                        <td><?= $row['id_fasilitas'] ?></td>
                        <td><?= htmlspecialchars($row['nama_fasilitas']) ?></td>
                        <td><?= htmlspecialchars($row['keterangan']) ?></td>
                        <td>Rp <?= number_format($row['harga_tambahan']) ?></td>
                        <td>
                            <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?= $row['id_fasilitas'] ?>"><i class="bi bi-pencil"></i></button>
                            <a href="data_fasilitas.php?hapus=<?= $row['id_fasilitas'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Anda yakin ingin menghapus data ini?')"><i class="bi bi-trash"></i></a>
                        </td>
                    </tr>
                    
                    <div class="modal fade" id="editModal<?= $row['id_fasilitas'] ?>" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form method="POST">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Fasilitas</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <input type="hidden" name="id_fasilitas" value="<?= $row['id_fasilitas'] ?>">
                                        <div class="mb-3"><label class="form-label">Nama Fasilitas</label><input type="text" name="nama_fasilitas" class="form-control" value="<?= htmlspecialchars($row['nama_fasilitas']) ?>" required></div>
                                        <div class="mb-3"><label class="form-label">Keterangan</label><input type="text" name="keterangan" class="form-control" value="<?= htmlspecialchars($row['keterangan']) ?>"></div>
                                        <div class="mb-3"><label class="form-label">Harga Tambahan</label><input type="number" name="harga_tambahan" class="form-control" value="<?= $row['harga_tambahan'] ?>" required></div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" name="edit_fasilitas" class="btn btn-primary">Simpan Perubahan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="tambahModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Fasilitas Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3"><label class="form-label">Nama Fasilitas</label><input type="text" name="nama_fasilitas" class="form-control" required></div>
                    <div class="mb-3"><label class="form-label">Keterangan</label><input type="text" name="keterangan" class="form-control"></div>
                    <div class="mb-3"><label class="form-label">Harga Tambahan</label><input type="number" name="harga_tambahan" class="form-control" required></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" name="tambah_fasilitas" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include '../layouts/admin_footer.php'; ?>