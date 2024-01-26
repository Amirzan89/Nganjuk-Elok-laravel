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
    <link href="{{ asset($tPath.'assets/css/tempat.css') }}" rel="stylesheet">
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
                $nav = 'tempat';
            @endphp
            @include('component.sidebar')
        </ul>
    </aside><!-- End Sidebar-->

    <main id="main" class="main">
        <div class="pagetitle">
            <h1>Detail Peminjaman</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/dashboard">Beranda</a></li>
                    <li class="breadcrumb-item"><a href="/sewa">Kelola Tempat</a></li>
                    @if ($sewaData['status'] == 'diajukan' || $sewaData['status'] == 'proses')
                        <li class="breadcrumb-item"><a href="/sewa/pengajuan">Verifikasi Peminjaman</a></li>
                    @elseif ($sewaData['status'] == 'diterima' || $sewaData['status'] == 'ditolak')
                        <li class="breadcrumb-item"><a href="/sewa/riwayat">Riwayat Peminjaman</a></li>
                    @endif
                    <li class="breadcrumb-item active">Detail Data Peminjaman</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->
        <section class="section dashboard">
            <div class="row">
                <div class="col-lg-12">
                    <div class="row mb-3 d-flex justify-content-center align-items-center">
                        @if ($sewaData['status'] == 'diterima')
                            <span class="badge bg-terima"><i class="bi bi-check-circle-fill"></i> Diterima</span>
                        @elseif ($sewaData['status'] == 'ditolak')
                            <span class="badge bg-tolak"><i class="bi bi-x-circle-fill"></i> Ditolak</span>
                        @endif
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <div class="card-body d-flex justify-content-center align-items-center">
                                <h5 class="card-title text-center">Detail Verifikasi Peminjaman</h5>
                            </div>
                            <!-- General Form Elements -->
                            <form>
                                <div class="col-md-12">
                                    <label for="nama_peminjam" class="form-label">Nama Lengkap</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" readonly value="{{ $sewaData['nama_peminjam'] }}">
                                    </div>
                                </div>
                                <br>
                                <div class="col-md-12">
                                    <label for="nik_sewa" class="form-label">Nomor Induk Kependudukan</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" readonly value="{{ $sewaData['nik_sewa'] }}">
                                    </div>
                                </div>
                                <br>
                                <div class="col-md-12">
                                    <label for="instansi" class="form-label">Instansi</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" readonly value="{{ $sewaData['instansi'] }}">
                                    </div>
                                </div>
                                <br>
                                <div class="col-md-12">
                                    <label for="nama_kegiatan_sewa" class="form-label">Nama Kegiatan</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" readonly value="{{ $sewaData['nama_kegiatan_sewa'] }}">
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-md-9">
                                        <label for="tempat" class="form-label">Nama Tempat</label>
                                        <div class="col-sm-12">
                                            <input type="text" class="form-control" readonly value="{{ $sewaData['nama_tempat'] }}">
                                        </div>
                                    </div>
                                    <br>
                                    <div class="col-md-3">
                                        <label for="jumlah_peserta" class="form-label">Jumlah Peserta</label>
                                        <div class="col-sm-12">
                                            <input type="number" class="form-control" readonly value="{{ $sewaData['jumlah_peserta'] }}">
                                        </div>
                                    </div>
                                    <br>
                                    <div class="col-md-6">
                                        <label for="tgl_awal_peminjaman" class="form-label">Tanggal Awal</label>
                                        <input type="text" class="form-control" readonly value="{{ $sewaData['tanggal_awal'] }}">
                                    </div>
                                    <br>
                                    <div class="col-md-6">
                                        <label for="tgl_akhir_peminjaman" class="form-label">Tanggal Akhir</label>
                                        <input type="text" class="form-control" readonly value="{{ $sewaData['tanggal_akhir'] }}">
                                    </div>
                                    <br>
                                    <div class="col-md-6">
                                        <label for="tgl_awal_peminjaman" class="form-label">Waktu Awal</label>
                                        <input type="time-local" class="form-control" readonly value="{{ $sewaData['waktu_awal'] }}">
                                    </div>
                                    <br>
                                    <div class="col-md-6">
                                        <label for="tgl_akhir_peminjaman" class="form-label">Waktu Akhir</label>
                                        <input type="time-loc   al" class="form-control" readonly value="{{ $sewaData['waktu_akhir'] }}">
                                    </div>  
                                    <br>
                                    <div class="col-md-12">
                                        <label for="surat_keterangan" class="form-label">Surat Keterangan</label>
                                        <div class="col-sm-10">
                                            <button class="btn btn-info" type="button" onclick="preview('surat')"> Lihat surat keterangan </button>
                                            <button class="btn btn-info" type="button" onclick="download('surat')"> Download surat keterangan </button>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="col-md-12">
                                        <label for="deskripsi_sewa_tempat" class="form-label">Deskripsi Kegiatan</label>
                                        <div class="col-sm-12">
                                            <textarea class="form-control" style="height: 100px" readonly>{{ $sewaData['deskripsi_sewa_tempat'] }}</textarea>
                                        </div>
                                    </div>
                                    <br>
                                    @if (isset($sewaData['catatan']) && !is_null($sewaData['catatan']) && !empty($sewaData['catatan']))
                                        <div class="col-12">
                                            <label for="inputText" class="form-label">Alasan Penolakan</label>
                                            <textarea class="form-control" id="inputTextarea" style="height: 100px;" readonly>{{ $sewaData['catatan'] }}</textarea>
                                        </div>
                                        <br>
                                    @endif
                                    @if (isset($sewaData['kode_pinjam']) && !is_null($sewaData['kode_pinjam']) && !empty($sewaData['kode_pinjam']))
                                    <div class="col-md-12">
                                        <label for="nik" class="form-label">Kode Surat</label>
                                        <input type="text" class="form-control" id="nik" readonly value="{{ $sewaData['kode_pinjam'] }}">
                                    </div>
                                    @endif
                                    <br><br><br><br>
                                    <div class="row mb-3 justify-content-end">
                                        <div class="col-sm-10 text-end">
                                            @if ($sewaData['status'] == 'diajukan' || $sewaData['status'] == 'proses')
                                                <a href="/sewa/pengajuan" class="btn btn-secondary" style="margin-right: 5px;">kembali</a>
                                            @elseif ($sewaData['status'] == 'diterima' || $sewaData['status'] == 'ditolak')
                                                <a href="/sewa/riwayat" class="btn btn-secondary" style="margin-right: 5px;">kembali</a>
                                            @endif
                                            @if ($sewaData['status'] == 'diajukan')
                                                <button type="button" class="btn btn-tambah" style="margin-right: 5px;" onclick="openProses({{ $sewaData['id_sewa'] }})">Proses
                                                </button>
                                            @elseif ($sewaData['status'] == 'proses')
                                                <button type="button" class="btn btn-tambah" style="margin-right: 5px;" onclick="openSetuju({{ $sewaData['id_sewa'] }})">Terima
                                                </button>
                                                <button type="button" class="btn btn-tolak" style="margin-right: 5px;" onclick="openTolak({{ $sewaData['id_sewa'] }})">Tolak
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </form>
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
                    <h5 class="modal-title">Konfirmasi Proses Peminjaman Tempat</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin memproses data sewa tempat?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <form onsubmit="proses(event, 'proses')">
                        <input type="hidden" name="id_sewa" id="inpSewaP">
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
                    Apakah anda yakin ingin menerima pengajuan peminjaman tempat ini?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <form onsubmit="proses(event, 'diterima')">
                        <input type="hidden" name="id_sewa" id="inpSewaS">
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
                        <input type="hidden" name="id_sewa" id="inpSewaT">
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
        var inpSewaP = document.getElementById('inpSewaP');
        var inpSewaS = document.getElementById('inpSewaS');
        var inpSewaT = document.getElementById('inpSewaT');
        function showLoading(){
            document.querySelector('div#preloader').style.display = 'block';
        }
        function closeLoading(){
            document.querySelector('div#preloader').style.display = 'none';
        }
        function openProses(dataU, ) {
            inpSewaP.value = dataU;
            var myModal = new bootstrap.Modal(modalProses);
            myModal.show();
        }

        function openSetuju(dataU) {
            inpSewaS.value = dataU;
            var myModal = new bootstrap.Modal(modalSetuju);
            myModal.show();
        }

        function openTolak(dataU) {
            inpSewaT.value = dataU;
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
                id_sewa: idSewa,
                item: 'sewa',
                deskripsi: desc
            };
            //open the request
            xhr.open('POST', domain + "/preview/sewa");
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
            if (desc != 'ktp' && desc != 'foto' && desc != 'surat') {
                console.log('invalid description');
                return;
            }
            var xhr = new XMLHttpRequest();
            var requestBody = {
                email: email,
                id_sewa: idSewa,
                item: 'sewa',
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
                            // Assuming JSON response
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
            var Id = event.target.querySelector('[name="id_sewa"]').value;
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
                id_sewa: Id,
                keterangan: ket,
                catatan:catatan
            };
            xhr.open('PUT', domain + "/sewa/pengajuan")
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
                            window.location.href = "/sewa/pengajuan";
                        }else if(ket == 'diterima' || ket == 'ditolak'){
                            window.location.href = "/sewa/pengajuan";
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