<?
session_start();
include "../../config/koneksi.php";
include "../../config/fungsi_indotgl.php";
if(isset($_SESSION['hak_akses_skpi'])){
	if(isset($_POST['aksi'])){
		$tanggal=date("Y-m-d H:i:s");
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

		if($_POST['aksi']=="update"){
			$data=mysqli_fetch_assoc(mysqli_query($koneksi_skpi, "SELECT id_jns_dokumen FROM bukti_dokumen WHERE id_bukti_dok='$_POST[id_bukti_dok]'"));
			$sql="UPDATE prestasi SET sts_skpi='$_POST[sts_skpi]', sts_prestasi='$_POST[sts_prestasi]', tgl_prestasi='$_POST[tgl_prestasi]', id_bukti_dok='$_POST[id_bukti_dok]', id_jns_dokumen='$data[id_jns_dokumen]', in_prestasi='".mysqli_escape_string($koneksi_skpi, $_POST['in_prestasi'])."', catatan='".mysqli_escape_string($koneksi_skpi, $_POST['catatan'])."', tgl_update='$tanggal', update_by='".mysqli_escape_string($koneksi_skpi, $_SESSION['nama_lengkap'])."' WHERE id_prestasi='$_POST[id_prestasi]'";
			
			mysqli_query($koneksi_skpi, $sql) or die(mysqli_error($koneksi_skpi));
		}

		if($_POST['aksi']=="cari"){
			$data=mysqli_fetch_assoc(mysqli_query($koneksi_skpi, "SELECT * FROM prestasi a INNER JOIN bukti_dokumen b ON a.id_bukti_dok=b.id_bukti_dok WHERE a.id_prestasi='$_POST[id_prestasi]'"));
			$mhs=mysqli_fetch_assoc(mysqli_query($koneksi_sikadu, "SELECT a.nim, a.nama, b.nama_prodi FROM mhs a INNER JOIN prodi b ON a.kode_prodi=b.kode_prodi WHERE a.nim='$data[nim]'"));
			$data['nama']=$mhs['nama'];
			$data['nama_prodi']=$mhs['nama_prodi'];
			$data['in_prestasi']=strip_tags($data['in_prestasi']);

			$file="../../file_prestasi/".$data['nim']."/".$data['file'];
			$array = explode('.', $data['file']);
			$extension = strtolower(end($array));
			if(file_exists($file) && $data['file']!="") {
				$file=str_replace("../", "", $file);
				if($extension=="pdf"){
					$data['file']="<embed src='$file#toolbar=0&navpanes=0&scrollbar=0' type='application/pdf' width='100%' height='600px' />";
				}
				else $data['file']="<img src='$file' width='100%'>";
			}
			else $data['file']="<p>Tidak ada file pendukung. $file tidak ditemukan</p>";

			echo json_encode($data);
		}

		if($_POST['aksi']=="progress"){
			$data=mysqli_fetch_assoc(mysqli_query($koneksi_skpi, "SELECT a.*, b.nama_prestasi FROM prestasi a INNER JOIN bukti_dokumen b ON a.id_bukti_dok=b.id_bukti_dok WHERE a.id_prestasi='$_POST[id_prestasi]'"));
			$mhs=mysqli_fetch_assoc(mysqli_query($koneksi_sikadu, "SELECT a.nim, a.nama, b.nama_prodi FROM mhs a INNER JOIN prodi b ON a.kode_prodi=b.kode_prodi WHERE a.nim='$data[nim]'"));
			echo "<table>
			<tr valign='top'><td width='80px'>Nama</td><td width='10px'>:</td><td class='p-1'>$mhs[nama]</td></tr>
			<tr valign='top'><td class='p-1'>Prodi</td><td class='p-1'>:</td><td class='p-1'>$mhs[nama_prodi]</td></tr>
			<tr valign='top'><td class='p-1'>Prestasi</td><td class='p-1'>:</td><td class='p-1'>$data[in_prestasi]</td></tr>";
			echo "</table><table class='table table-bordered'><tr style='font-weight:bold; text-align:center;'><td width=10px class='p-1'>No</td><td class='p-1'>Progres</td><td width=150px class='p-1'>Tanggal</td></tr>";
			echo "<tr><td class='p-1'>1</td><td class='p-1'>Input prestasi</td><td class='p-1'>".tgl_indo($data['tgl_upload'])."</td></tr>";
			echo "<tr><td class='p-1'>2</td><td class='p-1'>Submit prestasi</td><td class='p-1'>".tgl_indo($data['tgl_skpi'])."</td></tr>";
			$tr=mysqli_query($koneksi_skpi, "SELECT * FROM prestasi_translate WHERE id_prestasi='$_POST[id_prestasi]' ORDER BY jenis");
			$no=2;
			while($d=mysqli_fetch_assoc($tr)){
				$no++;
				$bahasa=$d['jenis']=="I" ? "Inggris" : "Arab";
				echo "<tr><td class='p-1'>$no</td><td class='p-1'>Penetapan penerjemah bahasa $bahasa</td><td class='p-1'>".tgl_indo($d['tgl_bagi'])."</td></tr>";
				if(!empty($d['tgl_translate'])){
					$no++;
					echo "<tr><td class='p-1'>$no</td><td class='p-1'>Penerjemahan bahasa $bahasa</td><td class='p-1'>".tgl_indo($d['tgl_translate'])."</td></tr>";
				}
				if(!empty($d['tgl_review'])){
					$no++;
					echo "<tr><td class='p-1'>$no</td><td class='p-1'>Review bahasa $bahasa</td><td class='p-1'>".tgl_indo($d['tgl_review'])."</td></tr>";
				}
			}
			echo "</table>";
		}
	}
}
?>