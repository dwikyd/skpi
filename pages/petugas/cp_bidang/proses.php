<?
session_start();
include "../../../config/koneksi.php";
if(isset($_SESSION['hak_akses_skpi'])){
	if(isset($_POST['aksi'])){
		$tanggal=date("Y-m-d H:i:s");
		if($_POST['aksi']=="delete"){
			mysqli_query($koneksi_skpi, "DELETE FROM cp_bidang where id_bidang='$_POST[id]'") or die(mysqli_error($koneksi_skpi));
		}

		if($_POST['aksi']=="tambah"){
			$sql="INSERT INTO cp_bidang SET id_bidang='$_POST[id_bidang]', nama_bidang='$_POST[nama_bidang]', eng_bidang='$_POST[eng_bidang]', arb_bidang='$_POST[arb_bidang]', tgl_upload='$tanggal', tgl_update='$tanggal', upload_by='$_SESSION[nama_lengkap]', update_by='$_SESSION[nama_lengkap]'";
			mysqli_query($koneksi_skpi, $sql) or die(mysqli_error($koneksi_skpi));
		}

		if($_POST['aksi']=="edit"){
			$sql="UPDATE cp_bidang SET nama_bidang='$_POST[nama_bidang]', eng_bidang='$_POST[eng_bidang]', arb_bidang='$_POST[arb_bidang]', tgl_update='$tanggal', update_by='$_SESSION[nama_lengkap]' WHERE id_bidang='$_POST[id_bidang]'";
			mysqli_query($koneksi_skpi, $sql) or die(mysqli_error($koneksi_skpi));
		}
	}
}
?>