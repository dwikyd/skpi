<?
if($mhs['jenjang']=="S1") $level=6;
if($mhs['jenjang']=="S2") $level=8;
if($mhs['jenjang']=="S3") $level=9;
?>
<p>Surat Keterangan Pendamping Ijazah (SKPI) mengacu pada Kerangka Kualifikasi Nasional Indonesia (KKNI) dan Konvensi UNESCO tentang pengakuan studi, ijazah, dan gelar pendidikan tinggi. Tujuan SKPI ini adalah menjadi dokumen yang menyatakan kemampuan kerja, penguasaan pengetahuan, dan sikap/moral pemegangnya.</p>

<p><i>This Diploma Supplement refers to the Indonesian Qualification Framework and UNESCO Convention on the Recognition of Studies, Diplomas and Degrees in Higher Education. The purpose of the supplement is to provide a description of the nature, level, context and status of the studies that were pursued and successfully completed by the individual named on the original qualification to which this supplement is appended.</i></p>


<table class="tabel">
	<tr class="row-a">
		<td width="35px" class="judul_kiri"><b>01</b></td>
		<td class="judul_kanan" colspan="2">INFORMASI TENTANG IDENTITAS DIRI PEMEGANG SKPI<br><i>Information Identifying the Holder of Diploma Supplement</i>
		</td>
	</tr>
	<tr>
		<td class="subjudul">1.1</td>
		<td class="subjudul" width="250px">
			Nama Lengkap
			<br><i>Full Name</i>
		</td>
		<td class="subjudul"><?= $mhs['nama'] ?></td>
	</tr>
	<tr>
		<td class="subjudul">1.2</td>
		<td class="subjudul">
			Tempat Dan Tanggal Lahir
			<br><i>Date and Place of Birth</i>
		</td>
		<td class="subjudul"><?= $mhs['tmp_lahir'].", ".$mhs['texttgl_lahir'] ?><br><i><?= $mhs['tmp_lahir'].", ". date('d F Y', strtotime($mhs['tgl_lahir'])) ?></i></td>
	</tr>
	<tr>
		<td class="subjudul">1.3</td>
		<td class="subjudul">
			Nomor Induk Mahasiswa
			<br><i>Student Identification Number</i>
		</td>
		<td class="subjudul"><?= $mhs['nim'] ?></td>
	</tr>
	<tr>
		<td class="subjudul">1.4</td>
		<td class="subjudul">
			Tahun Lulus
			<br><i>Year of Completion</i>
		</td>
		<td class="subjudul"><?= substr($mhs['tgl_lulus'], 0, 4) ?></td>
	</tr>
	<tr>
		<td class="subjudul">1.5</td>
		<td class="subjudul">
			Nomor Ijazah
			<br><i>Diploma Number</i>
		</td>
		<td class="subjudul"><?= $mhs['no_ijazah'] ?><br><?= $mhs['no_pin'] ?></td>
	</tr>
	<tr>
		<td class="subjudul">1.6</td>
		<td class="subjudul">
			Gelar
			<br><i>Name of Qualification</i>
		</td>
		<td class="subjudul"><?= $mhs['gelar'] ?> (<?= $mhs['gelar_singkat'] ?>)<br><i><?= $mhs['eng_gelar'] ?></i></td>
	</tr>
</table>

