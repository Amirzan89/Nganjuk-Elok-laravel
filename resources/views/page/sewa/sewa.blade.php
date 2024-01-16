@php
$tPath = app()->environment('local') ? '' : '/public/';
@endphp
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
    var email = "{{ $userAuth['email'] }}";
    var number = "{{ $userAuth['number'] }}";
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
        $nav = 'tempat';
      @endphp
      @include('component.sidebar')
    </ul>
  </aside>
  <!-- End Sidebar-->

  <main id="main" class="main">
    <div class="pagetitle">
      <h1>Kelola Tempat</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/dashboard">Beranda</a></li>
          <li class="breadcrumb-item active">Kelola Tempat</li>
        </ol>
      </nav>
    </div>
    <!-- End Page Title -->

    <section class="section dashboard">
      <div class="row">
        <div class="col-lg-12">
          <div class="row">
            <div class="col-xxl-4 col-md-4">
              <div class="card success-card revenue-card"><a href="/sewa/formulir">
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
            <div class="card success-card revenue-card"><a href="/sewa/pengajuan">
                <div class="card-body">
                  <h5 class="card-title">Verifikasi Peminjaman</h5>
                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bi bi-bell-fill"></i>
                    </div>
                    <div class="ps-3">
                      <h4> {{ $totalPengajuan }}</h4>
                    </div>
                  </div>
              </a>
            </div>
          </div>
        </div>
        <div class="col-xxl-4 col-md-4">
          <div class="card success-card revenue-card"><a href="/sewa/riwayat">
              <div class="card-body">
                <h5 class="card-title">Riwayat</h5>
                <div class="d-flex align-items-center">
                  <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                    <i class="bi bi-clock-fill"></i>
                  </div>
                  <div class="ps-3">
                    <h4> {{ $totalRiwayat }}</h4>
                  </div>
                </div>
              </div>
            </a>
          </div>
        </div>
        <div class="col-xxl-4 col-md-4">
          <div class="card success-card revenue-card"><a href="/tempat/data">
              <div class="card-body">
                <h5 class="card-title">Data Tempat</h5>
                <div class="d-flex align-items-center">
                  <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                    <i class="bi bi-folder-fill"></i>
                  </div>
                  <div class="ps-3">
                    <h4> {{ $totalTempat }}</h4>
                  </div>
                </div>
            </a>
          </div>
        </div>
      </div>
    </section>
  </main>
  <!-- End #main -->

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