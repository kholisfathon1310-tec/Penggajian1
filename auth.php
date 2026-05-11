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

    // ======================
    // LOGIN USER
    // ======================
    public function login() {

        try {

            // QUERY USER
            $stmt = $this->koneksi->prepare("
                SELECT *
                FROM user
                WHERE NIP = :nip
                LIMIT 1
            ");

            $stmt->execute([
                ':nip' => $this->NIP
            ]);

            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // ======================
            // CEK USER
            // ======================
            if ($user) {

                // ======================
                // PASSWORD HASH
                // ======================
                if (password_verify($this->password, $user['password'])) {

                    return $user;
                }

                // ======================
                // BACKUP PASSWORD LAMA
                // (kalau database lama masih plain text)
                // ======================
                if ($this->password === $user['password']) {

                    return $user;
                }
            }

            return false;

        } catch (PDOException $e) {

            die("Login error: " . $e->getMessage());
        }
    }
}

?>
