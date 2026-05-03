<?php
// Koneksi ke database
$host = "localhost";
$db_name = "penggajian";
$username = "root";
$password = "";
$conn = null;

try {
    $conn = new PDO("mysql:host=" . $host . ";dbname=" . $db_name, $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $exception) {
    echo "Koneksi gagal: " . $exception->getMessage();
    exit;
}

// Mengambil NIP dari parameter URL
$NIP = isset($_GET['NIP']) ? $_GET['NIP'] : ''; // Mengambil NIP dari query string URL

// Menyusun query untuk mengambil data dari tabel admin_penggajian berdasarkan NIP
$sql = "SELECT * FROM admin_penggajian WHERE NIP = :NIP";  
$stmt = $conn->prepare($sql);

// Mengikat parameter NIP yang diterima dari URL
$stmt->bindParam(':NIP', $NIP);

$stmt->execute();

// Mengambil data hasil query
$gaji = $stmt->fetch(PDO::FETCH_ASSOC);

// Periksa apakah data ditemukan
if (!$gaji) {
    echo "<div class='info-container'>";
    echo "<p>Data gaji untuk NIP <strong>" . htmlspecialchars($nip, ENT_QUOTES, 'UTF-8') . "</strong> tidak ditemukan.</p>";
    echo "<button onclick=\"window.location.href='admin_gaji.php'\">Kembali</button>";
    echo "</div>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Gaji Karyawan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h2 {
            margin: 0;
            font-size: 24px;
            color: #333;
        }

        .info-container {
            margin: 20px;
            padding: 10px;
            background-color: #fff;
            border: 1px solid #ddd;
        }

        .info-container p {
            margin: 5px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background-color: #fff;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
            color: #333;
        }

        td {
            color: #555;
        }

        .button-container {
            text-align: left;
            margin-top: 20px;
            margin-left: 20px;
        }

        .button-container button {
            background-color: #555;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            margin-right: 10px;
            border-radius: 5px;
        }

        .button-container button:hover {
            background-color: #333;
        }

        /* Gaya khusus untuk saat mencetak */
        @media print {
            body {
                font-family: 'Courier New', Courier, monospace; /* Font yang lebih cocok untuk cetakan */
                font-size: 12px;
            }

            .button-container {
                display: none; /* Sembunyikan tombol saat mencetak */
            }

            .info-container {
                margin: 0;
                padding: 0;
            }

            .header h2 {
                font-size: 28px; /* Ukuran font header lebih besar saat print */
            }

            table {
                font-size: 12px;
                border: 1px solid #333; /* Warna border lebih gelap saat print */
            }

            th, td {
                padding: 8px;
            }

            th {
                background-color: #ddd; /* Warna latar belakang header tabel */
            }
        }
    </style>
</head>
<body>

<div class="header">
    <h2>SLIP GAJI KARYAWAN</h2>
</div>

<div class="info-container">
    <p><strong>NIP:</strong> <?php echo htmlspecialchars($gaji['NIP'], ENT_QUOTES, 'UTF-8'); ?></p>
    <p><strong>Nama Karyawan:</strong> <?php echo htmlspecialchars($gaji['nama_karyawan'], ENT_QUOTES, 'UTF-8'); ?></p>
    <p><strong>Posisi:</strong> <?php echo $gaji['posisi']; ?></p>
    <p><strong>Periode:</strong> <?php echo $gaji['periode']; ?></p>
</div>

<table>
    <tr>
        <th>Periode</th>
        <th>Tanggal Gaji</th>
        <th>Salary</th>
        <th>Potongan BPJS</th>
        <th>Potongan Absen</th>
        <th>Transportasi</th>
        <th>Lembur (Jam)</th>
        <th>Gaji Lembur Per Jam</th>
        <th>Status</th>
        <th>Total</th>
    </tr>
    <tr>
        <td><?php echo $gaji['periode']; ?></td>
        <td><?php echo $gaji['tanggal_gaji']; ?></td>
        <td>Rp <?php echo number_format($gaji['gaji_pokok'] ?: 0, 0, ',', '.'); ?></td>
        <td>Rp <?php echo number_format($gaji['pot_BPJS'] ?: 0, 0, ',', '.'); ?></td>
        <td>Rp <?php echo number_format($gaji['pot_absen'] ?: 0, 0, ',', '.'); ?></td>
        <td>Rp <?php echo number_format($gaji['transportasi'] ?: 0, 0, ',', '.'); ?></td>
        <td><?php echo $gaji['lembur'] ?: 0; ?></td>
        <td>Rp <?php echo number_format($gaji['gaji_lembur_per_jam'] ?: 0, 0, ',', '.'); ?></td>
        <td><?php echo htmlspecialchars($gaji['status'], ENT_QUOTES, 'UTF-8'); ?></td>
        <td>Rp <?php echo number_format($gaji['total'] ?: 0, 0, ',', '.'); ?></td>
    </tr>
</table>

<div class="button-container">
    <button onclick="window.location.href='admin_gaji.php'">Kembali</button>
    <button onclick="window.print()">Print</button>
</div>

</body>
</html>
