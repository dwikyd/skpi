<?php  
session_start();
include "koneksi.php";
if($_GET[jenis]=="foto"){
	$dataGambar = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT 'Foto Diri' as name, 'image/jpeg' as tipe, file_foto as file from user_item where user_id='$_GET[id]'"));  
}

if($_GET[jenis]=="ktp"){
	$dataGambar = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT 'KTP' as name, 'image/jpeg' as tipe, file_ktp as file from user_item where user_id='$_GET[id]'"));  
}

if($_GET[jenis]=="pendidikan"){
	$dataGambar = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT pdk_nama as name, file, file_type as tipe from pendidikan where pdk_id='$_GET[id]'"));  
}

if($_GET[jenis]=="bahasa"){
	$dataGambar = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT nama as name, file, file_type as tipe from sertifikat_bahasa where id='$_GET[id]'"));  
}

if($_GET[jenis]=="slider"){
	$dataGambar = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT name, file_slider as file, file_type as tipe from slider where id='$_GET[id]'"));  
}

if($_GET[jenis]=="partner"){
	$dataGambar = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT nama_partner as name, file_partner as file, file_type as tipe from partner where id_partner='$_GET[id]'"));  
}

if($_GET[jenis]=="download"){
	$dataGambar = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT name, file, file_type as tipe from downloads where id='$_GET[id]'"));  
}

if($_GET[jenis]=="dokumen"){
	$dataGambar = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT file_dokumen as file, 'application/pdf' as tipe from  user_documents where id='$_GET[id]'"));  
}

$filename = str_replace(" ","_", $dataGambar['name']);
$mime_type = $dataGambar['tipe'];  
$filedata = $dataGambar['file'];  

// header(“Content-Type: $row[tipefile]“);
// header(“Content-Disposition: attachment; filename=$materi.pdf “);
// header(“Pragma: no-cache”);
// header(“Expires: 0″);
// print “$row[materi]“;

header("content-disposition: inline; filename=$filename");  
header("Content-Disposition: attachment; filename=$filename");
header("content-type: $mime_type");  
header("content-length: ".strlen($filedata));  

echo ($filedata);  
?> 