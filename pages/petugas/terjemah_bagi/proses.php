<?
session_start();
include "../../../config/koneksi.php";
if(isset($_SESSION['hak_akses_skpi'])){
	if(isset($_POST['aksi'])){
		$tanggal=date("Y-m-d H:i:s");
		if($_POST['aksi']=="delete"){
			mysqli_query($koneksi_skpi, "DELETE FROM penerjemah where id_pegawai='$_POST[id]'") or die(mysqli_error($koneksi_skpi));
		}

		if($_POST['aksi']=="edit"){
			mysqli_query($koneksi_skpi, "DELETE FROM prestasi_translate WHERE id_prestasi='$_POST[id_prestasi]' AND status_translate='B'") or die(mysqli_error($koneksi_skpi));

			$tanggal=date("Y-m-d H:i:s");
			$bagi="N";
			if(!empty($_POST['id_inggris'])){
				$sql="INSERT INTO prestasi_translate SET id_prestasi='$_POST[id_prestasi]', id_pegawai='$_POST[id_inggris]', jenis='I', tgl_bagi='$tanggal'";
				if(mysqli_query($koneksi_skpi, $sql)) $bagi="Y";
			}
			if(!empty($_POST['id_arab'])){
				$sql="INSERT INTO prestasi_translate SET id_prestasi='$_POST[id_prestasi]', id_pegawai='$_POST[id_arab]', jenis='A', tgl_bagi='$tanggal'";
				if(mysqli_query($koneksi_skpi, $sql)) $bagi="Y";
			}
			mysqli_query($koneksi_skpi, "UPDATE prestasi SET sts_bagi_translate='$bagi' WHERE id_prestasi='$_POST[id_prestasi]'") or die(mysqli_error($koneksi_skpi));
		}

		if($_POST['aksi']=="cari"){
			$data=mysqli_fetch_assoc(mysqli_query($koneksi_skpi, "SELECT a.*, b.nama FROM prestasi a INNER JOIN skpi b ON a.nim=b.nim where a.id_prestasi='$_POST[id]'"));

			if($data['sts_bagi_translate']=="Y"){
				$tr=mysqli_query($koneksi_skpi, "SELECT * FROM prestasi_translate WHERE id_prestasi='$data[id_prestasi]'");
				while($t=mysqli_fetch_assoc($tr)){
					if($t['jenis']=="I") $data['id_inggris']=$t['id_pegawai'];
					if($t['jenis']=="A") $data['id_arab']=$t['id_pegawai'];
				}
			}

			echo json_encode($data);
		}
	}
}
?>