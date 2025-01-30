<?
session_start();
include "../../../config/koneksi.php";
if(isset($_SESSION['hak_akses_skpi'])){
	if(isset($_POST['aksi'])){
		$tanggal=date("Y-m-d H:i:s");
		if($_POST['aksi']=="delete"){
			mysqli_query($koneksi_skpi, "DELETE FROM penerjemah where id_pegawai='$_POST[id]'") or die(mysqli_error($koneksi_skpi));
		}

		if($_POST['aksi']=="rekap"){
			foreach ($_POST['id_translate'] as $id) {
				$sql="UPDATE prestasi_translate SET tgl_rekap_translate='$_POST[tanggal]' WHERE id_translate='$id'";
				mysqli_query($koneksi_skpi, $sql) or die(mysqli_error($koneksi_skpi));
			}
		}
	}
}
?>