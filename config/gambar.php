<?php
session_start();
include "koneksi.php";

// Pastikan parameter "jenis" valid
$allowed_types = ["foto", "ktp", "pendidikan", "bahasa", "slider", "partner", "download", "dokumen"];
if (!isset($_GET['jenis']) || !in_array($_GET['jenis'], $allowed_types)) {
    die("Akses tidak valid!");
}

// Pastikan "id" adalah angka untuk mencegah SQL Injection
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID tidak valid!");
}

$id = intval($_GET['id']);  // Pastikan id adalah angka

// Buat query dengan prepared statement
$query = "";
if ($_GET['jenis'] == "foto") {
    $query = "SELECT 'Foto Diri' as name, 'image/jpeg' as tipe, file_foto as file FROM user_item WHERE user_id = ?";
} elseif ($_GET['jenis'] == "ktp") {
    $query = "SELECT 'KTP' as name, 'image/jpeg' as tipe, file_ktp as file FROM user_item WHERE user_id = ?";
} elseif ($_GET['jenis'] == "pendidikan") {
    $query = "SELECT pdk_nama as name, file, file_type as tipe FROM pendidikan WHERE pdk_id = ?";
} elseif ($_GET['jenis'] == "bahasa") {
    $query = "SELECT nama as name, file, file_type as tipe FROM sertifikat_bahasa WHERE id = ?";
} elseif ($_GET['jenis'] == "slider") {
    $query = "SELECT name, file_slider as file, file_type as tipe FROM slider WHERE id = ?";
} elseif ($_GET['jenis'] == "partner") {
    $query = "SELECT nama_partner as name, file_partner as file, file_type as tipe FROM partner WHERE id_partner = ?";
} elseif ($_GET['jenis'] == "download") {
    $query = "SELECT name, file, file_type as tipe FROM downloads WHERE id = ?";
} elseif ($_GET['jenis'] == "dokumen") {
    $query = "SELECT file_dokumen as file, 'application/pdf' as tipe FROM user_documents WHERE id = ?";
}

// Eksekusi query dengan prepared statement
$stmt = mysqli_prepare($koneksi, $query);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$dataGambar = mysqli_fetch_assoc($result);

if (!$dataGambar) {
    die("Data tidak ditemukan!");
}

// Atur nama file dan tipe MIME
$filename = str_replace(" ", "_", $dataGambar['name'] ?? "file");
$mime_type = $dataGambar['tipe'];
$filedata = $dataGambar['file'];

// Header untuk mengunduh file
header("Content-Disposition: attachment; filename=$filename");
header("Content-Type: $mime_type");
header("Content-Length: " . strlen($filedata));

echo $filedata;
?>

