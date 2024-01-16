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
        $nav = 'tempat';
      @endphp
      @include('component.sidebar')
    </ul>
  </aside><!-- End Sidebar-->
    <main id="main" class="main">
    <div class="pagetitle">
            <h1>Data Tempat</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/dashboard">Beranda</a></li>
                    <li class="breadcrumb-item"><a href="/tempat">Kelola Tempat</a></li>
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
                          <a href="/tempat/tambah">
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
                                  @php $no = 1; @endphp
                                  @foreach ($tempatData as $tempat)
                                    <tr>
                                      <td>{{ $no++ }}</td>
                                      <td>{{ $tempat['nama_tempat'] }}</td>
                                      <td>{{ $tempat['alamat_tempat'] }}</td>
                                      <td>
                                        <a href="/tempat/detail/{{ $tempat['id_tempat'] }}" class="btn btn-lihat"><i class="bi bi-eye-fill"></i>   Lihat</a>
                                        <a href="/tempat/edit/{{ $tempat['id_tempat'] }}" class="btn btn-edit"><i class="bi bi-pencil-fill"></i>   Edit</a>
                                        <button type="button" class="btn btn-tolak" onclick="openDelete({{ $tempat['id_tempat'] }} )"> <i class="bi bi-trash-fill"></i>   Hapus</button>
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
          <form action="/web/tempat/tempat" id="deleteForm" method="POST">
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
    @include('component.footer')
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
      xhr.open('PUT', domain + "/event/pengajuan")
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
  <!-- Vendor JS Files -->
  <script src="{{ asset($tPath.'assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset($tPath.'assets/vendor/simple-datatables/simple-datatables.js') }}"></script>
  <script src="{{ asset($tPath.'assets/vendor/tinymce/tinymce.min.js') }}"></script>

  <!-- Template Main JS File -->
  <script src="{{ asset($tPath.'assets/js/main.js') }}"></script>

</body>

</html>