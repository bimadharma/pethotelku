<?php
// /config/database.php

$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'db_petservice';

$koneksi = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

if (!$koneksi) {
    die("Koneksi Gagal: " . mysqli_connect_error());
}

// Set base URL dynamically
$base_url = sprintf(
    "%s://%s%s",
    isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
    $_SERVER['HTTP_HOST'],
    dirname($_SERVER['SCRIPT_NAME']) === DIRECTORY_SEPARATOR ? '' : dirname($_SERVER['SCRIPT_NAME'])
);
// Replace backslashes with forward slashes for Windows compatibility in URL
$base_url = str_replace('\\', '/', $base_url);
// Remove subdirectories to get the project root
$base_url = preg_replace('/\/admin|\/user|\/auth/', '', $base_url);


if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>