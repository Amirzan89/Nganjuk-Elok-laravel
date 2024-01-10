<?php
require_once(__DIR__ . '/../web/koneksi.php');
require_once(__DIR__ . '/../web/authenticate.php');
require_once(__DIR__ . '/../env.php');
require_once(__DIR__ . '/../Date.php');
loadEnv();
$database = koneksi::getInstance();
$conn = $database->getConnection();
$userAuth = authenticate($_POST, [
  'uri' => $_SERVER['REQUEST_URI'],
  'method' => $_SERVER['REQUEST_METHOD']
], $conn);
if ($userAuth['status'] == 'error') {
  header('Location: /login.php');
} else {
  $userAuth = $userAuth['data'];
  if (!in_array($userAuth['role'], ['super admin', 'admin seniman', 'admin pentas'])) {
    echo "<script>alert('Anda bukan admin seniman !')</script>";
    echo "<script>window.location.href = '/dashboard.php';</script>";
    exit();
  }
  $tPath = ($_SERVER['APP_ENV'] == 'local') ? '' : $_SERVER['APP_FOLDER'];
  $csrf = $GLOBALS['csrf'];
}
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
  <link href="<?php echo $tPath; ?>/public/assets/img/favicon.png" rel="icon">
  <link href="<?php echo $tPath; ?>/public/assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <!-- <link href="https://fonts.gstatic.com" rel="preconnect"> -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Jost:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
  <!-- Vendor CSS Files -->
  <link href="<?php echo $tPath; ?>/public/assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="<?php echo $tPath; ?>/public/assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="<?php echo $tPath; ?>/public/assets/vendor/simple-datatables/style.css" rel="stylesheet">


  <!-- Template Main CSS File -->
  <link href="<?php echo $tPath; ?>/public/assets/css/tempat.css" rel="stylesheet">
  <link href="<?php echo $tPath; ?>/public/css/popup.css" rel="stylesheet">
  <style>
    .ui-datepicker-calendar {
      display: none;
    }

    .srcDate {
      float: right;
      padding: 10px;
    }

    .inp {
      padding: 6px;
      border: 1px solid #ccc;
      border-radius: 5px;
      font-size: 16px;
      width: 100%;
    }
  </style>
  <script src="<?php echo $tPath; ?>/public/assets/vendor/jquery/jquery.min.js"></script>
  <!-- <script>
    var $jq = jQuery.noConflict();
</script> -->
</head>

