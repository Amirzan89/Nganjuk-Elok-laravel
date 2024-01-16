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
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Jost:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
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
            <h1>Formulir Peminjaman</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/dashboard">Beranda</a></li>
                    <li class="breadcrumb-item"><a href="/sewa">Kelola Tempat</a></li>
                    <li class="breadcrumb-item active">Formulir Peminjaman</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->


        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Formulir Peminjaman Tempat</h5>

                            <!-- Multi Columns Form -->
                            <form class="row g-3" method="POST" action="proses-tambah-tempat">
                                <div class="col-md-12">
                                    <label for="nama_peminjam" class="form-label">Nama Lengkap</label>
                                    <input type="text" class="form-control" readonly value="">
                                </div>
                                <div class="col-md-12">
                                    <label for="nik_sewa" class="form-label">Nomor Induk Kependudukan</label>
                                    <input type="text" class="form-control" readonly value="">
                                </div>
                                <div class="col-md-12">
                                    <label for="instansi" class="form-label">Instansi</label>
                                    <input type="text" class="form-control" readonly value="">
                                </div>
                                <div class="col-md-12">
                                    <label for="nama_kegiatan_sewa" class="form-label">Nama Kegiatan</label>
                                    <input type="text" class="form-control" readonly value="">
                                </div>
                                <div class="col-md-9">
                                    <label for="nama_organisasi" class="form-label">Nama Tempat</label>
                                    <input type="text" class="form-control" readonly value="">
                                </div>
                                <div class="col-md-3">
                                    <label for="jumlah_peserta" class="form-label">Jumlah Peserta</label>
                                    <input type="number" class="form-control" readonly value="">
                                </div>
                                <div class="col-md-6">
                                    <label for="tgl_awal_peminjaman" class="form-label">Tanggal Awal</label>
                                    <input type="date" class="form-control" readonly value="">
                                </div>
                                <div class="col-md-6">
                                    <label for="tgl_akhir_peminjaman" class="form-label">Tanggal Akhir</label>
                                    <input type="date" class="form-control" readonly value="">
                                </div>
                                <div class="col-md-6">
                                    <label for="waktu_awal_peminjaman" class="form-label">Waktu Awal</label>
                                    <input type="time" class="form-control" readonly value="">
                                </div>
                                <div class="col-md-6">
                                    <label for="waktu_akhir_peminjaman" class="form-label">Waktu Akhir</label>
                                    <input type="time" class="form-control" readonly value="">
                                </div>
                                <div class="col-md-12 ">
                                    <label for="deskripsi_sewa_tempat" class="form-label">Deskripsi Kegiatan</label>
                                    <textarea class="form-control" readonly value="" style="height: 100px;"></textarea>
                                </div>
                                <div class="col-12">
                                    <label for="surat_keterangan" class="form-label">Surat Keterangan</label>
                                    <input type="file" class="form-file-input form-control" id="surat_keterangan" readonly disabled>
                                </div>
                            </form>

                            <br><br>

                            <div class="col-lg-12 col-md-4">
                                <div class="card success-card revenue-card">
                                    <div class="card-body">
                                        <h6><strong>DENGAN PENGAJUAN FORMULIR INI, ANDA MENYETUJUI HAL- HAL BERIKUT
                                                :</strong></h6>
                                        <br>
                                        <h6>
                                            <ol start="1">
                                                <li>Peminjaman tempat ini bersifat gratis dan tidak dipungut biaya.
                                                </li>
                                                <li>Harus memiliki surat keterangan dari desa atau instansi.</li>
                                                <li>Mengajukan surat permohonan peminjaman minimal 1 hari sebelum tempat
                                                    digunakan.</li>
                                                <li>Persetujuan atau penolakan surat permohonan peminjaman dapat dilihat
                                                    paling lambat 1 x 24 jam setelah surat permohonan diajukan.</li>
                                                <li>Surat peminjaman tempat ini berlaku satu kali pemakaian tempat yang
                                                    dipinjam.</li>
                                                <li>Dilarang menggunakan tempat untuk kegiatan yang bertentangan dengan
                                                    kepribadian Bangsa Indonesia.</li>
                                                <li>Tidak melanggar tata tertib dan bertentangan dengan norma-norma
                                                    agama.</li>
                                                <li>Ruangan atau tempat yang dipinjam untuk kegiatan harus dijaga dan
                                                    dipelihara dengan sebaik-baiknya.</li>
                                            </ol>
                                        </h6>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3 justify-content-end">
                                <div class="col-sm-10 text-end">
                                    <a href="/sewa" class="btn btn-secondary">Kembali</a>
                                </div>
                            </div>
        </section>


    </main><!-- End #main -->
    <!-- ======= Footer ======= -->
    <footer id="footer" class="footer">
        @include('component.footer')
    </footer>

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

    <!-- Vendor JS Files -->
    <script src="{{ asset($tPath.'assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset($tPath.'assets/vendor/simple-datatables/simple-datatables.js') }}"></script>
    <script src="{{ asset($tPath.'assets/vendor/tinymce/tinymce.min.js') }}"></script>

    <!-- Template Main JS File -->
    <script src="{{ asset($tPath.'assets/js/main.js') }}"></script>

</body>

</html>