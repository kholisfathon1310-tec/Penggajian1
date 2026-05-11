<?php
ob_start();

session_start();

require_once "config.php";

require_once "template/header.php";
require_once "template/sidebar.php";

// =====================
// LOGIN CHECK
// =====================
if (!isset($_SESSION['NIP'])) {

    header("Location: form_login.php");

    exit();
}

// =====================
// SESSION USER
// =====================
$nip =
    $_SESSION['NIP'];

$nama_user =
    $_SESSION['nama_user'];

// =====================
// AMBIL POSISI
// =====================
$stmt = $koneksi->prepare("
    SELECT hak
    FROM user
    WHERE NIP = :nip
");

$stmt->execute([
    ':nip' => $nip
]);

$posisi =
    $stmt->fetchColumn();

// =====================
// CEK ABSEN HARI INI
// =====================
$stmt = $koneksi->prepare("
    SELECT *
    FROM admin_absen
    WHERE NIP = :nip
    AND DATE(jam_masuk) = CURDATE()
");

$stmt->execute([
    ':nip' => $nip
]);

$data_today =
    $stmt->fetch(PDO::FETCH_ASSOC);

// =====================
// ABSEN MASUK
// =====================
if (
    $_SERVER['REQUEST_METHOD'] === 'POST'
    &&
    isset($_POST['absen_masuk'])
) {

    if ($data_today) {

        $message =
            "Kamu sudah absen hari ini!";

    } else {

        if (!empty($_FILES['image']['name'])) {

            $allowed =
                ['jpg', 'jpeg', 'png'];

            $ext =
                strtolower(
                    pathinfo(
                        $_FILES['image']['name'],
                        PATHINFO_EXTENSION
                    )
                );

            // =====================
            // VALIDASI FORMAT
            // =====================
            if (!in_array($ext, $allowed)) {

                $message =
                    "Format file harus JPG / JPEG / PNG!";

            }

            // =====================
            // VALIDASI SIZE
            // =====================
            elseif ($_FILES['image']['size'] > 2000000) {

                $message =
                    "Ukuran file maksimal 2MB!";

            }

            else {

                // =====================
                // FOLDER UPLOAD
                // =====================
                if (!is_dir("uploads")) {

                    mkdir(
                        "uploads",
                        0777,
                        true
                    );
                }

                // =====================
                // NAMA FILE
                // =====================
                $fileName =
                    uniqid()
                    . "."
                    . $ext;

                $path =
                    "uploads/"
                    . $fileName;

                // =====================
                // UPLOAD FILE
                // =====================
                if (
                    move_uploaded_file(
                        $_FILES['image']['tmp_name'],
                        $path
                    )
                ) {

                    // =====================
                    // INSERT ABSEN
                    // =====================
                    $stmt = $koneksi->prepare("
                        INSERT INTO admin_absen (

                            NIP,
                            nama_user,
                            posisi,
                            jam_masuk,
                            image,
                            lembur

                        ) VALUES (

                            :nip,
                            :nama_user,
                            :posisi,
                            NOW(),
                            :image,
                            'tidak'
                        )
                    ");

                    $stmt->execute([

                        ':nip' => $nip,

                        ':nama_user' => $nama_user,

                        ':posisi' => $posisi,

                        ':image' => $path
                    ]);

                    header("Location: absensi.php");

                    exit();

                } else {

                    $message =
                        "Upload gambar gagal!";
                }
            }

        } else {

            $message =
                "Silakan upload foto!";
        }
    }
}

// =====================
// ABSEN PULANG
// =====================
if (
    $_SERVER['REQUEST_METHOD'] === 'POST'
    &&
    isset($_POST['absen_pulang'])
) {

    $stmt = $koneksi->prepare("
        UPDATE admin_absen

        SET

            jam_keluar = NOW(),

            durasi_kerja =
                TIMEDIFF(
                    NOW(),
                    jam_masuk
                ),

            status_kehadiran = 'Hadir'

        WHERE NIP = :nip

        AND DATE(jam_masuk) = CURDATE()

        AND jam_keluar IS NULL
    ");

    $stmt->execute([
        ':nip' => $nip
    ]);

    if ($stmt->rowCount() > 0) {

        header("Location: absensi.php");

        exit();

    } else {

        $message =
            "Kamu belum absen masuk!";
    }
}
?>

<!-- =====================
     UI ABSENSI
===================== -->
<div class="container-fluid mt-4 d-flex justify-content-center">

    <div class="card shadow-sm"
         style="max-width: 400px; width:100%;">

        <div class="card-header text-center">

            <h4>
                Absensi Karyawan
            </h4>

        </div>

        <div class="card-body">

            <!-- USER -->
            <h6 class="text-center">

                Selamat datang,
                <?= htmlspecialchars($nama_user); ?>

            </h6>

            <!-- JAM -->
            <p class="text-center"
               id="jam">
            </p>

            <!-- MESSAGE -->
            <?php if (isset($message)): ?>

                <div class="alert alert-info">

                    <?= htmlspecialchars($message); ?>

                </div>

            <?php endif; ?>

            <!-- STATUS -->
            <?php if ($data_today): ?>

                <div class="alert alert-success text-center">

                    Sudah absen hari ini ✅

                </div>

            <?php endif; ?>

            <div class="mt-3">

                <!-- ABSEN MASUK -->
                <?php if (!$data_today): ?>

                <form method="POST"
                      enctype="multipart/form-data">

                    <input type="file"
                           name="image"
                           id="imageInput"
                           class="form-control mb-2"
                           required>

                    <!-- PREVIEW -->
                    <img id="preview"
                         style="width:100%; display:none; margin-bottom:10px;">

                    <button name="absen_masuk"
                            class="btn btn-success w-100">

                        Absen Masuk

                    </button>

                </form>

                <!-- ABSEN PULANG -->
                <?php elseif ($data_today && !$data_today['jam_keluar']): ?>

                <form method="POST">

                    <button name="absen_pulang"
                            class="btn btn-danger w-100">

                        Absen Pulang

                    </button>

                </form>

                <!-- SUDAH ABSEN -->
                <?php else: ?>

                <div class="alert alert-info text-center">

                    Kamu sudah absen masuk & pulang hari ini 👍

                </div>

                <?php endif; ?>

            </div>

        </div>

    </div>

</div>

<!-- =====================
     JS
===================== -->
<script>

// =====================
// JAM REALTIME
// =====================
function updateJam() {

    const now =
        new Date();

    document.getElementById("jam").innerHTML =

        now.toLocaleDateString()
        + " - "
        + now.toLocaleTimeString();
}

setInterval(updateJam, 1000);

updateJam();

// =====================
// PREVIEW GAMBAR
// =====================
document
.getElementById("imageInput")
?.addEventListener("change", function(e) {

    const file =
        e.target.files[0];

    if (file) {

        const preview =
            document.getElementById("preview");

        preview.src =
            URL.createObjectURL(file);

        preview.style.display =
            "block";
    }
});
</script>

<?php
require_once "template/footer.php";
?>