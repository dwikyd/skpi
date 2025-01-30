<?php
date_default_timezone_set('Asia/Jakarta');
// $db_host = "172.25.5.114";
// $db_user = "skpi";
// $db_pass = "EsKaPe1-kudus";
// $db_name = "siska";
$db_host = "localhost";
$db_user = "slamet";
$db_pass = "";
$db_name = "pontianak_siska";

$koneksi_sikadu = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

if(mysqli_connect_errno()){
	echo "<div align=center><b>Gagal melakukan koneksi ke Server SISKA</b></div>";
	exit;
}

$db_name = "pontianak_skpi";
$koneksi_skpi = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

if(mysqli_connect_errno()){
	echo "<div align=center><b>Gagal melakukan koneksi ke Server SKPI</b></div>";
	exit;
}

$db_name = "pontianak_edata";

$koneksi_simpeg = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

if(mysqli_connect_errno()){
	echo "<div align=center><b>Gagal melakukan koneksi ke Server SIMPEG</b></div>";
	exit;
}
?>