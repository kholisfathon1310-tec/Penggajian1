<?php
session_start();

require_once "config.php";

// ======================
// FORMAT RUPIAH
// ======================
function formatRupiah($angka)
{
    return "Rp " . number_format($angka, 0, ',', '.');
}

// ======================
// PROSES FORM
// ======================
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // ======================
    // AMBIL DATA FORM
    // ======================
    $NIP = $_POST['NIP'];
    $nama_user = $_POST['nama_user'];
    $tanggal_gaji = $_POST['tanggal_gaji'];
    $hak = $_POST['hak'];
    $periode = $_POST['periode'];

    $baseSalary = $_POST['base_salary'];
    $potBPJS = $_POST['pot_BPJS'];
    $transportasi = $_POST['transportasi'];
    $potAbsen = $_POST['pot_absen'];

    $lembur = $_POST['lembur'];

    // ======================
    // HITUNG LEMBUR
    // ======================
    $gajiLembur =
        ($lembur === "Iya")
        ? 50000
        : 0;

    // ======================
    // TOTAL GAJI
    // ======================
    $totalGaji =
        $baseSalary
        - $potBPJS
        - $potAbsen
        + $transportasi
        + $gajiLembur;

    try {

        // ======================
        // CEK NIP
        // ======================
        $stmt = $koneksi->prepare("
            SELECT COUNT(*)
            FROM admin_penggajian
            WHERE NIP = :nip
        ");

        $stmt->execute([
            ':nip' => $NIP
        ]);

        $exists = $stmt->fetchColumn();

        // ======================
        // UPDATE DATA
        // ======================
        if ($exists > 0) {

            $query = "
                UPDATE admin_penggajian
                SET
                    nama_user = :nama_user,
                    hak = :hak,
                    periode = :periode,
                    base_salary = :base_salary,
                    pot_BPJS = :pot_bpjs,
                    tanggal_gaji = :tanggal_gaji,
                    transportasi = :transportasi,
                    pot_absen = :pot_absen,
                    lembur = :lembur,
                    salary = :salary
                WHERE NIP = :nip
            ";

        } else {

            // ======================
            // INSERT DATA
            // ======================
            $query = "
                INSERT INTO admin_penggajian (
                    NIP,
                    nama_user,
                    hak,
                    periode,
                    base_salary,
                    pot_BPJS,
                    tanggal_gaji,
                    transportasi,
                    pot_absen,
                    lembur,
                    salary
                )
                VALUES (
                    :nip,
                    :nama_user,
                    :hak,
                    :periode,
                    :base_salary,
                    :pot_bpjs,
                    :tanggal_gaji,
                    :transportasi,
                    :pot_absen,
                    :lembur,
                    :salary
                )
            ";
        }

        // ======================
        // EXECUTE QUERY
        // ======================
        $stmt = $koneksi->prepare($query);

        $result = $stmt->execute([

            ':nip' => $NIP,
            ':nama_user' => $nama_user,
            ':hak' => $hak,
            ':periode' => $periode,
            ':base_salary' => $baseSalary,
            ':pot_bpjs' => $potBPJS,
            ':tanggal_gaji' => $tanggal_gaji,
            ':transportasi' => $transportasi,
            ':pot_absen' => $potAbsen,
            ':lembur' => $lembur,
            ':salary' => $totalGaji
        ]);

        // ======================
        // BERHASIL
        // ======================
        if ($result) {

            $_SESSION['message'] =
                "Data gaji berhasil disimpan.";

            $_SESSION['message_type'] =
                "success";

            header("Location: admin_gaji.php");

            exit();

        } else {

            echo "
            <div class='alert alert-danger'>

                Terjadi kesalahan saat menyimpan data.

            </div>
            ";
        }

    } catch (PDOException $e) {

        die("Terjadi kesalahan database: " . $e->getMessage());
    }
}
?>
