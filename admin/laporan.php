<?php
// /admin/laporan.php
include '../layouts/admin_header.php';

$tgl_mulai = isset($_GET['tgl_mulai']) ? $_GET['tgl_mulai'] : '';
$tgl_selesai = isset($_GET['tgl_selesai']) ? $_GET['tgl_selesai'] : '';
$status_filter = isset($_GET['status']) ? $_GET['status'] : '';

$params = [];
$types = '';

$query_laporan = "SELECT 
                    p.*, 
                    u.nama, 
                    l.nama_layanan, 
                    py.status_bayar,
                    GROUP_CONCAT(f.nama_fasilitas SEPARATOR ', ') AS fasilitas
                  FROM tb_pesanan p
                  JOIN tb_users u ON p.id_user = u.id_user
                  JOIN tb_layanan l ON p.id_layanan = l.id_layanan
                  LEFT JOIN tb_pembayaran py ON p.id_pesanan = py.id_pesanan
                  LEFT JOIN tb_pesanan_fasilitas pf ON p.id_pesanan = pf.id_pesanan
                  LEFT JOIN tb_fasilitas f ON pf.id_fasilitas = f.id_fasilitas
                  WHERE 1=1";

// Filter tanggal
if (!empty($status_filter)) {
    if ($status_filter == 'Terkonfirmasi') {
        $query_laporan .= " AND py.status_bayar = ?";
        array_push($params, 'Terkonfirmasi');
        $types .= 's';
    } else if ($status_filter == 'Belum') {
        $query_laporan .= " AND (py.status_bayar IS NULL OR py.status_bayar = 'Belum_Dikonfirmasi')";
        // Tidak perlu tambah parameter karena kita langsung tulis di query
    }
}

$query_laporan .= " GROUP BY p.id_pesanan";

$stmt = $koneksi->prepare($query_laporan);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
?>

<div class="no-print">
    <h1 class="h3 mb-4 text-white">Laporan Pemesanan</h1>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3 no-print">
        <h6 class="m-0 font-weight-bold text-primary">Filter Laporan</h6>
    </div>
    <div class="card-body">
        <form method="GET" action="laporan.php" class="row g-3 align-items-end no-print">
            <div class="col-md-4">
                <label class="form-label">Tanggal Mulai</label>
                <input type="date" name="tgl_mulai" class="form-control" value="<?= $tgl_mulai ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label">Tanggal Selesai</label>
                <input type="date" name="tgl_selesai" class="form-control" value="<?= $tgl_selesai ?>">
            </div>
            <div class="col-md-2">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
    <option value="">Semua</option>
    <option value="Belum" <?= $status_filter == 'Belum' ? 'selected' : '' ?>>Belum Bayar / Belum Dikonfirmasi</option>
    <option value="Terkonfirmasi" <?= $status_filter == 'Terkonfirmasi' ? 'selected' : '' ?>>Terkonfirmasi</option>
</select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">Filter</button>
            </div>
        </form>

        <hr class="my-4">
        <div class="d-flex justify-content-between align-items-center">
            <h4>Hasil Laporan</h4>
            <a href="cetak_laporan.php?tgl_mulai=<?= $tgl_mulai ?>&tgl_selesai=<?= $tgl_selesai ?>&status=<?= $status_filter ?>" class="btn btn-secondary no-print" target="_blank">
                <i class="bi bi-printer"></i> Cetak Laporan
            </a>
        </div>

        <div class="table-responsive mt-3">
            <table class="table table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>ID Pesanan</th>
                        <th>Nama User</th>
                        <th>Layanan</th>
                        <th>Fasilitas</th>
                        <th>Tgl Masuk</th>
                        <th>Status Pesanan</th>
                        <th>Status Bayar</th>
                        <th>Harga Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= $row['id_pesanan'] ?></td>
                            <td><?= htmlspecialchars($row['nama']) ?></td>
                            <td><?= htmlspecialchars($row['nama_layanan']) ?></td>
                            <td><?= $row['fasilitas'] ? htmlspecialchars($row['fasilitas']) : '-' ?></td>
                            <td><?= date('d M Y', strtotime($row['tgl_masuk'])) ?></td>
                            <td><?= $row['status'] ?></td>
                            <td>
                                <span class="badge bg-<?= $row['status_bayar'] == 'Terkonfirmasi' ? 'success' : 'danger' ?>">
                                    <?= $row['status_bayar'] ? htmlspecialchars($row['status_bayar']) : 'Belum Bayar' ?>
                                </span>
                            </td>
                            <td>Rp<?= number_format($row['total_harga'], 0, ',', '.') ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../layouts/admin_footer.php'; ?>
