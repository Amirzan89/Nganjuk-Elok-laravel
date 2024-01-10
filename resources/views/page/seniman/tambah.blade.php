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
  <link href="{{ asset($tPath.'assets/css/nomor-induk.css') }}" rel="stylesheet">

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
    <?php include(__DIR__.'/../header.php');
    ?>
  </header><!-- End Header -->

  <!-- ======= Sidebar ======= -->
  <aside id="sidebar" class="sidebar">
    <ul class="sidebar-nav" id="sidebar-nav">
      <?php 
      $nav = 'seniman';
      include(__DIR__.'/../sidebar.php');
      ?>
    </ul>
  </aside><!-- End Sidebar-->

  <main id="main" class="main">
<div class="pagetitle">
      <h1>Tambah Seniman Baru</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/dashboard.php">Beranda</a></li>
          <li class="breadcrumb-item"><a href="/seniman.php">Kelola Seniman</a></li>
          <li class="breadcrumb-item"><a href="/seniman/data_seniman.php">Data Seniman</a></li>
          <li class="breadcrumb-item active">Tambah Seniman Baru</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->
    <section class="section">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title"></h5>

              <form class="row g-3" action="/web/seniman/seniman.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id_user" value="<?php echo $userAuth['id_user'] ?>">
                <input type="hidden" name="keterangan" value="tambah">
                <div class="col-md-12">
                  <label for="nik" class="form-label">Nomor Induk Kependudukan</label>
                  <input type="text" class="form-control" id="nik" name="nik" placeholder="Masukkan Nomor Induk Kependudukan">
                </div>
                <br>
                <div class="col-md-12">
                  <label for="nama_seniman" class="form-label">Nama Lengkap</label>
                  <input type="text" class="form-control" id="nama_seniman" name="nama_seniman" placeholder="Masukkan Nama Lengkap sesuai KTP">
                </div>
                <br>
                <div class="col-mb-3 mt-0">
                  <label for="jenis_kelamin" class="col-md-12 pt-3 col-form-label">Jenis Kelamin</label>
                  <div class="col-md-6">
                    <select class="form-select" name="jenis_kelamin" aria-label="Default select example">
                      <option value="laki-laki">Laki-laki</option>
                      <option value="perempuan">Perempuan</option>
                    </select>
                  </div>
                </div>
                <br>
                <div class="col-md-8">
                  <label for="tempat_lahir" class="form-label">Tempat lahir</label>
                  <input type="text" class="form-control" name="tempat_lahir" id="tempat_lahir" placeholder="Masukkan Tempat Lahir">
                </div>
                <div class="col-md-4">
                  <label for="tanggal_lahir" class="form-label">Tanggal lahir</label>
                  <input type="date" name="tanggal_lahir" class="form-control" id="tanggal_lahir">
                </div>
                <br>
                <div class="col-md-12 ">
                  <label for="alamat_seniman" class="form-label">Alamat</label>
                  <textarea class="form-control" id="alamat_seniman" name="alamat_seniman" placeholder="Masukkan Alamat" style="height: 100px;"></textarea>
                </div>
                <br>
                <div class="col-md-12">
                  <label for="no_telpon" class="form-label">Nomor Telepon</label>
                  <input type="text" name="no_telpon" class="form-control" id="no_telpon" placeholder="Masukkan Nomor Telepon Aktif">
                </div>
                <br>
                <!-- <div class="col-mb-3 mt-0"> -->
                <div class="col-md-6">
                  <label for="jenis_kelamin" class="col-md-12 pt-3 col-form-label">Kecamatan</label>
                  <div class="col-md-6">
                    <select class="form-select" name="kecamatan" aria-label="Default select example">
                      <option value="">Pilih kecamatan</option>
                      <option value="bagor">Bagor</option>
                      <option value="baron">Baron</option>
                      <option value="berbek">Berbek</option>
                      <option value="gondang">Gondang</option>
                      <option value="jatikalen">Jatikalen</option>
                      <option value="kertosono">Kertosono</option>
                      <option value="lengkong">Lengkong</option>
                      <option value="loceret">Loceret</option>
                      <option value="nganjuk">Nganjuk</option>
                      <option value="ngetos">Ngetos</option>
                      <option value="ngluyu">Ngluyu</option>
                      <option value="ngronggot">Ngronggot</option>
                      <option value="pace">Pace</option>
                      <option value="patianrowo">Patianrowo</option>
                      <option value="prambon">Prambon</option>
                      <option value="rejoso">Rejoso</option>
                      <option value="sawahan">Sawahan</option>
                      <option value="sukomoro">Sukomoro</option>
                      <option value="tanjunganom">Tanjuangnom</option>
                      <option value="wilangan">Wilangan</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-6">
                  <label for="kategori_seniman" class="col-md-12 pt-3 col-form-label">Kategori Seniman</label>
                  <div class="col-md-6">
                    <select class="form-select" aria-label="Default select example" name="singkatan_kategori">
                      <option value="">Pilih kategori</option>
                      <?php foreach($kategoriDB as $kategori){ ?>
                      <option value="<?php echo $kategori['singkatan_kategori'] ?>"><?php echo ucfirst($kategori['nama_kategori'])?></option>
                      <?php }?>
                    </select>
                  </div>
                </div>
                <br>
                <div class="col-md-8">
                  <label for="nama_organisasi" class="form-label">Nama Organisasi</label>
                  <input type="text" name="nama_organisasi" class="form-control" id="nama_organisasi" placeholder="Masukkan Nama Organisasi">
                </div>
                <br>
                <div class="col-md-4">
                  <label for="jumlah_anggota" class="form-label">Jumlah Anggota</label>
                  <input type="number" name="jumlah_anggota" class="form-control" id="jumlah_anggota" placeholder="Masukkan Jumlah Anggota">
                </div>
                <br>
                <div class="col-12">
                  <label for="surat_keterangan" class="form-label">Surat Keterangan Desa</label>
                  <input type="file" name="surat_keterangan" class="form-file-input form-control" id="surat_keterangan" ">
                </div>
                <br>
                <div class=" col-12">
                  <label for="ktp_seniman" class="form-label">Foto Kartu Tanda Penduduk</label>
                  <input type="file" class="form-file-input form-control" id="ktp_seniman" name="ktp_seniman">
                </div>
                <br>
                <div class="col-12">
                  <label for="pass_foto" class="form-label">Pass Foto 3x4</label>
                  <input type="file" name="pass_foto" class="form-file-input form-control" id="pass_foto">
                </div>
                <div class="row mb-3 justify-content-end">
                <div class="col-sm-10 text-end"><br>
                  <a href="/seniman/data_seniman.php" class="btn btn-secondary" style="margin-right: 5px;">Kembali</a>
                  <button type="submit" class="btn btn-tambah" onclick="openTambah(<?php echo $seniman['id_seniman']?>)">Tambah</button>
                </div>
              </div>

              </form>
    </section>

  </main><!-- End #main -->
      <!-- start modal tambah -->
      <div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Konfirmasi tambah data seniman</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          Apakah anda yakin ingin menambahkan data seniman?
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <form action="/web/seniman/seniman.php" id="deleteForm" method="POST">
            <input type="hidden" name="_method" value="DELETE">
            <input type="hidden" name="id_user" value="<?php echo $userAuth['id_user'] ?>">
            <input type="hidden" name="id_tempat" id="inpTempat">
            <button type="submit" class="btn btn-tambah" name="hapusAdmin">Tambah</button>
          </form>
        </div>
      </div>
    </div>
  </div>
  <!-- end modal tambah -->

  <!-- ======= Footer ======= -->
  <footer id="footer" class="footer">
    <?php include(__DIR__.'/../footer.php');
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