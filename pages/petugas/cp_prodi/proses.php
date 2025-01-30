<?
session_start();
include "../../../config/koneksi.php";
if(isset($_SESSION['hak_akses_skpi'])){
	if(isset($_POST['aksi'])){
		$tanggal=date("Y-m-d H:i:s");
		if($_POST['aksi']=="delete"){
			mysqli_query($koneksi_skpi, "DELETE FROM cp_prodi where id_cp='$_POST[id]'") or die(mysqli_error($koneksi_skpi));
		}

		if($_POST['aksi']=="tambah"){
			$id_cp=sprintf( '%04x%04x_%04x%04x', mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ));
			$sql="INSERT INTO cp_prodi SET id_cp='$id_cp', in_cp='".mysqli_escape_string($koneksi_skpi, $_POST['in_cp'])."', eng_cp='".mysqli_escape_string($koneksi_skpi, $_POST['eng_cp'])."', arb_cp='".mysqli_escape_string($koneksi_skpi, $_POST['arb_cp'])."', kode_prodi='$_POST[kode_prodi]', id_bidang='$_POST[id_bidang]', order_cp='$_POST[order_cp]', tgl_upload='$tanggal', tgl_update='$tanggal', upload_by='".mysqli_escape_string($koneksi_skpi, $_SESSION['nama_lengkap'])."', update_by='$_SESSION[nama_lengkap]'";
			mysqli_query($koneksi_skpi, $sql) or die(mysqli_error($koneksi_skpi));
		}

		if($_POST['aksi']=="edit"){
			$sql="UPDATE cp_prodi SET in_cp='".mysqli_escape_string($koneksi_skpi, $_POST['in_cp'])."', eng_cp='".mysqli_escape_string($koneksi_skpi, $_POST['eng_cp'])."', arb_cp='".mysqli_escape_string($koneksi_skpi, $_POST['arb_cp'])."', id_bidang='$_POST[id_bidang]', order_cp='$_POST[order_cp]', tgl_update='$tanggal', update_by='".mysqli_escape_string($koneksi_skpi, $_SESSION['nama_lengkap'])."' WHERE id_cp='$_POST[id_cp]'";
			mysqli_query($koneksi_skpi, $sql) or die(mysqli_error($koneksi_skpi));
		}

		if($_POST['aksi']=="cari"){
			$data=mysqli_fetch_assoc(mysqli_query($koneksi_skpi, "SELECT * FROM jenis_dokumen where id_jns_dokumen='$_POST[id]'"));
			echo json_encode($data);
		}
	}
}
?>