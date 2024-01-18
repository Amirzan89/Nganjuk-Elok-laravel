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
  <link href="{{ asset($tPath.'img/icon/utama/logo.png') }}" rel="icon">

  <!-- Google Fonts -->
  <!-- <link href="https://fonts.gstatic.com" rel="preconnect"> -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Jost:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
  <!-- Vendor CSS Files -->
  <link href="{{ asset($tPath.'assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset($tPath.'assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="{{ asset($tPath.'assets/css/style.css') }}" rel="stylesheet">
  <link href="{{ asset($tPath.'css/popup.css') }}" rel="stylesheet">

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
      <h1>Detail Pentas</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/dashboard">Beranda</a></li>
          <li class="breadcrumb-item"><a href="/pentas">Kelola Pentas</a></li>
          @if ($pentasData['status'] == 'diajukan' || $pentasData['status'] == 'proses')
            <li class="breadcrumb-item"><a href="/pentas/pengajuan">Pengajuan Pentas</a></li>
          @elseif ($pentasData['status'] == 'diterima' || $pentasData['status'] == 'ditolak')
            <li class="breadcrumb-item"><a href="/pentas/riwayat">Riwayat Pengajuan Pentas</a></li>
          @endif
          <li class="breadcrumb-item active">Detail pentas</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section">
      <div class="row">
        <div class="col-lg-12">
          <div class="row mb-3 d-flex justify-content-center align-items-center">
            @if ($pentasData['status'] == 'diterima')
              <span class="badge bg-terima"><i class="bi bi-check-circle-fill"></i> Diterima</span>
            @elseif ($pentasData['status'] == 'ditolak')
              <span class="badge bg-tolak"><i class="bi bi-x-circle-fill"></i> Ditolak</span>
              </li>
            @endif
          </div>
          <div class="card">
            <div class="card-body">
              <h5 class="card-title  mt-3 mb-4"><strong>
                  SURAT ADVIS
                  <br>
                  <u>PENYELENGGARAAN PERTUNJUKAN KESENIAN</u>
                </strong>
              </h5>

              <form method="POST" action="../users/proses-tambah-user">
                <div class="col-md-12">
                  <label for="nama_seniman" class="form-label">Nomor Induk Seniman</label>
                  <input type="text" class="form-control" name="nik" value="{{ $pentasData['nomor_induk'] }}" readonly>
                </div>
                <br>
                <div class="col-md-12">
                  <label for="nama_seniman" class="form-label">Nama Pemohon</label>
                  <input type="text" class="form-control" name="nama" value="{{ $pentasData['nama_advis'] }}" readonly>
                </div>
                <br>
                <div class="col-md-12 ">
                  <label for="alamat_seniman" class="form-label">Alamat</label>
                  <textarea class="form-control" id="alamat_seniman" style="height: 100px;" readonly>{{ $pentasData['alamat_advis'] }}</textarea>
                </div>
                <br>
                <div class="col-md-12">
                  <label for="no_telpon" class="form-label">Untuk Pentas</label>
                  <input type="text" class="form-control" name="phone" value="{{ $pentasData['deskripsi_advis'] }}" readonly>
                </div>
                <br>
                <div class="col-md-12">
                  <label for="tgl_awal_peminjaman" class="form-label">Tanggal</label>
                  <input type="text" class="form-control" readonly value="{{ $pentasData['tanggal'] }}">
                </div>
                <br>
                <div class="col-md-12">
                  <label for="nama_seniman" class="form-label">Bertempat di</label>
                  <input type="text" class="form-control" name="tempatL" readonly value="{{ $pentasData['tempat_advis'] }}">
                </div>
                <br>
                @if (isset($pentasData['catatan']) && !is_null($pentasData['catatan']) && !empty($pentasData['catatan']))
                  <div class="col-12">
                    <label for="inputText" class="form-label">Alasan Penolakan</label>
                    <textarea class="form-control" id="inputTextarea" style="height: 100px;" readonly>{{ $pentasData['catatan'] }}</textarea>
                  </div>
                  <br>
                @endif
                @if (isset($pentasData['kode_verifikasi']) && !is_null($pentasData['kode_verifikasi']) && !empty($pentasData['kode_verifikasi']))
                <div class="col-md-12">
                  <label for="nik" class="form-label">Kode Surat</label>
                  <input type="text" class="form-control" id="nik" readonly value="{{ $pentasData['kode_verifikasi'] }}">
                </div>
                @endif
                <br>
                <div class="row mb-3 justify-content-end">
                  <div class="col-sm-10 text-end">
                    @if ($pentasData['status'] == 'diajukan' || $pentasData['status'] == 'proses')
                      <a href="/pentas/pengajuan" class="btn btn-secondary" style="margin-right: 5px;">Kembali</a>
                    @elseif ($pentasData['status'] == 'diterima' || $pentasData['status'] == 'ditolak')
                      <a href="/pentas/riwayat" class="btn btn-secondary" style="margin-right: 5px;"><i></i>Kembali</a>
                    @endif
                    @if ($pentasData['status'] == 'diajukan')
                      <button type="button" class="btn btn-success" style="margin-right: 5px;" onclick="openProses({{ $pentasData['id_advis'] }})">
                        <i class="bi bi-edit-fill">Proses</i>
                      </button>
                    @elseif ($pentasData['status'] == 'proses')
                      <button type="button" class="btn btn-tambah" style="margin-right: 5px;" onclick="openSetuju({{ $pentasData['id_advis'] }})">Terima
                      </button>
                      <button type="button" class="btn btn-tolak" style="margin-right: 5px;" onclick="openTolak({{ $pentasData['id_advis'] }})">Tolak
                      </button>
                    @endif
                  </div>
                </div>
              </form><!-- End General Form Elements -->


            </div>
          </div>

        </div>
      </div>
    </section>

  </main>
  <!-- End #main -->
  <!-- start modal proses -->
  <div class="modal fade" id="modalProses" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Konfirmasi proses pengajuan pentas</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          Apakah Anda yakin ingin memproses pengajuan pentas?
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <form onsubmit="proses(event, 'proses')">
            <input type="hidden" name="id_pentas" id="inpPentasP">
            <button type="submit" class="btn btn-tamnbah">Proses</button>
          </form>
        </div>
      </div>
    </div>
  </div>
  <!-- end modal proses -->

  <!-- start modal setuju -->
  <div class="modal fade" id="modalSetuju" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Terima Pengajuan</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          Apakah anda yakin ingin menerima pengajuan pentas?
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <form onsubmit="proses(event, 'diterima')">
            <input type="hidden" name="id_pentas" id="inpPentasS">
            <button type="submit" class="btn btn-tambah">Terima</button>
          </form>
        </div>
      </div>
    </div>
  </div>
  <!-- end modal setuju -->

  <!-- start modal tolak -->
  <div class="modal fade" id="modalTolak" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Tolak Pengajuan</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form onsubmit="proses(event, 'ditolak')">
          <div class="modal-body" style="text-align: left;">
            <label for="catatan" class="form-label">Alasan penolakan</label>
            <textarea class="form-control" id="catatan" name="catatan" placeholder="Masukkan Alasan Penolakan" style="height: 100px;"></textarea>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <input type="hidden" name="id_pentas" id="inpPentasT">
            <button type="submit" class="btn btn-tolak">Tolak</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <!-- end modal tolak -->
  <!-- ======= Footer ======= -->
  <footer id="footer" class="footer">
    @include('component.footer')
  </footer>

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
  <div id="preloader" style="display: none;"></div>
  <div id="greenPopup" style="display:none"></div>
  <div id="redPopup" style="display:none"></div>
  <script src="{{ asset($tPath.'js/popup.js') }}"></script>
  <!-- Vendor JS Files -->
  <script src="{{ asset($tPath.'assets/vendor/apexcharts/apexcharts.min.js') }}"></script>
  <script src="{{ asset($tPath.'assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

  <!-- Template Main JS File -->
  <script src="{{ asset($tPath.'assets/js/main.js') }}"></script>
  <script>
    var modalProses = document.getElementById('modalProses');
    var modalSetuju = document.getElementById('modalSetuju');
    var modalTolak = document.getElementById('modalTolak');
    var inpPentasP = document.getElementById('inpPentasP');
    var inpPentasS = document.getElementById('inpPentasS');
    var inpPentasT = document.getElementById('inpPentasT');
    function showLoading(){
      document.querySelector('div#preloader').style.display = 'block';
    }
    function closeLoading(){
      document.querySelector('div#preloader').style.display = 'none';
    }
    function openProses(dataU, ) {
      inpPentasP.value = dataU;
      var myModal = new bootstrap.Modal(modalProses);
      myModal.show();
    }
    function openSetuju(dataU) {
      inpPentasS.value = dataU;
      var myModal = new bootstrap.Modal(modalSetuju);
      myModal.show();
    }
    function openTolak(dataU) {
      inpPentasT.value = dataU;
      var myModal = new bootstrap.Modal(modalTolak);
      myModal.show();
    }
    function closeModal(dataU) {
      var myModal = new bootstrap.Modal(dataU);
      myModal.hide();
    }
    document.addEventListener('DOMContentLoaded', function() {
      var currentPageURL = window.location.href;
      var menuLinks = document.querySelectorAll('.nav-link');
      menuLinks.forEach(function(menuLink) {
        var menuLinkURL = menuLink.getAttribute('href');
        if (currentPageURL === menuLinkURL) {
          menuLink.parentElement.classList.add('active');
        }
      });
    });
    //preview data
    function preview(desc) {
      if (desc != 'surat') {
        console.log('invalid description');
        return;
      }
      var xhr = new XMLHttpRequest();
      var requestBody = {
        email: email,
        id_pentas: idPentas,
        item: 'pentas',
        deskripsi: desc
      };
      //open the request
      xhr.open('POST', domain + "/preview")
      xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken);
      xhr.setRequestHeader('Content-Type', 'application/json');
      //send the form data
      xhr.send(JSON.stringify(requestBody));
      xhr.onreadystatechange = function() {
        if (xhr.readyState == XMLHttpRequest.DONE) {
          if (xhr.status === 200 || xhr.status === 300 || xhr.status === 302) {
            var response = JSON.parse(xhr.responseText);
            window.location.href = response.data;
          } else {
            showRedPopup(JSON.parse(xhr.responseText));
          }
        }
      }
    }
    //preview data
    function download(desc) {
      if (desc != 'surat') {
        console.log('invalid description');
        return;
      }
      var xhr = new XMLHttpRequest();
      var requestBody = {
        email: email,
        id_pentas: idPentas,
        item: 'pentas',
        deskripsi: desc
      };
      //open the request
      xhr.open('POST', domain + "/download")
      xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken);
      xhr.setRequestHeader('Content-Type', 'application/json');
      xhr.responseType = 'blob';
      // send the form data
      xhr.send(JSON.stringify(requestBody));
      xhr.onreadystatechange = function () {
        if (xhr.readyState == XMLHttpRequest.DONE) {
          if (xhr.status === 200) {
            if (xhr.responseType === 'blob') {
                var blob = xhr.response;
                var contentDisposition = xhr.getResponseHeader('Content-Disposition');
                var match = contentDisposition.match(/filename="(.+\..+?)"/);
                if (match) {
                    var filename = match[1];
                    var link = document.createElement('a');
                    link.href = window.URL.createObjectURL(blob);
                    link.download = filename;
                    link.click();
                } else {
                    console.log('Invalid content-disposition header');
                }
            } else {
              var jsonResponse = JSON.parse(xhr.responseText);
              console.log(jsonResponse);
            }
          } else {
            xhr.response.text().then(function (jsonText) {
              showRedPopup(JSON.parse(jsonText));
            });
          }
        }
      };
    }
    function proses(event, ket) {
      event.preventDefault();
      var modals = '';
      var Id = event.target.querySelector('[name="id_pentas"]').value;
      var catatan = '';
      if(ket == 'proses'){
        modals = modalProses;
      }else if(ket == 'diterima'){
        modals = modalSetuju;
      }else if(ket == 'ditolak'){
        catatan = event.target.querySelector('[name="catatan"]').value;
        modals = modalTolak;
      }
      showLoading();
      var xhr = new XMLHttpRequest();
      var requestBody = {
        email: email,
        id_pentas: Id,
        keterangan: ket,
        catatan:catatan
      };
      xhr.open('PUT', domain + "/pentas/pengajuan")
      xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken);
      xhr.setRequestHeader('Content-Type', 'application/json');
      xhr.send(JSON.stringify(requestBody));
      xhr.onreadystatechange = function() {
        if (xhr.readyState == XMLHttpRequest.DONE) {
          if (xhr.status === 200) {
            closeLoading();
            closeModal(modals);
            var response = JSON.parse(xhr.responseText);
            showGreenPopup(response);
            setTimeout(() => {
              if(ket == 'proses'){
                window.location.href = "/pentas/pengajuan";
              }else if(ket == 'diterima' || ket == 'ditolak'){
                window.location.href = "/pentas/pengajuan";
              }
            }, 3000);
          } else {
            closeLoading();
            closeModal(modals);
            var response = JSON.parse(xhr.responseText);
            showRedPopup(response);
          }
        }
      }
    }
  </script>
</body>

</html>