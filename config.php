<?php

// ======================
// DATABASE CONFIG
// ======================
define('DB_HOST', 'localhost');
define('DB_USER', 'adkcloud_admin');
define('DB_PASS', '9G8URw.EiQ;SankA');
define('DB_NAME', 'adkcloud_Penggajian1');

// ======================
// BASE URL
// ======================
define('BASE_URL', 'https://adkcloud.com/penggajian/');

// ======================
// KONEKSI DATABASE PDO
// ======================
try {

    $koneksi = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME,
        DB_USER,
        DB_PASS
    );

    $koneksi->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {

    die("Koneksi database gagal: " . $e->getMessage());
}

?>
