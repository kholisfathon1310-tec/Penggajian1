<?php
class Auth {
    protected $koneksi;
    protected $NIP;
    protected $password;

    public function __construct($koneksi, $NIP, $password) {
        $this->koneksi = $koneksi;
        $this->NIP = $NIP;
        $this->password = $password;
    }

    // Fungsi login tanpa hashing
    public function login() {
        // Query untuk mencari pengguna berdasarkan NIP
        $query = "SELECT * FROM user WHERE NIP = ?";
        $stmt = $this->koneksi->prepare($query);
        
        if (!$stmt) {
            die("Kesalahan query: " . $this->koneksi->error);
        }

        // Bind parameter dan eksekusi query
        $stmt->bind_param('s', $this->NIP);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        // Jika pengguna ditemukan dan password valid
        if ($user && $this->password === $user['password']) {
            return $user;  // Mengembalikan data pengguna
        }

        return false;  // Jika username atau password salah
    }
}

?>
