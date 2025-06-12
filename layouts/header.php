<?php
require_once __DIR__ . '/../config/database.php';
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pet Service - Penitipan Hewan Profesional</title>
    <link href="<?= $base_url ?>/assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= $base_url ?>/assets/css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top shadow">
        <div class="container">
            <a class="navbar-brand fw-bold d-flex align-items-center justify-content-center" href="<?= $base_url ?>/index.php">
                <img src="<?= $base_url ?>/assets/img/logo.png" alt="Logo" width="40" class="me-2">
                <span>PetService</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <?php if (!isset($_SESSION['user_id'])): ?>
                        <li class="nav-item"><a class="nav-link" href="<?= $base_url ?>/index.php#beranda">Beranda</a></li>
                        <li class="nav-item"><a class="nav-link" href="<?= $base_url ?>/index.php#tentang">Tentang Kami</a></li>
                        <li class="nav-item"><a class="nav-link" href="<?= $base_url ?>/index.php#layanan">Layanan</a></li>
                        <li class="nav-item"><a class="nav-link" href="<?= $base_url ?>/index.php#kontak">Kontak</a></li>
                        <li class="nav-item d-flex align-items-center ms-lg-3">
                            <a class="btn btn-outline-light btn-sm me-2" href="<?= $base_url ?>/auth/login.php">Login</a>
                            <a class="btn btn-primary btn-sm" href="<?= $base_url ?>/auth/register.php">Daftar</a>
                        </li>
                    <?php else: ?>
                        <?php if ($_SESSION['role'] == 'admin'): ?>
                            <li class="nav-item"><a class="nav-link" href="<?= $base_url ?>/admin/dashboard.php">Dashboard Admin</a></li>
                        <?php else: ?>
                            <li class="nav-item"><a class="nav-link" href="<?= $base_url ?>/user/dashboard.php">Dashboard</a></li>
                            <li class="nav-item"><a class="nav-link" href="<?= $base_url ?>/user/form_pemesanan.php">Pesan Layanan</a></li>
                            <li class="nav-item"><a class="nav-link" href="<?= $base_url ?>/user/status_pesanan.php">Status Pesanan</a></li>
                            <li class="nav-item"><a class="nav-link" href="<?= $base_url ?>/user/riwayat_pesanan.php">Riwayat</a></li>
                        <?php endif; ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-person-circle"></i> <?= htmlspecialchars($_SESSION['nama']); ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <li><button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#logoutModal">Logout</button></li>
                            </ul>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <main class="container">