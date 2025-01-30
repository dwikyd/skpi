<?
session_start();
include "../../../config/koneksi.php";
if(isset($_SESSION['hak_akses_skpi'])){
	if(isset($_POST['aksi'])){
		$tanggal=date("Y-m-d H:i:s");
		if($_POST['aksi']=="delete"){
			mysqli_query($koneksi_skpi, "DELETE FROM jenis_dokumen where id_jns_dokumen='$_POST[id]'") or die(mysqli_error($koneksi_skpi));
		}

		if($_POST['aksi']=="tambah"){
			$sql="INSERT INTO jenis_dokumen SET id_jns_dokumen='$_POST[id_jns_dokumen]', nama_dokumen='$_POST[nama_dokumen]', tgl_upload='$tanggal', tgl_update='$tanggal', upload_by='$_SESSION[nama_lengkap]', update_by='$_SESSION[nama_lengkap]'";
			mysqli_query($koneksi_skpi, $sql) or die(mysqli_error($koneksi_skpi));
		}

		if($_POST['aksi']=="edit"){
			$sql="UPDATE jenis_dokumen SET nama_dokumen='$_POST[nama_dokumen]', tgl_update='$tanggal', update_by='$_SESSION[nama_lengkap]' WHERE id_jns_dokumen='$_POST[id_jns_dokumen]'";
			mysqli_query($koneksi_skpi, $sql) or die(mysqli_error($koneksi_skpi));
		}

		if($_POST['aksi']=="cari"){
			$data=mysqli_fetch_assoc(mysqli_query($koneksi_skpi, "SELECT * FROM jenis_dokumen where id_jns_dokumen='$_POST[id]'"));
			echo json_encode($data);
		}
	}
}
?>