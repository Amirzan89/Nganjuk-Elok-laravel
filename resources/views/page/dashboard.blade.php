<?php
$tPath = app()->environment('local') ? '' : '/public/';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Disporabudpar - Nganjuk</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="{{ asset($tPath.'img/icon/utama/logo.png') }}" rel="icon">
  <!-- Google Fonts -->
  <!-- <link href="https://fonts.gstatic.com" rel="preconnect"> -->
  <link
    href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Jost:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
    rel="stylesheet">
  <!-- Vendor CSS Files -->
  <link href="{{ asset($tPath.'assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset($tPath.'assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
  <link href="{{ asset($tPath.'assets/vendor/simple-datatables/style.css') }}" rel="stylesheet">


  <!-- Template Main CSS File -->
  <link href="{{ asset($tPath.'assets/css/event.css') }}" rel="stylesheet">

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
    var email = "{{ $userAuth['email'] }}";
    var number = "{{ $userAuth['number'] }}";
    var role = "{{ $userAuth['role'] }}";
  </script>
  <!-- ======= Header ======= -->
  <header id="header" class="header fixed-top d-flex align-items-center">
    @include('component.header')
  </header><!-- End Header -->

  <!-- ======= Sidebar ======= -->
  <aside id="sidebar" class="sidebar">
    <ul class="sidebar-nav" id="sidebar-nav">
      @php
        $nav = 'dashboard';
      @endphp
      @include('component.sidebar')
    </ul>
  </aside><!-- End Sidebar-->

  <main id="main" class="main">

    <div class="pagetitle">
      <h1>Beranda</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item active">Beranda</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
      <div class="row">
        <div class="col-lg-12">
          <div class="row">
            <div class="col-lg-6 col-md-4">
              <div class="card success-card revenue-card"><a href="/admin">
                  <div class="card-body">
                    <h5 class="card-title">Daftar Admin</h5>
                    <div class="d-flex align-items-center">
                      <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                        <i class="bi bi-people-fill"></i>
                      </div>
                      <div class="ps-3"> 
                        <h4> {{ $totalAdmin }}</h4>
                      </div>
                    </div>
                  </div>
                </a>
              </div>
            </div>
            <div class="col-lg-6 col-md-4">
              <div class="card success-card revenue-card"><a href="/pengguna">
                  <div class="card-body">
                    <h5 class="card-title">Daftar Pengguna</h5>
                    <div class="d-flex align-items-center">
                      <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                        <i class="bi bi-people-fill"></i>
                      </div>
                      <div class="ps-3">
                        <h4> {{ $totalPengguna }}</h4>
                      </div>
                    </div>
                </a>
              </div>
            </div>

          </div>
        </div>

        </div class="row">
      <div class="col-lg-4 col-xl-8">
          <div class="card">
            <div class="card-body mt-2 mb-5">
              <h5 class="card-title mt-3 "><strong>Kalender Peminjaman Tempat</strong></h5>
              @include('component.dynamic-full-calendar')
            </div>
          </div>
        </div>
        
        <div class="col-md-4">
          <!-- Recent Activity -->
          <div class="card">
            <div class="card-body mt-3 mb-3">
              <h5 class="card-title mb-3 "><strong>Pengajuan Terbaru </strong><span>| Hari Ini</span></h5>

              <div class="activity">

                <div class="activity-item d-flex">
                  <div class="activite-label">
                    <?php echo date('d M Y') ?>
                  </div>
                  <i class='bi bi-circle-fill activity-badge text-success align-self-start'></i>
                  <div class="activity-content">
                    <a href="/event/pengajuan" class="fw-bold text-dark">
                      <h6><strong>Kelola Event</strong></h6>
                      {{ $totalEvent }} notifikasi
                    </a>
                  </div>
                </div><!-- End activity item-->

                <div class="activity-item d-flex">
                  <div class="activite-label">
                    <?php echo date('d M Y') ?>
                  </div>
                  <i class='bi bi-circle-fill activity-badge text-danger align-self-start'></i>
                  <div class="activity-content">
                    <a href="/tempat/pengajuan" class="fw-bold text-dark">
                      <h6><strong>Peminjaman Tempat</strong></h6>
                      {{ $totalSewa }} notifikasi
                    </a>
                  </div>
                </div><!-- End activity item-->

                <div class="activity-item d-flex">
                  <div class="activite-label">
                    <?php echo date('d M Y') ?>
                  </div>
                  <i class='bi bi-circle-fill activity-badge text-primary align-self-start'></i>
                  <div class="activity-content">
                    <a href="/seniman/pengajuan" class="fw-bold text-dark">
                      <h6><strong>Regitrasi Nomor Induk Seniman</strong></h6>
                      {{ $totalSeniman }} notifikasi
                    </a>
                  </div>
                </div><!-- End activity item-->

                <div class="activity-item d-flex">
                  <div class="activite-label">
                    <?php echo date('d M Y') ?>
                  </div>
                  <i class='bi bi-circle-fill activity-badge text-primary align-self-start'></i>
                  <div class="activity-content">
                    <a href="/seniman/perpanjangan" class="fw-bold text-dark">
                      <h6><strong>Perpanjang Nomor Induk Seniman</strong></h6>
                      {{ $totalPerpanjangan }} notifikasi
                    </a>
                  </div>
                </div><!-- End activity item-->

                <div class="activity-item d-flex">
                  <div class="activite-label">
                    <?php echo date('d M Y') ?>
                  </div>
                  <i class='bi bi-circle-fill activity-badge text-warning align-self-start'></i>
                  <div class="activity-content">
                    <a href="/pentas/pengajuan" class="fw-bold text-dark">
                      <h6><strong>Surat Advis</strong></h6>
                      {{ $totalPentas }} notifikasi
                    </a>
                  </div>
    </div>
        
                </div><!-- End activity item-->
              </div>

            </div>
          </div>
        </div>
      </div>
      </div>
      </div>
    </section>



  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <footer id="footer" class="footer">
    @include('component.footer')
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