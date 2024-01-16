@php
$tPath = app()->environment('local') ? '' : '/public/';
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Disporabudpar - Nganjuk</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="{{ asset($tPath.'assets/img/favicon.png') }}" rel="icon">
  <link href="{{ asset($tPath.'assets/img/apple-touch-icon.png') }}" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <!-- <link href="https://fonts.gstatic.com" rel="preconnect"> -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Jost:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
  <!-- Vendor CSS Files -->
  <link href="{{ asset($tPath.'assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset($tPath.'assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
  <link href="{{ asset($tPath.'assets/vendor/simple-datatables/style.css') }}" rel="stylesheet">


  <!-- Template Main CSS File -->
  <link href="{{ asset($tPath.'assets/css/nomor-induk.css') }}" rel="stylesheet">

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
  </header><!-- End Header -->

  <!-- ======= Sidebar ======= -->
  <aside id="sidebar" class="sidebar">
    <ul class="sidebar-nav" id="sidebar-nav">
      @php
        $nav = 'seniman';
      @endphp
      @include('component.sidebar')
    </ul>
  </aside><!-- End Sidebar-->

  <main id="main" class="main">
    <div class="pagetitle">
      <h1>Formulir Pendaftaran</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/dashboard">Beranda</a></li>
          <li class="breadcrumb-item"><a href="/seniman">Kelola Seniman</a></li>
          <li class="breadcrumb-item active">Formulir Pendaftaran</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->
    <section class="section">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title mb-3 mt-3"> Formulir Registrasi Nomor Induk Seniman</h5>

              <!-- Multi Columns Form -->
              <form class="row g-3">
                <div class="col-md-12">
                  <label for="nik" class="form-label">Nomor Induk Kependudukan</label>
                  <input type="text" class="form-control" id="nik" readonly>
                </div>
                <div class="col-md-12">
                  <label for="nama_seniman" class="form-label">Nama Lengkap</label>
                  <input type="text" class="form-control" id="nama_seniman" readonly>
                </div>
                <div class="col-mb-3 mt-0">
                  <label for="jenis_kelamin" class="col-md-12 pt-3 col-form-label">Jenis Kelamin</label>
                  <div class="col-md-6">
                    <select class="form-select" aria-label="Default select example">
                      <option selected>Pilih Jenis Kelamin</option>
                      <option value="laki-laki">Laki-laki</option>
                      <option value="perempuan">Perempuan</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-8">
                  <label for="tempat_lahir" class="form-label">Tempat lahir</label>
                  <input type="text" class="form-control" id="tempat_lahir" readonly>
                </div>
                <div class="col-md-4">
                  <label for="tanggal_lahir" class="form-label">Tanggal lahir</label>
                  <input type="date" class="form-control" id="tanggal_lahir" readonly>
                </div>
                <div class="col-md-6 mt-0">
                  <label for="kecamatan" class="col-md-12 pt-3 col-form-label">Kecamatan</label>
                  <select class="form-select" aria-label="Default select example">
                    <option selected>Pilih Kecamatan</option>
                    <option value="laki-laki">Nganjuk</option>
                    <option value="ngetos">Ngetos</option>
                    <option value="berbek">Berbek</option>
                    <option value="loceret">Loceret</option>
                    <option value="pace">Pace</option>
                    <option value="prambon">Prambon</option>
                    <option value="ngronggot">Ngronggot</option>
                    <option value="kertosono">Kertosono</option>
                    <option value="patianrowo">Patianrowo</option>
                    <option value="ngetos">Ngetos</option>
                    <option value="baron">Baron</option>
                    <option value="tanjunganom">Tanjunganom</option>
                    <option value="sukomoro">Sukomoro</option>
                    <option value="bagor">Bagor</option>
                    <option value="wilangan">Wilangan</option>
                    <option value="rejoso">Rejoso</option>
                    <option value="gondang">Gondang</option>
                    <option value="ngluyu">Ngluyu</option>
                    <option value="lengkong">Lengkong</option>
                    <option value="katikalen">Jatikalen</option>
                  </select>
                </div>
                <div class="col-md-12 ">
                  <label for="alamat_seniman" class="form-label">Alamat</label>
                  <textarea class="form-control" id="alamat_seniman" style="height: 100px;" readonly></textarea>
                </div>
                <div class="col-md-12">
                  <label for="no_telpon" class="form-label">Nomor Telepon</label>
                  <input type="text" class="form-control" id="no_telpon" readonly>
                </div>
                <div class="col-mb-3 mt-0">
                  <label for="kategori_seniman " class="col-md-12 pt-3 col-form-label">Kategori Seniman</label>
                  <div class="col-md-6">
                    <select class="form-select" aria-label="Default select example">
                      <option selected>Pilih Kategori Seniman</option>
                      <option value="1">Campursari</option>
                      <option value="2">Dalang</option>
                      <option value="3">Jaranan</option>
                      <option value="4">Karawitan</option>
                      <option value="5">MC</option>
                      <option value="6">Ludruk</option>
                      <option value="7">Organisasi Kesenian Musik</option>
                      <option value="8">Organisasi</option>
                      <option value="9">Pramugari Tayup</option>
                      <option value="10">Sanggar</option>
                      <option value="11">Sinden</option>
                      <option value="12">Vocalis</option>
                      <option value="13">Wiranggono</option>
                      <option value="14">Barongsai</option>
                      <option value="15">Ketoprak</option>
                      <option value="16">Pataji</option>
                      <option value="17">Reog</option>
                      <option value="18">Taman Hiburan Rakyat</option>
                      <option value="19">Pelawak</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-8">
                  <label for="nama_organisasi" class="form-label">Nama Organisasi</label>
                  <input type="text" class="form-control" id="nama_organisasi" readonly>
                </div>
                <div class="col-md-4">
                  <label for="jumlah_anggota" class="form-label">Jumlah Anggota</label>
                  <input type="number" class="form-control" id="jumlah_anggota" readonly>
                </div>
                <div class="col-md-12">
                  <label for="surat_keterangan" class="form-label">Surat Keterangan</label>
                  <input type="file" class="form-file-input form-control" id="surat_keterangan" disabled>
                </div>
                <div class="col-md-12">
                  <label for="ktp_seniman" class="form-label">Foto Kartu Tanda Penduduk</label>
                  <input type="file" class="form-file-input form-control" id="ktp_seniman" disabled>
                </div>
                <div class="col-md-12">
                  <label for="pass_foto" class="form-label">Pas Foto 3x4</label>
                  <input type="file" class="form-file-input form-control" id="pass_foto" disabled>
                </div>
              </form>
              <br><br>
              <div class="col-lg-12 col-md-4">
                <div class="card success-card revenue-card">
                  <div class="card-body">
                    <h6><strong>DENGAN PENGAJUAN FORMULIR INI, ANDA MENYETUJUI HAL- HAL BERIKUT :</strong></h6>
                    <br>
                    <h6>
                      <ol start="1">
                        <li>Dokumen yang disertakan sudah sesuai dengan persyaratan yang ada. </li>
                        <li>Nomor Induk Seniman hanya berlaku per 31 Desember tiap tahunnya, 
                          silahkan lakukan perpanjangan setelahnya.</li>
                        <li>Apabila data tidak setujui silahkan lakukan pengajuan ulang.</li>
                      </ol>
                    </h6>
                  </div>
                </div>
              </div>
              <br>
              <div class="row mb-3 justify-content-end">
                <div class="col-sm-10 text-end">
                  <a href="../seniman" class="btn btn-secondary">Kembali</a>
                </div>
              </div>
    </section> 

  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <footer id="footer" class="footer">
    @include('component.footer')
  </footer>

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="{{ asset($tPath.'assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset($tPath.'assets/vendor/simple-datatables/simple-datatables.js') }}"></script>
  <script src="{{ asset($tPath.'assets/vendor/tinymce/tinymce.min.js') }}"></script>

  <!-- Template Main JS File -->
  <script src="{{ asset($tPath.'assets/js/main.js') }}"></script>

</body>

</html>