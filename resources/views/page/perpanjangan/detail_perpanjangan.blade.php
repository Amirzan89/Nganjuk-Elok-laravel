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
    <link href="{{ asset($tPath.'assets/css/nomor-induk.css') }}" rel="stylesheet">
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
                $nav = 'seniman';
            @endphp
            @include('component.sidebar')
        </ul>
    </aside><!-- End Sidebar-->

    <main id="main" class="main">
        <div class="pagetitle">
            <h1>Detail data seniman</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/dashboard">Beranda</a></li>
                    <li class="breadcrumb-item"><a href="/seniman">Kelola Seniman</a></li>
                    <?php if ($perpanjangan['status'] == 'diajukan' || $perpanjangan['status'] == 'proses') { }}
                        <li class="breadcrumb-item"><a href="/seniman/perpanjangan">Verifikasi Perpanjangan</a></li>
                    <?php // } else if ($perpanjangan['status'] == 'diterima' || $perpanjangan['status'] == 'ditolak') { }}
                        <!-- <li class="breadcrumb-item"><a href="/seniman/riwayat">Riwayat Nomer Induk Seniman</a>
                        </li> -->
                    <?php } }}
                    <li class="breadcrumb-item active">Detail Data perpanjangan Seniman</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->
        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="row mb-3 d-flex justify-content-center align-items-center">
                        <?php if ($perpanjangan['status'] == 'diterima') { }}
                            <span class="badge bg-terima"><i class="bi bi-check-circle-fill"></i> Diterima</span>
                        <?php } else if ($perpanjangan['status'] == 'ditolak') { }}
                            <span class="badge bg-tolak"><i class="bi bi-x-circle-fill"></i> Ditolak</span>
                            </li>
                        <?php } }}
                    </div>
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title"></h5>
                                <!-- Multi Columns Form -->
                                <form class="row g-3">
                                    <div class="col-md-12">
                                        <label for="nik" class="form-label">Nomor Induk Kependudukan</label>
                                        <input type="text" class="form-control" id="nik" readonly value="}} base64_decode($perpanjangan['nik']) }}">
                                    </div>
                                    <br>
                                    <div class="col-md-12">
                                        <label for="nama_seniman" class="form-label">Nama Lengkap</label>
                                        <input type="text" class="form-control" id="nama_seniman" readonly value="}} $perpanjangan['nama_seniman'] }}">
                                    </div>
                                    <br>
                                    <div class="col-md-12">
                                        <label for="nik" class="form-label">Nomor Induk Seniman</label>
                                        <input type="text" class="form-control" id="nik" readonly value="}} $perpanjangan['nomor_induk'] }}">
                                    </div>
                                    <br>
                                    <div class="col-12">
                                        <label for="surat_keterangan" class="form-label">Surat Keterangan</label>
                                        <div class="col-sm-10">
                                            <button class="btn btn-info" type="button" onclick="preview('surat') "> Lihat surat
                                                keterangan </button>
                                            <button class="btn btn-info" type="button" onclick="download('surat') "> Download
                                                surat keterangan </button>
                                        </div>
                                    </div>
                                    <br>
                                    <div class=" col-12">
                                        <label for="ktp_seniman" class="form-label">Foto Kartu Tanda Penduduk</label>
                                        <div class="col-sm-10">
                                            <button class="btn btn-info" type="button" onclick="preview('ktp')"> Lihat Foto
                                                KTP</button>
                                            <button class="btn btn-info" type="button" onclick="download('ktp')"> Download Foto
                                                KTP</button>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="col-12">
                                        <label for="pass_foto" class="form-label">Pas Foto 3x4</label>
                                        <div class="col-sm-10">
                                            <button class="btn btn-info" type="button" onclick="preview('foto')"> Lihat pass
                                                foto </button>
                                            <button class="btn btn-info" type="button" onclick="download('foto')"> Download pass
                                                foto </button>
                                        </div>
                                    </div>
                                    <br>
                                    <?php if (isset($perpanjangan['catatan']) && !is_null($perpanjangan['catatan']) && !empty($perpanjangan['catatan'])) { }}
                                        <div class="col-12">
                                            <label for="inputText" class="form-label">Alasan Penolakan</label>
                                            <textarea class="form-control" id="inputTextarea" style="height: 100px;" readonly>}} $perpanjangan['catatan'] }}</textarea>
                                        </div>
                                    <?php } }}
                                    <div class="row mb-3 justify-content-end">
                                        <div class="col-sm-10 text-end">
                                            <br>
                                            <?php if ($perpanjangan['status'] == 'diajukan' || $perpanjangan['status'] == 'proses') { }}
                                                <a href="/seniman/perpanjangan" class="btn btn-secondary" style="margin-right: 5px;"><i></i>kembali</a>
                                            <?php // } else if ($perpanjangan['status'] == 'diterima' || $perpanjangan['status'] == 'ditolak') { }}
                                                <!-- <a href="/seniman/riwayat" class="btn btn-secondary" style="margin-right: 5px;"><i></i>kembali</a> -->
                                            <?php } }}
                                            <?php if ($perpanjangan['status'] == 'diajukan') { }}
                                                <button type="button" class="btn btn-tambah" style="margin-right: 5px;" onclick="openProses(}} $perpanjangan['id_seniman'] }})">Proses
                                                </button>
                                            <?php } else if ($perpanjangan['status'] == 'proses') { }}
                                                <button type="button" class="btn btn-tambah" style="margin-right: 5px;" onclick="openSetuju(}} $perpanjangan['id_seniman'] }})">Terima
                                                </button>
                                                <button type="button" class="btn btn-tolak" style="margin-right: 5px;" onclick="openTolak(}} $perpanjangan['id_seniman'] }})">Tolak
                                                </button>
                                            <?php } }}
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
                    <h5 class="modal-title">Konfirmasi Proses Pengajuan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin memproses data seniman?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <form action="/web/seniman/seniman" id="prosesForm" method="POST">
                        <input type="hidden" name="_method" value="PUT">
                        <input type="hidden" name="id_user" value="}} $userAuth['id_user'] }}">
                        <input type="hidden" name="id_perpanjangan" value="}} $id }}">
                        <input type="hidden" name="id_seniman" id="inpSenimanP">
                        <input type="hidden" name="keterangan" value="proses">
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
                    <form action="/web/seniman/seniman" id="prosesForm" method="POST">
                        <input type="hidden" name="_method" value="PUT">
                        <input type="hidden" name="id_user" value="}} $userAuth['id_user'] }}">
                        <input type="hidden" name="id_perpanjangan" value="}} $id }}">
                        <input type="hidden" name="id_seniman" id="inpSenimanS">
                        <input type="hidden" name="keterangan" value="diterima">
                        <input type="hidden" name="desc" value="perpanjangan">
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
                <form action="/web/seniman/seniman" id="prosesForm" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title">Tolak Pengajuan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" style="text-align: left;">
                        <label for="catatan" class="form-label">Alasan penolakan</label>
                        <textarea class="form-control" name="catatan" id="alamat_seniman" placeholder="Masukkan Alasan Penolakan" style="height: 100px;"></textarea>
                    </div>
                    <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <input type="hidden" name="_method" value="PUT">
                        <input type="hidden" name="id_user" value="}} $userAuth['id_user'] }}">
                        <input type="hidden" name="id_perpanjangan" value="}} $id }}">
                        <input type="hidden" name="id_seniman" id="inpSenimanT">
                        <input type="hidden" name="keterangan" value="ditolak">
                        <input type="hidden" name="desc" value="perpanjangan">
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
    <div id="greenPopup" style="display:none"></div>
    <div id="redPopup" style="display:none"></div>
    <script src="{{ asset($tPath.'js/popup.js') }}"></script>
    <script>
        var modalProses = document.getElementById('modalProses');
        var modalSetuju = document.getElementById('modalSetuju');
        var modalTolak = document.getElementById('modalTolak');
        var inpSenimanP = document.getElementById('inpSenimanP');
        var inpSenimanS = document.getElementById('inpSenimanS');
        var inpSenimanT = document.getElementById('inpSenimanT');

        function openProses(dataU, ) {
            inpSenimanP.value = dataU;
            var myModal = new bootstrap.Modal(modalProses);
            myModal.show();
        }

        function openSetuju(dataU) {
            inpSenimanS.value = dataU;
            var myModal = new bootstrap.Modal(modalSetuju);
            myModal.show();
        }

        function openTolak(dataU) {
            inpSenimanT.value = dataU;
            var myModal = new bootstrap.Modal(modalTolak);
            myModal.show();
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
                id_seniman: idSeniman,
                item: 'perpanjangan',
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
                id_seniman: idSeniman,
                item: 'perpanjangan',
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
    </script>

    <!-- Vendor JS Files -->
    <script src="{{ asset($tPath.'assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset($tPath.'assets/vendor/simple-datatables/simple-datatables.js') }}"></script>
    <script src="{{ asset($tPath.'assets/vendor/tinymce/tinymce.min.js') }}"></script>

    <!-- Template Main JS File -->
    <script src="{{ asset($tPath.'assets/js/main.js') }}"></script>

</body>

</html>