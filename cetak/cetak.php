<?
ob_start();
session_start();
// error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED);
date_default_timezone_set('Asia/Jakarta');

include "../config/koneksi.php";
include "../config/fungsi_indotgl.php";
require_once "../config/function.php";
$url=explode("-", mysqli_escape_string($koneksi_skpi, $_GET['url']));
$nim="";
if(isset($url[1])) $nim=str_replace("_", "-", $url[1]);
if(isset($_SESSION['nim']))$nim=$_SESSION['nim']; 
?>
<!DOCTYPE html>
<html lang="en">

<head> 
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<!-- Primary Meta Tags -->
	<title>SKPI <?= $nim ?></title>
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
	<meta property="og:image" content="https://skpi.iainkudus.ac.id/assets/img/<?= $_SESSION['logo'] ?>">

	<!-- Twitter -->
	<meta property="twitter:card" content="summary_large_image">
	<meta property="twitter:url" content="https://demo.themesberg.com/volt">
	<meta property="twitter:title" content="SKPI <?= $_SESSION['nama_pt'] ?>">
	<meta property="twitter:description" content="Surat Keterangan Pendamping Ijazah (SKPI) <?= $_SESSION['nama_pt'] ?>.">
	<meta property="twitter:image" content="https://skpi.iainkudus.ac.id/assets/img/<?= $_SESSION['logo'] ?>">

	<!-- Favicon -->
	<link rel="apple-touch-icon" sizes="120x120" href="../assets/img/<?= $_SESSION['logo'] ?>">
	<link rel="icon" type="image/png" sizes="32x32" href="../assets/img/<?= $_SESSION['logo'] ?>">
	<link rel="icon" type="image/png" sizes="16x16" href="../assets/img/<?= $_SESSION['logo'] ?>">
	<link rel="manifest" href="assets/img/favicon/site.webmanifest">
	<link rel="mask-icon" href="assets/img/favicon/safari-pinned-tab.svg" color="#ffffff">
	<meta name="msapplication-TileColor" content="#ffffff">
	<meta name="theme-color" content="#ffffff">

	<link type="text/css" href="../vendor/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet">
</head>

<body style="font-family: Calibri, Candara, Segoe, Optima, Arial, sans-serif; font-size: 16px; font-style: normal; font-variant: normal; line-height: 20px; width: 21cm;">
	<?
	if($_SESSION['hak_akses']){
		if(file_exists("$url[0].php")){
			include "$url[0].php";
		}
		else{
			echo "<h2>Laporan tidak tersedia</h2>";
		}
	}
	else{
		echo "<h2>Maaf akses anda tidak diijinkan.</h2><p>SIlahkan login terlebih dahulu</p>";
	}
	?>

</body>
</html>