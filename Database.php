<?php
class Database {
    private $host = "localhost"; // Ganti dengan host database kamu
    private $db_name = "penggajian"; // Nama database
    private $username = "root"; // Username database
    private $password = ""; // Password database
    public $conn;

    public function getConnection() {
        $this->conn = null;

        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }

        return $this->conn;
    }
}
?>
