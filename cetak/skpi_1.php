<?
session_start();
ob_start();
if($nim!=""){
	$c=mysqli_fetch_assoc(mysqli_query($koneksi_skpi, "SELECT a.* FROM nomor_skpi a inner join skpi b on a.nim=b.nim WHERE a.nim='$nim'"));
	
	$mhs=mysqli_fetch_assoc(mysqli_query($koneksi_sikadu, "SELECT a.nim, a.nama, a.tmp_lahir, a.tgl_lahir, a.texttgl_lahir, a.jenjang, b.nama_prodi, c.nama_jurusan, a.ta, a.tgl_munaqosah, a.no_ijazah, a.no_pin, b.gelar, b.gelar_singkat, b.sk_pendirian, b.sk_ban, b.eng_prodi, c.eng_jurusan, a.kode_prodi, b.eng_gelar, a.tgl_lulus, b.dekan FROM mhs a inner join prodi b on a.kode_prodi=b.kode_prodi inner join jurusan c on b.kode_jurusan=c.kode_jurusan WHERE a.nim='$nim'"));
}
?>
<page backleft="10mm" backtop="15mm" backright="25mm" backbottom="10mm" backimg="../assets/img/skpi.jpg" style="font-size: 12pt;">
	<table style="width:110%;">
		<tr style="valign:top">
			<td style="width:100%; text-align: center;">
				<img src="../assets/img/logo.png" style='width:80px; margin:5px'>
				<br>
				<b>INSTITUT AGAMA ISLAM NEGERI KUDUS
					<br>SURAT KETERANGAN PENDAMPING IJAZAH
					<br><i>Diploma Supplement</i>
				</b>
				<br>No: <?= $c['nomor_skpi'] ?>
			</td>
		</tr>
	</table>
	
	<?
	if($mhs['jenjang']=="S1") $level=6;
	if($mhs['jenjang']=="S2") $level=8;
	if($mhs['jenjang']=="S3") $level=9;
	?>
	<table class="tabel">
		<tr class="row-a">
			<td width="25px" class="subjudul"><b>01</b></td>
			<td class="subjudul">DESKRIPSI SURAT KETERANGAN PENDAMPING IJAZAH (SKPI)<br><i>Diploma Supplement Description</i></td>
		</tr>
		<tr>
			<td></td>
			<td style="text-align: justify;">
				Surat Keterangan Pendamping Ijazah (SKPI) ini mengacu pada Kerangka Kualifikasi Nasional Indonesia (KKNI) dan Standar Nasional Pendidikan Tinggi (SNPT) serta Peraturan Menteri Agama Republik Indonesia Nomor 17 tahun 2020 tentang Ijazah, Sertifikat Kompetensi dan Sertifikat Profesi. Tujuan dari SKPI ini adalah menjadi dokumen yang menyatakan kemampuan kerja, penguasaan pengetahuan, dan sikap/moral pemegangnya.	
			</td>
		</tr>
		<tr>
			<td></td>
			<td style="text-align: justify;">
				<i>This Diploma Supplement (SKPI) refers to the Indonesian National Qualifications Framework (KKNI) and the National Higher Education Standards (SNPT) as well as Regulation of the Minister of Religious Affairs of the Republic of Indonesia Number 17 Year 2020 concerning Diploma, Academic Transcript, and Profession Supplementary Document. The purpose of this SKPI is to become a document that states working ability, mastery of knowledge, and the attitude/moral of the holder.</i>
			</td>
		</tr>
		<tr class="row-a">
			<td class="subjudul">02</td>
			<td class="subjudul">INFORMASI TENTANG IDENTITAS DIRI PEMEGANG SKPI<br><i>Information Identifying The Holder of Diploma Supplement</i></td>
		</tr>
		<tr>
			<td></td>
			<td>
				<table width="100%" style="border-collapse: collapse; border: none;">
					<tr class="ganjil">
						<td width="48%">
							NAMA LENGKAP
							<br><i>Full Name</i>
						</td>
						<td width="4%">
						</td>
						<td width="48%">
							TAHUN MASUK DAN LULUS
							<br><i>Year of Completion</i>
						</td>
					</tr>
					<tr class="ganjil">
						<td class="ganjil"><?= $mhs['nama'] ?></td>
						<td class="ganjil"></td>
						<td class="ganjil"><?= $mhs['ta'] ?> / <?= substr($mhs['tgl_munaqosah'], 0, 4) ?></td>
					</tr>
					<tr>
						<td>
							TEMPAT DAN TANGGAL LAHIR
							<br><i>Date and Place of Birth</i>
						</td>
						<td>
						</td>
						<td>
							NOMOR SERI IJAZAH DAN NOMOR IJAZAH NASIONAL
							<br><i>Diploma Number</i>
						</td>
					</tr>
					<tr>
						<td><?= $mhs['tmp_lahir'].", ".$mhs['texttgl_lahir'] ?><br><i><?= $mhs['tmp_lahir'].", ". date('d F Y', strtotime($mhs['tgl_lahir'])) ?></i></td>
						<td></td>
						<td><?= $mhs['no_ijazah'] ?><br><?= $mhs['no_pin'] ?></td>
					</tr>
					<tr class="ganjil">
						<td>
							NOMOR INDUK MAHASISWA
							<br><i>Student Identification Number</i>
						</td>
						<td>
						</td>
						<td>
							GELAR DAN SINGKATAN
							<br><i>Name of Qualification</i>
						</td>
					</tr>
					<tr class="ganjil">
						<td><?= $mhs['nim'] ?></td>
						<td></td>
						<td><?= $mhs['gelar'] ?> (<?= $mhs['gelar_singkat'] ?>)<br><i><?= $mhs['eng_gelar'] ?></i></td>
					</tr>
				</table>
			</td>
		</tr>
		<tr class="row-a">
			<td class="subjudul">03</td>
			<td class="subjudul">INFORMASI TENTANG IDENTITAS PENYELENGGARA PROGRAM<br><i>Information Identifying The Awarding Institution</i></td>
		</tr>
		<tr>
			<td></td>
			<td>
				<table style="border-collapse: collapse; border: none; width: 800px;">
					<tr class="ganjil">
						<td width="48%">
							SK PENDIRIAN PERGURUAN TINGGI
							<br><i>Awarding Institution's License</i>
						</td>
						<td width="4%">
						</td>
						<td width="48%">
							PERSYARATAN PENERIMAAN
							<br><i>Entry Requirements</i>
						</td>
					</tr>
					<tr class="ganjil">
						<!-- <td class="ganjil">Keputusan Presiden Nomor 11 Tahun 1997 Tanggal 21 Maret 1997, dan  Peraturan Presiden Republik Indonesia Nomor 27 Tahun 2018 Tanggal 5 April 2018<br><i>Presidential Decree (Keppres) Number 11 Year 1997, dated March 21, 1997  and Presidential Regulation (Perpres) Number 27 Year 2018, dated April 5, 2018</i></td>
						<td class="ganjil"></td>
						<td class="ganjil"><?= $mhs['jenjang']=="S2" ? "Lulus S1<br><i>Graduate from bachelor</i>" : "Lulus SMA/MA/SMK Sederajat<br><i>Graduate from high school or similar  level of education</i>" ?></td> -->
					</tr>
					<!-- <tr>
						<td>
							SK AKREDITASI PROGRAM STUDI
							<br><i>The Accreditation of Major</i>
						</td>
						<td>
						</td>
						<td>
							<br><i></i>
						</td>
					</tr>
					<tr>
						<td><?= $mhs['sk_ban'] ?></td>
						<td></td>
						<td></td>
					</tr>

					<tr class="ganjil">
						<td>
							NAMA PERGURUAN TINGGI
							<br><i>Awarding Institution</i>
						</td>
						<td>
						</td>
						<td>
							BAHASA PENGANTAR KULIAH
							<br><i>Language of Institution</i>
						</td>
					</tr>
					<tr class="ganjil">
						<td class="ganjil">Institut Agama Islam Negeri Kudus<br><i>Kudus State Institute of Islamic Studies</i></td>
						<td class="ganjil"></td>
						<td class="ganjil">Indonesia<br><i>Indonesian</i></td>
					</tr>
					<tr>
						<td>
							FAKULTAS
							<br><i>Faculty</i>
						</td>
						<td>
						</td>
						<td>
							PROGRAM STUDI
							<br><i>Major</i>
						</td>
					</tr>
					<tr>
						<td><?= $mhs['nama_jurusan'] ?><br><i><?= $mhs['eng_jurusan'] ?></i></td>
						<td></td>
						<td><?= $mhs['nama_prodi'] ?><br><i><?= $mhs['eng_prodi'] ?></i></td>
					</tr>

					<tr class="ganjil">
						<td>
							SISTEM PEMBELAJARAN
							<br><i>Learning System</i>
						</td>
						<td>
						</td>
						<td>
							SISTEM PENILAIAN
							<br><i>Grading System</i>
						</td>
					</tr>
					<tr class="ganjil">
						<td class="ganjil">KELAS: Reguler<br><i>Class: Regular</i></td>
						<td class="ganjil"></td>
						<td class="ganjil">Skala 0-4; A=4, B=3, C=2, D=1, E=0 <br><i>Scale1-4; A=4, B=3, C=2, D=1, E=0</i></td>
					</tr>
					<tr>
						<td>
							PROGRAM PENDIDIKAN
							<br><i>Academic Programe</i>
						</td>
						<td>
						</td>
						<td>
							LAMA STUDI
							<br><i>Length of Study</i>
						</td>
					</tr>
					<tr>
						<td><?= $mhs['jenjang']=="S2" ? "Strata 2" : "Akademik dan Sarjana (Strata 1)" ?><br><i><?= $mhs['jenjang']=="S2" ? "Magister" : "Academic & Bachelor Degree" ?></i></td>
						<td></td>
						<td><?= $mhs['jenjang']=="S2" ? "4 Semester" : "8 Semester" ?><br><i><?= $mhs['jenjang']=="S2" ? "4 Semester" : "8 Semester" ?></i></td>
					</tr>

					<tr class="ganjil">
						<td>
							JENJANG KUALIFIKASI SESUAI KKNI
							<br><i>Level of Qualification in the National Qualification Framework </i>
						</td>
						<td>
						</td>
						<td>
							JENIS DAN JENJANG PENDIDIKAN LANJUTAN
							<br><i>Access to Further Study</i>
						</td>
					</tr>
					<tr class="ganjil">
						<td class="ganjil">Level <?= $level ?><br><i>Level <?= $level ?></i></td>
						<td class="ganjil"></td>
						<td class="ganjil">Program Magister dan Doktoral<br><i>Magister & Doctoral Program</i></td>
					</tr> -->
				</table>
			</td>
		</tr>
		<!--<tr class="row-a">
			<td class="subjudul">04</td>
			<td class="subjudul">INFORMASI TENTANG KERANGKA KUALIFIKASI NASIONAL INDONESIA (KKNI)<br><i>Information of Indonesian Qualification Framework</i></td>
		</tr> -->
		
	</table>
</page>  

<style type="text/css">
	.tabel {
		border-collapse: collapse;
		width: 100%;
	}
	.tabel th {
		height: 35px;
		padding-left: 5px;
		padding-right: 5px;
		color: #000000;
		text-align: center;
		text-transform:uppercase;
		background-color: #CCCCCC;
	}
	.tabel tr {
		height: 29px;
		vertical-align: top;
	}
	.tabel td {
		padding: 5px;
	}
	.tabel td.subjudul {
		padding-top: 15px;
	}
	.tabel tr.row-a {
		font-weight: bold;
		vertical-align: top;
	}
	.tabel tr.ganjil {
		background-color: #e2f7c8;
		border-color: #e2f7c8;
	}
</style>

<?php
$content = ob_get_clean();

require_once('../html2pdf/html2pdf.class.php');
try
{
	$html2pdf = new HTML2PDF('P', 'A4', 'fr', true, 'UTF-8', 0);
	$html2pdf->pdf->SetDisplayMode('fullpage');
	$html2pdf->writeHTML($content);
	$namafile="SKPI.pdf";
	ob_clean();
	$html2pdf->Output($namafile,"I"); 
}
catch(HTML2PDF_exception $e) {
	echo $e;
	exit;
}
?>