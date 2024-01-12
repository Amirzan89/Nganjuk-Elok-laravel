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
  <link href="{{ asset($tPath.'css/popup.css') }}" rel="stylesheet">
  <style>
    .ui-datepicker-calendar {
      display: none;
    }
    
    .srcDate {
      float: right;
      margin-top: 70px;
      margin-left: 10px;
      margin-right: 10px;


    }

    .kategori {
      float: right;
      margin-top: 10px;

    }

    .inp {
      padding: 6px;
      border: 1px solid #ccc;
      border-radius: 5px;
      font-size: 16px;
      width: 100%;
    }

  </style>
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
      <h1>Data Seniman</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/dashboard.php">Beranda</a></li>
          <li class="breadcrumb-item"><a href="/seniman.php">Kelola Seniman</a></li>
          <li class="breadcrumb-item active">Data Seniman</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section">
      <div class="row">
        <div class="col-lg-12">

          <div class="card">
            <div class="card-body">
              <h5 class="card-title"></h5>
              <div class="col-md-3">
                  <select id="inpKategori" onchange="tampilkanKategori()" class="inp">
                          <option value="semua">semua</option>
                          <?php
                            foreach($kategoriSeniman as $n){
                              echo "<option value='".$n['id_kategori_seniman']."'>".$n['nama_kategori']."</option>";
                            }
                          ?>
                        </select>
                  </div>
                
              <a href="tambah.php" class="btn btn-primary">
                <i class="bi bi-person-plus-fill"></i> Tambah Seniman
              </a>
              
                <div class="srcDate">
                  <div class="col-lg-12">
                    <div class="row">
                      <div class="col-lg-3">
                        <input type="text" name="" id="inpTahun" placeholder="Tahun" class="inp" value="<?php echo date('Y') ?>" oninput="tampilkanTahun()">
                      </div>
                      <div class="col-lg-5">
                        <select id="inpBulan" onchange="tampilkanBulan()" class="inp" value="<?php echo date('M')  ?>">
                          <option value="semua">semua</option>
                          <option value="1" <?php echo (date('m') == 1) ? 'selected' : ''; ?> >Januari</option>
                          <option value="2" <?php echo (date('m') == 2) ? 'selected' : ''; ?> >Februari</option>
                          <option value="3" <?php echo (date('m') == 3) ? 'selected' : ''; ?> >Maret</option>
                          <option value="4" <?php echo (date('m') == 4) ? 'selected' : ''; ?> >April</option>
                          <option value="5" <?php echo (date('m') == 5) ? 'selected' : ''; ?> >Mei</option>
                          <option value="6" <?php echo (date('m') == 6) ? 'selected' : ''; ?> >Juni</option>
                          <option value="7" <?php echo (date('m') == 7) ? 'selected' : ''; ?> >Juli</option>
                          <option value="8" <?php echo (date('m') == 8) ? 'selected' : ''; ?> >Agustus</option>
                          <option value="9" <?php echo (date('m') == 9) ? 'selected' : ''; ?> >September</option>
                          <option value="10" <?php echo (date('m') == 10) ? 'selected' : ''; ?> >Oktober</option>
                          <option value="11" <?php echo (date('m') == 11) ? 'selected' : ''; ?> >November</option>
                          <option value="12" <?php echo (date('m') == 12) ? 'selected' : ''; ?> >Desember</option>
                        </select>
                      </div>
                    </div>
                  </div>
              </div>
              <table class="table datatable" id="tableSeniman">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Nomor Induk Seniman</th>
                    <th>Kategori</th>
                    <th>Nama Seniman</th>
                    <th>Nomor Telepon</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                    $query = mysqli_query($conn, "SELECT id_seniman, nomor_induk, nama_kategori, nama_seniman, no_telpon, DATE(created_at) AS tanggal, status, catatan FROM seniman INNER JOIN kategori_seniman ON seniman.id_kategori_seniman = kategori_seniman.id_kategori_seniman WHERE status = 'diterima' ORDER BY id_seniman DESC");
                    $no = 1;
                    $senimanData = mysqli_fetch_all($query, MYSQLI_ASSOC);
                    foreach ($senimanData as $seniman) {
                  ?>
                    <tr>
                      <td><?php echo $no ?></td>
                      <td><?php echo $seniman['nomor_induk'] ?></td>
                      <td><?php echo $seniman['nama_kategori'] ?></td>
                      <td><?php echo $seniman['nama_seniman'] ?></td>
                      <td><?php echo $seniman['no_telpon'] ?></td>
                      <td>
                        <a href="/seniman/detail_seniman.php?id_seniman=<?= $seniman['id_seniman'] ?>" class="btn btn-lihat"><i class="bi bi-eye-fill"></i>   Lihat</a>
                        <a href="/seniman/edit_detail_seniman.php?id_seniman=<?= $seniman['id_seniman'] ?>" class="btn btn-edit"><i class="bi bi-pencil-fill"></i>   Edit</a>
                        <button type="button" class="btn btn-tolak" onclick="openDelete(<?php echo $seniman['id_seniman']?>)"> <i class="bi bi-trash-fill"></i>   Hapus</button>
                      </td>
                    </tr>
                  <?php $no++;
                  } ?>
                </tbody>
              </table>
              <br>
              <div class="row mb-3 justify-content-end">
                <div class="col-sm-10 text-end">
                  <a href="../seniman.php" class="btn btn-secondary">Kembali</a>
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
          <h5 class="modal-title">Konfirmasi hapus data seniman</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          Apakah anda yakin ingin menghapus data seniman?  
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <form action="/web/seniman/seniman.php" id="deleteForm" method="POST">
            <input type="hidden" name="_method" value="DELETE">
            <input type="hidden" name="desc" value="hapus">
            <input type="hidden" name="id_user" value="<?php echo $userAuth['id_user'] ?>">
            <input type="hidden" name="id_seniman" id="inpSenimanDelete">
            <button type="submit" class="btn btn-tolak">Hapus</button>
          </form>
        </div>
      </div>
    </div>
  </div>
  <!-- end modal delete -->
  <div id="redPopup" style="display:none"></div>
  <!-- ======= Footer ======= -->
  <footer id="footer" class="footer">
    <?php include(__DIR__.'/../footer.php');
    ?>
  </footer>

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
  <script src="{{ asset($tPath.'js/popup.js') }}"></script>
  <!-- Vendor JS Files -->
  <script src="{{ asset($tPath.'assets/vendor/jquery/jquery.min.js') }}"></script>
  <script src="{{ asset($tPath.'assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset($tPath.'assets/vendor/tinymce/tinymce.min.js') }}"></script>
  <script src="{{ asset($tPath.'assets/vendor/simple-datatables/simple-datatables.js') }}"></script>
  <script src="{{ asset($tPath.'assets/vendor/datatables/js/jquery.dataTables.min.js') }}"></script>
  <script>
    var tahunInput = document.getElementById('inpTahun');
    var bulanInput = document.getElementById('inpBulan');
    var kategoriInput = document.getElementById('inpKategori');
    var inpSenimanDelete = document.getElementById('inpSenimanDelete');
    var tahun;
    function updateTable(dataT = ''){
      var table = $('#tableSeniman').DataTable();
      table.clear().draw();
      var num = 1;
      if (dataT !== '') {
        let count = 0;
        dataT.forEach(function (item) {
          count++;
          table.row.add([
            num,
            item['nomor_induk'],
            item['nama_kategori'],
            item['nama_seniman'],
            item['no_telpon'],
            getActionButton(item['status'], item['id_seniman'])
          ]).draw();
          num++;
        });
      }
      $('#tableSeniman_length').remove();
      $('#tableSeniman_filter').remove();
      $('#tableSeniman_paginate').remove();
      $('#tableSeniman_info').remove();
      //change info
      ////////////////

      function getActionButton(status, idSeniman) {
          return `
          <a href="/seniman/detail_seniman.php?id_seniman=${idSeniman}" class="btn btn-lihat"><i class="bi bi-eye-fill"></i> Lihat</a>
          <a href="/seniman/edit_detail_seniman.php?id_seniman=${idSeniman}" class="btn btn-edit"><i class="bi bi-pencil-fill"></i> Lihat</a>
          <button class="btn btn-tolak" onclick="openDelete('${idSeniman}')"><i class="bi bi-trash-fill"></i> Lihat</button>`;
      }
    }
    function updateTableOld(dataT = ''){
      while (tableSeniman.firstChild) {
        tableSeniman.removeChild(tableSeniman.firstChild);
      }
      var num = 1;
      if(dataT != ''){
        dataT.forEach(function (item){
          var row = document.createElement('tr');
          var td = document.createElement('td');
          //data
          td.innerText = num;
          row.appendChild(td);
          var td = document.createElement('td');
          td.innerText = item['nomor_induk'];
          row.appendChild(td);
          var td = document.createElement('td');
          td.innerText = item['nama_kategori'];
          row.appendChild(td);
          var td = document.createElement('td');
          td.innerText = item['nama_seniman'];
          row.appendChild(td);
          var td = document.createElement('td');
          td.innerText = item['no_telpon'];
          row.appendChild(td);
          //btn 1
          var td = document.createElement('td');
          var link = document.createElement('a');
          var icon = document.createElement('i');
          icon.classList.add('bi','bi-eye-fill');
          icon.innerText = 'Lihat';
          link.appendChild(icon);
          link.classList.add('btn','btn-lihat');
          link.setAttribute('href',`/seniman/detail_seniman.php?id_seniman=${item['id_seniman']}`);
          td.appendChild(link);
          //btn 2
          var btn = document.createElement('button');
          var icon = document.createElement('i');
          icon.classList.add('bi','bi-pencil-fill');
          icon.innerText = 'Edit';
          btn.appendChild(icon);
          btn.classList.add('btn','btn-edit');
          td.appendChild(btn);
          //btn 3
          var btn = document.createElement('button');
          var icon = document.createElement('i');
          icon.classList.add('bi','bi-trash-fill');
          icon.innerText = 'Hapus';
          btn.appendChild(icon);
          btn.classList.add('btn','btn-hapus');
          td.appendChild(btn);
          row.appendChild(td);
          tableSeniman.appendChild(row);
          num++;
        });
      }
    }
    function getData(con = null){
      var xhr = new XMLHttpRequest();
      if(con == 'semua'){
        var requestBody = {
          email: email,
          tanggal:'semua',
          kategori:kategoriInput.value,
          table:'seniman',
          desc:'data'
        };
      }else if(con == null){ 
        var tanggal = bulanInput.value +'-'+tahunInput.value;
        var requestBody = {
          email: email,
          tanggal:tanggal,
          kategori:kategoriInput.value,
          table:'seniman',
          desc:'data'
        };
      }
      //open the request
      xhr.open('POST', domain + "/web/seniman/seniman.php")
      xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken);
      xhr.setRequestHeader('Content-Type', 'application/json');
      //send the form data
      xhr.send(JSON.stringify(requestBody));
      xhr.onreadystatechange = function () {
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
    function tampilkanBulan(){
      if(bulanInput.value == 'semua'){
        tahun = tahunInput.value;
        tahunInput.disabled = true;
        tahunInput.value = '';
        setTimeout(() => {
          getData('semua');
        }, 250);
      }else{
        if(tahunInput.disabled == true){
          tahunInput.disabled = false;
          tahunInput.value = tahun;
        }
        setTimeout(() => {
          getData();
        }, 250);
      }
    }
    function tampilkanTahun(){
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
      }, 5);
    }
    function tampilkanKategori(){
      tampilkanBulan();
    }
    function openDelete(dataU) {
      inpSenimanDelete.value = dataU;
      var myModal = new bootstrap.Modal(modalDelete);
      myModal.show();
    }
  </script>

  <!-- Template Main JS File -->
  <script src="{{ asset($tPath.'assets/js/main.js') }}"></script>

</body>

</html>