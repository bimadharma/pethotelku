<?php
session_start();
include '../config/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'user') {
    header("Location: ../auth/login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_pesanan = $_POST['id_pesanan'];
    $bukti = $_FILES['bukti_bayar'];

    if ($bukti['error'] === 0 && $bukti['size'] <= 2 * 1024 * 1024) { // max 2MB
        $ext = pathinfo($bukti['name'], PATHINFO_EXTENSION);
        $namaFileBaru = uniqid('bukti_') . '.' . $ext;
        $target = '../assets/bukti/' . $namaFileBaru;

        if (move_uploaded_file($bukti['tmp_name'], $target)) {
            $tgl_bayar = date('Y-m-d');

            // Simpan data pembayaran ke tb_pembayaran
            $stmt = $koneksi->prepare("INSERT INTO tb_pembayaran (id_pesanan, tgl_bayar, bukti_bayar, status_bayar) VALUES (?, ?, ?, 'Belum_DiKonfirmasi')");
            $stmt->bind_param("iss", $id_pesanan, $tgl_bayar, $namaFileBaru);
            $stmt->execute();

            // Update status pesanan menjadi 'Diproses'
            $update = $koneksi->prepare("UPDATE tb_pesanan SET status = 'Diproses' WHERE id_pesanan = ?");
            $update->bind_param("i", $id_pesanan);
            $update->execute();

            header("Location: status_pesanan.php?success=1");
            exit;
        } else {
            echo "Gagal upload file.";
        }
    } else {
        echo "File tidak valid (maks 2MB dan tidak error).";
    }
} else {
    header("Location: status_pesanan.php");
}
