<?
session_start();
include "../config/koneksi.php";
if(isset($_SESSION['id_pegawai'])) $_SESSION['id_pegawai'];
if(isset($_SESSION['hak_akses'])) $_SESSION['hak_akses'];
if(isset($_SESSION['kode_peg'])) $_SESSION['kode_peg'];
if(isset($_SESSION['nim'])) $_SESSION['nim'];
if(isset($_SESSION['nama_lengkap'])) $_SESSION['nama_lengkap'];
if(isset($_SESSION['kode_prodi'])) $_SESSION['kode_prodi'];
if(isset($_SESSION['kode_jurusan'])) $_SESSION['kode_jurusan'];
if(isset($_SESSION['hak_akses_skpi'])) $_SESSION['hak_akses_skpi'];
if(isset($_SESSION['nama_prodi'])) $_SESSION['nama_prodi'];
$login=false;
$sql="SELECT a.nim, a.nama, a.kode_prodi, b.nama_prodi, a.password FROM mhs a inner join prodi b on a.kode_prodi=b.kode_prodi WHERE a.token='$url[1]' AND a.nim='$url[2]'";

$d=mysqli_fetch_assoc(mysqli_query($koneksi_sikadu, $sql));
if(!empty($d['nim'])){
	$_SESSION['nim']			=$d['nim'];
	$_SESSION['hak_akses']		="mahasiswa";
	$_SESSION['hak_akses_skpi'] ="MHS";
	$_SESSION['nama_lengkap']	=$d['nama'];
	$_SESSION['kode_prodi']		=$d['kode_prodi'];
	$_SESSION['prodi']			=$d['nama_prodi'];
	$login=true;

	$cek=mysqli_fetch_assoc(mysqli_query($koneksi_skpi, "SELECT nim FROM skpi WHERE nim='$_SESSION[nim]'"));
	if(empty($cek['nim'])){
		$sql="INSERT INTO skpi SET nim='$_SESSION[nim]', nama='".mysqli_escape_string($koneksi_skpi, $_SESSION['nama_lengkap'])."', kode_prodi='$_SESSION[kode_prodi]', prodi='$_SESSION[prodi]', upload_by='".mysqli_escape_string($koneksi_skpi, $_SESSION['nama_lengkap'])."', update_by='".mysqli_escape_string($koneksi_skpi, $_SESSION['nama_lengkap'])."'";
		mysqli_query($koneksi_skpi, $sql);
	}
}
else{
	$sql="SELECT id_pegawai, nama, password, id_unit FROM pegawai WHERE token='$url[1]' AND id_pegawai='$url[2]'";
	$d=mysqli_fetch_assoc(mysqli_query($koneksi_simpeg, $sql));

	if(!empty($d['id_pegawai'])){
		$_SESSION['id_pegawai']		=$d['id_pegawai'];
		$_SESSION['hak_akses']		="petugas";
		$_SESSION['nama_lengkap']	=str_replace(",", "", $d['nama']);

		$sql="SELECT * FROM hak_akses_sikadu WHERE id_pegawai='$d[id_pegawai]' AND id_unit='$d[id_unit]'";

		$_SESSION['hak_akses_skpi'] = "";
		$_SESSION['kode_prodi'] 	= "";
		$_SESSION['admin_skpi'] 	= "N";

		$v=mysqli_fetch_assoc(mysqli_query($koneksi_sikadu, $sql));
		if(isset($v['id_pegawai'])){
			$_SESSION['hak_akses_skpi'] = $v['hak_akses'];
			$_SESSION['kode_prodi'] 	= "'".str_replace("," , "','", $v['kode_prodi'])."'";
			$_SESSION['admin_skpi'] 	=strpos($v['hak_akses'], 'SPV') !== false ? "Y" : "N";
		}
		else{
			$sql="SELECT id_pegawai, kode_peg FROM pegawai WHERE id_pegawai='$d[id_pegawai]'";
			$d=mysqli_fetch_assoc(mysqli_query($koneksi_sikadu, $sql));
			$_SESSION['kode_peg']	=$d['kode_peg'];

			$sql="SELECT dekan, kode_jurusan FROM prodi WHERE dekan='$_SESSION[id_pegawai]'";
			$d=mysqli_fetch_assoc(mysqli_query($koneksi_sikadu, $sql));
			if(isset($d['dekan'])){
				$_SESSION['dekan']=$d['dekan']=="" ? "N" : "Y";
				$_SESSION['kode_jurusan']=$d['kode_jurusan'];
				$_SESSION['hak_akses_skpi'].=",DKN";
			}

			$sql="SELECT wadek_1, kode_jurusan FROM prodi WHERE wadek_1='$_SESSION[id_pegawai]'";
			$d=mysqli_fetch_assoc(mysqli_query($koneksi_sikadu, $sql));
			if(isset($d['wadek_1'])){
				$_SESSION['wadek_1']=$d['wadek_1']=="" ? "N" : "Y";
				$_SESSION['kode_jurusan']=$d['kode_jurusan'];
				$_SESSION['hak_akses_skpi'].=",WD1";
			}

			$sql="SELECT kode_prodi FROM prodi WHERE kaprodi='$_SESSION[id_pegawai]' OR sekprodi='$_SESSION[id_pegawai]'";
			$d=mysqli_fetch_assoc(mysqli_query($koneksi_sikadu, $sql));
			if(isset($d['kode_prodi'])){
				$_SESSION['kaprodi']=$d['kode_prodi']=="" ? "N" : "Y";
				$_SESSION['kode_prodi']=$d['kode_prodi'];
				$_SESSION['hak_akses_skpi'].=",KPD";
			}

			$sql="SELECT id_pegawai FROM penerjemah WHERE id_pegawai='$_SESSION[id_pegawai]' AND aktif='Y'";
			$d=mysqli_fetch_assoc(mysqli_query($koneksi_skpi, $sql));
			if(isset($d['id_pegawai'])){
				$_SESSION['hak_akses_skpi'].=",TJM";
				$sql="SELECT kode_prodi FROM prodi ORDER BY kode_prodi";
				$prd=mysqli_query($koneksi_sikadu, $sql);

				$_SESSION['kode_prodi']="";
				while($pd=mysqli_fetch_assoc($prd)){
					if($_SESSION['kode_prodi']!="") $_SESSION['kode_prodi'].=",";
					$_SESSION['kode_prodi'].="'".$pd['kode_prodi']."'";
				}
			}

			$sql="SELECT id_pegawai FROM reviewer WHERE id_pegawai='$_SESSION[id_pegawai]' AND aktif='Y'";
			$d=mysqli_fetch_assoc(mysqli_query($koneksi_skpi, $sql));
			if(isset($d['id_pegawai'])){
				$_SESSION['hak_akses_skpi'].=",RVR";
				$sql="SELECT kode_prodi FROM prodi ORDER BY kode_prodi";
				$prd=mysqli_query($koneksi_sikadu, $sql);

				$_SESSION['kode_prodi']="";
				while($pd=mysqli_fetch_assoc($prd)){
					if($_SESSION['kode_prodi']!="") $_SESSION['kode_prodi'].=",";
					$_SESSION['kode_prodi'].="'".$pd['kode_prodi']."'";
				}
			}


			if(isset($_SESSION['kode_jurusan'])){
				if($_SESSION['kode_jurusan']!=""){
					$sql="SELECT kode_prodi FROM prodi WHERE kode_jurusan='$_SESSION[kode_jurusan]' ORDER BY kode_prodi";
					$prd=mysqli_query($koneksi_sikadu, $sql);

					$_SESSION['kode_prodi']="";
					while($pd=mysqli_fetch_assoc($prd)){
						if($_SESSION['kode_prodi']!="") $_SESSION['kode_prodi'].=",";
						$_SESSION['kode_prodi'].="'".$pd['kode_prodi']."'";
					}
				}
			}
		}

		$login=true;
	}
}
echo "<meta http-equiv=refresh content=0;url=\"index.html\">";
?>