<body>
  <script>
    const domain = window.location.protocol + '//' + window.location.hostname + ":" + window.location.port;
    var csrfToken = "<?php echo $csrf ?>";
    var email = "<?php echo $userAuth['email'] ?>";
    var idUser = "<?php echo $userAuth['id_user'] ?>";
    var number = "<?php echo $userAuth['number'] ?>";
    var role = "<?php echo $userAuth['role'] ?>";
  </script>
  <!-- ======= Header ======= -->
  <header id="header" class="header fixed-top d-flex align-items-center">
    <?php include(__DIR__ . '/../header.php');
    ?>
  </header><!-- End Header -->

  <!-- ======= Sidebar ======= -->
  <aside id="sidebar" class="sidebar">
    <ul class="sidebar-nav" id="sidebar-nav">
      <?php
      $nav = 'pentas';
      include(__DIR__ . '/../sidebar.php');
      ?>
    </ul>
  </aside><!-- End Sidebar-->

  <main id="main" class="main">

    <div class="pagetitle">
      <h1>Pengajuan Pentas</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/dashboard.php">Beranda</a></li>
          <li class="breadcrumb-item"><a href="/pentas.php">Kelola Pentas</a></li>
          <li class="breadcrumb-item active">Pengajuan Pentas</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section">
      <div class="row">
        <div class="col-lg-12">

          <div class="card">
            <div class="card-body">
              <h5 class="card-title"></h5>
              <div class="srcDate">
                <div class="col-lg-12">
                  <div class="row">
                    <div class="col-lg-3">
                      <input type="text" name="" id="inpTahun" placeholder="Tahun" class="inp" value="<?php echo date('Y') ?>" oninput="tampilkanTahun()">
                    </div>
                    <div class="col-lg-5">
                      <select id="inpBulan" onchange="tampilkanBulan()" class="inp" value="<?php echo date('M')  ?>">
                        <option value="semua">semua</option>
                        <option value="1" <?php echo (date('m') == 1) ? 'selected' : ''; ?>>Januari</option>
                        <option value="2" <?php echo (date('m') == 2) ? 'selected' : ''; ?>>Februari</option>
                        <option value="3" <?php echo (date('m') == 3) ? 'selected' : ''; ?>>Maret</option>
                        <option value="4" <?php echo (date('m') == 4) ? 'selected' : ''; ?>>April</option>
                        <option value="5" <?php echo (date('m') == 5) ? 'selected' : ''; ?>>Mei</option>
                        <option value="6" <?php echo (date('m') == 6) ? 'selected' : ''; ?>>Juni</option>
                        <option value="7" <?php echo (date('m') == 7) ? 'selected' : ''; ?>>Juli</option>
                        <option value="8" <?php echo (date('m') == 8) ? 'selected' : ''; ?>>Agustus</option>
                        <option value="9" <?php echo (date('m') == 9) ? 'selected' : ''; ?>>September</option>
                        <option value="10" <?php echo (date('m') == 10) ? 'selected' : ''; ?>>Oktober</option>
                        <option value="11" <?php echo (date('m') == 11) ? 'selected' : ''; ?>>November</option>
                        <option value="12" <?php echo (date('m') == 12) ? 'selected' : ''; ?>>Desember</option>
                      </select>
                    </div>
                  </div>
                </div>
              </div>
              <table class="table datatable" id="tablePentas">
                <thead>
                  <tr>
                    <th scope="col">No</th>
                    <th scope="col">Nomor Induk Seniman</th>
                    <th scope="col">Nama Seniman</th>
                    <th scope="col">Tanggal Pengajuan</th>
                    <th scope="col">Status</th>
                    <th scope="col">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $query = mysqli_query($conn, "SELECT id_advis, nomor_induk, nama_advis, DATE(created_at) AS tanggal, status, catatan FROM surat_advis WHERE status = 'diajukan' OR status = 'proses' ORDER BY id_advis DESC");
                  $no = 1;
                  $advisData = changeMonth(mysqli_fetch_all($query, MYSQLI_ASSOC));
                  foreach ($advisData as $advis) {  
                  ?>
                    <tr>
                      <td><?php echo $no ?></td>
                      <td><?php echo $advis['nomor_induk'] ?></td>
                      <td><?php echo $advis['nama_advis'] ?></td>
                      <td><?php echo $advis['tanggal'] ?></td>
                      <td>
                        <?php if ($advis['status'] == 'diajukan') { ?>
                          <span class="badge bg-proses">Diajukan</span>
                        <?php } else if ($advis['status'] == 'proses') { ?>
                          <span class="badge bg-terima">Diproses</span>
                        <?php } ?>
                      </td>
                      <td>
                        <?php if ($advis['status'] == 'diajukan') { ?>
                          <button class="btn btn-lihat" onclick="proses(<?php echo $advis['id_advis'] ?>)"><i class="bi bi-eye-fill"></i> Lihat</button>
                        <?php } else if ($advis['status'] == 'proses') { ?>
                          <a href="/pentas/detail_pentas.php?id_pentas=<?= $advis['id_advis'] ?>" class="btn btn-lihat"><i class="bi bi-eye-fill"></i> Lihat</a>
                        <?php } ?>
                      </td>
                    </tr>
                  <?php $no++;
                  } ?>
                </tbody>
              </table>
              <br>
              <div class="row mb-3 justify-content-end">
                <div class="col-sm-10 text-end">
                  <a href="../pentas.php" class="btn btn-secondary">Kembali</a>
                </div>
              </div>
            </div>
          </div>
    </section>



  </main><!-- End #main -->
  <div id="redPopup" style="display:none"></div>
  <!-- ======= Footer ======= -->
  <footer id="footer" class="footer">
    <?php include(__DIR__ . '/../footer.php');
    ?>
  </footer>

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
  <script src="<?php echo $tPath; ?>/public/js/popup.js"></script>
  <!-- Vendor JS Files -->
  <script src="<?php echo $tPath; ?>/public/assets/vendor/jquery/jquery.min.js"></script>
  <script src="<?php echo $tPath; ?>/public/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="<?php echo $tPath; ?>/public/assets/vendor/tinymce/tinymce.min.js"></script>
  <script src="<?php echo $tPath; ?>/public/assets/vendor/simple-datatables/simple-datatables.js"></script>
  <script src="<?php echo $tPath; ?>/public/assets/vendor/datatables/js/jquery.dataTables.min.js"></script>
  <script>
    // var tablePentas = document.getElementById('tablePentas');
    var tahunInput = document.getElementById('inpTahun');
    var bulanInput = document.getElementById('inpBulan');
    var tahun;
    function updateTable(dataT = ''){
      var table = $('#tablePentas').DataTable();
      table.clear().draw();
      var num = 1;
      if (dataT !== '') {
        let count = 0;
        dataT.forEach(function (item) {
          count++;
          table.row.add([
            num,
            item['nomor_induk'],
            item['nama_advis'],
            item['tanggal'],
            getStatusBadge(item['status']),
            getActionButton(item['status'], item['id_advis'])
          ]).draw();
          num++;
        });
      }
      $('#tablePentas_length').remove();
      $('#tablePentas_filter').remove();
      $('#tablePentas_paginate').remove();
      $('#tablePentas_info').remove();
      //change info 
      ////////////////

      function getStatusBadge(status) {
        if (status == 'diajukan') {
          return '<span class="badge bg-proses">Diajukan</span>';
        } else if (status == 'proses') {
          return '<span class="badge bg-terima">Diproses</span>';
        }
        return '';
      }
      function getActionButton(status, idAdvis) {
        if (status == 'diajukan') {
          return `<button class="btn btn-lihat" onclick="proses('${idAdvis}')"><i class="bi bi-eye-fill"></i> Lihat</button>`;
        } else if (status == 'proses') {
          return `<a href="/pentas/detail_pentas.php?id_pentas=${idAdvis}" class="btn btn-lihat"><i class="bi bi-eye-fill"></i> Lihat</a>`;
        }
        return '';
      }
    }

    function getData(con = null) {
      var xhr = new XMLHttpRequest();
      if (con == 'semua') {
        var requestBody = {
          email: email,
          tanggal: 'semua',
          desc: 'pengajuan'
        };
      } else if (con == null) {
        var tanggal = bulanInput.value + '-' + tahunInput.value;
        var requestBody = {
          email: email,
          tanggal: tanggal,
          desc: 'pengajuan'
        };
      }
      //open the request
      xhr.open('POST', domain + "/web/pentas/pentas.php")
      xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken);
      xhr.setRequestHeader('Content-Type', 'application/json');
      //send the form data
      xhr.send(JSON.stringify(requestBody));
      xhr.onreadystatechange = function() {
        if (xhr.readyState == XMLHttpRequest.DONE) {
          if (xhr.status === 200) {
            var response = xhr.responseText;
            updateTable(JSON.parse(response)['data']);
          } else {
            var response = xhr.responseText;
            updateTable();
            return;
          }
        }
      }
    }

    function tampilkanBulan() {
      if (bulanInput.value == 'semua') {
        tahun = tahunInput.value;
        tahunInput.disabled = true;
        tahunInput.value = '';
        setTimeout(() => {
          getData('semua');
        }, 250);
      } else {
        if (tahunInput.disabled == true) {
          tahunInput.disabled = false;
          tahunInput.value = tahun;
        }
        setTimeout(() => {
          getData();
        }, 250);
      }
    }

    function tampilkanTahun() {
      setTimeout(() => {
        var tahun = tahunInput.value;
        tahun = tahun.replace(/\s/g, '');
        if (isNaN(tahun)) {
          showRedPopup('Tahun harus angka !');
          return;
        }
        setTimeout(() => {
          getData();
        }, 250);
      }, 50);
    }

    function proses(Id) {
      var xhr = new XMLHttpRequest();
      var requestBody = {
        _method: 'PUT',
        id_user: idUser,
        id_pentas: Id,
        keterangan: 'proses'
      };
      //open the request
      xhr.open('POST', domain + "/web/pentas/pentas.php")
      xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken);
      xhr.setRequestHeader('Content-Type', 'application/json');
      //send the form data
      xhr.send(JSON.stringify(requestBody));
      xhr.onreadystatechange = function() {
        if (xhr.readyState == XMLHttpRequest.DONE) {
          if (xhr.status === 200) {
            window.location.href = "/pentas/detail_pentas.php?id_pentas=" + Id;
          } else {
            try {
              eval(xhr.responseText);
            } catch (error) {
              console.error('Error evaluating JavaScript:', error);
            }
          }
        }
      }
    }
  </script>

  <!-- Template Main JS File -->
  <script src="<?php echo $tPath; ?>/public/assets/js/main.js"></script>

</body>

</html>