<?
session_start();
include "../../../config/koneksi.php";
if(isset($_SESSION['hak_akses_skpi'])){
	if(isset($_POST['aksi'])){
		$tanggal=date("Y-m-d H:i:s");
		if($_POST['aksi']=="approval"){
			$arti=mysqli_escape_string($koneksi_skpi, $_POST['arti']);
			$terjemah=$_POST['jenis']=="I" ? "eng_prestasi='$arti'" : "arb_prestasi='$arti'";
			$sql="UPDATE prestasi SET $terjemah WHERE id_prestasi='$_POST[id_prestasi]'";
			// if(mysqli_query($koneksi_skpi, $sql)){
			$sts=empty($arti) ? "B" : "S";
			$sql="UPDATE prestasi_translate SET status_translate='$sts', hasil_translate='$arti', tgl_translate='$tanggal' WHERE id_prestasi='$_POST[id_prestasi]' AND id_pegawai='$_POST[id_pegawai]'";
			mysqli_query($koneksi_skpi, $sql) or die (mysqli_error($koneksi_skpi).$sql);
			// } 
			// else (mysqli_error($koneksi_skpi));
		}
	}
}
?>