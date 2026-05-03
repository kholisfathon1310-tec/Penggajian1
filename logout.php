<?php
session_start(); // Memulai sesi

// Class untuk mengelola sesi
class SessionManager {
    public function startSession() {
        session_start();
    }

    public function endSession() {
        session_unset();
        session_destroy();
    }

    public function setMessage($message) {
        $_SESSION['message'] = $message;
    }

    public function getMessage() {
        return isset($_SESSION['message']) ? $_SESSION['message'] : '';
    }

    public function clearMessage() {
        unset($_SESSION['message']);
    }
}

// Class untuk menangani proses logout
class Logout {
    private $sessionManager;

    public function __construct(SessionManager $sessionManager) {
        $this->sessionManager = $sessionManager;
    }

    public function performLogout() {
        // Mengakhiri sesi
        $this->sessionManager->endSession();

        // Set message untuk ditampilkan di halaman login
        $this->sessionManager->setMessage("Berhasil Logout! Anda akan diarahkan kembali ke halaman login.");

        // Redirect ke halaman login
        header("Location: form_login.php");
        exit; // Pastikan setelah redirect, script berhenti
    }
}

// Membuat objek SessionManager
$sessionManager = new SessionManager();

// Membuat objek Logout dan memanggil metode performLogout
$logout = new Logout($sessionManager);
$logout->performLogout();
?>
