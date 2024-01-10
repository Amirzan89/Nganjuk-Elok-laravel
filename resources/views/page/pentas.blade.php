<?php
$tPath = app()->environment('local') ? '' : '/public/';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta content="width=device-width, initial-scale=1.0" name="viewport" />

  <title>Disporabudpar - Nganjuk</title>
  <meta content="" name="description" />
  <meta content="" name="keywords" />

  <!-- Favicons -->
  <link href="{{ asset($tPath.'img/icon/utama/logo.png') }}" rel="icon">
  <link
    href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Jost:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
    rel="stylesheet" />
  <!-- Vendor CSS Files -->
  <link href="{{ asset($tPath.'assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" />
  <link href="{{ asset($tPath.'assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet" />
  <link href="{{ asset($tPath.'assets/vendor/simple-datatables/style.css') }}" rel="stylesheet" />

  <!-- Template Main CSS File -->
  <link href="{{ asset($tPath.'assets/css/nomor-induk.css') }}" rel="stylesheet" />
</head>

<body>
  <script>
		var csrfToken = "<?php echo $csrf ?>";
    var email = "<?php echo $userAuth['email'] ?>";
    var idUser = "<?php echo $userAuth['id_user'] ?>";
    var number = "<?php echo $userAuth['number'] ?>";
    var role = "<?php echo $userAuth['role'] ?>";
	</script>
  <!-- ======= Header ======= -->
  <header id="header" class="header fixed-top d-flex align-items-center">
    <?php include(__DIR__.'/header.php');
        ?>
  </header>
  <!-- End Header -->

  <!-- ======= Sidebar ======= -->
  <aside id="sidebar" class="sidebar">
    <ul class="sidebar-nav" id="sidebar-nav">
      <?php
        $nav = 'pentas'; 
        include(__DIR__.'/sidebar.php');
      ?>
    </ul>
  </aside>
  <!-- End Sidebar-->

  <main id="main" class="main">
    <div class="pagetitle">
      <h1>Kelola Pentas</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/dashboard.php">Beranda</a></li>
          <li class="breadcrumb-item active">Kelola Pentas</li>
        </ol>
      </nav>
    </div>
    <!-- End Page Title -->

    <section class="section dashboard">
    <div class="row">
        <div class="col-lg-12">
          <div class="row">
            <div class="row">
              <div class="col-xxl-4 col-md-4">
                <div class="card success-card revenue-card"><a href="/pentas/formulir.php">
                    <div class="card-body">
                      <h5 class="card-title">Formulir</h5>
                      <div class="d-flex align-items-center">
                        <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                          <i class="bi bi-file-earmark-text-fill"></i>
                        </div>
                      </div>
                  </a>
                </div>
              </div>
            </div>
            <div class="col-xxl-4 col-md-4">
              <div class="card success-card revenue-card"><a href="/pentas/pengajuan.php">
                  <div class="card-body">
                    <h5 class="card-title">Verifikasi Pengajuan</h5>
                    <div class="d-flex align-items-center">
                      <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                        <i class="bi bi-bell-fill"></i>
                      </div>
                      <div class="ps-3">
                        <?php
                        $sql  = mysqli_query($conn, "SELECT COUNT(*) AS total FROM surat_advis WHERE status = 'diajukan' OR status = 'proses'");
                        $data = mysqli_fetch_assoc($sql);
                        echo "<h4>" . $data['total'] . "</h4>";
                        ?>
                      </div>
                    </div>
                </a>
              </div>
            </div>
          </div>
          <div class="col-xxl-4 col-md-4">
            <div class="card success-card revenue-card"><a href="/pentas/riwayat.php">
                <div class="card-body">
                  <h5 class="card-title">Riwayat</h5>
                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bi bi-clock-fill"></i>
                    </div>
                    <div class="ps-3">
                      <?php
                      $sql  = mysqli_query($conn, "SELECT COUNT(*) AS total FROM surat_advis WHERE status = 'diterima' OR status = 'ditolak'");
                      $data = mysqli_fetch_assoc($sql);
                      echo "<h4>" . $data['total'] . "</h4>";
                      ?>
                    </div>
                  </div>
                </div>
              </a>
            </div>
          </div>
        </div>
      </div>
      <!-- <div class="row">
      <div class="col-lg-12">
            <div class="row">
              <div class="col-xxl-4 col-md-4">
                  <div class="card success-card revenue-card">
                      <div class="card-body">
                          <h5 class="card-title">Pengajuan</h5>
                          <div class="d-flex align-items-center">
                              <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                  <i class="bi bi-bell-fill"></i>
                              </div>
                              <div class="ps-3">
                                <?php 
                                  // $sql  = mysqli_query($conn, "SELECT COUNT(*) AS total FROM surat_advis WHERE status = 'diajukan' OR status = 'proses'");
                                  // $data = mysqli_fetch_assoc($sql);
                                  // echo "<h4>".$data['total']."</h4>";
                                ?>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
              <div class="col-xxl-4 col-md-4">
                  <div class="card success-card revenue-card">
                      <div class="card-body">
                          <h5 class="card-title">Riwayat</h5>
                          <div class="d-flex align-items-center">
                              <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                  <i class="bi bi-clock-fill"></i>
                              </div>
                              <div class="ps-3">
                                <?php 
                                  //$sql  = mysqli_query($conn, "SELECT COUNT(*) AS total FROM surat_advis WHERE status = 'diajukan' OR status = 'proses'");
                                  //$data = mysqli_fetch_assoc($sql);
                                  //echo "<h4>".$data['total']."</h4>";
                                ?>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
            </div>
        </div>
        <div class="col-lg-12">
          <div class="row">
            <div class="col-xxl-4 col-md-4">
              <div class="card success-card revenue-card">
                <div class="card-body">
                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded d-flex align-items-center justify-content-center">
                      <i class="bi bi-file-earmark-text-fill"></i>
                    </div>
                    <div class="ps-1">
                      <h5 class="card-title"><a href="/pentas/formulir-advis.php">Formulir</a></h5>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xxl-4 col-md-4">
              <div class="card success-card revenue-card">
                <div class="card-body">
                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded d-flex align-items-center justify-content-center">
                      <i class="bi bi-bell-fill"></i>
                    </div>
                    <div class="ps-1">
                      <h5 class="card-title"><a href="/pentas/pengajuan.php">Verifikasi Pengajuan</a></h5>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xxl-4 col-md-4">
              <div class="card success-card revenue-card">
                <div class="card-body">
                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded d-flex align-items-center justify-content-center">
                      <i class="bi bi-clock-fill"></i>
                    </div>
                    <div class="ps-1">
                      <h5 class="card-title"><a href="/pentas/riwayat.php">Riwayat Pengajuan pentas</a></h5>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div> -->
    </section>
  </main>
  <!-- End #main -->

  <!-- ======= Footer ======= -->
  <footer id="footer" class="footer">
    <?php include(__DIR__.'/footer.php');
        ?>
  </footer>

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
      class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="{{ asset($tPath.'assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset($tPath.'assets/vendor/simple-datatables/simple-datatables.js') }}"></script>
  <script src="{{ asset($tPath.'assets/vendor/tinymce/tinymce.min.js') }}"></script>

  <!-- Template Main JS File -->
  <script src="{{ asset($tPath.'assets/js/main.js') }}"></script>
</body>

</html>