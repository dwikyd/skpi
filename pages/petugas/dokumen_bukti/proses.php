<?
session_start();
include "../../../config/koneksi.php";
if(isset($_SESSION['hak_akses_skpi'])){
	if(isset($_POST['aksi'])){
		$tanggal=date("Y-m-d H:i:s");
		if($_POST['aksi']=="delete"){
			mysqli_query($koneksi_skpi, "DELETE FROM bukti_dokumen where id_bukti_dok='$_POST[id]'") or die(mysqli_error($koneksi_skpi));
		}

		if($_POST['aksi']=="tambah"){
			$id_bukti_dok=$_POST['id_jns_dokumen']."#";
			
			$data=mysqli_fetch_assoc(mysqli_query($koneksi_skpi, "SELECT max(right(id_bukti_dok, 2)) as nomer FROM bukti_dokumen where id_jns_dokumen='$_POST[id_jns_dokumen]'"));

			$klp=$data['nomer']+1;
			$no = "00".$klp;
			$nomer = substr($no,strlen($no)-2, 2);
			$id_bukti_dok.=$nomer;

			$sql="INSERT INTO bukti_dokumen SET id_bukti_dok='$id_bukti_dok', id_jns_dokumen='$_POST[id_jns_dokumen]', nama_prestasi='$_POST[nama_prestasi]', bukti='$_POST[bukti]',jenis='$_POST[jenis]', tgl_upload='$tanggal', tgl_update='$tanggal', upload_by='$_SESSION[nama_lengkap]', update_by='$_SESSION[nama_lengkap]'";
			mysqli_query($koneksi_skpi, $sql) or die(mysqli_error($koneksi_skpi));
		}

		if($_POST['aksi']=="edit"){
			$sql="UPDATE bukti_dokumen SET nama_prestasi='$_POST[nama_prestasi]', bukti='$_POST[bukti]',jenis='$_POST[jenis]', tgl_update='$tanggal', update_by='$_SESSION[nama_lengkap]' WHERE id_bukti_dok='$_POST[id_bukti_dok]'";
			mysqli_query($koneksi_skpi, $sql) or die(mysqli_error($koneksi_skpi));
		}

		if($_POST['aksi']=="cari"){
			$data=mysqli_fetch_assoc(mysqli_query($koneksi_skpi, "SELECT * FROM jenis_dokumen where id_jns_dokumen='$_POST[id]'"));
			echo json_encode($data);
		}
	}
}
?>