<?
session_start();
include "config/koneksi.php";
include "config/fungsi_indotgl.php";
$url=explode("-", $_GET['url']);
if(empty($_SESSION['nama_pt'])){
    $d=mysqli_fetch_assoc(mysqli_query($koneksi_simpeg, "SELECT * FROM global"));
    $_SESSION['nama_pt']=$d['nama_kantor'];
    $_SESSION['logo']=$d['logo'];
}
$perangkat=strpos($_SERVER['HTTP_USER_AGENT'], 'Android')==true ? "Android" : "Komputer";
?>
<!DOCTYPE html>
<html lang="en">

<head> 
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <!-- Primary Meta Tags -->
    <title>SKPI <?= $_SESSION['nama_pt'] ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="title" content="SKPI <?= $_SESSION['nama_pt'] ?>">
    <meta name="author" content="Slamet Siswanto">
    <meta name="description" content="Surat Keterangan Pendamping Ijazah (SKPI) <?= $_SESSION['nama_pt'] ?>.">
    <meta name="keywords" content="Surat Keterangan Pendamping Ijazah, SKPI, <?= $_SESSION['nama_pt'] ?>." />
    <!-- <link rel="canonical" href="https://themesberg.com/product/admin-dashboard/volt-bootstrap-5-dashboard"> -->

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://skpi.iainkudus.ac.id">
    <meta property="og:title" content="SKPI <?= $_SESSION['nama_pt'] ?>">
    <meta property="og:description" content="Surat Keterangan Pendamping Ijazah (SKPI) <?= $_SESSION['nama_pt'] ?>.">
    <meta property="og:image" content="assets/img/<?= $_SESSION['logo'] ?>">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="https://demo.themesberg.com/volt">
    <meta property="twitter:title" content="SKPI <?= $_SESSION['nama_pt'] ?>">
    <meta property="twitter:description" content="Surat Keterangan Pendamping Ijazah (SKPI) <?= $_SESSION['nama_pt'] ?>.">
    <meta property="twitter:image" content="assets/img/logo.png">

    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="120x120" href="assets/img/<?= $_SESSION['logo'] ?>">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/img/<?= $_SESSION['logo'] ?>">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/img/<?= $_SESSION['logo'] ?>">
    <link rel="manifest" href="assets/img/favicon/site.webmanifest">
    <link rel="mask-icon" href="assets/img/favicon/safari-pinned-tab.svg" color="#ffffff">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="theme-color" content="#ffffff">

    <!-- Fontawesome -->
    <link type="text/css" href="vendor/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet">

    <!-- Notyf -->
    <link type="text/css" href="vendor/notyf/notyf.min.css" rel="stylesheet">

    <!-- Volt CSS -->
    <link type="text/css" href="css/volt.css" rel="stylesheet">

    <!-- <link type="text/css" href="css/tabel.css" rel="stylesheet"> -->
    <link type="text/css" href="assets/css/sweetalert2.min.css" rel="stylesheet">
    <!-- NOTICE: You can use the _analytics.html partial to include production code specific code & trackers -->
    <link rel="stylesheet" type="text/css" href="assets/css/jquery.dataTables.min.css">
    <link href="assets/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
</head>

<body class="bg-soft">
    <?
    if($url[0]=="sso"){
        include "pages/sso.php";
    }
    elseif($url[0]=="logout"){
        session_destroy();
        unset($_SESSION['id_pegawai'], $_SESSION['kode_peg'], $_SESSION['nim'], $_SESSION['nama_lengkap'], $_SESSION['hak_akses'], $_SESSION['admin_skpi'], $_SESSION['hak_akses_skpi'], $_SESSION['nim']);
        if (isset($_COOKIE['nim'])) {
            unset($_COOKIE['nim']);
            setcookie('nim', '', time() - 3600, '/'); 
        }
        echo "<meta http-equiv=refresh content=0;url=\"https://smartcampus.iainptk.ac.id/application.html\">";
    }
    elseif(!isset($_SESSION['hak_akses']) || empty($_SESSION['hak_akses'])){
        include "pages/login.php";
    } 
    else{ 
        include "pages/open_page.php";
    }
    ?>

    <!-- Core -->
    <script src="vendor/popper.js/dist/umd/popper.min.js"></script>
    <script src="vendor/bootstrap/dist/js/bootstrap.min.js"></script>

    <!-- Vendor JS -->
    <script src="vendor/onscreen/dist/on-screen.umd.min.js"></script>

    <!-- Slider -->
    <script src="vendor/nouislider/distribute/nouislider.min.js"></script>

    <!-- Jarallax -->
    <script src="vendor/jarallax/dist/jarallax.min.js"></script>

    <!-- Smooth scroll -->
    <script src="vendor/smooth-scroll/dist/smooth-scroll.polyfills.min.js"></script>

    <!-- Count up -->
    <script src="vendor/countup.js/dist/countUp.umd.js"></script>

    <!-- Notyf -->
    <!-- <script src="vendor/notyf/notyf.min.js"></script> -->

    <!-- Charts -->
    <!-- <script src="vendor/chartist/dist/chartist.min.js"></script>
        <script src="vendor/chartist-plugin-tooltips/dist/chartist-plugin-tooltip.min.js"></script> -->

        <!-- Datepicker -->
        <!-- <script src="vendor/vanillajs-datepicker/dist/js/datepicker.min.js"></script> -->

        <!-- Simplebar -->
        <script src="vendor/simplebar/dist/simplebar.min.js"></script>

        <!-- Github buttons -->
        <script async defer src="assets/js/buttons.js"></script>

        <!-- Volt JS -->
        <script src="assets/js/volt.js"></script>

        <script src="assets/js/sweetalert2.all.min.js"></script>
        <script src="assets/js/simple-datatables.js"></script>
        <script src="assets/select2/js/select2.full.min.js"></script>

        <script type="text/javascript">
            var dataTableEl = d.getElementById('datatable');
            if(dataTableEl) {
                const dataTable = new simpleDatatables.DataTable(dataTableEl);
            }

            $(document).ready(function() {
                $('.select').select2();
            });
        </script>

        <style type="text/css">
        /*table {
            table-layout:fixed;
        }
        table td {
            word-wrap: break-word;
            max-width: 400px;
            white-space:normal;
        }*/
    </style>
</body>

</html>
