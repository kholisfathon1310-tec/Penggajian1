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
define(
    'BASE_URL',
    'https://adkcloud.my.id/'
);

// ======================
// KONEKSI PDO
// ======================
try {

    $dsn =
        "mysql:host=" . DB_HOST .
        ";dbname=" . DB_NAME .
        ";charset=utf8mb4";

    $koneksi = new PDO(

        $dsn,

        DB_USER,

        DB_PASS,

        [
            PDO::ATTR_ERRMODE =>
                PDO::ERRMODE_EXCEPTION,

            PDO::ATTR_DEFAULT_FETCH_MODE =>
                PDO::FETCH_ASSOC,

            PDO::ATTR_EMULATE_PREPARES =>
                false
        ]
    );

} catch (PDOException $e) {

    die(
        "Connection error: "
        . $e->getMessage()
    );
}
?>
