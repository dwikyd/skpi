<?php
session_start();
include "../../config/koneksi.php";

// Hapus sesi jika ada
if (isset($_SESSION['hak_akses'])) {
    session_unset();
}

// Pastikan admin SKPI telah login
if (!isset($_SESSION['admin_skpi']) || $_SESSION['admin_skpi'] !== "Y") {
    die("Akses Ditolak");
}

// Validasi input
if (!isset($_POST['klp']) || !isset($_POST['id'])) {
    die("Data tidak lengkap");
}

$klp = $_POST['klp'];
$id = trim($_POST['id']);

if ($klp === "mhs") {
    $stmt = $koneksi_sikadu->prepare("SELECT a.nim, a.nama, a.kode_prodi, b.nama_prodi FROM mhs a INNER JOIN prodi b ON a.kode_prodi = b.kode_prodi WHERE a.nim = ?");
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $d = $result->fetch_assoc();
    $stmt->close();
    
    if ($d) {
        $_SESSION['nim'] = $d['nim'];
        $_SESSION['hak_akses'] = "mahasiswa";
        $_SESSION['hak_akses_skpi'] = "MHS";
        $_SESSION['nama_lengkap'] = $d['nama'];
        $_SESSION['kode_prodi'] = $d['kode_prodi'];
        $_SESSION['prodi'] = $d['nama_prodi'];
        
        $stmt = $koneksi_skpi->prepare("INSERT INTO skpi (nim, nama, kode_prodi, prodi, upload_by, update_by) SELECT ?, ?, ?, ?, ?, ? WHERE NOT EXISTS (SELECT 1 FROM skpi WHERE nim = ?)");
        $stmt->bind_param("sssssss", $_SESSION['nim'], $_SESSION['nama_lengkap'], $_SESSION['kode_prodi'], $_SESSION['prodi'], $_SESSION['nama_lengkap'], $_SESSION['nama_lengkap'], $_SESSION['nim']);
        $stmt->execute();
        $stmt->close();
    }
} elseif ($klp === "peg") {
    $stmt = $koneksi_simpeg->prepare("SELECT id_pegawai, nama FROM pegawai WHERE id_pegawai = ?");
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $d = $result->fetch_assoc();
    $stmt->close();

    if ($d) {
        $_SESSION['id_pegawai'] = $d['id_pegawai'];
        $_SESSION['hak_akses'] = "petugas";
        $_SESSION['nama_lengkap'] = str_replace(",", "", $d['nama']);
        $_SESSION['hak_akses_skpi'] = "";
        
        // Ambil hak akses tambahan
        $stmt = $koneksi_sikadu->prepare("SELECT hak_akses, kode_prodi FROM hak_akses_sikadu WHERE id_pegawai = ?");
        $stmt->bind_param("s", $_SESSION['id_pegawai']);
        $stmt->execute();
        $result = $stmt->get_result();
        $v = $result->fetch_assoc();
        $stmt->close();
        
        if (!empty($v['hak_akses'])) {
            $_SESSION['hak_akses_skpi'] = $v['hak_akses'];
            $_SESSION['kode_prodi'] = "'" . str_replace(",", "','", $v['kode_prodi']) . "'";
        }
    }
} else {
    die("Kelompok tidak valid");
}
?>

