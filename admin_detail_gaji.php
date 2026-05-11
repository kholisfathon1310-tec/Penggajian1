<?php
session_start();

require_once 'config.php';

// ======================
// KONEKSI DATABASE
// ======================
$conn = $koneksi;

// ======================
// AMBIL NIP
// ======================
$NIP = isset($_GET['NIP'])
    ? $_GET['NIP']
    : '';

// ======================
// QUERY DATA
// ======================
$stmt = $conn->prepare("
    SELECT *
    FROM admin_penggajian
    WHERE NIP = :nip
");

$stmt->execute([
    ':nip' => $NIP
]);

$gaji =
    $stmt->fetch(PDO::FETCH_ASSOC);

// ======================
// VALIDASI DATA
// ======================
if (!$gaji) {

    echo "
    <div class='info-container'>

        <p>
            Data gaji untuk NIP
            <strong>" .
            htmlspecialchars($NIP, ENT_QUOTES, 'UTF-8') .
            "</strong>
            tidak ditemukan.
        </p>

        <button onclick=\"window.location.href='admin_gaji.php'\">
            Kembali
        </button>

    </div>
    ";

    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>
    Detail Gaji Karyawan
</title>

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

table,
th,
td {
    border: 1px solid #ddd;
}

th,
td {
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

/* PRINT */
@media print {

    body {
        font-family: "Courier New", Courier, monospace;
        font-size: 12px;
    }

    .button-container {
        display: none;
    }

    .info-container {
        margin: 0;
        padding: 0;
    }

    .header h2 {
        font-size: 28px;
    }

    table {
        font-size: 12px;
        border: 1px solid #333;
    }

    th,
    td {
        padding: 8px;
    }

    th {
        background-color: #ddd;
    }
}

</style>

</head>

<body>

<div class="header">

    <h2>
        SLIP GAJI KARYAWAN
    </h2>

</div>

<div class="info-container">

    <p>

        <strong>NIP:</strong>

        <?= htmlspecialchars($gaji['NIP'], ENT_QUOTES, 'UTF-8'); ?>

    </p>

    <p>

        <strong>Nama Karyawan:</strong>

        <?= htmlspecialchars($gaji['nama_user'], ENT_QUOTES, 'UTF-8'); ?>

    </p>

    <p>

        <strong>Posisi:</strong>

        <?= htmlspecialchars($gaji['hak'], ENT_QUOTES, 'UTF-8'); ?>

    </p>

    <p>

        <strong>Periode:</strong>

        <?= htmlspecialchars($gaji['periode'], ENT_QUOTES, 'UTF-8'); ?>

    </p>

</div>

<table>

    <tr>

        <th>Periode</th>
        <th>Tanggal Gaji</th>
        <th>Salary</th>
        <th>Potongan BPJS</th>
        <th>Potongan Absen</th>
        <th>Transportasi</th>
        <th>Lembur</th>
        <th>Total</th>

    </tr>

    <tr>

        <td>

            <?= htmlspecialchars($gaji['periode']); ?>

        </td>

        <td>

            <?= htmlspecialchars($gaji['tanggal_gaji']); ?>

        </td>

        <td>

            Rp
            <?= number_format($gaji['base_salary'] ?: 0, 0, ',', '.'); ?>

        </td>

        <td>

            Rp
            <?= number_format($gaji['pot_BPJS'] ?: 0, 0, ',', '.'); ?>

        </td>

        <td>

            Rp
            <?= number_format($gaji['pot_absen'] ?: 0, 0, ',', '.'); ?>

        </td>

        <td>

            Rp
            <?= number_format($gaji['transportasi'] ?: 0, 0, ',', '.'); ?>

        </td>

        <td>

            <?= htmlspecialchars($gaji['lembur']); ?>

        </td>

        <td>

            Rp
            <?= number_format($gaji['salary'] ?: 0, 0, ',', '.'); ?>

        </td>

    </tr>

</table>

<div class="button-container">

    <button onclick="window.location.href='admin_gaji.php'">

        Kembali

    </button>

    <button onclick="window.print()">

        Print

    </button>

</div>

</body>
</html>
