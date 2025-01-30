<?
session_destroy();
// if(isset($_SESSION['id_pegawai'])) 
unset($_SESSION['id_pegawai'], $_SESSION['kode_peg'], $_SESSION['nim'], $_SESSION['nama_lengkap'], $_SESSION['hak_akses'], $_SESSION['admin_skpi'], $_SESSION['hak_akses_skpi'], $_SESSION['nim']);
if (isset($_COOKIE['nim'])) {
    unset($_COOKIE['nim']);
    setcookie('nim', '', time() - 3600, '/'); // empty value and old timestamp
}
echo "<meta http-equiv=refresh content=0;url=\"https://smartcampus.iainptk.ac.id/application.html\">";
?>