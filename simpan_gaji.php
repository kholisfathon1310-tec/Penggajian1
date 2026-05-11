<?php
session_start();

require_once "config.php";

// ======================
// CEK METHOD
// ======================
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    try {

        // ======================
        // AMBIL DATA FORM
        // ======================
        $NIP =
            $_POST['NIP'];

        $nama_user =
            $_POST['nama_user'];

        $hak =
            $_POST['hak'];

        $periode =
            $_POST['periode'];

        $tanggal_gaji =
            isset($_POST['tanggal_gaji'])
            ? $_POST['tanggal_gaji']
            : date('Y-m-d');

        $base_salary =
            $_POST['base_salary'];

        $pot_BPJS =
            $_POST['pot_BPJS'];

        $transportasi =
            $_POST['transportasi'];

        $pot_absen =
            $_POST['pot_absen'];

        $lembur =
            $_POST['lembur'];

        // ======================
        // HITUNG LEMBUR
        // ======================
        $gajiLembur =
            ($lembur === "Iya")
            ? 50000
            : 0;

        // ======================
        // HITUNG TOTAL GAJI
        // ======================
        $salary =
            $base_salary
            - $pot_BPJS
            - $pot_absen
            + $transportasi
            + $gajiLembur;

        // ======================
        // CEK DATA SUDAH ADA
        // ======================
        $cek = $koneksi->prepare("
            SELECT COUNT(*)
            FROM admin_penggajian
            WHERE NIP = :NIP
        ");

        $cek->execute([
            ':NIP' => $NIP
        ]);

        $exists =
            $cek->fetchColumn();

        // ======================
        // UPDATE
        // ======================
        if ($exists > 0) {

            $query = "
                UPDATE admin_penggajian
                SET

                    nama_user = :nama_user,
                    hak = :hak,
                    tanggal_gaji = :tanggal_gaji,
                    periode = :periode,
                    base_salary = :base_salary,
                    pot_BPJS = :pot_BPJS,
                    transportasi = :transportasi,
                    pot_absen = :pot_absen,
                    lembur = :lembur,
                    salary = :salary

                WHERE NIP = :NIP
            ";

        } else {

            // ======================
            // INSERT
            // ======================
            $query = "
                INSERT INTO admin_penggajian (

                    NIP,
                    nama_user,
                    hak,
                    tanggal_gaji,
                    periode,
                    base_salary,
                    pot_BPJS,
                    transportasi,
                    pot_absen,
                    lembur,
                    salary

                ) VALUES (

                    :NIP,
                    :nama_user,
                    :hak,
                    :tanggal_gaji,
                    :periode,
                    :base_salary,
                    :pot_BPJS,
                    :transportasi,
                    :pot_absen,
                    :lembur,
                    :salary
                )
            ";
        }

        // ======================
        // PREPARE
        // ======================
        $stmt =
            $koneksi->prepare($query);

        // ======================
        // EXECUTE
        // ======================
        $result = $stmt->execute([

            ':NIP' => $NIP,

            ':nama_user' => $nama_user,

            ':hak' => $hak,

            ':tanggal_gaji' => $tanggal_gaji,

            ':periode' => $periode,

            ':base_salary' => $base_salary,

            ':pot_BPJS' => $pot_BPJS,

            ':transportasi' => $transportasi,

            ':pot_absen' => $pot_absen,

            ':lembur' => $lembur,

            ':salary' => $salary
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
            <script>

                alert('Gagal menyimpan data!');

                window.location.href='admin_input_gaji.php';

            </script>
            ";
        }

    } catch (PDOException $e) {

        die(
            "Terjadi kesalahan database: "
            . $e->getMessage()
        );
    }

} else {

    header("Location: admin_input_gaji.php");

    exit();
}
?>