<?
session_start();
include "../../../config/koneksi.php";
if(isset($_SESSION['hak_akses_skpi'])){
	if(isset($_POST['aksi'])){
		$tanggal=date("Y-m-d H:i:s");
		if($_POST['aksi']=="delete"){
			mysqli_query($koneksi_skpi, "DELETE FROM penerjemah where id_pegawai='$_POST[id]'") or die(mysqli_error($koneksi_skpi));
		}

		if($_POST['aksi']=="tambah"){
			$peg=mysqli_fetch_assoc(mysqli_query($koneksi_simpeg, "SELECT trim(nama_lengkap) as nama_lengkap FROM pegawai WHERE id_pegawai='$_POST[id_pegawai]'"));
			$peg['nama_lengkap']=mysqli_escape_string($koneksi_simpeg, $peg['nama_lengkap']);
			$sql="INSERT INTO penerjemah SET id_pegawai='$_POST[id_pegawai]', nama_lengkap='$peg[nama_lengkap]', aktif='$_POST[aktif]', jenis='$_POST[jenis]'";
			mysqli_query($koneksi_skpi, $sql) or die(mysqli_error($koneksi_skpi));
		}

		if($_POST['aksi']=="edit"){
			$sql="UPDATE penerjemah SET aktif='$_POST[aktif]', jenis='$_POST[jenis]' WHERE id_pegawai='$_POST[id_pegawai]'";
			mysqli_query($koneksi_skpi, $sql) or die(mysqli_error($koneksi_skpi));
		}

		if($_POST['aksi']=="cari"){
			$data=mysqli_fetch_assoc(mysqli_query($koneksi_skpi, "SELECT * FROM penerjemah where id_pegawai='$_POST[id]'"));
			echo json_encode($data);
		}
	}
}
?>