<div align="center">
	<img src="../assets/img/<?= $g['logo'] ?>" width="100px;" alt="<?= $g['logo'] ?>"><br>
	<b><?= strtoupper($g['nama_kantor']) ?><br>
		جامع ة بونتيانك ا لسلامية الحكومي ة
	</b>
	<br><br>
	SURAT KETERANGAN PENDAMPING IJAZAH<br>
	شهادة ملحق الدبلو م

	<br>
	<br><b>Nomor / رقم : <?= $c['nomor_skpi'] ?></b>
</div>
<?
if($mhs['jenjang']=="S1") $level=6;
if($mhs['jenjang']=="S2") $level=8;
if($mhs['jenjang']=="S3") $level=9;
?>
<p>Surat Keterangan Pendamping Ijazah (SKPI) mengacu pada Kerangka Kualifikasi Nasional Indonesia (KKNI) dan Konvensi UNESCO tentang pengakuan studi, ijazah, dan gelar pendidikan tinggi. Tujuan SKPI ini adalah menjadi dokumen yang menyatakan kemampuan kerja, penguasaan pengetahuan, dan sikap/moral pemegangnya.</p>

<p>تشير شهادة ملحق الدبلوم الى اطار المؤهلات الوطنيّة الاندونيس يّة ومؤتمر منظّمة ا أ لمم المتّحدة للتربية والعلم والثقافة
)اليونسكو( عن الاعتراف بالدراسات، والشهادة، ودرجة التعليم العالي. والغرض من هذا هو أأن يكون وثيقة توضح قدرات
العمل، و اتقان المعرفة، والمواقف أأو ا أ لخلاق لحاملها.</p>


<table class="tabel">
	<tr class="row-a">
		<td width="35px" class="judul_kiri"><b>01</b></td>
		<td class="judul_kanan" colspan="2">INFORMASI TENTANG IDENTITAS DIRI PEMEGANG SKPI<br>المعلومات عن الهوية الشخصية لحامل شهادة ملحق الدبلو م
		</td>
	</tr>
	<tr>
		<td class="subjudul">1.1</td>
		<td class="subjudul" width="250px">
			Nama Lengkap (ا لسم الكامل)
		</td>
		<td class="subjudul"><?= $mhs['nama'] ?></td>
	</tr>
	<tr>
		<td class="subjudul">1.2</td>
		<td class="subjudul">
			Tempat Dan Tanggal Lahir (مكان وتاريخ الميلا د)
		</td>
		<td class="subjudul"><?= $mhs['tmp_lahir'].", ".$mhs['texttgl_lahir'] ?> (<i><?= $mhs['tmp_lahir'].", ". date('d F Y', strtotime($mhs['tgl_lahir'])) ?></i>)</td>
	</tr>
	<tr>
		<td class="subjudul">1.3</td>
		<td class="subjudul">
			Nomor Induk Mahasiswa (رقم قيد الطالب)
		</td>
		<td class="subjudul"><?= $mhs['nim'] ?></td>
	</tr>
	<tr>
		<td class="subjudul">1.4</td>
		<td class="subjudul">
			Tahun Lulus (س نة التخرج)
		</td>
		<td class="subjudul"><?= substr($mhs['tgl_lulus'], 0, 4) ?></td>
	</tr>
	<tr>
		<td class="subjudul">1.5</td>
		<td class="subjudul">
			Nomor Ijazah (رقم الشهادة)
		</td>
		<td class="subjudul"><?= $mhs['no_ijazah'] ?> / <?= $mhs['no_pin'] ?></td>
	</tr>
	<tr>
		<td class="subjudul">1.6</td>
		<td class="subjudul">
			Gelar (درجة أأكادمية)
		</td>
		<td class="subjudul"><?= $mhs['gelar'] ?> (<?= $mhs['gelar_singkat'] ?>) (<i><?= $mhs['arb_gelar'] ?></i>)</td>
	</tr>
</table>

<table class="tabel">
	<tr class="row-a">
		<td width="35px" class="judul_kiri"><b>02</b></td>
		<td class="judul_kanan" colspan="2">INFORMASI TENTANG IDENTITAS PENYELENGGARA PROGRAM<br>المع لومات عن هوية ادارة البرنام ج
		</td>
	</tr>
	<tr>
		<td class="subjudul">2.1</td>
		<td class="subjudul" width="250px">
			SK Pendirian Perguruan Tinggi (مرسوم انشاء الجامعة)
		</td>
		<td class="subjudul"><?= $g['sk_pendirian'] ?></td>
	</tr>
	<tr>
		<td class="subjudul">2.2</td>
		<td class="subjudul" width="250px">
			Nama Perguruan Tinggi (اسم الجامعة)
		</td>
		<td class="subjudul"><?= $g['nama_kantor'] ?> (<i><?= $g['arb_kantor'] ?></i>)</td>
	</tr>
	<tr>
		<td class="subjudul">2.3</td>
		<td class="subjudul" width="250px">
			Program Studi
			<br>شعبة التعل يم
		</td>
		<td class="subjudul"><?= $mhs['nama_prodi'] ?> (<i><?= $mhs['arb_prodi'] ?></i>)</td>
	</tr>
	<tr>
		<td class="subjudul">2.4</td>
		<td class="subjudul" width="250px">
			Jenis & Jenjang Pendidikan (نوع ومرحلة التعليم)
		</td>
		<td class="subjudul">
			<? 
			if($mhs['jenjang']=="S1") echo "Akademik dan Sarjana (Strata 1) (<i>Academic & Bachelor Degree</i>)";
			if($mhs['jenjang']=="S2") echo "Magister (Strata 2) (<i>Magister</i>)";
			?>
		</td>
	</tr>
	<tr>
		<td class="subjudul">2.5</td>
		<td class="subjudul" width="250px">
			Jenjang Kualifikasi Sesuai KKNI (مس توى المؤهل المناسب باطار
المؤهلات الوطنية الاندونيس ية)
		</td>
		<td class="subjudul"><?= $level ?></td>
	</tr>
	<tr>
		<td class="subjudul">2.6</td>
		<td class="subjudul" width="250px">
			Persyaratan Penerimaan (شروط الاس تلا م)
		</td>
		<td class="subjudul">
			<?
			if($mhs['jenjang']=="S1") echo "Lulus Pendidikan Menengah Atas/Sederajat (تخرّج المدرسة الثانوية أأو ما يعادلها)";
			if($mhs['jenjang']=="S2") echo "Lulus Sarjana (<i>Graduated from Bachelor Degree</i>)";
			?>
		</td>
	</tr>
	<tr>
		<td class="subjudul">2.7</td>
		<td class="subjudul" width="250px">
			Bahasa Pengantar Kuliah (لغة التدريس الجامعي)
		</td>
		<td class="subjudul">Indonesia (اندونيسية)</td>
	</tr>
	<tr>
		<td class="subjudul">2.8</td>
		<td class="subjudul" width="250px">
			Sistem Penilaian (نظام التقييم)
		</td>
		<td class="subjudul">Skala 1-4; A=4, B=3, C=2, D=1 (<i>Scale 1-4; A=4, B=3, C=2, D=1</i>)</td>
	</tr>
	<tr>
		<td class="subjudul">2.9</td>
		<td class="subjudul" width="250px">
			Lama Studi Reguler (مدّة التعليم العاد ي)
		</td>
		<td class="subjudul">
			<?
			if($mhs['jenjang']=="S1") echo "مس تويات";
			if($mhs['jenjang']=="S2") echo "مس تويات";
			?>
		</td>
	</tr>
	<tr>
		<td class="subjudul">2.10</td>
		<td class="subjudul" width="250px">
			Jenis dan Jenjang Pendidikan Lanjutan (ن وع ومرحلة التعليم اللاحقة)
		</td>
		<td class="subjudul">
			<?
			if($mhs['jenjang']=="S1") echo "Program Magister (ماجس تير ودكتور ة)";
			if($mhs['jenjang']=="S2") echo "Program Doktoral (<i>Doctoral Program</i>)";
			?>
		</td>
	</tr>
	<tr>
		<td class="subjudul">2.11</td>
		<td class="subjudul" width="250px">
			Status Profesi (bila ada) (حالة المهنة )إن كانت()
		</td>
		<td class="subjudul"></td>
	</tr>
</table>

<table class="tabel">
	<tr class="row-a">
		<td width="35px" class="judul_kiri"><b>03</b></td>
		<td class="judul_kanan" colspan="3">INFORMASI TENTANG KUALIFIKASI DAN HASIL YANG DICAPAI<br>معلومات حول المؤهلات والنتائج المحققة
		</td>
	</tr>
	<tr>
		<td width="35px" class="subjudul">A</td>
		<td class="subjudul" width="340px">
			CAPAIAN PEMBELAJARAN
		</td>
		<td width="35px" class="subjudul"><i>A</i></td>
		<td class="subjudul">
			نتائج التعلم
		</td>
	</tr>
	<?
	$sql=mysqli_query($koneksi_skpi, "SELECT a.id_cp, a.in_cp, a.arb_cp, a.id_bidang, b.nama_bidang, b.arb_bidang FROM cp_prodi a inner join cp_bidang b on a.id_bidang=b.id_bidang WHERE a.kode_prodi='$mhs[kode_prodi]' ORDER BY a.id_bidang, a.order_cp, a.id_cp");
	$no=0;
	$bidang="";
	while($d=mysqli_fetch_assoc($sql)){
		if($bidang!=$d['id_bidang']){
			// $no=0;
			$bidang=$d['id_bidang'];
			echo "<tr><td colspan=2 class='subjudul' align='center'>$d[nama_bidang]</td><td colspan=2 class='subjudul' align='center'>$d[arb_bidang]</td></tr>";
		}
		$no++;
		echo "<tr><td class='subjudul'>A.$no.</td><td class='subjudul' style='text-align:justify;'>".($d['in_cp'])."</td><td class='subjudul'>A.$no.</td><td class='subjudul' style='text-align:justify;'>".($d['arb_cp'])."</td></tr>";
	}
	?>

	<tr>
		<td width="35px" class="subjudul">B</td>
		<td class="subjudul" width="340px">
			AKTIVITAS, PRESTASI, DAN PENGHARGAAN
		</td>
		<td width="35px" class="subjudul"><i>B</i></td>
		<td class="subjudul">
			الأنشطة والإنجازات والجوائز
		</td>
	</tr>
	<?
	$sql=mysqli_query($koneksi_skpi, "SELECT a.*, b.hasil_translate FROM prestasi a INNER JOIN prestasi_translate b ON a.id_prestasi=b.id_prestasi WHERE a.nim='$mhs[nim]' AND b.status_review='S' AND b.jenis='$url[3]' ORDER BY a.id_jns_dokumen, a.id_bukti_dok, a.id_prestasi");
	$no=0;
	while($d=mysqli_fetch_assoc($sql)){
		$no++;
		echo "<tr style='text-align:justify;'><td class='subjudul'>B.$no.</td><td class='subjudul'>".($d['in_prestasi'])."</td><td class='subjudul'>B.$no.</td><td class='subjudul'>".($d['hasil_translate'])."</td></tr>";
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
	<br><i><?= $jabatan_e." of ".$mhs['arb_jurusan'] ?></i>
	<br><br><br><br><br>
	<?= $c['nama_dekan'] ?>
	<br>NIP : <?= $nip ?>
</div>