<div style="background-color: white; color: black; width:21cm; padding:10px;" align="center" id="konten">
	<?
	if($nim!=""){
		$g=mysqli_fetch_assoc(mysqli_query($koneksi_simpeg, "SELECT * FROM global"));
		$c=mysqli_fetch_assoc(mysqli_query($koneksi_skpi, "SELECT * FROM skpi WHERE nim='$nim'"));
		if(empty($c['nomor_skpi'])){
			echo "<p>Nomor SKPI belum diterbitkan</p>";
		}
		else{
			$sql="SELECT a.nim, a.nama, a.tmp_lahir, a.tgl_lahir, a.texttgl_lahir, a.jenjang, b.nama_prodi, c.nama_jurusan, a.ta, a.tgl_munaqosah, a.no_ijazah, a.no_pin, b.gelar, b.gelar_singkat, b.sk_pendirian, b.sk_ban, b.eng_prodi, c.eng_jurusan, a.kode_prodi, b.eng_gelar, a.tgl_lulus, b.dekan FROM mhs a inner join prodi b on a.kode_prodi=b.kode_prodi inner join jurusan c on b.kode_jurusan=c.kode_jurusan WHERE a.nim='$nim'";
			$qm=mysqli_query($koneksi_sikadu, $sql) or die(mysqli_query($koneksi_sikadu).$sql);
			$mhs=mysqli_fetch_assoc($qm);
			// $margin=$url[2]=="a4" ? 2.9 : 5;
			$margin=2.9;

			$qcek="SELECT a.nim, COUNT(a.id_prestasi) as jumlah FROM prestasi a INNER JOIN prestasi_translate b ON a.id_prestasi=b.id_prestasi WHERE a.nim='$nim' AND b.jenis='$url[3]' AND b.status_review='S' GROUP BY a.nim";
			$cek=mysqli_fetch_assoc(mysqli_query($koneksi_skpi, $qcek));
			if(empty($cek['jumlah'])) echo "<div class='alert alert-info' style='border:solid thin black; border-radius:3px; background-color:red; color:yellow; padding:20px;'><i class='fa fa-exclamation-triangle'></i> Prestasi masih kosong.<br><button type='button' onclick='window.close();'>Tutup</button></div>";
			else include "isi_skpi_$url[3].php";
		}
	}
	?>
</div>
<!-- <div id="editor"></div>
<button id="btn">Generate PDF</button> -->

<script src="https://code.jquery.com/jquery-1.12.3.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/0.9.0rc1/jspdf.min.js"></script>

<script type="text/javascript">
	$(document).ready(function() {
		var doc = new jsPDF();
		var specialElementHandlers = {
			'#editor': function (element, renderer) {
				return true;
			}
		};
		$('#btn').click(function () {
			doc.fromHTML($('#konten').html(), 15, 15, {
				'width': 170,
				'elementHandlers': specialElementHandlers
			});
			doc.save('SKPI.pdf');
		});
	});
</script>

<style type="text/css">
	@media screen {
		body {
			margin: auto;
			color: #fff;
			background-color: #d4d4d4;
		}

	}
	/* print styles */
	@media print {
		body {
			margin: 0;
			color: #000;
			background-color: #fff;
		}

	}

	.tabel {
		border-collapse: collapse;
		margin-top: 10px;
		margin-bottom: 10px;
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
		border-collapse: collapse; 
	}

	.tabel td.judul_kiri {
		border: black 1px solid;
		border-right: none;
	}

	.tabel td.judul_kanan {
		border: black 1px solid;
		border-left: none;
	}

	.tabel td.subjudul {
		padding-top: 5px;
		border: black 1px solid;
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