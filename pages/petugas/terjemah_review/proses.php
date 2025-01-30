<?
session_start();
include "../../../config/koneksi.php";
if(isset($_SESSION['hak_akses_skpi'])){
	if(isset($_POST['aksi'])){
		$tanggal=date("Y-m-d H:i:s");
		if($_POST['aksi']=="approval"){
			$sql="";
			if($_POST['hasil']=="S"){	
				$sql="UPDATE prestasi_translate SET status_review='S', id_reviewer='$_POST[id_pegawai]', tgl_review='$tanggal' WHERE id_prestasi='$_POST[id_prestasi]' AND jenis='$_POST[jenis]'";
			}
			if($_POST['hasil']=="R"){	
				$sql="UPDATE prestasi_translate SET status_translate='R', status_review='B', id_reviewer='$_POST[id_pegawai]', tgl_review='$tanggal' WHERE id_prestasi='$_POST[id_prestasi]' AND jenis='$_POST[jenis]'";
			}
			if(!empty($sql)) mysqli_query($koneksi_skpi, $sql) or die (mysqli_error($koneksi_skpi).$sql);
		}
	}
}
?>