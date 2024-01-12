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
  <link href="{{ asset($tPath.'assets/css/tempat.css') }}" rel="stylesheet">

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
      $nav = 'tempat';
      include(__DIR__.'/../sidebar.php');
      ?>
    </ul>
  </aside><!-- End Sidebar-->
    <main id="main" class="main">
    <div class="pagetitle">
            <h1>Data Tempat</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/dashboard.php">Beranda</a></li>
                    <li class="breadcrumb-item"><a href="/tempat.php">Kelola Tempat</a></li>
                    <li class="breadcrumb-item active">Data Tempat</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="section">
            <div class="row">
                <div class="col-lg-12">

                    <div class="card">
                        <div class="card-body">
                          <h4 class="card-title">Data Tempat</h4>
                          <a href="/tempat/tambah_tempat.php">
                            <button type="button" class="btn btn-primary">
                                <i class="bi bi-file-earmark-plus" style='font-size: 20px; font-weight: bold;'></i>   Tambah Tempat
                            </button>
                          </a>
                            <table class="table datatable">
                                <thead>
                                    <tr>
                                        <th class="col"><strong>No.</th>
                                        <th scope="col">Nama Tempat</th>
                                        <th scope="col">Alamat Tempat</th>
                                        <th scope="col">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                  <?php
                                    $query = mysqli_query($conn, "SELECT id_tempat, nama_tempat, alamat_tempat, deskripsi_tempat FROM list_tempat ");
                                    $no = 1;
                                    while ($tempat = mysqli_fetch_array($query)) {
                                  ?>
                                      <tr>
                                        <td><?php echo $no?></td>
                                        <td><?php echo $tempat['nama_tempat'] ?></td>
                                        <td><?php echo $tempat['alamat_tempat'] ?></td>
                                        <td>
                                          <a href="/tempat/detail_tempat.php?id_tempat=<?= $tempat['id_tempat'] ?>" class="btn btn-lihat"><i class="bi bi-eye-fill"></i>   Lihat</a>
                                          <a href="/tempat/edit_detail_tempat.php?id_tempat=<?= $tempat['id_tempat'] ?>" class="btn btn-edit"><i class="bi bi-pencil-fill"></i>   Edit</a>
                                          <button type="button" class="btn btn-tolak" onclick="openDelete(<?php echo $tempat['id_tempat']?>)"> <i class="bi bi-trash-fill"></i>   Hapus</button>
                                        </td>
                                      </tr>
                                    <?php $no++;
                                  } ?>
                                </tbody>
                            </table>
                            <div class="row mb-3 justify-content-end">
                                <div class="col-sm-10 text-end">
                                    <a href="../tempat.php" class="btn btn-secondary">Kembali</a>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>

    </main><!-- End #main -->
    <!-- start modal delete -->
  <div class="modal fade" id="modalDelete" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Konfirmasi hapus data tempat</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          Apakah anda yakin ingin menghapus data tempat?  
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <form action="/web/tempat/tempat.php" id="deleteForm" method="POST">
            <input type="hidden" name="_method" value="DELETE">
            <input type="hidden" name="id_user" value="<?php echo $userAuth['id_user'] ?>">
            <input type="hidden" name="id_tempat" id="inpTempat">
            <button type="submit" class="btn btn-tolak" name="hapusAdmin">Hapus</button>
          </form>
        </div>
      </div>
    </div>
  </div>
  <!-- end modal delete -->
  <!-- ======= Footer ======= -->
  <footer id="footer" class="footer">
    <?php include(__DIR__.'/../footer.php');
    ?>
  </footer>

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
  <script>
    var modal = document.getElementById('modalDelete');
    var deleteForm = document.getElementById('deleteForm');
    var inpTempat = document.getElementById('inpTempat');
    function openDelete(dataU){
      inpTempat.value = dataU;
      var myModal = new bootstrap.Modal(modal);
      myModal.show();
    }
  </script>
  <!-- Vendor JS Files -->
  <script src="{{ asset($tPath.'assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset($tPath.'assets/vendor/simple-datatables/simple-datatables.js') }}"></script>
  <script src="{{ asset($tPath.'assets/vendor/tinymce/tinymce.min.js') }}"></script>

  <!-- Template Main JS File -->
  <script src="{{ asset($tPath.'assets/js/main.js') }}"></script>

</body>

</html>