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
    var dataUsers = {!! json_encode($adminData) !!};
  </script>
  <!-- ======= Header ======= -->
  <header id="header" class="header fixed-top d-flex align-items-center">
    @include('component.header')
  </header><!-- End Header -->

  <!-- ======= Sidebar ======= -->
  <aside id="sidebar" class="sidebar">
    <ul class="sidebar-nav" id="sidebar-nav">
      @php
        $nav = 'admin';
      @endphp
      @include('component.sidebar')
    </ul>

  </aside><!-- End Sidebar-->

  <main id="main" class="main">

    <div class="pagetitle">
      <h1>Kelola Admin</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/dashboard">Beranda</a></li>
          <li class="breadcrumb-item active">Kelola Admin</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->
    <section class="section">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Daftar Admin</h5>
              <a href="/admin/tambah" class="btn btn-primary">
                <i class="bi bi-person-plus-fill"></i> Tambah Admin
              </a>
              <div class="col-lg-12">
                <table class="table datatable">
                  <thead>
                    <tr>
                      <th class="col"><strong>No.</th>
                      <th scope="col">Nama Pengguna</th>
                      <th scope="col">No Telpon</th>
                      <th scope="col">Role Admin</th>
                      <th scope="col">Email</th>
                      <th scope="col">Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    @php $no = 1; @endphp
                    @foreach ($adminData as $admin)
                      <tr>
                        <td>{{ $no++ }}</td>
                        <td>{{ $admin['nama_lengkap'] }}</td>
                        <td>{{ $admin['no_telpon'] }}</td>
                        <td>{{ $admin['role'] }}</td>
                        <td>{{ $admin['email'] }}</td>
                        <td>
                          <button type="button" class="btn btn-lihat" onclick="openDetail({{ $admin['id_user'] }})"> <i class="bi bi-eye-fill"></i> Lihat</button>
                          <a href="/admin/edit/{{ $admin['id_user'] }}" class="btn btn-edit"><i class="bi bi-pencil-fill"></i> Edit</a>
                          <button type="button" class="btn btn-danger" onclick="openDelete({{ $admin['id_user'] }})"> <i class="bi bi-trash-fill"></i> Hapus</button>
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
          </div>

        </div>
      </div>
    </section>

    <!-- start modal detail -->
    <div class="modal fade" id="modalDetail" tabindex="-1">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title"><strong>Detail Admin</strong></h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form method="" action="" enctype="" style="padding: 4px; padding-left: 4;">
            <div class="row mb-4">
              <label for="inputText" class="col-sm-2 col-form-label">Nama Lengkap</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="inpNamaDetail" name="nama" placeholder="Nama Lengkap" readonly>
              </div>
            </div>
            <div class="row mb-4">
              <label for="inputText" class="col-sm-2 col-form-label">No Handphone</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="inpPhoneDetail" name="phone" placeholder="No Handphone" readonly>
              </div>
            </div>
            <div class="row mb-4">
              <label for="inputText" class="col-sm-2 col-form-label">Jenis Kelamin</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="inpKelaminDetail" name="phone" placeholder="Jenis Kelamin" readonly>
              </div>
            </div>
            <div class="row mb-4">
              <label for="inputText" class="col-sm-2 col-form-label">Tempat / Tanggal Lahir</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="inpTTLDetail" name="phone" placeholder="Tempat / Tanggal Lahir" readonly>
              </div>
            </div>
            <div class="row mb-4">
              <label for="inputText" class="col-sm-2 col-form-label">Role</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="inpRoleDetail" name="phone" placeholder="Role" readonly>
              </div>
            </div>
            <div class="row mb-4">
              <label for="inputEmail" class="col-sm-2 col-form-label">Email</label>
              <div class="col-sm-10">
                <input type="email" class="form-control" id="inpEmailDetail" name='email' placeholder="Email" readonly>
              </div>
            </div>
          </form>
          <div class="modal-footer">
            <button type="cancel" class="btn btn-secondary" data-bs-dismiss="modal">Kembali</button>
          </div>
        </div>
      </div>
    </div>
    <!-- end modal detail -->
    <!-- start modal delete -->
    <div class="modal fade" id="modalDelete" tabindex="-1">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Konfirmasi hapus Admin</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            Apakah anda yakin ingin menghapus Admin ?
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <form onsubmit="proses(event)">
              <input type="hidden" name="id_user" id="inpUserDelete">
              <button type="submit" class="btn btn-tolak" name="hapusAdmin">Hapus</button>
            </form>
          </div>
        </div>
      </div>
    </div>
    <!-- end modal delete -->
  </main>
  <!-- End #main -->

  <!-- ======= Footer ======= -->/
  <footer id="footer" class="footer">
    <div class="copyright">
      &copy; Copyright <strong><span>Huffle Puff</span></strong>. All Rights Reserved
    </div>
  </footer>
  <div id="preloader" style="display: none;"></div>
  <div id="greenPopup" style="display:none"></div>
    <div id="redPopup" style="display:none"></div>
  <a href="#" class="back-to-top d-flex align-items-center justify-content-center">
    <i class="bi bi-arrow-up-short"></i>
  </a>
  <script src="{{ asset($tPath.'/js/popup.js') }}"></script>
  <script>
    var modalDetail = document.getElementById('modalDetail');
    var inpNamaDetail = document.getElementById('inpNamaDetail');
    var inpPhoneDetail = document.getElementById('inpPhoneDetail');
    var inpKelaminDetail = document.getElementById('inpKelaminDetail');
    var inpTTLDetail = document.getElementById('inpTTLDetail');
    var inpRoleDetail = document.getElementById('inpRoleDetail');
    var inpEmailDetail = document.getElementById('inpEmailDetail');
    var modalDelete = document.getElementById('modalDelete');
    var inpUserDelete = document.getElementById('inpUserDelete');
    function showLoading(){
      document.querySelector('div#preloader').style.display = 'block';
    }
    function closeLoading(){
      document.querySelector('div#preloader').style.display = 'none';
    }
    function openDetail(dataU) {
      dataUsers.forEach((dataUser) => {
        if (dataUser.id_user == dataU) {
          inpNamaDetail.value = dataUser['nama_lengkap'];
          inpPhoneDetail.value = dataUser['no_telpon'];
          inpKelaminDetail.value = dataUser['jenis_kelamin'];
          inpTTLDetail.value = dataUser['tempat_lahir'] + '/' + dataUser['tanggal_lahir'];
          inpRoleDetail.value = dataUser['role'];
          inpEmailDetail.value = dataUser['email'];
        }
      });
      var myModal = new bootstrap.Modal(modalDetail);
      myModal.show();
    }

    function openDelete(dataU) {
      inpUserDelete.value = dataU;
      var myModal = new bootstrap.Modal(modalDelete);
      myModal.show();
    }
    function proses(event) {
      event.preventDefault();
      var modals = '';
      var inpUser = event.target.querySelector('[name="id_user"]').value;
      var catatan = '';
      showLoading();
      var xhr = new XMLHttpRequest();
      var requestBody = {
        emailID: inpUser,
      };
      xhr.open('DELETE', domain + "/admin/delete")
      xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken);
      xhr.setRequestHeader('Content-Type', 'application/json');
      xhr.send(JSON.stringify(requestBody));
      xhr.onreadystatechange = function() {
        if (xhr.readyState == XMLHttpRequest.DONE) {
          if (xhr.status === 200) {
            closeLoading();
            var response = JSON.parse(xhr.responseText);
            showGreenPopup(response);
          } else {
            closeLoading();
            var response = JSON.parse(xhr.responseText);
            showRedPopup(response);
          }
        }
      }
    }
  </script>
  <!-- Vendor JS Files -->
  <script src="{{ asset($tPath.'assets/vendor/apexcharts/apexcharts.min.js') }}"></script>
  <script src="{{ asset($tPath.'assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset($tPath.'assets/vendor/tinymce/tinymce.min.js') }}"></script>
  <script src="{{ asset($tPath.'assets/vendor/simple-datatables/simple-datatables.js') }}"></script>

  <!-- Template Main JS File -->
  <script src="{{ asset($tPath.'assets/js/admin/main.js') }}"></script>
  <script>
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
  </script>
</body>

</html>