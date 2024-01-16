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
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Jost:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
  <!-- Vendor CSS Files -->
  <link href="{{ asset($tPath.'assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset($tPath.'assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
  <link href="{{ asset($tPath.'assets/vendor/simple-datatables/style.css') }}" rel="stylesheet">
  <link href="{{ asset($tPath.'assets/css/tempat.css') }}" rel="stylesheet">
  <link href="{{ asset($tPath.'css/style.css') }}" rel="stylesheet">
  <!-- Template Main CSS File -->
  <link href="{{ asset($tPath.'css/popup.css') }}" rel="stylesheet">
  <style>
    div.drag#divImg{
      border:4px solid black;
    }
    #divImg{
      position: relative;
      left:50%;
      transform: translateX(-50%);
      max-width: 800px;
      width:100%;
      max-height: 450px;
      height: 450px;
      border:4px dashed gray;
      cursor:pointer;
    }
    #divText{
      position: absolute;
      left:50%;
      top:50%;
      translate: -50% -50%;
      font-size:25px;
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
        $nav = 'tempat';
      @endphp
      @include('component.sidebar')
    </ul>
  </aside><!-- End Sidebar-->

  <main id="main" class="main">
    <div class="pagetitle">
        <h1>Tambah Tempat</h1>
        <nav>
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/dashboard">Beranda</a></li>
            <li class="breadcrumb-item"><a href="/sewa">Kelola Tempat</a></li>
            <li class="breadcrumb-item"><a href="/tempat/data">Data tempat</a></li>
            <li class="breadcrumb-item active">Tambah Data Tempat</li>
          </ol>
        </nav>
      </div><!-- End Page Title -->
    <section class="section dashboard">
      <div class="row">
        <div class="row align-items-top">
            <div class="col-lg-12">
              <!-- Default Card -->
              <div class="card">
                <div class="card-body"> <br>
                  <form action="/web/tempat/tempat" method="POST" class="row" enctype="multipart/form-data">
                    <div class="col-md-6">
                      <div id="carouselExampleSlidesOnly" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner" id="divImg" ondrop="dropHandler(event)" ondragover="dragHandler(event,'over')" ondragleave="dragHandler(event,'leave')">
                          <div id="divText">
                            <i class="fa-solid fa-images"></i>
                            <span id="imgText">Pilih atau jatuhkan file gambar tempat </span>
                          </div>
                          <div class="carousel-item active">
                            <input class="form-control" type="file" multiple="false" id="inpFile" name="foto" style="display:none">
                            <img src="" id="inpImg" class="d-block" alt="">
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <br>
                      <label for="inputText"><strong>Nama Tempat</strong></label>
                      <div class="row mb-3">
                        <div class="col-sm-12">
                          <input type="text" name="nama_tempat" class="form-control" placeholder="Masukkan Nama Tempat">
                        </div>
                      </div>
                      <label for="inputText"><strong>Alamat Tempat</strong></label>
                      <div class="row mb-3">
                        <div class="col-sm-12">
                          <input type="text" name="alamat" class="form-control" placeholder="Masukkan Alamat Tempat">
                        </div>
                      </div>
                      <label for="inputText"><strong>Nama Pengelola</strong></label>
                      <div class="row mb-3">
                        <div class="col-sm-12">
                          <input type="text" name="nama_pengelola" class="form-control" placeholder="Masukkan nama pengelola">
                        </div>
                      </div>
                      <label for="inputText"><strong>Nomor pengelola</strong></label>
                      <div class="row mb-3">
                        <div class="col-sm-12">
                          <input type="text" name="phone" class="form-control" placeholder="Masukkan Nomor pengelola">
                        </div>
                      </div>
                      <label for="inputText"><strong>Deskripsi Tempat</strong></label>
                      <div class="col-sm-12">
                        <textarea class="form-control" name="deskripsi" style="height: 80px" placeholder="Masukkan Deskripsi Tempat"></textarea>
                      </div>
                      <br>
                      <div class="row mb-3 justify-content-end">
                        <div class="col-sm-10 text-end">
                          <a href="/tempat/data" class="btn btn-secondary">Kembali</a>
                          <button type="button" class="btn btn-tambah" onclick="upload()">Tambah</button>
                        </div>
                      </div>
                    </div>
                  </form>
                </div>
              </div><!-- End Default Card -->
            </div>
          </div>
      </div>
    </section>

  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <footer id="footer" class="footer">
    @include('component.footer')
  </footer>

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i> </a>
  <div id="greenPopup" style="display:none"></div>
  <div id="redPopup" style="display:none"></div>
  <script src="{{ asset($tPath.'js/popup.js') }}"></script>
  <script>
    const maxSizeInBytes = 4 * 1024 * 1024; //max file 4MB
    var divText = document.getElementById('divText');
    var divImg = document.getElementById('divImg');
    var inpFile = document.getElementById('inpFile');
    var imgText = document.getElementById('imgText');
    var fileImg = '';
    var uploadStat = false;
    divImg.addEventListener("click", function(){
      inpFile.click();
    });
    function upload(){
      if(uploadStat){
        return;
      }
      //check file 
      if(fileImg == ''){
        showRedPopup('Gambar harus di isi !');
        return;
      }
      uploadStat = true;
      const formData = new FormData();
      formData.append('email',email);
      formData.append('nama_tempat', document.querySelector('input[name="nama_tempat"]').value);
      formData.append('alamat', document.querySelector('input[name="alamat"]').value);
      formData.append('nama_pengelola', document.querySelector('input[name="nama_pengelola"]').value);
      formData.append('phone', document.querySelector('input[name="phone"]').value);
      formData.append('deskripsi', document.querySelector('textarea[name="deskripsi"]').value);
      formData.append('foto', fileImg, fileImg.name);
      const xhr = new XMLHttpRequest();
      xhr.open('POST', '/tempat/tambah', true);
      xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken);
      xhr.onload = function () {
        if (xhr.status === 200) {
          showGreenPopup(JSON.parse(xhr.responseText));
          setTimeout(() => {
              window.location.href = '/tempat/data';
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
          divText.innerHTML = "";
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
          divText.innerHTML = "";
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
  <!-- Vendor JS Files -->
  <script src="{{ asset($tPath.'assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset($tPath.'assets/vendor/simple-datatables/simple-datatables.js') }}"></script>
  <script src="{{ asset($tPath.'assets/vendor/tinymce/tinymce.min.js') }}"></script>

  <!-- Template Main JS File -->
  <script src="{{ asset($tPath.'assets/js/main.js') }}"></script>

</body>

</html>