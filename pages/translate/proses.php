<?
session_start();
include "../../config/koneksi.php";
if(isset($_SESSION['hak_akses_skpi'])){
	if(isset($_SESSION['id_pegawai'])){
		$tanggal=date("Y-m-d H:i:s");
		$sql="UPDATE prestasi SET sts_translate='Y', in_prestasi='".mysqli_escape_string($koneksi_skpi, $_POST['in_prestasi'])."', eng_prestasi='".mysqli_escape_string($koneksi_skpi, $_POST['eng_prestasi'])."',  arb_prestasi='".mysqli_escape_string($koneksi_skpi, $_POST['arb_prestasi'])."', tgl_update='$tanggal', update_by='".mysqli_escape_string($koneksi_skpi, $_SESSION['nama_lengkap'])."', id_translate='$_SESSION[id_pegawai]' WHERE id_prestasi='$_POST[id_prestasi]'";

		mysqli_query($koneksi_skpi, $sql) or die(mysqli_error($koneksi_skpi));
	}
}
?>
<script type="text/javascript">
	window.close();
</script>