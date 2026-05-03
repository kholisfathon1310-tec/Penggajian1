
      <!-- Sidebar -->
      <div class="sidebar" data-background-color="dark">
        <div class="sidebar-logo">
          <!-- Logo Header -->
          <div class="logo-header" data-background-color="dark">
              <img
                src="jk/assets/img/sigap.png"
                alt="navbar brand"
                class="navbar-brand"
                height="160"
                style="margin-left: 1px; margin-top: 8px;"
              />
            </a>
            <div class="nav-toggle">
              <button class="btn btn-toggle toggle-sidebar">
                <i class="gg-menu-right"></i>
              </button>
              <button class="btn btn-toggle sidenav-toggler">
                <i class="gg-menu-left"></i>
              </button>
            </div>
            <button class="topbar-toggler more">
              <i class="gg-more-vertical-alt"></i>
            </button>
          </div>
          <!-- End Logo Header -->
        </div>
        <div class="sidebar-wrapper scrollbar scrollbar-inner">
          <div class="sidebar-content">
            <ul class="nav nav-secondary">
              <li class="nav-item active">
              <li class="nav-item">
              <a href="grafik_absensi.php">
              <i class="fas fa-tachometer-alt"></i>
                  <p>Dasboard</p>
                </a>
                <a href="staff.php">
                  <i class="fas fa-user"></i>
                  <p>Staff</p>
                </a>
                <a href="admin_cuti_utama.php">
                  <i class="fas fa-file"></i>
                  <p>Permohonan Cuti</p>
                </a>
              <a href="admin_absensi.php">
                  <i class="fas fa-file"></i>
                  <p>Absensi</p>
                </a>
                <a href="admin_gaji.php">
                  <i class="fas fa-money-bill"></i>
                  <p>Gaji</p>
                </a>
              <!-- Tombol Logout -->
              <a href="logout.php" onclick="return confirmLogout();">
                  <i class="fas fa-sign-out-alt"></i>
                  <p>Logout</p>
                </a>
                <!-- Script Konfirmasi -->
                 <script>
                 function confirmLogout() {
                  return confirm("Apakah Anda yakin ingin keluar?");
                  }
                </script>
              </li>
          </div>
        </div>
      </div>
      <!-- End Sidebar -->

      <div class="main-panel">
        <div class="main-header">
          <div class="main-header-logo">
            <!-- Logo Header -->
          
            <!-- End Logo Header -->
          </div>
        