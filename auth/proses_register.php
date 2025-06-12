<?php
require_once '../config/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $no_hp = $_POST['no_hp'];
    $alamat = $_POST['alamat'];
    $password = $_POST['password'];

    $stmt = $koneksi->prepare("SELECT email FROM tb_users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $_SESSION['flash_message'] = ['type' => 'danger', 'text' => 'Email sudah terdaftar.'];
        header("Location: register.php");
        exit();
    }
    $stmt->close();

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $role = 'user';

    $stmt_insert = $koneksi->prepare("INSERT INTO tb_users (nama, email, password, no_hp, alamat, role) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt_insert->bind_param("ssssss", $nama, $email, $hashed_password, $no_hp, $alamat, $role);
    
    if ($stmt_insert->execute()) {
        $_SESSION['flash_message'] = ['type' => 'success', 'text' => 'Registrasi berhasil! Silakan login.'];
        header("Location: login.php");
    } else {
        $_SESSION['flash_message'] = ['type' => 'danger', 'text' => 'Registrasi gagal. Coba lagi.'];
        header("Location: register.php");
    }
    $stmt_insert->close();
    $koneksi->close();
}
?>