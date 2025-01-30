<?
session_start();
include "../../../config/koneksi.php";
if(isset($_SESSION['hak_akses_skpi'])){
	if(isset($_POST['aksi'])){
		$tanggal=date("Y-m-d H:i:s");
		if($_POST['aksi']=="approval"){
			$apr=$_POST['approval'];
			$ctt="catatan_".$apr;
			$sts="sts_".$apr;
			$tgl="tgl_".$apr;

			$sql="UPDATE prestasi SET in_prestasi='".mysqli_escape_string($koneksi_skpi, $_POST['in_prestasi'])."', eng_prestasi='".mysqli_escape_string($koneksi_skpi, $_POST['eng_prestasi'])."', $sts='$_POST[rekomendasi]', $ctt='$_POST[catatan]', $tgl='$tanggal' WHERE id_prestasi='$_POST[id_prestasi]'";
			mysqli_query($koneksi_skpi, $sql) or die(mysqli_error($koneksi_skpi));
		}

		if($_POST['aksi']=="submit"){
			$sql="UPDATE skpi SET sts_$_POST[sumber]='Disetujui', sts_$_POST[tujuan]='Pengajuan', tgl_$_POST[sumber]='$tanggal', nama_$_POST[sumber]='".mysqli_escape_string($koneksi_skpi, $_SESSION['nama_lengkap'])."' WHERE nim='$_POST[nim]'";
			if(!mysqli_query($koneksi_skpi, $sql)) echo "Submit $_POST[nim] dari ".ucwords($_POST['sumber'])." ke ".ucwords($_POST['tujuan'])." gagal.";

			$sql="UPDATE prestasi SET sts_$_POST[tujuan]='Pengajuan', tgl_$_POST[sumber]='$tanggal' WHERE nim='$_POST[nim]' AND sts_$_POST[sumber]='Disetujui' AND sts_$_POST[tujuan]!='Disetujui'";
			if(!mysqli_query($koneksi_skpi, $sql)) echo "Submit Prestasi NIM $_POST[nim] dari ".ucwords($_POST['sumber'])." ke ".ucwords($_POST['tujuan'])." gagal.".$sql;
		}

		if($_POST['aksi']=="batal"){
			if($_POST['sumber']=="operator") {
				$sql="UPDATE skpi SET sts_$_POST[sumber]='Revisi', sts_$_POST[tujuan]='Revisi' WHERE nim='$_POST[nim]'";
				mysqli_query($koneksi_skpi, "UPDATE prestasi SET sts_prestasi='Revisi' WHERE nim='$_POST[nim]' AND sts_prestasi='Pengajuan'");
			}
			else $sql="UPDATE skpi SET sts_$_POST[sumber]='Draft', sts_$_POST[tujuan]='Diproses' WHERE nim='$_POST[nim]'";
			if(!mysqli_query($koneksi_skpi, $sql)) echo "Pembatalan $_POST[nim] dari ".ucwords($_POST['sumber'])." ke ".ucwords($_POST['tujuan'])." gagal.";

		}

		if($_POST['aksi']=="netral"){
			$pres=mysqli_fetch_assoc(mysqli_query($koneksi_skpi, "SELECT * FROM prestasi WHERE id_prestasi='$_POST[id_prestasi]'"));
			$in_prestasi=mysqli_escape_string($koneksi_skpi, strip_tags($pres['in_prestasi']));
			$eng_prestasi=mysqli_escape_string($koneksi_skpi, strip_tags($pres['eng_prestasi']));
			$sql="UPDATE prestasi SET in_prestasi='$in_prestasi', eng_prestasi='$eng_prestasi' WHERE id_prestasi='$_POST[id_prestasi]'";
			mysqli_query($koneksi_skpi, $sql);
		}
	}
}
?>