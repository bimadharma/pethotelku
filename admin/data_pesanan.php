<?php 
// /admin/data_pesanan.php
include '../layouts/admin_header.php';

// Filter
$filter_status = isset($_GET['status']) ? $_GET['status'] : '';
$sql = "SELECT p.*, u.nama AS nama_user, l.nama_layanan FROM tb_pesanan p
        JOIN tb_users u ON p.id_user = u.id_user
        JOIN tb_layanan l ON p.id_layanan = l.id_layanan";
if (!empty($filter_status)) {
    $sql .= " WHERE p.status = ?";
}
$sql .= " ORDER BY p.id_pesanan DESC";
$stmt = $koneksi->prepare($sql);
if(!empty($filter_status)){
    $stmt->bind_param("s", $filter_status);
}
$stmt->execute();
$result = $stmt->get_result();

?>

<h1 class="h3 mb-4 text-white">Data Pesanan</h1>

<div class="card shadow mb-4">
    <div class="card-header">
        <form method="GET" class="d-flex justify-content-end">
            <div class="w-25">
                <select name="status" class="form-select" onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    <option value="Menunggu" <?= $filter_status == 'Menunggu' ? 'selected' : '' ?>>Menunggu</option>
                    <option value="Diproses" <?= $filter_status == 'Diproses' ? 'selected' : '' ?>>Diproses</option>
                    <option value="Selesai" <?= $filter_status == 'Selesai' ? 'selected' : '' ?>>Selesai</option>
                </select>
            </div>
        </form>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>User</th>
                        <th>Layanan</th>
                        <th>Hewan</th>
                        <th>Tgl Masuk/Keluar</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $result->fetch_assoc()): 
                        $badge_class = '';
                        switch($row['status']){
                            case 'Menunggu': $badge_class = 'bg-warning text-dark'; break;
                            case 'Diproses': $badge_class = 'bg-info text-dark'; break;
                            case 'Selesai': $badge_class = 'bg-success'; break;
                        }
                    ?>
                    <tr>
                        <td><?= $row['id_pesanan'] ?></td>
                        <td><?= htmlspecialchars($row['nama_user']) ?></td>
                        <td><?= htmlspecialchars($row['nama_layanan']) ?></td>
                        <td><?= htmlspecialchars($row['nama_hewan']) ?> (<?= htmlspecialchars($row['jenis_hewan']) ?>)</td>
                        <td><?= date('d M Y', strtotime($row['tgl_masuk'])) ?> - <?= date('d M Y', strtotime($row['tgl_keluar'])) ?></td>
                        <td><span class="badge <?= $badge_class ?>"><?= $row['status'] ?></span></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../layouts/admin_footer.php'; ?>