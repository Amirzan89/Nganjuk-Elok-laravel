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
  @if(app()->environment('local'))
  <script>
      var tPath = '';
  </script>
  @else
  <script>
      var tPath = '/public/';
  </script>
  @endif
  <script>
    var csrfToken = "{{ csrf_token() }}";
  </script>
  <!-- ======= Header ======= -->
  <header id="header" class="header fixed-top d-flex align-items-center">
    @include('component.header')
  </header>
  <!-- End Header -->

  <!-- ======= Sidebar ======= -->
  <aside id="sidebar" class="sidebar">
    <ul class="sidebar-nav" id="sidebar-nav">
      @php
        $nav = 'event';
      @endphp
      @include('component.sidebar')
    </ul>
  </aside>
  <!-- End Sidebar-->

  <main id="main" class="main">
    <div class="pagetitle">
      <h1>Kelola Event</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/dashboard.php">Beranda</a></li>
          <li class="breadcrumb-item active">Kelola Event</li>
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
                <div class="card success-card revenue-card"><a href="/event/formulir.php">
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
              <div class="card success-card revenue-card"><a href="/event/pengajuan.php">
                  <div class="card-body">
                    <h5 class="card-title">Verifikasi Pengajuan</h5>
                    <div class="d-flex align-items-center">
                      <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                        <i class="bi bi-bell-fill"></i>
                      </div>
                      <div class="ps-3">
                        <?php
                        $sql = mysqli_query($conn, "SELECT COUNT(*) AS total FROM events WHERE status = 'diajukan' OR status = 'proses'");
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
            <div class="card success-card revenue-card"><a href="/event/riwayat.php">
                <div class="card-body">
                  <h5 class="card-title">Riwayat</h5>
                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bi bi-clock-fill"></i>
                    </div>
                    <div class="ps-3">
                      <?php
                      $sql = mysqli_query($conn, "SELECT COUNT(*) AS total FROM events WHERE status = 'diterima' OR status = 'ditolak'");
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
  <script src="<?php echo $tPath; ?>/public/assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script src="<?php echo $tPath; ?>/public/assets/vendor/simple-datatables/simple-datatables.js') }}"></script>
  <script src="<?php echo $tPath; ?>/public/assets/vendor/tinymce/tinymce.min.js') }}"></script>

  <!-- Template Main JS File -->
  <script src="<?php echo $tPath; ?>/public/assets/js/main.js') }}"></script>
</body>

</html>