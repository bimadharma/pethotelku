<?php
include '../config/database.php';

if (!isset($_GET['id'])) {
    die("ID Pesanan tidak ditemukan.");
}

$id_pesanan = $_GET['id'];

// Ambil data utama
$query = "SELECT 
            p.*, 
            u.nama, u.no_hp, u.alamat,
            l.nama_layanan,
            py.status_bayar,
            GROUP_CONCAT(f.nama_fasilitas SEPARATOR ', ') AS fasilitas
          FROM tb_pesanan p
          JOIN tb_users u ON p.id_user = u.id_user
          JOIN tb_layanan l ON p.id_layanan = l.id_layanan
          LEFT JOIN tb_pembayaran py ON p.id_pesanan = py.id_pesanan
          LEFT JOIN tb_pesanan_fasilitas pf ON p.id_pesanan = pf.id_pesanan
          LEFT JOIN tb_fasilitas f ON pf.id_fasilitas = f.id_fasilitas
          WHERE p.id_pesanan = ?
          GROUP BY p.id_pesanan";

$stmt = $koneksi->prepare($query);
$stmt->bind_param("i", $id_pesanan);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Data tidak ditemukan.");
}

$data = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Struk Pemesanan</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            padding: 20px;
            color: #000;
        }

        .struk-container {
            border: 1px solid #ccc;
            padding: 20px;
            max-width: 700px;
            margin: auto;
        }

        h2, h4 {
            text-align: center;
            margin: 0;
        }

        .info {
            margin-top: 20px;
        }

        .info table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .info th, .info td {
            text-align: left;
            padding: 6px;
            border-bottom: 1px solid #ddd;
        }

        .section-title {
            background-color: #f5f5f5;
            font-weight: bold;
            padding: 5px;
        }

        .text-right {
            text-align: right;
        }

        .print-btn {
            display: block;
            margin: 20px auto;
        }

        @media print {
            .print-btn {
                display: none;
            }
        }
    </style>
</head>
<body>

<div class="struk-container">
    <h2>Struk Pemesanan</h2>
    <h4>ID Pesanan: #<?= $data['id_pesanan'] ?></h4>

    <div class="info">
        <div class="section-title">Informasi Pengguna</div>
        <table>
            <tr><th>Nama</th><td><?= htmlspecialchars($data['nama']) ?></td></tr>
            <tr><th>No HP</th><td><?= htmlspecialchars($data['no_hp']) ?></td></tr>
            <tr><th>Alamat</th><td><?= htmlspecialchars($data['alamat']) ?></td></tr>
        </table>

        <div class="section-title">Detail Pemesanan</div>
        <table>
            <tr><th>Nama Hewan</th><td><?= htmlspecialchars($data['nama_hewan']) ?></td></tr>
            <tr><th>Jenis Hewan</th><td><?= htmlspecialchars($data['jenis_hewan']) ?></td></tr>
            <tr><th>Layanan</th><td><?= htmlspecialchars($data['nama_layanan']) ?></td></tr>
            <tr><th>Fasilitas</th><td><?= $data['fasilitas'] ? htmlspecialchars($data['fasilitas']) : '-' ?></td></tr>
            <tr><th>Tanggal Masuk</th><td><?= date('d M Y', strtotime($data['tgl_masuk'])) ?></td></tr>
            <tr><th>Tanggal Keluar</th><td><?= date('d M Y', strtotime($data['tgl_keluar'])) ?></td></tr>
            <tr><th>Status Pesanan</th><td><?= htmlspecialchars($data['status']) ?></td></tr>
            <tr><th>Status Bayar</th><td><?= $data['status_bayar'] ?? 'Belum Bayar' ?></td></tr>
        </table>

        <div class="section-title text-right">Total Harga: <strong>Rp<?= number_format($data['total_harga'], 0, ',', '.') ?></strong></div>
    </div>

    <button class="print-btn" onclick="window.print()">Cetak Struk</button>
</div>

</body>
</html>
