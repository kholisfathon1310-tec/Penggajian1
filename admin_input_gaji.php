<!-- MODAL INPUT GAJI -->
<div class="modal fade"
     id="inputGajiModal"
     tabindex="-1"
     aria-hidden="true">

    <div class="modal-dialog modal-lg">

        <div class="modal-content">

            <!-- HEADER -->
            <div class="modal-header">

                <h4 class="modal-title">

                    Input Gaji Karyawan

                </h4>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                </button>

            </div>

            <!-- BODY -->
            <div class="modal-body">

                <form action="simpan_gaji.php"
                      method="POST">

                    <!-- NIP -->
                    <div class="mb-3">

                        <label class="form-label">

                            NIP

                        </label>

                        <select class="form-control"
                                id="NIP"
                                name="NIP"
                                required>

                            <option value="">
                                Pilih NIP
                            </option>

                            <?php foreach ($employees as $employee): ?>

                                <option
                                    value="<?= htmlspecialchars($employee['NIP']); ?>"
                                    data-nama="<?= htmlspecialchars($employee['nama_user']); ?>"
                                    data-hak="<?= htmlspecialchars($employee['hak']); ?>">

                                    <?= htmlspecialchars($employee['NIP']); ?>

                                </option>

                            <?php endforeach; ?>

                        </select>

                    </div>

                    <!-- NAMA -->
                    <div class="mb-3">

                        <label class="form-label">

                            Nama

                        </label>

                        <input type="text"
                               class="form-control"
                               id="nama_user"
                               name="nama_user"
                               readonly
                               required>

                    </div>

                    <!-- POSISI -->
                    <div class="mb-3">

                        <label class="form-label">

                            Posisi

                        </label>

                        <input type="text"
                               class="form-control"
                               id="hak"
                               name="hak"
                               readonly
                               required>

                    </div>

                    <!-- PERIODE -->
                    <div class="mb-3">

                        <label class="form-label">

                            Periode

                        </label>

                        <select class="form-control"
                                name="periode"
                                required>

                            <option value="">
                                Pilih Periode
                            </option>

                            <option value="Januari 2026">
                                Januari 2026
                            </option>

                            <option value="Februari 2026">
                                Februari 2026
                            </option>

                            <option value="Maret 2026">
                                Maret 2026
                            </option>

                            <option value="April 2026">
                                April 2026
                            </option>

                        </select>

                    </div>

                    <!-- TANGGAL GAJI -->
                    <div class="mb-3">

                        <label class="form-label">

                            Tanggal Gaji

                        </label>

                        <input type="date"
                               class="form-control"
                               name="tanggal_gaji"
                               required>

                    </div>

                    <!-- GAJI POKOK -->
                    <div class="mb-3">

                        <label class="form-label">

                            Gaji Pokok

                        </label>

                        <input type="number"
                               class="form-control"
                               id="base_salary"
                               name="base_salary"
                               required>

                    </div>

                    <!-- BPJS -->
                    <div class="mb-3">

                        <label class="form-label">

                            Potongan BPJS

                        </label>

                        <input type="number"
                               class="form-control"
                               id="pot_BPJS"
                               name="pot_BPJS"
                               required>

                    </div>

                    <!-- TRANSPORTASI -->
                    <div class="mb-3">

                        <label class="form-label">

                            Transportasi

                        </label>

                        <input type="number"
                               class="form-control"
                               id="transportasi"
                               name="transportasi"
                               required>

                    </div>

                    <!-- POTONGAN ABSEN -->
                    <div class="mb-3">

                        <label class="form-label">

                            Potongan Absen

                        </label>

                        <input type="number"
                               class="form-control"
                               id="pot_absen"
                               name="pot_absen"
                               value="0">

                    </div>

                    <!-- LEMBUR -->
                    <div class="mb-3">

                        <label class="form-label">

                            Lembur

                        </label>

                        <select class="form-control"
                                id="lembur"
                                name="lembur"
                                required>

                            <option value="Tidak">
                                Tidak
                            </option>

                            <option value="Iya">
                                Iya
                            </option>

                        </select>

                    </div>

                    <!-- TOTAL -->
                    <div class="mb-3">

                        <label class="form-label">

                            Total Gaji

                        </label>

                        <input type="number"
                               class="form-control"
                               id="salary"
                               readonly>

                    </div>

                    <!-- FOOTER -->
                    <div class="modal-footer">

                        <button type="button"
                                class="btn btn-secondary"
                                data-bs-dismiss="modal">

                            Tutup

                        </button>

                        <button type="submit"
                                class="btn btn-primary">

                            Simpan Data

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</div>

<script>

document.addEventListener("DOMContentLoaded", function () {

    const nipSelect =
        document.getElementById('NIP');

    const namaInput =
        document.getElementById('nama_user');

    const hakInput =
        document.getElementById('hak');

    // ======================
    // AUTO FILL USER
    // ======================
    nipSelect.addEventListener('change', function () {

        const selected =
            this.options[this.selectedIndex];

        namaInput.value =
            selected.getAttribute('data-nama');

        hakInput.value =
            selected.getAttribute('data-hak');
    });

    // ======================
    // HITUNG TOTAL
    // ======================
    function hitungTotal() {

        const baseSalary =
            parseFloat(
                document.getElementById('base_salary').value
            ) || 0;

        const bpjs =
            parseFloat(
                document.getElementById('pot_BPJS').value
            ) || 0;

        const transportasi =
            parseFloat(
                document.getElementById('transportasi').value
            ) || 0;

        const potAbsen =
            parseFloat(
                document.getElementById('pot_absen').value
            ) || 0;

        const lembur =
            document.getElementById('lembur').value;

        const gajiLembur =
            (lembur === 'Iya')
            ? 50000
            : 0;

        const total =
            baseSalary
            - bpjs
            - potAbsen
            + transportasi
            + gajiLembur;

        document.getElementById('salary').value =
            total;
    }

    document.getElementById('base_salary')
        .addEventListener('input', hitungTotal);

    document.getElementById('pot_BPJS')
        .addEventListener('input', hitungTotal);

    document.getElementById('transportasi')
        .addEventListener('input', hitungTotal);

    document.getElementById('pot_absen')
        .addEventListener('input', hitungTotal);

    document.getElementById('lembur')
        .addEventListener('change', hitungTotal);
});

</script>
