<?
session_start();
include "../../../config/koneksi.php";
if(isset($_SESSION['hak_akses_skpi'])){
	if(isset($_POST['aksi'])){
		$tanggal=date("Y-m-d H:i:s");
		if($_POST['aksi']=="tambah"){
			if(!empty($_POST['ttd'])){
				$ttd=", ttd='$_POST[ttd]', nama_dekan='$_POST[nama_dekan]'";
			}
			$sql="UPDATE skpi SET tgl_lulus='$_POST[tgl_lulus]', nomor_skpi='$_POST[nomor_skpi]' $ttd WHERE nim='$_POST[nim]'";
			mysqli_query($koneksi_skpi, $sql) or die(mysqli_error($koneksi_skpi));
		}

		if($_POST['aksi']=="edit"){
			$sql="UPDATE nomor_skpi SET nomor_skpi='$_POST[nomor_skpi]' WHERE nim='$_POST[nim]'";
			
			if(!mysqli_query($koneksi_skpi, $sql)){
				echo "Nomor SKPI gagal disimpan ! $sql";
			}
			else{
				if(!empty($_POST['ttd'])){
					$sql="UPDATE skpi SET ttd='$_POST[ttd]' WHERE nim='$_POST[nim]'";
					mysqli_query($koneksi_skpi, $sql) or die(mysqli_error($koneksi_skpi));
				}
			}
		}

		if($_POST['aksi']=="cari"){
			$_POST['nim']=str_replace("_", "-", $_POST['nim']);
			$data=mysqli_fetch_assoc(mysqli_query($koneksi_skpi, "SELECT * FROM skpi WHERE nim='$_POST[nim]'"));

			$fak=mysqli_fetch_assoc(mysqli_query($koneksi_sikadu, "SELECT a.nim, c.singkat_jur, a.no_ijazah FROM mhs a inner join prodi b on a.kode_prodi=b.kode_prodi inner join jurusan c on b.kode_jurusan=c.kode_jurusan WHERE a.nim='$_POST[nim]'"));
			$data['no_ijazah']=$fak['no_ijazah'];

			if($data['nomor_skpi']==""){
				$urut="xxx";
				$data=mysqli_fetch_assoc(mysqli_query($koneksi_skpi, "SELECT nim, nama, prodi FROM skpi WHERE nim='$_POST[nim]'"));

				$hasil=strpos($fak['no_ijazah'], "/");
				$hasil1=substr($fak['no_ijazah'], $hasil+1);
				$hasil2=strpos($hasil1, "/");
				$hasil2=substr($hasil1, $hasil2+1);

				$data['nomor_skpi']="SKPI/$fak[singkat_jur]/$hasil2";
			}
			
			if($data['ttd']==0 || empty($data['ttd']) || $data['ttd']=="" || empty($data['nama_dekan'])){
				$dek=mysqli_fetch_assoc(mysqli_query($koneksi_sikadu, "SELECT b.id_pegawai, b.nama_peg FROM prodi a INNER JOIN pegawai b ON a.dekan=b.id_pegawai INNER JOIN mhs c ON a.kode_prodi=c.kode_prodi WHERE c.nim='$_POST[nim]'"));

				$data['ttd']=$dek['id_pegawai'];
				$data['nama_dekan']=$dek['nama_peg'];
			}
			$data['aksi']="tambah";
			echo json_encode($data);
		}

		if($_POST['aksi']=="dekan"){
			$dek=mysqli_fetch_assoc(mysqli_query($koneksi_sikadu, "SELECT nama_peg FROM pegawai WHERE id_pegawai='$_POST[id_pegawai]'"));
			echo $dek['nama_peg'];
		}
	}
}
?>