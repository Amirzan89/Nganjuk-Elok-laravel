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
  <link href="{{ asset($tPath.'assets/vendor/simple-datatables/style.css') }}" rel="stylesheet">


  <!-- Template Main CSS File -->
  <link href="{{ asset($tPath.'assets/css/event.css') }}" rel="stylesheet">
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
        $nav = 'event';
      @endphp
      @include('component.sidebar')
    </ul>
  </aside><!-- End Sidebar-->


  <main id="main" class="main">

    <div class="pagetitle">
      <h1>Detail Data Event</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/dashboard">Beranda</a></li>
          <li class="breadcrumb-item"><a href="/event">Kelola Event</a></li>
          @if ($eventsData['status'] == 'diajukan' || $eventsData['status'] == 'proses')
            <li class="breadcrumb-item"><a href="/event/pengajuan">Verifikasi Pengajuan</a></li>
          @elseif ($eventsData['status'] == 'diterima' || $eventsData['status'] == 'ditolak')
            <li class="breadcrumb-item"><a href="/event/riwayat">Riwayat Pengajuan</a></li>
          @endif
          <li class="breadcrumb-item active">Detail event</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section">
      <div class="row">
        <div class="col-lg-12">
          <div class="row mb-3 d-flex justify-content-center align-items-center">
            @if ($eventsData['status'] == 'diterima')
              <span class="badge bg-terima"><i class="bi bi-check-circle-fill"></i> Diterima</span>
            @elseif ($eventsData['status'] == 'ditolak')
              <span class="badge bg-tolak"><i class="bi bi-x-circle-fill"></i> Ditolak</span>
              </li>
            @endif
          </div>
          <div class="card">
            <div class="card-body">
              @if ($eventsData['status'] == 'diajukan' || $eventsData['status'] == 'proses')
                <h5 class="card-title"> Pengajuan event</h5>
              @elseif ($eventsData['status'] == 'diterima' || $eventsData['status'] == 'ditolak')
                <h5 class="card-title"> Riwayat event</h5>
              @endif
              <form class="row g-3">
                <div class="col-md-12">
                  <label for="inputText" class="form-label">Nama Pengirim</label>
                  <input type="text" class="form-control" id="inputText" readonly value="{{  $eventsData['nama_pengirim'] }}">
                </div>
                <div class="col-md-12">
                  <label for="inputText" class="form-label">Nama Event</label>
                  <input type="text" class="form-control" id="inputText" readonly value="{{  $eventsData['nama_event'] }}">
                </div>
                <div class="col-md-4">
                  <label for="inputDate" class="form-label">Tanggal awal</label>
                  <input type="text" class="form-control" id="inputDate" readonly value="{{  $eventsData['tanggal_awal'] }}">
                </div>
                <div class="col-md-4">
                  <label for="inputDate" class="form-label">Tanggal akhir</label>
                  <input type="text" class="form-control" id="inputDate" readonly value="{{  $eventsData['tanggal_akhir'] }}">
                </div>
                <div class="col-md-8">
                  <label for="inputText" class="form-label">Tempat</label>
                  <input type="text" class="form-control" id="inputText" readonly value="{{  $eventsData['tempat_event'] }}">
                </div>
                <div class="col-12">
                  <label for="inputText" class="form-label">Deskripsi Event</label>
                  <textarea class="form-control" id="inputTextarea" style="height: 100px;" readonly>{{  $eventsData['deskripsi'] }}</textarea>
                </div>
                <div class="col-12">
                  <label for="inputLink" class="form-label">Link Pendaftaran</label>
                  <input type="link" class="form-control" id="inputLink" readonly value="{{  $eventsData['link_pendaftaran'] }}">
                </div>

                <div class="col-12">
                  <label for="inputFile" class="form-label">Poster Event</label>
                  <div class="col-sm-10">
                    <button class="btn btn-info" type="button" onclick="preview('foto')"> Lihat poster </button>
                    <button class="btn btn-info" type="button" onclick="download('foto')"> Download poster </button>
                  </div>

                </div>
                @if (isset($eventsData['catatan']) && !is_null($eventsData['catatan']) && !empty($eventsData['catatan']))
                  <div class="col-12">
                    <label for="inputText" class="form-label">Alasan Penolakan</label>
                    <textarea class="form-control" id="inputTextarea" style="height: 100px;" readonly>{{  $eventsData['catatan'] }}</textarea>
                  </div>
                @endif
                <div class="row mb-3 justify-content-end">
                  <div class="col-sm-10 text-end"><br>

                    @if ($eventsData['status'] == 'diajukan' || $eventsData['status'] == 'proses')
                      <a href="/event/pengajuan" class="btn btn-secondary" style="margin-right: 5px;">Kembali</a>
                    @elseif ($eventsData['status'] == 'diterima' || $eventsData['status'] == 'ditolak')
                      <a href="/event/riwayat" class="btn btn-secondary" style="margin-right: 5px;">Kembali</a>
                    @endif
                    @if ($eventsData['status'] == 'diajukan')
                      <button type="button" class="btn btn-tambah" style="margin-right: 5px;" onclick="openProses({{ $eventsData['id_event'] }})"> Proses
                      </button>
                    @elseif ($eventsData['status'] == 'proses')
                      <button type="button" class="btn btn-tambah" style="margin-right: 5px;" onclick="openSetuju({{ $eventsData['id_event'] }})">Terima
                      </button>
                      <button type="button" class="btn btn-tolak" style="margin-right: 5px;" onclick="openTolak({{ $eventsData['id_event'] }})">Tolak
                      </button>
                    @endif
                  </div>
                </div>
              </form>
              <!-- End General Form Elements -->
            </div>
          </div>

        </div>
      </div>
    </section>

  </main><!-- End #main -->
  <!-- start modal proses -->
  <div class="modal fade" id="modalProses" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Konfirmasi Proses Pengajuan Event</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          Apakah anda yakin ingin memproses pengajuan event?
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <form onsubmit="proses(event, 'proses')">
            <input type="hidden" name="id_event" id="inpEventP">
            <button type="submit" class="btn btn-tambah">Proses</button>
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
          Apakah anda yakin ingin menerima pengajuan ini?
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <form onsubmit="proses(event, 'diterima')">
            <input type="hidden" name="id_event" id="inpEventS">
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
            <input type="hidden" name="id_event" id="inpEventT">
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
  <div id="preloader" style="display: none;"></div>
  <div id="greenPopup" style="display:none"></div>
  <div id="redPopup" style="display:none"></div>
  <script src="{{ asset($tPath.'js/popup.js') }}"></script>
  <script>
    var modalProses = document.getElementById('modalProses');
    var modalSetuju = document.getElementById('modalSetuju');
    var modalTolak = document.getElementById('modalTolak');
    var inpEventP = document.getElementById('inpEventP');
    var inpEventS = document.getElementById('inpEventS');
    var inpEventT = document.getElementById('inpEventT');
    function showLoading(){
      document.querySelector('div#preloader').style.display = 'block';
    }
    function closeLoading(){
      document.querySelector('div#preloader').style.display = 'none';
    }
    function openProses(dataU, ) {
      inpEventP.value = dataU;
      var myModal = new bootstrap.Modal(modalProses);
      myModal.show();
    }

    function openSetuju(dataU) {
      inpEventS.value = dataU;
      var myModal = new bootstrap.Modal(modalSetuju);
      myModal.show();
    }
    
    function openTolak(dataU) {
      inpEventT.value = dataU;
      var myModal = new bootstrap.Modal(modalTolak);
      myModal.show();
    }
    function closeModal(dataU) {
      var myModal = new bootstrap.Modal(dataU);
      myModal.hide();
    }
    //preview data
    function preview(desc) {
      if (desc != 'ktp' && desc != 'foto' && desc != 'surat') {
        console.log('invalid description');
        return;
      }
      var xhr = new XMLHttpRequest();
      var requestBody = {
        email: email,
        id_event: idEvent,
        item: 'event',
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
    //download data
    function download(desc) {
      if (desc != 'ktp' && desc != 'foto' && desc != 'surat') {
        console.log('invalid description');
        return;
      }
      var xhr = new XMLHttpRequest();
      var requestBody = {
        email: email,
        id_event: idEvent,
        item: 'event',
        deskripsi: desc
      };
      // open the request
      xhr.open('POST', domain + "/download", true);
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
      var Id = event.target.querySelector('[name="id_event"]').value;
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
        _method: 'PUT',
        email: email,
        id_event: Id,
        keterangan: ket,
        catatan:catatan
      };
      //open the request
      xhr.open('PUT', domain + "/event/pengajuan")
      xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken);
      xhr.setRequestHeader('Content-Type', 'application/json');
      //send the form data
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
                window.location.href = "/event/pengajuan";
              }else if(ket == 'diterima' || ket == 'ditolak'){
                window.location.href = "/event/pengajuan";
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
  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="{{ asset($tPath.'assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset($tPath.'assets/vendor/simple-datatables/simple-datatables.js') }}"></script>
  <script src="{{ asset($tPath.'assets/vendor/tinymce/tinymce.min.js') }}"></script>

  <!-- Template Main JS File -->
  <script src="{{ asset($tPath.'assets/js/main.js') }}"></script>

</body>

</html>