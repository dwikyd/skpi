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

if($_POST['username']==""){
	echo "Username atau NIM harus diisi !";
}
else if($_POST['password']==""){
	echo "Password harus diisi !";
}
else{
	$sql="SELECT a.nim, a.nama, a.kode_prodi, b.nama_prodi, a.password FROM mhs a inner join prodi b on a.kode_prodi=b.kode_prodi WHERE a.nim='$_POST[username]'";

	$d=mysqli_fetch_assoc(mysqli_query($koneksi_sikadu, $sql));
	if(isset($d['nim'])){
		$pass1=$d['password'];
		$pass2=$_POST['password'];
		if($pass1==$pass2 || $pass1==md5($pass2)){
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
			$login=false;
			echo "Password tidak valid !";
		}
	}
	else{
		$sql="SELECT id_pegawai, nama, password, id_unit FROM pegawai WHERE username='$_POST[username]'";
		$d=mysqli_fetch_assoc(mysqli_query($koneksi_simpeg, $sql));

		if(isset($d['id_pegawai'])){
			if($d['password']==$_POST['password'] || $d['password']==md5($_POST['password'])){
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
			else{
				$login=false;
				echo "Password tidak valid !";	
			}
		}
		else{
			$login=false;
			echo "Username tidak terdaftar !";
		}
	}
// echo $_SESSION['nama_lengkap'];

	if($login){
		// $glob=mysqli_fetch_assoc(mysqli_query($koneksi_simpeg, "SELECT * FROM global"));
		// $_SESSION['namapt']=$glob['nama_kantor'];
		// $_SESSION['singkatpt']=$glob['singkat_kantor'];
		// $_SESSION['alamatpt']=$glob['alamat_kantor'];

		$glob=mysqli_fetch_assoc(mysqli_query($koneksi_sikadu, "SELECT * FROM global"));
		$_SESSION['ta']=$glob['ta'];
		$_SESSION['smt']=$glob['smt'];
		// $_SESSION['server']=$glob['url_classroom'];
		$_SESSION['pilihperiode']=$_SESSION['ta'].$_SESSION['smt'];
	}
	else{
	// echo "<meta http-equiv=refresh content=0;url=\"index.html\">";
	}
}
?>