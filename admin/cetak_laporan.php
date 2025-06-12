<?php
include '../config/database.php';

$tgl_mulai = isset($_GET['tgl_mulai']) ? $_GET['tgl_mulai'] : '';
$tgl_selesai = isset($_GET['tgl_selesai']) ? $_GET['tgl_selesai'] : '';
$status_filter = isset($_GET['status']) ? $_GET['status'] : '';

$params = [];
$types = '';

$query = "SELECT 
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

if (!empty($tgl_mulai) && !empty($tgl_selesai)) {
    $query .= " AND p.tgl_masuk BETWEEN ? AND ?";
    array_push($params, $tgl_mulai, $tgl_selesai);
    $types .= 'ss';
}
if (!empty($status_filter)) {
    if ($status_filter == 'Terkonfirmasi') {
        $query .= " AND p.status = 'Terkonfirmasi' AND py.status_bayar = 'Terkonfirmasi'";
    } elseif ($status_filter == 'Belum') {
        $query .= " AND (p.status = 'Belum_Dikonfirmasi' OR py.status_bayar IS NULL OR py.status_bayar != 'Terkonfirmasi')";
    }
}


$query .= " GROUP BY p.id_pesanan";

$stmt = $koneksi->prepare($query);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Cetak Laporan Pemesanan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #000;
            padding: 20px;
        }

        h2 {
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #444;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #ddd;
        }

        .print-btn {
            margin: 10px 0;
            display: block;
        }

        @media print {
            .print-btn {
                display: none;
            }
        }
    </style>
</head>
<body>

<h2>Laporan Pemesanan</h2>
<?php if (!empty($tgl_mulai) && !empty($tgl_selesai)): ?>
    <p><strong>Periode:</strong> <?= date('d M Y', strtotime($tgl_mulai)) ?> - <?= date('d M Y', strtotime($tgl_selesai)) ?></p>
<?php endif; ?>
<?php if (!empty($status_filter)): ?>
    <p><strong>Status:</strong> <?= htmlspecialchars($status_filter) ?></p>
<?php endif; ?>

<button onclick="window.print()" class="print-btn">Cetak</button>

<table>
    <thead>
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
                <td><?= htmlspecialchars($row['status']) ?></td>
                <td><?= $row['status_bayar'] ? htmlspecialchars($row['status_bayar']) : 'Belum Bayar' ?></td>
                <td>Rp<?= number_format($row['total_harga'], 0, ',', '.') ?></td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>

</body>
</html>
