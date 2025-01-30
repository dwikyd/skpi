<?
session_start();
include "../../../config/koneksi.php";
if(isset($_SESSION['hak_akses_skpi'])){
	if(isset($_POST['aksi'])){
		if($_POST['aksi']=="sinkron"){
			mysqli_query($koneksi_skpi, "DELETE FROM jurusan");
			$jur=mysqli_query($koneksi_sikadu, "SELECT * FROM jurusan ORDER BY kode_jurusan");
			while($p=mysqli_fetch_assoc($jur)){
				$sql="INSERT INTO jurusan SET kode_jurusan=\"$p[kode_jurusan]\", nama_jurusan=\"$p[nama_jurusan]\", eng_jurusan=\"$p[eng_jurusan]\", dekan=\"$p[dekan]\", wadek_1=\"$p[wadek_1]\"";
				mysqli_query($koneksi_skpi, $sql) or die(mysqli_error($koneksi_skpi));
			}

			mysqli_query($koneksi_skpi, "DELETE FROM prodi");
			$prodi=mysqli_query($koneksi_sikadu, "SELECT * FROM prodi ORDER BY kode_jurusan, kode_prodi");
			while($p=mysqli_fetch_assoc($prodi)){
				$sql="INSERT INTO prodi SET kode_prodi=\"$p[kode_prodi]\", nama_prodi=\"$p[nama_prodi]\", singkat_prodi=\"$p[singkat_prodi]\", jenjang=\"$p[jenjang]\", kode_jurusan=\"$p[kode_jurusan]\"";
				mysqli_query($koneksi_skpi, $sql) or die(mysqli_error($koneksi_skpi));
			}
		}
	}
}
?>