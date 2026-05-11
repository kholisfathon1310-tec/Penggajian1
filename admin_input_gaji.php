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
                onchange="window.location.href='admin_input_gaji.php?NIP='+this.value"
                required>

            <option value="">
                Pilih NIP
            </option>

            <?php foreach ($nips as $nip): ?>

                <option value="<?= $nip['NIP']; ?>"
                    <?= isset($NIP) && $NIP == $nip['NIP'] ? 'selected' : ''; ?>>

                    <?= $nip['NIP']; ?>

                </option>

            <?php endforeach; ?>

        </select>

    </div>

    <!-- NAMA -->
    <div class="mb-3">

        <label class="form-label">
            Nama Karyawan
        </label>

        <input type="text"
               class="form-control"
               name="nama_user"
               value="<?= $name ?>"
               readonly>

    </div>

    <!-- POSISI -->
    <div class="mb-3">

        <label class="form-label">
            Posisi
        </label>

        <input type="text"
               class="form-control"
               name="hak"
               value="<?= $position ?>"
               readonly>

    </div>

    <!-- PERIODE -->
    <div class="mb-3">

        <label class="form-label">
            Periode
        </label>

        <input type="text"
               class="form-control"
               name="periode"
               required>

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
               name="pot_BPJS"
               required>

    </div>

    <!-- TRANSPORT -->
    <div class="mb-3">

        <label class="form-label">
            Transportasi
        </label>

        <input type="number"
               class="form-control"
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
               name="pot_absen"
               required>

    </div>

    <!-- LEMBUR -->
    <div class="mb-3">

        <label class="form-label">
            Lembur
        </label>

        <select class="form-control"
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

    <!-- BUTTON -->
    <div class="d-flex justify-content-between">

        <a href="admin_gaji.php"
           class="btn btn-secondary">

            Kembali

        </a>

        <button type="submit"
                class="btn btn-primary">

            Simpan

        </button>

    </div>

</form>