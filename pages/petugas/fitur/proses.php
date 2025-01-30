<?
session_start();

include "../../../config/koneksi.php";
include("../../../config/function.php");
if(isset($_SESSION['id_pegawai'])){
	if(isset($_POST['aksi'])){
		if($_POST['aksi']=="delete"){
			mysqli_query($koneksi_skpi, "DELETE FROM fitur_skpi where id='$_POST[id]' OR parent_id='$_POST[id]'") or die(mysqli_error($koneksi_skpi));
		}

		if($_POST['aksi']=="tambah"){
			$user=isset($_POST['user']) ? implode(",", $_POST['user']) : "";
			$sql="INSERT INTO fitur_skpi set parent_id='$_POST[parent_id]', level='$_POST[level]', title='$_POST[title]', user='$user', url='$_POST[url]', icon='$_POST[icon]', publish='$_POST[publish]', fitur_order='$_POST[fitur_order]'";
			if(!mysqli_query($koneksi_skpi, $sql)){
				echo "Gagal menambahkan data !".mysqli_error($koneksi_skpi);
			}
		}

		if($_POST['aksi']=="edit"){
			$user=isset($_POST['user']) ? implode(",", $_POST['user']) : "";
			$sql="UPDATE fitur_skpi set title='$_POST[title]', user='$user', url='$_POST[url]', icon='$_POST[icon]', publish='$_POST[publish]', fitur_order='$_POST[fitur_order]' where id='$_POST[id]'";					
			if(!mysqli_query($koneksi_skpi, $sql)){
				echo "Gagal merubah data !".mysqli_error($koneksi_skpi);
			}
		}

		if($_POST['aksi']=="cari"){
			$data=mysqli_fetch_assoc(mysqli_query($koneksi_skpi, "SELECT * FROM fitur_skpi WHERE id='$_POST[id]'"));
			$data['user']=explode(",", $data['user']);
			echo json_encode($data);
		}
	}
}
else{
	echo "Silahkan Login ulang";
}

?>