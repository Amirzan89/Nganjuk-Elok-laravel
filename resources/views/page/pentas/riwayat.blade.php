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
  <!-- <link href="{{ asset($tPath.'assets/vendor/bootstrap/css/bootstrap.css') }}" rel="stylesheet"> -->

  <link href="{{ asset($tPath.'assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset($tPath.'assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
  <link href="{{ asset($tPath.'assets/vendor/simple-datatables/style.css') }}" rel="stylesheet">


  <!-- Template Main CSS File -->
  <link href="{{ asset($tPath.'assets/css/tempat.css') }}" rel="stylesheet">
  <link href="{{ asset($tPath.'css/popup.css') }}" rel="stylesheet">
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
    const domain = window.location.protocol + '//' + window.location.hostname + ":" + window.location.port;
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
        $nav = 'pentas';
      @endphp
      @include('component.sidebar')
    </ul>
  </aside><!-- End Sidebar-->

  <main id="main" class="main">

    <div class="pagetitle">
      <h1>Riwayat Pengajuan Pentas</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/dashboard">Beranda</a></li>
          <li class="breadcrumb-item"><a href="/pentas">Kelola Pentas</a></li>
          <li class="breadcrumb-item active">Riwayat Pengajuan Pentas</li>
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
                    <th>No</th>
                    <th>Nomor Induk Seniman</th>
                    <th>Nama Pemohon</th>
                    <th>Tanggal Pengajuan</th>
                    <th>Status</th>
                    <th>Kode Surat</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  @php $no = 1; @endphp
                  @foreach ($pentasData as $pentas)
                    <tr>
                      <td>{{ $no++ }}</td>
                      <td>{{ $pentas['nomor_induk'] }}</td>
                      <td>{{ $pentas['nama_advis'] }}</td>
                      <td>{{ $pentas['tanggal'] }}</td>
                      <td>
                        @if ($pentas['status'] == 'diterima')
                          <span class="badge bg-terima">Diterima</span>
                        @elseif ($pentas['status'] == 'ditolak')
                          <span class="badge bg-tolak">Ditolak</span>
                        @endif
                      </td>
                      <td><?php echo $pentas['kode_verifikasi'] ?></td>
                      <td>
                        <a href="/pentas/detail/{{ $pentas['id_advis'] }}" class="btn btn-lihat"><i class="bi bi-eye-fill"></i> Lihat</a>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
              <br>
              <div class="row mb-3 justify-content-end">
                <div class="col-sm-10 text-end">
                  <a href="../pentas" class="btn btn-secondary">Kembali</a>
                </div>
              </div>

            </div>
          </div>
    </section>

  </main><!-- End #main -->
  <div id="redPopup" style="display:none"></div>
  <!-- ======= Footer ======= -->
  <footer id="footer" class="footer">
    @include('component.footer')
  </footer>
  <!-- </footer> -->

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
    var tahun;

    function updateTable(dataT = ''){
      var table = $('#tablePentas').DataTable();
      table.clear().draw();
      var num = 1;
      if (dataT !== '') {
        dataT.forEach(function (item) {
          table.row.add([
            num,
            item['nomor_induk'],
            item['nama_advis'],
            item['tanggal'],
            getStatusBadge(item['status']),
            item['kode_verifikasi'],
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
        if (status == 'ditolak') {
          return '<span class="badge bg-tolak">Ditolak</span>';
        } else if (status == 'diterima') {
          return '<span class="badge bg-terima">Diterima</span>';
        }
        return '';
      }
      function getActionButton(status, idAdvis) {
        if (status == 'ditolak' || status == 'diterima') {
          return `<a href="/pentas/detail_pentas?id_pentas=${idAdvis}" class="btn btn-lihat"><i class="bi bi-eye-fill"></i> Lihat</a>`;
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
          desc: 'riwayat'
        };
      } else if (con == null) {
        var tanggal = bulanInput.value + '-' + tahunInput.value;
        var requestBody = {
          email: email,
          tanggal: tanggal,
          desc: 'riwayat'
        };
      }
      //open the request
      xhr.open('POST', domain + "/web/pentas/pentas")
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
      }, 5);
    }
  </script>

  <!-- Template Main JS File -->
  <script src="{{ asset($tPath.'assets/js/main.js') }}"></script>

</body>

</html>