<table class="tabel">
	<tr class="row-a">
		<td width="35px" class="judul_kiri"><b>02</b></td>
		<td class="judul_kanan" colspan="2">INFORMASI TENTANG IDENTITAS PENYELENGGARA PROGRAM<br><i>Information Identifying the Awarding Institution</i>
		</td>
	</tr>
	<tr>
		<td class="subjudul">2.1</td>
		<td class="subjudul" width="250px">
			SK Pendirian Perguruan Tinggi
			<br><i>Awarding Institution’s License</i>
		</td>
		<td class="subjudul"><?= $g['sk_pendirian'] ?></td>
	</tr>
	<tr>
		<td class="subjudul">2.2</td>
		<td class="subjudul" width="250px">
			Nama Perguruan Tinggi
			<br><i>Awarding Institution</i>
		</td>
		<td class="subjudul"><?= $g['nama_kantor'] ?><br><i><?= $g['eng_kantor'] ?></i></td>
	</tr>
	<tr>
		<td class="subjudul">2.3</td>
		<td class="subjudul" width="250px">
			Program Studi
			<br><i>Major</i>
		</td>
		<td class="subjudul"><?= $mhs['nama_prodi'] ?><br><i><?= $mhs['eng_prodi'] ?></i></td>
	</tr>
	<tr>
		<td class="subjudul">2.4</td>
		<td class="subjudul" width="250px">
			Jenis & Jenjang Pendidikan
			<br><i>Type & Level of Education</i>
		</td>
		<td class="subjudul">
			<? 
			if($mhs['jenjang']=="S1") echo "Akademik dan Sarjana (Strata 1)<br><i>Academic & Bachelor Degree</i>";
			if($mhs['jenjang']=="S2") echo "Magister (Strata 2)<br><i>Magister</i>";
			?>
		</td>
	</tr>
	<tr>
		<td class="subjudul">2.5</td>
		<td class="subjudul" width="250px">
			Jenjang Kualifikasi Sesuai KKNI
			<br><i>Level of Qualification in the Indonesian Qualification Framework</i>
		</td>
		<td class="subjudul"><?= $level ?></td>
	</tr>
	<tr>
		<td class="subjudul">2.6</td>
		<td class="subjudul" width="250px">
			Persyaratan Penerimaan
			<br><i>Entry Requirements</i>
		</td>
		<td class="subjudul">
			<?
			if($mhs['jenjang']=="S1") echo "Lulus Pendidikan Menengah Atas/Sederajat<br><i>Graduated from High School or Equal Level of Education</i>";
			if($mhs['jenjang']=="S2") echo "Lulus Sarjana<br><i>Graduated from Bachelor Degree</i>";
			?>
		</td>
	</tr>
	<tr>
		<td class="subjudul">2.7</td>
		<td class="subjudul" width="250px">
			Bahasa Pengantar Kuliah
			<br><i>Language of Instruction</i>
		</td>
		<td class="subjudul">Indonesia<br><i>Indonesian</i></td>
	</tr>
	<tr>
		<td class="subjudul">2.8</td>
		<td class="subjudul" width="250px">
			Sistem Penilaian
			<br><i>Grading System</i>
		</td>
		<td class="subjudul">Skala 1-4; A=4, B=3, C=2, D=1<br><i>Scale 1-4; A=4, B=3, C=2, D=1</i></td>
	</tr>
	<tr>
		<td class="subjudul">2.9</td>
		<td class="subjudul" width="250px">
			Lama Studi Reguler
			<br><i>Regular Length of Study</i>
		</td>
		<td class="subjudul">
			<?
			if($mhs['jenjang']=="S1") echo "8 Semester";
			if($mhs['jenjang']=="S2") echo "4 Semester";
			?>
		</td>
	</tr>
	<tr>
		<td class="subjudul">2.10</td>
		<td class="subjudul" width="250px">
			Jenis dan Jenjang Pendidikan Lanjutan
			<br><i>Access to Further Study</i>
		</td>
		<td class="subjudul">
			<?
			if($mhs['jenjang']=="S1") echo "Program Magister<br><i>Master Program</i>";
			if($mhs['jenjang']=="S2") echo "Program Doktoral<br><i>Doctoral Program</i>";
			?>
		</td>
	</tr>
	<tr>
		<td class="subjudul">2.11</td>
		<td class="subjudul" width="250px">
			Status Profesi (bila ada)
			<br><i>Professional Status ( if applicable)</i>
		</td>
		<td class="subjudul"></td>
	</tr>
</table>

