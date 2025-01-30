<?
session_start();
include "../../config/koneksi.php";
if(isset($_SESSION['hak_akses_skpi'])){
	$nim=isset($_SESSION['nim']) ? $_SESSION['nim'] : $_COOKIE['nim'];
	if(isset($_POST['aksi'])){
		$tanggal=date("Y-m-d H:i:s");
		if($_POST['aksi']=="delete"){
			$d=mysqli_fetch_assoc(mysqli_query($koneksi_skpi, "SELECT nim, file FROM prestasi WHERE id_prestasi='$_POST[id]'"));
			$file="../../file_prestasi/$d[nim]/$d[file]";
			if(file_exists($file)) unlink($file);
			mysqli_query($koneksi_skpi, "DELETE FROM prestasi where id_prestasi='$_POST[id]'") or die(mysqli_error($koneksi_skpi));
		}

		if($_POST['aksi']=="bidang"){
			echo '<select class="form-control" name="id_bukti_dok" id="id_bukti_dok" required onchange="bukti(this);">
			<option value="">-- Jenis Prestasi --</option>';
			$jns=mysqli_query($koneksi_skpi, "SELECT id_jns_dokumen, nama_prestasi, id_bukti_dok FROM bukti_dokumen WHERE id_jns_dokumen='$_POST[id]' ORDER BY id_bukti_dok");
			while($bd=mysqli_fetch_assoc($jns)){
				echo "<option value='$bd[id_bukti_dok]'>$bd[nama_prestasi]</option>";
			}
			echo '</select>';
		}

		if($_POST['aksi']=="bukti"){
			$jns=mysqli_query($koneksi_skpi, "SELECT bukti, jenis FROM bukti_dokumen WHERE id_bukti_dok='$_POST[id]'");
			$bk=mysqli_fetch_assoc($jns);
			echo "Bukti : $bk[bukti] dalam bentuk $bk[jenis]";
		}

		if($_POST['aksi']=="tambah"){
			$id_prestasi=sprintf( '%04x%04x_%04x%04x', mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ));
			$dir="../../file_prestasi/$_SESSION[nim]/";
			if (!file_exists($dir)) {
				mkdir($dir, 0775, true);
			}
			$file="";
			if($_FILES['file']['size'] > 0 && $_FILES['file']['error'] == 0){ 
				$path = $_FILES['file']['name'];
				$ext = pathinfo($path, PATHINFO_EXTENSION);
				$namafile = $id_prestasi.".".$ext;
				$file=", file='$namafile'";
				move_uploaded_file($_FILES['file']["tmp_name"], $dir.$namafile);
			}
			$nama=mysqli_escape_string($koneksi_skpi, $_SESSION['nama_lengkap']);
			$sql="INSERT INTO prestasi SET id_prestasi='$id_prestasi', in_prestasi='".mysqli_escape_string($koneksi_skpi, $_POST['in_prestasi'])."', eng_prestasi='".mysqli_escape_string($koneksi_skpi, $_POST['eng_prestasi'])."',  arb_prestasi='".mysqli_escape_string($koneksi_skpi, $_POST['arb_prestasi'])."', nim='$nim', id_jns_dokumen='$_POST[id_jns_dokumen]', id_bukti_dok='$_POST[id_bukti_dok]', tgl_prestasi='$_POST[tgl_prestasi]' $file , tgl_upload='$tanggal', tgl_update='$tanggal', upload_by='$nama', update_by='$nama'";
			
			mysqli_query($koneksi_skpi, $sql);// or die (mysqli_error($koneksi_skpi));
			echo "<meta http-equiv=refresh content=0;url=\"../../sertifikat-$_POST[id_jns_dokumen].html\">";
		}

		if($_POST['aksi']=="edit"){
			$file="";
			if($_FILES['file']['size'] > 0 && $_FILES['file']['error'] == 0){ 
				$path = $_FILES['file']['name'];
				$ext = pathinfo($path, PATHINFO_EXTENSION);
				$namafile = $_POST['id_prestasi'].".".$ext;
				$file=", file='$namafile'";
				move_uploaded_file($_FILES['file']["tmp_name"], $dir.$namafile);
			}

			$sql="UPDATE prestasi SET in_prestasi='".mysqli_escape_string($koneksi_skpi, $_POST['in_prestasi'])."', eng_prestasi='".mysqli_escape_string($koneksi_skpi, $_POST['eng_prestasi'])."',  arb_prestasi='".mysqli_escape_string($koneksi_skpi, $_POST['arb_prestasi'])."', id_jns_dokumen='$_POST[id_jns_dokumen]', id_bukti_dok='$_POST[id_bukti_dok]', tgl_prestasi='$_POST[tgl_prestasi]' $file , tgl_update='$tanggal', update_by='".mysqli_escape_string($koneksi_skpi, $_SESSION['nama_lengkap'])."' WHERE id_prestasi='$_POST[id_prestasi]'";
			mysqli_query($koneksi_skpi, $sql) or die(mysqli_error($koneksi_skpi));
			echo "<meta http-equiv=refresh content=0;url=\"../../sertifikat-$_POST[id_jns_dokumen].html\">";
		}

		if($_POST['aksi']=="submit"){
			$sql="UPDATE skpi SET finalisasi='Y', tgl_finalisasi='$tanggal' WHERE nim='$_SESSION[nim]'";
			$d=mysqli_query($koneksi_skpi, $sql) or die(mysqli_error($koneksi_skpi));
			if($d){
				$sql="UPDATE mhs SET skpi='Y' WHERE nim='$_SESSION[nim]'";
				mysqli_query($koneksi_sikadu, $sql) or die("Sikadu : ".mysqli_error($koneksi_sikadu));
			}
			else{
				echo "Gagal Finalisasi SKPI ".mysql_error($koneksi_skpi);
			}
		}

		if($_POST['aksi']=="submit_prestasi"){
			$sql="UPDATE prestasi SET sts_prestasi='Pengajuan', sts_skpi='N', sts_translate='N', tgl_skpi='$tanggal', tgl_update='$tanggal', update_by='".mysqli_escape_string($koneksi_skpi, $_SESSION['nama_lengkap'])."' WHERE id_prestasi='$_POST[id]'";
			
			mysqli_query($koneksi_skpi, $sql) or die(mysqli_error($koneksi_skpi));
		}

		if($_POST['aksi']=="cek_tanggal"){
			if($_POST['tanggal']>date("Y-m-d")){
				echo date("Y-m-d");
			}
		}
	}
}
?>