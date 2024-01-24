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
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
  <link href="{{ asset($tPath.'img/icon/utama/logo.png') }}" rel="icon">

  <!-- Google Fonts -->
  <!-- <link href="https://fonts.gstatic.com" rel="preconnect"> -->
  <link
    href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Jost:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
    rel="stylesheet">
  <!-- Vendor CSS Files -->
  <link href="{{ asset($tPath.'assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset($tPath.'assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="{{ asset($tPath.'assets/css/style.css') }}" rel="stylesheet">
  <link href="{{ asset($tPath.'css/popup.css') }}" rel="stylesheet">
  <style>
    div.drag#divImg{
      border:4px solid black;
    }
    #divImg{
      position: relative;
      left:0;
      max-width: 300px;
      width:100%;
      max-height: 200px;
      height: 200px;
      cursor:pointer;
    }
    #divText{
      position: relative;
      left:50%;
      top:50%;
      translate: -50% -50%;
      font-size:22px;
      text-align: center;
      display:flex;
      flex-direction: column;
    }
    #divText i{
      font-size:65px;
    }
    #inpImg {
      display: block;
      margin: auto;
      max-width: 100%;
      max-height: 100%;
      width: auto;
      height: auto;
    }
    @media (max-width: 480px) {
    }
    @media (min-width: 481px) and (max-width: 767px) {
    }
    @media (min-width: 768px) {
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
        $nav = 'admin';
      @endphp
      @include('component.sidebar')
    </ul>
  </aside><!-- End Sidebar-->

  <main id="main" class="main">

  <div class="pagetitle">
      <h1>Edit Admin</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/dashboard">Beranda</a></li>
          <li class="breadcrumb-item"><a href="/admin">Kelola Admin</a></li>
          <li class="breadcrumb-item active">Edit Admin</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section">
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Edit Admin</h5>
              <form onsubmit="upload(event, '{{ $adminData['email'] }}')">
                <div class="row mb-3">
                  <label for="inputText" class="col-sm-2 col-form-label">Nama Lengkap</label>
                  <div class="col-sm-10">
                  <input type="text" class="form-control" name="nama" placeholder="Nama Lengkap" value="{{ $adminData['nama_lengkap']}}">
                  </div>
                </div>
                <div class="row mb-3">
                  <label for="inputText" class="col-sm-2 col-form-label">No Handphone</label>
                  <div class="col-sm-10">
                  <input type="text" class="form-control" name="phone" placeholder="No Handphone" value="{{ $adminData['no_telpon']}}">
                  </div>
                </div>
                <fieldset class="row mb-3">
                  <legend class="col-form-label col-sm-2 pt-0">Jenis Kelamin</legend>
                  <div class="col-sm-10">
                    <div class="form-check">
                    <input class="form-check-input" type="radio" name="jenisK" value="laki-laki" {{ ($adminData['jenis_kelamin'] == 'laki-laki') ? 'checked' : ''}}>
                    <label class="form-check-label" for="gridRadios1">
                      Laki-Laki
                    </label>
                  </div>
                  <div class="form-check">
                      <input class="form-check-input" type="radio" name="jenisK" value="perempuan" {{ ($adminData['jenis_kelamin'] == 'perempuan') ? 'checked' : ''}}>
                      <label class="form-check-label" for="gridRadios2">
                        Perempuan
                      </label>
                    </div>
                  </div>
                </fieldset>
                <div class="row mb-3">
                  <label for="inputText" class="col-sm-2 col-form-label">Tempat Lahir</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" name="tempatL" placeholder="Tempat Lahir" value="{{ $adminData['tempat_lahir']}}">
                  </div>
                </div>
                <div class="row mb-3">
                  <label for="inputDate" class="col-sm-2 col-form-label">Tanggal Lahir</label>
                  <div class="col-sm-10">
                    <input type="date" class="form-control" name="tanggalL" placeholder="Tanggal Tanggal" value="{{ $adminData['tanggal_lahir']}}">
                  </div>
                </div>
                <div class="row mb-3">
                  <label class="col-sm-2 col-form-label">Role</label>
                  <div class="col-sm-10">
                  <select class="form-select" name="role" aria-label="Default select example">
                      <option value="admin event" {{ ($adminData['role'] == 'admin event') ? 'selected' : ''}}>Admin Event</option>
                      <option value="admin tempat" {{ ($adminData['role'] == 'admin tempat') ? 'selected' : ''}}>Admin Tempat</option>
                      <option value="admin seniman" {{ ($adminData['role'] == 'admin seniman') ? 'selected' : ''}}>Admin Seniman</option>
                  </select>
                  </div>
                </div>
                <div class="row mb-3">
                  <label for="inputEmail" class="col-sm-2 col-form-label">Email</label>
                  <div class="col-sm-10">
                    <input type="email" class="form-control" name='email' placeholder="Email" value="{{ $adminData['email']}}">
                  </div>
                </div>
                <div class="row mb-3">
                  <label for="inputPassword" class="col-sm-2 col-form-label">Password</label>
                  <div class="col-sm-10">
                    <input type="password" class="form-control" name='pass' placeholder="Password">
                  </div>
                </div>
                <div class="row mb-3">
                  <label for="inputPassword" class="col-sm-2 col-form-label">foto</label>
                  <div class="col-sm-10">
                    <div id="divImg" ondrop="dropHandler(event)" ondragover="dragHandler(event,'over')" ondragleave="dragHandler(event,'leave')">
                      <input class="form-control" type="file" multiple="false" id="inpFile" name="foto" style="display:none">
                      <img src="{{ '/public/download/foto/'. $adminData['id_user'] }}" alt="" id="inpImg" class="d-block">
                    </div>
                  </div>
                </div>
                <div class="row mb-3">
                <button type="submit" class="btn btn-success" name="editAdmin">Edit Data</button>
                </div>

              </form><!-- End General Form Elements -->

            </div>
          </div>

        </div>
      </div>
    </section>

  </main>
  <!-- End #main -->

  <!-- ======= Footer ======= -->
  <footer id="footer" class="footer">
    <div class="copyright">
      &copy; Copyright <strong><span>Huffle Puff</span></strong>. All Rights Reserved
    </div>
  </footer>

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>
    <div id="preloader" style="display: none;"></div>
    <div id="greenPopup" style="display:none"></div>
    <div id="redPopup" style="display:none"></div>
    <!-- Vendor JS Files -->
    <script src="{{ asset($tPath.'assets/vendor/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ asset($tPath.'assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset($tPath.'assets/vendor/tinymce/tinymce.min.js') }}"></script>

    <!-- Template Main JS File -->
    <script src="{{ asset($tPath.'assets/js/main.js') }}"></script>
  <script src="{{ asset($tPath.'js/popup.js') }}"></script>
  <script>
    const maxSizeInBytes = 4 * 1024 * 1024; //max file 4MB
    var divImg = document.getElementById('divImg');
    var inpFile = document.getElementById('inpFile');
    var imgText = document.getElementById('imgText');
    var fileImg = '';
    var uploadStat = false;
    divImg.addEventListener("click", function(){
      inpFile.click();
    });
    var users = {!! json_encode($adminData) !!};
    function upload(event, inpEmail){
      event.preventDefault();
      var inpNama = document.querySelector("input[name='nama']").value;
      var inpTLP = document.querySelector("input[name='phone']").value;
      var inpJenis = document.querySelector("input[name='jenisK']").value;
      var inpTempat = document.querySelector("input[name='tempatL']").value;
      var inpTanggal = document.querySelector("input[name='tanggalL']").value;
      var inpRole = document.querySelector("select[name='role']").value;
      var inpNewEmail = document.querySelector("input[name='email']").value;
      var inpPass = document.querySelector("input[name='pass']").value;
      //check data if edit or not
      if((fileImg === null || fileImg === '') && inpNama === users.nama_lengkap && inpTLP === users.no_telpon && inpJenis === users.jenis_kelamin && inpTempat === users.tempat_lahir && inpTanggal === users.tanggal_lahir && inpRole === users.role && inpNewEmail === users.email){
          showRedPopup('Data belum diubah');
          return;
      }
      uploadStat = true;
      const formData = new FormData();
      formData.append('email',email);
      formData.append('email_admin', inpEmail);
      formData.append('nama_lengkap', document.querySelector('input[name="nama"]').value);
      formData.append('jenis_kelamin', document.querySelector('input[name="jenisK"]:checked').value);
      formData.append('no_telpon', document.querySelector('input[name="phone"]').value);
      formData.append('tempat_lahir', document.querySelector('input[name="tempatL"]').value);
      formData.append('tanggal_lahir', document.querySelector('input[name="tanggalL"]').value);
      formData.append('role', document.querySelector('select[name="role"]').value);
      formData.append('password', document.querySelector('input[name="pass"]').value);
      formData.append('email_new', document.querySelector('input[name="email"]').value);
      if(fileImg !== null && fileImg !== ''){
        formData.append('foto', fileImg, fileImg.name);
      }
      const xhr = new XMLHttpRequest();
      xhr.open('POST', '/admin/edit', true);
      xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken);
      xhr.onload = function () {
        if (xhr.status === 200) {
          uploadStat = false;
          showGreenPopup(JSON.parse(xhr.responseText));
          setTimeout(() => {
                window.location.href = '/admin';
            }, 1000);
          return;
        } else {
          uploadStat = false;
          showRedPopup(JSON.parse(xhr.responseText));
          return;
        }
      };
      xhr.onerror = function () {
        uploadStat = false;
        showRedPopup('Request gagal');
        return;
      };
      xhr.send(formData);
    }
    inpFile.addEventListener('change',function(e){
      if (e.target.files.length === 1) {
        const file = e.target.files[0];
        if (file.type.startsWith('image/')) {
          if (file.size <= maxSizeInBytes) {
            const reader = new FileReader();
            reader.onload = function (event) {
            document.getElementById('inpImg').src = event.target.result;
          };
          reader.readAsDataURL(file);
          fileImg = file;
          //delete inside box
          divImg.style.borderStyle = "none";
          divImg.style.borderWidth = "0px";
          divImg.style.borderColor = "transparent";
          } else {
            showRedPopup('Ukuran maksimal gambar 4MB !');
          }
        } else {
          showRedPopup('File harus Gambar !');
        }
      }
    });
    function dropHandler(event) {
      event.preventDefault();
      if (event.dataTransfer.items) {
        const file = event.dataTransfer.items[0].getAsFile();
        if (file.type.startsWith('image/')) {
          const reader = new FileReader();
          reader.onload = function (event) {
            document.getElementById('inpImg').src = event.target.result;
          };
          reader.readAsDataURL(file);
          fileImg = file;
          //delete inside box
          divImg.style.borderStyle = "none";
          divImg.style.borderWidth = "0px";
          divImg.style.borderColor = "transparent";
        } else {
          showRedPopup('File harus Gambar !');
        }
      }
    }
    function dragHandler(event, con){
      event.preventDefault();
      if(con == 'over'){
        imgText.innerText = 'Jatuhkan file';
        divImg.classList.add('drag');
      }else if(con == 'leave'){
        imgText.innerText = 'Pilih atau jatuhkan file gambar tempat';
        divImg.classList.remove('drag');
      }
    }
    </script>
    <script>
      document.addEventListener('DOMContentLoaded', function () {
        var currentPageURL = window.location.href;
        var menuLinks = document.querySelectorAll('.nav-link');
        menuLinks.forEach(function (menuLink) {
          var menuLinkURL = menuLink.getAttribute('href');
          if (currentPageURL === menuLinkURL) {
            menuLink.parentElement.classList.add('active');
          }
        });
      });
    </script>
</body>

</html>