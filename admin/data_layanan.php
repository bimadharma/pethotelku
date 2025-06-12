<?php 
// /admin/data_layanan.php
include '../layouts/admin_header.php';

// --- PROSES FORM (CREATE, UPDATE, DELETE) ---

// 1. PROSES TAMBAH DATA (CREATE)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['tambah_layanan'])) {
    $nama = $_POST['nama_layanan'];
    $deskripsi = $_POST['deskripsi'];
    $harga = $_POST['harga'];

    $stmt = $koneksi->prepare("INSERT INTO tb_layanan (nama_layanan, deskripsi, harga) VALUES (?, ?, ?)");
    $stmt->bind_param("ssi", $nama, $deskripsi, $harga);
    
    if ($stmt->execute()) {
        $_SESSION['flash_message'] = ['type' => 'success', 'text' => 'Layanan baru berhasil ditambahkan!'];
    } else {
        $_SESSION['flash_message'] = ['type' => 'danger', 'text' => 'Gagal menambahkan layanan.'];
    }
    echo "<script>window.location='data_layanan.php';</script>";
    exit();
}

// 2. PROSES EDIT DATA (UPDATE)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_layanan'])) {
    $id = $_POST['id_layanan'];
    $nama = $_POST['nama_layanan'];
    $deskripsi = $_POST['deskripsi'];
    $harga = $_POST['harga'];

    $stmt = $koneksi->prepare("UPDATE tb_layanan SET nama_layanan=?, deskripsi=?, harga=? WHERE id_layanan=?");
    $stmt->bind_param("ssii", $nama, $deskripsi, $harga, $id);

    if ($stmt->execute()) {
        $_SESSION['flash_message'] = ['type' => 'success', 'text' => 'Data layanan berhasil diperbarui!'];
    } else {
        $_SESSION['flash_message'] = ['type' => 'danger', 'text' => 'Gagal memperbarui data.'];
    }
    echo "<script>window.location='data_layanan.php';</script>";
    exit();
}

// 3. PROSES HAPUS DATA (DELETE)
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $check_stmt = $koneksi->prepare("SELECT COUNT(*) FROM tb_pesanan WHERE id_layanan = ?");
    $check_stmt->bind_param("i", $id);
    $check_stmt->execute();
    $result = $check_stmt->get_result()->fetch_row();

    if ($result[0] > 0) {
        $_SESSION['flash_message'] = ['type' => 'danger', 'text' => 'Gagal menghapus! Layanan ini sudah digunakan dalam data pesanan.'];
    } else {
        $stmt = $koneksi->prepare("DELETE FROM tb_layanan WHERE id_layanan = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            $_SESSION['flash_message'] = ['type' => 'success', 'text' => 'Data layanan berhasil dihapus.'];
        } else {
            $_SESSION['flash_message'] = ['type' => 'danger', 'text' => 'Gagal menghapus data.'];
        }
    }
    echo "<script>window.location='data_layanan.php';</script>";
    exit();
}
?>

<h1 class="h3 mb-4 text-white">Kelola Data Layanan</h1>

<?php
if (isset($_SESSION['flash_message'])) {
    $message = $_SESSION['flash_message'];
    unset($_SESSION['flash_message']);
    echo "<div class='alert alert-{$message['type']} alert-dismissible fade show' role='alert'>
            {$message['text']}
            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
          </div>";
}
?>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahModal">
            <i class="bi bi-plus-lg"></i> Tambah Layanan
        </button>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nama Layanan</th>
                        <th>Deskripsi</th>
                        <th>Harga per Hari</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $result = $koneksi->query("SELECT * FROM tb_layanan ORDER BY id_layanan DESC");
                    while($row = $result->fetch_assoc()):
                    ?>
                    <tr>
                        <td><?= $row['id_layanan'] ?></td>
                        <td><?= htmlspecialchars($row['nama_layanan']) ?></td>
                        <td><?= htmlspecialchars($row['deskripsi']) ?></td>
                        <td>Rp <?= number_format($row['harga']) ?> / hari</td>
                        <td>
                            <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?= $row['id_layanan'] ?>"><i class="bi bi-pencil"></i></button>
                            <a href="data_layanan.php?hapus=<?= $row['id_layanan'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Anda yakin ingin menghapus data ini?')"><i class="bi bi-trash"></i></a>
                        </td>
                    </tr>

                    <!-- Modal Edit -->
                    <div class="modal fade" id="editModal<?= $row['id_layanan'] ?>" tabindex="-1" aria-labelledby="editModalLabel<?= $row['id_layanan'] ?>" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form method="POST" action="data_layanan.php">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editModalLabel<?= $row['id_layanan'] ?>">Edit Layanan</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <input type="hidden" name="id_layanan" value="<?= $row['id_layanan'] ?>">
                                        <div class="mb-3">
                                            <label class="form-label">Nama Layanan</label>
                                            <input type="text" name="nama_layanan" class="form-control" value="<?= htmlspecialchars($row['nama_layanan']) ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Deskripsi</label>
                                            <textarea name="deskripsi" class="form-control" rows="3"><?= htmlspecialchars($row['deskripsi']) ?></textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Harga (per Hari)</label>
                                            <input type="number" name="harga" class="form-control" value="<?= $row['harga'] ?>" required>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" name="edit_layanan" class="btn btn-primary">Simpan Perubahan</button>
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

<!-- Modal Tambah -->
<div class="modal fade" id="tambahModal" tabindex="-1" aria-labelledby="tambahModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="data_layanan.php">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahModalLabel">Tambah Layanan Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Layanan</label>
                        <input type="text" name="nama_layanan" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Harga (per Hari)</label>
                        <input type="number" name="harga" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" name="tambah_layanan" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include '../layouts/admin_footer.php'; ?>
