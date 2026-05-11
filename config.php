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
// CLASS DATABASE
// ======================
class Database {

    private $host = DB_HOST;
    private $db_name = DB_NAME;
    private $username = DB_USER;
    private $password = DB_PASS;

    public function getConnection()
    {
        try {

            $conn = new PDO(
                "mysql:host={$this->host};dbname={$this->db_name}",
                $this->username,
                $this->password
            );

            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            return $conn;

        } catch (PDOException $e) {

            die("Koneksi database gagal: " . $e->getMessage());
        }
    }
}

// ======================
// KONEKSI GLOBAL
// ======================
$db = new Database();

$koneksi = $db->getConnection();

?>