<table class="tabel">
	<tr class="row-a">
		<td width="35px" class="judul_kiri"><b>03</b></td>
		<td class="judul_kanan" colspan="3">03.	INFORMASI TENTANG KUALIFIKASI DAN HASIL YANG DICAPAI<br><i>Information Identifying the Qualification and Outcomes Obtained</i>
		</td>
	</tr>
	<tr>
		<td width="35px" class="subjudul">A</td>
		<td class="subjudul" width="340px">
			CAPAIAN PEMBELAJARAN
		</td>
		<td width="35px" class="subjudul"><i>A</i></td>
		<td class="subjudul">
			<i>LEARNING OUTCOMES</i>
		</td>
	</tr>
	<?
	$sql=mysqli_query($koneksi_skpi, "SELECT a.id_cp, a.in_cp, a.eng_cp, a.id_bidang, b.nama_bidang, b.eng_bidang FROM cp_prodi a inner join cp_bidang b on a.id_bidang=b.id_bidang WHERE a.kode_prodi='$mhs[kode_prodi]' ORDER BY a.id_bidang, a.order_cp, a.id_cp");
	$no=0;
	$bidang="";
	while($d=mysqli_fetch_assoc($sql)){
		if($bidang!=$d['id_bidang']){
			// $no=0;
			$bidang=$d['id_bidang'];
			echo "<tr><td colspan=2 class='subjudul' align='center'>$d[nama_bidang]</td><td colspan=2 class='subjudul' align='center'><i>$d[eng_bidang]</i>)</td></tr>";
		}
		$no++;
		echo "<tr><td class='subjudul'>A.$no.</td><td class='subjudul' style='text-align:justify;'>".($d['in_cp'])."</td><td class='subjudul'>A.$no.</td><td class='subjudul' style='text-align:justify;'><i>".($d['eng_cp'])."</i></td></tr>";
	}
	?>

	<tr>
		<td width="35px" class="subjudul">B</td>
		<td class="subjudul" width="340px">
			AKTIVITAS, PRESTASI, DAN PENGHARGAAN
		</td>
		<td width="35px" class="subjudul"><i>B</i></td>
		<td class="subjudul">
			<i>ACTIVITIES, ACHIEVEMENT, AND AWARDS</i>
		</td>
	</tr>
	<?
	$sql=mysqli_query($koneksi_skpi, "SELECT in_prestasi, eng_prestasi FROM prestasi WHERE nim='$mhs[nim]' AND sts_skpi='Y' AND sts_translate='Y' ORDER BY id_jns_dokumen, id_bukti_dok, id_prestasi");
	$no=0;
	while($d=mysqli_fetch_assoc($sql)){
		$no++;
		echo "<tr style='text-align:justify;'><td class='subjudul'>B.$no.</td><td class='subjudul'>".($d['in_prestasi'])."</td><td class='subjudul'>B.$no.</td><td class='subjudul'><i>".($d['eng_prestasi'])."</i></td></tr>";
	}
	?>
</table>
<?
if($mhs['jenjang']=="S1"){
	$jabatan_i="Dekan";
	$jabatan_e="Dean";
}
if($mhs['jenjang']=="S2"){
	$jabatan_i="Direktur";
	$jabatan_e="Director";
}

// $dk=mysqli_fetch_assoc(mysqli_query($koneksi_sikadu, "SELECT id_pegawai, nip, nama_peg from pegawai WHERE kode_peg='$mhs[dekan]'"));
$dek=mysqli_fetch_assoc(mysqli_query($koneksi_simpeg, "SELECT nip, nama, gelar_depan, gelar_belakang from pegawai WHERE id_pegawai='$c[ttd]'"));
$dekan=$dek['gelar_depan']." ".ucwords(strtolower(str_replace(",", "", $dek['nama'])));
$dekan=$dek['gelar_belakang']=="" ? $dekan : $dekan.", ".$dek['gelar_belakang'];
$nip= $dek['nip'];
$nama_jenjang=$mhs['jenjang']=="S1" ? "Fakultas" : "";
?>

<div align="center">
	<?= $g['kota'] ?>, <?= tgl_indo($mhs['tgl_lulus']) ?>
	<br><i><?= date('F d Y', strtotime($mhs['tgl_lulus'])) ?></i>
	<br><br><?= $jabatan_i." ".$nama_jenjang." ".$mhs['nama_jurusan'] ?>
	<br><i><?= $jabatan_e." of ".$mhs['eng_jurusan'] ?></i>
	<br><br><br><br><br>
	<?= $c['nama_dekan'] ?>
	<br>NIP : <?= $nip ?>
</div>