<?php
class Database {
    private $host = "maleo"; // Ganti dengan host database kamu
    private $db_name = "adkcloud_Penggajian1"; // Nama database
    private $username = "adkcloud_admin"; // Username database
    private $password = "9G8URw.EiQ;SankA"; // Password database
    public $conn;

    public function getConnection() {
        $this->conn = null;

        try {
            $this->conn = new PDO("mysql:host=" . $this->host . "adkcloud_penggajian" . $this->db_name, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }

        return $this->conn;
    }
}
?>
