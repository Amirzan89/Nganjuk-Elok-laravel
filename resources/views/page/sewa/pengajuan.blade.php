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
  <link href="{{ asset($tPath.'assets/img/landing-page/favicon.png') }}" rel="icon">
    <link href="{{ asset($tPath.'assets/img/landing-page/apple-touch-icon.png') }}" rel="apple-touch-icon">

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
        $nav = 'tempat';
      @endphp
      @include('component.sidebar')
    </ul>
  </aside><!-- End Sidebar-->

    <main id="main" class="main">
        <div class="pagetitle">
            <h1>Verifikasi Peminjaman</h1>
            <nav>
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/dashboard">Beranda</a></li>
                <li class="breadcrumb-item"><a href="/sewa">Kelola Tempat</a></li>
                <li class="breadcrumb-item active">Verifikasi Peminjaman</li>
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
                                    <input type="text" name="" id="inpTahun" placeholder="Tahun" class="inp" value="{{date('Y') }}" oninput="tampilkanTahun()">
                                  </div>
                                  <div class="col-lg-5">
                                    <select id="inpBulan" onchange="tampilkanBulan()" class="inp" value="{{date('M')  }}">
                                      <option value="semua">semua</option>
                                      <option value="1" {{(date('m') == 1) ? 'selected' : ''}} >Januari</option>
                                      <option value="2" {{(date('m') == 2) ? 'selected' : ''}} >Februari</option>
                                      <option value="3" {{(date('m') == 3) ? 'selected' : ''}} >Maret</option>
                                      <option value="4" {{(date('m') == 4) ? 'selected' : ''}} >April</option>
                                      <option value="5" {{(date('m') == 5) ? 'selected' : ''}} >Mei</option>
                                      <option value="6" {{(date('m') == 6) ? 'selected' : ''}} >Juni</option>
                                      <option value="7" {{(date('m') == 7) ? 'selected' : ''}} >Juli</option>
                                      <option value="8" {{(date('m') == 8) ? 'selected' : ''}} >Agustus</option>
                                      <option value="9" {{(date('m') == 9) ? 'selected' : ''}} >September</option>
                                      <option value="10" {{(date('m') == 10) ? 'selected' : ''}} >Oktober</option>
                                      <option value="11" {{(date('m') == 11) ? 'selected' : ''}} >November</option>
                                      <option value="12" {{(date('m') == 12) ? 'selected' : ''}} >Desember</option>
                                    </select>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <table class="table datatable" id="tableSewa">
                                <thead>
                                    <tr>
                                        <th class="col"><strong>No.</th>
                                        <th scope="col">Nama Peminjam</th>
                                        <th scope="col">Nama Tempat</th>
                                        <th scope="col">Tanggal Pengajuan</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                  @php $no = 1; @endphp
                                  @foreach ($sewaData as $sewa)
                                    <tr>
                                      <td>{{ $no++ }}</td>
                                      <td>{{ $sewa['nama_peminjam'] }}</td>
                                      <td>{{ $sewa['nama_tempat'] }}</td>
                                      <td>{{ $sewa['tanggal'] }}</td>
                                      <td>
                                        @if ($sewa['status'] == 'diajukan')
                                          <span class="badge bg-proses">Diajukan</span>
                                        @elseif ($sewa['status'] == 'proses')
                                          <span class="badge bg-terima">Diproses</span>
                                        @endif
                                      </td>
                                      <td>
                                        @if ($sewa['status'] == 'diajukan')
                                          <button class="btn btn-lihat" onclick="proses({{ $sewa['id_sewa'] }})"><i class="bi bi-eye-fill"></i> Lihat</button>
                                        @elseif ($sewa['status'] == 'proses')
                                          <a href="/sewa/detail/{{ $sewa['id_sewa'] }}" class="btn btn-lihat"><i class="bi bi-eye-fill"></i> Lihat</a>
                                        @endif
                                      </td>
                                    </tr>
                                  @endforeach
                                </tbody>
                            </table>
                            <div class="row mb-3 justify-content-end">
                                <div class="col-sm-10 text-end">
                                    <a href="/sewa" class="btn btn-secondary">Kembali</a>
                                </div>
                            </div>
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
      var table = $('#tableSewa').DataTable();
      table.clear().draw();
      var num = 1;
      if (dataT !== '') {
        let count = 0;
        dataT.forEach(function (item) {
          count++;
          table.row.add([
            num,
            item['nama_peminjam'],
            item['nama_tempat'],
            item['tanggal'],
            getStatusBadge(item['status']),
            getActionButton(item['status'], item['id_sewa'])
          ]).draw();
          num++;
        });
      }
      $('#tableSewa_length').remove();
      $('#tableSewa_filter').remove();
      $('#tableSewa_paginate').remove();
      $('#tableSewa_info').remove();
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
      function getActionButton(status, idSewa) {
        if (status == 'diajukan') {
          return `<button class="btn btn-lihat" onclick="proses('${idSewa}')"><i class="bi bi-eye-fill"></i> Lihat</button>`;
        } else if (status == 'proses') {
          return `<a href="/sewa/detail/${idSewa}" class="btn btn-lihat"><i class="bi bi-eye-fill"></i> Lihat</a>`;
        }
        return '';
      }
    }
    function getData(con = null){
      var xhr = new XMLHttpRequest();
      if(con == 'semua'){
        var requestBody = {
          email: email,
          tanggal:'semua',
          desc:'pengajuan'
        };
      }else if(con == null){
        var tanggal = bulanInput.value +'-'+tahunInput.value;
        var requestBody = {
          email: email,
          tanggal:tanggal,
          desc:'pengajuan'
        };
      }
      //open the request
      xhr.open('POST', domain + "/sewa/pengajuan")
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
      }, 50);
    }
    function proses(Id) {
      var xhr = new XMLHttpRequest();
      var requestBody = {
        email: email,
        id_sewa: Id,
        keterangan: 'proses'
      };
      //open the request
      xhr.open('PUT', domain + "/sewa/pengajuan")
      xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken);
      xhr.setRequestHeader('Content-Type', 'application/json');
      xhr.send(JSON.stringify(requestBody));
      xhr.onreadystatechange = function() {
        if (xhr.readyState == XMLHttpRequest.DONE) {
          if (xhr.status === 200) {
            var response = JSON.parse(xhr.responseText);
            window.location.href = "/sewa/detail/"+ Id;
          } else {
            var response = JSON.parse(xhr.responseText);
            showRedPopup(response);
          }
        }
      }
    }
  </script>

  <!-- Template Main JS File -->
  <script src="{{ asset($tPath.'assets/js/main.js') }}"></script>

</body>

</html>