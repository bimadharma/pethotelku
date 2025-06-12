<?php
require_once __DIR__ . '/../config/database.php';

// Proteksi halaman admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    $_SESSION['flash_message'] = ['type' => 'danger', 'text' => 'Anda tidak memiliki akses ke halaman ini.'];
    header("Location: {$base_url}/auth/login.php");
    exit();
}

$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - PetHotelku</title>
    <link href="<?= $base_url ?>/assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= $base_url ?>/assets/css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body>
    <!-- Sidebar -->
    <div class="bg-dark text-white" id="sidebar-wrapper" style="min-width: 250px;">
        <div class="sidebar-heading text-center py-4 fs-4 fw-bold border-bottom">
            <img src="<?= $base_url ?>/assets/img/logo.png" alt="Logo" style="width: 45px;">
            PetHotelku
        </div>

        <div class="text-center border-bottom py-3">
            <i class="bi bi-person-circle fs-3 mb-1 d-block"></i>
            <strong><?= htmlspecialchars($_SESSION['nama']); ?></strong>
            <div class="mt-2">
                <button type="button" class="btn btn-outline-light btn-sm" data-bs-toggle="modal" data-bs-target="#logoutModal">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </button>
            </div>
        </div>

        <div class="list-group list-group-flush mt-3">
            <a href="dashboard.php" class="list-group-item list-group-item-action bg-dark text-white <?= ($current_page == 'dashboard.php') ? 'active' : '' ?>"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
            <a href="konfirmasi_pembayaran.php" class="list-group-item list-group-item-action bg-dark text-white <?= ($current_page == 'konfirmasi_pembayaran.php') ? 'active' : '' ?>"><i class="bi bi-patch-check-fill me-2"></i>Konfirmasi Pembayaran</a>
            <a href="data_pesanan.php" class="list-group-item list-group-item-action bg-dark text-white <?= ($current_page == 'data_pesanan.php') ? 'active' : '' ?>"><i class="bi bi-journal-text me-2"></i>Data Pesanan</a>
            <a href="data_layanan.php" class="list-group-item list-group-item-action bg-dark text-white <?= ($current_page == 'data_layanan.php') ? 'active' : '' ?>"><i class="bi bi-box-seam me-2"></i>Data Layanan</a>
            <a href="data_fasilitas.php" class="list-group-item list-group-item-action bg-dark text-white <?= ($current_page == 'data_fasilitas.php') ? 'active' : '' ?>"><i class="bi bi-plus-square-dotted me-2"></i>Data Fasilitas</a>
            <a href="data_users.php" class="list-group-item list-group-item-action bg-dark text-white <?= ($current_page == 'data_users.php') ? 'active' : '' ?>"><i class="bi bi-people-fill me-2"></i>Data Users</a>
            <a href="laporan.php" class="list-group-item list-group-item-action bg-dark text-white <?= ($current_page == 'laporan.php') ? 'active' : '' ?>"><i class="bi bi-file-earmark-bar-graph me-2"></i>Laporan</a>
        </div>
    </div>
    <div class="d-flex" id="wrapper">
        <!-- Page Content -->
        <div id="page-content-wrapper" class="w-100">
            <div class="d-md-none bg-light p-2 shadow-sm">
                <div class="d-flex justify-content-end">
                    <button class="btn btn-outline-dark" id="menu-toggle">
                        <i class="bi bi-list" id="menu-icon"></i> Menu
                    </button>
                </div>
            </div>
            <main class="container-fluid p-4">